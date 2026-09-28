<?php

require_once "Modul_Controller.php";

class _processPihakMainRules extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);
        $cCode = $this->cCode;

    }

    public function select()
    {

        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrcRules']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrcRules'] : array();
        $noResetGate = isset($this->configUi[$this->jenisTr]['noResetGate']) ? $this->configUi[$this->jenisTr]['noResetGate'] : true;

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
        $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        //-----------------------------------------------------

        if (sizeof($tmpB) > 0) {
            if ($id == "pm") {
                $ppnFactor = 10;
            }
            else {
                $ppnFactor = 0;
            }
            if ($noResetGate == true) {
                unset($_SESSION[$cCode]['items']);
            }

            $_SESSION[$cCode]['main']['pihakMainRulesID'] = $id;
            $_SESSION[$cCode]['main']['ppn_persen_dipakai'] = $ppnFactor;
            $_SESSION[$cCode]['main']['pihakMainRulesName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";

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
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakRules').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_rules').innerHTML='';";
            echo "  }";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakMainRulesID'] = $id;
            $_SESSION[$cCode]['main']['pihakRulesID'] = "";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakRulesID').value='" . $_SESSION[$cCode]['main']['pihakMainRulesName'] . "';";
            echo "top.document.getElementById('pilihan_rules').innerHTML='';";
            echo "  }";
            echo "</script>";
        }
    }

    public function remove()
    {
        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $_SESSION[$cCode]['main']['pihakMainRulesID'] = null;
        $_SESSION[$cCode]['main']['pihakMainRulesName'] = null;
        $_SESSION[$cCode]['main']['pihakMainNameRules'] = null;

//        $_SESSION[$cCode]['main']['pihakMdlName'] = null;
        unset($_SESSION[$cCode]['main']['pihakMainRulesID']);
        unset($_SESSION[$cCode]['main']['pihakMainRulesName']);
        unset($_SESSION[$cCode]['main']['pihakMainNameRules']);
        unset($_SESSION[$cCode]['items']);

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
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );

        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        // matiHere(__METHOD__ . __LINE__);
        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
//        $_SESSION[$cCode]['out_master']['pihakID'] = null;
//        $_SESSION[$cCode]['out_master']['pihakName'] = null;
//        unset($_SESSION[$cCode]['out_master']['pihakID']);
//        unset($_SESSION[$cCode]['out_master']['pihakName']);
    }


    public function selectReference()
    {
        $pihakMainFiltersRulesAdd = isset($this->configUi[$this->jenisTr]['pihakMainFiltersRulesAdd']) ? $this->configUi[$this->jenisTr]['pihakMainFiltersRulesAdd'] : array();
        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrcRules']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrcRules'] : array();
        $noResetGate = isset($this->configUi[$this->jenisTr]['noResetGate']) ? $this->configUi[$this->jenisTr]['noResetGate'] : true;

        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $date = isset($_GET['date']) ? $_GET['date'] : NULL;
        $mdlName = $this->uri->segment(5);

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        if($id > 0){
            $tmpB = $b->lookupByID($id)->result();
        }
        elseif($date != NULL){
            if (sizeof($pihakMainFiltersRulesAdd) > 0) {
                if (isset($pihakMainFiltersRulesAdd["enabled"]) && ($pihakMainFiltersRulesAdd["enabled"] == true)) {
                    $pihakMainFiltersRulesAddFilter = isset($pihakMainFiltersRulesAdd["filter"]) ? $pihakMainFiltersRulesAdd["filter"] : array();
                    $exception = isset($pihakMainFiltersRulesAdd["exception"]) ? $pihakMainFiltersRulesAdd["exception"] : array();
                    if (sizeof($pihakMainFiltersRulesAddFilter) > 0) {
//                        foreach ($pihakMainFiltersRulesAddFilter as $f) {
//                            $f_ex = explode("=", $f);
//                            if (!isset($f_ex[1])) {
//                                $f_ey = explode(">", $f_ex[0]);
//                                if (substr($f_ey[1], 0, 1) == ".") {
//                                    $b->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
//                                }
//                                else {
//                                    $b->addFilter($f_ey[0] . ">'" . $_SESSION['cCode']['main'][$f_ey[1]] . "'");
//                                }
//                            }
//                            else {
//                                if (substr($f_ex[1], 0, 1) == ".") {
//                                    $b->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
//                                }
//                                else {
//                                    if (!isset($_SESSION[$cCode]['main'][$f_ex[1]]) || ($_SESSION[$cCode]['main'][$f_ex[1]] == "") || ($_SESSION[$cCode]['main'][$f_ex[1]] == 0)) {
//
//                                    }
//                                    else {
//                                        if (!in_array($_SESSION[$cCode]['main'][$f_ex[1]], $exception)) {
//                                            $b->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
//                                        }
//                                    }
//                                }
//                            }
//                        }
                        makeFilter($pihakMainFiltersRulesAddFilter, $_SESSION[$cCode]["main"], $b);
                    }
                    else{
                        cekMerah("tidak ada filter");
                    }
                }
            }
            $b->addFilter("fulldate='$date'");
            $tmpB = $b->lookupAll()->result();
        }
        else{
            $tmpB = array();
        }
