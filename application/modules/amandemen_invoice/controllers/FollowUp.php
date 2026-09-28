<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Eksekusi simpan amandemen (ACID) dengan pemisahan Notes Client & Catatan Internal Amandemen, serta flag ?saved=1 pasca-reload
 * COMPLIANCE: CI3 Database Transaction (Strict), ISO 9001
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "modules/amandemen_invoice/controllers/Modul_Controller.php";

class FollowUp extends Modul_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('he_angka');
        $this->load->model('CustomCounter');
        $this->load->model('Coms/ComAmandemenInvoice');
    }

    public function validate_and_save($invoice_id) {
// START OF COMPLETE REPEATED LOGIC
        $invoice_id = (int) $invoice_id;
        $description = $this->input->post('description'); // Notes Client (Dicetak di Invoice)
        $catatan_amandemen = $this->input->post('catatan_amandemen'); // Catatan Amandemen Khusus Internal
        $post_items = $this->input->post('items');
        $post_jurnal_custom = $this->input->post('jurnal_custom'); // Custom Manual Journal Override
        $absorb_tax_difference = (int)$this->input->post('absorb_tax_difference');

        if (empty($post_items) || !is_array($post_items)) {
            die('Gagal: Tidak ada item produk yang ditagihkan.');
        }

        // 1. Snapshot lama
        $snapshot = $this->ComAmandemenInvoice->createAuditSnapshot($invoice_id);
        if (!$snapshot) {
            die('Gagal membuat snapshot audit.');
        }

        // Kalkulasi nilai tagihan baru dari POST items (Anti-Minus Balance Guard)
        $calc_total = 0;
        foreach ($post_items as $p_itm) {
            $raw_qty = isset($p_itm['jml']) ? $p_itm['jml'] : 0;
            $raw_hrg = isset($p_itm['harga']) ? $p_itm['harga'] : 0;
            $p_qty = is_string($raw_qty) ? (float)str_replace(',', '.', str_replace('.', '', $raw_qty)) : (float)$raw_qty;
            $p_hrg = is_string($raw_hrg) ? (float)str_replace(',', '.', str_replace('.', '', $raw_hrg)) : (float)$raw_hrg;
            $calc_total += ($p_qty * $p_hrg);
        }
        if ($calc_total <= 0) {
            die('Gagal: Nilai diskon / adjustment tagihan tidak boleh kosong atau bernilai negatif (<= Rp 0).');
        }
        $calc_gt = $calc_total + round($calc_total * 0.11);

        // Lapis 0 Blocker: Cek status pajak Approved DJP (PER-03/PJ/2022) & Opsi Penyerapan Pajak
        $tax_info = $this->ComAmandemenInvoice->checkTaxStatus($invoice_id);
        $absorb_tax = false;
        if ($tax_info['status'] === 'APPROVED_DJP') {
            if ($absorb_tax_difference === 1) {
                // Anti-Underreporting Guard: Kenaikan omzet mutlak wajib buat Faktur Pengganti di DJP
                // Cari nilai Grand Total Tagihan Awal Murni (termasuk PPN) agar perbandingan seimbang (apel-ke-apel)
                $old_tagihan_check = 0;

                // 1. Cek dari transaksi_payment_source (kartu piutang resmi)
                if ($this->db->table_exists('transaksi_payment_source')) {
                    $tps_chk_ids = array($invoice_id);
                    if (isset($snapshot['header']['id_master']) && $snapshot['header']['id_master'] > 0) {
                        $tps_chk_ids[] = (int)$snapshot['header']['id_master'];
                    }
                    $this->db->select('tagihan_asal, tagihan');
                    $this->db->where_in('transaksi_id', $tps_chk_ids);
                    $this->db->order_by('id', 'ASC');
                    $tps_chk = $this->db->get('transaksi_payment_source')->row_array();
                    if ($tps_chk) {
                        if (isset($tps_chk['tagihan_asal']) && (float)$tps_chk['tagihan_asal'] > 0) {
                            $old_tagihan_check = (float)$tps_chk['tagihan_asal'];
                        } elseif (isset($tps_chk['tagihan']) && (float)$tps_chk['tagihan'] > 0) {
                            $old_tagihan_check = (float)$tps_chk['tagihan'];
                        }
                    }
                }

                // 2. Cek dari jurnal_detail awal (Piutang Dagang 1010020010 Debet)
                if ($old_tagihan_check <= 0 && !empty($snapshot['jurnal_detail'])) {
                    foreach ($snapshot['jurnal_detail'] as $sj) {
                        $r_code = isset($sj['rekening']) ? (string)$sj['rekening'] : '';
                        $r_deb  = isset($sj['debet']) ? (float)$sj['debet'] : 0;
                        if ($r_code === '1010020010' && $r_deb > 0) {
                            $old_tagihan_check = $r_deb;
                            break;
                        }
                    }
                }

                // 3. Cek dari snapshot registry main
                if ($old_tagihan_check <= 0 && isset($snapshot['registry']['main'])) {
                    $snap_main = @unserialize(base64_decode($snapshot['registry']['main']));
                    if ($snap_main && is_array($snap_main)) {
                        if (isset($snap_main['piutang_dagang']) && (float)$snap_main['piutang_dagang'] > 0) {
                            $old_tagihan_check = (float)$snap_main['piutang_dagang'];
                        } elseif (isset($snap_main['grand_total']) && (float)$snap_main['grand_total'] > 0) {
                            $old_tagihan_check = (float)$snap_main['grand_total'];
                        }
                    }
                }

                // 4. Fallback dari header transaksi (jika transaksi_nilai adalah DPP murni, tambahkan PPN 11%)
                if ($old_tagihan_check <= 0 && isset($snapshot['header']['transaksi_nilai']) && (float)$snapshot['header']['transaksi_nilai'] > 0) {
                    $raw_h_val = (float)$snapshot['header']['transaksi_nilai'];
                    $old_tagihan_check = $raw_h_val + round($raw_h_val * 0.11);
                }

                if ($calc_gt > ($old_tagihan_check + 0.01)) {
                    die('Gagal: Penyerapan selisih pajak tanpa pembatalan e-Faktur HANYA DIPERBOLEHKAN jika nominal tagihan baru LEBIH KECIL ATAU SAMA dengan tagihan awal (<= Rp ' . number_format($old_tagihan_check, 0, ',', '.') . '). Karena tagihan baru (Rp ' . number_format($calc_gt, 0, ',', '.') . ') mengalami kenaikan, Anda WAJIB menerbitkan Faktur Pajak Pengganti (011) di DJP untuk menghindari pidana kurang bayar PPN.');
                }
                $absorb_tax = true;
            } else {
                die('Gagal: Invoice ini tidak dapat diamandemen karena sudah memiliki Faktur Pajak Resmi yang disetujui DJP (Nomor e-Faktur: ' . $tax_info['efaktur'] . '). Harap selesaikan prosedur Faktur Pengganti (011) / Nota Retur di Modul Taxes, atau centang persetujuan Penyerapan Selisih Pajak Internal.');
            }
        }

        // Lapis 1 Blocker: Cek kecukupan tagihan baru terhadap pembayaran kasir yang sudah masuk
        $terbayar_kasir = 0;
        $this->db->select('transaksi_dibayar');
        $this->db->where('id', $invoice_id);
        $cek_bayar = $this->db->get('transaksi')->row_array();
        if ($cek_bayar && (float)$cek_bayar['transaksi_dibayar'] > 0) {
            $terbayar_kasir = (float)$cek_bayar['transaksi_dibayar'];
        }
        if ($this->db->table_exists('transaksi_payment_source')) {
            $tps_target_ids = array($invoice_id);
            if (isset($snapshot['header']['id_master']) && $snapshot['header']['id_master'] > 0) {
                $tps_target_ids[] = (int)$snapshot['header']['id_master'];
            }
            if (isset($snapshot['header']['reference_id']) && $snapshot['header']['reference_id'] > 0) {
                $tps_target_ids[] = (int)$snapshot['header']['reference_id'];
            }
            $tps_row = $this->db->select_max('terbayar')->where_in('transaksi_id', $tps_target_ids)->get('transaksi_payment_source')->row_array();
            if ($tps_row && (float)$tps_row['terbayar'] > $terbayar_kasir) {
                $terbayar_kasir = (float)$tps_row['terbayar'];
            }
        }

        if ($terbayar_kasir > 0 && ($calc_gt - $terbayar_kasir) < -0.01) {
            die('Gagal: Nominal tagihan baru (Rp ' . number_format($calc_gt, 0, ',', '.') . ') tidak boleh lebih kecil dari pembayaran kasir yang sudah diterima (Rp ' . number_format($terbayar_kasir, 0, ',', '.') . '). Terdapat lebih bayar konsumen sebesar Rp ' . number_format($terbayar_kasir - $calc_gt, 0, ',', '.') . '. Harap batalkan transaksi penerimaan kas terlebih dahulu jika ingin menurunkan tagihan.');
        }

        $this->db->trans_start();

        // 2. Kalkulasi ulang JSON & eksekusi langsung ke transaksi (membawa parameter $absorb_tax)
        $tagihan_baru = $this->ComAmandemenInvoice->processAmandemenJSON($invoice_id, $post_items, $snapshot, $description, $catatan_amandemen, $post_jurnal_custom, $absorb_tax);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || $tagihan_baru === false) {
            $err = $this->db->error();
            die('Transaksi database gagal diselesaikan. Error: ' . json_encode($err));
        }

        echo "<h2>Diskon / Adjustment Berhasil Disimpan!</h2>";
        echo "<p>Total Tagihan Invoice kini telah berubah menjadi: <b>Rp " . number_format($tagihan_baru, 0, ',', '.') . "</b></p>";
        if ($absorb_tax) {
            echo "<p style='color:#c53030;'><b>Catatan Fiskal:</b> Selisih PPN diserap perusahaan ke Beban Pajak internal. e-Faktur resmi DJP tetap aktif dan tidak dibatalkan.</p>";
        }
        echo "<script>
                setTimeout(function(){ 
                    if(window.parent) {
                        var curUrl = window.parent.location.href.split('?')[0];
                        window.parent.location.href = curUrl + '?saved=1" . ($absorb_tax ? "&absorb_tax=1" : "") . "';
                    }
                }, 1500);
              </script>";
