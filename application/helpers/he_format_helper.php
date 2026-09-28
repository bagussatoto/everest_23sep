<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/27/2018
 * Time: 7:52 PM
 */

function formatField($fieldName, $fieldValues, $jenisTr = "")
{
    if (is_string($fieldValues) && (substr($fieldValues, 0, 2) == "<a" || substr($fieldValues, 0, 7) == "<button" || substr($fieldValues, 0, 4) == "<div" || substr($fieldValues, 0, 5) == "<span")) {
        return $fieldValues;
    }
    //    cekOrange("$fieldName -> $fieldValues");
    $ci =& get_instance();
    $segment_2 = $ci->uri->segment(2);
    $ci->load->config("heTransaksi_ui");
    $ci->load->library("MataUang");
    $mu = new MataUang();

    $fkali = "";
    $type = "";
    $sym = "";

    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
        $fkali = isset($_GET['f']) ? blobDecode($_GET['f']) : "";
        $type = isset($_GET['type']) ? blobDecode($_GET['type']) : "";
        if ($fkali > 1) {
            $arrMataUang = $mu->getMataUang($type);
            if (sizeof($arrMataUang) > 0) {
                $sym = $arrMataUang['symbol'];
            }
        }
        elseif ($fkali == 1) {
            $sym = "";
        }
        else {
            $sym = "";
        }

    }


    $fieldName = strtolower(trim($fieldName));
    //region tambahan formater nomer transaksi
    if (in_array($fieldName, arrAvailFields())) {
        $exp = explode(".", $fieldValues);
        $lastKey = $exp[0];
        unset($exp[0]);
        $newValues = implode(".", $exp);

        $existKey = str_replace(".", "", $lastKey);//582

        if (isset(arrConfName()[$existKey])) {
            $extendKey = str_replace($lastKey, arrConfName()[$existKey], "$existKey");
//            $existKey = str_replace(".", "", $lastKey);//582

        }
        else {
            $fieldValue = $fieldValues;
        }

//        $fieldValue = isset(arrConfName()[$existKey]) ? str_replace($exp[0], arrConfName()[$existKey], "$fieldValues") : $fieldValues;
        $fieldValue = isset(arrConfName()[$existKey]) ? $extendKey . "." . $newValues : $fieldValues;
//        cekMerah($extendKey." || ".$lastKey);
//        cekLime($fieldValue);
    }
    else {
        $fieldValue = $fieldValues;
    }
    //endregion

    //-----------------------------------------------------------
    if ($fieldName == "date_transaksi_bank") {
        $fieldValues = str_replace("T", " ", $fieldValues);
    }

    //-----------------------------------------------------------
    $tmpOut = "";
//    if ($fieldName == 'produk_kode') {
//        $ci->load->model("Mdls/MdlProduk");
//        $o = new MdlProduk();
//        $o->addFilter("kode='" . trim($fieldValues) . "'");
//        $tmp = $o->lookupAll()->result();
//
//        $tmpOut = "noimage";
//        if (sizeof($tmp) > 0) {
//
//            $ci->load->model("Mdls/MdlImages");
//            $oi = new MdlImages();
//            $oi->addFilter("parent_id='" . trim($tmp[0]->id) . "'");
//            $tmps = $oi->lookupAll()->result();
//            $tmpOut = isset($tmps[0]->files) && $tmps[0]->files != '' ? $tmps[0]->files : "noimage";
//        }
//    }

    $fieldRules = array(
        "customerDetails_tlp_1" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "transaksi_jenis2" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "transaksi_jenis2_label" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "customerDetails_tlp_2" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "customerDetails_npwp" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        //        "nomer_top"      => array(
        //            "style" => "color:#000000;text-align:center;",
        //            "link"  => "window.open('" . base_url() . "Transaksi/viewReceipt/" . $fieldValue . "')",
        //            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
        //            "class" => "",
        //        ),
        "shipping_service" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_total_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nett1_bulat" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_ppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "tagihan_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_pembulatan" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "end_dtime" => array(
            "style" => "",
            "class" => "text-bold",
        ),
        "pph_23" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "total_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_grand_ppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "pph23_nilai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "extern_nilai2" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "payment_out" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "credit_note_dipakai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "uang_muka_dipakai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_net3" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_disc" => array(
            "class" => "pull-right",
        ),
        "sub_nett1" => array(
            "class" => "pull-right",
        ),
        "sub_nett2" => array(
            "class" => "pull-right",
        ),
        "disc_percent" => array(
            "class" => "pull-right",
        ),
        "sub_nett1_valas" => array(
            "class" => "pull-right",
        ),
        "harganppn" => array(
            "class" => "pull-right",
        ),
        "review_details" => array(
            "style" => "color:#000000;text-align:center;display:block;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResumeDetails/$fieldValues','view details')",
            "class" => "",
        ),
        "print_label" => array(
            "style" => "color:#0525f7;text-align:center;display:block;",
            //            "link"  => "showModal('" . base_url() . "Transaksi/viewReceipt/$fieldValues','print nota')",
            "link" => "top.popBig('" . base_url() . "Transaksi/viewReceiptReg/$fieldValues');",
            "class" => "",
        ),
        "print_barcode" => array(
            "style" => "color:#0525f7;text-align:center;display:block;",
            // "link" => "top.popBig('" . $modul . "Printing/barcodeTransaksi/$jenisTr/$fieldValues');",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModul?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        //----------------
        "print_barcode_pembelian" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModulPembelian?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_pembelian_2" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/BarcodePrinter/doPrintModulPembelian?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        //----------------
        "print_barcode_return" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModulReg?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_return_2" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/BarcodePrinter/doPrintModulReg?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        //----------------
        "nomer_top" => array(
//            "style" => "text-align:left; display:block;",
//            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
//            "class" => "text-hover",

            "style" => "color:#000000;text-align:center;",
//            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",

        ),
        "nomer_top_new" => array(
//            "style" => "text-align:left; display:block;",
//            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
//            "class" => "text-hover",

            "style" => "color:#000000;text-align:center;",
//            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",

        ),
        "references_num" => array(
            "style" => "text-align:left; display:block;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__1" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__2" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__3" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__4" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__5" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
//        "nomer_top2"              => array(
//            "style" => "text-align:left; display:block;",
//            "link"  => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
//            "class" => "text-hover",
//        ),
        //        "nomer"          => array(
        //            "style" => "color:#000000;text-align:center;",
        //            "link"  => substr($fieldValue, 0, 3) == "582" ? "popSmall('" . base_url() . "Transaksi/viewSmallReceipt/$fieldValue')" : "popBig('" . base_url() . "Transaksi/viewReceipt/$fieldValue')",
        //            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
        //            "class" => "",
        //        ),
        "nomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "so_num" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "nomer_po" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "nomer_um" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "allow_project" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "nomer_new" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "efakturSource" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "eFaktur" => array(
            "style" => "color:#000000;text-align:center;",
            "class" => "",
        ),
        "eFaktur_insntif" => array(
            "style" => "color:#000000;text-align:center;",
            "class" => "",
        ),
        "referencenomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_1" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_2" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_3" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "nomer_nolink" => array(
            "style" => "color:#000000;text-align:left;",
            "link" => "",
            "class" => "",
        ),
        "nomer_download" => array(),
        "refNum" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "transaksi_no" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "jenis" => array(
            "style" => "color:#000000;text-align:left;",
            //            "link"  => substr($fieldValue, 0, 3) == "582" ? "popSmall('" . base_url() . "Transaksi/viewSmallReceipt/$fieldValue')" : "popBig('" . base_url() . "Transaksi/viewReceipt/$fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "btn_action" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . base_url() . "OverDue_releaser/preview/$fieldValues','Temporary unlock')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "btn btn-danger",
        ),
        "no" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "pull-right",
        ),
        "lock" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "text-center",
        ),
        "project_start" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "text-center",
        ),
        // "dtime"          => array(
        //     "style" => "color:#565656;text-align:center;",
        //     "class" => "",
        // ),
        "fulldate" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dateFaktur" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dtime_bank" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dateFaktur_insentif" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "extern_date2" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "oleh_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "extern_label2" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "kode" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "kode_cabang" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "no_part" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "suppliers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "customers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),

        "produk_ord_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "produk_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "produk_kode " => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "jenis_label" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),

        "satuan" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "btn-block text-right",
        ),
        "state" => array(
            "style" => "",
            "class" => "btn-block text-center",
        ),
        "action" => array(
            "style" => "text-align:center;",
            "class" => "btn-block text-right",
        ),
        "transaksi_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_nilai" => array(
            "class" => "pull-right",
        ),
        "sub_diskon_supplier_nilai" => array(
            "class" => "pull-right",
        ),
        "ppn_nilai" => array(
            "class" => "pull-right",
        ),
        "ppn_netto" => array(
            "class" => "pull-right",
        ),
        "transaksi_net" => array(
            "class" => "pull-right",
        ),
        "new_net1" => array(
            "class" => "pull-right",
        ),
        "disc" => array(
            "class" => "pull-right",
        ),
        "disc_valas" => array(
            "class" => "pull-right",
        ),
        "sub_harga_valas" => array(
            "class" => "pull-right",
        ),
        "ppn" => array(
            "class" => "pull-right",
        ),
        "extern_nilai3" => array(
            "class" => "pull-right",
        ),
        "new_grand_ppn" => array(
            "class" => "pull-right",
        ),
        "nilai_tambah_ppn_out" => array(
            "class" => "pull-right",
        ),
        "nett2" => array(
            "class" => "pull-right",
        ),
        "nett2" => array(
            "class" => "pull-right",
        ),
        "nett1" => array(
            "class" => "pull-right",
        ),
        "ppv" => array(
            "class" => "pull-right",
        ),
        "jual" => array(
            "class" => "pull-right",
        ),
        "jual_nppn" => array(
            "class" => "pull-right",
        ),
        "angka" => array(
            "class" => "pull-right",
        ),
        "grand_total" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hpp_nppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "harga" => array(
            "style" => "text-align:right;",
            "class" => "pull-right",
        ),
        "harga_ori" => array(
            "class" => "pull-right",
        ),
        "disc_value" => array(
            "class" => "pull-right",
        ),
        "hpp" => array(
            "style" => "color:#8f8f8f;font-weight:lighter;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jml" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jml_nilai" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_jmlx" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_harga" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_hrg" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "harga_perolehan" => array(
            "style" => "color:#005689;font-weight:bold;",
            "class" => "btn-block",
        ),
        "sisa_depre" => array(
            "style" => "color:#005609;font-weight:bold;",
            "class" => "btn-block",
        ),
        "saldo_sisa" => array(
            "style" => "color:#011689;font-weight:bold;",
            "class" => "btn-block",
        ),
        "harga_sisa" => array(
            "style" => "color:#011600;font-weight:bold;",
            "class" => "btn-block",
        ),
        "nett" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "ongkir" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "ongkir_tax" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "install" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "install_tax" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "subtotal" => array(
            "style" => "color:#000;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_anggaran_biaya" => array(
            "style" => "color:#8f8f8f;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "anggaran_biaya" => array(
            "style" => "color:#8f8f8f;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hrg_hpp" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hrg_hpp" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hpp" => array(
            "style" => "color:#8f8f8f;font-weight:lighter;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hpp_nppn" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_harga" => array(
            "class" => "pull-right",
        ),
        "tagihan" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "creditAmount" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "terbayar" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sisa" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "diskon" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "add_disc" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_bayar" => array(
            "style" => "color:#009900;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_setor" => array(
            "style" => "color:#009900;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_entry" => array(
            "style" => "color:#000000;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_sisa" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "total" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;font-size:larger;",
            "class" => "btn-block text-right",
        ),
        "sisa" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;font-size:larger;",
            "class" => "btn-block text-right",
        ),
        "refValue" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "active" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hold" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_debet_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "balance" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "nilai_be" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "nilai_be_spo" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_spo" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_cl" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ne_spo" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_spd" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_spo" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //
        "unit_be_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_be_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_in_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_in_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_ot_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_ot_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_af_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_af_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //
        "nilai_be_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_be_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "textJumlah" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "text-bold text-uppercase pull-right",
        ),
        "uname" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "ipadd" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "devices" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "browser" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "discount" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        // "disc"   => array(
        //     "style" => "",
        //     "class" => "",
        // ),
        "dp" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "dp_value" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "dp_ppn_value" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //"tagihan"    => array(
        //    "style" => "color:#992200;font-weight:bold;text-align:right;",
        //    "class" => "btn-block text-right",
        //),
        "stok" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "reference" => array(
            "style" => "",
            "class" => "btn-block text-left",
        ),
        //"ppn"        => array(
        //    "style" => "",
        //    "class" => "btn-block text-right",
        //),
        "value" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "value_in" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "value_out" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "extern_nilai2" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        // "tlp"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_1"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_2"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_3"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "phone"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-left",
        // ),
        // "handphone"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-left",
        // ),
        "qty_opname" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_pembulatan" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "additionalfactor" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jenistr_reference" => array(
            "style" => "color:#000000;text-align:left;",
            "class" => "btn-block text-left",
        ),
        "saldo_qty_berjalan" => array(
            "class" => "pull-right",
        ),
        "saldo_berjalan" => array(
            "class" => "pull-right",
        ),
        "netto" => array(
            "class" => "pull-right",
        ),
        "ng_debet" => array(
            "class" => "pull-right",
        ),
        "ng_kredit" => array(
            "class" => "pull-right",
        ),
        "ng_qty_kredit" => array(
            "class" => "pull-right",
        ),
        "ng_qty_debet" => array(
            "class" => "pull-right",
        ),
        "total_qty_kredit" => array(
            "class" => "pull-right",
        ),
        "total_qty_debet" => array(
            "class" => "pull-right",
        ),
        "total_debet" => array(
            "class" => "pull-right",
        ),
        "total_kredit" => array(
            "class" => "pull-right",
        ),
        "harga_last" => array(
            "class" => "pull-right",
        ),
        "sub_harga_last" => array(
            "class" => "pull-right",
        ),
        "dp_dipakai_ui" => array(
            "class" => "pull-right",
        ),
        "dp_ppn_dipakai_ui" => array(
            "class" => "pull-right",
        ),
        "harga_last_purchase" => array(
            "class" => "pull-right",
        ),
        "number_value" => array(
            "class" => "pull-right",
        ),
        "number_value" => array(
            "class" => "pull-right",
        ),
        "sub_outstanding_items" => array(
            "class" => "pull-right",
        ),
        "amount" => array(
            "class" => "pull-right",
        ),
//        "harga_ori" => array(
//            "class" => "pull-right",
//        ),
//
        "nilai" => array(
            "class" => "pull-right",
        ),
        "sub_nilai" => array(
            "class" => "pull-right",
        ),
        "premi" => array(
            "class" => "pull-right",
        ),
        "premi_percent" => array(
            "class" => "pull-right",
        ),
        "harga_kompensasi" => array(
            "class" => "pull-right",
        ),
        "sub_harga_kompensasi" => array(
            "class" => "pull-right",
        ),
        "ppn_final" => array(
            "class" => "pull-right",
        ),
        "dpp_final" => array(
            "class" => "pull-right",
        ),
        "dpp_pengganti" => array(
            "class" => "pull-right",
        ),
        "diskon_1_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_2_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_3_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_4_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_5_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_6_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_7_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_8_nilai" => array(
            "class" => "pull-right",
        ),
        "uang_muka_tanpa_ppn" => array(
            "class" => "pull-right",
        ),
        "dpp" => array(
            "class" => "pull-right",
        ),
        "dpp_nppn" => array(
            "class" => "pull-right",
        ),
        "pph23_nilai" => array(
            "class" => "pull-right",
        ),
        "pph21_nilai" => array(
            "class" => "pull-right",
        ),
        "returned" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_01" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_02" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_03" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_04" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_05" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_06" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_07" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_08" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_09" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_gunggung" => array(
            "class" => "pull-right",
        ),
        "nilai_didelete" => array(
            "class" => "pull-right",
        ),
        "nilai_cash" => array(
            "class" => "pull-right",
        ),
        "dpp_taxes" => array(
            "class" => "pull-right",
        ),
        "dpp_pengganti_taxes" => array(
            "class" => "pull-right",
        ),
        "ppn_taxes" => array(
            "class" => "pull-right",
        ),
        "grand_total_taxes" => array(
            "class" => "pull-right",
        ),
        "projectNilai" => array(
            "class" => "pull-right",
        ),
        "nilai_pph_original" => array(
            "class" => "pull-right",
        ),
        "nilai_kas_cn" => array(
            "class" => "pull-right",
        ),
    );


    $formats = array(
        "ppn_netto" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "nilai_entry" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "pph23_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "pph21_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "grand_ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "extern_nilai3" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        // "nilai_tambah_ppn_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "sub_hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "ppn_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "new_grand_ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor($fieldValue)),


        "shipping_service" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett1_bulat" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_total_ui" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),

        "tagihan_ui" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "credit_note_dipakai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "uang_muka_dipakai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "pph_23" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "extern_nilai2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "pph23_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "payment_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_nett1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_nett2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "new_net3" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "review_details" => "<span class='fa fa-eye'></span>",
        "print_label" => "<span class='fa fa-print' style=\"font-size:20px\"></span>",
        "print_barcode" => "<button type='button' class='btn btn-info btn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_barcode_pembelian" => "&nbsp;<button type='button' class='btn btn-info bbtn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_barcode_pembelian_2" => "&nbsp;<button type='button' class='btn btn-primary bbtn-block hidden-xs hidden-sm'><span class='fa fa-barcode' style=\"font-size:1.2em\"> Print Barcode</span></button>",

        "print_barcode_return" => "&nbsp;<button type='button' class='btn btn-info bbtn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_barcode_return_2" => "&nbsp;<button type='button' class='btn btn-primary bbtn-block hidden-xs hidden-sm'><span class='fa fa-barcode' style=\"font-size:1.2em\"> Print Barcode</span></button>",

        "nomer_top" => "$fieldValue",
        "nomer_top_new" => "$fieldValue",
        // "nomer_top"        => "<span class='fa fa-file-o' style='color:#55cc55;'></span> $fieldValue",
        "nomer" => "$fieldValue",
        "so_num" => "$fieldValue",
        "nomer_po" => "$fieldValue",
        "nomer_um" => "$fieldValue",
        "allow_project" => "$fieldValue*****",

        "lock" => $fieldValue == 1 ? "<span class='btn btn-xs btn-danger'><i class='fa fa-lock'></i></span>" : "<span class='btn btn-xs btn-success'><i class='fa fa-unlock'></i></span>",
        "project_start" => $fieldValue == 1 ? "<span class='btn btn-xs btn-success'><i class='fa fa-refresh fa-spin'></i> RUNNING </span>" : "<span class='btn btn-xs btn-danger'><i class='fa fa-stop'></i></span>",

        "nomer_new" => "$fieldValue",
        "nomer_top2" => "$fieldValue",
        "references_num" => "$fieldValue",
        "referencenomer__1" => "$fieldValue",
        "referencenomer__2" => "$fieldValue",
        "referencenomer__3" => "$fieldValue",
        "referencenomer__4" => "$fieldValue",
        "referencenomer__5" => "$fieldValue",
        "produk_kode" => "<span onclick=\"showImageSwal('$fieldValue','$tmpOut')\">$fieldValue\n</span>",
        "produk_kode_nolink" => "$fieldValue",
        "referencenomer" => "$fieldValue",
        "end_dtime" => "<div class='text-red' data-value='$fieldValue'>" . date('d F Y', strtotime($fieldValue)) . "</div><small>" . createTimeDescSoon($fieldValue) . "</small>",
        "nomer_nolink" => "$fieldValue",
        "nomer_download" => "$fieldValue",
        // "nomer"            => "<span class='fa fa-file-text-o' style='color:#0056cd;'></span> $fieldValue",
        "refNum" => strlen($fieldValue) > 0 ? "<span class='fa fa-file-text-o' style='color:#ff7700;'></span> $fieldValue" : $fieldValue,
        //        "jenis"           => "<span class='fa fa-file-text-o'></span> ".$ci->config->item('heTransaksi_ui')[$fieldValue]['label'],
        // "transaksi_no" => "<span class='fa fa-file-text-o' style='color:#000000;'></span> $fieldValue",
        "transaksi_no" => "$fieldValue",
        "efakturSource" => "$fieldValue",
        "eFaktur" => "$fieldValue",
        "eFaktur_insentif" => "$fieldValue",
        "pairtransaksinomer_1" => "$fieldValue",
        "pairtransaksinomer_2" => "$fieldValue",
        "pairtransaksinomer_3" => "$fieldValue",
        // "date_time"          => formatTanggal($fieldValue, "d M Y H:i"),
        // "dtime"              => formatTanggal($fieldValue, "d F Y"),
        // "fulldate"           => formatTanggal($fieldValue, "d F Y"),
        // "shippingDate_value" => formatTanggal($fieldValue, "d F Y"),
        // "duedate_value"      => formatTanggal($fieldValue, "d F Y"),
        // "shippingdate_value" => formatTanggal($fieldValue, "d F Y"),
        // "viewR" => formatTanggal($fieldValue,"d F Y"),
        "date_time" => date("d M Y H:i", strtotime($fieldValue)),
        "auth_dtime" => date("d F Y ", strtotime($fieldValue)),
        "dtime" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "dtime_po" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "dtime_um" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "date_transaksi_bank" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
//        "dtime_bank" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        //        "dtime" => date("d F Y", strtotime($fieldValue)) . "<br><r><sub>".date("h:i:s", strtotime($fieldValue))."</sub></r>",
        "dtime_bank" => isDate($fieldValue) ? date("d F Y", strtotime($fieldValue)) : $fieldValue,
        "fulldate" => isDate($fieldValue) ? date("d F Y", strtotime($fieldValue)) : $fieldValue,
        "dateFaktur" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "dateFaktur_insentif" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "extern_date2" => ($fieldValue != "0000-00-00") ? date("d F Y", strtotime($fieldValue)) : "",
        "shippingDate_value" => date("d F Y", strtotime($fieldValue)),
        "duedate_value" => date("d F Y", strtotime($fieldValue)),
        "shippingdate_value" => date("d F Y", strtotime($fieldValue)),

        "oleh_nama" => "$fieldValue",
        "extern_label2" => "$fieldValue",
        //        "oleh_nama" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",
        //        "kode" => "<span class='glyphicon glyphicon-copyright-mark'></span> $fieldValue",
        "kode" => "$fieldValue",
        "kode_cabang" => "$fieldValue",
        "no_part" => "<span class='glyphicon glyphicon-subtitles'></span> $fieldValue",
        "uname" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",
        //        "customers_nama" => "<span class='fa fa-users'></span> $fieldValue",
        "customers_nama" => "$fieldValue",
        "transaksi_jenis2" => "$fieldValue",
        "transaksi_jenis2_label" => "$fieldValue",
        "suppliers_nama" => "$fieldValue",
        "jenis_nama" => "<span class='glyphicon glyphicon-th-list'></span> $fieldValue",
        "produk_nama" => "<span > $fieldValue</span>",
        "hpp" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        // "harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "harga_ori" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "disc_value" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_tambah_ppn_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "subtotal" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_anggaran_biaya" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "anggaran_biaya" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "hrg_hpp" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_hrg_hpp" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_nett1_include_ppn" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        // "sub_nett1_include_ppn" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_hpp" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "transaksi_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_total" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "total" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ongkir" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ongkir_tax" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "install" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "install_tax" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "tagihan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_pembulatan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "creditAmount" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "terbayar" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "diskon" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "add_disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_bayar" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_setor" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
//        "nilai_entry" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "new_sisa" => number_format(0 + $fieldValue),
        "disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "refValue" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "berat" => number_format((0 + $fieldValue), 2),
        "premi_nilai" => number_format((0 + $fieldValue), 0, ",", "."),
        "pph" => number_format((0 + $fieldValue), 2, ",", "."),
//        "sisa"                    => number_format((0 + $fieldValue), 0,"",","),
        // "tlp_1"                  => number_format((0 + $fieldValue), 0, ",", "."),

        //        "hpp" => (str_replace(".0000000000","",$fieldValue)),
        //        "harga" => (str_replace(".0000000000","",$fieldValue)),
        //        "ppn" => (str_replace(".0000000000","",$fieldValue)),
        //        "nett" => (str_replace(".0000000000","",$fieldValue)),
        "harga_debet_awal" => number_format(0 + $fieldValue),
        "harga_debet" => number_format(0 + $fieldValue),
        "harga_kredit" => number_format(0 + $fieldValue),
        "harga_saldo" => number_format(0 + $fieldValue),
        "debet_awal" => number_format(0 + $fieldValue),
        "debet" => number_format(0 + $fieldValue),
        "active" => number_format(0 + $fieldValue),
        "hold" => number_format(0 + $fieldValue),
        "debet_akhir" => number_format(0 + $fieldValue),
        "akhir" => number_format(0 + $fieldValue),
        "avail" => number_format(0 + $fieldValue),
        "saldo" => is_numeric($fieldValue) ? number_format(0 + $fieldValue) : $fieldValue,

        "kredit_awal" => number_format(0 + $fieldValue),
        "kredit" => number_format(0 + $fieldValue),
        "kredit_akhir" => number_format(0 + $fieldValue),
        "balance" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",

        "qty_debet_awal" => number_format(0 + $fieldValue),
        "qty_debet" => number_format(0 + $fieldValue),
        "qty_debet_akhir" => number_format(0 + $fieldValue),

        "qty_kredit_awal" => number_format(0 + $fieldValue),
        "qty_kredit" => number_format(0 + $fieldValue),
        "qty_kredit_akhir" => number_format(0 + $fieldValue),

        "nilai_be_spo" => number_format(0 + $fieldValue),
        "nilai_ot_spo" => number_format(0 + $fieldValue),
        "nilai_ot_cl" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_ne_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_ot_spd" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        // "nilai_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",

        // "nilai_be"        => number_format(0 + $fieldValue),
        "nilai_be" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_in" => number_format(0 + $fieldValue),
        "nilai_ot" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_af" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        // "nilai_af"        => number_format(0 + $fieldValue),
        "disc_valas" => number_format(0 + $fieldValue, 2),
        "sub_harga_valas" => number_format(0 + $fieldValue, 2),
        "sub_nett1_valas" => number_format(0 + $fieldValue, 2),
        "disc_percent" => number_format(0 + $fieldValue, 2),
        //
        //        "unit_be_debet"  => number_format(0 + $fieldValue),
        //        "unit_be_kredit" => number_format(0 + $fieldValue),
        //        "unit_in_debet"  => number_format(0 + $fieldValue),
        //        "unit_in_kredit" => number_format(0 + $fieldValue),
        //        "unit_ot_debet"  => number_format(0 + $fieldValue),
        //        "unit_ot_kredit" => number_format(0 + $fieldValue),
        "unit_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "unit_af" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "unit_af_debet" => number_format(0 + $fieldValue),
        "unit_af_kredit" => number_format(0 + $fieldValue),
        "nilai_kredit" => number_format(0 + $fieldValue),

        //        "nilai_be_debet"  => number_format(0 + $fieldValue),
        "kredit_limit" => number_format(0 + $fieldValue, 0, ",", "."),
        "nilai_in_debet" => number_format(0 + $fieldValue),
        "nilai_in_kredit" => number_format(0 + $fieldValue),
        "nilai_ot_debet" => number_format(0 + $fieldValue),
        "nilai_ot_kredit" => number_format(0 + $fieldValue),
        "nilai_af_debet" => number_format(0 + $fieldValue),
        "nilai_af_kredit" => number_format(0 + $fieldValue),
        "stok_aktif" => $fieldValue < 0 ? "<span class='text-red'>(" . ($fieldValue * -1) . ")</span>" : "<span class='font-size-1-2 tebal'>" . number_format(0 + $fieldValue) . "</span>",

        "produk_ord_harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "produk_ord_hrg" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga_perolehan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sisa_depre" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 0, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "saldo_sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 0, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga_sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : (($fieldValue * 1) > 0 ? number_format(0 + $fieldValue) : $fieldValue),
        "produk_ord_jmlx" => number_format(0 + $fieldValue),
        "ppv" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "jual" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "jual_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harganppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),

        "new_net1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "diskon_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_diskon_supplier_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ppn_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "transaksi_net" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "state" => $fieldValue,
        "ipadd" => ($fieldValue),
        "devices" => "<span class='fa fa-tablet'></span> " . heReturnDeviceName($fieldValue)['device'] . "/" . heReturnDeviceName($fieldValue)['browser'],
        "browser" => "<span class='fa fa-tablet'></span> " . heReturnDeviceName($fieldValue)['device'] . "/" . heReturnDeviceName($fieldValue)['browser'],
        //        "devices"  => "<span class='fa fa-tablet'></span> " . $fieldValue,
        "discount" => number_format(0 + $fieldValue),
        "dp" => number_format(0 + $fieldValue),
        "dp_value" => number_format(0 + $fieldValue),
        "dp_ppn_value" => number_format(0 + $fieldValue),
        //        "tagihan"  => number_format(0 + $fieldValue),
        "curency" => "Rp. " . number_format(0 + $fieldValue),
        "barcode" => $fieldValue,
        "npwp" => $fieldValue,
        "no_ktp" => $fieldValue,
        "nik" => $fieldValue,
        "tlp" => $fieldValue,
        "tlp_1" => $fieldValue,
        "tlp_2" => $fieldValue,
        "tlp_3" => $fieldValue,
        "phone" => $fieldValue,
        "handphone" => $fieldValue,
        "customerdetails_tlp_1" => $fieldValue,
        "customerdetails_tlp_2" => $fieldValue,
        //        "ppn"      => $fieldValue,

        "no" => number_format(0 + $fieldValue),
        "value" => number_format(0 + $fieldValue),
        "value_in" => number_format(0 + $fieldValue),
        "value_out" => number_format(0 + $fieldValue),
        "angka" => number_format(0 + $fieldValue, 2),
        "disc_persent" => number_format(0 + $fieldValue, 2),
        "customerDetails__npwp" => "$fieldValue",
        "customerDetails__tlp_1" => "$fieldValue",
        "customerDetails__tlp_2" => "$fieldValue",
        "btn_action" => "<span class='btn btn-xs fa fa-calendar' style=\"font-size:15px\"> review</span>",

        "berat_gross" => number_format(($fieldValue * 1) / 1000) . "",
        "sub_berat_gross" => number_format(($fieldValue * 1) / 1000) . "",
        "volume_gross" => number_format(($fieldValue * 1) / 1000000000, 2) . "",
        "sub_volume_gross" => number_format(($fieldValue * 1) / 1000000000, 2) . "",
        "stok" => ($fieldValue < 0) ? "(" . number_format($fieldValue * -1) . ")" : number_format(0 + $fieldValue),
        "qty_opname" => number_format(0 + $fieldValue),
        "extern_nilai2" => number_format(0 + $fieldValue),
        "nilai_pembulatan" => "$fieldValue",
        "additionalfactor" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "produk_kode " => $fieldValue,
        "jenistr_reference " => $fieldValue,
        "saldo_qty_berjalan" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "saldo_berjalan" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "netto" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_qty_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_qty_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_qty_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_qty_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "harga_last" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "sub_harga_last" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dp_dipakai_ui" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dp_ppn_dipakai_ui" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "harga_last_purchase" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dpp_dp" => $fieldValue >= 0 ? (number_format(ceil($fieldValue))) : "(" . (number_format(-1 * ceil($fieldValue))) . ")",
        "ppn_dp" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "number_value" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "total_ui" => $fieldValue >= 0 ? (number_format(ceil($fieldValue))) : "(" . (number_format(-1 * ceil($fieldValue))) . ")",
        "new_grand_ppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_outstanding_items" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "unit" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "jml_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "amount" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
//        "harga_ori" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "premi" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "premi_percent" => number_format(0 + $fieldValue, 2),
        "harga_kompensasi" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_harga_kompensasi" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "spek" => nl2br($fieldValue),
        "note" => nl2br($fieldValue),
        "reference" => nl2br($fieldValue),
        "ppn_final" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_final" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_pengganti" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",

        // "harga_ori" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        // "nomer_top"        => "<span class='fa fa-file-o' style='color:#55cc55;'></span> $fieldValue",
        // "nomer"            => "<span class='fa fa-file-text-o' style='color:#0056cd;'></span> $fieldValue",
        // "jenis"           => "<span class='fa fa-file-text-o'></span> ".$ci->config->item('heTransaksi_ui')[$fieldValue]['label'],
        // "transaksi_no" => "<span class='fa fa-file-text-o' style='color:#000000;'></span> $fieldValue",
        // "date_time"          => formatTanggal($fieldValue, "d M Y H:i"),
        // "dtime"              => formatTanggal($fieldValue, "d F Y"),
        // "fulldate"           => formatTanggal($fieldValue, "d F Y"),
        // "shippingDate_value" => formatTanggal($fieldValue, "d F Y"),
        // "duedate_value"      => formatTanggal($fieldValue, "d F Y"),
        // "shippingdate_value" => formatTanggal($fieldValue, "d F Y"),
        // "viewR" => formatTanggal($fieldValue,"d F Y"),
        // "dtime" => date("d F Y", strtotime($fieldValue)) . "<br><r><sub>".date("h:i:s", strtotime($fieldValue))."</sub></r>",
        // "oleh_nama" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",
        // "kode" => "<span class='glyphicon glyphicon-copyright-mark'></span> $fieldValue",
        // "customers_nama" => "<span class='fa fa-users'></span> $fieldValue",
        // "harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        // "sisa" => number_format((0 + $fieldValue), 0,"",","),
        // "tlp_1" => number_format((0 + $fieldValue), 0, ",", "."),
        // "hpp" => (str_replace(".0000000000","",$fieldValue)),
        // "harga" => (str_replace(".0000000000","",$fieldValue)),
        // "ppn" => (str_replace(".0000000000","",$fieldValue)),
        // "nett" => (str_replace(".0000000000","",$fieldValue)),
        // "nilai_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        // "nilai_be"        => number_format(0 + $fieldValue),
        // "nilai_af"        => number_format(0 + $fieldValue),
        // "unit_be_debet"  => number_format(0 + $fieldValue),
        // "unit_be_kredit" => number_format(0 + $fieldValue),
        // "unit_in_debet"  => number_format(0 + $fieldValue),
        // "unit_in_kredit" => number_format(0 + $fieldValue),
        // "unit_ot_debet"  => number_format(0 + $fieldValue),
        // "unit_ot_kredit" => number_format(0 + $fieldValue),
        // "nilai_be_debet"  => number_format(0 + $fieldValue),
        // "devices"  => "<span class='fa fa-tablet'></span> " . $fieldValue,
        // "tagihan"  => number_format(0 + $fieldValue),
        // "ppn"      => $fieldValue,
        "diskon_1_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_2_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_3_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_4_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_5_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_6_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_7_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_8_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "uang_muka_tanpa_ppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_nppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "returned" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_01" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_02" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_03" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_04" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_05" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_06" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_07" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_08" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_09" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_gunggung" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_didelete" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_cash" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nomer_referensi_bank" => (string)$fieldValue,
        "nomer_rekening_asal" => (string)$fieldValue,
        "nama_rekening_asal" => (string)$fieldValue,
        "bank_rekening_nama" => (string)$fieldValue,
        "dpp_taxes" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_pengganti_taxes" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "ppn_taxes" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        // START OF COMPLETE REPEATED LOGIC
        "grand_total_taxes" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "projectNilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_pph_original" => (0 + $fieldValue) >= 0 ? (number_format(0 + $fieldValue, 2)) : "(" . (number_format(-1 * (0 + $fieldValue), 2)) . ")",
        "nilai_kas_cn" => (0 + $fieldValue) >= 0 ? (number_format(0 + $fieldValue, 2)) : "(" . (number_format(-1 * (0 + $fieldValue), 2)) . ")",
    );
        // END OF COMPLETE REPEATED LOGIC

    // cekHijau("$fieldName  $fieldValue");
    /*====================================
     * format pengecualian untuk print
     * ====================================*/
    $cleans = array(
        "exp",
        "viewReceipt",
        "jurnal",
    );
    if (in_array($segment_2, $cleans)) {
        $formatsReceipt = array(
            "nomer" => "$fieldValue",
            "nomer_new" => "$fieldValue",
            "nomer_nolink" => "$fieldValue",
            "nomer_download" => "$fieldValue",
            "so_num" => "$fieldValue",
            "nomer_po" => "$fieldValue",
            "nomer_um" => "$fieldValue",
            "transaksi_no" => "$fieldValue",
            "produk_kode" => "$fieldValue",
        );

        if (key_exists($fieldName, $formatsReceipt)) {
            $formats = $formatsReceipt;
        }

        // return $fieldValues;
        // matiHere($fieldValuesOri . "*/*/" );
    }

    /* =================================================================
     * format dengan pola tertentu (custom)
     * =================================================================*/
    switch ($fieldName) {

        case "npwp":
        case "nomer_npwp":
        case "customerDetails__npwp":
        case "customerDetails_npwp":
            // cekMerah("$fieldName");
            $clean_0 = str_replace(".", "", $fieldValues);
            $clean_1 = str_replace("-", "", $clean_0);
            $clean_2 = str_replace(" ", "", $clean_1);
            $clean_10 = str_replace("_", "", $clean_2);
            $jmlDigit = strlen($clean_10);
            if ($jmlDigit == 15) {
                // cekLime("$clean_10");
                $splits = str_split($clean_10, 1);

                $newFormat = $splits[0] . $splits[1] . "." . $splits[2] . $splits[3] . $splits[4] . "." . $splits[5] . $splits[6] . $splits[7] . "." . $splits[8] . "-" . $splits[9] . $splits[10] . $splits[11] . "." . $splits[12] . $splits[13] . $splits[14];
                // cekHitam($newFormat);
                $formats[$fieldName] = $newFormat;
                // $formats["customerDetails_npwp"] = $newFormat;
            }
            else {
                $formats[$fieldName] = "<i class='text-red' title='$jmlDigit digit' data-toggle='tooltip'> $fieldValues</i>";
            }
            // arrPrint($formats);
            break;
        case "angka":
        case "debet":
        case "debet_awal":
        case "debet_akhir":
        case "kredit":
        case "kredit_awal":
        case "kredit_akhir":
            if (is_numeric($fieldValue)) {
                if ($fieldValue < 0) {
                    $formats[$fieldName] = "(" . number_format($fieldValue * -1) . ")";
                }
            }
            break;
        default:
            break;

        case "nama":
            if (is_numeric($fieldValue)) {
                $splits = str_split($fieldValue, 4);
                $fieldValue_f = implode("-", $splits);
                $formats[$fieldName] = $fieldValue_f;
                // arrPrint($splits);
                // cekMerah();
            }
            break;
        // case "nomer_top":
        // case "nomer":
        //     if ($segment_2 == "viewReceipt") {
        //         $exp = explode("-", $fieldValue);
        //         $tail = end($exp);
        //         // cekLime($tail);
        //         $tail_f = "<b class='font-size-1-2'>$tail</b>";
        //         $newFormat = str_replace($tail, $tail_f, $fieldValue);
        //         if (sizeof($exp) > 0) {
        //             $formats[$fieldName] = "$newFormat";
        //         }
        //         else {
        //             $formats[$fieldName] = "$newFormat";
        //
        //         }
        //         // cekHijau($tail_f . " " . $newFormat);
        //     }
        //
        //     break;
        case "tlp":
        case "tlp_2":
        case "tlp_3":
        case "phone":
        case "handphone":
        case "tlp_1":
            $splits = str_split($fieldValues, 4);
            // arrPrint($splits);
            if (sizeof($splits) > 1) {
                $newFormat = implode("-", $splits);
            }
            else {
                $newFormat = $fieldValues;
            }
            $formats[$fieldName] = $newFormat;
            break;

        case "premi":
        case "diskon":
            //        case "ppn":
        case "hpp":
        case "pph":
            // cekLime($fieldValues);
            $expl = explode(".", $fieldValues);
            // arrPrint($expl);
            if (isset($expl[1]) && $expl[1] > 0) {
                $newFormat = number_format($fieldValues, 2);
            }
            else {
                $newFormat = number_format($fieldValues, 0);
            }


            $formats[$fieldName] = $newFormat;
            break;
        case "eFaktur":
        case "eFaktur_insentif":
            $formats[$fieldName] = $fieldValue . "**";
            break;
    }

    if (array_key_exists($fieldName, $fieldRules)) {
        if (isset($fieldRules[$fieldName]['link']) && strlen($fieldRules[$fieldName]['link']) > 1) {
            $link = $fieldRules[$fieldName]['link'];
        }
        else {
            $link = "";
        }

        if (strlen($link) > 1) {
            $str = "<a href=\"javascript:void(0);\" style='white-space: nowrap;' titles='#open $fieldValue' data-toggles='tooltip' data-placements='auto' onClick=\"$link\">";
            $str .= "<span href='" . base_url() . "Addons/ViewDetails/nomer/$fieldValues' name='qtips' style='" . $fieldRules[$fieldName]['style'] . "' class='" . $fieldRules[$fieldName]['class'] . "'>";
            $str .= isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
            $str .= "</span>";
            $str .= "</a>";
        }
        else {
            $stle = isset($fieldRules[$fieldName]['style']) ? "style='" . $fieldRules[$fieldName]['style'] . "'" : "";
            $clss = isset($fieldRules[$fieldName]['class']) ? "class='" . $fieldRules[$fieldName]['class'] . "'" : "";
            $id = isset($fieldRules[$fieldName]['id']) ? "id='" . $fieldRules[$fieldName]['id'] . "'" : "";
            $str = "";
            //--- TIDAK PAKAI SPAN dan EMBEL2
            switch ($fieldName) {
                case "nomer_download":
                    $str .= isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
                    break;
                default:
                    $str .= "<span $id $stle $clss>";
                    $str .= isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
                    $str .= "</span>";
                    break;
            }
        }

        return $str;

    }
    else {
        if (array_key_exists($fieldName, $formats)) {
//            cekHere("[$fieldName]");
            $str = $formats[$fieldName];
        }
        else {
//            cekKuning("[$fieldName]");
            if (is_numeric($fieldValue)) {
                //                cekBiru($fieldName);
                $fieldValues = $fieldValue < 0 ? $fieldValue * -1 : $fieldValue;
                $isdesimal = preg_match('/^\d+\.\d+$/', $fieldValues);
                if ($isdesimal == 1) {

                    $str = number_format($fieldValue + 0, 2);
                }
                else {

                    $str = number_format($fieldValue + 0);
                }
            }
            else {
                $str = $fieldValue;
            }
        }

        return $str;
    }

}

