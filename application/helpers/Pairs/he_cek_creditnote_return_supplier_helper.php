<?php

function cekCreditnoteReturnSupplierTransaksi($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;
    $rekening = "1010020030";

    $pIDs = array();
//    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
//        foreach ($_SESSION[$cCode][$gate] as $iSpec) {
//            $pIDs[] = $iSpec['id'];
//        }
//    }


    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailItem");
    $cs = New ComRekeningPembantuPiutangSupplierDetailItem();
//    if (sizeof($pIDs) > 0) {
//        $cs->addFilter("extern_id in ('" . implode("','", $pIDs) . "')");
//    }
//
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

    $result = array(
        "saldo_creditnote_return" => isset($tmpResult[0]->debet) ? $tmpResult[0]->debet : 0,
    );

    if ($gate == "items") {
        foreach ($_SESSION[$cCode][$gate] as $pid => $spec) {
            foreach ($result as $kyy => $vll) {
                $spec[$kyy] = $vll;
            }
            $_SESSION[$cCode][$gate][$pid] = $spec;
        }
    }

    return $result;
}