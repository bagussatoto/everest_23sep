<?php

/**
 * Created by PhpStorm.
 * User: chepy
 * Date: 10/23/2021
 * Time: 13:16 PM
 */

$config["coTransaksiLayout"] = array(

    //config aset puircahing request
    "421" => array(
        "receiptTemplate" => array(
            1 => "template/421r.html",//466r
            2 => "template/421.html",//466
            3 => "template/423.html",
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
                    "label" => ".Supplier",
                    "contents" => "vendorDetails_nama",
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Supplier",
                    "contents" => "vendorDetails_nama",
                ),
            ),
            3 => array(
                "vendor" => array(
                    "label" => ".Supplier",
                    "contents" => "vendorDetails_nama",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "aset name",
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
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptDetailFields2" => array(
            3 => array(
                "nama" => "nama",
                "label" => "label",
                "merk" => "merk",
                "serial_no" => "nomer seri",
                "kode" => "kode",
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
                "harga_other" => "total amount",
//                "dpp_vat" => "DPP VAT",
                "ppn" => "VAT",
                "nett" => "grand total",
            ),
            2 => array(
                "harga_other" => "total amount",
//                "dpp_vat" => "DPP VAT",
                "ppn" => "VAT",
                "nett" => "grand total",
            ),
            3 => array(
                "harga_other" => "total amount",
//                "dpp_vat" => "DPP VAT",
                "ppn" => "VAT",
                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
//                "non_ppn" => "Non PPN<br>PPN (-)",
                "other" => "other (+)",
            ),
            2 => array(
                "harga" => "Price",
                "other" => "other (+)",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
                "harga" => "Price",
                "other" => "other (+)",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga" => "Total Price",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        "staticFooter" => array(),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptAddDpp"=>array(
            1=>array(
                "ppn"=>array(
                    "dpp_pengganti"=>"DPP(Tax Basis)"
                ),
            ),
            2=>array(
                "ppn"=>array(
                    "dpp_pengganti"=>"DPP(Tax Basis)"
                ),
            ),
            3=>array(
                "ppn"=>array(
                    "dpp_pengganti"=>"DPP(Tax Basis)"
                ),
            ),
            4=>array(
                "ppn"=>array(
                    "dpp_pengganti"=>"DPP(Tax Basis)"
                ),
            ),
            5=>array(
                "ppn"=>array(
                    "dpp_pengganti"=>"DPP(Tax Basis)"
                ),
            ),


        ),
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
        //--------------

    ),

    //config penambahan aset langasung ke modal
    "422" => array(
        "receiptTemplate" => array(
            1 => "template/423.html",
            2 => "template/423.html",
            3 => "template/423.html",
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
            "produk_nama" => "aset name",
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
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptDetailFields2" => array(
            2 => array(
                "nama" => "nama",
                "label" => "label",
                "merk" => "merk",
                "serial_no" => "nomer seri",
                "kode" => "kode",
//                "deskripsi" => "deskripsi",
                "harga" => "harga",
            ),
            3 => array(
                "nama" => "nama",
                "label" => "label",
                "merk" => "merk",
                "serial_no" => "nomer seri",
                "kode" => "kode",
                "harga" => "harga",
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
        "receiptNumFields" => array(
            1 => array(
//                "harga" => "Price",
////                "non_ppn" => "Non PPN<br>PPN (-)",
//                "other" => "other (+)",
            ),
            2 => array(
//                "harga" => "Price",
//                "other" => "other (+)",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
            ),
            2 => array(
                "harga" => "total amount",

            ),
            3 => array(
                "harga" => "total amount",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(//                "harga" => "total amount",
            ),
            2 => array(//                "harga" => "total amount",

            ),
            3 => array(//                "harga" => "total amount",
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


    //distribusi aset pusat
    "2483" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang2_nama" => "branch",
                "pihakMainName" => "jenis"
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
                "cabang2_nama" => "branch",
                "pihakMainName" => "jenis"
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
                "produk_nama" => "asset name",
                "merk" => "merk",
                "kode" => "product code",
                "serial_no" => "serial no",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "asset name",
                "merk" => "merk",
                "kode" => "product code",
                "serial_no" => "serial no",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
//                "hpp" => "price",
//                "harga" => "price",
            ),
            2 => array(
//                "hpp" => "price",
//                "harga" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
        ),
        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            //            "in_word" => array("inWordInd" => "hpp",),

        ),
        "lockerStock" => "MdlLockeAktiva",
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "date approved",
                    "oleh_nama" => "approval",
                    "cabang_nama" => "dari",
                    "cabang2_nama" => " tujuan",
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
                    "produk_nama" => "produk nama",
                    "produk_kode" => "produk kode",
                    "cabang2_nama" => "cabang nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "request",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "cabang2_nama" => "cabang2_nama",
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
    ),

    //penerimaan distribusi aset di cabang
    "2485" => array(
        "receiptTemplate" => array(
            1 => "template/583r.html",
            2 => "template/583.html",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang2_nama" => "branch",
                ""
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
                "cabang2_nama" => "branch",
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

                "produk_nama" => "product/folder", "merk" => "merk",
                "kode" => "kode",
                "serial_no" => "serial no",
                "produk_ord_jml" => "qty",
//                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
            2 => array(

                "produk_nama" => "product/folder", "merk" => "merk",
                "kode" => "kode",
                "serial_no" => "serial no",
                "produk_ord_jml" => "qty",
//                "satuan" => "UOM",
                //                "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(//                "hpp" => "grand total",
            ),
            2 => array(//                "hpp" => "grand total",
            ),
        ),
        "reportSumFields" => array(
            "cabang2_id" => "cabang2_nama",
        ),
        "receiptNumFields" => array(
            1 => array(//                "harga" => "price",
            ),
            2 => array(//                "harga" => "price",
            ),
            3 => array(
                "harga" => "price",
            ),
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "hpp"),
            ),
            //            "in_word" => array("inWordInd" => "hpp",),

        ),
        "lockerStock" => "MdlLockerStock",
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "date approved",
                    "oleh_nama" => "approval",
                    "cabang_nama" => "dari",
                    "cabang2_nama" => " tujuan",
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
                    "produk_nama" => "produk nama",
                    "produk_kode" => "produk kode",
                    "cabang2_nama" => "cabang nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "request",
                    "ord_sent_qty" => "dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "stok avail",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top" => "nomer_top",
                    "cabang2_nama" => "cabang2_nama",
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
    ),

    //config depresiasi PUSAT
    "8786" => array(
        "receiptTemplate" => array(
            1 => "template/8786r.html",
            2 => "template/8786.html",
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
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
//        ),
            ),
            2 => array(
//            "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
//            ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "asset name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "id" => "pid",
                "kode" => "kode",
                "serial_no" => "no seri",
                "merk" => "merk",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),

    //config depresiasi
    "8787" => array(
        "receiptTemplate" => array(
            1 => "template/8787r.html",
            2 => "template/8787.html",
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
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
//        ),
            ),
            2 => array(
//            "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
//            ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "asset name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "name",
                //                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),

    "8788" => array(
        "receiptTemplate" => array(
            1 => "template/675r.html",
            2 => "template/675.html",
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
                "cabang_nama" => "branch",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "cabang_nama" => "branch",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
//            ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
//        ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
//            ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "expense name",
            "produk_ord_hrg" => "amount",
            //            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "cabang_nama" => "branch",
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
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
    ),

    // penjualan asset
    "8789" => array(
        "receiptTemplate" => array(
            1 => "template/8789r.html",
            2 => "template/8789.html",
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
        "customButton" => array(
            1 => array(
                1 => array(
                    "label" => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            2 => array(
                1 => array(
                    "label" => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
        ),
        "elementFixedNumberSO" => array(
            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing Address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment Method",
                "shippingDate_value" => "Delivery Date",
                "shippingService_name" => "shipping service",
            ),
            2 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "paymentMethod_name" => "Payment Method",
                "top_nama" => "Term of Payment",
                "shippingDate_value" => "Delivery Date",
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "product name",
            "produk_kode" => "product no",
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
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
        ),
        "receiptNumFields" => array(
            1 => array(
                "nett1" => "Price",
            ),
            2 => array(
                "nett1" => "Price",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1" => "Price",
            ),
            2 => array(
                "nett1" => "Price",
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
//                "ongkir_ui" => "Shipping Service",
//                "nilai_pembulatan" => "pembulatan",

                "new_net1" => "Total Amount",
                "nilai_ppn" => "vat",
                "tagihan_ui" => "Grand Total",
            ),
            2 => array(
//                "ongkir_ui" => "Shipping Service",
//                "nilai_pembulatan" => "pembulatan",

                "new_net1" => "Total Amount",
                "nilai_ppn" => "vat",
                "tagihan_ui" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
        ),
        "staticFooter" => array(
//            2 => "SAN/F/SA001/R00",
//            3 => "SAN/F/LOG001/R00",
//            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "tagihan_ui",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "tagihan_ui",),
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
                ),
                "loop" => array(),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    "produk_nama" => "product",
                    "produk_kode" => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top" => "Transaksi",
                    "ord_qty" => "Order",
                    "ord_sent_qty" => "Dikirim",
                    "ord_valid_qty" => "Outstanding",
                    "stok" => "Tersedia",
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
    //config sewa
    "424" => array(
        "receiptTemplate" => array(
            1 => "template/424r.html",
            2 => "template/424.html",
            3 => "template/425.html",
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
                "nomer" => "No PO",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment method",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "pihakMainRulesName" => "Vat"
            ),
            2 => array(
                "nomer" => "No PO",
                "dtime" => "Date",
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
                ),
            ),
            2 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                ),
            ),
            3 => array(
                "vendor" => array(
                    "label" => ".Confirmed & Acknowledged by",
                    "contents" => "vendorDetails_nama",
                ),
            ),
        ),
        "headerTables" => array(
            "produk_nama" => "aset name",
            "produk_ord_jml" => "jumlah",
            "produk_ord_hrg" => "amount",
            "sub_total" => "sub amount",
        ),
        "receiptMainFields" => array(
            "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "produk_nama" => "aset name",
                "produk_ord_jml" => "qty",
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
                                "harga" => "Unit Price",
                //                "ppn" => "VAT",
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
                                "harga" => "Unit Price",
                                "ppn" => "VAT",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total amount",
                "pph_value" => "pph",
                "ppn" => "VAT",
                "nett" => "grand total",
            ),
            2 => array(
                "harga" => "total amount",
                "pph_value" => "pph",
                "ppn" => "pph",
                "nett" => "grand total",
            ),
            3 => array(
                "harga" => "total amount",
                "pph_value" => "pph",
                "ppn" => "VAT",
                "nett" => "grand total",
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
            //            1 => array(
            //                "size" => "normal",
            //            ),

        ),
    ),

);