<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    "671" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pettycash (branch)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "pettycash",
                "actionLabel" => "save",
                "source" => "",
                "target" => "671r",
                "userGroup" => "o_kasir",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "allowReject" => true,
            ),
//            2 => array(
//                "label" => "pettycash. authorization",
//                "actionLabel" => "approve request",
//                "source" => "671r",
//                "target" => "671",
//                "userGroup" => "o_kasir",
//                "stateLabel" => "make claim",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        "template" => "template/transaksi_pettycash.html",
        "selectorModel" => "MdlExpense",
        "selectorSrcModel" => "MdlExpense",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "pettycash=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pettycash",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        //        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "kantor pusat harus ditentukan...",
        "pihakFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakDisabled" => "disabled",

        "shortStepHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang2_nama" => "dari",
            "cabang_nama" => "tujuan",
            "nomer" => "nomer",
            //            "759" => "approval number",
            //            "758r" => "request number",
            // "758" => "receipt number",

            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
            // "cash_account_source__label" => "bank account source",
            // "cash_account_target__label" => "bank account target",
            "next_pic" => "next step otorisator",
        ),
        //tambahan pihak2

        "mainselectorModel" => array(
            "MdlDtaBiayaProduksi" => array(
                "label" => "biaya produksi",
                "allowed_branch" => array(25)
            ),
            "MdlDtaBiayaUsaha" => array(
                "label" => "biaya usaha",
                "allowed_branch" => array(1, 21),
            ),
            "MdlDtaBiayaUmum" => array(
                "label" => "biaya umum",
                "allowed_branch" => array(1, 21, 25),
            ),

        ),

        "pihakModelMain" => "MdlPettycashStatic",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "jenis biaya",
        "pihakMainFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        //

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
//            "suppliers_nama" => "vendor",
            "nomer_top" => "request number",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
            "pihakMainName" => "kategori biaya",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                //                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "pihakMainName" => "kategori biaya",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                //                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "pihakMainName" => "kategori biaya",
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

            "dpp_nilai" => "dpp_nilai",
            "ppn_nilai" => "ppn_nilai",
            "harga" => "harga",
            "dateFaktur" => "dateFaktur",
            "eFaktur" => "eFaktur",
            "ppn_sudah_faktur" => "ppn_sudah_faktur",
            "ppn_nilai_sudah_faktur" => "ppn_nilai_sudah_faktur",
            "ppnPersenCheck" => "ppnPersenCheck",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
                //                "jml" => "qty",
//                "reference" => "reference",
                "ppnPersenCheck" => "Non PPN/PPN",
//                "ppnPersenCheck" => array(
//                    "label" => " ### ",
//                    "processSelect" => "_processSelectProductPpn/selectItem/",
//                    "form" => array(
//                        "non_ppn" => array(
//                            "label" => "Non PPN",
//                            "srcMain" => "ppnPersenCheck",
//                            "overWriteMain" => "ppnFactor",
//                            "srcItem" => "ppnFactor",
//                            "value" => "0",
//                            "style" => array( //wajib di tentukan
//                                "element" => "div",
//                                "class" => "",
//                                "id" => "",
//                                "name" => "",
//                            )
//                        ),
//                        "ppn" => array(
//                            "label" => "PPN",
//                            "srcMain" => "ppnPersenCheck",
//                            "overWriteMain" => "ppnFactor",
//                            "srcItem" => "ppnFactor",
//                            "value" => "1",
//                            "style" => array( //wajib di tentukan
//                                "element" => "div",
//                                "class" => "",
//                                "id" => "",
//                                "name" => "",
//                            )
//                        ),
//                    ),
//                ),
            ),
            2 => array(
                "nama" => "nama",
                "jml" => "qty",
//                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "reference" => "reference",
                "harga" => "harga",
            ),
            2 => array(
                "reference" => "reference",
                "harga" => "harga",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => true,
            //            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartEditableFieldsOptions" => array(
            1 => array(
                "ppnPersenCheck" => array(
                    "inputType" => "radio",
                    "label" => "Non PPN/PPN",
                    "processSelect" => "_processSelectProductPpn/selectItem/",
                    "dataField" => array(
                        "non_ppn" => array(
                            "label" => "Non PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 0,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                        "ppn" => array(
                            "label" => "PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 1,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                    ),
                ),
            ),
        ),

        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
//            "eFaktur" => "eFaktur",
//            "dateFaktur" => "dateFaktur",
            //            "reference" => "reference",
        ),
        //        "shoppingCartRowValidators" => array(),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            "harga" => "sumber",
            "pettycash_account__saldo" => "target",
        ),
        "shoppingCartFieldMidValidatorsComparisonLabel" => array(
            "harga" => "request klaim",
            "pettycash_account__saldo" => "saldo pettycash",
        ),
        "shoppingCartValidatorsComparison" => array(
            "harga" => "sumber",
            "pettycash_account__saldo" => "target",
        ),
        "shoppingCartValidatorsComparisonLabel" => array(
            "harga" => "request klaim",
            "pettycash_account__saldo" => "saldo pettycash",
        ),
