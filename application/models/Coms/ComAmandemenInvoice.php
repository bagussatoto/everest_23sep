<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Model bisnis untuk amandemen invoice, menangani unpack JSON, re-calculate, dan jurnal storno.
 * COMPLIANCE: ISO 9001 (Audit Trail), Strict ACID CI3 Transactions
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ComAmandemenInvoice extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('he_angka');
        $this->load->model('CustomCounter');
    }

// START OF COMPLETE REPEATED LOGIC
    public function checkTaxStatus($invoice_id) {
        $invoice_id = (int)$invoice_id;
        $this->db->select('id, nomer, reference_id, reference_nomer, efaktur, efaktur_dtime, transaksi_nilai, ppn_nilai');
        $this->db->where('id', $invoice_id);
        
        $inv = $this->db->get('transaksi')->row_array();
        if (!$inv) return array('status' => 'SAFE');

        $ref_id = !empty($inv['reference_id']) ? (int)$inv['reference_id'] : 0;
        $ids_to_check = array($invoice_id);
        if ($ref_id > 0) {
            $ids_to_check[] = $ref_id;
        }

        // Ambil transaksi terkait untuk memeriksa status pajak dan pembatalan
        $this->db->select('id, nomer, jenis, id_master, id_top, reference_id, reference_nomer, efaktur, efaktur_dtime, transaksi_nilai, ppn_nilai, trash_4, link_id, keterangan');
        $this->db->group_start();
            $this->db->where_in('id', $ids_to_check);
            $this->db->or_where_in('reference_id', $ids_to_check);
            $this->db->or_where('reference_nomer', $inv['nomer']);
            if (!empty($inv['reference_nomer'])) {
                $this->db->or_where('reference_nomer', $inv['reference_nomer']);
            }
        $this->db->group_end();
        $this->db->order_by('id', 'DESC');
        $res = $this->db->get('transaksi')->result_array();

        // 1. Identifikasi transaksi pajak yang telah dibatalkan (trash_4 = 1 atau dibatalkan via Modul Pembatalan 9911)
        $cancelled_tax_ids = array();
        $cancelled_efakturs = array();

        foreach ($res as $r) {
            $j = strtolower(trim($r['jenis']));
            // Transaksi pembatalan jenis 9911 aktif
            if (strpos($j, '9911') === 0 && (int)$r['trash_4'] === 0) {
                if (!empty($r['reference_id'])) {
                    $cancelled_tax_ids[(int)$r['reference_id']] = true;
                }
            }
            // Transaksi yang ditandai batal (trash_4 = 1)
            if ((int)$r['trash_4'] === 1) {
                $cancelled_tax_ids[(int)$r['id']] = true;
                $ef = trim($r['efaktur']);
                if (!empty($ef) && $ef !== '0') {
                    $cancelled_efakturs[$ef] = true;
                }
            }
        }

        // 2. Evaluasi transaksi pajak aktif
        $tax_draft = null;

        foreach ($res as $trx) {
            $trx_id = (int)$trx['id'];
            $efaktur = trim($trx['efaktur']);
            $jenis = strtolower(trim($trx['jenis']));
            $link_id = (int)$trx['link_id'];
            $is_trash = ((int)$trx['trash_4'] === 1);

            // Abaikan baris shadow / child transaksi (link_id > 0)
            if ($link_id > 0) {
                continue;
            }

            // Abaikan transaksi yang sudah dibatalkan (trash_4 = 1 atau direferensikan oleh 9911)
            if ($is_trash || isset($cancelled_tax_ids[$trx_id])) {
                continue;
            }

            // Abaikan transaksi prekursor (seperti 110e) jika nomor efaktur-nya sudah dibatalkan
            if (!empty($efaktur) && isset($cancelled_efakturs[$efaktur])) {
                continue;
            }

            // Jika ada e-Faktur resmi yang masih AKTIF (belum dibatalkan)
            if (!empty($efaktur) && $efaktur !== '0') {
                $dpp = (float)$trx['transaksi_nilai'] - (float)$trx['ppn_nilai'];
                if ($dpp <= 0) $dpp = (float)$inv['transaksi_nilai'] - (float)$inv['ppn_nilai'];
                
                return array(
                    'status' => 'APPROVED_DJP',
                    'tax_id' => $trx['id'],
                    'tax_nomer' => $trx['nomer'],
                    'tax_jenis' => $trx['jenis'],
                    'efaktur' => $efaktur,
                    'efaktur_dtime' => $trx['efaktur_dtime'],
                    'dpp' => $dpp,
                    'ppn' => $trx['ppn_nilai'],
                    'nilai' => $trx['transaksi_nilai']
                );
            }

            // Simpan referensi draft pajak aktif jika ada (110r, 110e, 110 tanpa approved e-faktur)
            if (in_array($jenis, array('110', '110e', '110r'))) {
                if (!$tax_draft) {
                    $tax_draft = $trx;
                }
            }
        }

        if ($tax_draft) {
            return array(
                'status' => 'DRAFT',
                'tax_id' => $tax_draft['id'],
                'tax_nomer' => $tax_draft['nomer'],
                'tax_jenis' => $tax_draft['jenis'],
                'efaktur' => '0',
                'efaktur_dtime' => $tax_draft['efaktur_dtime'],
                'dpp' => (float)$tax_draft['transaksi_nilai'] - (float)$tax_draft['ppn_nilai'],
                'ppn' => $tax_draft['ppn_nilai'],
                'nilai' => $tax_draft['transaksi_nilai']
            );
        }

        return array('status' => 'SAFE');
    }
