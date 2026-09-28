<?php

function cekStockPinjam($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;

    $pIDs = array();
    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
        foreach ($_SESSION[$cCode][$gate] as $iSpec) {
            $pIDs[] = $iSpec['id'];
        }
    }

    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuPinjamBarang");
    $cs = New ComRekeningPembantuPinjamBarang();
    $cs->setFilters(array());
    if (sizeof($pIDs) > 0) {
        $cs->addFilter("extern_id in ('" . implode("','", $pIDs) . "')");
    }
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
        }
        $tmpResult = $cs->lookupAll()->result();
//        mati_disini($ci->db->last_query());
    } else {
        $tmpResult = array();
    }


    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            $result[$eSpec->extern_id] = $eSpec->qty_kredit;
        }
    }
//arrPrint($result);
//mati_disini();
    return $result;
}