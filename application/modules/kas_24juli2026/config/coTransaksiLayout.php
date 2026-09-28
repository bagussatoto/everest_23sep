<?php

$config["coTransaksiLayout"] = array(
    //  config penyetoran
    "759" => array(
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
    "758" => array(
        "receiptTemplate" => array(
            1 => "template/758r.html",
            2 => "template/758.html",
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
                "extern2_nama" => "customer",
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
    // config uang muka
    "4643" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
                //                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "pihakName" => "Vendor",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by*",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
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
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            //            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "UM",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
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

        //-------------
        "receiptEfakturFields" => array(
            2 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_ppn" => "DPP",
                "ppn_realisasi" => "PPN",
            ),
        ),

    ),
    // config uang muka
    "4644" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
//            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
                //                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "pihakName" => "Vendor",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by*",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            //            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "UM",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
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

        //-------------
        "receiptEfakturFields" => array(
            2 => array(
                "eFaktur" => "Faktur PPN",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_ppn" => "DPP",
                "ppn_realisasi" => "PPN",
            ),
        ),

    ),

    // config uang muka
    "4656" => array(
        "receiptTemplate" => array(
//            1 => "template/464r.html",
            1 => "template/582spo_mod.html",
//            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
                //                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "pihakName" => "Vendor",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by*",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            //            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "UM",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
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

        //-------------
        "receiptEfakturFields" => array(
            2 => array(
                "eFaktur" => "Faktur PPN",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_ppn" => "DPP",
                "ppn_realisasi" => "PPN",
            ),
        ),

    ),

    "9994" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
                //                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "pihakName" => "Vendor",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by*",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
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
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            //            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "UM",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
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

        //-------------
        "receiptEfakturFields" => array(
            2 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_ppn" => "DPP",
                "ppn_realisasi" => "PPN",
            ),
        ),

    ),
    // config uang muka
    "464" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
                //                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "pihakName" => "Vendor",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by*",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            //            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "UM",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
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

        //-------------
        "receiptEfakturFields" => array(
            2 => array(
                "eFaktur" => "Faktur PPN",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_ppn" => "DPP",
                "ppn_realisasi" => "PPN",
            ),
        ),

    ),
    // config uang muka
    "465" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
                //                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "pihakName" => "Vendor",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by*",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            //            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "UM",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
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

        //-------------
        "receiptEfakturFields" => array(
            2 => array(
                "eFaktur" => "Faktur PPN",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_ppn" => "DPP",
                "ppn_realisasi" => "PPN",
            ),
        ),

    ),
    //  uang muka valas
    "4466" => array(
        "receiptTemplate" => array(
            1 => "template/4466r.html",
            2 => "template/4466.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
                //                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos__name" => "Term of Shipment",
                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".petugas",
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
            //            3 => array(
            //                "vendor" => array(
            //                    "label" => ".Confirmed & Acknowledged by",
            //                    "contents" => "vendorDetails_nama",
            //                    "footers" => "--",
            //                    //                "caption_department" => "",
            //                ),
            //            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            //            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(//                "harga" => "Unit Price",
            ),
            2 => array(//                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "pihak2Name" => "Exchange",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "pihak2Name" => "Exchange",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Amount",
                //                "valas_nilai_bayar" => "stock valas",
                //                "valas_kurang" => "kekurangan valas",
                //                "kurs_actual" => "kurs saat ini",
                //                "valas_kurang_nilai" => "nilai pembelian valas",
            ),
            2 => array(
                "harga" => "Amount",
                //                "valas_nilai_bayar" => "stock valas",
                //                "valas_kurang" => "kekurangan valas",
                //                "kurs_actual" => "kurs saat ini",
                //                "valas_kurang_nilai" => "nilai pembelian valas",
            ),
        ),
        "receipSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "valas_nilai_bayar" => "stock valas",
                "valas_kurang" => "kekurangan valas",
                "kurs_actual" => "kurs saat ini",
                "biaya_lain_lain" => "biaya lain-lain",
                "valas_kurang_nilai" => "nilai pembelian valas",
                "biaya_transfer" => "biaya transfer",
                "biaya_lain_lain_novalas" => "biaya lain-lain (tidak berkaitan dengan valas)",
                "total_bayar" => "total bayar",
            ),
            2 => array(
                "harga" => "Amount",
                "valas_nilai_bayar" => "stock valas",
                "valas_kurang" => "kekurangan valas",
                "kurs_actual" => "kurs saat ini",
                "biaya_lain_lain" => "biaya lain-lain",
                "valas_kurang_nilai" => "nilai pembelian valas",
                "biaya_transfer" => "biaya transfer",
                "biaya_lain_lain_novalas" => "biaya lain-lain (tidak berkaitan dengan valas)",
                "total_bayar" => "total bayar",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "UM",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
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

    ),
    //uang muka faktur gabungan
    "4645" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
