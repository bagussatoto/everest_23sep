<?php

//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    // DISTRIBUSI PROJECT
    "5834" => array(
        "icon" => "fa fa-truck",
        "label" => "distribusi supplies kepelaksana project",
        "place" => "center",
//        "autoTerimaCabang" => "enable",
        "steps" => array(
            1 => array(
                "label" => "distribusi supplies project",
                "actionLabel" => "distribusi supplies kepelaksana project",
                "source" => "",
                "target" => "5834r",
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "autoSelectTarget" => true,
//                "autoNextStep" => true,
            ),
//            2 => array(
//                "label" => "distribution (scan qr/barcode)",
//                "actionLabel" => "save scan qr/barcode",
//                "source" => "5834r",
//                "target" => "5834sc",
//                "userGroup" => "w_gudang_spv",
//                "stateLabel" => "sent",
//                "stateColor" => "#009900",
//                "stateCaption" => "approved by",
////                "allowEdit" => false,
////                "allowScaner" => true,
//                "autoNextStep" => true,
//            ),
            2 => array(
                "label" => "OTORISASI DISTRIBUSI SUPPLIES PROJECT",
                "actionLabel" => "PERSETUJUAN DISTRIBUSI SUPPLIES",
                "source" => "5834r",
                "target" => "5834",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
//                "allowEdit" => false,
//                "autoNextStep" => true,
            ),
        ),
//        "template" => "template/transaksi_project.html",
        "template" => "template/transaksi_project4.html",

//        "selectorModel" => "MdlLockerStock",
//        "selectorSrcModel" => "MdlProduk2",
//        "selectedPrice" => array(
//            "model" => "MdlHargaProduk",
//            "label" => array("jual"),
//            "key_label" => array(
//                "jual" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStock",
//        ),
//        "selectorFilters" => array(
//            "stock_locker.cabang_id=placeID",
//            "stock_locker.gudang_id=gudangProjectID",
////            "stock_locker.gudang_id=gudangID",
////            "stock_locker.jumlah>.0",
//            "stock_locker.state=.active",
//        ),
//        // ini digunakan untuk filter locker_cek
//        "selectorLockerProcessFilters" => array(
//            "state=.active",
//            "cabang_id=placeID",
//            "gudang_id=gudangProjectID",
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "silahkan diisi dengan item ID, nama, kode...",
//        "selectorParamFields" => array(
//            "id" => "produk_id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//            "jumlah" => "jumlah",
//            "produk_kode" => "kode",
//        ),
//        "selectorViewedFields" => array(
//            "id",
//            "keterangan",
//            "kode",
//            "satuan",
//            "jumlah",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",

//        "selectorModel" => "MdlProjectWorkOrderSub", //ori
        "selectorModel" => "MdlTasklistProject",
        "selectorSrcModel" => "MdlProjectWorkOrderSub", //ori
//        "selectorSrcModel" => "MdlProjectKomponenBiayaDetailsRabSub",
//        "selectorSubSrcModel" => "MdlProjectKomposisiWorkorderSub", //ori
        "selectorSubSrcModel" => "MdlProjectKomponenBiayaDetailsRabSub",
        "selectorSubSrcModelTambahan" => "MdlProjectKomponenBiayaDetailsRabSubTambahan",
//        "selectorSubSrcModel" => "MdlSubProgresTasklist",
//        "selectorSrcModel" => "MdlProjectKomposisiWorkorderSub",
        "selectorModelEdit" => "MdlProduk2",
        "selectorSrcModelEdit" => "MdlProduk2",
        "selectorSrcParamFields" => array("id" => "sub_fase_id"),
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
        "selectedPrice2" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "hpp",
                "jual",
            ),
            "key_label" => array(
                "hpp" => "hpp",
                "jual" => "harga",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockSupplies",
            "lockerFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangProjectID",
            ),
        ),
        "selectorFilters" => array( //custom filter
            "produk_id=pihakProjekID",
//            "produk_id=pihakProjekID",
//            "fase_id=pihakMainID",
        ),
        "selectorSubFilters" => array(
//            "fase_id=pihakMainID",
            "project_id=produkProjek",
            "jenis=.supplies",
        ),
        "lockerFilterReplacer" => array(
            "cabang_id" => "placeID",
            "gudang_id" => "gudangProjectID",
        ),
