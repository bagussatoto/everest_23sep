<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    "999" => array(
        "icon" => "fa fa-cube",
        "label" => "adjustment journaling",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "adjustment journaling",
                "actionLabel" => "make adjustment",
                "source" => "",
                "target" => "999",
                "userGroup" => "root",
                "stateLabel" => "done",
                "stateColor" => "#dd3300",
                "stateCaption" => "made by",
            ),
        ),
//        "template" => "template/transaksi_nopihak2.html",
        "template" => "template/transaksi_nopihak4.html",

//        "selectorModel" => "MdlRekeningKredit",
//        "selectorSrcModel" => "MdlRekeningKredit",
        "selectorModel" => "MdlRekeningDebet",
        "selectorSrcModel" => "MdlRekeningDebet",

//        "selectorModel2" => "MdlRekeningKredit",
//        "selectorSrcModel2" => "MdlRekeningKredit",
        "selectorModel2" => "MdlRekeningDebet",
        "selectorSrcModel2" => "MdlRekeningDebet",

//        "selectorModel3" => "MdlRekeningKredit",
//        "selectorSrcModel3" => "MdlRekeningKredit",
//        "selectorModel3" => "MdlRekeningDebet",
//        "selectorSrcModel3" => "MdlRekeningDebet",

//        "selectorModel4" => "MdlRekeningKredit",
//        "selectorSrcModel4" => "MdlRekeningKredit",
//        "selectorModel4" => "MdlRekeningDebet",
//        "selectorSrcModel4" => "MdlRekeningDebet",


        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "bank.cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorCaller2" => "_selectorItem/selectItem2",// bikin shopping cart background
        "selectorCaller3" => "_selectorItem/selectItem3",// bikin shopping cart background
        "selectorCaller4" => "_selectorItem/selectItem4",// bikin shopping cart background
        "selectorLabel" => "from account",
        "selectorLabel2" => "to account",
        "selectorParamFields" => array(
            "id" => "id",
            "name" => "name",
        ),
        "selectorViewedFields" => array(
            "name",
            "defPosition",
        ),
        "selectorProcessor" => "_processSelectRekeningAdjustment/select",
        "selectorProcessor2" => "_processSelectRekeningAdjustment/select2",
        "selectorProcessor3" => "_processSelectRekeningAdjustment/select3",
        "selectorProcessor4" => "_processSelectRekeningAdjustment/select4",
        "editHandlerMethod" => "edit",

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
            //            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "source account",

            ),
            2 => array(
                "nama" => "source account",

            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "target account",

            ),
            2 => array(
                "nama" => "target account",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "name" => "name",
            "nama" => "nama",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "jml" => "(don't change)",
                "debet" => "debet",
                "kredit" => "kredit",
            ),
            2 => array(
                "jml" => "(don't change)",
                "debet" => "debet",
                "kredit" => "kredit",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "harga" => "receiving amount",
                "jml" => "qty",
            ),
            2 => array(
                "harga" => "receiving amount",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartAvoidRemove" => true,

        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "debet",
                "kredit",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "receiptElements" => array(
            "extern1" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "source sub-account",
                "mdlName" => "MdlExtern",
                "mdlFilter" => array("relName=srcRel"),
                "key" => "extern_id",
                "labelSrc" => "extern_id/extern_nama",
                "usedFields" => array(
                    "extern_nama" => "account name",
                ),
                "noValidate" => true,
                "editPoints" => array(1),
            ),

            "extern2" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
//                "inputType" => "hidden",
                "label" => "target sub-account",
                "mdlName" => "MdlExtern",
                "mdlFilter" => array(
                    "relName=targetRel",
                ),
                "key" => "extern_id",
                "labelSrc" => "extern_id/extern_nama",
                "usedFields" => array(
                    "extern_nama" => "account name",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),

            //klo bukan project matikan, jika project harus di nyalakan
//            "extern_project" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
////                "inputType" => "hidden",
//                "label" => "target project",
//                "mdlName" => "MdlProdukProject",
//                "mdlFilter" => array(
//
//                ),
//                "key" => "id",
//                "labelSrc" => "id/nama",
//                "usedFields" => array(
//                    "id" => "id project",
//                    "nama" => "nama project",
//                ),
//                "editPoints" => array(1),
//                "noValidate" => true,
//            ),

//            "extern3" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "target 3 sub-account",
//                "mdlName" => "MdlExtern",
//                "mdlFilter" => array("relName=target3Rel"),
//                "key" => "extern_id",
//                "labelSrc" => "extern_id/extern_nama",
//                "usedFields" => array(
//                    "extern_nama" => "account name",
//                ),
//                "noValidate" => true,
//                "editPoints" => array(1),
//            ),
//
//            "extern4" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "target 4 sub-account",
//                "mdlName" => "MdlExtern",
//                "mdlFilter" => array("relName=target4Rel"),
//                "key" => "extern_id",
//                "labelSrc" => "extern_id/extern_nama",
//                "usedFields" => array(
//                    "extern_nama" => "account name",
//                ),
//                "noValidate" => true,
//                "editPoints" => array(1),
//            ),
        ),
        "previewCtr" => "Create",
    ),

    "999_1" => array(
        "icon" => "fa fa-cube",
        "label" => "adjustment journaling",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "adjustment journaling",
                "actionLabel" => "make adjustment",
                "source" => "",
                "target" => "999",
                "userGroup" => "root",
                "stateLabel" => "done",
                "stateColor" => "#dd3300",
                "stateCaption" => "made by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        //        "selectorModel"    => "MdlRekeningKredit",
        //        "selectorSrcModel" => "MdlRekeningKredit",
        //        "selectorModel" => "MdlRekeningDebet",
        //        "selectorSrcModel" => "MdlRekeningDebet",
        "selectorModel" => "MdlRekeningDebetKredit",
        "selectorSrcModel" => "MdlRekeningDebetKredit",

        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "bank.cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        //        "selectorCaller2"      => "_selectorItem/selectItem2",// bikin shopping cart background
        "selectorLabel" => "from account",
        "selectorLabel2" => "to account",
        "selectorParamFields" => array(
            "id" => "id",
            "name" => "name",
        ),
        "selectorViewedFields" => array(
            "name",
            "defPosition",
        ),
        "selectorProcessor" => "_processSelectRekeningAdjustment/select",
        "selectorProcessor2" => "_processSelectRekeningAdjustment/select2",
        "editHandlerMethod" => "edit",

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
            //            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "source account",

            ),
            2 => array(
                "nama" => "source account",

            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "target account",

            ),
            2 => array(
                "nama" => "target account",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "name" => "name",
            "nama" => "nama",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "jml" => "(don't change)",
                "debet" => "debet",
                "kredit" => "kredit",
            ),
            2 => array(
                "jml" => "(don't change)",
                "debet" => "debet",
                "kredit" => "kredit",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "harga" => "receiving amount",
                "jml" => "qty",
            ),
            2 => array(
                "harga" => "receiving amount",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
                //                "tagihan" => "amount remains to pay",
                //                "ppn" => "vat",
                //                "nett2" => "total",
            ),
        ),
        //        "shoppingCartPairedItem"  => array(
        //            "enabled"   => true,
        //            "mdlName"   => "MdlBankAccount",
        //            "mdlFilter" => array(
        //                "cabang_id=placeID"
        //            ),
        //            "srcKey"    => "id",
        //            "srcLabel"  => array("nama"),
        //            "mdlFilter" => array("id=id"),
        //        ),

        //        "shoppingCartPairedSelectedItem" => array(
        //            "enabled" => true,
        //            "mdlName" => "ComRekeningPembantuKas",
        //            "srcKey" => "extern_id",
        //            "srcLabel" => array("nama"),
        //            "mdlFilter" => array(
        //                "cabang_id=placeID",
        //                "periode=forever",
        //                "rekening=kas",
        //                ),
        //        ),

        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "debet",
                "kredit",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "receiptElements" => array(
            "extern1" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "source sub-account",
                "mdlName" => "MdlExtern",
                "mdlFilter" => array("relName=srcRel"),
                "key" => "extern_id",
                "labelSrc" => "extern_id/extern_nama",
                "usedFields" => array(
                    "extern_nama" => "account name",
                ),
                "editPoints" => array(1),
            ),

            //            "extern2" => array(
            //                "elementType" => "dataModel",
            //                "inputType"   => "radio",
            //                "label"       => "target sub-account",
            //                "mdlName"     => "MdlExtern",
            //                "mdlFilter"   => array("relName=targetRel"),
            //                "key"         => "extern_id",
            //                "labelSrc"    => "extern_nama",
            //                "usedFields"  => array(
            //                    "extern_nama" => "account name",
            //                ),
            //                "editPoints"  => array(1),
            //            ),
        ),

        "previewCtr" => "Create",

    ),

    //    "999_0" => array(
    //        "icon"     => "fa fa-cube",
    //        "label"    => "adjustment journaling",
    //        "place"    => "center",//=> "center",
    //        "steps"    => array(
    //            1 => array(
    //                "label"        => "adjustment journaling",
    //                "actionLabel"  => "make adjustment",
    //                "source"       => "",
    //                "target"       => "999_0",
    //                "userGroup"    => "root",
    //                "stateLabel"   => "done",
    //                "stateColor"   => "#dd3300",
    //                "stateCaption" => "made by",
    //            ),
    //        ),
    //        "template" => "application/template/transaksi_nopihak.html",
    //
    //        "selectorModel"    => "MdlRekeningDebetKredit",
    //        "selectorSrcModel" => "MdlRekeningDebetKredit",
    //
    //        "selectedPrice"        => array(),
    //        "lockerCheck"          => array(),
    //        "selectorFilters"      => array(//            "bank.cabang_id=placeID",
    //        ),
    //        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
    //        //        "selectorCaller2"      => "Selectors/_selectorItem/selectItem2",// bikin shopping cart background
    //        "selectorLabel"        => "from account",
    //        "selectorLabel2"       => "to account",
    //        "selectorParamFields"  => array(
    //            "id"   => "id",
    //            "name" => "name",
    //        ),
    //        "selectorViewedFields" => array(
    //            "name",
    //            "defPosition",
    //        ),
    //        "selectorProcessor"    => "Selectors/_processSelectRekeningAdjustment/select",
    //        "selectorProcessor2"   => "Selectors/_processSelectRekeningAdjustment/select2",
    //        "editHandlerMethod"    => "edit",
    //
    //        "pihakModel"     => "MdlGudang",
    //        "pihakCaller"    => "Selectors/_selectorPihak/selectPihak",
    //        "pihakLabel"     => "gudang",
    //        "pihakFilters"   => array(
    //            "cabang_id=cabang_id",
    //            "id<>gudang_id",
    //        ),
    //        "pihakProcessor" => "Selectors/_processPihak/select",
    //
    //        "shortHistoryFields" => array(
    //            "jenis_label" => "activity",
    //            "dtime"       => "date",
    //            //            "cabang2_nama" => "recipient",
    //            "nomer"       => "receipt number",
    //            "oleh_nama"   => "person",
    //        ),
    //        "selectorFields"     => array("id", "nama"),
    //        "pihakFields"        => array("id", "nama"),
    //        "shoppingCart"       => array(
    //            "initPrices" => "beli",
    //        ),
    //
    //        "shoppingCartFields"      => array(
    //            1 => array(
    //                "nama" => "source account",
    //
    //            ),
    //            2 => array(
    //                "nama" => "source account",
    //
    //            ),
    //        ),
    //        "shoppingCartFields2"     => array(
    //            1 => array(
    //                "nama" => "target account",
    //
    //            ),
    //            2 => array(
    //                "nama" => "target account",
    //
    //            ),
    //        ),
    //        "shoppingCartFieldSrc"    => array(
    //            "name" => "name",
    //            "nama" => "nama",
    //
    //        ),
    //        "shoppingCartNumFields"   => array(
    //            1 => array(
    //                "jml"    => "(don't change)",
    //                "debet"  => "debet",
    //                "kredit" => "kredit",
    //            ),
    //            2 => array(
    //                "jml"    => "(don't change)",
    //                "debet"  => "debet",
    //                "kredit" => "kredit",
    //            ),
    //        ),
    //        "shoppingCartNumFields2"  => array(
    //            1 => array(
    //                "harga" => "receiving amount",
    //                "jml"   => "qty",
    //            ),
    //            2 => array(
    //                "harga" => "receiving amount",
    //                "jml"   => "qty",
    //            ),
    //        ),
    //        "shoppingCartNoteEnabled" => false,
    //        "shoppingCartAvoidRemove" => false,
    //        "shoppingCartSumFields"   => array(
    //            1 => array(
    //                //                "tagihan" => "amount remains to pay",
    //                //                "ppn" => "vat",
    //                //                "nett2" => "total",
    //            ),
    //        ),
    //        //        "shoppingCartPairedItem"  => array(
    //        //            "enabled"   => true,
    //        //            "mdlName"   => "MdlBankAccount",
    //        //            "mdlFilter" => array(
    //        //                "cabang_id=placeID"
    //        //            ),
    //        //            "srcKey"    => "id",
    //        //            "srcLabel"  => array("nama"),
    //        //            "mdlFilter" => array("id=id"),
    //        //        ),
    //
    //        //        "shoppingCartPairedSelectedItem" => array(
    //        //            "enabled" => true,
    //        //            "mdlName" => "ComRekeningPembantuKas",
    //        //            "srcKey" => "extern_id",
    //        //            "srcLabel" => array("nama"),
    //        //            "mdlFilter" => array(
    //        //                "cabang_id=placeID",
    //        //                "periode=forever",
    //        //                "rekening=kas",
    //        //                ),
    //        //        ),
    //
    //        "shoppingCartEditableFields" => array(
    //            1 => array(
    //                "jml",
    //                "debet",
    //                "kredit",
    //            ),
    //        ),
    //        "shoppingCartAmountValue"    => array(
    //            1 => "jml*harga",
    //            2 => "jml*harga",
    //        ),
    //        "receiptElements"            => array(
    //            //            "extern1" => array(
    //            //                "elementType" => "dataModel",
    //            //                "inputType" => "radio",
    //            //                "label" => "source sub-account",
    //            //                "mdlName" => "MdlExtern",
    //            //                "mdlFilter" => array("relName=srcRel"),
    //            //                "key" => "extern_id",
    //            //                "labelSrc" => "extern_nama",
    //            //                "usedFields" => array(
    //            //                    "extern_nama" => "account name",
    //            //                ),
    //            //                "editPoints" => array(1),
    //            //            ),
    //
    //            //            "extern2" => array(
    //            //                "elementType" => "dataModel",
    //            //                "inputType"   => "radio",
    //            //                "label"       => "target sub-account",
    //            //                "mdlName"     => "MdlExtern",
    //            //                "mdlFilter"   => array("relName=targetRel"),
    //            //                "key"         => "extern_id",
    //            //                "labelSrc"    => "extern_nama",
    //            //                "usedFields"  => array(
    //            //                    "extern_nama" => "account name",
    //            //                ),
    //            //                "editPoints"  => array(1),
    //            //            ),
    //        ),
    //
    //
    //    ),
    //
    //    //  config penyesuaian piutang, hutang, kas. pokoknya selain produk
    //    "888_1" => array(
    //        "icon" => "fa fa-cube",
    //        "label" => "penyesuaian non produk (tambah)",
    //        "place" => "center",//=> "center",
    //        "steps" => array(
    //            1 => array(
    //                "label" => "penyesuaian non produk (tambah)",
    //                "actionLabel" => "adjust",
    //                "source" => "",
    //                "target" => "888_1",
    //                "userGroup" => "root",
    //                "stateLabel" => "done",
    //                "stateColor" => "#dd3300",
    //                "stateCaption" => "made by",
    //            ),
    //        ),
    //        "template" => "application/template/transaksi.html",
    //        "selectorModel" => "MdlPembantu",
    //        "selectorSrcModel" => "MdlPembantu",
    //        "selectedPrice" => array(),
    //        "lockerCheck" => array(),
    //        "selectorFilters" => array(
    //            //            "cabang_id=placeID",
    //            //            "rekening=pihakID",
    //        ),
    //        "selectorCaller" => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
    //        "selectorLabel" => "from account",
    //        "selectorParamFields" => array(
    //            "id" => "id",
    //            "name" => "name",
    //        ),
    //        "selectorViewedFields" => array(
    //            "name",
    //        ),
    //        "selectorProcessor" => "Selectors/_processSelectRekeningImporter/select",
    //        "editHandlerMethod" => "edit",
    //
    //        "pihakModel" => "MdlRekeningDebetKredit",
    //        "pihakName" => true,
    //        //        "pihakMainNota" => true,
    //        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
    //        "pihakLabel" => "rekening name",
    //        "pihakMainValueSrc" => array(
    //            "defPosition" => "defPosition",
    //        ),
    //        "pihakFilters" => array(
    //            "cabang_id=cabang_id",
    //            "id<>gudang_id",
    //        ),
    //        "pihakProcessor" => "Selectors/_processPihak/select",
    //
    //        "shortHistoryFields" => array(
    //            "jenis_label" => "activity",
    //            "dtime" => "date",
    //            "nomer" => "receipt number",
    //            "oleh_nama" => "person",
    //        ),
    //        "selectorFields" => array("id", "name"),
    //        "pihakFields" => array("id", "name"),
    //        "shoppingCart" => array(
    //            "initPrices" => "beli",
    //        ),
    //
    //        "shoppingCartFields" => array(
    //            1 => array(
    //                "nama" => "source account",
    //
    //            ),
    //            2 => array(
    //                "nama" => "source account",
    //
    //            ),
    //        ),
    //        //        "shoppingCartFields2" => array(
    //        //            1 => array(
    //        //                "nama" => "target account",
    //        //
    //        //            ),
    //        //            2 => array(
    //        //                "nama" => "target account",
    //        //
    //        //            ),
    //        //        ),
    //        "shoppingCartFieldSrc" => array(
    //            "name" => "name",
    //            "nama" => "nama",
    //        ),
    //        "shoppingCartNumFields" => array(
    //            1 => array(
    //                "jml" => "(don't change)",
    //                //                "debet" => "debet",
    //                //                "kredit" => "kredit",
    //                "harga" => "harga",
    //            ),
    //            //            2 => array(
    //            //                "jml" => "(don't change)",
    //            ////                "debet" => "debet",
    //            ////                "kredit" => "kredit",
    //            //                "harga" => "harga",
    //            //            ),
    //        ),
    //        //        "shoppingCartNumFields2" => array(
    //        //            1 => array(
    //        //                "harga" => "receiving amount",
    //        //                "jml" => "qty",
    //        //            ),
    //        //            2 => array(
    //        //                "harga" => "receiving amount",
    //        //                "jml" => "qty",
    //        //            ),
    //        //        ),
    //        "shoppingCartNoteEnabled" => false,
    //        "shoppingCartAvoidRemove" => false,
    //        "shoppingCartSumFields" => array(
    //            1 => array(
    //                "harga" => "total",
    //                //                "ppn" => "vat",
    //                //                "nett2" => "total",
    //            ),
    //        ),
    //        //        "shoppingCartPairedItem"  => array(
    //        //            "enabled"   => true,
    //        //            "mdlName"   => "MdlBankAccount",
    //        //            "mdlFilter" => array(
    //        //                "cabang_id=placeID"
    //        //            ),
    //        //            "srcKey"    => "id",
    //        //            "srcLabel"  => array("nama"),
    //        //            "mdlFilter" => array("id=id"),
    //        //        ),
    //
    //        //        "shoppingCartPairedSelectedItem" => array(
    //        //            "enabled" => true,
    //        //            "mdlName" => "ComRekeningPembantuKas",
    //        //            "srcKey" => "extern_id",
    //        //            "srcLabel" => array("nama"),
    //        //            "mdlFilter" => array(
    //        //                "cabang_id=placeID",
    //        //                "periode=forever",
    //        //                "rekening=kas",
    //        //                ),
    //        //        ),
    //
    //        "shoppingCartEditableFields" => array(
    //            1 => array(
    //                "jml",
    //                "debet",
    //                "kredit",
    //                "harga",
    //            ),
    //        ),
    //        "shoppingCartAmountValue" => array(
    //            1 => "jml*harga",
    //            2 => "jml*harga",
    //        ),
    //        "receiptElements" => array(
    //            //            "position" => array(
    //            //                "elementType" => "dataModel",
    //            //                "inputType" => "radio",
    //            //                "label" => "account position",
    //            //                "mdlName" => "MdlPosition",
    //            ////                "mdlName" => "MdlRekeningDebetKredit",
    //            ////                "mdlFilter" => array("relName=srcRel"),
    //            //                "key" => "id",
    //            //                "labelSrc" => "name",
    //            //                "usedFields" => array(
    //            //                    "name" => "position",
    //            //                ),
    //            //                "editPoints" => array(1),
    //            //            ),
    //        ),
    //    ),
    //    "888_2" => array(
    //        "icon" => "fa fa-cube",
    //        "label" => "penyesuaian non produk (kurang)",
    //        "place" => "branch",//=> "center",
    //        "steps" => array(
    //            1 => array(
    //                "label" => "penyesuaian non produk (kurang)",
    //                "actionLabel" => "adjust",
    //                "source" => "",
    //                "target" => "888_2",
    //                "userGroup" => "root",
    //                "stateLabel" => "done",
    //                "stateColor" => "#dd3300",
    //                "stateCaption" => "made by",
    //            ),
    //        ),
    //        "template" => "application/template/transaksi.html",
    //        "selectorModel" => "MdlPembantu",
    //        "selectorSrcModel" => "MdlPembantu",
    //        "selectedPrice" => array(),
    //        "lockerCheck" => array(),
    //        "selectorFilters" => array(
    //            //            "cabang_id=placeID",
    //            //            "rekening=pihakID",
    //        ),
    //        "selectorCaller" => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
    //        "selectorLabel" => "from account",
    //        "selectorParamFields" => array(
    //            "id" => "id",
    //            "name" => "name",
    //        ),
    //        "selectorViewedFields" => array(
    //            "name",
    //        ),
    //        "selectorProcessor" => "Selectors/_processSelectRekeningImporter/select",
    //        "editHandlerMethod" => "edit",
    //
    //        "pihakModel" => "MdlRekeningDebetKredit",
    //        "pihakName" => true,
    //        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
    //        "pihakLabel" => "rekening name",
    //        "pihakMainValueSrc" => array(
    //            "defPosition" => "defPosition",
    //        ),
    //        "pihakFilters" => array(
    //            "cabang_id=cabang_id",
    //            "id<>gudang_id",
    //        ),
    //        "pihakProcessor" => "Selectors/_processPihak/select",
    //
    //        "shortHistoryFields" => array(
    //            "jenis_label" => "activity",
    //            "dtime" => "date",
    //            "nomer" => "receipt number",
    //            "oleh_nama" => "person",
    //        ),
    //        "selectorFields" => array("id", "nama"),
    //        "pihakFields" => array("id", "nama"),
    //        "shoppingCart" => array(
    //            "initPrices" => "beli",
    //        ),
    //
    //        "shoppingCartFields" => array(
    //            1 => array(
    //                "nama" => "source account",
    //
    //            ),
    //            2 => array(
    //                "nama" => "source account",
    //
    //            ),
    //        ),
    //        //        "shoppingCartFields2" => array(
    //        //            1 => array(
    //        //                "nama" => "target account",
    //        //
    //        //            ),
    //        //            2 => array(
    //        //                "nama" => "target account",
    //        //
    //        //            ),
    //        //        ),
    //        "shoppingCartFieldSrc" => array(
    //            "name" => "name",
    //            "nama" => "nama",
    //        ),
    //        "shoppingCartNumFields" => array(
    //            1 => array(
    //                "jml" => "(don't change)",
    //                //                "debet" => "debet",
    //                //                "kredit" => "kredit",
    //                "harga" => "harga",
    //            ),
    //            //            2 => array(
    //            //                "jml" => "(don't change)",
    //            ////                "debet" => "debet",
    //            ////                "kredit" => "kredit",
    //            //                "harga" => "harga",
    //            //            ),
    //        ),
    //        //        "shoppingCartNumFields2" => array(
    //        //            1 => array(
    //        //                "harga" => "receiving amount",
    //        //                "jml" => "qty",
    //        //            ),
    //        //            2 => array(
    //        //                "harga" => "receiving amount",
    //        //                "jml" => "qty",
    //        //            ),
    //        //        ),
    //        "shoppingCartNoteEnabled" => false,
    //        "shoppingCartAvoidRemove" => false,
    //        "shoppingCartSumFields" => array(
    //            1 => array(
    //                //                "tagihan" => "amount remains to pay",
    //                //                "ppn" => "vat",
    //                //                "nett2" => "total",
    //            ),
    //        ),
    //        //        "shoppingCartPairedItem"  => array(
    //        //            "enabled"   => true,
    //        //            "mdlName"   => "MdlBankAccount",
    //        //            "mdlFilter" => array(
    //        //                "cabang_id=placeID"
    //        //            ),
    //        //            "srcKey"    => "id",
    //        //            "srcLabel"  => array("nama"),
    //        //            "mdlFilter" => array("id=id"),
    //        //        ),
    //
    //        //        "shoppingCartPairedSelectedItem" => array(
    //        //            "enabled" => true,
    //        //            "mdlName" => "ComRekeningPembantuKas",
    //        //            "srcKey" => "extern_id",
    //        //            "srcLabel" => array("nama"),
    //        //            "mdlFilter" => array(
    //        //                "cabang_id=placeID",
    //        //                "periode=forever",
    //        //                "rekening=kas",
    //        //                ),
    //        //        ),
    //
    //        "shoppingCartEditableFields" => array(
    //            1 => array(
    //                "jml",
    //                "debet",
    //                "kredit",
    //                "harga",
    //            ),
    //        ),
    //        "shoppingCartAmountValue" => array(
    //            1 => "jml*harga",
    //            2 => "jml*harga",
    //        ),
    //        "receiptElements" => array(
    //            //            "position" => array(
    //            //                "elementType" => "dataModel",
    //            //                "inputType" => "radio",
    //            //                "label" => "account position",
    //            //                "mdlName" => "MdlPosition",
    //            ////                "mdlName" => "MdlRekeningDebetKredit",
    //            ////                "mdlFilter" => array("relName=srcRel"),
    //            //                "key" => "id",
    //            //                "labelSrc" => "name",
    //            //                "usedFields" => array(
    //            //                    "name" => "position",
    //            //                ),
    //            //                "editPoints" => array(1),
    //            //            ),
    //        ),
    //    ),
    //    //  config penyesuaian produk. pokoknya selain piutang, hutang, kas

    "777_1" => array(
        "icon" => "fa fa-cube",
        "label" => "adjustment product inventory (tambah)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "adjustment product inventory (tambah)",
                "actionLabel" => "adjust",
                "source" => "",
                "target" => "777_1",
                "userGroup" => "root",
                "stateLabel" => "done",
                "stateColor" => "#dd3300",
                "stateCaption" => "made by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlPembantu",
        "selectorSrcModel" => "MdlPembantu",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id=placeID",
            //            "rekening=pihakID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "from account",
        "selectorParamFields" => array(
            "id" => "id",
            "name" => "name",
        ),
        "selectorViewedFields" => array(
            "name",
        ),
        "selectorProcessor" => "_processSelectRekeningImporter/select",
        "editHandlerMethod" => "edit",

        "pihakModel" => "MdlRekeningDebetKredit",
        "pihakName" => true,
        //        "pihakMainNota" => true,
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "rekening name",
        "pihakMainValueSrc" => array(
            "defPosition" => "defPosition",
        ),
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "source account",

            ),
            2 => array(
                "nama" => "source account",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "name" => "name",
            "nama" => "nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "jml" => "(don't change)",
                //                "debet" => "debet",
                //                "kredit" => "kredit",
                "harga" => "harga",
            ),
            //            2 => array(
            //                "jml" => "(don't change)",
            ////                "debet" => "debet",
            ////                "kredit" => "kredit",
            //                "harga" => "harga",
            //            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
                //                "tagihan" => "amount remains to pay",
                //                "ppn" => "vat",
                //                "nett2" => "total",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "debet",
                "kredit",
                "harga",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "receiptElements" => array(
            "opAccount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "opposite account",
                "mdlName" => "MdlRekeningDebetKredit",
                "mdlFilter" => array(
                    "name<>pihakName",
                ),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
        ),
        "previewCtr" => "Create",
    ),
    "7778" => array(
        "icon" => "fa fa-cube",
        "label" => "adjustment product inventory (tambah)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "adjustment product inventory (tambah)",
                "actionLabel" => "adjust",
                "source" => "",
                "target" => "7778",
                "userGroup" => "root",
                "stateLabel" => "done",
                "stateColor" => "#dd3300",
                "stateCaption" => "made by",
            ),
        ),

    ),

    //    "777_2" => array(
    //        "icon" => "fa fa-cube",
    //        "label" => "adjustment for produk (kurang)",
    //        "place" => "branch",//=> "center",
    //        "steps" => array(
    //            1 => array(
    //                "label" => "adjustment for produk (kurang)",
    //                "actionLabel" => "adjust",
    //                "source" => "",
    //                "target" => "777_2",
    //                "userGroup" => "root",
    //                "stateLabel" => "done",
    //                "stateColor" => "#dd3300",
    //                "stateCaption" => "made by",
    //            ),
    //        ),
    //        "template" => "application/template/transaksi.html",
    //        "selectorModel" => "MdlPembantu",
    //        "selectorSrcModel" => "MdlPembantu",
    //        "selectedPrice" => array(),
    //        "lockerCheck" => array(),
    //        "selectorFilters" => array(
    ////            "cabang_id=placeID",
    ////            "rekening=pihakID",
    //        ),
    //        "selectorCaller" => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
    //        "selectorLabel" => "from account",
    //        "selectorParamFields" => array(
    //            "id" => "id",
    //            "name" => "name",
    //        ),
    //        "selectorViewedFields" => array(
    //            "name",
    //        ),
    //        "selectorProcessor" => "Selectors/_processSelectRekeningImporter/select",
    //        "editHandlerMethod" => "edit",
    //
    //        "pihakModel" => "MdlRekeningDebetKredit",
    //        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
    //        "pihakLabel" => "rekening name",
    //        "pihakMainValueSrc" => array(
    //            "defPosition" => "defPosition",
    //        ),
    //        "pihakFilters" => array(
    //            "cabang_id=cabang_id",
    //            "id<>gudang_id",
    //        ),
    //        "pihakProcessor" => "Selectors/_processPihak/select",
    //
    //        "shortHistoryFields" => array(
    //            "jenis_label" => "activity",
    //            "dtime" => "date",
    //            "nomer" => "receipt number",
    //            "oleh_nama" => "person",
    //        ),
    //        "selectorFields" => array("id", "nama"),
    //        "pihakFields" => array("id", "nama"),
    //        "shoppingCart" => array(
    //            "initPrices" => "beli",
    //        ),
    //
    //        "shoppingCartFields" => array(
    //            1 => array(
    //                "nama" => "source account",
    //
    //            ),
    //            2 => array(
    //                "nama" => "source account",
    //
    //            ),
    //        ),
    ////        "shoppingCartFields2" => array(
    ////            1 => array(
    ////                "nama" => "target account",
    ////
    ////            ),
    ////            2 => array(
    ////                "nama" => "target account",
    ////
    ////            ),
    ////        ),
    //        "shoppingCartFieldSrc" => array(
    //            "name" => "name",
    //            "nama" => "nama",
    //        ),
    //        "shoppingCartNumFields" => array(
    //            1 => array(
    //                "jml" => "(don't change)",
    ////                "debet" => "debet",
    ////                "kredit" => "kredit",
    //                "harga" => "harga",
    //            ),
    ////            2 => array(
    ////                "jml" => "(don't change)",
    //////                "debet" => "debet",
    //////                "kredit" => "kredit",
    ////                "harga" => "harga",
    ////            ),
    //        ),
    ////        "shoppingCartNumFields2" => array(
    ////            1 => array(
    ////                "harga" => "receiving amount",
    ////                "jml" => "qty",
    ////            ),
    ////            2 => array(
    ////                "harga" => "receiving amount",
    ////                "jml" => "qty",
    ////            ),
    ////        ),
    //        "shoppingCartNoteEnabled" => false,
    //        "shoppingCartAvoidRemove" => false,
    //        "shoppingCartSumFields" => array(
    //            1 => array(
    ////                "tagihan" => "amount remains to pay",
    ////                "ppn" => "vat",
    ////                "nett2" => "total",
    //            ),
    //        ),
    //        //        "shoppingCartPairedItem"  => array(
    //        //            "enabled"   => true,
    //        //            "mdlName"   => "MdlBankAccount",
    //        //            "mdlFilter" => array(
    //        //                "cabang_id=placeID"
    //        //            ),
    //        //            "srcKey"    => "id",
    //        //            "srcLabel"  => array("nama"),
    //        //            "mdlFilter" => array("id=id"),
    //        //        ),
    //
    //        //        "shoppingCartPairedSelectedItem" => array(
    //        //            "enabled" => true,
    //        //            "mdlName" => "ComRekeningPembantuKas",
    //        //            "srcKey" => "extern_id",
    //        //            "srcLabel" => array("nama"),
    //        //            "mdlFilter" => array(
    //        //                "cabang_id=placeID",
    //        //                "periode=forever",
    //        //                "rekening=kas",
    //        //                ),
    //        //        ),
    //
    //        "shoppingCartEditableFields" => array(
    //            1 => array(
    //                "jml",
    //                "debet",
    //                "kredit",
    //                "harga",
    //            ),
    //        ),
    //        "shoppingCartAmountValue" => array(
    //            1 => "jml*harga",
    //            2 => "jml*harga",
    //        ),
    //        "receiptElements" => array(
    //            "opAccount" => array(
    //                "elementType" => "dataModel",
    //                "inputType" => "radio",
    //                "label" => "opposite account",
    //                "mdlName" => "MdlRekeningDebetKredit",
    //                "mdlFilter" => array(
    //                    "name<>pihakName",
    //                ),
    //                "key" => "id",
    //                "labelSrc" => "name",
    //                "usedFields" => array(
    //                    "name" => "name",
    //                ),
    //                "editPoints" => array(1, 2, 3, 4),
    //            ),
    //        ),
    //    ),
    //    //  config penyesuaian supplies. pokoknya selain piutang, hutang, kas
    //    "666_1" => array(
    //        "icon" => "fa fa-cube",
    //        "label" => "adjustment for supplies (tambah)",
    //        "place" => "branch",//=> "center",
    //        "steps" => array(
    //            1 => array(
    //                "label" => "adjustment for supplies (tambah)",
    //                "actionLabel" => "adjust",
    //                "source" => "",
    //                "target" => "666_1",
    //                "userGroup" => "root",
    //                "stateLabel" => "done",
    //                "stateColor" => "#dd3300",
    //                "stateCaption" => "made by",
    //            ),
    //        ),
    //        "template" => "application/template/transaksi.html",
    //        "selectorModel" => "MdlPembantu",
    //        "selectorSrcModel" => "MdlPembantu",
    //        "selectedPrice" => array(),
    //        "lockerCheck" => array(),
    //        "selectorFilters" => array(
    ////            "cabang_id=placeID",
    ////            "rekening=pihakID",
    //        ),
    //        "selectorCaller" => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
    //        "selectorLabel" => "from account",
    //        "selectorParamFields" => array(
    //            "id" => "id",
    //            "name" => "name",
    //        ),
    //        "selectorViewedFields" => array(
    //            "name",
    //        ),
    //        "selectorProcessor" => "Selectors/_processSelectRekeningImporter/select",
    //        "editHandlerMethod" => "edit",
    //
    //        "pihakModel" => "MdlRekeningDebetKredit",
    //        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
    //        "pihakLabel" => "rekening name",
    //        "pihakMainValueSrc" => array(
    //            "defPosition" => "defPosition",
    //        ),
    //        "pihakFilters" => array(
    //            "cabang_id=cabang_id",
    //            "id<>gudang_id",
    //        ),
    //        "pihakProcessor" => "Selectors/_processPihak/select",
    //
    //        "shortHistoryFields" => array(
    //            "jenis_label" => "activity",
    //            "dtime" => "date",
    //            "nomer" => "receipt number",
    //            "oleh_nama" => "person",
    //        ),
    //        "selectorFields" => array("id", "nama"),
    //        "pihakFields" => array("id", "nama"),
    //        "shoppingCart" => array(
    //            "initPrices" => "beli",
    //        ),
    //
    //        "shoppingCartFields" => array(
    //            1 => array(
    //                "nama" => "source account",
    //
    //            ),
    //            2 => array(
    //                "nama" => "source account",
    //
    //            ),
    //        ),
    //        "shoppingCartFieldSrc" => array(
    //            "name" => "name",
    //            "nama" => "nama",
    //        ),
    //        "shoppingCartNumFields" => array(
    //            1 => array(
    //                "jml" => "(don't change)",
    ////                "debet" => "debet",
    ////                "kredit" => "kredit",
    //                "harga" => "harga",
    //            ),
    ////            2 => array(
    ////                "jml" => "(don't change)",
    //////                "debet" => "debet",
    //////                "kredit" => "kredit",
    ////                "harga" => "harga",
    ////            ),
    //        ),
    //        "shoppingCartNoteEnabled" => false,
    //        "shoppingCartAvoidRemove" => false,
    //        "shoppingCartSumFields" => array(
    //            1 => array(
    ////                "tagihan" => "amount remains to pay",
    ////                "ppn" => "vat",
    ////                "nett2" => "total",
    //            ),
    //        ),
    //        "shoppingCartEditableFields" => array(
    //            1 => array(
    //                "jml",
    //                "debet",
    //                "kredit",
    //                "harga",
    //            ),
    //        ),
    //        "shoppingCartAmountValue" => array(
    //            1 => "jml*harga",
    //            2 => "jml*harga",
    //        ),
    //        "receiptElements" => array(
    //            "opAccount" => array(
    //                "elementType" => "dataModel",
    //                "inputType" => "radio",
    //                "label" => "opposite account",
    //                "mdlName" => "MdlRekeningDebetKredit",
    //                "mdlFilter" => array(
    //                    "name<>pihakName",
    //                ),
    //                "key" => "id",
    //                "labelSrc" => "name",
    //                "usedFields" => array(
    //                    "name" => "name",
    //                ),
    //                "editPoints" => array(1, 2, 3, 4),
    //            ),
    //        ),
    //    ),
    //    "666_2" => array(
    //        "icon" => "fa fa-cube",
    //        "label" => "adjustment for supplies (kurang)",
    //        "place" => "branch",//=> "center",
    //        "steps" => array(
    //            1 => array(
    //                "label" => "adjustment for supplies (kurang)",
    //                "actionLabel" => "adjust",
    //                "source" => "",
    //                "target" => "666_2",
    //                "userGroup" => "root",
    //                "stateLabel" => "done",
    //                "stateColor" => "#dd3300",
    //                "stateCaption" => "made by",
    //            ),
    //        ),
    //        "template" => "application/template/transaksi.html",
    //        "selectorModel" => "MdlPembantu",
    //        "selectorSrcModel" => "MdlPembantu",
    //        "selectedPrice" => array(),
    //        "lockerCheck" => array(),
    //        "selectorFilters" => array(
    ////            "cabang_id=placeID",
    ////            "rekening=pihakID",
    //        ),
    //        "selectorCaller" => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
    //        "selectorLabel" => "from account",
    //        "selectorParamFields" => array(
    //            "id" => "id",
    //            "name" => "name",
    //        ),
    //        "selectorViewedFields" => array(
    //            "name",
    //        ),
    //        "selectorProcessor" => "Selectors/_processSelectRekeningImporter/select",
    //        "editHandlerMethod" => "edit",
    //
    //        "pihakModel" => "MdlRekeningDebetKredit",
    //        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
    //        "pihakLabel" => "rekening name",
    //        "pihakMainValueSrc" => array(
    //            "defPosition" => "defPosition",
    //        ),
    //        "pihakFilters" => array(
    //            "cabang_id=cabang_id",
    //            "id<>gudang_id",
    //        ),
    //        "pihakProcessor" => "Selectors/_processPihak/select",
    //
    //        "shortHistoryFields" => array(
    //            "jenis_label" => "activity",
    //            "dtime" => "date",
    //            "nomer" => "receipt number",
    //            "oleh_nama" => "person",
    //        ),
    //        "selectorFields" => array("id", "nama"),
    //        "pihakFields" => array("id", "nama"),
    //        "shoppingCart" => array(
    //            "initPrices" => "beli",
    //        ),
    //
    //        "shoppingCartFields" => array(
    //            1 => array(
    //                "nama" => "source account",
    //
    //            ),
    //            2 => array(
    //                "nama" => "source account",
    //
    //            ),
    //        ),
    //        "shoppingCartFieldSrc" => array(
    //            "name" => "name",
    //            "nama" => "nama",
    //        ),
    //        "shoppingCartNumFields" => array(
    //            1 => array(
    //                "jml" => "(don't change)",
    ////                "debet" => "debet",
    ////                "kredit" => "kredit",
    //                "harga" => "harga",
    //            ),
    ////            2 => array(
    ////                "jml" => "(don't change)",
    //////                "debet" => "debet",
    //////                "kredit" => "kredit",
    ////                "harga" => "harga",
    ////            ),
    //        ),
    //        "shoppingCartNoteEnabled" => false,
    //        "shoppingCartAvoidRemove" => false,
    //        "shoppingCartSumFields" => array(
    //            1 => array(
    ////                "tagihan" => "amount remains to pay",
    ////                "ppn" => "vat",
    ////                "nett2" => "total",
    //            ),
    //        ),
    //        "shoppingCartEditableFields" => array(
    //            1 => array(
    //                "jml",
    //                "debet",
    //                "kredit",
    //                "harga",
    //            ),
    //        ),
    //        "shoppingCartAmountValue" => array(
    //            1 => "jml*harga",
    //            2 => "jml*harga",
    //        ),
    //        "receiptElements" => array(
    //            "opAccount" => array(
    //                "elementType" => "dataModel",
    //                "inputType" => "radio",
    //                "label" => "opposite account",
    //                "mdlName" => "MdlRekeningDebetKredit",
    //                "mdlFilter" => array(
    //                    "name<>pihakName",
    //                ),
    //                "key" => "id",
    //                "labelSrc" => "name",
    //                "usedFields" => array(
    //                    "name" => "name",
    //                ),
    //                "editPoints" => array(1, 2, 3, 4),
    //            ),
    //        ),
    //    ),
    //  DEVELOPMENT ONLY

    //adjustmen biaya
    "9993" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "Adjustment biaya",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "Adjusment",
                "actionLabel" => "create",
                "source" => "",
                "target" => "9993",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "create by",
            ),
        ),
