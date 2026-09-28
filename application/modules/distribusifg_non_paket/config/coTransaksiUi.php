<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    "583" => array(
        "icon" => "fa fa-truck",
        "label" => "fg distribution",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "fg distribution request",
                "actionLabel" => "request",
                "source" => "",
                "target" => "583r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "distribution (scan qr/barcode)",
                "actionLabel" => "save scan qr/barcode",
                "source" => "583r",
                "target" => "583sc",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
//                "allowEdit" => false,
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "distribution authorization(auto)",
                "actionLabel" => "approve distribution",
                "source" => "583sc",
                "target" => "583",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
//                "allowEdit" => false,
                "autoNextStep" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("jual"),
            "key_label" => array(
                "jual" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
//            "enabled" => true,
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
//            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "silahkan diisi dengan item ID, nama, kode...",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
            "id",
            "keterangan",
            "kode",
            "kategori_nama",
            "sub_kategori_nama",
            "satuan",
            "jumlah",
        ),
        "selectorLinkMutasi" => array(
            "enabled" => true,
            "link" => "Ledger/viewMoveDetails/RekeningPembantuProduk/1010030030/",//persediaan produk
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "jenis=.cabang",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "requestReferenceNomer" => "referensi SO",
            "customerName" => "konsumen",
            "salesmanName" => "salesman",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "583r" => "request number",
            "583" => "approval number",
            //            "585r" => "request number",
            "585" => "receipt number",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortStatusFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
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
        //end
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "current_stok" => "stock<BR>available",
                "intransit_stok" => "stock<BR>intransit",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            3 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
//            "stok" => "stock<BR>after",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
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
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
//                "jml",
            ),
            3 => array(
                //            "harga",
                //            "ppn",
//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
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
        ),

        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),

        "connectTo" => "585",
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),
        "pairMakers" => array(
            1 => array(
//                "stokProduk" => array(
//                    "helperName" => "he_cek_stock_produk_locker",
//                    "functionName" => "cekStockProdukLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "jenis" => ".produk",
//                        "state" => ".active",
//                    ),
//                    "gate" => "items",
//                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                    ),
                    "gate" => "items",
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
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
//                        "jenis" => ".produk",
//                        "state" => ".active",
                    ),
//                    "kolom" => array(
////                        "no_part",
//                    ),
                    "gate" => "items",
                ),
            ),
            2 => array(
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
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "intransit_stok",
                    ),
                ),
            ),
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "cabang_nama" => "sender",
            "cabang2_nama" => "recipient",
            //            "suppliers_nama" =>"vendor",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),

        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "583re",
                "label" => "EDIT distribution request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "583rrj",
                "label" => "REJECT distribution authorization",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "583scrj",
                "label" => "REJECT scan qr/barcode",
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
        //----
        "shortRequestFields2" => array(
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
            "seller_nama" => array(
                "step" => 1,
                "key" => "olehName",
                "label" => "sales admin",
            ),
            "oleh_nama" => "person",
            "salesman_nama" => "salesman",
//            "jual" => "bruto",
//            "disc" => "diskon tambahan",
//            "ppn" => "ppn",
//            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            "shippingMethod__label" => "dikirim/diambil sendiri",
            "pengirim_nama" => "nama pengirim",
//            "next_pic" => "Next step otorisator",

        ),
        "shortRequestFields2Config" => array(
            "enabled" => true,
            "filter" => array(
                "transaksi.jenis=5822so",
                "transaksi.gudang_status_jenis=pusat",
                "transaksi_data.produk_ord_kurang>0",
            ),
            "linkSwapper" => "Create/swapFrom/",
            "lockerTransaksi" => true,
            "labelWarning" => "* Transaksi sedang diedit di cabang.<br>Proses tidak bisa dilanjutkan.",
            "labelHeader" => "JOBLIST YANG PERLU DIKIRIM DARI GUDANG DC/PUSAT KE KONSUMEN ",
        ),
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "customerID" => "pihakID",
            "customerName" => "pihakName",
            "salesmanID" => "pihakMain2ID",
            "salesmanName" => "pihakMain2Name",
            "pihakID" => "placeID",
            "pihakName" => "placeName",

            "pihakMain2ID" => "pihakMain2ID",
            "pihakMain2Name" => "pihakMain2Name",
            "pihakMainID" => "pihakMainID",
            "pihakMainName" => "pihakMainName",
            "pihakMainJenis" => "pihakMainJenis",
        ),
        "shopingCartReload" => true,
        "shopingCartReferenceValidator" => array(
            "enabled" => true,
            "key" => "requestReferenceID",
            "allowIncrement" => false,
            "fields" => "disabled",
        ),
        "shopingCartStockValidate" => array(
            1 => true,
        ),
        "serialNumberLocker" => array(
            "enabled" => false,
        ),
        //----
        "shortDirectFields2" => array(
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "customers_nama" => "konsumen",
//            "nomer_top" => "SO number",
            // sumber dari kolom id_his
            "nomer_poa" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "nomer PO",
            ),
            "reference_nomer" => "nomer SO",