//        "shoppingCartHeaderElement" => array(
//            1 => array(
//                "ppn" => array(
//                    "label" => "TENTUKAN PPN",
//                    "class" => "col-xs-6 no-padding",
//                    "subElements" => array(
//                        "non_ppn" => array(
//                            "label" => "Non PPN",
//                            "srcMain" => "ppnPersenCheck",
//                            "overWriteMain" => "ppnFactor",
//                            "srcItem" => "ppnFactor",
//                            "value" => "0",
//                            "style" => array( //wajib di tentukan
//                                "element" => "div",
//                                "class" => "",
//                                "id" => "",
//                                "name" => "",
//                            )
//                        ),
//                        "ppn" => array(
//                            "label" => "PPN",
//                            "srcMain" => "ppnPersenCheck",
//                            "overWriteMain" => "ppnFactor",
//                            "srcItem" => "ppnFactor",
//                            "value" => "1",
//                            "style" => array( //wajib di tentukan
//                                "element" => "div",
//                                "class" => "",
//                                "id" => "",
//                                "name" => "",
//                            )
//                        ),
//                    ),
//                ),
//            ),
//        ),
        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "dpp_nilai" => "Dasar Pengenaan pajak",
                    "ppn_nilai" => "Ppn",
                    "harga" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer e-faktur PPN Masukan",
                    "nomer_npwp" => "nomer NPWP",
                ),
                "addFields" => array(
                    "ppn_sudah_faktur" => "ppn_final",
                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    "nomer_npwp" => "text",
                ),
                "editProcess" => "_processPihak/addTaxData",
                "gateTarget" => "items",
                "gateItemCek" => "ppnPersenCheck",
                "defaultCheck" => "non_ppn",
            ),
        ),
        "receiptElements" => array(
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash amount",
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "pettycash",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
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
        "relativeElements" => array(),
        "relativeOptions" => array(),

        "pairRegistries" => array(
            "main", "items"
        ),
        "connectTo" => "672",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "671re",
                "label" => "EDIT pettycash",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "671rrj",
                "label" => "REJECT pettycash",
            ),
        ),
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
            "dateFaktur" => "tanggal faktur",
            "eFaktur" => "nomer faktur",
        ),
        "pettycash" => array(
            "plafon" => array(
                "comModel" => "ComRekeningPembantuKas",
                "rekening" => "1010010040",
                "comFilter" => array(
                    "cabang_id=.-1",
                    "extern_id=pettycash_account",
                ),
            ),

        ),
        "pettycashHeader" => array(
            "pettycash_plafon" => array(
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__plafon",
                "label" => "plafon",
            ),
            "pettycash_terpakai" => array(
                "cabang_id" => "placeID",
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__terpakai",
                "label" => "dipakai",
            ),
            "pettycash_saldo" => array(
                "cabang_id" => "placeID",
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__saldo",
                "label" => "saldo",
                "link_mutasi" => "Ledger/viewMoveDetailsPettycash/RekeningPembantuPettycash/1010010040/",
            ),
        ),
        "shopingCartReload" => true,
    ),
    "672" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "otorisasi pettycash (branch)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pettycash",
                "actionLabel" => "save",
                "source" => "",
                "target" => "672r",
                "userGroup" => "sys",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi pettycash",
                "actionLabel" => "otorisasi claim pettycash",
                "source" => "672r",
                "target" => "672",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlPettycash",
        "selectorSrcModel" => "MdlPettycash",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "suppliers_id=pihakID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pettycash",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",

