<?php

$config["coTransaksiLayout"] = array(

    // PROJECT
    "5834" => array(
        "receiptTemplate" => array(
            1 => "template/585r.html",
            2 => "template/585.html",
            3 => "template/585r.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang2_nama" => "branch",
                "produkProjek__nama" => "project",
                "pihakProjekWorkorderSubGudangNama" => "work order",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                "cabang2_nama" => "branch",
                "produkProjek__nama" => "project",
                "pihakProjekWorkorderSubGudangNama" => "work order",
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
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID#1",
                "produk_nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                //                "hpp" => "price",
            ),
            2 => array(
//                "id" => "pID#2",
//                "barcode" => "sku",
//                "produk_nama" => array(
//                    "label" => "product name",
//                    "addKey" => "static_keterangan",
//                ),
//                "produk_kode" => "Product code",
//                "keterangan" => "part number",
//                "produk_ord_jml" => "qty",
//                "satuan" => "UOM",

                "biaya_nama" => "biaya",

                //                "hpp" => "price",
            ),
            3 => array(
                "id" => "pID#3",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                //                "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
                1 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                    "biaya_dasar_nama" => "supplies",
                    "current_stok" => "ready<br>stock",
                    "jml_wo" => "jumlah<br>diminta",
                    "jml_intransit" => "intransit",
                    "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                    "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                    "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
                ),
                2 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                    "biaya_dasar_nama" => "supplies",
                    "current_stok" => "ready<br>stock",
                    "jml_wo" => "jumlah<br>diminta",
                    "jml_intransit" => "intransit",
                    "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                    "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                    "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
                ),
                3 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                    "biaya_dasar_nama" => "supplies",
                    "current_stok" => "ready<br>stock",
                    "jml_wo" => "jumlah<br>diminta",
                    "jml_intransit" => "intransit",
                    "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                    "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                    "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
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
        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
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
            //            "in_word" => array("inWordInd" => "hpp",),

        ),
        "lockerStock" => "MdlLockerStock",
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "date approved",
                    "oleh_nama" => "approval",
                    "cabang_nama" => "dari",
                    "cabang2_nama" => " tujuan",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "produk nama",
                    "produk_kode" => "produk kode",
                    "cabang2_nama" => "cabang nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "request",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "cabang2_nama" => "cabang2_nama",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "supplier" => array(
                "label" => "supplier",
                "target" => "supplier",
                "srcKey" => "suppliers_id",
                "fields" => array(
                    "suppliers_nama" => "supplier",
                    "nomer_top" => "Transaksi PO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "PURCHASE",
                    "ord_sent_qty" => "DITERIMA",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),
        ),

        // =============================================
        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            //            "harga" => "unit price",
            //            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "date",
            "cabang_nama" => "cabang tujuan",
            //            "cabang2_nama" => "date",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            //            "-" => "-",
            //            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
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
            3 => array(
                "sign_1",
                "sign_2",
            ),
        ),
    ),
    "5856" => array(
        "receiptTemplate" => array(
            1 => "template/585r.html",
            2 => "template/585.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang2_nama" => "branch",

                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Distribution No.",
                "dtime" => "Date",
                "cabang_nama" => "branch",

                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
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
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array("inWordInd" => "hpp"),

        // =============================================
        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            //            "harga" => "unit price",
            //            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "date",
            "cabang_nama" => "cabang tujuan",
            //            "cabang2_nama" => "date",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            //            "-" => "-",
            //            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
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
    ),


    "984" => array(
        "receiptTemplate" => array(
            1 => "template/983.html",
            2 => "template/983.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang2_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                "cabang2_nama" => "branch",
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
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "product code",
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
        "receiptInword" => array("inWordInd" => "hpp"),
        "printLocation" => "Printing/viewReceiptReg/",
    ),
    "986" => array(
        "receiptTemplate" => array(
            1 => "template/985.html",
            2 => "template/985.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
                //                "cabang2_nama" => "branch",
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
                "cabang2_nama" => "branch",
                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),

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
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "product code",
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
        //        "receiptInword" => array("inWordInd" => "hpp"),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array("inWordInd" => "hpp"),

        // =============================================
        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            //            "harga" => "unit price",
            //            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "date",
            "cabang_nama" => "cabang tujuan",
            //            "cabang2_nama" => "date",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            //            "-" => "-",
            //            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
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
    ),


    "9834" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang2_nama" => "branch",
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
                "cabang2_nama" => "branch",
                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
        ),

        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID#1",
                "produk_nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                //                "hpp" => "price",
            ),
            2 => array(
//                "id" => "pID#2",
//                "barcode" => "sku",
//                "produk_nama" => array(
//                    "label" => "product name",
//                    "addKey" => "static_keterangan",
//                ),
//                "produk_kode" => "Product code",
//                "keterangan" => "part number",
//                "produk_ord_jml" => "qty",
//                "satuan" => "UOM",

                "biaya_nama" => "biaya",

                //                "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                "biaya_dasar_nama" => "supplies",
//                "current_stok" => "ready<br>stock",
//                "jml_wo" => "jumlah<br>diminta",
//                "jml_intransit" => "intransit",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DIKEMBALIKAN<BR>SAAT INI",
//                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
            ),
            2 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                "biaya_dasar_nama" => "supplies",
