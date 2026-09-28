<?php

function cekStockProduk($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
//    $paramsFilter = isset(config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokProduk']['params']) ? config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokProduk']['params'] : array();

// arrPrint($paramsFilter);
// matiHEre();
    $pIDs = array();
    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
        foreach ($_SESSION[$cCode][$gate] as $iSpec) {
            $pIDs[] = $iSpec['id'];
        }
    }

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
    $cs = New MdlProjectKomposisiWorkorderSub();
    $cs->setFilters(array());
    if (sizeof($pIDs) > 0) {
        $cs->addFilter("produk_id in ('" . implode("','", $pIDs) . "')");
    }

    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {

            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);

            $cs->addFilter("$key='$realVal'");
        }
        $tmpResult = $cs->lookupAll()->result();
//        mati_disini($ci->db->last_query());
    }
    else {
        $tmpResult = array();
    }
//matiHere(__LINE__);

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            if(!$result[$eSpec->produk_id]){
                $result[$eSpec->produk_id] = 0;
            }
            $result[$eSpec->produk_id] += $eSpec->jumlah;
        }
    }
//arrPrint($result);
//mati_disini();
    return $result;
}