//        // ini digunakan untuk filter locker_cek
        "selectorLockerProcessFilters" => array(
            "state=.active",
//            "biaya_id=biaya_id",
            "cabang_id=placeID",
            "gudang_id=gudangProjectID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih sub work order",
        "selectorParamFields" => array(
            "id" => "sub_fase_id",
            "nama" => "produk_nama",
            "no_spk" => "no_spk",
//            "satuan" => "satuan",
//            "jumlah" => "jumlah",
//            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
//            "produk_nama",
            "nama",
            "no_spk",
//            "kode",
//            "jumlah",
//            "satuan",
        ),
//        "selectorProcessor" => "_processSelectProduct/selectProject",
        "selectorProcessor" => "_processSelectSupplies/selectProject",

        // PILIH CABANG
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakView" => " project",
        "pihakFilters" => array(
//            "id<>cabang_id",
            "jenis=.cabang",
        ),
        "pihakProcessor" => "_processPihak/select",

        // PIHAK IV, PILIH-PILIH PROJECT NYA
        "pihakModelProjek" => "MdlProdukProject",
        "pihakProjekCaller" => "_selectorPihak/selectPihakProjek",
        "pihakProjekLabel" => "produk project",
        "pihakProjekViewedFields" => array(
//            "kode",
//            "nama",
//            "transaksi_no_app",
        ),
        "pihakProjekFilters" => array(
            "status=.1",
            "trash=.0",
            "project_start=.1",
//            "kode>.0",
            "lock>.0",
//            "customer_id=customerProjek",
//            "transaksi_id_app>.0",
//            "closing_status=.0",
        ),
        "pihakProjekProcessor" => "_processPihak/selectProjek",
//        "pihakExternNota" => true,

        // PILIH WORK ORDER NYA
        "pihakModelMain" => "MdlProjectWorkOrder",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "order",
        "pihakMainFilters" => array(
            "produk_id=pihakProjekID",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "pihakProjekName" => "project",
            "pihakProjekWorkOrderSubNama" => "work order",
            "pihakTasklist__produk_nama" => "nama spk",
            "pihakTasklist__label" => "spk",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "dtime" => "date",
                "cabang2_nama" => "cabang tujuan",
                "nomer" => "nomer request",
                "item_fields" => "isi",
                "pihakProjekName" => "project",
                "pihakProjekWorkOrderSubNama" => "work order",
                "pihakTasklist__produk_nama" => "nama spk",
                "pihakTasklist__label" => "spk",
                "oleh_nama" => "pic",
                "next_pic" => "Next step otorisator",
                "keterangan" => "keterangan",
            ),
            2 => array(
                "dtime" => "date",
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer otorisasi",
                "item_fields" => "isi",
                "pihakProjekName" => "project",
                "pihakProjekWorkOrderSubNama" => "work order",
                "pihakTasklist__produk_nama" => "nama spk",
                "pihakTasklist__label" => "spk",
                "oleh_nama" => "pic",
                "next_pic" => "Next step otorisator",
                "keterangan" => "keterangan",
            ),
            3 => array(
                "dtime" => "date",
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer otorisasi",
                "item_fields" => "isi",
                "pihakProjekName" => "project",
                "pihakProjekWorkOrderSubNama" => "work order",
                "pihakTasklist__produk_nama" => "nama spk",
                "pihakTasklist__label" => "spk",
                "oleh_nama" => "pic",
                "next_pic" => "Next step otorisator",
                "keterangan" => "keterangan",
            ),
        ),
        "shortStepHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "tanggal",
            "cabang2_nama" => "pengirim",
            "cabang_nama" => "penerima",
            "5834r" => "no. request",
            "5834" => "no. approval",
            "5856" => "no. receipt",

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
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "supplies",
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
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
                "biaya_nama" => "biaya",
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
//                "current_stok" => "ready<br>stock",
//                "jml_wo" => "jumlah<br>diminta",
//                "jml_intransit" => "intransit",
//                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
//                "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
//                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
            ),
            2 => array(
                "biaya_nama" => "biaya",
//                "id" => "pID",
//                "nama" => array(
//                    "label" => "produk",
//                    "addKey" => "static_keterangan",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
//                "jml_wo" => "BOM",
//                "current_stok" => "stock<BR>tersedia",
//                "jml_intransit" => "intransit",
//                "jml_diterima" => "diterima",
//                "jml" => "qty",
//                "stok" => "stock<BR>akhir",
//                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            3 => array(
                "biaya_nama" => "biaya",
//                "id" => "pID",
//                "nama" => array(
//                    "label" => "produk",
//                    "addKey" => "static_keterangan",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
//                "jml_wo" => "BOM",
//                "current_stok" => "stock<BR>tersedia",
//                "jml_intransit" => "intransit",
//                "jml_diterima" => "diterima",
//                "jml" => "qty",
//                "stok" => "stock<BR>akhir",
//                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                "biaya_dasar_nama" => "supplies",
                "company_stok" => "Total Stok<br>Perusahaan",
                "current_stok" => "ready stock<br>Gd. Project DC",
                "jml_wo" => "jumlah<br>diminta",
                "jml_intransit" => "intransit",
                "jml_available" => "stock aktif",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
            ),
            2 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                "biaya_dasar_nama" => "supplies",
                "company_stok" => "Total Stok<br>Perusahaan",
                "current_stok" => "ready stock<br>Gd. Project DC",
                "jml_wo" => "jumlah<br>diminta",
                "jml_intransit" => "intransit",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
            ),
            3 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
                "biaya_dasar_nama" => "supplies",
                "company_stok" => "Total Stok<br>Perusahaan",
                "current_stok" => "ready stock<br>Gd. Project DC",
                "jml_wo" => "jumlah<br>diminta",
                "jml_intransit" => "intransit",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
