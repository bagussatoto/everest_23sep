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
        $autoSelectItemNonProject = isset($this->configUi[$this->jenisTr]['autoSelectItemNonProject']) ? $this->configUi[$this->jenisTr]['autoSelectItemNonProject'] : false;
        $pihakKoreksiData = isset($this->configUi[$this->jenisTr]['pihakKoreksiData']) ? $this->configUi[$this->jenisTr]['pihakKoreksiData'] : array();


        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();
//        showLast_query("biru");

        /* ------------------------------------------------------------
         * mendapatkan field2 mandatori
         * ------------------------------------------------------------*/
        $getValidationRules = $b->getValidationRules();
        $getFields = $b->getFields();
        // arrPrint($getValidationRules);
        // arrPrint($getFields);
        foreach ($getValidationRules as $kolom => $validationRules) {
            if (in_array("required", $validationRules)) {
                $requiredKolom[] = $kolom;
                foreach ($getFields as $getField) {
                    $fkolom = $getField["kolom"];
                    $flabel = $getField["label"];
                    if ($kolom == $fkolom) {
                        $requiredParams[$kolom]['label'] = $flabel;
                        $requiredParams[$kolom]['kolom'] = $fkolom;
                    }
                }
            }
        }


        /* ----------------------------------------------------
         * ceking paramRequired
         * ----------------------------------------------------*/
        $datas = $tmpB[0];
        $cuNama = $datas->nama;
        $hasil = "";
        foreach ($requiredParams as $reqKolom => $requiredParam) {
            $reqlabel = $requiredParam['label'];
            $data = $datas->$reqKolom;
            if (strlen($data) < 1) {
                $var = ucwords($reqlabel);
                if ($hasil == "") {
                    $hasil .= "$var";
                }
                else {
                    $hasil = "$hasil, $var";
                }
            }
        }

//        $msg = "<h3>INFO:</h3>";
//        $msg .= "Data <b class='text-uppercase'>$cuNama</b> &nbsp;($id)<br> " . $hasil;
//        $msg .= "<br> <span class='text-red'>Masih belum lengkap.</span>";
//        $msg .= "<br><br> Supaya bisa masuk pada transaksi yang akan dibuat, lengkapi dahulu dari menu data customer, baru kemudian pilih ulang.";
//        $msg .= "<br> Video tutorial bisa dilihat dari icon video kanan atas";
        // $msg .= "<br> <button type='button' class='btn btn-danger' onclick=\"$xx\">lanjut</button>";
