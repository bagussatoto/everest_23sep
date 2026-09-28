<?php

$config["coTransaksiLayout"] = array(
    //hutang bank
    "444" => array(
        "receiptTemplate" => array(
            1 => "template/444r.html",
            2 => "template/444.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                //                "cabang2_nama" => "branch",
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
            "name" => "product name",
            "harga" => "price",
            "jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            //            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "bank name",
                //                "jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "nama" => "bank name",
                //                "jml" => "qty",
                //                "hpp" => "price",
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
                "harga" => "Total hutang",
            ),
            2 => array(
                "harga" => "Total hutang",
            ),
        ),
        "reportSumFields" => array(
            "cabang_id" => "cabang_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "harga",),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            "harga" => "Total hutang",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "No.",
            "nomer_top" => "PO No.",
            "dtime" => "Date",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "qty",
            //            "*" => "-",
            //            "-" => "-",
            "subtotal" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Total Amount",
            //            "ppn" => "VAT",
            //            "hpp_nppn" => "Grand Total",
            //
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
    //penambahan hutang kepemegang saham
    "447" => array(
        "receiptTemplate" => array(
            1 => "template/446r.html",
            2 => "template/446.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                //                "cabang2_nama" => "branch",
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
            "name" => "product name",
            "harga" => "price",
            "jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            //            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "name",
                //                "jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "nama" => "name",
                //                "jml" => "qty",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "subtotal" => "grand total",
            ),
            2 => array(
                "subtotal" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
                //                "nilai_bunga" => "nilai bunga<br>(bulanan)",
            ),
            2 => array(
                "harga" => "Hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
                //                "nilai_bunga" => "nilai bunga<br>(bulanan)",
            ),
        ),
        "reportSumFields" => array(
            "cabang_id" => "cabang_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "harga",),

        ),
    ),
    //penambahan modal pemegang saham
    "445" => array(
        "receiptTemplate" => array(
            1 => "template/444r.html",
            2 => "template/444.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                //                "cabang2_nama" => "branch",
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
            "name" => "product name",
            "harga" => "price",
            "jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            //            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "name",
                //                "jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "nama" => "name",
                //                "jml" => "qty",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "subtotal" => "grand total",
            ),
            2 => array(
                "subtotal" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Setor modal",
            ),
            2 => array(
                "harga" => "Setor modal",
            ),
        ),
        "reportSumFields" => array(
            "cabang_id" => "cabang_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "harga",),

        ),
    ),
    //penambahan hutang kepemegang saham
    "446" => array(
        "receiptTemplate" => array(
            1 => "template/446r.html",
            2 => "template/446.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
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
                //                "cabang2_nama" => "branch",
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
            "name" => "product name",
            "harga" => "price",
            "jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            //            "cabang2_nama" => "branch",
            "dtime" => "date",
        ),

        "receiptDetailFields" => array(
            1 => array(
                "nama" => "name",
                //                "jml" => "qty",
                //                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "nama" => "name",
                //                "jml" => "qty",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "subtotal" => "grand total",
            ),
            2 => array(
                "subtotal" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
                //                "nilai_bunga" => "nilai bunga<br>(bulanan)",
            ),
            2 => array(
                "harga" => "Hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
                //                "nilai_bunga" => "nilai bunga<br>(bulanan)",
            ),
        ),
        "reportSumFields" => array(
            "cabang_id" => "cabang_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "harga",),

        ),
    ),
    //  config pemindahan rekening kas (center)
    "1757" => array(
        "receiptTemplate" => array(
            1 => "template/1757r.html",
            2 => "template/1757.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cash_account_source__nama" => "bank asal",
                "cash_account__label" => "bank tujuan",

            ),
            2 => array(
                "nomer" => "No",
                "nomer_top" => "No reg",
                "dtime" => "Date",
                "cash_account_source__nama" => "bank asal",
                "cash_account__label" => "bank tujuan",
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
                "folders_nama" => "bank",
                "produk_nama" => "item name",
                "harga" => "transfer amount",
            ),
            2 => array(
                "folders_nama" => "bank",
                "produk_nama" => "item name",
                "harga" => "transfer amount",
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
    "4470" => array(
        "receiptTemplate" => array(
            1 => "template/671ro.html",
            2 => "template/671r.html",
            3 => "template/671.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            //            1 => array(
            //                "nomer" => "No",
            //                "dtime" => "Date",
            //                "shippingDate_value" => "Delivery Date",
            //                "top_nama" => "Term of Payment",
            //                "tos_nama" => "Term of Shipment",
            //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //            ),
            //            2 => array(
            //                "nomer" => "No.",
            //                "nomer_top" => "PO No.",
            //                "dtime" => "Date",
            //                "shippingDate_value" => "Delivery Date",
            //                "top_nama" => "Term of Payment",
            //                "tos_nama" => "Term of Shipment",
            //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //            ),
            //            3 => array(
            //                "nomer" => "Receipt  No.",
            //                "nomer_top" => "PO No.",
            //                "dtime" => "Date",
            //                //                "shippingDate_value" => "Delivery Date",
            //                "top_nama" => "Term of Payment",
            //                //                "tos_nama" => "Term of Shipment",
            //                //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //            ),
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
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "newPlafon" => "newPlafon",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    //  config pengurangan plafon rekening koran
    "4970" => array(
        "receiptTemplate" => array(
            1 => "template/671ro.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "name",
                "tlp_1" => "phone",
                "alamat_1" => "address",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "devlivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            //            1 => array(
            //                "nomer" => "No",
            //                "dtime" => "Date",
            //                "shippingDate_value" => "Delivery Date",
            //                "top_nama" => "Term of Payment",
            //                "tos_nama" => "Term of Shipment",
            //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //            ),
            //            2 => array(
            //                "nomer" => "No.",
            //                "nomer_top" => "PO No.",
            //                "dtime" => "Date",
            //                "shippingDate_value" => "Delivery Date",
            //                "top_nama" => "Term of Payment",
            //                "tos_nama" => "Term of Shipment",
            //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //            ),
            //            3 => array(
            //                "nomer" => "Receipt  No.",
            //                "nomer_top" => "PO No.",
            //                "dtime" => "Date",
            //                //                "shippingDate_value" => "Delivery Date",
            //                "top_nama" => "Term of Payment",
            //                //                "tos_nama" => "Term of Shipment",
            //                //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //            ),
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
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
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
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "newPlafon" => "amount",
            ),
            2 => array(//                "newPlafon" => "amount",
            ),
            //            3 => array(
            //                "newPlafon" => "amount",
            //            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),

);