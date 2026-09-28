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
        $pihakPair = isset($this->configUi[$this->jenisTr]['pihakPair']) ? $this->configUi[$this->jenisTr]['pihakPair'] : array();
        $kreditValidation = isset($this->configUi[$this->jenisTr]["kreditValidation"]) ? $this->configUi[$this->jenisTr]["kreditValidation"] : false;

        $cCode = $this->cCode;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor");

        // matiHere(__LINE__);
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();
        // showLast_query("biru");
        // arrPrintWebs($tmpB);

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
        // arrPrintHijau($requiredParams);

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
        // cekHere($hasil);
        // matiHere(__LINE__);

        $msg = "<h3>INFO:</h3>";
        $msg .= "Data <b class='text-uppercase'>$cuNama</b> &nbsp;($id)<br> " . $hasil;
        $msg .= "<br> <span class='text-red'>Masih belum lengkap.</span>";
        $msg .= "<br><br> Supaya bisa masuk pada transaksi yang akan dibuat, lengkapi dahulu dari menu data customer, baru kemudian pilih ulang.";
        $msg .= "<br> Video tutorial bisa dilihat dari icon video kanan atas";
        // $msg .= "<br> <button type='button' class='btn btn-danger' onclick=\"$xx\">lanjut</button>";

        if (strlen($hasil) > 1) {
            echo cekAlert($msg);

            // $linkEdit = base_url() . "/statik/Data/edit/Customer/$id";
            // $xx = modalDialogBtn("cek",$linkEdit);
            // $alert = array(
            //     "html" => $msg,
            // );
            // echo swalAlertGoTo($alert, "sss","Perbaiki Sekarang","Nanti Saja");
        }
        //---------------------------------------------------
        /** ------------------------------------------------------
         * cek kredi tlimit
         * ------------------------------------------------------*/
        $this->load->model("Mdls/MdlPaymentSource");
        $tcr = new MdlPaymentSource();
        $scrTcr = $tcr->getHutangTertua($id);
        $nomerFirstHutang = $scrTcr->nomer;
        $dtimeFirstHutang = $scrTcr->dtime;
        $umurFirsthutang = !empty($scrTcr->dtime) ? umurDay($dtimeFirstHutang) : 0;
        showLast_query("kuning");
        // cekBiru("$umurFirsthutang / $dtimeFirstHutang");
        //
        // matiHere(__LINE__);

        $this->load->model("Coms/ComRekeningPembantuCustomer");
        $cr = new ComRekeningPembantuCustomer();
        $limits = $cr->fetchBalancesCustomer($id);
        showLast_query("biru");
        arrPrint($limits[0]["debet_total"]);
        $sisa_hutang = $limits[0]["debet_total"];
        $sisa_hutang_f = number_format($sisa_hutang, 0, ',', '.');
        $kredit_limit = $tmpB[0]->kredit_limit;
        $pihakKreditSaldo = $kredit_limit - $sisa_hutang;
        //------------------
        // $kredit_limit = 240000000;
        // $umurFirsthutang = 1;
        //------------------
        $kredit_limit_alert= $kredit_limit - (10/100 * $kredit_limit);
        $kredit_limit_f = number_format($kredit_limit, 0, ',', '.');

        // cekPink("$sisa_hutang > $kredit_limit");
        if ($sisa_hutang > $kredit_limit) {
            $kredit_limit_notif = "Sisa hutang anda ($sisa_hutang_f) sudah MELEBIHI kredit limit ($kredit_limit_f)";
        }
        elseif ($sisa_hutang == $kredit_limit) {
            $kredit_limit_notif = "Sisa hutang anda ($sisa_hutang_f) sudah MENCAPAI kredit limit ($kredit_limit_f)";
        }
        else {
            $kredit_limit_notif = "Sisa hutang anda ($sisa_hutang_f) masih di bawah kredit limit ($kredit_limit_f)";
        }

        // if ($kredit_limit > 0) {
        //     echo cekAlert($kredit_limit_notif);
        // }

        // matiHere(__LINE__);

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

                    // cekHere(":: $kf => $vf :: $addMode ::");
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

        if (sizeof($pihakPair) > 0) {
            $pihakPairData = array();
            if (isset($pihakPair["enabled"]) && ($pihakPair["enabled"] == true)) {
                $pairModel = $pihakPair["model"];
                $this->load->model("Coms/$pairModel");
                $pm = New $pairModel();
                $pm->addFilter("extern_id='$id'");
                if (sizeof($pihakPair["filter"]) > 0) {
                    makeFilter($pihakPair["filter"], $_SESSION[$cCode]["main"], $pm);
                }
                $pmTmp = $pm->$pihakPair["method"]($pihakPair["rekening"]);
                showLast_query("biru");
                if (sizeof($pmTmp) > 0) {
//                    arrPrintPink($pmTmp);
                    $pihakPairData[$id]["saldo"] = $pmTmp[0]->$pihakPair["key"];
                }
            }
        }
        //---------------------------------------------------