//                "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
//            "stok" => "stock<BR>after",
            "company_stok" => "Total Stok<br>Perusahaan",
            "current_stok" => "current<BR>stock",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "jml_wo" => "jml_wo",
            "jml_intransit" => "jml_intransit",
            "jml_diterima" => "jml_diterima",
            "barcode" => "barcode",
            //-------------------
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
            1 => array(//                "jml",
            ),
            2 => array(
                "jml",
            ),
        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "jml",
            ),
            2 => array(
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
        "receiptElements" => array(
            "produkProjek" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Project",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array("id=pihakProjekID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Nama",
//                    "transaksi_id" => "SO ID",
                    "transaksi_no" => "SO Number",
                    "nomor_kontrak" => "Kontrak",
                ),
                "editPoints" => array(),
                "noValidate" => true,
            ),

            "pihakTasklist" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",//radio
                "label" => "Informasi Penugasan",
                "mdlName" => "MdlTasklistProject",
                "mdlFilter" => array("no_spk=pihakProjekNoSpk"),
                "key" => "id",
                "labelSrc" => "no_spk",
                "usedFields" => array(
                    "produk_nama" => "Tugas",
                    "produk_paket_nama" => "paket",
//                    "no_spk" => "SPK",
                    "employee_nama" => "Pelaksana",
                ),
                "noValidate" => false,
                "editPoints" => array(),
            ),

            "pihakID" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",//radio
                "label" => "Cabang pelaksana project",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=.1"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "noValidate" => false,
                "editPoints" => array(),
            ),
//            "gudangProject" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "gudang default project",
//                "mdlName" => "MdlGudang",
//                "mdlFilter" => array(
//                    "cabang_id=placeID"
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                    "employee_nama" => "pengawas",
//                ),
//                "editPoints" => array(1),
//            ),
//            "workOrderDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "WORK ORDER",
//                "mdlName" => "MdlProjectWorkOrder",
//                "mdlFilter" => array(
//                    "id=pihakProjekWorkOrderID"
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                    "employee_nama" => "pengawas",
//                ),
//                "editPoints" => array(1),
//            ),

//            "workOrderSubDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "SUB WORK ORDER",
//                "mdlName" => "MdlProjectWorkOrderSub",
//                "mdlFilter" => array(
//                    "id=pihakProjekWorkOrderSubID"
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                    "employee_nama" => "pengawas",
//                ),
//                "editPoints" => array(1),
//            ),
        ),
        "connectTo" => "5856",
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),

        "pairMakers" => array(
            1 => array(
                "intransitById" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangProjectID",
                        "jenis" => ".supplies",
                        "state" => ".hold",
                    ),
                    "gate" => "items2_sum",
                ),
                "activeById" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangProjectID",
                        "jenis" => ".supplies",
                        "state" => ".active",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "intransitById" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_intransit",
                    ),
                ),
                "activeById" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_available",
                    ),
                ),
            ),
        ),

        "pairMakersProject" => array(

//            "biaya_dasar_nama" => "supplies",
//            "current_stok" => "ready<br>stock",
//            "jml_wo" => "jumlah<br>diminta",
//            "jml_intransit" => "intransit",
//            "jml_diterima" => "SUDAH<BR>DISERAHKAN",
//            "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
//            "stok" => "total<br>diserahkan",

            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies_locker_project",
                    "functionName" => "cekStockSuppliesLockerProject",
                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangProjectID",
                        "gudang_id" => "gudangProject2ID",
//                        "jenis" => ".supplies",
//                        "jenis" => ".produk",
//                        "state" => ".active",
                    ),
                    "gate" => "items2",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_supplies",
                    "functionName" => "cekPairDataSupplies",
                    "params" => array(),
                    "kolom" => array(
                        "no_part",
                    ),
                    "gate" => "items2",
                ),