// END OF COMPLETE REPEATED LOGIC

    public function createAuditSnapshot($invoice_id) {
        $this->db->where('id', $invoice_id);
        $header = $this->db->get('transaksi')->row_array();
        
        if (!$header) return false;

        $this->db->where('transaksi_id', $invoice_id);
        $this->db->order_by('dtime', 'DESC');
        $this->db->limit(1);
        $registry = $this->db->get('transaksi_data_registry')->row_array();

        // Tangkap juga seluruh baris fisik rincian item di transaksi_data (SELECT *)
        $this->db->where('transaksi_id', $invoice_id);
        if ($this->db->field_exists('trash', 'transaksi_data')) {
            $this->db->where('trash', '0');
        }
        $items_detail = $this->db->get('transaksi_data')->result_array();

        // Tangkap juga seluruh baris jurnal fisik aktif yang akan diganti (mencakup jurnal di id_master)
        $id_master = isset($header['id_master']) ? (int)$header['id_master'] : 0;
        $target_ids = array_unique(array_filter(array((int)$invoice_id, $id_master)));
        if (empty($target_ids)) {
            $target_ids = array($invoice_id);
        }

        $this->db->where_in('transaksi_id', $target_ids);
        if ($this->db->field_exists('trash', 'jurnal')) {
            $this->db->where('trash', '0');
        }
        $jurnal_detail = $this->db->get('jurnal')->result_array();

        // 100% COMPLETE SNAPSHOT: Menyimpan SELECT * dari transaksi, transaksi_data_registry, transaksi_data, dan jurnal
        $snapshot = array(
            'header' => $header,
            'registry' => $registry,
            'items_detail' => $items_detail,
            'jurnal_detail' => $jurnal_detail,
            'timestamp' => date('Y-m-d H:i:s')
        );

        return $snapshot;
    }

    /**
     * Memproses logika ACID Amandemen dari data POST Items
     */
    public function processAmandemenJSON($invoice_id, $post_items, $snapshot, $description = '', $catatan_amandemen = '', $post_jurnal_custom = null, $absorb_tax = false) {
        $old_registry = $snapshot['registry'];
        if (!$old_registry) return false;

        $items5_sum = array();
// START OF COMPLETE REPEATED LOGIC
        $grandTotalDPP = 0;

        $old_items5_sum = @unserialize(base64_decode($old_registry['items5_sum']));
        $items5_sum = $old_items5_sum; // Pertahankan struktur asli

        // Self-Healing: Jika items5_sum kosong (memang tidak ter-capture saat pembuatan invoice),
        // rebuild dari project_sub_tasklist_komposisi via project_id di registry items.
        if (empty($items5_sum)) {
            // Coba pulihkan dari histori amandemen
            $this->db->where('transaksi_id', $invoice_id);
            $this->db->order_by('id', 'ASC');
            $this->db->limit(1);
            $his = $this->db->get('transaksi_amandemen_history')->row_array();
            if ($his && !empty($his['old_registry_data'])) {
                $old_reg_his = json_decode($his['old_registry_data'], true);
                if ($old_reg_his && !empty($old_reg_his['items5_sum'])) {
                    $old_his_items5 = @unserialize(base64_decode($old_reg_his['items5_sum']));
                    if (!empty($old_his_items5)) {
                        $items5_sum = $old_his_items5;
                    }
                }
            }
        }

        // Self-Healing Level 2: Rebuild dari project_sub_tasklist_komposisi 
        // (sumber kebenaran untuk invoice proyek 4822)
        if (empty($items5_sum)) {
            $old_items = @unserialize(base64_decode($old_registry['items']));
            $rebuild_project_id = 0;
            if (is_array($old_items)) {
                $first_item = reset($old_items);
                if (isset($first_item['project_id'])) {
                    $rebuild_project_id = (int)$first_item['project_id'];
                } elseif (isset($first_item['projectID'])) {
                    $rebuild_project_id = (int)$first_item['projectID'];
                }
            }

            if ($rebuild_project_id > 0) {
                // Query komposisi SPK yang aktif untuk proyek ini
                $sql_rebuild = "
                    SELECT t.id as tasklist_id, t.no_spk, t.produk_id as project_produk_id,
                           pp.nama as project_nama, pp.harga as project_harga,
                           k.id as komp_id, k.jenis, k.produk_dasar_id, k.produk_dasar_nama,
                           k.jml, k.harga as komp_harga, k.jml_return, k.nilai_return,
                           k.biaya_id, k.biaya_dasar_id, k.no_sub, k.sub_fase_id, k.link_id,
                           k.produk_id as komp_produk_id
                    FROM project_sub_tasklist_komposisi k
                    INNER JOIN project_tasklist t ON k.no_spk = t.no_spk
                    INNER JOIN project_produk pp ON t.produk_id = pp.id
                    WHERE t.produk_id = ? AND t.trash = 0 AND k.trash = 0 AND k.progress_id != 3
                    ORDER BY k.jenis, k.produk_dasar_nama
                ";
                $res_rebuild = $this->db->query($sql_rebuild, array($rebuild_project_id));

                if ($res_rebuild && $res_rebuild->num_rows() > 0) {
                    // Bangun items5_sum dengan format nested bahan_baku
                    // sesuai struktur yang diharapkan oleh Printing.php view
                    $tasklist_map = array();
                    foreach ($res_rebuild->result_array() as $komp) {
                        $tl_id = $komp['tasklist_id'];
                        if (!isset($tasklist_map[$tl_id])) {
                            $tasklist_map[$tl_id] = array(
                                'id' => $tl_id,
                                'nama' => $komp['project_nama'],
                                'no_spk' => $komp['no_spk'],
                                'produk_id' => $komp['project_produk_id'],
                                'produk_nama' => $komp['project_nama'],
                                'bahan_baku' => array(
                                    'produk' => array(),
                                    'biaya' => array()
                                )
                            );
                        }

                        $pd_id = $komp['produk_dasar_id'];
                        $entry = array(
                            'id' => $komp['komp_id'],
                            'jenis' => $komp['jenis'],
                            'no_spk' => $komp['no_spk'],
                            'produk_dasar_id' => $pd_id,
                            'produk_dasar_nama' => $komp['produk_dasar_nama'],
                            'jml' => (float)$komp['jml'],
                            'harga' => (float)$komp['komp_harga'],
                            'saldo' => (float)$komp['jml'] * (float)$komp['komp_harga'],
                            'jml_return' => (float)$komp['jml_return'],
                            'nilai_return' => (float)$komp['nilai_return'],
                            'biaya_id' => isset($komp['biaya_id']) ? $komp['biaya_id'] : 0,
                            'biaya_dasar_id' => isset($komp['biaya_dasar_id']) ? $komp['biaya_dasar_id'] : 0,
                            'sub_fase_id' => isset($komp['sub_fase_id']) ? $komp['sub_fase_id'] : 0,
                            'link_id' => isset($komp['link_id']) ? $komp['link_id'] : 0,
                            'satuan' => ''
                        );

                        if ($komp['jenis'] == 'biaya' || $komp['jenis'] == 'supplies') {
                            $tasklist_map[$tl_id]['bahan_baku']['biaya'][$pd_id] = $entry;
                        } else {
                            $tasklist_map[$tl_id]['bahan_baku']['produk'][$pd_id] = $entry;
                        }
                    }
                    $items5_sum = $tasklist_map;
                }
            }
        }

        $post_map_by_pd = array();
        $post_map_by_id = array();
        $new_tableIn_detail_values = array();

        $bahan_baku_produk = array();
        $bahan_baku_biaya = array();

        $project_id = isset($snapshot['header']['project_id']) ? (int)$snapshot['header']['project_id'] : 0;
        $project_name = '';
        if (isset($snapshot['header']['project_nama']) && !empty($snapshot['header']['project_nama']) && $snapshot['header']['project_nama'] !== 'PEKERJAAN PROJECT') {
            $project_name = $snapshot['header']['project_nama'];
        } elseif (isset($snapshot['main']['projectName']) && !empty($snapshot['main']['projectName']) && $snapshot['main']['projectName'] !== 'PEKERJAAN PROJECT') {
            $project_name = $snapshot['main']['projectName'];
        } elseif ($project_id > 0) {
            $prj_row = $this->db->select('nama')->where('id', $project_id)->get('project_produk')->row_array();
            if ($prj_row && !empty($prj_row['nama'])) {
                $project_name = $prj_row['nama'];
            }
        }
        if (empty($project_name)) {
            $project_name = 'AVESTA - MATERIAL';
        }

        $spk_id = isset($snapshot['items']) && is_array($snapshot['items']) && count($snapshot['items']) > 0 ? key($snapshot['items']) : 0;

        // Auto-Lookup Master Data Satuan (produk.size_nama)
        $pd_ids_to_lookup = array();
        if (is_array($post_items)) {
            foreach ($post_items as $itm) {
                $pd_val = isset($itm['produk_dasar_id']) ? (int)$itm['produk_dasar_id'] : 0;
                if ($pd_val > 0) $pd_ids_to_lookup[] = $pd_val;
            }
        }
        $master_satuan_map = array();
        if (!empty($pd_ids_to_lookup)) {
            $this->db->select('id, size_nama, nama');
            $this->db->where_in('id', array_unique($pd_ids_to_lookup));
            $res_prd = $this->db->get('produk')->result_array();
            if (!empty($res_prd)) {
                foreach ($res_prd as $rp) {
                    if (!empty($rp['size_nama'])) {
                        $master_satuan_map[$rp['id']] = $rp['size_nama'];
                    }
                }
            }
        }

        if (is_array($post_items)) {
            $urut = 1;
            foreach ($post_items as $itm) {
                $pd = isset($itm['produk_dasar_id']) ? (string)$itm['produk_dasar_id'] : '0';
                $id = isset($itm['id']) ? (string)$itm['id'] : '0';
                if ($pd === '') $pd = '0';
                if ($id === '') $id = '0';

                $raw_qty = isset($itm['jml']) ? $itm['jml'] : 0;
                $raw_harga = isset($itm['harga']) ? $itm['harga'] : 0;
                $qty = is_string($raw_qty) ? (float)str_replace(',', '.', str_replace('.', '', $raw_qty)) : (float)$raw_qty;
                $harga = is_string($raw_harga) ? (float)str_replace(',', '.', str_replace('.', '', $raw_harga)) : (float)$raw_harga;
                $nama = !empty($itm['nama']) ? $itm['nama'] : '';
                $raw_satuan = isset($itm['satuan']) ? trim($itm['satuan']) : '';
                $subtotal = $qty * $harga;
                
                if ($qty > 0) {
                    $is_jasa = (stripos($nama, 'jasa') !== false || stripos($nama, 'bongkar') !== false || stripos($nama, 'biaya') !== false || stripos($nama, 'instalasi') !== false);

                    // Auto-sync satuan ke master data produk jika kosong / 'null'
                    if (empty($raw_satuan) || strtolower($raw_satuan) === 'null' || $raw_satuan === '-') {
                        if ($pd !== '0' && isset($master_satuan_map[(int)$pd])) {
                            $satuan = $master_satuan_map[(int)$pd];
                        } else {
                            $satuan = $is_jasa ? 'Unit' : 'Unit';
                        }
                    } else {
                        $satuan = $raw_satuan;
                    }

                    $bb_row = array(
                        'id' => $id,
                        'produk_id' => $pd,
                        'produk_dasar_id' => $pd,
                        'produk_dasar_nama' => $nama,
                        'nama' => $nama,
                        'satuan' => $satuan,
                        'jml' => $qty,
                        'harga' => $harga,
                        'saldo' => $subtotal,
                        'jenis' => $is_jasa ? 'biaya' : 'produk'
                    );

                    if ($is_jasa) {
                        $bahan_baku_biaya[] = $bb_row;
                    } else {
                        $bahan_baku_produk[] = $bb_row;
                    }

                    // Rebuild tableIn_detail_values (for Printing.php)
                    if ($pd !== '0') {
                        $new_tableIn_detail_values[$pd] = array(
                            'qty' => $qty,
                            'harga' => $harga,
                            'subtotal' => $subtotal,
                            'produk_nama' => $nama,
                            'urutan' => $urut
                        );
                    }
                    $urut++;
                }

                if ($pd !== '0') {
                    $post_map_by_pd[$pd] = $itm;
                }
                if ($id !== '0') {
                    $post_map_by_id[$id] = $itm;
                }
                
                $grandTotalDPP += $qty * $harga;
            }
        }

        // Rebuild Hirarkis items5_sum dengan struktur bahan_baku (Lengkap untuk 6 OPSI CETAK Printing.php)
        $new_items5_sum = array(
            0 => array(
                'id' => $spk_id > 0 ? $spk_id : 1,
                'produk_id' => $project_id,
                'produk_nama' => $project_name,
                'nama' => $project_name,
                'harga' => $grandTotalDPP,
                'saldo' => $grandTotalDPP,
                'bahan_baku' => array(
                    'produk' => $bahan_baku_produk,
                    'biaya' => $bahan_baku_biaya
                )
            )
        );

        // --- ENTERPRISE RETURN SERVICE LOGIC ---
        $return_list = array('supplies' => array(), 'produk' => array());
        $selisih_map = array(); // Map berdasarkan komposisi ID

        // 1. Baca langsung input retur_qty yang ditetapkan user di form ($post_items)
        if (is_array($post_items)) {
            foreach ($post_items as $itm) {
                $raw_retur = isset($itm['retur_qty']) ? $itm['retur_qty'] : 0;
                $retur_qty = is_string($raw_retur) ? (float)str_replace(',', '.', str_replace('.', '', $raw_retur)) : (float)$raw_retur;
                $komp_id = isset($itm['id']) ? (int)$itm['id'] : 0;
                
                if ($retur_qty > 0 && $komp_id > 0) {
                    $selisih_map[$komp_id] = $retur_qty;
                }
            }
        }

        $items5_sum_old = array();
        if (isset($old_registry['items5_sum'])) {
            $items5_sum_old = @unserialize(base64_decode($old_registry['items5_sum']));
        }

        if (is_array($items5_sum_old)) {
            foreach ($items5_sum_old as $td_id => $td_data) {
                if (isset($td_data['bahan_baku']['produk']) && is_array($td_data['bahan_baku']['produk'])) {
                    foreach ($td_data['bahan_baku']['produk'] as $pd_key => $prd) {
                        $qty_lama = (float)$prd['jml'];
                        $komp_id = isset($prd['id']) ? (int)$prd['id'] : 0;
                        
                        $item_pd_id = isset($prd['produk_dasar_id']) ? (string)$prd['produk_dasar_id'] : (string)$pd_key;
                        $item_id = isset($prd['id']) ? (string)$prd['id'] : '';

                        $matched = null;
                        if ($item_id !== '' && $item_id !== '0' && isset($post_map_by_id[$item_id])) {
                            $matched = $post_map_by_id[$item_id];
                        } elseif ($item_pd_id !== '' && $item_pd_id !== '0' && isset($post_map_by_pd[$item_pd_id])) {
                            $matched = $post_map_by_pd[$item_pd_id];
                        }

                        $qty_baru = 0;
                        if ($matched !== null) {
                            $raw_match_qty = isset($matched['jml']) ? $matched['jml'] : 0;
                            $qty_baru = is_string($raw_match_qty) ? (float)str_replace(',', '.', str_replace('.', '', $raw_match_qty)) : (float)$raw_match_qty;
                        }

                        $selisih = $qty_lama - $qty_baru;
                        if ($selisih > 0 && $komp_id > 0 && !isset($selisih_map[$komp_id])) {
                            $selisih_map[$komp_id] = $selisih;
                        }
                    }
                }

                if (isset($td_data['bahan_baku']['biaya']) && is_array($td_data['bahan_baku']['biaya'])) {
                    foreach ($td_data['bahan_baku']['biaya'] as $pd_key => $biy) {
                        $qty_lama = (float)$biy['jml'];
                        $komp_id = isset($biy['id']) ? (int)$biy['id'] : 0;

                        $item_pd_id = isset($biy['produk_dasar_id']) ? (string)$biy['produk_dasar_id'] : (string)$pd_key;
                        $item_id = isset($biy['id']) ? (string)$biy['id'] : '';

                        $matched = null;
                        if ($item_id !== '' && $item_id !== '0' && isset($post_map_by_id[$item_id])) {
                            $matched = $post_map_by_id[$item_id];
                        } elseif ($item_pd_id !== '' && $item_pd_id !== '0' && isset($post_map_by_pd[$item_pd_id])) {
                            $matched = $post_map_by_pd[$item_pd_id];
                        }

                        $qty_baru = 0;
                        if ($matched !== null) {
                            $raw_match_qty = isset($matched['jml']) ? $matched['jml'] : 0;
                            $qty_baru = is_string($raw_match_qty) ? (float)str_replace(',', '.', str_replace('.', '', $raw_match_qty)) : (float)$raw_match_qty;
                        }

                        $selisih = $qty_lama - $qty_baru;
                        if ($selisih > 0 && $komp_id > 0 && !isset($selisih_map[$komp_id])) {
                            $selisih_map[$komp_id] = $selisih;
                        }
                    }
                }
                
                // Flat structure support (if items5_sum was flattened)
                if (!isset($td_data['bahan_baku']) && (isset($td_data['produk_dasar_id']) || isset($td_data['id']))) {
                    $qty_lama = (float)(isset($td_data['jml']) ? $td_data['jml'] : 0);
                    $komp_id = isset($td_data['id']) ? (int)$td_data['id'] : 0;

                    $item_pd_id = isset($td_data['produk_dasar_id']) ? (string)$td_data['produk_dasar_id'] : '';
                    $item_id = isset($td_data['id']) ? (string)$td_data['id'] : '';

                    $matched = null;
                    if ($item_id !== '' && $item_id !== '0' && isset($post_map_by_id[$item_id])) {
                        $matched = $post_map_by_id[$item_id];
                    } elseif ($item_pd_id !== '' && $item_pd_id !== '0' && isset($post_map_by_pd[$item_pd_id])) {
                        $matched = $post_map_by_pd[$item_pd_id];
                    }

                    $qty_baru = 0;
                    if ($matched !== null) {
                        $raw_match_qty = isset($matched['jml']) ? $matched['jml'] : 0;
                        $qty_baru = is_string($raw_match_qty) ? (float)str_replace(',', '.', str_replace('.', '', $raw_match_qty)) : (float)$raw_match_qty;
                    }

                    $selisih = $qty_lama - $qty_baru;
                    if ($selisih > 0 && $komp_id > 0 && !isset($selisih_map[$komp_id])) {
                        $selisih_map[$komp_id] = $selisih;
                    }
                }
            }
        }

        // Assign rebuilt arrays back
        $items5_sum = $new_items5_sum;

        if (!empty($selisih_map)) {
            $ids = array_keys($selisih_map);
            $this->db->where_in('id', $ids);
            $komposisiData = $this->db->get('project_sub_tasklist_komposisi')->result_array();

            $no_spk = "";
            $sub_nomer = "";
            $sub_fase_id = 0;
            $link_id = 0; // tasklist_id
            $wo_paket_id = 0;
            $produk_id = 0;

            foreach ($komposisiData as $komp) {
                $old_id = (int)$komp['id'];
                $selisih = (float)$selisih_map[$old_id];
                $jenis = strtolower(trim($komp['jenis']));

                $no_spk = $komp['no_spk'];
                $sub_nomer = $komp['no_sub'];
                $sub_fase_id = (int)$komp['sub_fase_id'];
                $link_id = (int)$komp['link_id'];
                $wo_paket_id = isset($komp['produk_paket_id']) ? (int)$komp['produk_paket_id'] : 0;
                $produk_id = (int)$komp['produk_id'];
                
                $biaya_id = !empty($komp['biaya_id']) ? (int)$komp['biaya_id'] : 0;

                $new_jml_return = (float)$komp['jml_return'] + $selisih;
                $new_nilai_return = (float)$komp['nilai_return'] + ($selisih * (float)$komp['harga']);
                
                $this->db->where('id', $old_id);
                $this->db->update('project_sub_tasklist_komposisi', array(
                    'jml_return' => $new_jml_return,
                    'nilai_return' => $new_nilai_return
                ));

                if ($jenis == 'biaya' || $jenis == 'supplies') {
                    $return_list['supplies'][$biaya_id][] = array(
                        'produk_dasar_id' => $komp['produk_dasar_id'],
                        'produk_dasar_nama' => $komp['produk_dasar_nama'],
                        'satuan' => isset($komp['satuan']) ? $komp['satuan'] : '',
                        'jml_return' => $selisih
                    );
                } else {
                    $return_list['produk'][$biaya_id][] = array(
                        'produk_dasar_id' => $komp['produk_dasar_id'],
                        'produk_dasar_nama' => $komp['produk_dasar_nama'],
                        'satuan' => isset($komp['satuan']) ? $komp['satuan'] : '',
                        'jml_return' => $selisih
                    );
                }
            }

            $projectNameStr = "Diskon / Adjustment Invoice " . $snapshot['header']['nomer'];

            // Dapatkan info Termin Induk untuk catatan audit retur
            $termin_nomer = '';
            if (isset($snapshot['header']['reference_id']) && (int)$snapshot['header']['reference_id'] > 0) {
                $ref_trx = $this->db->select('nomer')->where('id', (int)$snapshot['header']['reference_id'])->get('transaksi')->row_array();
                if ($ref_trx && !empty($ref_trx['nomer'])) {
                    $termin_nomer = $ref_trx['nomer'];
                }
            }
            if (empty($termin_nomer)) {
                $termin_nomer = isset($snapshot['header']['nomer_top']) && !empty($snapshot['header']['nomer_top']) ? $snapshot['header']['nomer_top'] : 'Termin Proyek';
            }

            $spk_label = !empty($no_spk) ? $no_spk : '-';
            $invoice_label = isset($snapshot['header']['nomer']) ? $snapshot['header']['nomer'] : (string)$invoice_id;
            $catatan_user = !empty($history_keterangan) ? " | Alasan: " . $history_keterangan : "";
            $structured_return_desc = "Retur Fisik Amandemen Invoice: " . $invoice_label . " | SPK: " . $spk_label . " | Termin: " . $termin_nomer . $catatan_user;
            
            // Dapatkan Gudang WO SPK sebenarnya dari project_tasklist
            $spk_tasklist = $this->db->get_where('project_tasklist', array('no_spk' => $no_spk, 'trash' => 0))->row_array();
            $gudang_wo_actual = ($spk_tasklist && !empty($spk_tasklist['gudang_wo'])) ? $spk_tasklist['gudang_wo'] : 9;

            if (!empty($return_list['supplies'])) {
                $supplies_items = array();
                foreach ($return_list['supplies'] as $biyID => $biyItems) {
                    foreach ($biyItems as $subItem) {
                        $supplies_items[$biyID][] = array(
                            "biaya_id" => $biyID,
                            "sub_biaya_id" => 0,
                            "produk_dasar_id" => $subItem['produk_dasar_id'],
                            "nama" => isset($subItem['produk_dasar_nama']) ? $subItem['produk_dasar_nama'] : "-",
                            "satuan" => isset($subItem['satuan']) ? $subItem['satuan'] : "-",
                            "jml_return" => $subItem['jml_return']
                        );
                    }
                }
                if (!empty($supplies_items)) {
                    $this->load->library("SuppliesReturnService");
                    $suppliesService = new SuppliesReturnService();
                    $paramsSupplies = array(
                        "produk_id" => $produk_id,
                        "produk_nama" => $projectNameStr,
                        "no_spk" => $no_spk,
                        "sub_nomer" => $sub_nomer,
                        "tasklist_id" => $link_id,
                        "sub_fase_id" => $sub_fase_id,
                        "wo_paket_id" => $wo_paket_id,
                        "wo_paket_nama" => "-",
                        "gudang_wo_id" => $gudang_wo_actual,
                        "return_items" => $supplies_items,
                        "cabang_id" => function_exists('my_cabang_id') ? my_cabang_id() : 1,
                        "cabang_nama" => function_exists('my_cabang_nama') ? my_cabang_nama() : '',
                        "bookingNumber" => $snapshot['header']['nomer'],
                        "description" => $structured_return_desc
                    );
                    $suppliesService->processReturn($paramsSupplies);
                }
            }

            if (!empty($return_list['produk'])) {
                $fg_items = array();
                foreach ($return_list['produk'] as $biyID => $biyItems) {
                    foreach ($biyItems as $subItem) {
                        $fg_items[] = array(
                            "biaya_id" => $biyID,
                            "produk_dasar_id" => $subItem['produk_dasar_id'],
                            "nama" => isset($subItem['produk_dasar_nama']) ? $subItem['produk_dasar_nama'] : "-",
                            "satuan" => isset($subItem['satuan']) ? $subItem['satuan'] : "-",
                            "jml_return" => $subItem['jml_return']
                        );
                    }
                }
                if (!empty($fg_items)) {
                    $this->load->library("FgReturnService");
                    $fgService = new FgReturnService();
                    $paramsFg = array(
                        "produk_id" => $produk_id,
                        "produk_nama" => $projectNameStr,
                        "no_spk" => $no_spk,
                        "sub_nomer" => $sub_nomer,
                        "tasklist_id" => $link_id,
                        "sub_fase_id" => $sub_fase_id,
                        "wo_paket_id" => $wo_paket_id,
                        "wo_paket_nama" => "-",
                        "gudang_wo_id" => $gudang_wo_actual,
                        "return_items" => $fg_items,
                        "cabang_id" => function_exists('my_cabang_id') ? my_cabang_id() : 1,
                        "cabang_nama" => function_exists('my_cabang_nama') ? my_cabang_nama() : '',
                        "bookingNumber" => $snapshot['header']['nomer'],
                        "description" => $structured_return_desc
                    );
                    $fgService->processReturn($paramsFg);
                }
            }
        }
        // --- END ENTERPRISE RETURN SERVICE LOGIC ---

        // --- PREPARE REGISTRY & DB UPDATE ---
        $header = $snapshot['header'];
        $ppn_rate = 11;
        $ppn_val = ($grandTotalDPP * $ppn_rate) / 100;
        $tagihan_baru = $grandTotalDPP + $ppn_val;

        $items = @unserialize(base64_decode($old_registry['items']));
        if ($items && is_array($items)) {
            $first_key = key($items);
            if (isset($items[$first_key])) {
                $items[$first_key]['subtotal'] = $grandTotalDPP;
                $items[$first_key]['tagihan'] = $grandTotalDPP;
                $items[$first_key]['sisa'] = $grandTotalDPP;
            }
        }

        $main = @unserialize(base64_decode($old_registry['main']));
        if ($main && is_array($main)) {
            // 1. Kategori DPP / Nilai Jual
            $dpp_keys = array('nett1', 'dpp_ppn', 'subtotal', 'tagihan', 'nilai_bayar', 'penjualan', 'penjualan_bulat', 'new_net1', 'grand_total_ui', 'harus_bayar', 'nilai_entry', 'nilai_cash');
            foreach ($dpp_keys as $k) {
                if (isset($main[$k])) $main[$k] = $grandTotalDPP;
            }

            // 2. Kategori PPN
            $ppn_keys = array('ppn_out_bulat', 'ppn', 'grand_ppn');
            foreach ($ppn_keys as $k) {
                if (isset($main[$k])) $main[$k] = $ppn_val;
            }

            // 3. Kategori Grand Total
            $gt_keys = array('grand_pembulatan', 'grand_total', 'new_net3', 'piutang_dagang', 'piutang_usaha');
            foreach ($gt_keys as $k) {
                if (isset($main[$k])) $main[$k] = $tagihan_baru;
            }
            // Explicitly ensure critical keys for layout, printing, and inword are always present
            $main['grand_pembulatan'] = $tagihan_baru;
            $main['grand_total']      = $tagihan_baru;
            $main['new_net3']         = $tagihan_baru;
            $main['piutang_dagang']   = $tagihan_baru;
            $main['piutang_usaha']    = $tagihan_baru;
            $main['nett1']            = $grandTotalDPP;
            $main['dpp_ppn']          = $grandTotalDPP;
            $main['ppn_out_bulat']    = $ppn_val;
            $main['ppn']              = $ppn_val;
            $main['grand_ppn']        = $ppn_val;

            // 4. Kategori Pajak Khusus (DPP Pengganti)
            if (isset($main['dpp_pengganti_factor']) && isset($main['dpp_pengganti'])) {
                $main['dpp_pengganti'] = $grandTotalDPP * $main['dpp_pengganti_factor'];
            }

            // Notes Client (Untuk Penagihan Client / Dicetak di Invoice)
            if (!empty($description)) {
                $main['keterangan'] = $description;
                $main['description'] = nl2br($description);
            }
            
            // Catatan Amandemen (Khusus Internal)
            if (!empty($catatan_amandemen)) {
                $main['catatan_amandemen_internal'] = $catatan_amandemen;
            }
            $main['keterangan_amandemen'] = 'Amandemen via Modul';
        }

        // Update tableIn_master_values jika ada pada old_registry
        $tableIn_master_values_encoded = isset($old_registry['tableIn_master_values']) ? $old_registry['tableIn_master_values'] : '';
        if (!empty($old_registry['tableIn_master_values'])) {
            $master_vals = @unserialize(base64_decode($old_registry['tableIn_master_values']));
            if ($master_vals && is_array($master_vals)) {
                $master_vals['tagihan'] = $tagihan_baru;
                $master_vals['nett1'] = $grandTotalDPP;
                $master_vals['ppn'] = $ppn_val;
                $master_vals['grand_total'] = $tagihan_baru;
                if (!empty($description)) {
                    $master_vals['keterangan'] = $description;
                    $master_vals['description'] = $description;
                }
                $tableIn_master_values_encoded = base64_encode(serialize($master_vals));
            }
        }

        // Rebuild tableIn_detail_values safely
        $tableIn_detail_values_encoded = '';
        if (!empty($old_registry['tableIn_detail_values'])) {
            $detail_vals = @unserialize(base64_decode($old_registry['tableIn_detail_values']));
            if ($detail_vals && is_array($detail_vals)) {
                // Remove deleted items, and update existing ones. (For Printing.php)
                foreach ($detail_vals as $pid => &$dval) {
                    $pid_str = (string)$pid;
                    if (isset($new_tableIn_detail_values[$pid_str])) {
                        $matched = $new_tableIn_detail_values[$pid_str];
                        $dval['qty'] = $matched['qty'];
                        $dval['subtotal'] = $matched['subtotal'];
                        $dval['harga'] = $matched['harga'];
                        $dval['produk_nama'] = $matched['produk_nama'];
                    } else {
                        $dval['qty'] = 0;
                        $dval['subtotal'] = 0;
                    }
                }
                
                // Add new custom rows to tableIn_detail_values if not exist
                foreach ($new_tableIn_detail_values as $pid => $new_val) {
                    if (!isset($detail_vals[$pid])) {
                        $detail_vals[$pid] = $new_val;
                    }
                }
                $tableIn_detail_values_encoded = base64_encode(serialize($detail_vals));
            }
        }

        // Kalkulasi Jurnal Storno
        // Dihapus: Sistem Everest menggunakan mapping rules di jurnal_index (misal: 'loop' => ['1120' => 'grand_total']).
        // Karena kita sudah memperbarui nilai di $main (seperti grand_total, nett1, ppn),
        // maka posting jurnal otomatis akan membaca nilai yang baru. Kita cukup mempertahankan jurnal_index lama.
        $jurnal_index_baru = isset($old_registry['jurnal_index']) ? $old_registry['jurnal_index'] : '';

        $new_registry = $old_registry;
        unset($new_registry['id']);
        $new_registry['transaksi_id'] = $invoice_id;
        $new_registry['dtime']        = date('Y-m-d H:i:s');
        $new_registry['items5_sum']   = base64_encode(serialize($items5_sum));
        $new_registry['items']        = base64_encode(serialize($items));
        $new_registry['main']         = base64_encode(serialize($main));
        $new_registry['jurnal_index'] = $jurnal_index_baru;
        if (!empty($tableIn_master_values_encoded)) {
            $new_registry['tableIn_master_values'] = $tableIn_master_values_encoded;
        }
        if (!empty($tableIn_detail_values_encoded)) {
            $new_registry['tableIn_detail_values'] = $tableIn_detail_values_encoded;
        }
        if (!empty($old_registry['main_elements'])) {
            $main_elems = @unserialize(base64_decode($old_registry['main_elements']));
            if (is_array($main_elems) && isset($main_elems['noteDetails']) && !empty($description)) {
                $main_elems['noteDetails']['contents'] = array($description);
                $new_registry['main_elements'] = base64_encode(serialize($main_elems));
            }
        }

        $oleh_id = isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : 0;
        $oleh_nama = isset($_SESSION['login']['nama']) ? $_SESSION['login']['nama'] : '';
        
        $history_keterangan = !empty($catatan_amandemen) ? $catatan_amandemen : $description;

        $old_reg_json = json_encode($snapshot); // Save the complete 4-layer snapshot
        $history_data = array(
            'transaksi_id' => $invoice_id,
            'dtime' => date('Y-m-d H:i:s'),
            'oleh_id' => $oleh_id,
            'oleh_nama' => $oleh_nama,
            'keterangan' => $history_keterangan,
            'old_registry_data' => $old_reg_json
        );
        if ($this->db->field_exists('old_registry_bytes', 'transaksi_amandemen_history')) {
            $history_data['old_registry_bytes'] = strlen($old_reg_json);
        }
        $this->db->insert('transaksi_amandemen_history', $history_data);

        $this->db->where('transaksi_id', $invoice_id);
        $this->db->update('transaksi_data_registry', $new_registry);

        // Update Tabel Relasional `transaksi_data` MySQL (Agar Cetakan Invoice Langsung Berubah dan Sesuai Urutan Drag&Drop)
        if (is_array($post_items)) {
            // 1. Ambil semua baris lama yang aktif sebagai template
            $this->db->where('transaksi_id', $invoice_id);
            if ($this->db->field_exists('trash', 'transaksi_data')) {
                $this->db->where('trash', '0');
            }
            $old_td_rows = $this->db->get('transaksi_data')->result_array();
            $old_td_map = array();
            $old_td_map_by_dasar = array();
            foreach ($old_td_rows as $row) {
                // Index ganda: produk_id DAN produk_dasar_id (jika berbeda)
                // Karena form mengirim produk_dasar_id, bukan produk_id
                $pid = (int)$row['produk_id'];
                if (!isset($old_td_map[$pid])) {
                    $old_td_map[$pid] = array();
                }
                $old_td_map[$pid][] = $row;
            }

            // Siapkan base_template untuk fallback (jika transaksi_data sudah kosong)
            // Ambil dari baris pertama yang ada, atau dari snapshot header
            $base_template = array();
            if (!empty($old_td_rows)) {
                $base_template = $old_td_rows[0];
            } else {
                // transaksi_data sudah kosong (akibat amandemen sebelumnya yang rusak)
                // Fallback: bangun template dari header transaksi di snapshot
                $hdr = $snapshot['header'];
                $base_template = array(
                    'transaksi_id' => $invoice_id,
                    'cabang_id' => isset($hdr['cabang_id']) ? $hdr['cabang_id'] : 0,
                    'gudang_id' => isset($hdr['gudang_id']) ? $hdr['gudang_id'] : 0,
                    'gudang_id_tujuan' => isset($hdr['gudang_id_tujuan']) ? $hdr['gudang_id_tujuan'] : 0,
                    'kategori_id' => isset($hdr['kategori_id']) ? $hdr['kategori_id'] : 0,
                    'jenis' => isset($hdr['jenis']) ? $hdr['jenis'] : '',
                    'jenisTr' => isset($hdr['jenisTr']) ? $hdr['jenisTr'] : '',
                    'step_number' => isset($hdr['step_number']) ? $hdr['step_number'] : 0,
                    'nomer' => isset($hdr['nomer']) ? $hdr['nomer'] : '',
                    'pembayaran_sys' => isset($hdr['pembayaran_sys']) ? $hdr['pembayaran_sys'] : '',
                    'status' => isset($hdr['status']) ? $hdr['status'] : '1',
                    'trash' => '0',
                    'link_id' => '0',
                    'next_substep_code' => isset($hdr['next_substep_code']) ? $hdr['next_substep_code'] : '',
                    'sub_step_number' => isset($hdr['sub_step_number']) ? $hdr['sub_step_number'] : 0,
                    'valid_qty' => 1
                );
            }

            // 2. Matikan baris lama (Soft-Delete: trash = 1) agar data original fisik tersimpan utuh di MySQL
            $this->db->where('transaksi_id', $invoice_id);
            if ($this->db->field_exists('trash', 'transaksi_data')) {
                $this->db->update('transaksi_data', array(
                    'trash' => '1',
                    'status' => '0'
                ));
            }

            // 3. Insert ulang berurutan sesuai $post_items dari form (hasil Drag & Drop)
            foreach ($post_items as $itm) {
                $pid = isset($itm['produk_dasar_id']) ? (int)$itm['produk_dasar_id'] : 0;
                $qty = (float)$itm['jml'];
                $harga = (float)$itm['harga'];
                $nama = !empty($itm['nama']) ? $itm['nama'] : '';

                if ($qty > 0 || $pid > 0) {
                    // Coba cari template lama: pertama cek produk_id, kalau tidak ada coba iterasi semua
                    $found_template = null;
                    if ($pid > 0 && isset($old_td_map[$pid]) && count($old_td_map[$pid]) > 0) {
                        $found_template = array_shift($old_td_map[$pid]);
                    } else if ($pid > 0) {
                        // Fallback: cari di semua old_td_map berdasarkan nama produk (case insensitive)
                        foreach ($old_td_map as $map_pid => $map_rows) {
                            if (count($map_rows) > 0) {
                                foreach ($map_rows as $mk => $mr) {
                                    if (strtolower(trim($mr['produk_nama'])) == strtolower(trim($nama))) {
                                        $found_template = $mr;
                                        unset($old_td_map[$map_pid][$mk]);
                                        $old_td_map[$map_pid] = array_values($old_td_map[$map_pid]);
                                        break 2;
                                    }
                                }
                            }
                        }
                    }

                    if ($found_template) {
                        $ins_td = $found_template;
                        unset($ins_td['id']); // Biarkan auto-increment bekerja agar urut
                        $ins_td['produk_ord_jml'] = $qty;
                        $ins_td['produk_ord_hrg'] = $harga;
                        $ins_td['trash'] = '0';
                        $ins_td['status'] = '1';
                        if (!empty($nama)) {
                            $ins_td['produk_nama'] = $nama;
                        }
                        $this->db->insert('transaksi_data', $ins_td);
                    } else {
                        // Insert baris custom / baru
                        if ($qty > 0) {
                            $ins_td = array(
                                'transaksi_id' => $invoice_id,
                                'produk_id' => $pid,
                                'produk_nama' => $nama,
                                'produk_ord_jml' => $qty,
                                'produk_ord_hrg' => $harga,
                                'satuan' => isset($itm['satuan']) ? $itm['satuan'] : 'lot',
                                'dtime' => date('Y-m-d H:i:s'),
                                'oleh_id' => $oleh_id,
                                'oleh_nama' => $oleh_nama,
                                'trash' => '0',
                                'status' => '1'
                            );

                            // Salin field-field wajib dari template agar Printing.php tidak crash
                            // lookupJoined() memfilter: status='1', trash='0', link_id='0'
                            // dan GROUP BY: transaksi_id, next_substep_code
                            $inherit_fields = array(
                                'cabang_id', 'gudang_id', 'gudang_id_tujuan', 'kategori_id',
                                'jenis', 'jenisTr', 'step_number', 'nomer', 'pembayaran_sys',
                                'status', 'trash', 'link_id', 'next_substep_code',
                                'sub_step_number', 'valid_qty'
                            );
                            foreach ($inherit_fields as $ifield) {
                                if (isset($base_template[$ifield]) && !isset($ins_td[$ifield])) {
                                    $ins_td[$ifield] = $base_template[$ifield];
                                }
                            }

                            $this->db->insert('transaksi_data', $ins_td);
                        }
                    }
                }
            }
        }

        // Update Header Transaksi
        $update_trx = array(
            'transaksi_net' => $grandTotalDPP,
            'ppn_nilai' => $ppn_val,
            'transaksi_nilai' => $tagihan_baru,
            'transaksi_bulat' => $tagihan_baru,
            'transaksi_dibayar' => 0, 
            'transaksi_saldo' => $tagihan_baru
        );
        if (!empty($description)) {
            $update_trx['keterangan'] = $description;
        }
        // Tulis Notes Client ke transaksi.keterangan (agar dicetak di Invoice Client)
        // Rebuild indexing_details blob untuk header transaksi (WAJIB untuk Printing.php / lookupJoined)
        $active_td_rows = $this->db->select('id')
            ->where('transaksi_id', $invoice_id)
            ->where('trash', '0')
            ->get('transaksi_data')->result_array();

        if (!empty($active_td_rows)) {
            $indexing_arr = array();
            foreach ($active_td_rows as $tdr) {
                $indexing_arr[] = (string)$tdr['id'];
            }
            $update_trx['indexing_details'] = base64_encode(serialize($indexing_arr));
        }

        $this->db->where('id', $invoice_id);
        $this->db->update('transaksi', $update_trx);

        // Update juga Header Transaksi Induk (Penerimaan Termin 7499) & Rekening Penerimaan A/R (749) agar nilainya 100% sinkron
        if (isset($snapshot['header']['id_master']) && $snapshot['header']['id_master'] > 0) {
            $id_master = (int)$snapshot['header']['id_master'];
            $this->db->group_start();
                $this->db->where('id', $id_master);
                $this->db->or_where('id_master', $id_master);
            $this->db->group_end();
            $this->db->update('transaksi', $update_trx);
        }

        // Reset Antrean Pajak 110r (Prepare e-Faktur) ke status siap input baru & nonaktifkan draft 110e lama
        // HANYA JIKA TIDAK MENYERAP PAJAK ($absorb_tax == false)
        // Jika $absorb_tax == true, e-Faktur resmi DJP tetap utuh dan antrean 110r tidak boleh diaktifkan ulang!
        if (!$absorb_tax) {
            $ref_id = isset($snapshot['header']['reference_id']) ? (int)$snapshot['header']['reference_id'] : 0;
            $id_master_val = isset($snapshot['header']['id_master']) ? (int)$snapshot['header']['id_master'] : 0;
            $tax_targets = array_unique(array_filter(array($invoice_id, $ref_id, $id_master_val)));

            if (!empty($tax_targets)) {
                // Update transaksi_data untuk 110r agar valid_qty = 1 dan harga sesuai DPP baru
                $tax_110r_rows = $this->db->where_in('reference_id', $tax_targets)
                    ->where('jenis', '110r')
                    ->where('trash_4', 0)
                    ->get('transaksi')->result_array();

                foreach ($tax_110r_rows as $t110r) {
                    $t110r_id = (int)$t110r['id'];
                    $this->db->where('transaksi_id', $t110r_id)->update('transaksi_data', array(
                        'valid_qty' => 1,
                        'produk_ord_hrg' => $grandTotalDPP,
                        'trash' => 0
                    ));
                }

                // Bersihkan draft 110e lama yang sudah batal faktur resminya (110)
                $old_110e_rows = $this->db->where_in('reference_id', $tax_targets)
                    ->where('jenis', '110e')
                    ->where('trash_4', 0)
                    ->get('transaksi')->result_array();

                foreach ($old_110e_rows as $oe) {
                    $oe_id = (int)$oe['id'];
                    $this->db->where('id', $oe_id)->update('transaksi', array('trash_4' => 1));
                    $this->db->where('transaksi_id', $oe_id)->update('transaksi_data', array('valid_qty' => 0, 'trash' => 1));
                }
            }
        }

        // Update Tabel `transaksi_payment_source` (Plafon Alokasi Penagihan Proyek & Status Sisa Tagihan)
        // Ini KRITIS agar modul penerimaanprojek membaca sisa tagihan/plafon yang akurat setelah amandemen!
        if ($this->db->table_exists('transaksi_payment_source')) {
            $target_ids = array($invoice_id);
            if (isset($snapshot['header']['id_master']) && $snapshot['header']['id_master'] > 0) {
                $target_ids[] = (int)$snapshot['header']['id_master'];
            }
            if (isset($snapshot['header']['reference_id']) && $snapshot['header']['reference_id'] > 0) {
                $target_ids[] = (int)$snapshot['header']['reference_id'];
            }

            $tps_rows = $this->db->select('id, terbayar, tagihan, tagihan_asal, amandemen_nilai')
                ->where_in('transaksi_id', $target_ids)
                ->get('transaksi_payment_source')->result_array();

            if (!empty($tps_rows)) {
                foreach ($tps_rows as $tps) {
                    $sudah_terbayar = (float)$tps['terbayar'];
                    $sisa_baru = $tagihan_baru - $sudah_terbayar;
                    if ($sisa_baru < 0) $sisa_baru = 0;
                    $ppn_sisa_baru = ($tagihan_baru > 0 && $sisa_baru > 0) ? round($ppn_val * ($sisa_baru / $tagihan_baru), 2) : 0;
                    $lunas_val = ($sisa_baru <= 0 ? 1 : 0);
                    $terbayar_persen = ($tagihan_baru > 0 ? round(($sudah_terbayar / $tagihan_baru) * 100, 2) : 0);
                    $sisa_persen = ($tagihan_baru > 0 ? round(($sisa_baru / $tagihan_baru) * 100, 2) : 0);

                    // Tentukan tagihan_asal (nilai komitmen sebelum amandemen)
                    $orig_tps_tagihan = (float)$tps['tagihan'];
                    $tps_asal = ((float)$tps['tagihan_asal'] > 0) ? (float)$tps['tagihan_asal'] : ($orig_tps_tagihan > 0 ? $orig_tps_tagihan : $tagihan_baru);
                    if ($tps_asal <= 0 && isset($snapshot['header']['transaksi_nilai'])) {
                        $tps_asal = (float)$snapshot['header']['transaksi_nilai'];
                    }
                    $delta_amandemen = $tagihan_baru - $tps_asal;

                    $pay_source_update = array(
                        'tagihan'         => $tagihan_baru,
                        'tagihan_asal'    => $tps_asal,
                        'amandemen_nilai' => $delta_amandemen,
                        'sisa'            => $sisa_baru,
                        'dpp_ppn'         => $grandTotalDPP,
                        'ppn'             => $ppn_val,
                        'ppn_sisa'        => $ppn_sisa_baru,
                        'lunas'           => $lunas_val,
                        'terbayar_persen' => $terbayar_persen,
                        'sisa_persen'     => $sisa_persen
                    );
                    if (!empty($description)) {
                        $pay_source_update['payment_source_keterangan'] = $description;
                    }
                    $this->db->where('id', $tps['id']);
                    $this->db->update('transaksi_payment_source', $pay_source_update);
                }
            } else {
                $fallback_tps = array(
                    'tagihan'         => $tagihan_baru,
                    'tagihan_asal'    => $tagihan_baru,
                    'amandemen_nilai' => 0,
                    'sisa'            => $tagihan_baru,
                    'dpp_ppn'         => $grandTotalDPP,
                    'ppn'             => $ppn_val,
                    'ppn_sisa'        => $ppn_val,
                    'lunas'           => 0,
                    'terbayar_persen' => 0,
                    'sisa_persen'     => 100
                );
                if (!empty($description)) {
                    $fallback_tps['payment_source_keterangan'] = $description;
                }
                $this->db->where_in('transaksi_id', $target_ids);
                $this->db->update('transaksi_payment_source', $fallback_tps);
            }
        }

        // --- MUTASI PENYESUAIAN AKUNTANSI (ISO 9001:2015 & ISO/IEC 27001 AUDIT TRAIL) ---
        $old_tagihan = 0;
        $old_dpp     = 0;
        $old_ppn     = 0;
        $id_master   = isset($snapshot['header']['id_master']) ? (int)$snapshot['header']['id_master'] : 0;
        $invoice_no  = isset($snapshot['header']['nomer']) ? $snapshot['header']['nomer'] : '';

        // Ekstrak nilai asli dari snapshot registry main
        if (isset($old_registry['main'])) {
            $snap_main = @unserialize(base64_decode($old_registry['main']));
            if ($snap_main && is_array($snap_main)) {
                $old_tagihan = isset($snap_main['piutang_dagang']) ? (float)$snap_main['piutang_dagang'] : (isset($snap_main['grand_total']) ? (float)$snap_main['grand_total'] : (isset($snap_main['new_net3']) ? (float)$snap_main['new_net3'] : 0));
                $old_dpp     = isset($snap_main['dpp_ppn']) ? (float)$snap_main['dpp_ppn'] : (isset($snap_main['penjualan']) ? (float)$snap_main['penjualan'] : (isset($snap_main['subtotal']) ? (float)$snap_main['subtotal'] : 0));
                $old_ppn     = isset($snap_main['ppn']) ? (float)$snap_main['ppn'] : (isset($snap_main['ppn_out_bulat']) ? (float)$snap_main['ppn_out_bulat'] : (isset($snap_main['grand_ppn']) ? (float)$snap_main['grand_ppn'] : 0));
            }
        }

        // Fallback ke snapshot jurnal_detail
        if (($old_dpp <= 0 || $old_tagihan <= 0) && !empty($snapshot['jurnal_detail'])) {
            foreach ($snapshot['jurnal_detail'] as $sj) {
                $r_code = isset($sj['rekening']) ? (string)$sj['rekening'] : '';
                $r_deb  = isset($sj['debet']) ? (float)$sj['debet'] : 0;
                $r_krd  = isset($sj['kredit']) ? (float)$sj['kredit'] : 0;
                if ($r_code === '4010' || strpos(strtolower(isset($sj['rekening_nama']) ? $sj['rekening_nama'] : ''), 'penjualan') !== false) {
                    if ($r_krd > 0 && $old_dpp <= 0) $old_dpp = $r_krd;
                } elseif ($r_code === '2030060' || strpos(strtolower(isset($sj['rekening_nama']) ? $sj['rekening_nama'] : ''), 'ppn') !== false) {
                    if ($r_krd > 0 && $old_ppn <= 0) $old_ppn = $r_krd;
                } elseif ($r_code === '1010020010' || strpos(strtolower(isset($sj['rekening_nama']) ? $sj['rekening_nama'] : ''), 'piutang') !== false) {
                    if ($r_deb > 0 && $old_tagihan <= 0) $old_tagihan = $r_deb;
                }
            }
        }

        // Sinkronisasi Tabel Realisasi Kontrak Proyek (`z_transaksi_project_mutasi` & `z_transaksi_project_cache`)
        $project_id = isset($snapshot['header']['project_id']) ? (int)$snapshot['header']['project_id'] : 0;
        if ($project_id <= 0 && isset($snap_main['projectID'])) {
            $project_id = (int)$snap_main['projectID'];
        }
        if ($project_id > 0) {
            // Update juga master nilai kontrak project_produk agar selaras dengan addendum amandemen (Opsi A)
            $this->db->where('id', $project_id);
            $this->db->update('project_produk', array(
                'harga'      => $grandTotalDPP,
                'harga_nppn' => $tagihan_baru
            ));

            // Update SPK payment source (target_jenis 7499) jika ada
            $this->db->where('project_id', $project_id);
            $this->db->where('target_jenis', '7499');
            $this->db->update('transaksi_payment_source', array(
                'tagihan'  => $grandTotalDPP,
                'terbayar' => $grandTotalDPP,
                'sisa'     => 0
            ));

            if ($this->db->table_exists('z_transaksi_project_mutasi')) {
                $delta_dpp = $grandTotalDPP - $old_dpp;
                // 1. Update baris mutasi penarikan proyek
                $this->db->where_in('transaksi_id', $target_ids);
                $this->db->where('rekening', 'project');
                $this->db->update('z_transaksi_project_mutasi', array(
                    'kredit' => $grandTotalDPP,
                    'kredit_akhir' => $grandTotalDPP
                ));

                // 2. Sesuaikan saldo kredit akumulasi termin pada cache proyek
                if ($delta_dpp != 0 && $this->db->table_exists('z_transaksi_project_cache')) {
                    $this->db->set('kredit', 'kredit + (' . (float)$delta_dpp . ')', FALSE);
                    $this->db->where('extern_id', $project_id);
                    $this->db->where('rekening', 'project');
                    $this->db->where('periode', 'forever');
                    $this->db->update('z_transaksi_project_cache');
                }
            }
        }

        // Fallback terakhir ke header transaksi
        if ($old_dpp <= 0 && isset($snapshot['header']['transaksi_net']) && (float)$snapshot['header']['transaksi_net'] > 0) {
            $old_dpp = (float)$snapshot['header']['transaksi_net'];
        }
        if ($old_ppn <= 0 && isset($snapshot['header']['ppn_nilai']) && (float)$snapshot['header']['ppn_nilai'] > 0) {
            $old_ppn = (float)$snapshot['header']['ppn_nilai'];
        }
        if ($old_tagihan <= 0 && isset($snapshot['header']['transaksi_nilai']) && (float)$snapshot['header']['transaksi_nilai'] > 0) {
            $old_tagihan = (float)$snapshot['header']['transaksi_nilai'];
        }
        if ($old_tagihan <= $old_dpp && $old_ppn > 0) {
            $old_tagihan = $old_dpp + $old_ppn;
        }

        if (!empty($post_jurnal_custom) && is_array($post_jurnal_custom)) {
            $this->_processCustomManualJournal($invoice_id, $id_master, $invoice_no, $post_jurnal_custom, $oleh_id, $oleh_nama);
        } else {
            $this->_recordAdjustmentMutation($invoice_id, $id_master, $invoice_no, $old_tagihan, $tagihan_baru, $old_dpp, $grandTotalDPP, $old_ppn, $ppn_val, $history_keterangan, $oleh_id, $oleh_nama, $absorb_tax);
        }

        return $tagihan_baru;
    }

    /**
     * Memproses penginputan jurnal penyesuaian manual pilihan user (Custom COA Override)
     */
    private function _processCustomManualJournal($invoice_id, $id_master, $invoice_no, $post_jurnal_custom, $oleh_id, $oleh_nama) {
        $target_ids = array_unique(array_filter(array((int)$invoice_id, (int)$id_master)));
        if (empty($target_ids)) return;

        $target_trx_id = ($invoice_id > 0) ? $invoice_id : $id_master;
        $now_time = date('Y-m-d H:i:s');
        $tgl = date('d');
        $bln = date('m');
        $thn = date('Y');
        $fulldate = date('Y-m-d');

        $this->db->where('transaksi_id', $invoice_id);
        $count_prev = $this->db->count_all_results('transaksi_amandemen_history');
        $koreksi_num = $count_prev + 1;

        foreach ($post_jurnal_custom as $jitem) {
            $rekening = isset($jitem['rekening']) ? trim($jitem['rekening']) : '';
            $rekening_nama = isset($jitem['rekening_nama']) ? trim($jitem['rekening_nama']) : 'Jurnal Penyesuaian Manual';
            $debet = isset($jitem['debet']) ? (float)$jitem['debet'] : 0;
            $kredit = isset($jitem['kredit']) ? (float)$jitem['kredit'] : 0;

            if (empty($rekening) || ($debet <= 0 && $kredit <= 0)) continue;

            $ins_j = array(
                'transaksi_id'  => $target_trx_id,
                'transaksi_no'  => $invoice_no,
                'jenis'         => '4822a',
                'rekening'      => $rekening,
                'rekening_2'    => $rekening_nama,
                'debet'         => $debet,
                'kredit'        => $kredit,
                'keterangan'    => "[Jurnal Manual Diskon / Adjustment " . $invoice_no . "] " . (isset($jitem['keterangan']) ? $jitem['keterangan'] : ''),
                'dtime'         => $now_time,
                'tgl'           => $tgl,
                'bln'           => $bln,
                'thn'           => $thn,
                'fulldate'      => $fulldate,
                'oleh_id'       => $oleh_id,
                'author'        => $oleh_id
            );

            if ($this->db->field_exists('trash', 'jurnal')) {
                $ins_j['trash'] = 0;
            }
            if ($this->db->field_exists('status', 'jurnal')) {
                $ins_j['status'] = 1;
            }

            $this->_insertJurnalSafe($ins_j);
        }
    }

    /**
     * Membukukan mutasi penyesuaian (koreksi delta) secara resmi ke tabel jurnal, buku besar (__rek_master__*),
     * dan buku pembantu (__rek_pembantu_*) sesuai Standar ISO 9001:2015 Klausul 7.5 dan ISO/IEC 27001 (Audit Trail Utuh)
     */
    private function _recordAdjustmentMutation($invoice_id, $id_master, $invoice_no, $old_tagihan, $new_tagihan, $old_dpp, $new_dpp, $old_ppn, $new_ppn, $history_keterangan, $oleh_id, $oleh_nama, $absorb_tax = false) {
// START OF COMPLETE REPEATED LOGIC
        $delta_tagihan = $new_tagihan - $old_tagihan;
        $delta_dpp     = $new_dpp - $old_dpp;
        $delta_ppn     = $new_ppn - $old_ppn;

        // Jika tidak ada perubahan nominal, tidak perlu mencatat mutasi penyesuaian
        if (abs($delta_tagihan) < 0.01 && abs($delta_dpp) < 0.01 && abs($delta_ppn) < 0.01) {
            return;
        }

        $now_time = date('Y-m-d H:i:s');
        $tgl = date('d');
        $bln = date('m');
        $thn = date('Y');
        $fulldate = date('Y-m-d');

        $this->db->where('transaksi_id', $invoice_id);
        $count_prev = $this->db->count_all_results('transaksi_amandemen_history');
        $koreksi_num = $count_prev + 1;

        // Ambil data header transaksi untuk cabang_id, customer, dan project
        $this->db->select('cabang_id, gudang_id, nomer, customers_id, customers_nama, project_id, project_nama');
        $this->db->where('id', $invoice_id);
        $trx_hdr = $this->db->get('transaksi')->row_array();
        $cabang_id = isset($trx_hdr['cabang_id']) ? (int)$trx_hdr['cabang_id'] : (int)my_cabang_id();
        $termin_no = isset($trx_hdr['nomer']) ? $trx_hdr['nomer'] : '';
        $customer_id = isset($trx_hdr['customers_id']) ? (int)$trx_hdr['customers_id'] : 0;
        $customer_nama = isset($trx_hdr['customers_nama']) ? $trx_hdr['customers_nama'] : '';
        $project_id = isset($trx_hdr['project_id']) ? (int)$trx_hdr['project_id'] : 0;
        $project_nama = isset($trx_hdr['project_nama']) ? $trx_hdr['project_nama'] : '';

        // Fallback jika customers_id / project_id di invoice kosong, ambil dari master transaksi / referensi
        if (($customer_id <= 0 || $project_id <= 0) && $id_master > 0) {
            $this->db->select('customers_id, customers_nama, project_id, project_nama');
            $this->db->where('id', $id_master);
            $mst_row = $this->db->get('transaksi')->row_array();
            if ($mst_row) {
                if ($customer_id <= 0 && !empty($mst_row['customers_id'])) {
                    $customer_id = (int)$mst_row['customers_id'];
                    $customer_nama = $mst_row['customers_nama'];
                }
                if ($project_id <= 0 && !empty($mst_row['project_id'])) {
                    $project_id = (int)$mst_row['project_id'];
                    $project_nama = $mst_row['project_nama'];
                }
            }
        }

        // Fallback pencarian project & customer dari transaksi_data_registry (main)
        if ($project_id <= 0 || $customer_id <= 0) {
            $tdr_chk_ids = array($invoice_id);
            if ($id_master > 0) {
                $tdr_chk_ids[] = (int)$id_master;
            }
            $this->db->select('transaksi_id, main');
            $this->db->where_in('transaksi_id', $tdr_chk_ids);
            $tdr_chk_rows = $this->db->get('transaksi_data_registry')->result_array();
            if ($tdr_chk_rows) {
                foreach ($tdr_chk_rows as $tdr_r) {
                    if (!empty($tdr_r['main'])) {
                        $m_data = @unserialize(base64_decode($tdr_r['main']));
                        if (is_array($m_data)) {
                            if ($project_id <= 0) {
                                if (isset($m_data['projectID']) && (int)$m_data['projectID'] > 0) {
                                    $project_id = (int)$m_data['projectID'];
                                } elseif (isset($m_data['project_id']) && (int)$m_data['project_id'] > 0) {
                                    $project_id = (int)$m_data['project_id'];
                                }
                            }
                            if (empty($project_nama)) {
                                if (!empty($m_data['projectName'])) {
                                    $project_nama = $m_data['projectName'];
                                } elseif (!empty($m_data['project_nama'])) {
                                    $project_nama = $m_data['project_nama'];
                                }
                            }
                            if ($customer_id <= 0) {
                                if (isset($m_data['customerID']) && (int)$m_data['customerID'] > 0) {
                                    $customer_id = (int)$m_data['customerID'];
                                } elseif (isset($m_data['pihakID']) && (int)$m_data['pihakID'] > 0) {
                                    $customer_id = (int)$m_data['pihakID'];
                                }
                            }
                            if (empty($customer_nama)) {
                                if (!empty($m_data['customerName'])) {
                                    $customer_nama = $m_data['customerName'];
                                } elseif (!empty($m_data['pihakName'])) {
                                    $customer_nama = $m_data['pihakName'];
                                }
                            }
                        }
                    }
                    if ($project_id > 0 && $customer_id > 0) {
                        break;
                    }
                }
            }
        }

        $ket_koreksi = "[Diskon / Adjustment " . $invoice_no . "] Koreksi penyesuaian nilai tagihan";
        if (!empty($history_keterangan)) {
            $ket_koreksi .= " (" . $history_keterangan . ")";
        }

        $this->load->helper("he_angka");
        $this->load->helper("he_accounting");
        $this->load->helper("he_mass_table");

        $static_base = array(
            'cabang_id'    => $cabang_id,
            'jenis'        => '4822a',
            'transaksi_id' => $invoice_id,
            'transaksi_no' => $invoice_no,
            'fulldate'     => $fulldate,
            'dtime'        => $now_time,
            'keterangan'   => $ket_koreksi,
            'author'       => $oleh_id
        );

        // Susun parameter loop akun rekening sesuai perilaku detectRekPosition:
        // 1010020010 (Piutang Dagang): Normal Debet. Nilai negatif (-333.000) otomatis menghasilkan Kredit.
        // 4010 (Penjualan): Normal Kredit. Nilai negatif (-300.000) otomatis menghasilkan Debet.
        // 6010 (Biaya Usaha): Normal Debet. Beban pajak bertambah diakui di DEBET (+33.000).
        // 2030060 (Hutang PPN): Normal Kredit. Nilai negatif (-33.000) otomatis menghasilkan Debet.
        // 4030 (Penjualan Belum Realisasi): Normal Kredit. Kontra diakui Kredit (-333.000).
        // 1010070030 (Piutang Belum Realisasi Project): Normal Debet. Kontra diakui Debet (+333.000).
        $loop_rek = array();
        if (abs($delta_tagihan) > 0.0001) {
            $loop_rek['1010020010'] = $delta_tagihan;
        }
        if (abs($delta_dpp) > 0.0001) {
            $loop_rek['4010'] = $delta_dpp;
        }
        if ($absorb_tax) {
            if (abs($delta_ppn) > 0.0001) {
                $loop_rek['6010'] = abs($delta_ppn);
            }
        } else {
            if (abs($delta_ppn) > 0.0001) {
                $loop_rek['2030060'] = $delta_ppn;
            }
        }
        if (abs($delta_tagihan) > 0.0001) {
            $loop_rek['4030'] = -$delta_tagihan;
            $loop_rek['1010070030'] = -$delta_tagihan;
        }

        // =========================================================================
        // 1. Eksekusi ComJurnal (Pencatatan Ayat Jurnal Berimbang)
        // =========================================================================
        if (sizeof($loop_rek) > 0) {
            $this->load->model('Coms/ComJurnal');
            $com_jurnal = new ComJurnal();
            $params_jurnal = array(
                'loop'   => $loop_rek,
                'static' => $static_base
            );
            $com_jurnal->pair($params_jurnal);
            $com_jurnal->exec();

            // Berikan penanda koreksi_number pada baris jurnal yang baru dimasukkan
            $this->db->where('transaksi_id', $invoice_id);
            $this->db->where('jenis', '4822a');
            $this->db->where('dtime', $now_time);
            $this->db->update('jurnal', array('koreksi_number' => $koreksi_num));
        }

        // =========================================================================
        // 2. Eksekusi ComRekening (Update _rek_master_cache & Mutasi __rek_master_*)
        // =========================================================================
        if (sizeof($loop_rek) > 0) {
            if (!class_exists('CustomCounter')) {
                $this->load->model('CustomCounter');
                if (!class_exists('CustomCounter') && defined('APPPATH')) {
                    @include_once APPPATH . 'models/CustomCounter.php';
                }
            }
            $this->load->model('Coms/ComRekening');
            $com_rek = new ComRekening();
            $params_rek = array(
                'loop'   => $loop_rek,
                'static' => $static_base
            );
            $com_rek->pair($params_rek);
            $com_rek->exec();
        }

        // =========================================================================
        // 3. Eksekusi ComRekeningPembantuCustomer (Update _rek_pembantu_customer_cache)
        // =========================================================================
        if ($customer_id > 0) {
            $loop_cust = array();
            if (abs($delta_tagihan) > 0.0001) {
                $loop_cust['1010020010'] = $delta_tagihan;
            }
            if (!$absorb_tax && abs($delta_ppn) > 0.0001) {
                $loop_cust['2030060'] = $delta_ppn;
            }
            if (sizeof($loop_cust) > 0) {
                $this->load->model('Coms/ComRekeningPembantuCustomer');
                $com_cust = new ComRekeningPembantuCustomer();
                $static_cust = array_merge($static_base, array(
                    'extern_id'   => $customer_id,
                    'extern_nama' => $customer_nama
                ));
                $com_cust->pair(array('loop' => $loop_cust, 'static' => $static_cust));
                $com_cust->exec();
            }
        }

        // =========================================================================
        // 4. Eksekusi ComRekeningPembantuCustomerProject (_rek_pembantu_customer_project_cache)
        // Sesuai coTransaksiCore 7499: loop 1010070030 => -delta_tagihan, extern_id = pihakID, extern2_id = projectID
        // =========================================================================
        if ($customer_id > 0 && $project_id > 0 && abs($delta_tagihan) > 0.0001) {
            $this->load->model('Coms/ComRekeningPembantuCustomerProject');
            $com_cproj = new ComRekeningPembantuCustomerProject();
            $static_cproj = array_merge($static_base, array(
                'extern_id'    => $customer_id,
                'extern_nama'  => $customer_nama,
                'extern2_id'   => $project_id,
                'extern2_nama' => $project_nama,
                'extern3_id'   => '0',
                'extern3_nama' => ''
            ));
            $com_cproj->pair(array(
                'loop'   => array('1010070030' => -$delta_tagihan),
                'static' => $static_cproj
            ));
            $com_cproj->exec();
        }

        // =========================================================================
        // 5. Eksekusi ComRekeningPembantuPenjualan (Update _rek_pembantu_penjualan_cache)
        // Sesuai coTransaksiCore 7499:
        // - 4030: extern_id = '4030030' (penjualan kontijensi project)
        // - 4010: extern_id = '4010030' (penjualan project)
        // =========================================================================
        $this->load->model('Coms/ComRekeningPembantuPenjualan');

        // 5a. Rekening 4030 (Penjualan Kontijensi Project)
        if (abs($delta_tagihan) > 0.0001) {
            $com_penj_4030 = new ComRekeningPembantuPenjualan();
            $static_penj_4030 = array_merge($static_base, array(
                'extern_id'    => '4030030',
                'extern_nama'  => 'penjualan kontijensi project',
                'extern2_id'   => '0',
                'extern2_nama' => '',
                'extern4_id'   => $customer_id,
                'extern4_nama' => $customer_nama
            ));
            $com_penj_4030->pair(array('loop' => array('4030' => -$delta_tagihan), 'static' => $static_penj_4030));
            $com_penj_4030->exec();
        }

        // 5b. Rekening 4010 (Penjualan Project)
        if (abs($delta_dpp) > 0.0001) {
            $com_penj_4010 = new ComRekeningPembantuPenjualan();
            $static_penj_4010 = array_merge($static_base, array(
                'extern_id'    => '4010030',
                'extern_nama'  => 'penjualan project',
                'extern2_id'   => '0',
                'extern2_nama' => '',
                'extern4_id'   => $customer_id,
                'extern4_nama' => $customer_nama
            ));
            $com_penj_4010->pair(array('loop' => array('4010' => $delta_dpp), 'static' => $static_penj_4010));
            $com_penj_4010->exec();
        }

        // =========================================================================
        // 6. Eksekusi ComRekeningPembantuPenjualanProject (_rek_pembantu_penjualan_project_cache)
        // Sesuai coTransaksiCore 7499:
        // - 4030: extern_id = projectID, extern2_id = '4030030'
        // - 4010: extern_id = projectID, extern2_id = '4010030'
        // =========================================================================
        if ($project_id > 0) {
            $this->load->model('Coms/ComRekeningPembantuPenjualanProject');

            // 6a. Rekening 4030 (Penjualan Kontijensi Project per Project)
            if (abs($delta_tagihan) > 0.0001) {
                $com_pproj_4030 = new ComRekeningPembantuPenjualanProject();
                $static_pproj_4030 = array_merge($static_base, array(
                    'extern_id'    => $project_id,
                    'extern_nama'  => $project_nama,
                    'extern2_id'   => '4030030',
                    'extern2_nama' => 'penjualan kontijensi project',
                    'extern3_id'   => '0',
                    'extern3_nama' => '',
                    'extern4_id'   => $customer_id,
                    'extern4_nama' => $customer_nama
                ));
                $com_pproj_4030->pair(array('loop' => array('4030' => -$delta_tagihan), 'static' => $static_pproj_4030));
                $com_pproj_4030->exec();
            }

            // 6b. Rekening 4010 (Penjualan Project per Project)
            if (abs($delta_dpp) > 0.0001) {
                $com_pproj_4010 = new ComRekeningPembantuPenjualanProject();
                $static_pproj_4010 = array_merge($static_base, array(
                    'extern_id'    => $project_id,
                    'extern_nama'  => $project_nama,
                    'extern2_id'   => '4010030',
                    'extern2_nama' => 'penjualan project',
                    'extern3_id'   => '0',
                    'extern3_nama' => '',
                    'extern4_id'   => $customer_id,
                    'extern4_nama' => $customer_nama
                ));
                $com_pproj_4010->pair(array('loop' => array('4010' => $delta_dpp), 'static' => $static_pproj_4010));
                $com_pproj_4010->exec();
            }
        }

        // =========================================================================
        // 7. Eksekusi ComRekeningPembantuBiayaUsaha jika PPN Diserap (6010 / 601000036)
        // =========================================================================
        if ($absorb_tax && abs($delta_ppn) > 0.0001) {
            $this->load->model('Coms/ComRekeningPembantuBiayaUsaha');
            $com_biaya = new ComRekeningPembantuBiayaUsaha();
            $static_biaya = array_merge($static_base, array(
                'extern_id'   => '36',
                'extern_nama' => 'beban penjualan lainnya',
                'rek_id'      => '0360000000'
            ));
            $com_biaya->pair(array(
                'loop'   => array('6010' => abs($delta_ppn)),
                'static' => $static_biaya
            ));
            $com_biaya->exec();
        }
// END OF COMPLETE REPEATED LOGIC
    }

    /**
     * Helper sinkronisasi saldo cache agregat (_rek_master_cache & _rek_pembantu_*_cache)
     * untuk amandemen invoice yang sudah tersimpan di mutasi
     */
    public function syncCacheForInvoiceAmendment($invoice_id) {
        $invoice_id = (int)$invoice_id;
        $this->db->select('id, nomer, cabang_id, customers_id, customers_nama');
        $this->db->where('id', $invoice_id);
        $inv = $this->db->get('transaksi')->row_array();
        if (!$inv) return false;

        $cabang_id = (int)$inv['cabang_id'];
        $cust_id   = (int)$inv['customers_id'];

        // 1. Cek saldo terakhir di mutasi __rek_master__1010020010
        if ($this->db->table_exists('__rek_master__1010020010')) {
            $this->db->where('cabang_id', $cabang_id);
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $last_m = $this->db->get('__rek_master__1010020010')->row_array();
            if ($last_m && isset($last_m['debet_akhir']) && (float)$last_m['debet_akhir'] > 0) {
                $target_debet = (float)$last_m['debet_akhir'];
                $target_dtime = $last_m['dtime'];

                // Update _rek_master_cache untuk rekening 1010020010 di cabang ini
                $this->db->where('rekening', '1010020010');
                $this->db->where('cabang_id', $cabang_id);
                $this->db->where_in('periode', array('forever', 'tahunan', 'bulanan', 'harian'));
                if (isset($last_m['fulldate'])) {
                    $this->db->group_start();
                        $this->db->where('periode !=', 'harian');
                        $this->db->or_where('fulldate', $last_m['fulldate']);
                    $this->db->group_end();
                }
                $this->db->update('_rek_master_cache', array(
                    'debet' => $target_debet,
                    'kredit' => 0,
                    'dtime' => $target_dtime
                ));
            }
        }

        // 2. Cek saldo terakhir di mutasi __rek_pembantu_customer__1010020010 untuk customer ini
        if ($cust_id > 0 && $this->db->table_exists('__rek_pembantu_customer__1010020010')) {
            $this->db->where('extern_id', $cust_id);
            $this->db->where('cabang_id', $cabang_id);
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $last_c = $this->db->get('__rek_pembantu_customer__1010020010')->row_array();
            if ($last_c && isset($last_c['debet_akhir'])) {
                $target_c_debet = (float)$last_c['debet_akhir'];
                $target_c_dtime = $last_c['dtime'];

                $this->db->where('rekening', '1010020010');
                $this->db->where('extern_id', $cust_id);
                $this->db->where('cabang_id', $cabang_id);
                $this->db->where_in('periode', array('forever', 'tahunan', 'bulanan', 'harian'));
                $this->db->update('_rek_pembantu_customer_cache', array(
                    'debet' => $target_c_debet,
                    'kredit' => 0,
                    'dtime' => $target_c_dtime
                ));
            }
        }

        return true;
    }

    /**
     * Helper aman untuk insert data ke tabel fisik buku besar/pembantu dengan penyaringan kolom otomatis
     */
    private function _insertTableSafe($table, $data) {
        if (empty($table) || empty($data) || !is_array($data)) return false;

        // Auto-create tabel master buku besar jika belum ada
        if (!$this->db->table_exists($table)) {
            if (strpos($table, '__rek_master__') === 0 && $this->db->table_exists('__rek_master__4010')) {
                $this->db->query("CREATE TABLE IF NOT EXISTS `{$table}` LIKE `__rek_master__4010`");
            }
        }
        if (!$this->db->table_exists($table)) return false;

        $fields = $this->db->list_fields($table);
        $clean_data = array();
        foreach ($data as $key => $val) {
            if (in_array($key, $fields)) {
                $clean_data[$key] = $val;
            }
        }
        return $this->db->insert($table, $clean_data);
    }

    /**
     * Helper aman untuk insert data ke tabel `jurnal` dengan penyaringan kolom otomatis
     */
    private function _insertJurnalSafe($data) {
        if (empty($data) || !is_array($data)) return false;

        $fields = $this->db->list_fields('jurnal');
        $clean_data = array();
        foreach ($data as $key => $val) {
            if (in_array($key, $fields)) {
                $clean_data[$key] = $val;
            }
        }
        return $this->db->insert('jurnal', $clean_data);
    }

    /**
     * Memulihkan (Rollback / Restore) invoice ke versi snapshot historis tertentu
     */
    public function rollbackToHistory($invoice_id, $history_id) {
        $invoice_id = (int)$invoice_id;
        $history_id = (int)$history_id;

        $this->db->where('id', $history_id);
        $this->db->where('transaksi_id', $invoice_id);
        $his_row = $this->db->get('transaksi_amandemen_history')->row_array();

        if (!$his_row || empty($his_row['old_registry_data'])) {
            return false;
        }

        $old_registry = json_decode($his_row['old_registry_data'], true);
        if (!$old_registry) return false;

        // Mendukung kompatibilitas backward: Jika snapshot menggunakan struktur baru 4-layer
        if (isset($old_registry['registry'])) {
            $old_registry = $old_registry['registry'];
        }

        $old_items5_sum = @unserialize(base64_decode($old_registry['items5_sum']));
        if (!is_array($old_items5_sum)) return false;

        // Ambil snapshot saat ini sebelum pemulihan
        $current_snapshot = $this->createAuditSnapshot($invoice_id);

        $catatan_rollback = "[ROLLBACK RESTORE] Dipulihkan ke versi histori #" . $his_row['id'] . " (Snapshot: " . $his_row['dtime'] . ")";
        
        return $this->processAmandemenJSON($invoice_id, $old_items5_sum, $current_snapshot, $his_row['keterangan'], $catatan_rollback);
    }

    /**
     * Cari produk yang digunakan oleh gudang_wo di tabel stock_locker
     */
    public function getLockerStockForGudangWo($gudang_wo, $project_id = 0, $state_filter = 'active')
    {
        $result = array();
        if (empty($gudang_wo)) {
            return $result;
        }

        $tables = array('stock_locker_work_oder', 'stock_locker_supplies', 'stock_locker');

        foreach ($tables as $table) {
            if (!$this->db->table_exists($table)) {
                continue;
            }

            $fields = $this->db->list_fields($table);
            
            $this->db->select('*');
            $this->db->from($table);
            
            // Gudang WO ID / Gudang ID check
            if (in_array('gudang_wo', $fields)) {
                $this->db->where("gudang_wo = '$gudang_wo'");
            } elseif (in_array('gudang_id', $fields)) {
                $this->db->where("gudang_id = '$gudang_wo'");
            }

            if ($project_id > 0 && in_array('project_id', $fields)) {
                $this->db->where('project_id', $project_id);
            }

            if (!empty($state_filter) && in_array('state', $fields)) {
                $this->db->where('state', $state_filter);
            }

            if (in_array('trash', $fields)) {
                $this->db->where('trash', 0);
            }

            if (in_array('jumlah', $fields)) {
                $this->db->where('jumlah >', 0);
            }

            $query = $this->db->get();
            if ($query && $query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $row['_source_table'] = $table;
                    $result[] = $row;
                }
            }
        }

        return $result;
    }
}