//            "seller_nama" => array(
//                "step" => 1,
//                "key" => "olehName",
//                "label" => "sales admin",
//            ),
            "oleh_nama" => "person",
//            "salesman_nama" => "salesman",
            "item_fields" => "isi",
//            "paymentMethod__label" => "payment method",
//            "shippingMethod__label" => "dikirim/diambil sendiri",
//            "pengirim_nama" => "nama pengirim",
        ),
        "shortDirectFields2Config" => array(
            "enabled" => true,
            "filter" => array(
                "transaksi.jenis=467",
                "transaksi.reference_terima_barang=1",
//                "transaksi.gudang_status_jenis=pusat",
                "transaksi_data.produk_ord_kurang>0",
            ),
            "linkSwapper" => "Create/swapDirectFrom/",
        ),
        "itemDirectSwapper" => "_processSelectProduct/multiSelect",
        "directSwappedKeys" => array(
            "customerID" => "reference_customers_id",// konsumen pihakID
            "customerName" => "reference_customers_nama",// konsumen pihakName
            "salesmanID" => "reference_salesman_id",// salesman
            "salesmanName" => "reference_salesman_id",// salesman
            "pihakID" => "reference_cabang_id",// cabang tujuan distribusi placeID
            "pihakName" => "reference_cabang_nama",// cabang tujuan distribusi placeName

            "pihakMain2ID" => "reference_salesman_id",// salesman
            "pihakMain2Name" => "reference_salesman_id",// salesman
            "pihakMainID" => "reference_gudang_status_id",// dikirim dari
            "pihakMainName" => "reference_gudang_status_nama",// dikirim dari
            "pihakMainJenis" => "reference_gudang_status_jenis",// dikirim dari
            "terima_barang" => "terima_barang",// dikirim dari
        ),
        "scanQrCart" => array(
            "deleteSerial" => true,
        ),
        //----
        "deliveryParsial" => array(
            2 => array(
                "enabled" => false,
            ),
        ),
        //----
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",
            ),
        ),
        "autoOtorisasiSesiValidate" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
    ),
    "585" => array(
        "icon" => "fa fa-ship",
        "label" => "stock reception",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "stock initiation",
                "actionLabel" => "init reception",
                "source" => "",
                "target" => "585r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",

            ),
            2 => array(
                "runCoreAkunting" => true,
                "label" => "stock reception",
                //                "label" => "distribusi",
                "actionLabel" => "receive",
                "source" => "585r",
                "target" => "585",
                "userGroup" => "o_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
                // "allowScaner" => true,
               "allowScaner" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            "jumlah",
        ),
        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "item_fields" => "detil item",
            "oleh_nama" => "person",
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
        //end
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "print_label" => "print",
                "keterangan" => "keterangan",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "Nomer",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "print_label" => "print",
                "keterangan" => "keterangan",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
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
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartSubDetailFields" => array(
            1 => array(
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
            2 => array(
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

        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "settlementHistoryFields" => array(
            "fulldate" => "time",
            "nomer" => "receipt number",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "revertException" => true,
        "pairMakers" => array(
            2 => array(
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
        "previewCtr" => "Create",
        "scanQrValidator" => array(
            "enabled" => true,
            "gateSource" => "items3_sum",
            "gateTarget" => "items5_sum",
        ),
        "scanQrCart" => array(
            "deleteSerial" => false,
            "label" => "Langkah anda adalah menerima distribusi dari DC/Pusat. Anda tidak diijinkan menghapus nomer serial yang sudah terdaftar.",
        ),
    ),


    // config return distribusi by nota distribusi
    "983" => array(
        "icon" => "fa fa-truck",
        "label" => "stock return (receipt number)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "return request",
                "actionLabel" => "request return",
                "source" => "",
                "target" => "983r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "distribution (scan qr/barcode)",
                "actionLabel" => "save scan qr/barcode",
                "source" => "983r",
                "target" => "983sc",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label" => "return distribusi",
                "actionLabel" => "approve request",
                "source" => "983sc",
                "target" => "983",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //            ),
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "returned=.0",
            "jenis=.585",
            "cabang_id=placeID",
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
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "id=.-1",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "583r" => "request number",
            "583" => "approval number",
            //            "585r" => "request number",
            "585" => "receipt number",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
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
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "produk_nama",
            "kode" => "produk_kode",
            "label" => "produk_label",
            "satuan" => "satuan",
            "stok" => "stock",

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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
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
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "referenceFields" => array(
            "referenceID" => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod" => "pembayaran",
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
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
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang cabang",
                //                "mdlName" => "MdlGudang",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang dc",
                //                "mdlName" => "MdlGudang",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),

        "connectTo" => "985",
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "983re",
                "label" => "EDIT stock return (receipt number)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "983rrj",
                "label" => "REJECT stock return (receipt number)",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "983scrj",
                "label" => "REJECT scan qr/barcode",
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
        "shopingCartReload" => true,
    ),
    "985" => array(
        "icon" => "fa fa-ship",
        "label" => "stock reception (stock return receipt number)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "stock initiation",
                "actionLabel" => "init reception",
                "source" => "",
                "target" => "985r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "return stock reception",
                //                "label" => "return distribusi",
                "actionLabel" => "receive",
                "source" => "985r",
                "target" => "985",
                "userGroup" => "c_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "keterangan" => "keterangan",
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
        //end
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "print_label" => "print",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "GRN Number",
                "oleh_nama" => "person",
                "print_label" => "print",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),


        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => "item name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => "item name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "revertException" => true,
        "previewCtr" => "Create",
    ),
    // config return distribusi by produk
    "1983" => array(
        "icon" => "fa fa-truck",
        "label" => "stock return (by product)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request return stok ke dc",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "1983r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "autoSelectTarget" => true,
            ),
            2 => array(
                "label" => "return stok ke dc (scan qr/barcode)",
                "actionLabel" => "simpan scan qr/barcode",
                "source" => "1983r",
                "target" => "1983sc",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "return stok ke dc",
                "actionLabel" => "approve return stok ke dc",
                "source" => "1983sc",
                "target" => "1983",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "autoNextStep" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //            ),
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            //            "returned=.0",
            //            "jenis=.585",
            //            "cabang_id=placeID",
//            "cabang_id=placeID",
//            "gudang_id=gudangID",
//            "state=.active",
//            "jumlah>.0",
            //            "produk.jenis=.item",
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
            "stock_locker.state=.active",
            "stock_locker.jumlah>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            //            "id" => "id",
            //            "nama" => "nomer",
            "id" => "produk_id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "label",
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
            //            "keterangan", "kode", "satuan", "jumlah",
            //            "nama", "kode", "satuan", "jumlah"
        ),
        //        "selectorProcessor" => "_processSelectNotaItem/select",
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "id=.-1",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "cabang pengirim",
            "cabang_nama" => "cabang penerima",
            "1983r" => "request number",
            "1983" => "approval number",
            //            "585r" => "request number",
            "1985" => "receipt number",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
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
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_kode" => "product code",
                "current_stok" => "stock<BR>available",
                "intransit_stok" => "stock<BR>intransit",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",

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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
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
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "referenceFields" => array(
            "referenceID" => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod" => "pembayaran",
        ),
        "pairMakers" => array(
            1 => array(
//                "stokProduk" => array(
//                    "helperName" => "he_cek_stock_produk_locker",
//                    "functionName" => "cekStockProdukLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "jenis" => ".produk",
//                        "state" => ".active",
//                    ),
//                    "gate" => "items",
//                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                    ),
                    "gate" => "items",
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
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
//                        "jenis" => ".produk",
//                        "state" => ".active",
                    ),
//                    "kolom" => array(
////                        "no_part",
//                    ),
                    "gate" => "items",
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "intransit_stok",
                    ),
                ),
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang cabang",
                //                "mdlName" => "MdlGudang",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang dc",
                //                "mdlName" => "MdlGudang",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "allowedMainEdit" => array("1"),
        "connectTo" => "1985",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1983re",
                "label" => "EDIT stock return request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1983rrj",
                "label" => "REJECT stock return request",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1983scrj",
                "label" => "REJECT scan qr/barcode",
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
        "shopingCartReload" => true,
    ),
    "1985" => array(
        "icon" => "fa fa-ship",
        "label" => "Penerimaan Return Stok",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "Pre Penerimaan Return Stok",
                "actionLabel" => "Pre Penerimaan Return Stok",
                "source" => "",
                "target" => "1985r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "Penerimaan Return Stok",
                "actionLabel" => "terima",
                "source" => "1985r",
                "target" => "1985",
                "userGroup" => "c_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
                "allowScaner" => true,
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "cabang pengirim",
//            "cabang_nama" => "cabang penerima",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "keterangan" => "keterangan",
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
        //end
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "Nomer",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "print_label" => "print",
                "keterangan" => "keterangan",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "Nomer",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "print_label" => "print",
                "keterangan" => "keterangan",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),


        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "revertException" => true,
        "previewCtr" => "Create",
        "scanQrValidator" => array(
            "enabled" => true,
            "gateSource" => "items3_sum",
            "gateTarget" => "items5_sum",
        ),
    ),

    //config repack produk komposit
    "773" => array(
        "icon" => "fa fa-cube",
        "label" => "product komposit",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "repack produk komposit request",
                "actionLabel" => "make assembling request",
                "source" => "",
                "target" => "773r",
                "userGroup" => "sys",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "packing produk komposit",
                "actionLabel" => "approve",
                "source" => "773r",
                "target" => "773",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
//                "allowEdit" => true,
                // "allowIncrement" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlProdukKomposit",
        "selectorSrcModel" => "MdlProdukKomposit",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //            "enabled" => false,
            //            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProductKomposit/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "customers_nama" => "customers",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
        ),
        "historyFields" => array(
            1 => array(
                //                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "gudang_nama" => "warehouse",
                //                "next_pic" => "Next step otorisator",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                //                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "gudang_nama" => "warehouse",
                //                "next_pic" => "Next step otorisator",
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
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "stok" => "stock",
                "jml" => "qty",
                "sisa" => "sisa",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item source name",
                "stok" => "stock",
                "jml" => "qty",
                // "sisa" => "sisa",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",

            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "volume" => "volume",
            "berat" => "berat",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
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
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        "shoppingCartPairedItem" => array(
            "enabled" => false,
            "mdlName" => "MdlProduk",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "componentsAss" => array(
            "model" => "MdlProdukKompositKomposisi",
            "modelSrc" => "MdlProduk",
        ),
        "followupItemEditable" => "_followupLiveEdit/updateItemFieldKomposit/",
        "followupItemRemove" => "_followupLiveEdit/removeItemKomposit/",
        "pairMakers" => array(
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceProduk" => array(
                    "helperName" => "he_cek_price_produk",
                    "functionName" => "cekPriceProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            2 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "priceProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "harga_last",
                    ),
                ),
            ),
        ),
        "kompositValidate" => array(
            "enabled" => true,
            "mdlName" => "MdlProdukKompositKomposisi",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "773re",
                "label" => "EDIT repack produk komposit request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "773rrj",
                "label" => "REJECT repack produk komposit request",
            ),
        ),
    ),

    // PROJECT
    "5833" => array(
        "icon" => "fa fa-truck",
        "label" => "distribusi ke cabang project",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request distribusi ke project",
                "actionLabel" => "distribusi ke project",
                "source" => "",
                "target" => "5833r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi distribusi ke project",
//                "label" => "authorization",
                "actionLabel" => "approve distribusi ke project",
                "source" => "5833r",
                "target" => "5833",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
//                "allowEdit" => false,
            ),
        ),