function formatField_he_format($fieldName, $fieldValues, $jenis = "", $modul_path = "")
{
    // cekKuning("$fieldName, $fieldValues, $jenis , $modul_path");
    $jenisTr = strlen($jenis) > 2 ? $jenis : url_segment(4);
    $modul = strlen($modul_path) > 2 ? $modul_path : MODUL_PATH;


    $ci =& get_instance();
    $segment_2 = $ci->uri->segment(3);
    $ci->load->config("heTransaksi_ui");
    $ci->load->library("MataUang");
    $mu = new MataUang();

    $fkali = "";
    $type = "";
    $sym = "";

    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
        $fkali = isset($_GET['f']) ? blobDecode($_GET['f']) : "";
        $type = isset($_GET['type']) ? blobDecode($_GET['type']) : "";
        if ($fkali > 1) {
            $arrMataUang = $mu->getMataUang($type);
            if (sizeof($arrMataUang) > 0) {
                $sym = $arrMataUang['symbol'];
            }
        }
        elseif ($fkali == 1) {
            $sym = "";
        }
        else {
            $sym = "";
        }

    }


    $fieldName = strtolower(trim($fieldName));

    //region tambahan formater nomer transaksi
    if (in_array($fieldName, arrAvailFields())) {
        $exp = explode(".", $fieldValues);
        $lastKey = $exp[0];
        unset($exp[0]);
        $newValues = implode(".", $exp);

        $existKey = str_replace(".", "", $lastKey);//582

        if (isset(arrConfName()[$existKey])) {
            $extendKey = str_replace($lastKey, arrConfName()[$existKey], "$existKey");
//            $existKey = str_replace(".", "", $lastKey);//582

        }
        else {
            $fieldValue = $fieldValues;
        }

//        $fieldValue = isset(arrConfName()[$existKey]) ? str_replace($exp[0], arrConfName()[$existKey], "$fieldValues") : $fieldValues;
        $fieldValue = isset(arrConfName()[$existKey]) ? $extendKey . "." . $newValues : $fieldValues;
//        cekMerah($extendKey." || ".$lastKey);
//        cekLime($fieldValue);
    }
    else {
        $fieldValue = $fieldValues;
    }
    //endregion

    //-----------------------------------------------------------
    if ($fieldName == "date_transaksi_bank") {
        $fieldValues = str_replace("T", " ", $fieldValues);
    }


    //-----------------------------------------------------------
    $tmpOut = "";


    $fieldRules = array(
        "customerDetails_tlp_1" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "transaksi_jenis2" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "transaksi_jenis2_label" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "customerDetails_tlp_2" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "customerDetails_npwp" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),

        "lock" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "text-center",
        ),
        "project_start" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "text-center",
        ),

        //        "nomer_top"      => array(
        //            "style" => "color:#000000;text-align:center;",
        //            "link"  => "window.open('" . $modul . "Transaksi/viewReceipt/$jenisTr/" . $fieldValue . "')",
        //            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
        //            "class" => "",
        //        ),
        "nomer_ref_so" => array(
            "style" => "display: block; max-width: 250px; overflow-x: auto; white-space: nowrap; vertical-align: middle;' title='" . htmlspecialchars(strip_tags($fieldValue), ENT_QUOTES) . "",
            "class" => "text-left slim-scroll",
        ),
        "shipping_service" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_total_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nett1_bulat" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_ppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "ppn_netto" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "tagihan_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "pph_23" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "total_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_grand_ppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "pph23_nilai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "extern_nilai2" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "payment_out" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "credit_note_dipakai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "uang_muka_dipakai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_net3" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_disc" => array(
            "class" => "pull-right",
        ),
        "sub_nett1" => array(
            "class" => "pull-right",
        ),
        "sub_nett2" => array(
            "class" => "pull-right",
        ),
        "disc_percent" => array(
            "class" => "pull-right",
        ),
        "sub_nett1_valas" => array(
            "class" => "pull-right",
        ),
        "harganppn" => array(
            "class" => "pull-right",
        ),
        "review_details" => array(
            "style" => "color:#000000;text-align:center;display:block;",
            "link" => "showModal('" . $modul . "Transaksi/viewResumeDetails/$jenisTr/$fieldValues','view details')",
            "class" => "",
        ),
        "print_label" => array(
            "style" => "color:#0525f7;text-align:center;display:block;",
            "link" => "top.popBig('" . $modul . "Printing/viewReceiptReg/$jenisTr/$fieldValues');",
            "class" => "",
        ),
        "print_label_mod" => array(
            "style" => "color:#0525f7;text-align:center;display:block;",
            "link" => "top.popBig('" . $modul . "Printing/viewReceiptReg/$jenisTr/$fieldValues?mod=1');",
            "class" => "",
        ),
        "print_barcode" => array(
            "style" => "color:#0525f7;text-align:center;display:block;",
            // "link" => "top.popBig('" . $modul . "Printing/barcodeTransaksi/$jenisTr/$fieldValues');",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModul?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_pembelian" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModulPembelian?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_pembelian_2" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/BarcodePrinter/doPrintModulPembelian?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_voucher" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintVoucher?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_return" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModulReg?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_return_2" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/BarcodePrinter/doPrintModulReg?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "nomer_top" => array(
            "style" => "text-align:left; display:block;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "nomer_top_new" => array(
            "style" => "text-align:left; display:block;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "references_num" => array(
            "style" => "text-align:left; display:block;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__1" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__2" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__3" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "referencenomer__4" => array(
            "style" => "text-align:left; display:block;color:#000000;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "nomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "so_num" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),

        "nomer_po" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "nomer_um" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "allow_project" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "nomer_new" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "efakturSource" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "eFaktur" => array(
            "style" => "color:#000000;text-align:center;",
            "class" => "",
        ),
        "eFaktur_insentif" => array(
            "style" => "color:#000000;text-align:center;",
            "class" => "",
        ),
        "referencenomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_1" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_2" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_3" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "reference_so_nomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
//        "nomer_nolink" => array(
////            "style" => "color:#000000;text-align:left;",
////            "link" => "",
////            "class" => "",
//        ),
        "nomer_download" => array(),
        "refNum" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "transaksi_no" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "jenis" => array(
            "style" => "color:#000000;text-align:left;",
            //            "link"  => substr($fieldValue, 0, 3) == "582" ? "popSmall('" . $modul . "Transaksi/viewSmallReceipt/$jenisTr/$fieldValue')" : "popBig('" . $modul . "Transaksi/viewReceipt/$jenisTr/$fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "btn_action" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "OverDue_releaser/preview/$jenisTr/$fieldValues','Temporary unlock')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "btn btn-danger",
        ),
        "no" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "pull-right",
        ),
        "barcode" => array(
            "style" => "color:orange;text-align:left;font-weight:700;",
            "class" => "",
        ),
        "fulldate" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dateFaktur_insentif" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "extern_date2" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dtime_bank" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "oleh_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "extern_label2" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "kode" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "kode_cabang" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "no_part" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "suppliers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "customers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),

        "produk_ord_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "produk_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "produk_kode " => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "jenis_label" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),

        "satuan" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "btn-block text-right",
        ),
        "state" => array(
            "style" => "",
            "class" => "btn-block text-center",
        ),
        "action" => array(
            "style" => "text-align:center;",
            "class" => "btn-block text-right",
        ),
        "transaksi_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_nilai" => array(
            "class" => "pull-right",
        ),
        "sub_diskon_supplier_nilai" => array(
            "class" => "pull-right",
        ),
        "ppn_nilai" => array(
            "class" => "pull-right",
        ),
        "transaksi_net" => array(
            "class" => "pull-right",
        ),
        "new_net1" => array(
            "class" => "pull-right",
        ),
        "disc" => array(
            "class" => "pull-right",
        ),
        "disc_valas" => array(
            "class" => "pull-right",
        ),
        "sub_harga_valas" => array(
            "class" => "pull-right",
        ),
        "ppn" => array(
            "class" => "pull-right",
        ),
        "extern_nilai3" => array(
            "class" => "pull-right",
        ),
        "exchange__harga" => array(
            "class" => "pull-right",
        ),
        "nilai_tambah_ppn_out" => array(
            "class" => "pull-right",
        ),
        "nett2" => array(
            "class" => "pull-right",
        ),
        "nett1" => array(
            "class" => "pull-right",
        ),
        "jual" => array(
            "class" => "pull-right",
        ),
        "jual_nppn" => array(
            "class" => "pull-right",
        ),
        "angka" => array(
            "class" => "pull-right",
        ),
        "grand_total" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hpp_nppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "harga" => array(
            "class" => "pull-right",
        ),
        "nett1nppn" => array(
            "class" => "pull-right",
        ),
        "sub_nett1" => array(
            "class" => "pull-right",
        ),
        "projectharga" => array(
            "class" => "pull-right",
        ),
        "projectppn" => array(
            "class" => "pull-right",
        ),
        "projectgrandtotal" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "harga_ori" => array(
            "class" => "pull-right",
        ),
        "disc_value" => array(
            "class" => "pull-right",
        ),
        "hpp" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jml" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jml_nilai" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_jmlx" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_harga" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_hrg" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "harga_perolehan" => array(
            "style" => "color:#005689;font-weight:bold;",
            "class" => "btn-block",
        ),
        "sisa_depre" => array(
            "style" => "color:#005609;font-weight:bold;",
            "class" => "btn-block",
        ),
        "saldo_sisa" => array(
            "style" => "color:#011689;font-weight:bold;",
            "class" => "btn-block",
        ),
        "harga_sisa" => array(
            "style" => "color:#011600;font-weight:bold;",
            "class" => "btn-block",
        ),
        "nett" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "ongkir" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "ongkir_tax" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "install" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "install_tax" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "subtotal" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hrg_hpp" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hrg_hpp" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hpp" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hpp_nppn" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_harga" => array(
            "class" => "pull-right",
        ),
        "tagihan" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_pembulatan" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "creditAmount" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "terbayar" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sisa" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "a_sisa" => array(
            "style" => "color:#992200;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "a_saldo" => array(
            "style" => "color:#992200;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "diskon" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "add_disc" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_bayar" => array(
            "style" => "color:#009900;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_setor" => array(
            "style" => "color:#009900;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_entry" => array(
            "style" => "color:#000000;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_sisa" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "total" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;font-size:larger;",
            "class" => "btn-block text-right",
        ),
        "refValue" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "active" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hold" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_debet_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "balance" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "nilai_be" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "nilai_be_spo" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_spo" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_cl" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ne_spo" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_spd" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_spo" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //
        "unit_be_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_be_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_in_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_in_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_ot_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_ot_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_af_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_af_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //
        "nilai_be_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_be_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "textJumlah" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "text-bold text-uppercase pull-right",
        ),
        "uname" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "ipadd" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "devices" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "browser" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "discount" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        // "disc"   => array(
        //     "style" => "",
        //     "class" => "",
        // ),
        "dp" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "dp_value" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "dp_ppn_value" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //"tagihan"    => array(
        //    "style" => "color:#992200;font-weight:bold;text-align:right;",
        //    "class" => "btn-block text-right",
        //),
        "stok" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "reference" => array(
            "style" => "",
            "class" => "btn-block text-left",
        ),
        //"ppn"        => array(
        //    "style" => "",
        //    "class" => "btn-block text-right",
        //),
        "value" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "value_in" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "value_out" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "extern_nilai2" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        // "tlp"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_1"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_2"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_3"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "phone"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-left",
        // ),
        // "handphone"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-left",
        // ),
        "qty_opname" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_pembulatan" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "additionalfactor" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jenistr_reference" => array(
            "style" => "color:#000000;text-align:left;",
            "class" => "btn-block text-left",
        ),
        "saldo_qty_berjalan" => array(
            "class" => "pull-right",
        ),
        "saldo_berjalan" => array(
            "class" => "pull-right",
        ),
        "netto" => array(
            "class" => "pull-right",
        ),
        "ng_debet" => array(
            "class" => "pull-right",
        ),
        "ng_kredit" => array(
            "class" => "pull-right",
        ),
        "ng_qty_kredit" => array(
            "class" => "pull-right",
        ),
        "ng_qty_debet" => array(
            "class" => "pull-right",
        ),
        "total_qty_kredit" => array(
            "class" => "pull-right",
        ),
        "total_qty_debet" => array(
            "class" => "pull-right",
        ),
        "total_debet" => array(
            "class" => "pull-right",
        ),
        "total_kredit" => array(
            "class" => "pull-right",
        ),
        "harga_last" => array(
            "class" => "pull-right",
        ),
        "sub_harga_last" => array(
            "class" => "pull-right",
        ),
        "dp_dipakai_ui" => array(
            "class" => "pull-right",
        ),
        "dp_ppn_dipakai_ui" => array(
            "class" => "pull-right",
        ),
        "harga_last_purchase" => array(
            "class" => "pull-right",
        ),
        "number_value" => array(
            "class" => "pull-right",
        ),
        "number_value" => array(
            "class" => "pull-right",
        ),
        "sub_outstanding_items" => array(
            "class" => "pull-right",
        ),
        "amount" => array(
            "class" => "pull-right",
        ),
//        "harga_ori" => array(
//            "class" => "pull-right",
//        ),
//
        "nilai" => array(
            "class" => "pull-right",
        ),
        "sub_nilai" => array(
            "class" => "pull-right",
        ),
        "dpp_pph_persen" => array(
            "class" => "pull-right",
        ),
        "dpp_ppn_persen" => array(
            "class" => "pull-right",
        ),
        "dpppph" => array(
            "class" => "pull-right",
        ),
        "dppppn" => array(
            "class" => "pull-right",
        ),
        "nilai_supplies" => array(
            "class" => "pull-right",
        ),
        "sub_nilai_supplies" => array(
            "class" => "pull-right",
        ),
        "harga_bom" => array(
            "class" => "pull-right",
        ),
        "sub_harga_bom" => array(
            "class" => "pull-right",
        ),
        "harga_kompensasi" => array(
            "class" => "pull-right",
        ),
        "sub_harga_kompensasi" => array(
            "class" => "pull-right",
        ),
        "diskon_nilai" => array(
            "class" => "pull-right",
        ),
        "sub_diskon_supplier_nilai" => array(
            "class" => "pull-right",
        ),
        "sub_diskon_nilai" => array(
            "class" => "pull-right",
        ),
        "dpp_final" => array(
            "class" => "pull-right",
        ),
        "dpp_pengganti" => array(
            "class" => "pull-right",
        ),
        "ppn_final" => array(
            "class" => "pull-right",
        ),
        "list_items" => array(
            "class" => "",
        ),
        "total_diskon" => array(
            "class" => "pull-right",
        ),
        "ppn_out_bulat" => array(
            "class" => "pull-right",
        ),
        "grand_pembulatan" => array(
            "class" => "pull-right",
        ),
        "diskon_1_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_2_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_3_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_4_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_5_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_6_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_7_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_8_nilai" => array(
            "class" => "pull-right",
        ),
        "referensi_po_uangmuka" => array(
            "class" => "pull-right",
        ),
        "referensi_titipan_po_uangmuka" => array(
            "class" => "pull-right",
        ),
        "uang_muka_tanpa_ppn" => array(
            "class" => "pull-right",
        ),
        "dpp" => array(
            "class" => "pull-right",
        ),
        "dpp_nppn" => array(
            "class" => "pull-right",
        ),
        "nilai_kas_cn" => array(
            "class" => "pull-right",
        ),
        "nilai_kas_cn_detail" => array(
            "class" => "pull-right",
        ),
        "nilai_pph_original" => array(
            "class" => "pull-right",
        ),
        "harga_original" => array(
            "class" => "pull-right",
        ),
        "realisasi_netto" => array(
            "class" => "pull-right",
        ),
        "realisasi_kurang" => array(
            "class" => "pull-right",
        ),
        "returned" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_01" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_02" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_03" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_04" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_05" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_06" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_07" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_08" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_09" => array(
            "class" => "pull-right",
        ),
        "nilai_ppn_gunggung" => array(
            "class" => "pull-right",
        ),
        "nilai_didelete" => array(
            "class" => "pull-right",
        ),
        "nilai_cash" => array(
            "class" => "pull-right",
        ),
        "diskon_nilai_total" => array(
            "class" => "pull-right",
        ),
        "nilai_total_diklaim" => array(
            "class" => "pull-right",
        ),
        "sisa_diskon" => array(
            "class" => "pull-right",
        ),
        "diskon_nilai_total_freeproduk" => array(
            "class" => "pull-right",
        ),
        "dpp_taxes" => array(
            "class" => "pull-right",
        ),
        "dpp_pengganti_taxes" => array(
            "class" => "pull-right",
        ),
        "ppn_taxes" => array(
            "class" => "pull-right",
        ),
        "grand_total_taxes" => array(
            "class" => "pull-right",
        ),
        "projectNilai" => array(
            "class" => "pull-right",
        ),
        "detail_marketplace" => array(
            "class" => "pull-left",
        ),
    );

    $formats = array(
        "nilai_entry" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "ppn_netto" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "grand_ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "extern_nilai3" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "nilai_tambah_ppn_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "sub_hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "ppn_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "new_grand_ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor($fieldValue)),

        "lock" => $fieldValue == 1 ? "<span class='btn btn-xs btn-danger' title='BOM Project telah dikunci.'><i class='fa fa-lock'></i> Locked</span>" : "<span class='btn btn-xs btn-success' title='BOM Project belum di kunci, BOM masih bisa di Edit oleh pihak berwenang.'><i class='fa fa-unlock'></i> Unlocked</span>",
        "project_start" => $fieldValue == 1 ? "<span class='btn btn-xs btn-success' title='Project telah RUNNING,, Silahkan menambahkan TUGAS PROYEK.'><i class='fa fa-refresh fa-spin'></i> RUNNING </span>" : "<span class='btn btn-xs btn-danger' title='Menunggu Mulainya PROJECT, jika Quotation sudah di approve, silahkan untuk menombol Mulai Project.'><i class='fa fa-stop'></i> BELUM DIRUNNING</span>",

        "shipping_service" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett1_bulat" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_total_ui" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "projectharga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "projectppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "projectgrandtotal" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),

        "tagihan_ui" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "credit_note_dipakai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "uang_muka_dipakai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "pph_23" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "extern_nilai2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "pph23_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "payment_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_nett1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_nett2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "new_net3" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "review_details" => "<span class='fa fa-eye'></span>",
        "print_label" => "<span class='fa fa-print' style=\"font-size:20px\"></span>",
        "print_label_mod" => "<span class='fa fa-print' style=\"font-size:20px\"></span>",
        "print_barcode" => "<button type='button' class='btn btn-info btn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_barcode_pembelian" => "&nbsp;<button type='button' class='btn btn-info bbtn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_voucher" => "&nbsp;<button type='button' class='btn btn-info bbtn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print Voucher</span></button>",
        "print_barcode_pembelian_2" => "&nbsp;<button type='button' class='btn btn-primary bbtn-block hidden-xs hidden-sm'><span class='fa fa-barcode' style=\"font-size:1.2em\"> Print Barcode</span></button>",
        "print_barcode_return" => "&nbsp;<button type='button' class='btn btn-info bbtn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_barcode_return_2" => "&nbsp;<button type='button' class='btn btn-primary bbtn-block hidden-xs hidden-sm'><span class='fa fa-barcode' style=\"font-size:1.2em\"> Print Barcode</span></button>",

        "nomer_top" => "$fieldValue",
        "referencenomer__1" => "$fieldValue",
        "referencenomer__2" => "$fieldValue",
        "referencenomer__3" => "$fieldValue",
        "referencenomer__4" => "$fieldValue",
        "nomer_top_new" => "$fieldValue",
        // "nomer_top"        => "<span class='fa fa-file-o' style='color:#55cc55;'></span> $fieldValue",
        "nomer" => "$fieldValue",
        "nomer_po" => "$fieldValue",
        "nomer_um" => "$fieldValue",
        "allow_project" => "$fieldValue",
        "nomer_new" => "$fieldValue",
        "nomer_top2" => "$fieldValue",
        "references_num" => "$fieldValue",
        "produk_kode" => "<span onclick=\"showImageSwal('$fieldValue','$tmpOut')\">$fieldValue\n</span>",
        "produk_kode_nolink" => "$fieldValue",
        "referencenomer" => "$fieldValue",
        "nomer_nolink" => "$fieldValue",
        "nomer_download" => "$fieldValue",
        // "nomer"            => "<span class='fa fa-file-text-o' style='color:#0056cd;'></span> $fieldValue",
        "refNum" => strlen($fieldValue) > 0 ? "<span class='fa fa-file-text-o' style='color:#ff7700;'></span> $fieldValue" : $fieldValue,
        //        "jenis"           => "<span class='fa fa-file-text-o'></span> ".$ci->config->item('heTransaksi_ui')[$fieldValue]['label'],
        // "transaksi_no" => "<span class='fa fa-file-text-o' style='color:#000000;'></span> $fieldValue",
        "transaksi_no" => "$fieldValue",
        "efakturSource" => "$fieldValue",
        "eFaktur" => "$fieldValue",
        "eFaktur_insentif" => "$fieldValue",
        "pairtransaksinomer_1" => "$fieldValue",
        "pairtransaksinomer_2" => "$fieldValue",
        "pairtransaksinomer_3" => "$fieldValue",
        // "date_time"          => formatTanggal($fieldValue, "d M Y H:i"),
        // "dtime"              => formatTanggal($fieldValue, "d F Y"),
        // "fulldate"           => formatTanggal($fieldValue, "d F Y"),
        // "shippingDate_value" => formatTanggal($fieldValue, "d F Y"),
        // "duedate_value"      => formatTanggal($fieldValue, "d F Y"),
        // "shippingdate_value" => formatTanggal($fieldValue, "d F Y"),
        // "viewR" => formatTanggal($fieldValue,"d F Y"),
        "date_time" => date("d M Y H:i", strtotime($fieldValue)),

        "auth_dtime" => date("d F Y ", strtotime($fieldValue)),
        // "dtime" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "dtime" => isDate($fieldValue) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "dtime_po" => isDate($fieldValue) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "dtime_um" => isDate($fieldValue) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "date_transaksi_bank" => isDate($fieldValue) ? date("d M Y H:i", strtotime($fieldValue)) : "",
