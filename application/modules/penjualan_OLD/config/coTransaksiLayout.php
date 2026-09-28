<?php

$config["coTransaksiLayout"] = array(
    // netto
    "582" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/582.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "nama",
                "alamat_1" => "alamat",
                "tlp_1" => "Tlp",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "tanggal",
                "customers_nama" => "Konsumen",
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
                "nomer" => "Nomer",
                "dtime" => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "paymentMethod_name" => "Pembayaran",
                "top_nama" => "TOP",
                "shippingDate_value" => "Tanggal Kirim",
                //                "shippingService_name" => "Biaya Kirim",
                //                "transaksi_jenis2_label" => "Paket",
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
                //                "transaksi_jenis2_label" => "Paket",
            ),
            3 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "shippingDate_value" => "Delivery Date",
                //                "shippingService_name" => "shipping service",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                //                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "nomer" => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                "dtime" => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                //                "shippingService_name" => "shipping service",
                //                "transaksi_jenis2_label" => "Paket",
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
            1 => array(
                // "nomer"                    => "Nomer",
                // "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                // "top_nama"                 => "TOP",
                // "paymentMethod_name"       => "Pembayaran",
                // "shippingDate_value"       => "Tanggal Kirim",
                // "shippingService_name"     => "Biaya Kirim",
                // "transaksi_jenis2_label"   => "Paket",
            ),
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
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            "deliveryDetails" => array(
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
        ),
        "headerField" => "heTransaksi_layout",
        "headerTables" => array(
            "produk_nama" => "nama produk",
            // "produk_kode" => "product no",
            "produk_ord_hrg" => "harga",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            // "jenis_label" => "activity",
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
        /*1b*/
        "receipNumFields" => array(
            1 => array(
                "nett1" => "harga",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(

                "nett1" => "harga",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "stok" => "Stok available",
                "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "harga",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        /*1c*/
        "receiptDetailFields" => array(
            1 => array(
                "id" => "PID",
                "barcode" => "sku",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "produk_ord_jml" => "Qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "PID",
                "barcode" => "sku",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id" => "PID",
                "barcode" => "sku",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                //                "produk_nama" => "Description",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml" => "dicancel",
                "packed_jml" => "dipacking",
                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new" => "Sub Berat",
                //                "sub_berat_gross"  => "Sub Berat",
                //                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
                //                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id" => "PID",
                "barcode" => "sku",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "barcode" => "sku",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_harga_dropshiper" => "subtotal",
                "sub_harga" => "subtotal",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        /*2a*/
        "receiptSumFields" => array(
            1 => array(
                "nett1" => "jumlah",
                //                "disc" => "disc",
                "ongkir_ui" => "Biaya kirim",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "sub total",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "PPN",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Total",
                "point_transaksi" => "point",
                "point_saldo_akhir" => "point total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "Total Amount",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "VAT",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
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
                "ongkir" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat",
                "total_ui" => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "total_ui" => "total Amount",
                "new_grand_ppn" => "VAT ",
                "tagihan" => "Grand Total",
            ),

        ),
        "terbilangSumFields" => array("grand_pembulatan" => "terbilang"),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "printMod" => array(
            1 => "&mod=1",
            2 => "&mod=1",
        ),
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
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
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items*",
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
                    "outstanding_items" => "detail items*",
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
        // "printException" => array(
        //     5 => "bulat",
        // ),
        //
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
        "showCabangInvoice" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
    ),
    // bruto
    "582_mod" => array(
        "receiptTemplate" => array(
            1 => "template/582spo_mod.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/582.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "nama",
                "alamat_1" => "alamat",
                "tlp_1" => "Tlp",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "tanggal",
                "customers_nama" => "Konsumen",
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
                "nomer" => "Nomer",
                "dtime" => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "paymentMethod_name" => "Pembayaran",
                "top_nama" => "TOP",
                "shippingDate_value" => "Tanggal Kirim",
                //                "shippingService_name" => "Biaya Kirim",
                //                "transaksi_jenis2_label" => "Paket",
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
                //                "transaksi_jenis2_label" => "Paket",
            ),
            3 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "shippingDate_value" => "Delivery Date",
                //                "shippingService_name" => "shipping service",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                //                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "nomer" => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                "dtime" => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                //                "shippingService_name" => "shipping service",
                //                "transaksi_jenis2_label" => "Paket",
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
            1 => array(
                // "nomer"                    => "Nomer",
                // "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                // "top_nama"                 => "TOP",
                // "paymentMethod_name"       => "Pembayaran",
                // "shippingDate_value"       => "Tanggal Kirim",
                // "shippingService_name"     => "Biaya Kirim",
                // "transaksi_jenis2_label"   => "Paket",
            ),
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
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            "deliveryDetails" => array(
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
            ),
        ),
        "fixedSignatures" => array(
            1 => array(
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
        ),
        "headerField" => "heTransaksi_layout",
        "headerTables" => array(
            "produk_nama" => "nama produk",
            // "produk_kode" => "product no",
            "produk_ord_hrg" => "harga",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            // "jenis_label" => "activity",
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
        /*1b*/
        "receipNumFields" => array(
            1 => array(
                "jual_dipakai" => "harga",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "jual_dipakai" => "harga",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "stok" => "Stok available",
                "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "jual_dipakai" => "harga",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        /*1c*/
        "receiptDetailFields" => array(
            1 => array(
                "id" => "PID",
                "barcode" => "sku",
                "produk_kode" => "sku",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "produk_ord_jml" => "Qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "PID",
                "barcode" => "sku",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id" => "PID",
                "barcode" => "sku",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                //                "produk_nama" => "Description",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml" => "dicancel",
                "packed_jml" => "dipacking",
                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new" => "Sub Berat",
                //                "sub_berat_gross"  => "Sub Berat",
                //                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
                //                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id" => "PID",
                "barcode" => "sku",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "barcode" => "sku",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_harga_dropshiper" => "subtotal",
                "sub_jual_dipakai" => "subtotal",
            ),
            2 => array(
                "sub_jual_dipakai" => "subtotal",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        /*2a*/
        "receiptSumFields" => array(
            1 => array(
                "jual_dipakai" => "jumlah",
                //                "disc" => "disc",
                //                "ongkir_ui" => "Biaya kirim",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                //                "nilai_pembulatan" => "pembulatan",
                //                "nett1_bulat" => "sub total",
                //                "grand_ppn" => "VAT",
                //                "ppn_out_bulat" => "PPN",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                //                "grand_pembulatan" => "Total",
                //                "point_transaksi" => "point",
                //                "point_saldo_akhir" => "point total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                //                "nilai_pembulatan" => "pembulatan",
                "jual_dipakai" => "Total",
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
                "ongkir" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat",
                "total_ui" => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "total_ui" => "total Amount",
                "new_grand_ppn" => "VAT ",
                "tagihan" => "Grand Total",
            ),

        ),
        "terbilangSumFields" => array("jual_dipakai" => "terbilang"),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter" => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            4 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "jual_dipakai",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "jual_dipakai",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "jual_dipakai",),
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
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items*",
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
                    "outstanding_items" => "detail items*",
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
        // "printException" => array(
        //     5 => "bulat",
        // ),
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
        "showCabangInvoice" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
    ),

    // netto
    "5822" => array(
        "receiptTemplate" => array(
            1 => "template/582spo_mod_polos.html",
            2 => "template/582spo_mod.html",
            3 => "template/582pkd_mod.html",
            4 => "template/466r_mod.html",
            5 => "template/582.html",
        ),
        "receiptView" => array(
            "default" => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582spo_mod.html",
            ),
//            1 => array(
//                "viewer" => "viewReceipt_mod",
//                "template" => "template/582spo_mod_polos.html",
//            ),
            3 => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582pkd_mod.html",
            ),
            4 => array(
                "viewer" => "viewReceipt_new",
                "template" => "template/466r_mod.html",
            ),
            5 => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582.html",
            ),
            7 => array(
                "viewer" => "viewReceipt_new",
                "template" => "template/print_a4.html",
            ),
            8 => array(
                "viewer" => "viewReceipt_new",
                "template" => "template/print_test.html",
            ),
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "nama",
                "alamat_1" => "alamat",
                "tlp_1" => "Tlp",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "tanggal",
                "customers_nama" => "Konsumen",
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
                    "label" => "Download Data Mentah SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Download Data Mentah APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Download Data Mentah PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Download Data Mentah PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
                2 => array(
                    "label" => "Download Data Mentah PACKING LIST FULL",
                    "target" => "ExcelWriter/row/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Download Data Mentah INVOICE",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
        ),
        "customHistoriExcel" => array(
            "nomer" => array(
                "label" => "INV",
                "type" => "string",
            ),
            "dtime" => array(
                "label" => "tanggal",
                "type" => "string",
            ),
            "nama" => array(
                "label" => "Model",
                "type" => "string",
            ),
            "produk_kode" => array(
                "label" => "type",
                "type" => "string",
            ),
            "pihakName" => array(
                "label" => "Customer",
                "type" => "string",
            ),
            "customerDetails__kabupaten" => array(
                "label" => "Kota",
                "type" => "string",
            ),
            "oleh_nama" => array(
                "label" => "Person",
                "type" => "string",
            ),
            "salesmanDetails__nama" => array(
                "label" => "salesman",
                "type" => "string",
            ),
            "jml" => array(
                "label" => "Qty",
                "type" => "integer",
            ),
            "sub_harga" => array(
                "label" => "Price",
                "type" => "integer",
            ),
            "sub_nett1" => array(
                "label" => "DPP",
                "type" => "integer",
            ),
            "trash_4" => array(
                "label" => "STATUS",
                "type" => "text",
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
                "nomer" => "Nomer",
                "dtime" => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "paymentMethod_name" => "Pembayaran",
                "top_nama" => "TOP",
                "shippingDate_value" => "Tanggal Kirim",
                //                "shippingService_name" => "Biaya Kirim",
                //                "transaksi_jenis2_label" => "Paket",
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
                //                "transaksi_jenis2_label" => "Paket",
            ),
            3 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",

                "dtime" => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",
                //                "shippingService_name" => "shipping service",
                //                "tanggal_transaksi" => "tanggal_transaksi",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                //                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "nomer" => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                "dtime" => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                //                "shippingService_name" => "shipping service",
                //                "transaksi_jenis2_label" => "Paket",
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
            1 => array(
                // "nomer"                    => "Nomer",
                // "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                // "top_nama"                 => "TOP",
                // "paymentMethod_name"       => "Pembayaran",
                // "shippingDate_value"       => "Tanggal Kirim",
                // "shippingService_name"     => "Biaya Kirim",
                // "transaksi_jenis2_label"   => "Paket",
            ),
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
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            "deliveryDetails" => array(
                "usedFields" => array(
                    "alias" => "<b>Attn</b>",
                    "alamat" => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
            ),
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "headerField" => "heTransaksi_layout",
        "headerTables" => array(
            "produk_nama" => "nama produk",
            // "produk_kode" => "product no",
            "produk_ord_hrg" => "harga",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            // "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        // ----------------------------------------------------------------------
        /*1a*/
        "receiptDetailFields" => array(
            1 => array(
                //                "id" => "PID",
                // "barcode" => "barcode",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "produk_ord_jml" => "Qty",
                // "satuan" => "satuan",
            ),
            2 => array(
                //                "id" => "PID",
                //                 "barcode" => "sku",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Deskripsi",
                    "addKey" => "keterangan",
                ),
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                //                "id" => "PID",
                // "barcode" => "barcode",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                //                "produk_nama" => "Description",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                // "berat_new" => "W(KG)",
                // "volume_new" => "CBM",
                //                "max_jml" => "SO",
                //                "req_cancel_jml" => "cancel request",
                //                "cancel_jml" => "dicancel",
                //                "packed_jml" => "dipacking",
                //                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                // "sub_berat_new" => "Sub Berat",
                //                "sub_berat_gross"  => "Sub Berat",
                //                "satuan" => "uom",
                // "sub_volume_new" => "Sub Volume",
                //                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                //                "id" => "PID",
                // "barcode" => "sku",
                // "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "jml" => "Qty",
                // "berat_new" => "Net/Pkg (Kgs)",
                // "sub_berat_new" => "Total (Kgs)",
                // "volume_new" => "Net/Pkg (Cbm)",
                // "sub_volume_new" => "Total (Cbm)",

//                "nett1nppn" => array(
//                    "label" => "harga",
//                    "attr" => "",
//                ),
//                "nett1" => array(
//                    "label" => "dpp",
//                    "attr" => "class='lysimpel'",
//                ),
//                "xppn" => array(
//                    "label" => "ppn",
//                    "attr" => "class='lysimpel text-right'",
//                ),

            ),
            5 => array(
                //                "id" => "PID",
                //                 "barcode" => "sku",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Deskripsi",
                    "addKey" => "keterangan",
                ),
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
        ),
        /*1b*/
        "receiptNumFields" => array(
            1 => array(
                "nett1nppn" => array(
                    "label" => "harga",
                    "attr" => "",
                ),
                "nett1" => array(
                    "label" => "dpp",
                    "attr" => "class='lysimpel'",
                ),
                "xppn" => array(
                    "label" => "ppn",
                    "attr" => "class='lysimpel text-right'",
                ),
            ),
            2 => array(
                "nett1nppn" => array(
                    "label" => "harga",
                    "attr" => "",
                ),
                "nett1" => array(
                    "label" => "dpp",
                    "attr" => "class='lysimpel'",
                ),
                "xppn" => array(
                    "label" => "ppn",
                    "attr" => "class='lysimpel text-right'",
                ),
            ),
            3 => array(
                // "stok"        => "Stok available",
                // "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        /*1c*/
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_harga_dropshiper" => "subtotal",
                // "sub_harga" => "subtotal",
                /*---mengunakan subtotal dari harga (angkanya bisa dulet2)---*/
                "sub_nett1_include_ppn" => array(
                    "label" => "subtotal",
                    "attr" => "class='subtotal'",
                    "alt" => "subtotal"
                ),
                /*---akan mengunakan subtotal dr dpp--*/
//                 "subtotal"              => array(
//                     "label" => "subtotal",
//                     "attr"  => "class='subtotal'",
//                     "alt"   => "sub_nett1_include_ppn"
//                 ),

            ),
            2 => array(
                /*---mengunakan subtotal dari harga (angkanya bisa dulet2)---*/
                "sub_nett1" => array(
                    "label" => "jumlah",
                    "attr" => "class='subtotal'",
                    "alt" => "subtotal"
                ),
                /*---akan mengunakan subtotal dr dpp--*/
                // "subtotal" => array(
                //     "label" => "jumlah",
                //     "attr"  => "class='subtotal'",
                //     "alt"   => "sub_nett1"
                // ),
            ),
            3 => array(// "sub_harga" => "Total Price",
            ),
        ),
        /*2a*/
        "receiptSumFields" => array(
            1 => array(
                "lastNett" => "total bruto",
                "diskon_kategori_unit" => "diskon",
                "nett1" => "total dpp",
                //                "disc" => "disc",
                // "ongkir_ui" => "Biaya kirim",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                // "nilai_pembulatan" => "pembulatan",
                // "nett1_bulat" => "sub total",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "total PPN",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "grand Total",
                // "point_transaksi"      => "point transaksi",
                // "point_saldo_akhir"    => "point total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                // "ongkir_ui" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                // "nilai_pembulatan" => "pembulatan",
                "diskon_kategori_unit" => "diskon",
                "nett1" => "total dpp",
                "ppn_out_bulat" => "total PPN",
                // "nett1_bulat" => "Total",
                //                "grand_ppn" => "VAT",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
            3 => array(

                //                "berat_new" => "Berat",
                //                "volume_new" => "Volume",
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            4 => array(
                "sum_jml" => "total",
            ),
            5 => array(
                "pym_src_total_dipakai" => "Uang Muda (DP)",
                "nett1" => "total dpp",
                //                "disc" => "disc",
                // "ongkir_ui" => "Biaya kirim",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                // "nilai_pembulatan" => "pembulatan",
                // "nett1_bulat" => "sub total",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "total PPN",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Total",
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*nett1",
            //            5 => "jml*(harga-disc)",
        ),
        // ----------------------------------gunakan grand_pembulatan (key yang sama digunakan di receiptSumFields)------------------------------------
        "terbilangSumFields" => array("grand_pembulatan" => "terbilang"),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printMod" => array(
            1 => "&mod=1",
            2 => "&mod=1",
        ),
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
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
        "fixedSignatures" => array(
            1 => array(
                "sign_1" => true,
                "sign_2" => false,
                "sign_4" => false,
                "sign_5" => false,
                "salesman" => array(
                    "label" => ".salesman",
                    "contents" => "salesmanDetails__nama",
                    "stateCaption" => "",
                ),
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            2 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => false,
                "sign_5" => false,
                "salesman" => array(
                    "label" => ".salesman",
                    "contents" => "salesmanDetails__nama",
                    "stateCaption" => "",
                ),
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            3 => array(
                "sign_1" => true,
                "sign_2" => true,
                // "sign_3" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "sign_5" => array(
                    "label" => ".Driver",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
            ),
            4 => array(
                "sign_1" => true,
                "sign_2" => false,
                "sign_4" => true,
                // "sign_4" => array(
                //     "label" => ".Ka. Logistik",
                //     "contents" => "worker_nama",
                //     // "stateCaption" => "",
                // ),
                "salesman" => false,
                "customer" => array(
                    "label" => ".ttd & cap penerima",
                    // "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim ttt",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),

                "driver" => array(
                    "label" => ".driver",
                    "contents" => "",
                    "stateCaption" => "",
                ),
                "worker" => array(
                    "label" => ".teknisi",
                    "contents" => "",
                    "stateCaption" => "",
                ),
                "logistik" => array(
                    "label" => "ka.&nbsp;logistic",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
            5 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
        ),
        // --------------------------------------------------------------------
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            //            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__nama" => "nama",
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
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items*",
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
                "sign_0",
                "sign_1",
            ),
            2 => array(
                "sign_0",
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
                    "outstanding_items" => "detail items*",
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
        "showCabangInvoice" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
        "showGudangStatus" => array(
            1 => true,
            2 => true,
            //            3 => true,
            //            4 => true,
            //            5 => false,
            //
        ),
        //------
        "receiptCustomMembership" => array(
            "o_finance", "c_finance"
        ),
        "receiptDetailFieldsCustom" => array(
            4 => array(
                "produk_kode" => "sku",
                "produk_nama" => array(
                    "label" => "Deskripsi",
                    "addKey" => "keterangan",
                ),
                "jml" => "Qty",
                "nett1nppn" => array(
                    "label" => "harga",
                    "attr" => "",
                ),
                "nett1" => array(
                    "label" => "dpp",
                    "attr" => "class='lysimpel'",
                ),
                "xppn" => array(
                    "label" => "ppn",
                    "attr" => "class='lysimpel text-right'",
                ),
                "sub_nett1" => array(
                    "label" => "jumlah",
                    "attr" => "class='subtotal'",
                    "alt" => "subtotal"
                ),
            ),
        ),
        "receiptSumFieldsCustom" => array(
            4 => array(
//                "lastNett" => "total bruto",
//                "diskon_kategori_unit" => "diskon",
                "nett1" => "total dpp",
                "ppn_out_bulat" => "total PPN",
                "grand_pembulatan" => "grand Total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                // "ongkir_ui" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                // "nilai_pembulatan" => "pembulatan",
                "diskon_kategori_unit" => "diskon",
                "nett1" => "total dpp",
                "ppn_out_bulat" => "total PPN",
                // "nett1_bulat" => "Total",
                //                "grand_ppn" => "VAT",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
        ),
        "receiptHadiahFields" => array(
            "nama" => "produk",
            "produk_kode" => "sku",
            "qty" => "qty",
        ),
        "receiptItemHadiahKey" => array(
            "key" => "type_produk",
            "value" => "hadiah"
        ),
        //----
//        "receiptPolos" => array(
//            1 => array(
//                "enabled" => true,
//                "template" => "template/582spo_mod_polos.html",
//            ),
//        ),
    ),
    // bruto
    "5822_mod" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/582.html",
        ),
        "receiptView" => array(
            "default" => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582spo.html",
            ),
            1 => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582spo.html",
            ),
            2 => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582so.html",
            ),
            3 => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582pkd.html",
            ),
            4 => array(
                "viewer" => "viewReceipt_new",
                "template" => "template/466r_mod.html",
            ),
            5 => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582.html",
            ),
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "nama",
                "alamat_1" => "alamat",
                "tlp_1" => "Tlp",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "tanggal",
                "customers_nama" => "Konsumen",
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
                "nomer" => "Nomer",
                "dtime" => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "paymentMethod_name" => "Pembayaran",
                "top_nama" => "TOP",
                "shippingDate_value" => "Tanggal Kirim",
                //                "shippingService_name" => "Biaya Kirim",
                //                "transaksi_jenis2_label" => "Paket",
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
                //                "transaksi_jenis2_label" => "Paket",
            ),
            3 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "shippingDate_value" => "Delivery Date",
                //                "shippingService_name" => "shipping service",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                //                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "nomer" => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                "dtime" => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                //                "shippingService_name" => "shipping service",
                //                "transaksi_jenis2_label" => "Paket",
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
            1 => array(
                // "nomer"                    => "Nomer",
                // "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                // "top_nama"                 => "TOP",
                // "paymentMethod_name"       => "Pembayaran",
                // "shippingDate_value"       => "Tanggal Kirim",
                // "shippingService_name"     => "Biaya Kirim",
                // "transaksi_jenis2_label"   => "Paket",
            ),
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
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            "deliveryDetails" => array(
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
            ),
        ),
        // "fixedSignatures" => array(
        //     1 => array(
        //         "customer" => array(
        //             "label" => ".Konsumen",
        //             "contents" => "customerDetails__nama",
        //             "stateCaption" => "",
        //         ),
        //     ),
        //     2 => array(
        //         "customer" => array(
        //             "label" => ".Konsumen",
        //             "contents" => "customerDetails__nama",
        //             "stateCaption" => "",
        //         ),
        //     ),
        //     4 => array(
        //         "customer" => array(
        //             "label" => ".Konsumen",
        //             "contents" => "customerDetails__nama",
        //             "stateCaption" => "",
        //         ),
        //         "pengirim" => array(
        //             "label" => ".Pengirim",
        //             "contents" => "pengirim_nama",
        //             "stateCaption" => "",
        //         ),
        //         "penerima" => array(
        //             "label" => ".Penerima",
        //             "contents" => "",
        //             "stateCaption" => "",
        //         ),
        //     ),
        // ),
        "fixedSignatures" => array(
            1 => array(
                "sign_1" => true,
                "sign_2" => false,
                "sign_4" => false,
                "sign_5" => false,
                "customerSignitures" => false,
                "salesman" => array(
                    "label" => ".salesman",
                    "contents" => "salesmanDetails__nama",
                    "stateCaption" => "",
                ),
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            2 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => false,
                "sign_5" => false,
                "salesman" => array(
                    "label" => ".salesman",
                    "contents" => "salesmanDetails__nama",
                    "stateCaption" => "",
                ),
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            3 => array(
                "sign_1" => true,
                "sign_2" => true,
                // "sign_3" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "sign_5" => array(
                    "label" => ".Driver",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
            ),
            4 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => true,
                // "sign_4" => array(
                //     "label" => ".Ka. Logistik",
                //     "contents" => "worker_nama",
                //     // "stateCaption" => "",
                // ),
                "salesman" => false,
                "customer" => array(
                    "label" => ".ttd penerima",
                    // "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim ttt",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),

                "driver" => array(
                    "label" => ".driver",
                    "contents" => "",
                    "stateCaption" => "",
                ),
                "worker" => array(
                    "label" => ".teknisi",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
            5 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
        ),
        "headerField" => "heTransaksi_layout",
        "headerTables" => array(
            "produk_nama" => "nama produk",
            // "produk_kode" => "product no",
            "produk_ord_hrg" => "harga",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            // "jenis_label" => "activity",
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
        /*1b*/
        "receipNumFields" => array(
            1 => array(
                "jual_dipakai" => "harga",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "jual_dipakai" => "harga",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            3 => array(
                "stok" => "Stok available",
                "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "jual_dipakai" => "harga",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
                "nett1" => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        /*1c*/
        "receiptDetailFields" => array(
            1 => array(
                //                "id" => "PID",
                "barcode" => "barcode",
                // "produk_kode" => "sku",
                "produk_nama" => array(
                    "label" => "Deskripsi",
                    "addKey" => "keterangan",
                ),
                "produk_ord_jml" => "Qty",
                "satuan" => "satuan",
            ),
            2 => array(
                //                "id" => "PID",
                "barcode" => "barcode",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                //                "id" => "PID",
                "barcode" => "sku",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                //                "produk_nama" => "Description",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "berat_new" => "W(KG)",
                "volume_new" => "CBM",
                "max_jml" => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml" => "dicancel",
                "packed_jml" => "dipacking",
                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new" => "Sub Berat",
                //                "sub_berat_gross"  => "Sub Berat",
                //                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
                //                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                //                "id" => "PID",
                "barcode" => "sku",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "barcode" => "sku",
                "produk_kode" => "Product code",
                "no_part" => "part number",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_harga_dropshiper" => "subtotal",
                "sub_jual_dipakai" => "subtotal",
            ),
            2 => array(
                "sub_jual_dipakai" => "subtotal",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),
        /*2a*/
        "receiptSumFields" => array(
            1 => array(
                "nett1_ppn" => "jumlah",
                //                "jual_dipakai" => "jumlah",
                //                "disc" => "disc",
                //                "ongkir_ui" => "Biaya kirim",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                //                "nilai_pembulatan" => "pembulatan",
                //                "nett1_bulat" => "sub total",
                //                "grand_ppn" => "VAT",
                //                "ppn_out_bulat" => "PPN",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                //                "grand_pembulatan" => "Total",
                //                "point_transaksi" => "point",
                //                "point_saldo_akhir" => "point total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                //                "nilai_pembulatan" => "pembulatan",
                "jual_dipakai" => "Total",
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
                "ongkir" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                "new_net1" => "Amount",
                //                "new_net2" => "grand total",
                "dp_value" => "Downpayment",
                "dp_ppn_value" => "Dp Vat",
                "total_ui" => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "total_ui" => "total Amount",
                "new_grand_ppn" => "VAT ",
                "tagihan" => "Grand Total",
            ),

        ),
        "terbilangSumFields" => array("nett1_ppn" => "terbilang"),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
//        "staticFooter" => array(
//            2 => "SAN/F/SA001/R00",
//            3 => "SAN/F/LOG001/R00",
//            4 => "SAN/F/LOG001/R00",
//            5 => "SAN/F/FA005/R00",
//        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "jual_dipakai",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "jual_dipakai",),
            ),
            "3" => array(),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordInd" => "jual_dipakai",),
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
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items*",
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
                    "outstanding_items" => "detail items*",
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
        // "printException" => array(
        //     5 => "bulat",
        // ),
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
        "showCabangInvoice" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
        "showGudangStatus" => array(
            1 => true,
            2 => true,
            //            3 => true,
            //            4 => true,
            //            5 => false,
            //
        ),
        "receiptHadiahFields" => array(
            "nama" => "produk",
            "produk_kode" => "sku",
            "qty" => "qty",
        ),
        "receiptItemHadiahKey" => array(
            "key" => "type_produk",
            "value" => "hadiah"
        ),
    ),


    "982" => array(
        "receiptTemplate" => array(
            1 => "template/982r.html",
            2 => "template/982g.html",
            3 => "template/982.html",
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
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            4 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            5 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            6 => array(
                "nomer" => "INV No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
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
            3 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
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
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
            3 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nett2",),
        //
        //        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
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
    ),
    "9822" => array(
        "receiptTemplate" => array(
            1 => "template/982r.html",
            2 => "template/982g.html",
            3 => "template/982.html",
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
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            3 => array(
                "nomer" => "No",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            4 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            5 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            6 => array(
                "nomer" => "INV No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                //                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
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
            3 => array(
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
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
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "barcode" => "sku",
                "produk_nama" => "product name",
                "produk_kode" => "part name",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
            3 => array(
                "harga" => "Amount",
                "disc" => "DISC",
                "ppn" => "VAT",
                "nett2" => "Grand Total",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nett2",),
        //
        //        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "nett2"),
            ),
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
    ),


    //export
    "382" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/382.html",
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "name",
                "alamat_1" => "address",
                "country_label" => "country",
                "tlp_1" => "phone",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery addrress" => array(
                "dtime" => "date",
                "suppliers_nama" => "Supplier",
                "tlp_1" => "phone",
                "alamat_1" => "address",
                "country_label" => "country",
                "dtime_jatuh_tempo" => "due date",
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
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "top_nama" => "Term of Payment",
                "shippingDate_value" => "Delivery Date",
            ),
            2 => array(
                //                "nomer" => "No",
                "dtime" => "Date",
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama" => "PIC name",
                "customerDetails_tlp_1" => "Phone",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                //                "customerDetails_npwp"     => "Tax ID/NPWP",
                "top_nama" => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value"            => "Due Date",
                "shippingDate_value" => "Delivery Date",
            ),

            3 => array(
                "nomer" => "No",
                "shippingDate_value" => "Delivery Date",
                "nomer_top" => "SO No.",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
            ),
            4 => array(
                "nomer" => "No",
                "shippingDate_value" => "Delivery Date",
                "nomer_top" => "SO No.",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
            ),
            5 => array(
                "nomer" => "INV No",
                "nomer_top" => "SO No.",
                "dtime" => "Date",
                "top_nama" => "Term of Payment",
                //                "dueDate_value" => "Due Date",
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
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "sub_nett2_valas" => "sub-total"
            ),
            2 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),

            3 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "berat_gross" => "weight",
                "volume_gross" => "volume",
            ),
            4 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                "berat_gross" => "weight",
                "volume_gross" => "volume",
            ),
            5 => array(
                "produk_nama" => "product name",
                "produk_kode" => "product no",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1_valas" => "Harga",
                "sub_nett1_valas" => "sub-total",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "nett1_valas" => "price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
                "sub_nett1_valas" => "sub-total",
            ),
            3 => array(
                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1_valas" => "price",
                //                "ppn" => "VAT",
                "sub_nett1_valas" => "sub-total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                //                "harga" => "price",
                "valas_nilai" => "price",
                "disc_percent" => "disc (%)",
                "disc_valas" => "disc",
                "sub_harga_valas" => "sub-total"
                //                "ppn" => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "valas_nilai" => "price",
                "disc_percent" => "disc (%)",
                "disc_valas" => "disc",
                "sub_harga_valas" => "sub-total",
            ),

            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                "valas_nilai" => "price",
                "disc_percent" => "disc (%)",
                "disc_valas" => "disc",
                "sub_harga_valas" => "sub-total",

            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "nett2_valas" => "total",
                "ongkir" => "shipping service",
                "grand_total_valas" => "grand total",
            ),
            2 => array(
                "nett2_valas" => "total",
                "ongkir" => "shipping service",
                "grand_total_valas" => "grand total",
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
                "nett2_valas" => "total amount",
                "ppn" => "vat (0%)",
                "ongkir" => "shipping service",
                "grand_total_valas" => "grand total",
            ),
        ),
        "receiptSumFieldsZeroAllowed" => array(
            5 => array(
                "ppn",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "receiptInword" => array(
            "1" => array(
                "in_word" => array("inWordEng" => "grand_total_valas",),
                "currency_id" => "valasDetails",
            ),
            "2" => array(
                "in_word" => array("inWordEng" => "grand_total_valas",),
                "currency_id" => "valasDetails",
            ),
            "3" => array(
                "in_word" => array("inWordEng" => "grand_total_valas",),
                "currency_id" => "valasDetails",
            ),
            "4" => array(),
            "5" => array(
                "in_word" => array("inWordEng" => "grand_total_valas",),
                "currency_id" => "valasDetails",
            ),
        ),
        "print_nvalas" => true,
        "staticFooter" => array(
            3 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes" => array(
            3 => "",
            5 => "true",
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
                    "print_label" => "tool",
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
                    "avail_qty" => "Tersedia",
                    "print_label" => "tool",

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

    ),
    "1982" => array(
        "receiptTemplate" => array(
            1 => "template/982r.html",
            2 => "template/982r.html",
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
                "nomer" => "No",
                "nomer2" => "No SO",
                //                "ids_his"            => "No SO-",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No",
                "nomer2" => "No SO",
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
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
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
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => "Description",
                "produk_kode" => "Product No.",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => "Description",
                "produk_kode" => "Product No.",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
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
                //                "harga" => "Amount",
                //                "disc"  => "DISC",
                //                "ppn"   => "VAT",
                //                "nett2" => "Grand Total",
            ),
            2 => array(
                //                "harga" => "Amount",
                //                "disc"  => "DISC",
                //                "ppn"   => "VAT",
                //                "nett2" => "Grand Total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            //            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nett2",),
        //
        //        ),
        "receiptInword" => array(
            1 => array(//                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(//                "sub_harga" => "Total Price",
            ),
        ),
    ),
    "19822" => array(
        "receiptTemplate" => array(
            1 => "template/982r.html",
            2 => "template/982r.html",
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
                "nomer" => "No",
                "nomer2" => "No SO",
                //                "ids_his"            => "No SO-",
                "dtime" => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama" => "Term of Payment",
                "tos_nama" => "Term of Shipment",
                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
            2 => array(
                "nomer" => "No",
                "nomer2" => "No SO",
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
                "customer" => array(
                    "label" => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
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
            "customers_nama" => "customer",
            "dtime" => "date",
        ),
        "receiptDetailFields" => array(
            1 => array(
                "barcode" => "sku",
                "produk_nama" => "Description",
                "produk_kode" => "Product No.",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "barcode" => "sku",
                "produk_nama" => "Description",
                "produk_kode" => "Product No.",
                "produk_ord_jml" => "qty",
                "satuan" => "uom",
                //                "hpp" => "price",
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
                //                "harga" => "Amount",
                //                "disc"  => "DISC",
                //                "ppn"   => "VAT",
                //                "nett2" => "Grand Total",
            ),
            2 => array(
                //                "harga" => "Amount",
                //                "disc"  => "DISC",
                //                "ppn"   => "VAT",
                //                "nett2" => "Grand Total",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(),
            2 => array(),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceipt/",
        "allowPrint" => array(
            //            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nett2",),
        //
        //        ),
        "receiptInword" => array(
            1 => array(//                "in_word" => array("inWordInd" => "nett2"),
            ),
        ),
        "receiptSumDetailFields" => array(
            1 => array(//                "sub_harga" => "Total Price",
            ),
        ),
    ),
    // paket
    "1582" => array(
        "receiptTemplate" => array(
            1 => "template/1582spo.html",
            2 => "template/1582spo.html",
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
                "oleh_name" => "",
                //                "customerDetails_alamat_1" => "Billing address",
                //                "top_nama" => "Term of Payment",
            ),
            2 => array(
                "nomer" => "No",
                "dtime" => "Date",
                //                "shippingDate_value" => "Consignment notesss",
                "nomer_top" => "Ref No.",
                //                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
            ),

            //            3 => array(
            //                "nomer" => "No",
            //                "shippingDate_value" => "Delivery Date",
            //                "nomer_top" => "SO No.",
            //                "tos_nama" => "Term of Shipment",
            //                "keterangan" => "Remark",
            //                //                "top_nama" => "Term of Payment",
            //                //                "capacity_nama" => "Capacity",
            //                //                "dueDate_value" => "Due Date",
            //                //                "dtime" => "Date",
            //            ),
            //            4 => array(
            //                "nomer" => "No",
            //                "shippingDate_value" => "Delivery Date",
            //                "nomer_top" => "SO No.",
            //                "tos_nama" => "Term of Shipment",
            //                "keterangan" => "Remark",
            //                //                "top_nama" => "Term of Payment",
            //                //                "capacity_nama" => "Capacity",
            //                //                "dueDate_value" => "Due Date",
            //                //                "dtime" => "Date",
            //            ),
            //            5 => array(
            //                "nomer" => "INV No",
            //                "nomer_top" => "SO No.",
            //                "dtime" => "Date",
            //                "top_nama" => "Term of Payment",
            //                "dueDate_value" => "Due Date",
            //                //                "tos_nama" => "Term of Shipment",
            //                //                "capacity_nama" => "Capacity",
            //                //                "shippingDate_value" => "Delivery Date",
            //            ),
            //            6 => array(
            //                "nomer" => "No",
            //                "dtime" => "Date",
            //                "customerDetails_alamat_1" => "Billing address",
            //                "customerDetails_nama" => "PIC name",
            //                "customerDetails_tlp_1" => "Phone",
            //                "customerDetails_tlp_2" => "Handphone",
            //                "customerDetails_email" => "Email",
            //                "customerDetails_npwp" => "Tax ID/NPWP",
            //                "top_nama" => "Term of Payment",
            //                //                "tos_nama" => "Term of Shipment",
            //                //                "capacity_nama" => "Capacity",
            //                "dueDate_value" => "Due Date",
            //                "shippingDate_value" => "Delivery Date",
            //            ),
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
                    "label" => ".Confirmed and received by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),

        ),
        "headerTables" => array(
            "produk_nama" => "item name",
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
                //                "produk_nama+produk_ord_jml" => "item name",
                //                "produk_nama"    => "item name",
                "nama" => "item name",
                //                "produk_ord_jml" => "qty",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",
                //                "berat_gross" => "berat",
                //                "volume_gross" => "volume",
            ),
        ),
        "receiptDetailFields2" => array(
            1 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "uom",

                //                "harga_ori"    => "price",
                //                "disc_percent" => "disc(%)",
                //                "disc"         => "disc(idr)",
                //                "ppn"          => "vat",

                "nett1" => "price",

            ),
            2 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                //                "produk_nama" => "item source name",
                //                "produk_ord_jml" => "qty",
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                //                                "harga_ori" => "amount",
                //                                "disc" => "disc",
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
            ),
            2 => array(
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "disc" => "discount",
                //                "nett2" => "total",
            ),
        ),
        //        "receiptSumFields2" => array(
        //            1 => array(
        //                "harga_ori" => "amount",
        //                "disc" => "disc",
        //                "ongkir_ui" => "Shipping Service",
        //                "nilai_pembulatan" => "pembulatan",
        //                "nett1_bulat" => "Total Amount",
        //                "ppn_out_bulat" => "VAT",
        //                "grand_pembulatan" => "Grand Total",
        //            ),
        //            2 => array(//                "hpp" => "grand total"
        //            ),
        //            3 => array(//                "hpp" => "grand total"
        //            ),
        //        ),
        "receiptNumFields" => array(
            1 => array(
                "nett1_bulat" => "price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn_out_bulat" => "VAT",
            ),
            2 => array(
                "nett1_bulat" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn_out_bulat" => "VAT",
            ),
            3 => array(
                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "receipNumFields" => array(
            1 => array(
                "nett1_bulat" => "price",
                //                "disc" => "disc",
                //                "disc_percent"  => "disc (%)",
                //                "disc"          => "disc (IDR)",
                //                "ppn_out_bulat" => "VAT",
            ),
            2 => array(
                "nett1_bulat" => "price",
                //                "disc" => "disc",
                //                "disc_percent"  => "disc (%)",
                //                "disc"          => "disc (IDR)",
                //                "ppn_out_bulat" => "VAT",
            ),
            3 => array(
                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "price",
                //                "ppn" => "VAT",
            ),
        ),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "staticFooter" => array(
            //            3 => "GROSIR/I/LOG001/R00",
            //            5 => "GROSIR/I/FA005/R00",
        ),
        "allowPrint" => array(
            1 => array("size" => "normal"),
            //            2 => array("size" => "normal"),
        ),
        //        "smallPrint" => array(
        //            1 => array(
        //                "dtime" => "date",
        //                "nomer" => "no nota",
        //                "oleh_nama" => "kasir",
        //
        //            ),
        //        ),
    ),
    //penjaualan jasa
    "584" => array(
        "receiptTemplate" => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582.html",
            //            4 => "template/582spd.html",
            //            5 => "template/582.html",
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
                "nomer_top" => "SO No",
                "nomer" => "INV No",
                "dtime" => "Date",
                "paymentMethod_name" => "Payment Method",
                //                "dueDate_value" => "Due Date",
                //                "shippingService_name" => "shipping service",
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
            3 => array(
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
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml*(harga-disc)",
            //            4 => "jml",
            //            5 => "jml*(harga-disc)",
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
                //                "stok" => "Stok",
                "nett1" => "Price",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "receiptNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            3 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),

        ),
        "receiptDetailFields" => array(
            1 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "UOM",
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
                //                "berat_new" => "W(KG)",
                //                "volume_new" => "CBM",
                //                "max_jml" => "SO",
                //                "sent_jml" => "Tekirim",
                //                "produk_ord_jml" => "Qty",
                //                "sub_berat_new" => "Sub Berat",
                //                //                "satuan" => "uom",
                //                "sub_volume_new" => "Sub Volume",
            ),
            4 => array(
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml" => "Quantity Per Pkg (Ctns)",
                "berat_new" => "Net/Pkg (Kgs)",
                "sub_berat_new" => "Total (Kgs)",
                "volume_new" => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_kode" => "Product No.",
                "produk_nama" => "Description",
                "produk_ord_jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                "grand_ppn" => "vat",
                //                "new_net3" => "total amount",
                //                "pph_net_23" => "pph 23",
                "new_net4" => "Grand Total"
            ),
            2 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                "grand_ppn" => "vat",
                //                "new_net3" => "total amount",
                //                "pph_net_23" => "pph 23",
                "new_net4" => "Grand Total"
            ),

            3 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                "grand_ppn" => "vat",
                //                "new_net3" => "total amount",
                //                "pph_net_23" => "pph 23",
                "new_net4" => "Grand Total"
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
                "in_word" => array("inWordInd" => "new_net4",),
            ),
            "2" => array(
                "in_word" => array("inWordInd" => "new_net4",),
            ),
            "3" => array(
                "in_word" => array("inWordInd" => "new_net4",),
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
    "5823" => array(
        "receiptTemplate" => array(
            1 => "template/582spo_mod.html",
            2 => "template/582spo_mod.html",
            // 2 => "template/582so.html",
            3 => "template/582pkd_mod.html",
            // 3 => "template/582spo_mod.html",
            //             4 => "template/582spd_mod.html",
            //             4 => "template/582spo_mod.html",
            4 => "template/466r_mod.html",
            5 => "template/582.html",
            // 5 => "template/582spo_mod.html",
        ),
        "receiptView" => array(
            "default" => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582spo_mod.html",
            ),
            3 => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582pkd_mod.html",
            ),
            4 => array(
                "viewer" => "viewReceipt_new",
                "template" => "template/466r_mod.html",
            ),
            5 => array(
                "viewer" => "viewReceipt_mod",
                "template" => "template/582.html",
            ),
            7 => array(
                "viewer" => "viewReceipt_new",
                "template" => "template/print_a4.html",
            ),
            8 => array(
                "viewer" => "viewReceipt_new",
                "template" => "template/print_test.html",
            ),
        ),
        "headerNota" => array(
            "customer" => array(
                "customers_nam" => "nama",
                "alamat_1" => "alamat",
                "tlp_1" => "Tlp",
                "tlp_2" => "handphone",
                "fax" => "fax",
            ),
            "delivery address" => array(
                "dtime" => "tanggal",
                "customers_nama" => "Konsumen",
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
                    "label" => "Download Data Mentah SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label" => "Download Data Mentah APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label" => "Download Data Mentah PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label" => "Download Data Mentah PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
                2 => array(
                    "label" => "Download Data Mentah PACKING LIST FULL",
                    "target" => "ExcelWriter/row/",
                ),
            ),
            5 => array(
                1 => array(
                    "label" => "Download Data Mentah INVOICE",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
        ),
        "customHistoriExcel" => array(
            "nomer" => array(
                "label" => "INV",
                "type" => "string",
            ),
            "dtime" => array(
                "label" => "tanggal",
                "type" => "string",
            ),
            "nama" => array(
                "label" => "Model",
                "type" => "string",
            ),
            "produk_kode" => array(
                "label" => "type",
                "type" => "string",
            ),
            "pihakName" => array(
                "label" => "Customer",
                "type" => "string",
            ),
            "customerDetails__kabupaten" => array(
                "label" => "Kota",
                "type" => "string",
            ),
            "oleh_nama" => array(
                "label" => "Person",
                "type" => "string",
            ),
            "salesmanDetails__nama" => array(
                "label" => "salesman",
                "type" => "string",
            ),
            "jml" => array(
                "label" => "Qty",
                "type" => "integer",
            ),
            "sub_harga" => array(
                "label" => "Price",
                "type" => "integer",
            ),
            "sub_nett1" => array(
                "label" => "DPP",
                "type" => "integer",
            ),
            "trash_4" => array(
                "label" => "STATUS",
                "type" => "text",
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
                "nomer" => "Nomer",
                "dtime" => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                "paymentMethod_name" => "Pembayaran",
                "top_nama" => "TOP",
                "shippingDate_value" => "Tanggal Kirim",
                //                "shippingService_name" => "Biaya Kirim",
                //                "transaksi_jenis2_label" => "Paket",
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
                //                "transaksi_jenis2_label" => "Paket",
            ),
            3 => array(
                "nomer" => "No",
                "nomer_top" => "SO No.",

                "dtime" => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",
                //                "shippingService_name" => "shipping service",
                //                "tanggal_transaksi" => "tanggal_transaksi",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                //                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "nomer" => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top" => "SO No",
                "dtime" => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",
                "tos_nama" => "Term of Shipment",
                "keterangan" => "Remark",
                "description_additional" => "Note",
                //                "shippingService_name" => "shipping service",
                //                "transaksi_jenis2_label" => "Paket",
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
            1 => array(
                // "nomer"                    => "Nomer",
                // "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama" => "PIC",
                "customerDetails_tlp_1" => "Tlp",
                "customerDetails_tlp_2" => "Handphone",
                "customerDetails_email" => "Email",
                // "top_nama"                 => "TOP",
                // "paymentMethod_name"       => "Pembayaran",
                // "shippingDate_value"       => "Tanggal Kirim",
                // "shippingService_name"     => "Biaya Kirim",
                // "transaksi_jenis2_label"   => "Paket",
            ),
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
        "receiptElements" => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama" => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            "deliveryDetails" => array(
                "usedFields" => array(
                    "alias" => "<b>Attn</b>",
                    "alamat" => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Tlp",
                    // "tlp_2"     => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
            ),
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "headerField" => "heTransaksi_layout",
        "headerTables" => array(
            "produk_nama" => "nama produk",
            // "produk_kode" => "product no",
            "produk_ord_hrg" => "harga",
            "produk_ord_jml" => "jumlah",
            "sub_total" => "sub total",
        ),
        "receiptMainFields" => array(
            // "jenis_label" => "activity",
            "nomer" => "reference no.",
            "result_nomer" => "receipt no.",
            "customers_nama" => "customer",
            "dtime" => "date",
            "transaksi_jenis2" => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        // ----------------------------------------------------------------------
        /*1a*/
        "receiptDetailFields" => array(
            1 => array(
                //                "id" => "PID",
                // "barcode" => "barcode",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "produk_ord_jml" => "Qty",
                // "satuan" => "satuan",
            ),
            2 => array(
                //                "id" => "PID",
                //                 "barcode" => "sku",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Deskripsi",
                    "addKey" => "keterangan",
                ),
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                //                "id" => "PID",
                // "barcode" => "barcode",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                //                "produk_nama" => "Description",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                // "berat_new" => "W(KG)",
                // "volume_new" => "CBM",
                //                "max_jml" => "SO",
                //                "req_cancel_jml" => "cancel request",
                //                "cancel_jml" => "dicancel",
                //                "packed_jml" => "dipacking",
                //                "sent_jml" => "dikirim",
                "produk_ord_jml" => "Qty",
                // "sub_berat_new" => "Sub Berat",
                //                "sub_berat_gross"  => "Sub Berat",
                //                "satuan" => "uom",
                // "sub_volume_new" => "Sub Volume",
                //                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                //                "id" => "PID",
                // "barcode" => "sku",
                // "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Description",
                    "addKey" => "keterangan",
                ),
                "jml" => "Qty",
                // "berat_new" => "Net/Pkg (Kgs)",
                // "sub_berat_new" => "Total (Kgs)",
                // "volume_new" => "Net/Pkg (Cbm)",
                // "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                //                "id" => "PID",
                //                 "barcode" => "sku",
                "produk_kode" => "sku",
                // "no_part" => "part number",
                "produk_nama" => array(
                    "label" => "Deskripsi",
                    "addKey" => "keterangan",
                ),
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
        ),
        /*1b*/
        "receiptNumFields" => array(
            1 => array(
                "nett1nppn" => array(
                    "label" => "harga",
                    "attr" => "",
                ),
                "nett1" => array(
                    "label" => "dpp",
                    "attr" => "class='lysimpel'",
                ),
                "xppn" => array(
                    "label" => "ppn",
                    "attr" => "class='lysimpel text-right'",
                ),
            ),
            2 => array(
                "nett1nppn" => array(
                    "label" => "harga",
                    "attr" => "",
                ),
                "nett1" => array(
                    "label" => "dpp",
                    "attr" => "class='lysimpel'",
                ),
                "xppn" => array(
                    "label" => "ppn",
                    "attr" => "class='lysimpel text-right'",
                ),
            ),
            3 => array(
                // "stok"        => "Stok available",
                // "stok_center" => "Stok dc",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                //                "harga" => "price",
                "nett1" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        /*1c*/
        "receiptSumDetailFields" => array(
            1 => array(
                // "sub_harga_dropshiper" => "subtotal",
                "sub_harga" => "subtotal",
            ),
            2 => array(
                // "label" => "Total Price",
                "sub_nett1" => array(
                    "label" => "jumlah",
                    "attr" => "",
                ),
            ),
            3 => array(// "sub_harga" => "Total Price",
            ),
        ),
        /*2a*/
        "receiptSumFields" => array(
            1 => array(
                "lastNett" => "total bruto",
                "diskon_kategori_unit" => "diskon",
                "nett1" => "total dpp",
                //                "disc" => "disc",
                // "ongkir_ui" => "Biaya kirim",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                // "nilai_pembulatan" => "pembulatan",
                // "nett1_bulat" => "sub total",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "total PPN",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "grand Total",
                // "point_transaksi"      => "point transaksi",
                // "point_saldo_akhir"    => "point total",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                // "ongkir_ui" => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                // "nilai_pembulatan" => "pembulatan",
                "diskon_kategori_unit" => "diskon",
                "nett1" => "total dpp",
                "ppn_out_bulat" => "total PPN",
                // "nett1_bulat" => "Total",
                //                "grand_ppn" => "VAT",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
            3 => array(

                //                "berat_new" => "Berat",
                //                "volume_new" => "Volume",
                //                "harga" => "amount",
                //                "ppn" => "VAT",
                //                "nett" => "total",
            ),
            4 => array(
                "sum_jml" => "total",
            ),
            5 => array(
                "pym_src_total_dipakai" => "Uang Muda (DP)",
                "nett1" => "total dpp",
                //                "disc" => "disc",
                // "ongkir_ui" => "Biaya kirim",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                // "nilai_pembulatan" => "pembulatan",
                // "nett1_bulat" => "sub total",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat" => "total PPN",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Total",
            ),

        ),
        "subAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*nett1",
            //            5 => "jml*(harga-disc)",
        ),
        // ----------------------------------------------------------------------
        "terbilangSumFields" => array("grand_pembulatan" => "terbilang"),
        "reportSumFields" => array(
            "customers_id" => "customers_nama",

        ),
        "printMod" => array(
//            1 => "&mod=1",
//            2 => "&mod=1",
        ),
        "allowPrint" => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
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
        "fixedSignatures" => array(
            1 => array(
                "sign_1" => true,
                "sign_2" => false,
                "sign_4" => false,
                "sign_5" => false,
                "salesman" => array(
                    "label" => ".salesman",
                    "contents" => "salesmanDetails__nama",
                    "stateCaption" => "",
                ),
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            2 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => false,
                "sign_5" => false,
                "salesman" => array(
                    "label" => ".salesman",
                    "contents" => "salesmanDetails__nama",
                    "stateCaption" => "",
                ),
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
            ),
            3 => array(
                "sign_1" => true,
                "sign_2" => true,
                // "sign_3" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "sign_5" => array(
                    "label" => ".Driver",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
            ),
            4 => array(
                "sign_1" => true,
                "sign_2" => false,
                "sign_4" => true,
                // "sign_4" => array(
                //     "label" => ".Ka. Logistik",
                //     "contents" => "worker_nama",
                //     // "stateCaption" => "",
                // ),
                "salesman" => false,
                "customer" => array(
                    "label" => ".ttd & cap penerima",
                    // "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim ttt",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),

                "driver" => array(
                    "label" => ".driver",
                    "contents" => "",
                    "stateCaption" => "",
                ),
                "worker" => array(
                    "label" => ".teknisi",
                    "contents" => "",
                    "stateCaption" => "",
                ),
                "logistik" => array(
                    "label" => "ka.&nbsp;logistic",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
            5 => array(
                "sign_1" => true,
                "sign_2" => true,
                "sign_4" => true,
                "salesman" => false,
                "customer" => array(
                    "label" => ".Konsumen",
                    "contents" => "customerDetails__nama",
                    "stateCaption" => "",
                ),
                "pengirim" => array(
                    "label" => ".Pengirim",
                    "contents" => "pengirim_nama",
                    "stateCaption" => "",
                ),
                "penerima" => array(
                    "label" => ".Penerima",
                    "contents" => "",
                    "stateCaption" => "",
                ),
            ),
        ),
        // --------------------------------------------------------------------
        "reviewDetailCompactListsLabel" => array(
            "produk_kode" => "part no",
            "nama" => "product name",
            "harga" => "unit price",
            //            "harganppn" => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc" => "unit disc",
            "qty" => "qty",
            "sub_harga" => "sub bruto",
            "sub_disc" => "sub diskon",
            "sub_nett1" => "sub netto",
        ),
        "reviewMainCompactListsLabel" => array(
            "nomer" => "Nomer",
            "customerDetails__nama" => "nama",
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
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    "oleh_nama" => "approval",
                    "customers_nama" => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items" => "detail items*",
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
                "sign_0",
                "sign_1",
            ),
            2 => array(
                "sign_0",
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
                    "outstanding_items" => "detail items*",
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
        "showCabangInvoice" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
        "showGudangStatus" => array(
            1 => true,
            2 => true,
            //            3 => true,
            //            4 => true,
            //            5 => false,
            //
        ),
        "receiptHadiahFields" => array(
            "nama" => "produk",
            "produk_kode" => "sku",
            "qty" => "qty",
        ),
        "receiptItemHadiahKey" => array(
            "key" => "type_produk",
            "value" => "hadiah"
        ),
        //----
//        "receiptPolos" => array(
//            1 => array(
//                "enabled" => true,
//                "template" => "template/582spo_mod_polos.html",
//            ),
//        ),
    ),
    //pengeliuaran cashback berupa barang
    "66771" => array(
        "receiptTemplate" => array(
            1 => "template/6677r.html",
            2 => "template/6677.html",
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
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
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
                "produk_nama" => "nama",
//                "nama" => "Nama Produk",
                "produk_kode" => "Kode.Part",
                "satuan" => "Satuan",
                "jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "nama",
//                "nama" => "Nama Produk",
                "produk_kode" => "Kode.Part",
                "satuan" => "Satuan",
                "jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
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
        "itemLabels2" => array(
            "nama" => "Detil cash back",
            "produk_kode" => "SKU",
            "qty" => "qty",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
        "fixedElementsSwitch" => array(
            "gate" => "pajakOption",
            "key" => array(
                "pph21" => array(
                    "customerDetails" => false,
                    "freelancerDetails" => true,
                ),
                "pph23" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
                "pph23_15" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
            ),
        ),
    ),

    "66773" => array(
        "receiptTemplate" => array(
            1 => "template/6677r.html",
            2 => "template/6677.html",
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
                //                ),
            ),
            2 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
            ),
            3 => array(
                //                "vendor" => array(
                //                    "label" => ".Confirmed & Acknowledged by",
                //                    "contents" => "vendorDetails_nama",
                //                    //                "caption_department" => "",
                //                ),
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
                "produk_nama" => "nama",
//                "nama" => "Nama Produk",
                "produk_kode" => "Kode.Part",
                "satuan" => "Satuan",
                "jml" => "qty",
            ),
            2 => array(
                "produk_nama" => "nama",
//                "nama" => "Nama Produk",
                "produk_kode" => "Kode.Part",
                "satuan" => "Satuan",
                "jml" => "qty",
            ),
        ),
        "receiptSumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
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
        "itemLabels2" => array(
            "nama" => "Detil cash back",
            "produk_kode" => "SKU",
            "qty" => "qty",
        ),
        "reportSumFields" => array(
            "suppliers_id" => "suppliers_nama",
        ),
        "staticFooter" => array(//            2 => "SAN/F/PUR002/R00",
        ),
        "printLocation" => "Printing/viewReceiptReg/",
        "allowPrint" => array(
            1 => array(
                "size" => "normal",
            ),

        ),
        "fixedElementsSwitch" => array(
            "gate" => "pajakOption",
            "key" => array(
                "pph21" => array(
                    "customerDetails" => false,
                    "freelancerDetails" => true,
                ),
                "pph23" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
                "pph23_15" => array(
                    "customerDetails" => true,
                    "freelancerDetails" => false,
                ),
            ),
        ),
    ),


);