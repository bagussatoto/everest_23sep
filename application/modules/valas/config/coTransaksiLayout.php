<?php

$config["coTransaksiLayout"] = array(
    //valas exchange
    "383" => array(
        "receiptTemplate" => array(
            1 => "template/383.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran" => "payment method",
                "alias" => "attn",

            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
                "delivery_date" => "delivery date",
                "top" => "term of payment",
                "tos" => "term of shipment",
                "capacity" => "address",
            ),

        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
            ),
        ),
        "fixedSignatures" => array(),
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
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "nilai valas",
                "satuan" => "uom",
                //                "sub_nett2_valas" => "sub-total"
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),

            3 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "berat_gross" => "berat",
                "volume_gross" => "volume",
            ),
            4 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "berat_gross" => "berat",
                "volume_gross" => "volume",
            ),
            5 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //                "dics_valas" => "disc",
                "nett2" => "amount",
                //                "valas_nilai" => "amount",
            ),
            2 => array(
                "valas_nilai" => "amount",
                "nett2_valas" => "total",
            ),

            3 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            4 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            5 => array(
                "valas_nilai" => "amount",
                "nett2_valas" => "total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "grand_total" => "sub-total"

            ),
            //            2 => array(
            //                "stok" => "stok",
            //                "valas_nilai" => "price",
            //                "sub_nett2_valas" => "sub-total"
            //            ),
            //
            //            3 => array(
            ////                "stok" => "stok",
            ////                "harga" => "price",
            ////                "ppn"   => "VAT",
            //            ),
            //            4 => array(
            ////                "harga" => "price",
            ////                "ppn"   => "VAT",
            //            ),
            //            5 => array(
            //                "valas_nilai" => "price",
            //                "disc" => "disc",
            //                "sub_nett2_valas" => "sub-total"
            //
            //            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),

        "staticFooter" => array(
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nett2",),
            "currency_id" => "valasDetails",
        ),
    ),
    //  purchasing valas
    "384" => array(
        "receiptTemplate" => array(
            1 => "template/384r.html",
            2 => "template/384.html",
        ),
        "headerNota" => array(
            "Konversi valas" => array(
                "nomer" => "no.",
                "dtime" => "date",
                "paymentMethod_cash__label" => "cash account",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cash_account__label" => "cash account",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cash_account__label" => "cash account",
            ),
        ),
        "fixedSignatures" => array(),
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
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "valas name",
                "produk_ord_jml" => "qty valas",
            ),
            2 => array(
                "produk_nama" => "valas name",
                "produk_ord_jml" => "qty valas",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
                "biaya" => "biaya",
                "netto" => "netto",
            ),
            2 => array(
                "harga" => "total",
                "biaya" => "biaya",
                "netto" => "netto",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "harga perolehan",
            ),
            2 => array(
                "harga" => "harga perolehan",
            ),
        ),
        "reportSumFields" => array(//            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "staticFooter" => array(),
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nett2",),
            "currency_id" => "valasDetails",
        ),
        "fixedNoteTop" => "Harga perolehan Valas akan bertambah bila ada biaya yang terlibat.",
    ),
    //  config penyetoran mata uang asing
    "1759" => array(
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
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa_valas" => "receivable amount",
                //                "creditAmount" => "paid using credit",
                "nilai_entry" => "paid using cash account",
                "nilai_bayar_valas" => "total amount of payment",
                "new_sisa_valas" => "remain receivable (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa_valas" => "due remain",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "receiptInword" => array(),
    ),
    "1758" => array(
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
            "produk_nama" => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
            "cash_account_source__label" => "cash account source",
            "cashMethode__label" => "target method account",
            "cash_account_target__label" => "cash account target",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
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
        "printLocation" => "Printing/viewReceipt/",
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

    //  konversi kas ke valas
    "385" => array(
        "receiptTemplate" => array(
            1 => "template/384r.html",
            2 => "template/384.html",
        ),
        "headerNota" => array(
            "Konversi valas" => array(
                "nomer" => "no.",
                "dtime" => "date",
                "paymentMethod_cash__label" => "cash account",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cash_account__label" => "cash account",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
                "cash_account__label" => "cash account",
            ),
        ),
        "fixedSignatures" => array(),
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
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "valas name",
                "produk_ord_jml" => "qty valas",
                //                "kurs" => "kurs",
            ),
            2 => array(
                "produk_nama" => "valas name",
                "produk_ord_jml" => "qty valas",
                //                "kurs" => "kurs",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
                "biaya" => "biaya",
                "netto" => "netto",
            ),
            2 => array(
                "harga" => "total",
                "biaya" => "biaya",
                "netto" => "netto",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "kurs",
            ),
            2 => array(
                "harga" => "kurs",
            ),
        ),
        "reportSumFields" => array(//            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        "staticFooter" => array(),
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nett2",),
            "currency_id" => "valasDetails",
        ),
    ),



);