//        "dtime_bank" => isDate($fieldValue) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        //        "dtime" => date("d F Y", strtotime($fieldValue)) . "<br><r><sub>".date("h:i:s", strtotime($fieldValue))."</sub></r>",
        // "fulldate" => date("d F Y", strtotime($fieldValue)),
        "dtime_bank" => isDate($fieldValue) ? date("d F Y", strtotime($fieldValue)) : $fieldValue,
        "fulldate" => isDate($fieldValue) ? date("d F Y", strtotime($fieldValue)) : $fieldValue,
        "fulldate_m" => date("d M Y", strtotime($fieldValue)),
        "dateFaktur" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "dateFaktur_insentif" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "extern_date2" => ($fieldValue != "0000-00-00") ? date("d F Y", strtotime($fieldValue)) : "",
        "dtime_entry" => date("d F Y", strtotime($fieldValue)),
        "ebillingDate" => date("d F Y", strtotime($fieldValue)),
        "ebillingDatef" => date("d F Y", strtotime($fieldValue)),
        "shippingDate_value" => date("d F Y", strtotime($fieldValue)),
        "duedate_value" => date("d F Y", strtotime($fieldValue)),
        "shippingdate_value" => date("d F Y", strtotime($fieldValue)),

        "oleh_nama" => "$fieldValue",
        "extern_label2" => "$fieldValue",
        //        "oleh_nama" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",
        //        "kode" => "<span class='glyphicon glyphicon-copyright-mark'></span> $fieldValue",
        "kode" => "$fieldValue",
        "kode_cabang" => "$fieldValue",
        "no_part" => "<span class='glyphicon glyphicon-subtitles'></span> $fieldValue",
        "uname" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",
        //        "customers_nama" => "<span class='fa fa-users'></span> $fieldValue",
        "customers_nama" => "$fieldValue",
        "transaksi_jenis2" => "$fieldValue",
        "transaksi_jenis2_label" => "$fieldValue",
        "suppliers_nama" => "$fieldValue",
        "jenis_nama" => "<span class='glyphicon glyphicon-th-list'></span> $fieldValue",
        "produk_nama" => "<span > $fieldValue</span>",
        "hpp" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        // "harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "nett1nppn" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_nett1" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "harga_ori" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "disc_value" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_tambah_ppn_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "subtotal" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "hrg_hpp" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_hrg_hpp" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_nett1_include_ppn" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",

        "sub_hpp" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "transaksi_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_total" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "total" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ongkir" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ongkir_tax" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "install" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "install_tax" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "tagihan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_pembulatan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "creditAmount" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "terbayar" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "diskon" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "add_disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "a_sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "a_saldo" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_bayar" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_setor" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
//        "nilai_entry" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "refValue" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "berat" => number_format((0 + $fieldValue), 2),
        "premi_nilai" => number_format((0 + $fieldValue), 0, ",", "."),
        "pph" => number_format((0 + $fieldValue), 2, ",", "."),
//        "sisa"                    => number_format((0 + $fieldValue), 0,"",","),
        // "tlp_1"                  => number_format((0 + $fieldValue), 0, ",", "."),

        //        "hpp" => (str_replace(".0000000000","",$fieldValue)),
        //        "harga" => (str_replace(".0000000000","",$fieldValue)),
        //        "ppn" => (str_replace(".0000000000","",$fieldValue)),
        //        "nett" => (str_replace(".0000000000","",$fieldValue)),
        "new_sisa" => number_format(0 + $fieldValue),
        "harga_debet_awal" => number_format(0 + $fieldValue),
        "harga_debet" => number_format(0 + $fieldValue),
        "harga_kredit" => number_format(0 + $fieldValue),
        "harga_saldo" => number_format(0 + $fieldValue),
        "debet_awal" => number_format(0 + $fieldValue),
        "debet" => number_format(0 + $fieldValue),
        "active" => number_format(0 + $fieldValue),
        "hold" => number_format(0 + $fieldValue),
        "debet_akhir" => number_format(0 + $fieldValue),
        "akhir" => number_format(0 + $fieldValue),
        "avail" => number_format(0 + $fieldValue),
        "saldo" => is_numeric($fieldValue) ? number_format(0 + $fieldValue) : $fieldValue,

        "kredit_awal" => number_format(0 + $fieldValue),
        "kredit" => number_format(0 + $fieldValue),
        "nilai_kredit" => number_format(0 + $fieldValue),
        "kredit_akhir" => number_format(0 + $fieldValue),
        "balance" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",

        "qty_debet_awal" => number_format(0 + $fieldValue),
        "qty_debet" => number_format(0 + $fieldValue),
        "qty_debet_akhir" => number_format(0 + $fieldValue),

        "qty_kredit_awal" => number_format(0 + $fieldValue),
        "qty_kredit" => number_format(0 + $fieldValue),
        "qty_kredit_akhir" => number_format(0 + $fieldValue),

        "nilai_be_spo" => number_format(0 + $fieldValue),
        "nilai_ot_spo" => number_format(0 + $fieldValue),
        "nilai_ot_cl" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_ne_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_ot_spd" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        // "nilai_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",

        // "nilai_be"        => number_format(0 + $fieldValue),
        "nilai_be" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_in" => number_format(0 + $fieldValue),
        "nilai_ot" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_af" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        // "nilai_af"        => number_format(0 + $fieldValue),
        "disc_valas" => number_format(0 + $fieldValue, 2),
        "sub_harga_valas" => number_format(0 + $fieldValue, 2),
        "sub_nett1_valas" => number_format(0 + $fieldValue, 2),
        "disc_percent" => number_format(0 + $fieldValue, 2),
        //
        //        "unit_be_debet"  => number_format(0 + $fieldValue),
        //        "unit_be_kredit" => number_format(0 + $fieldValue),
        //        "unit_in_debet"  => number_format(0 + $fieldValue),
        //        "unit_in_kredit" => number_format(0 + $fieldValue),
        //        "unit_ot_debet"  => number_format(0 + $fieldValue),
        //        "unit_ot_kredit" => number_format(0 + $fieldValue),
        "unit_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "unit_af" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "unit_af_debet" => number_format(0 + $fieldValue),
        "unit_af_kredit" => number_format(0 + $fieldValue),

        //        "nilai_be_debet"  => number_format(0 + $fieldValue),
        "kredit_limit" => number_format(0 + $fieldValue, 0, ",", "."),
        "nilai_in_debet" => number_format(0 + $fieldValue),
        "nilai_in_kredit" => number_format(0 + $fieldValue),
        "nilai_ot_debet" => number_format(0 + $fieldValue),
        "nilai_ot_kredit" => number_format(0 + $fieldValue),
        "nilai_af_debet" => number_format(0 + $fieldValue),
        "nilai_af_kredit" => number_format(0 + $fieldValue),
        // "nilai_kredit" => number_format(0 + $fieldValue),
        "stok_aktif" => $fieldValue < 0 ? "<span class='text-red'>(" . ($fieldValue * -1) . ")</span>" : "<span class='font-size-1-2 tebal'>" . number_format(0 + $fieldValue) . "</span>",

        "produk_ord_harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "produk_ord_hrg" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga_perolehan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sisa_depre" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 0, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "saldo_sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 0, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga_sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : (($fieldValue * 1) > 0 ? number_format(0 + $fieldValue) : $fieldValue),
        "produk_ord_jmlx" => number_format(0 + $fieldValue),
        "jual" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "jual_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harganppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),

        "new_net1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "diskon_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_diskon_supplier_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ppn_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "transaksi_net" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "state" => $fieldValue,
        "ipadd" => ($fieldValue),
        "devices" => "<span class='fa fa-tablet'></span> " . heReturnDeviceName($fieldValue)['device'] . "/" . heReturnDeviceName($fieldValue)['browser'],
        "browser" => "<span class='fa fa-tablet'></span> " . heReturnDeviceName($fieldValue)['device'] . "/" . heReturnDeviceName($fieldValue)['browser'],
        //        "devices"  => "<span class='fa fa-tablet'></span> " . $fieldValue,
        "discount" => number_format(0 + $fieldValue),
        "dp" => number_format(0 + $fieldValue),
        "dp_value" => number_format(0 + $fieldValue),
        "dp_ppn_value" => number_format(0 + $fieldValue),
        //        "tagihan"  => number_format(0 + $fieldValue),
        "curency" => "Rp. " . number_format(0 + $fieldValue),
//        "barcode" => "--",
        "barcode" => $fieldValue,
        "npwp" => $fieldValue,
        "no_ktp" => $fieldValue,
        "nik" => $fieldValue,
        "tlp" => $fieldValue,
        "tlp_1" => $fieldValue,
        "tlp_2" => $fieldValue,
        "tlp_3" => $fieldValue,
        "phone" => $fieldValue,
        "handphone" => $fieldValue,
        "customerdetails_tlp_1" => $fieldValue,
        "customerdetails_tlp_2" => $fieldValue,
        //        "ppn"      => $fieldValue,

        "no" => number_format(0 + $fieldValue),
        "value" => number_format(0 + $fieldValue),
        "value_in" => number_format(0 + $fieldValue),
        "value_out" => number_format(0 + $fieldValue),
        "angka" => number_format(0 + $fieldValue, 2),
        "disc_persent" => number_format(0 + $fieldValue, 2),
        "customerDetails__npwp" => "$fieldValue",
        "customerDetails__tlp_1" => "$fieldValue",
        "customerDetails__tlp_2" => "$fieldValue",
        "btn_action" => "<span class='btn btn-xs fa fa-calendar' style=\"font-size:15px\"> review</span>",

        "berat_gross" => number_format(($fieldValue * 1) / 1000) . "",
        "sub_berat_gross" => number_format(($fieldValue * 1) / 1000) . "",
        "volume_gross" => number_format(($fieldValue * 1) / 1000000000, 2) . "",
        "sub_volume_gross" => number_format(($fieldValue * 1) / 1000000000, 2) . "",
//        "stok" => number_format(0 + $fieldValue),
        "stok" => ($fieldValue < 0) ? "(" . number_format($fieldValue * -1) . ")" : number_format(0 + $fieldValue),
        "qty_opname" => number_format(0 + $fieldValue),
        "sub_outstanding_items" => number_format(0 + $fieldValue),
        "exchange__harga" => number_format(0 + $fieldValue),
        "nilai_pembulatan" => "$fieldValue",
        "additionalfactor" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "produk_kode " => $fieldValue,
        "jenistr_reference " => $fieldValue,
        "saldo_qty_berjalan" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "saldo_berjalan" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "netto" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_qty_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_qty_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_qty_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_qty_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "harga_last" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "sub_harga_last" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dp_dipakai_ui" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dp_ppn_dipakai_ui" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "harga_last_purchase" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dpp_dp" => $fieldValue >= 0 ? (number_format(ceil($fieldValue))) : "(" . (number_format(-1 * ceil($fieldValue))) . ")",
        "ppn_dp" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "number_value" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        // "total_ui" => $fieldValue >= 0 ? (number_format(ceil($fieldValue))) : "(" . (number_format(-1 * ceil($fieldValue))) . ")",
        "total_ui" => $fieldValue >= 0 ? (number_format(round($fieldValue))) : "(" . (number_format(-1 * round($fieldValue))) . ")",
        "new_grand_ppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_outstanding_items" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "unit" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "jml_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "amount" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
