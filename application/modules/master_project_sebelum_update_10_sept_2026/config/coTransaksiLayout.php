<?php

$config["coTransaksiLayout"] = array(
    "5882" => array(
        "receiptTemplate" => array(
            1 => "template/588spo.html",
            2 => "template/582so.html",
//            3 => "template/582pkd.html",
            3 => "template/588spd.html",
            4 => "template/582.html",
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
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),

            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "No",
            ),
            3 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
//            1 => array(
//                "nomer" => "No",
//                "dtime" => "Date",
//                "customerDetails_alamat_1" => "Billing Address",
//                "customerDetails_nama" => "PIC name",
//                "customerDetails_tlp_1" => "Phone",
//                "customerDetails_tlp_2" => "Handphone",
//                "customerDetails_email" => "Email",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment Method",
//                "shippingDate_value" => "Delivery Date",
//                "shippingService_name" => "shipping service",
//                "transaksi_jenis2_label" => "Paket",
//            ),
//            2 => array(
//                "nomer" => "No",
//                "nomer_top" => "SO No.",
//                "dtime" => "Date",
//                "customerDetails_alamat_1" => "Billing address",
//                "customerDetails_nama" => "PIC name",
//                "customerDetails_tlp_1" => "Phone",
//                "customerDetails_tlp_2" => "Handphone",
//                "customerDetails_email" => "Email",
//                //                "customerDetails_npwp" => "Tax ID/NPWP",
//                "paymentMethod_name" => "Payment Method",
//                //                "tos_nama" => "Term of Shipment",
//                //                "capacity_nama" => "Capacity",
//                "top_nama" => "Term of Payment",
//                //                "dueDate_value" => "Due Date",
//                "shippingDate_value" => "Delivery Date",
//                "shippingService_name" => "shipping service",
//                "transaksi_jenis2_label" => "Paket",
//            ),
            1 => array(
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
                "transaksi_jenis2_label" => "Paket",
            ),
            2 => array(
                "projectName" => "Project",
                // "projectHarga"=>"Project price",
                // "projectPpn"=>"Project tax",
                // "projectGrandtotal"=>"Project grandtotal",
                "nomer" => " No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                // "dtime" => "Packing list date",
//                "shippingDate_value" => "Delivery Date",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                "projectHarga" => "Project price",
                "projectPpn" => "Project tax",
                "projectGrandtotal" => "Project grandtotal",

                //                "shippingService_name" => "shipping service",
                // "transaksi_jenis2_label" => "Paket",
            ),
            3 => array(
                "nomer" => "INV No",
                "nomers_prev" => "PL No",
                "nomer_top" => "SO No",
                "dtime" => "Date",
                "paymentMethod_name" => "Payment Method",
                "dueDate_value" => "Due Date",
                "shippingService_name" => "shipping service",
                //                "shippingService_name" => "shipping service",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
                "transaksi_jenis2_label" => "Paket",
            ),
        ),
        "hideFixedElements" => array(
            3 => array(
                array(
                    "key" => "paymentMethod_name",
                    "keyResult" => array("cash", "cash in advance"),
                    "label" => array(
                        "dueDate_value" => "Due Date",
                    ),
                ),
            ),
        ),
        "fixedSignatures" => array(
//            1 => array(
//                "customer" => array(
//                    "label" => ".Confirmed and approved by",
//                    "contents" => "customerDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//            2 => array(
//                "customer" => array(
//                    "label" => ".Confirmed and approved by",
//                    "contents" => "customerDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
            2 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
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
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
            1 => "jml",
            2 => "jml",
            3 => "jml*nett1",
//            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
//            1 => array(
//                "nett1" => "Price",
//                //                "disc" => "disc",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            2 => array(
//
//                "nett1" => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
            1 => array(
//                "stok" => "Stok available",
//                "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            2 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            3 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
//            1 => array(
//                "harga" => "price",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
//            ),
//            2 => array(
//                "harga" => "price",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
//            ),
            1 => array(
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
            2 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
//            5 => array(
//                //                "harga" => "price",
//                "nett1" => "price",
//                //                "ppn" => "VAT",
//            ),
        ),
        "receipCartNumFields2" => array(
//            1 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),

        ),
        "receiptDetailFields" => array(
//            1 => array(
//                "produk_kode" => "Product code",
////                "no_part" => "part number",
//                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "produk_kode" => "Product code",
////                "no_part" => "part number",
//                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "produk_kode" => "Product code",
//                "no_part" => "part number",
                "produk_nama" => "Description",
//                "berat_new" => "W(KG)",
//                "volume_new" => "CBM",
                "max_jml" => "SO",
//                "req_cancel_jml" => "cancel request",
//                "cancel_jml" => "dicancel",
//                "packed_jml" => "dipacking",
//                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
//                "sub_berat_new" => "Sub Berat",
//                "sub_berat_gross"  => "Sub Berat",
                "satuan" => "uom",
//                "sub_volume_new" => "Sub Volume",
//                "sub_volume_gross" => "Sub Volume",
            ),
            2 => array(
                "dtime" => "date",
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

        ),
        "receiptDetailFields2" => array(
//            1 => array(
//                "produk_nama" => "item name",
////                "produk_ord_harga" => "harga",
//                // "satuan" => "uom",
//            ),
//            2 => array(
//                "nama" => "item name",
//                // "harga" => "harga",
//                "produk_kode" => "produk code",
//                // "produk_nama" => "Description",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "nama" => "item name",
                // "harga" => "harga",
                "produk_kode" => "produk code",
                // "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "item name",
                // "harga" => "harga",
                "produk_kode" => "produk code",
                // "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
//            1 => array(
//                "nett1" => "amount",
//                "ongkir_ui" => "Shipping Service",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
//                "ppn_out_bulat" => "VAT",
//                "grand_pembulatan" => "Grand Total",
//            ),
//            2 => array(
//                //                "nett1" => "amount",
//                //                "disc" => "disc",
//                "ongkir_ui" => "Shipping Service",
//                //                "grand_total" => "total amount",
////                "grand_total_ui" => "Total Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
////                "grand_ppn" => "VAT",
//                "ppn_out_bulat" => "VAT",
//                //                "dp" => "DOWNPAYMENT",
////                "new_net3" => "Grand Total",
//                "grand_pembulatan" => "Grand Total",
//            ),

//            1 => array(
//
//                "berat_new" => "Berat",
//                "volume_new" => "Volume",
//                //                "harga" => "amount",
//                //                "ppn" => "VAT",
//                //                "nett" => "total",
//            ),
//            2 => array(
//                //                "harga" => "amount",
//                //                "ppn" => "VAT",
//                //                "nett" => "total",
//                //                "shipping_service" => "shipping service",
//            ),
//            3 => array(
//                //                "nett1" => "amount",
//                "ongkir" => "Shipping Service",
//                "new_net1" => "Amount",
//                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
//                "dp_ppn_value" => "Dp Vat 10%",
//                "total_ui" => "Sub Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "total_ui" => "total Amount",
//                "new_grand_ppn" => "VAT 10%",
//                "tagihan" => "Grand Total",
//            ),

        ),
        "receiptSumFields2" => array(
//            1 => array(//                "hpp" => "grand total"
//            ),
//            2 => array(//                "hpp" => "grand total"
//            ),
            1 => array(//                "hpp" => "grand total"
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
//            1 => array("size" => "normal"),
            // 2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
//            2 => "SAN/F/SA001/R00",
            1 => "SAN/F/LOG001/R00",
            2 => "SAN/F/LOG001/R00",
            3 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            1 => "",
            3 => "true",
        ),
        "receiptInword" => array(
//            "1" => array(
//                "in_word" => array("inWordInd" => "grand_pembulatan",),
//            ),
//            "2" => array(
//                "in_word" => array("inWordInd" => "new_net3",),
//            ),
            "1" => array(),
            "2" => array(),
            "3" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
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
        "fixedFieldHoldConsolidate" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(

                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama" => "cabang",
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
                "cabang_nama" => "cabang",
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
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
//            1 => array(
//                "sign_1",
//            ),
//            2 => array(
//                "sign_1",
//                "sign_2",
//            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
//                    "transaksi_nilai" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
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
//            "steps" => array(
//                1 => array(
//                    "label" => "pre order",
//                    "labelPre" => "invoice",
//                ),
//            ),
        ),
        "print_hitung" => array(
            3 => false,
        ),
        "print_hitung_itemRecap" => array(
            3 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer" => array(
            3 => array(
                "ongkir" => "ongkir",
                "new_net1" => "nett1+ongkir",
//                "dp_value" => "dp_value",
//                "dp_ppn_value" => "dp_ppn_value",
//                "total_ui" => "total_ui",
                "nett1_bulat" => "new_net1",
                "ppn_out_bulat" => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net" => "ppn",
//                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan" => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields" => array(
            3 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown" => array(
            3 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),
        "receiptElementInjector" => array(
            "source" => array(
                "element" => "customerDetails",
                "fields" => array(
                    "nama" => "customer_nama",
//                    "tlp_1" => "customer_tlp",
//                    "npwp" => "customer_npwp",
                ),
                "usedFields" => array(
                    "customer_nama" => "Customer",
                ),
            ),
            "target" => array(
                "element" => "deliveryDetails",
            ),
        ),
        //-PO PROJEK--------------
        "purchasingProjek" => array(
//            2 => array(
//                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
////                "produk_ord_harga" => "Price",
//            ),
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
//                "produk_ord_harga" => "Price",
            ),
        ),
        "receiptAdvanceItems" => true,
        "receiptAdvanceItemsKey" => "pph",
        "receiptAdvanceFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
//                1 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                2 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                3 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
            0 => array(
//                1 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                2 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                3 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
        ),
        "receiptAdvanceNumFields" => array(
            1 => array(
//                1 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                2 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
            ),
            0 => array(
//                1 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                2 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
            ),
        ),
        "receiptAdvanceAmountValue" => array(
            1 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),
            0 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),

        ),
        "receiptAdvanceSubFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
        ),
        "receiptAdvanceSubNumFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                2 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                3 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                4 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
            ),
            0 => array(
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        "receiptAdvanceSubEditableFields" => array(
            1 => array(
                1 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                ),
                2 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                ),
            ),
            0 => array(
                1 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
                2 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
            ),
        ),
        //tambahan untuk multi/ closing project
        "receiptMultiMainFields" => array(
            "pihakName" => "customer",
            "projectName" => "project",
        ),
        //baris item paling atas
        "receiptMultiItemFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref",
            "projectHarga" => "price",
            "projectPpn" => "tax",
            "projectGrandtotal" => "subtotal",
        ),
        //bagian Packing list
        "receiptMultiItemSubFields" => array(
            "dtime" => "date",
            "nama" => "Packing List",
            "details" => "detail",
        ),
        "receiptMultitemSubField_detils" => array(
//            1 => array(),
//            2 => array(
//                "produk_kode" => "Product code",
//                "produk_nama" => "Description",
//                "satuan" => "UOM",
//                "produk_ord_jml" => "Qty",
//                "produk_ord_harga" => "price",
//                // "sub_harga" => "subtotal",
//            ),
            1 => array(
                "produk_kode" => "Product code",
                "nama" => "Description",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            2 => array(
                "produk_kode" => "Product code",
                "nama" => "Product",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            // "produk_kode" => "Product code",
            // "nama" => "Description",
            // "satuan" => "UOM",
            // "jml" => "Qty",
            // "harga" => "price",
            // "sub_harga" => "subtotal",
        ),
        "receiptMultitemSubField_detil_biaya" => array(
//            1 => array(),
//            2 => array(
//                "nama" => "PO SERVICE",
//                "satuan" => "UOM",
//                "jml" => "Qty",
//                "harga" => "price",
//                "sub_harga" => "subtotal",
//            ),
            1 => array(
                "nama" => "PO SERVICE",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            2 => array(
                "kode" => "kode",
                "nama" => "PO SERVICE",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),

        ),
    ),

    "588" => array(
        "receiptTemplate" => array(
            1 => "template/588spo.html",
            2 => "template/588so.html",
            3 => "template/588so.html",
//            3 => "template/588spd.html",
//            4 => "template/582.html",
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
                "dtime" => "Tanggal",
                "customerDetails_alamat_1" => "Billing Address",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment Method",
                "shippingDate_value" => "Delivery Date",
                "shippingService_name" => "shipping service",
//                "transaksi_jenis2_label" => "Paket",
                "tanggalStart" => "Mulai pengerjaan",
                "tenggatWaktu" => "Tenggat",
            ),
            2 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
//                "customerDetails_alamat_1" => "Billing address",
//                "customerDetails_nama" => "PIC name",
//                "customerDetails_tlp_1" => "Phone",
//                "customerDetails_tlp_2" => "Handphone",
//                "customerDetails_email" => "Email",
                //                "customerDetails_npwp" => "Tax ID/NPWP",
//                "paymentMethod_name" => "Payment Method",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
//                "top_nama" => "Term of Payment",
                //                "dueDate_value" => "Due Date",
//                "shippingDate_value" => "Delivery Date",
//                "shippingService_name" => "shipping service",
//                "transaksi_jenis2_label" => "Paket",
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
                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "projectName" => "Project",
                // "projectHarga"=>"Project price",
                // "projectPpn"=>"Project tax",
                // "projectGrandtotal"=>"Project grandtotal",
                "nomer" => " No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                // "dtime" => "Packing list date",
//                "shippingDate_value" => "Delivery Date",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                "projectHarga" => "Project price",
                "projectPpn" => "Project tax",
                "projectGrandtotal" => "Project grandtotal",

                //                "shippingService_name" => "shipping service",
                // "transaksi_jenis2_label" => "Paket",
            ),
            5 => array(
                "nomer" => "INV No",
                "nomers_prev" => "PL No",
                "nomer_top" => "SO No",
                "dtime" => "Date",
                "paymentMethod_name" => "Payment Method",
                "dueDate_value" => "Due Date",
                "shippingService_name" => "shipping service",
                //                "shippingService_name" => "shipping service",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
                "transaksi_jenis2_label" => "Paket",
            ),
        ),
        "hideFixedElements" => array(
            5 => array(
                array(
                    "key" => "paymentMethod_name",
                    "keyResult" => array("cash", "cash in advance"),
                    "label" => array(
                        "dueDate_value" => "Due Date",
                    ),
                ),
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
            5 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
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
            3 => "jml",
            4 => "jml",
            5 => "jml*nett1",
//            5 => "jml*(harga-disc)",
        ),

        "receipNumFields" => array(
            1 => array(
//                "nett1" => "Price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
//                "nett1" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
//                "stok" => "Stok available",
//                "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
//                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
//                "harga" => "price",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
            ),
            2 => array(
//                "harga" => "price",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
            ),
            3 => array(
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
//            5 => array(
//                //                "harga" => "price",
//                "nett1" => "price",
//                //                "ppn" => "VAT",
//            ),
        ),
        "receipCartNumFields2" => array(
//            1 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            3 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            4 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
//                "produk_kode" => "Product code*",
//                "no_part" => "part number",
                "nama" => "Nama Project",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
            ),
            2 => array(
//                "produk_kode" => "Product code**",
//                "no_part" => "part number",
                "nama" => "Nama Project",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
            ),
            3 => array(
                "nama" => "Nama Project",
//                "produk_kode" => "Product code***",
//                "no_part" => "part number",
//                "produk_nama" => "Description",
//                "berat_new" => "W(KG)",
//                "volume_new" => "CBM",
//                "max_jml" => "SO",
//                "req_cancel_jml" => "cancel request",
//                "cancel_jml" => "dicancel",
//                "packed_jml" => "dipacking",
//                "sent_jml" => "dikirim",
//                "produk_ord_jml" => "Qty",
//                "sub_berat_new" => "Sub Berat",
//                "sub_berat_gross"  => "Sub Berat",
//                "satuan" => "uom",
//                "sub_volume_new" => "Sub Volume",
//                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "nama" => "Nama Project",
//                "dtime" => "date",
//                "produk_ord_jml" => "Qty (Pcs)",
//                "produk_kode" => "Product code****",
//                "no_part" => "part number",
//                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
//                "jml" => "Quantity Per Pkg (Ctns)",
//                "berat_new" => "Net/Pkg (Kgs)",
//                "sub_berat_new" => "Total (Kgs)",
//                "volume_new" => "Net/Pkg (Cbm)",
//                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "nama" => "Nama Project",
//                "dtime" => "date",
//                "produk_ord_jml" => "Qty (Pcs)",
//                "produk_kode" => "Product code****",
//                "no_part" => "part number",
//                "produk_nama" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
//                "jml" => "Quantity Per Pkg (Ctns)",
//                "berat_new" => "Net/Pkg (Kgs)",
//                "sub_berat_new" => "Total (Kgs)",
//                "volume_new" => "Net/Pkg (Cbm)",
//                "sub_volume_new" => "Total (Cbm)",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "material",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "material",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "material",
                "satuan" => "satuan",
            ),
            4 => array(
                "nama" => "material",
                "satuan" => "satuan",
            ),
            5 => array(
                "nama" => "material",
                "satuan" => "satuan",
            ),
        ),
        "receiptDetailFields3" => array(
            1 => array(
                "nama" => "biaya",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "biaya",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "biaya",
                "satuan" => "satuan",
            ),
            4 => array(
                "nama" => "biaya",
                "satuan" => "satuan",
            ),
            5 => array(
                "nama" => "biaya",
                "satuan" => "satuan",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
//                "nett1" => "amount",
//                "ongkir_ui" => "Shipping Service",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
//                "ppn_out_bulat" => "VAT",
//                "grand_pembulatan" => "Grand Total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
//                "ongkir_ui" => "Shipping Service",
                //                "grand_total" => "total amount",
//                "grand_total_ui" => "Total Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
//                "grand_ppn" => "VAT",
//                "ppn_out_bulat" => "VAT",
                //                "dp" => "DOWNPAYMENT",
//                "new_net3" => "Grand Total",
//                "grand_pembulatan" => "Grand Total",
            ),
            3 => array(
//                "berat_new" => "Berat",
//                "volume_new" => "Volume",
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            4 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
                //                "shipping_service" => "shipping service",
            ),
            5 => array(
                //                "nett1" => "amount",
//                "ongkir" => "Shipping Service",
//                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
//                "dp_ppn_value" => "Dp Vat 10%",
//                "total_ui" => "Sub Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "total_ui" => "total Amount",
//                "new_grand_ppn" => "VAT 10%",
//                "tagihan" => "Grand Total",
            ),
        ),
        "receiptSumFields2" => array(
            1 => array(//                "hpp" => "grand total"
            ),
            2 => array(//                "hpp" => "grand total"
            ),
            3 => array(//                "hpp" => "grand total"
            ),
        ),

        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
//            1 => array("size" => "normal"),
//            2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
//            2 => "SAN/F/SA001/R00",
//            3 => "SAN/F/LOG001/R00",
//            4 => "SAN/F/LOG001/R00",
//            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net3",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
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
        "fixedFieldHoldConsolidate" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(
                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama" => "cabang",
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
                "cabang_nama" => "cabang",
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
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
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
//                    "transaksi_nilai" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
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
        "print_hitung" => array(
            5 => false,
        ),
        "print_hitung_itemRecap" => array(
            5 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer" => array(
            5 => array(
                "ongkir" => "ongkir",
                "new_net1" => "nett1+ongkir",
//                "dp_value" => "dp_value",
//                "dp_ppn_value" => "dp_ppn_value",
//                "total_ui" => "total_ui",
                "nett1_bulat" => "new_net1",
                "ppn_out_bulat" => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net" => "ppn",
//                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan" => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields" => array(
            5 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown" => array(
            5 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),
        "receiptElementInjector" => array(
            "source" => array(
                "element" => "customerDetails",
                "fields" => array(
                    "nama" => "customer_nama",
//                    "tlp_1" => "customer_tlp",
//                    "npwp" => "customer_npwp",
                ),
                "usedFields" => array(
                    "customer_nama" => "Customer",
                ),
            ),
            "target" => array(
                "element" => "deliveryDetails",
            ),
        ),
        //-PO PROJEK--------------
        "purchasingProjek" => array(
            2 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
//                "produk_ord_harga" => "Price",
            ),
            3 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
//                "produk_ord_harga" => "Price",
            ),
        ),
        "receiptAdvanceItems" => true,
        "receiptAdvanceItemsKey" => "pph",
        "receiptAdvanceFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
//                1 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                2 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                3 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
            0 => array(
//                1 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                2 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                3 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
        ),
        "receiptAdvanceNumFields" => array(
            1 => array(
//                1 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                2 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
            ),
            0 => array(
//                1 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                2 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
            ),
        ),
        "receiptAdvanceAmountValue" => array(
            1 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),
            0 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),

        ),
        "receiptAdvanceSubFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                5 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                5 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
        ),
        "receiptAdvanceSubNumFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                2 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                3 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                4 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                5 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
            ),
            0 => array(
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                5 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        "receiptAdvanceSubEditableFields" => array(
            1 => array(
                1 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                ),
                2 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                ),
            ),
            0 => array(
                1 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
                2 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
            ),
        ),
        //tambahan untuk multi/ closing project
        "receiptMultiMainFields" => array(
            "pihakName" => "customer",
            "projectName" => "project",
        ),
        //baris item paling atas
        "receiptMultiItemFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref",
            "projectHarga" => "price",
            "projectPpn" => "tax",
            "projectGrandtotal" => "subtotal",
        ),
        //bagian Packing list
        "receiptMultiItemSubFields" => array(
            "dtime" => "date",
            "nama" => "Packing List",
            "details" => "detail",
        ),
        "receiptMultitemSubField_detils" => array(
            1 => array(),
            2 => array(
                "produk_kode" => "Product code",
                "produk_nama" => "Description",
                "satuan" => "UOM",
                "produk_ord_jml" => "Qty",
                "produk_ord_harga" => "price",
                // "sub_harga" => "subtotal",
            ),
            3 => array(
                "produk_kode" => "Product code",
                "nama" => "Description",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            4 => array(
                "produk_kode" => "Product code",
                "nama" => "Product",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            5 => array(
                "produk_kode" => "Product code",
                "nama" => "Product",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            // "produk_kode" => "Product code",
            // "nama" => "Description",
            // "satuan" => "UOM",
            // "jml" => "Qty",
            // "harga" => "price",
            // "sub_harga" => "subtotal",
        ),
        "receiptMultitemSubField_detil_biaya" => array(
            1 => array(),
            2 => array(
                "nama" => "PO SERVICE",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            3 => array(
                "nama" => "PO SERVICE",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            4 => array(
                "kode" => "kode",
                "nama" => "PO SERVICE",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            5 => array(
                "kode" => "kode",
                "nama" => "PO SERVICE",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),

        ),
    ),
    // uang muka
    "4469" => array(
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

    "5883" => array(
        "receiptTemplate" => array(
            1 => "template/588spo.html",
            2 => "template/582so.html",
//            3 => "template/582pkd.html",
            3 => "template/588spd.html",
            4 => "template/582.html",
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
//            1 => array(
//                "nomer" => "No",
//            ),
//            2 => array(
//                "nomer" => "",
//            ),

            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "No",
            ),
            3 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
//            1 => array(
//                "nomer" => "No",
//                "dtime" => "Date",
//                "customerDetails_alamat_1" => "Billing Address",
//                "customerDetails_nama" => "PIC name",
//                "customerDetails_tlp_1" => "Phone",
//                "customerDetails_tlp_2" => "Handphone",
//                "customerDetails_email" => "Email",
//                "top_nama" => "Term of Payment",
//                "paymentMethod_name" => "Payment Method",
//                "shippingDate_value" => "Delivery Date",
//                "shippingService_name" => "shipping service",
//                "transaksi_jenis2_label" => "Paket",
//            ),
//            2 => array(
//                "nomer" => "No",
//                "nomer_top" => "SO No.",
//                "dtime" => "Date",
//                "customerDetails_alamat_1" => "Billing address",
//                "customerDetails_nama" => "PIC name",
//                "customerDetails_tlp_1" => "Phone",
//                "customerDetails_tlp_2" => "Handphone",
//                "customerDetails_email" => "Email",
//                //                "customerDetails_npwp" => "Tax ID/NPWP",
//                "paymentMethod_name" => "Payment Method",
//                //                "tos_nama" => "Term of Shipment",
//                //                "capacity_nama" => "Capacity",
//                "top_nama" => "Term of Payment",
//                //                "dueDate_value" => "Due Date",
//                "shippingDate_value" => "Delivery Date",
//                "shippingService_name" => "shipping service",
//                "transaksi_jenis2_label" => "Paket",
//            ),
            1 => array(
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
                "transaksi_jenis2_label" => "Paket",
            ),
            2 => array(
                "projectName" => "Project",
                // "projectHarga"=>"Project price",
                // "projectPpn"=>"Project tax",
                // "projectGrandtotal"=>"Project grandtotal",
                "nomer" => " No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                // "dtime" => "Packing list date",
//                "shippingDate_value" => "Delivery Date",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                "projectHarga" => "Project price",
                "projectPpn" => "Project tax",
                "projectGrandtotal" => "Project grandtotal",

                //                "shippingService_name" => "shipping service",
                // "transaksi_jenis2_label" => "Paket",
            ),
            3 => array(
                "nomer" => "INV No",
                "nomers_prev" => "PL No",
                "nomer_top" => "SO No",
                "dtime" => "Date",
                "paymentMethod_name" => "Payment Method",
                "dueDate_value" => "Due Date",
                "shippingService_name" => "shipping service",
                //                "shippingService_name" => "shipping service",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
                "transaksi_jenis2_label" => "Paket",
            ),
        ),
        "hideFixedElements" => array(
            3 => array(
                array(
                    "key" => "paymentMethod_name",
                    "keyResult" => array("cash", "cash in advance"),
                    "label" => array(
                        "dueDate_value" => "Due Date",
                    ),
                ),
            ),
        ),
        "fixedSignatures" => array(
//            1 => array(
//                "customer" => array(
//                    "label" => ".Confirmed and approved by",
//                    "contents" => "customerDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
//            2 => array(
//                "customer" => array(
//                    "label" => ".Confirmed and approved by",
//                    "contents" => "customerDetails_nama",
//                    //                "caption_department" => "",
//                ),
//            ),
            2 => array(
                "customer" => array(
                    "label" => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
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
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
            1 => "jml",
            2 => "jml",
            3 => "jml*nett1",
//            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
//            1 => array(
//                "nett1" => "Price",
//                //                "disc" => "disc",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            2 => array(
//
//                "nett1" => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
            1 => array(
//                "stok" => "Stok available",
//                "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            2 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            3 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
//            1 => array(
//                "harga" => "price",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
//            ),
//            2 => array(
//                "harga" => "price",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
//            ),
            1 => array(
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
            2 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
//            5 => array(
//                //                "harga" => "price",
//                "nett1" => "price",
//                //                "ppn" => "VAT",
//            ),
        ),
        "receipCartNumFields2" => array(
//            1 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),

        ),
        "receiptDetailFields" => array(
//            1 => array(
//                "produk_kode" => "Product code",
////                "no_part" => "part number",
//                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "produk_kode" => "Product code",
////                "no_part" => "part number",
//                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "produk_kode" => "Product code",
//                "no_part" => "part number",
                "produk_nama" => "Description",
//                "berat_new" => "W(KG)",
//                "volume_new" => "CBM",
                "max_jml" => "SO",
//                "req_cancel_jml" => "cancel request",
//                "cancel_jml" => "dicancel",
//                "packed_jml" => "dipacking",
//                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
//                "sub_berat_new" => "Sub Berat",
//                "sub_berat_gross"  => "Sub Berat",
                "satuan" => "uom",
//                "sub_volume_new" => "Sub Volume",
//                "sub_volume_gross" => "Sub Volume",
            ),
            2 => array(
                "dtime" => "date",
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

        ),
        "receiptDetailFields2" => array(
//            1 => array(
//                "produk_nama" => "item name",
////                "produk_ord_harga" => "harga",
//                // "satuan" => "uom",
//            ),
//            2 => array(
//                "nama" => "item name",
//                // "harga" => "harga",
//                "produk_kode" => "produk code",
//                // "produk_nama" => "Description",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "nama" => "item name",
                // "harga" => "harga",
                "produk_kode" => "produk code",
                // "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "item name",
                // "harga" => "harga",
                "produk_kode" => "produk code",
                // "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
//            1 => array(
//                "nett1" => "amount",
//                "ongkir_ui" => "Shipping Service",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
//                "ppn_out_bulat" => "VAT",
//                "grand_pembulatan" => "Grand Total",
//            ),
//            2 => array(
//                //                "nett1" => "amount",
//                //                "disc" => "disc",
//                "ongkir_ui" => "Shipping Service",
//                //                "grand_total" => "total amount",
////                "grand_total_ui" => "Total Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "nett1_bulat" => "Total Amount",
////                "grand_ppn" => "VAT",
//                "ppn_out_bulat" => "VAT",
//                //                "dp" => "DOWNPAYMENT",
////                "new_net3" => "Grand Total",
//                "grand_pembulatan" => "Grand Total",
//            ),

//            1 => array(
//
//                "berat_new" => "Berat",
//                "volume_new" => "Volume",
//                //                "harga" => "amount",
//                //                "ppn" => "VAT",
//                //                "nett" => "total",
//            ),
//            2 => array(
//                //                "harga" => "amount",
//                //                "ppn" => "VAT",
//                //                "nett" => "total",
//                //                "shipping_service" => "shipping service",
//            ),
//            3 => array(
//                //                "nett1" => "amount",
//                "ongkir" => "Shipping Service",
//                "new_net1" => "Amount",
//                //                "new_net2" => "grand total",
//                "dp_value" => "Downpayment",
//                "dp_ppn_value" => "Dp Vat 10%",
//                "total_ui" => "Sub Amount",
//                "nilai_pembulatan" => "pembulatan",
//                "total_ui" => "total Amount",
//                "new_grand_ppn" => "VAT 10%",
//                "tagihan" => "Grand Total",
//            ),

        ),
        "receiptSumFields2" => array(
//            1 => array(//                "hpp" => "grand total"
//            ),
//            2 => array(//                "hpp" => "grand total"
//            ),
            1 => array(//                "hpp" => "grand total"
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
//            1 => array("size" => "normal"),
            // 2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
//            2 => "SAN/F/SA001/R00",
            1 => "SAN/F/LOG001/R00",
            2 => "SAN/F/LOG001/R00",
            3 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            1 => "",
            3 => "true",
        ),
        "receiptInword" => array(
//            "1" => array(
//                "in_word" => array("inWordInd" => "grand_pembulatan",),
//            ),
//            "2" => array(
//                "in_word" => array("inWordInd" => "new_net3",),
//            ),
            "1" => array(),
            "2" => array(),
            "3" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
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
        "fixedFieldHoldConsolidate" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(

                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama" => "cabang",
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
                "cabang_nama" => "cabang",
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
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
//            1 => array(
//                "sign_1",
//            ),
//            2 => array(
//                "sign_1",
//                "sign_2",
//            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
//                    "transaksi_nilai" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
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
//            "steps" => array(
//                1 => array(
//                    "label" => "pre order",
//                    "labelPre" => "invoice",
//                ),
//            ),
        ),
        "print_hitung" => array(
            3 => false,
        ),
        "print_hitung_itemRecap" => array(
            3 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer" => array(
            3 => array(
                "ongkir" => "ongkir",
                "new_net1" => "nett1+ongkir",
//                "dp_value" => "dp_value",
//                "dp_ppn_value" => "dp_ppn_value",
//                "total_ui" => "total_ui",
                "nett1_bulat" => "new_net1",
                "ppn_out_bulat" => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net" => "ppn",
//                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan" => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields" => array(
            3 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown" => array(
            3 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),
        "receiptElementInjector" => array(
            "source" => array(
                "element" => "customerDetails",
                "fields" => array(
                    "nama" => "customer_nama",
//                    "tlp_1" => "customer_tlp",
//                    "npwp" => "customer_npwp",
                ),
                "usedFields" => array(
                    "customer_nama" => "Customer",
                ),
            ),
            "target" => array(
                "element" => "deliveryDetails",
            ),
        ),
        //-PO PROJEK--------------
        "purchasingProjek" => array(
//            2 => array(
//                "produk_nama" => "Description",
//                "produk_ord_jml" => "Qty",
////                "produk_ord_harga" => "Price",
//            ),
            1 => array(
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
//                "produk_ord_harga" => "Price",
            ),
        ),
        "receiptAdvanceItems" => true,
        "receiptAdvanceItemsKey" => "pph",
        "receiptAdvanceFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
//                1 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                2 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                3 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
            0 => array(
//                1 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                2 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                3 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
//                4 => array(
//                    "nama" => "Description",
////                "jml" => "Qty",
////                "satuan" => "Satuan",
//                ),
            ),
        ),
        "receiptAdvanceNumFields" => array(
            1 => array(
//                1 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                2 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPh" => "dpp pph",
//                    "pph_nilai" => "PPH(Rp)",
//                ),
            ),
            0 => array(
//                1 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                2 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                3 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
//                4 => array(
////                "harga" => "Unit Price",
////                "discPersen" => "DISC(%)",
////                "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
////                "dpp_persen" => "DPP PPN(%)",
//                    "dppPPn" => "dpp ppn",
//                    "ppn" => "PPN(Rp)",
//                ),
            ),
        ),
        "receiptAdvanceAmountValue" => array(
            1 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),
            0 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),

        ),
        "receiptAdvanceSubFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
        ),
        "receiptAdvanceSubNumFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                2 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                3 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
                4 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph" => "PPH(Rp)",
                ),
            ),
            0 => array(
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        "receiptAdvanceSubEditableFields" => array(
            1 => array(
                1 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                ),
                2 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                ),
            ),
            0 => array(
                1 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
                2 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
            ),
        ),
        //tambahan untuk multi/ closing project
        "receiptMultiMainFields" => array(
            "pihakName" => "customer",
            "projectName" => "project",
        ),
        //baris item paling atas
        "receiptMultiItemFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref",
            "projectHarga" => "price",
            "projectPpn" => "tax",
            "projectGrandtotal" => "subtotal",
        ),
        //bagian Packing list
        "receiptMultiItemSubFields" => array(
            "dtime" => "date",
            "nama" => "Packing List",
            "details" => "detail",
        ),
        "receiptMultitemSubField_detils" => array(
//            1 => array(),
//            2 => array(
//                "produk_kode" => "Product code",
//                "produk_nama" => "Description",
//                "satuan" => "UOM",
//                "produk_ord_jml" => "Qty",
//                "produk_ord_harga" => "price",
//                // "sub_harga" => "subtotal",
//            ),
            1 => array(
                "produk_kode" => "Product code",
                "nama" => "Description",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            2 => array(
                "produk_kode" => "Product code",
                "nama" => "Product",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            // "produk_kode" => "Product code",
            // "nama" => "Description",
            // "satuan" => "UOM",
            // "jml" => "Qty",
            // "harga" => "price",
            // "sub_harga" => "subtotal",
        ),
        "receiptMultitemSubField_detil_biaya" => array(
//            1 => array(),
//            2 => array(
//                "nama" => "PO SERVICE",
//                "satuan" => "UOM",
//                "jml" => "Qty",
//                "harga" => "price",
//                "sub_harga" => "subtotal",
//            ),
            1 => array(
                "nama" => "PO SERVICE",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),
            2 => array(
                "kode" => "kode",
                "nama" => "PO SERVICE",
                "satuan" => "UOM",
                "jml" => "Qty",
                "harga" => "price",
                "sub_harga" => "subtotal",
            ),

        ),
    ),
    "5886" => array(
        "receiptTemplate" => array(
            1 => "template/588spo.html",
            2 => "template/582so.html",
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
        "customButton" => array(),
        "elementFixedNumberSO" => array(
            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "No",
            ),
            3 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
            ),
            2 => array(
                "projectName" => "Project",
                // "projectHarga"=>"Project price",
                // "projectPpn"=>"Project tax",
                // "projectGrandtotal"=>"Project grandtotal",
                "nomer" => " No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                // "dtime" => "Packing list date",
//                "shippingDate_value" => "Delivery Date",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                "projectHarga" => "Project price",
                "projectPpn" => "Project tax",
                "projectGrandtotal" => "Project grandtotal",

                //                "shippingService_name" => "shipping service",
                // "transaksi_jenis2_label" => "Paket",
            ),
        ),
        "hideFixedElements" => array(),
        "fixedSignatures" => array(

        ),
        "headerTables" => array(
            "produk_nama" => "project",
//            "produk_kode" => "product no",
            "produk_ord_hrg" => "biaya",
//            "produk_ord_jml" => "jumlah",
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
//            2 => "jml*(harga-disc)",
            1 => "jml",
            2 => "jml",
            3 => "jml*nett1",
//            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
//            1 => array(
//                "nett1" => "Price",
//                //                "disc" => "disc",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            2 => array(
//
//                "nett1" => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
            1 => array(
//                "stok" => "Stok available",
//                "stok_center" => "Stok dc",
                "harga" => "biaya",
                //                "ppn"   => "VAT",
            ),
            2 => array(
                "harga" => "biaya",
                //                "ppn"   => "VAT",
            ),
            3 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "biaya",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
            2 => array(
                "harga" => "biaya",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
        ),
        "receipCartNumFields2" => array(
//            1 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
                "customer_nama" => "konsumen",
                "nama" => "project",
                "harga_nppn" => "nilai project",
            ),
            2 => array(
                "customer_nama" => "konsumen",
                "nama" => "project",
                "harga_nppn" => "nilai project",
                "harga" => "biaya",
            ),

        ),
        "receiptDetailFields2" => array(
//            1 => array(
//                "produk_nama" => "item name",
////                "produk_ord_harga" => "harga",
//                // "satuan" => "uom",
//            ),
//            2 => array(
//                "nama" => "item name",
//                // "harga" => "harga",
//                "produk_kode" => "produk code",
//                // "produk_nama" => "Description",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "nama" => "item name",
                // "harga" => "harga",
                "produk_kode" => "produk code",
                // "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "item name",
                // "harga" => "harga",
                "produk_kode" => "produk code",
                // "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptSumFields2" => array(
//            1 => array(//                "hpp" => "grand total"
//            ),
//            2 => array(//                "hpp" => "grand total"
//            ),
            1 => array(//                "hpp" => "grand total"
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
//            1 => array("size" => "normal"),
            // 2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
//            2 => "SAN/F/SA001/R00",
            1 => "SAN/F/LOG001/R00",
            2 => "SAN/F/LOG001/R00",
            3 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            1 => "",
            3 => "true",
        ),
        "receiptInword" => array(
//            "1" => array(
//                "in_word" => array("inWordInd" => "grand_pembulatan",),
//            ),
//            "2" => array(
//                "in_word" => array("inWordInd" => "new_net3",),
//            ),
            "1" => array(),
            "2" => array(),
            "3" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
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
        "fixedFieldHoldConsolidate" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(

                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama" => "cabang",
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
                "cabang_nama" => "cabang",
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
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
//            1 => array(
//                "sign_1",
//            ),
//            2 => array(
//                "sign_1",
//                "sign_2",
//            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
//                    "transaksi_nilai" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
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
//            "steps" => array(
//                1 => array(
//                    "label" => "pre order",
//                    "labelPre" => "invoice",
//                ),
//            ),
        ),
        "print_hitung" => array(
            3 => false,
        ),
        "print_hitung_itemRecap" => array(
            3 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer" => array(
            3 => array(
                "ongkir" => "ongkir",
                "new_net1" => "nett1+ongkir",
//                "dp_value" => "dp_value",
//                "dp_ppn_value" => "dp_ppn_value",
//                "total_ui" => "total_ui",
                "nett1_bulat" => "new_net1",
                "ppn_out_bulat" => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net" => "ppn",
//                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan" => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields" => array(
            3 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown" => array(
            3 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),
        "receiptElementInjector" => array(
            "source" => array(
                "element" => "customerDetails",
                "fields" => array(
                    "nama" => "customer_nama",
//                    "tlp_1" => "customer_tlp",
//                    "npwp" => "customer_npwp",
                ),
                "usedFields" => array(
                    "customer_nama" => "Customer",
                ),
            ),
            "target" => array(
                "element" => "deliveryDetails",
            ),
        ),

    ),
    "5887" => array(
        "receiptTemplate" => array(
            1 => "template/588spo.html",
            2 => "template/582so.html",
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
        "customButton" => array(),
        "elementFixedNumberSO" => array(
            1 => array(
                "nomer" => "No",
            ),
            2 => array(
                "nomer" => "No",
            ),
            3 => array(
                "nomer" => "INV No",
            ),
        ),
        "fixedElements" => array(
            1 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
            ),
            2 => array(
                "projectName" => "Project",
                // "projectHarga"=>"Project price",
                // "projectPpn"=>"Project tax",
                // "projectGrandtotal"=>"Project grandtotal",
                "nomer" => " No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                // "dtime" => "Packing list date",
//                "shippingDate_value" => "Delivery Date",

                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                "projectHarga" => "Project price",
                "projectPpn" => "Project tax",
                "projectGrandtotal" => "Project grandtotal",

                //                "shippingService_name" => "shipping service",
                // "transaksi_jenis2_label" => "Paket",
            ),
        ),
        "hideFixedElements" => array(),
        "fixedSignatures" => array(

        ),
        "headerTables" => array(
            "produk_nama" => "project",
//            "produk_kode" => "product no",
            "produk_ord_hrg" => "biaya",
//            "produk_ord_jml" => "jumlah",
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
//            2 => "jml*(harga-disc)",
            1 => "jml",
            2 => "jml",
            3 => "jml*nett1",
//            5 => "jml*(harga-disc)",
        ),
        "receipNumFields" => array(
//            1 => array(
//                "nett1" => "Price",
//                //                "disc" => "disc",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
//            2 => array(
//
//                "nett1" => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                //                "ppn" => "VAT",
//            ),
            1 => array(
//                "stok" => "Stok available",
//                "stok_center" => "Stok dc",
                "harga" => "biaya",
                //                "ppn"   => "VAT",
            ),
            2 => array(
                "harga" => "biaya",
                //                "ppn"   => "VAT",
            ),
            3 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "biaya",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
            2 => array(
                "harga" => "biaya",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
        ),
        "receipCartNumFields2" => array(
//            1 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "produk_ord_jml" => "Qty",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
                "customer_nama" => "konsumen",
                "nama" => "project",
                "harga_nppn" => "nilai project",
            ),
            2 => array(
                "customer_nama" => "konsumen",
                "nama" => "project",
                "harga_nppn" => "nilai project",
                "harga" => "biaya",
            ),

        ),
        "receiptDetailFields2" => array(
//            1 => array(
//                "produk_nama" => "item name",
////                "produk_ord_harga" => "harga",
//                // "satuan" => "uom",
//            ),
//            2 => array(
//                "nama" => "item name",
//                // "harga" => "harga",
//                "produk_kode" => "produk code",
//                // "produk_nama" => "Description",
//                "satuan" => "UOM",
//            ),
            1 => array(
                "nama" => "item name",
                // "harga" => "harga",
                "produk_kode" => "produk code",
                // "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "item name",
                // "harga" => "harga",
                "produk_kode" => "produk code",
                // "produk_nama" => "Description",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
            2 => array(
                "harga" => "total",
            ),
        ),
        "receiptSumFields2" => array(
//            1 => array(//                "hpp" => "grand total"
//            ),
//            2 => array(//                "hpp" => "grand total"
//            ),
            1 => array(//                "hpp" => "grand total"
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
//            1 => array("size" => "normal"),
            // 2 => array("size" => "normal"),
//            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
//            2 => "SAN/F/SA001/R00",
            1 => "SAN/F/LOG001/R00",
            2 => "SAN/F/LOG001/R00",
            3 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            1 => "",
            3 => "true",
        ),
        "receiptInword" => array(
//            "1" => array(
//                "in_word" => array("inWordInd" => "grand_pembulatan",),
//            ),
//            "2" => array(
//                "in_word" => array("inWordInd" => "new_net3",),
//            ),
            "1" => array(),
            "2" => array(),
            "3" => array(
                "in_word" => array("inWordInd" => "grand_pembulatan",),
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
        "fixedFieldHoldConsolidate" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk" => array(

                "label" => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama" => "cabang",
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
                "cabang_nama" => "cabang",
                "label" => "customer",
                "target" => "customer",
                "srcKey" => "customers_id",
                "fields" => array(
                    "customers_nama" => "Customer",
                    "nomer_top" => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
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
//            1 => array(
//                "sign_1",
//            ),
//            2 => array(
//                "sign_1",
//                "sign_2",
//            ),
        ),
        "fixedFieldHold" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top" => "nomer",
                    "dtime" => "approved",
                    "seller_nama" => array(
                        "step" => 1,
                        "key" => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
//                    "transaksi_nilai" => "nilai",
                    "outstanding_items" => "detail items",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop" => array(),
                "items" => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
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
//            "steps" => array(
//                1 => array(
//                    "label" => "pre order",
//                    "labelPre" => "invoice",
//                ),
//            ),
        ),
        "print_hitung" => array(
            3 => false,
        ),
        "print_hitung_itemRecap" => array(
            3 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer" => array(
            3 => array(
                "ongkir" => "ongkir",
                "new_net1" => "nett1+ongkir",
//                "dp_value" => "dp_value",
//                "dp_ppn_value" => "dp_ppn_value",
//                "total_ui" => "total_ui",
                "nett1_bulat" => "new_net1",
                "ppn_out_bulat" => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net" => "ppn",
//                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan" => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields" => array(
            3 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown" => array(
            3 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),
        "receiptElementInjector" => array(
            "source" => array(
                "element" => "customerDetails",
                "fields" => array(
                    "nama" => "customer_nama",
//                    "tlp_1" => "customer_tlp",
//                    "npwp" => "customer_npwp",
                ),
                "usedFields" => array(
                    "customer_nama" => "Customer",
                ),
            ),
            "target" => array(
                "element" => "deliveryDetails",
            ),
        ),

    ),
);