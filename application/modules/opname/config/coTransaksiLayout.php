<?php

$config["coTransaksiLayout"] = array(

    // stok opname produk pusat
    "1119" => array(
        "receiptTemplate" => array(
            1 => "template/1119r.html",
            2 => "template/1119r.html",
            3 => "template/1119.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
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
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock riil",
            ),
            2 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock riil",
            ),
            3 => array(
                "hpp" => "price",
                "stok" => "Stok buku",
                // "hpp*stok" => "value awal",
                "qty_debet" => "QTY masuk",
                // "hpp*qty_debet" => "value masuk",
                "debet" => "Nilai Masuk",
                "qty_kredit" => "QTY Keluar",
                // "hpp*qty_kredit" => "value keluar-",
                "nilai_kredit" => "Nilai Keluar",
                "qty_opname" => "Stock Akhir",
                // "hpp*qty_opname" => "value akhir",
                // "kredit" => "value akhir",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "Product ID",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
            2 => array(
                "id" => "Product ID",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
            3 => array(
                "id" => "Product ID",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
        ),

        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receipSumFields" => array(
            1 => array(
                "stok"=>"stok",
                "qty_debet"=>"qty_debet",
                "qty_kredit"=>"qty_kredit",
                "qty_opname"=>"qty_opname",
                // "debet" => "debet",
                // "nilai_kredit" => "kredit",
            ),
            2 => array(
                "stok"=>"stok",
                "qty_debet"=>"qty_debet",
                "qty_kredit"=>"qty_kredit",
                "qty_opname"=>"qty_opname",
                "debet" => "debet",
                "nilai_kredit" => "kredit",
            ),
            3 => array(
                "stok"=>"stok",
                "qty_debet"=>"qty_debet",
                "qty_kredit"=>"qty_kredit",
                "qty_opname"=>"qty_opname",
                "debet" => "debet",
                "nilai_kredit" => "kredit",
                ),

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
//        "allowPrint" => array(
//            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
//        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
    ),
    // stok opname produk cabang
    "2229" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583r.html",
            3 => "template/583.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
//        "customButton" => array(
//            1 => array(
//                1 => array(
//                    "label" => "Export SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//                // 2 => array(
//                //     "label" => "Export SO Browwwww",
//                //     "target" => "ExcelWriter/exp/",
//                // ),
//            ),
//            2 => array(
//                1 => array(
//                    "label" => "Export APP SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//            3 => array(
//                1 => array(
//                    "label" => "Export PRE PACKING",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//        ),
//        "elementFixedNumberSO" => array(
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),
//
//            3 => array(
//                "nomer" => "No",
//            ),
//            4 => array(
//                "nomer" => "No",
//            ),
//            5 => array(
//                "nomer" => "INV No",
//            ),
//        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
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
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
//                "hpp" => "price",
//                "stok" => "stok buku",
//                "qty_debet" => "Qty Db",
//                "qty_kredit" => "Qty Cr",
//                "qty_opname" => "stock riil",
                "stok" => "stok awal",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock akhir",
            ),
            2 => array(
//                "hpp" => "price",
//                "stok" => "stok buku",
//                "qty_debet" => "Qty Db",
//                "qty_kredit" => "Qty Cr",
//                "qty_opname" => "stock riil",
                "stok" => "stok awal",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock akhir",
            ),
            3 => array(
                "hpp" => "price",
                "stok" => "stok awal",
                "hpp*stok" => "value awal",
                "qty_debet" => "penyesuaian (+)",
                "hpp*qty_debet" => "value masuk",
                "qty_kredit" => "penyesuaian (-)",
                "hpp*qty_kredit" => "value keluar",
                "qty_opname" => "stock akhir",
                "hpp*qty_opname" => "value akhir",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
//        "allowPrint" => array(
//            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
//        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
    ),
    // stok opname supplies pusat
    "1118" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583r.html",
            3 => "template/583.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
