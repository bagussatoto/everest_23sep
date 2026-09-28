<?php

$config["coTransaksiLayout"] = array(
    //pettycash
    "671" => array(
        "receiptTemplate" => array(
            //            1 => "application/template/671ro.html",
            1 => "template/671r.html",
            2 => "template/671.html",
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
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
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
                "biaya_dpp" => "DPP",
                "biaya_ppn" => "PPN",
                "biaya_nonppn" => "NON PPN",
                "harga" => "amount",
            ),
            2 => array(
                "biaya_dpp" => "DPP",
                "biaya_ppn" => "PPN",
                "biaya_nonppn" => "NON PPN",
                "harga" => "amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    "672" => array(
        "receiptTemplate" => array(
            //            1 => "application/template/672ro.html",
            1 => "template/672r.html",
            2 => "template/672.html",
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
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
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
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            //            3 => array(
            //                "produk_nama" => "product name",
            //                "produk_ord_jml" => "qty",
            //            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "biaya_dpp" => "DPP",
                "biaya_ppn" => "PPN",
                "biaya_nonppn" => "NON PPN",
                "harga" => "amount",
            ),
            2 => array(
                "biaya_dpp" => "DPP",
                "biaya_ppn" => "PPN",
                "biaya_nonppn" => "NON PPN",
                "harga" => "amount",
            ),
            3 => array(
                "biaya_dpp" => "DPP",
                "biaya_ppn" => "PPN",
                "biaya_nonppn" => "NON PPN",
                "harga" => "amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    "771" => array(
        "receiptTemplate" => array(
            1 => "template/771.html",
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
                "nomer_top" => "SO No.",
                "dtime" => "Date",
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
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "product name",
                "jml" => "qty",
                "harga" => "price",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //                "sisa" => "amount",
                //                "creditAmount" => "supplier credit amount",
                //                "additional_value" => "additional ",
                //                "harus_bayar" => "amount remains to pay",
                //
                //                "nilai_entry" => "amount of payment",
                //                "new_sisa" => "",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar",),
            ),

        ),

        "reviewDetailCompactListsLabel" => array(
            "nama" => "reference number",
            "sisa" => "amount",
            //            "diskon" => "discount",
            "nilai_bayar" => "paid amount",
            //            "new_sisa" => "remain",
            "tagihan" => "total price",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "dtime" => "Date",
            "cabang_nama" => "Branch",
        ),
        "reviewCompactListDetailSum" => array(
            "." => "-",
            //            "*" => "-",
            //            "-" => "-",
            "nilai_bayar" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "tagihan" => "original amount",
            "nilai_entry" => "paid using cash account",
            "nilai_bayar" => "total amount of payment",
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

    "1671" => array(
        "receiptTemplate" => array(
            //            1 => "application/template/1671ro.html",
            1 => "template/1671r.html",
            2 => "template/1671.html",
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
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No.",
                //                "nomer_top" => "PO No.",
                "dtime" => "Date",
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
                "produk_nama" => "expense name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "expense name",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "expense name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "biaya_dpp" => "DPP",
                "biaya_ppn" => "PPN",
                "biaya_nonppn" => "NON PPN",
                "harga" => "amount",
            ),
            2 => array(
                "biaya_dpp" => "DPP",
                "biaya_ppn" => "PPN",
                "biaya_nonppn" => "NON PPN",
                "harga" => "amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    "1672" => array(
        "receiptTemplate" => array(
            //            1 => "application/template/1671ro.html",
            1 => "template/1671r.html",
            2 => "template/1671.html",
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
                "nomer" => "No",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "Request No.",
                "dtime" => "Date",
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
                "produk_nama" => "expense name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "expense name",
                "produk_ord_jml" => "qty",
            ),
            //            3 => array(
            //                "produk_nama" => "product name",
            //                "produk_ord_jml" => "qty",
            //            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            3 => array(
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    "1771" => array(
        "receiptTemplate" => array(
            1 => "template/1771.html",
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
                "produk_nama" => "expense name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "product name",
                "jml" => "qty",
                "harga" => "price",
                //            "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "amount",
                //                "creditAmount" => "supplier credit amount",
                //                "additional_value" => "additional ",
                //                "harus_bayar" => "amount remains to pay",
                //
                //                "nilai_entry" => "amount of payment",
                //                "new_sisa" => "",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array(
                    "inWordInd" => "harus_bayar",
                ),
            ),

        ),
    ),

    "770" => array(
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
        "staticFooter" => array(),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    "970" => array(
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
        "staticFooter" => array(),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
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