<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    //  config transfer bahan baku/supplies ke cab produksi
    "3583" => array(
        "icon" => "fa fa-truck",
        "label" => "supplies & raw material distribution",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "distribution request",
                "actionLabel" => "distribute",
                "source" => "",
                "target" => "3583r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                //                "label" => "distribution authorization",
                "label" => "authorization",
                "actionLabel" => "approve distribution",
                "source" => "3583r",
                "target" => "3583",
                "userGroup" => "c_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("jual"),
            "key_label" => array(
                "jual" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockSupplies",
        ),
        "selectorFilters" => array(
            "cabang_id=placeID",
            "gudang_id=gudangID",
//            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
            //            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectSupplies/select",
        //        "itemSwapper" => "_processSelectProductStock/multiSelect",
        "itemSwapper" => "_processSelectSupplies/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
            "gudang",
            "gudang__name",
            "gudang__label",

        ),
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            //            "suppliers_nama" => "vendor",
            "nomer_top" => "Req number",
            "oleh_nama" => "person",
            //            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett" => "total amount",
            //            "trash_4" => "trash 4",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "product name",
                "stok" => "stock",
                "jml" => "jumlah",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama" => "product name",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldsExt" => array(
            1 => array(
                "nama" => "product name",
                "request_jml" => "request",
                "stok_avail" => "avail",
                "jml" => "ready-to-send",
                "stok" => "stock tersisa",
                "outstanding" => "out-standing",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama" => "product name",
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
                //                "jml",
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
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
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
                    "gate" => "items",
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
        "connectTo" => "3585",
        "pairChild" => array(
            "761"
        ),
        "aliasMainTrans" => "763",
        "extended" => "",
        "comPrePurchase" => array(
            1 => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "AutoPurchaseSupplies",
                        "loop" => array(
                            "produk_id" => "jml",
                        ),
                        "static" => array(),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),

        ),
        "tabHistoryFields" => array(
            "produk_id" => array(
                "label" => "By Produk",
                "allowFollowup" => true,
            ),
            "transaksi_id" => array(
                "label" => "By Transaksi",
                "allowFollowup" => false,
            ),
        ),
        "tabRequestCode" => array(
            "masterCode" => "763",
            "stateCode" => "763r",
            "stepNumber" => "1",
            "allowMultiSelect" => true,
        ),
        "previewCtr" => "Create",

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3583re",
                "label" => "EDIT distribution request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3583rrj",
                "label" => "REJECT distribution request",
            ),
        ),
    ),
    "3585" => array(
        "icon" => "fa fa-ship",
        "label" => "raw material stock reception",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "stock initiation",
                "actionLabel" => "init reception",
                "source" => "",
                "target" => "3585r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "stock reception",
                //                "label" => "distribusi",
                "actionLabel" => "receive",
                "source" => "3585r",
                "target" => "3585",
                "userGroup" => "p_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
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

        "selectorProcessor" => "_processSelectSupplies/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "item_fields" => "detil item",
            "oleh_nama" => "person",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "code",
            "nama" => "product",
            "qty" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
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
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "kode",
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
        "revertException" => "true",
        "previewCtr" => "Create",
        "pairRegistries" => array(
            "main", "items"
        ),
    ),
    // config return distribusi by bahan baku
    "2983" => array(
        "icon" => "fa fa-truck",
        "label" => "supplies & raw material return (by item)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "return request",
                "actionLabel" => "request return",
                "source" => "",
                "target" => "2983r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "return authorization",
                //                "label" => "return distribusi",
                "actionLabel" => "approve request",
                "source" => "2983r",
                "target" => "2983",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //            ),
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStockSupplies",
            "jenis" => "supplies",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "cabang_id=placeID",
            "gudang_id=gudangID",
            "state=.active",
            "jumlah>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            //            "id" => "id",
            //            "nama" => "nomer",
            "id" => "produk_id",
            "nama" => "nama",
            //            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            //            "nomer", "dtime",
            "nama",
            "satuan",
            "jumlah",
        ),
        "selectorProcessor" => "_processSelectSupplies/select",
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
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
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
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
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
                //                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "produk_kode",
            "label" => "produk_label",
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
                "jml",
            ),
            2 => array(
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
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pusat/dc",
            "pihakName" => "pusat/dc",
        ),

        "referenceFields" => array(
            "referenceID" => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod" => "pembayaran",
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
                    "gate" => "items",
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
                    "name" => "name",
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
                    "name" => "name",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "allowedMainEdit" => array("1"),
        "connectTo" => "2985",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2983re",
                "label" => "EDIT supplies & raw material return (by item)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2983rrj",
                "label" => "REJECT supplies & raw material return (by item)",
            ),
        ),
    ),
    "2985" => array(
        "icon" => "fa fa-ship",
        "label" => "supplies & raw material reception (stock return)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "stock initiation",
                "actionLabel" => "init reception",
                "source" => "",
                "target" => "2985r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "stock reception",
                //                "label" => "distribusi",
                "actionLabel" => "receive",
                "source" => "2985r",
                "target" => "2985",
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
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
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
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "kode",
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
        "revertException" => "true",
        "previewCtr" => "Create",
    ),

    //
    //  config pemindahan antar gudang di center
    "1587" => array(
        "icon" => "fa fa-truck",
        "label" => "destock (to other warehouse center)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "destock request",
                "actionLabel" => "make destock request",
                "source" => "",
                "target" => "1587r",
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "authorization",
                "actionLabel" => "approve destock request",
                "source" => "1587r",
                "target" => "1587ra",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label" => "destock reception",
                "actionLabel" => "receive destocked items",
                "source" => "1587ra",
                "target" => "1587",
                "userGroup" => "w_gudang",//w_gudang
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2", // MdlProdukRakitan
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
            "gudang_id=gudangID",
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
            "jumlah",
            "satuan",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakAddStaticEntry" => array(
            "id" => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "gudang2_nama" => "warehouse",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "gudang2_nama" => "warehouse",
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
                "nama" => "item name",
                "stok" => "stock",
                "jml" => "qty",
                //            "harga" => "harga",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
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
            1 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            3 => array(//            "harga",
                //            "ppn",
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
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target warehouse",
                //                "mdlName" => "MdlGudangDefault_center",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "name" => "nama",
                ),
                "editPoints" => array(1, 2, 3),
            ),
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
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1587re",
                "label" => "EDIT request pindah gudang",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1587rrj",
                "label" => "REJECT request pindah gudang",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1587rarj",
                "label" => "REJECT otorisasi pindah gudang",
            ),
        ),
    ),
    //
    //config transfer hasil produksi ke pusat
    "3683" => array(
        "icon" => "fa fa-truck",
        "label" => "transfer stok to DC",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "Transfer Request",
                "actionLabel" => "request transfer",
                "source" => "",
                "target" => "3683r",
                "userGroup" => "p_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepared by",
            ),
            2 => array(
                "label" => "Stock Transfer",
                //                "label" => "return distribusi",
                "actionLabel" => "approve request",
                "source" => "3683r",
                "target" => "3683",
                "userGroup" => "p_gudang_spv",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "Approved by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStockProduksi",
        "selectorSrcModel" => "MdlProdukProduksi",
        "selectedPrice" => array(
            "model" => "MdlFifoAverage",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "resetFilter" => true,
            "mdlFilter" => array("jenis=.produk"),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "cabang_id=placeID",
            "gudang_id=gudangID",
            "state=.active",
            "jumlah>.0",
            "produk.jenis=.item_rakitan",
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
            "cabang_nama" => "sender",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "3683r" => "request number",
            // "583r"         => "request number",
            "3685r" => "approval number",
            // "583"          => "approval number",
            //            "585r" => "request number",
            "3685" => "receipt number",
            // "585"          => "receipt number",

            "oleh_nama" => "sender person",
            "next_pic" => "next step otorisator",
        ),
        "shortStatusFields" => array(
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
                "nama" => "Description",
                "produk_kode" => "Part No",
                "stok" => "Stock",
                "jml" => "Qty",
                "satuan" => "UOM",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama" => "Description",
                "produk_kode" => "Part No",
                "jml" => "Qty",
                "satuan" => "UOM",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "price",
                "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "price",
                "harga" => "price",
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
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            "pihakName" => "cabang tujuan",
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //                        "harga" => "price",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "hpp" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                //                "grand_ppn" => "vat",
                //                "tagihan_ui" => "grand total",
            ),
            2 => array(
                "hpp" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                //                "grand_ppn" => "vat",
                //                "tagihan_ui" => "grand total",
            ),
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
                        "jenis" => ".produk rakitan",
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

        "connectTo" => "3685",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3683re",
                "label" => "EDIT Transfer Request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3683rrj",
                "label" => "REJECT Transfer Request",
            ),
        ),
    ),
    "3685" => array(
        "icon" => "fa fa-ship",
        "label" => "stock reception (transfer stok)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "stock reception",
                "actionLabel" => "init reception",
                "source" => "",
                "target" => "3685r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "stock transfer reception ",
                //                "label" => "return distribusi(by product)",
                "actionLabel" => "receive",
                "source" => "3685r",
                "target" => "3685",
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
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "label",
            "keterangan",
            "kode",
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
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "sender person",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
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
                "nama" => "Description",
                "produk_kode" => "Part No",
                //                "stok" => "Stock",
                "jml" => "Qty",
                "satuan" => "UOM",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama" => "Description",
                "produk_kode" => "Part No",
                "jml" => "Qty",
                "satuan" => "UOM",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
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
            1 => false,
            2 => false,
        ),
    ),
);