//            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Supplier",
            "tlp_1" => "phone",
            "alamat_1" => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
                //                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "pihakName" => "Vendor",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by*",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                    //                "caption_department" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".SUPPLIER/VENDOR",
                    "contents" => "vendorDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "",
                    "footers" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            //            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "dpp" => "harga",
                "dpp_pengganti" => "DPP pengganti",
                "ppn_pengganti" => "ppn",
            ),
            2 => array(
                "dpp" => "harga",
                "dpp_pengganti" => "DPP pengganti",
                "ppn_pengganti" => "ppn",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Po Number",
//                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "dpp" => "total",
                "dpp_pengganti" => "dpp pengganti",
                "ppn_pengganti" => "Ppn",
                "harga" => "Total Amount",
            ),
            2 => array(
                "dpp" => "total",
                "dpp_pengganti" => "dpp pengganti",
                "ppn_pengganti" => "Ppn",
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "oleh_nama" => "salesman",
                    "customers_nama" => "customer",
                    "print_label" => "tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "ord_qty" => "produk_ord_jml",
                    "ord_valid_qty" => "valid_qty",
                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "UM",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
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

        //-------------
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Faktur PPN",
                "dateFaktur" => "Tanggal Faktur",
//                "satuan" => "Jumlah",
                "dpp_pengganti" => "DPP",
                "ppn_pengganti" => "PPN",
            ),
        ),

    ),
    //transfer kas to brance
    "453" => array(
        "receiptTemplate" => array(
            1 => "template/681r.html",
            2 => "template/466.html",
            3 => "template/423.html",
        ),
        "headerNota" => array(
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
            1 => array(
                "nomer" => "No PO",
                "dtime" => "Date",
                "cash_account_source__nama" => "cash account source",
                "cash_account_target__nama" => "cash account target",
                "pihakName" => "Branch",
            ),
            2 => array(
                "nomer" => "No PO",
                //                "nomer_top" => "No pre PO",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                //                "paymentMethod_name" => "Payment method",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "pihakMainRulesName" => "Vat"
            ),
            3 => array(
                "nomer" => "GRN Number",
                "nomers_prev" => "PO Number",
                "nomer_top" => "PRE-PO Number",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "pihakMainRulesName" => "Vat"
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
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "name",
            "produk_ord_jml" => "jumlah",
            "produk_ord_hrg" => "amount",

            "sub_total" => "sub amount",
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
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receipCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Unit Price",
                "ppn" => "VAT",
            ),
            3 => array(
                //                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //                "harga_other" => "total amount",
                //                "dpp_vat"     => "DPP VAT",
                //                "ppn"         => "VAT",
                //                "nett"        => "grand total",
            ),
            2 => array(
                //                "harga_other" => "total amount",
                //                "dpp_vat"     => "DPP VAT",
                //                "ppn"         => "VAT",
                //                "nett"        => "grand total",
            ),
            3 => array(),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
                //                "non_ppn" => "Non PPN<br>PPN (-)",
                //                "other" => "other (+)",
            ),
            2 => array(
                "harga" => "Price",
                //                "other" => "other (+)",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            1 => "SAN/F/PUR002/R00",
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    "454" => array(
        "receiptTemplate" => array(
            1 => "template/681r.html",
            2 => "template/466.html",
            3 => "template/423.html",
        ),
        "headerNota" => array(
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
            1 => array(
                "nomer" => "No PO",
                "dtime" => "Date",
                "cash_account_source__nama" => "cash account source",
                "cash_account_target__nama" => "cash account target",
                "pihakName" => "Branch",
            ),
            2 => array(
                "nomer" => "No PO",
                //                "nomer_top" => "No pre PO",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                //                "paymentMethod_name" => "Payment method",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "pihakMainRulesName" => "Vat"
            ),
            3 => array(
                "nomer" => "GRN Number",
                "nomers_prev" => "PO Number",
                "nomer_top" => "PRE-PO Number",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "pihakMainRulesName" => "Vat"
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
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "name",
            "produk_ord_jml" => "jumlah",
            "produk_ord_hrg" => "amount",

            "sub_total" => "sub amount",
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
                "produk_nama" => "name",
                "harga" => "price",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                "harga" => "price",
            ),
            3 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receipCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Unit Price",
                "ppn" => "VAT",
            ),
            3 => array(
                //                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //                "harga_other" => "total amount",
                //                "dpp_vat"     => "DPP VAT",
                //                "ppn"         => "VAT",
                //                "nett"        => "grand total",
            ),
            2 => array(
                //                "harga_other" => "total amount",
                //                "dpp_vat"     => "DPP VAT",
                //                "ppn"         => "VAT",
                //                "nett"        => "grand total",
            ),
            3 => array(),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
                //                "non_ppn" => "Non PPN<br>PPN (-)",
                //                "other" => "other (+)",
            ),
            2 => array(
                "harga" => "Price",
                //                "other" => "other (+)",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            1 => "SAN/F/PUR002/R00",
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),
    // uang muka dari konsumen
    "4464_OLD_" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customerDetails__nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
            "cash_account__label" => "cash account",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
//            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customerDetails__nama" => "customer",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
//        "fixedFieldHold" => array(
//            "transaksi" => array(
//                "label" => "transaksi",
//                "target" => "transaksi",
//                "srcKey" => "id_master",
//                "fields" => array(
//                    "nomer_top" => "nomer",
//                    "dtime" => "approved",
//                    "oleh_nama" => "salesman",
//                    "customers_nama" => "customer",
//                    "print_label" => "tool",
//                ),
//                "loop" => array(),
//            ),
//            "produk" => array(
//                "label" => "produk",
//                "target" => "produk",
//                "srcKey" => "produk_id",
//                "fields" => array(
//                    "produk_nama" => "product",
//                    "customers_nama" => "customers nama",
//                    "nomer_top" => "Transaksi",
//                    "ord_qty" => "Order",
//                    "ord_sent_qty" => "Dikirim",
//                    "ord_valid_qty" => "Outstanding",
//                    "avail_qty" => "Tersedia",
//                    "print_label" => "tool",
//
//                ),
//                "loop" => array(
//                    "customers_nama" => "customers_nama",
//                    "nomer_top" => "nomer_top",
//                    "ord_qty" => "produk_ord_jml",
//                    "ord_valid_qty" => "valid_qty",
//                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
//                ),
//
//            ),
//            "customer" => array(
//                "label" => "customer",
//                "target" => "customer",
//                "srcKey" => "customers_id",
//                "fields" => array(
//                    "customers_nama" => "Customer",
//                    "nomer_top" => "UM",
//                    "produk_kode" => "produk kode",
//                    "produk_ord_jml" => "order",
//                    "ord_sent_qty" => "dikirim",
//                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
//                ),
//                "loop" => array(
//                    "nomer_top" => "nomer_top",
//                    "produk_kode" => "produk_kode",
//                    "produk_ord_jml" => "produk_ord_jml",
//                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
//                    "ord_valid_qty" => "valid_qty",
//                ),
//                "array_flip" => array(
//                    1,
//                ),
//            ),
//        ),

    ),
    // uang muka dari konsumen
    "4465" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customerDetails__nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
            "cash_account__label" => "cash account",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
//            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customerDetails__nama" => "customer",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "uang_muka_dpp" => "DPP",
                "uang_muka_ppn" => "PPN",
                "harga" => "Unit Price",
            ),
            2 => array(
                "uang_muka_dpp" => "DPP",
                "uang_muka_ppn" => "PPN",
                "harga" => "Unit Price",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "uang_muka_dpp" => "DPP",
                "uang_muka_ppn" => "PPN",
                "harga" => "Unit Price",
            ),
            2 => array(
                "uang_muka_dpp" => "DPP",
                "uang_muka_ppn" => "PPN",
                "harga" => "Unit Price",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),
