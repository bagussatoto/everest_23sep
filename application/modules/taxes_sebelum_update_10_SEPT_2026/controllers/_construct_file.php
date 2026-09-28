<?php
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
$this->configUiJenis = $this->configUi[$this->jenisTr];

$this->load->config($configPath . "coTransaksiCore");
$this->configCore = $this->config->item("coTransaksiCore");
$this->configCoreJenis = $this->configCore[$this->jenisTr];

$this->load->config($configPath . "coTransaksiLayout");
$this->configLayout = $this->config->item("coTransaksiLayout");
$this->configLayoutJenis = $this->configLayout[$this->jenisTr];

// $this->debugArrPrint($this->uri->segment_array());
// cekBiru($this->cCode);
// $this->debugArrPrint($_SESSION[$this->cCode]);
// arrPrintPink($this->session);
// $this->debugArrPrint($this->session->_TR_461);
// $this->debugArrPrint($this->session->$cCode);
// if(!isset($this->session)){
//
// }
cekKuning("_contruct_file included " . __FILE__);
?>