//        "addMainSource" => array(
//            1 => array(
//                "fields" => array(
//                    //                    "nomer" => "INV",
//                    "dpp_nilai" => "Dasar Pengenaan pajak",
//                    "ppn_nilai" => "Ppn(11%)",
////                    "nilai_entry" => "Total",
//                    "harga" => "Total",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "nomer e-faktur PPN Masukan",
////                    "skip_faktur" => "belum ada faktur",
//                ),
//                "addFields" => array(
//                    "ppn_sudah_faktur" => "ppn_final",
//                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
//                ),
//                "editableFields" => array(
////                    "dpp_nilai" => "number",
//                    //                    "dpp_ppn" => "number",
//                    //                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
////                    "skip_faktur" => "checkbox",
//                ),
//                "editProcess" => "_processPihak/addTaxData",
////                "enabledPpn" => "true",
//                "gateTarget" => "items",
//                "addMainSourceShow" => false,
//            ),
//            2 => array(
//                "fields" => array(
//                    //                    "nomer" => "INV",
//                    "dpp_nilai" => "Dasar Pengenaan pajak",
//                    "ppn_nilai" => "Ppn(11%)",
////                    "nilai_entry" => "Total",
//                    "harga" => "Total",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "nomer e-faktur PPN Masukan",
////                    "skip_faktur" => "belum ada faktur",
//                ),
//                "addFields" => array(
//                    "ppn_sudah_faktur" => "ppn_final",
//                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
//                ),
//                "editableFields" => array(
////                    "dpp_nilai" => "number",
//                    //                    "dpp_ppn" => "number",
//                    //                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
////                    "skip_faktur" => "checkbox",
//                ),
//                "editProcess" => "_processPihak/addTaxData",
////                "enabledPpn" => "true",
//                "gateTarget" => "items",
//                "addMainSourceShow" => false,
//            ),
//        ),
        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "dpp_nilai" => "Dasar Pengenaan pajak",
                    "ppn_nilai" => "Ppn",
                    "harga" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer e-faktur PPN Masukan",
                    "nomer_npwp" => "nomer NPWP",
                ),
                "addFields" => array(
                    "ppn_sudah_faktur" => "ppn_final",
                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    "nomer_npwp" => "text",
                ),
                "editProcess" => "_processPihak/addTaxData",
                "gateTarget" => "items",
                "gateItemCek" => "ppnPersenCheck",
            ),
            2 => array(
                "fields" => array(
                    "dpp_nilai" => "Dasar Pengenaan pajak",
                    "ppn_nilai" => "Ppn",
                    "harga" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer e-faktur PPN Masukan",
                    "nomer_npwp" => "nomer NPWP",
                ),
                "addFields" => array(
                    "ppn_sudah_faktur" => "ppn_final",
                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
                ),
                "editableFields" => array(
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
//                    "nomer_npwp" => "text",
                ),
                "editProcess" => "_processPihak/addTaxData",
                "gateTarget" => "items",
                "gateItemCek" => "ppnPersenCheck",
            ),
        ),

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "cabang",
            "nomer_top" => "request number",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
            "pihakMainName" => "kategori biaya",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "pihakMainName" => "kategori biaya",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "pihakMainName" => "kategori biaya",
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
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
                "ppnPersenCheck" => "Non PPN/PPN",
            ),
            2 => array(
                "nama" => "nama",
                "ppnPersenCheck" => "Non PPN/PPN",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
            ),
            2 => array(
                "max_harga" => "request",
                "harga" => "harga",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => false,
            3 => false,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(
                "harga",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",

        "shoppingCartEditableFieldsOptions" => array(
            1 => array(
                "ppnPersenCheck" => array(
                    "inputType" => "radio",
                    "label" => "Non PPN/PPN",
                    "processSelect" => "_processSelectProductPpn/selectItem/",
                    "dataField" => array(
                        "non_ppn" => array(
                            "label" => "Non PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 0,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                        "ppn" => array(
                            "label" => "PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 1,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                    ),
                ),
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
//        "shoppingCartAvoidRemove" => true,
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
            "dateFaktur" => "tanggal faktur",
            "eFaktur" => "nomer faktur",
        ),
        "shopingCartPairRebuild" => array(
            //dipakai live edit
            2 => array(
                "gate" => "items",
                "src" => array(
                    "source" => "max_harga",// atau  harga_original dibuild oleh _followUpLiveEdit
                    "target" => "harga",
                ),
                "validate" => array(
                    /*
                     * jika memanggil dari Mdls, gunakan mdlName
                     * * contoh "modelPath"=>"Mdls
                     * contoh "mdlName"=>"MdlRekening
                     * jika memnggail dari Coms, gunakan comName
                     * contoh "modelPath"=>"Coms
                     * contoh "comName"=>"comRekening
                     * saat ini baru suport ComlockerValue bre
                     */
                    "modelPath" => "Coms",
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "rekening" => "pettycash",//wajibbb
                    "value" => "saldo",
                    "target" => "plafon_koreksi",
                    "mdlFilter" => array(
                        "cabang_id=place2ID",
                        "state" => ".active",
                    ),
                    "errorLabel" => "Saldo petty cash tidak cukup.",
                    "errorFixing" => "Silahkan lakukan refill atau naikan plafon terlebih dahulu,<br> jika akan melakukan otorisasi lebih besar dari plafon",
                ),

            ),
        ),
        //---------
    ),
    "771" => array(
        "icon" => "fa fa-money",
        "label" => "pettycash refill (branch)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "refill pettycash",
                "actionLabel" => "process refill",
                "source" => "",
                "target" => "771",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi_payment.html",
        //        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.671",
            "transaksi_nilai_sisa>.0",
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

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "customers_nama" => "cabang",
            "nomer" => "nomer",
            "nilai_entry" => "nilai",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "customers_nama" => "cabang",
                "nomer" => "nomer",
                "nilai_entry" => "nilai",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" =>"id",
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
                "jml" => "qty",
            ),

        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "ppnPersenCheck" => "Non PPN/PPN",
                "jml" => "qty",
                "harga" => "price",
                //                "referensi" => "reference",
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                //                "satuan" => "satuan",
                //                "referensi" => "reference",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "id_master" => "id_master",
            "extern_label2" => "pihakMainName",
            "dpp_nilai" => "dpp_ppn",
            "ppn_nilai" => "ppn",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                //                "pairedModel" => array(
                //                    "mdlName" => "ComRekeningPembantuKas",
                //                    "mdlMethod" => "fetchBalances",
                //                    "mdlFilter" => array(
                //                        "cabang_id=placeID",
                //                    ),
                //                    "key" => "extern_id",
                //                    "rekening" => "kas",
                //                    "fieldID" => "debet",
                //                    "fieldLabel" => "saldo",
                //                ),
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "kas",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                //                "mdlName" => "MdlBankAccount_cash_and_out",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(//                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
            ),
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash amount",
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "pettycash_target"=>array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "jenis pettycash",
                "mdlName" => "MdlPettycashAdjustment",
                "mdlFilter" => array(
//                    "cabang_id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
//                "noValidate" => true,
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
            "pettycash_target"=>array(
                "2"=>array(
                    "jenisReferensi"=>array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Jenis titipan",
                        "mdlName" => "MdlUangMukaRefereceStatic2",
                        "mdlFilter" => array(
//                    "cabang_id=pihakID",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "account",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                    ),

                ),//
            ),
            "jenisReferensi"=>array(
                "1"=>array(
                    "referensiSo" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "purchase order",
                        "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
                        "key" => "id",
                        "mdlFilter" => array(
                            "suppliers_id=supplier_id",
                            "jenis=.466",
                            "link_id=.0",
                        ),
                        "labelSrc" => "nomer",
                        "usedFields" => array(
                            "id_master" => "mid",
                            "id" => "referensi order",
                            "fulldate" => "tgl order",
                            "nomer" => "nomer po/order",
//                        "oleh_nama" => 'salesman'
                        ),
                        "editPoints" => array(1,),
                        "labelValidate" => "Silahkan memilih PO yang sudah disetujui sesuai Supplier.",

                    ),
                ),
                "3"=>array(
                    "supplierTitipan"=>array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "supplier",
                        "mdlName" => "MdlSupplier",//ini klonengan mdltransaksi
                        "key" => "id",
                        "mdlFilter" => array(
//                            "suppliers_id=supplier_id",
//                            "jenis=.466",
//                            "link_id=.0",
                        ),
                        "labelSrc" => "nama",
                        "usedFields" => array(

                            "id" => "pid",
                            "nama" => "nama",
                            "npwp" => "npwp",
//                        "oleh_nama" => 'salesman'
                        ),
                        "editPoints" => array(1,),
                        "labelValidate" => "Silahkan memilih Supplier.",

                    ),
                ),
            ),

        ),
        "pairMakers" => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items2_sum",
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "preBiaya" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "costName",
                    ),
                ),
            ),
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                //                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total request",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue" => "(sisa-creditAmount-creditValue)",
                        "minValue" => "(sisa-creditAmount-creditValue)",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => "true",
                    ),
                    "nilai_entry" => array(
                        "label" => "jumlah refill",
                        "defaultValue" => "harus_bayar",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "extern_label2" => "jenis",
            "nomer" => "nomor otorisasi",
            "nomer_top" => "nomor request",
            "fulldate" => "tanggal",
            "tagihan" => "bruto (Rp)",
            "terbayar" => "direfill (Rp)",
            "sisa" => "netto (Rp)",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "bruto (Rp)",
            "terbayar" => "direfill (Rp)",
            "sisa" => "netto (Rp)",
        ),
        "mainCloner" => array(
            "items" => array(
                "pettycash_account" => "pettycash_account",
                "pettycash_account__label" => "pettycash_account__label",
            ),
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "relativeComNameDetails" => array(
            "biaya usaha" => "RekeningPembantuBiayaUsaha",
            "biaya produksi" => "RekeningPembantuBiayaProduksi",
            "biaya umum" => "RekeningPembantuBiayaUmum",
        ),
        "shoppingCartPairedItem" => array(
            //            "enabled" => true,
            //            "mdlName" => "MdlProduk",
            //            "srcKey" => "id",
            //            "srcLabel" => array("nama"),
            //            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "previewCtr" => "Create",
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
        ),
        "shopingCartReload" => true,

        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "supplier_nama" => "Vendor",
                    "dpp_nilai" => "Dasar Pengenaan pajak",
                    "ppn_nilai" => "Ppn",
//                    "nilai_entry" => "Total",
                    "harga" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer e-faktur PPN Masukan",
                    "nomer_npwp" => "nomer NPWP",
                ),
                "addFields" => array(
                    "ppn_sudah_faktur" => "ppn_final",
                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
                ),
                "editableFields" => array(
                    "supplier_nama" => "combo",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    "nomer_npwp" => "text",
                ),
                "editProcess" => "_processPihak/addTaxDataRefillMode",