//        "harga_ori" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_ppn_persen" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_pph_persen" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dppppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpppph" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_supplies" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_nilai_supplies" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "harga_bom" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_harga_bom" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "harga_kompensasi" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_harga_kompensasi" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_diskon_supplier_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_diskon_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
//        "note" => nl2br($fieldValue),
//        "description" => nl2br($fieldValue),
        "spek" => nl2br($fieldValue),
        "note" => ($fieldValue),
        "list_items" => ($fieldValue),
        "description" => ($fieldValue),
        "reference" => ($fieldValue),
        "dpp_final" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_pengganti" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "ppn_final" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "total_diskon" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "ppn_out_bulat" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "grand_pembulatan" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_1_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_2_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_3_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_4_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_5_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_6_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_7_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_8_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "referensi_po_uangmuka" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "referensi_titipan_po_uangmuka" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "uang_muka_tanpa_ppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_nppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_kas_cn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_kas_cn_detail" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_pph_original" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "harga_original" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "realisasi_netto" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "realisasi_kurang" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "returned" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_01" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_02" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_03" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_04" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_05" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_06" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_07" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_08" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_09" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_ppn_gunggung" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_didelete" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_cash" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nomer_referensi_bank" => (string)$fieldValue,
        "nomer_rekening_asal" => (string)$fieldValue,
        "nama_rekening_asal" => (string)$fieldValue,
        "bank_rekening_nama" => (string)$fieldValue,
        "diskon_nilai_total" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_total_diklaim" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sisa_diskon" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_nilai_total_freeproduk" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_taxes" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_pengganti_taxes" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "ppn_taxes" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "grand_total_taxes" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "projectNilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "detail_marketplace" => $fieldValue,

    );


    /*====================================
     * format pengecualian untuk print
     * ====================================*/
    $cleans = array(
        "exp",
        "viewReceipt",
        "jurnal",
    );
    if (in_array($segment_2, $cleans)) {
        $formatsReceipt = array(
            "nomer" => "$fieldValue",
            "nomer_new" => "$fieldValue",
            "nomer_nolink" => "$fieldValue",
            "nomer_download" => "$fieldValue",
            "transaksi_no" => "$fieldValue",
            "produk_kode" => "$fieldValue",
        );

        if (key_exists($fieldName, $formatsReceipt)) {
            $formats = $formatsReceipt;
        }

        // return $fieldValues;
        // matiHere($fieldValuesOri . "*/*/" );
    }

    /* =================================================================
     * format dengan pola tertentu (custom)
     * =================================================================*/
    switch ($fieldName) {

        case "efaktur":
            $formats[$fieldName] = $fieldValue;
            break;

        case "npwp":
        case "nomer_npwp":
        case "customerDetails__npwp":
        case "customerDetails_npwp":
            // cekMerah("$fieldName");
            $clean_0 = str_replace(".", "", $fieldValues);
            $clean_1 = str_replace("-", "", $clean_0);
            $clean_2 = str_replace(" ", "", $clean_1);
            $clean_10 = str_replace("_", "", $clean_2);
            $jmlDigit = strlen($clean_10);
            if ($jmlDigit == 15) {
                // cekLime("$clean_10");
                $splits = str_split($clean_10, 1);

                $newFormat = $splits[0] . $splits[1] . "." . $splits[2] . $splits[3] . $splits[4] . "." . $splits[5] . $splits[6] . $splits[7] . "." . $splits[8] . "-" . $splits[9] . $splits[10] . $splits[11] . "." . $splits[12] . $splits[13] . $splits[14];
                // cekHitam($newFormat);
                $formats[$fieldName] = $newFormat;
                // $formats["customerDetails_npwp"] = $newFormat;
            }
            else {
                $formats[$fieldName] = "<i class='text-red' title='$jmlDigit digit' data-toggle='tooltip'> $fieldValues</i>";
            }
            // arrPrint($formats);
            break;
        case "angka":
        case "debet":
        case "debet_awal":
        case "debet_akhir":
        case "kredit":
        case "kredit_awal":
        case "kredit_akhir":
            if (is_numeric($fieldValue)) {
                if ($fieldValue < 0) {
                    $formats[$fieldName] = "(" . number_format($fieldValue * -1) . ")";
                }
            }
            break;
        default:
            break;

        case "nama":
            if (is_numeric($fieldValue)) {
                $splits = str_split($fieldValue, 4);
                $fieldValue_f = implode("-", $splits);
                $formats[$fieldName] = $fieldValue_f;
                // arrPrint($splits);
                // cekMerah();
            }
            break;
        // case "nomer_top":
        // case "nomer":
        //     if ($segment_2 == "viewReceipt") {
        //         $exp = explode("-", $fieldValue);
        //         $tail = end($exp);
        //         // cekLime($tail);
        //         $tail_f = "<b class='font-size-1-2'>$tail</b>";
        //         $newFormat = str_replace($tail, $tail_f, $fieldValue);
        //         if (sizeof($exp) > 0) {
        //             $formats[$fieldName] = "$newFormat";
        //         }
        //         else {
        //             $formats[$fieldName] = "$newFormat";
        //
        //         }
        //         // cekHijau($tail_f . " " . $newFormat);
        //     }
        //
        //     break;
        case "tlp":
        case "tlp_2":
        case "tlp_3":
        case "phone":
        case "handphone":
        case "tlp_1":
            $splits = str_split($fieldValues, 4);
            // arrPrint($splits);
            if (sizeof($splits) > 1) {
                $newFormat = implode("-", $splits);
            }
            else {
                $newFormat = $fieldValues;
            }
            $formats[$fieldName] = $newFormat;
            break;

        case "premi":
        case "diskon":
            //        case "ppn":
        case "hpp":
        case "pph":
            // cekLime($fieldValues);
            $expl = explode(".", $fieldValues);
            // arrPrint($expl);
            if (isset($expl[1]) && $expl[1] > 0) {
                $newFormat = number_format($fieldValues, 2);
            }
            else {
                $newFormat = number_format($fieldValues, 0);
            }


            $formats[$fieldName] = $newFormat;
            break;
    }

    if (array_key_exists($fieldName, $fieldRules)) {

        if (isset($fieldRules[$fieldName]['link']) && strlen($fieldRules[$fieldName]['link']) > 1) {
            $link = $fieldRules[$fieldName]['link'];
        }
        else {
            $link = "";
        }

        if (strlen($link) > 1) {
            $str = "<a href=\"javascript:void(0);\" style='white-space: nowrap;' titles='#open $fieldValue' data-toggles='tooltip' data-placements='auto' onClick=\"$link\">";
            $str .= "<span href='" . $modul . "ViewDetails/nomer/$jenisTr/$fieldValues' name='qtips' style='" . $fieldRules[$fieldName]['style'] . "' class='" . $fieldRules[$fieldName]['class'] . "'>";
            $str .= isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
            $str .= "</span>";
            $str .= "</a>";
        }
        else {
            $stle = isset($fieldRules[$fieldName]['style']) ? "style='" . $fieldRules[$fieldName]['style'] . "'" : "";
            $clss = isset($fieldRules[$fieldName]['class']) ? "class='" . $fieldRules[$fieldName]['class'] . "'" : "";
            $id = isset($fieldRules[$fieldName]['id']) ? "id='" . $fieldRules[$fieldName]['id'] . "'" : "";
            $str = "";

            static $slim_scroll_printed = false;
            if ($fieldName == 'nomer_ref_so' && !$slim_scroll_printed) {
                $str .= "<style>.slim-scroll::-webkit-scrollbar { height: 6px; } .slim-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }</style>";
                $slim_scroll_printed = true;
            }

            //--- TIDAK PAKAI SPAN dan EMBEL2
            switch ($fieldName) {
                case "nomer_download":
                    $str .= isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
                    break;
                default:
                    $str .= "<span $id $stle $clss>";
                    $str .= isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
                    $str .= "</span>";
                    break;
            }
        }


        return $str;
        //        return $fieldValue;
    }
    else {
        if (array_key_exists($fieldName, $formats)) {
            $str = $formats[$fieldName];
        }
        else {
            if (is_numeric($fieldValue)) {
                $fieldValues = $fieldValue < 0 ? $fieldValue * -1 : $fieldValue;
                $isdesimal = preg_match('/^\d+\.\d+$/', $fieldValues);
                if ($isdesimal == 1) {
                    $str = number_format($fieldValue + 0, 2);
                }
                else {
                    //cekKuning("[$fieldName]");
                    $str = number_format($fieldValue + 0);
                }
            }
            else {
                $str = $fieldValue;
            }
        }


        return $str;
    }

}

