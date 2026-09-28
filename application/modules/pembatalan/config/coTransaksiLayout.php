<?php

$config["coTransaksiLayout"] = array(
    "9911" => array(
        "receiptTemplate" => array(
            1 => "template/651r.html",
            2 => "template/651.html",

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
            1 => array(
                "customers_nama" => "GRN No",
                "nomer" => "No",
                "dtime" => "Date",

                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "customers_nama" => "GRN No",
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
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
            //            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                //                "produk_ord_jml" => "qty",
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
                "amount" => "extern_nilai2",
                "nilai_pph23" => "pph 23",
                "nilai_pph21" => "pph 21",
                "tagihan" => "grand total",

                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "amount",
            ),
            2 => array(
                "sisa" => "amount",
            ),
        ),
        "reportSumFields" => array(
            //            "suppliers_id" => "suppliers_nama",
            "jenis" => "jenis_label",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                //                "extern_nilai2" => "Unit Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            //            2 => array(
            //                "stok" => "stok",
            //                "harga" => "Unit Price",
            //                "disc_percent" => "disc (%)",
            //                "disc" => "disc (IDR)",
            //                "ppn" => "VAT",
            //            ),
            //            3 => array(
            //                "harga" => "Unit Price",
            //                "stok" => "stok",
            //                "harga" => "price",
            //                "ppn"   => "VAT",
            //            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),


        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "qty" => "qty",
            //            "sisa" => "sisa",
            "subtotal" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
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
        "fixedNoteTop" => "
                <span style='font-size:20px;'>Transaksi ini (Pembatalan Transaksi) membatalkan permanen transaksi yang dipilih, BUKAN mundur 1 langkah.</span><br>

                ",
    ),
    "9912" => array(
        "receiptTemplate" => array(
            1 => "template/651r.html",
            2 => "template/651.html",

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
            1 => array(
                "customers_nama" => "GRN No",
                "nomer" => "No",
                "dtime" => "Date",

                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "customers_nama" => "GRN No",
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
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
            //            "suppliers_nama" => "vendor",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                //                "produk_ord_jml" => "qty",
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
                "amount" => "extern_nilai2",
                "nilai_pph23" => "pph 23",
                "nilai_pph21" => "pph 21",
                "tagihan" => "grand total",

                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "amount",
            ),
            2 => array(
                "sisa" => "amount",
            ),
        ),
        "reportSumFields" => array(
            //            "suppliers_id" => "suppliers_nama",
            "jenis" => "jenis_label",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                //                "extern_nilai2" => "Unit Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            //            2 => array(
            //                "stok" => "stok",
            //                "harga" => "Unit Price",
            //                "disc_percent" => "disc (%)",
            //                "disc" => "disc (IDR)",
            //                "ppn" => "VAT",
            //            ),
            //            3 => array(
            //                "harga" => "Unit Price",
            //                "stok" => "stok",
            //                "harga" => "price",
            //                "ppn"   => "VAT",
            //            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),


        ),
        "fixedNoteTop" => "
                <span style='font-size:20px;'>Transaksi ini (Pembatalan Transaksi) membatalkan permanen transaksi yang dipilih, BUKAN mundur 1 langkah.</span><br>

                ",
    ),
    // config penerimaan piutang customer (uang masuk)
    "9749" => array(
        "receiptTemplate" => array(
            1 => "template/9749r.html",
            2 => "template/9749.html",
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
                "dtime" => "Date",

            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",

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
        ),
        "receiptDetailFields" => array(
            1 => array(
                "nama" => "Invoice",
                "produk_ord_jml" => "Qty",
            ),
            2 => array(
                "nama" => "Invoice",
                "produk_ord_jml" => "Qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "subtotal" => "total tagihan",
                //                "creditAmount" => "credit note(deposit)",
                //                "nilai_biaya" => "(biaya suport)",
                //                "nilai_entry" => "paid ",
                "nilai_bayar" => "penghapusan piutang",
                "new_sisa" => "sisa tagihan",
            ),
            2 => array(
                "subtotal" => "total tagihan",
                //                "creditAmount" => "credit note(deposit)",
                //                "nilai_biaya" => "(biaya suport)",
                //                "nilai_entry" => "paid ",
                "nilai_bayar" => "penghapusan piutang",
                "new_sisa" => "sisa tagihan",
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
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nilai_bayar",),
        ),

    ),
);