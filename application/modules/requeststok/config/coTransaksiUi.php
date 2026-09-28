<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion

$config["coTransaksiUi"] = array(
    // config pr (request cabang)
    "761" => array(
        "icon" => "fa fa-quote-left",
        "label" => "supplies request (branch)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "supplies request(branch)",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "761r", // request order
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "authorization(branch)",
                "actionLabel" => "approve supplies request",
                "source" => "761r",
                "target" => "761",
                "userGroup" => "w_gudang",//===ini dilanjutkan di purchasing
                "stateLabel" => "awaiting for process",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "lockerCheckAppr" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockSupplies",
            "jenis" => "supplies",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "satuan" => "satuan",
            //            "jumlah"=>"jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
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
            //            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                //            "suppliers_nama" => "vendor",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                //            "suppliers_nama" => "vendor",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
            3 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                //            "suppliers_nama" => "vendor",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
            4 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                //            "suppliers_nama" => "vendor",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
        ),
        "shortStepHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "761r" => "request number",
            "761" => "approval number",
            //            "585r" => "request number",
            "763" => "receipt number",

            "oleh_nama" => "person",
//            "next_pic" => "next step otorisator",


            "1763r" => "purchase otorisator",
            "763r" => "logistic otorisator",

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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
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
            4 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            //            1 =>array(
            //                "stok" => "Stok",
            //            ),
            //            2 =>array(
            //                "stok" => "Stok",
            //            ),
            //            "harga" => "price",
            //            "ppn" => "VAT",
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
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
            4 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga"=>"harga beli",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "center ID",
            "pihakName" => "center name",
        ),
        "receiptElements" => array(
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang dc",
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
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "pushPurchas" => array(
            2 => true,
        ),
        "connectTo" => "763",
        "extConnectTo" => array("3583r", "461ro"),
        "extConnectToMain" => array("763r", "1763r"),
        "extConnectToPair" => array("1763r" => "461ro", "763r" => "3583r"),
        "allowedMainEdit" => array("1"),
        "aliasMainTrans" => "763",
        "connectToPrePurchase" => "1763",
        "requestCode" => array(
            "masterCode" => "763",
            "stateCode" => "763r",
            "stepNumber" => "1",
            "swapGudang" => array(
                "cabang_id" => "cabang2_ID"
            ),
            "swapCabang" => array(
                "cabang_id" => "cabang2_ID"
            ),
            "allowMultiSelect" => false,
        ),
        "distributionCode" => array(
            "masterCode" => "3583",
            "stateCode" => "3583r",
            "stepNumber" => "1",
            "allowMultiSelect" => false,
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "761re",
                "label" => "EDIT supplies request(branch)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "761rrj",
                "label" => "REJECT supplies request(branch)",
            ),
        ),
    ),
    //config pr (approval dan kirim pusat)
    "763" => array(
        "icon" => "fa fa-truck",
        "label" => "# supplies request (pusat)",
        "place" => "center",
        "hideMenu" => true,
        "steps" => array(
            1 => array(
                "label" => "new supplies request",
                "actionLabel" => "new supplies request",
                "source" => "",
                "target" => "763r",
                "userGroup" => "sys",
                "stateLabel" => "onprocess",
                "stateColor" => "#dd3300",
                "stateCaption" => "approved by",
            ),
            2 => array(
                //                "label" => "supplies distribution",
                "label" => "auth",
                "actionLabel" => "view new supplies request",
                "source" => "763r",
                "target" => "763",
                "userGroup" => "sys",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "hideFollowUp" => true,
                //                "allowEdit" => false,
            ),
        ),
        "showPoStatus" => array(
            "enable" => true
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
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
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
            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),
        "swappedKeys" => array("pihakID", "pihakName"),
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
                "nama" => "product name",
                //                "produk_kode" => "product code",
                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama" => "product name",
                //                "produk_kode" => "product code",
                "stok" => "stock",
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
        "pairChild" => array(
            "3583", "761"
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),
        "pairMakers" => array(
            2 => array(
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
            2 => array(
                "stokSupplies" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
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
        "validationRules" => array(
            "items" => array(
                "target" => "stok",
                "source" => "jml",
            ),
        ),
        "glanceHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt",
            "cabang2_nama" => "branch",
            "oleh_nama" => "person",
        ),
        "allowedMainEdit" => array(0),
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
        "tabFieldsItems" => array(
            "produk_id" => array(
                "select" => "All",
                "dtime" => "tanggal",
                "produk_nama" => "Produk Nama",
                "nomer_top" => "Transaksi No",
                "produk_ord_jml" => "Request(qty) ",
//                "cabang_nama" =>"cabang",
                //                "purchased" => "On Purchase",
                //                "valid_qty" => "Outstanding",

            ),
            "transaksi_id" => array(
                //                "select" => "tic",
                "dtime" => "tanggal",
                "nomer" => "Approval no",
                "nomer_top" => "Supplies Request No",
                "arrProduk" => "Produk",
                "cabang2_nama" => "Cabang",
                "oleh_nama" => "PIC",
                "action" => "Action",
            ),
        ),
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => true,
                "label" => "close/fullfillment request ",
                "targetJenisMaster" => "9763",
                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
                "allowedGroups" => array(
                    "c_holding",
                    "c_gudang",
                    "c_gudang_spv",
                    //                    "c_finance"
                ),
            ),
        ),

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "763re",
                "label" => "EDIT new supplies request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "763rrj",
                "label" => "REJECT new supplies request",
            ),
        ),
    ),
    "9763" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "close/fullfillment Supplies request",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "close/fullfillment request",
                "actionLabel" => "make close/fullfillment",
                "source" => "",
                "target" => "9763",
                "userGroup" => "c_purchasing",
                "stateLabel" => "close/fullfillment request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
                "isCancelPacking" => true,
            ),
