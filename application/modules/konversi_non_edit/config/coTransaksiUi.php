<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

$config["coTransaksiUi"] = array(
    //  config konversi finish goods dioffkan belum suport CLI
    "1334" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi produk (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request konversi produk",
                "actionLabel" => "simpan request konversi produk",
                "source" => "",
                "target" => "1334r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi konversi produk",
                "actionLabel" => "approve",
                "source" => "1334r",
                "target" => "1334",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",//ini menghandle produk dan produk rakitan
        "selectedPrice" => array(
            "enabled" => false,
            //            "model" => "MdlHargaProduk",//ini hanya menghandle produk
            "model" => "MdlHargaProduk2",//ini menghandle produk dan produk rakitan
            "label" => array("hpp"),
            "key_label" => array(
                "hpp_nppv" => "harga",
            ),
            "mainSrc" => "hpp_nppv",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "nama", "satuan", "jumlah",
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductConvertion/select",
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
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
//                "produk_kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
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
            //-------------------
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
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
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartPairedItemRecorder" => "recordPairedItem",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlProduk2",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
//            "hpp" => "hpp",// dimatikan supaya tidak mencari hpp target konversi
        ),
        "shoppingCartValidatorsPairedItem" => array(
            "sumber" => "items",
            "target" => "items2_sum",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1334re",
                "label" => "EDIT product conversion request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1334rrj",
                "label" => "REJECT product conversion request",
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
    // konversi supplies ke produk (center)
    "2334" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi supplies ke produk (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request konversi supplies ke produk",
                "actionLabel" => "request konversi",
                "source" => "",
                "target" => "2334r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi konversi supplies ke produk",
                "actionLabel" => "approve request konversi",
                "source" => "2334r",
                "target" => "2334",
                "userGroup" => "c_gudang",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",

        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",

        "selectorModelTarget" => "MdlProduk2",
        "selectorSrcModelTarget" => "MdlProduk2",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectSuppliesConvertion/select",
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
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "nomer" => "request number",
            "nomer_approve" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "approval number",
            ),
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),

        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "product name",
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
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        //        "shoppingCartPairedItemEnabled" => true,
        "shoppingCartPairedItemRecorder" => "recordPairedItemOther",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlProduk2",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array(//                "id<>id"
            ),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
            //            "hpp" => "hpp",
        ),
        //        "allowedMainEdit"         => array("1"),
        "shoppingCartValidatorsPairedItem" => array(
            //            "sumber" => "items",
            //            "target" => "items2_sum",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2334re",
                "label" => "EDIT request konversi supplies ke produk",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2334rrj",
                "label" => "REJECT request konversi supplies ke produk",
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
    // konversi produk ke supplies (center)
    "2336" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi produk ke supplies (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request konversi produk ke supplies",
                "actionLabel" => "request konversi",
                "source" => "",
                "target" => "2336r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi konversi produk ke supplies",
                "actionLabel" => "approve request konversi",
                "source" => "2336r",
                "target" => "2336",
                "userGroup" => "c_gudang",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",

        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",

        "selectorModelTarget" => "MdlSupplies",
        "selectorSrcModelTarget" => "MdlSupplies",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectSuppliesConvertion/select",
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
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "nomer" => "request number",
            "nomer_approve" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "approval number",
            ),
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),

        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "jml" => "qty",
                "satuan" => "satuan",
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
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        //        "shoppingCartPairedItemEnabled" => true,
        "shoppingCartPairedItemRecorder" => "recordPairedItemOther",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlSupplies",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array(//                "id<>id"
            ),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
            //            "hpp" => "hpp",
        ),
        //        "allowedMainEdit"         => array("1"),
        "shoppingCartValidatorsPairedItem" => array(
            //            "sumber" => "items",
            //            "target" => "items2_sum",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2336re",
                "label" => "EDIT request konversi produk ke supplies",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2336rrj",
                "label" => "REJECT request konversi produk ke supplies",
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
    // konversi produk ke supplies (branch)
    "2337" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi produk ke supplies (branch)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request konversi produk ke supplies",
                "actionLabel" => "request konversi",
                "source" => "",
                "target" => "2337r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi konversi produk ke supplies",
                "actionLabel" => "approve request konversi",
                "source" => "2337r",
                "target" => "2337",
                "userGroup" => "o_gudang",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",

        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",

        "selectorModelTarget" => "MdlSupplies",
        "selectorSrcModelTarget" => "MdlSupplies",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectSuppliesConvertion/select",
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
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "nomer" => "request number",
            "nomer_approve" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "approval number",
            ),
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),

        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "jml" => "qty",
                "satuan" => "satuan",
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
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartPairedItemRecorder" => "recordPairedItemOther",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlSupplies",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array(//                "id<>id"
            ),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
            //            "hpp" => "hpp",
        ),
        "shoppingCartValidatorsPairedItem" => array(
            //            "sumber" => "items",
            //            "target" => "items2_sum",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2337re",
                "label" => "EDIT request konversi produk ke supplies",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2337rrj",
                "label" => "REJECT request konversi produk ke supplies",
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
    //konversi finish good
    "334" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi produk (branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request konversi produk",
                "actionLabel" => "simpan request konversi produk",
                "source" => "",
                "target" => "334r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi konversi produk",
                "actionLabel" => "approve",
                "source" => "334r",
                "target" => "334",
                "userGroup" => "o_gudang_spv",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",//ini menghandle produk dan produk rakitan
        "selectedPrice" => array(
            "enabled" => false,
            //            "model" => "MdlHargaProduk",//ini hanya menghandle produk
            "model" => "MdlHargaProduk2",//ini menghandle produk dan produk rakitan
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "nama", "satuan", "jumlah",
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductConvertion/select",
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
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                //                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                //                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
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
                "nama" => "item source name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item source name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            //            3 => array(
            //                "nama" => "item source name",
            //                "code" => "product code",
            //                "jml" => "qty",
            //                "satuan" => "satuan",
            //            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item target name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item target name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            //            3 => array(
            //                "nama" => "item target name",
            //                "jml" => "qty",
            //                "satuan" => "satuan",
            //            ),
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
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        //        "shoppingCartPairedItemEnabled" => true,
        "shoppingCartPairedItemRecorder" => "recordPairedItem",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlProduk2",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
//            "hpp" => "hpp",// dimatikan supaya tidak mencari hpp target konversi
        ),
        //                "allowedMainEdit"         => array("1"),
        "shoppingCartValidatorsPairedItem" => array(
            "sumber" => "items",
            "target" => "items2_sum",
        ),
        "pairMakers" => array(
            2 => array(
                //                "preBiaya" => array(
                //                    "helperName" => "he_pair_produksi_prebiaya_opname_helper",
                //                    "functionName" => "cekPairProduksiPreBiayaOpname",
                //                    "source" => "rsltItems",
                //                    "sourceKey" => array(
                //                        "hpp_sumber", "hpp_target",
                //                    ),
                //                ),
                "preBiayaMain" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_main_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpnameMain",
                    "source" => "main",
                    "sourceKey" => array(
                        "kredit", "debet", "kredit_rsltItems", "hpp_sumber", "hpp_target",
                    ),
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "334re",
                "label" => "EDIT conversion request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "334rrj",
                "label" => "REJECT conversion request",
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
    // -------------------------------------------
    //  config konversi supplies (satuan), branch...
    "335" => array(
        "icon" => "fa fa-cube",
        "label" => "supplies conversion (satuan)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "conversion (satuan) request",
                "actionLabel" => "make conversion request",
                "source" => "",
                "target" => "335r",
                "userGroup" => "p_produksi",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "authorization conversion",
                "actionLabel" => "approve conversion (satuan) request",
                "source" => "335r",
                "target" => "335",
                "userGroup" => "p_produksi_spv",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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

        "selectorProcessor" => "_processSelectProductConvertionSatuan/select",
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
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name source",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name source",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item name target",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name target",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false, // ini notes per-items
        //        "shoppingCartPairedItemEnabled" => true,

        "shoppingCartPairedItemRecorder" => "recordPairedItemSatuan",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlSupplies",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "shoppingCartFieldsPairedItem" => array(
            1 => array(
                //                "nama" => "item name target",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),

        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            //            2 => array(
            ////            "harga",
            ////            "ppn",
            //                "jml",
            //            ),
            //            3 => array(
            ////            "harga",
            ////            "ppn",
            //                "jml",
            //            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
            //            "hpp" => "hpp",
        ),
        "shoppingCartFieldMidValidatorsPairedItem" => array(
            "hpp_sumber" => "sumber",
            "hpp_target" => "target",
        ),
        "shoppingCartValidatorsPairedItem" => array(
            "sumber" => "items",
            "target" => "items2_sum",
        ),
        "previewCtr" => "Create",
        //        "shoppingCartValidatorsTargetItem" => array(
        //            "sumber" => "items",
        //            "target" => "items2_sum",
        //        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "335re",
                "label" => "EDIT conversion (satuan) request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "335rrj",
                "label" => "REJECT conversion (satuan) request",
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
    // konversi supplies ke produk (branch)
    "2335" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi supplies ke produk (branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request konversi supplies ke produk",
                "actionLabel" => "request konversi",
                "source" => "",
                "target" => "2335r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi konversi supplies ke produk",
                "actionLabel" => "approve request konversi",
                "source" => "2335r",
                "target" => "2335",
                "userGroup" => "o_gudang",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",

        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",

        "selectorModelTarget" => "MdlProduk2",
        "selectorSrcModelTarget" => "MdlProduk2",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectSuppliesConvertion/select",
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
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "nomer" => "request number",
            "nomer_approve" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "approval number",
            ),
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "supplies name",
                "produk_kode" => "supplies code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),

        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "product name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "product name",
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
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        //        "shoppingCartPairedItemEnabled" => true,
        "shoppingCartPairedItemRecorder" => "recordPairedItemOther",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlProduk2",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array(//                "id<>id"
            ),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
            //            "hpp" => "hpp",
        ),
        //        "allowedMainEdit"         => array("1"),
        "shoppingCartValidatorsPairedItem" => array(
            //            "sumber" => "items",
            //            "target" => "items2_sum",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2335re",
                "label" => "EDIT request konversi supplies ke produk",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2335rrj",
                "label" => "REJECT request konversi supplies ke produk",
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

    "1337" => array(
        "icon" => "fa fa-cube",
        "label" => "supplies conversion",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "supplies conversion request",
                "actionLabel" => "make conversion request",
                "source" => "",
                "target" => "1337r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "supplies conversion",
                "actionLabel" => "approve conversion request",
                "source" => "1337r",
                "target" => "1337",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",

        "selectedPrice" => array(
            "enabled" => false,
            "model" => "MdlHargaSupplies",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "nama", "satuan", "jumlah",
            //            "keterangan",
            //            "kode",
            "nama",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectSuppliesConvertion/select",
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
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item source name",
                //                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item source name",
                //                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item target name",
                //                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item target name",
                //                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
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
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        //        "shoppingCartPairedItemEnabled" => true,
        "shoppingCartPairedItemRecorder" => "recordPairedItem",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlSupplies",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
//            "hpp" => "hpp",// dimatikan supaya tidak mencari hpp target konversi
            //            "hpp_target" => "hpp target",
        ),
        //        "allowedMainEdit"         => array("1"),
        "shoppingCartValidatorsPairedItem" => array(
            "sumber" => "items",
            "target" => "items2_sum",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1337re",
                "label" => "EDIT supplies conversion request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1337rrj",
                "label" => "REJECT supplies conversion request",
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

    //adjustment supplies ke aset dioffkan belum suport CLI
    "7620" => array(
        "icon" => "fa fa-circle",
        "label" => "penambahan nilai aset(from supplies)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request penambahan nilai aset",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "7620r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "approval penambahan nilai aset",
                "actionLabel" => "approve",
                "source" => "7620r",
                "target" => "7620",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label" => "Realisasi PPN",
                "actionLabel" => "entry faktur",
                "source" => "7620",
                "target" => "7620f",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        // "template" => "application/template/transaksi.html",
        // "template" => "application/template/transaksi_supplies_biaya.html",
        "template" => "template/transaksi_pihak4.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockSupplies",
        ),

        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
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
            "jumlah",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectSupplies/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(//            "id>.0",
        ),
        "pihakProcessor" => "_processPihak/select",

        "pihakModelMainRules" => "MdlFolderAset",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "kategory",
        "pihakMainFiltersRules" => array(),
        "pihakMainRulePair" => array(
            //            "MdlName" => "MdlAsetDetail",
            //            "viewdFields" => array("kode", "serial_no"),
        ),
        //        "pihakMainRuleKeys" =>"produk_id",
        "pihakMainValueSrcRules" => array(
            //            "pihakMdlName" => "mdl_name",
            "pihakMainRulesID_coa" => "coa_code",
            "pihakMainRulesName_coa" => "nama",
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",

        "pihakModelMain" => "MdlAsetDetail",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "target aset",
        "pihakMainFilters" => array(
            //            "cabang_id=pihakID",
            "folders=pihakMainRulesID"
            //            "jumlah>.0",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainPair" => array(
            "MdlName" => "MdlLockerStockAktiva",
            "filter" => array(
                "cabang_id=pihakID",
                "jumlah>.0",
            ),
            //            "viewdFields" => array("kode", "serial_no"),
        ),
        "pihakMainViewedFields" => array(
            "nama",
            "kode",
            "serial_no",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
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
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            3 => array(
                "harga" => "price",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total",
                "ppn" => "VAT",
                "grandTotal" => "Grand Total",
            ),
            2 => array(
                "harga" => "total",
                "ppn" => "VAT",
                "grandTotal" => "Grand Total",
            ),
            3 => array(
                "harga" => "total",
                "ppn" => "VAT",
                "grandTotal" => "Grand Total",
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
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartNoteType" => "textarea",
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
            "pihakMainRulesID" => "target aset",
            //            "pihak2Name" => "category expense",

        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
            3 => "jml*harga",
        ),
        "shoppingCartAvoidRemove" => false,
        "resumeFieldNames" => array(
            "selectFields" => "cabang_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            "branchTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "lokasi aset",
                "mdlName" => "MdlCabang",
                "key" => "id",
                "mdlFilter" => array(
                    "id=pihakID",
                ),
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),

            ),
            "asetTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target aset",
                "mdlName" => "MdlAsetDetail",
                "key" => "id",
                "mdlFilter" => array(
                    "id=pihakMainID",
                ),
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "kode" => "kode",
                    "serial_no" => "no seri",

                ),
                "editPoints" => array(1),
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        // "jenis" => ".supplies",
                        "state" => ".active",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokSupplies" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "allowedMainEdit" => array("3"),
        "addMainSource" => array(
            3 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "harga" => "DPP",
                    "ppn" => "PPN (belum ada faktur)",
                    "ppn_realisasi" => "PPN Realisasi",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer faktur",
                ),
                "editableFields" => array(
                    "harga" => "number",
                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            3 => true,
        ),
        "efakturValidator" => array(
            3 => array(
                "enabled" => true,
                "kolom" => array(
                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
                    "eFaktur" => "nomer e-faktur belum diisikan.",
                ),
                "source" => array(
                    "ppn", // lebih dari 0
                    //                "ppnfactor",
                ),
            ),
        ),
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7620re",
                "label" => "EDIT supplies conversion request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7620rrj",
                "label" => "REJECT supplies conversion request",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "7620rj",
                "label" => "REJECT approval penambahan nilai aset",
            ),
        ),
    ),
    //konversi supplies to aset baru
    "7622" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "konversi supplies to new Aset",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request supplies conversion to new aset",
                "actionLabel" => "save",
                "source" => "",
                "target" => "7622r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "authorization supplies conversion to new aset ",
                "actionLabel" => "approved",
                "source" => "7622r",
                "target" => "7622a",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "aset receive",
                "actionLabel" => "receive",
                "source" => "7622a",
                "target" => "7622",
                "userGroup" => "c_gudang",
                "stateLabel" => "ASET made",
                "stateColor" => "#ff7700",
                "stateCaption" => "receive by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            4 => array(
                "label" => "Realisasi PPN",
                "actionLabel" => "approve efaktur",
                "source" => "7622",
                "target" => "7622f",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi_pihak3.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockSupplies",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "jumlah",
            "satuan",
            //            "satuan",
        ),
        //        "selectorProcessor" => "_processSelectBiaya/select",
        "selectorProcessor" => "_processSelectSupplies/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlFolderAset",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "kategori aset",
        "pihakFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc2" => array(//            "ppnFactor" => "ppn",
            "pihakMdlName" => "mdl_name",
        ),
        "pihakProcessor" => "_processPihak/select",
        //tambahan pihak rules misal selector ppn
        "pihakModelMainRules" => "MdlAsetBerwujud",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "aset",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrcRules" => array(//            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",
        //tambahan pihak2

        "mainselectorModel" => array(),

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "suppliers_nama"  => "vendor",
            "nomer_top" => "PRE-PO number",
            //            "nomer" => "receipt number",
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "GRN number",
            ),
            "oleh_nama" => "person",
            "transaksi_nilai" => "price",
            "ppn" => "ppn",
            //            "other"           => "other (+)",
            "grandTotal" => "total amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "price",
                "ppn" => "ppn",
                //            "other"           => "other (+)",
                "grandTotal" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "price",
                "ppn" => "ppn",
                //            "other"           => "other (+)",
                "grandTotal" => "total amount",
                "print_label" => "tool",
            ),


        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
            ),
            2 => array(
                //                "review_details" =>"id",
                "print_label" => "nomer",
            ),

        ),

        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "reference" => "reference",
            //            "ppn_persen" => "ppn_persen",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "stok" => "stock",
                "jml" => "qty",
                //                "reference" => "reference",
                //                "ppn_persen" => "vat(%)",
            ),
            2 => array(
                "nama" => "item name",
                "stok" => "stock",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
            3 => array(
                "nama" => "item name",
                "stok" => "stock",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                // "ppn" => "VAT",
                //                "non_ppn" => "Non PPN<br>PPN (-)",
                //                "other" => "other (+)",
            ),
            2 => array(
                "harga" => "Price",
                // "ppn" => "VAT",
                //                "other" => "other (+)",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
            4 => array(
                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nett1" => "Total Amount",
                "ppn" => "VAT",
                "grandTotal" => "Grand Total",
            ),
            2 => array(
                "nett1" => "Total Amount",
                "dpp_vat" => "DPP VAT",
                "ppn" => "VAT",
                "grandTotal" => "Grand Total",
            ),
            3 => array(
                //                "nett1" => "Total Amount",
                //                "dpp_vat" => "DPP VAT",
                //                "ppn" => "VAT",
                //                "nett2" => "Grand Total",
            ),
            4 => array(
                "nett1" => "Total Amount",
                // "nett1" => "DPP VAT",
                "ppn" => "VAT",
                "grandTotal" => "Grand Total",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => true,
            //            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //                "harga",
                "jml",
                //                "non_ppn",
                //                "other",
                "ppn_persen",
                "reference",
            ),
            2 => array(),
            3 => array(//                "jml",
            ),
            4 => array(//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
            3 => "jml*harga",
            4 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => false,
        "shoppingCartImageType" => "images",
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => false,
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),
        "shopingCartDetailFields" => array(
            "2" => array(
                "fields" => array(
                    "nama" => "nama",
                    "label" => "label",
                    "merk" => "merk",
                    "serial_no" => "nomer seri",
                    "kode" => "kode",
                    //                    "deskripsi" => "deskripsi",
                    //                   "harga" =>"harga",
                ),
                "editable" => array(
                    "label" => "label",
                    "merk" => "merk",
                    "serial_no" => "nomer seri",
                    "kode" => "kode",
                    //                    "deskripsi" => "deskripsi",
                ),
                //ini untuk replace fields karena gerbang dari main supaya tidak rebutan dengan gerbang items
                "fieldAlias" => array(
                    "nama" => "pihakMainRulesName",
                    "label" => "label",
                    "merk" => "merk",
                    "serial_no" => "serial_no",
                    "kode" => "kode",
                ),
                "changeToItems" => array(
                    "main" => array(
                        "id" => "pihakMainRulesID",
                        "nama" => "pihakMainRulesName",
                        "label" => "",
                        "harga_disc" => "harga_disc",
                        "ppn" => "ppn",
                        "nett" => "nett1",
                        "srcAccount" => "pihakMainRulesName",
                        "harga_dipakai" => "nett2",
                        "name" => "pihakMainRulesName",
                        "sub_harga" => "nett2",
                        "sub_subtotal" => "nett2",
                        "sub_discount_persen" => "",
                        "sub_discount_qty" => "",
                        "sub_reference" => "",
                        "sub_disc" => "",
                        "sub_harga_disc" => "",
                        "sub_harga_other" => "",
                        "sub_ppn" => "ppn",
                        "sub_hpp_nppn" => "nett2",
                        "sub_nett" => "subtotal",
                        "sub_harga_dipakai" => "nett1",
                        "olehID" => "olehID",
                        "olehName" => "olehName",
                        "pihakID" => "placeID",
                        "pihakName" => "placeName",
                        "pihakMainID" => 'pihakID',
                        "pihakMainName" => "pihakName",
                        "pihakMainID_coa" => 'pihakID_coa',
                        "pihakMainName_coa" => "pihakName_coa",
                        "placeID" => "placeID",
                        "placeName" => "placeName",
                        "cabangID" => "cabangID",
                        "cabangName" => "cabangName",
                        "gudangID" => "gudangID",
                        "gudangName" => "gudangName",
                        "jenisTr" => "jenisTr",
                        "jenisTrMaster" => "jenisTrMaster",
                        "ppn_persen_dipakai" => "",
                        "next_substep_code" => "next_step_code",
                        "next_subgroup_code" => "next_group_code",
                        "sub_step_number" => "sub_step_number",
                        "sub_step_current" => "sub_step_current",
                        "other" => "",
                        "sub_other" => "",
                        "transaksi_id" => "transaksi_id",
                        "nomer" => "nomer",
                        "packed_jml" => "",
                        "sent_jml" => "",
                        "cancel_jml" => "",
                        "req_cancel_jml" => "",
                        "sub_max_jml" => "",
                        "sub_packed_jml" => "",
                        "sub_cancel_jml" => "",
                        "sub_req_cancel_jml" => "",
                        "masterID" => "masterID",
                    ),
                ),
                "gate" => "main",
            ),
        ),
        "pairMakers" => array(
            1 => array(
                "stokSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokSupplies" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "receiptElements" => array(
            //            "gudang" => array(
            //                "elementType" => "dataModel",
            //                "inputType"   => "radio",
            //                "label"       => "target warehouse",
            //                "mdlName"     => "MdlGudangDefault",
            //                "mdlFilter"   => array("cabang_id=cabangID"),
            //                "key"         => "id",
            //                "labelSrc"    => "name",
            //                "usedFields"  => array(
            //                    "name" => "",
            //                ),
            //                "editPoints"  => array(1, 2, 3),
            //            ),

        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "addDetailData" => array(
            2 => array("mdlName" => "MdlAsetDetail"),
        ),
        "allowedMainEdit" => array("4"),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "harga" => "DPP",
                    "ppn" => "PPN (belum ada faktur)",
                    "ppn_realisasi" => "PPN Realisasi",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer faktur",
                ),
                "editableFields" => array(
                    "harga" => "number",
                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            4 => true,
        ),
        "efakturValidator" => array(
            4 => array(
                "enabled" => true,
                "kolom" => array(
                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
                    "eFaktur" => "nomer e-faktur belum diisikan.",
                ),
                "source" => array(
                    "ppn", // lebih dari 0
                    //                "ppnfactor",
                ),
            ),
        ),
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7622re",
                "label" => "EDIT request supplies conversion to new aset",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7622rrj",
                "label" => "REJECT request supplies conversion to new aset",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "7622arj",
                "label" => "REJECT authorization supplies conversion to new aset",
            ),
        ),
    ),


    // konversi dari unit ke non unit
    "1336" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi produk unit ke non unit (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request konversi produk unit ke non unit",
                "actionLabel" => "simpan konversi produk unit ke non unit",
                "source" => "",
                "target" => "1336r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "scan qr/barcode konversi produk unit ke non unit",
                "actionLabel" => "simpan scan qr/barcode",
                "source" => "1336r",
                "target" => "1336sc",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "otorisasi konversi produk unit ke non unit",
                "actionLabel" => "approve konversi produk unit ke non unit",
                "source" => "1336sc",
                "target" => "1336",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",//ini menghandle produk dan produk rakitan
        "selectedPrice" => array(
            "enabled" => false,
            "model" => "MdlHargaProduk2",//ini menghandle produk dan produk rakitan
            "label" => array("hpp"),
            "key_label" => array(
                "hpp_nppv" => "harga",
            ),
            "mainSrc" => "hpp_nppv",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
            "produk.kategori_id=.1",// produk sumber konversi berupa unit
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "nama", "satuan", "jumlah",
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
            "kategori_nama",
            "sub_kategori_nama",
        ),

        "selectorProcessor" => "_processSelectProductConvertion/select",
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
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi konversi",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "item_fields" => "isi konversi",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval 1 number",
                "item_fields" => "isi konversi",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            3 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "approval 1 number",
                ),
                "nomer" => "approval 2 number",
                "item_fields" => "isi konversi",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "kode" => "sku",
//                "keterangan" => "part number",
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
            //-------------------
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
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
                "hpp_avg" => "hpp",
            ),
            2 => array(
                "hpp_avg" => "hpp",
            ),
            3 => array(
                "hpp_avg" => "hpp",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "hpp_avg" => "hpp",
            ),
            2 => array(
                "hpp_avg" => "hpp",
            ),
            3 => array(
                "hpp_avg" => "hpp",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartPairedItemRecorder" => "recordPairedItem",
        "shoppingCartPairedItem" => array(
            "enabled" => false,
            "mdlName" => "MdlProduk2",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
//                "jml",
                "hpp_avg",
            ),
            2 => array(
//                "jml",
//                "hpp_avg",
            ),
            3 => array(
//                "jml",
                "hpp_avg",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
            ),
            4 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),

            ),
        ),
        //--------------------------------------------
        "shoppingCartPairedItemBreakDown" => array(
            "enabled" => true,
            "itemRecorder" => "_processSelectProductConvertion/selectPaired",
            "hitungUlangHppTarget" => true,
        ),
        "shoppingCartPairedItemBreakDownValidator" => array(
            1 => array(
                "enabled" => true,
            ),
            3 => array(
                "enabled" => true,
            ),
        ),
        "shoppingCartPairedItemBreakDownPartValidator" => array(
            1 => array(
                "enabled" => true,
                "source" => "items",
                "target" => "items4",
            ),
        ),
        //--------------------------------------------
        "pairMakers" => array(
            1 => array(
                "hpp_avg" => array(
                    "helperName" => "he_cek_stock_produk_hpp_avg",
                    "functionName" => "cekStockProdukHppAvg",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                    ),
                ),
            ),
            3 => array(
                "hpp_avg" => array(
                    "helperName" => "he_cek_stock_produk_hpp_avg",
                    "functionName" => "cekStockProdukHppAvg",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "hpp_avg" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "hpp_avg",
                    ),
                ),
            ),
            3 => array(
                "hpp_avg" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "hpp_avg",
                    ),
                ),
            ),
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
//            "hpp" => "hpp",// dimatikan supaya tidak mencari hpp target konversi
        ),

        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1336re",
                "label" => "EDIT product conversion request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1336rrj",
                "label" => "REJECT product conversion request",
            ),
        ),
        //----
        "pairRegistries" => array(
            "main", "items", "items4"
        ),
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
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
    "336" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi produk unit ke non unit (branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request konversi produk unit ke non unit",
                "actionLabel" => "simpan konversi produk unit ke non unit",
                "source" => "",
                "target" => "336r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "scan qr/barcode konversi produk unit ke non unit",
                "actionLabel" => "simpan scan qr/barcode",
                "source" => "336r",
                "target" => "336sc",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "otorisasi konversi produk unit ke non unit",
                "actionLabel" => "approve konversi produk unit ke non unit",
                "source" => "336sc",
                "target" => "336",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",//ini menghandle produk dan produk rakitan
        "selectedPrice" => array(
            "enabled" => false,
            "model" => "MdlHargaProduk2",//ini menghandle produk dan produk rakitan
            "label" => array("hpp"),
            "key_label" => array(
                "hpp_nppv" => "harga",
            ),
            "mainSrc" => "hpp_nppv",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
            "produk.kategori_id=.1",// produk sumber konversi berupa unit
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            //            "nama", "satuan", "jumlah",
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
            "kategori_nama",
            "sub_kategori_nama",
        ),

        "selectorProcessor" => "_processSelectProductConvertion/select",
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
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi konversi",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "item_fields" => "isi konversi",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval 1 number",
                "item_fields" => "isi konversi",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            3 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "approval 1 number",
                ),
                "nomer" => "approval 2 number",
                "item_fields" => "isi konversi",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
