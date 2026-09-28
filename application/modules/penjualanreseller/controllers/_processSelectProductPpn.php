<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
require_once "Modul_Controller.php";

class _processSelectProductPpn extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->jenisTr = $this->uri->segment(4);
//        $cCode = "_TR_" . $this->jenisTr;
//        if (!isset($_SESSION[$cCode]['items'])) {
//            $_SESSION[$cCode]['items'] = array();
//        }

    }

    public function select()
    {
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $ppn = $_GET['ppn'];
        $ppnTargetItems = $_GET['ppnTargetItems'];
        $ppnTargetMain = $_GET['ppnTargetMain'];

        $overWriteVendor = isset($_GET['overWriteMain']) ? $_GET['overWriteMain'] : "ppnVendor";

        $cCode = $this->cCode;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");
        if (isset($_SESSION[$cCode]['items'])) {
            $newPpn = 10 * $ppn;
            if (isset($_SESSION[$cCode]['main'])) {
                if (!isset($_SESSION[$cCode]['main'][$ppnTargetMain])) {
                    $_SESSION[$cCode]['main'][$ppnTargetMain] = array();
                }
                $_SESSION[$cCode]['main'][$ppnTargetMain] = $ppn;
                $_SESSION[$cCode]['main'][$overWriteVendor] = $newPpn;
            }
            foreach ($_SESSION[$cCode]['items'] as $id => $aaaaaaaaaaaaaaa) {
                $_SESSION[$cCode]['items'][$id][$ppnTargetItems] = $newPpn;
                $_SESSION[$cCode]['items'][$id][$overWriteVendor] = $newPpn;
                // arrPrint( $_SESSION[$cCode]['items'][$id][$ppnTargetItems]);
            }
        }

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if(!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"]==null)){
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

//arrPrintPink($this->configCoreJenis);
        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setppnFactor($ppnFactor);
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
            "stepNumber" => $_SESSION[$cCode]['main']['stepNumber'],
            "stepCode" => $this->configUiJenis['steps'][$_SESSION[$cCode]['main']['stepNumber']]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";
//        if(isset($_GET['spc'])){
//            echo "<script>";
//            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&spc=1')";
//            echo "</script>";
//        }
//        else{
//            echo "<script>";
//            echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0')";
//            echo "</script>";
//        }
    }
}