//        "customButton" => array(
//            1 => array(
//                1 => array(
//                    "label" => "Export SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//                // 2 => array(
//                //     "label" => "Export SO Browwwww",
//                //     "target" => "ExcelWriter/exp/",
//                // ),
//            ),
//            2 => array(
//                1 => array(
//                    "label" => "Export APP SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//            3 => array(
//                1 => array(
//                    "label" => "Export PRE PACKING",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//        ),
//        "elementFixedNumberSO" => array(
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),
//
//            3 => array(
//                "nomer" => "No",
//            ),
//            4 => array(
//                "nomer" => "No",
//            ),
//            5 => array(
//                "nomer" => "INV No",
//            ),
//        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
//            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Db",
                "qty_kredit" => "Qty Cr",
                "qty_opname" => "stock riil",
            ),
            2 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Db",
                "qty_kredit" => "Qty Cr",
                "qty_opname" => "stock riil",
            ),
            3 => array(
//                "hpp" => "price",
                "stok" => "stok awal",
                "qty_debet" => "stok masuk",
                "qty_kredit" => "stok keluar",
                "qty_opname" => "stock akhir",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            3 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
    ),
    // stok opname supplies bom
    "2228" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583r.html",
            3 => "template/583.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
//        "customButton" => array(
//            1 => array(
//                1 => array(
//                    "label" => "Export SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//                // 2 => array(
//                //     "label" => "Export SO Browwwww",
//                //     "target" => "ExcelWriter/exp/",
//                // ),
//            ),
//            2 => array(
//                1 => array(
//                    "label" => "Export APP SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//            3 => array(
//                1 => array(
//                    "label" => "Export PRE PACKING",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//        ),
//        "elementFixedNumberSO" => array(
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),
//
//            3 => array(
//                "nomer" => "No",
//            ),
//            4 => array(
//                "nomer" => "No",
//            ),
//            5 => array(
//                "nomer" => "INV No",
//            ),
//        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
//            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Db",
                "qty_kredit" => "Qty Cr",
                "qty_opname" => "stock riil",
            ),
            2 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Db",
                "qty_kredit" => "Qty Cr",
                "qty_opname" => "stock riil",
            ),
            3 => array(
//                "hpp" => "price",
                "stok" => "stok awal",
                "qty_debet" => "stok masuk",
                "qty_kredit" => "stok keluar",
                "qty_opname" => "stock akhir",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Debet",
                "qty_kredit" => "Qty Credit",
                "qty_opname" => "stock riil",
            ),
            2 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Debet",
                "qty_kredit" => "Qty Credit",
                "qty_opname" => "stock riil",
            ),
            3 => array(
//                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Debet",
                "qty_kredit" => "Qty Credit",
                "qty_opname" => "stock riil",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            3 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
        "fixedNoteTop" => "Transaksi stok opname supplies ini hanya digunakan untuk adjustment supplies yang berkaitan dengan Produksi/BOM.",
    ),
    // stok opname supplies non bom
    "2227" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583r.html",
            3 => "template/583.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
//        "customButton" => array(
//            1 => array(
//                1 => array(
//                    "label" => "Export SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//                // 2 => array(
//                //     "label" => "Export SO Browwwww",
//                //     "target" => "ExcelWriter/exp/",
//                // ),
//            ),
//            2 => array(
//                1 => array(
//                    "label" => "Export APP SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//            3 => array(
//                1 => array(
//                    "label" => "Export PRE PACKING",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//        ),
//        "elementFixedNumberSO" => array(
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),
//
//            3 => array(
//                "nomer" => "No",
//            ),
//            4 => array(
//                "nomer" => "No",
//            ),
//            5 => array(
//                "nomer" => "INV No",
//            ),
//        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
//            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Db",
                "qty_kredit" => "Qty Cr",
                "qty_opname" => "stock riil",
            ),
            2 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Db",
                "qty_kredit" => "Qty Cr",
                "qty_opname" => "stock riil",
            ),
            3 => array(
//                "hpp" => "price",
                "stok" => "stok awal",
                "qty_debet" => "stok masuk",
                "qty_kredit" => "stok keluar",
                "qty_opname" => "stock akhir",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            3 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
        "fixedNoteTop" => "Transaksi stok opname supplies ini hanya digunakan untuk adjustment supplies yang TIDAK berkaitan dengan Produksi/BOM.",
    ),
    // stok opname produk cabang
    "3339" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583r.html",
            3 => "template/583.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