function formatField_he_format_json($fieldName, $fieldValues, $jenis = "", $modul_path = "")
{
    // cekKuning("$fieldName, $fieldValues, $jenis , $modul_path");
    $jenisTr = strlen($jenis) > 2 ? $jenis : url_segment(4);
    $modul = strlen($modul_path) > 2 ? $modul_path : MODUL_PATH;


    $ci =& get_instance();
    $segment_2 = $ci->uri->segment(3);
    $ci->load->config("heTransaksi_ui");
    $ci->load->library("MataUang");
    $mu = new MataUang();

    $fkali = "";
    $type = "";
    $sym = "";

    if (isset($_GET['type']) && blobDecode($_GET['type']) != 'IDR' && isset($_GET['f'])) {
        $fkali = isset($_GET['f']) ? blobDecode($_GET['f']) : "";
        $type = isset($_GET['type']) ? blobDecode($_GET['type']) : "";
        if ($fkali > 1) {
            $arrMataUang = $mu->getMataUang($type);
            if (sizeof($arrMataUang) > 0) {
                $sym = $arrMataUang['symbol'];
            }
        }
        elseif ($fkali == 1) {
            $sym = "";
        }
        else {
            $sym = "";
        }

    }


    $fieldName = strtolower(trim($fieldName));

    //region tambahan formater nomer transaksi
    if (in_array($fieldName, arrAvailFields())) {
        $exp = explode(".", $fieldValues);
        $lastKey = $exp[0];
        unset($exp[0]);
        $newValues = implode(".", $exp);

        $existKey = str_replace(".", "", $lastKey);//582

        if (isset(arrConfName()[$existKey])) {
            $extendKey = str_replace($lastKey, arrConfName()[$existKey], "$existKey");
//            $existKey = str_replace(".", "", $lastKey);//582

        }
        else {
            $fieldValue = $fieldValues;
        }

//        $fieldValue = isset(arrConfName()[$existKey]) ? str_replace($exp[0], arrConfName()[$existKey], "$fieldValues") : $fieldValues;
        $fieldValue = isset(arrConfName()[$existKey]) ? $extendKey . "." . $newValues : $fieldValues;
//        cekMerah($extendKey." || ".$lastKey);
//        cekLime($fieldValue);
    }
    else {
        $fieldValue = $fieldValues;
    }
    //endregion

    //-----------------------------------------------------------


    //-----------------------------------------------------------
    $tmpOut = "";
    if ($fieldName == 'produk_kode') {
        $ci->load->model("Mdls/MdlProduk");
        $o = new MdlProduk();
        $o->addFilter("kode='" . trim($fieldValues) . "'");
        $tmp = $o->lookupAll()->result();

        $tmpOut = "noimage";
        if (sizeof($tmp) > 0) {
            $ci->load->model("Mdls/MdlImages");
            $oi = new MdlImages();
            $oi->addFilter("parent_id='" . trim($tmp[0]->id) . "'");
            $tmps = $oi->lookupAll()->result();
            $tmpOut = isset($tmps[0]->files) && $tmps[0]->files != '' ? $tmps[0]->files : "noimage";
        }
    }

    $fieldRules = array(
        "customerDetails_tlp_1" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "transaksi_jenis2" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "transaksi_jenis2_label" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "customerDetails_tlp_2" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),
        "customerDetails_npwp" => array(
            "style" => "color:#000000;text-align:center;",
            //            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "",
        ),

        "lock" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "text-center",
        ),
        "project_start" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . base_url() . "Transaksi/viewResume/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "text-center",
        ),

        //        "nomer_top"      => array(
        //            "style" => "color:#000000;text-align:center;",
        //            "link"  => "window.open('" . $modul . "Transaksi/viewReceipt/$jenisTr/" . $fieldValue . "')",
        //            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
        //            "class" => "",
        //        ),
        "shipping_service" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_total_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nett1_bulat" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_ppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "ppn_netto" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "tagihan_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "pph_23" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "total_ui" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_grand_ppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "pph23_nilai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "extern_nilai2" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "payment_out" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "credit_note_dipakai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "uang_muka_dipakai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_net3" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_disc" => array(
            "class" => "pull-right",
        ),
        "sub_nett1" => array(
            "class" => "pull-right",
        ),
        "sub_nett2" => array(
            "class" => "pull-right",
        ),
        "disc_percent" => array(
            "class" => "pull-right",
        ),
        "sub_nett1_valas" => array(
            "class" => "pull-right",
        ),
        "harganppn" => array(
            "class" => "pull-right",
        ),
        "review_details" => array(
            "style" => "color:#000000;text-align:center;display:block;",
            "link" => "showModal('" . $modul . "Transaksi/viewResumeDetails/$jenisTr/$fieldValues','view details')",
            "class" => "",
        ),
        "print_label" => array(
            "style" => "color:#0525f7;text-align:center;display:block;",
            "link" => "top.popBig('" . $modul . "Printing/viewReceiptReg/$jenisTr/$fieldValues');",
            "class" => "",
        ),
        "print_label_mod" => array(
            "style" => "color:#0525f7;text-align:center;display:block;",
            "link" => "top.popBig('" . $modul . "Printing/viewReceiptReg/$jenisTr/$fieldValues?mod=1');",
            "class" => "",
        ),
        "print_barcode" => array(
            "style" => "color:#0525f7;text-align:center;display:block;",
            // "link" => "top.popBig('" . $modul . "Printing/barcodeTransaksi/$jenisTr/$fieldValues');",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModul?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_pembelian" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModulPembelian?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_pembelian_2" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/BarcodePrinter/doPrintModulPembelian?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_voucher" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintVoucher?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_return" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/Qr/doPrintModulReg?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "print_barcode_return_2" => array(
            "style" => "color:#0525f7;text-align:center;display:bblock;",
            "link" => "top.popBig('" . base_url() . "addons/BarcodePrinter/doPrintModulReg?jn=$jenisTr&mid=$fieldValues&FromTransaksi=$fieldValues');",
            "class" => "",
        ),
        "nomer_top" => array(
            "style" => "text-align:left; display:block;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "nomer_top_new" => array(
            "style" => "text-align:left; display:block;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "references_num" => array(
            "style" => "text-align:left; display:block;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            "class" => "text-hover",
        ),
        "nomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "allow_project" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "nomer_new" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "efakturSource" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "eFaktur" => array(
            "style" => "color:#000000;text-align:center;",
            "class" => "",
        ),
        "referencenomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_1" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_2" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "pairtransaksinomer_3" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "nomer_nolink" => array(
            "style" => "color:#000000;text-align:left;",
            "link" => "",
            "class" => "",
        ),
        "nomer_download" => array(),
        "refNum" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "transaksi_no" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "jenis" => array(
            "style" => "color:#000000;text-align:left;",
            //            "link"  => substr($fieldValue, 0, 3) == "582" ? "popSmall('" . $modul . "Transaksi/viewSmallReceipt/$jenisTr/$fieldValue')" : "popBig('" . $modul . "Transaksi/viewReceipt/$jenisTr/$fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "btn_action" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "showModal('" . $modul . "OverDue_releaser/preview/$jenisTr/$fieldValues','Temporary unlock')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "btn btn-danger",
        ),
        "no" => array(
            // "style" => "color:#000000;text-align:center;",
            // "link" => "showModal('" . $modul . "Transaksi/viewResume/$jenisTr/$fieldValues','view resume for $fieldValue')",
            //            "link"  => "window.open('" . $modul . "Transaksi/viewJembreng/$jenisTr/$fieldValue')",
            "class" => "pull-right",
        ),
        "barcode" => array(
            "style" => "color:orange;text-align:left;font-weight:700;",
            "class" => "",
        ),
        "fulldate" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dtime_bank" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dateFaktur" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "extern_date2" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "oleh_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "extern_label2" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "kode" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "no_part" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "suppliers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "customers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),

        "produk_ord_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "produk_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "produk_kode " => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "jenis_label" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),

        "satuan" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "btn-block text-right",
        ),
        "state" => array(
            "style" => "",
            "class" => "btn-block text-center",
        ),
        "action" => array(
            "style" => "text-align:center;",
            "class" => "btn-block text-right",
        ),
        "transaksi_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_nilai" => array(
            "class" => "pull-right",
        ),
        "sub_diskon_supplier_nilai" => array(
            "class" => "pull-right",
        ),
        "ppn_nilai" => array(
            "class" => "pull-right",
        ),
        "transaksi_net" => array(
            "class" => "pull-right",
        ),
        "new_net1" => array(
            "class" => "pull-right",
        ),
        "disc" => array(
            "class" => "pull-right",
        ),
        "disc_valas" => array(
            "class" => "pull-right",
        ),
        "sub_harga_valas" => array(
            "class" => "pull-right",
        ),
        "ppn" => array(
            "class" => "pull-right",
        ),
        "extern_nilai3" => array(
            "class" => "pull-right",
        ),
        "exchange__harga" => array(
            "class" => "pull-right",
        ),
        "nilai_tambah_ppn_out" => array(
            "class" => "pull-right",
        ),
        "nett2" => array(
            "class" => "pull-right",
        ),
        "nett1" => array(
            "class" => "pull-right",
        ),
        "jual" => array(
            "class" => "pull-right",
        ),
        "jual_nppn" => array(
            "class" => "pull-right",
        ),
        "angka" => array(
            "class" => "pull-right",
        ),
        "grand_total" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hpp_nppn" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "harga" => array(
            "class" => "pull-right",
        ),
        "nett1nppn" => array(
            "class" => "pull-right",
        ),
        "sub_nett1" => array(
            "class" => "pull-right",
        ),
        "projectharga" => array(
            "class" => "pull-right",
        ),
        "projectppn" => array(
            "class" => "pull-right",
        ),
        "projectgrandtotal" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "harga_ori" => array(
            "class" => "pull-right",
        ),
        "disc_value" => array(
            "class" => "pull-right",
        ),
        "hpp" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jml" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jml_nilai" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_jmlx" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_harga" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "produk_ord_hrg" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "harga_perolehan" => array(
            "style" => "color:#005689;font-weight:bold;",
            "class" => "btn-block",
        ),
        "sisa_depre" => array(
            "style" => "color:#005609;font-weight:bold;",
            "class" => "btn-block",
        ),
        "saldo_sisa" => array(
            "style" => "color:#011689;font-weight:bold;",
            "class" => "btn-block",
        ),
        "harga_sisa" => array(
            "style" => "color:#011600;font-weight:bold;",
            "class" => "btn-block",
        ),
        "nett" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "ongkir" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "ongkir_tax" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "install" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "install_tax" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "subtotal" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hrg_hpp" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hrg_hpp" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hpp" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_hpp_nppn" => array(
            "style" => "color:#005689;font-weight:normal;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sub_harga" => array(
            "class" => "pull-right",
        ),
        "tagihan" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "grand_pembulatan" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "creditAmount" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "terbayar" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "sisa" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "a_sisa" => array(
            "style" => "color:#992200;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "a_saldo" => array(
            "style" => "color:#992200;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "diskon" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "add_disc" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_bayar" => array(
            "style" => "color:#009900;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_setor" => array(
            "style" => "color:#009900;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_entry" => array(
            "style" => "color:#000000;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "new_sisa" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "total" => array(
            "style" => "color:#005689;font-weight:bold;text-align:right;font-size:larger;",
            "class" => "btn-block text-right",
        ),
        "refValue" => array(
            "style" => "color:#008800;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "active" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "hold" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "debet_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_debet_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit_awal" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "kredit_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "qty_kredit_akhir" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "balance" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "nilai_be" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "nilai_be_spo" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_spo" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_cl" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ne_spo" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_spd" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_spo" => array(
            // "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //
        "unit_be_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_be_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_in_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_in_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_ot_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_ot_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_af_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "unit_af_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //
        "nilai_be_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_be_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_in_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_ot_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_debet" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_af_kredit" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),

        "textJumlah" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "text-bold text-uppercase pull-right",
        ),
        "uname" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "ipadd" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "devices" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "browser" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "discount" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        // "disc"   => array(
        //     "style" => "",
        //     "class" => "",
        // ),
        "dp" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "dp_value" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "dp_ppn_value" => array(
            "style" => "color:#992200;font-weight:bold;text-align:right;",
            "class" => "btn-block text-right",
        ),
        //"tagihan"    => array(
        //    "style" => "color:#992200;font-weight:bold;text-align:right;",
        //    "class" => "btn-block text-right",
        //),
        "stok" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "reference" => array(
            "style" => "",
            "class" => "btn-block text-left",
        ),
        //"ppn"        => array(
        //    "style" => "",
        //    "class" => "btn-block text-right",
        //),
        "value" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "value_in" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "value_out" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        "extern_nilai2" => array(
            "style" => "",
            "class" => "btn-block text-right",
        ),
        // "tlp"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_1"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_2"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "tlp_3"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-right",
        // ),
        // "phone"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-left",
        // ),
        // "handphone"  => array(
        //     "style" => "",
        //     "class" => "btn-block text-left",
        // ),
        "qty_opname" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "nilai_pembulatan" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "additionalfactor" => array(
            "style" => "color:#000000;text-align:right;",
            "class" => "btn-block text-right",
        ),
        "jenistr_reference" => array(
            "style" => "color:#000000;text-align:left;",
            "class" => "btn-block text-left",
        ),
        "saldo_qty_berjalan" => array(
            "class" => "pull-right",
        ),
        "saldo_berjalan" => array(
            "class" => "pull-right",
        ),
        "netto" => array(
            "class" => "pull-right",
        ),
        "ng_debet" => array(
            "class" => "pull-right",
        ),
        "ng_kredit" => array(
            "class" => "pull-right",
        ),
        "ng_qty_kredit" => array(
            "class" => "pull-right",
        ),
        "ng_qty_debet" => array(
            "class" => "pull-right",
        ),
        "total_qty_kredit" => array(
            "class" => "pull-right",
        ),
        "total_qty_debet" => array(
            "class" => "pull-right",
        ),
        "total_debet" => array(
            "class" => "pull-right",
        ),
        "total_kredit" => array(
            "class" => "pull-right",
        ),
        "harga_last" => array(
            "class" => "pull-right",
        ),
        "sub_harga_last" => array(
            "class" => "pull-right",
        ),
        "dp_dipakai_ui" => array(
            "class" => "pull-right",
        ),
        "dp_ppn_dipakai_ui" => array(
            "class" => "pull-right",
        ),
        "harga_last_purchase" => array(
            "class" => "pull-right",
        ),
        "number_value" => array(
            "class" => "pull-right",
        ),
        "number_value" => array(
            "class" => "pull-right",
        ),
        "sub_outstanding_items" => array(
            "class" => "pull-right",
        ),
        "amount" => array(
            "class" => "pull-right",
        ),
//        "harga_ori" => array(
//            "class" => "pull-right",
//        ),
//
        "nilai" => array(
            "class" => "pull-right",
        ),
        "sub_nilai" => array(
            "class" => "pull-right",
        ),
        "dpp_pph_persen" => array(
            "class" => "pull-right",
        ),
        "dpp_ppn_persen" => array(
            "class" => "pull-right",
        ),
        "dpppph" => array(
            "class" => "pull-right",
        ),
        "dppppn" => array(
            "class" => "pull-right",
        ),
        "nilai_supplies" => array(
            "class" => "pull-right",
        ),
        "sub_nilai_supplies" => array(
            "class" => "pull-right",
        ),
        "harga_bom" => array(
            "class" => "pull-right",
        ),
        "sub_harga_bom" => array(
            "class" => "pull-right",
        ),
        "harga_kompensasi" => array(
            "class" => "pull-right",
        ),
        "sub_harga_kompensasi" => array(
            "class" => "pull-right",
        ),
        "diskon_nilai" => array(
            "class" => "pull-right",
        ),
        "sub_diskon_supplier_nilai" => array(
            "class" => "pull-right",
        ),
        "sub_diskon_nilai" => array(
            "class" => "pull-right",
        ),
        "dpp_final" => array(
            "class" => "pull-right",
        ),
        "dpp_pengganti" => array(
            "class" => "pull-right",
        ),
        "ppn_final" => array(
            "class" => "pull-right",
        ),
        "list_items" => array(
            "class" => "",
        ),
        "total_diskon" => array(
            "class" => "pull-right",
        ),
        "ppn_out_bulat" => array(
            "class" => "pull-right",
        ),
        "grand_pembulatan" => array(
            "class" => "pull-right",
        ),
        "diskon_1_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_2_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_3_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_4_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_5_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_6_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_7_nilai" => array(
            "class" => "pull-right",
        ),
        "diskon_8_nilai" => array(
            "class" => "pull-right",
        ),
        "referensi_po_uangmuka" => array(
            "class" => "pull-right",
        ),
        "referensi_titipan_po_uangmuka" => array(
            "class" => "pull-right",
        ),
        "projectNilai" => array(
            "class" => "pull-right",
        ),
    );

    $formats = array(
        "nilai_entry" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "ppn_netto" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "grand_ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "extern_nilai3" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "nilai_tambah_ppn_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "sub_hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(round(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(round(0 + $fieldValue)),
        "ppn_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor(0 + $fieldValue)),
        "new_grand_ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(floor(0 + $fieldValue)) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(floor($fieldValue)),

        "lock" => $fieldValue == 1 ? "<span class='btn btn-xs btn-danger' title='BOM Project telah dikunci.'><i class='fa fa-lock'></i></span>" : "<span class='btn btn-xs btn-success' title='BOM Project belum di kunci, BOM masih bisa di Edit oleh pihak berwenang.'><i class='fa fa-unlock'></i></span>",
        "project_start" => $fieldValue == 1 ? "<span class='btn btn-xs btn-success' title='Project telah RUNNING,, Silahkan menambahkan TUGAS PROYEK.'><i class='fa fa-refresh fa-spin'></i> RUNNING </span>" : "<span class='btn btn-xs btn-danger' title='Menunggu Mulainya PROJECT, jika Quotation sudah di approve, silahkan untuk menombol Mulai Project.'><i class='fa fa-stop'></i></span>",

        "shipping_service" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett1_bulat" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_total_ui" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "projectharga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "projectppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "projectgrandtotal" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),

        "tagihan_ui" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "credit_note_dipakai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "uang_muka_dipakai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "pph_23" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "extern_nilai2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "pph23_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "payment_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_nett1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_nett2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "new_net3" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "review_details" => "<span class='fa fa-eye'></span>",
        "print_label" => "<span class='fa fa-print' style=\"font-size:20px\"></span>",
        "print_label_mod" => "<span class='fa fa-print' style=\"font-size:20px\"></span>",
        "print_barcode" => "<button type='button' class='btn btn-info btn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_barcode_pembelian" => "&nbsp;<button type='button' class='btn btn-info bbtn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_voucher" => "&nbsp;<button type='button' class='btn btn-info bbtn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print Voucher</span></button>",
        "print_barcode_pembelian_2" => "&nbsp;<button type='button' class='btn btn-primary bbtn-block hidden-xs hidden-sm'><span class='fa fa-barcode' style=\"font-size:1.2em\"> Print Barcode</span></button>",
        "print_barcode_return" => "&nbsp;<button type='button' class='btn btn-info bbtn-block hidden-xs hidden-sm'><span class='fa fa-qrcode' style=\"font-size:1.2em\"> Print QR</span></button>",
        "print_barcode_return_2" => "&nbsp;<button type='button' class='btn btn-primary bbtn-block hidden-xs hidden-sm'><span class='fa fa-barcode' style=\"font-size:1.2em\"> Print Barcode</span></button>",

        "nomer_top" => "$fieldValue",
        "nomer_top_new" => "$fieldValue",
        // "nomer_top"        => "<span class='fa fa-file-o' style='color:#55cc55;'></span> $fieldValue",
        "nomer" => "$fieldValue",
        "allow_project" => "$fieldValue*****",
        "nomer_new" => "$fieldValue",
        "nomer_top2" => "$fieldValue",
        "references_num" => "$fieldValue",
        "produk_kode" => "<span onclick=\"showImageSwal('$fieldValue','$tmpOut')\">$fieldValue\n</span>",
        "produk_kode_nolink" => "$fieldValue",
        "referencenomer" => "$fieldValue",
        "nomer_nolink" => "$fieldValue",
        "nomer_download" => "$fieldValue",
        // "nomer"            => "<span class='fa fa-file-text-o' style='color:#0056cd;'></span> $fieldValue",
        "refNum" => strlen($fieldValue) > 0 ? "<span class='fa fa-file-text-o' style='color:#ff7700;'></span> $fieldValue" : $fieldValue,
        //        "jenis"           => "<span class='fa fa-file-text-o'></span> ".$ci->config->item('heTransaksi_ui')[$fieldValue]['label'],
        // "transaksi_no" => "<span class='fa fa-file-text-o' style='color:#000000;'></span> $fieldValue",
        "transaksi_no" => "$fieldValue",
        "efakturSource" => "$fieldValue",
        "eFaktur" => "$fieldValue",
        "pairtransaksinomer_1" => "$fieldValue",
        "pairtransaksinomer_2" => "$fieldValue",
        "pairtransaksinomer_3" => "$fieldValue",
        // "date_time"          => formatTanggal($fieldValue, "d M Y H:i"),
        // "dtime"              => formatTanggal($fieldValue, "d F Y"),
        // "fulldate"           => formatTanggal($fieldValue, "d F Y"),
        // "shippingDate_value" => formatTanggal($fieldValue, "d F Y"),
        // "duedate_value"      => formatTanggal($fieldValue, "d F Y"),
        // "shippingdate_value" => formatTanggal($fieldValue, "d F Y"),
        // "viewR" => formatTanggal($fieldValue,"d F Y"),
        "date_time" => date("d M Y H:i", strtotime($fieldValue)),
        "auth_dtime" => date("d F Y ", strtotime($fieldValue)),
        "dtime" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        //        "dtime" => date("d F Y", strtotime($fieldValue)) . "<br><r><sub>".date("h:i:s", strtotime($fieldValue))."</sub></r>",
        // "fulldate" => date("d F Y", strtotime($fieldValue)),
        "dtime_bank" => isDate($fieldValue) ? date("d F Y", strtotime($fieldValue)) : $fieldValue,
        "fulldate" => isDate($fieldValue) ? date("d F Y", strtotime($fieldValue)) : $fieldValue,
        "fulldate_m" => date("d M Y", strtotime($fieldValue)),
        "dateFaktur" => (($fieldValue != NULL) || ($fieldValue != 0)) ? date("d M Y H:i", strtotime($fieldValue)) : "",
        "extern_date2" => ($fieldValue != "0000-00-00") ? date("d F Y", strtotime($fieldValue)) : "",
        "shippingDate_value" => date("d F Y", strtotime($fieldValue)),
        "duedate_value" => date("d F Y", strtotime($fieldValue)),
        "shippingdate_value" => date("d F Y", strtotime($fieldValue)),

        "oleh_nama" => "$fieldValue",
        "extern_label2" => "$fieldValue",
        //        "oleh_nama" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",
        //        "kode" => "<span class='glyphicon glyphicon-copyright-mark'></span> $fieldValue",
        "kode" => "$fieldValue",
        "no_part" => "<span class='glyphicon glyphicon-subtitles'></span> $fieldValue",
        "uname" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",
        //        "customers_nama" => "<span class='fa fa-users'></span> $fieldValue",
        "customers_nama" => "$fieldValue",
        "transaksi_jenis2" => "$fieldValue",
        "transaksi_jenis2_label" => "$fieldValue",
        "suppliers_nama" => "$fieldValue",
        "jenis_nama" => "<span class='glyphicon glyphicon-th-list'></span> $fieldValue",
        "produk_nama" => "<span > $fieldValue</span>",
        "hpp" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        // "harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "nett1nppn" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_nett1" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "harga_ori" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "disc_value" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_tambah_ppn_out" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "subtotal" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "hrg_hpp" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_hrg_hpp" => $fieldValue >= 0 ? ($fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue)) : "(" . number_format(-1 * $fieldValue) . ")",
        "sub_hpp" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "transaksi_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_total" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "hpp_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "total" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ongkir" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ongkir_tax" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "install" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "install_tax" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "tagihan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "grand_pembulatan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "creditAmount" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "terbayar" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "diskon" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "add_disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "a_sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "a_saldo" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_bayar" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nilai_setor" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
//        "nilai_entry" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "disc" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "refValue" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "berat" => number_format((0 + $fieldValue), 2),
        "premi_nilai" => number_format((0 + $fieldValue), 0, ",", "."),
        "pph" => number_format((0 + $fieldValue), 2, ",", "."),
//        "sisa"                    => number_format((0 + $fieldValue), 0,"",","),
        // "tlp_1"                  => number_format((0 + $fieldValue), 0, ",", "."),

        //        "hpp" => (str_replace(".0000000000","",$fieldValue)),
        //        "harga" => (str_replace(".0000000000","",$fieldValue)),
        //        "ppn" => (str_replace(".0000000000","",$fieldValue)),
        //        "nett" => (str_replace(".0000000000","",$fieldValue)),
        "new_sisa" => number_format(0 + $fieldValue),
        "harga_debet_awal" => number_format(0 + $fieldValue),
        "harga_debet" => number_format(0 + $fieldValue),
        "harga_kredit" => number_format(0 + $fieldValue),
        "harga_saldo" => number_format(0 + $fieldValue),
        "debet_awal" => number_format(0 + $fieldValue),
        "debet" => number_format(0 + $fieldValue),
        "active" => number_format(0 + $fieldValue),
        "hold" => number_format(0 + $fieldValue),
        "debet_akhir" => number_format(0 + $fieldValue),
        "akhir" => number_format(0 + $fieldValue),
        "avail" => number_format(0 + $fieldValue),
        "saldo" => is_numeric($fieldValue) ? number_format(0 + $fieldValue) : $fieldValue,

        "kredit_awal" => number_format(0 + $fieldValue),
        "kredit" => number_format(0 + $fieldValue),
        "nilai_kredit" => number_format(0 + $fieldValue),
        "kredit_akhir" => number_format(0 + $fieldValue),
        "balance" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",

        "qty_debet_awal" => number_format(0 + $fieldValue),
        "qty_debet" => number_format(0 + $fieldValue),
        "qty_debet_akhir" => number_format(0 + $fieldValue),

        "qty_kredit_awal" => number_format(0 + $fieldValue),
        "qty_kredit" => number_format(0 + $fieldValue),
        "qty_kredit_akhir" => number_format(0 + $fieldValue),

        "nilai_be_spo" => number_format(0 + $fieldValue),
        "nilai_ot_spo" => number_format(0 + $fieldValue),
        "nilai_ot_cl" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_ne_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_ot_spd" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        // "nilai_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",

        // "nilai_be"        => number_format(0 + $fieldValue),
        "nilai_be" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_in" => number_format(0 + $fieldValue),
        "nilai_ot" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "nilai_af" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        // "nilai_af"        => number_format(0 + $fieldValue),
        "disc_valas" => number_format(0 + $fieldValue, 2),
        "sub_harga_valas" => number_format(0 + $fieldValue, 2),
        "sub_nett1_valas" => number_format(0 + $fieldValue, 2),
        "disc_percent" => number_format(0 + $fieldValue, 2),
        //
        //        "unit_be_debet"  => number_format(0 + $fieldValue),
        //        "unit_be_kredit" => number_format(0 + $fieldValue),
        //        "unit_in_debet"  => number_format(0 + $fieldValue),
        //        "unit_in_kredit" => number_format(0 + $fieldValue),
        //        "unit_ot_debet"  => number_format(0 + $fieldValue),
        //        "unit_ot_kredit" => number_format(0 + $fieldValue),
        "unit_af_spo" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "unit_af" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "unit_af_debet" => number_format(0 + $fieldValue),
        "unit_af_kredit" => number_format(0 + $fieldValue),

        //        "nilai_be_debet"  => number_format(0 + $fieldValue),
        "kredit_limit" => number_format(0 + $fieldValue, 0, ",", "."),
        "nilai_in_debet" => number_format(0 + $fieldValue),
        "nilai_in_kredit" => number_format(0 + $fieldValue),
        "nilai_ot_debet" => number_format(0 + $fieldValue),
        "nilai_ot_kredit" => number_format(0 + $fieldValue),
        "nilai_af_debet" => number_format(0 + $fieldValue),
        "nilai_af_kredit" => number_format(0 + $fieldValue),
        // "nilai_kredit" => number_format(0 + $fieldValue),
        "stok_aktif" => $fieldValue < 0 ? "<span class='text-red'>(" . ($fieldValue * -1) . ")</span>" : "<span class='font-size-1-2 tebal'>" . number_format(0 + $fieldValue) . "</span>",

        "produk_ord_harga" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "produk_ord_hrg" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga_perolehan" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sisa_depre" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 0, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "saldo_sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 0, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harga_sisa" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : (($fieldValue * 1) > 0 ? number_format(0 + $fieldValue) : $fieldValue),
        "produk_ord_jmlx" => number_format(0 + $fieldValue),
        "jual" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "jual_nppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "harganppn" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),

        "new_net1" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "diskon_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "sub_diskon_supplier_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "ppn_nilai" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "transaksi_net" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "nett2" => $fkali > 0 ? "<span data-toggle='tooltip'data-placement='top'data-html='true'data-original-title='nilai ori: " . number_format(0 + $fieldValue) . "<br>valas: " . $fkali . "'><b>$sym</b>" . number_format((0 + $fieldValue) / $fkali, 2, ",", ".") . "</span>" : number_format(0 + $fieldValue),
        "state" => $fieldValue,
        "ipadd" => ($fieldValue),
        "devices" => "<span class='fa fa-tablet'></span> " . heReturnDeviceName($fieldValue)['device'] . "/" . heReturnDeviceName($fieldValue)['browser'],
        "browser" => "<span class='fa fa-tablet'></span> " . heReturnDeviceName($fieldValue)['device'] . "/" . heReturnDeviceName($fieldValue)['browser'],
        //        "devices"  => "<span class='fa fa-tablet'></span> " . $fieldValue,
        "discount" => number_format(0 + $fieldValue),
        "dp" => number_format(0 + $fieldValue),
        "dp_value" => number_format(0 + $fieldValue),
        "dp_ppn_value" => number_format(0 + $fieldValue),
        //        "tagihan"  => number_format(0 + $fieldValue),
        "curency" => "Rp. " . number_format(0 + $fieldValue),
//        "barcode" => "--",
        "barcode" => $fieldValue,
        "npwp" => $fieldValue,
        "no_ktp" => $fieldValue,
        "nik" => $fieldValue,
        "tlp" => $fieldValue,
        "tlp_1" => $fieldValue,
        "tlp_2" => $fieldValue,
        "tlp_3" => $fieldValue,
        "phone" => $fieldValue,
        "handphone" => $fieldValue,
        "customerdetails_tlp_1" => $fieldValue,
        "customerdetails_tlp_2" => $fieldValue,
        //        "ppn"      => $fieldValue,

        "no" => number_format(0 + $fieldValue),
        "value" => number_format(0 + $fieldValue),
        "value_in" => number_format(0 + $fieldValue),
        "value_out" => number_format(0 + $fieldValue),
        "angka" => number_format(0 + $fieldValue, 2),
        "disc_persent" => number_format(0 + $fieldValue, 2),
        "customerDetails__npwp" => "$fieldValue",
        "customerDetails__tlp_1" => "$fieldValue",
        "customerDetails__tlp_2" => "$fieldValue",
        "btn_action" => "<span class='btn btn-xs fa fa-calendar' style=\"font-size:15px\"> review</span>",

        "berat_gross" => number_format(($fieldValue * 1) / 1000) . "",
        "sub_berat_gross" => number_format(($fieldValue * 1) / 1000) . "",
        "volume_gross" => number_format(($fieldValue * 1) / 1000000000, 2) . "",
        "sub_volume_gross" => number_format(($fieldValue * 1) / 1000000000, 2) . "",
//        "stok" => number_format(0 + $fieldValue),
        "stok" => ($fieldValue < 0) ? "(" . number_format($fieldValue * -1) . ")" : number_format(0 + $fieldValue),
        "qty_opname" => number_format(0 + $fieldValue),
        "sub_outstanding_items" => number_format(0 + $fieldValue),
        "exchange__harga" => number_format(0 + $fieldValue),
        "nilai_pembulatan" => "$fieldValue",
        "additionalfactor" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "produk_kode " => $fieldValue,
        "jenistr_reference " => $fieldValue,
        "saldo_qty_berjalan" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "saldo_berjalan" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "netto" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_qty_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "ng_qty_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_qty_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_qty_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_debet" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "total_kredit" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "harga_last" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "sub_harga_last" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dp_dipakai_ui" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dp_ppn_dipakai_ui" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "harga_last_purchase" => $fieldValue >= 0 ? (number_format(0 + $fieldValue)) : "(" . (number_format(-1 * $fieldValue)) . ")",
        "dpp_dp" => $fieldValue >= 0 ? (number_format(ceil($fieldValue))) : "(" . (number_format(-1 * ceil($fieldValue))) . ")",
        "ppn_dp" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "number_value" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        // "total_ui" => $fieldValue >= 0 ? (number_format(ceil($fieldValue))) : "(" . (number_format(-1 * ceil($fieldValue))) . ")",
        "total_ui" => $fieldValue >= 0 ? (number_format(round($fieldValue))) : "(" . (number_format(-1 * round($fieldValue))) . ")",
        "new_grand_ppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_outstanding_items" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "unit" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "jml_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "amount" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
//        "harga_ori" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_ppn_persen" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_pph_persen" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dppppn" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpppph" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "nilai_supplies" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_nilai_supplies" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "harga_bom" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_harga_bom" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "harga_kompensasi" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_harga_kompensasi" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_diskon_supplier_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "sub_diskon_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
//        "note" => nl2br($fieldValue),
//        "description" => nl2br($fieldValue),
        "spek" => nl2br($fieldValue),
        "note" => ($fieldValue),
        "list_items" => ($fieldValue),
        "description" => ($fieldValue),
        "reference" => ($fieldValue),
        "dpp_final" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "dpp_pengganti" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "ppn_final" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "total_diskon" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "ppn_out_bulat" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "grand_pembulatan" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_1_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_2_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_3_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_4_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_5_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_6_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_7_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "diskon_8_nilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "referensi_po_uangmuka" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "referensi_titipan_po_uangmuka" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
        "projectNilai" => $fieldValue >= 0 ? (number_format(floor($fieldValue))) : "(" . (number_format(-1 * floor($fieldValue))) . ")",
    );

    // cekHijau("$fieldName  $fieldValue");
    /*====================================
     * format pengecualian untuk print
     * ====================================*/
    $cleans = array(
        "exp",
        "viewReceipt",
        "jurnal",
    );
    if (in_array($segment_2, $cleans)) {
        $formatsReceipt = array(
            "nomer" => "$fieldValue",
            "nomer_new" => "$fieldValue",
            "nomer_nolink" => "$fieldValue",
            "nomer_download" => "$fieldValue",
            "transaksi_no" => "$fieldValue",
            "produk_kode" => "$fieldValue",
        );

        if (key_exists($fieldName, $formatsReceipt)) {
            $formats = $formatsReceipt;
        }

        // return $fieldValues;
        // matiHere($fieldValuesOri . "*/*/" );
    }

    /* =================================================================
     * format dengan pola tertentu (custom)
     * =================================================================*/
    switch ($fieldName) {

        case "npwp":
        case "nomer_npwp":
        case "customerDetails__npwp":
        case "customerDetails_npwp":
            // cekMerah("$fieldName");
            $clean_0 = str_replace(".", "", $fieldValues);
            $clean_1 = str_replace("-", "", $clean_0);
            $clean_2 = str_replace(" ", "", $clean_1);
            $clean_10 = str_replace("_", "", $clean_2);
            $jmlDigit = strlen($clean_10);
            if ($jmlDigit == 15) {
                // cekLime("$clean_10");
                $splits = str_split($clean_10, 1);

                $newFormat = $splits[0] . $splits[1] . "." . $splits[2] . $splits[3] . $splits[4] . "." . $splits[5] . $splits[6] . $splits[7] . "." . $splits[8] . "-" . $splits[9] . $splits[10] . $splits[11] . "." . $splits[12] . $splits[13] . $splits[14];
                // cekHitam($newFormat);
                $formats[$fieldName] = $newFormat;
                // $formats["customerDetails_npwp"] = $newFormat;
            }
            else {

                $formats[$fieldName] = $jmlDigit > 0 ? "<i class='text-red' title='$jmlDigit digit' data-toggle='tooltip'> $fieldValues</i>" : "";
            }
            // arrPrint($formats);
            break;
        case "angka":
        case "debet":
        case "debet_awal":
        case "debet_akhir":
        case "kredit":
        case "kredit_awal":
        case "kredit_akhir":
            if (is_numeric($fieldValue)) {
                if ($fieldValue < 0) {
                    $formats[$fieldName] = "(" . number_format($fieldValue * -1) . ")";
                }
            }
            break;
        default:
            break;

        case "nama":
            if (is_numeric($fieldValue)) {
                $splits = str_split($fieldValue, 4);
                $fieldValue_f = implode("-", $splits);
                $formats[$fieldName] = $fieldValue_f;
                // arrPrint($splits);
                // cekMerah();
            }
            break;
        // case "nomer_top":
        // case "nomer":
        //     if ($segment_2 == "viewReceipt") {
        //         $exp = explode("-", $fieldValue);
        //         $tail = end($exp);
        //         // cekLime($tail);
        //         $tail_f = "<b class='font-size-1-2'>$tail</b>";
        //         $newFormat = str_replace($tail, $tail_f, $fieldValue);
        //         if (sizeof($exp) > 0) {
        //             $formats[$fieldName] = "$newFormat";
        //         }
        //         else {
        //             $formats[$fieldName] = "$newFormat";
        //
        //         }
        //         // cekHijau($tail_f . " " . $newFormat);
        //     }
        //
        //     break;
        case "tlp":
        case "tlp_2":
        case "tlp_3":
        case "phone":
        case "handphone":
        case "tlp_1":
            $splits = str_split($fieldValues, 4);
            // arrPrint($splits);
            if (sizeof($splits) > 1) {
                $newFormat = implode("-", $splits);
            }
            else {
                $newFormat = $fieldValues;
            }
            $formats[$fieldName] = $newFormat;
            break;

        case "premi":
        case "diskon":
            //        case "ppn":
        case "hpp":
        case "pph":
            // cekLime($fieldValues);
            $expl = explode(".", $fieldValues);
            // arrPrint($expl);
            if (isset($expl[1]) && $expl[1] > 0) {
                $newFormat = number_format($fieldValues, 2);
            }
            else {
                $newFormat = number_format($fieldValues, 0);
            }


            $formats[$fieldName] = $newFormat;
            break;
    }

    if (array_key_exists($fieldName, $fieldRules)) {

        if (isset($fieldRules[$fieldName]['link']) && strlen($fieldRules[$fieldName]['link']) > 1) {
            $link = $fieldRules[$fieldName]['link'];
        }
        else {
            $link = "";
        }

        if (strlen($link) > 1) {
            $str['link'] = $link;
            $str['href'] = "'" . $modul . "ViewDetails/nomer/$jenisTr/$fieldValues'";
            $str['modul'] = $modul;
            $str['style'] = $fieldRules[$fieldName]['style'];
            $str['class'] = $fieldRules[$fieldName]['class'];
            $str['value'] = isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
        }
        else {
            $stle = isset($fieldRules[$fieldName]['style']) ? "style='" . $fieldRules[$fieldName]['style'] . "'" : "";
            $clss = isset($fieldRules[$fieldName]['class']) ? "class='" . $fieldRules[$fieldName]['class'] . "'" : "";
            $id = isset($fieldRules[$fieldName]['id']) ? "id='" . $fieldRules[$fieldName]['id'] . "'" : "";
            $str = array();
            //--- TIDAK PAKAI SPAN dan EMBEL2
            switch ($fieldName) {
                case "nomer_download":
                    $str['value'] = isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
                    break;
                default:
                    $str['id'] = $id;
                    $str['style'] = $stle;
                    $str['class'] = $clss;
                    $str['value'] = isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
                    break;
            }
        }

        return $str;
    }
    else {
        if (array_key_exists($fieldName, $formats)) {
            $str = $formats[$fieldName];
        }
        else {
            if (is_numeric($fieldValue)) {
                $fieldValues = $fieldValue < 0 ? $fieldValue * -1 : $fieldValue;
                $isdesimal = preg_match('/^\d+\.\d+$/', $fieldValues);
                if ($isdesimal == 1) {
                    $str = number_format($fieldValue + 0, 2);
                }
                else {
                    $str = number_format($fieldValue + 0);
                }
            }
            else {
                $str = $fieldValue;
            }
        }
        return $str;
    }

}

function formatGlanceField($fieldName, $fieldValue)
{
    $fieldRules = array(
        "nomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "window.open('" . base_url() . "Transaksi/viewReceipt/$fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "nomer_new" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "window.open('" . base_url() . "Transaksi/viewReceipt/$fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "transaksi_no" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "window.open('" . base_url() . "Transaksi/viewReceipt/$fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "dtime" => array(
            "style" => "text-align:center;font-size:12px;",
            "class" => "text-muted",
        ),
        "fulldate" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dtime_bank" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "oleh_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "suppliers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "customers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),

        "produk_ord_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "produk_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "jenis_label" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),


        "transaksi_nilai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:center;",
            "class" => "btn-block text-center",
        ),


    );
    $formats = array(

        "nomer" => "<span class='fa fa-file-text-o'></span> $fieldValue",
        "nomer_new" => "<span class='fa fa-file-text-o'></span> $fieldValue",
        "transaksi_no" => "<span class='fa fa-file-text-o'></span> $fieldValue",
        "dtime" => date("M/d H:i", strtotime($fieldValue)),
        "fulldate" => date("M/d Y", strtotime($fieldValue)),
        "dtime_bank" => date("M/d Y", strtotime($fieldValue)),
        "oleh_nama" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",

        "customers_nama" => "<span class='fa fa-users'></span> $fieldValue",
        "suppliers_nama" => "<span class='fa fa-users'></span> $fieldValue",
        "jenis_nama" => "<span class='glyphicon glyphicon-th-list'></span> $fieldValue",
        "produk_nama" => "<span style='font-family: Consolas, Monaco, Courier New, Courier, monospace;'> $fieldValue</span>",

        "transaksi_nilai" => number_format(0 + $fieldValue),

    );
    if (array_key_exists($fieldName, $fieldRules)) {
        if (isset($fieldRules[$fieldName]['link']) && strlen($fieldRules[$fieldName]['link']) > 1) {
            $link = $fieldRules[$fieldName]['link'];
        }
        else {
            $link = "#";
        }

        //        $str = "<a href=# onClick=\"$link\">";
        $str = "<span style='" . $fieldRules[$fieldName]['style'] . "' class='" . $fieldRules[$fieldName]['class'] . "'>";
        $str .= isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
        $str .= "</span>";

        //        $str .= "</a>";

        return $str;
        //        return $fieldValue;
    }
    else {
        if (array_key_exists($fieldName, $formats)) {
            $str = $formats[$fieldName];
        }
        else {
            if (is_numeric($fieldValue)) {
                $isdesimal = preg_match('/^\d+\.\d+$/', $fieldValue);
                if ($isdesimal == 1) {

                    $str = number_format($fieldValue + 0, 2);
                }
                else {

                    $str = number_format($fieldValue + 0);
                }
            }
            else {
                $str = $fieldValue;
            }

            // $str = number_format($fieldValue + 0,);
        }

        return $str;
    }

}

