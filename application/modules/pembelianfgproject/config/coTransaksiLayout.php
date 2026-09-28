<?php

$config["coTransaksiLayout"] = array(
    "1466" => array(
        "receiptTemplate" => array(
//            1 => "template/466r.html",
//            2 => "template/466.html",
//            3 => "template/467.html",
//            4 => "template/651.html",

            1 => "template/463_mod_1.html",
            2 => "template/463_mod_2.html",
            3 => "template/463_mod.html",
            4 => "template/463_mod.html",
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
                "nomer" => "PRE-PO Number",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                //                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "PO Number",
                "nomer_top" => "PRE-PO Number",
                //                "nomer_top" => "No pre PO",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "paymentMethod_name" => "Payment method",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            3 => array(
                "nomer" => "PRE GRN Number",
                "referenceNomer__2" => "PO Number",
                "nomer_top" => "PRE-PO Number",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "description_main_followup" => "Vendor INV",
            ),
            4 => array(
                "nomer" => "GRN Number",
                "referenceNomer__3" => "PRE-GRN Number",
                "referenceNomer__2" => "PO Number",
                "nomer_top" => "PRE-PO Number",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
//                "description_main_followup" => "Vendor INV",
            ),
            5 => array(
                "nomer" => "No.",
                //                "nomers_prev" => "PO Number",
                //                "nomer_top" => "PRE-PO Number",
                "dtime" => "Date",
                "eFaktur" => "e-Faktur",
                //                "top_nama" => "Term of Payment",
                //                "paymentMethod_name" => "Payment method",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
                "sign_1" => true,
                "sign_2" => false,
                "sign_3" => false,
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
                "sign_1" => true,
                "sign_2" => true,
                "sign_3" => false,
            ),
            3 => array(
                // "vendor" => array(
                //     "label" => ".Confirmed & Acknowledged by",
                //     "contents" => "vendorDetails_nama",
                //     //                "caption_department" => "",
                // ),
                "vendor" => false,
                "sign_1" => true,
                "sign_2" => true,
                "sign_3" => true,
            ),
            4 => array(
                // "vendor" => array(
                //     "label" => ".Confirmed & Acknowledged by",
                //     "contents" => "vendorDetails_nama",
                //     //                "caption_department" => "",
                // ),
                "vendor" => false,
                "sign_1" => true,
                "sign_2" => true,
                "sign_3" => true,
            ),
            5 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
                "sign_1" => false,
                "sign_2" => false,
                "sign_3" => false,
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
        "headerField" => "heTransaksi_layout",
        "receiptDetailFields" => array(
            1 => array(
                "produk_kode" => "sku",
                // "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                // "produk_kode" => "Product code",
                "keterangan" => "part number",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                // "satuan" => "UOM",
            ),
            2 => array(
                // "barcode" => "sku",
                "produk_kode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                // "produk_kode" => "Product code",
                "keterangan" => "part number",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                // "satuan" => "UOM",
            ),
            3 => array(
                // "barcode" => "sku",
                "produk_kode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                // "produk_kode" => "Product code",
                "keterangan" => "part number",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                // "satuan" => "UOM",
            ),
            4 => array(
                // "barcode" => "sku",
                "produk_kode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                // "produk_kode" => "Product code",
                "keterangan" => "part number",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                // "satuan" => "UOM",
            ),
            5 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part number",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receipCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
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
        "receipNumFields" => array(
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
                "harga" => "Unit Price",
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
            4 => array(
                "harga" => "Total Amount",
                "ppn" => "VAT",
                "hpp_nppn" => "Grand Total",
            ),
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
            4 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "receiptShowDiskon" => array(
            2 => true,
            4 => true,
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
        "receiptAddDetail" => array(
            /*
             * untuk penampil diskon pembelian free produk
             */
            1 => array(
                "produk_nama" => "promo pembelian",
                "qty_min" => "pembelian minimum",
                "produk_rel_nama" => "produk",
                "qty" => "qty",
                "produk_rel_satuan_nama" => "satuan",
                "produk_rel_harga" => "harga",
                "subtotal" => "subtotal",
            ),
            2 => array(
                "produk_nama" => "promo pembelian",
                "qty_min" => "pembelian minimum",
                "produk_rel_nama" => "produk",
                "qty" => "qty",
                "produk_rel_satuan_nama" => "satuan",
                "produk_rel_harga" => "harga",
                "subtotal" => "subtotal",
            ),
            3 => array(
                "produk_nama" => "promo pembelian",
                "qty_min" => "pembelian minimum",
                "produk_rel_nama" => "produk",
                "qty" => "qty",
                "produk_rel_satuan_nama" => "satuan",
                "produk_rel_harga" => "harga",
                "subtotal" => "subtotal",
            ),
        ),
    ),
    "9967" => array(
        "receiptTemplate" => array(
            1 => "template/463_mod_1.html",
            2 => "template/463_mod_2.html",
            3 => "template/463_mod.html",
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
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            3 => array(
                "nomer" => "No.",
                "nomer_top" => "PO No.",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
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
            "produk_ord_hrg" => "Dpp",
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
        "headerField" => "heTransaksi_layout",
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
//                "hpp" => "DPP",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
//                "hpp" => "DPP",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
//                "hpp" => "DPP",
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
            3 => array(
                "harga" => "amount",
                "ppn" => "VAT",
                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
//                "harga" => "DPP",
//                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
//                "harga" => "DPP",
//                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
//                "harga" => "DPP",
//                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nett",),
        //
        //        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett"),
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
    ),

    "19967" => array(
        "receiptTemplate" => array(
//            1 => "template/967r.html",
//            2 => "template/967r.html",
            1 => "template/463_mod_1.html",
            2 => "template/463_mod_2.html",
            3 => "template/463_mod.html",
            4 => "template/463_mod.html",
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
                "satuan" => "uom",
                "harga" => "price",
                //            "ppn" => "ppn",
            ),
            2 => array(
                "produk_nama" => "product name",
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
//    //supplies
//    "461" => array(
//        "receiptTemplate" => array(
//            1 => "template/461ro.html",
//            2 => "template/461r.html",
//            3 => "template/461.html",
//            4 => "template/461.html",
//
//        ),
//        "headerNota" => array(
//            "dtime" => "date",
//            "suppliers_nama" => "Supplier",
//            "tlp_1" => "phone",
//            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
//        ),
//        "fixedElements" => array(
//            1 => array(
//                "nomer" => "PRE-PO Number",
//                "dtime" => "Date",
//                "valas_ord_nama" => "Currency",
//                //                "shippingDate_value" => "Delivery Date",
//                //                "paymentMethod_name" => "Term of Payment",
//                //                "paymentMethod_name" => "Term of Payment",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
//                //                "dueDate_value" => "Due Date",
//            ),
//            2 => array(
//                "nomer" => "PO Number",
//                "nomer_top" => "PRE-PO Number",
//                "dtime" => "Date",
//                "shippingDate_value" => "Delivery Date",
//                //                "paymentMethod_name" => "Term of Payment",
//                //                "paymentMethod_name" => "Term of Payment",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos__name" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
//                //                "dueDate_value" => "Due Date",
//            ),
//            3 => array(
//                "nomer" => "Invoice No",
//                "nomers_prev" => "PO Number",
//                "nomer_top" => "PRE-PO Number",
//                "dtime" => "Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "dueDate_value" => "Due Date",
////                "description_main_followup" => "Vendor INV",
//            ),
//        ),
//        "fixedSignatures" => array(
//            1 => array(
//                "vendor" => array(
//                    "label" => ".Confirmed & Acknowledged by",
//                    "contents" => "vendorDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//            2 => array(
//                "vendor" => array(
//                    "label" => ".Confirmed & Acknowledged by",
//                    "contents" => "vendorDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//            3 => array(
//                "vendor" => array(
//                    "label" => ".Confirmed & Acknowledged by",
//                    "contents" => "vendorDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//        ),
//        "headerTables" => array(
//            "produk_nama" => "product name",
//            "produk_ord_hrg" => "price",
//            "produk_ord_jml" => "jumlah",
//            "sub_total" => "sub total",
//        ),
//        "receiptMainFields" => array(
//            "jenis_label" => "activity",
//            "nomer" => "reference no.",
//            "result_nomer" => "receipt no.",
//            "suppliers_nama" => "vendor",
//            "dtime" => "date",
//        ),
//        "receiptDetailFields" => array(
//            1 => array(
//                "produk_nama" => "Description",
//                "produk_kode" => "Part No.",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "produk_nama" => "Description",
//                "produk_kode" => "Part No.",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            3 => array(
//                "produk_nama" => "Description",
//                "produk_kode" => "Part No.",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            4 => array(
//                "produk_nama" => "Description",
//                "produk_kode" => "Part No.",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//        ),
//        "subAmountValue" => array(
//            1 => "jml*(harga)",//nett2
//            2 => "jml*(harga)",
//            3 => "jml*(harga)",
//            4 => "jml*(harga)",
//        ),
//        "receipNumFields" => array(
//            1 => array(
//                "harga" => "Unit Price",
//                //                "disc" => "disc",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            2 => array(
//                //                "stok" => "stok",
//                "harga" => "Unit Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            3 => array(
//                "harga" => "Unit Price",
//                //                "stok" => "stok",
//                //                "harga" => "price",
//                //                "ppn"   => "VAT",
//            ),
//            4 => array(
//                "harga" => "Unit Price",
//                //                "stok" => "stok",
//                //                "harga" => "price",
//                //                "ppn"   => "VAT",
//            ),
//        ),
//        "receiptNumFields" => array(
//            1 => array(
//                "harga" => "Unit Price",
//                //                "disc" => "disc",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            2 => array(
//                //                "stok" => "stok",
//                "harga" => "Unit Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            3 => array(
//                "harga" => "Unit Price",
//                //                "stok" => "stok",
//                //                "harga" => "price",
//                //                "ppn"   => "VAT",
//            ),
//            4 => array(
//                "harga" => "Unit Price",
//                //                "stok" => "stok",
//                //                "harga" => "price",
//                //                "ppn"   => "VAT",
//            ),
//        ),
//        // "customButton" => array(
//        //     // 1 => array(
//        //     //     1 => array(
//        //     //         "label" => "Export SO",
//        //     //         "target" => "ExcelWriter/exp/",
//        //     //     ),
//        //     // ),
//        //     3 => array(
//        //         1 => array(
//        //             "label" => "test",
//        //             // "target" => "ExcelWriter/exp/",
//        //             "target" => "#",
//        //         ),
//        //     ),
//        // ),
//        //        "receiptNumFields" => array(
//        //            1 => array(
//        //                "harga" => "Unit<br>Price",
//        //                "discPersen" => "DISC<br>(%)",
//        //                "disc" => "DISC<br>(Rp)",
//        //                "ppnPersen" => "VAT<br>(%)",
//        //                "ppn" => "VAT<br>(Rp)",
//        //            ),
//        //            2 => array(
//        //                "harga" => "Unit<br>Price",
//        //                "discPersen" => "DISC(%)",
//        //                "disc" => "DISC(Rp)",
//        //                "ppnPersen" => "VAT(%)",
//        //                "ppn" => "VAT(Rp)",
//        //            ),
//        //            3 => array(
//        //                "harga" => "Unit<br>Price",
//        //                "discPersen" => "DISC(%)",
//        //                "disc" => "DISC(Rp)",
//        //                "ppnPersen" => "VAT(%)",
//        //                "ppn" => "VAT(Rp)",
//        //            ),
//        //            4 => array(
//        //                "harga" => "Unit Price",
//        //                "discPersen" => "DISC(%)",
//        //                "disc" => "DISC(Rp)",
//        //                "ppnPersen" => "VAT(%)",
//        //                "ppn" => "VAT(Rp)",
//        //            ),
//        //        ),
//        "receiptSumFields" => array(
//            1 => array(
//                "harga" => "<sub><r>(excl VAT)</r></sub><br>Total Amount",
//                "disc" => "DISC",
//                "ppn" => "VAT*",
//                "nett" => "Total",
//            ),
//            2 => array(
//                "harga" => "<sub><r>(excl VAT)</r></sub><br>Total Amount",
//                "disc" => "DISC",
//                "ppn" => "VAT",
//                "nett" => "Total",
//            ),
//            3 => array(
//                "harga" => "<sub><r>(excl VAT)</r></sub><br>Total Amount",
//                "disc" => "DISC",
//                "ppn" => "VAT",
//                "nett" => "Total",
//            ),
//            4 => array(
//                "harga" => "<sub><r>(excl VAT)</r></sub><br>Total Amount",
//                "disc" => "DISC",
//                "ppn" => "VAT",
//                "nett" => "Total",
//            ),
//        ),
//        "reportSumFields" => array(
//            "suppliers_id" => "suppliers_nama",
//
//        ),
//        "printLocation" => "Printing/viewReceiptReg/",
//        "staticFooter" => array(
//            1 => "SAN/F/PUR002/R00",
//            2 => "SAN/F/PUR002/R00",
//        ),
//        "receiptInword" => array(
//            "in_word" => array("inWordInd" => "nett",),
//
//        ),
//        "allowPrint" => array(
//            1 => array(
//                "size" => "normal",
//            ),
//            2 => array(
//                "size" => "normal",
//            ),
//            3 => array(
//                "size" => "normal",
//            ),
//        ),
//        "receiptSumDetailFields" => array(
//            1 => array(
//                "sub_harga" => "Total Price",
//            ),
//            2 => array(
//                "sub_harga" => "Total Price",
//            ),
//            3 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
//            ),
//            4 => array(//                "sub_harga" => "Total Price*",//tak matiin grn ndak ada total price
//            ),
//        ),
//        "lockerStock" => "MdlLockerStockSupplies",
//        "fixedFieldHold" => array(
//            "transaksi" => array(
//                "label" => "transaksi",
//                "target" => "transaksi",
//                "srcKey" => "id_master",
//                "fields" => array(
////                    "nomer_top" => "nomer",// nomer PRE-PO
//                    "nomer" => "nomer PO",// nomer PO
//                    "dtime" => "date approved",
//                    "oleh_nama" => "approval",
//                    "suppliers_nama" => "supplier nama",
//                    "outstanding_items" => "detail items",
//                    "sub_outstanding_items" => "nilai",
//                    //                    "print_label" =>"tool",
//                ),
//                "loop" => array(),
//                "items" => array(
//                    "outstanding_items" => array(
//                        "harga",
//                    ),
//                ),
//            ),
//            "produk" => array(
//                "label" => "produk",
//                "target" => "produk",
//                "srcKey" => "produk_id",
//                "fields" => array(
//                    "produk_nama" => "produk nama",
//                    "produk_id" => "part no/pid",
//                    "suppliers_nama" => "supplier nama",
//                    "nomer_top" => "Transaksi PO",
//                    "ord_qty" => "purchase",
//                    "ord_sent_qty" => "diterima",
//                    "ord_valid_qty" => "Outstanding",
//                    "stok" => "stok avail",
//                    //                    "print_label" =>"tool",
//                ),
//                "loop" => array(
//                    "customers_nama" => "suppliers_nama",
//                    "nomer_top" => "nomer_top",
//                    //                    "produk_id" =>"produk_id",
//                    "ord_qty" => "produk_ord_jml",
//                    "ord_valid_qty" => "valid_qty",
//                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
//                ),
//
//            ),
//            "supplier" => array(
//                "label" => "supplier",
//                "target" => "supplier",
//                "srcKey" => "suppliers_id",
//                "fields" => array(
//                    "suppliers_nama" => "supplier",
//                    "nomer_top" => "Transaksi PO",
//                    "produk_id" => "part no/pid",
//                    "produk_nama" => "produk_nama",
//                    "produk_ord_jml" => "PURCHASE",
//                    "ord_sent_qty" => "DITERIMA",
//                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
//                ),
//                "loop" => array(
//                    "nomer_top" => "nomer_top",
//                    "produk_nama" => "produk_nama",
//                    "produk_id" => "produk_id",
//                    "produk_ord_jml" => "produk_ord_jml",
//                    "ord_sent_qty" => "produk_ord_jml-valid_qty",
//                    "ord_valid_qty" => "valid_qty",
//                ),
//                "array_flip" => array(
//                    1,
//                ),
//            ),
//
//        ),
//
//        "reviewDetailCompactListsLabel" => array(
//            "nama" => "Description",
//            "produk_kode" => "part no",
//            "qty" => "qty",
//            "satuan" => "UOM",
//
//            "harga" => "unit price",
//            "subtotal" => "total price",
//
//        ),
//        "reviewMainCompactListsLabel" => array(
//            "nomer" => "Nomer",
//            "vendorDetails__nama" => "name",
//            "vendorDetails__alamat_1" => "address",
//            "vendorDetails__tlp_1" => "phone",
//            "vendorDetails__tlp_2" => "handphone",
//            "vendorDetails__npwp" => "npwp",
//            //            "billingDetails__nik" => "nik",
//            //            "valas_nama" => "currency",
//        ),
//        "reviewCompactListDetailSum" => array(
//            "qty" => "qty",
//            "*" => "-",
//            "-" => "-",
//            "harga" => "grand total",
//        ),
//        "reviewCompactListSum" => array(
//            "harga" => "Total Amount",
//            "ppn" => "VAT",
//            "hpp_nppn" => "Grand Total",
//        ),
//        "reviewAddRows" => array(
//            //            "top__nama" => "pembayaran",
//            //            "dp" => "downpayment",
//            //            "paymentMethod" => "paymentMethod",
//        ),
//        "reviewSign" => array(
//            1 => array(
//                "sign_1",
//            ),
//            2 => array(
//                "sign_1",
//                "sign_2",
//            ),
//        ),
//
//        "noteWarning" => array(
//            2 => "Khusus transaksi FG/Supplies Purchasing, Approval langsung menutup PRE-PO.",
//        ),
//    ),
//
//    //  config return pembelian supplies
//    "961" => array(
//        "receiptTemplate" => array(
//            1 => "template/961r.html",
//            2 => "template/961.html",
//        ),
//        "headerNota" => array(
//            "dtime" => "date",
//            "suppliers_nama" => "Supplier",
//            "tlp_1" => "phone",
//            "alamat_1" => "address",
//            "dtime_jatuh_tempo" => "jatuh tempo",
//            "pembayaran" => "payment method",
//        ),
//        "fixedElements" => array(
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
//                "nomer" => "No.",
//                "nomer_top" => "PO No.",
//                "dtime" => "Date",
//                "shippingDate_value" => "Delivery Date",
//                "top_nama" => "Term of Payment",
//                "tos_nama" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
//                "dueDate_value" => "Due Date",
//            ),
//        ),
//        "fixedSignatures" => array(
//            1 => array(
//                "vendor" => array(
//                    "label" => ".Confirmed & Acknowledged by",
//                    "contents" => "vendorDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//            2 => array(
//                "vendor" => array(
//                    "label" => ".Confirmed & Acknowledged by",
//                    "contents" => "vendorDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//            3 => array(
//                "vendor" => array(
//                    "label" => ".Confirmed & Acknowledged by",
//                    "contents" => "vendorDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//        ),
//        "headerTables" => array(
//            "produk_nama" => "product name",
//            "produk_ord_hrg" => "price",
//            "produk_ord_jml" => "jumlah",
//            "sub_total" => "sub total",
//        ),
//        "receiptMainFields" => array(
//            "jenis_label" => "activity",
//            "nomer" => "reference no.",
//            "result_nomer" => "receipt no.",
//            "suppliers_nama" => "vendor",
//            "dtime" => "date",
//        ),
//        "receiptDetailFields" => array(
//            1 => array(
//                "produk_nama" => "item name",
//                "produk_ord_jml" => "qty",
//                "satuan" => "uom",
//                //                "hpp"            => "price",
//                //            "ppn" => "ppn",
//            ),
//            2 => array(
//                "produk_nama" => "item name",
//                "produk_ord_jml" => "qty",
//                "satuan" => "uom",
//                //                "hpp"            => "price",
//                //            "ppn" => "ppn",
//            ),
//        ),
//        "receiptSumFields" => array(
//            1 => array(
//                //                "hpp" => "amount",
//                //                "bruto" => "amount",
//                "harga" => "amount",
//                "ppn" => "VAT",
//                "nett" => "grand total",
//            ),
//            2 => array(
//                //                "hpp" => "amount",
//                //                "bruto" => "amount",
//                "harga" => "amount",
//                "ppn" => "VAT",
//                "nett" => "grand total",
//            ),
//        ),
//        "receiptNumFields" => array(
//            1 => array(
//                "harga" => "Price",
//                "ppn" => "VAT",
//                //            "avail" => "current stock",
//            ),
//            2 => array(
//                "harga" => "Price",
//                "ppn" => "VAT",
//                //            "avail" => "current stock",
//            ),
//        ),
//        "reportSumFields" => array(
//            "suppliers_id" => "suppliers_nama",
//
//        ),
//        "printLocation" => "Printing/viewReceipt/",
//        "allowPrint" => array(
//            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//        ),
//
//        "receiptInword" => array(
//            "1" => array(
//                "in_word" => array("inWordInd" => "nett"),
//            ),
//            "2" => array(
//                "in_word" => array("inWordInd" => "nett"),
//            ),
//            "3" => array(
//                "in_word" => array("inWordInd" => "nett"),
//            ),
//        ),
//
//        "reviewDetailCompactListsLabel" => array(
//            "nama" => "Description",
//            "produk_kode" => "part no",
//            "qty" => "qty",
//            "satuan" => "UOM",
//
//            "harga" => "unit price",
//            "subtotal" => "total price",
//
//        ),
//        "reviewMainCompactListsLabel" => array(
//            "nomer" => "Nomer",
//            "vendorDetails__nama" => "name",
//            "vendorDetails__alamat_1" => "address",
//            "vendorDetails__tlp_1" => "phone",
//            "vendorDetails__tlp_2" => "handphone",
//            "vendorDetails__npwp" => "npwp",
//            //            "billingDetails__nik" => "nik",
//            //            "valas_nama" => "currency",
//        ),
//        "reviewCompactListDetailSum" => array(
//            "qty" => "qty",
//            "*" => "-",
//            "-" => "-",
//            "harga" => "grand total",
//        ),
//        "reviewCompactListSum" => array(
//            "harga" => "Total Amount",
//            "ppn" => "VAT",
//            "hpp_nppn" => "Grand Total",
//        ),
//        "reviewAddRows" => array(
//            //            "top__nama" => "pembayaran",
//            //            "dp" => "downpayment",
//            //            "paymentMethod" => "paymentMethod",
//        ),
//        "reviewSign" => array(
//            1 => array(
//                "sign_1",
//            ),
//            2 => array(
//                "sign_1",
//                "sign_2",
//            ),
//        ),
//    ),
//    "1961" => array(
//        "receiptTemplate" => array(
//            1 => "template/961r.html",
//            2 => "template/961r.html",
//        ),
//        "headerNota" => array(
//            "dtime" => "date",
//            "suppliers_nama" => "Vendor",
//            "tlp_1" => "phone",
//            "alamat_1" => "address",
//            //            "dtime_jatuh_tempo" => "jatuh tempo",
//            //            "pembayaran" => "payment method",
//        ),
//        "fixedElements" => array(
//            1 => array(
//                "nomer" => "No",
//                "dtime" => "Date",
//                "transaksiDatas__nomer" => "PO No.",
//                //                "shippingDate_value" => "Delivery Date",
//                //                "top_nama" => "Term of Payment",
//                //                "tos_nama" => "Term of Shipment",
//                //                "capacity_nama" => "Capacity",
//                //                "dueDate_value" => "Due Date",
//            ),
//            2 => array(
//                "nomer" => "No.",
//                "dtime" => "Date",
//                "transaksiDatas__nomer" => "PO No.",
//                //                "shippingDate_value" => "Delivery Date",
//                //                "top_nama" => "Term of Payment",
//                //                "tos_nama" => "Term of Shipment",
//                //                "capacity_nama" => "Capacity",
//                //                "dueDate_value" => "Due Date",
//            ),
//        ),
//        "fixedSignatures" => array(
//            1 => array(
//                "vendor" => array(
//                    "label" => ".Confirmed & Acknowledged by",
//                    "contents" => "vendorDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//            2 => array(
//                "vendor" => array(
//                    "label" => ".Confirmed & Acknowledged by",
//                    "contents" => "vendorDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//        ),
//        "headerTables" => array(
//            "produk_nama" => "item name",
//            "produk_ord_hrg" => "price",
//            "produk_ord_jml" => "jumlah",
//            "sub_total" => "sub total",
//        ),
//        "receiptMainFields" => array(
//            "jenis_label" => "activity",
//            "nomer" => "reference no.",
//            "result_nomer" => "receipt no.",
//            "suppliers_nama" => "vendor",
//            "dtime" => "date",
//        ),
//        "receiptDetailFields" => array(
//            1 => array(
//                "produk_nama" => "item name",
//                "produk_ord_jml" => "qty",
//                "satuan" => "uom",
//                "harga" => "price",
//                //            "ppn" => "ppn",
//            ),
//            2 => array(
//                "produk_nama" => "item name",
//                "produk_ord_jml" => "qty",
//                "satuan" => "uom",
//                "harga" => "price",
//                //            "ppn" => "ppn",
//            ),
//        ),
//        "receipNumFields" => array(
//            1 => array(
//                //                "harga" => "Unit Price",
//                //                                "disc_percent" => "disc (%)",
//                //                                "disc" => "disc (IDR)",
//                //                                "ppn" => "VAT",
//                //                            "avail" => "current stock",
//            ),
//            2 => array(
//                //                "harga" => "Unit Price",
//                //                                "disc_percent" => "disc (%)",
//                //                                "disc" => "disc (IDR)",
//                //                                "ppn" => "VAT",
//                //                            "avail" => "current stock",
//            ),
//        ),
//        "receiptSumFields" => array(
//            1 => array(
//                "harga" => "amount",
//                "ppn" => "VAT",
//                "nett" => "grand total",
//            ),
//            2 => array(
//                "harga" => "amount",
//                "ppn" => "VAT",
//                "nett" => "grand total",
//            ),
//        ),
//        "receiptNumFields" => array(
//            1 => array(),
//            2 => array(),
//        ),
//        "receiptSumDetailFields" => array(
//            1 => array(
//                "sub_harga" => "Total Price",
//            ),
//            2 => array(
//                "sub_harga" => "Total Price",
//            ),
//
//        ),
//        "reportSumFields" => array(
//            "suppliers_id" => "suppliers_nama",
//        ),
//        "printLocation" => "Printing/viewReceiptReg/",
//        "allowPrint" => array(
//            //            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//        ),
//        "receiptInword" => array(
//            1 => array(//                "in_word" => array("inWordInd" => "nett2"),
//            ),
//        ),
//
//    ),

    //supplies
    "1461" => array(
        "receiptTemplate" => array(
            //            1 => "template/461ro.html",
            //            2 => "template/461r.html",
            //            3 => "template/461.html",
            //            4 => "template/461.html",
            1 => "template/466r_mod.html",
            2 => "template/466r_mod.html",
            3 => "template/466r_mod.html",
            4 => "template/466r_mod.html",
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
                "nomer" => "PRE-PO Number",
                "dtime" => "Date",
                "valas_ord_nama" => "Currency",
                //                "shippingDate_value" => "Delivery Date",
                //                "paymentMethod_name" => "Term of Payment",
                //                "paymentMethod_name" => "Term of Payment",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "PO Number",
                "nomer_top" => "PRE-PO Number",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "paymentMethod_name" => "Term of Payment",
                //                "paymentMethod_name" => "Term of Payment",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos__name" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            3 => array(
                "nomer" => "Invoice No",
                "nomers_prev" => "PO Number",
                "nomer_top" => "PRE-PO Number",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "dueDate_value" => "Due Date",
                //                "description_main_followup" => "Vendor INV",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
                "sign_1" => array(
                    "label" => ".Prepare By",
                ),
                "sign_3" => false,
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
                "sign_1" => array(
                    "label" => ".prepare by",
                ),
                "sign_3" => array(
                    "label" => "Approval By",
                ),
            ),
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                    //                "caption_department" => "",
                ),
                "sign_1" => array(
                    "label" => ".prepare by",
                ),
                "sign_3" => array(
                    "label" => "Approval By",
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
                "produk_nama" => "Description",
                "produk_kode" => "Part No.",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_nama" => "Description",
                "produk_kode" => "Part No.",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "produk_nama" => "Description",
                "produk_kode" => "Part No.",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            4 => array(
                "produk_nama" => "Description",
                "produk_kode" => "Part No.",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "subAmountValue" => array(
            1 => "jml*(harga)",//nett2
            2 => "jml*(harga)",
            3 => "jml*(harga)",
            4 => "jml*(harga)",
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                "harga" => "Unit Price",
                //                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                "harga" => "Unit Price",
                //                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
        ),
        // "customButton" => array(
        //     // 1 => array(
        //     //     1 => array(
        //     //         "label" => "Export SO",
        //     //         "target" => "ExcelWriter/exp/",
        //     //     ),
        //     // ),
        //     3 => array(
        //         1 => array(
        //             "label" => "test",
        //             // "target" => "ExcelWriter/exp/",
        //             "target" => "#",
        //         ),
        //     ),
        // ),
        //        "receiptNumFields" => array(
        //            1 => array(
        //                "harga" => "Unit<br>Price",
        //                "discPersen" => "DISC<br>(%)",
        //                "disc" => "DISC<br>(Rp)",
        //                "ppnPersen" => "VAT<br>(%)",
        //                "ppn" => "VAT<br>(Rp)",
        //            ),
        //            2 => array(
        //                "harga" => "Unit<br>Price",
        //                "discPersen" => "DISC(%)",
        //                "disc" => "DISC(Rp)",
        //                "ppnPersen" => "VAT(%)",
        //                "ppn" => "VAT(Rp)",
        //            ),
        //            3 => array(
        //                "harga" => "Unit<br>Price",
        //                "discPersen" => "DISC(%)",
        //                "disc" => "DISC(Rp)",
        //                "ppnPersen" => "VAT(%)",
        //                "ppn" => "VAT(Rp)",
        //            ),
        //            4 => array(
        //                "harga" => "Unit Price",
        //                "discPersen" => "DISC(%)",
        //                "disc" => "DISC(Rp)",
        //                "ppnPersen" => "VAT(%)",
        //                "ppn" => "VAT(Rp)",
        //            ),
        //        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "<sub><r>(excl VAT)</r></sub><br>Total Amount",
                "disc" => "DISC",
                "ppn" => "VAT*",
                "nett" => "Total",
            ),
            2 => array(
                "harga" => "<sub><r>(excl VAT)</r></sub><br>Total Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett" => "Total",
            ),
            3 => array(
                "harga" => "<sub><r>(excl VAT)</r></sub><br>Total Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett" => "Total",
            ),
            4 => array(
                "harga" => "<sub><r>(excl VAT)</r></sub><br>Total Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett" => "Total",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "staticFooter" => array(
            //            1 => "SAN/F/PUR002/R00",
            //            2 => "SAN/F/PUR002/R00",
        ),
        "receiptInword" => array(
            "in_word" => array("inWordInd" => "nett",),

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
        "lockerStock" => "MdlLockerStockSupplies",
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
                    "produk_nama" => "produk nama",
                    "produk_id" => "part no/pid",
                    "suppliers_nama" => "supplier nama",
                    "nomer_top" => "Transaksi PO",
                    "ord_qty" => "purchase",
                    "ord_sent_qty" => "diterima",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "suppliers_nama",
                    "nomer_top" => "nomer_top",
                    //                    "produk_id" =>"produk_id",
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
                    "produk_id" => "part no/pid",
                    "produk_nama" => "produk_nama",
                    "produk_ord_jml" => "PURCHASE",
                    "ord_sent_qty" => "DITERIMA",
                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
                ),
                "loop" => array(
                    "nomer_top" => "nomer_top",
                    "produk_nama" => "produk_nama",
                    "produk_id" => "produk_id",
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


);