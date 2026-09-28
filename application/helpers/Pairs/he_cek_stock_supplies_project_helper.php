<?php

function cekStockSuppliesProject($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
//    $paramsFilter = isset(config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokProduk']['params']) ? config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokProduk']['params'] : array();
//    $rekening = "persediaan supplies";
    $rekening = "1010030010";

    $pIDs = array();
//    if(isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate])>0)){
//        foreach ($_SESSION[$cCode][$gate] as $iSpec){
//            $pIDs[] = $iSpec['id'];
//        }
//    }

    if(isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate])>0)){
        foreach ($_SESSION[$cCode][$gate] as $byID => $dSpec){
            foreach($dSpec as $iSpec){
                $pIDs[] = isset($iSpec['biaya_dasar_id']) ? $iSpec['biaya_dasar_id'] : $iSpec['id'];
            }
        }
    }

    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuSupplies");
    $cs = New ComRekeningPembantuSupplies();
    if(sizeof($pIDs)>0){
        $cs->addFilter("extern_id in ('" . implode("','", $pIDs) . "')");
    }
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


    $resultTmp = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            $resultTmp[$eSpec->extern_id] = $eSpec->qty_debet;
        }
    }
    $result=array();
    if(isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate])>0)){
        foreach ($_SESSION[$cCode][$gate] as $byID => $dSpec){
            foreach($dSpec as $pSpec){
                if(isset($resultTmp[$pSpec['biaya_dasar_id']])){
                    $result[$byID][$pSpec['biaya_dasar_id']] = $resultTmp[$pSpec['biaya_dasar_id']];
                }
            }
        }
    }

//    cekMerah('##cekStockSuppliesProject: <br>' . $ci->db->last_query());
//    arrPrint($result);
    return $result;
}