function formatGlanceField_he_format($fieldName, $fieldValue, $jenis = "", $modul_path = "")
{
    $jenisTr = strlen($jenis) > 2 ? $jenis : url_segment(4);
    $modul = strlen($modul_path) > 2 ? $modul_path : MODUL_PATH;

    $fieldRules = array(
        "nomer" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "window.open('" . $modul . "Transaksi/viewReceipt/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "nomer_new" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "window.open('" . $modul . "Transaksi/viewReceipt/$jenisTr/$fieldValue')",
            "class" => "",
        ),
        "transaksi_no" => array(
            "style" => "color:#000000;text-align:center;",
            "link" => "window.open('" . $modul . "Transaksi/viewReceipt/$jenisTr/$fieldValue')",
            //            "link"  => "window.open('" . base_url() . "Transaksi/viewJembreng/$fieldValue')",
            "class" => "",
        ),
        "dtime" => array(
            "style" => "text-align:center;font-size:12px;",
            "class" => "text-muted",
        ),
        "fulldate" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "dtime_bank" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "oleh_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "suppliers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),
        "customers_nama" => array(
            "style" => "color:#565656;text-align:center;",
            "class" => "",
        ),

        "produk_ord_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "produk_nama" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),
        "jenis_label" => array(
            "style" => "color:#565656;text-align:left;",
            "class" => "",
        ),


        "transaksi_nilai" => array(
            "style" => "color:#005689;font-weight:bold;text-align:center;",
            "class" => "btn-block text-center",
        ),


    );
    $formats = array(

        "nomer" => "<span class='fa fa-file-text-o'></span> $fieldValue",
        "nomer_new" => "<span class='fa fa-file-text-o'></span> $fieldValue",
        "transaksi_no" => "<span class='fa fa-file-text-o'></span> $fieldValue",
        "dtime" => date("M/d H:i", strtotime($fieldValue)),
        "fulldate" => date("M/d Y", strtotime($fieldValue)),
        "dtime_bank" => date("M/d Y", strtotime($fieldValue)),
        "oleh_nama" => "<span class='glyphicon glyphicon-user'></span> $fieldValue",

        "customers_nama" => "<span class='fa fa-users'></span> $fieldValue",
        "suppliers_nama" => "<span class='fa fa-users'></span> $fieldValue",
        "jenis_nama" => "<span class='glyphicon glyphicon-th-list'></span> $fieldValue",
        "produk_nama" => "<span style='font-family: Consolas, Monaco, Courier New, Courier, monospace;'> $fieldValue</span>",

        "transaksi_nilai" => number_format(0 + $fieldValue),

    );
    if (array_key_exists($fieldName, $fieldRules)) {
        if (isset($fieldRules[$fieldName]['link']) && strlen($fieldRules[$fieldName]['link']) > 1) {
            $link = $fieldRules[$fieldName]['link'];
        }
        else {
            $link = "#";
        }

        //        $str = "<a href=# onClick=\"$link\">";
        $str = "<span style='" . $fieldRules[$fieldName]['style'] . "' class='" . $fieldRules[$fieldName]['class'] . "'>";
        $str .= isset($formats[$fieldName]) ? $formats[$fieldName] : $fieldValue;
        $str .= "</span>";

        //        $str .= "</a>";

        return $str;
        //        return $fieldValue;
    }
    else {
        if (array_key_exists($fieldName, $formats)) {
            $str = $formats[$fieldName];
        }
        else {
            if (is_numeric($fieldValue)) {
                $isdesimal = preg_match('/^\d+\.\d+$/', $fieldValue);
                if ($isdesimal == 1) {

                    $str = number_format($fieldValue + 0, 2);
                }
                else {

                    $str = number_format($fieldValue + 0);
                }
            }
            else {
                $str = $fieldValue;
            }

            // $str = number_format($fieldValue + 0,);
        }

        return $str;
    }

}

function lgSimpleTime($tm)
{
    $awal = date_create($tm);
    $akhir = date_create(); // waktu sekarang
    $diff = date_diff($awal, $akhir);
    $strRslt = "";
    if ($diff->y > 0) {
        $strRslt .= $diff->y . " tahun lalu";
    }
    else {
        if ($diff->m > 0) {
            $strRslt .= $diff->m . " bulan lalu";
        }
        else {
            if ($diff->d > 0) {
                $strRslt .= $diff->d . " hari lalu";
            }
            else {
                if ($diff->h > 0) {
                    $strRslt .= $diff->h . " jam lalu";
                }
                else {
                    if ($diff->i > 0) {
                        $strRslt .= $diff->i . " menit lalu";
                    }
                    else {
                        $strRslt .= $diff->s . " detik lalu";
                    }
                }
            }
        }
    }

    return $strRslt;
}

function lgTranslateTime($tm)
{

    if (strlen($tm) > 12) {
        $str = date("M/d Y - H:i", strtotime($tm));
    }
    else {
        if (strlen($tm) > 9) {
            $str = date("M/d Y", strtotime($tm));
        }
        else {
            if (strlen($tm) > 6) {
                $str = date("M Y", strtotime($tm));
            }
            else {
                if (strlen($tm) > 3) {
                    $str = date("Y", strtotime($tm));
                }
                else {
                    $str = "-n/a-";
                }
            }
        }
    }

    return $str;
}

function lgTranslateTime2($tm)
{

    if (strlen($tm) > 12) {
        $str = date("M/d Y - H:i", strtotime($tm));
    }
    else {
        if (strlen($tm) > 9) {
            $str = date("M/d Y", strtotime($tm));
        }
        else {
            if (strlen($tm) > 6) {
                //                $str = date("M Y", strtotime($tm));
                $str_first = date('01 M Y', strtotime($tm));
                $str_last = date('t M Y', strtotime($tm));
                $str = "$str_first - $str_last";
            }
            elseif (strlen($tm) > 3) {
                $tgl = "$tm-01-01";
                $str_first = date('01 M Y', strtotime($tgl));
                //                $str_first = dtimeNow("Y") . "-01-01";
                $str_last = date('t M Y', strtotime($tm));
                $str = "$str_first - $str_last";
            }
            else {
                $str = "-n/a-";
            }
        }
    }

    return $str;
}

function lgTranslateTime3($tm)
{

    if (strlen($tm) > 12) {
        $str = date("M/d Y - H:i", strtotime($tm));
    }
    else {
        if (strlen($tm) > 9) {
            $str = date("M/d Y", strtotime($tm));
        }
        else {
            if (strlen($tm) > 6) {
                $str_first = date('01 M Y', strtotime($tm));
                $str_last = date('t M Y', strtotime($tm));
                $str = "$str_first - $str_last";
            }
            elseif (strlen($tm) > 3) {
                $tgl = "$tm-01-01";
                $tgl2 = "$tm-12-31";
                $str_first = date('01 M Y', strtotime($tgl));
                $str_last = date('t M Y', strtotime($tgl2));
                $str = "$str_first - $str_last";
            }
            else {
                $str = "-n/a-";
            }
        }
    }

    return $str;
}

function lgTranslateTime4($tm)
{

    if (strlen($tm) > 12) {
        $str = date("M/d Y - H:i", strtotime($tm));
    }
    else {
        if (strlen($tm) > 9) {
            $str = date("d M Y", strtotime($tm));
        }
        else {
            if (strlen($tm) > 6) {
                //                $str = date("M Y", strtotime($tm));
                $str_first = date('01 M Y', strtotime($tm));
                $str_last = date('t M Y', strtotime($tm));
                $str = "$str_first - $str_last";
            }
            elseif (strlen($tm) > 3) {
                $tgl = "$tm-01-01";
                $str_first = date('01 M Y', strtotime($tgl));
                //                $str_first = dtimeNow("Y") . "-01-01";
                $str_last = date('t M Y', strtotime($tm));
                $str = "$str_first - $str_last";
            }
            else {
                $str = "-n/a-";
            }
        }
    }

    return $str;
}

function lgTranslateTimeFirstMonth($tm)
{

    if (strlen($tm) > 12) {
        $str = date("M/d Y - H:i", strtotime($tm));
    }
    else {
        if (strlen($tm) > 9) {
            $str = date("M/d Y", strtotime($tm));
        }
        else {
            if (strlen($tm) > 6) {
                $str_first = "01 Jan " . date('Y', strtotime($tm));
                $str_last = date('t M Y', strtotime($tm));
                $str = "$str_first - $str_last";
            }
            elseif (strlen($tm) > 3) {
                $tgl = "$tm-01-01";
                $str_first = "01 Jan " . date('Y', strtotime($tgl));
                $str_last = date('t M Y', strtotime($tm));
                $str = "$str_first - $str_last";
            }
            else {
                $str = "-n/a-";
            }
        }
    }

    return $str;
}

function createObjectSuffix($str)
{
    $spcSuffs = array("s", "o", "x", "z");
    $spcSuffs2 = array("ch", "sh");
    $spcSuffs3 = array("y"); //==convert y to i
    $car1 = substr($str, strlen($str) - 1);
    $car2 = substr($str, strlen($str) - 2);
    if (in_array($car1, $spcSuffs3)) {
        return "es";
    }
    else {
        if (in_array($car1, $spcSuffs) || in_array($car2, $spcSuffs2)) {
            return "es";
        }
        else {
            return "s";
        }
    }

}

function lgCountMinutes($tm)
{
    $awal = date_create($tm);
    $akhir = date_create(); // waktu sekarang
    $diff = date_diff($awal, $akhir);


    return $diff->i;
}


function heTrimAvoidedChars($char)
{
    // arrPrintPink($char);
    /* -----------------------------
     * memuang karakter spt di array ini
     * -----------------------------*/
    $avoidedChars = array(
        "\"",
        "'",
        "=",
        "",
    );
    $cChar = $char;
    foreach ($avoidedChars as $c) {
        $cChar = str_replace("$c", "", $cChar);
    }

    return $cChar;
}

function heSimpleTime($dateStr)
{

    if (date("M/d Y", strtotime($dateStr)) == date("M/d Y")) {
        $result = date("H:i", strtotime($dateStr));
    }
    else {
        $result = date("M/d H:i", strtotime($dateStr));
    }

    return $result;
}

//tambahan formater nomer transaksi
function arrConfName()
{

    $arrInitial = array(
        "460re" => "PRE.PO.FG.IMP.EDIT",      // Purchasing Order Import
        "460rrj" => "PRE.PO.FG.IMP.RJ",      // Purchasing Order Import
        "460arj" => "PO.FG.IMP.RJ",     // Purchasing Order Authorisation Import
        "460r" => "PRE.PO.FG.IMP",      // Purchasing Order Import
        "460a" => "PO.FG.IMP",     // Purchasing Order Authorisation Import
        "460" => "GRN.IMP",     // Goods Receivemen Note Import

        "466re" => "PRE.PO.FG.EDIT",      // Purchasing Order
        "466rrj" => "PRE.PO.FG.RJ",      // Purchasing Order
        "466rj" => "PO.FG.RJ",     // Purchasing Order Authorisation
        "466r" => "PRE.PO.FG",      // Purchasing Order
        "466" => "PO.FG",     // Purchasing Order Authorisation
        "467r" => "PRE.GRN",     // Pre Goods Receivemen Note
        "467" => "GRN",     // Goods Receivemen Note

        "1466re" => "PRE.PO.FG.PJ.EDIT",      // Purchasing Order
        "1466rrj" => "PRE.PO.FG.PJ.RJ",      // Purchasing Order
        "1466rj" => "PO.FG.PJ.RJ",     // Purchasing Order Authorisation
        "1466r" => "PRE.PO.FG.PJ",      // Purchasing Order
        "1466" => "PO.FG.PJ",     // Purchasing Order Authorisation
        "1467r" => "PRE.GRN.PJ",     // Pre Goods Receivemen Note
        "1467" => "GRN.PJ",     // Goods Receivemen Note


        "461roe" => "PRE.PO.SP.EDIT",    // PO Supplies
        "461rorj" => "PRE.PO.SP.RJ",    // PO Supplies
        "461rrj" => "PO.SP.RJ",   // PO Authorisation Supplies
        "461ro" => "PRE.PO.SP",    // PO Supplies
        "461r" => "PO.SP",   // PO Authorisation Supplies
        // "461" => "RSP",       // Receivement Supplies
        "461" => "SRN",       // Receivement Supplies

        "421r" => "PRE.PO.AS",      // Purchasing Order aset
        "421" => "PO.AS",     // Purchasing Order Authorisation aset
        "423" => "RAS",     //  Receivement  aset
        "422r" => "Re.AS", //req penambahanan aset
        "422o" => "EDA",//entry detail aset
        "422" => "RES",//penerimaan aset

        "3463roe" => "PRE.PO.PJ.EDIT",   // PO Service Projek
        "3463rorj" => "PRE.PO.PJ.RJ",   // PO Service Projek
        "3463orj" => "PO.PJ.RJ",  // PO Authorisation Service Projek
        "3463ro" => "PRE.PO.PJ",   // PO Service Projek
        "3463o" => "PO.PJ",  // PO Authorisation Service Projek
        "3463" => "SRN.PJ",  // PO Knowledge Service Projek
        "3113" => "Re.PM.PJ",//realisasi ppn masukan Projek
        "3461r" => "RD.PJ",
        "3461" => "RDA.PJ",
        "3465r" => "SI.PJ",
        "3465" => "RS.PJ",

        "761roe" => "PR.EDIT",      // Purchasing Request
        "761rorj" => "PR.RJ",      // Purchasing Request
        "761rrj" => "PRA.RJ",     // Purchasing Request Authorisation
        "761ro" => "PR",      // Purchasing Request
        "761r" => "PRA",     // Purchasing Request Authorisation
        "761ros" => "",
        "761" => "RF",

        "763re" => "NSR.EDIT",
        "763rrj" => "NSR.RJ",
        "763rj" => "ASR.RJ",
        "763r" => "NSR",
        "763" => "ASR",

        "1763re" => "sNSR.EDIT",
        "1763rrj" => "sNSR.RJ",
        "1763rj" => "sASR.RJ",
        "1763r" => "sNSR",
        "1763" => "sASR",

        "463roe" => "PRE.PO.SE.EDIT",   // PO Service
        "463rorj" => "PRE.PO.SE.RJ",   // PO Service
        "463orj" => "PO.SE.RJ",  // PO Authorisation Service
        "463ro" => "PRE.PO.SE",   // PO Service
        "463o" => "PO.SE",  // PO Authorisation Service
        "463" => "SRN.SE",  // PO Knowledge Service

        "1463re" => "PRE.PO.SE.C.EDIT",   // PO Service
        "1463rrj" => "PRE.PO.SE.C.RJ",   // PO Service
        "1463orj" => "PO.SE.C.RJ",  // PO Authorisation Service
        "1463r" => "PRE.PO.SE.C",   // PO Service
        "1463o" => "PO.SE.C",  // PO Authorisation Service
        "1463" => "SRN.SE.C",  // PO Knowledge Service

        "1587re" => "RDe.C.EDIT",//destok to other ware houuse center
        "1587rrj" => "RDe.C.RJ",//destok to other ware houuse center
        "1587rarj" => "DeA.C.RJ",//destok Authorisation
        "1587r" => "RDe.C",//destok to other ware houuse center
        "1587ra" => "DeA.C",//destok Authorisation
        "1587" => "DeR.C",//receipt desetock


        "583re" => "RD.EDIT",      // Request Distribusi
        "583rrj" => "RD.RJ",      // Request Distribusi
        "583r" => "RD",      // Request Distribusi
        "583rs" => "PRE.RDA",      // Request Distribusi
        "583" => "RDA",     // Request Distribusi Authorisation

        "3583re" => "RmD.EDIT",      // Request Distribusi
        "3583rrj" => "RmD.RJ",      // Request Distribusi
        "3583r" => "RmD",      // Request Distribusi
        "3583" => "RmDA",     // Request Distribusi Authorisation

        "585re" => "SI.EDIT",      // Stock Initiation
        "585rrj" => "SI.RJ",      // Stock Initiation
        "585r" => "SI",      // Stock Initiation
        "585rs" => "PRE.RcS",      // Stock Initiation
        "585" => "RcS",      // Stock Receivment

        "3585re" => "SIm.EDIT",      // Stock Initiation raw material
        "3585rrj" => "SIm.RJ",      // Stock Initiation raw material
        "3585r" => "SIm",      // Stock Initiation raw material
        "3585" => "RcSm",      // Stock Receivment raw material

        "675re" => "RBUm.B.EDIT", //request expense/biaya umum cabang
        "675rrj" => "RBUm.B.RJ", //request expense/biaya umum cabang
        "675r" => "RBUm.B", //request expense/biaya umum cabang
        "675" => "ABUm.B", //request expense/biaya umum cabang

        "2675re" => "RBUm.C.EDIT", //aproval expense/biaya umum center
        "2675rrj" => "RBUm.C.RJ", //aproval expense/biaya umum center
        "2675r" => "RBUm.C", //aproval expense/biaya umum center
        "2675" => "ABUm.C", //aproval expense/biaya umum center

        "676re" => "RBP.B.EDIT", //request expense/biaya produksi cabang
        "676rrj" => "RBP.B.RJ", //request expense/biaya produksi cabang
        "676r" => "RBP.B", //request expense/biaya produksi cabang
        "676" => "ABP.B", //request expense/biaya produksi cabang

        "2676re" => "RBP.C.EDIT", // approval expense/biaya produksi pusat
        "2676rrj" => "RBP.C.RJ", // approval expense/biaya produksi pusat
        "2676r" => "RBP.C", // approval expense/biaya produksi pusat
        "2676" => "ABP.C", // approval expense/biaya produksi pusat

        "677re" => "RBU.B.EDIT", //request expense/biaya usaha cabang
        "677rrj" => "RBU.B.RJ", //request expense/biaya usaha cabang
        "677r" => "RBU.B", //request expense/biaya usaha cabang
        "677" => "ABU.B", //request expense/biaya usaha cabang

        "2677re" => "RBU.C.EDIT", //approval expense/biaya usaha pusat
        "2677rrj" => "RBU.C.RJ", //approval expense/biaya usaha pusat
        "2677r" => "RBU.C", //approval expense/biaya usaha pusat
        "2677" => "ABU.C", //approval expense/biaya usaha pusat

        "477" => "PM.BU", //pembayaran expense/biaya usaha pusat
        "476" => "PM.BP", //pembayaran expense/biaya produksi pusat
        "475" => "PM.BUm", //pembayaran expense/biaya umum pusat


        "983re" => "ReD.EDIT",     // Return Distribusi
        "983rrj" => "ReD.RJ",     // Return Distribusi
        "983r" => "ReD",     // Return Distribusi
        "983rs" => "PRE.ReDA",     // Return Distribusi
        "983" => "ReDA",    // Return Distribusi Authorisation

        "985re" => "RcSI.EDIT",    //
        "985rrj" => "RcSI.RJ",    //
        "985r" => "RcSI",    //
        "985rs" => "PRE.RcSIA",    //
        "985" => "RcSIA",

        "1983re" => "ReDP.EDIT",   // Return Distribusi by Product
        "1983rrj" => "ReDP.RJ",   // Return Distribusi by Product
        "1983r" => "ReDP",   // Return Distribusi by Product
        "1983" => "ReDPA",   // Return Distribusi by Product Authorisation

        "1985re" => "ReSIP.EDIT",   // return Distribusi
        "1985rrj" => "ReSIP.RJ",   // return Distribusi
        "1985r" => "ReSIP",   // return Distribusi
        "1985" => "ReSIPA",

        "488" => "CO.PM.FG",// cah out Payment Finishgoods
        "489" => "PM.FG",        // Payment Finishgoods
        "487" => "PM.SP",        // Payment Rowmaterial supplies
        "462" => "PM.SE",        // Payment Service
        "473" => "PME",
        "749" => "RPC",         // Receive Payment Credit
        "2749" => "RPS",         // Receive Payment Shiping Service
        "1749" => "PM",
        "759r" => "RPM.KAS",
        "758r" => "RPM.KAS",
        "758" => "PM.KAS",
        "742" => "RPL.KAS",//receive  pendapatan lain lain
        "743" => "PM.BL.KAS",//beban lain lain

        "762re" => "RB-S.EDIT",
        "762rrj" => "RB-S.RJ",
        "762r" => "RB-S",
        "762" => "B-S",

        "7620re" => "ReS-A.EDIT",
        "7620rrj" => "ReS-A.RJ",
        "7620r" => "ReS-A",
        "7620" => "PN-A",

        "7622re" => "ReS-AN.EDIT",
        "7622rrj" => "ReS-AN.RJ",
        "7622r" => "ReS-AN",
        "7622a" => "ReA-AN",
        "7622" => "PN-AN",
        "7622f" => "PN-ANF",

        "400" => "CO",
        "700" => "CI",
        "967r" => "RRBM",
        "967re" => "RRBM.EDIT",
        "967rrj" => "RRBM.RJ",
        "967" => "RBM",
        "961r" => "RR",
        "961re" => "RR.EDIT",
        "961rrj" => "RR.RJ",
        "961" => "RBM",
        "960r" => "RRBM.IMP",     // Goods Receivemen Note Import
        "960re" => "RRBM.IMP.EDIT",     // Goods Receivemen Note Import
        "960rrj" => "RRBM.IMP.RJ",     // Goods Receivemen Note Import
        "960" => "RBM.IMP",     // Goods Receivemen Note Import

        "587re" => "RDR.EDIT",
        "587rrj" => "RDR.RJ",
        "587r" => "RDR",
        "587rarj" => "DRO.RJ",
        "587ra" => "DRO",
        "587" => "DR",

        "687re" => "RSTR.EDIT",
        "687rrj" => "RSTR.RJ",
        "687r" => "RSTR",
        "687ra" => "RSTR-O",
        "687rarj" => "RSTR-O.RJ",
        "687" => "STR",

        "1687re" => "RSTR.C.EDIT",
        "1687rrj" => "RSTR.C.RJ",
        "1687r" => "RSTR.C",
        "1687rarj" => "RSTR.C.RJ",
        "1687ra" => "RSTR.C",
        "1687" => "STR.C",

        "334re" => "RCOR.EDIT",
        "334rrj" => "RCOR.RJ",
        "334r" => "RCOR",
        "334" => "COR",

        "1334re" => "RCOR.EDIT",
        "1334rrj" => "RCOR.RJ",
        "1334r" => "RCOR",
        "1334" => "CONV",

        "1337re" => "RCOR-S.EDIT",
        "1337rrj" => "RCOR-S.RJ",
        "1337r" => "RCOR-S",
        "1337" => "CONV-S",

        "776re" => "RASR.EDIT",
        "776rrj" => "RASR.RJ",
        "776r" => "RASR",
        "776arj" => "AASR.RJ",
        "776a" => "AASR",
        "776" => "ASR",

        "976re" => "RDE-ASR.EDIT",
        "976rrj" => "RDE-ASR.RJ",
        "976r" => "RDE-ASR",
        "976" => "DE_ASR",

        "1582spoe" => "SOp.EDIT",      // Sales Order
        "1582sporj" => "SOp.RJ",      // Sales Order
        "1582spo" => "SOp",      // Sales Order
        "582spoe" => "SO.EDIT",      // Edit Sales Order
        "582spo" => "SO",      // Sales Order
        "582so" => "SOA",     // Sales Order Authorisation
        "582pkd" => "PRE.PL",  // Pre Packinglist
        "582spd" => "PL",      // Packing list (Shipment)
        "582" => "INV",     // Invoice
        "582k" => "PL",
        "582sporj" => "SO.RJ",      // Sales Order Reject
        "582sorj" => "SOA.RJ",     // Sales Order Authorisation Reject
        "582pkdrj" => "PRE.PL.RJ",  // Pre Packinglist Reject

        "1580spoe" => "SOp.EDIT",      // Sales Order
        "1580sporj" => "SOp.RJ",      // Sales Order
        "1580spo" => "SOp",      // Sales Order
        "580spoe" => "SO.EDIT",      // Edit Sales Order
        "580spo" => "SO.DS",      // Sales Order
        "580so" => "SOA.DS",     // Sales Order Authorisation
        "580pkd" => "PRE.PL.DS",  // Pre Packinglist
        "580spd" => "PL.DS",      // Packing list (Shipment)
        "580" => "INV.DS",     // Invoice
        "580k" => "PL.DS",
        "580sporj" => "SO.DS.RJ",      // Sales Order Reject
        "580sorj" => "SOA.DS.RJ",     // Sales Order Authorisation Reject
        "580pkdrj" => "PRE.PL.DS.RJ",  // Pre Packinglist Reject

        //penjualan jasa
        "584spoe" => "SO-J.EDIT", //sales order jasa
        "584sporj" => "SO-J.RJ", //sales order jasa
        "584spo" => "SO-J", //sales order jasa
        "584sorj" => "SOA-J.RJ", //sales order authorisation jasa
        "584so" => "SOA-J", //sales order authorisation jasa
        "584" => "INV-J", //invoicing jas
        "1784" => "RPC-J", //A/R penjualan jasa

        "588spoe" => "SO.PJ.EDIT",      // Sales Order
        "588sporj" => "SO.PJ.RJ",      // Sales Order
        "588spo" => "SO.PJ",      // Sales Order
        "588sorj" => "SOA.PJ.RJ",     // Sales Order Authorisation
        "588so" => "SOA.PJ",     // Sales Order Authorisation
        "588spd" => "PL.PJ",      // Packing list (Shipment)
        "588" => "INV.PJ",     // Invoice

        "982re" => "SR.EDIT",      // Sales Return
        "982rrj" => "SR.RJ",      // Sales Return
        "982r" => "SR",      // Sales Return
        "982g" => "SRA",     // Sales Return Authorisation
        "982grj" => "SRA.RJ",     // Sales Return Authorisation
        "982" => "RSR",     // Receivement Sales Return

        "382spoe" => "SOX.EDIT",
        "382sporj" => "SOX.RJ",
        "382spo" => "SOX",
        "382so" => "SOAX",
        "382sorj" => "SOAX.RJ",
        "382pkd" => "PRE.PLX",
        "382pkdrj" => "PRE.PLX.RJ",
        "382spd" => "PLX",
        "382" => "INVX",

        "383" => "M-EXC",
        "757re" => "RBI.EDIT",
        "757rrj" => "RBI.RJ",
        "757r" => "RBI",
        "757" => "BI-O",
        "1757re" => "RBI.EDIT",
        "1757rrj" => "RBI.RJ",
        "1757r" => "RBI",
        "1757" => "RB-O",
        "651re" => "RIE.EDIT",
        "651rrj" => "RIE.RJ",
        "651r" => "RIE",
        "651" => "IE-O",
        "652" => "IE-PYM",
        "671re" => "RPTC.EDIT",
        "671rrj" => "RPTC.RJ",
        "671r" => "RPTC",
        "671" => "PTC",
        "672re" => "RPTC.EDIT",
        "672rrj" => "RPTC.RJ",
        "672r" => "RPTC",
        "672" => "PTC-O",
        "771" => "REP",
        "1671r" => "RPTC",
        "1671" => "PTC-O",
        "1672r" => "RPTC",
        "1672" => "PTC-O",
        "1771" => "RE-PTC",
        "770" => "ADD-PTC",
        "970" => "RED-PTC",
        "999" => "ADJ-J",
        "999_0" => "ADJ-J",
        "888_1" => "ADJ-NON-PRD-P",
        "888_2" => "ADJ-NON-PRD-M",
        "777_1" => "ADJ-PRD-P",
        "777_2" => "ADJ-PRD-M",
        "666_1" => "ADJ-SPL-P",
        "666_2" => "ADS-SPL-M",
        "499" => "CRN",

        "1582" => "INV",
        "1582spd" => "SCO",
        "673r" => "RE",
        "673" => "EO",

        "3683" => "ST",
        "3683r" => "RST",
        "1982" => "CPKG",       //cancel packing request
        "1982g" => "CPKG-A",    //Cancel Packing Auth
        "8787r" => "DEP-RE",    //request depresiasi auto
        "8787" => "DEP-A",    //approval depresiasi auto
        "8788r" => "SwDEP-RE",    //request depresiasi auto
        "8788" => "SwDEP-A",    //approval depresiasi auto
        "8789r" => "SSA-RE",    //request jual aset
        "8789" => "SSA-A",    //approval jual aset

        "424r" => "ApSw-RE",    //request PO sewa
        "424" => "ApSw-A",    //approval PO sewa
        "425" => "GRN-Sw",    //approval PO sewa

        "681r" => "RPM-PIB",
        "681" => "APM-PIB",
        "5681r" => "RPM-PPH22",
        "5681" => "APM-PPH22",
        "5682" => "PM-PPH22",
        "5683" => "RPM-PPH29",//request pph29
        "5684" => "PM-PPH29",//payment pph 29
        "682" => "PM-PIB",

        "9982r" => "TER-ME",
        "9982" => "TE-ME",
        "9983r" => "TER-GE",
        "9983" => "TE-GE",
        "9984r" => "TER-PE",
        "9984" => "TE-PE",
        "110" => "EF-A",
        "110r" => "EF-R",
        "110e" => "EF-E",
        "111" => "RePM-C",
        "118r" => "EPP-C",
        "118" => "APP-C",
        "115" => "PM-PPH23",
        "464r" => "R-UM",
        "464" => "A-UM",
        "112" => "Re-PM",//realisasi ppn masukan
        "113" => "Re-PM",//realisasi ppn masukan
        "9911" => "J-Rej",//jurnal rejected/pembatalan jurnal
        "444r" => "R-HB",//request hutang bank
        "444" => "A-HB",//otorisasi hutang bank
        "4447" => "PYM-HB",//pembayaran hutang bank
        "448r" => "R-RHB",//request hutang bank rekening koran
        "448" => "A-RHB",//otorisasi hutang bank rekening koran
        "4440" => "PYM-RHB",//pembayaran hutang bank rekening koran
        "446r" => "RPH-PS",//Request penambahan hutang ke pemegang saham
        "446" => "APH-PS",//approval penambahan hutang ke pemegang saham
        "4448" => "PYM-RPH",//Approval Loan Interest

        "447r" => "RH-PL",//Request penambahan hutang ke pihak lain
        "447" => "AH-PL",//approval penambahan hutang ke pihak lain
        "4411" => "PYM-RH",//Approval Loan Interest

        "4449r" => "RLN-PS",//Request Loan Interest
        "4449" => "ALN-PS",//Approval Loan Interest
        "4410" => "PYM-RLN",//pembayaran biaya bunga kepemegang saham

        "445r" => "R-PeM",//request penambahan modal pemegang saham
        "445" => "A-PeM",//otorisasi penambahan modal pemegang saham

        "335r" => "RCOR-S",
        "335" => "CONV-S",
        "2983r" => "R-RM",
        "2983" => "RET-RM",
        "2985r" => "SI-RM",
        "2985" => "SR-RM",
        "2483r" => "P-ADR",
        "2483" => "P-ADA",
        "2485r" => "C-ADR",
        "2485" => "C-ADA",
        "1118" => "ADJ-S",
        "9912" => "J-Rej-B",
        "114" => "PYM-tax-C",
        "9749r" => "R-REM-AR",
        "9749" => "REM-AR",
        "384r" => "PO-V",
        "384" => "POA-V",
        "388r" => "CON-V",
        "388" => "CON-A-V",
        "4466r" => "R-UM-V",
        "4466" => "UM-V",
        "7499" => "INV-PJ",
        //--------------------
        "3311r" => "R.KPHJ",
        "3311" => "KPHJ",
        "3322r" => "R.KPHS",
        "3322" => "KPHS",
        //--------------------
        "4822" => "INV",
    );

    return $arrInitial;
}

