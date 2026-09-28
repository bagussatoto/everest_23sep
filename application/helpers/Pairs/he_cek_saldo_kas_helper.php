<?php

function cekStockSaldoKas($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
//    $paramsFilter = isset(config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['saldoRekening']['params']) ? config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['saldoRekening']['params'] : array();
//    $rekening = "kas";
    $rekening = "1010010010";


    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuKas");
    $cs = New ComRekeningPembantuKas();
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

            $result[$eSpec->rekening] = $eSpec->debet;
        }
    }

    return $result;
}