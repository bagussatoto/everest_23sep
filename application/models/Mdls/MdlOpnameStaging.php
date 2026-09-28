<?php
// START OF COMPLETE REPEATED LOGIC
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class MdlOpnameStaging
 *
 * Model jembatan (bridge) untuk menyalin data dokumen Stock Opname dari HTTP Session
 * ke tabel Transactional Buffer (so_headers_staging & so_items_staging)
 * dan menerbitkan Event Message ke RabbitMQ Server.
 *
 * Kompatibel penuh dengan PHP 5.6 & CodeIgniter 3.1.8
 */
class MdlOpnameStaging extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('Rabbitmq_publisher');
    }

    /**
     * Merekam dokumen Opname ke staging database dan mengirim event tiket ke RabbitMQ
     *
     * @param int|string $transaksiId  ID baris di tabel transaksi
     * @param string     $nomerNota    Nomor nota transaksi opname
     * @param string     $cCode        Kode sesi transaksi ($_SESSION[$cCode])
     * @return array
     */
    public function pushToQueue($transaksiId, $nomerNota, $cCode)
    {
        if (empty($transaksiId) || empty($cCode)) {
            return array(
                'status'  => 400,
                'message' => 'Parameter transaksiId atau cCode tidak valid.',
            );
        }

        if (!isset($_SESSION[$cCode])) {
            return array(
                'status'  => 404,
                'message' => 'Data sesi transaksi opname tidak ditemukan.',
            );
        }

        $sessionData = $_SESSION[$cCode];
        $main        = isset($sessionData['main']) ? $sessionData['main'] : array();
        $items       = isset($sessionData['items']) ? $sessionData['items'] : array();
        $dataSerial  = isset($sessionData['dataSerial']) ? $sessionData['dataSerial'] : array();

        $cabangId = isset($main['cabang_id']) ? (int)$main['cabang_id'] : (isset($this->session->login['cabang_id']) ? (int)$this->session->login['cabang_id'] : 0);
        $gudangId = isset($main['gudang_id']) ? (int)$main['gudang_id'] : (isset($this->session->login['gudang_id']) ? (int)$this->session->login['gudang_id'] : 0);
        $userId   = isset($this->session->login['id']) ? (int)$this->session->login['id'] : (isset($main['oleh_id']) ? (int)$main['oleh_id'] : 0);
        $jenisTr  = isset($main['jenis']) ? $main['jenis'] : '1119';

        $totalItems = count($items);
        $now        = date('Y-m-d H:i:s');
        $checksum   = md5($transaksiId . '_' . $nomerNota . '_' . $totalItems . '_' . $cabangId . '_' . $gudangId);

        // 1. Idempotency Check & Cleanup baris lama yang belum selesai
        $checkExisting = $this->db->where('transaksi_id', $transaksiId)->get('so_headers_staging');
        if ($checkExisting->num_rows() > 0) {
            $existingRow = $checkExisting->row();
            if ($existingRow->status == 'COMPLETED') {
                return array(
                    'status'    => 200,
                    'message'   => 'Dokumen opname sudah selesai diproses sebelumnya.',
                    'header_id' => $existingRow->id,
                );
            } else {
                // Bersihkan staging lama yang belum selesai untuk pendaftaran ulang bersih
                $this->db->where('header_staging_id', $existingRow->id)->delete('so_items_staging');
                $this->db->where('header_staging_id', $existingRow->id)->delete('so_audit_logs');
                $this->db->where('id', $existingRow->id)->delete('so_headers_staging');
            }
        }

        // 2. Insert ke tabel so_headers_staging
        $headerData = array(
            'transaksi_id'     => $transaksiId,
            'booking_number'   => $cCode,
            'nomer_nota'       => $nomerNota,
            'jenis_transaksi'  => $jenisTr,
            'cabang_id'        => $cabangId,
            'gudang_id'        => $gudangId,
            'total_item'       => $totalItems,
            'status'           => 'QUEUED',
            'retry_count'      => 0,
            'payload_checksum' => $checksum,
            'created_by'       => $userId,
            'approved_by'      => $userId,
            'error_message'    => NULL,
            'created_at'       => $now,
            'updated_at'       => $now,
        );
        $this->db->insert('so_headers_staging', $headerData);
        $headerStagingId = $this->db->insert_id();

        if (empty($headerStagingId)) {
            $err = $this->db->error();
            log_message('error', 'Gagal insert so_headers_staging: ' . (isset($err['message']) ? $err['message'] : 'Unknown'));
            return array(
                'status'  => 500,
                'message' => 'Gagal membuat baris header staging opname.',
            );
        }

        // 3. Insert rincian barang ke tabel so_items_staging dengan chunking per 200 baris
        if (!empty($items)) {
            $itemsBatch = array();
            foreach ($items as $produkId => $iSpec) {
                $qtySistem  = isset($iSpec['stok']) ? (float)$iSpec['stok'] : (isset($iSpec['qty_sistem']) ? (float)$iSpec['qty_sistem'] : (isset($iSpec['produk_ord_stok']) ? (float)$iSpec['produk_ord_stok'] : 0));
                $qtyFisik   = isset($iSpec['qty']) ? (float)$iSpec['qty'] : (isset($iSpec['qty_fisik']) ? (float)$iSpec['qty_fisik'] : (isset($iSpec['produk_ord_jml']) ? (float)$iSpec['produk_ord_jml'] : 0));
                $selisih    = isset($iSpec['qty_selisih']) ? (float)$iSpec['qty_selisih'] : ($qtyFisik - $qtySistem);

                $qtyDebet   = isset($iSpec['qty_debet']) ? (float)$iSpec['qty_debet'] : ($selisih > 0 ? $selisih : 0);
                $qtyKredit  = isset($iSpec['qty_kredit']) ? (float)$iSpec['qty_kredit'] : ($selisih < 0 ? abs($selisih) : 0);

                $hppSatuan  = isset($iSpec['hpp']) ? (float)$iSpec['hpp'] : (isset($iSpec['nilai']) ? (float)$iSpec['nilai'] : (isset($iSpec['produk_nilai']) ? (float)$iSpec['produk_nilai'] : (isset($iSpec['produk_ord_hrg']) ? (float)$iSpec['produk_ord_hrg'] : (isset($iSpec['harga']) ? (float)$iSpec['harga'] : 0))));
                $nilaiTotal = abs($selisih) * $hppSatuan;

                $serialsJson = NULL;
                if (isset($dataSerial[$produkId]) && !empty($dataSerial[$produkId])) {
                    $serialsJson = json_encode($dataSerial[$produkId]);
                } elseif (isset($iSpec['serial_numbers']) && !empty($iSpec['serial_numbers'])) {
                    $serialsJson = json_encode($iSpec['serial_numbers']);
                }

                $itemsBatch[] = array(
                    'header_staging_id'   => $headerStagingId,
                    'transaksi_id'        => $transaksiId,
                    'produk_id'           => $produkId,
                    'produk_kode'         => isset($iSpec['kode']) ? $iSpec['kode'] : (isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : ''),
                    'produk_nama'         => isset($iSpec['nama']) ? $iSpec['nama'] : (isset($iSpec['produk_nama']) ? $iSpec['produk_nama'] : 'Produk #' . $produkId),
                    'satuan'              => isset($iSpec['satuan']) ? $iSpec['satuan'] : 'PCS',
                    'stok_sistem'         => $qtySistem,
                    'qty_fisik'           => $qtyFisik,
                    'qty_selisih'         => $selisih,
                    'qty_debet'           => $qtyDebet,
                    'qty_kredit'          => $qtyKredit,
                    'hpp_satuan'          => $hppSatuan,
                    'total_nilai_selisih' => $nilaiTotal,
                    'serial_numbers_json' => $serialsJson,
                    'chunk_batch'         => 1,
                    'status'              => 'PENDING',
                );
            }

            if (!empty($itemsBatch)) {
                $chunks = array_chunk($itemsBatch, 200);
                $chunkIndex = 1;
                foreach ($chunks as $chunk) {
                    for ($ci = 0; $ci < count($chunk); $ci++) {
                        $chunk[$ci]['chunk_batch'] = $chunkIndex;
                    }
                    $this->db->insert_batch('so_items_staging', $chunk);
                    $chunkIndex++;
                }
            }
        }

        // 4. Catat Jejak Audit Awal (ISO 27001)
        $auditData = array(
            'header_staging_id' => $headerStagingId,
            'transaksi_id'      => $transaksiId,
            'event_type'        => 'SUBMITTED',
            'actor_type'        => 'USER',
            'actor_id'          => $userId,
            'details'           => 'Dokumen Opname nomor ' . $nomerNota . ' berhasil disalin ke staging penampung (' . $totalItems . ' item). Siap diterbitkan ke antrean RabbitMQ.',
            'created_at'        => $now,
        );
        $this->db->insert('so_audit_logs', $auditData);

        // 5. Menerbitkan Event DTO ke RabbitMQ Broker
        $payload = array(
            'transaksi_id'     => (int)$transaksiId,
            'booking_number'   => $cCode,
            'nomer_nota'       => $nomerNota,
            'cabang_id'        => (int)$cabangId,
            'gudang_id'        => (int)$gudangId,
            'jenis_transaksi'  => $jenisTr,
            'total_items'      => (int)$totalItems,
            'payload_checksum' => $checksum,
            'submitted_at'     => $now,
        );

        $publishResult = $this->rabbitmq_publisher->publishOpname($payload);

        if (isset($publishResult['status']) && $publishResult['status'] == 200) {
            log_message('info', 'Stock Opname ' . $nomerNota . ' berhasil dipublikasikan ke antrean RabbitMQ.');
            return array(
                'status'    => 200,
                'message'   => 'Dokumen berhasil dimasukkan ke antrean latar belakang.',
                'header_id' => $headerStagingId,
            );
        } else {
            log_message('error', 'Gagal mempublikasikan Stock Opname ' . $nomerNota . ' ke RabbitMQ: ' . (isset($publishResult['message']) ? $publishResult['message'] : ''));
            return array(
                'status'    => 206, // Staging tersimpan, tapi pesan antrean ditunda
                'message'   => 'Data opname tersimpan di staging, namun gagal menghubungi broker pesan.',
                'header_id' => $headerStagingId,
            );
        }
    }
}
// END OF COMPLETE REPEATED LOGIC
