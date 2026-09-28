<?php

$config["coTransaksiLayout"] = array(
    "9999" => array(
        "receiptTemplate" => array(
            1 => "template/9999r.html",
            2 => "template/9999.html",
        ),
        "fixedElements" => array(
            1 => array(),
            2 => array(),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenisTrName" => "activity",
            "dtime" => "date",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabangName" => "branch",
            "dateTarget" => "date target",
        ),
        "receiptSubDetail"=>array(),
        "receiptDetailFields" => array(
            1 => array(
                "head_code" => "kode rekening",
                "produk_nama" => "rekening",
            ),
            2 => array(
                "head_code" => "kode rekening",
                "produk_nama" => "rekening",
            ),
        ),
        "receiptSumFields" => array(

        ),
        "receiptNumFields" => array(
            1 => array(
                "debet_prev" => "debet previous",
                "kredit_prev" => "kredit previous",
                "debet_curent_ori" => "debet awal",
                "kredit_curent_ori" => "kredit awal",
                "debet_adj" => "debet adj",
                "kredit_adj" => "kredit adj",
                "debet_after" => "debet akhir",
                "kredit_after" => "kredit akhir",
            ),
            2 => array(
                "debet_prev" => "debet previous",
                "kredit_prev" => "kredit previous",
                "debet_curent_ori" => "debet awal",
                "kredit_curent_ori" => "kredit awal",
                "debet_adj" => "debet adj",
                "kredit_adj" => "kredit adj",
                "debet_after" => "debet akhir",
                "kredit_after" => "kredit akhir",
            ),
        ),
        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
    ),
    "9990" => array(
        "receiptTemplate" => array(
            1 => "template/9999r.html",
            2 => "template/9999.html",
        ),
        "fixedElements" => array(
            1 => array(),
            2 => array(),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenisTrName" => "activity",
            "dtime" => "date",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabangName" => "branch",
//            "dateTarget" => "date target",
        ),
        "receiptSubDetail"=>array(),
        "receiptDetailFields" => array(
            1 => array(
                "head_code" => "kode rekening",
                "produk_nama" => "rekening",
            ),
            2 => array(
                "head_code" => "kode rekening",
                "produk_nama" => "rekening",
            ),
        ),
        "receiptSumFields" => array(

        ),
        "receiptNumFields" => array(
            1 => array(
                "debet_prev" => "debet previous",
                "kredit_prev" => "kredit previous",
                "debet_curent_ori" => "debet awal",
                "kredit_curent_ori" => "kredit awal",
                "debet_adj" => "debet adj",
                "kredit_adj" => "kredit adj",
                "debet_after" => "debet akhir",
                "kredit_after" => "kredit akhir",
            ),
            2 => array(
                "debet_prev" => "debet previous",
                "kredit_prev" => "kredit previous",
                "debet_curent_ori" => "debet awal",
                "kredit_curent_ori" => "kredit awal",
                "debet_adj" => "debet adj",
                "kredit_adj" => "kredit adj",
                "debet_after" => "debet akhir",
                "kredit_after" => "kredit akhir",
            ),
        ),
        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
    ),
);