//                "enabledPpn" => "true",
//                "gateSource" => "items2_sum",
                "gateTarget" => "items2_sum",
                "gateItemCek" => "ppnPersenCheck",
                "addMainSourceShow" => false,
                "refillMode" => true,
                "refillModePpn" => "ppn_nilai",
                "pairData" => array(
                    "mdlName" => "MdlSupplier",
                    "mdlFilter" => array(),
                    "key" => "supplier_id",
                ),
            ),
//            2 => array(
//                "fields" => array(
//                    //                    "nomer" => "INV",
//                    "dpp_nilai" => "Dasar Pengenaan pajak",
//                    "ppn_nilai" => "Ppn(11%)",
////                    "nilai_entry" => "Total",
//                    "harga" => "Total",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "nomer e-faktur PPN Masukan",
////                    "skip_faktur" => "belum ada faktur",
//                ),
//                "addFields" => array(
//                    "ppn_sudah_faktur" => "ppn_final",
//                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
//                ),
//                "editableFields" => array(
////                    "dpp_nilai" => "number",
//                    //                    "dpp_ppn" => "number",
//                    //                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
////                    "skip_faktur" => "checkbox",
//                ),
//                "editProcess" => "_processPihak/addTaxData",
////                "enabledPpn" => "true",
//                "gateTarget" => "items",
//                "addMainSourceShow" => false,
//            ),
        ),
        "shoppingCartEditableFieldsOptions" => array(
            1 => array(
                "ppnPersenCheck" => array(
                    "inputType" => "radio",
                    "label" => "Non PPN/PPN",
                    "processSelect" => "_processSelectProductPpn/selectItemRefill/",
                    "dataField" => array(
                        "non_ppn" => array(
                            "label" => "Non PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 0,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                        "ppn" => array(
                            "label" => "PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 1,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                    ),
                ),
            ),
        ),
        "shoppingCartFakturValidator" => array(
            "enabled" => true,
            "cek" => "ppnPersenCheck",
            "cekValue" => 1,
            "cekGerbangParam" => "supplier_id",
            "label" => "Vendor untuk biaya {biaya_nama} belum ditentukan. Silahkan tentukan dahulu.",
        ),
    ),
    //  config pettycash center
    "1671" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pettycash (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "pettycash",
                "actionLabel" => "save",
                "source" => "",
                "target" => "1671r",
                "userGroup" => "c_holding",
                //                "userGroup" => "disabled",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
//            2 => array(
//                "label" => "pettycash. authorization",
//                "actionLabel" => "approve request",
//                "source" => "1671r",
//                "target" => "1671",
//                "userGroup" => "c_holding",
//                //                "userGroup" => "disabled",
//                "stateLabel" => "make claim",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        "template" => "template/transaksi_pettycash.html",
        "selectorModel" => "MdlExpense",
        "selectorSrcModel" => "MdlExpense",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "pettycash=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pettycash",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        //        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakDisabled" => "disabled",

        "shortStepHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang2_nama" => "dari",
            "cabang_nama" => "tujuan",
            "nomer" => "nomer",
            //            "759" => "approval number",
            //            "758r" => "request number",
            // "758" => "receipt number",

            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
            // "cash_account_source__label" => "bank account source",
            // "cash_account_target__label" => "bank account target",
            "next_pic" => "next step otorisator",
        ),

        //tambahan pihak2

        "mainselectorModel" => array(
            "MdlDtaBiayaProduksi" => array(
                "label" => "biaya produksi",
                "allowed_branch" => array(25)
            ),
            "MdlDtaBiayaUsaha" => array(
                "label" => "biaya usaha",
                "allowed_branch" => array(1, 21),
            ),
            "MdlDtaBiayaUmum" => array(
                "label" => "biaya umum",
                "allowed_branch" => array(1, 21, 25),
            ),

        ),

        "pihakModelMain" => "MdlPettycashStatic",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "jenis biaya",
        "pihakMainFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        //

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
//            "suppliers_nama" => "vendor",
            "nomer_top" => "request number",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
            "pihakMainName" => "kategori biaya",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                //                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "pihakMainName" => "kategori biaya",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                //                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "pihakMainName" => "kategori biaya",
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

            "dpp_nilai" => "dpp_nilai",
            "ppn_nilai" => "ppn_nilai",
            "harga" => "harga",
            "dateFaktur" => "dateFaktur",
            "eFaktur" => "eFaktur",
            "ppn_sudah_faktur" => "ppn_sudah_faktur",
            "ppn_nilai_sudah_faktur" => "ppn_nilai_sudah_faktur",
            "ppnPersenCheck" => "ppnPersenCheck",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
                //                "jml" => "qty",
//                "reference" => "reference",
                "ppnPersenCheck" => "Non PPN/PPN",
            ),
            2 => array(
                "nama" => "nama",
                "jml" => "qty",
//                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "reference" => "reference",
                "harga" => "harga",
            ),
            2 => array(
                "reference" => "reference",
                "harga" => "harga",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => true,
            //            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartEditableFieldsOptions" => array(
            1 => array(
                "ppnPersenCheck" => array(
                    "inputType" => "radio",
                    "label" => "Non PPN/PPN",
                    "processSelect" => "_processSelectProductPpn/selectItem/",
                    "dataField" => array(
                        "non_ppn" => array(
                            "label" => "Non PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 0,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                        "ppn" => array(
                            "label" => "PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 1,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                    ),
                ),
            ),
        ),

        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
//            "eFaktur" => "eFaktur",
//            "dateFaktur" => "dateFaktur",
            //            "reference" => "reference",
        ),
        //        "shoppingCartRowValidators" => array(),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            "harga" => "sumber",
            "pettycash_account__saldo" => "target",
        ),
        "shoppingCartFieldMidValidatorsComparisonLabel" => array(
            "harga" => "request klaim",
            "pettycash_account__saldo" => "saldo pettycash",
        ),
        "shoppingCartValidatorsComparison" => array(
            "harga" => "sumber",
            "pettycash_account__saldo" => "target",
        ),
        "shoppingCartValidatorsComparisonLabel" => array(
            "harga" => "request klaim",
            "pettycash_account__saldo" => "saldo pettycash",
        ),
