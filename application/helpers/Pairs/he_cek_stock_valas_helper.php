<?php

function cekStockValas($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
//    $paramsFilter = isset(config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokProduk']['params']) ? config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokProduk']['params'] : array();
//    $rekening = "valas";
    $rekening = "1010010020";


    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuValas");
    $cs = New ComRekeningPembantuValas();
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {

            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);

            $cs->addFilter("$key='$realVal'");
        }
        $tmpResult = $cs->fetchBalances($rekening);
    }
    else {
        $tmpResult = array();
    }

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {

            $result[$eSpec->extern_id] = $eSpec->qty_debet;
        }
    }

    return $result;
}