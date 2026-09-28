<?php

function cekStockLogamMulia($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
    $rekening = "1010025010";


    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuLogamMulia");
    $cs = New ComRekeningPembantuLogamMulia();
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