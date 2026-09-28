<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    // salesman mode
    "582" => array(
        "icon" => "fa fa-opencart",
        "label" => "sales",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "SALES ORDER",
                "label_nota" => "INVOICE",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "582spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Dipersiapkan Oleh",
            ),
            2 => array(
                "label" => "SALES",
                "actionLabel" => "approve order/<br>otorisasi diskon",
                "source" => "582spo",
                "target" => "582so",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optStateLabel"    => "pending disc. approval",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
            ),
            3 => array(
                "label" => "PRE PACKING",
                "actionLabel" => "process packing",
                "source" => "582so",
                "target" => "582pkd", // packed
                "userGroup" => "o_gudang",
                "stateLabel" => "packed",
                "stateColor" => "#009900",
                "stateCaption" => "Prepared",
                "allowEdit" => true,
                "paymentSrc" => array(
                    "enabled" => true,
                    "filter" => array(
                        "label='incoming cash'",
                    ),
                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
                    "label" => "Uang muka belum diterima. Segera hubungi pihak Finance.",
                ),
                "allowScaner" => true,
            ),
            4 => array(
                "label" => "PACKING LIST",
                "actionLabel" => "process shipment",
                "source" => "582pkd",
                "target" => "582spd", // shipped
                "userGroup" => "o_gudang",
                "stateLabel" => "shipped",
                "stateColor" => "#009900",
                "stateCaption" => "shipped by",
            ),
            //            5 => array(
            //                "label" => "I N V O I C E",
            //                "actionLabel" => "create invoice",
            //                "source" => "582spd",
            //                "target" => "582", // invoice
            //                "userGroup" => "o_finance",
            //                "stateLabel" => "invoiced",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "completed by",
            //                "allowJoin" => true,
            //                //                "allowEdit" => true,
            //            ),
        ),
        "shoppingCartMeasurement" => array(
            3 => true,
            4 => true,
        ),
        "template" => "template/transaksi_nosalesman.html",
        "selectorModel" => "MdlProduk2",
        "selectorSrcModel" => "MdlProduk2",
//        "selectorModel" => "MdlLockerStock",
//        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "jual_reseller",
                "jual_online",
                "jual",
                "ppv",
                "disc",
                "disc_percent"
            ),
            "key_label" => array(
                "jual_reseller" => "jual_reseller",
                "jual_online" => "jual_online",
                "jual" => "harga",
                "ppv" => "ppv",
                "disc" => "disc",
                "disc_percent" => "disc (%)",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "stock_locker.cabang_id=placeID",
//            "stock_locker.gudang_id=gudangID",
//            "stock_locker.jumlah>.0",
//            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
//            "no_part" => "no_part",
            "satuan" => "satuan",
            // "jenis" => "jenis",
        ),
        "selectorViewedFields" => array(
            "barcode",
            "nama",
            "kode",
            "satuan",
            "kategori_nama",
            "sub_kategori_nama",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "pihakPair" => array(
            "enabled" => true,
            "model" => "ComRekeningPembantuCustomerDetail",
            "filter" => array(
//                "extern_id=pihakID",
                "extern2_id=.2010050030",
                "cabang_id=placeID",
            ),
            "rekening" => "2010050",
            "key" => "qty_kredit",
            "method" => "fetchBalances",
        ),

        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            // sumber dari kolom id_his
            "nomer_soa" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "SOA number",
            ),
            "nomer_prepl" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "PRE-PL number",
            ),
            "nomer_pl" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "PL number",
            ),
            "nomer_inv" => array(
                "step" => 5,
                "key" => "nomer",
                "label" => "INV number",
            ),
            //            "nomer" => "receipt number",
            "seller_nama" => array(
                "step" => 1,
                "key" => "olehName",
                "label" => "sales",
            ),

            "oleh_nama" => "person",

            "jual" => "bruto",
//            "disc"                   => "discount",
            "disc" => "diskon tambahan",
            "ppn" => "ppn",
            //            "nett2" => "total amount",
            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            "pengirim_nama" => "nama pengirim",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nett1" => "amount",
            "disc" => "discount",
            "add_diskon" => "diskon tambahan",
            "ppn" => "ppn",
            "nett2" => "total amount",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "cetak inv",
                "print_label_mod" => "cetak inv<br>bruto",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "SOA number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer" => "PRE-PL number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE-PL number",
                ),
                "nomer" => "PL number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                "jual" => array(
                    "step" => 5,
                    "key" => "jual",
                    "label" => "bruto",
                    "transaksi_jenis2" => array(
                        "paket" => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE-PL number",
                ),
                "ids_his" => array(
                    "step" => 4,
                    "key" => "nomer",
                    "label" => "PL number",
                ),
                "nomer" => "invoice number",
                "sales_name" => "sales",
                "logistic" => "logistic",
                "oleh_nama" => "invoice",
                //            "transaksi_nilai" => "amount",
                //                "jual" => "amount",
                "jual" => array(
                    "step" => 5,
                    "key" => "jual",
                    "label" => "bruto",
                    "transaksi_jenis2" => array(
                        "paket" => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                // "harga" => "amount",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto<br>(after discount)",
                // "ppn" => "ppn",
                // "nett2" => "total amount",
                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "return" => "return",
                "netto_return" => "netto",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "paymentMethod__label" => "payment method",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
                "print_label_mod" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(

                "print_label" => "nomer",
            ),
            5 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryKeterangan" => array(
            1 => array(
                "edit" => array(
                    "kolom" => "status_edit",
                    "value" => "1",
                    "labels" => array(
                        "edit_name", "edit_dtime",
                    ),
                    "style" => array(
                        "bgcolor" => "yellow",
                        "color" => "red",
                    ),
                ),
            ),
            4 => array(
                "return" => array(
                    "kolom" => "returned",
                    "value" => "1",
                    "labels" => "RETURNED",
                    "style" => array(
                        "bgcolor" => "orange",
                        "color" => "black",
                    ),
                ),
            ),
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            5 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(ppnFactor/100)",
            "barcode" => "barcode",
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",

            //            "volume" => "volume",
            //            "berat" => "berat",
            //            "lebar" => "lebar",
            //            "tinggi" => "tinggi",
            //            "panjang" => "panjang",
            "jenis" => "jenis",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                "nett1" => "price(net)",
                // "jual_reseller" => "jual reseller",
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
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc",
                "nett1",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                //                "disc_percent",
                //                "disc",
            ),
            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => true,
            5 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total amount bruto",
                //                "disc" => "disc",
                //                "add_diskon" => "diskon tambahan",
                "total_diskon" => "diskon",
//                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "total amount netto*",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "grand total",
                //---------------
                "point_saldo_awal" => "point awal",
//                ".6640" => "point awal",
                "point_transaksi" => "point transaksi",
                "point_saldo_akhir" => "point akhir",
//                "point_saldo_akhir+6640" => "point akhir",
            ),
            2 => array(
                "harga" => "total amount bruto",
//                "disc"             => "disc",
                "disc" => "diskon tambahan",
//                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "nett amount",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "nett amount + PPN",
            ),
            4 => array(
                //                "berat_gross" => "Berat",
                //                "volume_gross"   => "Volume",
                //                "grand_ppn"        => "VAT",
                //                "tagihan_ui" => "Grand Total",
                //                "new_net3"         => "Grand Total",
            ),
            5 => array(
//                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Amount",
                //                "nilai_pembulatan" => "Pembulatan",
                "nett1_bulat" => "Total Amount",
                "ppn_out_bulat" => "VAT",
                "grand_pembulatan" => "Grand Total",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=addCommas(((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))/100))",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
                "nett1" => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "harga jual",
            // "disc_percent" => "disc(%)",
            // "disc" => "disc(IDR)",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shopingCartValidatorExt" => array(
            "disc_percent" => array(
                "name" => "disc(%)",
                "min" => ".0",
                "max" => ".100",
            ),

            "disc" => array(
                "name" => "disc(IDR)",
                "min" => ".0",
                "max" => "harga",
            ),
        ),
        "shoppingCartRowOptionalValidators" => array(
//            "shippingService" => array(
//                "ongkir_ppn_by_cust" => array(
//                    "shipping_service" => "ongkir",
//                ),
//                "ongkir_tanpa_ppn_by_cust" => array(
//                    "shipping_service" => "ongkir",
//                ),
//                //                "ongkir_tanpa_ppn_by_company" => array(
//                //                    "shipping_service" => "ongkir",
//                //
//                //                ),
//            ),
        ),
        "shoppingCartSumEditableFields" => array(
            //            1 => array(
            //                "add_diskon",
            //            ),
            //
        ),
        "shoppingCartSubDetailFields" => array(
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
            4 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
        ),

        "shoppingCartNoteAdditionalEditabled" => array(
            //            3 => array(
            //                "enabled" => true,
            //                "editabled" => true,
            //            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
            ),
            5 => array(
                "previews" => true,
                "enabled" => false,
                "editabled" => false,
            ),
        ),
        //        "shoppingCartHeaderElement" => array(
        //            1 => array(
        //                "tipe_penjualan" => array(
        //                    "label" => "TENTUKAN TIPE PENJUALAN (REGULER atau dengan RESELLER)",
        //                    "class" => "col-xs-6 no-padding",
        //                    "subElements" => array(
        //                        "reguler" => array(
        //                            "label" => "Reguler",
        //                            "srcMain" => "tipePenjualanCheck",
        ////                            "overWriteMain" => "ppnFactor",
        ////                            "srcItem" => "ppnFactor",
        //                            "value" => "1",
        //                            "style" => array( //wajib di tentukan
        //                                "element" => "div",
        //                                "class" => "",
        //                                "id" => "",
        //                                "name" => "",
        //                            )
        //                        ),
        //                        "reseller" => array(
        //                            "label" => "Reseller",
        //                            "srcMain" => "tipePenjualanCheck",
        ////                            "overWriteMain" => "ppnFactor",
        ////                            "srcItem" => "ppnFactor",
        //                            "value" => "2",
        //                            "style" => array( //wajib di tentukan
        //                                "element" => "div",
        //                                "class" => "",
        //                                "id" => "",
        //                                "name" => "",
        //                            )
        //                        ),
        //                    )
        //                ),
        //            )
        //        ),
        //        "shoppingCartHeaderElementSelector" => "_processSelectTipePenjualan/select",


        //        "receiptMesurementRows" => array(
        //            "cbu" => array(
        //                "volume" => array(
        //                    "volume_new" => "conv_mmc_mc",
        //                ),
        //                "berat" => array(
        //                    "berat_new" => "conv_g_kg",
        //                ),
        //            ),
        //            "ckd" => array(
        //                "volume_gross" => array(
        //                    "volume_new" => "conv_mmc_mc",
        //                ),
        //                "berat_gross" => array(
        //                    "berat_new" => "conv_g_kg",
        //                ),
        //            ),
        //            "mesurement" => array(
        //                "cbu_cbm" => array(
        //                    "label" => "cbu cbm",
        //                    "value" => "volume",
        //                    "helper" => "conv_mmc_mc",
        //                ),
        //                "cbu_kg" => array(
        //                    "label" => "cbu(kg)",
        //                    "value" => "berat",
        //                    "helper" => "conv_g_kg",
        //                ),
        //                "ckd_cbm" => array(
        //                    "label" => "ckd cbm",
        //                    "value" => "volume_gross",
        //                    "helper" => "conv_mmc_mc",
        //                ),
        //                "ckd_kg" => array(
        //                    "label" => "ckd (kg)",
        //                    "value" => "berat_gross",
        //                    "helper" => "conv_g_kg",
        //                ),
        //            ),
        //            "allowView" => array("1", "2", "3", "4"),
        //        ),
        "receiptElements" => array(
//            "shippingService" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "shipping service",
//                "mdlName" => "MdlOngkir",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "name",
//                "description" => "",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1),
//            ),
            "shippingMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "dikirim / diambil sendiri",
                "mdlName" => "MdlShippingMethodStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "description" => "",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detil Konsumen",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
                    "kredit_limit" => "KREDIT LIMIT",
                ),
                "editPoints" => array(1, 2, 3, 4),
                "reloadLink" => "_processPihak/select/",
            ),
            "billingDetails" => array(
                "elementType" => "dataModel",
                "elementJoint" => array(
                    "method" => "lookUpJointCustomer",
                    "mdlFilter" => "id=pihakID",
                    "usedFields" => array(
                        "no_ktp" => "nik",
                        "nik" => "nik",
                        "npwp" => "npwp",
                    ),
                ),
                "inputType" => "radio",
                "label" => "Detail Penagihan",
                "mdlName" => "MdlCustomerBillAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                //                "mdlName" => "MdlCustomer_and_pre",
                //                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                //                "labelSrc" => "alias",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "extern_name" => "Customer",
                    "alias" => "Attn*",
                    "alamat" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "NIK",
                ),
                "editPoints" => array(),
                "optionPoints" => array(
                    "label" => array(
                        "lengkap" => 1,
                        "simple" => 2,
                        "no detail" => 3,
                    ),
                    "labelTitle" => array(
                        "lengkap" => "semua data delivery address akan ditampilkan.",
                        "simple" => "hanya attn/pic dan nomor telepon yang akan ditampilkan.",
                        "no detail" => "tidak ada yang akan ditampilkan.",
                    ),
                    "step" => "5",
                    "hidden" => array(
                        2 => array(//hidden bernilai 0, list yang perlu hidden
                            "alamat",
                            "kelurahan",
                            "kecamatan",
                            "kabupaten",
                            "propinsi",
                            "npwp",
                            "no_ktp",
                        ),
                        3 => array(//hidden bernilai 0, list yang perlu hidden
                            "alias",
                            "alamat",
                            "kelurahan",
                            "kecamatan",
                            "kabupaten",
                            "propinsi",
                            "tlp",
                            "tlp_1",
                            "tlp_2",
                            "npwp",
                            "no_ktp",
                        ),
                    ),
                ),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Pengiriman",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Tlp",
                    "tlp_2" => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
//            "detilSize"       => array(
//                "elementType"  => "dataModel",
//                "inputType"    => "radio",
//                "label"        => "Data ukuran",
//                "mdlName"      => "MdlMeasurement",
//                "mdlFilter"    => array("extern_id=pihakID"),
//                "key"          => "id",
//                "labelSrc"     => "name",
//                "defaultValue" => "ckd",
//                "usedFields"   => array(
//                    "name" => "",
//                ),
//                "editPoints"   => array(1, 2, 3),
//            ),
            //            "tos" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "defaultValue" => "20",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),

            //            "capacity" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
//            "dueDate"         => array(
//                "elementType"  => "dataField",
//                "label"        => "due date",
//                "inputType"    => "date",
//                "defaultValue" => $date->format('Y-m-d'),
//                "editPoints"   => array(1, 2, 3, 4, 5),
//            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "Name",
                    "note" => "Note",
                ),
                "defaultValue" => "defaultPaymentMethod",
                "editPoints" => array(1),
                "kreditLimitValidate" => array(
                    1 => array(
                        "enabled" => true,
                    ),
                ),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        //                        "mdlName" => "MdlBankAccount_in",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataField",
                        "inputType" => "number",
                        "label" => "term of payment (hari)",
//                        "mdlName"     => "MdlTop",
//                        "mdlFilter"   => array(),
//                        "key"         => "kode",
//                        "labelSrc"    => "nama",
                        "description" => "",
                        "defaultValue" => "defaultTermOfPayment",