//                "keterangan" => "part number",
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
            //-------------------
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
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
                "hpp_avg" => "hpp",
            ),
            2 => array(
                "hpp_avg" => "hpp",
            ),
            3 => array(
                "hpp_avg" => "hpp",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "hpp_avg" => "hpp",
            ),
            2 => array(
                "hpp_avg" => "hpp",
            ),
            3 => array(
                "hpp_avg" => "hpp",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartPairedItemRecorder" => "recordPairedItem",
        "shoppingCartPairedItem" => array(
            "enabled" => false,
            "mdlName" => "MdlProduk2",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
//                "jml",
                "hpp_avg",
            ),
            2 => array(
//                "jml",
//                "hpp_avg",
            ),
            3 => array(
//                "jml",
                "hpp_avg",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
            ),
            4 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),

            ),
        ),
        //--------------------------------------------
        "shoppingCartPairedItemBreakDown" => array(
            "enabled" => true,
            "itemRecorder" => "_processSelectProductConvertion/selectPaired",
        ),
        "shoppingCartPairedItemBreakDownValidator" => array(
            1 => array(
                "enabled" => true,
            ),
            3 => array(
                "enabled" => true,
            ),
        ),
        "shoppingCartPairedItemBreakDownPartValidator" => array(
            1 => array(
                "enabled" => true,
                "source" => "items",
                "target" => "items4",
            ),
        ),
        //--------------------------------------------
        "pairMakers" => array(
            1 => array(
                "hpp_avg" => array(
                    "helperName" => "he_cek_stock_produk_hpp_avg",
                    "functionName" => "cekStockProdukHppAvg",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                    ),
                ),
            ),
            3 => array(
                "hpp_avg" => array(
                    "helperName" => "he_cek_stock_produk_hpp_avg",
                    "functionName" => "cekStockProdukHppAvg",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "hpp_avg" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "hpp_avg",
                    ),
                ),
            ),
            3 => array(
                "hpp_avg" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "hpp_avg",
                    ),
                ),
            ),
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
//            "hpp" => "hpp",// dimatikan supaya tidak mencari hpp target konversi
        ),

        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "336re",
                "label" => "EDIT product conversion request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "336rrj",
                "label" => "REJECT product conversion request",
            ),
        ),
        //----
        "pairRegistries" => array(
            "main", "items", "items4"
        ),
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
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

    "1339" => array(
        "icon" => "fa fa-cube",
        "label" => "konversi produk potong (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request konversi produk potong",
                "actionLabel" => "simpan konversi produk potong",
                "source" => "",
                "target" => "1339r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "scan qr/barcode konversi produk potong",
                "actionLabel" => "simpan scan qr/barcode",
                "source" => "1339r",
                "target" => "1339sc",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "otorisasi konversi produk potong",
                "actionLabel" => "approve konversi produk potong",
                "source" => "1339sc",
                "target" => "1339",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",//ini menghandle produk dan produk rakitan
        "selectedPrice" => array(
            "enabled" => false,
            "model" => "MdlHargaProduk2",//ini menghandle produk dan produk rakitan
            "label" => array("hpp"),
            "key_label" => array(
                "hpp_nppv" => "harga",
            ),
            "mainSrc" => "hpp_nppv",
        ),
        "lockerCheck" => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
            "produk.kategori_id=.3",// produk sumber konversi berupa non unit
            "produk.sub_kategori_id=.5",// produk sumber konversi berupa sparepart
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "size_nama" => "skala",
//            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
            "kategori_nama",
            "sub_kategori_nama",
        ),

        "selectorProcessor" => "_processSelectProductConvertion/select",
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
//            "jenis_label" => "activity",
            "dtime" => "date",
            "nomer_top" => "request number",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "approval 1 number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
                "print_barcode_pembelian" => array(
                    "label" => "print Serial",
                    "key" => array(
                        "print_barcode_pembelian",
                        "print_barcode_pembelian_2",
                    ),
                ),
            ),
            3 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang2_nama" => "recipient",
                "nomer_top" => "request number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "approval 1 number",
                ),
                "nomer" => "approval 2 number",
                "oleh_nama" => "person",
                "next_pic" => "Next step otorisator",
                "print_label" => "Tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array(
                "print_label" => "nomer",
                "print_barcode_pembelian" => "id",
                "print_barcode_pembelian_2" => "id"),
            3 => array("print_label" => "nomer"),
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",

                "stok" => "tersedia",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
                "sisa_dipakai"=>"sisa potongan",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",

                "stok" => "tersedia",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
                "sisa_dipakai"=>"sisa potongan",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",

                "stok" => "tersedia",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
                "sisa_dipakai"=>"sisa potongan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
