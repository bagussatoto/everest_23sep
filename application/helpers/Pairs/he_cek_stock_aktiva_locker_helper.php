<?php

function cekStockAktivaTetap($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
    $configUi = loadConfigModulJenis_he_misc($tr, "coTransaksiUi");
    $configCore = loadConfigModulJenis_he_misc($tr, "coTransaksiCore");
    $paramsFilter = isset($configUi['pairMakers'][$stepNumber]['stokProduk']['params']) ? $configUi['pairMakers'][$stepNumber]['stokProduk']['params'] : array();
    $rekening = "persediaan aktiva";


    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuAktivaTetap");
    $cs = New ComRekeningPembantuAktivaTetap();
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

            $result[$eSpec->id] = $eSpec->qty_debet;
        }
    }

    return $result;
}