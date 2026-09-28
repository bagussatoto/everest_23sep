<?php

$config["coTransaksiLayout"] = array(
    "1334" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "barcode" => "barcode",
                "nama" => "produk sumber konversi",
//                "produk_kode" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => "produk sumber konversi",
//                "produk_kode" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            //            3 => array(
            //                "produk_nama" => "product source name",
            //                "produk_ord_jml" => "qty",
            //                "satuan" => "uom",
            ////                "hpp" => "price",
            //            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "barcode" => "barcode",
                "produk_nama" => "produk hasil konversi",
//                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "barcode",
                "produk_nama" => "produk hasil konversi",
//                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            //            3 => array(
            //                "produk_nama" => "product target name",
            //                "produk_ord_jml" => "qty",
            //                "satuan" => "uom",
            ////                "hpp" => "price",
            //            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        //        "receiptInword" => array("inWordInd" => "hpp"),

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    // konversi supplies ke produk (center)
    "2334" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "qty" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "qty" => "qty",
                "satuan" => "uom",
            ),

        ),
        "receiptDetailFields2" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    // konversi produk ke supplies (center)
    "2336" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "nama" => "product name",
                "produk_kode" => "product code",
                "qty" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "qty" => "qty",
                "satuan" => "uom",
            ),

        ),
        "receiptDetailFields2" => array(
            1 => array(
                "produk_nama" => "supplies name",
                "produk_kode" => "supplies code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => "supplies name",
                "produk_kode" => "supplies code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    // konversi produk ke supplies (branch)
    "2337" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "nama" => "product name",
                "produk_kode" => "product code",
                "qty" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "qty" => "qty",
                "satuan" => "uom",
            ),

        ),
        "receiptDetailFields2" => array(
            1 => array(
                "produk_nama" => "supplies name",
                "produk_kode" => "supplies code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => "supplies name",
                "produk_kode" => "supplies code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    //  config konversi grade
    "334" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "produk_nama" => "product source name",
                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => "product source name",
                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            //            3 => array(
            //                "produk_nama" => "product source name",
            //                "produk_ord_jml" => "qty",
            //                "satuan" => "uom",
            ////                "hpp" => "price",
            //            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "produk_nama" => "product target name",
                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => "product target name",
                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            //            3 => array(
            //                "produk_nama" => "product target name",
            //                "produk_ord_jml" => "qty",
            //                "satuan" => "uom",
            ////                "hpp" => "price",
            //            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "price",
                //                "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "price",
                //                "harga" => "price",
            ),
        ),

        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        //        "receiptInword" => array("inWordInd" => "hpp"),

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),

    "335" => array(
        "receiptTemplate" => array(
            1 => "template/335r.html",
            2 => "template/335.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "produk_nama" => "item name source",
                //                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => "item name source",
                //                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "produk_nama" => "item name target",
                //                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => "item name target",
                //                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        //        "receiptInword" => array("inWordInd" => "hpp"),

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    // konversi supplies ke produk (branch)
    "2335" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "qty" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "qty" => "qty",
                "satuan" => "uom",
            ),

        ),
        "receiptDetailFields2" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),

    "1337" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "nama" => "item source name",