//        "shoppingCartHeaderElement" => array(
//            1 => array(
//                "ppn" => array(
//                    "label" => "TENTUKAN PPN",
//                    "class" => "col-xs-6 no-padding",
//                    "subElements" => array(
//                        "non_ppn" => array(
//                            "label" => "Non PPN",
//                            "srcMain" => "ppnPersenCheck",
//                            "overWriteMain" => "ppnFactor",
//                            "srcItem" => "ppnFactor",
//                            "value" => "0",
//                            "style" => array( //wajib di tentukan
//                                "element" => "div",
//                                "class" => "",
//                                "id" => "",
//                                "name" => "",
//                            )
//                        ),
//                        "ppn" => array(
//                            "label" => "PPN",
//                            "srcMain" => "ppnPersenCheck",
//                            "overWriteMain" => "ppnFactor",
//                            "srcItem" => "ppnFactor",
//                            "value" => "1",
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
        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "dpp_nilai" => "Dasar Pengenaan pajak",
                    "ppn_nilai" => "Ppn",
                    "harga" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer e-faktur PPN Masukan",
                    "nomer_npwp" => "nomer NPWP",
                ),
                "addFields" => array(
                    "ppn_sudah_faktur" => "ppn_final",
                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    "nomer_npwp" => "text",
                ),
                "editProcess" => "_processPihak/addTaxData",
                "gateTarget" => "items",
                "gateItemCek" => "ppnPersenCheck",
            ),
        ),
        "receiptElements" => array(
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash amount",
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "pettycash",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
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
        "relativeElements" => array(),
        "relativeOptions" => array(),

        "pairRegistries" => array(
            "main", "items"
        ),
        "connectTo" => "1672",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1671re",
                "label" => "EDIT pettycash",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1671rrj",
                "label" => "REJECT pettycash",
            ),
        ),
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
            "dateFaktur" => "tanggal faktur",
            "eFaktur" => "nomer faktur",
        ),
        "pettycash" => array(
            "plafon" => array(
                "comModel" => "ComRekeningPembantuKas",
                "rekening" => "1010010040",
                "comFilter" => array(
                    "cabang_id=.-1",
                    "extern_id=pettycash_account",
                ),
            ),

        ),
        "pettycashHeader" => array(
            "pettycash_plafon" => array(
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__plafon",
                "label" => "plafon",
            ),
            "pettycash_terpakai" => array(
                "cabang_id" => "placeID",
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__terpakai",
                "label" => "dipakai",
            ),
            "pettycash_saldo" => array(
                "cabang_id" => "placeID",
                "key_id" => "pettycash_account",
                "key" => "pettycash_account__saldo",
                "label" => "saldo",
                "link_mutasi" => "Ledger/viewMoveDetailsPettycash/RekeningPembantuPettycash/1010010040/",
            ),
        ),
        "shopingCartReload" => true,
    ),
    "1672" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "otorisasi pettycash (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "pettycash",
                "actionLabel" => "save",
                "source" => "",
                "target" => "1672r",
                "userGroup" => "sys",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi pettycash",
                "actionLabel" => "otorisasi claim pettycash",
                "source" => "1672r",
                "target" => "1672",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlPettycash",
        "selectorSrcModel" => "MdlPettycash",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "suppliers_id=pihakID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pettycash",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",


