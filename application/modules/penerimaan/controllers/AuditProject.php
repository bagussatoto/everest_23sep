<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Controller untuk menampilkan laporan audit kesesuaian saldo DP Project vs GL 2010050 di Web UI.
 * COMPLIANCE: ISO 9001 (Audit Quality) & ISO 27001 (RBAC & Authentication Guard)
 * LOG_EXPIRE: 2026-11-05
 * [/AGENT_LOG]
 */
class AuditProject extends Modul_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Coms/ComAuditArProjectDp");
    }

    /**
     * [AGENT_LOG]
     * ROLE      : Software Engineer Agent
     * PURPOSE   : Menampilkan tabel hasil audit 36 project secara interaktif.
     * COMPLIANCE: PHP 5.6 Compatible
     * LOG_EXPIRE: 2026-11-05
     * [/AGENT_LOG]
     */
    public function index() {
        $cabang_filter = isset($_GET['cabang_id']) ? (int)$_GET['cabang_id'] : null;
        $audit_data = $this->ComAuditArProjectDp->get_audit_report($cabang_filter);

        $data = array(
            'title'      => 'Laporan Audit Saldo DP Project vs GL 2010050',
            'audit_data' => $audit_data
        );

        $this->load->view('audit_project_view', $data);
    }
}