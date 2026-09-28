<?php

$config["coTransaksiLayout"] = array(

    //pendapatan lain-lain
    "742" => array(
        "receiptTemplate" => array(
            1 => "template/9982r.html",
            //            2 => "application/template/675.html",
        ),
        "headerNota" => array(
            "pendapatan lain-lain" => array(
                "nomer" => "receipt no.",
                "dtime" => "date",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
                "pphps4_2" => "pph ps4(2) 20%",
                "kas_nilai" => "kas diterima",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
//                "pphps4_2" => "pph ps4(2)",
//                "kas_nilai" => "kas diterima",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),

);