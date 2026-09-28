<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(

    "580" => array(
        "icon" => "fa fa-opencart",
        "label" => "sales",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "SALES ORDER",
                "label_nota" => "INVOICE",
                "label_alt" => "PROFORMA",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "580spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Dipersiapkan Oleh",
            ),
            2 => array(
                "label" => "SALES",
                "actionLabel" => "approve order/<br>otorisasi diskon",
                "source" => "580spo",
                "target" => "580so",
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
                "source" => "580so",
                "target" => "580pkd", // packed
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
                "source" => "580pkd",
                "target" => "580spd", // shipped
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
        "template" => "template/transaksi_project.html",
        //        "selectorModel" => "MdlProduk2",
        //        "selectorSrcModel" => "MdlProduk2",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "jual",
                "ppv",
                "disc",
                "disc_percent"
            ),
            "key_label" => array(
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


        // PIHAK IV, PILIH-PILIH RESELLER NYA
        "pihakModelProjek" => "MdlReseller",
        "pihakProjekCaller" => "_selectorPihak/selectPihakReseller",
        "pihakProjekLabel" => "pilih reseller....",
        "pihakProjekViewedFields" => array(
            "nama",
        ),
        "pihakProjekFilters" => array(
            "status=.1",
            "trash=.0",
        ),
        "pihakProjekProcessor" => "_processPihak/selectReseller",


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
            "disc" => "discount",
            "add_diskon" => "diskon tambahan",
            "ppn" => "ppn",
            //            "nett2" => "total amount",
            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            "shippingService__label" => "shipping note",
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
                "shippingService__label" => "shipping note",
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
                "shippingService__label" => "shipping note",
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
                "shippingService__label" => "shipping note",
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
                "shippingService__label" => "shipping note",
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


                "shippingService__label" => "shipping note",
                "paymentMethod__label" => "payment method",
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
        /*#1a*/
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Nama Produk",
                "produk_kode" => "SKU",
                //                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok tersedia",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                //                "no_part" => "part number",
                //                "stok_center" => "stok dc",
                //                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),

            3 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            5 => array(
                "nama" => "product name",
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
        ),
        /*#1b*/
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
                // "harga_reseller"        => "harga dropship",
                "harga_dropshiper" => "harga dropship",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                // "nett1"        => "harga(net)",
                "nett1_dropshiper" => "harga(net)",
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
        "shoppingCartSubNumFields" => array(
            1 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
            2 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
            3 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
            4 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
            5 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc",
                "nett1",
                "harga_dropshiper",
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
        /*#2*/
        "shoppingCartSumFields" => array(
            1 => array(
                // "harga"            => "total amount bruto*",
                "harga_dropshiper" => "jumlah",
                //                "disc" => "disc",
                //                "add_diskon" => "diskon tambahan",
                "total_diskon" => "diskon",
                "ongkir_ui" => "biaya kirim",
                //                "nilai_pembulatan" => "pembulatan",
                // "nett1_bulat"      => "total amount netto*",
                "nett1_dropshiper" => "sub total*",
                // "ppn_out_bulat"    => "PPN",
                "ppn_out_bulat_dropshiper" => "PPN",
                // "grand_pembulatan" => "grand total",
                "grand_pembulatan_dropshiper" => "total",
                "point_saldo_awal" => "point awal",
                "point_transaksi" => "point transaksi",
                "point_saldo_akhir" => "point akhir",
                "komisi_netto" => "komisi netto",

            ),
            2 => array(
                "new_net1" => "amount",
                "disc" => "disc",
                "add_diskon" => "diskon tambahan",
                "ongkir_ui" => "shipping service",
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
                "shipping_service" => "Shipping Service",
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
        /*--dipakai di print juga---*/
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
                "label" => "detil konsumen",
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
                    "extern_name" => "Customer",
                    "alias" => "Attn",
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
                "label" => "data pengiriman",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                ),
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
            "resellerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "RESELLER DETAILS",
                "mdlName" => "MdlReseller",
                "mdlFilter" => array("id=pihakResellerID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",

                ),
                "editPoints" => array(1),
            ),
//            "detilSize" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Data ukuran",
//                "mdlName" => "MdlMeasurement",
//                "mdlFilter" => array("extern_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "name",
//                "defaultValue" => "ckd",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1, 2, 3),
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
            "shippingDate" => array(
                "elementType" => "dataField",
                // "inputType" => "combo",
                "label" => "shipping date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                //                "editPoints" => array(1),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
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
//            "dueDate" => array(
//                "elementType" => "dataField",
//                "label" => "due date",
//                "inputType" => "date",
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
                    "name" => "Name",
                    "note" => "Note",
                ),
                "defaultValue" => "defaultPaymentMethod",
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
                        "editPoints" => array(1, 2, 3, 4, 5),
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
            //                        "maxValue" => "nett2*80/100",
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
            //                        //                        "defaultValue" => "nett2",
            //                        //                        "minValue" => "nett2",
            //                        //                        "maxValue" => "nett2",
            //                        //                        "defaultValue" => "new_net3",
            //                        "defaultValue" => "grand_pembulatan",
            //                        "minValue" => "grand_pembulatan",
            //                        "maxValue" => "grand_pembulatan",
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
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
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
            ),
            "dummyElement" => array(
                "yes" => array(
                    "pph_nilai" => array(
                        "label" => "pph",
                        "defaultValue" => "0",
                        "addPoints" => array(1,),
                    ),
                ),
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
                "connectTo" => "580spoe",
                "label" => "EDIT SALES PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "580sporj",
                "label" => "REJECT SALES PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "580sorj",
                "label" => "REJECT SALES ORDER",
            ),
            3 => array(
                "enabled" => true,
                "connectTo" => "580pkdrj",
                "label" => "REJECT PRE PACKING",
            ),
        ),
        //----
        "autoApprove" => array(
            1 => true,
        ),
        "kreditLimitValidate" => array(
            3 => array(
                "enabled" => true,
                "label" => "Transaksi ini sedang ditindaklanjuti oleh pihak finance.<br>Silahkan konfirmasi ke pihak finance.",
            ),
        ),
    ),
    //  config return penjualan dimatikan dulu ada cacat jika diretun jkarena ada pergeseran jurnal ppn
    "980" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "sales return",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "RETURN REQUEST",
                "actionLabel" => "make return request",
                "source" => "",
                "target" => "980r",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "RETURN AUTHORIZATION",
                "actionLabel" => "approve return request",
                "source" => "980r",
                "target" => "980g",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),
            3 => array(
                //                "label" => "goods received note",
                "label" => "RETURN PENJUALAN",
                "actionLabel" => "receive & make GRN",
                "source" => "980g",
                "target" => "980",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "GRN made",
                "stateColor" => "#ff7700",
                "stateCaption" => "received by",
            ),
        ),
        "template" => "template/transaksi.html",
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
            "jenis=.580spd",
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
        "pihakModel" => "MdlCustomer_and_pre",
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
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
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
        "referenceJenisTr" => "580",
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
                "connectTo" => "980re",
                "label" => "EDIT RETURN REQUEST",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "980rrj",
                "label" => "REJECT RETURN REQUEST",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "980grj",
                "label" => "REJECT RETURN APPROVAL",
            ),
        ),
    ),
    "1980" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "close/fullfillment Sales Order",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request close/fullfillment",
                "actionLabel" => "make close/fullfillment transaksi",
                "source" => "",
                "target" => "1980",
                "userGroup" => "o_seller",
                "stateLabel" => "request close/fullfillment transaksi",
                "stateColor" => "#dd3300",
                "stateCaption" => "request close/fullfillment transaksi by",
                "isCancelPacking" => true,
            ),
            2 => array(
                "label" => "otorisasi close/fullfillment",
                "actionLabel" => "approve close/fullfillment transaksi",
                "source" => "1980",
                "target" => "1980g",
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
        ),
        "shoppingCartFields" => array(
            1 => array(
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
    ),

    "580_mod" => array(
        "icon" => "fa fa-opencart",
        "label" => "sales",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "SALES PRE ORDER",
                "label_nota" => "INVOICE",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "580spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Dipersiapkan Oleh",
            ),
            2 => array(
                "label" => "SALES ORDER",
                "actionLabel" => "approve order/<br>otorisasi diskon",
                "source" => "580spo",
                "target" => "580so",
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
                "source" => "580so",
                "target" => "580pkd", // packed
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
                "source" => "580pkd",
                "target" => "580spd", // shipped
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
        "template" => "template/transaksi_project.html",
        //        "selectorModel" => "MdlProduk2",
        //        "selectorSrcModel" => "MdlProduk2",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "jual",
                "ppv",
                "disc",
                "disc_percent"
            ),
            "key_label" => array(
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


        // PIHAK IV, PILIH-PILIH RESELLER NYA
        "pihakModelProjek" => "MdlReseller",
        "pihakProjekCaller" => "_selectorPihak/selectPihakReseller",
        "pihakProjekLabel" => "pilih reseller....",
        "pihakProjekViewedFields" => array(
            "nama",
        ),
        "pihakProjekFilters" => array(
            "status=.1",
            "trash=.0",
        ),
        "pihakProjekProcessor" => "_processPihak/selectReseller",


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
            "disc" => "discount",
            "add_diskon" => "diskon tambahan",
            "ppn" => "ppn",
            //            "nett2" => "total amount",
            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            "shippingService__label" => "shipping note",
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
                "shippingService__label" => "shipping note",
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
                "shippingService__label" => "shipping note",
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
                "shippingService__label" => "shipping note",
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
                "shippingService__label" => "shipping note",
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


                "shippingService__label" => "shipping note",
                "paymentMethod__label" => "payment method",
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
        /*#1a*/
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Nama Produk",
                "produk_kode" => "SKU",
                //                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok tersedia",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                //                "no_part" => "part number",
                //                "stok_center" => "stok dc",
                //                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),

            3 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            5 => array(
                "nama" => "product name",
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
        ),
        /*#1b*/
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
                // "harga_reseller"        => "harga dropship",
                "harga_dropshiper" => "harga dropship",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                // "ppn" => "VAT",
                // "nett1"        => "harga(net)",
                "nett1_dropshiper" => "harga(net)",
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
        "shoppingCartSubNumFields" => array(
            1 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
            2 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
            3 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
            4 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
            5 => array(
                "subtotal_dropshiper" => "subtotal",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "disc_percent",
                "disc",
                "nett1",
                "harga_dropshiper",
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
        /*#2*/
        "shoppingCartSumFields" => array(
            1 => array(
                // "harga"            => "total amount bruto*",
                "harga_dropshiper" => "jumlah",
                //                "disc" => "disc",
                //                "add_diskon" => "diskon tambahan",
                "total_diskon" => "diskon",
                "ongkir_ui" => "biaya kirim",
                //                "nilai_pembulatan" => "pembulatan",
                // "nett1_bulat"      => "total amount netto*",
                "nett1_dropshiper" => "sub total*",
                // "ppn_out_bulat"    => "PPN",
                "ppn_out_bulat_dropshiper" => "PPN",
                // "grand_pembulatan" => "grand total",
                "grand_pembulatan_dropshiper" => "total",
                "point_saldo_awal" => "point awal",
                //                ".6640" => "point awal",
                "point_transaksi" => "point transaksi",
                "point_saldo_akhir" => "point akhir",
            ),
            2 => array(
                "new_net1" => "amount",
                "disc" => "disc",
                "add_diskon" => "diskon tambahan",
                "ongkir_ui" => "shipping service",
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
                "shipping_service" => "Shipping Service",
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
        /*--dipakai di print juga---*/
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
                "label" => "detil konsumen",
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
                    "extern_name" => "Customer",
                    "alias" => "Attn",
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
                "label" => "data pengiriman",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                ),
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
            "resellerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "RESELLER DETAILS",
                "mdlName" => "MdlReseller",
                "mdlFilter" => array("id=pihakResellerID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",

                ),
                "editPoints" => array(1),
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
            "shippingDate" => array(
                "elementType" => "dataField",
                // "inputType" => "combo",
                "label" => "shipping date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                //                "editPoints" => array(1),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
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
                    "name" => "Name",
                    "note" => "Note",
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
                        "editPoints" => array(1, 2, 3, 4, 5),
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
            //                        "maxValue" => "nett2*80/100",
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
            //                        //                        "defaultValue" => "nett2",
            //                        //                        "minValue" => "nett2",
            //                        //                        "maxValue" => "nett2",
            //                        //                        "defaultValue" => "new_net3",
            //                        "defaultValue" => "grand_pembulatan",
            //                        "minValue" => "grand_pembulatan",
            //                        "maxValue" => "grand_pembulatan",
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
                "connectTo" => "580spoe",
                "label" => "EDIT SALES PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "580sporj",
                "label" => "REJECT SALES PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "580sorj",
                "label" => "REJECT SALES ORDER",
            ),
            3 => array(
                "enabled" => true,
                "connectTo" => "580pkdrj",
                "label" => "REJECT PRE PACKING",
            ),
        ),
        //----
        "autoApprove" => array(
            1 => true,
        ),
    ),

);