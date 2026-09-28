<?php

function cekPairPpnUangMukaProject($tr, $stepNumber, $paramsFilter = array(), $gate)
{
    $cCode = "_TR_" . $tr;

    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuPpnProject");
    $cpp = New ComRekeningPembantuPpnProject();
    if (sizeof($paramsFilter) > 0) {
        arrPrintKuning($paramsFilter);
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cpp->addFilter("$key='$realVal'");
        }
    }
    $tmpResult = $cpp->lookupAll()->result();
    if(sizeof($tmpResult)>0){
        $saldo = $tmpResult[0]->kredit;
    }
    else{
        $saldo = 0;
    }

    if($saldo > $_SESSION[$cCode]['main']['ppn']){
        $ppn_uangmuka_dipakai = $_SESSION[$cCode]['main']['ppn'];
    }
    else{
        $ppn_uangmuka_dipakai = $saldo;
    }
//mati_disini("[$ppn_uangmuka_dipakai] [$saldo]");
    $result = array(
        "uang_muka_npajak_saldo" => $saldo,
        "uang_muka_npajak_dipakai" => $ppn_uangmuka_dipakai,
    );
    foreach ($result as $key => $val){
        $_SESSION[$cCode]['main'][$key] = $val;
    }

    return true;
}