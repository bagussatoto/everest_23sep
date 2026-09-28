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
        $autoSelectItem = isset($this->configUi[$this->jenisTr]['autoSelectItem']) ? $this->configUi[$this->jenisTr]['autoSelectItem'] : false;
        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();
        showLast_query("biru");

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

        if ($autoSelectItem) {
            $availData = $this->autoSelectItem();

        }

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
                "ppnFactor" => my_ppn_factor(),
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

        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();
// matiHEre($mdlName);
        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihak2ID'] = $id;
            $_SESSION[$cCode]['main']['pihak2Name'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihak2Coa_code'] = isset($tmpB[0]->coa_code) ? $tmpB[0]->coa_code : "";
            $_SESSION[$cCode]['main']['pihak2CoaName'] = isset($tmpB[0]->label) ? $tmpB[0]->label : "";
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
            echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak2Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet2').innerHTML='';";
            echo "</script>";

            // echo "<script>";
            // echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            // echo "top.document.getElementById('pihak2Name').value='" . $tmpPihakName . "';";
            // echo "top.document.getElementById('pilihan_outlet2').innerHTML='';";
            // echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";
//            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
//            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
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
                "ppnFactor" => my_ppn_factor(),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }

    public function select3()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc3'] : array();
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

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihak3ID'] = $id;
            $_SESSION[$cCode]['main']['pihak3Name'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihak3Coa_code'] = isset($tmpB[0]->coa_code) ? $tmpB[0]->coa_code : "";
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
                "ppnFactor" => my_ppn_factor(),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            echo "<script>";
            echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak3Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet3').innerHTML='';";
            echo "</script>";

            // echo "<script>";
            // echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?ohYes=ohNo');";
            // echo "top.document.getElementById('pihak3Name').value='" . $tmpPihakName . "';";
            // echo "top.document.getElementById('pilihan_outlet3').innerHTML='';";
            // echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";
            $_SESSION[$cCode]['main']['pihakName3'] = "";
            $_SESSION[$cCode]['main']['pihak3Coa_code'] = "";
//            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
//            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
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

            echo "<script>";
            echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }

    public function selectExtern()
    {


        $cCode = $this->cCode;
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

        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
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


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }
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
                "ppnFactor" => my_ppn_factor(),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakExternName').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
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
    }

    public function remove()
    {
        $cCode = $this->cCode;
        $id = 0;
        $_SESSION[$cCode]['main']['pihakID'] = null;
        $_SESSION[$cCode]['main']['pihakName'] = null;
        $_SESSION[$cCode]['main']['pihakName2'] = null;
        unset($_SESSION[$cCode]['main']['pihakID']);
        unset($_SESSION[$cCode]['main']['pihakName']);
        unset($_SESSION[$cCode]['main']['pihakName2']);

        $_SESSION[$cCode]['main']['pihak2ID'] = null;
        $_SESSION[$cCode]['main']['pihak2Name'] = null;
        $_SESSION[$cCode]['main']['pihak2Name2'] = null;
        $_SESSION[$cCode]['main']['pihak2Coa_code'] = null;
        $_SESSION[$cCode]['main']['pihak2CoaName'] = null;
        $_SESSION[$cCode]['main']['pihak3Coa_code'] = null;
        unset($_SESSION[$cCode]['main']['pihak2ID']);
        unset($_SESSION[$cCode]['main']['pihak2Name']);
        unset($_SESSION[$cCode]['main']['pihak2Name2']);
        unset($_SESSION[$cCode]['main']['pihak2Coa_code']);
        unset($_SESSION[$cCode]['main']['pihak2CoaName']);
        unset($_SESSION[$cCode]['main']['pihak3Coa_code']);
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
            "ppnFactor" => my_ppn_factor(),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        echo "<script>";
        echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "</script>";

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
                "ppnFactor" => my_ppn_factor(),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            echo "<script>";
            echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihak3Name').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet3').innerHTML='';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = "default warehouse";
            $_SESSION[$cCode]['main']['pihakName2'] = "";
