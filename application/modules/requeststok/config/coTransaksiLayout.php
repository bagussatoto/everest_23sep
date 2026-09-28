<?php

$config["coTransaksiLayout"] = array(
    // config pr (request)
    "761" => array(
        "receiptTemplate" => array(
            1 => "template/761ro.html",
            2 => "template/761r.html",
            3 => "template/761ros.html",
            4 => "template/761.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            //            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            //            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            //            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                "stok" => "Stok",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
                "stok" => "Stok",
            ),
            3 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
            4 => array(),
            //            "hpp" => "amount",
            //            "ppn" => "vat",
            //            "nett" => "grand total",
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
            4 => array(),
            //            "hpp" => "amount",
            //            "ppn" => "vat",
            //            "nett" => "grand total",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

    ),
    //config supplies request distribution
    "763" => array(
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
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product code",
                "produk_ord_jml" => "qty",
                "stok" => "stock",
                "satuan" => "uom",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
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
        //        "receiptInword" => array("inWordInd" => "hpp"),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
        ),
        "printLocation" => "Printing/viewReceipt/",
    ),
    "9763" => array(
        "receiptTemplate" => array(
            1 => "template/961r.html",
            2 => "template/961r.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "transaksiDatas__nomer" => "PO No.",
//                "shippingDate_value" => "Delivery Date",
//                "top_nama" => "Term of Payment",
//                "tos_nama" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
//                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "transaksiDatas__nomer" => "PO No.",
//                "shippingDate_value" => "Delivery Date",
//                "top_nama" => "Term of Payment",
//                "tos_nama" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
//                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
//                "harga" => "Unit Price",
//                                "disc_percent" => "disc (%)",
//                                "disc" => "disc (IDR)",
//                                "ppn" => "VAT",
//                            "avail" => "current stock",
            ),
            2 => array(
//                "harga" => "Unit Price",
//                                "disc_percent" => "disc (%)",
//                                "disc" => "disc (IDR)",
//                                "ppn" => "VAT",
//                            "avail" => "current stock",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "amount",
                "ppn" => "VAT",
                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "amount",
                "ppn" => "VAT",
                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
//            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),

        "receiptInword" => array(
            1 => array(//                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),


    ),
);