//        showLast_query("biru");
        if (isset($this->configUi[$this->jenisTr]["pihakMainNota"]) && $this->configUi[$this->jenisTr]["pihakMainNota"] == true) {
            $selectColumn = "nomer";
        }
        else {
            $selectColumn = "nama";
        }

//showLast_query("biru");
//mati_disini(__LINE__ . " === " . count($tmpB));
        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        //-----------------------------------------------------

        if (sizeof($tmpB) > 0) {
            $fieldSrcs2_sum = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc2_sum']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc2_sum'] : array("nama" => "nama");
            foreach ($tmpB as $row) {
                if($date != NULL){
                    $id = $row->id;
                }
                if (!array_key_exists($id, $_SESSION[$cCode]['items2_sum'])) {
                    $tmp = array(
                        "handler" => $this->uri->segment(1) . "/" . $this->uri->segment(2),
                        "id" => $id,
                        "jml" => 1,
                        "qty" => 1,
//                        "harga" => 0,
//                        "nilai_untung" => 0,
//                        "nilai_rugi" => 0,
//                        "nilai_final_rugilaba" => 0,
                        "subtotal" => 0,
                        "nama" => $row->nomer,
                        "name" => $row->nomer,
                    );
                    foreach ($fieldSrcs2_sum as $key => $src) {
//                        cekHere($row->$src . " " . $src);
                        $tmp[$key] = makeValue($src, $tmp, $tmp, $row->$src);
                    }
                    if ($subAmountConfig != null) {
                        $tmp['subtotal'] = makeValue($subAmountConfig, $tmp, $_SESSION[$cCode]['items2_sum'][$id], 0);
                    }
                    else {
                        $tmp['subtotal'] = 0;
                    }
//                    arrPrint($tmp);
                    $_SESSION[$cCode]['items2_sum'][$id] = $tmp;
                    // custom cabang2_id == cabang_id dari nota
                    $_SESSION[$cCode]['main']['cabang2_id'] = $row->cabang_id;
                    $_SESSION[$cCode]['main']['cabang2_nama'] = $row->cabang_nama;
                }
            }

//            mati_disini(__LINE__);
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
                "stepNumber" => 1,
                "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }
        else {
//            $_SESSION[$cCode]['main']['pihakMainRulesID'] = $id;
//            $_SESSION[$cCode]['main']['pihakRulesID'] = "";
//            if (sizeof($pihakMainValueSrc) > 0) {
//                foreach ($pihakMainValueSrc as $key => $src) {
//                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
//                }
//            }
            echo "<script>";
            echo "  if(top.document.getElementById('shopping_cart')){";
            echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "  }";
            echo "</script>";
        }
    }


    public function removeReference()
    {
        $cCode = $this->cCode;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if(isset($_SESSION[$cCode]['items2_sum'][$id])){
            $_SESSION[$cCode]['items2_sum'][$id] = NULL;
            unset($_SESSION[$cCode]['items2_sum'][$id]);
        }

//        mati_disini(__LINE__);
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
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);


        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";

    }


}