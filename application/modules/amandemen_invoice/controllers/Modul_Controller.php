<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Base controller untuk modul amandemen invoice, meload config, helper he_angka, model CustomCounter & session.
 * COMPLIANCE: Standar HMVC CI3 ERP
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * KILL SWITCH (DEVELOPER LEVEL)
 * Set ke FALSE untuk mematikan seluruh modul Amandemen Invoice demi keamanan.
 * Hanya ubah ke TRUE jika client request dan sudah menyetujui resiko operasional.
 */
define('DEV_ENABLE_AMANDEMEN_INVOICE', true);

class Modul_Controller extends MX_Controller {

    public function __construct() {
        parent::__construct();

        // Load helper he_angka & model CustomCounter untuk seluruh sub-controller modul amandemen invoice
        $this->load->helper('he_angka');
        $this->load->model('CustomCounter');

        if (DEV_ENABLE_AMANDEMEN_INVOICE !== true) {
            show_error('Modul Amandemen Invoice sedang dikunci oleh Sistem. Hubungi pihak pengembang (Vendor) untuk membuka otorisasi fitur ini.', 403, 'Akses Diblokir (Developer Lock)');
        }

// START OF COMPLETE REPEATED LOGIC
        // Validasi login (Strict Guard)
        if (!isset($_SESSION['login']['id']) || empty($_SESSION['login']['id'])) {
            $this->load->helper('he_misc');
            if (function_exists('gotoLogin')) {
                gotoLogin();
            } else {
                redirect(base_url('auth/Login'));
            }
        }

        // [COMPLIANCE]: ISO 9001 Fraud Prevention - Otorisasi Amandemen (Gembok 1)
        // Hanya membership level manajerial/keuangan yang diizinkan mengakses Amandemen.
        $allowed_roles = array('c_owner', 'c_finance', 'o_finance', 'o_project_spv', 'o_project_mgr');
        $user_memberships = isset($_SESSION['login']['membership']) ? $_SESSION['login']['membership'] : array();
        
        $is_authorized = false;
        if (is_array($user_memberships)) {
            foreach ($allowed_roles as $role) {
                if (in_array($role, $user_memberships)) {
                    $is_authorized = true;
                    break;
                }
            }
        }
        
        if (!$is_authorized) {
            show_error('Akses Ditolak: Modul Amandemen Invoice (Clean Invoice) hanya dapat diakses oleh user dengan tingkat otorisasi Manajerial atau Keuangan (Supervisor/Manager/Owner).', 403, 'Akses Diblokir (Otorisasi Tidak Mencukupi)');
        }
// END OF COMPLETE REPEATED LOGIC

        // Load 4 config wajib
        $this->load->config("coTransaksiCore");
        $this->load->config("coTransaksiUi");
        $this->load->config("coTransaksiLayout");
        $this->load->config("coTransaksiValues");
    }

}
