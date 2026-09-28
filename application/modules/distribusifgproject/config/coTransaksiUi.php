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
    "5833" => array(
        "icon" => "fa fa-truck",
        "label" => "DISTRIBUSI FG KE-PELAKSANA PROJECT",
        "place" => "center",
        "lockerTimeout" => 3600, // 3600 detik (1 jam) untuk proses pemindaian serial logistik
        "steps" => array(
            1 => array(
                "label" => "DISTRIBUSI FG PROJECT",
                "actionLabel" => "DISTRIBUSI FG KE-PELAKSANA PROJECT",
                "source" => "",
                "target" => "5833r",
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "autoSelectTarget" => true,
            ),
            2 => array(
                "label" => "scan qr/barcode",
                "actionLabel" => "save scan qr/barcode",
                "source" => "5833r",
                "target" => "5833sc",
                "userGroup" => "w_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
//                "allowEdit" => false,
                "allowScaner" => true,
                "lockerTimeout" => 3600, // 3600 detik (1 jam) untuk pemindaian serial
            ),
            3 => array(
                "label" => "OTORISASI DISTRIBUSI FG PROJECT",
                "actionLabel" => "PERSETUJUAN DISTRIBUSI FG PROJECT",
                "source" => "5833sc",
                "target" => "5833",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
//                "allowEdit" => false,
                "autoNextStep" => true,
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
//        "selectorSrcModel" => "MdlTasklistProject",
        "selectorSubSrcModel" => "MdlProjectKomposisiWorkorderSub", //ori
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
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array( //custom filter
            "produk_id=pihakProjekID",
//            "produk_id=pihakProjekID",
//            "fase_id=pihakMainID",
        ),
        "selectorSubFilters" => array(
//            "fase_id=pihakMainID",
            "produk_id=pihakProjekID",
        ),
//        // ini digunakan untuk filter locker_cek
        "selectorLockerProcessFilters" => array(
            "state=.active",
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
        "selectorProcessor" => "_processSelectProduct/selectProject",

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
            "gudangProjectName" => "gudang asal",
            "pihakProjekWorkorderSubGudangNama" => "gudang tujuan",
            "oleh_nama" => "person",
            "pihakProjekNoSpk" => "spk",
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
                "pihakProjekNoSpk" => "spk",
                "pihakProjekWorkOrderSubNama" => "work order",
                "gudangProjectName" => "gudang asal",
                "pihakProjekWorkorderSubGudangNama" => "gudang tujuan",
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
                "pihakProjekNoSpk" => "spk",
                "pihakProjekWorkOrderSubNama" => "work order",
                "gudangProjectName" => "gudang asal",
                "pihakProjekWorkorderSubGudangNama" => "gudang tujuan",
                "oleh_nama" => "pic",
                "next_pic" => "Next step otorisator",
                "keterangan" => "keterangan",
            ),
            3 => array(
                "dtime" => "date",
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "nomer request",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "transaksi scan",
                ),
                "nomer" => "nomer otorisasi",
                "item_fields" => "isi",
                "pihakProjekName" => "project",
                "pihakProjekNoSpk" => "spk",
                "pihakProjekWorkOrderSubNama" => "work order",
                "gudangProjectName" => "gudang asal",
                "pihakProjekWorkorderSubGudangNama" => "gudang tujuan",
                "oleh_nama" => "pic",
                "next_pic" => "Next step otorisator",
                "keterangan" => "keterangan",
            ),
        ),
        "shortStepHistoryFields" => array(
//            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "pengirim",
            "cabang_nama" => "penerima",
            "5833r" => "request number",
            "5833" => "approval number",
            "5855" => "receipt number",

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
        "showInformasiStok" => true,
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "no part",
//
                "company_stok" => "Total Stok<br>Perusahaan",
                "current_stok" => "ready stock<br>Gd. Project DC",
                "jml_wo" => "jumlah<br>diminta",
                "jml_intransit" => "intransit",
                "jml_available" => "stock aktif",
                "jml_diterima" => "SUDAH<BR>DISERAHKAN",
                "jml" => "JUMLAH<BE>DISERAHKAN<BR>SAAT INI",
                "stok" => "total<br>diserahkan",
//                "satuan" => "satuan",
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
//                "no_part" => "no part",
                "jml_wo" => "BOM",
                "company_stok" => "Total Stok<br>Perusahaan",
                "current_stok" => "ready stock<br>Gd. Project DC",
                "jml_intransit" => "intransit",
                "jml_diterima" => "diterima",
                "jml" => "qty",
                "stok" => "stock<BR>akhir",
                "satuan" => "satuan",
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
//                "no_part" => "no part",
                "jml_wo" => "BOM",
                "company_stok" => "Total Stok<br>Perusahaan",
                "current_stok" => "ready stock<br>Gd. Project DC",
                "jml_intransit" => "intransit",
                "jml_diterima" => "diterima",
                "jml" => "qty",
                "stok" => "stock<BR>akhir",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
//            "stok" => "stock<BR>after",
            "company_stok" => "company_stok",
            "current_stok" => "current<BR>stock",
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "jml_wo" => "jml_wo",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
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
//                        "cabang_id" => "placeID",
                        "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(),
                    "kolom" => array(
                        "no_part",
                    ),
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangProjectID",
                    ),
                    "gate" => "items",
                ),
                "stokDiterima" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
