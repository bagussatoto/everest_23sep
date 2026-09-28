<?php

function cekStockProdukHpp($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
//    $paramsFilter = isset(config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokProduk']['params']) ? config_item('heTransaksi_ui')[$tr]['pairMakers'][$stepNumber]['stokProduk']['params'] : array();
    $rekening = "1010030030";

    $pIDs = array();
    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
        foreach ($_SESSION[$cCode][$gate] as $iSpec) {
            $pIDs[] = $iSpec['id'];
        }
    }


    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuProduk");
    $cs = New ComRekeningPembantuProduk();
    if (sizeof($pIDs) > 0) {
        $cs->addFilter("extern_id in ('" . implode("','", $pIDs) . "')");
    }
    $cs->setSortBy(array(
        "mode" => "asc",
        "kolom" => "id"
    ));
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

            $result[$eSpec->extern_id] = $eSpec->harga;
        }
    }


    return $result;
}