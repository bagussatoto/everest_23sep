<?php

$config["coTransaksiLayout"] = array(

    "580"     => array(
        "receiptTemplate"        => array(
            1 => "template/582spo.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/582.html",
        ),
        "headerNota"             => array(
            "customer"         => array(
                "customers_nam" => "nama",
                "alamat_1"      => "alamat",
                "tlp_1"         => "Tlp",
                "tlp_2"         => "handphone",
                "fax"           => "fax",
            ),
            "delivery address" => array(
                "dtime"             => "tanggal",
                "customers_nama"    => "Konsumen",
                "tlp_1"             => "phone",
                "alamat_1"          => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran"        => "payment method",
                "alias"             => "attn",

            ),
            "purchase order"   => array(
                "nomer"         => "receipt no.",
                "currency"      => "currency",
                "delivery_date" => "delivery date",
                "top"           => "term of payment",
                "tos"           => "term of shipment",
                "capacity"      => "address",
            ),
        ),
        "customButton"           => array(
            1 => array(
                1 => array(
                    "label"  => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label"  => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label"  => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label"  => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label"  => "Export INVOICE",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
        ),
        "elementFixedNumberSO"   => array(
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
        "fixedElements"          => array(
            1 => array(
                "nomer"                    => "Nomer",
                "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama"     => "PIC",
                "customerDetails_tlp_1"    => "Tlp",
                "customerDetails_tlp_2"    => "Handphone",
                "customerDetails_email"    => "Email",
                "top_nama"                 => "TOP",
                "paymentMethod_name"       => "Pembayaran",
                "shippingDate_value"       => "Tanggal Kirim",
                "shippingService_name"     => "Biaya Kirim",
                "transaksi_jenis2_label"   => "Paket",
            ),
            2 => array(
                "nomer"                    => "No",
                "nomer_top"                => "SO No.",
                "dtime"                    => "Date",
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama"     => "PIC name",
                "customerDetails_tlp_1"    => "Phone",
                "customerDetails_tlp_2"    => "Handphone",
                "customerDetails_email"    => "Email",
                //                "customerDetails_npwp" => "Tax ID/NPWP",
                "paymentMethod_name"       => "Payment Method",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "top_nama"                 => "Term of Payment",
                //                "dueDate_value" => "Due Date",
                "shippingDate_value"       => "Delivery Date",
                "shippingService_name"     => "shipping service",
                "transaksi_jenis2_label"   => "Paket",
            ),
            3 => array(
                "nomer"                => "No",
                "nomer_top"            => "SO No.",
                "shippingDate_value"   => "Delivery Date",
                "shippingService_name" => "shipping service",

                "tos_nama"               => "Term of Shipment",
                "keterangan"             => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "nomer"       => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top"   => "SO No",
                "dtime"       => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",

                "tos_nama"               => "Term of Shipment",
                "keterangan"             => "Remark",
                "description_additional" => "Note",

                //                "shippingService_name" => "shipping service",
                "transaksi_jenis2_label" => "Paket",
            ),
            5 => array(
                "nomer"                  => "INV No",
                "nomers_prev"            => "PL No",
                "nomer_top"              => "SO No",
                "dtime"                  => "Date",
                "paymentMethod_name"     => "Payment Method",
                "dueDate_value"          => "Due Date",
                "shippingService_name"   => "shipping service",
                //                "shippingService_name" => "shipping service",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
                "transaksi_jenis2_label" => "Paket",
            ),
        ),
        "hideFixedElements"      => array(
            1 => array(
                // "nomer"                    => "Nomer",
                // "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama"     => "PIC",
                "customerDetails_tlp_1"    => "Tlp",
                "customerDetails_tlp_2"    => "Handphone",
                "customerDetails_email"    => "Email",
                // "top_nama"                 => "TOP",
                // "paymentMethod_name"       => "Pembayaran",
                // "shippingDate_value"       => "Tanggal Kirim",
                // "shippingService_name"     => "Biaya Kirim",
                // "transaksi_jenis2_label"   => "Paket",
            ),
            5 => array(
                array(
                    "key"       => "paymentMethod_name",
                    "keyResult" => array("cash", "cash in advance"),
                    "label"     => array(
                        "dueDate_value" => "Due Date",
                    ),
                ),
            ),
        ),
        "receiptElements"        => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama"   => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1"  => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp"   => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            "deliveryDetails" => array(
                "usedFields" => array(
                    "alias"     => "Attn",
                    "alamat"    => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi"  => "propinsi",
                    "tlp"       => "Tlp",
                    // "tlp_2"     => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
            ),
        ),
        "fixedSignatures"        => array(
            1 => array(
                "customer" => array(
                    "label"        => ".Konsumen",
                    "contents"     => "customerDetails_nama",
                    //                "caption_department" => "",
                    "stateCaption" => "Dipersiapkan Oleh**",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label"    => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label"    => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerField"            => "heTransaksi_layout",
        "headerTables"           => array(
            "produk_nama"    => "nama produk",
            // "produk_kode" => "product no",
            "produk_ord_hrg" => "harga",
            "produk_ord_jml" => "jumlah",
            "sub_total"      => "sub total",
        ),
        "receiptMainFields"      => array(
            // "jenis_label" => "activity",
            "nomer"                  => "reference no.",
            "result_nomer"           => "receipt no.",
            "customers_nama"         => "customer",
            "dtime"                  => "date",
            "transaksi_jenis2"       => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue"         => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*nett1",
            //            5 => "jml*(harga-disc)",
        ),
        /*1a*/
        "receiptNumFields"       => array(
            1 => array(
                "harga_dropshiper" => "harga**",
                //                "disc" => "disc",
                "disc_percent"     => "disc (%)",
                "disc"             => "disc (IDR)",
                "ppn"              => "VAT",
            ),
            2 => array(
                "stok_center"   => "stok dc",
                "stok"          => "stok available",
                "harga"         => "price",
                "disc_percent"  => "disc (%)",
                "disc"          => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi"         => "premi",
                "nett1"         => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok"        => "stok available",
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
        /*1b*/
        "receipNumFields"        => array(
            1 => array(
                // "nett1_dropshiper" => "harga",
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
                "stok"        => "Stok available",
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
        /*1c*/
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

        "receiptDetailFields"           => array(
            1 => array(
                "id"             => "PID",
                "produk_kode"    => "sku",
                // "no_part" => "part number",
                "produk_nama"    => "nama produk",
                "produk_ord_jml" => "Qty",
                "satuan"         => "satuan",
            ),
            2 => array(
                "id"             => "PID",
                "produk_kode"    => "Product code",
                "no_part"        => "part number",
                "produk_nama"    => "Description",
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id"             => "PID",
                "produk_kode"    => "Product code",
                "no_part"        => "part number",
                "produk_nama"    => "Description",
                "berat_new"      => "W(KG)",
                "volume_new"     => "CBM",
                "max_jml"        => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml"     => "dicancel",
                "packed_jml"     => "dipacking",
                "sent_jml"       => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new"  => "Sub Berat",
                //                "sub_berat_gross"  => "Sub Berat",
                //                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
                //                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id"             => "PID",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode"    => "Product code",
                "no_part"        => "part number",
                "produk_nama"    => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml"            => "Quantity Per Pkg (Ctns)",
                "berat_new"      => "Net/Pkg (Kgs)",
                "sub_berat_new"  => "Total (Kgs)",
                "volume_new"     => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_kode"    => "Product code",
                "no_part"        => "part number",
                "produk_nama"    => "Description",
                "produk_ord_jml" => "Qty",
                "satuan"         => "UOM",
            ),
        ),
        /*2a*/
        "receiptSumFields"              => array(
            1 => array(
                "nett1"                 => "jumlah",
                //                "disc" => "disc",
                "ongkir_ui"             => "Biaya kirim",
                "diskon_tambahan_nilai" => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan"      => "pembulatan",
                "nett1_bulat"           => "sub total",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat"         => "PPN",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan"      => "Total",
                "point_transaksi"       => "point",
                "point_saldo_akhir"     => "total point",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui"        => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat"      => "Total Amount",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat"    => "VAT",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
            3 => array(

                "berat_new"  => "Berat",
                "volume_new" => "Volume",
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
                "ongkir"           => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                "new_net1"         => "Amount",
                //                "new_net2" => "grand total",
                "dp_value"         => "Downpayment",
                "dp_ppn_value"     => "Dp Vat",
                "total_ui"         => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "total_ui"         => "total Amount",
                "new_grand_ppn"    => "VAT ",
                "tagihan"          => "Grand Total",
            ),

        ),
        "terbilangSumFields"                     => array("grand_pembulatan" => "terbilang"),
        "reportSumFields"               => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation"                 => "Printing/viewReceiptReg/",
        "allowPrint"                    => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter"                  => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            4 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes"                   => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword"                 => array(
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
            "produk_kode"  => "part no",
            "nama"         => "product name",
            "harga"        => "unit price",
            "harganppn"    => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc"         => "unit disc",
            "qty"          => "qty",
            "sub_harga"    => "sub bruto",
            "sub_disc"     => "sub diskon",
            "sub_nett1"    => "sub netto",
        ),
        "reviewMainCompactListsLabel"   => array(
            "nomer"                     => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1"    => "phone",
            "customerDetails__tlp_2"    => "handphone",
            "customerDetails__npwp"     => "npwp",
            "billingDetails__nik"       => "nik",
            "valas_nama"                => "currency",
        ),
        "reviewCompactListDetailSum"    => array(
            "qty"   => "qty",
            "jual"  => "jual",
            "disc"  => "disc",
            "nett1" => "grand total",
        ),
        "fixedFieldHoldConsolidate"     => array(
            "transaksi" => array(
                "label"     => "transaksi",
                "target"    => "transaksi",
                "srcKey"    => "id_master",
                "addFields" => "sales",
                "fields"    => array(
                    "cabang_nama"           => "cabang",
                    "nomer_top"             => "nomer",
                    "dtime"                 => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama"           => "sallesman",
                    "oleh_nama"             => "approval",
                    "customers_nama"        => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items"     => "detail items*",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop"      => array(),
                "items"     => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk"    => array(

                "label"  => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama"    => "cabang",
                    "produk_nama"    => "product",
                    "produk_kode"    => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top"      => "Transaksi",
                    "ord_qty"        => "Order",
                    "ord_sent_qty"   => "Dikirim",
                    "ord_valid_qty"  => "Outstanding",
                    "stok"           => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop"   => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top"      => "nomer_top",
                    "ord_qty"        => "produk_ord_jml",
                    "ord_valid_qty"  => "valid_qty",
                    "ord_sent_qty"   => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer"  => array(
                "cabang_nama" => "cabang",
                "label"       => "customer",
                "target"      => "customer",
                "srcKey"      => "customers_id",
                "fields"      => array(
                    "customers_nama" => "Customer",
                    "nomer_top"      => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode"    => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty"   => "dikirim",
                    "ord_valid_qty"  => "<span class='text-red'>Outstanding</span>",
                ),
                "loop"        => array(
                    "nomer_top"      => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode"    => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty"   => "produk_ord_jml-valid_qty",
                    "ord_valid_qty"  => "valid_qty",
                ),
                "array_flip"  => array(
                    1,
                ),
            ),

        ),
        "reviewCompactListSum"          => array(
            "shipping_service" => "shipping service",
            "grand_total_ui"   => "total amount",
            "grand_ppn"        => "VAT 11%",
            "new_net3"         => "grand total",
        ),
        "reviewAddRows"                 => array(
            "top__nama"     => "pembayaran",
            "dp"            => "downpayment",
            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign"                    => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedFieldHold"                => array(
            "transaksi" => array(
                "label"  => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top"             => "nomer",
                    "dtime"                 => "approved",
                    "seller_nama"           => array(
                        "step"  => 1,
                        "key"   => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama"             => "approval",
                    "customers_nama"        => "customer",
                    //                    "transaksi_nilai" => "nilai",
                    "outstanding_items"     => "detail items*",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop"   => array(),
                "items"  => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk"    => array(
                "label"  => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama"    => "product",
                    "produk_kode"    => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top"      => "Transaksi",
                    "ord_qty"        => "Order",
                    "ord_sent_qty"   => "Dikirim",
                    "ord_valid_qty"  => "Outstanding",
                    "stok"           => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop"   => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top"      => "nomer_top",
                    "ord_qty"        => "produk_ord_jml",
                    "ord_valid_qty"  => "valid_qty",
                    "ord_sent_qty"   => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer"  => array(
                "label"      => "customer",
                "target"     => "customer",
                "srcKey"     => "customers_id",
                "fields"     => array(
                    "customers_nama" => "Customer",
                    "nomer_top"      => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode"    => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty"   => "dikirim",
                    "ord_valid_qty"  => "<span class='text-red'>Outstanding</span>",
                ),
                "loop"       => array(
                    "nomer_top"      => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode"    => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty"   => "produk_ord_jml-valid_qty",
                    "ord_valid_qty"  => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "print_nvalas"                  => false,
        "print_lable"                   => array(
            "steps" => array(
                1 => array(
                    "label"    => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
        "print_hitung"                  => array(
            5 => false,
        ),
        "print_hitung_itemRecap"        => array(
            5 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer"     => array(
            5 => array(
                "ongkir"           => "ongkir",
                "new_net1"         => "nett1+ongkir",
                //                "dp_value" => "dp_value",
                //                "dp_ppn_value" => "dp_ppn_value",
                //                "total_ui" => "total_ui",
                "nett1_bulat"      => "new_net1",
                "ppn_out_bulat"    => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net"          => "ppn",
                //                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan"          => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields"   => array(
            5 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown"        => array(
            5 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),

        "receiptElementInjector" => array(
            "source" => array(
                "element"    => "customerDetails",
                "fields"     => array(
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
        "showCabangInvoice"      => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
    ),
    "580_mod" => array(
        "receiptTemplate"        => array(
            1 => "template/582spo_mod.html",
            2 => "template/582so.html",
            3 => "template/582pkd.html",
            4 => "template/582spd.html",
            5 => "template/582.html",
        ),
        "headerNota"             => array(
            "customer"         => array(
                "customers_nam" => "nama",
                "alamat_1"      => "alamat",
                "tlp_1"         => "Tlp",
                "tlp_2"         => "handphone",
                "fax"           => "fax",
            ),
            "delivery address" => array(
                "dtime"             => "tanggal",
                "customers_nama"    => "Konsumen",
                "tlp_1"             => "phone",
                "alamat_1"          => "address",
                "dtime_jatuh_tempo" => "jatuh tempo",
                "pembayaran"        => "payment method",
                "alias"             => "attn",

            ),
            "purchase order"   => array(
                "nomer"         => "receipt no.",
                "currency"      => "currency",
                "delivery_date" => "delivery date",
                "top"           => "term of payment",
                "tos"           => "term of shipment",
                "capacity"      => "address",
            ),
        ),
        "customButton"           => array(
            1 => array(
                1 => array(
                    "label"  => "Export SO",
                    "target" => "ExcelWriter/exp/",
                ),
                // 2 => array(
                //     "label" => "Export SO Browwwww",
                //     "target" => "ExcelWriter/exp/",
                // ),
            ),
            2 => array(
                1 => array(
                    "label"  => "Export APP SO",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            3 => array(
                1 => array(
                    "label"  => "Export PRE PACKING",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            4 => array(
                1 => array(
                    "label"  => "Export PACKING LIST",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
            5 => array(
                1 => array(
                    "label"  => "Export INVOICE",
                    "target" => "ExcelWriter/exp/",
                ),
            ),
        ),
        "elementFixedNumberSO"   => array(
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
        "fixedElements"          => array(
            1 => array(
                "nomer"                    => "Nomer",
                "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama"     => "PIC",
                "customerDetails_tlp_1"    => "Tlp",
                "customerDetails_tlp_2"    => "Handphone",
                "customerDetails_email"    => "Email",
                "top_nama"                 => "TOP",
                "paymentMethod_name"       => "Pembayaran",
                "shippingDate_value"       => "Tanggal Kirim",
                "shippingService_name"     => "Biaya Kirim",
                "transaksi_jenis2_label"   => "Paket",
            ),
            2 => array(
                "nomer"                    => "No",
                "nomer_top"                => "SO No.",
                "dtime"                    => "Date",
                "customerDetails_alamat_1" => "Billing address",
                "customerDetails_nama"     => "PIC name",
                "customerDetails_tlp_1"    => "Phone",
                "customerDetails_tlp_2"    => "Handphone",
                "customerDetails_email"    => "Email",
                //                "customerDetails_npwp" => "Tax ID/NPWP",
                "paymentMethod_name"       => "Payment Method",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "top_nama"                 => "Term of Payment",
                //                "dueDate_value" => "Due Date",
                "shippingDate_value"       => "Delivery Date",
                "shippingService_name"     => "shipping service",
                "transaksi_jenis2_label"   => "Paket",
            ),
            3 => array(
                "nomer"                => "No",
                "nomer_top"            => "SO No.",
                "shippingDate_value"   => "Delivery Date",
                "shippingService_name" => "shipping service",

                "tos_nama"               => "Term of Shipment",
                "keterangan"             => "Remark",
                //                "top_nama" => "Term of Payment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
                //                "dtime" => "Date",
                "transaksi_jenis2_label" => "Paket",
            ),
            4 => array(
                "nomer"       => "No",
                "nomers_prev" => "PRE-PL No",
                "nomer_top"   => "SO No",
                "dtime"       => "Packing list date",
                //                "shippingDate_value" => "Delivery Date",

                "tos_nama"               => "Term of Shipment",
                "keterangan"             => "Remark",
                "description_additional" => "Note",

                //                "shippingService_name" => "shipping service",
                "transaksi_jenis2_label" => "Paket",
            ),
            5 => array(
                "nomer"                  => "INV No",
                "nomers_prev"            => "PL No",
                "nomer_top"              => "SO No",
                "dtime"                  => "Date",
                "paymentMethod_name"     => "Payment Method",
                "dueDate_value"          => "Due Date",
                "shippingService_name"   => "shipping service",
                //                "shippingService_name" => "shipping service",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "shippingDate_value" => "Delivery Date",
                "transaksi_jenis2_label" => "Paket",
            ),
        ),
        "hideFixedElements"      => array(
            1 => array(
                // "nomer"                    => "Nomer",
                // "dtime"                    => "tanggal",
                "customerDetails_alamat_1" => "Alamat Tagihan",
                "customerDetails_nama"     => "PIC",
                "customerDetails_tlp_1"    => "Tlp",
                "customerDetails_tlp_2"    => "Handphone",
                "customerDetails_email"    => "Email",
                // "top_nama"                 => "TOP",
                // "paymentMethod_name"       => "Pembayaran",
                // "shippingDate_value"       => "Tanggal Kirim",
                // "shippingService_name"     => "Biaya Kirim",
                // "transaksi_jenis2_label"   => "Paket",
            ),
            5 => array(
                array(
                    "key"       => "paymentMethod_name",
                    "keyResult" => array("cash", "cash in advance"),
                    "label"     => array(
                        "dueDate_value" => "Due Date",
                    ),
                ),
            ),
        ),
        "receiptElements"        => array(
            "customerDetails" => array(
                "usedFields" => array(
                    "nama"   => "nama",
                    // "alamat_1"  => "alamat",
                    // "kelurahan" => "Kel",
                    // "kecamatan" => "Kec",
                    // "kabupaten" => "Kab",
                    // "propinsi"  => "Prop",
                    // "tlp"       => "Tlp",
                    "tlp_1"  => "Tlp",
                    // "tlp_2"     => "Handphone",
                    "npwp"   => "NPWP",
                    "no_ktp" => "nik",
                    // "nik"       => "NIK",
                ),
            ),
            "deliveryDetails" => array(
                "usedFields" => array(
                    "alias"     => "Attn",
                    "alamat"    => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi"  => "propinsi",
                    "tlp"       => "Tlp",
                    // "tlp_2"     => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
            ),
        ),
        "fixedSignatures"        => array(
            1 => array(
                "customer" => array(
                    "label"        => ".Konsumen",
                    "contents"     => "customerDetails_nama",
                    //                "caption_department" => "",
                    "stateCaption" => "Dipersiapkan Oleh**",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label"    => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            4 => array(
                "customer" => array(
                    "label"    => "Receipt",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerField"            => "heTransaksi_layout",
        "headerTables"           => array(
            "produk_nama"    => "nama produk",
            // "produk_kode" => "product no",
            "produk_ord_hrg" => "harga",
            "produk_ord_jml" => "jumlah",
            "sub_total"      => "sub total",
        ),
        "receiptMainFields"      => array(
            // "jenis_label" => "activity",
            "nomer"                  => "reference no.",
            "result_nomer"           => "receipt no.",
            "customers_nama"         => "customer",
            "dtime"                  => "date",
            "transaksi_jenis2"       => "type of sales",
            "transaksi_jenis2_label" => "type of product",
        ),
        "subAmountValue"         => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*nett1",
            //            5 => "jml*(harga-disc)",
        ),
        /*1a*/
        "receiptNumFields"       => array(
            1 => array(
                "harga_dropshiper" => "harga**",
                //                "disc" => "disc",
                "disc_percent"     => "disc (%)",
                "disc"             => "disc (IDR)",
                "ppn"              => "VAT",
            ),
            2 => array(
                "stok_center"   => "stok dc",
                "stok"          => "stok available",
                "harga"         => "price",
                "disc_percent"  => "disc (%)",
                "disc"          => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi"         => "premi",
                "nett1"         => "price(net)",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok"        => "stok available",
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
        /*1b*/
        "receipNumFields"        => array(
            1 => array(
                "nett1_dropshiper" => "harga",
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
                "stok"        => "Stok available",
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
        /*1c*/
        "receiptSumDetailFields" => array(
            1 => array(
                "sub_harga_dropshiper" => "subtotal",
            ),
            2 => array(
                "sub_harga" => "Total Price",
            ),
            3 => array(
                "sub_harga" => "Total Price",
            ),
        ),

        "receiptDetailFields"           => array(
            1 => array(
                "id"             => "PID",
                "produk_kode"    => "sku",
                // "no_part" => "part number",
                "produk_nama"    => "nama produk",
                "produk_ord_jml" => "Qty",
                "satuan"         => "satuan",
            ),
            2 => array(
                "id"             => "PID",
                "produk_kode"    => "Product code",
                "no_part"        => "part number",
                "produk_nama"    => "Description",
                //                "stok_center" => "Stok dc",
                //                "stok" => "Stok<br>available",
                "produk_ord_jml" => "Qty",
                //                "satuan" => "uom",
            ),
            3 => array(
                "id"             => "PID",
                "produk_kode"    => "Product code",
                "no_part"        => "part number",
                "produk_nama"    => "Description",
                "berat_new"      => "W(KG)",
                "volume_new"     => "CBM",
                "max_jml"        => "SO",
                "req_cancel_jml" => "cancel request",
                "cancel_jml"     => "dicancel",
                "packed_jml"     => "dipacking",
                "sent_jml"       => "dikirim",
                "produk_ord_jml" => "Qty",
                "sub_berat_new"  => "Sub Berat",
                //                "sub_berat_gross"  => "Sub Berat",
                //                "satuan" => "uom",
                "sub_volume_new" => "Sub Volume",
                //                "sub_volume_gross" => "Sub Volume",
            ),
            4 => array(
                "id"             => "PID",
                "produk_ord_jml" => "Qty (Pcs)",
                "produk_kode"    => "Product code",
                "no_part"        => "part number",
                "produk_nama"    => "Description",
                //                "produk_kode"       => "part number",
                //                "satuan"            => "uom",
                "jml"            => "Quantity Per Pkg (Ctns)",
                "berat_new"      => "Net/Pkg (Kgs)",
                "sub_berat_new"  => "Total (Kgs)",
                "volume_new"     => "Net/Pkg (Cbm)",
                "sub_volume_new" => "Total (Cbm)",
            ),
            5 => array(
                "produk_kode"    => "Product code",
                "no_part"        => "part number",
                "produk_nama"    => "Description",
                "produk_ord_jml" => "Qty",
                "satuan"         => "UOM",
            ),
        ),
        /*2a*/
        "receiptSumFields"              => array(
            1 => array(
                "nett1_dropshiper"            => "jumlah",
                //                "disc" => "disc",
                "ongkir_ui"                   => "Biaya kirim",
                "diskon_tambahan_nilai"       => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan_dropshiper" => "pembulatan",
                "nett1_bulat_dropshiper"      => "sub total",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat_dropshiper"    => "PPN",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan_dropshiper" => "Total",
                "point_transaksi"             => "point",
                "point_saldo_akhir"           => "total point",
            ),
            2 => array(
                //                "nett1" => "amount",
                //                "disc" => "disc",
                "ongkir_ui"        => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                //                "grand_total" => "total amount",
                //                "grand_total_ui" => "Total Amount",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat"      => "Total Amount",
                //                "grand_ppn" => "VAT",
                "ppn_out_bulat"    => "VAT",
                //                "dp" => "DOWNPAYMENT",
                //                "new_net3" => "Grand Total",
                "grand_pembulatan" => "Grand Total",
            ),
            3 => array(

                "berat_new"  => "Berat",
                "volume_new" => "Volume",
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
                "ongkir"           => "Shipping Service",
                //                "total_diskon"     => "diskon",
                //                "add_diskon" => "diskon tambahan",
                "new_net1"         => "Amount",
                //                "new_net2" => "grand total",
                "dp_value"         => "Downpayment",
                "dp_ppn_value"     => "Dp Vat",
                "total_ui"         => "Sub Amount",
                "nilai_pembulatan" => "pembulatan",
                "total_ui"         => "total Amount",
                "new_grand_ppn"    => "VAT ",
                "tagihan"          => "Grand Total",
            ),

        ),
        "terbilangSumFields"                     => array("grand_pembulatan_dropshiper" => "terbilang"),
        "reportSumFields"               => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation"                 => "Printing/viewReceiptReg/",
        "allowPrint"                    => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
            4 => array("size" => "normal"),
            5 => array("size" => "normal"),
        ),
        "staticFooter"                  => array(
            2 => "SAN/F/SA001/R00",
            3 => "SAN/F/LOG001/R00",
            4 => "SAN/F/LOG001/R00",
            5 => "SAN/F/FA005/R00",
        ),
        "staticNotes"                   => array(
            3 => "",
            5 => "true",
        ),
        "receiptInword"                 => array(
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
            "produk_kode"  => "part no",
            "nama"         => "product name",
            "harga"        => "unit price",
            "harganppn"    => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc"         => "unit disc",
            "qty"          => "qty",
            "sub_harga"    => "sub bruto",
            "sub_disc"     => "sub diskon",
            "sub_nett1"    => "sub netto",
        ),
        "reviewMainCompactListsLabel"   => array(
            "nomer"                     => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1"    => "phone",
            "customerDetails__tlp_2"    => "handphone",
            "customerDetails__npwp"     => "npwp",
            "billingDetails__nik"       => "nik",
            "valas_nama"                => "currency",
        ),
        "reviewCompactListDetailSum"    => array(
            "qty"   => "qty",
            "jual"  => "jual",
            "disc"  => "disc",
            "nett1" => "grand total",
        ),
        "fixedFieldHoldConsolidate"     => array(
            "transaksi" => array(
                "label"     => "transaksi",
                "target"    => "transaksi",
                "srcKey"    => "id_master",
                "addFields" => "sales",
                "fields"    => array(
                    "cabang_nama"           => "cabang",
                    "nomer_top"             => "nomer",
                    "dtime"                 => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama"           => "sallesman",
                    "oleh_nama"             => "approval",
                    "customers_nama"        => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items"     => "detail items*",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop"      => array(),
                "items"     => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk"    => array(

                "label"  => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "cabang_nama"    => "cabang",
                    "produk_nama"    => "product",
                    "produk_kode"    => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top"      => "Transaksi",
                    "ord_qty"        => "Order",
                    "ord_sent_qty"   => "Dikirim",
                    "ord_valid_qty"  => "Outstanding",
                    "stok"           => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop"   => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top"      => "nomer_top",
                    "ord_qty"        => "produk_ord_jml",
                    "ord_valid_qty"  => "valid_qty",
                    "ord_sent_qty"   => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer"  => array(
                "cabang_nama" => "cabang",
                "label"       => "customer",
                "target"      => "customer",
                "srcKey"      => "customers_id",
                "fields"      => array(
                    "customers_nama" => "Customer",
                    "nomer_top"      => "Transaksi SO",
                    // "transaksi_nilai" => "nilai",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode"    => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty"   => "dikirim",
                    "ord_valid_qty"  => "<span class='text-red'>Outstanding</span>",
                ),
                "loop"        => array(
                    "nomer_top"      => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode"    => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty"   => "produk_ord_jml-valid_qty",
                    "ord_valid_qty"  => "valid_qty",
                ),
                "array_flip"  => array(
                    1,
                ),
            ),

        ),
        "reviewCompactListSum"          => array(
            "shipping_service" => "shipping service",
            "grand_total_ui"   => "total amount",
            "grand_ppn"        => "VAT 11%",
            "new_net3"         => "grand total",
        ),
        "reviewAddRows"                 => array(
            "top__nama"     => "pembayaran",
            "dp"            => "downpayment",
            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign"                    => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
        "fixedFieldHold"                => array(
            "transaksi" => array(
                "label"  => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "fields" => array(
                    "nomer_top"             => "nomer",
                    "dtime"                 => "approved",
                    "seller_nama"           => array(
                        "step"  => 1,
                        "key"   => "olehName",
                        "label" => "salesman",
                    ),
                    "oleh_nama"             => "approval",
                    "customers_nama"        => "customer",
                    //                    "transaksi_nilai" => "nilai",
                    "outstanding_items"     => "detail items*",
                    "sub_outstanding_items" => "nilai",
                    //                    "print_label" =>"tool",
                ),
                "loop"   => array(),
                "items"  => array(
                    "outstanding_items" => array(
                        "nett1",
                    ),
                ),
            ),
            "produk"    => array(
                "label"  => "produk",
                "target" => "produk",
                "srcKey" => "produk_id",
                "fields" => array(
                    //                    "no" =>"No",
                    "produk_nama"    => "product",
                    "produk_kode"    => "product_no",
                    "customers_nama" => "customers nama",
                    "nomer_top"      => "Transaksi",
                    "ord_qty"        => "Order",
                    "ord_sent_qty"   => "Dikirim",
                    "ord_valid_qty"  => "Outstanding",
                    "stok"           => "Tersedia",
                    //                    "print_label" =>"tool",
                ),
                "loop"   => array(
                    "customers_nama" => "customers_nama",
                    "nomer_top"      => "nomer_top",
                    "ord_qty"        => "produk_ord_jml",
                    "ord_valid_qty"  => "valid_qty",
                    "ord_sent_qty"   => "produk_ord_jml-valid_qty",
                ),

            ),
            "customer"  => array(
                "label"      => "customer",
                "target"     => "customer",
                "srcKey"     => "customers_id",
                "fields"     => array(
                    "customers_nama" => "Customer",
                    "nomer_top"      => "Transaksi SO",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode"    => "produk kode",
                    "produk_ord_jml" => "order",
                    "ord_sent_qty"   => "dikirim",
                    "ord_valid_qty"  => "<span class='text-red'>Outstanding</span>",
                ),
                "loop"       => array(
                    "nomer_top"      => "nomer_top",
                    //                    "produk_nama" =>"produk_nama",
                    "produk_kode"    => "produk_kode",
                    "produk_ord_jml" => "produk_ord_jml",
                    "ord_sent_qty"   => "produk_ord_jml-valid_qty",
                    "ord_valid_qty"  => "valid_qty",
                ),
                "array_flip" => array(
                    1,
                ),
            ),

        ),
        "print_nvalas"                  => false,
        "print_lable"                   => array(
            "steps" => array(
                1 => array(
                    "label"    => "pre order",
                    "labelPre" => "invoice",
                ),
            ),
        ),
        // "printException" => array(
        //     5 => "bulat",
        // ),
        "print_hitung"                  => array(
            5 => false,
        ),
        "print_hitung_itemRecap"        => array(
            5 => array(
                "nett1" => "jml*nett1",
            ),
        ),
        "print_hitung_mainReplacer"     => array(
            5 => array(
                "ongkir"           => "ongkir",
                "new_net1"         => "nett1+ongkir",
                //                "dp_value" => "dp_value",
                //                "dp_ppn_value" => "dp_ppn_value",
                //                "total_ui" => "total_ui",
                "nett1_bulat"      => "new_net1",
                "ppn_out_bulat"    => "ongkir_ppn+(10/100*nett1)-dp_ppn_value",
                "ppn_net"          => "ppn",
                //                "tagihan" => "new_net1+ppn_out_bulat-dp-nilai_cia",
                "tagihan"          => "new_net1+ppn_net-dp-nilai_cia",
                "grand_pembulatan" => "grand_pembulatan",
            ),
        ),
        "print_hitung_unsetSumFields"   => array(
            5 => array(
                "nilai_pembulatan",
                "nett1_bulat",
            ),
        ),
        "print_hitung_roundDown"        => array(
            5 => array(
                "ppn_out_bulat",
                "tagihan",
            ),
        ),

        "receiptElementInjector" => array(
            "source" => array(
                "element"    => "customerDetails",
                "fields"     => array(
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
        "showCabangInvoice"      => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => false,
        ),
    ),
    "980"     => array(
        "receiptTemplate"        => array(
            1 => "template/982r.html",
            2 => "template/982g.html",
            3 => "template/982.html",
        ),
        "headerNota"             => array(
            "dtime"             => "date",
            "customers_nama"    => "Customer",
            "tlp_1"             => "phone",
            "alamat_1"          => "address",
            "dtime_jatuh_tempo" => "jatuh tempo",
            "pembayaran"        => "payment method",
        ),
        "fixedElements"          => array(
            1 => array(
                "nomer"              => "No",
                "dtime"              => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama"           => "Term of Payment",
                "tos_nama"           => "Term of Shipment",
                "capacity_nama"      => "Capacity",
                "dueDate_value"      => "Due Date",
            ),
            2 => array(
                "nomer"              => "No",
                "dtime"              => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama"           => "Term of Payment",
                "tos_nama"           => "Term of Shipment",
                "capacity_nama"      => "Capacity",
                "dueDate_value"      => "Due Date",
            ),
            3 => array(
                "nomer"              => "No",
                "dtime"              => "Date",
                "shippingDate_value" => "Delivery Date",
                "top_nama"           => "Term of Payment",
                "tos_nama"           => "Term of Shipment",
                "capacity_nama"      => "Capacity",
                "dueDate_value"      => "Due Date",
            ),
            4 => array(
                "nomer"              => "No",
                "nomer_top"          => "SO No.",
                "dtime"              => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                "tos_nama"           => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            5 => array(
                "nomer"              => "No",
                "nomer_top"          => "SO No.",
                "dtime"              => "Date",
                "shippingDate_value" => "Delivery Date",
                //                "top_nama" => "Term of Payment",
                "tos_nama"           => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                //                "dueDate_value" => "Due Date",
            ),
            6 => array(
                "nomer"         => "INV No",
                "nomer_top"     => "SO No.",
                "dtime"         => "Date",
                //                "shippingDate_value" => "Delivery Date",
                "top_nama"      => "Term of Payment",
                //                "tos_nama" => "Term of Shipment",
                //                "capacity_nama" => "Capacity",
                "dueDate_value" => "Due Date",
            ),
        ),
        "fixedSignatures"        => array(
            1 => array(
                "customer" => array(
                    "label"    => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            2 => array(
                "customer" => array(
                    "label"    => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
            3 => array(
                "customer" => array(
                    "label"    => ".Confirmed and approved by",
                    "contents" => "customerDetails_nama",
                    //                "caption_department" => "",
                ),
            ),
        ),
        "headerTables"           => array(
            "produk_nama"    => "product name",
            "produk_ord_hrg" => "price",
            "produk_ord_jml" => "jumlah",
            "sub_total"      => "sub total",
        ),
        "receiptMainFields"      => array(
            "jenis_label"    => "activity",
            "nomer"          => "reference no.",
            "result_nomer"   => "receipt no.",
            "customers_nama" => "customer",
            "dtime"          => "date",
        ),
        "receiptDetailFields"    => array(
            1 => array(
                "produk_nama"    => "product name",
                "produk_kode"    => "part name",
                "produk_ord_jml" => "qty",
                "satuan"         => "uom",
                //                "hpp" => "price",
            ),
            2 => array(
                "produk_nama"    => "product name",
                "produk_kode"    => "part name",
                "produk_ord_jml" => "qty",
                "satuan"         => "uom",
                //                "hpp" => "price",
                //            "ppn" => "ppn",
            ),
            3 => array(
                "produk_nama"    => "product name",
                "produk_kode"    => "part name",
                "produk_ord_jml" => "qty",
                "satuan"         => "uom",
                //                "hpp" => "price",
                //            "ppn" => "ppn",
            ),
        ),
        "receipNumFields"        => array(
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
        "receiptNumFields"       => array(
            1 => array(
                "harga"        => "Price",
                "disc_percent" => "disc (%)",
                "disc"         => "disc (IDR)",
                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga"        => "Price",
                "disc_percent" => "disc (%)",
                "disc"         => "disc (IDR)",
                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga"        => "Price",
                "disc_percent" => "disc (%)",
                "disc"         => "disc (IDR)",
                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "receiptSumFields"       => array(
            1 => array(
                "harga" => "Amount",
                "disc"  => "DISC",
                "ppn"   => "VAT",
                "nett2" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                "disc"  => "DISC",
                "ppn"   => "VAT",
                "nett2" => "Grand Total",
            ),
            3 => array(
                "harga" => "Amount",
                "disc"  => "DISC",
                "ppn"   => "VAT",
                "nett2" => "Grand Total",
            ),
        ),
        "reportSumFields"        => array(
            "customers_id" => "customers_nama",

        ),
        "printLocation"          => "Printing/viewReceipt/",
        "allowPrint"             => array(
            1 => array("size" => "normal"),
            2 => array("size" => "normal"),
            3 => array("size" => "normal"),
        ),
        //        "receiptInword" => array(
        //            "in_word" => array("inWordInd" => "nett2",),
        //
        //        ),
        "receiptInword"          => array(
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
            "produk_kode"  => "part no",
            "nama"         => "product name",
            "harga"        => "unit price",
            "harganppn"    => "unit price + ppn",
            "disc_percent" => "unit disc (%)",
            "disc"         => "unit disc",
            "qty"          => "qty",
            "sub_harga"    => "sub bruto",
            "sub_disc"     => "sub diskon",
            "sub_nett1"    => "sub netto",
        ),
        "reviewMainCompactListsLabel"   => array(
            "nomer"                     => "Nomer",
            "customerDetails__alamat_1" => "address",
            "customerDetails__tlp_1"    => "phone",
            "customerDetails__tlp_2"    => "handphone",
            "customerDetails__npwp"     => "npwp",
            "billingDetails__nik"       => "nik",
            "valas_nama"                => "currency",
        ),
        "reviewCompactListDetailSum"    => array(
            "qty"   => "qty",
            "jual"  => "jual",
            "disc"  => "disc",
            "nett1" => "grand total",
        ),
        "reviewCompactListSum"          => array(
            "shipping_service" => "shipping service",
            "grand_total_ui"   => "total amount",
            "grand_ppn"        => "VAT 10%",
            "new_net3"         => "grand total",
        ),
        "reviewAddRows"                 => array(
            "top__nama"     => "pembayaran",
            "dp"            => "downpayment",
            "paymentMethod" => "paymentMethod",
        ),
        "reviewSign"                    => array(
            1 => array(
                "sign_1",
            ),
            2 => array(
                "sign_1",
                "sign_2",
            ),
        ),
    ),

);