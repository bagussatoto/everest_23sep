<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/10/2019
 * Time: 12:42 PM
 */


function heInitCart($j)
{
    $ci =& get_instance();
    $jenisTr = $j;
    $cCode = "_TR_" . $jenisTr;

//    if (!isset($_SESSION[$cCode])) {
//        $_SESSION[$cCode] = array(
//            "items" => array(),
//            "main" => array(),
//            "out_master" => array(),
//        );
//    }
//    if (!isset($_SESSION[$cCode]['main'])) {
//        $_SESSION[$cCode]['main'] = array();
//    }
//    if (!isset($_SESSION[$cCode]['items'])) {
//        $_SESSION[$cCode]['items'] = array();
//    }
//    if (!isset($_SESSION[$cCode]['out_master'])) {
//        $_SESSION[$cCode]['out_master'] = array();
//    }

    $_SESSION[$cCode] = array(
        "items" => array(),
        "main" => array(),
//        "out_master" => array(),
    );


    $initMaster = array(
        "olehID" => $ci->session->login['id'],
        "olehName" => $ci->session->login['nama'],
        "sellerID" => $ci->session->login['id'],
        "sellerName" => $ci->session->login['nama'],
        "placeID" => $ci->session->login['cabang_id'],
        "placeName" => $ci->session->login['cabang_nama'],
        "cabangID" => $ci->session->login['cabang_id'],
        "cabangName" => $ci->session->login['cabang_nama'],
        "gudangID" => $ci->session->login['gudang_id'],
        "gudangName" => $ci->session->login['gudang_nama'],
        "jenisTr" => $jenisTr,
        "jenisTrMaster" => $jenisTr,
        "jenisTrTop" => $ci->config->item('heTransaksi_ui')[$jenisTr]['steps'][1]['target'],
        "jenisTrName" => $ci->config->item('heTransaksi_ui')[$jenisTr]['steps'][1]['label'],
        "stepNumber" => 1,
        "stepCode" => $ci->config->item('heTransaksi_ui')[$jenisTr]['steps'][1]['target'],
        "dtime" => date("Y-m-d H:i:s"),
    );


    foreach ($initMaster as $key => $val) {
        $_SESSION[$cCode]['main'][$key] = $val;
//        $_SESSION[$cCode]['out_master'][$key] = $val;
    }
}

function heInitGates($j)
{
    $ci =& get_instance();

    if (!isset($ci->session->login['id'])) {
        redirect(base_url() . "Login");
    }

    $jenisTr = $j;
    $cCode = "_TR_" . $jenisTr;


    $initTopVars = array(
        "main",
        "items",
        "tableIn_master",
        "tableIn_detail",
    );

    foreach ($initTopVars as $k) {
        if (!isset($_SESSION[$cCode][$k])) {
            $_SESSION[$cCode][$k] = array();
        }
    }


    //<editor-fold desc="initial values">
    $initMaster = array(
        "olehID" => $ci->session->login['id'],
        "olehName" => $ci->session->login['nama'],
        "placeID" => $ci->session->login['cabang_id'],
        "placeName" => $ci->session->login['cabang_nama'],
        "cabangID" => $ci->session->login['cabang_id'],
        "cabangName" => $ci->session->login['cabang_nama'],
        "gudangID" => $ci->session->login['gudang_id'],
        "gudangName" => $ci->session->login['gudang_nama'],
        "jenisTr" => $jenisTr,
        "jenisTrMaster" => $jenisTr,
        "jenisTrTop" => $ci->config->item('heTransaksi_ui')[$jenisTr]['steps'][1]['target'],
        "jenisTrName" => $ci->config->item('heTransaksi_ui')[$jenisTr]['label'],
        "stepNumber" => 1,
        "stepCode" => $ci->config->item('heTransaksi_ui')[$jenisTr]['steps'][1]['target'],
        "dtime" => date("Y-m-d H:i:s"),
        "fulldate" => date("Y-m-d"),
//        "deviceID"      => $ci->session->login['deviceID'],
//        "deviceName"    => $ci->session->login['deviceName'],
    );
//    $initDetail = array(
//        "olehID"     => $ci->session->login['id'],
//        "olehName"   => $ci->session->login['nama'],
//        "placeID"    => $ci->session->login['cabang_id'],
//        "placeName"  => $ci->session->login['cabang_nama'],
//        "cabangName" => $ci->session->login['cabang_nama'],
//        "gudangID"   => $ci->session->login['gudang_id'],
//        "gudangName" => $ci->session->login['gudang_nama'],
//        "cabangID"   => $ci->session->login['cabang_id'],
//        "jenisTr"    => $jenisTr,
//        "stepNumber" => 1,
//        "stepCode"   => $ci->config->item('heTransaksi_ui')[$jenisTr]['steps'][1]['target'],
//        "dtime"      => date("Y-m-d H:i:s"),
//    );


    foreach ($initMaster as $key => $val) {
        if (!isset($_SESSION[$cCode]['main'][$key])) {

            $_SESSION[$cCode]['main'][$key] = $val;
        }
//        if(!isset($_SESSION[$cCode]['out_master'][$key])){
//
//            $_SESSION[$cCode]['out_master'][$key] = $val;
//        }
    }

}

