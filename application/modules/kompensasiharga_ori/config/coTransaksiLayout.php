<?php

$config["coTransaksiLayout"] = array(
    // barang terjual
    "3311" => array(
        "receiptTemplate" => array(
            1 => "template/466r.html",
            2 => "template/466.html",

        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "",
                "tlp_1" => "phone",
                "alamat_1" => "",
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
                "nomer" => "Nomer",
                "dtime" => "Tanggal",
                "kompensasiMethod__name" => "Metode kompensasi",
                "cash_account__nama" => "Akun Bank",
            ),
            2 => array(
                "nomer" => "Nomer",
                "nomer_top" => "Request Nomer",
                "dtime" => "Tanggal",
                "kompensasiMethod__name" => "Metode kompensasi",
                "cash_account__nama" => "Akun Bank",
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
        "headerTables" => array(
            "produk_nama" => "description",
            "produk_kode" => "product number",
            "produk_ord_hrg" => "unit price",
            "produk_ord_jml" => "qty",
            "sub_total" => "total price",
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
                "produk_nama" => "Nama Produk",
                "produk_kode" => "kode",
                "no_part" => "nomer part",
                "produk_ord_jml" => "Qty",
                "satuan" => "Satuan",
            ),
            2 => array(
                "produk_nama" => "Nama Produk",
                "produk_kode" => "kode",
                "no_part" => "nomer part",
                "produk_ord_jml" => "Qty",
                "satuan" => "Satuan",
            ),

        ),
        "receipCartNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total",
            ),
            2 => array(
                "harga" => "Total",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
//            1 => "SAN/F/PUR002/R00",
//            2 => "SAN/F/PUR002/R00",
//            3 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "staticNotes" => array(
            1 => true,
            2 => true,
        ),
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
            4 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp_nppn"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp_nppn"),
            ),
//            "3" => array(
//                "in_word" => array("inWordInd" => "hpp_nppn"),
//            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
            ),
            4 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
            ),
        ),
        "print_nvalas" => true,
        "lockerStock" => "MdlLockerStock",
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
//                    "nomer_top" => "nomer",// nomer PRE-PO
                    "nomer" => "nomer PO",// nomer PO
                    "dtime" => "date approved",
                    "oleh_nama" => "approval",
                    "suppliers_nama" => "supplier nama",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "harga",
                    ),
                ),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "produk nama",
                    "produk_kode" => "produk kode",
                    "suppliers_nama" => "supplier nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "purchase",
                    "ord_sent_qty" => "diterima",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    "produk_id" => "PID",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
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

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            "harga" => "unit price",
            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
            //            "billingDetails__nik" => "nik",
            //            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
            "ppn" => "VAT",
            "hpp_nppn" => "Grand Total",
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

        "noteWarning" => array(
            2 => "Khusus transaksi FG/Supplies Purchasing, Approval langsung menutup PRE-PO.",
        ),
    ),
    // stok tersisa
    "3322" => array(
        "receiptTemplate" => array(
            1 => "template/466r.html",
            2 => "template/466.html",
            3 => "template/467.html",
            4 => "template/467.html",
            5 => "template/651.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "",
                "tlp_1" => "phone",
                "alamat_1" => "",
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
                "nomer" => "Nomer",
                "dtime" => "Tanggal",
                "kompensasiMethod__name" => "Metode kompensasi",
                "cash_account__nama" => "Akun Bank",
            ),
            2 => array(
                "nomer" => "Nomer",
                "nomer_top" => "Request Nomer",
                "dtime" => "Tanggal",
                "kompensasiMethod__name" => "Metode kompensasi",
                "cash_account__nama" => "Akun Bank",
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
        "headerTables" => array(
            "produk_nama" => "description",
            "produk_kode" => "product number",
            "produk_ord_hrg" => "unit price",
            "produk_ord_jml" => "qty",
            "sub_total" => "total price",
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
                "produk_nama" => "Description",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),

        ),
        "receipCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
            ),
            2 => array(
                "harga" => "Unit Price",
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
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total",
            ),
            2 => array(
                "harga" => "Total",
            ),

        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
//            1 => "SAN/F/PUR002/R00",
//            2 => "SAN/F/PUR002/R00",
//            3 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "staticNotes" => array(
            1 => true,
            2 => true,
        ),
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
            4 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp_nppn"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp_nppn"),
            ),
