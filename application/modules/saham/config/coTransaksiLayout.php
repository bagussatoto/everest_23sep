<?php

$config["coTransaksiLayout"] = array(

    //  config pemindahan rekening kas (center)
    "6666" => array(
        "receiptTemplate" => array(
            1 => "template/1757r.html",
            2 => "template/1757.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "nomor_akta_notaris" => "Nomer Akta Notaris",
//                "cash_account_source__nama" => "bank asal",
//                "cash_account__label" => "bank tujuan",

            ),
            2 => array(
                "nomer" => "No",
                "nomer_top" => "No reg",
                "dtime" => "Date",
                "nomor_akta_notaris" => "Nomer Akta Notaris",
//                "cash_account_source__nama" => "bank asal",
//                "cash_account__label" => "bank tujuan",
            ),
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
            "produk_nama" => "item name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "nama",
//                "harga" => "transfer amount",
            ),
            2 => array(
                "produk_nama" => "nama",
//                "harga" => "transfer amount",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "nama",
//                "harga" => "transfer amount",
            ),
            2 => array(
                "nama" => "nama",
//                "harga" => "transfer amount",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "harga" => "grand total",
            ),
            2 => array(//                "harga" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                "saldo_awal" => "saldo awal",
                "nilai_source" => "nilai dipindahkan (-)",
                //                "jml" => "qty",
                "saldo_akhir" => "saldo akhir",
            ),
            2 => array(
                "saldo_awal" => "saldo awal",
                "nilai_source" => "nilai dipindahkan (-)",
                //                "jml" => "qty",
                "saldo_akhir" => "saldo akhir",
            ),
        ),
//        "receiptNumFields2" => array(
//            1 => array(
//                "saldo_awal" => "saldo awal",
//                "nilai_source" => "nilai dipindahkan",
//                //                "jml" => "qty",
//                "saldo_akhir" => "saldo akhir",
//            ),
//            2 => array(
//                "saldo_awal" => "saldo awal",
//                "nilai_source" => "nilai dipindahkan",
//                //                "jml" => "qty",
//                "saldo_akhir" => "saldo akhir",
//            ),
//        ),
        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
    ),
    "757" => array(
        "receiptTemplate" => array(
            1 => "application/template/757r.html",
            2 => "application/template/757.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
                "cash_account_source__label" => "origin account",
                "cash_account_target__label" => "target account",
                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                "cabang_nama" => "branch",
                "cash_account_source__label" => "origin account",
                "cash_account_target__label" => "target account",
                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
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
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "account name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                "harga" => "price",
            ),
            2 => array(
                "produk_nama" => "account name",
                //                "produk_ord_jml" => "qty",
                //                "satuan" => "uom",
                "harga" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "grand total",
            ),
            2 => array(
                "harga" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "transfer amount",
                //                "jml" => "qty",
            ),
            2 => array(
                "harga" => "transfer amount",
                //                "jml" => "qty",
            ),
        ),
        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(),
    ),

);