<?php

function cekPriceSupplies($tr, $stepNumber, $paramsFilter=array(), $gate)
{

    $cCode = "_TR_" . $tr;



    $ci =& get_instance();
    $configUi = loadConfigModulJenis_he_misc($tr, "coTransaksiUi");
    $configCore = loadConfigModulJenis_he_misc($tr, "coTransaksiCore");
    $paramsFilter = isset($configUi['pairMakers'][$stepNumber]['priceSupplies']['params']) ? $configUi['pairMakers'][$stepNumber]['priceSupplies']['params'] : array();
    $ci->load->model("Mdls/MdlHargaSupplies");
    $cs = New MdlHargaSupplies();
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

            $result[$eSpec->produk_id] = $eSpec->nilai;
        }
    }



    return $result;
}