function arrAvailFields()
{
    $temp = array(
        "nomer",
        "so_num",
        "nomer_po",
        "nomer_um",
        "nomer_new",
        "nomer_nolink",
        "nomer_download",
        "efaktursource",
        "eFaktur",
        "nama",
        "nomer_top",
        "nomer_top_new",
        "nomer_top2",
        "referencenomer__1",
        "referencenomer__2",
        "referencenomer__3",
        "referencenomer__4",
        "referencenomer__5",
        "efaktur_source",
        "transaksi_no",
        "print_label",
        "produk_nama",
        "referencenomer",
        "pairtransaksinomer_1",
        "pairtransaksinomer_2",
        "pairtransaksinomer_3",
        "extern_nama",
        "transaksidatas__nomer",
        "references_num",
    );

    return $temp;
}

function formatNota($collom, $nomer)
{
    if (in_array($collom, arrAvailFields())) {
        $exp = explode(".", $nomer);
        $existKey = str_replace(".", "", $exp[0]);
        //                        cekHitam($existKey);
        $newTmpName = isset(arrConfName()[$existKey]) ? str_replace($exp[0], arrConfName()[$existKey], "$nomer") : $nomer;
    }
    else {
        $newTmpName = $nomer;
    }

    return $newTmpName;
}

function formatField2($fieldName, $fieldValues)
{
    $ci =& get_instance();
    $segment_2 = $ci->uri->segment(2);
    $ci->load->config("heTransaksi_ui");
    // $ci->load->helper("he_angka");
    // cekHere($segment_2);
    $fieldValuesOri = $fieldValues;

    $fieldName = strtolower(trim($fieldName));
    //region tambahan formater nomer transaksi
    $fieldValue = formatNota($fieldName, $fieldValues);
    //endregion

    $formats = array(
        "dtime" => date("F d, Y", strtotime($fieldValue)),
        "debet" => number_format($fieldValues * 1),
        "qty_debet" => number_format($fieldValues * 1),
    );

    if (key_exists($fieldName, $formats)) {

        $var = $formats[$fieldName];
    }
    else {
        $var = $fieldValue;
    }

    return $var;
}

function formatFieldMonth($fieldName, $fieldValues)
{
    $formats = array(
        // "dtime" => date("Y F", strtotime($fieldValues)),
        $fieldName => date("Y F", strtotime($fieldValues)),
        // "debet" => number_format($fieldValues * 1),
        // "qty_debet" => number_format($fieldValues * 1),
    );
    if (key_exists($fieldName, $formats)) {

        $var = $formats[$fieldName];
    }
    else {
        $var = $fieldValue;
    }
    return $var;
}

function format_harga($angka, $lang = 0)
{
    if ($angka == "") {
        $angkaku = 0;
    }
    else {
        $desi = explode(".", $angka);

        if (sizeof($desi) > 0) {

            if ((isset($desi[1]) && (int)$desi[1]) == 0) {
                $jml_desi = 1;
            }
            else {

                $jml_desi = count($desi) + 1;
            }
        }

        $tampil_desi = $jml_desi > 2 ? 2 : $jml_desi - 1;
        if ($lang == 0) {
            $angkaku = number_format($angka, "$tampil_desi", ",", ".");
        }
        else {
            $angkaku = number_format($angka, "$tampil_desi", ".", "");
        }
    }


    return $angkaku;
}

function arrCodeAliasing($placeID)
{

    if ($placeID > 0) {
        $place = "branch";
    }
    elseif ($placeID < 0) {
        $place = "center";
    }
    else {
        $place = "unknown";
    }
    $arrInitial = array();
    switch ($place) {
        case "center":
            $arrInitial = array(
                "460" => "Pembelian (GRN FG Import)",      // Purchasing Order
                "466r" => "Request Purchase Order",      // Purchasing Order
                "466" => "Purchase Order",     // Purchasing Order Authorisation
                "467" => "Pembelian (GRN FG)",     // Goods Receivemen Note

                "461ro" => "Request Purchase Order",    // PO Supplies
                "461r" => "Purchase Order",   // PO Authorisation Supplies
                "461" => "Pembelian (GRN Supplies)",       // Receivement Supplies

                "761ro" => "761ro",      // Purchasing Request
                "761r" => "761r",     // Purchasing Request Authorisation
                "761ros" => "761ros",
                "761" => "761",
                "763" => "763",

                "463ro" => "Request Purchase Order",   // PO Service
                "463o" => "Purchase Order",  // PO Authorisation Service
                "463" => "Pembelian (SRN)",  // PO Knowledge Service

                "3463ro" => "Service Request Purchase Order Project",   // PO Service
                "3463o" => "Service Purchase Order Project",  // PO Authorisation Service
                "3463" => "Pembelian (SRN) Project",  // PO Knowledge Service

                "3461r" => "REQUEST SERVICE PROJECT DISTRIBUTION",  // PO Authorisation Service
                "3461" => "SERVICE PROJECT DISTRIBUTION",  // PO Knowledge Service
                "3465r" => "SERVICE PROJECT DISTRIBUTION",  // PO Authorisation Service
                "3465" => "RECEIVE DISTRIBUTION SERVICE PROJECT",  // PO Knowledge Service
                "583r" => "Request Distribusi",      // Request Distribusi
                "583" => "Otorisasi Distribusi",     // Request Distribusi Authorisation

                "585r" => "Request Distribusi ke cabang",      // Stock Initiation
                "585" => "Distribusi ke cabang",      // Stock Receivment

                "983r" => "Request Return Distribusi cabang",     // Return Distribusi
                "983" => "Otorisasi Return Distribusi",    // Return Distribusi Authorisation

                "985r" => "Otorisasi Return Distribusi",    //
                "985" => "Penerimaan Return Distribusi dari cabang",

                "1983r" => "Request Return Distribusi (by Nota)",   // Return Distribusi by Product
                "1983" => "Otorisasi Return Distribusi (by Nota)",   // Return Distribusi by Product Authorisation

                "1985r" => "Otorisasi Return Distribusi (by Nota)",   // return Distribusi
                "1985" => "Return Distribusi (by Nota)",

                "489" => "Pembayaran Hutang Dagang (FG)",        // Payment Finishgoods
                "487" => "Pembayaran Hutang Dagang (Supplies)",        // Payment Rowmaterial supplies
                "462" => "Pembayaran Hutang Jasa",        // Payment Service
                "473" => "PME",
                //                "749" => "RPC",         // Receive Payment Credit
                //                "2749" => "RPS",         // Receive Payment Shiping Service
                //                "1749" => "PM",
                //                "759r" => "RPM.KAS",
                //                "758r" => "RPM.KAS",
                "758" => "Penerimaan Setoran Kas",
                "749" => "Penerimaan piutang",
                "762r" => "Request Pembiayaan Supplies",
                "762" => "Pembiayaan Supplies",
                "400" => "Pengeluaran Kas",
                "700" => "Penerimaan Kas",
                "967r" => "Request Return Pembelian (FG)",
                "967" => "Return Pembelian (FG)",
                "961r" => "Request Return Pembelian (supplies)",
                "961" => "Return Pembelian (supplies)",
                "587r" => "Request Pemindahan Produk ke Gudang Rusak",
                "587ra" => "Otorisasi Pemindahan Produk ke Gudang Rusak",
                "587" => "Penerimaan Produk di Gudang Rusak",
                "687r" => "Request Pemindahan Produk ke Gudang Penjualan",
                "687ra" => "Otorisasi Pemindahan Produk ke Gudang Penjualan",
                "687" => "Penerimaan Produk di Gudang Penjualan",

                "3355" => "Konversi Produk (Satuan)",
                "335" => "Konversi Produk (Satuan)",
                "334r" => "Request Konversi Produk (Branch)",
                "334" => "Konversi Produk (Branch)",
                "1334r" => "Request Konversi Produk (Center)",
                "1334" => "Konversi Produk (Center)",
                "1337r" => "Request Konversi Supplies (Center)",
                "1337" => "Konversi Supplies (Center)",
                "1339" => "Konversi Produk (Potong)",
                "776r" => "Request Produksi",
                "776" => "Produksi",
                "976r" => "Request Return Produksi",
                "976" => "Return Produksi",

                "582spoe" => "Edit Sales Pre Order",      // Sales Order
                "582spo" => "Sales Pre Order",      // Sales Order
                "582so" => "Otorisasi Sales Order",     // Sales Order Authorisation
                "582pkd" => "Pre Packing",  // Pre Packinglist
                "582spd" => "PackingList and Shipment (Domestic Sales)",      // Packing list (Shipment)
                "5822spd" => "Pengiriman ke konsumen",
                "582" => "Invoicing",     // Invoice

                "582k" => "PackingList and Shipment",

                "982r" => "Request Return Penjualan",      // Sales Return
                "982g" => "Otorisasi Return Penjualan",     // Sales Return Authorisation
                "982" => "Penerimaan Barang Return Penjualan",     // Receivement Sales Return

                "382spo" => "Sales Pre Order Eksport",
                "382so" => "Otorisasi Sales Order Eksport",
                "382pkd" => "Pre Packing Eksport",
                "382spd" => "PackingList and Shipment Eksport",
                "382" => "Invoicing Eksport",

                "383" => "M-EXC",
                "757r" => "Request Pemindahan Kas (Branch)",
                "757" => "Pemindahan Kas (Branch)",
                "1757r" => "Request Pemindahan Kas (Center)",
                "1757" => "Pemindahan Kas (Center)",
                "651r" => "RIE",
                "651" => "IE-O",
                "652" => "pembayaran biaya import",
                "671r" => "Request Pettycash",
                "671" => "Otorisasi Request Pettycash",
                "672r" => "Otorisasi Request Pettycash",
                "672" => "Otorisasi Request Pettycash",
                "771" => "Refill Pettycash for Branch",
                "1671r" => "Request Pettycash",
                "1671" => "Otorisasi Request Pettycash",
                "1672r" => "Otorisasi Request Pettycash",
                "1672" => "Otorisasi Request Pettycash",
                "1771" => "Refill Pettycash for Center",
                "770" => "Penambahan Plafon Pettycash",
                "970" => "Pengurangan Plafon Pettycash",
                "999" => "Adjustment",
                "999_0" => "Adjustment",
                "888_1" => "Adjustment Non Product",
                "888_2" => "Adjustment Non Product",
                "777_1" => "Adjustment Product inventory",
                "777_2" => "Adjustment Product inventory",
                "666_1" => "Adjustment",
                "666_2" => "Adjustment",
                "499" => "Credit Note",
                "1749" => "export",
                "2229" => "Stok opname produk",
                "7762" => "Pembiayaan supplies by recipnumber",
                "117" => "pph-25",
                "118" => "pph-pasal4 ayat 2",
                "113" => "realisasi ppn masukan jasa",
                "112" => "realisasi ppn masukan supplies",
                "111" => "realisasi ppn masukan fg",
                "114" => "setor ppn",
                "1118" => "opname supplies",
                "743" => "biaya lain-lain",
                "8787" => "depresiasi aset berwujud",
                "1582" => "INV",
                "1582spd" => "SCO",
                "673r" => "RE",
                "673" => "EO",
                "999_1" => "adjusment ...",
                "486" => "raw material cashtout",

                "3683" => "ST",
                "3683r" => "RST",
                "1119" => "stok opname",
                "9982r" => "Request Transfer Expense to Marketing Expense",
                "9982" => "Transfer Expense to Marketing Expense",
                "9983r" => "Request Transfer Expense to General Expense",
                "9983" => "Transfer Expense to General Expense",
                "9984r" => "Request Transfer Expense to Production Expense",
                "9984" => "Transfer Expense to Production Expense",
                "2675r" => "Request General Expense", //aproval expense/biaya umum center
                "2675" => "Approval General Expense", //aproval expense/biaya umum center
                "2677r" => "Request Marketing Expense", //approval expense/biaya usaha pusat
                "2677" => "Approval Marketing Expense", //approval expense/biaya usaha pusat
                "2676r" => "Request Production Expense", // approval expense/biaya produksi pusat
                "2676" => "Approval Production Expense", // approval expense/biaya produksi pusat
                "476" => "Production Expense A/P Payment",
                "475" => "General Expense A/P Payment",
                "477" => "Marketing Expense A/P Payment",
                "2985r" => "Inisiasi Penerimaan Return Distribusi Bahan Baku",
                "2985" => "Penerimaan Return Distribusi Bahan Baku",
                "3585r" => "Request Distribusi Bahan Baku ke Pabrik",
                "3585" => "Distribusi Bahan Baku ke Pabrik",
                "485" => "Cash Out Supplies",
                "488" => "Cash Out Finish Goods",
                "445r" => "Request Penambahan Modal",//request penambahan modal pemegang saham
                "445" => "Otorisasi Penambahan Modal",//otorisasi penambahan modal pemegang saham
                "9911" => "Pembatalan Transaksi",
                "464" => "Otorisasi Uang Muka",
                "682" => "Pembayaran Object Pajak",
                "5682" => "Pembayaran Hutang Pph 22",
                "3685" => "Penerimaan Transfer Stok dari Cabang",
                "110" => "Otorisasi Entry e-Faktur",
                "1463" => "Service Receipt Note (center)",
                "422" => "Penambahan Aset Tetap",
                "2485" => "Penerimaan Distribusi Aset Tetap di Cabang",
                "423" => "Pembelian Aset Tetap (GRN)",
                "444" => "Otorisasi Pemakaian Hutang Bank",
                "1475" => "Pembayaran Hutang Biaya Umum (center)",
                "1477" => "Pembayaran Hutang Biaya Usaha (center)",
                "1677" => "Otorisasi Request Biaya Usaha (center)",
                "1675" => "Otorisasi Request Biaya Umum (center)",
                "1462" => "Pembayaran Hutang Jasa (center)",        // Payment Service
                "115" => "Penyetoran Hutang Pph 23",
                "9922" => "Koreksi Biaya ke PPV",
                "8787" => "Otorisasi Depresiasi",
                "9912" => "Pembatalan Transaksi (branch)",
                "4675" => "Biaya (center)",
                "113" => "Realisasi PPN Masukan",
                "112" => "Realisasi PPN Masukan",
                "4449" => "Biaya bunga",
                "446" => "hutang kepemegang saham",
                "4448" => "pembayaran hutang kepemegang saham",
//                "4449" => "biaya bunga kepemegang saham",
                "4410" => "pembayaran biaya bunga kepemegang saham",
                "4821" => "pembayaran hutang aktiva tetap",
                "453r" => "transfer kas ke cabang",
                "454" => "penerimaan kas di cabang dari pusat",
                "425" => "Otorisasi Sewa",
                "1424" => "Pembayaran Hutang Biaya Sewa",
                "4891" => "Pembayaran Hutang Hutang Dagang Import",
                "2762" => "Pembiayaan Supplies (branch)",
                "1749" => "Penerimaan Piutang (Export)",
                "383" => "Penjualan Valas",
                "1582spo" => "Penjualan Paket",
                "1674" => "Biaya Gaji Cabang",
                "2674" => "Biaya Gaji Pusat",
                "1485" => "Pembayaran Hutang Gaji",
                "1483" => "Pembayaran Hutang pph21",
                "742" => "Pendapatan Lain-lain",
                "743" => "Biaya Lain-lain",
                "8786" => "otorisasi penyusutan aset berwujud (pusat)",
                "8788" => "otorisasi penyusutan aset tak berwujud",
                "8789" => "otorisasi penjualan aset tetap",
                "9985" => "otorisasi pemindahan biaya usaha",
                "7620" => "penambahan nilai aset",
                "4411" => "pembayaran hutang pihak lain",
                "447" => "penambahan hutang pihak lain",
                "4447" => "pembayaran hutang bank",
                "2119" => "pembayaran imbalan jasa",
                "119" => "otorisasi imbalan jasa",
                "1120" => "setor hutang pph ps4 ayat 2",
                "5683" => "otorisasi pph 29",
                "5684" => "pembayaran pph 29",
                "116" => "otorisasi efaktur pph 23",
                "4440" => "pembayaran hutang bank",//pembayaran hutang bank rekening koran
                "6475" => "pembayaran hutang biaya",
                "673" => "otorisasi biaya (pusat)",
                "7622" => "konversi supplies ke aset baru",
                "9749" => "penghapusan piutang dagang",
                "773r" => "request repack produk komposit",
                "773" => "repack produk komposit",
                "4464r" => "request uang muka (dp tanpa ppn)",
                "4464" => "approval uang muka (dp tanpa ppn)",
                "1758r" => "setoran mata uang asing",
                "1758" => "Penerimaan Setoran (Mata Uang Asing)",
                "1487" => "Pembayaran hutang BPJS",
                "2228" => "STOCK OPNAME BOM AUTHORIZATION 2",
                "2227" => "STOCK OPNAME NON BOM AUTHORIZATION 2",
                "384r" => "request purchasing valas",
                "384" => "purchasing valas authorization",
                "4466r" => "request uang muka (valas)",
                "4466" => "approval uang muka (valas)",
                "588spo" => "SALES PRE ORDER PROJECT",
                "588so" => "SALES ORDER PROJECT",
                "588spd" => "PACKING LIST PROJECT",
                "588" => "CLOSING PROJECT",
                "0000" => "adjustment",
                "99999" => "adjustment",
                "2687" => "pindah gudang dari gudang project ke gudang reguler",
                "9467" => "pengembalian uang ke konsumen",
                "19467" => "pengembalian uang ke konsumen",
                "9990r" => "request jurnal umum",
                "9990" => "jurnal umum",
                "7674" => "Otorisasi biaya bpjs dan pph21",
                "6677" => "cashback penjualan (biaya)",
                "16677" => "otorisasi cashback penjualan",
                "1488" => "Komisi AP payment",
            );
            break;
        case "branch":
            $arrInitial = array(
                "466r" => "Request Purchase Order",      // Purchasing Order
                "466" => "Purchase Order",     // Purchasing Order Authorisation
                "467" => "Pembelian (GRN FG)",     // Goods Receivemen Note

                "461ro" => "Request Purchase Order",    // PO Supplies
                "461r" => "Purchase Order",   // PO Authorisation Supplies
                "461" => "Pembelian (GRN Supplies)",       // Receivement Supplies

                "761ro" => "",      // Purchasing Request
                "761r" => "",     // Purchasing Request Authorisation
                "761ros" => "",
                "761" => "",
                "763" => "",
                "999_1" => "adjusment ...",
                "1119" => "stok opname produk",
                "7762" => "pembiayaan supples",

                "463ro" => "Request Purchase Order",   // PO Service
                "463o" => "Purchase Order",  // PO Authorisation Service
                "463" => "Pembelian (SRN)",  // PO Knowledge Service

                "3463ro" => "Service Request Purchase Order Project",   // PO Service
                "3463o" => "Service Purchase Order Project",  // PO Authorisation Service
                "3463" => "Pembelian (SRN) Project",  // PO Knowledge Service

                "583r" => "Request Distribusi",      // Request Distribusi
                "583" => "Otorisasi Distribusi",     // Request Distribusi Authorisation

                "585r" => "Distribusi",      // Stock Initiation
                "585" => "Penerimaan Distribusi",      // Stock Receivment

                "983r" => "Request Return Distribusi",     // Return Distribusi
                "983" => "Otorisasi Return Distribusi",    // Return Distribusi Authorisation

                "985r" => "Otorisasi Return Distribusi",    //
                "985" => "penerimaan Return Distribusi",

                "1983r" => "Request Return Distribusi (by Nota)",   // Return Distribusi by Product
                "1983" => "Otorisasi Return Distribusi (by Nota)",   // Return Distribusi by Product Authorisation

                "1985r" => "Otorisasi Return Distribusi (by Nota)",   // return Distribusi
                "1985" => "Return Distribusi (by Nota)",

                "489" => "Pembayaran Hutang Dagang (Produk)",        // Payment Finishgoods
                "487" => "Pembayaran Hutang Dagang (Supplies)",        // Payment Rowmaterial supplies
                "462" => "Pembayaran Hutang Jasa",        // Payment Service
                "473" => "PME",
                "749" => "Penerimaan Piutang",         // Receive Payment Credit
                "2749" => "RPS",         // Receive Payment Shiping Service
                "1749" => "PM",
                "759r" => "Request Penyetoran Kas",
                "759" => "Otorisasi Penyetoran Kas",
                "758r" => "Otorisasi Penyetoran Kas",
                "758" => "Setoran Kas",

                "762r" => "Request Pembiayaan Supplies",
                "762" => "Pembiayaan Supplies",
                "400" => "Pengeluaran Kas",
                "700" => "Penerimaan Uang Muka",
                "967r" => "Request Return Pembelian (FG)",
                "967" => "Return Pembelian (FG)",
                "961r" => "Request Return Pembelian (Supplies)",
                "961" => "Return Pembelian (Supplies)",

                "587r" => "Request Pemindahan Produk ke Gudang Rusak",
                "587ra" => "Otorisasi Pemindahan Produk ke Gudang Rusak",
                "587" => "Penerimaan Produk di Gudang Rusak",

                "687r" => "Request Pemindahan Produk ke Gudang Penjualan",
                "687ra" => "Otorisasi Pemindahan Produk ke Gudang Penjualan",
                "687" => "Penerimaan Produk di Gudang Penjualan",

                "334r" => "Request Konversi Produk (Branch)",
                "334" => "Konversi Produk (Branch)",
                "1334r" => "Request Konversi Produk (Center)",
                "1334" => "Konversi Produk (Center)",
                "1337r" => "Request Konversi Supplies (Center)",
                "1337" => "Konversi Supplies (Center)",
                "776r" => "Request Produksi",
                "776a" => "Approval Request Produksi",
                "776" => "Produksi",
                "976r" => "Request Return Produksi",
                "976" => "Return Produksi",

                "582spoe" => "Penjualan (Edit Sales Pre Order)",      // Sales Order
                "582spo" => "Penjualan (Sales Pre Order)",      // Sales Order
                "582so" => "Penjualan (Otorisasi Sales Order)",     // Sales Order Authorisation
                "582pkd" => "Penjualan (Pre Packing)",  // Pre Packinglist
                "582spd" => "Penjualan (Packing List Domestic Sales)",      // Packing list (Shipment)
                "582" => "Penjualan (Invoicing)",     // Invoice

                "582k" => "Penjualan (PackingList and Shipment)",
                "5822spd" => "Pengiriman ke konsumen",
                "982r" => "Return Penjualan (Request Return Penjualan)",      // Sales Return
                "982g" => "Return Penjualan (Otorisasi Return Penjualan)",     // Sales Return Authorisation
                "982" => "Return Penjualan (Penerimaan Barang)",     // Receivement Sales Return

                "382spo" => "Penjualan (Sales Pre Order Eksport)",
                "382so" => "Penjualan (Otorisasi Sales Order Eksport)",
                "382pkd" => "Penjualan (Pre Packing Eksport)",
                "382spd" => "Penjualan (PackingList and Shipment (Eksport Sales)",
                "382" => "Penjualan (Invoicing Eksport)",

                "383" => "M-EXC",
                "757r" => "Request Pemindahan Kas (Branch)",
                "757" => "Pemindahan Kas (Branch)",
                "1757r" => "Request Pemindahan Kas (Center)",
                "1757" => "Pemindahan Kas (Center)",
                "651r" => "RIE",
                "651" => "IE-O",
                "652" => "IE-PYM",
                "671r" => "Request Pettycash",
                "671" => "Otorisasi Request Pettycash",
                "672r" => "Otorisasi Request Pettycash",
                "672" => "Otorisasi Request Pettycash",
                "771" => "Refill Pettycash",
                "1671r" => "Request Pettycash",
                "1671" => "Otorisasi Request Pettycash",
                "1672r" => "Otorisasi Request Pettycash",
                "1672" => "Otorisasi Request Pettycash",
                "1771" => "Refill Pettycash",
                "770" => "Penambahan Plafon Pettycash",
                "970" => "Pengurangan Plafon Pettycash",
                "999" => "Adjustment",
                "999_0" => "Adjustment",
                "888_1" => "Adjustment Piutang Dagang",
                "888_2" => "Adjustment Piutang Dagang",
                "777_1" => "Adjustment Product inventory",
                "777_2" => "Adjustment Product inventory",
                "666_1" => "Adjustment",
                "666_2" => "Adjustment",
                "499" => "Credit Note",

                "1582" => "Penjualan (Paket)",
                "1582spd" => "Penjualan (Packing List Paket)",
                "673r" => "RE",
                "673" => "EO",

                "3683" => "ST",
                "3683r" => "RST",
                //penjualan jas
                //                "584spo" =>"Service sales", //sales order jasa
                //                "584so" =>"SOA-J", //sales order authorisation jasa
                "584" => "Service sales", //invoicing jas
                "1784" => "Penerimaan piutang", //A/R penjualan jasa
                "2229" => "stok opname",
                "9982r" => "Request Transfer Expense to Marketing Expense",
                "9982" => "Transfer Expense to Marketing Expense",
                "9983r" => "Request Transfer Expense to General Expense",
                "9983" => "Transfer Expense to General Expense",
                "9984r" => "Request Transfer Expense to Production Expense",
                "9984" => "Transfer Expense to Production Expense",
                "2675r" => "Request General Expense", //aproval expense/biaya umum center
                "2675" => "Approval General Expense", //aproval expense/biaya umum center
                "2677r" => "Request Marketing Expense", //approval expense/biaya usaha pusat
                "2677" => "Approval Marketing Expense", //approval expense/biaya usaha pusat
                "2676r" => "Request Production Expense", // approval expense/biaya produksi pusat
                "2676" => "Approval Production Expense", // approval expense/biaya produksi pusat
                "476" => "Production Expense A/P Payment",
                "475" => "General Expense A/P Payment",
                "477" => "Marketing Expense A/P Payment",

                "335r" => "Request Konversi Supplies (satuan)",
                "335" => "Konversi Supplies (satuan)",

                "2983r" => "Request Return Distribusi Bahan Baku",
                "2983" => "Otorisasi Return Distribusi Bahan Baku",
                "2985r" => "Inisiasi Return Distribusi Bahan Baku",
                "2985" => "Return Distribusi Bahan Baku",
                "3585r" => "Inisiasi Penerimaan Bahan Baku di Pabrik",
                "3585" => "Penerimaan Bahan Baku di Pabrik",
                "485" => "Cash Out Supplies",
                "488" => "Cash Out Finish Goods",
                "9911" => "Pembatalan Transaksi",
                "464" => "Otorisasi Uang Muka",
                "682" => "Pembayaran Object Pajak",
                "5682" => "Pembayaran Hutang Pph 22",
                "3685" => "Penerimaan Transfer Stok di Pusat",
                "110" => "Otorisasi Entry e-Faktur",
                "2485" => "Penerimaan Distribusi Aset Tetap di Cabang",
                "8787" => "Otorisasi Depresiasi",
                "9912" => "Pembatalan Transaksi (branch)",
                "453r" => "transfer kas ke cabang",
                "454" => "penerimaan kas di cabang dari pusat",
                "425" => "Otorisasi Sewa",
                "1424" => "Pembayaran Hutang Biaya Sewa",
                "4891" => "Pembayaran Hutang Hutang Dagang Import",
                "2762" => "Pembiayaan Supplies (branch)",
                "1749" => "Penerimaan Piutang (Export)",
                "383" => "Penjualan Valas",
                "1582spo" => "Penjualan Paket",
                "1674" => "Biaya Gaji Cabang",
                "2674" => "Biaya Gaji Pusat",
                "742" => "Pendapatan Lain-lain",
                "743" => "Biaya Lain-lain",
                "8786" => "otorisasi penyusutan aset berwujud (pusat)",
                "8788" => "otorisasi penyusutan aset tak berwujud",
                "8789" => "otorisasi penjualan aset tetap",
                "9749" => "penghapusan piutang dagang",
                "773r" => "request repack produk komposit",
                "773" => "repack produk komposit",
                "4464r" => "request uang muka (dp tanpa ppn)",
                "4464" => "approval uang muka (dp tanpa ppn)",
                "1758r" => "setoran mata uang asing",
                "1758" => "Penerimaan Setoran (Mata Uang Asing)",
                "1487" => "Pembayaran hutang BPJS",
                "2228" => "STOCK OPNAME BOM AUTHORIZATION 2",
                "2227" => "STOCK OPNAME NON BOM AUTHORIZATION 2",
                "384r" => "request purchasing valas",
                "384" => "purchasing valas authorization",
                "4466r" => "request uang muka (valas)",
                "4466" => "approval uang muka (valas)",

                "588spo" => "SALES PRE ORDER PROJECT",
                "588so" => "SALES ORDER PROJECT",
                "588spd" => "PACKING LIST PROJECT",
                "588" => "CLOSING PROJECT",
                "0000" => "adjustment",
                "99999" => "adjustment",
                "9467" => "pengembalian uang ke konsumen",
                "19467" => "pengembalian uang ke konsumen",
                "9990r" => "request jurnal umum",
                "9990" => "jurnal umum",
                "6677" => "cashback penjualan (biaya)",
                "16677" => "otorisasi cashback penjualan",
                "1488" => "Komisi AP payment",
            );
            break;
        case "unknown":
            $arrInitial = array();
            break;
    }


    return $arrInitial;
}