//                        "usedFields"  => array(
//                            "nama" => "",
//                        ),
                        "editPoints" => array(1,),
                    ),
                ),
            ),
            "shippingMethod" => array(
                "1" => array(
                    "shippingDate" => array(
                        "elementType" => "dataField",
                        "label" => "rencana tanggal pengiriman",
                        "inputType" => "date",
                        "defaultValue" => date("Y-m-d"),
                        "editPoints" => array(1),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => true,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "1982",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups" => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
            2 => array(
                "enabled" => true,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "1982",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups" => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
        ),

        "validateMeasurement" => array(
            //            1 => array(
            //                "panjang_gross" => "CKD length (in millimeters)",
            //                "lebar_gross" => "CKD width (in millimeters)",
            //                "tinggi_gross" => "CKD height (in millimeters)",
            //            ),
        ),
        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        //        "requestCode" => array(
        //            "masterCode" => "581",
        //            "stateCode" => "581r",
        //            "stepNumber" => "1",
        //            "allowMultiSelect" => false,
        //        ),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),

            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
//            "shippingService" => array(
//                "ongkir_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        'disabled' => "",
//                        "addPoints" => array(1),
//                        "editPoints" => array(4),
//                    ),
//                ),
//                "ongkir_tanpa_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        'disabled' => "",
//                        "addPoints" => array(1, 4),
//                        "editPoints" => array(4),
//                    ),
//                ),
//            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        ),
        "allowedMainEdit" => array("1"),
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),

        // "compactHistoryFields" => array(
        //     // "jenis_label" => "activity",
        //     "dtime" => "date",
        //     "suppliers_nama" => "customer",
        //     "nomer" => "receipt number",
        //     "oleh_nama" => "person",
        //
        //     "harga" => "amount",
        //     "disc" => "discount",
        //     "ppn" => "ppn",
        //     "nett2" => "total amount",
        // ),
        "compactHistoryFields" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    // "nomer_top" => "nomer",
                    // "dtime" => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    // "oleh_nama" => "approval",
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
        //        "addMainSource" => array(
        //            6 => array(
        //                "fields" => array(
        //                    "nomer" => "INV",
        //                    "grand_total_ui" => "DPP",
        //                    "new_grand_ppn" => "PPN",
        //                    "dateFaktur" => "Tgl faktur ",
        //                    "eFaktur" => "e-faktur",
        //                ),
        //                "editableFields" => array(
        //                    "eFaktur" => "",
        //                    "dateFaktur" => "",
        //                ),
        //            ),
        //        ),

        "connectTo" => "110",
        "connectoValidate" => array(
            5 => "nilai_credit",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "pusat",
            "place2ID" => "-1",
            "place2Name" => "pusat",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang

            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        //connecto ke pusat jika ada produk yang perlu direpack dipusat untuk penjualan produk komposit
        //        "extConnectTo" => array(
        //            2 => array(
        //                "connecTo" => "773",
        //            ),
        //        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "582spoe",
                "label" => "EDIT SALES PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "582sporj",
                "label" => "REJECT SALES PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "582sorj",
                "label" => "REJECT SALES ORDER",
            ),
            3 => array(
                "enabled" => true,
                "connectTo" => "582pkdrj",
                "label" => "REJECT PRE PACKING",
            ),
        ),
        //----
        "autoApprove" => array(
            1 => true,
        ),
        "kreditLimitValidate" => array(
//            1 => array(
//                "enabled" => true,
//            ),
//
            3 => array(
                "enabled" => true,
                "label" => "Transaksi ini sedang ditindaklanjuti oleh pihak finance.<br>Silahkan konfirmasi ke pihak finance.",
            ),
        ),
        "shopingCartReload" => true,
        //----
        "produkUnitPart" => array(
            "outdoor" => array(
                "outdoor_id" => "outdoor_nama",
            ),
            "indoor" => array(
                "indoor_id_1" => "indoor_nama_1",
                "indoor_id_2" => "indoor_nama_2",
                "indoor_id_3" => "indoor_nama_3",
                "indoor_id_4" => "indoor_nama_4",
            ),
            "heater" => array(
                "heater_id" => "heater_nama",
            ),
        ),
    ),
    // sales support mode
    "5822" => array(
        "icon" => "fa fa-opencart",
        "label" => "sales",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "SALES PRE ORDER",
                "label_nota" => "SALES PRE ORDER",
//                "label_nota" => "INVOICE",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "5822spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "sales admin",
            ),
            2 => array(
                "label" => "SALES",
                "label_nota" => "SALES ORDER",
//                "label_nota" => "INVOICE",
                "actionLabel" => "approve order/<br>otorisasi diskon",
                "source" => "5822spo",
                "target" => "5822so",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optStateLabel"    => "pending disc. approval",
                "stateColor" => "#ff7700",
                "stateCaption" => "mengetahui",
                "allowEdit" => true,
            ),
            3 => array(
                "label" => "PRE PACKING",
                "label_nota" => "SURAT JALAN",
                "actionLabel" => "process packing",
                "source" => "5822so",
                "target" => "5822pkd", // packed
                "userGroup" => "o_gudang",
                "stateLabel" => "packed",
                "stateColor" => "#009900",
                "stateCaption" => "Dipersiapkan Oleh",
                "allowEdit" => true,
                "paymentSrc" => array(
                    "enabled" => true,
                    "filter" => array(
                        "label='incoming cash'",
                    ),
                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
                    "label" => "Uang muka belum diterima. Segera hubungi pihak Finance.",
                ),
                "allowScaner" => true,
            ),
            4 => array(
                "label" => "PACKING LIST",
//                "label_nota" => "Proforma INVOICE",
//                "label_nota" => "INVOICE",
                "label_nota" => "PACKING LIST",
                "actionLabel" => "process shipment",
                "source" => "5822pkd",
                "target" => "5822spd", // shipped
                "userGroup" => "o_gudang",
                "stateLabel" => "shipped",
                "stateColor" => "#009900",
                "stateCaption" => "Pengirim ",
                "autoNextStep" => true,
            ),
            //            5 => array(
            //                "label" => "I N V O I C E",
            //                "actionLabel" => "create invoice",
            //                "source" => "582spd",
            //                "target" => "582", // invoice
            //                "userGroup" => "o_finance",
            //                "stateLabel" => "invoiced",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "completed by",
            //                "allowJoin" => true,
            //                //                "allowEdit" => true,
            //            ),
        ),
        "shoppingCartMeasurement" => array(
            3 => true,
            4 => true,
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProduk2",
        "selectorSrcModel" => "MdlProduk2",
//        "selectorModel" => "MdlLockerStock",
//        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "jual_reseller",
                "jual_online",
                "jual",
                "ppv",
                "disc",
                "disc_percent"
            ),
            "key_label" => array(
                "jual_reseller" => "jual_reseller",
                "jual_online" => "jual_online",
                "jual" => "harga",
                "ppv" => "ppv",
                "disc" => "disc",
                "disc_percent" => "disc (%)",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "stock_locker.cabang_id=placeID",
//            "stock_locker.gudang_id=gudangID",
//            "stock_locker.jumlah>.0",
//            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item / produk unit / non unit/jasa",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
//            "no_part" => "no_part",
            "satuan" => "satuan",
            // "jenis" => "jenis",
        ),
        "selectorViewedFields" => array(
//            "barcode",
            "nama",
            "kode",
            "satuan",
            "kategori_nama",
//            "sub_kategori_nama",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        //--------------
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "pihakPair" => array(
            "enabled" => true,
            "model" => "ComRekeningPembantuCustomerDetail",
            "filter" => array(
//                "extern_id=pihakID",
                "extern2_id=.2010050030",
                "cabang_id=placeID",
            ),
            "rekening" => "2010050",
            "key" => "qty_kredit",
            "method" => "fetchBalances",
        ),
        //--------------
        "pihakModelMain" => "MdlGudangStatus",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "pilih dikirim dari...",
        "pihakMainFilters" => array(),
        "pihakMainProcessor" => "_processPihakMain/select",
        //--------------
        "selectorLabel2" => "pilih salesman terdaftar...",
        "selectorModel2" => "MdlEmployeeSalesman",
        "selectorCaller2" => "_selectorPihakMain/selectPihakSalesman",
        "selectorProcessor2" => "_processPihakMain/selectSalesman",
        "selectorParamFields2" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields2" => array(
            "nama",
        ),
        "selectorFilters2" => array(//            "cabang_id=placeID",
        ),
        //--------------

        "shortHistoryFields" => array(
            //            "no" => "no",
//            "jenis_label" => "activity",
            "dtime" => "date",
//            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "pre so number",
            "nomer" => "SO number",
            "item_fields" => "isi",
            // sumber dari kolom id_his
//            "nomer_soa" => array(
//                "step" => 2,
//                "key" => "nomer",
//                "label" => "SOA number",
//            ),
//            "nomer_prepl" => array(
//                "step" => 3,
//                "key" => "nomer",
//                "label" => "PRE-PL number",
//            ),
//            "nomer_pl" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "PL number",
//            ),
//            "nomer_inv" => array(
//                "step" => 5,
//                "key" => "nomer",
//                "label" => "INV number",
//            ),
            //            "nomer" => "receipt number",
            "seller_nama" => array(
                "step" => 1,
                "key" => "olehName",
                "label" => "sales admin",
            ),
            "salesman_nama" => "salesman",
//            "oleh_nama" => "person",
            "jual" => "bruto",
//            "disc"                   => "discount",
            "disc" => "diskon tambahan",
            "ppn" => "ppn",
            //            "nett2" => "total amount",
            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            "pengirim_nama" => "nama pengirim",
            "gudang_status_jenis" => "dikirim dari",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "pre so number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nett1" => "amount",
            "disc" => "discount",
            "add_diskon" => "diskon tambahan",
            "ppn" => "ppn",
            "nett2" => "total amount",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "item_fields" => "isi",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "salesman_nama" => "salesman",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "gudang_status_jenis" => "dikirim dari",
                "keterangan" => "keterangan",
                "print_label" => "cetak nota",
                "print_label_mod" => "cetak nota<br>bruto",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "SOA number",
                "item_fields" => "isi",
                "sales_name" => "sales admin",
                "salesman_nama" => "salesman",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "gudang_status_jenis" => "dikirim dari",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer" => "PRE-PL number",
                "item_fields" => "isi",
                "sales_name" => "sales admin",
                "salesman_nama" => "salesman",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "gudang_status_jenis" => "dikirim dari",
                "pengirim_nama" => array(
                    "step" => 3,
                    "key" => "pengirim_nama",
                    "label" => "nama pengirim",
                ),
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE-PL number",
                ),
                "nomer" => "PL number",
                "item_fields" => "isi",
                "sales_name" => "sales admin",
                "salesman_nama" => "salesman",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                "jual" => array(
                    "step" => 5,
                    "key" => "jual",
                    "label" => "bruto",
                    "transaksi_jenis2" => array(
                        "paket" => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "gudang_status_jenis" => "dikirim dari",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE-PL number",
                ),
                "ids_his" => array(
                    "step" => 4,
                    "key" => "nomer",
                    "label" => "PL number",
                ),
                "nomer" => "invoice number",
                "item_fields" => "isi",
                "sales_name" => "sales admin",
                "salesman_nama" => "salesman",
                "logistic" => "logistic",
                "oleh_nama" => "invoice",
                //            "transaksi_nilai" => "amount",
                //                "jual" => "amount",
                "jual" => array(
                    "step" => 5,
                    "key" => "jual",
                    "label" => "bruto",
                    "transaksi_jenis2" => array(
                        "paket" => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                // "harga" => "amount",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto<br>(after discount)",
                // "ppn" => "ppn",
                // "nett2" => "total amount",
                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "return" => "return",
                "netto_return" => "netto",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "paymentMethod__label" => "payment method",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
                "print_label_mod" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(

                "print_label" => "nomer",
            ),
            5 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryKeterangan" => array(
            1 => array(
                "edit" => array(
                    "kolom" => "status_edit",
                    "value" => "1",
                    "labels" => array(
                        "edit_name", "edit_dtime",
                    ),
                    "style" => array(
                        "bgcolor" => "yellow",
                        "color" => "red",
                    ),
                ),
            ),
            4 => array(
                "return" => array(
                    "kolom" => "returned",
                    "value" => "1",
                    "labels" => "RETURNED",
                    "style" => array(
                        "bgcolor" => "orange",
                        "color" => "black",
                    ),
                ),
            ),
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "stok_center" => "stok dc",
                "stok" => "stok<br>tersedia",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            5 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(ppnFactor/100)",
            "barcode" => "barcode",
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",

            //            "volume" => "volume",
            //            "berat" => "berat",
            //            "lebar" => "lebar",
            //            "tinggi" => "tinggi",
            //            "panjang" => "panjang",
            "jenis" => "jenis",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "outdoor_sku" => "outdoor_sku",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_sku_1" => "indoor_sku_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_sku_2" => "indoor_sku_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_sku_3" => "indoor_sku_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "indoor_sku_4" => "indoor_sku_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            //HEADER FORM SHOPPINGCART DIATUR BERDASARKAN STEP
            1 => array(
//                "harga" => "harga jual",
                "harga_include_ppn" => "harga jual<br>incl. ppn",
                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)", // aslinya ini
                "_diskon_ppn" => "disc (IDR)<br>Incl.PPN",
                // "ppn" => "VAT",
//                "nett1" => "jual(net)<br>Incl.PPN",
                "nett1_ppn" => "jual(net)<br>Incl.PPN",
                // "jual_reseller" => "jual reseller",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
//                "harga" => "price",
                "harga_include_ppn" => "harga jual<br>incl. ppn",
                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
                "_diskon_ppn" => "disc (IDR)",
                "premi_percent" => "premi%",
                "premi" => "premi",
//                "nett1" => "price(net)",
                "nett1_ppn" => "jual(net)<br>Incl.PPN",
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
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc",
                "_diskon_ppn",
                "nett1",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                //                "disc_percent",
                //                "disc",
            ),
            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => true,
            5 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total bruto",
                //                "disc" => "disc",
                // "add_diskon" => "diskon tambahan",
                // "total_diskon" => "diskon",
                // -----------------
                "jml_kategori_unit" => "jumlah unit",
                "diskon_kategori_unit" => "nilai diskon unit",
                //---------------
//                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "total netto",
                "ppn_out_bulat" => "PPN",
//                "grand_pembulatan" => "grand total<br>include ppn",
                "nett1_include_ppn" => "grand total<br>include ppn",
                "point_saldo_awal" => "point awal",
//                ".6640" => "point awal",
                "point_transaksi" => "point transaksi",
                "point_saldo_akhir" => "point akhir",
//                "point_saldo_akhir+6640" => "point akhir",
            ),
            2 => array(
                "harga" => "total amount bruto",
//                "disc"             => "disc",
                "disc" => "diskon tambahan",
//                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "nett amount",
                "ppn_out_bulat" => "PPN",
//                "grand_pembulatan" => "nett amount + PPN",
                "nett1_include_ppn" => "nett amount + PPN",
            ),
            4 => array(
                //                "berat_gross" => "Berat",
                //                "volume_gross"   => "Volume",
                //                "grand_ppn"        => "VAT",
                //                "tagihan_ui" => "Grand Total",
                //                "new_net3"         => "Grand Total",
            ),
            5 => array(
//                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Amount",
                //                "nilai_pembulatan" => "Pembulatan",
                "nett1_bulat" => "Total Amount",
                "ppn_out_bulat" => "VAT",
                "grand_pembulatan" => "Grand Total",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=addCommas(((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))/100))",

                //untuk versi PPN
                "disc_percent" => "document.getElementById('{_diskon_ppn}').value=addCommas(((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga_include_ppn}').innerHTML)))/100))",

                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
                "_diskon_ppn" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga_include_ppn}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
                "nett1" => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "harga jual",
            // "disc_percent" => "disc(%)",
            // "disc" => "disc(IDR)",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shopingCartValidatorExt" => array(
            "disc_percent" => array(
                "name" => "disc(%)",
                "min" => ".0",
                "max" => ".100",
            ),

            "disc" => array(
                "name" => "disc(IDR)",
                "min" => ".0",
                "max" => "harga",
            ),
        ),
        "shoppingCartRowOptionalValidators" => array(
//            "shippingService" => array(
//                "ongkir_ppn_by_cust" => array(
//                    "shipping_service" => "ongkir",
//                ),
//                "ongkir_tanpa_ppn_by_cust" => array(
//                    "shipping_service" => "ongkir",
//                ),
//                //                "ongkir_tanpa_ppn_by_company" => array(
//                //                    "shipping_service" => "ongkir",
//                //
//                //                ),
//            ),
        ),
        "shoppingCartSumEditableFields" => array(
            //            1 => array(
            //                "add_diskon",
            //            ),
            //
        ),
        "shoppingCartSubDetailFields" => array(
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
            ),
            4 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
            ),
        ),
        "shoppingCartNoteAdditionalEditabled" => array(
            //            3 => array(
            //                "enabled" => true,
            //                "editabled" => true,
            //            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
            ),
            5 => array(
                "previews" => true,
                "enabled" => false,
                "editabled" => false,
            ),
        ),
        "shoppingCartHideGudangStatusKey" => "pihakMainJenis",
        "shoppingCartHideGudangStatus" => array(
            "cabang" => array(
                "stok_center",
            ),
            "pusat" => array(
                "stok",
            ),
        ),
        "shoppingCartCopySerialNumber" => array(
            3 => array(
                "statusGudang" => "pusat",
                "copyGate" => "items2,items3_sum",
                "copyGateSerial" => "items3_sum",
                "copyJenis" => "585",
            ),
        ),
        //        "shoppingCartHeaderElement" => array(
        //            1 => array(
        //                "tipe_penjualan" => array(
        //                    "label" => "TENTUKAN TIPE PENJUALAN (REGULER atau dengan RESELLER)",
        //                    "class" => "col-xs-6 no-padding",
        //                    "subElements" => array(
        //                        "reguler" => array(
        //                            "label" => "Reguler",
        //                            "srcMain" => "tipePenjualanCheck",
        ////                            "overWriteMain" => "ppnFactor",
        ////                            "srcItem" => "ppnFactor",
        //                            "value" => "1",
        //                            "style" => array( //wajib di tentukan
        //                                "element" => "div",
        //                                "class" => "",
        //                                "id" => "",
        //                                "name" => "",
        //                            )
        //                        ),
        //                        "reseller" => array(
        //                            "label" => "Reseller",
        //                            "srcMain" => "tipePenjualanCheck",
        ////                            "overWriteMain" => "ppnFactor",
        ////                            "srcItem" => "ppnFactor",
        //                            "value" => "2",
        //                            "style" => array( //wajib di tentukan
        //                                "element" => "div",
        //                                "class" => "",
        //                                "id" => "",
        //                                "name" => "",
        //                            )
        //                        ),
        //                    )
        //                ),
        //            )
        //        ),
        //        "shoppingCartHeaderElementSelector" => "_processSelectTipePenjualan/select",

        //        "receiptMesurementRows" => array(
        //            "cbu" => array(
        //                "volume" => array(
        //                    "volume_new" => "conv_mmc_mc",
        //                ),
        //                "berat" => array(
        //                    "berat_new" => "conv_g_kg",
        //                ),
        //            ),
        //            "ckd" => array(
        //                "volume_gross" => array(
        //                    "volume_new" => "conv_mmc_mc",
        //                ),
        //                "berat_gross" => array(
        //                    "berat_new" => "conv_g_kg",
        //                ),
        //            ),
        //            "mesurement" => array(
        //                "cbu_cbm" => array(
        //                    "label" => "cbu cbm",
        //                    "value" => "volume",
        //                    "helper" => "conv_mmc_mc",
        //                ),
        //                "cbu_kg" => array(
        //                    "label" => "cbu(kg)",
        //                    "value" => "berat",
        //                    "helper" => "conv_g_kg",
        //                ),
        //                "ckd_cbm" => array(
        //                    "label" => "ckd cbm",
        //                    "value" => "volume_gross",
        //                    "helper" => "conv_mmc_mc",
        //                ),
        //                "ckd_kg" => array(
        //                    "label" => "ckd (kg)",
        //                    "value" => "berat_gross",
        //                    "helper" => "conv_g_kg",
        //                ),
        //            ),
        //            "allowView" => array("1", "2", "3", "4"),
        //        ),
        "receiptElements" => array(
//            "shippingService" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "shipping service",
//                "mdlName" => "MdlOngkir",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "name",
//                "description" => "",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1),
//            ),
            "gudangStatusDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Status Gudang",
                "mdlName" => "MdlGudangStatus",
                "mdlFilter" => array("id=pihakMainID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
            "salesmanDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Salesman",
                "mdlName" => "MdlEmployeeSalesman",
                "mdlFilter" => array("id=pihakMain2ID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
            "shippingMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "dikirim / diambil sendiri",
                "mdlName" => "MdlShippingMethodStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "description" => "",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detil Konsumen",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
                    "kredit_limit" => "KREDIT LIMIT",
                ),
                "editPoints" => array(1, 2, 3, 4),
                "reloadLink" => "_processPihak/select/",
            ),
//            "billingDetails" => array(
//                "elementType" => "dataModel",
//                "elementJoint" => array(
//                    "method" => "lookUpJointCustomer",
//                    "mdlFilter" => "id=pihakID",
//                    "usedFields" => array(
//                        "no_ktp" => "nik",
//                        "nik" => "nik",
//                        "npwp" => "npwp",
//                    ),
//                ),
//                "inputType" => "radio",
//                "label" => "Detail Penagihan",
//                "mdlName" => "MdlCustomerBillAddress",
//                "mdlFilter" => array("extern_id=pihakID"),
//                //                "mdlName" => "MdlCustomer_and_pre",
//                //                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                //                "labelSrc" => "alias",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "extern_name" => "Customer",
//                    "alias" => "Attn*",
//                    "alamat" => "Addr",
//                    "kelurahan" => "Kel",
//                    "kecamatan" => "Kec",
//                    "kabupaten" => "Kab",
//                    "propinsi" => "Prop",
//                    "tlp" => "Phone",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Handphone",
//                    "npwp" => "NPWP",
//                    "no_ktp" => "NIK",
//                ),
//                "editPoints" => array(),
//                "optionPoints" => array(
//                    "label" => array(
//                        "lengkap" => 1,
//                        "simple" => 2,
//                        "no detail" => 3,
//                    ),
//                    "labelTitle" => array(
//                        "lengkap" => "semua data delivery address akan ditampilkan.",
//                        "simple" => "hanya attn/pic dan nomor telepon yang akan ditampilkan.",
//                        "no detail" => "tidak ada yang akan ditampilkan.",
//                    ),
//                    "step" => "5",
//                    "hidden" => array(
//                        2 => array(//hidden bernilai 0, list yang perlu hidden
//                            "alamat",
//                            "kelurahan",
//                            "kecamatan",
//                            "kabupaten",
//                            "propinsi",
//                            "npwp",
//                            "no_ktp",
//                        ),
//                        3 => array(//hidden bernilai 0, list yang perlu hidden
//                            "alias",
//                            "alamat",
//                            "kelurahan",
//                            "kecamatan",
//                            "kabupaten",
//                            "propinsi",
//                            "tlp",
//                            "tlp_1",
//                            "tlp_2",
//                            "npwp",
//                            "no_ktp",
//                        ),
//                    ),
//                ),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Detail Pengiriman",
//                "mdlName" => "MdlCustomerAddress",
//                "mdlFilter" => array(
//                    "extern_id=pihakID",
//                ),
//                "key" => "id",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "alias" => "Attn",
//                    "alamat" => "Alamat",
//                    "kecamatan" => "Kec",
//                    "kabupaten" => "Kab",
//                    "propinsi" => "propinsi",
//                    "tlp" => "Tlp",
//                    "tlp_2" => "Handphone",
//                    //                    "npwp" => "NPWP",
//                    //                    "propinsi" =>"",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),
//            "detilSize"       => array(
//                "elementType"  => "dataModel",
//                "inputType"    => "radio",
//                "label"        => "Data ukuran",
//                "mdlName"      => "MdlMeasurement",
//                "mdlFilter"    => array("extern_id=pihakID"),
//                "key"          => "id",
//                "labelSrc"     => "name",
//                "defaultValue" => "ckd",
//                "usedFields"   => array(
//                    "name" => "",
//                ),
//                "editPoints"   => array(1, 2, 3),
//            ),
            //            "tos" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "defaultValue" => "20",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),

            //            "capacity" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
//            "dueDate"         => array(
//                "elementType"  => "dataField",
//                "label"        => "due date",
//                "inputType"    => "date",
//                "defaultValue" => $date->format('Y-m-d'),
//                "editPoints"   => array(1, 2, 3, 4, 5),
//            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "Name",
                    "note" => "Note",
                ),
                "defaultValue" => "defaultPaymentMethod",
                "editPoints" => array(1),
                "kreditLimitValidate" => array(
                    1 => array(
                        "enabled" => true,
                    ),
                ),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        //                        "mdlName" => "MdlBankAccount_in",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "credit" => array(
                    "billingDetails" => array(
                        "elementType" => "dataModel",
                        "elementJoint" => array(
                            "method" => "lookUpJointCustomer",
                            "mdlFilter" => "id=pihakID",
                            "usedFields" => array(
                                "no_ktp" => "nik",
                                "nik" => "nik",
                                "npwp" => "npwp",
                            ),
                        ),
                        "inputType" => "radio",
                        "label" => "Detail Penagihan",
                        "mdlName" => "MdlCustomerBillAddress",
                        "mdlFilter" => array("extern_id=pihakID"),
                        //                "mdlName" => "MdlCustomer_and_pre",
                        //                "mdlFilter" => array("id=pihakID"),
                        "key" => "id",
                        //                "labelSrc" => "alias",
                        "labelSrc" => "alias",
                        "usedFields" => array(
                            "extern_name" => "Customer",
                            "alias" => "Attn*",
                            "alamat" => "Addr",
                            "kelurahan" => "Kel",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "Prop",
                            "tlp" => "Phone",
                            "tlp_1" => "Phone",
                            "tlp_2" => "Handphone",
                            "npwp" => "NPWP",
                            "no_ktp" => "NIK",
                        ),
                        "editPoints" => array(),
                        "optionPoints" => array(
                            "label" => array(
                                "lengkap" => 1,
                                "simple" => 2,
                                "no detail" => 3,
                            ),
                            "labelTitle" => array(
                                "lengkap" => "semua data delivery address akan ditampilkan.",
                                "simple" => "hanya attn/pic dan nomor telepon yang akan ditampilkan.",
                                "no detail" => "tidak ada yang akan ditampilkan.",
                            ),
                            "step" => "5",
                            "hidden" => array(
                                2 => array(//hidden bernilai 0, list yang perlu hidden
                                    "alamat",
                                    "kelurahan",
                                    "kecamatan",
                                    "kabupaten",
                                    "propinsi",
                                    "npwp",
                                    "no_ktp",
                                ),
                                3 => array(//hidden bernilai 0, list yang perlu hidden
                                    "alias",
                                    "alamat",
                                    "kelurahan",
                                    "kecamatan",
                                    "kabupaten",
                                    "propinsi",
                                    "tlp",
                                    "tlp_1",
                                    "tlp_2",
                                    "npwp",
                                    "no_ktp",
                                ),
                            ),
                        ),
                    ),