//arrPrintWebs($pihakPairData);
//mati_disini(__LINE__);

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
        $vg->setPpnFactor($ppnFactor);
        if (sizeof($tmpB) > 0) {

            $_SESSION[$cCode]['main']['pihakID'] = $id;
            $_SESSION[$cCode]['main']['pihakLevel'] = $tmpB[0]->level_id;
            $_SESSION[$cCode]['main']['pihakName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            $_SESSION[$cCode]['main']['pihakName2'] = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            $_SESSION[$cCode]['main']['pihakDisc'] = isset($tmpB[0]->diskon) ? $tmpB[0]->diskon : "";
            $_SESSION[$cCode]['main']['kategoriID'] = isset($tmpB[0]->kategori_id) ? $tmpB[0]->kategori_id : 0;
            $_SESSION[$cCode]['main']['kategoriNama'] = isset($tmpB[0]->kategori_nama) ? $tmpB[0]->kategori_nama : "";
            $_SESSION[$cCode]['main']['kategoriName'] = isset($tmpB[0]->kategori_nama) ? $tmpB[0]->kategori_nama : "";

            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";
            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }

            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            // ----------------------------------------------
            // $kredit_limit = $tmpB[0]->kredit_limit;
            $duedays = $tmpB[0]->due_days;
            $pihakKreditAllowed = $kredit_limit > 0 && $duedays > $umurFirsthutang ? 1 : 0;

            $default_payment_method = $kredit_limit > 0 ? "credit" : "cash";
            if($kreditValidation){
                if($pihakKreditAllowed == 0 && $kredit_limit > 0){
                    if($pihakKreditSaldo < 0){
                        $default_payment_method = "none";
                    }
                }
            }

            // $default_payment_method = "none";
            // ---------------------------------------------
            $default_term_of_payment = $duedays > 0 ? $duedays : 0;
            // ---------------------------------------------

            // $_SESSION[$cCode]['main']['paymentMethod'] = 0;
            // $_SESSION[$cCode]['main']['defaultPaymentMethod'] = 0;
            // $_SESSION[$cCode]['main']['defaultTermOfPayment'] = 0;
            // $_SESSION[$cCode]['main']['pihakKreditLimit'] = 0;

            // -----------------------------------------------------------------------------------
            $_SESSION[$cCode]['main']['paymentMethod'] = $default_payment_method;
            $_SESSION[$cCode]['main']['defaultPaymentMethod'] = $default_payment_method;
            $_SESSION[$cCode]['main']['paymentMethod__name'] = $default_payment_method;
            // -----------------------------------------------------------------------------------
            $_SESSION[$cCode]['main']['defaultTermOfPayment'] = $default_term_of_payment;
            $_SESSION[$cCode]['main']['pihakKreditLimit'] = $kredit_limit;
            $_SESSION[$cCode]['main']['pihakKreditActive'] = $sisa_hutang;
            $_SESSION[$cCode]['main']['pihakKreditTanggalTertua'] = $dtimeFirstHutang;
            $_SESSION[$cCode]['main']['pihakKreditUmurTertua'] = $umurFirsthutang;
            $_SESSION[$cCode]['main']['pihakKreditNomerTertua'] = $nomerFirstHutang;
            $_SESSION[$cCode]['main']['pihakKreditAllowed'] = $pihakKreditAllowed;
            $_SESSION[$cCode]['main']['pihakKreditSaldo'] = $pihakKreditSaldo;

            //---------------------------------------------
            if (isset($pihakPairData[$id])) {
                $_SESSION[$cCode]['main']['pihakPoint'] = $pihakPairData[$id]["saldo"];
            }
            //---------------------------------------------


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
                "stepNumber" => 1,
                "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

//mati_disini("$kredit_limit ;;; $default_payment_method ;;; " . $_SESSION[$cCode]['main']['defaultPaymentMethod']);

            echo "<script>";
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
//            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
//            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
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
//            $_SESSION[$cCode]['out_master']['pihakID'] = $id;
//            $_SESSION[$cCode]['out_master']['pihakName'] = "default warehouse";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                    $_SESSION[$cCode]['out_master'][$key] = $tmpB[0]->$src;
                }
            }
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


            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
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
            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakName').value='" . $_SESSION[$cCode]['main']['pihakName2'] . "';";
            echo "top.document.getElementById('pilihan_outlet').innerHTML='';";
            echo "</script>";
        }
    }
}

