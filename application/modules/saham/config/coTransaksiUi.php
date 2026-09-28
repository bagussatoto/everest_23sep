<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion


$config["coTransaksiUi"] = array(

    //  config pemindahan rekening kas (center)
    "6666" => array(
        "icon" => "fa fa-cube",
        "label" => "perubahan kepemilikan saham",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request perubahan kepemilikan saham",
                "actionLabel" => "request perubahan kepemilikan saham",
                "source" => "",
                "target" => "6666r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi perubahan kepemilikan saham",
                "actionLabel" => "perubahan kepemilikan saham",
                "source" => "6666r",
                "target" => "6666",
                "userGroup" => "c_holding",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlDtaPemegangSaham",
        "selectorSrcModel" => "MdlDtaPemegangSaham",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
//            "bank.cabang_id=placeID",
//            "bank.jenis2=.1",
//            "bank.jenis!=.account_cash",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item rekening",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            //            "id",
            "nama",
//            "debet",
        ),
        "selectorProcessor" => "_processSelectRekening/select",
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
            "dtime" => "tanggal",
            "nomer" => "nomer transaksi",
            "oleh_nama" => "pic",
            "nilai_source" => "nilai perubahan kepemilikan saham",
            "nomor_akta_notaris" => "nomer akta notaris",
//            "cash_account_source__label" => "cash account source",
//            "cash_account__label" => "cash account target",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "nomer" => "nomer transaksi",
            "oleh_nama" => "pic",
            "nilai_source" => "nilai penambahan modal",
//            "cash_account_source__label" => "cash account source",
//            "cash_account__label" => "cash account target",
        ),
        "historyFields" => array(
            1 => array(
                "dtime" => "tanggal",
                "nomer" => "nomer transaksi",
                "oleh_nama" => "pic",
                "nilai_source" => "nilai perubahan kepemilikan saham",
                "nomor_akta_notaris" => "nomer akta notaris",
//            "cash_account_source__label" => "cash account source",
//            "cash_account__label" => "cash account target",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "dtime" => "tanggal",
                "nomer_top" => "nomer request",
                "nomer" => "nomer transaksi",
                "oleh_nama" => "pic",
                "nilai_source" => "nilai perubahan kepemilikan saham",
                "nomor_akta_notaris" => "nomer akta notaris",
//            "cash_account_source__label" => "cash account source",
//            "cash_account__label" => "cash account target",
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
//            4 => array(
//                "return" => array(
//                    "kolom" => "returned",
//                    "value" => "1",
//                    "labels" => "RETURNED",
//                    "style" => array(
//                        "bgcolor" => "orange",
//                        "color" => "black",
//                    ),
//                ),
//            ),
        ),

        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
            ),
            2 => array(
                "nama" => "nama",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "nama",
//                "satuan" => "satuan",
//                "stok" => "stok tersedia",
//                "nilai_target" => "nilai dipindahkan",
//                "jml" => "qty",
//                "sub_nilai" => "subtotal",
            ),
            2 => array(
                "nama" => "nama",
//                "satuan" => "satuan",
//                "nilai_target" => "nilai dipindahkan",
//                "jml" => "qty",
//                "sub_nilai" => "subtotal",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "saldo_awal" => "saldo awal",
                "nilai_source" => "nilai dipindahkan (-)",
                //                "jml" => "qty",
                "saldo_akhir" => "saldo akhir",
            ),
            2 => array(
                "saldo_awal" => "saldo awal",
                "nilai_source" => "nilai dipindahkan (-)",
                //                "jml" => "qty",
                "saldo_akhir" => "saldo akhir",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "saldo_awal" => "saldo awal",
                "nilai_target" => "nilai dipindahkan (+)",
                //                "jml" => "qty",
                "saldo_akhir" => "saldo akhir",
            ),
            2 => array(
                "saldo_awal" => "saldo awal",
                "nilai_target" => "nilai dipindahkan (+)",
                //                "jml" => "qty",
                "saldo_akhir" => "saldo akhir",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "nilai_source",
                "jml",
            ),
            2 => array(//                "nilai_source",
            ),
        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "nilai_target",
            ),
        ),

        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartTargetModal" => array(
            "enabled" => true,
            "label" => "Kode/Nomer Seri Bahan Baku (*)",
            "link" => "_shoppingCart/recordProduksiBahanBaku/",
            "jenisTr" => "6666",
            "key" => "nilai_target",
            "gateTarget" => "items2_sum",

            "validate" => false,
            "validateKey" => array(
                "serial_bahan_baku" => "Kode/Nomer Seri Bahan Baku {produk_nama} harus ditentukan. Silahkan ditentukan dahulu.",
            ),

        ),


        "cloner" => array(
            "srcGateName" => "items",
            "cloneLabel" => array("harga"),
        ),
        "mainCloner" => array(
            "items" => array(
                "rekID" => "id",
                "rekName" => "nama",
            ),
            "items2" => array(
                "rek2ID" => "id",
                "rek2Name" => "nama",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "nilai_source" => "nilai pemindahan belum diisikan. silahkan diisi dahulu.",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
//            "harga" => "sumber",
//            "cash_account_source__saldo" => "target",
        ),
        "autoBuildTarget" => array(
            "enabled" => true,
            "targetGate" => "items2_sum",
        ),
        "shoppingCartPairedItem" => array(
            "enabled" => false,
            "mdlName" => "MdlDtaPemegangSaham",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "shoppingCartFieldEquivalent" => array(// kedua nilai harus sama
            "enabled" => true,
            "source" => array(
                "key" => "nilai_source",
            ),
            "target" => array(
                "key" => "nilai_target",
            ),
        ),


        "receiptElements" => array(
            "modal_source" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "source account",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuModal",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                    ),
                    "key" => "extern_id",
                    "rekening" => "3010020",//new coa diganti ke coa karena ambil dari comRekeningPembantuKas
                    "fieldID" => "kredit",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlDtaPemegangSaham",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "id=rekID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "alias" => "holder alias",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
//                "labelValidate" => "Silahkan memilih sumber rekening bank yang akan dipindahkan sebelum melanjutkan transaksi.",
            ),
            "nomor_akta_notaris" => array(
                "elementType" => "dataField",
                "label" => "Nomer Akta Notaris(*)",
                "inputType" => "text",
                "defaultValue" => "",
                "editPoints" => array(1),
                "labelValidate" => "Nomer Akta Notaris harus diisi. Silahkan diisi dahulu.",
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        "pairMakers" => array(
            1 => array(
                "modal_source" => array(
                    "helperName" => "he_cek_modal_saham",
                    "functionName" => "cekModalSaham",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items",
                ),
                "modal_target" => array(
                    "helperName" => "he_cek_modal_saham",
                    "functionName" => "cekModalSaham",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
//            2 => array(
//                "stokSupplies" => array(
//                    "helperName" => "he_cek_stock_supplies_locker",
//                    "functionName" => "cekStockSuppliesLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "state" => ".active",
//                    ),
//                    "gate" => "items2_sum",
//                ),
//            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "modal_source" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "saldo_awal",
                    ),
                ),
                "modal_target" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "saldo_awal",
                    ),
                ),
            ),
//            2 => array(
//                "stokSupplies" => array(
//                    "items2_sum" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//            ),
        ),

        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "6666re",
                "label" => "EDIT request balance interchange",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "6666rrj",
                "label" => "REJECT request balance interchange",
            ),
        ),
    ),
    //  config pemindahan rekening kas
    "757" => array(
        "icon" => "fa fa-cube",
        "label" => "cash balance interchange(branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request balance interchange",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "757r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                //                "label" => "authorization",
                "label" => "balance interchange",
                "actionLabel" => "approve request",
                "source" => "757r",
                "target" => "757",
                "userGroup" => "c_holding",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlBankAccountSaldo",
        "selectorSrcModel" => "MdlBankAccountSaldo",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "accountCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlBankAccountSaldo",
            "mdlFilter" => array(
                "bank.cabang_id=placeID",
                "bank.id=rekID",
            ),
        ),
        "selectorFilters" => array(
            "bank.cabang_id=placeID",
            "bank.jenis!=.account_cash",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item rekening",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "jumlah" => "debet",
        ),
        "selectorViewedFields" => array(
            //            "id",
            "nama",
            "debet",
        ),
        "selectorProcessor" => "_processSelectRekening/select",
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
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "cash_account_source__label" => "cash account source",
            "cash_account_target__label" => "cash account target",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "cash_account_source__label" => "cash account source",
            "cash_account_target__label" => "cash account target",
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
            "nama" => "nama",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "transfer amount",
                //                "jml" => "qty",
            ),
            2 => array(
                "harga" => "transfer amount",
                //                "jml" => "qty",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "harga" => "receiving amount",
                //                "jml" => "qty",
            ),
            2 => array(
                "harga" => "receiving amount",
                //                "jml" => "qty",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        //        "shoppingCartPairedItemRecorder" => "recordPaireditem",
        //        "shoppingCartPairedItem" => array(
        //            "enabled" => true,
        //            "mdlName" => "MdlBankAccount",
        //            "mdlFilter" => array(
        //                "cabang_id=placeID",
        //                "id<>id"
        //            ),
        //            "srcKey" => "id",
        //            "srcLabel" => array("nama"),
        //        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "source value",
        ),

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

        "receiptElements" => array(
            "cash_account_source" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "account source",
                "mdlName" => "MdlBankAccountSaldo",
                "mdlFilter" => array(
                    "bank.cabang_id=placeID",
                    "bank.id=rekID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    "debet" => "balance",
                ),
                "editPoints" => array(1,),
                "noValidate" => false,
                "labelValidate" => "Silahkan memilih sumber rekening bank yang akan dipindahkan sebelum melanjutkan transaksi.",
            ),
            "cash_account_target" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "account target",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "bank.cabang_id=placeID",
                    "bank.id<>rekID",
                    "bank.jenis2=.1",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    "debet" => "balance",
                ),
                "editPoints" => array(1,),
                "noValidate" => false,
                "labelValidate" => "Silahkan memilih target rekening bank sebelum melanjutkan transaksi.",
            ),
        ),

        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
            ),
            2 => array(
                "harga",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "cloner" => array(
            "srcGateName" => "items",
            "cloneLabel" => array("harga"),
        ),
        "mainCloner" => array(
            "items" => array(
                "rekID" => "id",
                "rekName" => "nama",
            ),
            "items2" => array(
                "rek2ID" => "id",
                "rek2Name" => "nama",
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "757re",
                "label" => "EDIT request balance interchange",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "757rrj",
                "label" => "REJECT request balance interchange",
            ),
        ),
    ),

);