//                    "top" => array(
//                        "elementType" => "dataField",
//                        "inputType" => "number",
//                        "label" => "term of payment (hari)",
////                        "mdlName"     => "MdlTop",
////                        "mdlFilter"   => array(),
////                        "key"         => "kode",
////                        "labelSrc"    => "nama",
//                        "description" => "",
//                        "defaultValue" => "",
////                        "usedFields"  => array(
////                            "nama" => "",
////                        ),
//                        "editPoints" => array(1,),
//                    ),
                ),
            ),
            "shippingMethod" => array(
                "1" => array(
                    "deliveryDetails" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Detail Pengiriman",
                        "mdlName" => "MdlCustomerAddress",
                        "mdlFilter" => array(
                            "extern_id=pihakID",
                        ),
                        "key" => "id",
                        "labelSrc" => "alias",
                        "usedFields" => array(
                            "alias" => "Attn",
                            "alamat" => "Alamat",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "propinsi",
                            "tlp" => "Tlp",
                            "tlp_2" => "Handphone",
                            //                    "npwp" => "NPWP",
                            //                    "propinsi" =>"",
                        ),
                        "editPoints" => array(1, 2, 3, 4),
                    ),
                    "shippingDate" => array(
                        "elementType" => "dataField",
                        "label" => "rencana tanggal pengiriman",
                        "inputType" => "date",
//                        "defaultValue" => date("d/m/Y"),
                        "defaultValue" => date("Y-m-d"),
                        "editPoints" => array(1),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => true,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "19822",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups" => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
            2 => array(
                "enabled" => true,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "19822",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups" => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
        ),

        "validateMeasurement" => array(
            //            1 => array(
            //                "panjang_gross" => "CKD length (in millimeters)",
            //                "lebar_gross" => "CKD width (in millimeters)",
            //                "tinggi_gross" => "CKD height (in millimeters)",
            //            ),
        ),
        "validateReceiveElement" => array(
//            1 => array(
//                "billingDetails" => array(
//                    "alias" => "Nama Customer harap di isi dengan benar",
//                    "tlp" => "Nomor telepon harap di isi dengan benar",
//                )
//            ),
        ),
        //        "requestCode" => array(
        //            "masterCode" => "581",
        //            "stateCode" => "581r",
        //            "stepNumber" => "1",
        //            "allowMultiSelect" => false,
        //        ),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items3_sum"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),

            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
//            "shippingService" => array(
//                "ongkir_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        'disabled' => "",
//                        "addPoints" => array(1),
//                        "editPoints" => array(4),
//                    ),
//                ),
//                "ongkir_tanpa_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        'disabled' => "",
//                        "addPoints" => array(1, 4),
//                        "editPoints" => array(4),
//                    ),
//                ),
//            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        ),
        "allowedMainEdit" => array("1", "2"),
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "5822",
                "jenis" => "5822",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),

        // "compactHistoryFields" => array(
        //     // "jenis_label" => "activity",
        //     "dtime" => "date",
        //     "suppliers_nama" => "customer",
        //     "nomer" => "receipt number",
        //     "oleh_nama" => "person",
        //
        //     "harga" => "amount",
        //     "disc" => "discount",
        //     "ppn" => "ppn",
        //     "nett2" => "total amount",
        // ),
        "compactHistoryFields" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    // "nomer_top" => "nomer",
                    // "dtime" => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    // "oleh_nama" => "approval",
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
        //        "addMainSource" => array(
        //            6 => array(
        //                "fields" => array(
        //                    "nomer" => "INV",
        //                    "grand_total_ui" => "DPP",
        //                    "new_grand_ppn" => "PPN",
        //                    "dateFaktur" => "Tgl faktur ",
        //                    "eFaktur" => "e-faktur",
        //                ),
        //                "editableFields" => array(
        //                    "eFaktur" => "",
        //                    "dateFaktur" => "",
        //                ),
        //            ),
        //        ),

        "connectTo" => "110",
        "connectoValidate" => array(
            5 => "nilai_credit",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "pusat",
            "place2ID" => "-1",
            "place2Name" => "pusat",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang

            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        //connecto ke pusat jika ada produk yang perlu direpack dipusat untuk penjualan produk komposit
        //        "extConnectTo" => array(
        //            2 => array(
        //                "connecTo" => "773",
        //            ),
        //        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5822spoe",
                "label" => "EDIT SALES PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5822sporj",
                "label" => "REJECT SALES PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "5822sorj",
                "label" => "REJECT SALES ORDER",
            ),
            3 => array(
                "enabled" => true,
                "connectTo" => "5822pkdrj",
                "label" => "REJECT PRE PACKING",
            ),
        ),
        //----
        "autoApprove" => array(
            1 => false,
        ),
        "kreditLimitValidate" => array(
//            1 => array(
//                "enabled" => true,
//            ),
//
            3 => array(
                "enabled" => true,
//                "label" => "Transaksi ini sedang ditindaklanjuti oleh pihak finance.<br>Silahkan konfirmasi ke pihak finance.",
            ),
        ),
        "shopingCartReload" => true,
        //----
        "produkUnitPart" => array(
            "outdoor" => array(
                "outdoor_id" => "outdoor_sku",
            ),
            "indoor" => array(
                "indoor_id_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_sku_4",
            ),
            "heater" => array(
                "heater_id" => "heater_nama",
            ),
            "part" => array(
                "produk_part_id_1" => "produk_part_nama_1",
                "produk_part_id_2" => "produk_part_nama_2",
                "produk_part_id_3" => "produk_part_nama_3",
            ),
        ),
        //----
        "allowNextStepOtorisasi" => array(
            3 => array(
                "pusat" => "disabled",
                "label" => "Penjualan ini dikirim dari DC/Pusat.<br>Cabang tidak perlu kirim.",
            ),
            4 => array(
                "pusat" => "disabled",
                "label" => "Penjualan ini dikirim dari DC/Pusat.<br>Cabang tidak perlu kirim.",
            ),
        ),
        //----
        "deliveryParsial" => array(
            3 => array(
                "enabled" => false,
            ),
        ),
        //----
        "serialNumberValidator" => array(
            3 => array(
                "enabled" => true,
                "source" => "items2",
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",
            ),
        ),
        "autoOtorisasiSesiValidate" => array(
            3 => array(
                "enabled" => true,
            ),
        ),

    ),


    //  config return penjualan dimatikan dulu ada cacat jika diretun jkarena ada pergeseran jurnal ppn
    "982" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "sales return",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "RETURN REQUEST",
                "actionLabel" => "make return request",
                "source" => "",
                "target" => "982r",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "RETURN AUTHORIZATION",
                "actionLabel" => "approve return request",
                "source" => "982r",
                "target" => "982g",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label" => "RETURN PENJUALAN",
                "actionLabel" => "receive & make GRN",
                "source" => "982g",
                "target" => "982",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "GRN made",
                "stateColor" => "#ff7700",
                "stateCaption" => "received by",
            ),
        ),
        "template" => "template/transaksi_nosalesman.html",
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "returned=.0",
            "jenis=.582spd",
            "customers_id=pihakID",
