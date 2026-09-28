<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_Controller extends MX_Controller
{
    protected $taxesMutationService = null;

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

    protected function getSafeDateParam($key, $defaultDate)
    {
        if (!isset($_GET[$key])) {
            return $defaultDate;
        }

        $value = trim((string)$_GET[$key]);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        return $defaultDate;
    }

    protected function decodeSafeBase64Param($key, $defaultValue = "")
    {
        if (!isset($_GET[$key])) {
            return $defaultValue;
        }

        $raw = base64_decode((string)$_GET[$key], true);
        if ($raw === false) {
            return $defaultValue;
        }

        $json = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $safeJson = $this->sanitizeDecodedPayload($json);
            return $safeJson === null ? $defaultValue : $safeJson;
        }

        // PHP 5.6 tidak mendukung argumen ke-2 pada unserialize.
        if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
            $decoded = @unserialize($raw, array("allowed_classes" => false));
        }
        else {
            $decoded = @unserialize($raw);
        }
        if ($decoded !== false || $raw === "b:0;") {
            $safeDecoded = $this->sanitizeDecodedPayload($decoded);
            return $safeDecoded === null ? $defaultValue : $safeDecoded;
        }

        return $raw;
    }

    protected function decodeSafeBase64ArrayParam($key)
    {
        $decoded = $this->decodeSafeBase64Param($key, array());
        return is_array($decoded) ? $decoded : array();
    }

    protected function addSafeFilters($model, $filters)
    {
        if (!is_array($filters) || !is_object($model)) {
            return;
        }

        foreach ($filters as $filter) {
            if ($this->isSafeFilterClause($filter)) {
                $model->addFilter($filter);
            }
        }
    }

    protected function isSafeFilterClause($filter)
    {
        if (!is_string($filter)) {
            return false;
        }

        $filter = trim($filter);
        if ($filter === "" || strlen($filter) > 255) {
            return false;
        }

        if (preg_match('/[;\x00]/', $filter)) {
            return false;
        }

        if (preg_match('/(--|\/\*|\*\/|#)/', $filter)) {
            return false;
        }

        if (preg_match('/\b(select|insert|update|delete|drop|alter|create|truncate|union|sleep|benchmark)\b/i', $filter)) {
            return false;
        }

        return (bool)preg_match('/^[a-zA-Z0-9_().,\'"`\-\s<>=!:+%\/]*$/', $filter);
    }

    protected function getSafeMdlsModelName($candidate)
    {
        $name = trim((string)$candidate);
        if (!preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $name)) {
            return null;
        }

        $modelPath = APPPATH . "models/Mdls/" . $name . ".php";
        if (!file_exists($modelPath)) {
            return null;
        }

        return $name;
    }

    protected function loadSafeMdlsModel($candidate)
    {
        $name = $this->getSafeMdlsModelName($candidate);
        if ($name === null) {
            show_error("Invalid model name", 400);
            exit;
        }

        $this->load->model("Mdls/" . $name);
        return $name;
    }

    protected function sanitizeDecodedPayload($payload)
    {
        if (is_null($payload)) {
            return null;
        }

        if (is_scalar($payload)) {
            return $payload;
        }

        if (!is_array($payload)) {
            return null;
        }

        $clean = array();
        foreach ($payload as $key => $value) {
            if (!is_scalar($key)) {
                continue;
            }

            $cleanValue = $this->sanitizeDecodedPayload($value);
            if ($cleanValue !== null) {
                $clean[$key] = $cleanValue;
            }
        }

        return $clean;
    }

    protected function enforceMutationGuard($readOnlyMethods = array())
    {
        $method = strtolower((string)$this->router->fetch_method());
        $readOnly = array_map('strtolower', (array)$readOnlyMethods);
        if (in_array($method, $readOnly, true)) {
            return;
        }

        $this->validateMutationRequest();
        $this->sanitizeCommonMutationInputs();
    }

    protected function validateMutationRequest()
    {
        if (!isset($this->session->login['id'])) {
            show_error("Unauthenticated request", 401);
            exit;
        }

        $this->validateMutationActionAccess();

        $token = $this->readMutationTokenFromRequest();
        $expected = $this->getMutationSessionToken();
        if (is_string($token) && strlen($token) > 0) {
            if (!hash_equals($expected, $token)) {
                show_error("Invalid mutation token", 403);
                exit;
            }
            return;
        }

        if (!$this->isSameOriginRequest()) {
            show_error("Potential CSRF request blocked", 403);
            exit;
        }
    }

    protected function validateMutationActionAccess()
    {
        $accessMap = isset($this->configUiJenis['mutationAccess']) && is_array($this->configUiJenis['mutationAccess']) ? $this->configUiJenis['mutationAccess'] : array();
        if (sizeof($accessMap) < 1) {
            return;
        }

        $method = strtolower((string)$this->router->fetch_method());
        if (!array_key_exists($method, $accessMap)) {
            return;
        }

        $allowedGroups = (array)$accessMap[$method];
        if (sizeof($allowedGroups) < 1) {
            return;
        }

        $userGroups = isset($this->session->login['membership']) && is_array($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (sizeof(array_intersect($allowedGroups, $userGroups)) < 1) {
            show_error("Forbidden action", 403);
            exit;
        }
    }

    protected function readMutationTokenFromRequest()
    {
        if (isset($_GET['csrf_token']) && is_string($_GET['csrf_token'])) {
            return trim($_GET['csrf_token']);
        }

        if (isset($_POST['csrf_token']) && is_string($_POST['csrf_token'])) {
            return trim($_POST['csrf_token']);
        }

        if (isset($_SERVER['HTTP_X_CSRF_TOKEN']) && is_string($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            return trim($_SERVER['HTTP_X_CSRF_TOKEN']);
        }

        $ciTokenName = method_exists($this->security, 'get_csrf_token_name') ? $this->security->get_csrf_token_name() : null;
        if ($ciTokenName) {
            if (isset($_GET[$ciTokenName]) && is_string($_GET[$ciTokenName])) {
                return trim($_GET[$ciTokenName]);
            }
            if (isset($_POST[$ciTokenName]) && is_string($_POST[$ciTokenName])) {
                return trim($_POST[$ciTokenName]);
            }
        }

        return "";
    }

    protected function getMutationSessionToken()
    {
        if (!isset($_SESSION['mutation_csrf_token']) || !is_string($_SESSION['mutation_csrf_token']) || strlen($_SESSION['mutation_csrf_token']) < 16) {
            $_SESSION['mutation_csrf_token'] = $this->generateSecureHexToken(16);
        }

        return $_SESSION['mutation_csrf_token'];
    }

    protected function generateSecureHexToken($byteLength = 16)
    {
        $len = (int)$byteLength;
        if ($len < 16) {
            $len = 16;
        }

        if (function_exists('random_bytes')) {
            try {
                return bin2hex(random_bytes($len));
            }
            catch (Exception $e) {
                // fallback ke openssl/mt_rand
            }
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            $strong = false;
            $bytes = openssl_random_pseudo_bytes($len, $strong);
            if ($bytes !== false && strlen($bytes) === $len) {
                return bin2hex($bytes);
            }
        }

        $seed = uniqid(mt_rand(), true) . microtime(true) . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        return substr(hash('sha256', $seed), 0, $len * 2);
    }

    protected function isSameOriginRequest()
    {
        $expectedHost = isset($_SERVER['HTTP_HOST']) ? strtolower((string)$_SERVER['HTTP_HOST']) : "";
        if ($expectedHost === "") {
            return false;
        }

        $origin = isset($_SERVER['HTTP_ORIGIN']) ? (string)$_SERVER['HTTP_ORIGIN'] : "";
        if ($origin !== "") {
            $originHost = strtolower((string)parse_url($origin, PHP_URL_HOST));
            if ($originHost !== "" && $originHost === strtolower((string)parse_url("http://" . $expectedHost, PHP_URL_HOST))) {
                return true;
            }
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? (string)$_SERVER['HTTP_REFERER'] : "";
        if ($referer !== "") {
            $refererHost = strtolower((string)parse_url($referer, PHP_URL_HOST));
            if ($refererHost !== "" && $refererHost === strtolower((string)parse_url("http://" . $expectedHost, PHP_URL_HOST))) {
                return true;
            }
        }

        return false;
    }

    protected function sanitizeCommonMutationInputs()
    {
        $intKeys = array('id', 'iid', 'transaksi_id', 'jml', 'newQty', 'step', 'page', 'stID', 'sID');
        foreach ($intKeys as $key) {
            if (isset($_GET[$key]) && is_scalar($_GET[$key])) {
                $val = (string)$_GET[$key];
                if (preg_match('/^-?\d+$/', $val)) {
                    $_GET[$key] = (int)$val;
                }
            }
            if (isset($_POST[$key]) && is_scalar($_POST[$key])) {
                $val = (string)$_POST[$key];
                if (preg_match('/^-?\d+$/', $val)) {
                    $_POST[$key] = (int)$val;
                }
            }
        }

        $safeKeys = array('key', 'valCol');
        foreach ($safeKeys as $key) {
            if (isset($_GET[$key]) && is_scalar($_GET[$key])) {
                $_GET[$key] = preg_replace('/[^A-Za-z0-9_]/', '', (string)$_GET[$key]);
            }
            if (isset($_POST[$key]) && is_scalar($_POST[$key])) {
                $_POST[$key] = preg_replace('/[^A-Za-z0-9_]/', '', (string)$_POST[$key]);
            }
        }
    }

    protected function isModuleDebugEnabled()
    {
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
            return false;
        }

        $forced = $this->config->item('taxesDebugMode');
        if ($forced !== null) {
            return (bool)$forced;
        }

        if (!defined('ENVIRONMENT')) {
            return false;
        }

        return in_array(ENVIRONMENT, array('development', 'testing'), true);
    }

    protected function debugArrPrint($value)
    {
        if (!$this->isModuleDebugEnabled()) {
            return;
        }

        if (function_exists('arrPrint')) {
            arrPrint($value);
        }
    }

    protected function debugShowLastQuery($color = "biru")
    {
        if (!$this->isModuleDebugEnabled()) {
            return;
        }

        if (function_exists('showLast_query')) {
            showLast_query($color);
        }
    }

    protected function validateUploadedImageFile($file, $maxBytes = 5242880)
    {
        if (!is_array($file) || !isset($file['error'], $file['size'], $file['name'], $file['tmp_name'])) {
            return array(false, "File upload tidak valid.");
        }

        if ((int)$file['error'] !== UPLOAD_ERR_OK) {
            return array(false, "Upload file gagal.");
        }

        if ((int)$file['size'] <= 0 || (int)$file['size'] > (int)$maxBytes) {
            return array(false, "Ukuran file melebihi batas.");
        }

        $safeName = strtolower((string)$file['name']);
        $ext = pathinfo($safeName, PATHINFO_EXTENSION);
        $allowedExt = array('jpg', 'jpeg', 'png', 'webp', 'gif');
        if (!in_array($ext, $allowedExt, true)) {
            return array(false, "Ekstensi file tidak diizinkan.");
        }

        $allowedMime = array('image/jpeg', 'image/png', 'image/webp', 'image/gif');
        $mime = "";
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mime = (string)finfo_file($finfo, (string)$file['tmp_name']);
                finfo_close($finfo);
            }
        }
        if ($mime === "" && isset($file['type'])) {
            $mime = strtolower((string)$file['type']);
        }
        if ($mime !== "" && !in_array($mime, $allowedMime, true)) {
            return array(false, "Mime type file tidak diizinkan.");
        }

        return array(true, "");
    }

    protected function beginSafeTransaction()
    {
        $this->logMutationEvent('tx_begin');
        $this->db->trans_begin();
    }

    protected function finalizeSafeTransaction($errorMessage = "Gagal saat berusaha commit transaction!", $onFailCompensate = null)
    {
        $status = $this->db->trans_status();

        if ($status === false) {
            $this->db->trans_rollback();
            $this->logMutationEvent('tx_rollback', array('reason' => 'trans_status_false'), 'error');
            if (is_callable($onFailCompensate)) {
                call_user_func($onFailCompensate);
            }
            $this->failMutationResponse($errorMessage, 500);
            return false;
        }

        $this->db->trans_commit();
        $this->logMutationEvent('tx_commit');
        return true;
    }

    protected function failMutationResponse($message, $httpCode = 500)
    {
        $msg = (string)$message;
        if (function_exists('log_message')) {
            $ctx = $this->buildMutationLogContext(array('message' => $msg, 'http_code' => (int)$httpCode));
            log_message('error', '[taxes] fail_mutation_response ' . json_encode($ctx));
        }

        if (function_exists('lgShowAlert')) {
            echo lgShowAlert($msg);
        }
        else {
            show_error($msg, $httpCode);
        }
    }

    protected function getTaxesMutationService()
    {
        if ($this->taxesMutationService !== null) {
            return $this->taxesMutationService;
        }

        $servicePath = APPPATH . 'modules/taxes/services/TaxesMutationService.php';
        if (file_exists($servicePath)) {
            require_once $servicePath;
        }

        if (class_exists('TaxesMutationService')) {
            $this->taxesMutationService = new TaxesMutationService($this->db);
        }

        return $this->taxesMutationService;
    }

    protected function updateTransaksiIndexing($id, $column, $arrBlob)
    {
        $service = $this->getTaxesMutationService();
        if ($service && method_exists($service, 'updateIndexingColumn')) {
            return $service->updateIndexingColumn($id, $column, $arrBlob);
        }

        $this->db->where('id', (int)$id);
        $this->db->set((string)$column, $arrBlob);
        return $this->db->update('transaksi');
    }

    protected function updateTransaksiIndexingMap($map, $column)
    {
        $service = $this->getTaxesMutationService();
        if ($service && method_exists($service, 'updateIndexingMap')) {
            return $service->updateIndexingMap($map, $column);
        }

        if (!is_array($map)) {
            return false;
        }
        foreach ($map as $id => $arrBlob) {
            $this->updateTransaksiIndexing($id, $column, $arrBlob);
        }
        return true;
    }

    protected function logMutationEvent($event, $extra = array(), $level = 'info')
    {
        if (!function_exists('log_message')) {
            return;
        }

        $payload = $this->buildMutationLogContext($extra);
        $payload['event'] = (string)$event;
        log_message($level, '[taxes] ' . json_encode($payload));
    }

    protected function buildMutationLogContext($extra = array())
    {
        $ctx = array(
            'controller' => get_class($this),
            'method' => method_exists($this, 'router') && isset($this->router) ? (string)$this->router->fetch_method() : '',
            'uri' => isset($_SERVER['REQUEST_URI']) ? (string)$_SERVER['REQUEST_URI'] : '',
            'user_id' => isset($this->session->login['id']) ? (int)$this->session->login['id'] : 0,
            'jenis_tr' => isset($this->jenisTr) ? (string)$this->jenisTr : '',
        );

        if (is_array($extra)) {
            foreach ($extra as $k => $v) {
                $ctx[$k] = $v;
            }
        }

        return $ctx;
    }

}