//                        "cabang_id" => "place2ID",
                        "gudang_id" => "pihakProjekWorkorderSubGudangID",
                    ),
                    "gate" => "items",
                ),
                "stokIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangProjectID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                    ),
                    "gate" => "items",
                ),
                "stokAktive" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangProjectID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
                ),
                "stokTotal" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangProjectID",
                        "periode" => ".forever",
                    ),
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
                "stokDiterima" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_diterima",
                    ),
                ),
                "stokIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_intransit",
                    ),
                ),
                "stokAktive" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_available",
                    ),
                ),
                "stokTotal" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "company_stok",
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
            "main", "items", "items3_sum"
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "jumlah diserahkan",
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
            1 => array(
                "enabled" => true,
                "source" => "jml_wo",
                "target" => "jml_intransit+jml_diterima",
                "mode" => "disabled", //unset
                "label_warning" => "*bg hijau = pengiriman lengkap (sesuai dengan kebutuhan BOM).<br>*bg kuning = pengiriman melebihi kebutuhan BOM.",
            ),
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
        "scannerQrConfig" => array(
            "enabled" => true,
            "replacerSelector" => array(
                "selectorGudangID" => "gudangProjectID",
                "selectorGudangName" => "gudangProjectName",
            ),
            "label" => "Silahkan scan/input Barcode/QRCode Serial/SKU produk dari {gudangProjectID}.",
        ),

        "shoppingCartFieldsLink" => array(
            1 => array(
                "jml_intransit" => array(
                    "cabang_id" => "placeID",
                    "gudang_id" => "gudangProjectID",
                ),

            ),
        ),

    ),

    "5855" => array(
        "icon" => "fa fa-ship",
        "label" => "pre penerimaan distribusi fg project",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "pre penerimaan distribusi fg project oleh pelaksana project",
                "actionLabel" => "pre terima distribusi fg project",
                "source" => "",
                "target" => "5855r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "penerimaan distribusi fg project oleh pelaksana project",
                "actionLabel" => "terima distribusi fg project",
                "source" => "5855r",
                "target" => "5855",
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
            "gudangProjectName" => "gudang asal",
            "pihakProjekWorkorderSubGudangNama" => "gudang tujuan",
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
                "cabang2_nama" => "pengirim",
                // "nomer_top" => "SO number",
                "nomer" => "nomer",
                "item_fields" => "isi",
                "pihakProjekName" => "project",
                "pihakProjekWorkOrderNama" => "work order",
                "gudangProjectName" => "gudang asal",
                "pihakProjekWorkorderSubGudangNama" => "gudang tujuan",
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
                "gudangProjectName" => "gudang asal",
                "pihakProjekWorkorderSubGudangNama" => "gudang tujuan",
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
    "9833" => array(
        "icon" => "fa fa-truck",
        "label" => "pengembalian kelebihan bahan baku project",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request return distribusi ke dc",
                "actionLabel" => "return distribusi ke dc",
                "source" => "",
                "target" => "9833r",
                "userGroup" => "o_project",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "return stok ke dc (scan qr/barcode)",
                "actionLabel" => "simpan scan qr/barcode",
                "source" => "9833r",
                "target" => "9833sc",
                "userGroup" => "o_project_spv",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "otorisasi return distribusi ke dc",
                "actionLabel" => "approve return distribusi ke dc",
                "source" => "9833sc",
                "target" => "9833",
                "userGroup" => "o_project_spv",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
            ),
        ),
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

        "selectorModel" => "MdlTasklistProject",
        "selectorSrcModel" => "MdlProjectWorkOrderSub",
        "selectorSubSrcModel" => "MdlProjectKomposisiWorkorderSub",
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
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "produk_id=pihakProjekID",
//            "customer_id=pihakID",
//            "transaksi_id=.0",
//            "produk_id=pihakProjekID",
        ),
        "selectorSubFilters" => array(
//            "fase_id=pihakMainID",
            "produk_id=pihakProjekID",
        ),
