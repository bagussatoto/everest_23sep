<?php

//by: chepy
//date: 10 Sept 2025

function generateShowButton($fase_urut, $prodID, $enabled) {
    $btnJs = "
        var tmpIdForm=top.$('#komposisi_fase_biaya_tambahan_{$fase_urut}{$prodID}');
        var idform=$(tmpIdForm).attr('idform');
        $(tmpIdForm).attr('onclick','document.getElementById(\"'+idform+'\").submit();');
        $(tmpIdForm).prop('disabled', " . ($enabled ? "false" : "true") . ");
        $(tmpIdForm)." . ($enabled ? "addClass('btn-success');" : "removeClass('btn-success');");

    return $btnJs;
}

// Helper untuk hitung subtotal
function getSubtotal($fase_urut, $prodID, $jml, $harga, $value = null) {
    if (isset($_SESSION["NEW_TAMBAHAN"]["komposisi_fase_biaya_tambahan"][$fase_urut][$prodID]["subtotal"])) {
        return round($_SESSION["NEW_TAMBAHAN"]["komposisi_fase_biaya_tambahan"][$fase_urut][$prodID]["subtotal"]);
    }
    if ($value !== null) {
        return round($_SESSION["NEW_TAMBAHAN"]["komposisi_fase_biaya_tambahan"][$fase_urut][$prodID]["jml"] * $value);
    }
    return round($jml * $harga);
}

// Helper untuk cetak output
function printOutputJs($fase_urut, $extraJs, $subTotal, $showButton) {
    $outputJs = base64_encode("
        top.$('#komposisi_fase_biaya_tambahan_{$fase_urut}subtotal').html(addCommas($subTotal));
        $extraJs
        $showButton
    ");
    echo $outputJs;
}

/**
 * Mengambil HPP riil produk project secara dinamis dari MdlHargaProduk2 (tabel price).
 *
 * 1. Prioritas Utama: master table price (MdlHargaProduk2) cabang_id = -1 (hpp_supplier / hpp)
 * 2. Fallback: fifo_avg gudang project pusat: cabang_id = -1, gudang_id = 9
 */
if (!function_exists('getHppProdukProject')) {
    function getHppProdukProject($produk_id) {
        $CI = &get_instance();
        if (empty($produk_id)) {
            return 0;
        }

        // 1. Sumber Utama: master table price (MdlHargaProduk2)
        if ($CI->db->table_exists('price')) {
            $qPrice = $CI->db->select("nilai, jenis_value")
                ->from("price")
                ->where("produk_id", $produk_id)
                ->where("cabang_id", -1)
                ->where("status", "1")
                ->where("trash", "0")
                ->where_in("jenis_value", array("hpp_supplier", "hpp"))
                ->order_by("FIELD(jenis_value, 'hpp_supplier', 'hpp')", "ASC", false)
                ->get();

            if ($qPrice->num_rows() > 0) {
                foreach ($qPrice->result() as $rowPrice) {
                    if ($rowPrice->nilai * 1 > 0) {
                        return (float)$rowPrice->nilai;
                    }
                }
            }
        }

        // 2. Fallback: fifo_avg gudang project pusat (cabang -1, gudang 9)
        if ($CI->db->table_exists('fifo_avg')) {
            $q1 = $CI->db->select("hpp")
                ->from("fifo_avg")
                ->where("produk_id", $produk_id)
                ->where("cabang_id", -1)
                ->where("gudang_id", 9)
                ->where("jenis", "produk")
                ->get();

            if ($q1->num_rows() > 0) {
                $row1 = $q1->row();
                if ($row1->hpp * 1 > 0) {
                    return (float)$row1->hpp;
                }
            }
        }

        return 0;
    }
}

/**
 * Mengambil HPP riil batch untuk banyak produk project dari MdlHargaProduk2 (tabel price).
 *
 * 1. Prioritas Utama: master table price (MdlHargaProduk2) cabang_id = -1 (hpp_supplier / hpp)
 * 2. Fallback: fifo_avg gudang project pusat: gudang_id = 9 (cabang_id = -1)
 */
if (!function_exists('getBatchHppProdukProject')) {
    function getBatchHppProdukProject($arr_produk_ids = array()) {
        $CI = &get_instance();
        $arrResult = array();

        if (empty($arr_produk_ids)) {
            return $arrResult;
        }

        // 1. Sumber Utama: master table price (MdlHargaProduk2)
        if ($CI->db->table_exists('price')) {
            $CI->db->select("produk_id, nilai, jenis_value");
            $CI->db->from("price");
            $CI->db->where("cabang_id", -1);
            $CI->db->where("status", "1");
            $CI->db->where("trash", "0");
            $CI->db->where_in("jenis_value", array("hpp_supplier", "hpp"));
            $CI->db->where_in("produk_id", $arr_produk_ids);
            $CI->db->order_by("FIELD(jenis_value, 'hpp_supplier', 'hpp')", "ASC", false);
            $qPrice = $CI->db->get();

            if ($qPrice->num_rows() > 0) {
                foreach ($qPrice->result() as $rowPrice) {
                    if ($rowPrice->nilai * 1 > 0 && !isset($arrResult[$rowPrice->produk_id])) {
                        $arrResult[$rowPrice->produk_id] = (float)$rowPrice->nilai;
                    }
                }
            }
        }

        // 2. Fallback: fifo_avg gudang project pusat (cabang -1, gudang 9) jika belum ada di master price
        $missing_ids = array();
        foreach ($arr_produk_ids as $pId) {
            if (!isset($arrResult[$pId]) || $arrResult[$pId] <= 0) {
                $missing_ids[] = $pId;
            }
        }

        if (!empty($missing_ids) && $CI->db->table_exists('fifo_avg')) {
            $CI->db->select("produk_id, gudang_id, hpp");
            $CI->db->from("fifo_avg");
            $CI->db->where("cabang_id", -1);
            $CI->db->where("gudang_id", 9);
            $CI->db->where("jenis", "produk");
            $CI->db->where_in("produk_id", $missing_ids);
            $qFifo = $CI->db->get();
            if ($qFifo->num_rows() > 0) {
                foreach ($qFifo->result() as $fRow) {
                    if ($fRow->hpp * 1 > 0 && (!isset($arrResult[$fRow->produk_id]) || $arrResult[$fRow->produk_id] <= 0)) {
                        $arrResult[$fRow->produk_id] = (float)$fRow->hpp;
                    }
                }
            }
        }

        return $arrResult;
    }
}

// END OF COMPLETE REPEATED LOGIC
?>