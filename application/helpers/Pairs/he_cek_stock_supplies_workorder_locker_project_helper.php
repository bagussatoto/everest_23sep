<?php

function cekStockSuppliesWorkorderLockerProject($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
    //    $paramsFilter = isset(config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokSupplies']['params']) ? config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokSupplies']['params'] : array();

    $pIDs = array();
    //    if(isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate])>0)){
    //        foreach ($_SESSION[$cCode][$gate] as $iSpec){
    //            $pIDs[] = $iSpec['id'];
    //        }
    //    }

    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
        foreach ($_SESSION[$cCode][$gate] as $byID => $dSpec) {
            foreach ($dSpec as $iSpec) {
                $pIDs[] = isset($iSpec['biaya_dasar_id']) ? $iSpec['biaya_dasar_id'] : $iSpec['id'];
            }
        }
    }


    $ci =& get_instance();
    $ci->load->model("Mdls/MdlLockerStockWorkOrder");
    $cs = New MdlLockerStockWorkOrder();

    if (sizeof($pIDs) > 0) {
        $cs->addFilter("produk_id in ('" . implode("','", $pIDs) . "')");

        if (sizeof($paramsFilter) > 0) {
            foreach ($paramsFilter as $key => $val) {
                $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                $cs->addFilter("$key='$realVal'");
            }
            $tmpResult = $cs->lookupAll()->result();
            cekMerah('##cekStockSuppliesLockerProject: <br>' . $ci->db->last_query());
        }
        else {
            $tmpResult = array();
        }
    }

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            $result[$eSpec->biaya_id][$eSpec->produk_id] = $eSpec->jumlah;
        }
    }

    //    arrprint($result);
    //    matiHere();
    return $result;
}