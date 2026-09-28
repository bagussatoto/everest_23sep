<?php

function cekDiskonSupplierTransaksi($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
    $rekening = "1010020030";

    $pIDs = array();
    if(isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate])>0)){
        foreach ($_SESSION[$cCode][$gate] as $iSpec){
            $pIDs[] = $iSpec['id'];
        }
    }


    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailTransMain");
    $cs = New ComRekeningPembantuPiutangSupplierDetailTransMain();
    if(sizeof($pIDs)>0){
        $cs->addFilter("extern_id in ('" . implode("','", $pIDs) . "')");
    }
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
        }
        $tmpResult = $cs->fetchBalances($rekening);
        showlast_query("biru");
    }
    else {
        $tmpResult = array();
    }

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            $result[$eSpec->extern_id] = $eSpec->debet;
        }
    }
//cekHere(":: pairing stok produk ::");
//arrPrint($result);
//mati_disini(__LINE__);
    return $result;
}