//        "template" => "template/transaksi_4.html",
        "template" => "template/transaksi_projek.html",
        "selectedPrice" => array(),
        "lockerCheck" => array(),

        "selectorModel" => "MdlJasa",
        "selectorSrcModel" => "MdlJasa",
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih barang / jasa",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProductException/select",
        "editHandlerMethod" => "select",
        // PIHAK I
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "Pilih vendor yang mengejakan projek",
        "pihakProcessor" => "_processPihak/select",
        // PIHAK II
        "pihakModelMain" => "MdlCabang",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "outlet / cabang",
        "pihakMainFilters" => array(
            "id<>.-1",

        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        // PIHAK III
//        "pihakModelExtern" => "MdlCustomerProject",
        "pihakModelExtern" => "MdlCustomer_and_pre",
        "pihakExternCaller" => "_selectorPihak/selectPihakExtern",
        "pihakExternLabel" => "customer project",
        "pihakExternViewedFields" => array(
            "nama",
        ),
        "pihakExternFilters" => array(
            "status=.1",
            "trash=.0",
//            "kategori_id=.2",
        ),
        "pihakExternProcessor" => "_processPihak/selectExtern",
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
            "transaksi_id>.0",
            "closing_status=.0",
//            "uang_muka_approved>.0",
        ),
        "pihakProjekProcessor" => "_processPihak/selectProjek",
        "pihakProjekResetor" => array(
            "enabled" => true,
//            "keys" => array(
//                "pihakExtern",
//                "pihakProjek",
//                "project",
//            ),
            "label_warning" => "Perhatian!!! Mengganti Project akan menghapus formulir yang sudah dibuat.",
        ),

        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "customerName" => "customer",
            "produkProjek__label" => "project",
            "nomer_top" => "PRE PO number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "SRN number",
            ),
            "nomer_ppn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "realisasi ppn number",
            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStatusFields" => array(
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            "customerName" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "customerName" => "customer",
                "produkProjek__label" => "project",
                "nomer_top" => "PO number",
                "oleh_nama" => "person",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "description" => "catatan",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "customerName" => "customer",
                "produkProjek__label" => "project",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "description" => "catatan",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "customerName" => "customer",
                "produkProjek__label" => "project",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer" => "receipt number",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "description" => "catatan",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "customerName" => "customer",
                "produkProjek__label" => "project",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer_srn" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "SRN number",
                ),
                "description_main_followup" => "INV<br>from vendor",
                "nomer" => "realisasi ppn number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "eFaktur" => "e-faktur",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
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
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pph23MethodPotongan__label" => "status pph 23",
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
            "dpp_ppn_persen" => "dpp_ppn_persen",
            "dpp_pph_persen" => "dpp_pph_persen",
            "pph" => "pph",
            "harga_disc" => "harga_disc",
            "dppPPh" => "dppPPh",
            "pph_nilai" => "pph_nilai",
            "dppPPn" => "dppPPn",
            "ppn" => "ppn",
        ),
        "shopingCartCompareFields" => array(
            1 => array(
                "main" => "pph",
                "slave" => "dpp_persen",
                //                "target" =>"valid_pph_key",
            ),

            2 => array(
                "main" => "pph",
                "slave" => "dpp_persen",
                //                "target" =>"valid_pph_key",
            ),

        ),
        //-------------------------
        "shoppingCartFields" => array(),
        "shoppingCartNumFields" => array(),
        //-------------------------
        "shoppingCartAdvanceItems" => true,
        "shoppingCartAdvanceItemsKey" => "pph",
        "shoppingCartAdvanceItemsSelector" => "_processSelectProductException/subSelect",
        "shoppingCartAdvanceItemsRemove" => "_processSelectProductException/subRemove",
        "shoppingCartAdvanceItemsAdd" => "_processSelectProductException/subAdd",
        "followupAdvanceItemsSelector" => "_followupLiveEdit/subSelect",
        "followupAdvanceItemsRemove" => "_followupLiveEdit/subRemove",
        "followupAdvanceItemsAdd" => "_followupLiveEdit/subAdd",
        "shoppingCartAdvanceFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
                ),
            ),
        ),
        "shoppingCartAdvanceNumFields" => array(
            1 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                    //-------------
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                    //-------------
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                    //-------------
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPh" => "dpp pph",
                    "pph_nilai" => "PPH(Rp)",
                    //-------------
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
            0 => array(
                1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
                    "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        "shoppingCartAdvanceAmountValue" => array(
            1 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),
            0 => array(
                1 => "jml*(harga_disc+ppn)",
                2 => "jml*(harga_disc+ppn)",
                3 => "jml*(harga_disc+ppn)",
                4 => "jml*(harga_disc+ppn)",
            ),

        ),

        "shoppingCartAdvanceSubFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
            0 => array(
                1 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                2 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                3 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
                4 => array(
                    "nama" => "Description",
//                    "jml" => "Qty",
//                    "satuan" => "Satuan",
                ),
            ),
        ),
        "shoppingCartAdvanceSubNumFields" => array(
            1 => array( // ini bila ada pph 23, atau biaya/jasa
                1 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph_nilai" => "PPH(Rp)",
                    //--------------
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph_nilai" => "PPH(Rp)",
                    //--------------
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph_nilai" => "PPH(Rp)",
                    //--------------
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_pph_persen" => "DPP PPH 23(%)",
                    "dppPPh" => "dpp pph 23",
                    "pph_nilai" => "PPH(Rp)",
                    //--------------
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
            0 => array(
                1 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                2 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                3 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
                4 => array(
                    "jml" => "Qty",
                    "harga" => "Unit Price",
//                    "discPersen" => "DISC(%)",
//                    "disc" => "DISC(Rp)",
//                    "harga_disc" => "Netto",
//
                    "dpp_ppn_persen" => "DPP PPN(%)",
                    "dppPPn" => "dpp ppn",
                    "ppn" => "PPN(Rp)",
                ),
            ),
        ),
        "shoppingCartAdvanceSubEditableFields" => array(
            1 => array(
                1 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                    "dpp_ppn_persen",
                ),
                2 => array(
                    "nama",
                    "harga",
                    "dpp_pph_persen",
                    "dpp_ppn_persen",
                ),
            ),
            0 => array(
                1 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
                2 => array(
                    "nama",
                    "jml",
                    "harga",
                    "dpp_ppn_persen",
                ),
            ),
        ),
        "shoppingCartAdvanceFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartAdvanceMaxValidators" => array(
            "key" => array(
                "dpp_pph_persen" => "DPP PPH (%) melebihi batas maksimum 100%",
                "dpp_ppn_persen" => " DPP PPN (%) melebihi batas maksimum 100%",
            ),
            "value" => "100",
        ),
        //-------------------------

        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
            ),
            2 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
            ),
            3 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
            ),
            4 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),

        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "jml",
