<?php

function cekKursValas($tr, $stepNumber, $paramsFilter = array(),$gate)
{

    $cCode = "_TR_" . $tr;



    $ci =& get_instance();
    $ci->load->model("Mdls/MdlCurrency");
    $cs = New MdlCurrency();
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
        }
    }

    $tmpResult = $cs->lookupAll()->result();
//    showLast_query("biru");

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {

            $result[$eSpec->id] = $eSpec->exchange;
        }
    }
//arrPrint($result);
//mati_disini();
    return $result;
}