//        "customButton" => array(
//            1 => array(
//                1 => array(
//                    "label" => "Export SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//                // 2 => array(
//                //     "label" => "Export SO Browwwww",
//                //     "target" => "ExcelWriter/exp/",
//                // ),
//            ),
//            2 => array(
//                1 => array(
//                    "label" => "Export APP SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//            3 => array(
//                1 => array(
//                    "label" => "Export PRE PACKING",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//        ),
//        "elementFixedNumberSO" => array(
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),
//
//            3 => array(
//                "nomer" => "No",
//            ),
//            4 => array(
//                "nomer" => "No",
//            ),
//            5 => array(
//                "nomer" => "INV No",
//            ),
//        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
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
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
//                "hpp" => "price",
//                "stok" => "stok buku",
//                "qty_debet" => "Qty Db",
//                "qty_kredit" => "Qty Cr",
//                "qty_opname" => "stock riil",
                "stok" => "stok awal",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock akhir",
            ),
            2 => array(
//                "hpp" => "price",
//                "stok" => "stok buku",
//                "qty_debet" => "Qty Db",
//                "qty_kredit" => "Qty Cr",
//                "qty_opname" => "stock riil",
                "stok" => "stok awal",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock akhir",
            ),
            3 => array(
                "hpp" => "price",
                "stok" => "stok awal",
                "hpp*stok" => "value awal",
                "qty_debet" => "penyesuaian (+)",
                "hpp*qty_debet" => "value masuk",
                "qty_kredit" => "penyesuaian (-)",
                "hpp*qty_kredit" => "value keluar",
                "qty_opname" => "stock akhir",
                "hpp*qty_opname" => "value akhir",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_kode" => "Product No.",
//                "produk_nama" => "Description",
//                "kode" => "Product No.",
                "nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_kode" => "Product No.",
//                "produk_nama" => "Description",
//                "kode" => "Product No.",
                "nama" => "Description",
                "satuan" => "UOM",
            ),
            3 => array(
                "produk_kode" => "Product No.",
//                "produk_nama" => "Description",
//                "kode" => "Product No.",
                "nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
//        "allowPrint" => array(
//            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
//        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
    ),
    "5559" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583r.html",
            3 => "template/583.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
//        "customButton" => array(
//            1 => array(
//                1 => array(
//                    "label" => "Export SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//                // 2 => array(
//                //     "label" => "Export SO Browwwww",
//                //     "target" => "ExcelWriter/exp/",
//                // ),
//            ),
//            2 => array(
//                1 => array(
//                    "label" => "Export APP SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//            3 => array(
//                1 => array(
//                    "label" => "Export PRE PACKING",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//        ),
//        "elementFixedNumberSO" => array(
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),
//
//            3 => array(
//                "nomer" => "No",
//            ),
//            4 => array(
//                "nomer" => "No",
//            ),
//            5 => array(
//                "nomer" => "INV No",
//            ),
//        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
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
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
//                "hpp" => "price",
//                "stok" => "stok buku",
//                "qty_debet" => "Qty Db",
//                "qty_kredit" => "Qty Cr",
//                "qty_opname" => "stock riil",
                "stok" => "stok awal",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock akhir",
            ),
            2 => array(
//                "hpp" => "price",
//                "stok" => "stok buku",
//                "qty_debet" => "Qty Db",
//                "qty_kredit" => "Qty Cr",
//                "qty_opname" => "stock riil",
                "stok" => "stok awal",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock akhir",
            ),
            3 => array(
                "hpp" => "price",
                "stok" => "stok awal",
                "hpp*stok" => "value awal",
                "qty_debet" => "penyesuaian (+)",
                "hpp*qty_debet" => "value masuk",
                "qty_kredit" => "penyesuaian (-)",
                "hpp*qty_kredit" => "value keluar",
                "qty_opname" => "stock akhir",
                "hpp*qty_opname" => "value akhir",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_kode" => "Product No.",
//                "produk_nama" => "Description",
//                "kode" => "Product No.",
                "nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_kode" => "Product No.",
//                "produk_nama" => "Description",
//                "kode" => "Product No.",
                "nama" => "Description",
                "satuan" => "UOM",
            ),
            3 => array(
                "produk_kode" => "Product No.",
//                "produk_nama" => "Description",
//                "kode" => "Product No.",
                "nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
//        "allowPrint" => array(
//            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
//        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
    ),


    // stok opname supplies pusat project
    "4418" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583r.html",
            3 => "template/583.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
