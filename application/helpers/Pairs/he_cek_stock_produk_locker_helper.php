<?php

function cekStockProdukLocker($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;

    $pIDs = array();
    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
        foreach ($_SESSION[$cCode][$gate] as $iSpec) {
            $pIDs[] = $iSpec['id'];
        }
    }
    if (sizeof($pIDs) == 0) {
        return array();
    }

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlLockerStock");
    $cs = New MdlLockerStock();
    $cs->setFilters(array());
    if (sizeof($pIDs) > 0) {
        $cs->addFilter("produk_id in ('" . implode("','", $pIDs) . "')");
    }
    if (sizeof($paramsFilter) > 0) {
        $invalidContext = false;
        foreach ($paramsFilter as $key => $val) {

            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            if ($key == "cabang_id" || $key == "gudang_id") {
                $ctxVal = trim((string)$realVal);
                if ($ctxVal === "" || $ctxVal === "0" || $ctxVal === "-555" || !is_numeric($ctxVal)) {
                    $invalidContext = true;
                    break;
                }
            }

            $cs->addFilter("$key='$realVal'");
        }
        if ($invalidContext) {
            return array();
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
            if (!isset($result[$eSpec->produk_id])) {
                $result[$eSpec->produk_id] = 0;
            }
            $result[$eSpec->produk_id] += $eSpec->jumlah;
        }
    }
//arrPrint($result);
//mati_disini();
    return $result;
}

function cekStokProdukSubWorkOrder($tr, $stepNumber, $paramsFilter = array(), $gate)
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
    if (sizeof($pIDs) == 0) {
        return array();
    }

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlLockerStockWorkOrder");
    $cs = New MdlLockerStockWorkOrder();
    $cs->setFilters(array());
    if (sizeof($pIDs) > 0) {
        $cs->addFilter("produk_id in ('" . implode("','", $pIDs) . "')");
    }
    if (sizeof($paramsFilter) > 0) {
        $invalidContext = false;
        foreach ($paramsFilter as $key => $val) {

            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            if ($key == "cabang_id" || $key == "gudang_id") {
                $ctxVal = trim((string)$realVal);
                if ($ctxVal === "" || $ctxVal === "0" || $ctxVal === "-555" || !is_numeric($ctxVal)) {
                    $invalidContext = true;
                    break;
                }
            }

            $cs->addFilter("$key='$realVal'");
        }
        if ($invalidContext) {
            return array();
        }
        $tmpResult = $cs->lookupAll()->result();
//        mati_disini($ci->db->last_query());
    }
    else {
        $tmpResult = array();
    }
//    arrPrint($tmpResult);
//matiHere(__LINE__);

    $result = array();
    if (sizeof($tmpResult) > 0) {
        foreach ($tmpResult as $eSpec) {
            if (!isset($result[$eSpec->produk_id])) {
                $result[$eSpec->produk_id] = 0;
            }
            $result[$eSpec->produk_id] += $eSpec->jumlah;
        }
    }
//arrPrint($result);
//mati_disini();
    return $result;
}

function cekStockProdukLockerBooking($tr, $stepNumber, $paramsFilter = array(), $gate)
{
    $cCode = "_TR_" . $tr;
    $pIDs = array();
    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
        foreach ($_SESSION[$cCode][$gate] as $iSpec) {
            $pIDs[] = $iSpec['id'];
        }
    }
    if (sizeof($pIDs) == 0) {
        return array();
    }

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlLockerStockBooking");

    $tbl_1 = "transaksi";
    $tbl_2 = "transaksi_data";
    $arrJenisTr = array("5822so", "5823so");

    $selected = array(
        "sum(valid_qty) as 'sum_valid_qty'",
        "produk_id",
        "produk_nama",
        "$tbl_1.gudang_status_id",
        "$tbl_1.gudang_status_nama",
    );
    $ci->db->select($selected);
    $ci->db->from($tbl_1);
    $ci->db->join($tbl_2, "$tbl_1.id = $tbl_2.transaksi_id", 'inner');

    $condites = array(
        "$tbl_1.trash_4" => "0",
        "$tbl_2.valid_qty>" => 0,
        "$tbl_2.next_substep_code!=" => "",
    );
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $new_key = "$tbl_1.$key";
            $condites[$new_key] = $realVal;
        }
    }
    $ci->db->where($condites);
    $ci->db->where_in("$tbl_1.jenis", $arrJenisTr);
    $ci->db->where_in("$tbl_2.produk_id", $pIDs);

    $ci->db->group_by("produk_id");
//    $ci->db->group_by("produk_id, gudang_status_id");
    $query = $ci->db->get()->result_array();

    $queries = array();
    foreach ($query as $item) {
        $produk_id = $item["produk_id"];
        $gudang_id = $item["gudang_status_id"];

        $queries[$produk_id] = $item["sum_valid_qty"];
    }
    return $queries;
}