//                "current_stok" => "ready<br>stock",
//                "jml_wo" => "jumlah<br>diminta",
//                "jml_intransit" => "intransit",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DIKEMBALIKAN<BR>SAAT INI",
//                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
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
        "printLocation" => "Printing/viewReceiptReg/",
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
            //            "in_word" => array("inWordInd" => "hpp",),

        ),
        "lockerStock" => "MdlLockerStock",
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "date approved",
                    "oleh_nama" => "approval",
                    "cabang_nama" => "dari",
                    "cabang2_nama" => " tujuan",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "produk nama",
                    "produk_kode" => "produk kode",
                    "cabang2_nama" => "cabang nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "request",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "cabang2_nama" => "cabang2_nama",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "supplier" => array(
                "label" => "supplier",
                "target" => "supplier",
                "srcKey" => "suppliers_id",
                "fields" => array(
                    "suppliers_nama" => "supplier",
                    "nomer_top" => "Transaksi PO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "PURCHASE",
                    "ord_sent_qty" => "DITERIMA",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                    "ord_valid_qty" => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),
        ),

        // =============================================
        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            //            "harga" => "unit price",
            //            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "date",
            "cabang_nama" => "cabang tujuan",
            //            "cabang2_nama" => "date",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            //            "-" => "-",
            //            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
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
    ),
    "9856" => array(
        "receiptTemplate" => array(
            1 => "template/585r.html",
            2 => "template/585.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang2_nama" => "branch",

                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Distribution No.",
                "dtime" => "Date",
                "cabang_nama" => "branch",

                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID#1",
                "produk_nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                //                "hpp" => "price",
            ),
            2 => array(
//                "id" => "pID#2",
//                "barcode" => "sku",
//                "produk_nama" => array(
//                    "label" => "product name",
//                    "addKey" => "static_keterangan",
//                ),
//                "produk_kode" => "Product code",
//                "keterangan" => "part number",
//                "produk_ord_jml" => "qty",
//                "satuan" => "UOM",

                "biaya_nama" => "biaya",

                //                "hpp" => "price",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                "biaya_dasar_nama" => "supplies",
//                "current_stok" => "ready<br>stock",
//                "jml_wo" => "jumlah<br>diminta",
//                "jml_intransit" => "intransit",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DIKEMBALIKAN<BR>SAAT INI",
//                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
            ),
            2 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                "biaya_dasar_nama" => "supplies",
//                "current_stok" => "ready<br>stock",
//                "jml_wo" => "jumlah<br>diminta",
//                "jml_intransit" => "intransit",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DIKEMBALIKAN<BR>SAAT INI",
//                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
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
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array("inWordInd" => "hpp"),

        // =============================================
        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            //            "harga" => "unit price",
            //            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "fulldate" => "date",
            "cabang_nama" => "cabang tujuan",
            //            "cabang2_nama" => "date",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            //            "-" => "-",
            //            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
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
    ),
);