//        if (strlen($hasil) > 1) {
//            $pic_data = callPICData(array("updaters"), $mdlName);
//            $pic_data_text = implode(",", $pic_data);
//            $kolom_koreksi = $pihakKoreksiData["kolom"];
//            $msg .= "  Silahkan hubungi <b>$pic_data_text</b>.";
//            echo cekAlert($msg);
//        }

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
        if (sizeof($pihakKoreksiData) > 0) {
            if (isset($pihakKoreksiData["enabled"]) && ($pihakKoreksiData["enabled"] == true)) {
                $pic_data = callPICData(array("updaters"), $mdlName);
                $pic_data_text = implode(",", $pic_data);
                $kolom_koreksi = $pihakKoreksiData["kolom"];
                $notif = false;
                foreach ($kolom_koreksi as $key_cek => $label_cek) {
                    if ($tmpB[0]->$key_cek == 0) {
                        $notif = true;
                        $label_cek = str_replace("{konsumen_nama}", $tmpB[0]->nama, $label_cek);
                        mati_disini($label_cek . " <br>Silahkan hubungi <b>$pic_data_text</b> untuk mengupdate/menentukan <b>$hasil</b>. code: " . __LINE__);
                    }
                }
                if($notif == true){
                    $msg = "Data <b class='text-uppercase'>$cuNama</b> &nbsp;($id)<br> belum kengkap. ";
                    $msg .= " <br>Silahkan hubungi <b>$pic_data_text</b> untuk mengupdate/menentukan <b>$hasil</b>. code: " . __LINE__;
                    mati_disini($msg);
                }
            }
        }

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
            if ($autoSelectItemNonProject == true) {
                $availData = $this->autoSelectItemReguler();
            }
            else {
                $availData = $this->autoSelectItem();
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
            $_SESSION[$cCode]['main']['pihakJenis'] = isset($tmpB[0]->folder_id) ? $tmpB[0]->folder_id : 0;

            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            if ($tmpB[0]->id == CB_ID_PUSAT) {
                $_SESSION[$cCode]['main']['pihakPembebananLabel'] = "pusat";
                $_SESSION[$cCode]['main']['pihakPembebananID'] = $tmpB[0]->id;
                $_SESSION[$cCode]['main']['pihakPembebananCode'] = 100;
            }
            else {
                $_SESSION[$cCode]['main']['pihakPembebananLabel'] = "cabang";
                $_SESSION[$cCode]['main']['pihakPembebananID'] = $tmpB[0]->id;
                $_SESSION[$cCode]['main']['pihakPembebananCode'] = 111;
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
            echo "top.document.getElementById('pihakName').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
        else {
            cekMerah($id);
            $warehouse = getDefaultWarehouseID($this->session->login['cabang_id']);
            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakName'] = $warehouse['gudang_nama'];
            $_SESSION[$cCode]['main']['pihakName2'] = "";

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
        }


        //------
//        $autoSelectPPh = isset($this->configUi[$this->jenisTr]['autoSelectPPh']) ? $this->configUi[$this->jenisTr]['autoSelectPPh'] : array();
//        if (sizeof($autoSelectPPh) > 0) {
//            if (isset($autoSelectPPh["enabled"]) && ($autoSelectPPh["enabled"] == true)) {
//                $npwp_cek = ($tmpB[0]->npwp != NULL) ? "npwp" : "non_npwp";
//                $elName = $autoSelectPPh["elName"];
//                $elMdlName = $autoSelectPPh["mdlName"];
//                echo "<script>";
//                echo "  top.$('#result').load('" . MODUL_PATH . "_shoppingCart/fetchElement/" . $this->jenisTr . "/$elName/$elMdlName/?key=$npwp_cek');";
//                echo "</script>";
//            }
//        }
        //------

        //------
        $autoLoadPihakMain = isset($this->configUi[$this->jenisTr]['autoLoadPihakMain']) ? $this->configUi[$this->jenisTr]['autoLoadPihakMain'] : false;
        if ($autoLoadPihakMain == true) {
            $pihakMainCaller = isset($this->configUi[$this->jenisTr]['pihakMainCaller']) ? $this->configUi[$this->jenisTr]['pihakMainCaller'] : "";
            $pihakModelMain = isset($this->configUi[$this->jenisTr]['pihakModelMain']) ? $this->configUi[$this->jenisTr]['pihakModelMain'] : "";
            echo "<script>";
            echo "top.getData('" . MODUL_PATH . "$pihakMainCaller/" . $this->jenisTr . "/$pihakModelMain?search=', 'pilihan_main')";
            echo "</script>";
        }
        //------


        //------
        $autoLoadSelecttorItem = isset($this->configUi[$this->jenisTr]['autoLoadSelectorItem']) ? $this->configUi[$this->jenisTr]['autoLoadSelectorItem'] : false;
        if ($autoLoadSelecttorItem == true) {
            $pihakSelectorCaller = isset($this->configUi[$this->jenisTr]['selectorCaller']) ? $this->configUi[$this->jenisTr]['selectorCaller'] : "";
            $pihakSelectorMain = isset($this->configUi[$this->jenisTr]['selectorModel']) ? $this->configUi[$this->jenisTr]['selectorModel'] : "";
            echo "<script>";
            echo "top.getData('" . MODUL_PATH . "$pihakSelectorCaller/" . $this->jenisTr . "/$pihakSelectorMain?search=', 'pilihan_item')";
            echo "</script>";
        }
        //------


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
//        arrprint($tmpB);
// matiHEre($mdlName);
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
        $_SESSION[$cCode]['main']['pihakDisc'] = null;
        $_SESSION[$cCode]['main']['pihakJenis'] = null;
        unset($_SESSION[$cCode]['main']['pihakID']);
        unset($_SESSION[$cCode]['main']['pihakName']);
        unset($_SESSION[$cCode]['main']['pihakName2']);
        unset($_SESSION[$cCode]['main']['pihakDisc']);
        unset($_SESSION[$cCode]['main']['pihakJenis']);

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

    public function autoSelectItem_old()
    {
        $this->load->library("FieldCalculator");

//matiHere();
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
//         matiHEre($selectorSrcModel);
        $b->setFilters(array());
        $tmpB = $b->lookUpAll()->result();
// arrPrint($tmpB);
//        if (sizeof($tmpB) > 0) {
//            foreach ($tmpB as $row) {
//                $id = $row->id;
//                $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";
//
//
//                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
//
//                //                $cek = array();
//                //                if (sizeof($_SESSION[$cCode]['items']) > 0) {
//                //                    foreach ($_SESSION[$cCode]['items'] as $k => $vSpec) {
//                //                        if (in_array($id, $vSpec)) {
//                //                            $cek[] = true;
//                //                        }
//                //                    }
//                //                }
//                //                if ((!array_key_exists($id, $_SESSION[$cCode]['items'])) && (sizeof($cek) == 0)) {
//                if ((!array_key_exists($id, $_SESSION[$cCode]['items']))) {
//                    cekHitam(":: MASUK ATAS ::");
//                    //baca dari config untuk yang wajib diisi/ mandatory
//
//                    $tmp = array(
//                        "handler" => $this->uri->segment(1) . "/" . $handler,
//                        "id" => $id,
//                        "jml" => 1,
//                        "harga" => 0,
//                        "subtotal" => 0,
//                        "rekening" => isset($row->rekening) ? $row->rekening : "",
//                    );
//
//                    foreach ($fieldSrcs as $key => $src) {
//                        $tmp[$key] = makeValue($src, $tmp, $tmp, isset($row->$key) ? $row->$key : 0);
//                    }
//
//                    //region perhitungan subtotal items
//                    if ($subAmountConfig != null) {
//                        $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
//                    }
//                    else {
//                        $subtotal = 0;
//                    }
//                    $tmp["subtotal"] = $subtotal;
//                    $_SESSION[$cCode]['items'][$id] = $tmp;
//                    //endregion
//
//                    if (sizeof($itemNumLabels) > 0) {
//                        //                        echo("iterating subNums..");
//                        //                        arrPrint($itemNumLabels);
//                        foreach ($itemNumLabels as $key => $label) {
//                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
//                                $newValue = $_GET[$key];
//                                $tmp[$key] = $newValue;
//                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
//
//                            }
//                        }
//
//                        //                        foreach ($itemNumLabels as $key => $label) {
//                        //                            $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
//                        //                        }
//                        //                        $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
//                    }
//
//                }
//                else {
//                    cekBiru(":: MASUK BAWAH ::");
//                    if (sizeof($itemNumLabels) > 0) {
//                        echo("iterating subNums..");
//                        arrPrint($itemNumLabels);
//                        foreach ($itemNumLabels as $key => $label) {
//                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
//                                $newValue = $_GET[$key];
//                                $tmp[$key] = $newValue;
//                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
//                                echo "replacing value for $key with " . $newValue . "<br>";
//                            }
//                        }
//
//                        foreach ($itemNumLabels as $key => $label) {
//                            $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
//                        }
//
//                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
//                    }
//                }
//
//
//                if (sizeof($mainClonerConfig) > 0) {
//                    foreach ($mainClonerConfig as $key => $val) {
//                        $_SESSION[$cCode]['main'][$key] = $row->$val;
//                    }
//                }
//            }
//            if (sizeof($_SESSION[$cCode]['items']) > 0) {
//                $_SESSION[$cCode]['main']['harga'] = 0;
//                $ctr = 0;
//                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
//                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//
//                    $_SESSION[$cCode]['main'][str_replace(' ', '_', $iSpec['rekening'])] = $iSpec['harga'];
//
//
//                    //                    $ctr++;
//                    //                    $_SESSION[$cCode]['items'][$ctr] = $iSpec;
//                    //                    $_SESSION[$cCode]['items'][$ctr]['id'] = $ctr;
//                    //                    $_SESSION[$cCode]['items'][$ctr]['rekening'] = str_replace(' ', '_', $id);
//                    //                    unset($_SESSION[$cCode]['items'][$id]);
//                }
//            }
//        }

        // arrPrint($tmpB);
        // matiHere();
        // matiHere("yup");
    }

    // ini dipakai project
    public function autoSelectItem($no_spk = 0)
    {
        $this->load->library("FieldCalculator");

        // $id = $_GET['id']; // produk_id source/sumber konversi
        $jml = isset($_GET['jml']) ? $_GET['jml'] : 1;

        $cCode = $this->cCode;
        $stepNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

        $selectorModel = $this->configUi[$this->jenisTr]['selectorModelAuto'];
        $selectorSrcModel = $this->configUi[$this->jenisTr]['selectorSrcModelAuto'];
        $selectorSrcModelDetails = $this->configUi[$this->jenisTr]['selectorSrcModelDetails'];

        $this->load->model("Mdls/" . $selectorSrcModelDetails);
        $bk = new $selectorSrcModelDetails();
        $bk->setFilters(array());
        $bk->addFilter("jenis='biaya");
        if ($no_spk != 0) {
            $bk->addFilter("no_spk=$no_spk");
        }
        $tmpBK0 = $bk->lookUpAll()->result();

//        arrPrint($selectorSrcModel);
//        matiHere(__LINE__);
        $this->load->model("Mdls/MdlProjectKomposisiWorkorderSubTambahan");
        $bkt = new MdlProjectKomposisiWorkorderSubTambahan();
        $bkt->setFilters(array());
        $bkt->addFilter("jenis='biaya");
        if ($no_spk != 0) {
            $bkt->addFilter("no_spk=$no_spk");
        }
        $tmpBK1 = $bkt->lookUpAll()->result();

        $tmpBK = array_merge($tmpBK0, $tmpBK1);


        $this->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();

        $this->load->model("Mdls/MdlProjectKomponenBiayaDetailsRabSubTambahan");
        $bt = new MdlProjectKomponenBiayaDetailsRabSubTambahan();

        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($this->configUi[$this->jenisTr]['selectedPrice']) ? $this->configUi[$this->jenisTr]['selectedPrice'] : array();
        $lockerConfig = isset($this->configUi[$this->jenisTr]['lockerCheck']) ? $this->configUi[$this->jenisTr]['lockerCheck'] : array();
        $subAmountConfig = isset($this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1]) ? $this->configUi[$this->jenisTr]['shoppingCartAmountValue'][1] : null;
        $mainClonerConfig = isset($this->configUi[$this->jenisTr]['mainCloner']['items']) ? $this->configUi[$this->jenisTr]['mainCloner']['items'] : array();
        $handlerTmp = explode("/", $this->configUi[$this->jenisTr]['selectorProcessor']);
        $handler = $handlerTmp[0];

        $handler2 = $this->configUi[$this->jenisTr]['selectorProcessor2'];

        //biaya used
        $this->load->model("Mdls/MdlProjectSubTasklistKomposisi");
        $stlkSup = new MdlProjectSubTasklistKomposisi();
        $stlkSup->setFilters(array());
        $stlkSup->addFilter("no_spk='$no_spk'");
        $stlkSup->addFilter("status=1");
        $stlkSup->addFilter("trash=0");
        $stlkSup->addFilter("jenis='supplies'");
        $sub_tasklist_komposisi = $stlkSup->lookUpAll()->result();

        $usedSuppliesBiaya = array();
        if (!empty($sub_tasklist_komposisi)) {
            foreach ($sub_tasklist_komposisi as $key => $subKom) {
                $usedSuppliesBiaya[$subKom->biaya_id][$subKom->biaya_dasar_id] = $subKom;
            }
        }

        // tambahan
        $this->load->model("Mdls/MdlProjectSubTasklistKomposisiTambahan");
        $stlkSupTmb = new MdlProjectSubTasklistKomposisiTambahan();
        $stlkSupTmb->setFilters(array());
        $stlkSupTmb->addFilter("no_spk='$no_spk'");
        $stlkSupTmb->addFilter("status=1");
        $stlkSupTmb->addFilter("trash=0");
        $stlkSupTmb->addFilter("jenis='supplies'");
        $sub_tasklist_komposisiTmb = $stlkSupTmb->lookUpAll()->result();

        if (!empty($sub_tasklist_komposisiTmb)) {
            foreach ($sub_tasklist_komposisiTmb as $key => $subKom) {
                $usedSuppliesBiaya[$subKom->biaya_id][$subKom->biaya_dasar_id] = $subKom;
            }
        }


        $b->setFilters(array());
        $bt->setFilters(array());

        if ($no_spk != 0) {
            $b->addFilter("no_spk=$no_spk");
            $b->addFilter("jenis='biaya'");
            $bt->addFilter("no_spk=$no_spk");
            $bt->addFilter("jenis='biaya'");
        }
        $tmpB0 = $b->lookUpAll()->result();
        $tmpB1 = $bt->lookUpAll()->result();

        $tmpB = array_merge($tmpB0, $tmpB1);

        if (sizeof($tmpBK) > 0) {
            $arrItems = array();
            $arrItems2 = array();
            foreach ($tmpBK as $row1) {
                $arrItems[] = $row1;
            }
            foreach ($tmpB as $row) {
                $arrItems2[$row->biaya_id][$row->biaya_dasar_id] = $row;
            }

            unset($_SESSION[$cCode]['items']);
            unset($_SESSION[$cCode]['items2']);
            //items
            foreach ($arrItems as $row) {
                $id = $row->produk_dasar_id;
                $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";
                $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                if ((!array_key_exists($id, $_SESSION[$cCode]['items']))) {
                    cekHitam(":: MASUK ATAS ::");
                    //baca dari config untuk yang wajib diisi/ mandatory
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $handler,
                        "id" => $id,
                        "nama" => $row->produk_dasar_nama,
                        "cat_id" => $row->cat_id,
                        "cat_nama" => $row->cat_nama,
                        "jml" => $row->jml,
                        "harga" => $row->harga,
                        "subtotal" => $row->jml * $row->harga,
                        "rekening" => isset($row->cat_nama) ? $row->cat_nama : "",
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
                        foreach ($itemNumLabels as $key => $label) {
                            if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                $newValue = $_GET[$key];
                                $tmp[$key] = $newValue;
                                $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                            }
                        }
                        $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                    }

                    if (isset($arrItems2[$id])) {
                        foreach ($arrItems2[$id] as $biaya_dasar_id => $row2) {

                            $id = $row2->biaya_id;
                            $id_dasar = $row2->biaya_dasar_id;
                            $satuan = (isset($row2->satuan) && strlen($row2->satuan) > 0) ? $row2->satuan : "n/a";
                            $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                            if ((!array_key_exists($id_dasar, $_SESSION[$cCode]['items2'][$id]))) {
                                cekHitam(":: MASUK ATAS ::");
                                //baca dari config untuk yang wajib diisi/ mandatory
                                $tmp = array(
                                    "handler" => $this->uri->segment(1) . "/" . $handler2,
                                    "editTarget" => MODUL_PATH . $handler2 . "/" . $this->uri->segment(4),
                                    "id" => $id,
                                    "biaya_dasar_id" => $id_dasar,
                                    "biaya_dasar_nama" => $row2->biaya_dasar_nama,
                                    "biaya_id" => $row2->biaya_id,
                                    "biaya_nama" => $row2->biaya_nama,
                                    "nama" => $row2->biaya_dasar_nama,
                                    "project_id" => $_SESSION[$cCode]['main']["pihakProjekID"],
                                    "project_nama" => $_SESSION[$cCode]['main']["pihakProjekName"],
                                    "place2ID" => $_SESSION[$cCode]['main']["placeID"],
                                    "gudangID" => $_SESSION[$cCode]['main']["gudangID"],
                                    "no_spk" => $_SESSION[$cCode]['main']["pihakWoProjekSpk"],
                                    "wo_id" => $_SESSION[$cCode]['main']["pihakWoProjek"],
                                    "wo_nama" => $_SESSION[$cCode]['main']["pihakWoProjekName"],
                                    "project_employee" => $_SESSION[$cCode]['main']["pihakWoProjekEmployee"],
                                    "project_employee_nama" => $_SESSION[$cCode]['main']["pihakWoProjekEmployeeName"],
                                    "no_spk" => $_SESSION[$cCode]['main']["pihakWoProjekSpk"],
                                    "jml_ori" => $row2->jml,
                                    "jml" => isset($usedSuppliesBiaya[$row2->biaya_id][$id_dasar]) ? $usedSuppliesBiaya[$row2->biaya_id][$id_dasar]->jml : $row2->jml,
                                    "harga" => $row2->harga,
                                    "cat_id" => $row2->cat_id,
                                    "cat_nama" => $row2->cat_nama,
                                    "subtotal" => isset($usedSuppliesBiaya[$row2->biaya_id][$id_dasar]) ? $usedSuppliesBiaya[$row2->biaya_id][$id_dasar]->jml * $row2->harga : $row2->jml * $row2->harga,
                                    "rekening" => isset($row2->rekening) ? $row2->rekening : "",
                                );

                                foreach ($fieldSrcs as $key => $src) {
                                    $tmp[$key] = makeValue($src, $tmp, $tmp, isset($row2->$key) ? $row2->$key : 0);
                                }

                                //region perhitungan subtotal items
                                if ($subAmountConfig != null) {
                                    $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                                }
                                else {
                                    $subtotal = 0;
                                }
                                $tmp["subtotal"] = $subtotal;
                                $_SESSION[$cCode]['items2'][$id][$id_dasar] = $tmp;
                                //endregion
                                if (sizeof($itemNumLabels) > 0) {
                                    foreach ($itemNumLabels as $key => $label) {
                                        if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                            $newValue = $_GET[$key];
                                            $tmp[$key] = $newValue;
                                            $_SESSION[$cCode]['items2'][$id][$id_dasar][$key] = $newValue;
                                        }
                                    }
                                    $_SESSION[$cCode]['items2'][$id][$id_dasar]['subtotal'] = ($_SESSION[$cCode]['items2'][$id][$id_dasar]['jml'] * $_SESSION[$cCode]['items2'][$id][$id_dasar]['harga']);
                                }
                            }
                            if (sizeof($mainClonerConfig) > 0) {
                                foreach ($mainClonerConfig as $key => $val) {
                                    $_SESSION[$cCode]['main'][$key] = $row2->$val;
                                }
                            }
                        }
                    }
                    else {

//                        arrprintWebs($row);
                        $id = $row->produk_dasar_id;
                        $id_dasar = $row->produk_dasar_id;
                        $satuan = (isset($row->satuan) && strlen($row->satuan) > 0) ? $row->satuan : "n/a";
                        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array("nama" => "nama");
                        if ((!array_key_exists($id_dasar, $_SESSION[$cCode]['items2'][$id]))) {
                            cekHitam(":: MASUK ATAS ::");
                            //baca dari config untuk yang wajib diisi/ mandatory
                            $tmp = array(
                                "handler" => $this->uri->segment(1) . "/" . $handler2,
                                "editTarget" => MODUL_PATH . $handler2 . "/" . $this->uri->segment(4),
                                "id" => $id,
                                "biaya_dasar_id" => $id_dasar,
                                "biaya_dasar_nama" => $row->produk_dasar_nama,
                                "biaya_id" => $row->produk_dasar_id,
                                "biaya_nama" => $row->produk_dasar_nama,
                                "nama" => $row->produk_dasar_nama,
                                "project_id" => $_SESSION[$cCode]['main']["pihakProjekID"],
                                "project_nama" => $_SESSION[$cCode]['main']["pihakProjekName"],
                                "place2ID" => $_SESSION[$cCode]['main']["placeID"],
                                "gudangID" => $_SESSION[$cCode]['main']["gudangID"],
                                "no_spk" => $_SESSION[$cCode]['main']["pihakWoProjekSpk"],
                                "wo_id" => $_SESSION[$cCode]['main']["pihakWoProjek"],
                                "wo_nama" => $_SESSION[$cCode]['main']["pihakWoProjekName"],
                                "project_employee" => $_SESSION[$cCode]['main']["pihakWoProjekEmployee"],
                                "project_employee_nama" => $_SESSION[$cCode]['main']["pihakWoProjekEmployeeName"],
                                "no_spk" => $_SESSION[$cCode]['main']["pihakWoProjekSpk"],
                                "jml_ori" => $row->jml,
                                "jml" => $row->jml,
                                "harga" => $row->harga,
                                "cat_id" => $row->cat_id,
                                "cat_nama" => $row->cat_nama,
                                "subtotal" => $row->jml * $row->harga,
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
                            $_SESSION[$cCode]['items2'][$id][$id_dasar] = $tmp;
                            //endregion
                            if (sizeof($itemNumLabels) > 0) {
                                foreach ($itemNumLabels as $key => $label) {
                                    if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                        $newValue = $_GET[$key];
                                        $tmp[$key] = $newValue;
                                        $_SESSION[$cCode]['items2'][$id][$id_dasar][$key] = $newValue;
                                    }
                                }
                                $_SESSION[$cCode]['items2'][$id][$id_dasar]['subtotal'] = ($_SESSION[$cCode]['items2'][$id][$id_dasar]['jml'] * $_SESSION[$cCode]['items2'][$id][$id_dasar]['harga']);
                            }
                        }
                        if (sizeof($mainClonerConfig) > 0) {
                            foreach ($mainClonerConfig as $key => $val) {
                                $_SESSION[$cCode]['main'][$key] = $row->$val;
                            }
                        }

                    }
                }
                if (sizeof($mainClonerConfig) > 0) {
                    foreach ($mainClonerConfig as $key => $val) {
                        $_SESSION[$cCode]['main'][$key] = $row->$val;
                    }
                }
            }

