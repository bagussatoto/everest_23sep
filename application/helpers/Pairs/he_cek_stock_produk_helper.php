<?php

function cekStockProduk($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
    $rekening = "1010030030";

    $pIDs = array();
    if(isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate])>0)){
        foreach ($_SESSION[$cCode][$gate] as $iSpec){
            $pIDs[] = $iSpec['id'];
        }
    }


    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuProduk");
    $cs = New ComRekeningPembantuProduk();
    if(sizeof($pIDs)>0){
        $cs->addFilter("extern_id in ('" . implode("','", $pIDs) . "')");
    }
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {

            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);

            $cs->addFilter("$key='$realVal'");
        }
        $tmpResult = $cs->fetchBalances($rekening);
        showlast_query("merah");
    }
    else {
        $tmpResult = array();
    }

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            if(!isset($result[$eSpec->extern_id])){
                $result[$eSpec->extern_id] = 0;
            }
            $result[$eSpec->extern_id] += $eSpec->qty_debet;
        }
    }
//cekHere(":: pairing stok produk ::");
//arrPrint($result);
//mati_disini(__LINE__);
    return $result;
}