//                "dpp_persen",
//                "discPersen",
//            ),
//            2 => array(
//                "harga",
//                "dpp_persen",
//                "discPersen",
//            ),
//            3 => array(
//                "harga",
//                "dpp_persen",
//                "discPersen",
//            ),
//            4 => array(
//                "dpp_persen",
//            ),
        ),

        "shopingCartParamForceEditable" => array(
            //ini untuk force editable fields
            1 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            2 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            3 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            4 => array(
                "allow_params_edit" => "dpp_persen"
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
            //            "nilai_dpp_ppn" =>"DPP PPN"
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "jml*(harga_disc+ppn)",
            //            2 => "jml*(harga_disc+ppn)",
            //            3 => "jml*(harga_disc+ppn)",
            //            4 => "jml*(harga_disc+ppn)",
            1 => "jml*(harga)",
            2 => "jml*(harga)",
            3 => "jml*(harga)",
            4 => "jml*(harga)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
            4 => false,
        ),
        "shopingCartEditableCompare" => array(
            "dpp_persen" => array(
                "npwp_allowed" => array(
                    0 => false,
                    1 => true
                ),

            ),
        ),
        "shopingcartAddDpp" => array(
            1 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            2 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            3 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            4 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            5 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),


        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "country" => "Country",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    "npwp" => "NPWP",
                    //                    "alias" => "Attn",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "biaya" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "biaya",
                "mdlName" => "MdlPettycashStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(),
                "editPoints" => array(1, 2, 3),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",

                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "biaya" => array(
                "700000" => array(
                    "biayaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "biaya detail",
                        "mdlName" => "MdlDtaBiayaUsaha",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "biaya"
                        ),
                        "editPoints" => array(1, 2, 3),
                    ),
                ),
                "780000" => array(
                    "biayaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "biaya detail",
                        "mdlName" => "MdlDtaBiayaUmum",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "biaya"
                        ),
                        "editPoints" => array(1, 2, 3),
                    ),

                ),
            ),
        ),
        "relativeOptions" => array(),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "dppPPh" => array(
                        "label" => "Dpp pph 23",
                        "defaultValue" => "dppPPh",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
//                            ",
//
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "pph_nilai" => array(
                        "label" => "Pph 23",
                        "defaultValue" => "pph_nilai",
                        "maxValue" => "pph_value",
                        "minValue" => "pph_value",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "dppPPn" => array(
                        "label" => "Dpp ppn",
                        "defaultValue" => "dppPPn",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
                            ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "ppn" => array(
                        "label" => "Ppn",
                        "defaultValue" => "ppn",
                        "maxValue" => "ppn_value",
                        "minValue" => "ppn_value",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "payment_out" => array(
                        "label" => "Grand total",
                        "defaultValue" => "payment_out",
                        "maxValue" => "payment_out",
                        "minValue" => "payment_out",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "suppliers_nama" => "vendor",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(
            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array("1", "4"),
        "addMainSource" => array(),
        "receiptEdit" => array(
            4 => true,
        ),
        // berada di midValidate() Transaksi
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
        "detailForceMain" => array(
            2 => array(
                "source" => "pph",
                "target" => "valid_pph_key",
                "elemenReset" => "MdlPph23MethodPotongan",
                "current_element" => "pph23MethodPotongan",
            ),
        ),
        // ======== =========
        "followupMainNoteValidator" => array(
            3 => array(
                "enabled" => true,
                "kolom" => array(
                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
                ),
                "source" => array(
                    "description_main_followup",
                ),
            ),
        ),
        "followupMainNote" => array(
            3 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
                "label" => "INVOICE FROM VENDOR (*)",
            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => false,
                "label" => "INVOICE FROM VENDOR (*)",
            ),

        ),
        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        // ======== =========
        "previewCtr" => "Create",

        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3463roe",
                "label" => "EDIT SERVICE PROJECT PURCHASE PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3463rorj",
                "label" => "REJECT SERVICE PROJECT PURCHASE PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "3463orj",
                "label" => "REJECT SERVICE PROJECT PURCHASE ORDER",
            ),
        ),
        //----
        /*
 * connect to untuk transaksi yang belum punya ppn aka faktur belum ready
 */
        "connectTo" => "111",
        "connectoValidate" => array(
            1 => "ppn",
        ),
        "replacerConnectTo" => array(
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
        "clonerTransaction" => array(
            1 => array(
                "main" => array(
                    "cloner" => true,
                ),
                "itemToMaster" => array(
                ),
                "staticItemToMaster" => array(//                    "transaksi_jenis2" => "paket",
                ),
                "details" => array(
                    //                    "harga" => "harga",
                    //                    // "jual_nppn" => "jual_nppn",
                    //                    "hpp" => "hpp",
                    //                    "disc" => "disc",
                    //                    // "ppn" => "ppn",
                    //                    "harga1" => "harga1",
                    //                    "harga_nett1" => "harga_nett1",
                    //                    "harga2" => "harga2",
                    //                    "harga_nett2" => "harga_nett2",
                ),
                "resetGate" => array(
                    //                    "items2",
                    //                    "items2_sum",
                    //                    "receiptSumFields2",
                    //                    "receiptDetailFields2",
                ),
            ),
        ),
        "tabRequestCode" => array(
            "masterCode" => "3463",
            "stateCode" => "3463",
            "stepNumber" => "1",
            "allowMultiSelect" => true,
        ),
        "tabHistoryFields" => array(
            "produk_id" => array(
                "label" => "All",
                "allowFollowup" => true,
            ),
//            "suppliers_id" => array(
//                "label" => "By Vendor",
//                "allowFollowup" => true,
//            ),
        ),
        "tabFieldsItems" => array(
//            "suppliers_id" => array(
//                "kode" => "kode",
//                "nama" => "Produk Nama",
//                "satuan" => "Satuan",
//                "omset" => "Omset",
//                "average" => "Average harian",
//                "stok" => "Stok",
//                "buffer" => "buffer(qty)",
//                // "moq" => "Moq",
//                "ideal_stok" => "proyeksi stok(qty)",
//                "new_order" => "Rekomendasi order",
//            ),
//            "produk_id" => array(
//                //                "select" => "All",
//                "kode" => "kode",
//                "nama" => "Produk Nama",
//                "satuan" => "Satuan",
//                "omset" => "Omset",
//                "average" => "Average harian",
//                "stok" => "Stok",
//                "buffer" => "buffer<br>(qty)",
//                // "moq" => "Moq",
//                "ideal_stok" => "proyeksi stok(qty)",
//                "new_order" => "Rekomendasi order",
//                // ""=>"",
//                //                "purchased" => "On Purchase",
//                //                "valid_qty" => "Outstanding",

//            ),
            "produk_id" => array(
                "select" => "All",
                //                "dtime" => "tanggal",
                //                "gudang2_nama" => "gudang",
                //                "produk_nama" => "Bahan Baku",
                //                "nomer_top" => "Transaksi No",
                //                "produk_ord_jml" => "Jumlah request",
                //                //                "purchased" => "On Purchase",
                //                //                "valid_qty" => "Outstanding",
//                "id"               => "bID",
                "nama" => "work order",
                "nama_jasa" => "nama jasa",
                "satuan" => "satuan",
                "sales_order" => "kebutuhan",
//                "stock"            => "stok tersedia",
//                "stok_buffer"      => "buffer",
                "purchase_request" => "request",
                "purchase_order" => "outstanding PO",
                // "outstanding"    => "outstanding",
                "new_order" => "kekurangan",
            ),
            "produk_id_attr" => array(
                "select" => array(),
                "id" => array(),
                "nama" => array(),
                "satuan" => array(),
                "stock" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                ),
                "sales_order" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "link" => "bi/Penjualan/supplies",
                    "link_head" => "nama",
                ),
                "stok_buffer" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                ),
                "purchase_request" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                ),
                "purchase_order" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "link" => "bi/Pembelian/Supplies",
                    "link_head" => "nama",
                ),
                "outstanding" => array(),
                "new_order" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "harga",
                    "attr" => "class='text-bold bg-danger'",
                ),
            ),
        ),
        "validateRelasiUangMuka" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "undoneItemsIndexAll" => true,
    ),
);