//            cekMerah($no_spk);
//            arrPrint($arrItems);
//            arrPrintWebs($_SESSION[$cCode]['items']);
//            matiHere(__LINE__);

            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $_SESSION[$cCode]['main']['harga'] = 0;
                $ctr = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                    $_SESSION[$cCode]['main'][str_replace(' ', '_', $iSpec['rekening'])] = $iSpec['harga'];
                }
            }

            $subcat_ = array();
            if (sizeof($_SESSION[$cCode]['items2']) > 0) {
                foreach ($_SESSION[$cCode]['items2'] as $xid => $dSpec) {
                    foreach ($dSpec as $iSpec) {
                        $id = $iSpec['id'];
                        $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                        $catnama_ = str_replace(" ", "_", $iSpec["cat_nama"]);
                        if (!isset($subcat_[$catnama_])) {
                            $subcat_[$catnama_] = 0;
                        }
                        $subcat_[$catnama_] += $iSpec["subtotal"] * 1;
                        if (!isset($subcat_["piutang_cabang"])) {
                            $subcat_["piutang_cabang"] = 0;
                        }
                        $subcat_["piutang_cabang"] += $iSpec["subtotal"] * 1;
                    }
                }
                foreach ($subcat_ as $key => $val) {
                    $_SESSION[$cCode]['main'][$key] = $val;
                }
            }
        }

    }

    public function selectSubBiaya()
    {
        $this->load->library("FieldCalculator");
        $cCode = "_TR_" . $this->jenisTr;

        $biaya_id = isset($_GET['biaya_id']) ? $_GET['biaya_id'] : die(toAlert("biaya id tidak dikirim"));
        $sub_biaya_id = isset($_GET['sub_biaya_id']) ? $_GET['sub_biaya_id'] : die(toAlert("sub biaya id tidak dikirim"));
        $key = $_GET['key'];

        switch ($key) {
            case "jml":
                $_SESSION[$cCode]['items2'][$biaya_id][$sub_biaya_id][$key] = trim($_GET[$key]);
                $_SESSION[$cCode]['items2'][$biaya_id][$sub_biaya_id]["subtotal"] = ($_GET[$key] * 1) * $_SESSION[$cCode]['items2'][$biaya_id][$sub_biaya_id]["harga"];
                break;
            case "harga":
                $_SESSION[$cCode]['items2'][$biaya_id][$sub_biaya_id][$key] = trim($_GET[$key]);
                $_SESSION[$cCode]['items2'][$biaya_id][$sub_biaya_id]["subtotal"] = ($_GET[$key] * 1) * $_SESSION[$cCode]['items2'][$biaya_id][$sub_biaya_id]["jml"];
                break;
            default:
                break;
        }

        $subcat = array();
        if (isset($_SESSION[$cCode]["items2"])) {
            foreach ($_SESSION[$cCode]["items2"] as $by_id => $data_0) {
                foreach ($data_0 as $by_drs_id => $data_1) {
                    $catnama = str_replace(" ", "_", $data_1["cat_nama"]);
                    if (!isset($subcat[$catnama])) {
                        $subcat[$catnama] = 0;
                    }
                    $subcat[$catnama] += $data_1["subtotal"] * 1;
                    if (!isset($subcat["piutang_cabang"])) {
                        $subcat["piutang_cabang"] = 0;
                    }
                    $subcat["piutang_cabang"] += $data_1["subtotal"] * 1;
                }
            }
            foreach ($subcat as $key => $val) {
                $_SESSION[$cCode]["main"][$key] = $val;
            }
        }

        echo "<script>

            top.getData('" . MODUL_PATH . "_shoppingCart/buildValues/" . $this->jenisTr . "?ohyes=ohno','shopping_cart');

        </script>";
    }

    public function selectProjek()
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
//            matiHere(__LINE__);

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
        echo "top.document.getElementById('pihakName').value='" . $tmpPihakName . "';";
        echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "</script>";
    }

    public function selectWoProjek()
    {

        $cCode = "_TR_" . $this->jenisTr;
        $tr = $this->uri->segment(4);
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $autoSelectItem2 = isset($this->configUi[$this->jenisTr]['autoSelectItem2']) ? $this->configUi[$this->jenisTr]['autoSelectItem2'] : false;
        if (isset($_SESSION[$cCode]['main']['pihak2Mdl'])) {
            $mdlName = $_SESSION[$cCode]['main']['pihak2Mdl'];
        }
        else {
            $mdlName = $this->uri->segment(5);
        }

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();

//        arrPrint($tmpB);
//        showlast_query("biru");
//        matiHere(__LINE__);

        $arrCekKolom = array(
            "nomer" => array(
                "pihakMainNota",
                "pihakExternNota"
            ),
            "name" => array("pihakProjekName"),
            "nama" => array("pihakProjekName"),
        );
        $selectColumn = "produk_nama";
        foreach ($arrCekKolom as $keyCek => $valueCeks) {
            foreach ($valueCeks as $valueCek) {
                if (isset($this->configUi[$this->jenisTr][$valueCek]) && $this->configUi[$this->jenisTr][$valueCek] == true) {
                    $selectColumn = $keyCek;
                    break;
                }
            }
        }

//        arrPrint($tmpB);
//        matiHere();
        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihakWoProjek'] = isset($tmpB[0]->id) ? $tmpB[0]->id : 0;
            $_SESSION[$cCode]['main']['pihakWoProjekName'] = isset($tmpB[0]->produk_nama) ? $tmpB[0]->produk_nama : 0;
            $_SESSION[$cCode]['main']['pihakWoProjekSpk'] = isset($tmpB[0]->no_spk) ? $tmpB[0]->no_spk : 0;
            $_SESSION[$cCode]['main']['pihakWoProjekEmployee'] = isset($tmpB[0]->employee_id) ? $tmpB[0]->employee_id : 0;
            $_SESSION[$cCode]['main']['pihakWoProjekEmployeeName'] = isset($tmpB[0]->employee_nama) ? $tmpB[0]->employee_nama : "no-name";
            //-------

            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->no_spk)) {
                $tmpPihakName .= " (" . $tmpB[0]->no_spk . ")";
            }

            if (isset($pihakMainValueSrc) && sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }


            if ($autoSelectItem2) {
                $availData = $this->autoSelectItem($tmpB[0]->no_spk);
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

        echo "<script>
            top.document.getElementById('pihak2Name').value='" . $tmpPihakName . "';
            top.document.getElementById('pilihan_outlet2').innerHTML='';
            top.getData('" . MODUL_PATH . "_shoppingCart/buildValues/" . $this->jenisTr . "?ohyes=ohno','shopping_cart');
        </script>";

//        echo "<script>";
//        echo "top.document.getElementById('pihak2Name').value='" . $tmpPihakName . "';";
//        echo "top.document.getElementById('pilihan_outlet2').innerHTML='';";
//        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
//        echo "</script>";
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

    private function autoSelectItemReguler()
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
// arrPrint($selectorSrcModel);
// matiHere("DEBUG MODE PLEASE WAIT");
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
//                    cekHitam(":: MASUK ATAS ::");
                    //baca dari config untuk yang wajib diisi/ mandatory

                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $handler,
                        "id" => $id,
                        "jml" => 1,
                        "harga" => 0,
                        "subtotal" => 0,
                        "rekening" => isset($row->rekening) ? $row->rekening : "",
                        "disabled" => isset($row->disabled) ? $row->disabled : 0,
                    );
                    if (isset($row->extern_coa) && $row->extern_coa > 0) {
                        $tmp["rek_coa"] = $row->rek_coa;
                        $tmp["extern_coa"] = $row->extern_coa;
                    }

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
                        $_SESSION[$cCode]['main'][str_replace(' ', '_', $iSpec['rekening'])] = $iSpec['harga'];
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

