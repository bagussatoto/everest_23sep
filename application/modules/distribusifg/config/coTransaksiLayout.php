<?php

$config["coTransaksiLayout"] = array(
    "583" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583.html",
            3 => "template/583.html",
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
            3 => array(
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
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
            3 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
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
    "585" => array(
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
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
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


    "983" => array(
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
    "985" => array(
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
    ),
    "1983" => array(
        "receiptTemplate" => array(
            1 => "template/1983r.html",
            2 => "template/1983.html",
            3 => "template/1983.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
                "cabang2_nama" => "Return to",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
                "cabang2_nama" => "Return to",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
                "cabang2_nama" => "Return to",
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
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
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
            "cabang2_id" => "cabang2_nama",
        ),
        "receiptInword" => array("inWordInd" => "hpp"),
        "printLocation" => "Printing/viewReceiptReg/",

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
            "cabang2_nama" => "cabang pengirim",
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
    "1985" => array(
        "receiptTemplate" => array(
            1 => "template/1985r.html",
            2 => "template/1985.html",

            // 1 => "template/467.html",
            // 2 => "template/467.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang2_nama" => "Branch",
                "cabang_nama" => "Return to",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                "cabang2_nama" => "Branch",
                "cabang_nama" => "Return to",
            ),

        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
                // "sign_1" => array(
                //     "label" => ".test",
                // ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            5 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
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
                // "id" => "pID",
                "barcode" => "barcode",
                "produk_kode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                // "keterangan" => "part number",
                "satuan" => "uom",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                // "id" => "pID",
                "barcode" => "barcode",
                "produk_kode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                // "keterangan" => "part number",
                "satuan" => "uom",
                "produk_ord_jml" => "qty",
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
        "headerField" => "heTransaksi_layout",
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
            "cabang2_nama" => "cabang pengirim",
            "cabang_nama" => "cabang tujuan",

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
    //  config repack produk komposit
    "773" => array(
        "receiptTemplate" => array(
            1 => "template/776r.html",
            2 => "template/776.html",
            3 => "template/776.html",
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
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item source name",
                "stok" => "stok",
                "jml" => "qty",
                // "sisa" => "sisa",
                "satuan" => "uom",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receiptDetailFields3" => array(
            1 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            2 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
            3 => array(
                "nama" => "summary standard cost",
                "sub_nilai" => "amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),
        "receiptSumFields3" => array(
            1 => array(
                "total_cost" => "total amount",
            ),
            2 => array(
                "total_cost" => "total amount",
            ),
            3 => array(
                "total_cost" => "total amount",
            ),
        ),
        "allowPrint" => array(
            2 => array("size" => "normal"),
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


    // PROJECT
    "5833" => array(
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
                "id" => "pID",
                "produk_nama" => "product name",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "produk_nama" => "product name",
                "produk_kode" => "Product code",
                "no_part" => "part number",
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
    "5855" => array(
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
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "produk_nama" => "product name",
                "produk_kode" => "product code",
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
    //penjualan ke diri sendiri
    "5844" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583.html",
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
                "produk_nama" => "product name",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "produk_nama" => "product name",
                "produk_kode" => "Product code",
                "no_part" => "part number",
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
        "fixedNoteTop" => "<strong>Gunakan kode faktur pajak (040)<br>faktur pajak untuk dipakai sendiri</strong>",
    ),

    //koreksi harga persediaan
    "777_3" => array(
        "receiptTemplate" => array(
            1 => "template/585r.html",
            2 => "template/585.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",

                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Req koreksi.",
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
        "fixedNoteTop"=>"* Transaksi beresiko tinggi wajib diotorisasi owner/ direksi.<br> * Hanya berlaku untuk produk yang berasal dari stok opname.",
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
//            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptNumFields" => array(
            1 => array(
                "hpp" => "harga persediaan per unit",
                "harga" => "harga koreksi per unit",
            ),
            2 => array(
                "hpp" => "harga persediaan per unit",
                "harga" => "harga koreksi per unit",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array("inWordInd" => "harga"),

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