function timeSince($original)
{
    //    date_default_timezone_set('Asia/Jakarta');
    //    $chunks = array(
    //        array(60 * 60 * 24 * 365, 'tahun'),
    //        array(60 * 60 * 24 * 30, 'bulan'),
    //        array(60 * 60 * 24 * 7, 'minggu'),
    //        array(60 * 60 * 24, 'hari'),
    //        array(60 * 60, 'jam'),
    //        array(60, 'menit'),
    //    );
    //    $today = time();
    //    $since = $today - $original;
    //
    //    if ($since > 604800) {
    //        $print = date("M jS", $original);
    //        if ($since > 31536000) {
    //            $print .= ", " . date("Y", $original);
    //        }
    //        return $print;
    //    }
    //
    //    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
    //        $seconds = $chunks[$i][0];
    //        $name = $chunks[$i][1];
    //
    //        if (($count = floor($since / $seconds)) != 0) {
    //            break;
    //        }
    //    }
    //
    //    $print = ($count == 1) ? '1 ' . $name : "$count {$name}";
    //    return $print . ' yang lalu';

    //    $time = strtotime( $original );

    return '' . humanTiming($original) . ' yg lalu';

}

function humanTiming($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'tahun',
        2592000 => 'bulan',
        604800 => 'minggu',
        86400 => 'hari',
        3600 => 'jam',
        60 => 'menit',
        1 => 'detik'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) {
            continue;
        }
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? '' : '');
    }

}

function timeSinceV2($original)
{
    $awal = date_create(date("Y-m-d H:i:s", $original));
    $akhir = date_create(); // waktu sekarang
    $diff = date_diff($awal, $akhir);
    return ($diff->y > 0 ? $diff->y . " thn " : "") . ($diff->m > 0 ? $diff->m . " bln " : "") . ($diff->d > 0 ? $diff->d . " hari " : "0 hari");
}

function getTransaksiJenis()
{

    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");

    $arrJenis = $ci->config->item("heTransaksi_ui");

    $arrJenisResult = array();
    if (sizeof($arrJenis) > 0) {
        foreach ($arrJenis as $jenis_master => $jenisSpec) {

            foreach ($jenisSpec['steps'] as $stepNum => $stepSpec) {
                $arrJenisResult[$stepSpec['target']] = $stepSpec['target'];
            }
        }
    }

    return $arrJenisResult;
}

function getTransaksiJenisTranslate()
{

    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");

    $arrJenis = $ci->config->item("heTransaksi_ui");

    $arrJenisResult = array();
    if (sizeof($arrJenis) > 0) {
        foreach ($arrJenis as $jenis_master => $jenisSpec) {
            foreach ($jenisSpec['steps'] as $stepNum => $stepSpec) {
                $arrJenisResult[$stepSpec['target']] = $stepSpec['label'];
            }
        }
    }

    return $arrJenisResult;
}


function arrCodeJurnalAliasing()
{
    $ci =& get_instance();
    $trCore = $ci->config->item("heTransaksi_core");
    $trUI = $ci->config->item("heTransaksi_ui");
    $arrCodeAliasingCenter = arrCodeAliasing("-1");
    $arrCodeAliasingCabang = arrCodeAliasing("1");

    $arrCodeJurnal = array();
    $targetCode = array();

    foreach ($trCore as $jenis_master => $spec) {
        if (isset($spec['components'])) {
            foreach ($spec['components'] as $step => $stepSpec) {
                if (isset($stepSpec['master'])) {
                    foreach ($stepSpec['master'] as $ii => $subSpec) {
                        if ($subSpec['comName'] == "Jurnal") {
                            $arrCodeJurnal[$jenis_master][$step] = $step;
                        }
                    }
                }
            }
        }
    }


    foreach ($arrCodeJurnal as $jenis_master => $arrSpec) {
        foreach ($arrSpec as $step) {
            if (isset($trUI[$jenis_master]['steps'][$step]['target'])) {
                $code = $trUI[$jenis_master]['steps'][$step]['target'];
                if (isset($arrCodeAliasingCenter[$code])) {
                    $codeName = $arrCodeAliasingCenter[$code];
                }
                else {
                    if (isset($arrCodeAliasingCabang[$code])) {
                        $codeName = $arrCodeAliasingCabang[$code];
                    }
                    else {
                        $codeName = $code;
                    }
                }
                $targetCode[$code] = $codeName != NULL ? $codeName : $code;
            }
        }
    }


    return $targetCode;

}

// untuk gerbang main
function addPrefixKeyM_he_format($myArray)
{

    $myNewArray = array_combine(

        array_map(function ($key) {
            return 'm_' . $key;
        }, array_keys($myArray)),

        $myArray

    );

    return $myNewArray;
}

// untuk gerbang items
function addPrefixKeyI_he_format($myArray)
{

    $myNewArray = array_combine(

        array_map(function ($key) {
            return 'i_' . $key;
        }, array_keys($myArray)),

        $myArray

    );

    return $myNewArray;
}

// untuk transaksi
function addPrefixKeyT_he_format($myArray)
{

    $myNewArray = array_combine(

        array_map(function ($key) {
            return 't_' . $key;
        }, array_keys($myArray)),

        $myArray

    );

    return $myNewArray;
}

function getNewNumberFormat($main, $sesMain)
{

    $ci =& get_instance();
    $arrValues = $main['main'];
    $ci->db->where("default=1");
    $ci->db->where("status=1");
    $ci->db->where("trash=0");
    $getSetting = $ci->db->get("set_numbering")->result();

    if (count($getSetting) == 1) {
        $valueSetting = $getSetting[0]->value != "" ? $getSetting[0]->value : matiHere("format number invoice belum di setting");
        $format_dtime = $getSetting[0]->format_dtime != "" ? $getSetting[0]->format_dtime : "";

        $stepCode = $sesMain['stepCode'];
        $cabangID = $sesMain['cabangID'];
        $pihakID = $sesMain['pihakID'];
        $olehID = $sesMain['olehID'];

        $newDtime = date($format_dtime, strtotime($sesMain['fulldate']));
        $sesMain['dtime'] = $newDtime;
        $numb = array();

        $tmpNumb = json_decode(base64_decode($getSetting[0]->value), 1);
        cekMerah("ini setingan numbering LINE: " . __LINE__);
        arrPrintWebs($tmpNumb);
        $isLast = 0;
        $sNumb = count($tmpNumb);
        foreach ($tmpNumb as $k => $set) {
            $isLast++;
            if ($set['show'] == 1) {
                $globalFormat = 1;
                if ($globalFormat) {
                    if ($set['key'] == "stepCode") {
                        $numb[] = isset(arrConfName()[$stepCode]) ? arrConfName()[$stepCode] : $stepCode;
                    }
                    else {
                        if ($set['key'] == "_company_cabangID_pihakID") {
                            if (isset($arrValues["_company_cabangID_supplierID"]) && $arrValues["_company_cabangID_supplierID"] != '') {
                                $numb[] = $arrValues["_company_cabangID_supplierID"];
                            }
                            else {
                                $numb[] = $arrValues["_company_cabangID_customerID"];
                            }
                        }
                        else {
                            $numb[] = isset($arrValues[$set['key']]) && $arrValues[$set['key']] != '' ? $arrValues[$set['key']] : (isset($sesMain[$set['key']]) && $sesMain[$set['key']] != '' ? $sesMain[$set['key']] : "");
                        }
                    }
                }
                else {
                    $numb[] = isset($arrValues[$set['key']]) && $arrValues[$set['key']] != '' ? $arrValues[$set['key']] : (isset($sesMain[$set['key']]) && $sesMain[$set['key']] != '' ? $sesMain[$set['key']] : "");
                }
//                if($set['separator']!=""){
                if ($isLast != $sNumb) {
                    $numb[] = $set['separator'];
                }
//                }
            }
        }
        $hasil_number = implode("", $numb);
        return $hasil_number;
    }
    else {
        matiHere("format number invoice belum di setting");
    }

}

if (!function_exists('isDate')) {
    // Fungsi belum didefinisikan, maka didefinisikan
    function isDate($string)
    {
        return strtotime($string) !== false;
    }
}

function trimArray($multidimensi_array)
{
    $temp = array();
    foreach ($multidimensi_array as $key => $value) {
        if (is_array($value)) {
            $key_1 = trim($key);

            $array_2 = trimArray($value);

            $temp[$key_1] = $array_2;
        }
        else {
            $temp[trim($key)] = trim($value);
        }
    }

    return $temp;
}


function highlight_v1_he_format($text, $kword)
{
    $keyword = explode(" ", $kword);
    $keyword_count = count($keyword);
    // $text_0 = "";
    // $text_2 = $text;
    // for ($i = 0; $i < $keyword_count; $i++) {
    //
    //     // echo $keyword[$i] ." | ";
    //     $highlighted_text = "{" . $keyword[$i] . "}";
    //
    //     $text = str_ireplace($keyword[$i], $highlighted_text, $text);
    // }

    for ($i = 0; $i < $keyword_count; $i++) {

        // echo $keyword[$i] ." | ";
        $highlighted_text = "<span class='text-bold text-red'>" . $keyword[$i] . "</span>";

        $text = str_ireplace($keyword[$i], $highlighted_text, $text);
    }

    // cekBiru("$text || $kword ");

    return $text;
}

function highlight_he_format($text, $words, $text_color = "red")
{
    preg_match_all('~[\p{L}\p{M}]+~u', $words, $m);
    if (!$m) {
        return $text;
    }
    $re = '~(' . implode('|', $m[0]) . ')~i';

    return preg_replace($re, "<span style='color: $text_color;'>$0</span>", $text);
}

function formatStringWithSeparator_he_format($string, $separator, $digitCount)
{
    $formattedString = '';
    $remainingDigits = strlen($string);

    while ($remainingDigits > 0) {
        $count = min($digitCount, $remainingDigits);
        $formattedString .= substr($string, 0, $count) . $separator;
        $string = substr($string, $count);
        $remainingDigits -= $count;
    }

    // Hapus separator ekstra di akhir
    $formattedString = rtrim($formattedString, $separator);

    return $formattedString;

    // return 1;
}

function getFirstPhone($string)
{
    $parts = preg_split('/\s+/', trim($string));
    return isset($parts[0]) ? $parts[0] : '';
}

function getColorSelector()
{
    $colors = array(
        "#000000",
//                "#0056cd",
        "#3c8dbc",
        "#ff7700",
        "#009900",
        "#9999cc",
    );
    return $colors;
}

if (!function_exists('renderCrossModulResumeLink')) {
    function renderCrossModulResumeLink($nomer, $customLabel = '', $customStyle = '', $customClass = '')
    {
        if (empty($nomer) || $nomer === '-') {
            return '-';
        }

        $ci =& get_instance();
        $ui = $ci->config->item('heTransaksi_ui');
        if (empty($ui)) {
            $ci->load->config('heTransaksi_ui');
            $ui = $ci->config->item('heTransaksi_ui');
        }

        $lbl = !empty($customLabel) ? $customLabel : $nomer;
        $exp = explode(".", trim($nomer));
        $prefix = $exp[0];

        $modul = "";
        $masterKey = $prefix;

        if (isset($ui[$prefix])) {
            $modul = isset($ui[$prefix]['modul']) ? $ui[$prefix]['modul'] : '';
            $masterKey = $prefix;
        } else if (!empty($ui)) {
            foreach ($ui as $mKey => $mSpec) {
                if (isset($mSpec['steps']) && is_array($mSpec['steps'])) {
                    foreach ($mSpec['steps'] as $stNum => $sSpec) {
                        if (isset($sSpec['target']) && $sSpec['target'] === $prefix) {
                            $modul = isset($mSpec['modul']) ? $mSpec['modul'] : '';
                            $masterKey = $mKey;
                            break 2;
                        }
                    }
                }
            }
        }

        if (!empty($modul)) {
            $url = base_url() . $modul . "/Transaksi/viewResume/" . $masterKey . "/" . trim($nomer);
        } else {
            $url = base_url() . "Transaksi/viewResume/" . trim($nomer);
        }

        $style = !empty($customStyle) ? $customStyle : "white-space:nowrap; text-decoration:underline;";
        $class = !empty($customClass) ? $customClass : "text-bold";

        return "<a href=\"javascript:void(0);\" onclick=\"showModal('" . $url . "','view resume for " . htmlspecialchars($nomer, ENT_QUOTES) . "')\" class='" . $class . "' style='" . $style . "' title='Buka Resume " . htmlspecialchars($nomer, ENT_QUOTES) . "'><i class='fa fa-external-link' style='font-size:10px; margin-right:2px;'></i> " . htmlspecialchars($lbl, ENT_QUOTES) . "</a>";
    }
}

?>