//            "tail_number=.5",
            "cabang_id=placeID",
            // "id<=.123852",//dimatiin karena transaki baru tidak bisa direturn
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectNotaItem/select",
        "editHandlerMethod" => "edit",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakMainValueSrc" => array(// "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "referenceNomer" => "reference number",
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "bruto",
            "disc" => "discount",
            "nett1" => "netto",
            "ppn" => "ppn",
            "nett2" => "grand total",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer" => "receipt number",
                "referenceNomer" => "reference number",
                //            "nomer" => "receipt number",
                "oleh_nama" => "person",
                "harga" => "bruto",
                "disc" => "discount",
                "nett1" => "netto",
                "ppn" => "ppn",
                "nett2" => "grand total",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "referenceNomer" => "reference number",
                "oleh_nama" => "person",
                "harga" => "bruto",
                "disc" => "discount",
                "nett1" => "netto",
                "ppn" => "ppn",
                "nett2" => "grand total",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer_po" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "approval number",
                ),
                "nomer" => "return number",
                "referenceNomer" => "reference number",
                "oleh_nama" => "person",
                "harga" => "bruto",
                "disc" => "discount",
                "nett1" => "netto",
                "ppn" => "ppn",
                "nett2" => "grand total",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
        ),
        "extHistoryKeterangan" => array(
            1 => array(
                "edit" => array(
                    "kolom" => "status_edit",
                    "value" => "1",
                    "labels" => array(
                        "edit_name", "edit_dtime",
                    ),
                    "style" => array(
                        "bgcolor" => "yellow",
                        "color" => "red",
                    ),
                ),
            ),

        ),

        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",

            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "jual",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "produk_nama",
            "produk_kode" => "produk_kode",
            "label" => "produk_label",
            "satuan" => "satuan",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga)",
            2 => "jml*(harga)",
            3 => "jml*(harga)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "new_net2" => "Grand Total",
            ),
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "applets" => array(
            //            "alamat_kirim" => array(
            //                "label" => "alamat kirim",
            //                "mdlName" => "MdlSupplierAddress",
            ////                "mdlFilter" => array("extern_id=pihakID"),
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "description" => "alamat+kelurahan+kecamatan+kabupaten+propinsi+kodepos",
            //            ),
            //            "tos" => array(
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
            //            "capacity" => array(
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
        ),
        "availPayments" => array(
            //            "cash"   => array(
            //                "label"     => "cash",
            //                "valueGate" => "nilai_cash",
            //                "valueSrc"  => "nett",
            //            ),
            //            "credit" => array(
            //                "label"     => "credit",
            //                "valueGate" => "nilai_credit",
            //                "valueSrc"  => "nett",
            //            ),
            "inherit" => array(
                "label" => "inherit",
                "valueGate" => "nilai_inherit",
                "valueSrc" => "grand_total",
            ),
        ),
        "referenceFields" => array(
            "referenceID" => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod" => "pembayaran",
        ),
        "referenceJenisTr" => "582",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMERS DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "reasonDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "REASON DETAILS",
                "mdlName" => "MdlAlasanBatal",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nilai",
                "usedFields" => array(
                    "nilai" => "Alasan",
                ),
                "noValidate" => true,
                "editPoints" => array(1, 2, 3, 4),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "topReferenceTrans" => array(
            "enabled" => true,
            "gate" => array(
                "main" => array(
                    //sumber => target
                    "oleh_id" => "sellerIDOrig",
                    "oleh_nama" => "sellerNameOrig",
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "982re",
                "label" => "EDIT RETURN REQUEST",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "982rrj",
                "label" => "REJECT RETURN REQUEST",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "982grj",
                "label" => "REJECT RETURN APPROVAL",
            ),
        ),
        //----
        "produkUnitPart" => array(
            "outdoor" => array(
                "outdoor_id" => "outdoor_nama",
            ),
            "indoor" => array(
                "indoor_id_1" => "indoor_nama_1",
                "indoor_id_2" => "indoor_nama_2",
                "indoor_id_3" => "indoor_nama_3",
                "indoor_id_4" => "indoor_nama_4",
            ),
            "heater" => array(
                "heater_id" => "heater_nama",
            ),
        ),
    ),
    "9822" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "sales return (sales support mode)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "RETURN REQUEST",
                "actionLabel" => "make return request",
                "source" => "",
                "target" => "9822r",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "SCAN QR/BARCODE",
                "actionLabel" => "simpan",
                "source" => "9822r",
                "target" => "9822g",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "RETURN PENJUALAN",
                "actionLabel" => "receive & make GRN",
                "source" => "9822g",
                "target" => "9822",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "GRN made",
                "stateColor" => "#ff7700",
                "stateCaption" => "received by",
            ),
        ),
        "template" => "template/transaksi_nosalesman.html",
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectorDetailModel" => "MdlProduk",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "returned=.0",
            "jenis=.5822spd",
            "customers_id=pihakID",
//            "tail_number=.5",
            "cabang_id=placeID",
            // "id<=.123852",//dimatiin karena transaki baru tidak bisa direturn
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectNotaItem/select",
        "editHandlerMethod" => "edit",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakMainValueSrc" => array(// "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "referenceNomer" => "reference number",
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "bruto",
            "disc" => "discount",
            "nett1" => "netto",
            "ppn" => "ppn",
            "nett2" => "grand total",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer" => "receipt number",
                "referenceNomer" => "reference number",
                //            "nomer" => "receipt number",
                "oleh_nama" => "person",
                "harga" => "bruto",
                "disc" => "discount",
                "nett1" => "netto",
                "ppn" => "ppn",
                "nett2" => "grand total",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "referenceNomer" => "reference number",
                "oleh_nama" => "person",
                "harga" => "bruto",
                "disc" => "discount",
                "nett1" => "netto",
                "ppn" => "ppn",
                "nett2" => "grand total",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer_po" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "approval number",
                ),
                "nomer" => "return number",
                "referenceNomer" => "reference number",
                "oleh_nama" => "person",
                "harga" => "bruto",
                "disc" => "discount",
                "nett1" => "netto",
                "ppn" => "ppn",
                "nett2" => "grand total",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
        ),
        "extHistoryKeterangan" => array(
            1 => array(
                "edit" => array(
                    "kolom" => "status_edit",
                    "value" => "1",
                    "labels" => array(
                        "edit_name", "edit_dtime",
                    ),
                    "style" => array(
                        "bgcolor" => "yellow",
                        "color" => "red",
                    ),
                ),
            ),

        ),

        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",

            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "jual",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "produk_nama",
            "produk_kode" => "produk_kode",
            "label" => "produk_label",
            "satuan" => "satuan",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),
            3 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga)",
            2 => "jml*(harga)",
            3 => "jml*(harga)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => true,
            5 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "new_net2" => "Grand Total",
            ),
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartSubDetailFields" => array(
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
            ),
            4 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
            ),
        ),

        "applets" => array(
            //            "alamat_kirim" => array(
            //                "label" => "alamat kirim",
            //                "mdlName" => "MdlSupplierAddress",
            ////                "mdlFilter" => array("extern_id=pihakID"),
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "description" => "alamat+kelurahan+kecamatan+kabupaten+propinsi+kodepos",
            //            ),
            //            "tos" => array(
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
            //            "capacity" => array(
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
        ),
        "availPayments" => array(
            //            "cash"   => array(
            //                "label"     => "cash",
            //                "valueGate" => "nilai_cash",
            //                "valueSrc"  => "nett",
            //            ),
            //            "credit" => array(
            //                "label"     => "credit",
            //                "valueGate" => "nilai_credit",
            //                "valueSrc"  => "nett",
            //            ),
            "inherit" => array(
                "label" => "inherit",
                "valueGate" => "nilai_inherit",
                "valueSrc" => "grand_total",
            ),
        ),
        "referenceFields" => array(
            "referenceID" => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod" => "pembayaran",
        ),
        "referenceJenisTr" => "582",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMERS DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
//            "reasonDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "REASON DETAILS",
//                "mdlName" => "MdlAlasanBatal",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "nilai",
//                "usedFields" => array(
//                    "nilai" => "Alasan",
//                ),
//                "noValidate" => true,
//                "editPoints" => array(1, 2, 3, 4),
//            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "topReferenceTrans" => array(
            "enabled" => true,
            "gate" => array(
                "main" => array(
                    //sumber => target
                    "oleh_id" => "sellerIDOrig",
                    "oleh_nama" => "sellerNameOrig",
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9822re",
                "label" => "EDIT RETURN REQUEST",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9822rrj",
                "label" => "REJECT RETURN REQUEST",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "9822grj",
                "label" => "REJECT RETURN APPROVAL",
            ),
        ),
        //----
        "produkUnitPart" => array(
            "outdoor" => array(
                "outdoor_id" => "outdoor_sku",
            ),
            "indoor" => array(
                "indoor_id_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_sku_4",
            ),
            "heater" => array(
                "heater_id" => "heater_nama",
            ),
            "part" => array(
                "produk_part_id_1" => "produk_part_nama_1",
                "produk_part_id_2" => "produk_part_nama_2",
                "produk_part_id_3" => "produk_part_nama_3",
            ),
        ),
    ),
    "1982" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "close/fullfillment Sales Order",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request close/fullfillment",
                "actionLabel" => "make close/fullfillment transaksi",
                "source" => "",
                "target" => "1982",
                "userGroup" => "o_seller",
                "stateLabel" => "request close/fullfillment transaksi",
                "stateColor" => "#dd3300",
                "stateCaption" => "request close/fullfillment transaksi by",
                "isCancelPacking" => true,
            ),
            2 => array(
                "label" => "otorisasi close/fullfillment",
                "actionLabel" => "approve close/fullfillment transaksi",
                "source" => "1982",
                "target" => "1982g",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "packing canceled",
                "stateColor" => "#ff7700",
                "isCancelPacking" => true,
            ),
        ),
        "template" => "template/transaksi_fullfill.html",
        "isDisableMakeTrans" => true,
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "returned=.0",
            "jenis=.582spd",
            "customers_id=pihakID",
            "tail_number=.5",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectNotaItem/select",
        "editHandlerMethod" => "cancel",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            //            "jenis_label"    => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "nomer_top" => "PL Number",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                //            "jenis_label"    => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer" => "request number",
                //                "nomer_top" => "PL Number",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                //            "jenis_label"    => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer" => "approval Number",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        //        "extHistoryKeterangan" => array(
        //            1 => array(
        //                "edit" => array(
        //                    "kolom" => "status_edit",
        //                    "value" => "1",
        //                    "labels" => array(
        //                        "edit_name", "edit_dtime",
        //                    ),
        //                    "style" => array(
        //                        "bgcolor" => "yellow",
        //                        "color" => "red",
        //                    ),
        //                ),
        //            ),
        //
        //        ),
        "compactHistoryFields" => array(
            //            "jenis_label"    => "activity",
            "dtime" => "date",
            //            "suppliers_nama" => "customer",
            //            "nomer"          => "receipt number",
            "oleh_nama" => "person",
            //            "harga"          => "amount",
            //            "disc"           => "discount",
            //            "ppn"            => "ppn",
            //            "nett2" => "total amount",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "jual",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "produk_nama",
            "produk_kode" => "produk_kode",
            "label" => "produk_label",
            "satuan" => "satuan",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => "Description",
                "produk_kode" => "Product No.",
                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut SO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>SO</span>",
                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH TERKIRIM</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>shipped</span>",
                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
                "satuan" => "uom",
                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => "Description",
                "produk_kode" => "Product No.",
                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut SO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>SO</span>",
                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH TERKIRIM</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>shipped</span>",
                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
                "satuan" => "uom",
                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "harga"        => "Price",
                //                "disc_percent" => "disc (%)",
                //                "stok" => "stok",
                //                "disc"         => "disc (IDR)",
                //                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                //                "harga"        => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc"         => "disc (IDR)",
                //                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                //                "harga"        => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc"         => "disc (IDR)",
                //                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "jml*(harga+ppn)",
            //            2 => "jml*(harga+ppn)",
            //            3 => "jml*(harga+ppn)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "harga" => "Amount",
                //                "disc"  => "Disc",
                //                "ppn"   => "VAT",
                //                "nett2" => "Grand Total",
            ),
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartFieldValidators" => array(
            //            "jml"   => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "applets" => array(
            //            "alamat_kirim" => array(
            //                "label" => "alamat kirim",
            //                "mdlName" => "MdlSupplierAddress",
            ////                "mdlFilter" => array("extern_id=pihakID"),
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "description" => "alamat+kelurahan+kecamatan+kabupaten+propinsi+kodepos",
            //            ),
            //            "tos" => array(
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
            //            "capacity" => array(
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
        ),
        "availPayments" => array(
            //            "cash"   => array(
            //                "label"     => "cash",
            //                "valueGate" => "nilai_cash",
            //                "valueSrc"  => "nett",
            //            ),
            //            "credit" => array(
            //                "label"     => "credit",
            //                "valueGate" => "nilai_credit",
            //                "valueSrc"  => "nett",
            //            ),
            "inherit" => array(
                "label" => "inherit",
                "valueGate" => "nilai_inherit",
                "valueSrc" => "grand_total",
            ),
        ),
        "referenceFields" => array(
            "referenceID" => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod" => "pembayaran",
        ),
        "referenceJenisTr" => "582",
        "itemAddConfig" => false,
        "receiptElements" => array(
            "transaksiDatas" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Sales Order",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array(
                    "id=currentID",
                ),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "Nomer",
                    "oleh_nama" => "BY",
                    "id_top" => "Pre SO",
                    "nomer_top" => "Nomer Pre SO",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMERS DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "reasonDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "REASON DETAILS",
                "mdlName" => "MdlAlasanBatal",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nilai",
                "usedFields" => array(
                    "nilai" => "Alasan",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        "xShipmentConfig" => array(
            2 => array(
                "enabled" => false,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "1982",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",

                "shipment" => "582spd",
                "packing" => "582pkd",
                "cancel" => "1982",
                //                "allowedGroups" => array(
                //                    "o_gudang", "o_gudang_spv", "o_finance"
                //                ),
            ),
        ),
        "referenceRevert" => array(
            "enabled" => true,
            "referenceID" => "transaksiDatas", // ambil dari gerbang registry main
            // untuk mengembalikan valid_qty, jadi valid_qty + cancel_qty (sesuai nota)
            "items" => array(
                "valid_qty" => "valid_qty",
            ),
        ),
        "previewCtr" => "Create",
        "descriptionValidate" => array(
            "enabled" => true,
            "key" => array(
                "description" => "detail alasan harus diisikan di formulir deskripsi.",
            ),
        ),
        //----
        "connectToEdit" => array(
            //            1 => array(
            //                "enabled" => true,
            //                "connectTo" => "982re",
            //                "label" => "EDIT RETURN REQUEST",
            //            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1982rj",
                "label" => "REJECT CLOSE/FULLFILLMENT REQUEST",
            ),
        ),
        //----
        "produkUnitPart" => array(
            "outdoor" => array(
                "outdoor_id" => "outdoor_nama",
            ),
            "indoor" => array(
                "indoor_id_1" => "indoor_nama_1",
                "indoor_id_2" => "indoor_nama_2",
                "indoor_id_3" => "indoor_nama_3",
                "indoor_id_4" => "indoor_nama_4",
            ),
            "heater" => array(
                "heater_id" => "heater_nama",
            ),
        ),
    ),
    "19822" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "close/fullfillment Sales Order",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request close/fullfillment",
                "actionLabel" => "make close/fullfillment transaksi",
                "source" => "",
                "target" => "19822",
                "userGroup" => "o_seller",
                "stateLabel" => "request close/fullfillment transaksi",
                "stateColor" => "#dd3300",
                "stateCaption" => "request close/fullfillment transaksi by",
                "isCancelPacking" => true,
            ),
            2 => array(
                "label" => "otorisasi close/fullfillment",
                "actionLabel" => "approve close/fullfillment transaksi",
                "source" => "19822",
                "target" => "19822g",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "packing canceled",
                "stateColor" => "#ff7700",
                "isCancelPacking" => true,
            ),
        ),
        "template" => "template/transaksi_fullfill.html",
        "isDisableMakeTrans" => true,
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "returned=.0",
            "jenis=.582spd",
            "customers_id=pihakID",
            "tail_number=.5",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectNotaItem/select",
        "editHandlerMethod" => "cancel",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            //            "jenis_label"    => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "nomer",
//            "nomer_top" => "PL Number",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                //            "jenis_label"    => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer" => "request number",
                //                "nomer_top" => "PL Number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                //            "jenis_label"    => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer" => "approval Number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //        "extHistoryKeterangan" => array(
        //            1 => array(
        //                "edit" => array(
        //                    "kolom" => "status_edit",
        //                    "value" => "1",
        //                    "labels" => array(
        //                        "edit_name", "edit_dtime",
        //                    ),
        //                    "style" => array(
        //                        "bgcolor" => "yellow",
        //                        "color" => "red",
        //                    ),
        //                ),
        //            ),
        //
        //        ),
        "compactHistoryFields" => array(
            //            "jenis_label"    => "activity",
            "dtime" => "date",
            //            "suppliers_nama" => "customer",
            //            "nomer"          => "receipt number",
            "oleh_nama" => "person",
            //            "harga"          => "amount",
            //            "disc"           => "discount",
            //            "ppn"            => "ppn",
            //            "nett2" => "total amount",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "jual",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "produk_nama",
            "produk_kode" => "produk_kode",
            "label" => "produk_label",
            "satuan" => "satuan",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => "Description",
                "produk_kode" => "Product No.",
                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut SO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>SO</span>",
                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH TERKIRIM</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>shipped</span>",
                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
                "satuan" => "uom",
                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => "Description",
                "produk_kode" => "Product No.",
                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut SO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>SO</span>",
                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH TERKIRIM</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>shipped</span>",
                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
                "satuan" => "uom",
                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "harga"        => "Price",
                //                "disc_percent" => "disc (%)",
                //                "stok" => "stok",
                //                "disc"         => "disc (IDR)",
                //                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                //                "harga"        => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc"         => "disc (IDR)",
                //                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                //                "harga"        => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc"         => "disc (IDR)",
                //                "ppn"          => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "jml*(harga+ppn)",
            //            2 => "jml*(harga+ppn)",
            //            3 => "jml*(harga+ppn)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "harga" => "Amount",
                //                "disc"  => "Disc",
                //                "ppn"   => "VAT",
                //                "nett2" => "Grand Total",
            ),
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartFieldValidators" => array(
            //            "jml"   => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "applets" => array(
            //            "alamat_kirim" => array(
            //                "label" => "alamat kirim",
            //                "mdlName" => "MdlSupplierAddress",
            ////                "mdlFilter" => array("extern_id=pihakID"),
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "description" => "alamat+kelurahan+kecamatan+kabupaten+propinsi+kodepos",
            //            ),
            //            "tos" => array(
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
            //            "capacity" => array(
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
        ),
        "availPayments" => array(
            //            "cash"   => array(
            //                "label"     => "cash",
            //                "valueGate" => "nilai_cash",
            //                "valueSrc"  => "nett",
            //            ),
            //            "credit" => array(
            //                "label"     => "credit",
            //                "valueGate" => "nilai_credit",
            //                "valueSrc"  => "nett",
            //            ),
            "inherit" => array(
                "label" => "inherit",
                "valueGate" => "nilai_inherit",
                "valueSrc" => "grand_total",
            ),
        ),
        "referenceFields" => array(
            "referenceID" => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod" => "pembayaran",
        ),
        "referenceJenisTr" => "582",
        "itemAddConfig" => false,
        "receiptElements" => array(
            "transaksiDatas" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Sales Order",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array(
                    "id=currentID",
                ),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "Nomer",
                    "oleh_nama" => "BY",
                    "id_top" => "Pre SO",
                    "nomer_top" => "Nomer Pre SO",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMERS DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
