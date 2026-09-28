<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/26/2018
 * Time: 5:01 PM
 */
require_once "Modul_Controller.php";

class _processPihak extends Modul_Controller
{

    // private $jenisTr;

    public function __construct()
    {
        parent::__construct();
        // $this->jenisTr = $this->uri->segment(4);
        // $cCode = "_TR_" . $this->jenisTr;
    }

    public function select()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $pihakValidate = isset($this->configUi[$this->jenisTr]['pihakValidate']) ? $this->configUi[$this->jenisTr]['pihakValidate'] : array();
        $pihakAddValidate = isset($this->configUi[$this->jenisTr]['pihakAddValidate']) ? $this->configUi[$this->jenisTr]['pihakAddValidate'] : array();

        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();
//showLast_query("biru");

        //---------------------------------------------------
        if (sizeof($pihakValidate) > 0) {

            foreach ($pihakValidate as $kolom => $spec) {
                if (isset($tmpB[0]->$kolom) && ($tmpB[0]->$kolom != NULL)) {
                    $result = $tmpB[0]->$kolom;
                    $tb_kolom = $spec['result'][$result]['kolom'];
                    $tb_label = $spec['result'][$result]['label'];
                    if (isset($tmpB[0]->$tb_kolom) && ($tmpB[0]->$tb_kolom != NULL)) {
                        cekHijau("LANJUT...");
                    }
                    else {
                        $label = $tmpB[0]->nama . ", " . $tb_label;
                        die(lgShowAlertBiru($label));
                    }
                }
                else {
                    $label = $tmpB[0]->nama . ", " . $spec['result']['none']['label'];
                    die(lgShowAlertBiru($label));
                }
            }
        }


        if (sizeof($pihakAddValidate) > 0) {
            $addMode = isset($pihakAddValidate['mode']) ? $pihakAddValidate['mode'] : NULL;
            $addFilter = isset($pihakAddValidate['filter']) ? $pihakAddValidate['filter'] : array();
            if (sizeof($addFilter) > 0) {
                foreach ($addFilter as $kf => $vf) {

                    cekHere(":: $kf => $vf :: $addMode ::");
                    switch ($addMode) {
                        case "!=":
                            if ($tmpB[0]->$kf != $vf) {
                                $label = $pihakAddValidate['label'][$kf];
                                die(lgShowAlertBiru($label));
                            }
                            break;
                        case "==":
                            if ($tmpB[0]->$kf == $vf) {
                                $label = $pihakAddValidate['label'][$kf];
                                die(lgShowAlertBiru($label));
                            }
                            break;
                        default:
                            cekHitam(":: masuk sini, default ::");
                            break;
                    }

                }
            }
        }

        //---------------------------------------------------


        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        // region resetor session delivery dan billing detail
        $gateReset = array("main", "tableIn_master_values");
        $resetor = array(
            "vendorDetails",
            "billingDetails",
            "deliveryDetails",
        );
        foreach ($gateReset as $gate) {

            if (isset($_SESSION[$cCode][$gate])) {
                foreach ($_SESSION[$cCode][$gate] as $keys => $values) {
                    $keysTmp = explode("__", $keys);
                    // buang yang sama dulu
                    if (in_array($keys, $resetor)) {
                        unset($_SESSION[$cCode][$gate][$keys]);
                    }
                    // buang yang mengandung __
                    if (in_array($keysTmp[0], $resetor)) {
                        unset($_SESSION[$cCode][$gate][$keys]);
                    }
                }
            }
        }
        if (isset($_SESSION[$cCode]['main_elements'])) {
            foreach ($resetor as $resetValue) {
                if (array_key_exists($resetValue, $_SESSION[$cCode]['main_elements'])) {
                    unset($_SESSION[$cCode]['main_elements'][$resetValue]);
                }
            }
        }


