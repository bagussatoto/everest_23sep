<?php

function cekStockLogamMuliaLocker($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
//    $rekening = "1010025010";


    $ci =& get_instance();
    $ci->load->model("Mdls/MdlLockerStockLogamMulia");
    $cs = New MdlLockerStockLogamMulia();
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
        }
        $tmpResult = $cs->lookupAll()->result();
    }
    else {
        $tmpResult = array();
    }

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            if(!isset($result[$eSpec->produk_id])){
                $result[$eSpec->produk_id] = 0;
            }
            $result[$eSpec->produk_id] += $eSpec->jumlah;
        }
    }

    return $result;
}