function heInitCart_he_cart($j, $initMaster)
{
    $ci =& get_instance();
    $jenisTr = $j;
    $cCode = cCodeBuilderMisc($jenisTr);

    $_SESSION[$cCode] = array(
        "items" => array(),
        "main" => array(),
    );

    foreach ($initMaster as $key => $val) {
        $_SESSION[$cCode]['main'][$key] = $val;
//        $_SESSION[$cCode]['out_master'][$key] = $val;
    }
}

function heInitGates_he_cart($j, $initMaster)
{
    $ci =& get_instance();
    if (!isset($ci->session->login['id'])) {
        redirect(base_url() . "Login");
    }
    $jenisTr = $j;
    $cCode = cCodeBuilderMisc($jenisTr);
    $initTopVars = array(
        "main",
        "items",
        "tableIn_master",
        "tableIn_detail",
    );
    foreach ($initTopVars as $k) {
        if (!isset($_SESSION[$cCode][$k])) {
            $_SESSION[$cCode][$k] = array();
        }
    }
    foreach ($initMaster as $key => $val) {
        if (!isset($_SESSION[$cCode]['main'][$key])) {
            $_SESSION[$cCode]['main'][$key] = $val;
        }
    }
}

function heInitMasterValues_he_cart($jenisTr, $stepNumber, $configUiJenis)
{
    $stepNumber = !empty($stepNumber) ? $stepNumber : 1;
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
        "jenisTr" => $jenisTr,
        "jenisTrMaster" => $jenisTr,
        "jenisTrTop" => isset($configUiJenis['steps'][$stepNumber]['target']) ? $configUiJenis['steps'][$stepNumber]['target'] : "",
        "jenisTrName" => isset($configUiJenis['steps'][$stepNumber]['label']) ? $configUiJenis['steps'][$stepNumber]['label'] : "",
        "stepNumber" => $stepNumber,
        "stepCode" => isset($configUiJenis['steps'][$stepNumber]['target']) ? $configUiJenis['steps'][$stepNumber]['target'] : 0,
        "dtime" => dtimeNow(),
        "fulldate" => dtimeNow("Y-m-d"),
        "ppnFactor" => my_ppn_factor(),
        "ppnFactorDesimal" => (my_ppn_factor() / 100),
        "ppnFactorInclude" => (my_ppn_factor() + 100) / 100,
    );

    return $initMasterValues;
}

//-------------------------------
function heInitMasterValuesReload_he_cart($jenisTr, $stepNumber, $configUiJenis)
{
    $initMasterValues = array(
        "olehID" => my_id(),
        "olehName" => my_name(),
//        "sellerID" => my_id(),
//        "sellerName" => my_name(),
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
        "jenisTr" => $jenisTr,
        "jenisTrMaster" => $jenisTr,
//        "jenisTrTop" => $configUiJenis['steps'][$stepNumber]['target'],
        "jenisTrTop" => $configUiJenis['steps'][1]['target'],
        "jenisTrName" => $configUiJenis['steps'][$stepNumber]['label'],
        "stepNumber" => $stepNumber,
        "stepCode" => isset($configUiJenis['steps'][$stepNumber]['target']) ? $configUiJenis['steps'][$stepNumber]['target'] : 0,
        "dtime" => dtimeNow(),
        "fulldate" => dtimeNow("Y-m-d"),
        "ppnFactor" => my_ppn_factor(),
        //-----------
        "nomer" => "",
        "nomer2" => "",
        "transaksi_id" => "",
        //-----------
    );

    return $initMasterValues;
}

function heInitGatesReload_he_cart($j, $initMaster)
{
    $ci =& get_instance();

    if (!isset($ci->session->login['id'])) {
        redirect(base_url() . "Login");
    }

    $jenisTr = $j;
    $cCode = cCodeBuilderMisc($jenisTr);


    $initTopVars = array(
        "main",
        "items",
        "tableIn_master",
        "tableIn_detail",
    );

    foreach ($initTopVars as $k) {
        if (!isset($_SESSION[$cCode][$k])) {
            $_SESSION[$cCode][$k] = array();
        }
    }

    foreach ($initMaster as $key => $val) {
//        if (!isset($_SESSION[$cCode]['main'][$key])) {

        $_SESSION[$cCode]['main'][$key] = $val;
//        }

    }

}

function heInitGates_ns_he_cart($j, $initMaster)
{
    $ci =& get_instance();
    $jenisTr = $j;
    $cCode = cCodeBuilderMisc($jenisTr);
    $initTopVars = array(
        "main",
        "items",
        "tableIn_master",
        "tableIn_detail",
    );
    foreach ($initTopVars as $k) {
        if (!isset($sessionData[$cCode][$k])) {
            $sessionData[$cCode][$k] = array();
        }
    }
    foreach ($initMaster as $key => $val) {
        if (!isset($sessionData[$cCode]['main'][$key])) {
            $sessionData[$cCode]['main'][$key] = $val;
        }
    }

    return $sessionData;
}


?>