//        "fixedFieldHold" => array(
//            "transaksi" => array(
//                "label" => "transaksi",
//                "target" => "transaksi",
//                "srcKey" => "id_master",
//                "fields" => array(
//                    "nomer_top" => "nomer",
//                    "dtime" => "approved",
//                    "oleh_nama" => "salesman",
//                    "customers_nama" => "customer",
//                    "print_label" => "tool",
//                ),
//                "loop" => array(),
//            ),
//            "produk" => array(
//                "label" => "produk",
//                "target" => "produk",
//                "srcKey" => "produk_id",
//                "fields" => array(
//                    "produk_nama" => "product",
//                    "customers_nama" => "customers nama",
//                    "nomer_top" => "Transaksi",
//                    "ord_qty" => "Order",
//                    "ord_sent_qty" => "Dikirim",
//                    "ord_valid_qty" => "Outstanding",
//                    "avail_qty" => "Tersedia",
//                    "print_label" => "tool",
//
//                ),
//                "loop" => array(
//                    "customers_nama" => "customers_nama",
//                    "nomer_top" => "nomer_top",
//                    "ord_qty" => "produk_ord_jml",
//                    "ord_valid_qty" => "valid_qty",
//                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
//                ),
//
//            ),
//            "customer" => array(
//                "label" => "customer",
//                "target" => "customer",
//                "srcKey" => "customers_id",
//                "fields" => array(
//                    "customers_nama" => "Customer",
//                    "nomer_top" => "UM",
//                    "produk_kode" => "produk kode",
//                    "produk_ord_jml" => "order",
//                    "ord_sent_qty" => "dikirim",
//                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
//                ),
//                "loop" => array(
//                    "nomer_top" => "nomer_top",
//                    "produk_kode" => "produk_kode",
//                    "produk_ord_jml" => "produk_ord_jml",
//                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
//                    "ord_valid_qty" => "valid_qty",
//                ),
//                "array_flip" => array(
//                    1,
//                ),
//            ),
//        ),

    ),
    // setoran uang muka dari konsumen
    "7759" => array(
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
    "7758" => array(
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

    // uang muka dari konsumen
    "4467" => array(
        "receiptTemplate" => array(
            // 1 => "template/464r.html",
            1 => "template/582spo_mod.html",
            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customerDetails__nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
            "cash_account__label" => "cash account",
        ),
        "headerField" => "heTransaksi_layout",
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            // "deliveryDetails" => array(
            //     "usedFields" => array(
            //         "alias" => "Attn",
            //         "alamat" => "Alamat",
            //         "kecamatan" => "Kec",
            //         "kabupaten" => "Kab",
            //         "propinsi" => "propinsi",
            //         "tlp" => "Tlp",
            //         // "tlp_2"     => "Handphone",
            //         //                    "npwp" => "NPWP",
            //         //                    "propinsi" =>"",
            //     ),
            // ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
//                "sign_2" => false,
                "customerSignitures" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
                "sign_1" => true,
//                "penerimaSign"=>false,
            ),
            2 => array(
                "customerSignitures" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
                "sign_1" => true,
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
//            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customerDetails__nama" => "customer",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "harga",
                // "disc" => "disc",
                // "nett1" => "harga net",
                // "nett1_ppn" => "harga ppn",
            ),
            2 => array(

                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "PPN",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                // "id" => "PID",
                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
            2 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
//                "stok_center" => "Stok dc",
//                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml" => "dicancel",
                "packed_jml" => "dipacking",
                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new" => "Sub Berat",
//                "sub_berat_gross"  => "Sub Berat",
//                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
//                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id" => "PID",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "total_diskon" => "diskon",
                "nett1_bulat" => "DPP",
                "ppn" => "PPN",
                "harga" => "<r class=\"meta\">Pembulatan</r> Grand Total",
                "pph23" => "pph23",
                "kas_nilai" => "Kas diterima",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui" => "Shipping Service",
                "total_diskon" => "diskon",
//                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
//                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "Total Amount",
//                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "PPN",
                //                "dp" => "DOWNPAYMENT",
//                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_nett1" => "Jumlah",
                "sub_harga" => "Jumlah",
                // "sub_harga" => "Jumlah",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),

    ),

    "9467" => array(
        "receiptTemplate" => array(
            // 1 => "template/464r.html",
            1 => "template/582spo_mod.html",
            2 => "template/582spo_mod.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customerDetails__nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
            "cash_account__label" => "cash account",
        ),
        "headerField" => "heTransaksi_layout",
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            // "deliveryDetails" => array(
            //     "usedFields" => array(
            //         "alias" => "Attn",
            //         "alamat" => "Alamat",
            //         "kecamatan" => "Kec",
            //         "kabupaten" => "Kab",
            //         "propinsi" => "propinsi",
            //         "tlp" => "Tlp",
            //         // "tlp_2"     => "Handphone",
            //         //                    "npwp" => "NPWP",
            //         //                    "propinsi" =>"",
            //     ),
            // ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "salesman" => array(),
                "customerSignitures" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
                "sign_1" => true,
//                "penerimaSign"=>false,
            ),
            2 => array(
                "customerSignitures" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
                "sign_1" => true,
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
//            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customerDetails__nama" => "customer",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "harga",
                // "disc" => "disc",
                // "nett1" => "harga net",
                // "nett1_ppn" => "harga ppn",
            ),
            2 => array(

                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "PPN",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                // "id" => "PID",
                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
            2 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
//                "stok_center" => "Stok dc",
//                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml" => "dicancel",
                "packed_jml" => "dipacking",
                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new" => "Sub Berat",
//                "sub_berat_gross"  => "Sub Berat",
//                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
//                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id" => "PID",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//                "total_diskon" => "diskon",
//                "nett1_bulat" => "DPP",
//                "ppn" => "PPN",
                "harga" => "<r class=\"meta\">Pembulatan</r> Grand Total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
//                "ongkir_ui" => "Shipping Service",
//                "total_diskon" => "diskon",
////                "add_diskon" => "diskon tambahan",
//                //                "grand_total" => "total amount",
////                "grand_total_ui" => "Total Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
////                "grand_ppn" => "VAT",
//                "ppn_out_bulat" => "PPN",
//                //                "dp" => "DOWNPAYMENT",
////                "new_net3" => "Grand Total",
//                "grand_pembulatan" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_nett1" => "Jumlah",
                "sub_harga" => "Jumlah",
                // "sub_harga" => "Jumlah",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),

    ),
    "19467" => array(
        "receiptTemplate" => array(
            1 => "template/582spo_mod.html",
            2 => "template/582spo_mod.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customerDetails__nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
            "cash_account__label" => "bank account",
        ),
        "headerField" => "heTransaksi_layout",
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            // "deliveryDetails" => array(
            //     "usedFields" => array(
            //         "alias" => "Attn",
            //         "alamat" => "Alamat",
            //         "kecamatan" => "Kec",
            //         "kabupaten" => "Kab",
            //         "propinsi" => "propinsi",
            //         "tlp" => "Tlp",
            //         // "tlp_2"     => "Handphone",
            //         //                    "npwp" => "NPWP",
            //         //                    "propinsi" =>"",
            //     ),
            // ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
//                "sign_2" => false,
                "customerSignitures" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
                "sign_1" => true,
//                "penerimaSign"=>false,
            ),
            2 => array(
                "customerSignitures" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "customerDetails_nama",
                    "footers" => "--",
                ),
                "sign_1" => true,
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
//            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customerName" => "customer",
            "dtime" => "date",
            "cash_account__folders_nama" => "bank",
            "cash_account__label" => "bank account",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "harga",
                // "disc" => "disc",
                // "nett1" => "harga net",
                // "nett1_ppn" => "harga ppn",
            ),
            2 => array(
                "harga" => "harga",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "harga",
                //                "disc" => "disc",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
//                "ppn" => "PPN",
            ),
            2 => array(
                "harga" => "harga",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
            2 => array(
                "id" => "PID",
//                "produk_kode" => "Product code",
//                "no_part" => "part number",
                "produk_nama" => "Description",
//                "stok_center" => "Stok dc",
//                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//
//                "total_diskon" => "diskon",
//                "nett1_bulat" => "DPP",
//                "ppn" => "PPN",
                "harga" => "<r class=\"meta\">kas</r> Dikembalikan",
            ),
            2 => array(
//                //                "nett1" => "amount",
//                //                "disc" => "disc",
//                "ongkir_ui" => "Shipping Service",
//                "total_diskon" => "diskon",
////                "add_diskon" => "diskon tambahan",
//                //                "grand_total" => "total amount",
////                "grand_total_ui" => "Total Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
////                "grand_ppn" => "VAT",
//                "ppn_out_bulat" => "PPN",
//                //                "dp" => "DOWNPAYMENT",
////                "new_net3" => "Grand Total",
//                "grand_pembulatan" => "Grand Total",
                "harga" => "<r class=\"meta\">kas</r> Dikembalikan",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_nett1" => "Jumlah",
                "sub_harga" => "Jumlah",
                // "sub_harga" => "Jumlah",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),

    ),

    "4464_OLD" => array(
        "receiptTemplate" => array(
            // 1 => "template/464r.html",
            1 => "template/582spo_mod.html",
            2 => "template/464.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customerDetails__nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
            "cash_account__label" => "cash account",
        ),
        "headerField" => "heTransaksi_layout",
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            // "deliveryDetails" => array(
            //     "usedFields" => array(
            //         "alias" => "Attn",
            //         "alamat" => "Alamat",
            //         "kecamatan" => "Kec",
            //         "kabupaten" => "Kab",
            //         "propinsi" => "propinsi",
            //         "tlp" => "Tlp",
            //         // "tlp_2"     => "Handphone",
            //         //                    "npwp" => "NPWP",
            //         //                    "propinsi" =>"",
            //     ),
            // ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "sign_1" => true,
                "sign_2" => false,
                "sign_4" => false,
                "salesman" => array(
                    "label" => ".salesman",
                    "contents" => "salesmanDetails__nama",
                    "stateCaption" => "",
                ),
                "customerSignitures" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            2 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => false,
                "salesman" => array(
                    "label" => ".salesman",
                    "contents" => "salesmanDetails__nama",
                    "stateCaption" => "",
                ),
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            3 => array(
                "sign_1" => true,
                "sign_2" => true,
                // "sign_3" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "sign_5" => array(
                    "label" => ".Driver",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
            ),
            4 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
            5 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
//            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customerDetails__nama" => "customer",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "harga",
                // "disc" => "disc",
                // "nett1" => "harga net",
                // "nett1_ppn" => "harga ppn",
            ),
            2 => array(

                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "PPN",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                // "id" => "PID",
                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
            2 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
//                "stok_center" => "Stok dc",
//                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id" => "PID",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml" => "dicancel",
                "packed_jml" => "dipacking",
                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new" => "Sub Berat",
//                "sub_berat_gross"  => "Sub Berat",
//                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
//                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id" => "PID",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                // "nett1" => "amount",
                //                "disc" => "disc",
                // "ongkir_ui" => "Shipping Service",
                "total_diskon" => "diskon",
//                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
//                "grand_total_ui" => "Total Amount",
//                 "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "DPP",
//                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "PPN",
                //                "dp" => "DOWNPAYMENT",
//                "new_net3" => "Grand Total",
                "grand_pembulatan" => "<r class=\"meta\">Pembulatan</r> Grand Total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui" => "Shipping Service",
                "total_diskon" => "diskon",
//                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
//                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "Total Amount",
//                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "PPN",
                //                "dp" => "DOWNPAYMENT",
//                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_nett1" => "Jumlah",
                "sub_harga" => "Jumlah",
                // "sub_harga" => "Jumlah",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),

    ),
    "4464" => array(
        "receiptTemplate" => array(
//            1 => "template/749.html",
            1 => "template/582spo_mod.html",
            2 => "template/464.html",
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
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customerSignitures" => array(
                    "label" => ".KONSUMEN",
                    "contents" => "customerDetails_nama",
                    "footers" => "",
                ),
                "sign_1" => array(
                    "label" => ".FINANCE",
                    "contents" => "olehName",
                    "footers" => "",
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
            "customers_nama" => "customer",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "UOM",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                // "selisih_round" => "total amount",
                "nilai_round" => "total amount",
                "uang_muka_dipakai" => "uang muka",
                "credit_amount" => "credit note(deposit) return",
                "nilai_biaya" => "(diskon)",
//                "ppn_nilai_dibayar" => "(ppn dibayar bendahara negara)",
//                "pph22_nilai" => "(pph 22 dibayar dimuka)",
                "pph23" => "potongan pph23",
                "nilai_bayar_netto" => "netto",
                "nilai_entry" => "paid ",
                "nilai_bayar" => "total payment",
                "lebih_bayar" => "lebih bayar",
                "new_sisa" => "kurang bayar",
                // "new_sisa" => "balance",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nilai_bayar",),
        ),
        "fixedNote" => "WARNING:<br>
                - Biaya Suport (biaya admin yang dipotong pihak lain) jika di isi, akan dibebankan sebagai biaya usaha.<br>
                - Cash Received (uang yang diterima) tidak boleh sama atau lebih dari nilai invoice karena sudah dipotong Biaya Support.<br>
                ",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "headerField" => "heTransaksi_layout",
    ),

    "7467" => array(
        "receiptTemplate" => array(
            // 1 => "template/464r.html",
            1 => "template/582spo_mod.html",
            2 => "template/582spo_mod.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customerDetails__nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
            "cash_account__label" => "cash account",
        ),
        "headerField" => "heTransaksi_layout",
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            // "deliveryDetails" => array(
            //     "usedFields" => array(
            //         "alias" => "Attn",
            //         "alamat" => "Alamat",
            //         "kecamatan" => "Kec",
            //         "kabupaten" => "Kab",
            //         "propinsi" => "propinsi",
            //         "tlp" => "Tlp",
            //         // "tlp_2"     => "Handphone",
            //         //                    "npwp" => "NPWP",
            //         //                    "propinsi" =>"",
            //     ),
            // ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No PRE UM",
                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer_top" => "No PRE UM",
                "nomer" => "No UM",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "salesman" => array(),
                "customerSignitures" => array(
                    "label" => ".Supplier",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                ),
                "sign_1" => true,
//                "penerimaSign"=>false,
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
//            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customerDetails__nama" => "customer",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "harga",
                // "disc" => "disc",
                // "nett1" => "harga net",
                // "nett1_ppn" => "harga ppn",
            ),
            2 => array(

                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "PPN",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
            2 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//                "total_diskon" => "diskon",
//                "nett1_bulat" => "DPP",
//                "ppn" => "PPN",
                "harga" => "<r class=\"meta\">Pembulatan</r> Grand Total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
//                "ongkir_ui" => "Shipping Service",
//                "total_diskon" => "diskon",
////                "add_diskon" => "diskon tambahan",
//                //                "grand_total" => "total amount",
////                "grand_total_ui" => "Total Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
////                "grand_ppn" => "VAT",
//                "ppn_out_bulat" => "PPN",
//                //                "dp" => "DOWNPAYMENT",
////                "new_net3" => "Grand Total",
//                "grand_pembulatan" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_nett1" => "Jumlah",
                "sub_harga" => "Jumlah",
                // "sub_harga" => "Jumlah",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),

    ),

    "7468" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
//            1 => "template/582spo_mod.html",
//            2 => "template/582spo_mod.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "customerDetails__nama" => "Customer",
            "tlp_1" => "phone",
            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
            "cash_account__label" => "cash account",
        ),
        "headerField" => "heTransaksi_layout",
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            // "deliveryDetails" => array(
            //     "usedFields" => array(
            //         "alias" => "Attn",
            //         "alamat" => "Alamat",
            //         "kecamatan" => "Kec",
            //         "kabupaten" => "Kab",
            //         "propinsi" => "propinsi",
            //         "tlp" => "Tlp",
            //         // "tlp_2"     => "Handphone",
            //         //                    "npwp" => "NPWP",
            //         //                    "propinsi" =>"",
            //     ),
            // ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "Nomer",
                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
//            2 => array(
//                "nomer_top" => "No PRE UM",
//                "nomer" => "No UM",
//                "dtime" => "Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
////                "tos__name" => "Term of Shipment",
////                "dueDate_value" => "Due Date",
//            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "salesman" => array(),
                "customerSignitures" => array(
                    "label" => ".Supplier",
                    "contents" => "vendorDetails_nama",
                    "footers" => "--",
                ),
                "sign_1" => true,
//                "penerimaSign"=>false,
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
//            3 => "jml*(harga)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customerDetails__nama" => "customer",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "harga",
                // "disc" => "disc",
                // "nett1" => "harga net",
                // "nett1_ppn" => "harga ppn",
            ),
            2 => array(

                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "PPN",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
            2 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//                "total_diskon" => "diskon",
//                "nett1_bulat" => "DPP",
//                "ppn" => "PPN",
                "harga" => "<r class=\"meta\">Pembulatan</r> Grand Total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
//                "ongkir_ui" => "Shipping Service",
//                "total_diskon" => "diskon",
////                "add_diskon" => "diskon tambahan",
//                //                "grand_total" => "total amount",
////                "grand_total_ui" => "Total Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
////                "grand_ppn" => "VAT",
//                "ppn_out_bulat" => "PPN",
//                //                "dp" => "DOWNPAYMENT",
////                "new_net3" => "Grand Total",
//                "grand_pembulatan" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_nett1" => "Jumlah",
                "sub_harga" => "Jumlah",
                // "sub_harga" => "Jumlah",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
        ),

    ),

    "7444" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
            2 => "template/464r.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cash_account__label" => "cash account",
        ),
        "headerField" => "heTransaksi_layout",
        "receiptElements" => array(
//            "customerDetails" => array(
//                "usedFields" => array(
//                    "nama" => "nama",
//                    // "alamat_1"  => "alamat",
//                    // "kelurahan" => "Kel",
//                    // "kecamatan" => "Kec",
//                    // "kabupaten" => "Kab",
//                    // "propinsi"  => "Prop",
//                    // "tlp"       => "Tlp",
//                    "tlp_1" => "Tlp",
//                    // "tlp_2"     => "Handphone",
//                    "npwp" => "NPWP",
//                    "no_ktp" => "nik",
//                    // "nik"       => "NIK",
//                ),
//            ),
            // "deliveryDetails" => array(
            //     "usedFields" => array(
            //         "alias" => "Attn",
            //         "alamat" => "Alamat",
            //         "kecamatan" => "Kec",
            //         "kabupaten" => "Kab",
            //         "propinsi" => "propinsi",
            //         "tlp" => "Tlp",
            //         // "tlp_2"     => "Handphone",
            //         //                    "npwp" => "NPWP",
            //         //                    "propinsi" =>"",
            //     ),
            // ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "Nomer",
                "dtime" => "Date",

            ),
            2 => array(
                "nomer" => "Nomer",
                "nomer_top" => "Nomer Request",
                "dtime" => "Date",

            ),
        ),
        "fixedSignatures" => array(
            1 => array(
//                "salesman" => array(),
//                "customerSignitures" => array(
//                    "label" => ".Supplier",
//                    "contents" => "vendorDetails_nama",
//                    "footers" => "--",
//                ),
                "sign_1" => true,
//                "penerimaSign"=>false,
            ),
            2 => array(
//                "salesman" => array(),
//                "customerSignitures" => array(
//                    "label" => ".Supplier",
//                    "contents" => "vendorDetails_nama",
//                    "footers" => "--",
//                ),
                "sign_1" => true,
                "sign_2" => true,
//                "penerimaSign"=>false,
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",
            2 => "jml*(harga)",

        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "jumlah uang diterima",
                // "disc" => "disc",
                // "nett1" => "harga net",
                // "nett1_ppn" => "harga ppn",
//
            ),
            2 => array(
                "harga" => "jumlah uang diterima",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
//
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "jumlah uang diterima",
            ),
            2 => array(
                "harga" => "jumlah uang diterima",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
            2 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Grand Total",
            ),
            2 => array(
                "harga" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Jumlah uang diterima",
            ),
            2 => array(
                "sub_harga" => "Jumlah uang diterima",
            ),
        ),

    ),
    "7445" => array(
        "receiptTemplate" => array(
            1 => "template/464r.html",
            2 => "template/464r.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cash_account__label" => "cash account",
        ),
        "headerField" => "heTransaksi_layout",
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            // "deliveryDetails" => array(
            //     "usedFields" => array(
            //         "alias" => "Attn",
            //         "alamat" => "Alamat",
            //         "kecamatan" => "Kec",
            //         "kabupaten" => "Kab",
            //         "propinsi" => "propinsi",
            //         "tlp" => "Tlp",
            //         // "tlp_2"     => "Handphone",
            //         //                    "npwp" => "NPWP",
            //         //                    "propinsi" =>"",
            //     ),
            // ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "Nomer",
                "dtime" => "Date",

            ),
            2 => array(
                "nomer" => "Nomer",
                "nomer_top" => "Nomer Request",
                "dtime" => "Date",

            ),
        ),
        "fixedSignatures" => array(
            1 => array(
//                "salesman" => array(),
//                "customerSignitures" => array(
//                    "label" => ".Supplier",
//                    "contents" => "vendorDetails_nama",
//                    "footers" => "--",
//                ),
                "sign_1" => true,
//                "penerimaSign"=>false,
            ),
            2 => array(
//                "salesman" => array(),
//                "customerSignitures" => array(
//                    "label" => ".Supplier",
//                    "contents" => "vendorDetails_nama",
//                    "footers" => "--",
//                ),
                "sign_1" => true,
                "sign_2" => true,
//                "penerimaSign"=>false,
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",
            2 => "jml*(harga)",

        ),
        "headerTables" => array(
            "produk_nama" => "Description",
            "produk_ord_hrg" => "Unit Price",
            "produk_ord_jml" => "Qty",
            "sub_total" => "Total Price",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "jumlah direlasikan",
                // "disc" => "disc",
                // "nett1" => "harga net",
                // "nett1_ppn" => "harga ppn",
//
            ),
            2 => array(
                "harga" => "jumlah direlasikan",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
//
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "jumlah direlasikan",
            ),
            2 => array(
                "harga" => "jumlah direlasikan",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
                "bank_nama" => "Bank",
                "bank_rekening_nama" => "Akun Bank",
                "date_transaksi_bank" => "Tanggal Bank",
                "nomer_referensi_bank" => "Nomer Referensi Transaksi Bank",
                "nomer_rekening_asal" => "Nomer Rekening Asal Transfer",
                "nama_rekening_asal" => "Nama Asal Transfer",
            ),
            2 => array(
                // "id" => "PID",
//                "produk_kode" => "SKU",
                // "no_part" => "part number",
                "produk_nama" => "Deskripsi",
                "produk_ord_jml" => "Qty",
                // "satuan" => "Satuan",
                "bank_nama" => "Bank",
                "bank_rekening_nama" => "Akun Bank",
                "date_transaksi_bank" => "Tanggal Bank",
                "nomer_referensi_bank" => "Nomer Referensi Transaksi Bank",
                "nomer_rekening_asal" => "Nomer Rekening Asal Transfer",
                "nama_rekening_asal" => "Nama Asal Transfer",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Grand Total",
            ),
            2 => array(
                "harga" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",

        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "jumlah direlasikan",
            ),
            2 => array(
                "sub_harga" => "jumlah direlasikan",
            ),
        ),

    ),


);