//            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
//            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
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
                "ppnFactor" => my_ppn_factor(),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            echo "<script>";
            echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }

    private function autoSelectItem()
    {
        $this->load->library("FieldCalculator");


        // $id = $_GET['id']; // produk_id source/sumber konversi
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;


        $cCode = $this->cCode;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModel'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModel'];

        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $mainClonerConfig = isset($this->configUi[$this->jenisTr]['mainCloner']['items']) ? $this->configUi[$this->jenisTr]['mainCloner']['items'] : array();
        $handlerTmp = explode("/", $this->configUi[$this->jenisTr]['selectorProcessor']);
        $handler = $handlerTmp[0];
        // arrPrint($handlerTmp);
        // // arrPrint(explode("/",$handler));
        // matiHEre($handler);
        $b->setFilters(array());
        $tmpB = $b->lookUpAll()->result();
// arrPrint($tmpB);
        if (sizeof($tmpB) > 0) {
            foreach ($tmpB as $row) {
                $id = $row->id;
                $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";


                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");

                //                $cek = array();
                //                if (sizeof($_SESSION[$cCode]['items']) > 0) {
                //                    foreach ($_SESSION[$cCode]['items'] as $k => $vSpec) {
                //                        if (in_array($id, $vSpec)) {
                //                            $cek[] = true;
                //                        }
                //                    }
                //                }
                //                if ((!array_key_exists($id, $_SESSION[$cCode]['items'])) && (sizeof($cek) == 0)) {
                if ((!array_key_exists($id, $_SESSION[$cCode]['items']))) {
                    cekHitam(":: MASUK ATAS ::");
                    //baca dari config untuk yang wajib diisi/ mandatory

                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $handler,
                        "id" => $id,
                        "jml" => 1,
                        "harga" => 0,
                        "subtotal" => 0,
                        "rekening" => isset($row->rekening) ? $row->rekening : "",
                    );

                    foreach ($fieldSrcs as $key => $src) {
                        $tmp[$key] = makeValue($src, $tmp, $tmp, isset($row->$key) ? $row->$key : 0);
                    }

                    //region perhitungan subtotal items
                    if ($subAmountConfig != null) {
                        $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                    }
                    else {
                        $subtotal = 0;
                    }
                    $tmp["subtotal"] = $subtotal;
                    $_SESSION[$cCode]['items'][$id] = $tmp;
                    //endregion

                    if (sizeof($itemNumLabels) > 0) {
                        //                        echo("iterating subNums..");
                        //                        arrPrint($itemNumLabels);
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;

                            }
                        }

                        //                        foreach ($itemNumLabels as $key => $label) {
                        //                            $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                        //                        }
                        //                        $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                    }

                }
                else {
                    cekBiru(":: MASUK BAWAH ::");
                    if (sizeof($itemNumLabels) > 0) {
                        echo("iterating subNums..");
                        arrPrint($itemNumLabels);
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                echo "replacing value for $key with " . $newValue . "<br>";
                            }
                        }

                        foreach ($itemNumLabels as $key => $label) {
                            $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                        }

                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                    }
                }


                if (sizeof($mainClonerConfig) > 0) {
                    foreach ($mainClonerConfig as $key => $val) {
                        $_SESSION[$cCode]['main'][$key] = $row->$val;
                    }
                }
            }
            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
                $ctr = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);

                    $_SESSION[$cCode]['main'][str_replace(' ', '_', $iSpec['rekening'])] = $iSpec['harga'];


                    //                    $ctr++;
                    //                    $_SESSION[$cCode]['items'][$ctr] = $iSpec;
                    //                    $_SESSION[$cCode]['items'][$ctr]['id'] = $ctr;
                    //                    $_SESSION[$cCode]['items'][$ctr]['rekening'] = str_replace(' ', '_', $id);
                    //                    unset($_SESSION[$cCode]['items'][$id]);
                }
            }
        }
        // arrPrint($tmpB);
        // matiHere();
        // matiHere("yup");
    }
}