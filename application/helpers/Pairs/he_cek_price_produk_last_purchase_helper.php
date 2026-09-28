<?php

function cekPriceProdukLastPurchase($tr, $stepNumber, $paramsFilter=array(),$gate)
{

    $cCode = "_TR_" . $tr;
//    $paramsFilter = isset(config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['hppProduk']['params']) ? config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['hppProduk']['params'] : array();
//    $rekening = "persediaan produk";


    $ci =& get_instance();
    $ci->load->model("Mdls/MdlHargaProdukLastPurchase");
    $cs = New MdlHargaProdukLastPurchase();
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {

            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);

            $cs->addFilter("$key='$realVal'");

        }
        $tmpResult = $cs->lookupAll()->result();
//        cekPink($ci->db->last_query());
    }
    else {
        $tmpResult = array();
    }

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {

            $result[$eSpec->produk_id] = $eSpec->nilai;
        }
    }



    return $result;
}