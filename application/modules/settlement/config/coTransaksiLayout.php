<?php

$config["coTransaksiLayout"] = array(
    // settlement uang muka dari konsumen
    "7761" => array(
        "receiptTemplate" => array(
            1 => "template/759.html",
        ),
        "headerNota" => array(
            //            "pusat" => array(
            //                "centerDetails__nama" => "name",
            //                "cash_account_source__label" => "sumber",
            //                "cash_account_target__label" => "bank pusat",
            //            ),

            // "dtime" => "date",
            // "placeName" => "Branch",
            // "tlp_1" => "phone",
            // "alamat_1" => "address",
            // "dtime_jatuh_tempo" => "jatuh tempo",
            // "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                //                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "cash_account_source__label" => "sumber dana",
                "cash_account_target__label" => "bank pusat",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
        ),
        "headerTables" => array(
            "extern2_nama" => "customer",
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "extern2_nama" => "customer",
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "receivable amount",
                "creditAmount" => "paid using credit",
                "nilai_entry" => "paid using cash account",
                "nilai_bayar" => "total amount of payment",
                "new_sisa" => "remain receivable (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(),
    ),
    "7760" => array(
        "receiptTemplate" => array(
            1 => "template/759.html",
            2 => "template/759.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customers_nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                //                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "cash_account_source__label" => "sumber dana",
                "cash_account_target__label" => "bank pusat",

                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No.",
                //                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "cash_account_source__label" => "sumber dana",
                "cash_account_target__label" => "bank pusat",

                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
        ),
        "headerTables" => array(
            "extern2_nama" => "customer",
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "receipt no.",
            "dtime" => "date",
            "cash_account_source__label" => "cash account source",
            "cashMethode__label" => "target method account",
            "cash_account_target__label" => "cash account target",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "extern2_nama" => "customer",
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
//                "extern2_nama" => "customer",
                "customers_nama" => "customer",
                "produk_nama" => "product name",
                //                "produk_ord_jml" => "qty",
                //                "satuan"         => "satuan",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
            2 => array(
                "sisa" => "due remain",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
            2 => array(
                "sisa" => "due remain",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "receivable amount",
                "creditAmount" => "paid using credit",
                "nilai_entry" => "paid using cash account",
                "nilai_bayar" => "total amount of payment",
                "new_sisa" => "remain receivable (from list)",
            ),
            2 => array(
                "nilai_bayar" => "receivable amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry"  => "paid using cash account",
                //                "nilai_bayar"  => "total amount of payment",
                //                "new_sisa"     => "remain receivable (from list)",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "product name",
            "sisa" => "due remain",
            "tagihan" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            //            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            "tagihan" => "grand total",
        ),
        "reviewCompactListSum" => array(
            //            "harga" => "Amount",
            //            "ppn" => "VAT",
            //            "nett" => "Grand Total",
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