//        "template" => "template/transaksi.html",
        "template" => "template/transaksi_project.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("jual"),
            "key_label" => array(
                "jual" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
//            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "silahkan diisi dengan item ID, nama, kode...",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
            "id",
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "jenis=.project",
        ),
        "pihakProcessor" => "_processPihak/select",

        // PIHAK IV
        "pihakModelProjek" => "MdlProdukProject",
        "pihakProjekCaller" => "_selectorPihak/selectPihakProjek",
        "pihakProjekLabel" => "produk project",
        "pihakProjekViewedFields" => array(
            "kode",
            "nama",
            "transaksi_no_app",
        ),
        "pihakProjekFilters" => array(
            "status=.1",
            "trash=.0",
//            "customer_id=customerProjek",
            "transaksi_id_app>.0",
            "closing_status=.0",
        ),
        "pihakProjekProcessor" => "_processPihak/selectProjek",
//        "pihakExternNota" => true,


        "shortHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "detil item",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "583r" => "request number",
            "583" => "approval number",
            //            "585r" => "request number",
            "585" => "receipt number",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortStatusFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
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
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "code",
            "produk_nama" => "product",
            "produk_ord_jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "current_stok" => "stock<BR>available",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
//            "stok" => "stock<BR>after",
            "current_stok" => "current<BR>stock",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "receiptElements" => array(
//            "gudang" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "target warehouse",
//                "mdlName" => "MdlGudangDefault",
//                "mdlFilter" => array("cabang_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
            "produkProjek" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Produk Project",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array("id=pihakProjekID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Nama",
                    "transaksi_id_app" => "SO ID",
                    "transaksi_no_app" => "SO Number",
                ),
                "editPoints" => array(),
                "noValidate" => true,
            ),
        ),

        "connectTo" => "5855",
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
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
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
//                        "jenis" => ".produk",
//                        "state" => ".active",
                    ),