//        "addMainSource" => array(
//            1 => array(
//                "fields" => array(
//                    //                    "nomer" => "INV",
//                    "dpp_nilai" => "Dasar Pengenaan pajak",
//                    "ppn_nilai" => "Ppn(11%)",
////                    "nilai_entry" => "Total",
//                    "harga" => "Total",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "nomer e-faktur PPN Masukan",
////                    "skip_faktur" => "belum ada faktur",
//                ),
//                "addFields" => array(
//                    "ppn_sudah_faktur" => "ppn_final",
//                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
//                ),
//                "editableFields" => array(
////                    "dpp_nilai" => "number",
//                    //                    "dpp_ppn" => "number",
//                    //                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
////                    "skip_faktur" => "checkbox",
//                ),
//                "editProcess" => "_processPihak/addTaxData",
////                "enabledPpn" => "true",
//                "gateTarget" => "items",
//                "addMainSourceShow" => false,
//            ),
//            2 => array(
//                "fields" => array(
//                    //                    "nomer" => "INV",
//                    "dpp_nilai" => "Dasar Pengenaan pajak",
//                    "ppn_nilai" => "Ppn(11%)",
////                    "nilai_entry" => "Total",
//                    "harga" => "Total",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "nomer e-faktur PPN Masukan",
////                    "skip_faktur" => "belum ada faktur",
//                ),
//                "addFields" => array(
//                    "ppn_sudah_faktur" => "ppn_final",
//                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
//                ),
//                "editableFields" => array(
////                    "dpp_nilai" => "number",
//                    //                    "dpp_ppn" => "number",
//                    //                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
////                    "skip_faktur" => "checkbox",
//                ),
//                "editProcess" => "_processPihak/addTaxData",
////                "enabledPpn" => "true",
//                "gateTarget" => "items",
//                "addMainSourceShow" => false,
//            ),
//        ),
        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "dpp_nilai" => "Dasar Pengenaan pajak",
                    "ppn_nilai" => "Ppn",
                    "harga" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer e-faktur PPN Masukan",
                    "nomer_npwp" => "nomer NPWP",
                ),
                "addFields" => array(
                    "ppn_sudah_faktur" => "ppn_final",
                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    "nomer_npwp" => "text",
                ),
                "editProcess" => "_processPihak/addTaxData",
                "gateTarget" => "items",
                "gateItemCek" => "ppnPersenCheck",
            ),
        ),

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "cabang",
            "nomer_top" => "request number",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "transaksi_nilai" => "nilai",
            "pihakMainName" => "kategori biaya",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "pihakMainName" => "kategori biaya",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
                "transaksi_nilai" => "nilai",
                "pihakMainName" => "kategori biaya",
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
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
                "ppnPersenCheck" => "Non PPN/PPN",
            ),
            2 => array(
                "nama" => "nama",
                "ppnPersenCheck" => "Non PPN/PPN",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga",
            ),
            2 => array(
                "harga" => "harga",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteEditabled" => array(
            2 => false,
            3 => false,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(
                "harga",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",

        "shoppingCartEditableFieldsOptions" => array(
            1 => array(
                "ppnPersenCheck" => array(
                    "inputType" => "radio",
                    "label" => "Non PPN/PPN",
                    "processSelect" => "_processSelectProductPpn/selectItem/",
                    "dataField" => array(
                        "non_ppn" => array(
                            "label" => "Non PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 0,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                        "ppn" => array(
                            "label" => "PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 1,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                    ),
                ),
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
            "dateFaktur" => "tanggal faktur",
            "eFaktur" => "nomer faktur",
        ),
    ),
    "1771" => array(
        "icon" => "fa fa-money",
        "label" => "pettycash refill (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "refill pettycash",
                "actionLabel" => "process refill",
                "source" => "",
                "target" => "1771",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "template" => "template/transaksi_payment.html",
        //        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.671",
            "transaksi_nilai_sisa>.0",
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

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "customers_nama" => "cabang",
            "nomer" => "nomer",
            "nilai_entry" => "nilai",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "customers_nama" => "cabang",
                "nomer" => "nomer",
                "nilai_entry" => "nilai",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" =>"id",
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
                "jml" => "qty",
            ),

        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "ppnPersenCheck" => "Non PPN/PPN",
                "jml" => "qty",
                "harga" => "price",
                //                "referensi" => "reference",
            ),
            2 => array(
                "nama" => "item source name",
                "jml" => "qty",
                //                "satuan" => "satuan",
                //                "referensi" => "reference",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "id_master" => "id_master",
            "extern_label2" => "pihakMainName",
            "dpp_nilai" => "dpp_ppn",
            "ppn_nilai" => "ppn",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                //                "pairedModel" => array(
                //                    "mdlName" => "ComRekeningPembantuKas",
                //                    "mdlMethod" => "fetchBalances",
                //                    "mdlFilter" => array(
                //                        "cabang_id=placeID",
                //                    ),
                //                    "key" => "extern_id",
                //                    "rekening" => "kas",
                //                    "fieldID" => "debet",
                //                    "fieldLabel" => "saldo",
                //                ),
                "pairedModel" => array(
                    "mdlName" => "ComLockerValue",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
                    ),
                    "key" => "produk_id",
                    "rekening" => "kas",
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                //                "mdlName" => "MdlBankAccount_cash_and_out",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(//                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
            ),
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash amount",
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
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
        "pairMakers" => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items2_sum",
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "preBiaya" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "costName",
                    ),
                ),
            ),
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                //                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount" => array(
                        "label" => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue" => "(sisa-creditAmount-creditValue)",
                        "minValue" => "(sisa-creditAmount-creditValue)",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        "hideRow" => "true",
                    ),
                    "nilai_entry" => array(
                        "label" => "amount of payment",
                        "defaultValue" => "harus_bayar",
                        "keyupAction" => "
    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harus_bayar').value;}
                            ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields" => array(
            "extern_label2" => "jenis",
            "nomer" => "nomor otorisasi",
            "nomer_top" => "nomor request",
            "fulldate" => "tanggal",
            "tagihan" => "bruto (Rp)",
            "terbayar" => "direfill (Rp)",
            "sisa" => "netto (Rp)",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "branch",
            "tagihan" => "bruto (Rp)",
            "terbayar" => "direfill (Rp)",
            "sisa" => "netto (Rp)",
        ),
        "mainCloner" => array(
            "items" => array(
                "pettycash_account" => "pettycash_account",
                "pettycash_account__label" => "pettycash_account__label",
            ),
        ),
        "componentsAss" => array(
            "model" => "MdlTransaksi",
            "modelSrc" => "MdlNotaItem",
        ),
        "relativeComNameDetails" => array(
            "biaya usaha" => "RekeningPembantuBiayaUsaha",
            "biaya produksi" => "RekeningPembantuBiayaProduksi",
            "biaya umum" => "RekeningPembantuBiayaUmum",
        ),
        "shoppingCartPairedItem" => array(
            //            "enabled" => true,
            //            "mdlName" => "MdlProduk",
            //            "srcKey" => "id",
            //            "srcLabel" => array("nama"),
            //            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "previewCtr" => "Create",
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
        ),
        "shopingCartReload" => true,

        "addMainSource" => array(
            1 => array(
                "fields" => array(
                    "supplier_nama" => "Vendor",
                    "dpp_nilai" => "Dasar Pengenaan pajak",
                    "ppn_nilai" => "Ppn",
//                    "nilai_entry" => "Total",
                    "harga" => "Total",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "nomer e-faktur PPN Masukan",
                    "nomer_npwp" => "nomer NPWP",
                ),
                "addFields" => array(
                    "ppn_sudah_faktur" => "ppn_final",
                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
                ),
                "editableFields" => array(
                    "supplier_nama" => "combo",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                    "nomer_npwp" => "text",
                ),
                "editProcess" => "_processPihak/addTaxDataRefillMode",
//                "enabledPpn" => "true",
//                "gateSource" => "items2_sum",
                "gateTarget" => "items2_sum",
                "gateItemCek" => "ppnPersenCheck",
                "addMainSourceShow" => false,
                "refillMode" => true,
                "refillModePpn" => "ppn_nilai",
                "pairData" => array(
                    "mdlName" => "MdlSupplier",
                    "mdlFilter" => array(),
                    "key" => "supplier_id",
                ),
            ),
//            2 => array(
//                "fields" => array(
//                    //                    "nomer" => "INV",
//                    "dpp_nilai" => "Dasar Pengenaan pajak",
//                    "ppn_nilai" => "Ppn(11%)",
////                    "nilai_entry" => "Total",
//                    "harga" => "Total",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "nomer e-faktur PPN Masukan",
////                    "skip_faktur" => "belum ada faktur",
//                ),
//                "addFields" => array(
//                    "ppn_sudah_faktur" => "ppn_final",
//                    "ppn_nilai_sudah_faktur" => "ppn_nilai",
//                ),
//                "editableFields" => array(
////                    "dpp_nilai" => "number",
//                    //                    "dpp_ppn" => "number",
//                    //                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
////                    "skip_faktur" => "checkbox",
//                ),
//                "editProcess" => "_processPihak/addTaxData",
////                "enabledPpn" => "true",
//                "gateTarget" => "items",
//                "addMainSourceShow" => false,
//            ),
        ),
        "shoppingCartEditableFieldsOptions" => array(
            1 => array(
                "ppnPersenCheck" => array(
                    "inputType" => "radio",
                    "label" => "Non PPN/PPN",
                    "processSelect" => "_processSelectProductPpn/selectItemRefill/",
                    "dataField" => array(
                        "non_ppn" => array(
                            "label" => "Non PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 0,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                        "ppn" => array(
                            "label" => "PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => 1,
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                    ),
                ),
            ),
        ),
        "shoppingCartFakturValidator" => array(
            "enabled" => true,
            "cek" => "ppnPersenCheck",
            "cekValue" => 1,
            "cekGerbangParam" => "supplier_id",
            "label" => "Vendor untuk biaya {biaya_nama} belum ditentukan. Silahkan tentukan dahulu.",
        ),
    ),

    "770" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "penambahan plafon pettycash",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "penambahan plafon pettycash",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "770",
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlCabang",
        "selectorSrcModel" => "MdlCabang",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "jenis=.cabang",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "cabang",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorProcessor" => "_processSelectPlafonPettycash/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