//                "produk_kode" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "nama" => "item source name",
//                "produk_kode" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "produk_nama" => "item target name",
//                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => "item target name",
//                "produk_kode" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),

    "7620" => array(
        "receiptTemplate" => array(
            1 => "template/7620r.html",
            2 => "template/7620.html",
            //                3 => "application/template/762.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            //            "suppliers_nama" => "Supplier",
            //            "tlp_1" => "phone",
            //            "alamat_1" => "address",
            //            "dtime_jatuh_tempo" => "jatuh tempo",
            //            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "category_expense_nama" => "Category",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "category_expense_nama" => "Category",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            //            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            //            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
            "cabang_nama" => "Branch",
            //            "category_expense_nama" => "Category",
            //            "cabang_nama" => "Detail",
            //            "gudang_nama" => "Warehouse",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumDetailFields" => array(
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
            2 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
            3 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            3 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "harga",),
        //
        //        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            //            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            "harga" => "unit price",
            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "Date",

        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedNote" => "Nilai yang tampil disini adalah estimasi pembiayaan supplies. Nilai yang sebenarnya setelah dilakukan otorisasi pembiayaan supplies.",
    ),
    // //supplies to aset baru
    "7622" => array(
        "receiptTemplate" => array(
            1 => "template/7620r.html",
            2 => "template/7620.html",
            //                3 => "template/762.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            //            "suppliers_nama" => "Supplier",
            //            "tlp_1" => "phone",
            //            "alamat_1" => "address",
            //            "dtime_jatuh_tempo" => "jatuh tempo",
            //            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "pihakName" => "Category",
                "pihakMainRulesName" => "Aset",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "pihakName" => "Category",
                "pihakMainRulesName" => "Aset",
            ),
            3 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "pihakName" => "Category",
                "pihakMainRulesName" => "Aset",
            ),
            4 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                //                "dtime_required" => "Required Date",
                "cabang_nama" => "branch",
                "pihakName" => "Category",
                "pihakMainRulesName" => "Aset",
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "item name",
            //            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            //            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "receipt no.",
            "dtime" => "date",
            "cabang_nama" => "Branch",
            //            "category_expense_nama" => "Category",
            //            "cabang_nama" => "Detail",
            //            "gudang_nama" => "Warehouse",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                //                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            4 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptDetailFields2" => array(
            2 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                //                "nett" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                //                "nett" => "price",
                //            "ppn" => "ppn",
            ),
            4 => array(
                "pihakMainName" => "category",
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
                "label" => "label",
                "serial_no" => "serial",
                "kode" => "code",
                "nett" => "price",
                "ppn" => "ppn",
            ),
        ),
        "receiptSumDetailFields" => array(
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),

        "receiptSumFields" => array(
            1 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
            2 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
            3 => array(
                //            "hpp" => "amount",
                //            "ppn" => "VAT",
                "harga" => "grand total",
            ),
            4 => array(
                "hpp" => "amount",
                "ppn" => "VAT",
                "nett2" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            4 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga"),
            ),
        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            //            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            "harga" => "unit price",
            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "Date",

        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
        ),
        "reviewAddRows" => array(
            //            "top__nama" => "pembayaran",
            //            "dp" => "downpayment",
            //            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign" => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedNote" => "Nilai yang tampil disini adalah estimasi ",
    ),

    // //supplies to aset baru
    // "7622" => array(
    //     "receiptTemplate" => array(
    //         1 => "template/7620r.html",
    //         2 => "template/7620.html",
    //         //                3 => "template/762.html",
    //     ),
    //     "headerNota" => array(
    //         "dtime" => "date",
    //         //            "suppliers_nama" => "Supplier",
    //         //            "tlp_1" => "phone",
    //         //            "alamat_1" => "address",
    //         //            "dtime_jatuh_tempo" => "jatuh tempo",
    //         //            "pembayaran" => "payment method",
    //     ),
    //     "fixedElements" => array(
    //         1 => array(
    //             "nomer" => "No.",
    //             "dtime" => "Date",
    //             //                "dtime_required" => "Required Date",
    //             "cabang_nama" => "branch",
    //             "pihakName" => "Category",
    //             "pihakMainRulesName" => "Aset",
    //         ),
    //         2 => array(
    //             "nomer" => "No.",
    //             "dtime" => "Date",
    //             //                "dtime_required" => "Required Date",
    //             "cabang_nama" => "branch",
    //             "pihakName" => "Category",
    //             "pihakMainRulesName" => "Aset",
    //         ),
    //         3 => array(
    //             "nomer" => "No.",
    //             "dtime" => "Date",
    //             //                "dtime_required" => "Required Date",
    //             "cabang_nama" => "branch",
    //             "pihakName" => "Category",
    //             "pihakMainRulesName" => "Aset",
    //         ),
    //         4 => array(
    //             "nomer" => "No.",
    //             "dtime" => "Date",
    //             //                "dtime_required" => "Required Date",
    //             "cabang_nama" => "branch",
    //             "pihakName" => "Category",
    //             "pihakMainRulesName" => "Aset",
    //         ),
    //     ),
    //     "headerTables" => array(
    //         "produk_nama" => "item name",
    //         //            "produk_ord_hrg" => "price",
    //         "produk_ord_jml" => "jumlah",
    //         //            "sub_total" => "sub total",
    //     ),
    //     "receiptMainFields" => array(
    //         "jenis_label" => "activity",
    //         "nomer" => "receipt no.",
    //         "dtime" => "date",
    //         "cabang_nama" => "Branch",
    //         //            "category_expense_nama" => "Category",
    //         //            "cabang_nama" => "Detail",
    //         //            "gudang_nama" => "Warehouse",
    //     ),
    //     "receiptDetailFields" => array(
    //         1 => array(
    //             "produk_nama" => "item name",
    //             "produk_ord_jml" => "qty",
    //             "satuan" => "satuan",
    //             "harga" => "price",
    //             //            "ppn" => "ppn",
    //         ),
    //         2 => array(
    //             "produk_nama" => "item name",
    //             "produk_ord_jml" => "qty",
    //             "satuan" => "satuan",
    //             "harga" => "price",
    //             //            "ppn" => "ppn",
    //         ),
    //         3 => array(
    //             "produk_nama" => "item name",
    //             "produk_ord_jml" => "qty",
    //             "satuan" => "satuan",
    //             //                "harga" => "price",
    //             //            "ppn" => "ppn",
    //         ),
    //         4 => array(
    //             "produk_nama" => "item name",
    //             "produk_ord_jml" => "qty",
    //             "satuan" => "satuan",
    //             "harga" => "price",
    //             //            "ppn" => "ppn",
    //         ),
    //     ),
    //     "receiptDetailFields2" => array(
    //         2 => array(
    //             "pihakMainName" => "category",
    //             "produk_nama" => "aset name",
    //             "produk_ord_jml" => "qty",
    //             "label" => "label",
    //             "serial_no" => "serial",
    //             "kode" => "code",
    //             //                "nett" => "price",
    //             //            "ppn" => "ppn",
    //         ),
    //         3 => array(
    //             "pihakMainName" => "category",
    //             "produk_nama" => "aset name",
    //             "produk_ord_jml" => "qty",
    //             "label" => "label",
    //             "serial_no" => "serial",
    //             "kode" => "code",
    //             //                "nett" => "price",
    //             //            "ppn" => "ppn",
    //         ),
    //         4 => array(
    //             "pihakMainName" => "category",
    //             "produk_nama" => "aset name",
    //             "produk_ord_jml" => "qty",
    //             "label" => "label",
    //             "serial_no" => "serial",
    //             "kode" => "code",
    //             "nett" => "price",
    //             "ppn" => "ppn",
    //         ),
    //     ),
    //     "receiptSumDetailFields" => array(
    //         2 => array(
    //             "sub_harga" => "Total Price",
    //         ),
    //     ),
    //
    //     "receiptSumFields" => array(
    //         1 => array(
    //             //            "hpp" => "amount",
    //             //            "ppn" => "VAT",
    //             "harga" => "grand total",
    //         ),
    //         2 => array(
    //             //            "hpp" => "amount",
    //             //            "ppn" => "VAT",
    //             "harga" => "grand total",
    //         ),
    //         3 => array(
    //             //            "hpp" => "amount",
    //             //            "ppn" => "VAT",
    //             "harga" => "grand total",
    //         ),
    //         4 => array(
    //             "hpp" => "amount",
    //             "ppn" => "VAT",
    //             "nett2" => "grand total",
    //         ),
    //     ),
    //     "receiptNumFields" => array(
    //         1 => array(
    //             //                "hpp" => "hpp",
    //             "harga" => "price",
    //             //            "ppn" => "VAT",
    //         ),
    //         2 => array(
    //             //                "hpp" => "hpp",
    //             "harga" => "price",
    //             //            "ppn" => "VAT",
    //         ),
    //         3 => array(
    //             //                "hpp" => "hpp",
    //             //                "harga" => "price",
    //             //            "ppn" => "VAT",
    //         ),
    //         4 => array(
    //             //                "hpp" => "hpp",
    //             "harga" => "price",
    //             //            "ppn" => "VAT",
    //         ),
    //     ),
    //     "reportSumFields" => array(
    //         "suppliers_id" => "suppliers_nama",
    //     ),
    //     "printLocation" => "Printing/viewReceipt/",
    //     "receiptInword" => array(
    //         "1" => array(
    //             "in_word" => array("inWordInd" => "harga"),
    //         ),
    //         "2" => array(
    //             "in_word" => array("inWordInd" => "harga"),
    //         ),
    //         "3" => array(
    //             "in_word" => array("inWordInd" => "harga"),
    //         ),
    //     ),
    //
    //     "reviewDetailCompactListsLabel" => array(
    //         "nama" => "Description",
    //         //            "produk_kode" => "part no",
    //         "qty" => "qty",
    //         "satuan" => "UOM",
    //
    //         "harga" => "unit price",
    //         "subtotal" => "total price",
    //
    //     ),
    //     "reviewMainCompactListsLabel" => array(
    //         "nomer" => "Nomer",
    //         "fulldate" => "Date",
    //
    //     ),
    //     "reviewCompactListDetailSum" => array(
    //         "qty" => "qty",
    //         "*" => "-",
    //         "-" => "-",
    //         "harga" => "grand total",
    //     ),
    //     "reviewCompactListSum" => array(
    //         "harga" => "Total Amount",
    //         //            "ppn" => "VAT",
    //         //            "hpp_nppn" => "Grand Total",
    //     ),
    //     "reviewAddRows" => array(
    //         //            "top__nama" => "pembayaran",
    //         //            "dp" => "downpayment",
    //         //            "paymentMethod" => "paymentMethod",
    //     ),
    //     "reviewSign" => array(
    //         1 => array(
    //             "sign_1",
    //         ),
    //         2 => array(
    //             "sign_1",
    //             "sign_2",
    //         ),
    //     ),
    //     "fixedNote" => "Nilai yang tampil disini adalah estimasi ",
    // ),
    // ---------------------------------


    "1336" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",
            3 => "template/334.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
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
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
//                "produk_nama" => array(
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
//                "produk_ord_jml" => "qty",
                "jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        //        "receiptInword" => array("inWordInd" => "hpp"),

        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "336" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "qty" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",
        //        "receiptInword" => array("inWordInd" => "hpp"),

        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),

    "1339" => array(
        "receiptTemplate" => array(
            1 => "template/334r.html",
            2 => "template/334.html",
            3 => "template/334.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(),
            2 => array(),
            3 => array(),
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
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",

                "stok" => "tersedia",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
                "sisa_dipakai"=>"sisa potongan",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",

                "stok" => "tersedia",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
                "sisa_dipakai"=>"sisa potongan",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",

                "stok" => "tersedia",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
                "sisa_dipakai"=>"sisa potongan",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),

        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptProduksi/",

        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
);