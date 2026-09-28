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

    protected function decodeSafeSerializedBase64($raw, $default = null)
    {
        if (!is_string($raw) || $raw === "") {
            return $default;
        }
        $decoded = base64_decode($raw, true);
        if ($decoded === false || $decoded === "") {
            return $default;
        }
        // Block serialized object/reference payloads from request.
        if (preg_match('/(^|;|{|})[OCR]:[0-9]+:/', $decoded) === 1) {
            return $default;
        }

        $value = @unserialize($decoded);
        if ($value === false && $decoded !== serialize(false)) {
            return $default;
        }
        if (is_object($value)) {
            return $default;
        }

        return $value;
    }

    protected function decodeSafeSerializedBase64Array($raw)
    {
        $value = $this->decodeSafeSerializedBase64($raw, array());
        return is_array($value) ? $value : array();
    }

    protected function getSafeArrayQueryParam($key)
    {
        if (!isset($_GET[$key]) || is_array($_GET[$key])) {
            return array();
        }
        return $this->decodeSafeSerializedBase64Array($_GET[$key]);
    }

    protected function getSafeIntQueryParam($key, $default = 0)
    {
        if (!isset($_GET[$key]) || is_array($_GET[$key])) {
            return (int)$default;
        }
        return (int)$_GET[$key];
    }

    protected function sanitizeFilterExpression($expression)
    {
        if (!is_string($expression)) {
            return null;
        }
        $expression = trim($expression);
        if ($expression === "") {
            return null;
        }
        if (preg_match('/(--|\/\*|\*\/|;|#)/', $expression) === 1) {
            return null;
        }
        if (preg_match('/^[a-zA-Z0-9_().,\s\'"`><=%-]+$/', $expression) !== 1) {
            return null;
        }

        return $expression;
    }

    protected function getSafeFilterParamsFromQuery($key)
    {
        $rawFilters = $this->getSafeArrayQueryParam($key);
        $safeFilters = array();
        if (sizeof($rawFilters) > 0) {
            foreach ($rawFilters as $filter) {
                $safeFilter = $this->sanitizeFilterExpression($filter);
                if ($safeFilter !== null) {
                    $safeFilters[] = $safeFilter;
                }
            }
        }
        return $safeFilters;
    }

    protected function isProjectNoteItem($itemSpec)
    {
        if (!is_array($itemSpec) || sizeof($itemSpec) == 0) {
            return false;
        }

        $projectId = 0;
        if (isset($itemSpec['project_id'])) {
            $projectId = (int)$itemSpec['project_id'];
        }
        elseif (isset($itemSpec['projectID'])) {
            $projectId = (int)$itemSpec['projectID'];
        }
        if ($projectId > 0) {
            return true;
        }

        $projectName = "";
        if (isset($itemSpec['project_nama'])) {
            $projectName = trim((string)$itemSpec['project_nama']);
        }
        elseif (isset($itemSpec['projectName'])) {
            $projectName = trim((string)$itemSpec['projectName']);
        }

        return $projectName !== "" && $projectName !== "0";
    }

    protected function assertNoMixedProjectNotes($cCode, $pendingItem = array(), $pendingId = null)
    {
        $selectorProcessor = isset($this->configUi[$this->jenisTr]['selectorProcessor']) ? $this->configUi[$this->jenisTr]['selectorProcessor'] : "";
        if (strpos((string)$selectorProcessor, "_processSelectNota") === false) {
            return;
        }

        $selectedItems = isset($_SESSION[$cCode]['items']) && is_array($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array();
        $projectCount = 0;
        $regularCount = 0;

        foreach ($selectedItems as $selectedId => $itemSpec) {
            if ($pendingId !== null && (string)$selectedId === (string)$pendingId) {
                continue;
            }

            if ($this->isProjectNoteItem((array)$itemSpec)) {
                $projectCount++;
            }
            else {
                $regularCount++;
            }
        }

        if (is_array($pendingItem) && sizeof($pendingItem) > 0) {
            if ($this->isProjectNoteItem($pendingItem)) {
                $projectCount++;
            }
            else {
                $regularCount++;
            }
        }

        if ($projectCount > 1) {
            matiHere("Project hanya boleh 1 nota per transaksi.");
        }
        if ($projectCount > 0 && $regularCount > 0) {
            matiHere("Nota project tidak bisa digabung dengan nota reguler.");
        }
    }

}