//            "nomer_top" => "add plafon number",
            "nomer" => "nomer",
            "oleh_nama" => "person",
            "lastPlafon" => "plafon awal",
            "addPlafon" => "jumlah penambahan",
            "newPlafon" => "plafon akhir",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
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
            "lastPlafon" => "lastPlafon",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "lastPlafon" => "lastPlafon",
                //                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "lastPlafon" => "lastPlafon",
                //                "newPlafon" => "newPlafon",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                //                "harga",
                "jml",
                "addPlafon",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "newPlafon",
            //            2 => "newPlafon",
        ),

        "shoppingCartFieldValidators" => array(
            //            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
        "shoppingCartFieldMidValidatorsComparison" => array(
            "lastPlafon" => "sumber",
            "newPlafon" => "target",
            //            "lastPlafon" => "target",
            //            "newPlafon" => "sumber",
        ),

        "shoppingCartHideSubamount" => array(
            1 => true,
        ),
        "receiptElements" => array(
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuKas",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id=placeID",
                    ),
                    "key" => "extern_id",
                    "rekening" => "1010010010",
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
//                "mdlName" => "MdlBankAccount_cash",
                "mdlFilter" => array(
//                    "cabang_id=placeID",
                    "jenis=.account_cash",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "saldo",
                ),
                "editPoints" => array(1,),
            ),
            "paymentMethod_pettycash" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash account",
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=cabang2ID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "cloner" => array(
            "srcGateName" => "items",
            "cloneLabel" => array(
                "id",
                "nama",
            ),
        ),
        "mainCloner" => array(
            "items" => array(
                "cabang2ID" => "id",
                "cabang2Name" => "nama",
            ),
            //            "items2" => array(
            //                "rek2ID" => "id",
            //                "rek2Name" => "nama",
            //            ),
        ),
        "previewCtr" => "Create",
        "pairRegistries" => array(
            "main", "items"
        ),
    ),
    "970" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "pengurangan plafon pettycash",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pengurangan plafon pettycash",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "970",
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlCabang",
        "selectorSrcModel" => "MdlCabang",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "jenis=.cabang",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "cabang",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorProcessor" => "_processSelectPlafonPettycash/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
