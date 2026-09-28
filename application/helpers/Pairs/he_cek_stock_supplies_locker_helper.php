<?php

function cekStockSuppliesLocker($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;

    $pIDs = array();
    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
//        foreach ($_SESSION[$cCode][$gate] as $byID => $dSpec) {
//            foreach ($dSpec as $iSpec) {
//                $pIDs[] = isset($iSpec['biaya_dasar_id']) ? $iSpec['biaya_dasar_id'] : $iSpec['id'];
//            }
//        }
        foreach ($_SESSION[$cCode][$gate] as $pID => $dSpec) {
            $pIDs[] = $dSpec['id'];
        }
    }

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlLockerStockSupplies");
    $cs = New MdlLockerStockSupplies();

    if (sizeof($pIDs) > 0) {
        $cs->addFilter("produk_id in ('" . implode("','", $pIDs) . "')");

        if (sizeof($paramsFilter) > 0) {
            $cs->setFilters(array());
            foreach ($paramsFilter as $key => $val) {
                $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                cekHitam($realVal);

                $cs->addFilter("$key='$realVal'");
            }
            $tmpResult = $cs->lookupAll()->result();
            cekMerah('##cekStockSuppliesLocker: ' . $ci->db->last_query());

        }
        else {
            $tmpResult = array();
        }
    }

    // arrPrint($tmpResult);

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            // $result[$eSpec->biaya_id][$eSpec->produk_id] = $eSpec->jumlah;
            if(!isset($result[$eSpec->produk_id])){
                $result[$eSpec->produk_id] = 0;
            }
            $result[$eSpec->produk_id] += $eSpec->jumlah;
        }
    }
//
//        arrprint($result);
//        matiHere(__LINE__);
    if (ipadd() == "202.65.117.72") {
        // arrprint($result);
        //
        // matiHere(__LINE__);
    }
    return $result;
}