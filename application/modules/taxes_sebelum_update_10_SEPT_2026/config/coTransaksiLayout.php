<?php

$config["coTransaksiLayout"] = array(

    "681" => array(
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
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "subtotal" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            //            3 => array(
            //                "harga" => "amount",
            ////                "ppn" => "VAT",
            ////                "nett" => "grand total",
            //            ),
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
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    //pph22
    "5681" => array(
        "receiptTemplate" => array(
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
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "subtotal" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            //            3 => array(
            //                "harga" => "amount",
            ////                "ppn" => "VAT",
            ////                "nett" => "grand total",
            //            ),
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
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    "110" => array(
        "receiptTemplate" => array(
            1 => "template/110r.html",
            2 => "template/110e.html",
            3 => "template/110.html",
        ),
        "headerNota" => array(
            "e faktur" => array(
                "nomer" => "receipt no.",
                "efakturSource" => "inv",
                "eFaktur" => "e-faktur",
                "dateFaktur" => "date",

            ),

        ),
        "customButton" => array(
            1 => array(
                1 => array(
                    "label" => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Export INVOICE",
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

            3 => array(
                "nomer" => "No",
            ),
            4 => array(
                "nomer" => "No",
            ),
            5 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "efaktur_source" => "inv",
                //                "eFaktur" => "e-faktur",
                //                "dateFaktur" => "date",
            ),
            2 => array(
                "nomer" => "No",
                //                "nomer_top" => "SO No.",
                "efaktur_source" => "INV",
                "eFaktur" => "E-Faktur",
                "dateFaktur" => "Date",
            ),

            3 => array(
                "nomer" => "No",
                //                "nomer_top" => "SO No.",
                "efaktur_source" => "INV",
                "eFaktur" => "E-Faktur",
                "dateFaktur" => "Date Faktur",
            ),

        ),
        "fixedSignatures" => array(),
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
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1" => "Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "nett1" => "Price",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(
//                "ongkir" => "Shipping Service",
                "new_net1" => "Sub Amount",
                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat 11%",
//                "total_ui" => "Sub Total",
//                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT 11%",
                "tagihan" => "Grand total",
            ),
            2 => array(
//                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat 11%",
//                "total_ui" => "Sub Amount",
//                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT 11%",
//                "nett1_bulat" => "total Amount",
                "tagihan" => "Grand total",
            ),
            3 => array(
//                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat 11%",
//                "total_ui" => "Sub Amount",
//                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT 11%",
//                "nett1_bulat" => "total Amount",
                "tagihan" => "Grand total*",
            ),
        ),
        "receipSumFields" => array(
            1 => array(
//                "ongkir" => "Shipping Service",
                "new_net1" => "Sub Amount",
                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
//                "dp_ppn_value" => "Dp Vat 11%",
//                "total_ui" => "Sub Total",
//                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT",
                "tagihan" => "Grand total*",
            ),
            2 => array(
//                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
//                "dp_ppn_value" => "Dp Vat 11%",
//                "total_ui" => "Sub Amount",
//                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT",
//                "nett1_bulat" => "total Amount",
                "tagihan" => "Grand total*",
            ),
            3 => array(
//                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
//                "dp_ppn_value" => "Dp Vat 11%",
//                "total_ui" => "Sub Amount",
//                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT",
//                "nett1_bulat" => "total Amount",
                "tagihan" => "Grand total*",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "receiptAddMain" => array(
            1 => array(
                "nomer" => "INV",
                "dateFaktur" => "Tgl faktur ",
                "eFaktur" => "Nomer faktur",
                "new_net1" => "DPP",
                "new_grand_ppn" => "PPN",
                //                    "nilai_faktur" => "nilai(RP)",
            ),
            2 => array(
                "efaktur_source" => "INV",
                "dateFaktur" => "Tgl faktur ",
                "eFaktur" => "nomer faktur",
                "new_net1" => "DPP",
                "new_grand_ppn" => "PPN",
            ),
            3 => array(
                "efaktur_source" => "INV",
                "dateFaktur" => "Tgl faktur ",
                "eFaktur" => "nomer faktur",
                "new_net1" => "DPP",
                "new_grand_ppn" => "PPN",
            ),
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
//            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//            3 => array("size" => "normal"),
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
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            //            "2" => array(
            //                "in_word" => array("inWordInd" => "new_net3",),
            //            ),
            //            "3" => array(),
            //            "4" => array(),
            //            "5" => array(
            //                "in_word" => array("inWordInd" => "tagihan",),
            //            ),
        ),
        "receiptInword2" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
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
            "grand_ppn" => "VAT 11%",
            "new_net3" => "grand total",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        "receiptDetailFieldsGunggung" => array(
//            1 => array(
//                "customers_nama" => "konsumen",
//                "referensi_so_nomer" => "nomer so",
//                "produk_nama" => "nomer pre-faktur",
////                "stok" => "stok",
//                "jml" => "qty",
//                "satuan" => "uom",
//                "dpp" => "DPP",
//                "grand_ppn" => "PPN",
//                "dpp_nppn" => "Subtotal",
//            ),
//            2 => array(
//                "customers_nama" => "konsumen",
//                "referensi_so_nomer" => "nomer so",
//                "produk_nama" => "nomer pre-faktur",
////                "stok" => "stok",
//                "jml" => "qty",
//                "satuan" => "uom",
//                "dpp" => "DPP",
//                "grand_ppn" => "PPN",
//                "dpp_nppn" => "Subtotal",
//            ),
//            3 => array(
//                "customers_nama" => "konsumen",
//                "referensi_so_nomer" => "nomer so",
//                "produk_nama" => "nomer pre-faktur",
////                "stok" => "stok",
//                "jml" => "qty",
//                "satuan" => "uom",
//                "dpp" => "DPP",
//                "grand_ppn" => "PPN",
//                "dpp_nppn" => "Subtotal",
//            ),
            1 => array(
                "customers_nama" => "konsumen",
                "referensi_so_nomer" => "nomer so",
                "referensi_spd_nomer" => "nomer packinglist",
                "produk_nama" => "nomer pre-faktur",
                "jml" => "qty",
                "satuan" => "uom",
                "dpp" => "DPP",
                "grand_ppn" => "PPN",
                "dpp_nppn" => "Subtotal",
            ),
            2 => array(
                "customers_nama" => "konsumen",
                "referensi_so_nomer" => "nomer so",
                "referensi_spd_nomer" => "nomer packinglist",
                "produk_nama" => "nomer pre-faktur",
                "jml" => "qty",
                "satuan" => "uom",
                "dpp" => "DPP",
                "grand_ppn" => "PPN",
                "dpp_nppn" => "Subtotal",
            ),
            3 => array(
                "customers_nama" => "konsumen",
                "referensi_so_nomer" => "nomer so",
                "referensi_spd_nomer" => "nomer packinglist",
                "produk_nama" => "nomer pre-faktur",
                "jml" => "qty",
                "satuan" => "uom",
                "dpp" => "DPP",
                "grand_ppn" => "PPN",
                "dpp_nppn" => "Subtotal",
            ),
        ),
    ),
    "111" => array(
        "receiptTemplate" => array(
            1 => "template/110r.html",
            2 => "template/110e.html",
            3 => "template/110.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",

        ),
        "customButton" => array(
            1 => array(
                1 => array(
                    "label" => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Export INVOICE",
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

            3 => array(
                "nomer" => "No",
            ),
            4 => array(
                "nomer" => "No",
            ),
            5 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
            ),

            3 => array(
                "nomer" => "No",
                //                "nomer_top" => "SO No.",
                "efaktur_source" => "INV",
                "eFaktur" => "E-Faktur",
                "dateFaktur" => "Date",
            ),

        ),
        "fixedSignatures" => array(),
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
            "suppliers_nama" => "supplier/vendor",
            "dtime" => "date",
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
            1 => "sisa",
            2 => "sisa",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "sisa" => "Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "sisa" => "Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
//            2 => array(
//                //                "stok" => "stok",
//                "harga" => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            3 => array(
//                "nett1" => "Price",
//                //                "harga" => "price",
//                //                "ppn"   => "VAT",
//            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
            ),
            2 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
//                "dpp" => "Qty",
                //                "satuan" => "uom",
            ),

            3 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "tagihan bruto",
                "selisih_koreksi" => "Koreksi -",
                "selisih_koreksi_plus" => "Koreksi +",
                "uang_muka_dipakai_ppn" => "Uangmuka + ppn",
                "dpp_final" => "Dpp",
                "ppn_belum_faktur" => "Ppn",
            ),
            2 => array(
                "sisa" => "tagihan bruto",
                "selisih_koreksi" => "Koreksi -",
                "selisih_koreksi_plus" => "Koreksi +",
                "uang_muka_dipakai_ppn" => "Uangmuka + ppn",
                "dpp_final" => "Dpp",
                "ppn_belum_faktur" => "Ppn",
            ),

            3 => array(
                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat 11%",
                "total_ui" => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT 11%",
                "nett1_bulat" => "total Amount",
                "tagihan" => "Grand total",
            ),

        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "reportSumFields" => array(//            "customers_id" => "customers_nama",

        ),
        "receiptAddMain" => array(
            1 => array(
//                "nomer" => "INV",
                "dateFaktur" => "Tgl faktur ",
                "eFaktur" => "nomer faktur",
                "dpp_final" => "DPP",
                "ppn_belum_faktur" => "PPN",
                "ppn_nilai_faktur" => "Total PPN (E-FAKTUR)",
                //                    "nilai_faktur" => "nilai(RP)",
            ),
            2 => array(
//                "efaktur_source" => "INV",
                "dateFaktur" => "Tgl faktur ",
                "eFaktur" => "nomer faktur",
                "dpp_final" => "DPP",
                "ppn_belum_faktur" => "PPN",
                "ppn_nilai_faktur" => "Total PPN (E-FAKTUR)",
            ),
            3 => array(
                "efaktur_source" => "INV",
                "dateFaktur" => "Tgl faktur ",
                "eFaktur" => "nomer faktur",
                "total_ui" => "DPP",
                "new_grand_ppn" => "PPN",
                "ppn_nilai_faktur" => "Total PPN (E-FAKTUR)",
            ),
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            5 => array("size" => "normal"),
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
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            //            "2" => array(
            //                "in_word" => array("inWordInd" => "new_net3",),
            //            ),
            //            "3" => array(),
            //            "4" => array(),
            //            "5" => array(
            //                "in_word" => array("inWordInd" => "tagihan",),
            //            ),
        ),
        "receiptInword2" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
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
            "grand_ppn" => "VAT 11%",
            "new_net3" => "grand total",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        "receiptEfakturFields" => array(
            1 => array(
                "eFaktur" => "Nomer Faktur",
                "dateFaktur" => "Tanggal Faktur",
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti (DPP Lain)",
                "ppn_final" => "PPN",
//                "ppn_nilai_faktur" => "Total PPN (E-FAKTUR)",
            ),
        ),

        "receiptMultiItemFields" => array(
            1 => array(
                "nomer_cek" => "Pre-Entry eFaktur",
                "transaksi_ref_po_nomer" => "Referensi PO",
                "nomer_top" => "Referensi Pembayaran",

            ),
            2 => array(
                "nomer_cek" => "Pre-Entry eFaktur",
                "transaksi_ref_po_nomer" => "Referensi PO",
                "nomer_top" => "Referensi Pembayaran",

            ),
            3 => array(
                "nomer_cek" => "Pre-Entry eFaktur",
                "transaksi_ref_po_nomer" => "Referensi PO",
                "nomer_top" => "Referensi Pembayaran",

            ),
            4 => array(
                "nomer_cek" => "Pre-Entry eFaktur",
                "transaksi_ref_po_nomer" => "Referensi PO",
                "nomer_top" => "Referensi Pembayaran",

            ),
        ),
        "receiptMultiItemNumFields" => array(
            1 => array(
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti",
                "ppn_final" => "PPN",
                "tagihan_bayar" => "Subtotal",

            ),
            2 => array(
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti",
                "ppn_final" => "PPN",
                "tagihan_bayar" => "Subtotal",

            ),
            3 => array(
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti",
                "ppn_final" => "PPN",
                "tagihan_bayar" => "Subtotal",

            ),
            4 => array(
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti",
                "ppn_final" => "PPN",
                "tagihan_bayar" => "Subtotal",

            ),
        ),
        "receiptMultiItemSumFields" => array(
            1 => array(
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti",
                "ppn_final" => "PPN",
                "tagihan_bayar" => "Subtotal",

            ),
            2 => array(
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti",
                "ppn_final" => "PPN",
                "tagihan_bayar" => "Subtotal",

            ),
            3 => array(
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti",
                "ppn_final" => "PPN",
                "tagihan_bayar" => "Subtotal",

            ),
            4 => array(
                "dpp_final" => "DPP",
                "dpp_pengganti" => "DPP Pengganti",
                "ppn_final" => "PPN",
                "tagihan_bayar" => "Subtotal",

            ),
        ),

    ),
    //---keatas sudah modul
    //config otorisasi ppn keluaran pusat

    // config pembayaran hutang gaji ke cabang
    "1483" => array(
        "receiptTemplate" => array(
            1 => "application/template/1483.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "pembayaran" => "payment method",
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
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
            "cabang2_nama" => "branch",
            "dtime" => "date",
            "bank_rekening_nama" => "cash account",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_nama" => "product name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //            "hpp" => "price",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "sisa" => "total amount",
                //                "creditAmount" => "supplier credit amount",
                "harus_bayar" => "amount remains to pay",
                "nilai_entry" => "amount of payment",
                "new_sisa" => "remain to pay (from list)",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "printLocation" => "Transaksi/viewReceipt/",

        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harus_bayar"),
            ),
        ),

    ),
    //pph29
    "5683" => array(
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
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "subtotal" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            //            3 => array(
            //                "harga" => "amount",
            ////                "ppn" => "VAT",
            ////                "nett" => "grand total",
            //            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
            2 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            2 => array(
                "size" => "normal",
            ),
            3 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(),
    ),
    //pph 25
    "117" => array(
        "receiptTemplate" => array(
            1 => "template/651.html",
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
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "subtotal" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            //            3 => array(
            //                "harga" => "amount",
            ////                "ppn" => "VAT",
            ////                "nett" => "grand total",
            //            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
            2 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),


        ),
    ),
    //pph pasal 4(2)
    "118" => array(
        "receiptTemplate" => array(
            1 => "template/651.html",
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
                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            2 => array(
                "subtotal" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "grand total",
            ),
            //            3 => array(
            //                "harga" => "amount",
            ////                "ppn" => "VAT",
            ////                "nett" => "grand total",
            //            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
            2 => array(
                //                "harga_source" =>"dpp",
                "harga" => "Price",
            ),
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(
            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),
            2 => array(
                "size" => "normal",
            ),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "harga",),
            ),


        ),
    ),
    //config approval pph 23 penjualan pusat
    "116" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
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
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Export INVOICE",
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

            3 => array(
                "nomer" => "No",
            ),
            4 => array(
                "nomer" => "No",
            ),
            5 => array(
                "nomer" => "INV No",
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
                //                "customerDetails_npwp" => "Tax ID/NPWP",
                "top_nama" => "Term of Payment",
                "paymentMethod_name" => "Payment Method",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
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
                //                "customerDetails_npwp" => "Tax ID/NPWP",
                "paymentMethod_name" => "Payment Method",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "top_nama" => "Term of Payment",
                //                "dueDate_value" => "Due Date",
                "shippingDate_value" => "Delivery Date",
                //                "shippingService_name" => "shipping service",
            ),

            3 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "shippingDate_value" => "Delivery Date",
                "shippingService_name" => "shipping service",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
            ),
            4 => array(
                "nomer" => "No",
                "nomer_top" => "SO No",
                "dtime" => "Packing list date",
                "shippingDate_value" => "Delivery Date",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "shippingService_name" => "shipping service",
            ),
            5 => array(
                "nomer" => "INV Number",
                "nomers_prev" => "Pl Number",
                "nomer_top" => "SO No",
                "dtime" => "Date",
                "paymentMethod_name" => "Payment Method",
                "dueDate_value" => "Due Date",
                "shippingService_name" => "shipping service",
                //                "shippingService_name" => "shipping service",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
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
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1" => "Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "nett1" => "Price",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),

            3 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
            ),

        ),
        "receiptSumFields" => array(
            1 => array(
                "ongkir" => "Shipping Service",
                "new_net1" => "Sub Amount",
                //                "new_net2" => "grand total",
                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat 11%",
                "total_ui" => "Sub Total",
                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT 11%",
                "tagihan" => "Due Amount",
            ),
            2 => array(
                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat 11%",
                "total_ui" => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT 11%",
                "nett1_bulat" => "total Amount",
                "tagihan" => "Due Amount",
            ),

            3 => array(
                "ongkir" => "Shipping Service",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat 11%",
                "total_ui" => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "ppn_out_bulat" => "VAT 11%",
                "nett1_bulat" => "total Amount",
                "tagihan" => "Due Amount",
            ),

        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            5 => array("size" => "normal"),
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
            "grand_ppn" => "VAT 11%",
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
        //                    //                    "print_label" =>"tool",
        //                ),
        //                "loop" => array(),
        //            ),
        //            "produk" => array(
        //                "label" => "produk",
        //                "target" => "produk",
        //                "srcKey" => "produk_id",
        //                "fields" => array(
        //                    //                    "no" =>"No",
        //                    "produk_nama" => "product",
        //                    "produk_kode" => "product_no",
        //                    "customers_nama" => "customers nama",
        //                    "nomer_top" => "Transaksi",
        //                    "ord_qty" => "Order",
        //                    "ord_sent_qty" => "Dikirim",
        //                    "ord_valid_qty" => "Outstanding",
        //                    "stok" => "Tersedia",
        //                    //                    "print_label" =>"tool",
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
        //                    "nomer_top" => "Transaksi SO",
        //                    //                    "produk_nama" =>"produk_nama",
        //                    "produk_kode" => "produk kode",
        //                    "produk_ord_jml" => "order",
        //                    "ord_sent_qty" => "dikirim",
        //                    "ord_valid_qty" => "<span class='text-red'>Outstanding</span>",
        //                ),
        //                "loop" => array(
        //                    "nomer_top" => "nomer_top",
        //                    //                    "produk_nama" =>"produk_nama",
        //                    "produk_kode" => "produk_kode",
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

    "1155" => array(
        "receiptTemplate" => array(
            1 => "template/110r.html",
            2 => "template/110e.html",
            3 => "template/110.html",
        ),
        "headerNota" => array(
            "dtime" => "date",
            "suppliers_nama" => "Vendor",
            "tlp_1" => "phone",
            "alamat_1" => "address",

        ),
        "customButton" => array(
            1 => array(
                1 => array(
                    "label" => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Export INVOICE",
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

            3 => array(
                "nomer" => "No",
            ),
            4 => array(
                "nomer" => "No",
            ),
            5 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No.",
                "dtime" => "Date",
            ),
            2 => array(
                "nomer" => "No.",
                "dtime" => "Date",
            ),

            3 => array(
                "nomer" => "No",
                //                "nomer_top" => "SO No.",
                "efaktur_source" => "INV",
                "eFaktur" => "E-Faktur",
                "dateFaktur" => "Date",
            ),

        ),
        "fixedSignatures" => array(),
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
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
            1 => "sisa",
            2 => "sisa",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
            1 => array(
                "extern_nilai2" => "DPP",
                "sisa" => "pph23",

                "dateFaktur" => "tanggal faktur",
                "eFaktur" => "nomer faktur",
            ),
            2 => array(
                "extern_nilai2" => "DPP",
                "sisa" => "pph23",

                "dateFaktur" => "tanggal faktur",
                "eFaktur" => "nomer faktur",
            ),
//            2 => array(
//                //                "stok" => "stok",
//                "harga" => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            3 => array(
//                "nett1" => "Price",
//                //                "harga" => "price",
//                //                "ppn"   => "VAT",
//            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
                "extern_nama" => "vendor/supplier",
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            2 => array(
                "extern_nama" => "vendor/supplier",
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),
            3 => array(
                "extern_nama" => "vendor/supplier",
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
            ),

        ),
        "receiptSumFields" => array(),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
            3 => array(),

        ),
        "reportSumFields" => array(//            "customers_id" => "customers_nama",

        ),
//        "receiptAddMain" => array(
//            1 => array(
////                "nomer" => "INV",
//                "dateFaktur" => "Tgl faktur ",
//                "eFaktur" => "nomer faktur",
//                "dpp_final" => "DPP",
//                "ppn_belum_faktur" => "PPN",
//                //                    "nilai_faktur" => "nilai(RP)",
//            ),
//            2 => array(
////                "efaktur_source" => "INV",
//                "dateFaktur" => "Tgl faktur ",
//                "eFaktur" => "nomer faktur",
//                "dpp_final" => "DPP",
//                "ppn_belum_faktur" => "PPN",
//            ),
//            3 => array(
//                "efaktur_source" => "INV",
//                "dateFaktur" => "Tgl faktur ",
//                "eFaktur" => "nomer faktur",
//                "total_ui" => "DPP",
//                "new_grand_ppn" => "PPN",
//            ),
//        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            5 => array("size" => "normal"),
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
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            //            "2" => array(
            //                "in_word" => array("inWordInd" => "new_net3",),
            //            ),
            //            "3" => array(),
            //            "4" => array(),
            //            "5" => array(
            //                "in_word" => array("inWordInd" => "tagihan",),
            //            ),
        ),
        "receiptInword2" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "new_grand_ppn",),
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
            "grand_ppn" => "VAT 11%",
            "new_net3" => "grand total",
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
        "print_nvalas" => false,
        "print_lable" => array(
            "steps" => array(
                1 => array(
                    "label" => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
//        "receiptEfakturFields" => array(
//            1 => array(
//                "eFaktur" => "Nomer Faktur",
//                "dateFaktur" => "Tanggal Faktur",
////                "satuan" => "Jumlah",
//                "dpp_final" => "DPP",
//                "ppn_final" => "PPN",
//            ),
//        ),
    ),

);