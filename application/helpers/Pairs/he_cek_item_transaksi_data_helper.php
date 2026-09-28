<?php

function cekItemTransaksiData($tr, $stepNumber, $paramsFilter = array(),$gate)
{

    $cCode = "_TR_" . $tr;
    cekMerah(":: MASUK SINI :: $stepNumber ::");

    $ci =& get_instance();
    $ci->load->model("MdlTransaksi");
    $cs = New MdlTransaksi();
    $cs->setFilters(array());
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
        }
//        $filter = makeFilter($paramsFilter, $_SESSION[$cCode]['main'], $cs);
        $cs->setTableName($cs->getTableNames()['detail']);
        $tmpResult = $cs->lookupAll()->result();
        showLast_query("pink");
    }
    else {
        $tmpResult = array();
    }

//    arrPrint($_SESSION[$cCode]['items']);
//    mati_disini();

    $result = array();
    if (sizeof($tmpResult) > 0) {
        $arrNotExist = array();
        foreach ($tmpResult as $eSpec) {
            if (!array_key_exists($eSpec->produk_id, $_SESSION[$cCode]['items'])) {
                $arrNotExist[] = $eSpec->produk_id;
            }
        }


        if (!isset($_SESSION[$cCode]['main']['partial_otorisasi'])) {
            $_SESSION[$cCode]['main']['partial_otorisasi'] = false;
        }
        if (sizeof($arrNotExist) > 0) {
//            mati_disini(sizeof($arrNotExist));
            arrPrint($arrNotExist);
            cekMerah(":: yang tidak tersedia ::");
            $_SESSION[$cCode]['main']['partial_otorisasi'] = true;
        }
        else {
            $_SESSION[$cCode]['main']['partial_otorisasi'] = false;
        }
    }

//    mati_disini();
    return $result;
}