//            "suppliers_nama" => "vendor",
//            "nomer_top" => "PO number",
            "nomer" => "nomer",
            "oleh_nama" => "person",
            "addPlafon" => "plafon awal",
            "lastPlafon" => "jumlah penambahan",
            "newPlafon" => "plafon akhir",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
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
            "lastPlafon" => "lastPlafon",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "lastPlafon" => "lastPlafon",
                "addPlafon" => "amount",
                "newPlafon" => "newPlafon",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                //                "harga",
                "jml",
                "addPlafon",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "newPlafon",
            //            2 => "newPlafon",
        ),

        "shoppingCartFieldValidators" => array(
            //            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),
        "shoppingCartFieldMidValidatorsComparison" => array(
            //            "lastPlafon" => "sumber",
            //            "newPlafon" => "target",

//            "lastPlafon" => "target",
//            "newPlafon" => "sumber",
        ),

        "shoppingCartHideSubamount" => array(
            1 => true,
        ),
        "receiptElements" => array(
            "pettycash_plafon" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash plafon",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuKas",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id=cabangID",//pihakID
                    ),
                    "key" => "extern_id",
                    "rekening" => "1010010040",//pettycash
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
                    "cabang_id=cabang2ID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
            ),
            "pettycash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pettycash account",
                "mdlName" => "MdlPettycashAccount",
                "mdlFilter" => array(
//                    "cabang_id=placeID",
                    "cabang_id=cabang2ID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
            ),
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuKas",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id=placeID",
                    ),
                    "key" => "extern_id",
                    "rekening" => "1010010010",
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
//                    "cabang_id=placeID",
                    //                    "jenis<>.pettycash",
                    //                    "jenis<>.bank",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "saldo",
                ),
                "editPoints" => array(1,),
            ),

        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "cloner" => array(
            "srcGateName" => "items",
            "cloneLabel" => array(
                "id",
                "nama",
            ),
        ),
        "mainCloner" => array(
            "items" => array(
                "cabang2ID" => "id",
                "cabang2Name" => "nama",
            ),
            //            "items2" => array(
            //                "rek2ID" => "id",
            //                "rek2Name" => "nama",
            //            ),
        ),
        "previewCtr" => "Create",
        "pairRegistries" => array(
            "main", "items"
        ),
    ),
);


