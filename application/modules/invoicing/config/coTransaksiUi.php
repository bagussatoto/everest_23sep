<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(

    "4822" => array(
        "icon"                    => "fa fa-opencart",
        "label"                   => "invoicing",
        "place"                   => "branch",//=> "center",
        "counter_global"          => "_company_cabangID_jenisTr", //=> "key globla counter",
        "counter_global_part"     => array(
            'jenis',
            "dtime",
            'cabangID',
            'customerID',
            '_company_cabangID_modul_subModul_jenisTr_customerID',
        ),
        // "counter_global_show"       => true,
        "steps"                   => array(

            1 => array(
                "label"        => "INVOICE",
                "label_nota"   => "INVOICE",
                "actionLabel"  => "Cetak Invoice",
                "source"       => "",
                "target"       => "4822",
                "userGroup"    => "o_finance",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "finance",
                "wa_sender"    => true,
            ),

            //            2 => array(
            //                "label" => "SALES",
            ////                "label_nota" => "SALES ORDER",
            //                "label_nota" => "INVOICE",
            //                "actionLabel" => "approve order/<br>otorisasi diskon",
            //                "source" => "5822spo",
            //                "target" => "5822so",
            //                "userGroup" => "o_seller_spv",
            //                "stateLabel" => "approved",
            //                				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
            //                				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
            //                				"optStateLabel"    => "pending disc. approval",
            //                "stateColor" => "#ff7700",
            //                "stateCaption" => "mengetahui",
            //                "allowEdit" => true,
            //            ),
            //            3 => array(
            //                "label" => "PRE PACKING",
            //                "label_nota" => "SURAT JALAN",
            //                "actionLabel" => "process packing",
            //                "source" => "5822so",
            //                "target" => "5822pkd", // packed
            //                "userGroup" => "o_gudang",
            //                "stateLabel" => "packed",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "Dipersiapkan Oleh",
            //                "allowEdit" => true,
            //                "paymentSrc" => array(
            //                    "enabled" => true,
            //                    "filter" => array(
            //                        "label='incoming cash'",
            //                    ),
            //                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
            //                    "label" => "Uang muka belum diterima. Segera hubungi pihak Finance.",
            //                ),
            //                "allowScaner" => true,
            //            ),
            //            4 => array(
            //                "label" => "PACKING LIST",
            //                "label_nota" => "Proforma INVOICE",
            //                "actionLabel" => "process shipment",
            //                "source" => "5822pkd",
            //                "target" => "5822spd", // shipped
            //                "userGroup" => "o_gudang",
            //                "stateLabel" => "shipped",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "Pengirim ",
            //                "autoNextStep" => true,
            //            ),
            //            5 => array(
            //                "label" => "INVOICE",
            //                "actionLabel" => "cetak invoice",
            //                "source" => "5822spd",
            //                "target" => "5822", // invoice
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
        "template"                => "template/transaksi.html",
        "selectorModel"           => "MdlProduk2",
        "selectorSrcModel"        => "MdlProduk2",
        //        "selectorModel" => "MdlLockerStock",
        //        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice"           => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model"     => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label"     => array(
                "jual_reseller",
                "jual_online",
                "jual",
                "ppv",
                "disc",
                "disc_percent"
            ),
            "key_label" => array(
                "jual_reseller" => "jual_reseller",
                "jual_online"   => "jual_online",
                "jual"          => "harga",
                "ppv"           => "ppv",
                "disc"          => "disc",
                "disc_percent"  => "disc (%)",
            ),
            "mainSrc"   => "jual",
        ),
        "lockerCheck"             => array(
            //            "enabled" => true,
            //            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters"         => array(
            //            "stock_locker.cabang_id=placeID",
            //            "stock_locker.gudang_id=gudangID",
            //            "stock_locker.jumlah>.0",
            //            "stock_locker.state=.active",
        ),
        "selectorCaller"          => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"           => "item / produk unit / non unit/jasa",
        "selectorParamFields"     => array(
            "id"          => "id",
            "nama"        => "nama",
            "produk_kode" => "kode",
            // "no_part" => "no_part",
            "satuan"      => "satuan",
            // "jenis" => "jenis",
        ),
        "selectorViewedFields"    => array(
            // "barcode",
            "nama",
            "kode",
            "satuan",
            "kategori_nama",
            // "sub_kategori_nama",
        ),
        "selectorProcessor"       => "_processSelectProduct/select",
        "itemSwapper"             => "_processSelectProduct/multiSelect",
        "swappedKeys"             => array("pihakID", "pihakName"),
        "editHandlerMethod"       => "select",
        //--------------
        "pihakModel"              => "MdlCustomer",
        "pihakCaller"             => "_selectorPihak/selectPihak",
        "pihakLabel"              => "customer",
        "pihakProcessor"          => "_processPihak/select",
        "pihakPair"               => array(
            "enabled"  => true,
            "model"    => "ComRekeningPembantuCustomerDetail",
            "filter"   => array(
                // "extern_id=pihakID",
                "extern2_id=.2010050030",
                "cabang_id=placeID",
            ),
            "rekening" => "2010050",
            "key"      => "qty_kredit",
            "method"   => "fetchBalances",
        ),
        //--------------
        "pihakModelMain"          => "MdlGudangStatus",
        "pihakMainCaller"         => "_selectorPihakMain/selectPihak",
        "pihakMainLabel"          => "pilih dikirim dari...",
        "pihakMainFilters"        => array(),
        "pihakMainProcessor"      => "_processPihakMain/select",
        //--------------
        "selectorLabel2"          => "pilih salesman terdaftar...",
        "selectorModel2"          => "MdlEmployeeSalesman",
        "selectorCaller2"         => "_selectorPihakMain/selectPihakSalesman",
        "selectorProcessor2"      => "_processPihakMain/selectSalesman",
        "selectorParamFields2"    => array(
            "id"   => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields2"   => array(
            "nama",
        ),
        "selectorFilters2"        => array(//            "cabang_id=placeID",
        ),
        //--------------
        "shortHistoryFields"      => array(
            //            "no" => "no",
            //            "jenis_label" => "activity",
            "dtime"          => "date",
            //            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top"      => "pre so number",
            "nomer_soa"      => array(
                "step"  => 2,
                "key"   => "nomer",
                "label" => "SOA number",
            ),
            "nomer_sopkd"    => array(
                "step"  => 3,
                "key"   => "nomer",
                "label" => "pre-packing number",
            ),
            "nomer_sospd"    => array(
                "step"  => 4,
                "key"   => "nomer",
                "label" => "packinglist number",
            ),

            "item_fields"           => "isi",
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
            "seller_nama"           => array(
                "step"  => 1,
                "key"   => "olehName",
                "label" => "sales admin",
            ),
            "salesman_nama"         => "salesman",
            //            "oleh_nama" => "person",
            "jual"                  => "bruto",
            //            "disc"                   => "discount",
            "disc"                  => "diskon tambahan",
            "ppn"                   => "ppn",
            //            "nett2" => "total amount",
            "grand_pembulatan"      => "total amount",
            "paymentMethod__label"  => "payment method",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            "pengirim_nama"         => "nama pengirim",
            "gudang_status_jenis"   => "dikirim dari",
            //            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields"       => array(
            "jenis_label"           => "activity",
            "dtime"                 => "date",
            "status_next"           => "status",
            "cabang_nama"           => "branch",
            "customers_nama"        => "customer",
            "nomer_top"             => "pre so number",
            "nomer"                 => "receipt number",
            "oleh_nama"             => "person",
            "nett1"                 => "amount",
            "disc"                  => "discount",
            "add_diskon"            => "diskon tambahan",
            "ppn"                   => "ppn",
            "nett2"                 => "total amount",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields"           => array(
            1 => array(
                "no"                         => "no",
                "dtime"                      => "date",
                "cabang_nama"                => "branch",
                "customers_nama"             => "customer",
                "customerDetails__kabupaten" => "kota",
                //                "review_details" => "review",
                "nomer_top"                  => "INV number",
                "referenceNumber_current"    => "Reference number",
                "item_fields"                => "isi",
                //                "nomer" => "receipt number",
                //            "transaksi_nilai" => "amount",
                "harga"                      => "bruto",
                "disc"                       => "discount",
                "add_diskon"                 => "diskon tambahan",
                "nett1"                      => "netto",
                "ongkir"                     => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                "grand_ppn"                  => "ppn",
                // "nett2" => "total amount",
                "new_net3"                   => "total amount",
                "paymentMethod__label"       => "payment method",
                "shippingMethod__label"      => "dikirim/diambil sendiri",
                "pengirim_nama"              => "nama pengirim",
                "gudang_status_jenis"        => "dikirim dari",
                "oleh_nama"                  => "person",
                "salesman_nama"              => "salesman",
                "keterangan"                 => "keterangan",
                "print_label"                => "cetak nota",
                //                "print_label_mod" => "cetak nota<br>bruto",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no"             => "no",
                "dtime"          => "date",
                "cabang_nama"    => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top"      => "SO number",
                "nomer"          => "SOA number",
                "item_fields"    => "isi",
                "sales_name"     => "sales admin",
                "salesman_nama"  => "salesman",
                "oleh_nama"      => "person",
                //            "transaksi_nilai" => "amount",
                "harga"          => "bruto",
                "disc"           => "discount",
                "add_diskon"     => "diskon tambahan",
                "nett1"          => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir"                => "shipping service",
                "grand_ppn"             => "ppn",
                "new_net3"              => "total amount",
                "paymentMethod__label"  => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama"         => "nama pengirim",
                "gudang_status_jenis"   => "dikirim dari",
                "keterangan"            => "keterangan",
                "print_label"           => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no"             => "no",
                "dtime"          => "date",
                "cabang_nama"    => "branch",
                "customers_nama" => "customer",
                "nomer_top"      => "SO number",
                "nomer_soa"      => array(
                    "step"  => 2,
                    "key"   => "nomer",
                    "label" => "SOA number",
                ),
                "nomer"          => "PRE-PL number",
                "item_fields"    => "isi",
                "sales_name"     => "sales admin",
                "salesman_nama"  => "salesman",
                "oleh_nama"      => "person",
                //            "transaksi_nilai" => "amount",
                "harga"          => "bruto",
                "disc"           => "discount",
                "add_diskon"     => "diskon tambahan",
                "nett1"          => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir"                => "shipping service",
                "grand_ppn"             => "ppn",
                "new_net3"              => "total amount",
                "paymentMethod__label"  => "payment method",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "gudang_status_jenis"   => "dikirim dari",
                "pengirim_nama"         => array(
                    "step"  => 3,
                    "key"   => "pengirim_nama",
                    "label" => "nama pengirim",
                ),
                "keterangan"            => "keterangan",
                "print_label"           => "tool",
            ),
            4 => array(
                // "jenis_label" => "activity",
                "no"             => "no",
                "dtime"          => "date",
                "cabang_nama"    => "branch",
                "customers_nama" => "customer",
                "nomer_top"      => "SO number",
                "nomer_soa"      => array(
                    "step"  => 2,
                    "key"   => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl"    => array(
                    "step"  => 3,
                    "key"   => "nomer",
                    "label" => "PRE-PL number",
                ),
                "nomer"          => "PL number",
                "item_fields"    => "isi",
                "sales_name"     => "sales admin",
                "salesman_nama"  => "salesman",
                "oleh_nama"      => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                "jual"           => array(
                    "step"             => 5,
                    "key"              => "jual",
                    "label"            => "bruto",
                    "transaksi_jenis2" => array(
                        "paket"     => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                "disc"           => "discount",
                "add_diskon"     => "diskon tambahan",
                "nett1"          => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                "ongkir"                => "shipping service",
                "grand_ppn"             => "ppn",
                "new_net3"              => "total amount",
                "shippingMethod__label" => "dikirim/diambil sendiri",
                "pengirim_nama"         => "nama pengirim",
                "gudang_status_jenis"   => "dikirim dari",
                "keterangan"            => "keterangan",
                "print_label"           => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "no"                         => "no",
                "dtime"                      => "date",
                "cabang_nama"                => "branch",
                "customers_nama"             => "customer",
                "customerDetails__kabupaten" => "kota",
                "review_details"             => "review",
                "nomer_top"                  => "SO number",
                "nomer_soa"                  => array(
                    "step"  => 2,
                    "key"   => "nomer",
                    "label" => "SOA number",
                ),
                "nomer_prepl"                => array(
                    "step"  => 3,
                    "key"   => "nomer",
                    "label" => "PRE-PL number",
                ),
                "ids_his"                    => array(
                    "step"  => 4,
                    "key"   => "nomer",
                    "label" => "PL number",
                ),
                "nomer"                      => "invoice number",
                "item_fields"                => "isi",
                "sales_name"                 => "sales admin",
                "salesman_nama"              => "salesman",
                "logistic"                   => "logistic",
                "oleh_nama"                  => "invoice",
                //            "transaksi_nilai" => "amount",
                //                "jual" => "amount",
                "jual"                       => array(
                    "step"             => 5,
                    "key"              => "jual",
                    "label"            => "bruto",
                    "transaksi_jenis2" => array(
                        "paket"     => "harga_ori",
                        "non paket" => "jual",
                    ),
                ),
                // "harga" => "amount",
                "disc"                       => "discount",
                "add_diskon"                 => "diskon tambahan",
                "nett1"                      => "netto<br>(after discount)",
                // "ppn" => "ppn",
                // "nett2" => "total amount",
                "ongkir"                     => "shipping service",
                "grand_ppn"                  => "ppn",
                "new_net3"                   => "total amount",
                "return"                     => "return",
                "netto_return"               => "netto",
                "shippingMethod__label"      => "dikirim/diambil sendiri",
                "paymentMethod__label"       => "payment method",
                "pengirim_nama"              => "nama pengirim",
                "keterangan"                 => "keterangan",
                "print_label"                => "tool",
            ),
        ),
        "extHistoryFields"        => array(
            1 => array(
                "review_details"  => "id",
                "print_label"     => "nomer",
                "print_label_mod" => "nomer",
            ),
            2 => array(
                "review_details" => "id",
                "print_label"    => "nomer",
            ),
            3 => array(
                "print_label" => "nomer",
            ),
            4 => array(

                "print_label" => "nomer",
            ),
            5 => array(
                "review_details" => "id",
                "print_label"    => "nomer",
            ),
        ),
        "extHistoryKeterangan"    => array(
            1 => array(
                "edit" => array(
                    "kolom"  => "status_edit",
                    "value"  => "1",
                    "labels" => array(
                        "edit_name", "edit_dtime",
                    ),
                    "style"  => array(
                        "bgcolor" => "yellow",
                        "color"   => "red",
                    ),
                ),
            ),
            4 => array(
                "return" => array(
                    "kolom"  => "returned",
                    "value"  => "1",
                    "labels" => "RETURNED",
                    "style"  => array(
                        "bgcolor" => "orange",
                        "color"   => "black",
                    ),
                ),
            ),
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields"        => array(
            "produk_kode" => "sku",
            "barcode"     => "barcode",
            "nama"        => array(
                "label"  => "product",
                "addKey" => "keterangan",
            ),
            "jml"         => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //replacer kolom....
        "historyFieldsReplacer"   => array(
            "harga"     => "sisa-ppn",
            "nett1"     => "sisa-ppn",
            "grand_ppn" => "ppn",
            "new_net3"  => "nilai_entry",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields"    => array("id", "nama"),

        "shoppingCartFields"                  => array(
            1 => array(
                "barcode"     => "barcode",
                "nama"        => array(
                    "label"  => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "stok_center" => "stok dc",
                "stok"        => "stok<br>tersedia",
                "jml"         => "qty",
                "satuan"      => "uom",
            ),
            2 => array(
                "barcode"     => "barcode",
                "nama"        => array(
                    "label"  => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "jml"         => "qty",
                "satuan"      => "uom",
            ),
            3 => array(
                "barcode"     => "sku",
                "nama"        => array(
                    "label"  => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part"     => "part number",
                "max_jml"     => "SO",
                "jml"         => "qty",
                "satuan"      => "uom",
            ),
            4 => array(
                "barcode"     => "sku",
                "nama"        => array(
                    "label"  => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part"     => "part number",
                "jml"         => "qty",
                "satuan"      => "uom",
            ),
            5 => array(
                "barcode"     => "sku",
                "nama"        => array(
                    "label"  => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "no_part"     => "part number",
                "jml"         => "qty",
                "satuan"      => "uom",
            ),
        ),
        "shoppingCartFieldSrc"                => array(
            "nama"              => "nama",
            "produk_kode"       => "kode",
            "no_part"           => "no_part",
            "label"             => "label",
            "satuan"            => "satuan",
            "ppn"               => "harga*(ppnFactor/100)",
            "barcode"           => "barcode",
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
            "jenis"             => "jenis",
            "produk_jenis_id"   => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "jml_serial"        => "jml_serial",
            "kategori_id"       => "kategori_id",
            "kategori_nama"     => "kategori_nama",
            //-------------------
            "part_id_1"         => "part_id_1",
            "part_nama_1"       => "part_nama_1",
            "part_barcode_1"    => "part_barcode_1",
            "part_id_2"         => "part_id_2",
            "part_nama_2"       => "part_nama_2",
            "part_barcode_2"    => "part_barcode_2",
            "heater_id"         => "heater_id",
            "heater_nama"       => "heater_nama",
            "heater_barcode"    => "heater_barcode",
            "outdoor_id"        => "outdoor_id",
            "outdoor_nama"      => "outdoor_nama",
            "outdoor_barcode"   => "outdoor_barcode",
            "outdoor_sku"       => "outdoor_sku",
            "indoor_id_1"       => "indoor_id_1",
            "indoor_nama_1"     => "indoor_nama_1",
            "indoor_barcode_1"  => "indoor_barcode_1",
            "indoor_sku_1"      => "indoor_sku_1",
            "indoor_id_2"       => "indoor_id_2",
            "indoor_nama_2"     => "indoor_nama_2",
            "indoor_barcode_2"  => "indoor_barcode_2",
            "indoor_sku_2"      => "indoor_sku_2",
            "indoor_id_3"       => "indoor_id_3",
            "indoor_nama_3"     => "indoor_nama_3",
            "indoor_barcode_3"  => "indoor_barcode_3",
            "indoor_sku_3"      => "indoor_sku_3",
            "indoor_id_4"       => "indoor_id_4",
            "indoor_nama_4"     => "indoor_nama_4",
            "indoor_barcode_4"  => "indoor_barcode_4",
            "indoor_sku_4"      => "indoor_sku_4",
            "qty_outdoor"       => "qty_outdoor",
            "qty_indoor"        => "qty_indoor",
            "keterangan"        => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor"    => "sub_qty_indoor",
            "sub_qty_outdoor"   => "sub_qty_outdoor",
            //-------------------
        ),
        "shoppingCartNumFields"               => array(
            //HEADER FORM SHOPPINGCART DIATUR BERDASARKAN STEP
            1 => array(
                //                "harga" => "harga jual",
                "harga_include_ppn" => "harga jual<br>incl. ppn",
                "disc_percent"      => "disc (%)",
                //                "disc" => "disc (IDR)", // aslinya ini
                "_diskon_ppn"       => "disc (IDR)<br>Incl.PPN",
                // "ppn" => "VAT",
                //                "nett1" => "jual(net)<br>Incl.PPN",
                "nett1_ppn"         => "jual(net)<br>Incl.PPN",
                // "jual_reseller" => "jual reseller",
            ),
            2 => array(
                "stok_center"       => "stok dc",
                "stok"              => "stok available",
                //                "harga" => "price",
                "harga_include_ppn" => "harga jual<br>incl. ppn",
                "disc_percent"      => "disc (%)",
                //                "disc" => "disc (IDR)",
                "_diskon_ppn"       => "disc (IDR)",
                "premi_percent"     => "premi%",
                "premi"             => "premi",
                //                "nett1" => "price(net)",
                "nett1_ppn"         => "jual(net)<br>Incl.PPN",
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
        "shoppingCartEditableFields"          => array(
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
        "shoppingCartAmountValue"             => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount"           => array(
            1 => false,
            2 => false,
            3 => true,
            4 => true,
            5 => false,
        ),
        "shoppingCartSumFields"               => array(
            1 => array(
                "harga"                => "total bruto",
                //                "disc" => "disc",
                // "add_diskon" => "diskon tambahan",
                // "total_diskon" => "diskon",
                // -----------------
                "jml_kategori_unit"    => "jumlah unit",
                "diskon_kategori_unit" => "nilai diskon unit",
                //---------------
                //                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat"          => "total netto",
                "ppn_out_bulat"        => "PPN",
                //                "grand_pembulatan" => "grand total<br>include ppn",
                "nett1_include_ppn"    => "grand total<br>include ppn",
                "point_saldo_awal"     => "point awal",
                //                ".6640" => "point awal",
                "point_transaksi"      => "point transaksi",
                "point_saldo_akhir"    => "point akhir",
                //                "point_saldo_akhir+6640" => "point akhir",
            ),
            2 => array(
                "harga"             => "total amount bruto",
                //                "disc"             => "disc",
                "disc"              => "diskon tambahan",
                //                "ongkir_ui" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "nett1_bulat"       => "nett amount",
                "ppn_out_bulat"     => "PPN",
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
                ////                "shipping_service" => "Shipping Service",
                //                "grand_total_ui" => "Amount",
                //                //                "nilai_pembulatan" => "Pembulatan",
                //                "nett1_bulat" => "Total Amount",
                //                "ppn_out_bulat" => "VAT",
                //                "grand_pembulatan" => "Grand Total",
                "harga"             => "total amount bruto",
                "disc"              => "diskon tambahan",
                "nett1_bulat"       => "nett amount",
                "ppn_out_bulat"     => "PPN",
                "nett1_include_ppn" => "nett amount + PPN",
            ),

        ),
        "shoppingCartUnionSelectors"          => array(
            1 => array(
                "base"    => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents"             => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=addCommas(((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))/100))",

                //untuk versi PPN
                "disc_percent" => "document.getElementById('{_diskon_ppn}').value=addCommas(((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga_include_ppn}').innerHTML)))/100))",

                "disc"        => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
                "_diskon_ppn" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga_include_ppn}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
                "nett1"       => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",
            ),
        ),
        "shoppingCartFieldValidators"         => array(
            "jml"   => "quantity",
            "harga" => "harga jual",
            // "disc_percent" => "disc(%)",
            // "disc" => "disc(IDR)",
        ),
        "shoppingCartRowValidators"           => array(
            "pihakID"   => "customer ID",
            "pihakName" => "customer name",
        ),
        "shopingCartValidatorExt"             => array(
            "disc_percent" => array(
                "name" => "disc(%)",
                "min"  => ".0",
                "max"  => ".100",
            ),

            "disc" => array(
                "name" => "disc(IDR)",
                "min"  => ".0",
                "max"  => "harga",
            ),
        ),
        "shoppingCartRowOptionalValidators"   => array(
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
        "shoppingCartSumEditableFields"       => array(
            //            1 => array(
            //                "add_diskon",
            //            ),
            //
        ),
        "shoppingCartSubDetailFields"         => array(
            3 => array(
                "nama"        => array(
                    "source" => "items3_sum",
                    "tipe"   => "textarea",
                    "gate"   => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe"   => "textarea",
                    "gate"   => "produk_serial",
                ),
            ),
            4 => array(
                "nama"        => array(
                    "source" => "items3_sum",
                    "tipe"   => "textarea",
                    "gate"   => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe"   => "textarea",
                    "gate"   => "produk_serial",
                ),
            ),
        ),
        "shoppingCartNoteAdditionalEditabled" => array(
            //            3 => array(
            //                "enabled" => true,
            //                "editabled" => true,
            //            ),
            4 => array(
                "previews"  => true,
                "enabled"   => true,
                "editabled" => true,
            ),
            5 => array(
                "previews"  => true,
                "enabled"   => false,
                "editabled" => false,
            ),
        ),
        "shoppingCartHideGudangStatusKey"     => "pihakMainJenis",
        "shoppingCartHideGudangStatus"        => array(
            "cabang" => array(
                "stok_center",
            ),
            "pusat"  => array(
                "stok",
            ),
        ),
        "shoppingCartCopySerialNumber"        => array(
            3 => array(
                "statusGudang"   => "pusat",
                "copyGate"       => "items2,items3_sum",
                "copyGateSerial" => "items3_sum",
                "copyJenis"      => "585",
            ),
        ),
        "receiptElements"                     => array(
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
                "inputType"   => "radio",
                "label"       => "Status Gudang",
                "mdlName"     => "MdlGudangStatus",
                "mdlFilter"   => array("id=pihakMainID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "nama",
                ),
                "editPoints"  => array(1,),
                //                "reloadLink" => "_processPihak/select/",
                "noValidate"  => true,
            ),
            "salesmanDetails"     => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "Salesman",
                "mdlName"     => "MdlEmployeeSalesman",
                "mdlFilter"   => array("id=pihakMain2ID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "nama",
                ),
                "editPoints"  => array(1,),
                //                "reloadLink" => "_processPihak/select/",
                "noValidate"  => true,
            ),
            "shippingMethod"      => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "dikirim / diambil sendiri",
                "mdlName"     => "MdlShippingMethodStatic",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "name",
                "description" => "",
                "usedFields"  => array(
                    "name" => "",
                ),
                "editPoints"  => array(1),
                "noValidate"  => true,
            ),
            "customerDetails"     => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "Detil Konsumen",
                "mdlName"     => "MdlCustomer_and_pre",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"         => "nama",
                    "alamat_1"     => "alamat",
                    "alamat_2"     => "alamat",
                    "kelurahan"    => "Kel",
                    "kecamatan"    => "Kec",
                    "kabupaten"    => "Kab",
                    "propinsi"     => "Prop",
                    "tlp"          => "Tlp",
                    "tlp_1"        => "Tlp",
                    "tlp_2"        => "Handphone",
                    "npwp"         => "NPWP",
                    "no_ktp"       => "nik",
                    "nik"          => "NIK",
                    //---------------
                    "kredit_limit" => "KREDIT LIMIT",
                ),
                "editPoints"  => array(1, 2, 3, 4),
                "reloadLink"  => "_processPihak/select/",
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
            "paymentMethod"       => array(
                "elementType"         => "dataModel",
                "inputType"           => "radio",
                "label"               => "payment method",
                "mdlName"             => "MdlPaymentMethod",
                "key"                 => "id",
                "labelSrc"            => "name",
                "usedFields"          => array(
                    "name" => "Name",
                    "note" => "Note",
                ),
                "defaultValue"        => "defaultPaymentMethod",
                "editPoints"          => array(1),
                "kreditLimitValidate" => array(
                    1 => array(
                        "enabled" => true,
                    ),
                ),
                "noValidate"          => true,
            ),
            "dummyElement"        => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "relativeElements"                    => array(
            "paymentMethod"  => array(
                "cash"   => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "radio",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount_cash_and_in",
                        "mdlFilter"   => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1, 4),
                    ),
                ),
                "cia"    => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "radio",
                        "label"       => "bank account",
                        //                        "mdlName" => "MdlBankAccount_in",
                        "mdlName"     => "MdlBankAccount_cash_and_in",
                        "mdlFilter"   => array(
                            "cabang_id=placeID",
                            //                            "currency_id=.0",
                            "jenis2=.1",
                        ),
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
                "credit" => array(
                    "billingDetails" => array(
                        "elementType"  => "dataModel",
                        "elementJoint" => array(
                            "method"     => "lookUpJointCustomer",
                            "mdlFilter"  => "id=pihakID",
                            "usedFields" => array(
                                "no_ktp" => "nik",
                                "nik"    => "nik",
                                "npwp"   => "npwp",
                            ),
                        ),
                        "inputType"    => "radio",
                        "label"        => "Detail Penagihan",
                        "mdlName"      => "MdlCustomerBillAddress",
                        "mdlFilter"    => array("extern_id=pihakID"),
                        //                "mdlName" => "MdlCustomer_and_pre",
                        //                "mdlFilter" => array("id=pihakID"),
                        "key"          => "id",
                        //                "labelSrc" => "alias",
                        "labelSrc"     => "alias",
                        "usedFields"   => array(
                            "extern_name" => "Customer",
                            "alias"       => "Attn*",
                            "alamat"      => "Addr",
                            "kelurahan"   => "Kel",
                            "kecamatan"   => "Kec",
                            "kabupaten"   => "Kab",
                            "propinsi"    => "Prop",
                            "tlp"         => "Phone",
                            "tlp_1"       => "Phone",
                            "tlp_2"       => "Handphone",
                            "npwp"        => "NPWP",
                            "no_ktp"      => "NIK",
                        ),
                        "editPoints"   => array(),
                        "optionPoints" => array(
                            "label"      => array(
                                "lengkap"   => 1,
                                "simple"    => 2,
                                "no detail" => 3,
                            ),
                            "labelTitle" => array(
                                "lengkap"   => "semua data delivery address akan ditampilkan.",
                                "simple"    => "hanya attn/pic dan nomor telepon yang akan ditampilkan.",
                                "no detail" => "tidak ada yang akan ditampilkan.",
                            ),
                            "step"       => "5",
                            "hidden"     => array(
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
                        "inputType"   => "radio",
                        "label"       => "Detail Pengiriman",
                        "mdlName"     => "MdlCustomerAddress",
                        "mdlFilter"   => array(
                            "extern_id=pihakID",
                        ),
                        "key"         => "id",
                        "labelSrc"    => "alias",
                        "usedFields"  => array(
                            "alias"     => "Attn",
                            "alamat"    => "Alamat",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi"  => "propinsi",
                            "tlp"       => "Tlp",
                            "tlp_2"     => "Handphone",
                            //                    "npwp" => "NPWP",
                            //                    "propinsi" =>"",
                        ),
                        "editPoints"  => array(1, 2, 3, 4),
                    ),
                    "shippingDate"    => array(
                        "elementType"  => "dataField",
                        "label"        => "rencana tanggal pengiriman",
                        "inputType"    => "date",
                        //                        "defaultValue" => date("d/m/Y"),
                        "defaultValue" => date("Y-m-d"),
                        "editPoints"   => array(1),
                    ),
                ),
            ),
        ),
        "relativeOptions"                     => array(),
        "updateDueDate"                       => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment"                   => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate"                     => array(
            4 => true,
        ),
        "xShipmentConfig"                     => array(
            1 => array(
                "enabled"           => true,
                "label"             => "close/fullfillment sales order",
                "targetJenisMaster" => "19822",
                "warning"           => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups"     => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
            2 => array(
                "enabled"           => true,
                "label"             => "close/fullfillment sales order",
                "targetJenisMaster" => "19822",
                "warning"           => "You may cancel the Packing with the remaining items that have not been Packed to Shipping. continue cancel this packing?",
                "allowedGroups"     => array(
                    "o_gudang", "o_gudang_spv", "o_finance"
                ),
            ),
        ),

        "validateMeasurement"              => array(
            //            1 => array(
            //                "panjang_gross" => "CKD length (in millimeters)",
            //                "lebar_gross" => "CKD width (in millimeters)",
            //                "tinggi_gross" => "CKD height (in millimeters)",
            //            ),
        ),
        "validateReceiveElement"           => array(
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
        "pairRegistries"                   => array(
            "tableIn_master_values", "main", "items", "items3_sum"
        ),
        "pairMakers"                       => array(
            1 => array(
                "stokProduk"        => array(
                    "helperName"   => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state"     => ".active",
                        "jenis"     => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName"   => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params"       => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state"     => ".active",
                        "jenis"     => ".produk",
                    ),
                ),

            ),
            2 => array(
                "stokProduk"        => array(
                    "helperName"   => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state"     => ".active",
                        "jenis"     => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName"   => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params"       => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state"     => ".active",
                        "jenis"     => ".produk",
                    ),
                ),
                "dataProduk"        => array(
                    "helperName"   => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params"       => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom"        => array(
                        "no_part",
                    ),
                ),
            ),
            3 => array(
                "stokProduk"        => array(
                    "helperName"   => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state"     => ".active",
                        "jenis"     => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName"   => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params"       => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state"     => ".active",
                        "jenis"     => ".produk",
                    ),
                ),
                "dataProduk"        => array(
                    "helperName"   => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params"       => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom"        => array(
                        "no_part",
                    ),
                ),
            ),
            4 => array(
                "stokProduk"        => array(
                    "helperName"   => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state"     => ".active",
                        "jenis"     => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName"   => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params"       => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state"     => ".active",
                        "jenis"     => ".produk",
                    ),
                ),
                "dataProduk"        => array(
                    "helperName"   => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params"       => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom"        => array(
                        "no_part",
                    ),
                ),
            ),
        ),
        "pairInjectors"                    => array(
            1 => array(
                "stokProduk"        => array(
                    "items" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk"        => array(
                    "items" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk"        => array(
                    "items" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            4 => array(
                "stokProduk"        => array(
                    "items" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
        ),
        "validationRules"                  => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "connectedDiscount"                => array(
            "enabled"         => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource"   => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows"                   => array(
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
        "resumeFieldNames"                 => array(
            "selectFields" => "customers_nama",
            "title"        => "customer",
        ),
        "settlementHistoryFields"          => array(
            "dtime"          => "time",
            "nomer"          => "receipt number",
            "customers_nama" => "customer",
            "jenis_label"    => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga"          => "orig. value",
            "disc"           => "discount",
            "nett1"          => "nett",
            "ppn"            => "ppn",
            "nett2"          => "total",
        ),
        "allowedMainEdit"                  => array("1", "2"),
        "addMainStep"                      => array(
            "749" => array(
                "jenis_master" => "5822",
                "jenis"        => "5822",
                "target"       => "749",
                "status_4"     => "1",
                "trash_4"      => "0",
            ),
        ),
        "compactHistoryFields"             => array(
            "transaksi" => array(
                "label"     => "transaksi",
                "target"    => "transaksi",
                "srcKey"    => "id_master",
                "addFields" => "sales",
                "fields"    => array(
                    "cabang_nama"           => "cabang",
                    // "nomer_top" => "nomer",
                    // "dtime" => "approved",
                    // "seller_nama" => array(
                    //     "step" => 1,
                    //     "key" => "olehName",
                    //     "label" => "salesman",
                    // ),
                    "seller_nama"           => "sallesman",
                    // "oleh_nama" => "approval",
                    "customers_nama"        => "customer",
                    // "outstanding_nilai_items" => "nilai",
                    "outstanding_items"     => "detail items",
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
        "followupMainEditable"             => "_followupLiveEdit/updateMainFieldByStep/",
        //connecto ke pusat jika ada produk yang perlu direpack dipusat untuk penjualan produk komposit
        //        "extConnectTo" => array(
        //            2 => array(
        //                "connecTo" => "773",
        //            ),
        //        ),
        "previewCtr"                       => "Create",
        //----
        "connectToEdit"                    => array(
            1 => array(
                "enabled"   => true,
                "connectTo" => "4822e",
                "label"     => "EDIT INVOICE",
            ),
        ),
        "connectToReject"                  => array(
            1 => array(
                "enabled"   => true,
                "connectTo" => "4822rj",
                "label"     => "REJECT INVOICE",
            ),
        ),
        //----
        "autoApprove"                      => array(
            1 => false,
        ),
        "kreditLimitValidate"              => array(
            //            1 => array(
            //                "enabled" => true,
            //            ),
            //
            3 => array(
                "enabled" => true,
                //                "label" => "Transaksi ini sedang ditindaklanjuti oleh pihak finance.<br>Silahkan konfirmasi ke pihak finance.",
            ),
        ),
        "shopingCartReload"                => true,
        //----
        "produkUnitPart"                   => array(
            "outdoor" => array(
                "outdoor_id" => "outdoor_sku",
            ),
            "indoor"  => array(
                "indoor_id_1" => "indoor_sku_1",
                "indoor_id_2" => "indoor_sku_2",
                "indoor_id_3" => "indoor_sku_3",
                "indoor_id_4" => "indoor_sku_4",
            ),
            "heater"  => array(
                "heater_id" => "heater_nama",
            ),
            "part"    => array(
                "produk_part_id_1" => "produk_part_nama_1",
                "produk_part_id_2" => "produk_part_nama_2",
                "produk_part_id_3" => "produk_part_nama_3",
            ),
        ),
        //----
        "allowNextStepOtorisasi"           => array(
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
        "deliveryParsial"                  => array(
            3 => array(
                "enabled" => false,
            ),
        ),
        //----
        "serialNumberValidator"            => array(
            3 => array(
                "enabled" => true,
                "source"  => "items2",
                "label"   => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",
            ),
        ),
        "autoOtorisasiSesiValidate"        => array(
            3 => array(
                "enabled" => true,
            ),
        ),
        //----ini yg ngatud tab-tab header transaksi in proses --------------------
        "feederTransaksiJenis"             => array(
            0 => array(
                "label"     => "pre sales order",
                "target"    => "5822spo",
                "attr"      => "style='background-color: lightgray;'",
                "header"    => array(
                    "dtime"                 => "date",
                    "customers_nama"        => "customer",
                    "nomer"                 => array(
                        "step"  => 1,
                        "key"   => "nomer",
                        "label" => "nomer",
                    ),
                    "reference_nomer"       => "Referensi SO",
                    //                    "nomer_soa" => array(
                    //                        "step" => 2,
                    //                        "key" => "nomer",
                    //                        "label" => "SOA number",
                    //                    ),
                    //                    "nomer_sopkd" => array(
                    //                        "step" => 3,
                    //                        "key" => "nomer",
                    //                        "label" => "pre-packing number",
                    //                    ),
                    //                    "nomer_sospd" => array(
                    //                        "step" => 4,
                    //                        "key" => "nomer",
                    //                        "label" => "packinglist number",
                    //                    ),
                    "item_fields"           => "isi",
                    "nett1"                 => "bruto",
                    //                    "disc" => "diskon tambahan",
                    "ppn"                   => "ppn",
                    "new_net3"              => "total amount",
                    "paymentMethod__label"  => "payment method",
                    "shippingMethod__label" => "dikirim/diambil sendiri",
                    "pengirim_nama"         => "nama pengirim",
                    "gudang_status_jenis"   => "dikirim dari",
                    "seller_nama"           => array(
                        "step"  => 1,
                        "key"   => "olehName",
                        "label" => "sales admin",
                    ),
                    "salesman_nama"         => "salesman",
                ),
                "diskripsi" => array(
                    "text" => "Transaksi <b>Pre SO</b> belum bisa dibuat Invoice",
                ),
            ),
            1 => array(
                "label"     => "sales order",
                "target"    => "5822so",
                "attr"      => "style='background-color: lightgray;'",
                "header"    => array(
                    "dtime"                 => "date",
                    "customers_nama"        => "customer",
                    "nomer"                 => array(
                        "step"  => 1,
                        "key"   => "nomer",
                        "label" => "nomer",
                    ),
                    "reference_nomer"       => "Referensi SO",
                    //                    "nomer_soa" => array(
                    //                        "step" => 2,
                    //                        "key" => "nomer",
                    //                        "label" => "SOA number",
                    //                    ),
                    //                    "nomer_sopkd" => array(
                    //                        "step" => 3,
                    //                        "key" => "nomer",
                    //                        "label" => "pre-packing number",
                    //                    ),
                    //                    "nomer_sospd" => array(
                    //                        "step" => 4,
                    //                        "key" => "nomer",
                    //                        "label" => "packinglist number",
                    //                    ),
                    "item_fields"           => "isi",
                    "nett1"                 => "bruto",
                    //                    "disc" => "diskon tambahan",
                    "ppn"                   => "ppn",
                    "new_net3"              => "total amount",
                    "paymentMethod__label"  => "payment method",
                    "shippingMethod__label" => "dikirim/diambil sendiri",
                    "pengirim_nama"         => "nama pengirim",
                    "gudang_status_jenis"   => "dikirim dari",
                    "seller_nama"           => array(
                        "step"  => 1,
                        "key"   => "olehName",
                        "label" => "sales admin",
                    ),
                    "salesman_nama"         => "salesman",
                ),
                "diskripsi" => array(
                    "text" => "Transaksi <b>Sales Order</b> belum bisa dibuat INVOICE",
                ),
            ),
            2 => array(
                "label"     => "PENJUALAN TUNAI",
                "target"    => "4464",
                "cli"       => true,
                "header"    => array(
                    "dtime"                 => "date",
                    "customers_nama"        => "customer",
                    "nomer"                 => array(
                        "step"  => 1,
                        "key"   => "nomer",
                        "label" => "nomer",
                    ),
                    "reference_nomer"       => "Referensi SO",

                    //                    "nomer_soa" => array(
                    //                        "step" => 2,
                    //                        "key" => "nomer",
                    //                        "label" => "SOA number",
                    //                    ),
                    //                    "nomer_sopkd" => array(
                    //                        "step" => 3,
                    //                        "key" => "nomer",
                    //                        "label" => "pre-packing number",
                    //                    ),
                    //                    "nomer_sospd" => array(
                    //                        "step" => 4,
                    //                        "key" => "nomer",
                    //                        "label" => "packinglist number",
                    //                    ),
                    "item_fields"           => "isi",
                    "nett1"                 => "bruto",
                    //                    "disc" => "diskon tambahan",
                    "ppn"                   => "ppn",
                    "new_net3"              => "total amount",
                    "paymentMethod__label"  => "payment method",
                    "shippingMethod__label" => "dikirim/diambil sendiri",
                    "pengirim_nama"         => "nama pengirim",
                    "gudang_status_jenis"   => "dikirim dari",
                    "seller_nama"           => array(
                        "step"  => 1,
                        "key"   => "olehName",
                        "label" => "sales admin",
                    ),
                    "salesman_nama"         => "salesman",

                ),
                "diskripsi" => array(
                    "text" => "Transaksi <b>Penjualan Tunai</b> yang bisa dibuat INVOICE adalah transksi yang sudah LUNAS",
                ),
            ),
            3 => array(
                "label"     => "PACKINGLIST",
                "target"    => "5822spd",
                "cli"       => true,
                "header"    => array(
                    "dtime"                 => "date",
                    "customers_nama"        => "customer",
                    //                    "nomer_top" => "Nomer",
                    //                    "nomer_soa" => array(
                    //                        "step" => 2,
                    //                        "key" => "nomer",
                    //                        "label" => "SOA number",
                    //                    ),
                    //                    "nomer_sopkd" => array(
                    //                        "step" => 3,
                    //                        "key" => "nomer",
                    //                        "label" => "pre-packing number",
                    //                    ),
                    "nomer_sospd"           => array(
                        "step"  => 4,
                        "key"   => "nomer",
                        "label" => "packinglist number",
                    ),
                    "nomer_so"              => array(
                        "step"  => 2,
                        "key"   => "nomer",
                        "label" => "sales order number",
                    ),
                    "item_fields"           => "isi",
                    "jual"                  => "bruto",
                    "disc"                  => "diskon tambahan",
                    "ppn"                   => "ppn",
                    "grand_pembulatan"      => "total amount",
                    "paymentMethod__label"  => "payment method",
                    "shippingMethod__label" => "dikirim/diambil sendiri",
                    "pengirim_nama"         => "nama pengirim",
                    "gudang_status_jenis"   => "dikirim dari",
                    "seller_nama"           => array(
                        "step"  => 1,
                        "key"   => "olehName",
                        "label" => "sales admin",
                    ),
                    "salesman_nama"         => "salesman",

                ),
                "diskripsi" => array(
                    "text" => "Transaksi <b>Penjualan Kredit</b> yang bisa dibuat INVOICE adalah transksi yang sudah DIKIRIM",
                ),
            ),
            4 => array(
                "label"     => "TERMIN",
                "target"    => "7499",
                "cli"       => true,
                "header"    => array(
                    "dtime"                 => "date",
                    "customers_nama"        => "customer",
                    "nomer"                 => array(
                        "step"  => 1,
                        "key"   => "nomer",
                        "label" => "nomer",
                    ),
                    "item_fields"           => "isi",
                    "jual"                  => "bruto",
                    "disc"                  => "diskon tambahan",
                    "ppn"                   => "ppn",
                    "grand_pembulatan"      => "total amount",
                    "paymentMethod__label"  => "payment method",
                    "shippingMethod__label" => "dikirim/diambil sendiri",
                    "pengirim_nama"         => "nama pengirim",
                    "gudang_status_jenis"   => "dikirim dari",
                    "seller_nama"           => array(
                        "step"  => 1,
                        "key"   => "olehName",
                        "label" => "sales admin",
                    ),
                    "salesman_nama"         => "salesman",

                ),
                "diskripsi" => array(
                    "text" => "Transaksi <b>projek</b> yang bisa dibuat INVOICE adalah transksi yang sudah DITERBITKAN TERMIN",
                ),
            ),
        ),
        /* --------------------
         * akan berpengaruh pada data yg ditampilkan, tapi header diatur oleh aray diatasnya
         * --------------------*/
        "feederTransaksiReplacerStepJenis" => array(
            "5822spo" => 0,
            "5822so"  => 1,
            "4464"    => 2,
            "5822spd" => 3,
            "7499"    => 4,
        ),
    ),


);