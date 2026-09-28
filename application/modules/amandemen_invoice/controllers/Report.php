<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Laporan Audit Khusus untuk Amandemen Invoice (Anti-Fraud Report)
 * COMPLIANCE: ISO 9001 (Audit Trail)
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "modules/amandemen_invoice/controllers/Modul_Controller.php";

class Report extends Modul_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $query = "
            SELECT r.*, t.nomer as invoice_no, t.dtime as invoice_dtime, t.total_rp, e.person_nama as user_name
            FROM transaksi_data_registry r
            LEFT JOIN transaksi t ON r.transaksi_id = t.id
            LEFT JOIN per_employee e ON r.oleh_id = e.id
            ORDER BY r.dtime DESC
        ";
        $data['riwayat'] = $this->db->query($query)->result_array();
        
        $this->load->view('v_amandemen_report', $data);
    }
}