//                "stokIntransit" => array(
//                    "helperName" => "he_cek_stock_supplies_locker_project",
//                    "functionName" => "cekStockSuppliesLockerProject",
//                    "params" => array(
////                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangProject2ID",
////                        "jenis" => ".supplies",
////                        "jenis" => ".produk",
//                        "state" => ".hold",
//                    ),
//                    "gate" => "items2",
//                ),
//                "stokBefore" => array(
////                    "helperName" => "he_cek_stock_supplies_project",
////                    "functionName" => "cekStockSuppliesProject",
//                    "helperName" => "he_cek_stock_supplies_locker_project",
//                    "functionName" => "cekStockSuppliesLockerProject",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangProjectID",
//                        "state" => ".active",
//                    ),
//                    "gate" => "items2",
//                ),
                "stokDiterima" => array(
//                    "helperName" => "he_cek_stock_supplies",
//                    "functionName" => "cekStockSupplies",
                    "helperName" => "he_cek_stock_supplies_locker_project",
                    "functionName" => "cekStockSuppliesLockerProject",
//                    "helperName" => "he_cek_stock_supplies_workorder_locker_project",
//                    "functionName" => "cekStockSuppliesWorkorderLockerProject",
                    "params" => array(
//                        "cabang_id" => "place2ID",
                        "gudang_id" => "gudangProject2ID",
                        "state" => ".active",
                    ),
                    "gate" => "items2",
                ),
                "stokTotal" => array(
                    "helperName" => "he_cek_stock_supplies_persediaan_project",
                    "functionName" => "cekStockSuppliesPersediaanProject",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangProjectID",
                    ),
                    "gate" => "items2",
                ),

            ),
            2 => array(
                "dataProduk" => array(
                    "helperName" => "he_pair_data_supplies",
                    "functionName" => "cekPairDataSupplies",
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
        "pairInjectorsProject" => array(
//            "biaya_dasar_nama" => "supplies",
//            "current_stok" => "ready<br>stock",
//            "jml_wo" => "jumlah<br>diminta",
//            "jml_intransit" => "intransit",
//            "jml_diterima" => "SUDAH<BR>DISERAHKAN",
//            "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
//            "stok" => "total<br>diserahkan",
            1 => array(
                "stokProduk" => array(
                    "items2" => array(
                        "targetKey" => "biaya_dasar_id",
                        "targetColumn" => "stok",
                    ),
                ),
//                "stokBefore" => array(
//                    "items2" => array(
//                        "targetKey" => "biaya_dasar_id",
//                        "targetColumn" => "current_stok",
//                    ),
//                ),
//                "stokIntransit" => array(
//                    "items2" => array(
//                        "targetKey" => "biaya_dasar_id",
//                        "targetColumn" => "jml_intransit",
//                    ),
//                ),
                "stokDiterima" => array(
                    "items2" => array(
                        "targetKey" => "biaya_dasar_id",
                        "targetColumn" => "jml_diterima",
                    ),
                ),
                "stokTotal" => array(
                    "items2" => array(
                        "targetKey" => "biaya_dasar_id",
//                        "targetColumn" => "jml_intransit",
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
            "main", "items", "items2"
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "jumlah diserahkan",
        ),
        "shoppingCartField2Validators" => array(
            "jml" => "jumlah diserahkan",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan WKWKWKW",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5834re",
                "label" => "EDIT request distribusi ke project",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5834rrj",
                "label" => "REJECT otorisasi distribusi ke project",
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
        "shoppingCartItems" => array(
//            1 => array(
//                "enabled" => true,
//                "source" => "jml_wo",
//                "target" => "jml_intransit+jml_diterima",
//                "mode" => "disabled", //unset
//                "label_warning" => "*bg hijau = pengiriman lengkap (sesuai dengan kebutuhan BOM).<br>*bg kuning = pengiriman melebihi kebutuhan BOM.",
//            ),
        ),
        "gudangSource" => "gudangProjectID",
        "autoTerimaCabang" => array(
            "enabled" => true,
        ),
        //----
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",
            ),
        ),
        //----
        "shoppingCartFields2_sum" => array(
            1 => array(
                "biaya_dasar_nama" => "supplies",
                "current_stok" => "ready<br>stock",
                "jml_wo" => "jumlah<br>diminta",
                "jml_intransit" => "intransit",
                "jml_available" => "stock aktif",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                "stok" => "total<br>diserahkan",
            ),
            2 => array(
                "biaya_dasar_nama" => "supplies",
                "current_stok" => "ready<br>stock",
                "jml_wo" => "jumlah<br>diminta",
                "jml_intransit" => "intransit",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                "stok" => "total<br>diserahkan",
            ),
        ),


    ),
    "5856" => array(
        "icon" => "fa fa-ship",
        "label" => "penerimaan supplies di gudang project",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "distribusi supplies ke gudang project",
                "actionLabel" => "distribusi supplies ke gudang project",
                "source" => "",
                "target" => "5856r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "penerimaan supplies di gudang project",
                "actionLabel" => "terima supplies",
                "source" => "5856r",
                "target" => "5856",
                "userGroup" => "o_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
                "autoNextStep" => true,
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
            "cabang2_nama" => "pengirim",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "pihakProjekName" => "project",
            "pihakProjekWorkOrderNama" => "work order",
            "pihakTasklist__produk_nama" => "nama spk",
            "pihakTasklist__label" => "spk",
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
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "supplies",
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
                "cabang2_nama" => "pengirim",
                // "nomer_top" => "SO number",
                "nomer" => "nomer",
                "item_fields" => "isi",
                "pihakProjekName" => "project",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakTasklist__produk_nama" => "nama spk",
                "pihakTasklist__label" => "spk",
                "oleh_nama" => "person",
                "print_label" => "print",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "pengirim",
                // "nomer_top" => "SO number",
                "nomer" => "nomer",
                "item_fields" => "isi",
                "pihakProjekName" => "project",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakTasklist__produk_nama" => "nama spk",
                "pihakTasklist__label" => "spk",
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
            "main", "items", "items2"
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "kode",
                "keterangan" => "part number",
//                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "kode",
                "keterangan" => "part number",
//                "no_part" => "part number",
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
            "barcode" => "barcode",
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

    // RETURN DISTRIBUSI PROJECT
    "9834" => array(
        "icon" => "fa fa-truck",
        "label" => "pengembalian kelebihan supplies project",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pengembalian supplies ke dc",
                "actionLabel" => "pengembalian supplies ke dc",
                "source" => "",
                "target" => "9834r",
                "userGroup" => "o_project",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
//            2 => array(
//                "label" => "pengembalian supplies ke dc (scan qr/barcode)",
//                "actionLabel" => "simpan scan qr/barcode",
//                "source" => "9834r",
//                "target" => "9834sc",
//                "userGroup" => "o_project_spv",
//                "stateLabel" => "sent",
//                "stateColor" => "#009900",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowScaner" => true,
//            ),
            2 => array(
                "label" => "otorisasi pengembalian supplies ke dc",
                "actionLabel" => "approve pengembalian supplies ke dc",
                "source" => "9834r",
                "target" => "9834",
                "userGroup" => "o_project_spv",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
            ),
        ),
        "template" => "template/transaksi_project4_return.html",

//        "selectorModel" => "MdlLockerStock",
//        "selectorSrcModel" => "MdlProduk2",
//        "selectedPrice" => array(
//            "model" => "MdlHargaProduk",
//            "label" => array("jual"),
//            "key_label" => array(
//                "jual" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStock",
//        ),
//        "selectorFilters" => array(
//            "stock_locker.cabang_id=placeID",
//            "stock_locker.gudang_id=gudangProjectID",
////            "stock_locker.gudang_id=gudangID",
////            "stock_locker.jumlah>.0",
//            "stock_locker.state=.active",
//        ),
//        // ini digunakan untuk filter locker_cek
//        "selectorLockerProcessFilters" => array(
//            "state=.active",
//            "cabang_id=placeID",
//            "gudang_id=gudangProjectID",
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "silahkan diisi dengan item ID, nama, kode...",
//        "selectorParamFields" => array(
//            "id" => "produk_id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//            "jumlah" => "jumlah",
//            "produk_kode" => "kode",
//        ),
//        "selectorViewedFields" => array(
//            "id",
//            "keterangan",
//            "kode",
//            "satuan",
//            "jumlah",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",

        "selectorModel" => "MdlProjectKomponenBiayaDetailsRabSub",
        "selectorSrcModel" => "MdlProjectWorkOrderSub",
//        "selectorSubSrcModel" => "MdlProjectKomposisiWorkorderSub",
        "selectorSubSrcModel" => "MdlProjectKomponenBiayaDetailsRabSub",
//        "selectorSrcModel" => "MdlProjectKomposisiWorkorderSub",
        "selectorModelEdit" => "MdlProduk2",
        "selectorSrcModelEdit" => "MdlProduk2",

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
        "selectedPrice2" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "hpp",
                "jual",
            ),
            "key_label" => array(
                "hpp" => "hpp",
                "jual" => "harga",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockSupplies",
        ),
        "selectorFilters" => array(
            "no_spk=pihakProjekTasklistSPK",
            "jenis=.supplies",
//            "transaksi_id=.0",
//            "produk_id=pihakProjekID",
        ),
        "selectorSubFilters" => array(
            "project_id=produkProjek",
            "jenis=.supplies",
        ),
//        // ini digunakan untuk filter locker_cek
        "selectorLockerProcessFilters" => array(
//            "state=.active",
//            "cabang_id=placeID",
//            "gudang_id=pihakProjekWorkorderGudangID",
//            "gudang_id=gudangID",
        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorCaller" => "_selectorItem/selectItemProjectReturn",// bikin shopping cart background
        "selectorLabel" => "pilih supplies",
        "selectorParamFields" => array(
            "biaya_id" => "biaya_id",
            "biaya_nama" => "biaya_nama",
            "id" => "biaya_dasar_id",
            "nama" => "biaya_dasar_nama",
            "no_spk" => "no_spk",
//            "satuan" => "satuan",
//            "jumlah" => "jumlah",
//            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
//            "produk_nama",
            "nama",
            "no_spk",
//            "kode",
//            "jumlah",
//            "satuan",
        ),
        "selectorProcessor" => "_processSelectSupplies/selectProjectReturn",
//        "selectorProcessor" => "_processSelectSupplies/selectProject",

        // PILIH CABANG
//        "editHandlerMethod" => "select",
        "editHandlerMethod" => "selectProjectReturn",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
//            "jenis=.project",
        ),
        "pihakProcessor" => "_processPihak/select",

        // PIHAK IV, PILIH-PILIH PROJECT NYA
        "pihakModelProjek" => "MdlProdukProject",
        "pihakProjekCaller" => "_selectorPihak/selectPihakProjek",
        "pihakProjekLabel" => "produk project",

        // PIHAK VI, PILIH-PILIH TASKLIST NYA

        "pihakProjekProcessorTasklist" => "_processPihak/selectProjekTasklist",
        "pihakProjekTasklistViewedFields" => array(
            "no_spk",
        ),
        "pihakModelProjekTaskList" => "MdlTasklistProject",
        "pihakProjekTaskListFilters" => array(
            "status=.1",
            "trash=.0",
            "produk_id=pihakProjekID",
//            "customer_id=customerProjek",
//            "transaksi_id_app>.0",
//            "closing_status=.0",
        ),
        "pihakProjekTaskListCaller" => "_selectorPihak/selectPihakProjekTaskList",
        "pihakProjekTaskListLabel" => "pilih tasklist",

        "pihakProjekViewedFields" => array(
            "kode",
            "nama",
            "transaksi_no_app",
        ),
        "pihakProjekFilters" => array(
            "status=.1",
            "trash=.0",
//            "customer_id=customerProjek",
//            "transaksi_id_app>.0",
//            "closing_status=.0",
        ),
        "pihakProjekProcessor" => "_processPihak/selectProjek",
//        "pihakExternNota" => true,

        // PILIH WORK ORDER NYA
        "pihakModelMain" => "MdlProjectWorkOrder",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "order",
        "pihakMainFilters" => array(
            "produk_id=pihakProjekID",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        "shortHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "tanggal",
            "nomer" => "no. return",
            "pihakProjekName" => "proyek",
            "pihakProjekWorkOrderNama" => "work order",
            "pihakProjekTasklistName" => "tugas / pekerjaan",
            "pihakProjekNoSpk" => "no. spk",
            "cabang_nama" => "cabang asal",
            "cabang2_nama" => "tujuan (dc)",
            "item_fields" => "rincian supplies",
            "oleh_nama" => "pemohon",
            "next_pic" => "otorisator selanjutnya",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "dtime" => "tanggal",
                "nomer" => "no. request return",
                "pihakProjekName" => "proyek",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakProjekTasklistName" => "tugas / pekerjaan",
                "pihakProjekNoSpk" => "no. spk",
                "cabang_nama" => "cabang asal",
                "cabang2_nama" => "tujuan (dc)",
                "item_fields" => "rincian supplies",
                "oleh_nama" => "pemohon",
                "next_pic" => "otorisator selanjutnya",
                "keterangan" => "keterangan",
            ),
            2 => array(
                "dtime" => "tanggal",
                "nomer_top" => "no. request return",
                "nomer" => "no. otorisasi return",
                "pihakProjekName" => "proyek",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakProjekTasklistName" => "tugas / pekerjaan",
                "pihakProjekNoSpk" => "no. spk",
                "cabang_nama" => "cabang asal",
                "cabang2_nama" => "tujuan (dc)",
                "item_fields" => "rincian supplies",
                "oleh_nama" => "pemohon",
                "next_pic" => "otorisator selanjutnya",
                "keterangan" => "keterangan",
            ),
            3 => array(
                "dtime" => "tanggal",
                "nomer_top" => "no. request return",
                "nomer" => "no. otorisasi return",
                "pihakProjekName" => "proyek",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakProjekTasklistName" => "tugas / pekerjaan",
                "pihakProjekNoSpk" => "no. spk",
                "cabang_nama" => "cabang asal",
                "cabang2_nama" => "tujuan (dc)",
                "item_fields" => "rincian supplies",
                "oleh_nama" => "pemohon",
                "next_pic" => "otorisator selanjutnya",
                "keterangan" => "keterangan",
            ),
        ),
        "shortStepHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "tanggal",
            "cabang_nama" => "cabang asal",
            "cabang2_nama" => "tujuan (dc)",
            "9834r" => "no. request return",
            "9834" => "no. otorisasi return",
            "9856" => "no. penerimaan di dc",

            "oleh_nama" => "pemohon",
            "next_pic" => "otorisator selanjutnya",
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
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "supplies",
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
//                "id" => "pID",
//                "nama" => array(
//                    "label" => "produk",
//                    "addKey" => "static_keterangan",
//                ),
                "biaya_nama" => "biaya",
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "part number",
//                "jml_wo" => "B.O.M",
//                "jml_used" => "jml sudah<br>digunakan project",
//                "current_stok" => "stock<BR>available",
//                "stok_awal" => "stock<BR>available",
//                "jml_intransit" => "intransit",
//                "jml_diterima" => "diterima",
//                "jml" => "qty",
//                "stok" => "last<BR>stock",
//                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "part number",
                "jml_wo" => "BOM",
                "current_stok" => "stock<BR>tersedia",
                "jml_intransit" => "intransit",
                "jml_diterima" => "diterima",
                "jml" => "qty",
                "stok" => "stock<BR>akhir",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            3 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "part number",
                "jml_wo" => "BOM",
                "current_stok" => "stock<BR>tersedia",
                "jml_intransit" => "intransit",
                "jml_diterima" => "diterima",
                "jml" => "qty",
                "stok" => "stock<BR>akhir",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
//                "id" => "pID",
//                "biaya_nama" => array(
//                    "label" => "biaya",
////                    "addKey" => "static_keterangan",
//                    "addKey" => "biaya_nama",
//                ),
                "biaya_dasar_nama" => "supplies",
                "no_spk" => "nomer spk",
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
//                "current_stok" => "ready<br>stock",
                "jml_wo" => "JUMLAH<br>SPK",
//                "jml_intransit" => "intransit",
                "jml_diterima" => "SUDAH<BR>DITERIMA",
                "jml" => "JUMLAH<BE>DIKEMBALIKAN",
                "stok" => "TOTAL<br>DIKEMBALIKAN",
//                "satuan" => "satuan",
//                "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "part number",
                "jml_wo" => "BOM",
                "current_stok" => "stock<BR>tersedia",
                "jml_intransit" => "intransit",
                "jml_diterima" => "diterima",
                "jml" => "qty",
                "stok" => "stock<BR>akhir",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            3 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "part number",
                "jml_wo" => "BOM",
                "current_stok" => "stock<BR>tersedia",
                "jml_intransit" => "intransit",
                "jml_diterima" => "diterima",
                "jml" => "qty",
                "stok" => "stock<BR>akhir",
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
            "jml_used" => "jml<br>digunakan",
            "stok_awal" => "jml<br>digunakan",
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
            //-------------------
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
            "no_spk" => "no_spk",
            "spk" => "no_spk",
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
            1 => array(//                "jml",
            ),
            2 => array(
                "jml",
            ),
        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "jml",
            ),
            2 => array(
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
        "receiptElements" => array(
            "cabangTargetID" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cabang tujuan",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array(
                    "id<.0"
//                    "jenis=cabang",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target warehouse",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
                    "id=.9",// ditembak 9 sebagai gudang project dc/pusat
//                    "cabang_id>0",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "produkProjek" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Project",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array("id=pihakProjekID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Nama",
                    "transaksi_no" => "SO Number",
                    "no_kontrak" => "Kontrak",
                    "spek" => "Notes",
                ),
                "editPoints" => array(),
                "noValidate" => true,
            ),
            "projectTasklist" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Info Penugasan",
                "mdlName" => "MdlTasklistProject",
                "mdlFilter" => array(
                    "id=pihakProjekTasklistID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "no_spk" => "No. SPK",
                    "produk_nama" => "Desc Tugas",
                    "produk_paket_nama" => "Paket",
                    "employee_nama" => "Pelaksana",
                    "nilai_sub_fase" => "Nilai Penugasan",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),

//            "workOrderSubDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "SUB WORK ORDER",
//                "mdlName" => "MdlProjectWorkOrderSub",
//                "mdlFilter" => array(
//                    "id=pihakProjekWorkOrderSubID"
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                    "employee_nama" => "pengawas",
//                ),
//                "editPoints" => array(1),
//            ),
        ),
        "connectTo" => "9856",
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),
        "pairMakersProject" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangProjectID",