//                "keterangan" => "part number",
                "jml" => "qty",
                "satuan_nilai" => "@",
                "size_nama" => "satuan",
                "produk_part_ukuran_nama" => "ukuran",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "barcode" => "barcode",
            "kode" => "kode",
            "size_id" => "size_id",
            "size_nama" => "size_nama",
            "satuan_nilai" => "satuan_nilai",
            "sisa_dipakai"=>"sisa potongan",
            "produk_part_kategori_id" => "produk_part_kategori_id",
            "produk_part_kategori_nama" => "produk_part_kategori_nama",
            "produk_part_jenis_id" => "produk_part_jenis_id",
            "produk_part_ukuran_id" => "produk_part_ukuran_id",
            "produk_part_ukuran_nama" => "produk_part_ukuran_nama",

            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(//                "hpp_avg" => "hpp",
            ),
            2 => array(//                "hpp_avg" => "hpp",
            ),
            3 => array(
//                "hpp_avg" => "hpp",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(//                "hpp_avg" => "hpp",
            ),
            2 => array(//                "hpp_avg" => "hpp",
            ),
            3 => array(//                "hpp_avg" => "hpp",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartPairedItemRecorder" => "recordPairedItemKonversi",// potong-potong...
        "shoppingCartPairedItem" => array(
            "enabled" => false,
            "mdlName" => "MdlProduk2",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array(
                "id<>id",
                "kategori_id=kategori_id",
                "satuan_nilai<satuan_nilai",
                "produk_part_ukuran_id=produk_part_ukuran_id"
            ),
            "targetGateName" => "items2_sum",
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
//                "jml",
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
        "shoppingCartEditableFields2" => array(
            1 => array(
                "jml",
//                "hpp_avg",
            ),
            2 => array(
                "jml",
//                "hpp_avg",
            ),
            3 => array(
//                "jml",
//                "hpp_avg",
            ),
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml",
//            2 => "jml",
//        ),
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
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
            ),
            4 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),

            ),
        ),
        //--------------------------------------------
        "shoppingCartPairedItemBreakDown" => array(
            "enabled" => false,
            "itemRecorder" => "_processSelectProductConvertion/selectPaired",
        ),
        "shoppingCartPairedItemBreakDownValidator" => array(
            1 => array(
                "enabled" => false,
            ),
            3 => array(
                "enabled" => false,
            ),
        ),
        "shoppingCartPairedItemBreakDownPartValidator" => array(
            1 => array(
                "enabled" => false,
                "source" => "items",
                "target" => "items4",
            ),
        ),
        //--------------------------------------------
        "shoppingCartPairedItemPotong" => array(
            "enabled" => true,
            "itemRecorder" => "_processSelectProductConvertion/selectPaired",
            "itemRecorderRemove" => "_processSelectProductConvertion/removePairedItem",
            "selectorRecorder" => "_processSelectProductConvertion/selectPairedItem",

            "mdlName" => "MdlProduk",
            "srcLabel" => array(
                "id" => "nama",
            ),
        ),
        "shoppingCartPairedItemPotongValidator" => array(
            1 => array(
                "enabled" => false,
            ),
            3 => array(
                "enabled" => true,
            ),
        ),
        "shoppingCartPairedItemPotongPartValidator" => array(
            1 => array(
                "enabled" => true,
                "source" => "items",
                "target" => "items4",
            ),
        ),
        //--------------------------------------------
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
//                "hpp_avg" => array(
//                    "helperName" => "he_cek_stock_produk_hpp_avg",
//                    "functionName" => "cekStockProdukHppAvg",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "jenis" => ".produk",
//                    ),
//                ),
            ),
            3 => array(
                "hpp_avg" => array(
                    "helperName" => "he_cek_stock_produk_hpp_avg",
                    "functionName" => "cekStockProdukHppAvg",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
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
                "stokProdukIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "intransit_stok",
                    ),
                ),
//                "hpp_avg" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "hpp_avg",
//                    ),
//                ),
            ),
            3 => array(
                "hpp_avg" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "hpp_avg",
                    ),
                ),
            ),
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
//            "hpp" => "hpp",// dimatikan supaya tidak mencari hpp target konversi
        ),

        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1339re",
                "label" => "EDIT product conversion request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1339rrj",
                "label" => "REJECT product conversion request",
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


);