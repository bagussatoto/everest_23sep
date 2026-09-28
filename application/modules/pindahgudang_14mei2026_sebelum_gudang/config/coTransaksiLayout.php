<?php

/**
 * Created by PhpStorm.
 * User: chepy
 * Date: 10/21/2021
 * Time: 16:16 PM
 */

$config["coTransaksiLayout"] = array(
    "587" => array(
        "receiptTemplate" => array(
            1 => "template/587r.html",
            2 => "template/587ra.html",
            3 => "template/587.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "gudang_nama" => "warehouse",
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
            "gudang_nama" => "from warehouse",
            "gudang2_nama" => "to warehouse",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),
            4 => array(//                "hpp" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),
            4 => array(//                "hpp" => "grand total",
            ),
        ),

        "reportSumFields" => array(
            "cabang_id" => "cabang_nama",
            "gudang_id" => "gudang_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            1 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "687" => array(
        "receiptTemplate" => array(
            1 => "template/687r.html",
            2 => "template/687ra.html",
            3 => "template/687.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "gudang_nama" => "warehouse",
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
            "gudang_nama" => "from warehouse",
            "gudang2_nama" => "to warehouse",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),
            4 => array(//                "hpp" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
            3 => array(//                "hpp" => "grand total",
            ),
            4 => array(//                "hpp" => "grand total",
            ),
        ),

        "reportSumFields" => array(
            "cabang_id" => "cabang_nama",
            "gudang_id" => "gudang_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            1 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "1587" => array(
        "receiptTemplate" => array(
            1 => "template/1587r.html",
            2 => "template/1587r.html",
            3 => "template/1587r.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
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
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            4 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            4 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
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
            4 => array(//                "hpp" => "grand total",
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
    "1687" => array(
        "receiptTemplate" => array(
            1 => "template/1587r.html",
            2 => "template/1587r.html",
            3 => "template/1587r.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
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
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            4 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            4 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
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
            4 => array(//                "hpp" => "grand total",
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

    "2587" => array(
        "receiptTemplate" => array(
            1 => "template/1587r.html",
            2 => "template/1587r.html",
            3 => "template/1587r.html",
            4 => "template/1587r.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            4 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
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
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            4 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            4 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
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
            4 => array(//                "hpp" => "grand total",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        //        "receiptInword" => array("inWordInd" => "hpp"),
        "receiptInword" => array(
//            "1" => array(
//                "in_word" => array("inWordInd" => "hpp"),
//            ),
//            "2" => array(
//                "in_word" => array("inWordInd" => "hpp"),
//            ),
//            "3" => array(
//                "in_word" => array("inWordInd" => "hpp"),
//            ),
        ),
    ),
    "2687" => array(
        "receiptTemplate" => array(
            1 => "template/1587r.html",
            2 => "template/1587r.html",
            3 => "template/1587r.html",
            4 => "template/1587r.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            4 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
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
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            4 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            4 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
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
            4 => array(//                "hpp" => "grand total",
            ),
        ),

        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        //        "receiptInword" => array("inWordInd" => "hpp"),
        "receiptInword" => array(
//            "1" => array(
//                "in_word" => array("inWordInd" => "hpp"),
//            ),
//            "2" => array(
//                "in_word" => array("inWordInd" => "hpp"),
//            ),
//            "3" => array(
//                "in_word" => array("inWordInd" => "hpp"),
//            ),
        ),
    ),

    "5587" => array(
        "receiptTemplate" => array(
            1 => "template/587r.html",
            2 => "template/587ra.html",
            3 => "template/587.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "gudang_nama" => "warehouse",
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
            "gudang_nama" => "from warehouse",
            "gudang2_nama" => "to warehouse",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
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
            "cabang_id" => "cabang_nama",
            "gudang_id" => "gudang_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            1 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),
    "6687" => array(
        "receiptTemplate" => array(
            1 => "template/687r.html",
            2 => "template/687ra.html",
            3 => "template/687.html",

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
        "headerNota" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "gudang_nama" => "warehouse",
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
            "gudang_nama" => "from warehouse",
            "gudang2_nama" => "to warehouse",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
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
            "cabang_id" => "cabang_nama",
            "gudang_id" => "gudang_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            1 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
            2 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
            3 => array(//                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
    ),

    "1588" => array(
        "receiptTemplate" => array(
            1 => "template/1588r.html",
            2 => "template/1588r.html",
            3 => "template/1588r.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "gudang_nama" => "From Warehouse",
                "gudang2_nama" => "To Warehouse",
            ),
        ),
        "fixedSignatures" => array(),
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
        "receiptNumFields" => array(
            1 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
            4 => array(
                //                "hpp" => "price",
//                "harga" => "price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            4 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
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
            4 => array(//                "hpp" => "grand total",
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
);