//            "3" => array(
//                "in_word" => array("inWordInd" => "hpp_nppn"),
//            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
            ),
            4 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
            ),
        ),
        "print_nvalas" => true,
        "lockerStock" => "MdlLockerStock",
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
//                    "nomer_top" => "nomer",// nomer PRE-PO
                    "nomer" => "nomer PO",// nomer PO
                    "dtime" => "date approved",
                    "oleh_nama" => "approval",
                    "suppliers_nama" => "supplier nama",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "harga",
                    ),
                ),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "produk nama",
                    "produk_kode" => "produk kode",
                    "suppliers_nama" => "supplier nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "purchase",
                    "ord_sent_qty" => "diterima",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    "produk_id" => "PID",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
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

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            "harga" => "unit price",
            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
            //            "billingDetails__nik" => "nik",
            //            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
            "ppn" => "VAT",
            "hpp_nppn" => "Grand Total",
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

        "noteWarning" => array(
            2 => "Khusus transaksi FG/Supplies Purchasing, Approval langsung menutup PRE-PO.",
        ),
    ),

    // realisasi klaim dari piutang ke kas/voucher
    "3333" => array(
        "receiptTemplate" => array(
            1 => "template/466r.html",
            2 => "template/466.html",
            3 => "template/467.html",
            4 => "template/467.html",
            5 => "template/651.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "",
                "tlp_1" => "phone",
                "alamat_1" => "",
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
                "nomer" => "Nomer",
                "dtime" => "Tanggal",
//                "shippingDate_value" => "Delivery Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer" => "PO Number",
                "nomer_top" => "PRE-PO Number",
                "dtime" => "Date",
//                "shippingDate_value" => "Delivery Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos_nama" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
            ),
//            3 => array(
//                "nomer" => "PRE GRN Number",
//                "nomers_prev" => "PO Number",
//                "nomer_top" => "PRE-PO Number",
//                "dtime" => "Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
////                "description_main_followup" => "Vendor INV",
//            ),
//            4 => array(
//                "nomer" => "GRN Number",
//                "nomers_prev" => "PO Number",
//                "nomer_top" => "PRE-PO Number",
//                "dtime" => "Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
////                "description_main_followup" => "Vendor INV",
//            ),
//            5 => array(
//                "nomer" => "No.",
//                //                "nomers_prev" => "PO Number",
//                //                "nomer_top" => "PRE-PO Number",
//                "dtime" => "Date",
//                "eFaktur" => "e-Faktur",
//                //                "top_nama" => "Term of Payment",
//                //                "paymentMethod_name" => "Payment method",
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
        "headerTables" => array(
            "produk_nama" => "description",
            "produk_kode" => "product number",
            "produk_ord_hrg" => "unit price",
            "produk_ord_jml" => "qty",
            "sub_total" => "total price",
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
                "nama" => "Referensi",
                "extern_nama" => "Description",
//                "produk_kode" => "Product code",
//                "no_part" => "part number",
                "extern2_nama" => "isi",
                "jml" => "Qty",
//                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "Referensi",
                "extern_nama" => "Description",
//                "produk_kode" => "Product code",
//                "no_part" => "part number",
                "extern2_nama" => "isi",
                "jml" => "Qty",
            ),
        ),
        "receipCartNumFields" => array(
            1 => array(
                "diskon_supplier_nilai" => "nilai klaim",
            ),
            2 => array(
                "diskon_supplier_nilai" => "nilai klaim",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
            4 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
            5 => array(
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                "ppn" => "VAT",
                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga" => "Total Amount",
                "ppn" => "VAT",
                "hpp_nppn" => "Grand Total",
            ),
            3 => array(),
            4 => array(),
            5 => array(),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
//            1 => "SAN/F/PUR002/R00",
//            2 => "SAN/F/PUR002/R00",
//            3 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "staticNotes" => array(
            1 => true,
            2 => true,
        ),
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
            4 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp_nppn"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp_nppn"),
            ),
