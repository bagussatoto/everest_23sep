<?php

function cekPricelist($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
    $configUi = loadConfigModulJenis_he_misc($tr, "coTransaksiUi");
    $configCore = loadConfigModulJenis_he_misc($tr, "coTransaksiCore");
//    $paramsFilter = isset($configUi['pairMakers'][$stepNumber]['hppProduk']['params']) ? $configUi['pairMakers'][$stepNumber]['hppProduk']['params'] : $paramsFilter;
//    $rekening = "persediaan produk";
    $pIDs = array();
    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
        foreach ($_SESSION[$cCode][$gate] as $iSpec) {
            $pIDs[] = $iSpec['id'];
        }
    }

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlHargaProdukPerSupplier");
    $cs = New MdlHargaProdukPerSupplier();
    if (sizeof($pIDs) > 0) {
        $cs->addFilter("produk_id in ('" . implode("','", $pIDs) . "')");
    }
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
//            cekPink("$key=$realVal");
        }
        $tmpResult = $cs->lookupAll()->result();
        cekPink($ci->db->last_query());
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
//cekHere(":: pairing stok produk ::");
//arrPrint($result);
//    mati_disini();
    return $result;
}