//            "reasonDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "REASON DETAILS",
//                "mdlName" => "MdlAlasanBatal",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "nilai",
//                "usedFields" => array(
//                    "nilai" => "Alasan",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        "xShipmentConfig" => array(
            2 => array(
                "enabled" => false,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "19822",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",

                "shipment" => "5822spd",
                "packing" => "5822pkd",
                "cancel" => "19822",
                //                "allowedGroups" => array(
                //                    "o_gudang", "o_gudang_spv", "o_finance"
                //                ),
            ),
        ),
        "referenceRevert" => array(
            "enabled" => true,
            "referenceID" => "transaksiDatas", // ambil dari gerbang registry main
            // untuk mengembalikan valid_qty, jadi valid_qty + cancel_qty (sesuai nota)
            "items" => array(
                "valid_qty" => "valid_qty",
            ),
        ),
        "previewCtr" => "Create",
        "descriptionValidate" => array(
            "enabled" => true,
            "key" => array(
                "description" => "detail alasan harus diisikan di formulir deskripsi.",
            ),
        ),
        //----
        "connectToEdit" => array(
            //            1 => array(
            //                "enabled" => true,
            //                "connectTo" => "982re",
            //                "label" => "EDIT RETURN REQUEST",
            //            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "19822rj",
                "label" => "REJECT CLOSE/FULLFILLMENT REQUEST",
            ),
        ),
        //----
        "produkUnitPart" => array(
            "outdoor" => array(
                "outdoor_id" => "outdoor_nama",
            ),
            "indoor" => array(
                "indoor_id_1" => "indoor_nama_1",
                "indoor_id_2" => "indoor_nama_2",
                "indoor_id_3" => "indoor_nama_3",
                "indoor_id_4" => "indoor_nama_4",
            ),
            "heater" => array(
                "heater_id" => "heater_nama",
            ),
        ),
    ),
    //export dioffkan belum suport CLI
    "382" => array(
        "icon" => "fa fa-opencart",
        "label" => "international sales",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "SALES PRE ORDER",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "382spo",
                "userGroup" => "o_export",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "SALES ORDER",
                "actionLabel" => "approve order",
                "source" => "382spo",
                "target" => "382so",
                "userGroup" => "o_export_spv",
                "stateLabel" => "ordered",
                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optStateLabel"    => "pending disc. approval",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
            ),
            3 => array(
                "label" => "PRE PACKING",
                "actionLabel" => "process packing",
                "source" => "382so",
                "target" => "382pkd", // packed
                "userGroup" => "o_export_spv",
                "stateLabel" => "packed",
                "stateColor" => "#009900",
                "stateCaption" => "packed by",
                "allowEdit" => true,
            ),
            4 => array(
                "label" => "EXPORT SHIPMENT",
                "actionLabel" => "process shipment",
                "source" => "382pkd",
                "target" => "382spd", // shipped
                "userGroup" => "o_export_spv",
                "stateLabel" => "shipped",
                "stateColor" => "#009900",
                "stateCaption" => "shipped by",

            ),
            5 => array(
                "label" => "I N V O I C E",
                "actionLabel" => "create invoice",
                "source" => "382spd",
                "target" => "382", // invoice
                "userGroup" => "o_export_spv",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "completed by",
                "allowJoin" => true,
            ),
        ),
        "template" => "template/transaksi_nosalesman.html",
        "selectorModel" => "MdlProduk2",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array("jual", "ppv", "disc"),
            "key_label" => array(
                "jual" => "harga",
                "ppv" => "ppv",
                "disc" => "disc",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "no_part",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        // "pihakModel" => "MdlCustomer",
        "pihakModel" => "MdlCustomerExport",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakFilters" => array(
            "country<>.ID"
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "nomer_pl" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "PL number",
            ),
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),

        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_nvalas" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_nvalas" => "tool",
            ),
            3 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                //                "review_details" =>"review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            4 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                //                "review_details" =>"review",
                "nomer_top" => "SO number",
                "nomer" => "PL number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            5 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                //                "review_details" =>"review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_nvalas" => "tool",
            ),

        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(
                "print_label" => "nomer",
            ),
            5 => array(
                "print_label" => "nomer",
            ),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "produk_kode" => "part no",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "produk_kode" => "part no",
                //                "stok_center" => "stok dc",
                //                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "satuan",
            ),

            3 => array(
                "nama" => "item name",
                "produk_kode" => "part no",
                "max_jml" => "SO",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            4 => array(
                "nama" => "item name",
                "produk_kode" => "part no",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            5 => array(
                "nama" => "item name",
                "produk_kode" => "part no",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",


            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "volume" => "volume",
            "berat" => "berat",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
        ),
        "shoppingCartNumFields" => array(
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
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc_valas",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                "disc_percent",
                "disc_valas",
            ),

            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc_valas",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc_valas}').value=((parseFloat(this.value)*parseFloat(removeCommas(document.getElementById('{valas_nilai}').innerHTML)))/100)",
                "disc_valas" => "document.getElementById('{disc_percent}').value=((parseFloat(this.value)/parseFloat(removeCommas(document.getElementById('{valas_nilai}').innerHTML)))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
            "valas_nilai" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-dics_valas)",
            2 => "jml*(harga-dics_valas)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-dics_valas)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => true,
        ),
        "shoppingCartRowOptionalValidators" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                //                "ongkir_tanpa_ppn_by_company" => array(
                //                    "shipping_service" => "ongkir",
                //
                //                ),
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "disc_valas" => "disc",
                "nett2_valas" => "total",
            ),
            2 => array(
                "valas_nilai" => "amount",
                "disc_valas" => "disc",
                "nett2_valas" => "total",
                "ongkir" => "shipping service",
                "grand_total_valas" => "grand total",
            ),
            5 => array(
                "valas_nilai" => "amount",
                "disc_valas" => "disc",
                "nett2_valas" => "total",
                "ongkir" => "shipping service",
                "grand_total_valas" => "grand total",
            ),
        ),
        "receiptMesurementRows" => array(
            "cbu" => array(
                "volume" => array(
                    "volume_new" => "conv_mmc_mc",
                ),
                "berat" => array(
                    "berat_new" => "conv_g_kg",
                ),
            ),
            "ckd" => array(
                "volume_gross" => array(
                    "volume_new" => "conv_mmc_mc",
                ),
                "berat_gross" => array(
                    "berat_new" => "conv_g_kg",
                ),
            ),
            "mesurement" => array(
                "cbu_cbm" => array(
                    "label" => "cbu cbm",
                    "value" => "volume",
                    "helper" => "conv_mmc_mc",
                ),
                "cbu_kg" => array(
                    "label" => "cbu(kg)",
                    "value" => "berat",
                    "helper" => "conv_g_kg",
                ),
                "ckd_cbm" => array(
                    "label" => "ckd cbm",
                    "value" => "volume_gross",
                    "helper" => "conv_mmc_mc",
                ),
                "ckd_kg" => array(
                    "label" => "ckd (kg)",
                    "value" => "berat_gross",
                    "helper" => "conv_g_kg",
                ),
            ),
            "allowView" => array("1", "2", "3", "4"),
        ),
        "receiptElements" => array(
            "discountMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "discount method",
                "mdlName" => "MdlDiskonMethod",
                "key" => "id",
                "defaultValue" => "item",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    "item" => "ReComDiscItemValas",
                    "customer" => "ReComDiscCustomerValas",
                ),
            ),
            "shippingService" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "shipping service",
                "mdlName" => "MdlOngkir",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "description" => "",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "valasDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "valas",
                "mdlName" => "MdlCurrency",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "description" => "",
                "usedFields" => array(
                    "nama" => "currency",
                    "exchange" => "exchange rate",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),

            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "customer details",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                    "alamat_1" => "address",
                    "country_label" => "country",
                    // "country" => "country",
                    "tlp_1" => "phone",
                    "contact_person" => "attn",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "billingDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "BILLING DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "address",
                    // "kecamatan" => "kecamatan",
                    // "kabupaten" => "kabupaten",
                    // "propinsi" => "propinsi",
                    "country_label" => "country",
                    "tlp_1" => "Phone",
                    "contact_person" => "attn",
                    // "tlp_2" => "Handphone",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "delivery details",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "alias" => "ATTN",
                    "alamat" => "address",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi" => "propinsi",
                    "country" => "country",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "tos" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "term of shipment",
                "mdlName" => "MdlTos",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "description" => "",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "shippingDate" => array(
                "elementType" => "dataField",
                //                "inputType" => "combo",
                "label" => "shipping date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
            "capacity" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "capacity",
                "mdlName" => "MdlCapacity",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            //            "inWords",
            //            "dueDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "due date",
            //                "inputType" => "date",
            //                "defaultValue" => $date->format('Y-m-d'),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),

        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "debit_card" => array(
                    "debit_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "debit account",
                        "mdlName" => "MdlBankAccount",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),

                "credit_card" => array(
                    "credit_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "credit account",
                        "mdlName" => "MdlCreditCard",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),

            ),
            "credit_account" => array(
                "visa_card" => array(
                    "card_number" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "card number",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "valid trough",
                        "inputType" => "date",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                    "card_name" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "name on card",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),


                ),
                "master_card" => array(
                    "card_number" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "card number",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "valid trough",
                        "inputType" => "date",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                    "card_name" => array(
                        "elementType" => "dataField",
                        "inputType" => "combo",
                        "label" => "name on card",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1,),
                    ),
                ),

            ),

        ),
        "relativeOptions" => array(
            //            "paymentMethod" => array(
            //                "credit" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_holding",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                ),
            //                "cash" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_holding",
            //                        ),
            //                        "addPoints" => array(2),
            //                    ),
            //
            //                ),
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            //                        "defaultValue" => "nett2",
            //                        "minValue" => "nett2",
            //                        "maxValue" => "nett2",
            //                        "auth" => array(
            //                            "groupID" => "c_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "c_holding",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //
            //                ),
            //
            //            ),
        ),
        "requestCode" => array(
            "masterCode" => "382",
            "stateCode" => "382r",
            "stepNumber" => "1",
            "allowMultiSelect" => false,
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "tableIn_detail_values" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                    "tableIn_detail_values" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "tableIn_detail_values" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                    "tableIn_detail_values" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "tableIn_detail_values" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                    "tableIn_detail_values" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "tableIn_detail_values" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                    "tableIn_detail_values" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
        ),
        "validationRules" => array(
            //tambahin step brooo
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled' => "",
                        "addPoints" => array(1),
                        "editPoints" => array(4),
                    ),
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled' => "",
                        "addPoints" => array(1, 4),
                        "editPoints" => array(4),
                    ),
                ),
                //                "ongkir_tanpa_ppn_by_company" =>array(
                //                    "shipping_service" => array(
                //                        "label" => "shipping service",
                //                        "defaultValue" => "",
                //                        "maxValue" => "",
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                //                        'disabled' => "",
                //                        "addPoints" => array(1,),
                //                    ),
                //
                //                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "allowedMainEdit" => array("1"),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "382spoe",
                "label" => "EDIT SALES PRE ORDER EXPORT",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "382sporj",
                "label" => "REJECT SALES PRE ORDER EXPORT",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "382sorj",
                "label" => "REJECT SALES ORDER EXPORT",
            ),
            3 => array(
                "enabled" => true,
                "connectTo" => "382pkdrj",
                "label" => "REJECT PRE PACKING EXPORT",
            ),
        ),
    ),
    // config penjualan paket
    "1582" => array(
        "icon" => "fa fa-opencart",
        "label" => "sales (package)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "SALES PRE ORDER PACKED",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "1582spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            //            2 => array(
            //                "label" => "SALES ORDER",
            //                "actionLabel" => "approve order",
            //                "source" => "1582spo",
            //                "target" => "1582so",
            //                "userGroup" => "o_seller_spv",
            //                "stateLabel" => "approved",
            //                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
            //                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
            //                //				"optStateLabel"    => "pending disc. approval",
            //                "stateColor" => "#ff7700",
            //                "stateCaption" => "Acknowledge by",
            //                "allowEdit" => true,
            //            ),
            //
            //            3 => array(
            //                "label" => "PRE PACKING",
            //                "actionLabel" => "process packing",
            //                "source" => "1582so",
            //                "target" => "1582pkd", // packed
            //                "userGroup" => "o_gudang",
            //                "stateLabel" => "packed",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "Prepared",
            //                "allowEdit" => true,
            //                "paymentSrc" => array(
            //                    "enabled" => true,
            //                    "filter" => array(
            //                        "label='incoming cash'",
            //                    ),
            //                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
            //                    "label" => "Uang muka belum diterima. Segera hubungi pihak Finance.",
            //                ),
            //            ),
            //            4 => array(
            //                "label" => "PACKING LIST",
            //                "actionLabel" => "process shipment",
            //                "source" => "1582pkd",
            //                "target" => "1582spd", // shipped
            //                "userGroup" => "o_gudang",
            //                "stateLabel" => "shipped",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "shipped by",
            //            ),
            //            5 => array(
            //                "label" => "I N V O I C E",
            //                "actionLabel" => "create invoice",
            //                "source" => "1582spd",
            //                "target" => "1582", // invoice
            //                "userGroup" => "o_finance",
            //                "stateLabel" => "completed",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "completed by",
            //                "allowJoin" => true,
            //                "allowEdit" => true,
            //            ),
        ),

        "shoppingCartMeasurement" => array(
            3 => true,
            4 => true,
        ),
        "template" => "template/transaksi_nosalesman.html",
        "selectorModel" => "MdlProdukPaket",
        "selectorSrcModel" => "MdlProdukPaket",
        "selectedPrice" => array(
            "model" => "MdlHargaProdukPaket",
            "label" => array("jual", "ppv", "disc", "disc_percent"),
            "key_label" => array(
                "jual" => "harga",
                "ppv" => "ppv",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
            ),
            "mainSrc" => "jual",
        ),
        "selectedPriceItems" => array(
            "model" => "MdlHargaProduk",
            "label" => array("jual", "ppv", "disc", "disc_percent"),
            "key_label" => array(
                "jual" => "harga",
                "ppv" => "ppv",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            //            "nama", "satuan",// "jumlah"
            //            "keterangan", "kode", "satuan",// "jumlah"
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProductPaket/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            // "jual" => "amount",
            "harga" => "bruto",
            "disc" => "discount",
            "ppn" => "ppn",
            "new_net3" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                // "nett1" => "sub amount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            3 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            4 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer" => "PL number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "jual" => "bruto",
                // "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(
                "print_label" => "nomer",
            ),
            5 => array(
                "print_label" => "nomer",
            ),
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                //                "stok" => "stok",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
                "jml" => "qty",
                "satuan" => "uom",
            ),

            3 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            5 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "ppn",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",

            "volume" => "volume",
            "berat" => "berat",
            "lebar" => "lebar",
            "tinggi" => "tinggi",
            "panjang" => "panjang",
        ),
        "shoppingCartFieldSrc2" => array(
            "nama" => "nama",
            "kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            // "ppn" => "harga*(10/100)",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            //            "nett1" =>"jml*harga",

            "volume" => "volume",
            "berat" => "berat",
            "lebar" => "lebar",
            "tinggi" => "tinggi",
            "panjang" => "panjang",
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item packed name",
                "kode" => "item packed code",
                "stok_center" => "stok dc",
                "stok" => "stok",
                "jml" => "qty",
                "harga_ori" => "price(bruto)",
                "disc_percent" => "disc(%)",
                "disc" => "disc nilai",
                "premi_percent" => "premi%",
                "premi" => "premi",
                // "ppn" => "vat",
                "jual" => "price(nett)"
                //                "total_price" => "total price",
                //                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item packed name",
                "kode" => "item packed code",
                "stok" => "stock",
                "jml" => "qty",
                "harga" => "price(bruto)",
                "disc_persent" => "disc(%)",
                "disc_value" => "disc nilai",
                "jual" => "price(nett)"
                //                "total_price" => "total price",
                //                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item packed name",
                "kode" => "item packed code",
                "stok" => "stock",
                "jml" => "qty",
                "harga" => "price(bruto)",
                "disc_persent" => "disc(%)",
                "disc_value" => "disc nilai",
                "jual" => "price(nett)"
                //                "total_price" => "total price",
                //                "satuan" => "satuan",
            ),
            4 => array(
                "nama" => "item packed name",
                "kode" => "item packed code",
                "stok" => "stock",
                "jml" => "qty",
                "harga" => "price(bruto)",
                "disc_persent" => "disc(%)",
                "disc_value" => "disc nilai",
                "jual" => "price(nett)"
                //                "total_price" => "total price",
                //                "satuan" => "satuan",
            ),
            5 => array(
                "nama" => "item packed name",
                "kode" => "item packed code",
                "stok" => "stock",
                "jml" => "qty",
                "harga" => "price(bruto)",
                "disc_persent" => "disc(%)",
                "disc_value" => "disc nilai",
                "jual" => "price(nett)"
                //                "total_price" => "total price",
                //                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "harga_disc" => "Netto",
                // "premi" => "disc (%)",
                // "premi_percent" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                //                "stok" => "stok",
                "harga" => "price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
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
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                //                 "disc_value",
                "disc_percent",
                "disc",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=addCommas(((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))/100))",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",

        ),
        "shoppingCartRowOptionalValidators" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                //                "ongkir_tanpa_ppn_by_company" => array(
                //                    "shipping_service" => "ongkir",
                //
                //                ),
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga+premi-disc)",//nett2
            2 => "jml*(harga+premi-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)",
        ),

        "shoppingCartHideSubamount" => array(
            1 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga_ori" => "total amount bruto",
                "disc" => "disc",
                "premi" => "premi",
                "ongkir_ui" => "shipping service",
                "add_diskon" => "diskon tambahan",
                "grand_total_ui" => "total amount netto",
                "grand_ppn" => "vat",
                "new_net3" => "grand total",
            ),
            2 => array(
                // "harga" => "amount",
                "harga_ori" => "total amount bruto",
                "disc" => "disc",
                "ongkir_ui" => "shipping service",
                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "total amount",
                //                "grand_total_ui" => "total amount",
                "ppn_out_bulat" => "vat",
                //                "grand_ppn" => "vat",
                //                "new_net3" => "grand total",
                "grand_pembulatan" => "grand total",
            ),
            5 => array(
                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Total Amount",
                "grand_ppn" => "VAT",
                //                "tagihan_ui" => "Grand Total",
                "new_net3" => "Grand Total",
            ),
        ),

        //        "shoppingCartPairedItemRecorder" => "recordPairedItem",
        "shoppingCartPairedItem" => array(
            "targetGateName" => "items2_sum",
        ),

        "receiptMesurementRows" => array(
            "cbu" => array(
                "volume" => array(
                    "volume_new" => "conv_mmc_mc",
                ),
                "berat" => array(
                    "berat_new" => "conv_g_kg",
                ),
            ),
            "ckd" => array(
                "volume_gross" => array(
                    "volume_new" => "conv_mmc_mc",
                ),
                "berat_gross" => array(
                    "berat_new" => "conv_g_kg",
                ),
            ),
            "mesurement" => array(
                "cbu_cbm" => array(
                    "label" => "cbu cbm",
                    "value" => "volume",
                    "helper" => "conv_mmc_mc",
                ),
                "cbu_kg" => array(
                    "label" => "cbu(kg)",
                    "value" => "berat",
                    "helper" => "conv_g_kg",
                ),
                "ckd_cbm" => array(
                    "label" => "ckd cbm",
                    "value" => "volume_gross",
                    "helper" => "conv_mmc_mc",
                ),
                "ckd_kg" => array(
                    "label" => "ckd (kg)",
                    "value" => "berat_gross",
                    "helper" => "conv_g_kg",
                ),
            ),
            "allowView" => array("1", "2", "3", "4"),
        ),
        "receiptElements" => array(
            "discountMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "discount method",
                "mdlName" => "MdlDiskonMethod",
                "key" => "id",
                "defaultValue" => "item",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    "item" => "ReComDiscItem",
                    "customer" => "ReComDiscCustomer",
                ),
            ),
            "shippingService" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "shipping service",
                "mdlName" => "MdlOngkir",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "description" => "",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "billingDetails" => array(
                "elementType" => "dataModel",
                "elementJoint" => array(
                    "method" => "lookUpJointCustomer",
                    "mdlFilter" => "id=pihakID",
                    "usedFields" => array(
                        "no_ktp" => "nik",
                        "nik" => "nik",
                        "npwp" => "npwp",
                    ),
                ),
                "inputType" => "radio",
                "label" => "BILLING DETAILS",
                "mdlName" => "MdlCustomerBillAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                //                "mdlName" => "MdlCustomer_and_pre",
                //                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                //                "labelSrc" => "alias",
                "labelSrc" => "alias",
                "usedFields" => array(
                    //                    "alias" => "Name",
                    "alias" => "Name",
                    "alamat" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "NIK",
                ),
                "editPoints" => array("4", "5"),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY DETAILS",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Address",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Phone",
                    "tlp_2" => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "detilSize" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Data ukuran",
                "mdlName" => "MdlMeasurement",
                "mdlFilter" => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "defaultValue" => "ckd",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "tos" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "term of shipment",
                "mdlName" => "MdlTos",
                "mdlFilter" => array(),
                "key" => "id",
                "defaultValue" => "20",
                "labelSrc" => "nama",
                "description" => "",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "shippingDate" => array(
                "elementType" => "dataField",
                // "inputType" => "combo",
                "label" => "shipping date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                //                "editPoints" => array(1),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
            "capacity" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "capacity",
                "mdlName" => "MdlCapacity",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "dueDate" => array(
                "elementType" => "dataField",
                "label" => "due date",
                "inputType" => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            //            "paymentMethod" => array(
            //                "credit" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_holding",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                ),
            //                "cash" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_holding",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                ),
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            //                        "defaultValue" => "nett2",
            //                        "minValue" => "nett2",
            //                        "maxValue" => "nett2",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_admin",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //
            //                ),
            //
            //            ),
        ),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "_validateMeasurement" => array(
            1 => array(
                "panjang_gross" => "CKD length (in millimeters)",
                "lebar_gross" => "CKD width (in millimeters)",
                "tinggi_gross" => "CKD height (in millimeters)",
            ),
        ),
        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        "componentsAss" => array(
            "model" => "MdlProdukPkgKomposisi",
            "modelSrc" => "MdlProduk",
        ),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "",
                        "addPoints" => array(1),
                        "editPoints" => array(4),
                    ),
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "",
                        "addPoints" => array(1, 4),
                        "editPoints" => array(4),
                    ),
                ),
                //                "ongkir_tanpa_ppn_by_company" =>array(
                //                    "shipping_service" => array(
                //                        "label" => "shipping service",
                //                        "defaultValue" => "",
                //                        "maxValue" => "",
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                //                        'disabled' => "",
                //                        "addPoints" => array(1,),
                //                    ),
                //
                //                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "allowedMainEdit" => array("1"),
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "1582",
                "jenis" => "1582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "connectTo" => "582",

        "clonerTransaction" => array(
            1 => array(
                "main" => array(
                    "cloner" => true,
                ),
                "itemToMaster" => array(
                    "nama" => "transaksi_jenis2_label",
                    "produk_kode" => "transaksi_jenis2_kode",
                    "sub_harga" => "transaksi_jenis2_value",
                    //"sub_ppn" => "transaksi_jenis2_value_ppn",
                    //                    "sub_harga_nppn"=>"transaksi_jenis2_value_nppn",
                    //                    "transaksi_jenis" =>"paket",
                ),
                "staticItemToMaster" => array(
                    "transaksi_jenis2" => "paket",
                ),
                "details" => array(
                    "harga" => "harga",
                    // "jual_nppn" => "jual_nppn",
                    "hpp" => "hpp",
                    "disc" => "disc",
                    // "ppn" => "ppn",
                    "harga1" => "harga1",
                    "harga_nett1" => "harga_nett1",
                    "harga2" => "harga2",
                    "harga_nett2" => "harga_nett2",
                ),
                "resetGate" => array(
                    "items2",
                    "items2_sum",
                    "receiptSumFields2",
                    "receiptDetailFields2",
                ),
            ),

        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1582spoe",
                "label" => "EDIT SALES PRE ORDER PACKED",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1582sporj",
                "label" => "REJECT SALES PRE ORDER PACKED",
            ),
        ),
    ),

    //penjualan jasa
    "584" => array(
        "icon" => "fa fa-opencart",
        "label" => "service sales",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "SALES PRE ORDER",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "584spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "SALES ORDER",
                "actionLabel" => "approve order",
                "source" => "584spo",
                "target" => "584so",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
            ),
            3 => array(
                "label" => "I N V O I C E",
                "actionLabel" => "create invoice",
                "source" => "584so",
                "target" => "584", // invoice
                "userGroup" => "o_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "completed by",
                "allowJoin" => true,
                "allowEdit" => true,
            ),
        ),

        "template" => "template/transaksi_nosalesman.html",
        "selectorModel" => "MdlProdukJasa",
        "selectorSrcModel" => "MdlProdukJasa",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual", "ppv", "disc", "disc_percent"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //                "ppv" => "ppv",
            //                "disc" => "disc",
            //                "disc_percent" => "disc (%)",
            //            ),
            //            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            //            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                // "nett1" => "sub amount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            3 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            4 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer" => "PL number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "jual" => "amount",
                // "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
        ),

        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(
                "print_label" => "nomer",
            ),
            5 => array(
                "print_label" => "nomer",
            ),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
            ),

            3 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "max_jml" => "SO",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
        ),
        "shoppingCartNumFields" => array(
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
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "produk_ord_jml",
                "disc_percent",
                "disc",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                "harga",
                //                "disc_percent",
                //                "disc",
            ),
            3 => array(
                //                "harga",
                //                "jml",
                //                "produk_ord_jml",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",

        ),
        "shoppingCartRowOptionalValidators" => array(),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            //            1 => "jml*(harga-disc)",//nett2
            //            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc+ppn)",
            //            5 => "jml*(harga-disc)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => true,
            5 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "Net amount",
                "grand_ppn" => "PPN/vat",
                "new_net3" => "total amount",
                "pph_net_23" => "pph 23**",
                "new_net4" => "Grand Total"
            ),
            2 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "Net amount",
                "grand_ppn" => "PPN/vat",
                "new_net3" => "total amount",
                "pph_net_23" => "pph 23",
                "new_net4" => "Grand Total"
            ),
            3 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "Net amount",
                "grand_ppn" => "PPN/vat",
                "new_net3" => "total amount",
                "pph_net_23" => "pph 23",
                "new_net4" => "Grand Total"
            ),
        ),
        "receiptMesurementRows" => array(),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "receiptElements" => array(
            //            "discountMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "discount method",
            //                "mdlName" => "MdlDiskonMethod",
            //                "key" => "id",
            //                "defaultValue" => "item",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "method",
            //                ),
            //                "editPoints" => array(1,),
            //                "targetMethod" => array(
            //                    "item" => "ReComDiscItem",
            //                    "customer" => "ReComDiscCustomer",
            //                ),
            //            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            "billingDetails" => array(
                "elementType" => "dataModel",
                "elementJoint" => array(
                    "method" => "lookUpJointCustomer",
                    "mdlFilter" => "id=pihakID",
                    "usedFields" => array(
                        "no_ktp" => "nik",
                        "nik" => "nik",
                        "npwp" => "npwp",
                    ),
                ),
                "inputType" => "radio",
                "label" => "BILLING DETAILS",
                "mdlName" => "MdlCustomerBillAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                //                "mdlName" => "MdlCustomer_and_pre",
                //                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                //                "labelSrc" => "alias",
                "labelSrc" => "alias",
                "usedFields" => array(
                    //                    "alias" => "Name",
                    "alias" => "Name",
                    "alamat" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "NIK",
                ),
                "editPoints" => array("4", "5"),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY DETAILS",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Address",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Phone",
                    "tlp_2" => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            //            "dueDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "due date",
            ////                "inputType" => "date",
            //                "inputType" => "hidden",
            //                "defaultValue" => $date->format('Y-m-d'),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "(nett2*50/100)-pph_23",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
                        "defaultValue" => "nett2-pph_23",
                        "minValue" => "nett2-pph_23",
                        "maxValue" => "nett2-pph_23",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "validateMeasurement" => array(),
        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(),
        "pairInjectors" => array(),
        "validationRules" => array(),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
        ),
        "additionalRows" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "allowedMainEdit" => array("1"),
        "addMainStep" => array(
            "1784" => array(
                "jenis_master" => "584",
                "jenis" => "584",
                "target" => "1784",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "connectTo" => "110",
        "connectoValidate" => array(
            3 => "nilai_credit",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "PUSAT",
            "place2ID" => "-1",
            "place2Name" => "PUSAT",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "584spoe",
                "label" => "EDIT SALES PRE-ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "584sporj",
                "label" => "REJECT SALES PRE-ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "584sorj",
                "label" => "REJECT SALES ORDER",
            ),
        ),
    ),


    "582_mod" => array(
        "icon" => "fa fa-opencart",
        "label" => "sales",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "SALES ORDER",
                "label_nota" => "INVOICE",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "582spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Dipersiapkan Oleh",
            ),
            2 => array(
                "label" => "SALES",
                "actionLabel" => "approve order/<br>otorisasi diskon",
                "source" => "582spo",
                "target" => "582so",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optStateLabel"    => "pending disc. approval",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
            ),
            3 => array(
                "label" => "PRE PACKING",
                "actionLabel" => "process packing",
                "source" => "582so",
                "target" => "582pkd", // packed
                "userGroup" => "o_gudang",
                "stateLabel" => "packed",
                "stateColor" => "#009900",
                "stateCaption" => "Prepared",
                "allowEdit" => true,
                "paymentSrc" => array(
                    "enabled" => true,
                    "filter" => array(
                        "label='incoming cash'",
                    ),
                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
                    "label" => "Uang muka belum diterima. Segera hubungi pihak Finance.",
                ),
            ),
            4 => array(
                "label" => "PACKING LIST",
                "actionLabel" => "process shipment",
                "source" => "582pkd",
                "target" => "582spd", // shipped
                "userGroup" => "o_gudang",
                "stateLabel" => "shipped",
                "stateColor" => "#009900",
                "stateCaption" => "shipped by",
            ),
            //            5 => array(
            //                "label" => "I N V O I C E",
            //                "actionLabel" => "create invoice",
            //                "source" => "582spd",
            //                "target" => "582", // invoice
            //                "userGroup" => "o_finance",
            //                "stateLabel" => "invoiced",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "completed by",
            //                "allowJoin" => true,
            //                //                "allowEdit" => true,
            //            ),
        ),
        "shoppingCartMeasurement" => array(
            3 => true,
            4 => true,
        ),
        "template" => "template/transaksi_nosalesman.html",
        //        "selectorModel" => "MdlProduk2",
        //        "selectorSrcModel" => "MdlProduk2",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "jual_reseller",
                "jual_online",
                "jual",
                "ppv",
                "disc",
                "disc_percent"
            ),
            "key_label" => array(
                "jual_reseller" => "jual_reseller",
                "jual_online" => "jual_online",
                "jual" => "harga",
                "ppv" => "ppv",
                "disc" => "disc",
                "disc_percent" => "disc (%)",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "satuan" => "satuan",
            // "jenis" => "jenis",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "no_part",
            "jumlah",
            "satuan",
            // "produk_serial_number",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "pihakPair" => array(
            "enabled" => true,
            "model" => "ComRekeningPembantuCustomerDetail",
            "filter" => array(
//                "extern_id=pihakID",
                "extern2_id=.2010050030",
                "cabang_id=placeID",
            ),
            "rekening" => "2010050",
            "key" => "qty_kredit",
            "method" => "fetchBalances",
        ),

        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            // sumber dari kolom id_his
            "nomer_soa" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "SOA number",
            ),
            "nomer_prepl" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "PRE-PL number",
            ),
            "nomer_pl" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "PL number",
            ),
            "nomer_inv" => array(
                "step" => 5,
                "key" => "nomer",
                "label" => "INV number",
            ),
            //            "nomer" => "receipt number",
            "seller_nama" => array(
                "step" => 1,
                "key" => "olehName",
                "label" => "sales",
            ),

            "oleh_nama" => "person",

            "jual" => "bruto",