// END OF COMPLETE REPEATED LOGIC
    }

    public function restore_history($invoice_id, $history_id) {
        $invoice_id = (int)$invoice_id;
        $history_id = (int)$history_id;

        // Lapis 1 Blocker: Cek pembayaran A/R Receipt
        $this->db->select('transaksi_dibayar');
        $this->db->where('id', $invoice_id);
        $cek_bayar = $this->db->get('transaksi')->row_array();
        if ($cek_bayar && (float)$cek_bayar['transaksi_dibayar'] > 0) {
            die('Gagal: Invoice tidak dapat dipulihkan karena sudah ada pembayaran A/R Receipt sebesar Rp ' . number_format($cek_bayar['transaksi_dibayar'], 0, ',', '.'));
        }

        $this->db->trans_start();

        $tagihan_baru = $this->ComAmandemenInvoice->rollbackToHistory($invoice_id, $history_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || $tagihan_baru === false) {
            die('Gagal memulihkan ke versi histori.');
        }

        echo "<h2>Pemulihan Versi Berhasil!</h2>";
        echo "<p>Invoice telah dipulihkan ke versi histori #" . $history_id . ". Nilai tagihan aktif kini: <b>Rp " . number_format($tagihan_baru, 0, ',', '.') . "</b></p>";
        echo "<script>
                setTimeout(function(){ 
                    if(window.parent) {
                        var curUrl = window.parent.location.href.split('?')[0];
                        window.parent.location.href = curUrl + '?restored=1&absorb_tax=1';
                    }
                }, 1500);
              </script>";
    }
}