//                        "cabang_id" => "cabangID",
//                        "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        "gudang_id" => "gudangWorkOrderTarget",
                        "jenis" => ".supplies",
                        "state" => ".active",
                    ),
                    "gate" => "items2",
                ),
//                "dataProduk" => array(
//                    "helperName" => "he_pair_data_produk",
//                    "functionName" => "cekPairDataProduk",
//                    "params" => array(),
//                    "kolom" => array(
//                        "no_part",
//                    ),
//                    "gate" => "items",
//                ),
//                "stokBefore" => array(
//                    "helperName" => "he_cek_stock_produk",
//                    "functionName" => "cekStockProduk",
//                    "params" => array(
////                        "cabang_id" => "placeID",
////                        "gudang_id" => "gudangProjectID",
//                        "cabang_id" => "pihakProjekWorkOrderID",
//                        "gudang_id" => "pihakProjekWorkorderSubGudangID",
//                    ),
//                    "gate" => "items",
//                ),
//                "stokDiterima" => array(
//                    "helperName" => "he_cek_stock_produk",
//                    "functionName" => "cekStockProduk",
//                    "params" => array(
//                        "cabang_id" => "place2ID",
//                        "gudang_id" => "gudang2ID",
////                        "oleh2_nama" => "no_spk",
//                    ),
//                    "gate" => "items",
//                ),
//                "stokIntransit" => array(
//                    "helperName" => "he_cek_stock_produk_locker",
//                    "functionName" => "cekStockProdukLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
////                        "gudang_id" => "gudangProjectID",
//                        "cabang_id" => "pihakProjekWorkOrderID",
//                        "gudang_id" => "pihakProjekWorkorderSubGudangID",
//                        "jenis" => ".produk",
//                        "state" => ".hold",
////                        "oleh2_nama" => "pihakProjekNoSpk",
////                        "oleh2_nama" => "no_spk",
//                    ),
//                    "gate" => "items",
//                ),
            ),