        // endregion

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());

        if (sizeof($tmpB) > 0) {

            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihakName2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihakDisc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";

            $_SESSION[$cCode]['main']['pihakPph23'] = (isset($tmpB[0]->npwp) && (strlen($tmpB[0]->npwp) > 1)) ? "2" : "4";

            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "sellerID" => my_id(),
                "sellerName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
//                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
//                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
                "stepNumber" => 1,
                "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            echo "<script>";
            // echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            echo "top.document.getElementById('pihakName').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
        else {
            cekMerah($id);
            $warehouse = getDefaultWarehouseID($this->session->login['cabang_id']);
//            arrPrint($warehouse);
            $_SESSION[$cCode]['main']['pihakID'] = $id;
//            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName'] = $warehouse['gudang_nama'];
            $_SESSION[$cCode]['main']['pihakName2'] = "";

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
            // echo "<script>";
            // echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            // echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName'] . "';";
            // echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            // echo "</script>";
        }
//        mati_disini("==========");

        /* --------------------------------------------------
             * ngereload shoping cart dlm modul
             * --------------------------------------------------*/
        echo "<script>";
        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "</script>";
    }

    public function select2()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihak2ID'] = $id;
            $_SESSION[$cCode]['main']['pihak2Name'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihak2Name2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihak2Disc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $_SESSION[$cCode]['main']['pihak2Mdl'] = isset($tmpB[0]->mdl_name) ? $tmpB[0]->mdl_name : "";
            $_SESSION[$cCode]['main']['pihak2Com'] = isset($accountChilds[$tmpB[0]->nama]) ? $accountChilds[$tmpB[0]->nama] : "";

            if (isset($tmpB[0]->exchange)) {
                $_SESSION[$cCode]['main']['pihak2Exchange'] = $tmpB[0]->exchange;
            }


            $resetorPihak3 = array(
                "pihak3ID",
                "pihak3Name",
                "pihak3Name3",
                "pihak3Disc",
                "pihak3Mdl",
                "pihak3Com",
            );
            foreach ($resetorPihak3 as $isi) {
                if (isset($_SESSION[$cCode]['main'][$isi])) {
                    $_SESSION[$cCode]['main'][$isi] = null;
                    unset($_SESSION[$cCode]['main'][$isi]);
                }
                if (isset($_SESSION[$cCode]['items'])) {
                    foreach ($_SESSION[$cCode]['items'] as $pid => $iSpec) {
                        if (isset($iSpec[$isi])) {
                            $iSpec[$isi] = null;
                            unset($iSpec[$isi]);
                        }
                        $_SESSION[$cCode]['items'][$pid] = $iSpec;
                    }
                }
            }


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak2Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet2').innerHTML='';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }

    public function select3()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
//        $mdlName = $this->uri->segment(5);
        if (isset($_SESSION[$cCode]['main']['pihak2Mdl'])) {
            $mdlName = $_SESSION[$cCode]['main']['pihak2Mdl'];
        }
        else {
            $mdlName = $this->uri->segment(5);
        }

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihak3ID'] = $id;
            $_SESSION[$cCode]['main']['pihak3Name'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihak3Name3'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihak3Disc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $_SESSION[$cCode]['main']['pihak3Mdl'] = isset($tmpB[0]->mdl_name) ? $tmpB[0]->mdl_name : "";
            $_SESSION[$cCode]['main']['pihak3Com'] = isset($accountChilds[$tmpB[0]->nama]) ? $accountChilds[$tmpB[0]->nama] : "";


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak3Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet3').innerHTML='';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }

    public function selectExtern()
    {


        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if (isset($_SESSION[$cCode]['main']['pihak2Mdl'])) {
            $mdlName = $_SESSION[$cCode]['main']['pihak2Mdl'];
        }
        else {
            $mdlName = $this->uri->segment(5);
        }


        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

//        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
//            $selectColumn = "nomer";
//        }
//        else {
//            $selectColumn = "nama";
//        }
        $arrCekKolom = array(
            "nomer" => array(
                "pihakMainNota",
                "pihakExternNota"
            ),
            "name" => array("pihakExternName"),
            "nama" => array("pihakExternName"),
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCeks) {
            foreach ($valueCeks as $valueCek) {
                if (isset($this->configUi[$this->jenisTr][$valueCek]) && $this->configUi[$this->jenisTr][$valueCek] == true) {
                    $selectColumn = $keyCek;
                    break;
                }
            }
        }

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihakExternID'] = $id;
            $_SESSION[$cCode]['main']['pihakExternMasterID'] = isset($tmpB[0]->id_master) ? $tmpB[0]->id_master : "";
            $_SESSION[$cCode]['main']['pihakExternName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihakExternValueSrc'] = isset($tmpB[0]->value_src) ? $tmpB[0]->value_src : "";
            $_SESSION[$cCode]['main']['pihakExternRevertStep'] = isset($tmpB[0]->revertStep) ? $tmpB[0]->revertStep : false;
            $_SESSION[$cCode]['main']['pihakExternDetailGate'] = isset($tmpB[0]->detailGate) ? $tmpB[0]->detailGate : "items";


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (isset($pihakMainValueSrc) && sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }


            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "sellerID" => my_id(),
                "sellerName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
//                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
//                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
                "stepNumber" => 1,
                "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

//            echo "<script>";
//            echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
////            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
//            echo "top.document.getElementById('pihakExternName').value='" . $tmpPihakName . "';";
//            echo "top.document.getElementById('pihak_extern_label').value='" . $tmpPihakName . "';";
//            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
//            echo "top.document.getElementById('pilihan_extern').innerHTML='';";
//            echo "</script>";
        }
        else {
//            $_SESSION[$cCode]['main']['pihakID'] = $id;
//            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
//            $_SESSION[$cCode]['main']['pihakName2'] = "";
////            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
////            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
//            if (sizeof($pihakMainValueSrc) > 0) {
//                foreach ($pihakMainValueSrc as $key => $src) {
//                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
////                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
//                }
//            }
//            echo "<script>";
//            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
//            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
//            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
//            echo "</script>";
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

//        mati_disini("$selectColumn :: $tmpPihakName");
        echo "<script>";
        echo "top.document.getElementById('pihakExternName').value='" . $tmpPihakName . "';";
        echo "top.document.getElementById('pilihan_extern').innerHTML='';";
        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "</script>";
    }

    public function remove()
    {
        $cCode = $this->cCode;

        $_SESSION[$cCode]['main']['pihakID'] = null;
        $_SESSION[$cCode]['main']['pihakName'] = null;
        $_SESSION[$cCode]['main']['pihakName2'] = null;
        unset($_SESSION[$cCode]['main']['pihakID']);
        unset($_SESSION[$cCode]['main']['pihakName']);
        unset($_SESSION[$cCode]['main']['pihakName2']);

        $_SESSION[$cCode]['main']['pihak2ID'] = null;
        $_SESSION[$cCode]['main']['pihak2Name'] = null;
        $_SESSION[$cCode]['main']['pihak2Name2'] = null;
        unset($_SESSION[$cCode]['main']['pihak2ID']);
        unset($_SESSION[$cCode]['main']['pihak2Name']);
        unset($_SESSION[$cCode]['main']['pihak2Name2']);


    }

    public function removeExtern()
    {
        $cCode = $this->cCode;

        $_SESSION[$cCode]['main']['pihakExternID'] = null;
        $_SESSION[$cCode]['main']['pihakExternMasterID'] = null;
        $_SESSION[$cCode]['main']['pihakExternName'] = null;
        $_SESSION[$cCode]['main']['pihakExternValueSrc'] = null;
        $_SESSION[$cCode]['main']['pihakExternRevertStep'] = null;
        $_SESSION[$cCode]['main']['pihakExternDetailGate'] = null;
        unset($_SESSION[$cCode]['main']['pihakExternID']);
        unset($_SESSION[$cCode]['main']['pihakExternMasterID']);
        unset($_SESSION[$cCode]['main']['pihakExternName']);
        unset($_SESSION[$cCode]['main']['pihakExternValueSrc']);
        unset($_SESSION[$cCode]['main']['pihakExternRevertStep']);
        unset($_SESSION[$cCode]['main']['pihakExternDetailGate']);

//        $_SESSION[$cCode]['main']['pihak2ID'] = null;
//        $_SESSION[$cCode]['main']['pihak2Name'] = null;
//        $_SESSION[$cCode]['main']['pihak2Name2'] = null;
//        unset($_SESSION[$cCode]['main']['pihak2ID']);
//        unset($_SESSION[$cCode]['main']['pihak2Name']);
//        unset($_SESSION[$cCode]['main']['pihak2Name2']);


    }

    //---------------------------
    public function select3UM()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);
//        if (isset($_SESSION[$cCode]['main']['pihak3Mdl'])) {
//            $mdlName = $_SESSION[$cCode]['main']['pihak3Mdl'];
//        }
//        else {
//            $mdlName = $this->uri->segment(5);
//        }

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        $dummyElementResetor = isset($this->configUi[$this->jenisTr]["dummyElementResetor"]) ? $this->configUi[$this->jenisTr]["dummyElementResetor"] : array();
        if (sizeof($dummyElementResetor) > 0) {
            foreach ($dummyElementResetor as $rVal) {
                $_SESSION[$cCode]['main'][$rVal] = NULL;
                unset($_SESSION[$cCode]['main'][$rVal]);
            }
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihak3ID'] = $id;
            $_SESSION[$cCode]['main']['pihak3Name'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihak3Name3'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihak3Disc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $_SESSION[$cCode]['main']['pihak3Mdl'] = isset($tmpB[0]->mdl_name) ? $tmpB[0]->mdl_name : "";
            $_SESSION[$cCode]['main']['pihak3Com'] = isset($accountChilds[$tmpB[0]->nama]) ? $accountChilds[$tmpB[0]->nama] : "";


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak3Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet3').innerHTML='';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
                "stepNumber" => 1,
                "stepCode" => $this->configUiJenis['steps'][1]['target'],
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }


    public function selectProjek()
    {
        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;



        // region detektor konsumen project
        $pihakExternResetor = (isset($this->configUi[$this->jenisTr]["pihakProjekResetor"])) ? $this->configUi[$this->jenisTr]["pihakProjekResetor"] : array();
        $receiptElements = (isset($this->configUi[$this->jenisTr]["receiptElements"])) ? $this->configUi[$this->jenisTr]["receiptElements"] : array();
        if (sizeof($pihakExternResetor) > 0) {
            cekHitam("ADA pihakExternResetor");
            if (isset($pihakExternResetor["enabled"]) && ($pihakExternResetor["enabled"] == true)) {
                if (isset($_SESSION[$cCode]["main"]["projectID"]) && ($_SESSION[$cCode]["main"]["projectID"] > 0)) {
                    // cek pihakExternID != $id, maka direset.... items, element, dan yang sudah di gerbang main
                    if ($_SESSION[$cCode]["main"]["projectID"] != $id) {
                        if (sizeof($receiptElements) > 0) {
                            foreach ($receiptElements as $elName => $elSpec) {
                                // reset element
                                if (isset($_SESSION[$cCode]["main_elements"][$elName])) {
                                    $_SESSION[$cCode]["main_elements"][$elName] = NULL;
                                    unset($_SESSION[$cCode]["main_elements"][$elName]);
                                }
                                // mereset yang ada di main
                                if (isset($_SESSION[$cCode]["main"])) {
                                    foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                                        if (strpos($key_reset, $elName) !== false) {
                                            unset($_SESSION[$cCode]["main"][$key_reset]);
                                        }
                                    }
                                }
                            }
                        }
                        if (sizeof($pihakExternResetor["keys"]) > 0) {
                            foreach ($pihakExternResetor["keys"] as $pihak_extern_reset) {
                                // mereset yang ada di main
                                if (isset($_SESSION[$cCode]["main"])) {
                                    foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                                        if (strpos($key_reset, $pihak_extern_reset) !== false) {
                                            unset($_SESSION[$cCode]["main"][$key_reset]);
                                        }
                                    }
                                }
                            }
                        }
                        if (sizeof($_SESSION[$cCode]['items']) > 0) {
                            $_SESSION[$cCode]['items'] = array();
                            unset($_SESSION[$cCode]['items']);
                            $_SESSION[$cCode]['items2'] = array();
                            unset($_SESSION[$cCode]['items2']);
                            $_SESSION[$cCode]['tableIn_detail_values'] = array();
                            unset($_SESSION[$cCode]['tableIn_detail_values']);
                            $_SESSION[$cCode]['tableIn_detail_values2'] = array();
                            unset($_SESSION[$cCode]['tableIn_detail_values2']);
                        }

//                        echo "<script>";
//                        echo "top.document.getElementById('pilihan_projek').innerHTML='';";
//                        echo "top.document.getElementById('pihakProjekName').value='';";
//                        echo "</script>";
                        cekMerah("HAPUS SESSION");
                    }
                }
                else {
                    cekMerah("TIDAK HAPUS SESSION");
                }
            }
        }
        else {
            cekHitam("TIDAK ADA pihakExternResetor");
        }
        // endregion detektor konsumen project



        if (isset($_SESSION[$cCode]['main']['pihak2Mdl'])) {
            $mdlName = $_SESSION[$cCode]['main']['pihak2Mdl'];
        }
        else {
            $mdlName = $this->uri->segment(5);
        }


        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

        $arrCekKolom = array(
            "nomer" => array(
                "pihakMainNota",
                "pihakExternNota"
            ),
            "name" => array("pihakProjekName"),
            "nama" => array("pihakProjekName"),
        );
        $selectColumn = "nama";
        foreach ($arrCekKolom as $keyCek => $valueCeks) {
            foreach ($valueCeks as $valueCek) {
                if (isset($this->configUi[$this->jenisTr][$valueCek]) && $this->configUi[$this->jenisTr][$valueCek] == true) {
                    $selectColumn = $keyCek;
                    break;
                }
            }
        }

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihakProjekID'] = $id;
            $_SESSION[$cCode]['main']['pihakProjekMasterID'] = isset($tmpB[0]->id_master) ? $tmpB[0]->id_master : "";
            $_SESSION[$cCode]['main']['pihakProjekName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = isset($tmpB[0]->value_src) ? $tmpB[0]->value_src : "";
            $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = isset($tmpB[0]->revertStep) ? $tmpB[0]->revertStep : false;
            $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = isset($tmpB[0]->detailGate) ? $tmpB[0]->detailGate : "items";
            //-GUDANG PER PROJECT------
            $_SESSION[$cCode]['main']['pihakProjekCustomerID'] = isset($tmpB[0]->customer_id) ? $tmpB[0]->customer_id : 0;
            $_SESSION[$cCode]['main']['pihakProjekCustomerName'] = isset($tmpB[0]->customer_nama) ? $tmpB[0]->customer_nama : 0;
            $_SESSION[$cCode]['main']['pihakProjekCustomerNama'] = isset($tmpB[0]->customer_nama) ? $tmpB[0]->customer_nama : 0;
            $_SESSION[$cCode]['main']['pihakProjekGudangID'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_id"];
            $_SESSION[$cCode]['main']['pihakProjekGudangName'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
            $_SESSION[$cCode]['main']['pihakProjekGudangNama'] = getDefaultWarehouseProject($id, $_SESSION[$cCode]['main']['pihakProjekName'])["gudang_nama"];
            //-------

            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }

            if (isset($pihakMainValueSrc) && sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            $this->load->library("ValueGate");
            $vg = new ValueGate();
            $vg->setConfigUiJenis($this->configUiJenis);
            $vg->setConfigCoreJenis($this->configCoreJenis);
            $vg->setConfigValuesJenis($this->configValuesJenis);
            $vg->setPpnFactor(my_ppn_factor());
            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "sellerID" => my_id(),
                "sellerName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
//                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
//                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
                "stepNumber" => 1,
                "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        echo "<script>";
        echo "top.document.getElementById('pihakProjekName').value='" . $tmpPihakName . "';";
        echo "top.document.getElementById('pilihan_projek').innerHTML='';";
        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "</script>";
    }

    public function removeProjek()
    {
        $cCode = $this->cCode;

        $_SESSION[$cCode]['main']['pihakProjekID'] = null;
        $_SESSION[$cCode]['main']['pihakProjekMasterID'] = null;
        $_SESSION[$cCode]['main']['pihakProjekName'] = null;
        $_SESSION[$cCode]['main']['pihakProjekValueSrc'] = null;
        $_SESSION[$cCode]['main']['pihakProjekRevertStep'] = null;
        $_SESSION[$cCode]['main']['pihakProjekDetailGate'] = null;
        unset($_SESSION[$cCode]['main']['pihakProjekID']);
        unset($_SESSION[$cCode]['main']['pihakProjekMasterID']);
        unset($_SESSION[$cCode]['main']['pihakProjekName']);
        unset($_SESSION[$cCode]['main']['pihakProjekValueSrc']);
        unset($_SESSION[$cCode]['main']['pihakProjekRevertStep']);
        unset($_SESSION[$cCode]['main']['pihakProjekDetailGate']);


    }

}