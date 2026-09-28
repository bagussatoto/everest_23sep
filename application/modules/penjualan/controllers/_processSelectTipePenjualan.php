<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/18/2018
 * Time: 8:45 PM
 */
require_once "Modul_Controller.php";

class _processSelectTipePenjualan extends Modul_Controller
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
        $this->load->model("Mdls/MdlStaticMethodPenjualan");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $tp = $_GET['tp'];

        $mp = New MdlStaticMethodPenjualan();
        $mpTmp = $mp->lookupAll()->result();
        $arrMPData = array();
        foreach ($mpTmp as $mpSpec) {
            $nilai = (int)$mpSpec->nilai;
            $arrMPData[$nilai] = $mpSpec->nama;
        }
//        arrPrintHijau($arrMPData);
//        cekHere($arrMPData[$tp] . " :: $tp ::");


        $cCode = $this->cCode;
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHEre("undefine ppn factor, please logout and login again");
//        if (isset($_SESSION[$cCode]['items'])) {
//            if (isset($_SESSION[$cCode]['main'])) {
        $_SESSION[$cCode]['main']['tipePenjualanID'] = $tp;
        $_SESSION[$cCode]['main']['tipePenjualanNama'] = $arrMPData[$tp];
        $_SESSION[$cCode]['main']['tipePenjualanLabel'] = $arrMPData[$tp];
//            }
//            foreach ($_SESSION[$cCode]['items'] as $id => $aaaaaaaaaaaaaaa) {
//                $_SESSION[$cCode]['items'][$id]['tipe_penjualan_id'] = $tp;
//                $_SESSION[$cCode]['items'][$id]['tipe_penjualan_nama'] = $arrMPData[$tp];
//                $_SESSION[$cCode]['items'][$id]['tipe_penjualan_label'] = $arrMPData[$tp];
//            }
//        }


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

    }


}