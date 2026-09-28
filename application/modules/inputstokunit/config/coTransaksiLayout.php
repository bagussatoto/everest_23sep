<?php

$config["coTransaksiLayout"] = array(
    "6698" => array(
        "receiptTemplate" => array(
            1 => "template/466r.html",
            2 => "template/466.html",
            3 => "template/467.html",
            4 => "template/467.html",
            5 => "template/651.html",
        ),
        "headerNota" => array(
//            "vendor" => array(
//                "suppliers_nama" => "",
//                "tlp_1" => "phone",
//                "alamat_1" => "",
//            ),
//            "delivery addrress" => array(
//                "dtime" => "date",
//                "suppliers_nama" => "Supplier",
//                "tlp_1" => "phone",
//                "alamat_1" => "address",
//                "dtime_jatuh_tempo" => "jatuh tempo",
//                "pembayaran" => "payment method",
//            ),
            "purchase order" => array(
                "nomer" => "receipt no.",
                "currency" => "currency",
//                "devlivery_date" => "delivery date",
//                "top" => "term of payment",
//                "tos" => "term of shipment",
//                "capacity" => "address",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "Nomer PRE Input Stok",
                "dtime" => "Tanggal",
//                "shippingDate_value" => "Delivery Date",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
                //                "tos_nama" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "Nomer Input Stok",
                "nomer_top" => "Nomer PRE Input Stok",
                //                "nomer_top" => "No pre PO",
                "dtime" => "Tanggal",
//                "shippingDate_value" => "Delivery Date",
                //                "paymentMethod_name" => "Payment method",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
//                "tos_nama" => "Term of Shipment",
//                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            3 => array(
                "nomer" => "Nomer Input Stok",
                "nomer_top" => "Nomer PRE Input Stok",
                //                "nomer_top" => "No pre PO",
                "dtime" => "Tanggal",
//                "shippingDate_value" => "Delivery Date",
                //                "paymentMethod_name" => "Payment method",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment method",
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
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            4 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            5 => array(
                "barcode" => "sku",
                "produk_nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "Product code",
                "keterangan" => "part",
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


);