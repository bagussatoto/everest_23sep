<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Controller untuk Jembatan Khusus SPK/RAB (Selector Modal)
 * COMPLIANCE: Strict Source, Strict QC Status, Strict Quota
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "modules/amandemen_invoice/controllers/Modul_Controller.php";

class Selector extends Modul_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function spk_items($project_id, $current_invoice_id) {
        $project_id = (int)$project_id;
        $current_invoice_id = (int)$current_invoice_id;

        if ($project_id <= 0) {
            show_error('Invalid Project ID');
        }

        // 1. Ambil daftar SPK unik yang sudah lulus QC
        $this->db->select('p.no_spk, MAX(p.invoiced) as invoiced, COUNT(s.id) as total_items, SUM(s.jml) as total_qty, SUM(s.jml * s.harga) as total_nilai');
        $this->db->from('project_tasklist p');
        $this->db->join('project_sub_tasklist_komposisi s', 'p.no_spk = s.no_spk', 'inner');
        $this->db->where('p.produk_id', $project_id);
        $this->db->where('p.progress_id', 3); // Lulus QC (SPK Level)
        $this->db->where('p.trash', 0);
        $this->db->where('s.trash', 0);
        $this->db->where('s.progress_id !=', 3); // Exclude validation QC items
        $this->db->where_in('s.jenis', array('produk', 'supplies', 'biaya'));
        $this->db->group_by('p.no_spk');
        $spk_list = $this->db->get()->result_array();

        $data['spk_list'] = $spk_list;
        $data['project_id'] = $project_id;
        $data['current_invoice_id'] = $current_invoice_id;

        $this->load->view('v_amandemen_selector', $data);
    }

    public function get_spk_items_ajax($project_id, $current_invoice_id) {
        @ini_set('display_errors', 0);
        error_reporting(0);

        $no_spk = $this->input->post('no_spk');
        if (empty($no_spk)) {
            header('Content-Type: application/json');
            echo json_encode(array('status' => false, 'message' => 'Nomor SPK wajib diisi'));
            return;
        }

        $project_id = (int)$project_id;
        $current_invoice_id = (int)$current_invoice_id;

        $valid_items = array();
        $spk_tasklist = null;
        $locker_stocks = array();

        try {
            // 1. Ambil info SPK & Gudang WO dari project_tasklist
            $spk_tasklist = $this->db->get_where('project_tasklist', array('no_spk' => $no_spk, 'trash' => 0))->row_array();
            $gudang_wo = ($spk_tasklist && isset($spk_tasklist['gudang_wo'])) ? $spk_tasklist['gudang_wo'] : '';

            // 2. Query stock_locker aktif untuk gudang_wo ini
            $active_stock_map = array();
            if (!empty($gudang_wo)) {
                $this->load->model('Coms/ComAmandemenInvoice');
                $locker_stocks = $this->ComAmandemenInvoice->getLockerStockForGudangWo($gudang_wo, $project_id, 'active');
                if (!empty($locker_stocks)) {
                    foreach ($locker_stocks as $stk) {
                        $stk_state = isset($stk['state']) ? strtolower(trim($stk['state'])) : '';
                        if ($stk_state === 'active') {
                            $pid = (int)(isset($stk['produk_id']) ? $stk['produk_id'] : 0);
                            $pd_id = (int)(isset($stk['produk_dasar_id']) ? $stk['produk_dasar_id'] : $pid);
                            $jml = (float)(isset($stk['jumlah']) ? $stk['jumlah'] : 0);
                            if ($pid > 0) {
                                $active_stock_map[$pid] = isset($active_stock_map[$pid]) ? ($active_stock_map[$pid] + $jml) : $jml;
                            }
                            if ($pd_id > 0 && $pd_id != $pid) {
                                $active_stock_map[$pd_id] = isset($active_stock_map[$pd_id]) ? ($active_stock_map[$pd_id] + $jml) : $jml;
                            }
                        }
                    }
                }
            }

            // 3. Ambil semua item komposisi dalam SPK ini
            $this->db->select('MAX(s.id) as id, s.produk_dasar_id, s.produk_dasar_nama as nama, s.satuan, s.jenis, SUM(s.jml) as jml, s.harga');
            $this->db->from('project_sub_tasklist_komposisi s');
            $this->db->where('s.no_spk', $no_spk);
            $this->db->where('s.trash', 0);
            if ($this->db->field_exists('progress_id', 'project_sub_tasklist_komposisi')) {
                $this->db->where('s.progress_id !=', 3); // Exclude validation QC items
            }
            $this->db->where_in('s.jenis', array('produk', 'supplies', 'biaya'));
            $this->db->group_by(array('s.produk_dasar_id', 's.produk_dasar_nama', 's.satuan', 's.jenis', 's.harga'));
            
            $res = $this->db->get();
            if ($res && $res->num_rows() > 0) {
                $spk_items = $res->result_array();
                foreach ($spk_items as $item) {
                    $total_qty = (float)$item['jml'];
                    $pd_id = (int)$item['produk_dasar_id'];
                    $stok_aktif = 0;
                    if ($pd_id > 0 && isset($active_stock_map[$pd_id])) {
                        $stok_aktif = $active_stock_map[$pd_id];
                    }

                    $item['no_spk'] = $no_spk;
                    $item['sisa_qty'] = $total_qty;
                    $item['billed_qty'] = 0;
                    $item['stok_aktif'] = $stok_aktif;
                    $valid_items[] = $item;
                }
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array('status' => false, 'message' => $e->getMessage()));
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => true, 
            'items' => $valid_items,
            'spk_info' => $spk_tasklist,
            'locker_stocks' => $locker_stocks
        ));
    }
}
