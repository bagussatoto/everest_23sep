<?php
// START OF COMPLETE REPEATED LOGIC
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller _tray_enchanced
 * Refactored to delegate logic to he_tray_helper.
 */
class _tray extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");
        $this->load->helper("he_tray"); // Load the new helper
    }

    public function index()
    {
        if (!isset($this->session->login['id'])) {
            return;
        }

        // Fetch UI Configs
        $this->config->load('heTransaksi_ui');
        $masterConfigUi = $this->config->item('heTransaksi_ui');
        $configUiAllModul = loadConfigUiModul(true, true);

        // Avoid recording for demo environment (improved proxy check)
        $is_demo = in_array($this->input->server('HTTP_HOST'), array(
//            'demo.sim-project.com',
//            'demo.sim-project.test',
//            'demo.sim-distribusi.com'
        ));

        if (!$is_demo) {
            tray_record_active_ip();
        }

        $start = microtime(true);
        $idleData      = tray_check_idle_time();
        $t1 = microtime(true);
        $transaksiData = tray_calculate_transaksi($configUiAllModul);
        $t2 = microtime(true);
        $proposalData  = tray_calculate_proposals();
        $t3 = microtime(true);
        $rekeningData  = tray_calculate_rekening();
        $t4 = microtime(true);

        $log = "Profiling _tray.php:\n";
        $log .= "idleData: " . ($t1 - $start) . " s\n";
        $log .= "transaksiData: " . ($t2 - $t1) . " s\n";
        $log .= "proposalData: " . ($t3 - $t2) . " s\n";
        $log .= "rekeningData: " . ($t4 - $t3) . " s\n";
        file_put_contents('/var/www/everest/logs/profile.txt', $log, FILE_APPEND);

        $viewData = array(
            'idleData'       => $idleData,
            'transaksiData'  => $transaksiData,
            'proposalData'   => $proposalData,
            'rekeningData'   => $rekeningData,
            'masterConfigUi' => $masterConfigUi
        );

        $this->load->view('components/js_tray', $viewData);
    }
}
// END OF COMPLETE REPEATED LOGIC