//                    $_SESSION[$cCode]['main'][str_replace(' ', '_', $iSpec['rekening'])] = $iSpec['harga'];


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

    //--------------------------
    public function selectOptionFreelancer()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $pihakValidate = isset($this->configUi[$this->jenisTr]['pihakValidate']) ? $this->configUi[$this->jenisTr]['pihakValidate'] : array();
        $pihakAddValidate = isset($this->configUi[$this->jenisTr]['pihakAddValidate']) ? $this->configUi[$this->jenisTr]['pihakAddValidate'] : array();
        $autoSelectItem = isset($this->configUi[$this->jenisTr]['autoSelectItem']) ? $this->configUi[$this->jenisTr]['autoSelectItem'] : false;
        $autoSelectItemNonProject = isset($this->configUi[$this->jenisTr]['autoSelectItemNonProject']) ? $this->configUi[$this->jenisTr]['autoSelectItemNonProject'] : false;
        $pihakKoreksiData = isset($this->configUi[$this->jenisTr]['pihakKoreksiData']) ? $this->configUi[$this->jenisTr]['pihakKoreksiData'] : array();
        $optionFreelancerReset = isset($this->configUi[$this->jenisTr]['optionFreelancerReset']) ? $this->configUi[$this->jenisTr]['optionFreelancerReset'] : array();


        $cCode = $this->cCode;
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