//            "disc"                   => "discount",
            "disc" => "diskon tambahan",
            "ppn" => "ppn",
            //            "nett2" => "total amount",
            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            "pengirim_nama" => "nama pengirim",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nett1" => "amount",
            "disc" => "discount",
            "add_diskon" => "diskon tambahan",
            "ppn" => "ppn",
            "nett2" => "total amount",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "SOA number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer" => "PRE-PL number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE-PL number",
                ),
                "nomer" => "PL number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                "jual" => array(
                    "step" => 5,
                    "key" => "jual",
                    "label" => "bruto",
                    "transaksi_jenis2" => array(
                        "paket" => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE-PL number",
                ),
                "ids_his" => array(
                    "step" => 4,
                    "key" => "nomer",
                    "label" => "PL number",
                ),
                "nomer" => "invoice number",
                "sales_name" => "sales",
                "logistic" => "logistic",
                "oleh_nama" => "invoice",
                //            "transaksi_nilai" => "amount",
                //                "jual" => "amount",
                "jual" => array(
                    "step" => 5,
                    "key" => "jual",
                    "label" => "bruto",
                    "transaksi_jenis2" => array(
                        "paket" => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                // "harga" => "amount",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto<br>(after discount)",
                // "ppn" => "ppn",
                // "nett2" => "total amount",
                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "return" => "return",
                "netto_return" => "netto",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "paymentMethod__label" => "payment method",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(

                "print_label" => "nomer",
            ),
            5 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryKeterangan" => array(
            1 => array(
                "edit" => array(
                    "kolom" => "status_edit",
                    "value" => "1",
                    "labels" => array(
                        "edit_name", "edit_dtime",
                    ),
                    "style" => array(
                        "bgcolor" => "yellow",
                        "color" => "red",
                    ),
                ),
            ),
            4 => array(
                "return" => array(
                    "kolom" => "returned",
                    "value" => "1",
                    "labels" => "RETURNED",
                    "style" => array(
                        "bgcolor" => "orange",
                        "color" => "black",
                    ),
                ),
            ),
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                //                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            5 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(ppnFactor/100)",
            "barcode" => "barcode",
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",

            //            "volume" => "volume",
            //            "berat" => "berat",
            //            "lebar" => "lebar",
            //            "tinggi" => "tinggi",
            //            "panjang" => "panjang",
            "jenis" => "jenis",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                "nett1" => "price(net)",
                // "jual_reseller" => "jual reseller",
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
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc",
                "nett1",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                //                "disc_percent",
                //                "disc",
            ),
            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => true,
            5 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total amount bruto",
                //                "disc" => "disc",
                //                "add_diskon" => "diskon tambahan",
                "total_diskon" => "diskon",
//                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "total amount netto*",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "grand total",
                //---------------
                "point_saldo_awal" => "point awal",
//                ".6640" => "point awal",
                "point_transaksi" => "point transaksi",
                "point_saldo_akhir" => "point akhir",
//                "point_saldo_akhir+6640" => "point akhir",
            ),
            2 => array(
                "harga" => "total amount bruto",
//                "disc"             => "disc",
                "disc" => "diskon tambahan",
//                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "nett amount",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "nett amount + PPN",
            ),
            4 => array(
                //                "berat_gross" => "Berat",
                //                "volume_gross"   => "Volume",
                //                "grand_ppn"        => "VAT",
                //                "tagihan_ui" => "Grand Total",
                //                "new_net3"         => "Grand Total",
            ),
            5 => array(
//                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Amount",
                //                "nilai_pembulatan" => "Pembulatan",
                "nett1_bulat" => "Total Amount",
                "ppn_out_bulat" => "VAT",
                "grand_pembulatan" => "Grand Total",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=addCommas(((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))/100))",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
                "nett1" => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
            // "disc_percent" => "disc(%)",
            // "disc" => "disc(IDR)",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",

        ),
        "shopingCartValidatorExt" => array(
            "disc_percent" => array(
                "name" => "disc(%)",
                "min" => ".0",
                "max" => ".100",
            ),

            "disc" => array(
                "name" => "disc(IDR)",
                "min" => ".0",
                "max" => "harga",
            ),
        ),
        "shoppingCartRowOptionalValidators" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                //                "ongkir_tanpa_ppn_by_company" => array(
                //                    "shipping_service" => "ongkir",
                //
                //                ),
            ),
        ),
        "shoppingCartSumEditableFields" => array(
            //            1 => array(
            //                "add_diskon",
            //            ),
            //
        ),
        "shoppingCartSubDetailFields" => array(
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
            4 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
        ),

        "shoppingCartNoteAdditionalEditabled" => array(
            //            3 => array(
            //                "enabled" => true,
            //                "editabled" => true,
            //            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
            ),
            5 => array(
                "previews" => true,
                "enabled" => false,
                "editabled" => false,
            ),
        ),
        //        "shoppingCartHeaderElement" => array(
        //            1 => array(
        //                "tipe_penjualan" => array(
        //                    "label" => "TENTUKAN TIPE PENJUALAN (REGULER atau dengan RESELLER)",
        //                    "class" => "col-xs-6 no-padding",
        //                    "subElements" => array(
        //                        "reguler" => array(
        //                            "label" => "Reguler",
        //                            "srcMain" => "tipePenjualanCheck",
        ////                            "overWriteMain" => "ppnFactor",
        ////                            "srcItem" => "ppnFactor",
        //                            "value" => "1",
        //                            "style" => array( //wajib di tentukan
        //                                "element" => "div",
        //                                "class" => "",
        //                                "id" => "",
        //                                "name" => "",
        //                            )
        //                        ),
        //                        "reseller" => array(
        //                            "label" => "Reseller",
        //                            "srcMain" => "tipePenjualanCheck",
        ////                            "overWriteMain" => "ppnFactor",
        ////                            "srcItem" => "ppnFactor",
        //                            "value" => "2",
        //                            "style" => array( //wajib di tentukan
        //                                "element" => "div",
        //                                "class" => "",
        //                                "id" => "",
        //                                "name" => "",
        //                            )
        //                        ),
        //                    )
        //                ),
        //            )
        //        ),
        //        "shoppingCartHeaderElementSelector" => "_processSelectTipePenjualan/select",


        //        "receiptMesurementRows" => array(
        //            "cbu" => array(
        //                "volume" => array(
        //                    "volume_new" => "conv_mmc_mc",
        //                ),
        //                "berat" => array(
        //                    "berat_new" => "conv_g_kg",
        //                ),
        //            ),
        //            "ckd" => array(
        //                "volume_gross" => array(
        //                    "volume_new" => "conv_mmc_mc",
        //                ),
        //                "berat_gross" => array(
        //                    "berat_new" => "conv_g_kg",
        //                ),
        //            ),
        //            "mesurement" => array(
        //                "cbu_cbm" => array(
        //                    "label" => "cbu cbm",
        //                    "value" => "volume",
        //                    "helper" => "conv_mmc_mc",
        //                ),
        //                "cbu_kg" => array(
        //                    "label" => "cbu(kg)",
        //                    "value" => "berat",
        //                    "helper" => "conv_g_kg",
        //                ),
        //                "ckd_cbm" => array(
        //                    "label" => "ckd cbm",
        //                    "value" => "volume_gross",
        //                    "helper" => "conv_mmc_mc",
        //                ),
        //                "ckd_kg" => array(
        //                    "label" => "ckd (kg)",
        //                    "value" => "berat_gross",
        //                    "helper" => "conv_g_kg",
        //                ),
        //            ),
        //            "allowView" => array("1", "2", "3", "4"),
        //        ),
        "receiptElements" => array(
//            "shippingService" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "shipping service",
//                "mdlName" => "MdlOngkir",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "name",
//                "description" => "",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1),
//            ),
            "shippingMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "dikirim / diambil sendiri",
                "mdlName" => "MdlShippingMethodStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "description" => "",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detil Konsumen",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
                    "kredit_limit" => "KREDIT LIMIT",
                ),
                "editPoints" => array(1, 2, 3, 4),
                "reloadLink" => "_processPihak/select/",
            ),
            "billingDetails" => array(
                "elementType" => "dataModel",
                "elementJoint" => array(
                    "method" => "lookUpJointCustomer",
                    "mdlFilter" => "id=pihakID",
                    "usedFields" => array(
                        "no_ktp" => "nik",
                        "nik" => "nik",
                        "npwp" => "npwp",
                    ),
                ),
                "inputType" => "radio",
                "label" => "Detail Penagihan",
                "mdlName" => "MdlCustomerBillAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                //                "mdlName" => "MdlCustomer_and_pre",
                //                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                //                "labelSrc" => "alias",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "extern_name" => "Customer",
                    "alias" => "Attn*",
                    "alamat" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "NIK",
                ),
                "editPoints" => array(),
                "optionPoints" => array(
                    "label" => array(
                        "lengkap" => 1,
                        "simple" => 2,
                        "no detail" => 3,
                    ),
                    "labelTitle" => array(
                        "lengkap" => "semua data delivery address akan ditampilkan.",
                        "simple" => "hanya attn/pic dan nomor telepon yang akan ditampilkan.",
                        "no detail" => "tidak ada yang akan ditampilkan.",
                    ),
                    "step" => "5",
                    "hidden" => array(
                        2 => array(//hidden bernilai 0, list yang perlu hidden
                            "alamat",
                            "kelurahan",
                            "kecamatan",
                            "kabupaten",
                            "propinsi",
                            "npwp",
                            "no_ktp",
                        ),
                        3 => array(//hidden bernilai 0, list yang perlu hidden
                            "alias",
                            "alamat",
                            "kelurahan",
                            "kecamatan",
                            "kabupaten",
                            "propinsi",
                            "tlp",
                            "tlp_1",
                            "tlp_2",
                            "npwp",
                            "no_ktp",
                        ),
                    ),
                ),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Pengiriman",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Tlp",
                    "tlp_2" => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