//            2 => array(
//                "dataProduk" => array(
//                    "helperName" => "he_pair_data_produk",
//                    "functionName" => "cekPairDataProduk",
//                    "params" => array(
//                        //                        "cabang_id" => ".-1",
//                        //                        "gudang_id" => ".-1",
//                        //                        "state" => ".active",
//                    ),
//                    "kolom" => array(
//                        "no_part",
//                    ),
//                    "gate" => "items2",
//                ),
//            ),
        ),
        "pairInjectorsProject" => array(
            1 => array(
                "stokProduk" => array(
                    "items2" => array(
                        "targetKey" => "biaya_dasar_id",
                        "targetColumn" => "stok",
                    ),
                ),
//                "stokBefore" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "current_stok",
//                    ),
//                ),
//                "stokIntransit" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "jml_intransit",
//                    ),
//                ),
            ),
//            2 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//                "stokBefore" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "current_stok",
//                    ),
//                ),
//                "stokIntransit" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "jml_intransit",
//                    ),
//                ),
//            ),
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
            "main", "items", "items2"
        ),

        "shoppingCartRowValidators" => array(
            "cabangTargetID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9834re",
                "label" => "EDIT request distribusi ke project",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9834rrj",
                "label" => "REJECT otorisasi return distribusi supplies dari project",
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
//        "serialNumberValidator" => array(
//            2 => array(
//                "enabled" => true,
//                "source" => "items2",
//                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",
//            ),
//        ),
    ),
    "9856" => array(
        "icon" => "fa fa-ship",
        "label" => "penerimaan return distribusi supplies (project)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "return distribusi supplies ke dc",
                "actionLabel" => "return distribusi supplies ke dc",
                "source" => "",
                "target" => "9856r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "penerimaan return distribusi supplies (project)",
                "actionLabel" => "terima",
                "source" => "9856r",
                "target" => "9856",
                "userGroup" => "c_gudang",
                "stateLabel" => "diterima",
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
            "dtime" => "tanggal",
            "nomer" => "no. penerimaan",
            "pihakProjekName" => "proyek",
            "pihakProjekWorkOrderNama" => "work order",
            "pihakProjekTasklistName" => "tugas / pekerjaan",
            "pihakProjekNoSpk" => "no. spk",
            "cabang_nama" => "cabang asal",
            "cabang2_nama" => "tujuan (dc)",
            "item_fields" => "rincian supplies",
            "oleh_nama" => "penerima",
            "next_pic" => "otorisator selanjutnya",
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
                "label" => "supplies",
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
                "dtime" => "tanggal",
                "nomer" => "no. penerimaan",
                "pihakProjekName" => "proyek",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakProjekTasklistName" => "tugas / pekerjaan",
                "pihakProjekNoSpk" => "no. spk",
                "cabang_nama" => "cabang asal",
                "cabang2_nama" => "tujuan (dc)",
                "item_fields" => "rincian supplies",
                "oleh_nama" => "penerima",
                "print_label" => "print",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "tanggal",
                "nomer" => "no. penerimaan",
                "pihakProjekName" => "proyek",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakProjekTasklistName" => "tugas / pekerjaan",
                "pihakProjekNoSpk" => "no. spk",
                "cabang_nama" => "cabang asal",
                "cabang2_nama" => "tujuan (dc)",
                "item_fields" => "rincian supplies",
                "oleh_nama" => "penerima",
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
            "main", "items", "items2"
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "kode",
                "keterangan" => "part number",
//                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "kode",
                "keterangan" => "part number",
//                "no_part" => "part number",
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
            "barcode" => "barcode",
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