//        if($id != 1){
        // reset detail freelancer akrena tidak menimbulkan hutang komisi
        if (sizeof($optionFreelancerReset) > 0) {
            foreach ($optionFreelancerReset as $el_reset) {
                if (isset($_SESSION[$cCode]["main_elements"][$el_reset])) {
                    $_SESSION[$cCode]["main_elements"][$el_reset] = NULL;
                    unset($_SESSION[$cCode]["main_elements"][$el_reset]);
//                    cekHitam("reset $el_reset " . __LINE__);
                }
                // mereset yang ada di main
                if (isset($_SESSION[$cCode]["main"])) {
                    foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                        if (strpos($key_reset, $el_reset) !== false) {
//                            cekkuning("$key_reset mengandung kata $el_reset dan harus direset");
                            unset($_SESSION[$cCode]["main"][$key_reset]);

                        }
                    }
                }
            }
        }
        if (isset($_SESSION[$cCode]["items4_sum"])) {
            $_SESSION[$cCode]["items4_sum"] = array();
            $_SESSION[$cCode]["main"]["nilai_kas_cn_detail"] = 0;
        }
//        }


        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        if (sizeof($tmpB) > 0) {

            $_SESSION[$cCode]['main']['optionFreelancerID'] = $id;
            $_SESSION[$cCode]['main']['optionFreelancerName'] = isset($tmpB[0]->name) ? $tmpB[0]->name : "";
            $_SESSION[$cCode]['main']['optionFreelancerName2'] = isset($tmpB[0]->name) ? formatNota("name", $tmpB[0]->name) : "";
            $_SESSION[$cCode]['main']['optionFreelancerKode'] = isset($tmpB[0]->kode) ? $tmpB[0]->kode : "";
//            $_SESSION[$cCode]['main']['optionFreelancerName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
//            $_SESSION[$cCode]['main']['optionFreelancerName2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
//            $_SESSION[$cCode]['main']['pihakDisc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
//            $_SESSION[$cCode]['main']['pihakJenis'] = isset($tmpB[0]->folder_id) ? $tmpB[0]->folder_id : 0;

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
//            echo "<script>";
//            echo "top.document.getElementById('pihakName').value='" . $tmpPihakName . "';";
//            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
//            echo "</script>";
        }
        else {

            $_SESSION[$cCode]['main']['optionFreelancerID'] = $id;
            $_SESSION[$cCode]['main']['optionFreelancerName'] = "";
            $_SESSION[$cCode]['main']['optionFreelancerName2'] = "";
            $_SESSION[$cCode]['main']['optionFreelancerKode'] = 0;

        }


        echo "<script>";
        echo "top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "top.location.reload();";
        echo "</script>";
    }

    public function selectPihakFreelancer()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc'] : array();
        $pihakValidate = isset($this->configUi[$this->jenisTr]['pihakValidate']) ? $this->configUi[$this->jenisTr]['pihakValidate'] : array();
        $pihakAddValidate = isset($this->configUi[$this->jenisTr]['pihakAddValidate']) ? $this->configUi[$this->jenisTr]['pihakAddValidate'] : array();
        $autoSelectItem = isset($this->configUi[$this->jenisTr]['autoSelectItem']) ? $this->configUi[$this->jenisTr]['autoSelectItem'] : false;
        $autoSelectItemNonProject = isset($this->configUi[$this->jenisTr]['autoSelectItemNonProject']) ? $this->configUi[$this->jenisTr]['autoSelectItemNonProject'] : false;
        $pihakKoreksiData = isset($this->configUi[$this->jenisTr]['pihakKoreksiData']) ? $this->configUi[$this->jenisTr]['pihakKoreksiData'] : array();
        $optionFreelancerReset = isset($this->configUi[$this->jenisTr]['optionFreelancerReset']) ? $this->configUi[$this->jenisTr]['optionFreelancerReset'] : array();
        $freelancerData = isset($this->configUi[$this->jenisTr]['freelancerData']) ? $this->configUi[$this->jenisTr]['freelancerData'] : array();
        if (sizeof($freelancerData) > 0) {
            $freelancerHeaders = $freelancerData["headers"];
        }

        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);
        $mdlKey = $this->uri->segment(6);
        $idkolom = $this->uri->segment(7);

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
//        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
//            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
//        }
//
        //-----------------------------------------------------

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        if (sizeof($tmpB) > 0) {

            $_SESSION[$cCode]['items4_sum'][$idkolom]['id'] = $id;
            $_SESSION[$cCode]['items4_sum'][$idkolom]['nama'] = isset($tmpB[0]->nama) ? $tmpB[0]->nama : "";
            $_SESSION[$cCode]['items4_sum'][$idkolom]['name'] = isset($tmpB[0]->nama) ? $tmpB[0]->nama : "";
            foreach ($freelancerHeaders as $aa => $bb) {
                if (isset($tmpB[0]->$aa)) {
                    $_SESSION[$cCode]['items4_sum'][$idkolom][$aa] = $tmpB[0]->$aa;
                }
            }
//            $_SESSION[$cCode]['main']['optionFreelancerName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
//            $_SESSION[$cCode]['main']['optionFreelancerName2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
//            $_SESSION[$cCode]['main']['pihakDisc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
//            $_SESSION[$cCode]['main']['pihakJenis'] = isset($tmpB[0]->folder_id) ? $tmpB[0]->folder_id : 0;

            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }

//            if (sizeof($pihakMainValueSrc) > 0) {
//                foreach ($pihakMainValueSrc as $key => $src) {
//                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                }
//            }


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
//            echo "<script>";
//            echo "top.document.getElementById('pihakName').value='" . $tmpPihakName . "';";
//            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
//            echo "</script>";
        }
        else {

            $_SESSION[$cCode]['items4_sum'][$idkolom]['id'] = $id;
            $_SESSION[$cCode]['items4_sum'][$idkolom]['nama'] = "";
            $_SESSION[$cCode]['items4_sum'][$idkolom]['name'] = "";

        }


        echo "<script>";
        echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "</script>";
    }


}



