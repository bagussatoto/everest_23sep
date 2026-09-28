<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

$config["coTransaksiUi"] = array(
    "9999" => array(
        "icon" => "fa fa-money",
        "label" => "adjustment neraca",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request adjustment",
                "actionLabel" => "request for adjustment",
                "labelPrint" => "",
                "source" => "",
                "target" => "9999r",
                "userGroup" => "sys",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "create by",
            ),
            2 => array(
                "label" => "otorisasi adjustment",
                "actionLabel" => "approve request",
                "labelPrint" => "",
                "source" => "9999r",
                "target" => "9999",
                "userGroup" => "sys",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => false,
                "allowIncrement" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        // "template" => "template/transaksi.html",
        "selectorModel" => "MdlCabang",
        "selectorSrcModel" => "MdlCabang",
        "selectorModelTarget" => "MdlNeracaLajur",
        "selectorSrcModelTarget" => "MdlNeracaLajur",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            // "is_active=.1",
            // // "p_head_code=.6",
            // "is_rekening_pembantu=.0",
        ),

        "customPrint" => array(
            "leftElements" => array(
                "main" => array(
                    "nomer" => '',
                    "jenis_label" => '',
                    "cabang_nama" => '',
                    "oleh_nama" => '',
                    "gudang_nama" => '',
                ),
                "items" => array(),
            ),
            "rightElements" => array(),
        ),

        "selectorCaller" => "_selectorPihak/selectPihak",// bikin shopping cart background
        "selectorLabel" => "pilih cabang",
        "selectorParamFields" => array(
            "id" => "rekening",
            "nama" => "rekening_label",
            "coa_code" => "rekening",
            "coa_label" => "rekening_label",
            "debet" => "debet",
            "kredit" => "kredit",
        ),
        "selectorViewedFields" => array(
            "head_code", "head_name",
        ),
        "selectorProcessor" => "_processPihak/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(// "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            //            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",

        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(// "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "rekening_label",
            "id" => "rekening",
            "coa_code" => "rekening",
            "head_code" => "rekening",
            "debet" => "debet",
            "kredit" => "kredit",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "head_code" => "kode rekening",
                "nama" => "rekening",
            ),
            2 => array(
                "head_code" => "kode rekening",
                "nama" => "rekening",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "debet_prev" => "debet previous",
                "kredit_prev" => "kredit previous",
                "debet_curent_ori" => "debet awal",
                "kredit_curent_ori" => "kredit awal",
                "debet_adj" => "debet adj",
                "kredit_adj" => "kredit adj",
                "debet_after" => "debet akhir",
                "kredit_after" => "kredit akhir",
            ),
            2 => array(
                "debet_prev" => "debet previous",
                "kredit_prev" => "kredit previous",
                "debet_curent_ori" => "debet awal",
                "kredit_curent_ori" => "kredit awal",
                "debet_adj" => "debet adj",
                "kredit_adj" => "kredit adj",
                "debet_after" => "debet akhir",
                "kredit_after" => "kredit akhir",
            ),
        ),

        "shoppingCartNoteEnabled" => false,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "nilai_penyesuaian",
                "kredit",
                "debet",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            // 1 => "jml*harga",
            // 2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartFieldValidators" => array(
            // "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ",
            //            "pihakName" => "cabang",
        ),
        "shoppingCartPairedItemRecorder" => "recordPairedItemOther",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlAccounts",
            "srcKey" => "head_code",
            "srcLabel" => array("head_name"),
            "mdlFilter" => array(
                "p_head_code=head_code"
            ),
            "targetGateName" => "items2_sum",
        ),
        "shoppingCartFieldPairedSrc" => array(
            "nama" => "head_name",
            "id" => "extern_id",
            "head_code" => "head_code",
            "head_name" => "head_name",
            "p_head_code" => "p_head_code",
        ),
        "shopingCartSubDetail" => array(
            "extern_nama" => "rekening",
            "debet" => "debet",
            "kredit" => "kredit",
        ),
        "shopingCartSubDetailGate" => "items7_sum",
        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
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
            //            "cash_account"  => array(
            //                "elementType" => "dataModel",
            //                "inputType"   => "radio",
            //                "label"       => "account used to pay",
            //                "mdlName"     => "MdlBankAccountSaldo",
            //                "mdlFilter"   => array(
            //                    "bank.cabang_id=placeID",
            //                ),
            //                "key"         => "id",
            //                "labelSrc"    => "nama",
            //                "usedFields"  => array(
            //                    "nama"  => "account number",
            //                    "alias" => "holder alias",
            //                    "debet" => "balance",
            //                ),
            //                "editPoints"  => array(1,),
            //            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4675re",
                "label" => "EDIT request biaya",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4675rrj",
                "label" => "REJECT request biaya",
            ),
        ),
        "pairMakers" => array(
            // 1 => array(
            //     "rekeningAwal" => array(
            //         "helperName" => "he_cek_rekening_saldo",
            //         "functionName" => "cekRekeningSaldoItems",
            //         "params" => array(
            //             "cabang_id" => "pihakID",
            //             "rekening"=>"head_code",
            //         ),
            //     ),
            // ),
            // 2 => array(
            //     "stokProduk" => array(
            //         "helperName" => "he_cek_stock_produk_locker",
            //         "functionName" => "cekStockProdukLocker",
            //         "params" => array(
            //             "cabang_id" => "placeID",
            //             "gudang_id" => "gudangID",
            //             "state" => ".active",
            //             "jenis" => ".produk",
            //         ),
            //     ),
            //     "stokProduk_center" => array(
            //         "helperName" => "he_cek_stock_produk_locker",
            //         "functionName" => "cekStockProdukLocker",
            //         "params" => array(
            //             "cabang_id" => ".-1",
            //             "gudang_id" => ".-1",
            //             "state" => ".active",
            //             "jenis" => ".produk",
            //         ),
            //     ),
            //     "dataProduk" => array(
            //         "helperName" => "he_pair_data_produk",
            //         "functionName" => "cekPairDataProduk",
            //         "params" => array(
            //             //                        "cabang_id" => ".-1",
            //             //                        "gudang_id" => ".-1",
            //             //                        "state" => ".active",
            //         ),
            //         "kolom" => array(
            //             "no_part",
            //         ),
            //     ),
            // ),

        ),
        "pairInjectors" => array(
            // 1 => array(
            //     "rekeningAwal" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "saldo_rekening_before",
            //         ),
            //     ),
            //
            // ),
            // 2 => array(
            //     "stokProduk" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok",
            //         ),
            //     ),
            //     "stokProduk_center" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok_center",
            //         ),
            //     ),
            // ),
            // 3 => array(
            //     "stokProduk" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok",
            //         ),
            //     ),
            //     "stokProduk_center" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok_center",
            //         ),
            //     ),
            // ),
            // 4 => array(
            //     "stokProduk" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok",
            //         ),
            //     ),
            //     "stokProduk_center" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok_center",
            //         ),
            //     ),
            // ),
        ),

        /*
         * untuk bagian shopingcart karena berbeda dengan yang reguler langsung di jembreng
         */
        "shopingCartMasterFiled" => array(
            ""
        ),
        "shopingCartJurnalEditable" => array(
            "adj" => array("debet_adj", "kredit_adj")
        ),
    ),

    "9990" => array(
        "icon" => "fa fa-money",
        "label" => "jurnal umum",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request jurnal umum",
                "actionLabel" => "request jurnal umum",
                "labelPrint" => "",
                "source" => "",
                "target" => "9990r",
                "userGroup" => "c_finance",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "create by",
            ),
            2 => array(
                "label" => "otorisasi jurnal umum",
                "actionLabel" => "approve jurnal umum",
                "labelPrint" => "",
                "source" => "9990r",
                "target" => "9990",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => false,
                "allowIncrement" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        // "template" => "template/transaksi.html",
        "selectorModel" => "MdlCabang",
        "selectorSrcModel" => "MdlCabang",
        "selectorModelTarget" => "MdlNeracaLajur",
        "selectorSrcModelTarget" => "MdlNeracaLajur",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            // "is_active=.1",
            // // "p_head_code=.6",
            // "is_rekening_pembantu=.0",
        ),

        "customPrint" => array(
            "leftElements" => array(
                "main" => array(
                    "nomer" => '',
                    "jenis_label" => '',
                    "cabang_nama" => '',
                    "oleh_nama" => '',
                    "gudang_nama" => '',
                ),
                "items" => array(),
            ),
            "rightElements" => array(),
        ),

        "selectorCaller" => "_selectorPihak/selectPihak",// bikin shopping cart background
        "selectorLabel" => "pilih cabang",
        "selectorParamFields" => array(
            "id" => "rekening",
            "nama" => "rekening_label",
            "coa_code" => "rekening",
            "coa_label" => "rekening_label",
            "debet" => "debet",
            "kredit" => "kredit",
        ),
        "selectorViewedFields" => array(
            "head_code", "head_name",
        ),
        "selectorProcessor" => "_processPihak/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(// "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            //            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",

        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(// "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "rekening_label",
            "id" => "rekening",
            "coa_code" => "rekening",
            "head_code" => "rekening",
            "debet" => "debet",
            "kredit" => "kredit",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "head_code" => "kode rekening",
                "nama" => "rekening",
            ),
            2 => array(
                "head_code" => "kode rekening",
                "nama" => "rekening",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "debet_prev" => "debet previous",
                "kredit_prev" => "kredit previous",
                "debet_curent_ori" => "debet awal",
                "kredit_curent_ori" => "kredit awal",
                "debet_adj" => "debet adj",
                "kredit_adj" => "kredit adj",
                "debet_after" => "debet akhir",
                "kredit_after" => "kredit akhir",
            ),
            2 => array(
                "debet_prev" => "debet previous",
                "kredit_prev" => "kredit previous",
                "debet_curent_ori" => "debet awal",
                "kredit_curent_ori" => "kredit awal",
                "debet_adj" => "debet adj",
                "kredit_adj" => "kredit adj",
                "debet_after" => "debet akhir",
                "kredit_after" => "kredit akhir",
            ),
        ),

        "shoppingCartNoteEnabled" => false,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "nilai_penyesuaian",
                "kredit",
                "debet",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            // 1 => "jml*harga",
            // 2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartFieldValidators" => array(
            // "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ",
            //            "pihakName" => "cabang",
        ),
        "shoppingCartPairedItemRecorder" => "recordPairedItemOther",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlAccounts",
            "srcKey" => "head_code",
            "srcLabel" => array("head_name"),
            "mdlFilter" => array(
                "p_head_code=head_code"
            ),
            "targetGateName" => "items2_sum",
        ),
        "shoppingCartFieldPairedSrc" => array(
            "nama" => "head_name",
            "id" => "extern_id",
            "head_code" => "head_code",
            "head_name" => "head_name",
            "p_head_code" => "p_head_code",
        ),
        "shopingCartSubDetail" => array(
            "extern_nama" => "rekening",
            "debet" => "debet",
            "kredit" => "kredit",
        ),
        "shopingCartSubDetailGate" => "items7_sum",
        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "cabangTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",//hidden
                "label" => "cabang",
                "mdlName" => "MdlCabang",