//                    "kolom" => array(
////                        "no_part",
//                    ),
                    "gate" => "items",
                ),
            ),
            2 => array(
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
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
                    ),
                ),
            ),
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "cabang_nama" => "sender",
            "cabang2_nama" => "recipient",
            //            "suppliers_nama" =>"vendor",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),

        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5833re",
                "label" => "EDIT request distribusi ke project",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5833rrj",
                "label" => "REJECT otorisasi distribusi ke project",
            ),
        ),
    ),
    "5855" => array(
        "icon" => "fa fa-ship",
        "label" => "penerimaan bahan baku di project",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "distribusi ke project",
                "actionLabel" => "distribusi ke project",
                "source" => "",
                "target" => "5855r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "penerimaan bahan baku di project",
                "actionLabel" => "terima bahan baku",
                "source" => "5855r",
                "target" => "5855",
                "userGroup" => "o_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "item_fields" => "detil item",
            "oleh_nama" => "person",
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
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "code",
            "produk_nama" => "product",
            "produk_ord_jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "print_label" => "print",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "GRN Number",
                "oleh_nama" => "person",
                "print_label" => "print",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "item name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "item name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "settlementHistoryFields" => array(
            "fulldate" => "time",
            "nomer" => "receipt number",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "revertException" => "true",
        "pairMakers" => array(
            2 => array(
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
        "previewCtr" => "Create",
    ),
);