//        // ini digunakan untuk filter locker_cek
        "selectorLockerProcessFilters" => array(
//            "state=.active",
//            "cabang_id=placeID",
//            "gudang_id=pihakProjekWorkorderGudangID",
//            "gudang_id=gudangID",
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
        "selectorProcessor" => "_processSelectProduct/selectProject",

        // PILIH CABANG
        "editHandlerMethod" => "select",
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
            "item_fields" => "rincian fg",
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
                "item_fields" => "rincian fg",
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
                "item_fields" => "rincian fg",
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
                "item_fields" => "rincian fg",
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
            "9833r" => "no. request return",
            "9833" => "no. otorisasi return",
            "9855" => "no. penerimaan di dc",

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
                "nama" => array(
                    "label" => "produk",
                    "addKey" => "static_keterangan",
                ),
//                "produk_kode" => "kode",
//                "keterangan" => "part number",
//                "no_part" => "part number",
                "jml_wo" => "B.O.M",
                "jml_used" => "jml sudah<br>digunakan project",
//                "current_stok" => "stock<BR>available",
                "stok_awal" => "stock<BR>available",
//                "jml_intransit" => "intransit",
//                "jml_diterima" => "diterima",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "uom",
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
                "label" => "Project",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array("id=pihakProjekID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Nama",
                    "transaksi_id" => "SO ID",
                    "transaksi_no" => "SO Number",
                ),
                "editPoints" => array(),
                "noValidate" => true,
            ),
            "workOrderDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "WORK ORDER",
                "mdlName" => "MdlProjectWorkOrder",
                "mdlFilter" => array(
                    "id=pihakProjekWorkOrderID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "employee_nama" => "pengawas",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "workOrderSubDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUB WORK ORDER",
                "mdlName" => "MdlProjectWorkOrderSub",
                "mdlFilter" => array(
                    "id=pihakProjekWorkOrderSubID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "employee_nama" => "pengawas",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
        ),
        "connectTo" => "9855",
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
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangProjectID",
                        "cabang_id" => "cabangID",
                        "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(),
                    "kolom" => array(
                        "no_part",
                    ),
                    "gate" => "items",
                ),
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
//                    "gate" => "items",
//                ),
//            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
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
            2 => array(
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
                "stokIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_intransit",
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
            "main", "items", "items3_sum"
        ),

        "shoppingCartRowValidators" => array(
//            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9833re",
                "label" => "EDIT request distribusi ke project",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9833rrj",
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
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",
            ),
        ),
        "scannerQrConfig" => array(
            "enabled" => true,
            "replacerSelector" => array(
                "selectorGudangID" => "pihakProjekWorkorderSubGudangID",
                "selectorGudangName" => "pihakProjekWorkorderSubGudangName",
            ),
            "label" => "Silahkan scan/input Barcode/QRCode Serial/SKU produk dari {pihakProjekWorkorderSubGudangID}.",
        ),
    ),

    "9855" => array(
        "icon" => "fa fa-ship",
        "label" => "penerimaan return distribusi (project)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "return distribusi ke dc",
                "actionLabel" => "return distribusi ke dc",
                "source" => "",
                "target" => "9855r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "penerimaan return distribusi (project)",
                "actionLabel" => "terima",
                "source" => "9855r",
                "target" => "9855",
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
            "nomer" => "no. return",
            "pihakProjekName" => "proyek",
            "pihakProjekWorkOrderNama" => "work order",
            "pihakProjekTasklistName" => "tugas / pekerjaan",
            "pihakProjekNoSpk" => "no. spk",
            "cabang_nama" => "cabang asal",
            "cabang2_nama" => "tujuan (dc)",
            "item_fields" => "rincian fg",
            "oleh_nama" => "pemohon",
            "next_pic" => "otorisator selanjutnya",
            "keterangan" => "keterangan",
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
                "dtime" => "tanggal",
                "nomer" => "no. request return",
                "pihakProjekName" => "proyek",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakProjekTasklistName" => "tugas / pekerjaan",
                "pihakProjekNoSpk" => "no. spk",
                "cabang_nama" => "cabang asal",
                "cabang2_nama" => "tujuan (dc)",
                "item_fields" => "rincian fg",
                "oleh_nama" => "pemohon",
                "next_pic" => "otorisator selanjutnya",
                "keterangan" => "keterangan",
                "print_label" => "print",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "tanggal",
                "nomer_top" => "no. request return",
                "nomer" => "no. otorisasi return",
                "pihakProjekName" => "proyek",
                "pihakProjekWorkOrderNama" => "work order",
                "pihakProjekTasklistName" => "tugas / pekerjaan",
                "pihakProjekNoSpk" => "no. spk",
                "cabang_nama" => "cabang asal",
                "cabang2_nama" => "tujuan (dc)",
                "item_fields" => "rincian fg",
                "oleh_nama" => "pemohon",
                "next_pic" => "otorisator selanjutnya",
                "keterangan" => "keterangan",
                "print_label" => "print",
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
                "item_fields" => "rincian fg",
                "oleh_nama" => "pemohon",
                "next_pic" => "otorisator selanjutnya",
                "keterangan" => "keterangan",
                "print_label" => "print",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang_nama" => "cabang asal",
            "cabang2_nama" => "tujuan (dc)",
            "9833r" => "no. request return",
            "9833" => "no. otorisasi return",
            "9855" => "no. penerimaan di dc",
            "oleh_nama" => "pemohon",
            "next_pic" => "otorisator selanjutnya",
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