//        "customButton" => array(
//            1 => array(
//                1 => array(
//                    "label" => "Export SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//                // 2 => array(
//                //     "label" => "Export SO Browwwww",
//                //     "target" => "ExcelWriter/exp/",
//                // ),
//            ),
//            2 => array(
//                1 => array(
//                    "label" => "Export APP SO",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//            3 => array(
//                1 => array(
//                    "label" => "Export PRE PACKING",
//                    "target" => "ExcelWriter/exp/",
//                ),
//            ),
//        ),
//        "elementFixedNumberSO" => array(
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),
//
//            3 => array(
//                "nomer" => "No",
//            ),
//            4 => array(
//                "nomer" => "No",
//            ),
//            5 => array(
//                "nomer" => "INV No",
//            ),
//        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
//            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Db",
                "qty_kredit" => "Qty Cr",
                "qty_opname" => "stock riil",
            ),
            2 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "Qty Db",
                "qty_kredit" => "Qty Cr",
                "qty_opname" => "stock riil",
            ),
            3 => array(
//                "hpp" => "price",
                "stok" => "stok awal",
                "qty_debet" => "stok masuk",
                "qty_kredit" => "stok keluar",
                "qty_opname" => "stock akhir",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            3 => array(
                "id" => "pID",
//                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
    ),
    // stok opname produk pusat project
    "4419" => array(
        "receiptTemplate" => array(
            1 => "template/1119r.html",
            2 => "template/1119r.html",
            3 => "template/1119.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "date",
                "customers_nama" => "Customer",
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
                "cabang_nama" => "Branch",

            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "Branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "part number",
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
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock riil",
            ),
            2 => array(
                "hpp" => "price",
                "stok" => "stok buku",
                "qty_debet" => "penyesuaian (+)",
                "qty_kredit" => "penyesuaian (-)",
                "qty_opname" => "stock riil",
            ),
            3 => array(
                "hpp" => "price",
                "stok" => "Stok buku",
                // "hpp*stok" => "value awal",
                "qty_debet" => "QTY masuk",
                // "hpp*qty_debet" => "value masuk",
                "debet" => "Nilai Masuk",
                "qty_kredit" => "QTY Keluar",
                // "hpp*qty_kredit" => "value keluar-",
                "nilai_kredit" => "Nilai Keluar",
                "qty_opname" => "Stock Akhir",
                // "hpp*qty_opname" => "value akhir",
                // "kredit" => "value akhir",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "receiptDetailFields" => array(
            1 => array(
                "id" => "Product ID",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
            2 => array(
                "id" => "Product ID",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
            3 => array(
                "id" => "Product ID",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "UOM",
            ),
        ),

        "receiptSumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),
        ),
        "receipSumFields" => array(
            1 => array(
                "stok"=>"stok",
                "qty_debet"=>"qty_debet",
                "qty_kredit"=>"qty_kredit",
                "qty_opname"=>"qty_opname",
                // "debet" => "debet",
                // "nilai_kredit" => "kredit",
            ),
            2 => array(
                "stok"=>"stok",
                "qty_debet"=>"qty_debet",
                "qty_kredit"=>"qty_kredit",
                "qty_opname"=>"qty_opname",
                "debet" => "debet",
                "nilai_kredit" => "kredit",
            ),
            3 => array(
                "stok"=>"stok",
                "qty_debet"=>"qty_debet",
                "qty_kredit"=>"qty_kredit",
                "qty_opname"=>"qty_opname",
                "debet" => "debet",
                "nilai_kredit" => "kredit",
            ),

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
//        "allowPrint" => array(
//            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
//        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "tagihan",),
            ),
        ),
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1" => "phone",
            "customerDetails__tlp_2" => "handphone",
            "customerDetails__npwp" => "npwp",
            "billingDetails__nik" => "nik",
            "valas_nama" => "currency",
        ),
        "reviewCompactListDetailSum" => array(
            "qty" => "qty",
            "jual" => "jual",
            "disc" => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum" => array(
            "shipping_service" => "shipping service",
            "grand_total_ui" => "total amount",
            "grand_ppn" => "VAT 10%",
            "new_net3" => "grand total",
        ),
        "reviewAddRows" => array(
            "top__nama" => "pembayaran",
            "dp" => "downpayment",
            "paymentMethod" => "paymentMethod",
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
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
            "customer" => array(
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode" => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty" => "dikirim",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
    ),
);