//            2 => array(
//                "label" => "otorisasi close/fullfillment purchasing",
//                "actionLabel" => "approve close/fullfillment",
//                "source" => "1961",
//                "target" => "1961a",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "purchasing canceled",
//                "stateColor" => "#ff7700",
//                "isCancelPacking" => true,
//            ),
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
            "jenis=.467",
            "suppliers_id=pihakID",
            "tail_number=.3",
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
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            //            "jenis_label"    => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "request number",
            "transaksiDatas__nomer" => "PO Number",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
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
                //                "produk_kode" => "Product No.",
                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut PO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>Req</span>",
                //                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
//                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH GRN</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>GRN</span>",
//                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
//                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
                "satuan" => "uom",
                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
            ),
            2 => array(
                "nama" => "Description",
                //                "produk_kode" => "Product No.",
                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut PO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>PO</span>",
                //                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH GRN</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>GRN</span>",
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
            1 => "jml*(harga)",
            2 => "jml*(harga)",
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
            1 => false,
            2 => false,
        ),
        "shoppingCartFieldValidators" => array(
            //            "jml"   => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "supplier ID",
            "pihakName" => "supplier name",
        ),
        "applets" => array(),
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
        "referenceJenisTr" => "763",
        "itemAddConfig" => false,
        "receiptElements" => array(
            "transaksiDatas" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Request supplies",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array(
                    "id=currentID",
                ),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "Nomer",
                    "oleh_nama" => "BY",
                ),
                "editPoints" => array(1),
            ),
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "VENDOR DETAILS",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "Name",
//                    "alamat_1" => "Address",
//                    "tlp_1" => "Phone",
//                    "npwp" => "NPWP",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),
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
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        // ======== =========
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => false,
                "label" => "close/fullfillment request order",
                "targetJenisMaster" => "9763",
                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",

                "shipment" => "763", // ini jadi GRN
                "packing" => "none", //
                "cancel" => "9763",
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
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9763e",
                "label" => "EDIT close/fullfillment request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9763rj",
                "label" => "REJECT close/fullfillment request",
            ),
        ),
    ),


);