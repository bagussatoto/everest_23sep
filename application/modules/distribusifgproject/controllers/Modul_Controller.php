<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_Controller extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* ----------------------------------------------------------------------------------
         * validasi session bila tidak ada dipaksa ke halaman login
         * ----------------------------------------------------------------------------------*/
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);

        /* ----------------------------------------------------------------------------------
         * loader dari masing-masing modul
         * ----------------------------------------------------------------------------------*/
        $this->jenisTr = $tmpJenis = $this->uri->segment(4);
        $this->cCode = $cCode = "_TR_" . $this->jenisTr;
        $this->modul = $modul = $this->uri->segment(1);
        $this->cabangId = $this->placeId = my_cabang_id();
        $this->dates = dtimeNow('y-m-d');

        /* ----------------------------------------------------------------------------------
         * MODUL_### dibentuk di he_url_helper
         * ---------------------------------------------------------------------------------*/
        $this->modulPath = $modulPath = MODUL_PATH;
        // cekHitam($this->modulPath);
        /* ---------------------------------------------------------------------------------
         * untuk ngeload config pada modul
         * ---------------------------------------------------------------------------------*/
        $this->configPath = $configPath = MODUL_CONFIG_PATH;
        // cekHitam($this->configPath);
        $this->load->config($configPath . "coTransaksiUi");
        $this->configUi = $this->config->item("coTransaksiUi");


        $this->load->config($configPath . "coTransaksiCore");
        $this->configCore = $this->config->item("coTransaksiCore");

        $this->load->config($configPath . "coTransaksiLayout");
        $this->configLayout = $this->config->item("coTransaksiLayout");
        $this->load->config($configPath . "coTransaksiValues");
        $this->configValues = $this->config->item("coTransaksiValues");

        if (isset($this->jenisTr)) {
            // $this->configUiJenis = $this->configUi[$this->jenisTr];
            $this->configUiJenis = isset($this->configUi[$this->jenisTr]) ? $this->configUi[$this->jenisTr] : cekOrange($this->jenisTr . " belum ada di coTransaksiUi di modul " . $this->modul);
            $this->configCoreJenis = isset($this->configCore[$this->jenisTr]) ? $this->configCore[$this->jenisTr] : cekBiru($this->jenisTr . " belum ada di coTransaksiCore di modul " . $this->modul);
            $this->configLayoutJenis = isset($this->configLayout[$this->jenisTr]) ? $this->configLayout[$this->jenisTr] : cekMerah($this->jenisTr . " belum ada di coTransaksiLayout di modul " . $this->modul);
            $this->configValuesJenis = isset($this->configValues[$this->jenisTr]) ? $this->configValues[$this->jenisTr] : cekMerah($this->jenisTr . " belum ada di coTransaksiLayout di modul " . $this->modul);
            $this->jenisTrName = isset($this->configUi[$this->jenisTr]['steps'][1]['label']) ? $this->configUi[$this->jenisTr]['steps'][1]['label'] : "unnamed";
        }

        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");
        $this->accessList = alowedAccess(my_id());

        $this->transaksiMaintenance = $this->config->item("maintenanceTransaksi") != null && $this->config->item("maintenanceTransaksi") == true ? true : false;
        $this->transaksiMaintenanceMsg = isset($this->config->item("maintenanceOptions")[1]) ? $this->config->item("maintenanceOptions")[1] : array();
        $this->mongoTableList = array(
            "main" => "transaksi",
            "mainValues" => "transaksi_values",
            "detail" => "transaksi_data",
            "detailValues" => "transaksi_data_values",
            "sign" => "transaksi_sign",
            "extras" => "transaksi_extstep",
            "registry" => "transaksi_registry",
        );
        $this->allSteps = isset($this->configUi[$this->jenisTr]['steps']) ? $this->configUi[$this->jenisTr]['steps'] : array();

        $this->ppnFactor = isset($_SESSION['login']['ppnFactor']) ? $this->session->login["ppnFactor"] : cekOrange("cek " . __FILE__);

    }

    public function index()
    {
        cekOrange($this->modul);
        die(__FILE__ . " gondes");

    }

    /**
     * Role: Software Engineer / QA Auditor (ISO 25010)
     * Helper DRY Terpusat 1: Sanitasi Sesi Items dari Phantom Item (tanpa nama/handler valid)
     */
    public function sanitizeItemsSession($cCode = null)
    {
        $cCode = !empty($cCode) ? $cCode : $this->cCode;
        if (isset($_SESSION[$cCode]['items']) && is_array($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $kItem => $vItem) {
                $namaItem = isset($vItem['nama']) ? trim($vItem['nama']) : (isset($vItem['name']) ? trim($vItem['name']) : "");
                $hasHandler = isset($vItem['handler']) && strlen(trim($vItem['handler'])) > 0;
                if ($namaItem === "" || (!empty($vItem['projectID']) && isset($vItem['id']) && $vItem['id'] == $vItem['projectID'] && !$hasHandler)) {
                    unset($_SESSION[$cCode]['items'][$kItem]);
                }
            }
        }
    }

    /**
     * Role: Software Engineer / Lead Architect (ISO 27001)
     * Helper DRY Terpusat 2: Early Locker Check Fail-Fast Pattern
     */
    public function checkTransactionLocker($no)
    {
        if (isset($this->session->login['ghost']) && $this->session->login['ghost'] == 0) {
            $this->load->model("Mdls/MdlLockerTransaksi");
            $lt = New MdlLockerTransaksi();
            $lt->addFilter("transaksi_id='$no'");
            $ltTmp = $lt->lookupAll()->result();
            if (sizeof($ltTmp) > 0) {
                $byUpdateHold = array();
                foreach ($ltTmp as $ltSpec) {
                    if (($ltSpec->state == "hold") && ($ltSpec->jumlah > 0)) {
                        $byUpdateHold[] = $ltSpec->oleh_id;
                    }
                }
                if (sizeof($byUpdateHold) > 0 && !in_array($this->session->login['id'], $byUpdateHold)) {
                    matiHere("Transaksi tidak dapat dilanjut karena sesi locker sedang dipegang oleh pengguna lain.<br>Silakan berkoordinasi agar tidak terjadi transaksi ganda.");
                }
            }
        }
    }

    /**
     * Role: Software Engineer / Compliance Agent
     * Helper DRY Terpusat 3: Safe Rollback & Exit
     */
    public function rollbackAndDie($msg)
    {
        if (isset($this->db)) {
            $this->db->trans_rollback();
        }
        mati_disini($msg);
    }
}
// END OF COMPLETE REPEATED LOGIC