//            "detilSize"       => array(
//                "elementType"  => "dataModel",
//                "inputType"    => "radio",
//                "label"        => "Data ukuran",
//                "mdlName"      => "MdlMeasurement",
//                "mdlFilter"    => array("extern_id=pihakID"),
//                "key"          => "id",
//                "labelSrc"     => "name",
//                "defaultValue" => "ckd",
//                "usedFields"   => array(
//                    "name" => "",
//                ),
//                "editPoints"   => array(1, 2, 3),
//            ),
            //            "tos" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "defaultValue" => "20",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),

            //            "capacity" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
//            "dueDate"         => array(
//                "elementType"  => "dataField",
//                "label"        => "due date",
//                "inputType"    => "date",
//                "defaultValue" => $date->format('Y-m-d'),
//                "editPoints"   => array(1, 2, 3, 4, 5),
//            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "Name",
                    "note" => "Note",
                ),
                "defaultValue" => "defaultPaymentMethod",
                "editPoints" => array(1),
                "kreditLimitValidate" => array(
                    1 => array(
                        "enabled" => true,
                    ),
                ),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        //                        "mdlName" => "MdlBankAccount_in",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataField",
                        "inputType" => "number",
                        "label" => "term of payment (hari)",
//                        "mdlName"     => "MdlTop",
//                        "mdlFilter"   => array(),
//                        "key"         => "kode",
//                        "labelSrc"    => "nama",
                        "description" => "",
                        "defaultValue" => "defaultTermOfPayment",
//                        "usedFields"  => array(
//                            "nama" => "",
//                        ),
                        "editPoints" => array(1,),
                    ),
                ),
            ),
            "shippingMethod" => array(
                "1" => array(
                    "shippingDate" => array(
                        "elementType" => "dataField",
                        "label" => "rencana tanggal pengiriman",
                        "inputType" => "date",
                        "defaultValue" => date("Y-m-d"),
                        "editPoints" => array(1),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => true,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "1982",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups" => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
            2 => array(
                "enabled" => true,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "1982",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups" => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
        ),

        "validateMeasurement" => array(
            //            1 => array(
            //                "panjang_gross" => "CKD length (in millimeters)",
            //                "lebar_gross" => "CKD width (in millimeters)",
            //                "tinggi_gross" => "CKD height (in millimeters)",
            //            ),
        ),
        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        //        "requestCode" => array(
        //            "masterCode" => "581",
        //            "stateCode" => "581r",
        //            "stepNumber" => "1",
        //            "allowMultiSelect" => false,
        //        ),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),

            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
//            "shippingService" => array(
//                "ongkir_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        'disabled' => "",
//                        "addPoints" => array(1),
//                        "editPoints" => array(4),
//                    ),
//                ),
//                "ongkir_tanpa_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        'disabled' => "",
//                        "addPoints" => array(1, 4),
//                        "editPoints" => array(4),
//                    ),
//                ),
//            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        ),
        "allowedMainEdit" => array("1"),
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),

        // "compactHistoryFields" => array(
        //     // "jenis_label" => "activity",
        //     "dtime" => "date",
        //     "suppliers_nama" => "customer",
        //     "nomer" => "receipt number",
        //     "oleh_nama" => "person",
        //
        //     "harga" => "amount",
        //     "disc" => "discount",
        //     "ppn" => "ppn",
        //     "nett2" => "total amount",
        // ),
        "compactHistoryFields" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    // "nomer_top" => "nomer",
                    // "dtime" => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    // "oleh_nama" => "approval",
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
        //        "addMainSource" => array(
        //            6 => array(
        //                "fields" => array(
        //                    "nomer" => "INV",
        //                    "grand_total_ui" => "DPP",
        //                    "new_grand_ppn" => "PPN",
        //                    "dateFaktur" => "Tgl faktur ",
        //                    "eFaktur" => "e-faktur",
        //                ),
        //                "editableFields" => array(
        //                    "eFaktur" => "",
        //                    "dateFaktur" => "",
        //                ),
        //            ),
        //        ),

        "connectTo" => "110",
        "connectoValidate" => array(
            5 => "nilai_credit",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "pusat",
            "place2ID" => "-1",
            "place2Name" => "pusat",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang

            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        //connecto ke pusat jika ada produk yang perlu direpack dipusat untuk penjualan produk komposit
        //        "extConnectTo" => array(
        //            2 => array(
        //                "connecTo" => "773",
        //            ),
        //        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "582spoe",
                "label" => "EDIT SALES PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "582sporj",
                "label" => "REJECT SALES PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "582sorj",
                "label" => "REJECT SALES ORDER",
            ),
            3 => array(
                "enabled" => true,
                "connectTo" => "582pkdrj",
                "label" => "REJECT PRE PACKING",
            ),
        ),
        //----
        "autoApprove" => array(
            1 => true,
        ),
        "kreditLimitValidate" => array(
//            1 => array(
//                "enabled" => true,
//            ),
//
            3 => array(
                "enabled" => true,
                "label" => "Transaksi ini sedang ditindaklanjuti oleh pihak finance.<br>Silahkan konfirmasi ke pihak finance.",
            ),
        ),
    ),
    "5822_mod" => array(
        "icon" => "fa fa-opencart",
        "label" => "sales",
        "place" => "branch",//=> "center",
        "steps" => array(
//            1 => array(
//                "label" => "SALES ORDER",
//                "label_nota" => "INVOICE",
//                "actionLabel" => "make order",
//                "source" => "",
//                "target" => "5822spo",
//                "userGroup" => "o_seller",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Dipersiapkan Oleh",
//            ),
//            2 => array(
//                "label" => "SALES",
//                "actionLabel" => "approve order/<br>otorisasi diskon",
//                "source" => "5822spo",
//                "target" => "5822so",
//                "userGroup" => "o_seller_spv",
//                "stateLabel" => "approved",
//                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
//                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
//                //				"optStateLabel"    => "pending disc. approval",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "Acknowledge by",
//                "allowEdit" => true,
//            ),
//            3 => array(
//                "label" => "PRE PACKING",
//                "actionLabel" => "process packing",
//                "source" => "5822so",
//                "target" => "5822pkd", // packed
//                "userGroup" => "o_gudang",
//                "stateLabel" => "packed",
//                "stateColor" => "#009900",
//                "stateCaption" => "Prepared",
//                "allowEdit" => true,
//                "paymentSrc" => array(
//                    "enabled" => true,
//                    "filter" => array(
//                        "label='incoming cash'",
//                    ),
//                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
//                    "label" => "Uang muka belum diterima. Segera hubungi pihak Finance.",
//                ),
//            ),
//            4 => array(
//                "label" => "PACKING LIST",
//                "actionLabel" => "process shipment",
//                "source" => "5822pkd",
//                "target" => "5822spd", // shipped
//                "userGroup" => "o_gudang",
//                "stateLabel" => "shipped",
//                "stateColor" => "#009900",
//                "stateCaption" => "shipped by",
//            ),
            //            5 => array(
            //                "label" => "I N V O I C E",
            //                "actionLabel" => "create invoice",
            //                "source" => "582spd",
            //                "target" => "582", // invoice
            //                "userGroup" => "o_finance",
            //                "stateLabel" => "invoiced",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "completed by",
            //                "allowJoin" => true,
            //                //                "allowEdit" => true,
            //            ),

            1 => array(
                "label" => "SALES ORDER",
                "label_nota" => "SALES ORDER",
//                "label_nota" => "INVOICE",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "5822spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "sales admin",
            ),
            2 => array(
                "label" => "SALES",
//                "label_nota" => "SALES ORDER",
                "label_nota" => "INVOICE",
                "actionLabel" => "approve order/<br>otorisasi diskon",
                "source" => "5822spo",
                "target" => "5822so",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optStateLabel"    => "pending disc. approval",
                "stateColor" => "#ff7700",
                "stateCaption" => "Hormat Kami",
                "allowEdit" => true,
            ),
            3 => array(
                "label" => "PRE PACKING",
                "label_nota" => "SURAT JALAN",
                "actionLabel" => "process packing",
                "source" => "5822so",
                "target" => "5822pkd", // packed
                "userGroup" => "o_gudang",
                "stateLabel" => "packed",
                "stateColor" => "#009900",
                "stateCaption" => "Prepared",
                "allowEdit" => true,
                "paymentSrc" => array(
                    "enabled" => true,
                    "filter" => array(
                        "label='incoming cash'",
                    ),
                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
                    "label" => "Uang muka belum diterima. Segera hubungi pihak Finance.",
                ),
                "allowScaner" => true,
            ),
            4 => array(
                "label" => "PACKING LIST",
                "label_nota" => "Proforma INVOICE",
                "actionLabel" => "process shipment",
                "source" => "5822pkd",
                "target" => "5822spd", // shipped
                "userGroup" => "o_gudang",
                "stateLabel" => "shipped",
                "stateColor" => "#009900",
                "stateCaption" => "shipped by",
                "autoNextStep" => true,
            ),
        ),
        "shoppingCartMeasurement" => array(
            3 => true,
            4 => true,
        ),
        "template" => "template/transaksi.html",
        //        "selectorModel" => "MdlProduk2",
        //        "selectorSrcModel" => "MdlProduk2",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "jual_reseller",
                "jual_online",
                "jual",
                "ppv",
                "disc",
                "disc_percent"
            ),
            "key_label" => array(
                "jual_reseller" => "jual_reseller",
                "jual_online" => "jual_online",
                "jual" => "harga",
                "ppv" => "ppv",
                "disc" => "disc",
                "disc_percent" => "disc (%)",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "satuan" => "satuan",
            // "jenis" => "jenis",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "no_part",
            "jumlah",
            "satuan",
            // "produk_serial_number",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "pihakPair" => array(
            "enabled" => true,
            "model" => "ComRekeningPembantuCustomerDetail",
            "filter" => array(
//                "extern_id=pihakID",
                "extern2_id=.2010050030",
                "cabang_id=placeID",
            ),
            "rekening" => "2010050",
            "key" => "qty_kredit",
            "method" => "fetchBalances",
        ),

        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            // sumber dari kolom id_his
            "nomer_soa" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "SOA number",
            ),
            "nomer_prepl" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "PRE-PL number",
            ),
            "nomer_pl" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "PL number",
            ),
            "nomer_inv" => array(
                "step" => 5,
                "key" => "nomer",
                "label" => "INV number",
            ),
            //            "nomer" => "receipt number",
            "seller_nama" => array(
                "step" => 1,
                "key" => "olehName",
                "label" => "sales",
            ),

            "oleh_nama" => "person",

            "jual" => "bruto",