//                "mdlFilter" => array("cabang_id=pihakID"),
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
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
            //            "cash_account"  => array(
            //                "elementType" => "dataModel",
            //                "inputType"   => "radio",
            //                "label"       => "account used to pay",
            //                "mdlName"     => "MdlBankAccountSaldo",
            //                "mdlFilter"   => array(
            //                    "bank.cabang_id=placeID",
            //                ),
            //                "key"         => "id",
            //                "labelSrc"    => "nama",
            //                "usedFields"  => array(
            //                    "nama"  => "account number",
            //                    "alias" => "holder alias",
            //                    "debet" => "balance",
            //                ),
            //                "editPoints"  => array(1,),
            //            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4675re",
                "label" => "EDIT request biaya",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4675rrj",
                "label" => "REJECT request biaya",
            ),
        ),
        "pairMakers" => array(
            // 1 => array(
            //     "rekeningAwal" => array(
            //         "helperName" => "he_cek_rekening_saldo",
            //         "functionName" => "cekRekeningSaldoItems",
            //         "params" => array(
            //             "cabang_id" => "pihakID",
            //             "rekening"=>"head_code",
            //         ),
            //     ),
            // ),
            // 2 => array(
            //     "stokProduk" => array(
            //         "helperName" => "he_cek_stock_produk_locker",
            //         "functionName" => "cekStockProdukLocker",
            //         "params" => array(
            //             "cabang_id" => "placeID",
            //             "gudang_id" => "gudangID",
            //             "state" => ".active",
            //             "jenis" => ".produk",
            //         ),
            //     ),
            //     "stokProduk_center" => array(
            //         "helperName" => "he_cek_stock_produk_locker",
            //         "functionName" => "cekStockProdukLocker",
            //         "params" => array(
            //             "cabang_id" => ".-1",
            //             "gudang_id" => ".-1",
            //             "state" => ".active",
            //             "jenis" => ".produk",
            //         ),
            //     ),
            //     "dataProduk" => array(
            //         "helperName" => "he_pair_data_produk",
            //         "functionName" => "cekPairDataProduk",
            //         "params" => array(
            //             //                        "cabang_id" => ".-1",
            //             //                        "gudang_id" => ".-1",
            //             //                        "state" => ".active",
            //         ),
            //         "kolom" => array(
            //             "no_part",
            //         ),
            //     ),
            // ),

        ),
        "pairInjectors" => array(
            // 1 => array(
            //     "rekeningAwal" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "saldo_rekening_before",
            //         ),
            //     ),
            //
            // ),
            // 2 => array(
            //     "stokProduk" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok",
            //         ),
            //     ),
            //     "stokProduk_center" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok_center",
            //         ),
            //     ),
            // ),
            // 3 => array(
            //     "stokProduk" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok",
            //         ),
            //     ),
            //     "stokProduk_center" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok_center",
            //         ),
            //     ),
            // ),
            // 4 => array(
            //     "stokProduk" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok",
            //         ),
            //     ),
            //     "stokProduk_center" => array(
            //         "items" => array(
            //             "targetKey" => "id",
            //             "targetColumn" => "stok_center",
            //         ),
            //     ),
            // ),
        ),

        /*
         * untuk bagian shopingcart karena berbeda dengan yang reguler langsung di jembreng
         */
        "shopingCartMasterFiled" => array(
            ""
        ),
        "shopingCartJurnalEditable" => array(
            "adj" => array("debet_adj", "kredit_adj")
        ),
    ),

);