//            "3" => array(
//                "in_word" => array("inWordInd" => "hpp_nppn"),
//            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
            ),
            4 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
            ),
        ),
        "print_nvalas" => true,
        "lockerStock" => "MdlLockerStock",
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
//                    "nomer_top" => "nomer",// nomer PRE-PO
                    "nomer" => "nomer PO",// nomer PO
                    "dtime" => "date approved",
                    "oleh_nama" => "approval",
                    "suppliers_nama" => "supplier nama",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "harga",
                    ),
                ),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "produk nama",
                    "produk_kode" => "produk kode",
                    "suppliers_nama" => "supplier nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "purchase",
                    "ord_sent_qty" => "diterima",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    "produk_id" => "PID",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
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

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            "harga" => "unit price",
            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
            //            "billingDetails__nik" => "nik",
            //            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
            "ppn" => "VAT",
            "hpp_nppn" => "Grand Total",
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

        "noteWarning" => array(
            2 => "Khusus transaksi FG/Supplies Purchasing, Approval langsung menutup PRE-PO.",
        ),
        "receiptSwictItems" => true,
    ),

    // diskon supplier dadakan
    "3344" => array(
        "receiptTemplate" => array(
            1 => "template/466r.html",
            2 => "template/466.html",
        ),
        "headerNota" => array(
            "vendor" => array(
                "suppliers_nama" => "",
                "tlp_1" => "phone",
                "alamat_1" => "",
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
                "nomer" => "Nomer",
                "dtime" => "Tanggal",
//                "shippingDate_value" => "Delivery Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "capacity_nama" => "Capacity",
            ),
            2 => array(
                "nomer" => "Nomer",
                "nomer_top" => "PRE Nomer",
                "dtime" => "Tanggal",
//                "shippingDate_value" => "Delivery Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos_nama" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
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
        "headerTables" => array(
            "produk_nama" => "description",
            "produk_kode" => "product number",
            "produk_ord_hrg" => "unit price",
            "produk_ord_jml" => "qty",
            "sub_total" => "total price",
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
                "barcode" => "sku",
                "referensi_item_supplier" => "ref",
                "produk_nama" => "Description",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
                "note" => "keterangan",
            ),
            2 => array(
                "barcode" => "sku",
                "referensi_item_supplier" => "ref",
                "produk_nama" => "Description",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receipCartNumFields" => array(
            1 => array(
                "harga_kompensasi" => "Unit Price",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga_kompensasi" => "Unit Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga_kompensasi" => "Unit Price",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga_kompensasi" => "Unit Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga_kompensasi" => "Total",
//                "ppn" => "VAT",
//                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga_kompensasi" => "Total",
//                "ppn" => "VAT",
//                "hpp_nppn" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(),
        "printLocation" => "Printing/viewReceiptReg/",
        "staticNotes" => array(
            1 => true,
            2 => true,
        ),
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
            4 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            1 => array(
                "in_word" => array("inWordInd" => "hpp_nppn"),
            ),
            2 => array(
                "in_word" => array("inWordInd" => "hpp_nppn"),
            ),
//            "3" => array(
//                "in_word" => array("inWordInd" => "hpp_nppn"),
//            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(//                "note" => "keterangan",
            ),
            2 => array(//                "sub_harga" => "Total Price",
            ),
            3 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
            ),
            4 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
            ),
        ),
        "print_nvalas" => true,
        "lockerStock" => "MdlLockerStock",
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
//                    "nomer_top" => "nomer",// nomer PRE-PO
                    "nomer" => "nomer PO",// nomer PO
                    "dtime" => "date approved",
                    "oleh_nama" => "approval",
                    "suppliers_nama" => "supplier nama",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "harga",
                    ),
                ),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "produk nama",
                    "produk_kode" => "produk kode",
                    "suppliers_nama" => "supplier nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "purchase",
                    "ord_sent_qty" => "diterima",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    "produk_id" => "PID",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
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

        "reviewDetailCompactListsLabel" => array(
            "nama" => "Description",
            "produk_kode" => "part no",
            "qty" => "qty",
            "satuan" => "UOM",

            "harga" => "unit price",
            "subtotal" => "total price",

        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "vendorDetails__nama" => "name",
            "vendorDetails__alamat_1" => "address",
            "vendorDetails__tlp_1" => "phone",
            "vendorDetails__tlp_2" => "handphone",
            "vendorDetails__npwp" => "npwp",
            //            "billingDetails__nik" => "nik",
            //            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "*" => "-",
            "-" => "-",
            "harga" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "harga" => "Total Amount",
            "ppn" => "VAT",
            "hpp_nppn" => "Grand Total",
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
        "fixedNote" => "Diskon tambahan<br>
Diskon tambahan ada beberapa jenis yang bisa dikompensasikan dengan HPP agar HPP riil turun, dan menjadikan harga jual bisa bersaing dengan kompetitor<br>
1. Diskon tambahan yang berelasi dengan PO<br>
2. Diskon tambahan yang berelasi dengan produk tertentu saja yg diberikan diskon<br>
3. Diskon tambahan berupa barang dagangan yang bisa dijual kembali, dengan atau tanpa PPN dalam perolehannya<br>
ada 1 jenis lagi diskon yang tidak bisa direlasikan dengan apapun, misalnya mendapat hadiah undian, diskon semacam ini hanya akan menjadi pendapatan lain lain saja<br>
            ",
        "noteWarning" => array(
            2 => "Khusus transaksi FG/Supplies Purchasing, Approval langsung menutup PRE-PO.",
        ),
    ),


);