//            "disc"                   => "discount",
            "disc" => "diskon tambahan",
            "ppn" => "ppn",
            //            "nett2" => "total amount",
            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            "pengirim_nama" => "nama pengirim",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "nett1" => "amount",
            "disc" => "discount",
            "add_diskon" => "diskon tambahan",
            "ppn" => "ppn",
            "nett2" => "total amount",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "SOA number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer" => "PRE-PL number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "bruto",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "paymentMethod__label" => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE-PL number",
                ),
                "nomer" => "PL number",
                "sales_name" => "sales",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                "jual" => array(
                    "step" => 5,
                    "key" => "jual",
                    "label" => "bruto",
                    "transaksi_jenis2" => array(
                        "paket" => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE-PL number",
                ),
                "ids_his" => array(
                    "step" => 4,
                    "key" => "nomer",
                    "label" => "PL number",
                ),
                "nomer" => "invoice number",
                "sales_name" => "sales",
                "logistic" => "logistic",
                "oleh_nama" => "invoice",
                //            "transaksi_nilai" => "amount",
                //                "jual" => "amount",
                "jual" => array(
                    "step" => 5,
                    "key" => "jual",
                    "label" => "bruto",
                    "transaksi_jenis2" => array(
                        "paket" => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                // "harga" => "amount",
                "disc" => "discount",
                "add_diskon" => "diskon tambahan",
                "nett1" => "netto<br>(after discount)",
                // "ppn" => "ppn",
                // "nett2" => "total amount",
                "ongkir" => "shipping service",
                "grand_ppn" => "ppn",
                "new_net3" => "total amount",
                "return" => "return",
                "netto_return" => "netto",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "paymentMethod__label" => "payment method",
                "pengirim_nama" => "nama pengirim",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(

                "print_label" => "nomer",
            ),
            5 => array(
                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryKeterangan" => array(
            1 => array(
                "edit" => array(
                    "kolom" => "status_edit",
                    "value" => "1",
                    "labels" => array(
                        "edit_name", "edit_dtime",
                    ),
                    "style" => array(
                        "bgcolor" => "yellow",
                        "color" => "red",
                    ),
                ),
            ),
            4 => array(
                "return" => array(
                    "kolom" => "returned",
                    "value" => "1",
                    "labels" => "RETURNED",
                    "style" => array(
                        "bgcolor" => "orange",
                        "color" => "black",
                    ),
                ),
            ),
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                //                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            5 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(ppnFactor/100)",
            "barcode" => "barcode",
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",

            //            "volume" => "volume",
            //            "berat" => "berat",
            //            "lebar" => "lebar",
            //            "tinggi" => "tinggi",
            //            "panjang" => "panjang",
            "jenis" => "jenis",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                "nett1" => "price(net)",
                // "jual_reseller" => "jual reseller",
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
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc",
                "nett1",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                //                "disc_percent",
                //                "disc",
            ),
            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => true,
            5 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total amount bruto",
                //                "disc" => "disc",
                //                "add_diskon" => "diskon tambahan",
                "total_diskon" => "diskon",
//                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "total amount netto*",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "grand total",
                //---------------
                "point_saldo_awal" => "point awal",
//                ".6640" => "point awal",
                "point_transaksi" => "point transaksi",
                "point_saldo_akhir" => "point akhir",
//                "point_saldo_akhir+6640" => "point akhir",
            ),
            2 => array(
                "harga" => "total amount bruto",
//                "disc"             => "disc",
                "disc" => "diskon tambahan",
//                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat" => "nett amount",
                "ppn_out_bulat" => "PPN",
                "grand_pembulatan" => "nett amount + PPN",
            ),
            4 => array(
                //                "berat_gross" => "Berat",
                //                "volume_gross"   => "Volume",
                //                "grand_ppn"        => "VAT",
                //                "tagihan_ui" => "Grand Total",
                //                "new_net3"         => "Grand Total",
            ),
            5 => array(
//                "shipping_service" => "Shipping Service",
                "grand_total_ui" => "Amount",
                //                "nilai_pembulatan" => "Pembulatan",
                "nett1_bulat" => "Total Amount",
                "ppn_out_bulat" => "VAT",
                "grand_pembulatan" => "Grand Total",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=addCommas(((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))/100))",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
                "nett1" => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
            // "disc_percent" => "disc(%)",
            // "disc" => "disc(IDR)",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",

        ),
        "shopingCartValidatorExt" => array(
            "disc_percent" => array(
                "name" => "disc(%)",
                "min" => ".0",
                "max" => ".100",
            ),

            "disc" => array(
                "name" => "disc(IDR)",
                "min" => ".0",
                "max" => "harga",
            ),
        ),
        "shoppingCartRowOptionalValidators" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                //                "ongkir_tanpa_ppn_by_company" => array(
                //                    "shipping_service" => "ongkir",
                //
                //                ),
            ),
        ),
        "shoppingCartSumEditableFields" => array(
            //            1 => array(
            //                "add_diskon",
            //            ),
            //
        ),
        "shoppingCartSubDetailFields" => array(
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
            4 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
        ),

        "shoppingCartNoteAdditionalEditabled" => array(
            //            3 => array(
            //                "enabled" => true,
            //                "editabled" => true,
            //            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
            ),
            5 => array(
                "previews" => true,
                "enabled" => false,
                "editabled" => false,
            ),
        ),
        //        "shoppingCartHeaderElement" => array(
        //            1 => array(
        //                "tipe_penjualan" => array(
        //                    "label" => "TENTUKAN TIPE PENJUALAN (REGULER atau dengan RESELLER)",
        //                    "class" => "col-xs-6 no-padding",
        //                    "subElements" => array(
        //                        "reguler" => array(
        //                            "label" => "Reguler",
        //                            "srcMain" => "tipePenjualanCheck",
        ////                            "overWriteMain" => "ppnFactor",
        ////                            "srcItem" => "ppnFactor",
        //                            "value" => "1",
        //                            "style" => array( //wajib di tentukan
        //                                "element" => "div",
        //                                "class" => "",
        //                                "id" => "",
        //                                "name" => "",
        //                            )
        //                        ),
        //                        "reseller" => array(
        //                            "label" => "Reseller",
        //                            "srcMain" => "tipePenjualanCheck",
        ////                            "overWriteMain" => "ppnFactor",
        ////                            "srcItem" => "ppnFactor",
        //                            "value" => "2",
        //                            "style" => array( //wajib di tentukan
        //                                "element" => "div",
        //                                "class" => "",
        //                                "id" => "",
        //                                "name" => "",
        //                            )
        //                        ),
        //                    )
        //                ),
        //            )
        //        ),
        //        "shoppingCartHeaderElementSelector" => "_processSelectTipePenjualan/select",


        //        "receiptMesurementRows" => array(
        //            "cbu" => array(
        //                "volume" => array(
        //                    "volume_new" => "conv_mmc_mc",
        //                ),
        //                "berat" => array(
        //                    "berat_new" => "conv_g_kg",
        //                ),
        //            ),
        //            "ckd" => array(
        //                "volume_gross" => array(
        //                    "volume_new" => "conv_mmc_mc",
        //                ),
        //                "berat_gross" => array(
        //                    "berat_new" => "conv_g_kg",
        //                ),
        //            ),
        //            "mesurement" => array(
        //                "cbu_cbm" => array(
        //                    "label" => "cbu cbm",
        //                    "value" => "volume",
        //                    "helper" => "conv_mmc_mc",
        //                ),
        //                "cbu_kg" => array(
        //                    "label" => "cbu(kg)",
        //                    "value" => "berat",
        //                    "helper" => "conv_g_kg",
        //                ),
        //                "ckd_cbm" => array(
        //                    "label" => "ckd cbm",
        //                    "value" => "volume_gross",
        //                    "helper" => "conv_mmc_mc",
        //                ),
        //                "ckd_kg" => array(
        //                    "label" => "ckd (kg)",
        //                    "value" => "berat_gross",
        //                    "helper" => "conv_g_kg",
        //                ),
        //            ),
        //            "allowView" => array("1", "2", "3", "4"),
        //        ),
        "receiptElements" => array(
//            "shippingService" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "shipping service",
//                "mdlName" => "MdlOngkir",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "name",
//                "description" => "",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1),
//            ),
            "shippingMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "dikirim / diambil sendiri",
                "mdlName" => "MdlShippingMethodStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "description" => "",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detil Konsumen",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
                    "kredit_limit" => "KREDIT LIMIT",
                ),
                "editPoints" => array(1, 2, 3, 4),
                "reloadLink" => "_processPihak/select/",
            ),
            "billingDetails" => array(
                "elementType" => "dataModel",
                "elementJoint" => array(
                    "method" => "lookUpJointCustomer",
                    "mdlFilter" => "id=pihakID",
                    "usedFields" => array(
                        "no_ktp" => "nik",
                        "nik" => "nik",
                        "npwp" => "npwp",
                    ),
                ),
                "inputType" => "radio",
                "label" => "Detail Penagihan",
                "mdlName" => "MdlCustomerBillAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                //                "mdlName" => "MdlCustomer_and_pre",
                //                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                //                "labelSrc" => "alias",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "extern_name" => "Customer",
                    "alias" => "Attn*",
                    "alamat" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "NIK",
                ),
                "editPoints" => array(),
                "optionPoints" => array(
                    "label" => array(
                        "lengkap" => 1,
                        "simple" => 2,
                        "no detail" => 3,
                    ),
                    "labelTitle" => array(
                        "lengkap" => "semua data delivery address akan ditampilkan.",
                        "simple" => "hanya attn/pic dan nomor telepon yang akan ditampilkan.",
                        "no detail" => "tidak ada yang akan ditampilkan.",
                    ),
                    "step" => "5",
                    "hidden" => array(
                        2 => array(//hidden bernilai 0, list yang perlu hidden
                            "alamat",
                            "kelurahan",
                            "kecamatan",
                            "kabupaten",
                            "propinsi",
                            "npwp",
                            "no_ktp",
                        ),
                        3 => array(//hidden bernilai 0, list yang perlu hidden
                            "alias",
                            "alamat",
                            "kelurahan",
                            "kecamatan",
                            "kabupaten",
                            "propinsi",
                            "tlp",
                            "tlp_1",
                            "tlp_2",
                            "npwp",
                            "no_ktp",
                        ),
                    ),
                ),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Pengiriman",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Alamat",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Tlp",
                    "tlp_2" => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
//            "detilSize"       => array(
//                "elementType"  => "dataModel",
//                "inputType"    => "radio",
//                "label"        => "Data ukuran",
//                "mdlName"      => "MdlMeasurement",
//                "mdlFilter"    => array("extern_id=pihakID"),
//                "key"          => "id",
//                "labelSrc"     => "name",
//                "defaultValue" => "ckd",
//                "usedFields"   => array(
//                    "name" => "",
//                ),
//                "editPoints"   => array(1, 2, 3),
//            ),
            //            "tos" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "defaultValue" => "20",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),

            //            "capacity" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
//            "dueDate"         => array(
//                "elementType"  => "dataField",
//                "label"        => "due date",
//                "inputType"    => "date",
//                "defaultValue" => $date->format('Y-m-d'),
//                "editPoints"   => array(1, 2, 3, 4, 5),
//            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "Name",
                    "note" => "Note",
                ),
                "defaultValue" => "defaultPaymentMethod",
                "editPoints" => array(1),
                "kreditLimitValidate" => array(
                    1 => array(
                        "enabled" => true,
                    ),
                ),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        //                        "mdlName" => "MdlBankAccount_in",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataField",
                        "inputType" => "number",
                        "label" => "term of payment (hari)",
//                        "mdlName"     => "MdlTop",
//                        "mdlFilter"   => array(),
//                        "key"         => "kode",
//                        "labelSrc"    => "nama",
                        "description" => "",
                        "defaultValue" => "defaultTermOfPayment",
//                        "usedFields"  => array(
//                            "nama" => "",
//                        ),
                        "editPoints" => array(1,),
                    ),
                ),
            ),
            "shippingMethod" => array(
                "1" => array(
                    "shippingDate" => array(
                        "elementType" => "dataField",
                        "label" => "rencana tanggal pengiriman",
                        "inputType" => "date",
                        "defaultValue" => date("Y-m-d"),
                        "editPoints" => array(1),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => true,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "1982",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups" => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
            2 => array(
                "enabled" => true,
                "label" => "close/fullfillment sales order",
                "targetJenisMaster" => "1982",
                "warning" => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups" => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
        ),

        "validateMeasurement" => array(
            //            1 => array(
            //                "panjang_gross" => "CKD length (in millimeters)",
            //                "lebar_gross" => "CKD width (in millimeters)",
            //                "tinggi_gross" => "CKD height (in millimeters)",
            //            ),
        ),
        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        //        "requestCode" => array(
        //            "masterCode" => "581",
        //            "stateCode" => "581r",
        //            "stepNumber" => "1",
        //            "allowMultiSelect" => false,
        //        ),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),

            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            4 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
//            "shippingService" => array(
//                "ongkir_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        'disabled' => "",
//                        "addPoints" => array(1),
//                        "editPoints" => array(4),
//                    ),
//                ),
//                "ongkir_tanpa_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
//                        'disabled' => "",
//                        "addPoints" => array(1, 4),
//                        "editPoints" => array(4),
//                    ),
//                ),
//            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        ),
        "allowedMainEdit" => array("1"),
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),

        // "compactHistoryFields" => array(
        //     // "jenis_label" => "activity",
        //     "dtime" => "date",
        //     "suppliers_nama" => "customer",
        //     "nomer" => "receipt number",
        //     "oleh_nama" => "person",
        //
        //     "harga" => "amount",
        //     "disc" => "discount",
        //     "ppn" => "ppn",
        //     "nett2" => "total amount",
        // ),
        "compactHistoryFields" => array(
            "transaksi" => array(
                "label" => "transaksi",
                "target" => "transaksi",
                "srcKey" => "id_master",
                "addFields" => "sales",
                "fields" => array(
                    "cabang_nama" => "cabang",
                    // "nomer_top" => "nomer",
                    // "dtime" => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama" => "sallesman",
                    // "oleh_nama" => "approval",
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
        //        "addMainSource" => array(
        //            6 => array(
        //                "fields" => array(
        //                    "nomer" => "INV",
        //                    "grand_total_ui" => "DPP",
        //                    "new_grand_ppn" => "PPN",
        //                    "dateFaktur" => "Tgl faktur ",
        //                    "eFaktur" => "e-faktur",
        //                ),
        //                "editableFields" => array(
        //                    "eFaktur" => "",
        //                    "dateFaktur" => "",
        //                ),
        //            ),
        //        ),

        "connectTo" => "110",
        "connectoValidate" => array(
            5 => "nilai_credit",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "pusat",
            "place2ID" => "-1",
            "place2Name" => "pusat",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang

            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        //connecto ke pusat jika ada produk yang perlu direpack dipusat untuk penjualan produk komposit
        //        "extConnectTo" => array(
        //            2 => array(
        //                "connecTo" => "773",
        //            ),
        //        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "582spoe",
                "label" => "EDIT SALES PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "582sporj",
                "label" => "REJECT SALES PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "582sorj",
                "label" => "REJECT SALES ORDER",
            ),
            3 => array(
                "enabled" => true,
                "connectTo" => "582pkdrj",
                "label" => "REJECT PRE PACKING",
            ),
        ),
        //----
        "autoApprove" => array(
            1 => true,
        ),
        "kreditLimitValidate" => array(
//            1 => array(
//                "enabled" => true,
//            ),
//
            3 => array(
                "enabled" => true,
                "label" => "Transaksi ini sedang ditindaklanjuti oleh pihak finance.<br>Silahkan konfirmasi ke pihak finance.",
            ),
        ),
    ),
);