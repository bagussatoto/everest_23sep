<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion


$config["coTransaksiUi"] = array(
    //hutang bank
    "444" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "penambahan hutang bank",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REQUEST PENAMBAHAN HUTANG",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "444r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "OTORISASI PENAMBAHAN HUTANG",
                "actionLabel" => "undo/reject/approve",
                "buttonLabel" => "approve",
                "source" => "444r",
                "target" => "444",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "Approved",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "allowEdit" => true,
                "allowJoin" => true,
            ),

        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlBank",
        "selectorSrcModel" => "MdlBank",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProdukPerSupplier",
            //            "mdlFilter" => array("suppliers_id=pihakID"),
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "jenis2=.1",
            //            "trash=.0",
            //            "cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih bank",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(),
        "selectorProcessor" => "_processSelectBank/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett" => "total amount",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "bank",
                "nomer_top" => "PRE PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                //                "disc" => "discount",
                //                // "nett1" => "sub amount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",
                "print_nvalas" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "bank",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",
                "print_nvalas" => "tool",
            ),

        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array("print_label" => "nomer"),
            4 => array("print_label" => "nomer"),
        ),
        "compactHistoryFields" => array(
            "suppliers_nama" => "bank",
            "transaksi_nilai" => "amount",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "bank",
            //            "customers_nama" => "customer",
            "nomer_top" => "Req PO number",
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),

        "selectorFields" => array("id", "nama",),
        "pihakFields" => array("id", "nama"),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => false,
        ),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",

        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "bank name",
                //                "jml" => "Qty",

            ),
            2 => array(
                "nama" => "bank name",
                //                "jml" => "Qty",
            ),

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Total hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
            ),
            2 => array(
                "harga" => "Total hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "persen_bunga",
                //                "jml",
                //                "produk_ord_jml",
            ),
            2 => array(
                "harga",
                "persen_bunga",
                //                "jml",
                //                "produk_ord_jml",
            ),

        ),
        "shoppingCartAmountValue" => array(
            1 => "(jml*harga)",// hpp
            2 => "(jml*harga)",// hpp

        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "total hutang",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            //            "harga"               => "sumber",
            //            "plafonDetail__saldo" => "target",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                //                "harga" => "Total Amount",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            //            3 => array(
            //                "harga" => "Total Amount",
            //                // "ppv" => "index",
            //                "ppn" => "VAT",
            //                "hpp_nppn" => "Grand Total",
            //            ),
        ),
        "allowedMainEdit" => array("1", "2"),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "awal_pinjaman" => array(
                "elementType" => "dataField",
                "label" => "tgl awal pinjaman",
                "inputType" => "date",
                "defaultValue" => date('Y-m-d'),
                "editPoints" => array(1, 2),
            ),
            "jatuh_tempo" => array(
                "elementType" => "dataField",
                "label" => "tgl berakhir pinjaman",
                "inputType" => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(1, 2),
            ),
            "tgl_angsuran" => array(
                "elementType" => "dataField",
                "label" => "jatuh tempo",
                "inputType" => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(1, 2),
            ),
            "angsuran" => array(
                "elementType" => "dataField",
                "label" => "angsuran",
                "inputType" => "text",
                "defaultValue" => "",
                "editPoints" => array(1, 2),
            ),
            //            "plafonDetail" => array(
            //                "elementType" => "dataModel",
            //                "inputType"   => "radio",
            //                "label"       => "plafon",
            //                "pairedModel" => array(
            //                    "mdlName"    => "ComLockerValue",
            //                    "mdlMethod"  => "fetchBalances",
            //                    "mdlFilter"  => array(
            //                        "cabang_id" => "placeID",
            //                        "state"     => ".active",
            //                    ),
            //                    "key"        => "produk_id",
            //                    "rekening"   => "hutang bank",
            //                    "fieldID"    => "nilai",
            //                    "fieldLabel" => "saldo",
            //                ),
            //                "mdlName"     => "MdlRekeningKoran",
            //                "mdlFilter"   => array(
            //                    "cabang_id=placeID",
            //                    "id=pihakID",
            //                ),
            //                "key"         => "id",
            //                "labelSrc"    => "nama",
            //                "usedFields"  => array(
            //                    "nama"  => "account",
            //                    "saldo" => "balance",
            //                ),
            //                "editPoints"  => array(1,),
            //                "noValidate"  => true,
            //            ),
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
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    // "folders<>pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "saldo" => "balance",
                ),
                "editPoints" => array(1, 2,),
                "noValidate" => false,
                "labelValidate" => "Silahkan memilih rekening bank sebelum melanjutkan transaksi.",
            ),

        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "combo",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //
                //
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount_out",
                //
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //
                //
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
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
        "addMainStep" => array(
            //            "489" => array(
            //                "jenis_master" => "467",
            //                "jenis" => "467",
            //                "target" => "489",
            //                "status_4" => "1",
            //                "trash_4" => "0",
            //            ),
        ),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "harga" => "DPP",
                    "ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            4 => true,
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "444re",
                "label" => "EDIT REQUEST PENAMBAHAN HUTANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "444rrj",
                "label" => "REJECT REQUEST PENAMBAHAN HUTANG",
            ),
        ),
    ),
    "447" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "penambahan hutang ke pihak lain",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REQUEST PENAMBAHAN HUTANG KE PIHAK LAIN",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "447r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "OTORISASI PENAMBAHAN HUTANG KE PIHAK LAIN",
                "actionLabel" => "undo/reject/approve",
                "buttonLabel" => "approve",
                "source" => "447r",
                "target" => "447",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "Approved",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "allowEdit" => true,
                "allowJoin" => true,
            ),

        ),
        "template" => "template/transaksi_nopihak_add.html",
        //        "template" => "template/transaksi.html",
        "selectorModel" => "MdlDtaHutangPihak3",
        "selectorSrcModel" => "MdlDtaHutangPihak3",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "employee_type=.hpl",//untuk pihak lain
            "trash=.0",
            //            "cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih pihak lain",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(),
        //        "selectorProcessor" => "_processSelectBank/select",
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "name" => "kreditur",
            "nomer_top" => "Loan Number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett" => "total amount",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "bank",
                "nomer_top" => "PRE PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                //                "disc" => "discount",
                //                // "nett1" => "sub amount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",
                "print_nvalas" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "bank",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",
                "print_nvalas" => "tool",
            ),

        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array("print_label" => "nomer"),
            4 => array("print_label" => "nomer"),
        ),
        "compactHistoryFields" => array(
            "suppliers_nama" => "bank",
            "transaksi_nilai" => "amount",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "bank",
            //            "customers_nama" => "customer",
            "nomer_top" => "Req PO number",
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),

        "selectorFields" => array("id", "nama",),
        "pihakFields" => array("id", "nama"),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => false,
        ),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",

        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "name",
            ),
            2 => array(
                "nama" => "name",
            ),

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
                //                "nilai_bunga" => "nilai bunga<br>(bulanan)",
            ),
            2 => array(
                "harga" => "Hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
                //                "nilai_bunga" => "nilai bunga<br>(bulanan)",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "persen_bunga",
            ),
            2 => array(
                "harga",
                "persen_bunga",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "(jml*harga)",// hpp
            2 => "(jml*harga)",// hpp

        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "hutang",
            "persen_bunga" => "bunga",
        ),
        "shoppingCartRowValidators" => array(
            //            "pihakID" => "vendor ID",
            //            "pihakName" => "vendor name",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            //            "harga" => "sumber",
            //            "plafonDetail__saldo" => "target",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Grand total",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga" => "Grand total",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            //            3 => array(
            //                "harga" => "Total Amount",
            //                // "ppv" => "index",
            //                "ppn" => "VAT",
            //                "hpp_nppn" => "Grand Total",
            //            ),
        ),
        "allowedMainEdit" => array("1", "2"),
        "pairRegistries" => array(
            "tableIn_master_values",
            "main",
            "items",
        ),
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
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "folders<>pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "saldo" => "balance",
                ),
                "editPoints" => array(1, 2,),
                "noValidate" => false,
                "labelValidate" => "Silahkan memilih rekening bank sebelum melanjutkan transaksi.",
            ),
            "jatuh_tempo" => array(
                "elementType" => "dataField",
                "label" => "jatuh tempo",
                "inputType" => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(1, 2),
            ),
            //            "bunga_pinjaman" =>array(
            //                "elementType" => "dataField",
            //                "label" => "bunga pinjaman(%)",
            //                "inputType" => "text",
            //                "defaultValue" => "15",
            //                "editPoints" => array(1, 2),
            //            ),

        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "combo",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //
                //
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount_out",
                //
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //
                //
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
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
        "addMainStep" => array(
            //            "489" => array(
            //                "jenis_master" => "467",
            //                "jenis" => "467",
            //                "target" => "489",
            //                "status_4" => "1",
            //                "trash_4" => "0",
            //            ),
        ),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "harga" => "DPP",
                    "ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            4 => true,
        ),
        "addData" => array(
            2 => array(
                "MdlNameMain" => "DComData",
                "MdlNameChild" => "MdlSetupBungaPihak3",
                "gate" => "items",
                "fieldName" => array(
                    "extern_id" => "id",//diisi vendor /pihak
                    "extern_nama" => "nama",//pihak nama
                    "extern_value" => "harga",//nilai
                    "extern_value_2" => "persen_bunga",//bunga
                    "repeat" => "jatuh_tempo",//bunga
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "447re",
                "label" => "EDIT REQUEST PENAMBAHAN HUTANG KE PIHAK LAIN",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "447rrj",
                "label" => "REJECT REQUEST PENAMBAHAN HUTANG KE PIHAK LAIN",
            ),
        ),
    ),
    //penambahan modal dari pemegang saham
    "445" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "penambahan modal pemegang saham",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REQUEST PENAMBAHAN MODAL",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "445r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "OTORISASI PENAMBAHAN MODAL",
                "actionLabel" => "undo/reject/approve",
                "buttonLabel" => "approve",
                "source" => "445r",
                "target" => "445",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "Approved",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "allowEdit" => true,
                "allowJoin" => true,
            ),

        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlDtaPemegangSaham",
        "selectorSrcModel" => "MdlDtaPemegangSaham",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "employee_type=.msd",
            "trash=.0",
            //            "cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih pemegang saham",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(),
        //        "selectorProcessor" => "_processSelectBank/select",
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett" => "total amount",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "bank",
                "nomer_top" => "PRE PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                //                "disc" => "discount",
                //                // "nett1" => "sub amount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",
                "print_nvalas" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "bank",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",
                "print_nvalas" => "tool",
            ),

        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array("print_label" => "nomer"),
            4 => array("print_label" => "nomer"),
        ),
        "compactHistoryFields" => array(
            "suppliers_nama" => "bank",
            "transaksi_nilai" => "amount",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "bank",
            //            "customers_nama" => "customer",
            "nomer_top" => "Req PO number",
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),

        "selectorFields" => array("id", "nama",),
        "pihakFields" => array("id", "nama"),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => true,
            3 => true,
            4 => false,
        ),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",

        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "name",
                //                "jml" => "Qty",

            ),
            2 => array(
                "nama" => "name",
                //                "jml" => "Qty",
            ),

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Setor modal",
            ),
            2 => array(
                "harga" => "Setor modal",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                //                "jml",
                //                "produk_ord_jml",
            ),
            2 => array(
                "harga",
                //                "jml",
                //                "produk_ord_jml",
            ),

        ),
        "shoppingCartAmountValue" => array(
            1 => "(jml*harga)",// hpp
            2 => "(jml*harga)",// hpp

        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "total modal",
        ),
        "shoppingCartRowValidators" => array(
            //            "pihakID" => "vendor ID",
            //            "pihakName" => "vendor name",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            //            "harga" => "sumber",
            //            "plafonDetail__saldo" => "target",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Grand total",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            1 => array(
                "harga" => "Grand total",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            //            3 => array(
            //                "harga" => "Total Amount",
            //                // "ppv" => "index",
            //                "ppn" => "VAT",
            //                "hpp_nppn" => "Grand Total",
            //            ),
        ),
        "allowedMainEdit" => array("1", "2"),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
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
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "folders<>pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "saldo" => "balance",
                ),
                "editPoints" => array(1, 2,),
                "noValidate" => true,
            ),

        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "combo",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //
                //
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount_out",
                //
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //
                //
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
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
        "addMainStep" => array(
            //            "489" => array(
            //                "jenis_master" => "467",
            //                "jenis" => "467",
            //                "target" => "489",
            //                "status_4" => "1",
            //                "trash_4" => "0",
            //            ),
        ),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "harga" => "DPP",
                    "ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            4 => true,
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "445re",
                "label" => "EDIT REQUEST PENAMBAHAN MODAL",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "445rrj",
                "label" => "REJECT REQUEST PENAMBAHAN MODAL",
            ),
        ),
    ),
    //penambahan hutang kepemegang saham
    "446" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "penambahan hutang ke pemegang saham",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REQUEST PENAMBAHAN HUTANG KE PEMEGANG SAHAM",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "446r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "OTORISASI PENAMBAHAN HUTANG KE PEMEGANG SAHAM",
                "actionLabel" => "undo/reject/approve",
                "buttonLabel" => "approve",
                "source" => "446r",
                "target" => "446",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "Approved",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "allowEdit" => true,
                "allowJoin" => true,
            ),

        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlDtaPemegangSaham",
        "selectorSrcModel" => "MdlDtaPemegangSaham",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "employee_type=.msd",
            "trash=.0",
            //            "cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih pemegang saham",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama" => "nama",
            "npwp" => "npwp",
        ),
        //        "selectorProcessor" => "_processSelectBank/select",
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            //            "name" => "kreditur",
            "nomer_top" => "Loan Number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett" => "total amount",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "bank",
                "nomer_top" => "PRE PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                //                "disc" => "discount",
                //                // "nett1" => "sub amount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",
                "print_nvalas" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "bank",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",
                "print_nvalas" => "tool",
            ),

        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array("print_label" => "nomer"),
            4 => array("print_label" => "nomer"),
        ),
        "compactHistoryFields" => array(
            "suppliers_nama" => "bank",
            "transaksi_nilai" => "amount",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "bank",
            //            "customers_nama" => "customer",
            "nomer_top" => "Req PO number",
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "selectorFields" => array("id", "nama",),
        "pihakFields" => array("id", "nama"),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => false,
        ),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",

        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "name",
            ),
            2 => array(
                "nama" => "name",
            ),

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
                //                "nilai_bunga" => "nilai bunga<br>(bulanan)",
            ),
            2 => array(
                "harga" => "Hutang",
                "persen_bunga" => "bunga(%)<br>(tahunan)",
                //                "nilai_bunga" => "nilai bunga<br>(bulanan)",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartShowScheme" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "persen_bunga",
            ),
            2 => array(
                "harga",
                "persen_bunga",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "(jml*harga)",// hpp
            2 => "(jml*harga)",// hpp

        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "hutang",
            "persen_bunga" => "persen_bunga",
        ),
        "shoppingCartRowValidators" => array(
            //            "pihakID" => "vendor ID",
            //            "pihakName" => "vendor name",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            //            "harga" => "sumber",
            //            "plafonDetail__saldo" => "target",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Grand total",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga" => "Grand total",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            //            3 => array(
            //                "harga" => "Total Amount",
            //                // "ppv" => "index",
            //                "ppn" => "VAT",
            //                "hpp_nppn" => "Grand Total",
            //            ),
        ),
        "allowedMainEdit" => array("1", "2"),
        "pairRegistries" => array(
            "tableIn_master_values",
            "main",
            "items",
        ),
        "receiptElements" => array(
            "awal_pinjaman" => array(
                "elementType" => "dataField",
                "label" => "tgl awal pinjaman",
                "inputType" => "date",
                "defaultValue" => date('Y-m-d'),
                "editPoints" => array(1, 2),
            ),
            "jatuh_tempo" => array(
                "elementType" => "dataField",
                "label" => "tgl berakhir pinjaman",
                "inputType" => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(1, 2),
            ),
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
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "folders<>pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "saldo" => "balance",
                ),
                "editPoints" => array(1, 2,),
                "noValidate" => false,
                "labelValidate" => "Silahkan memilih rekening bank sebelum melanjutkan transaksi.",
            ),
            //            "bunga_pinjaman" =>array(
            //                "elementType" => "dataField",
            //                "label" => "bunga pinjaman(%)",
            //                "inputType" => "text",
            //                "defaultValue" => "15",
            //                "editPoints" => array(1, 2),
            //            ),

        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "combo",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //
                //
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount_out",
                //
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //
                //
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "term of payment",
                        "mdlName" => "MdlTop",
                        "mdlFilter" => array(),
                        "key" => "kode",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
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
        "addMainStep" => array(
            //            "489" => array(
            //                "jenis_master" => "467",
            //                "jenis" => "467",
            //                "target" => "489",
            //                "status_4" => "1",
            //                "trash_4" => "0",
            //            ),
        ),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "harga" => "DPP",
                    "ppn" => "PPN",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            4 => true,
        ),
        "addData" => array(
            2 => array(
                "MdlNameMain" => "DComData",
                "MdlNameChild" => "MdlSetupLoanInterest",
                "gate" => "items",
                "fieldName" => array(
                    "extern_id" => "id",//diisi vendor /pihak
                    "transaksi_id" => "transaksi_id",//transaksi id
                    "nomer" => "nomer",//transaksi nomor
                    "extern_nama" => "nama",//pihak nama
                    "extern_value" => "harga",//nilai
                    "extern_value_2" => "persen_bunga",//bunga
                    //                    "repeat" => ".01",//fixed
                    "awal_pinjaman" => "awal_pinjaman",//bunga
                    "jatuh_tempo" => "jatuh_tempo",//bunga
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "446re",
                "label" => "EDIT REQUEST PENAMBAHAN HUTANG KE PEMEGANG SAHAM",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "446rrj",
                "label" => "REJECT REQUEST PENAMBAHAN HUTANG KE PEMEGANG SAHAM",
            ),
        ),
    ),
    //  config pemindahan rekening kas (center)
    "1757" => array(
        "icon" => "fa fa-cube",
        "label" => "cash balance interchange(pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request balance interchange",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "1757r",
                //                "userGroup" => "c_finance",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi request",
                "actionLabel" => "approve request",
                "source" => "1757r",
                "target" => "1757",
                //                "userGroup" => "c_finance",
                "userGroup" => "c_holding",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlBankAccountSaldo",
        "selectorSrcModel" => "MdlBankAccountSaldo",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
//            "bank.cabang_id=placeID",
            "_rek_pembantu_subkas_cache.cabang_id=placeID",
            "bank.jenis2=.1",
//            "cabang_id=placeID",
//            "bank.jenis!=.account_cash",
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
            "folders_nama",
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
            "cash_account__label" => "cash account target",
            "description" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "dtime" => "date",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "harga" => "amount",
//                "reference_nomer" => "reference number",
                "cash_account_source__label" => "cash account source",
                "cash_account__label" => "cash account target",
                "description" => "keterangan",
            ),
            2 => array(
                "dtime" => "date",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "harga" => "amount",
//                "reference_nomer" => "reference number",
                "cash_account_source__label" => "cash account source",
                "cash_account__label" => "cash account target",
                "description" => "keterangan",
            ),
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "cash_account_source__label" => "cash account source",
            "cash_account__label" => "cash account target",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "folders_nama" => "bank",
                "nama" => "source account",
            ),
            2 => array(
                "folders_nama" => "bank",
                "nama" => "source account",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "folders_nama" => "folders_nama",
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
        "shoppingCartNoteEnabled" => false,
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
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
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
            "harga" => "nilai pemindahan belum diisikan. silahkan diisi dahulu.",
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            "harga" => "sumber",
            "cash_account_source__saldo" => "target",
        ),
        "receiptElements" => array(
            "cash_account_source" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "source account",
                "pairedModel" => array(
                    "mdlName" => "ComRekeningPembantuKas",
                    "mdlMethod" => "fetchBalances",
                    "mdlFilter" => array(
                        "cabang_id" => "placeID",
                    ),
                    "key" => "extern_id",
                    // "rekening" => "010101",//old coa diganti ke coa karena ambil dari comRekeningPembantuKas
                    "rekening" => "1010010010",//new coa diganti ke coa karena ambil dari comRekeningPembantuKas
                    "fieldID" => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount",
                "mdlFilter" => array(
//                    "cabang_id=placeID",
                    "id=rekID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
                "usedFields" => array(
                    "folders_nama" => "BANK",
                    "nama" => "account",
                    "alias" => "holder alias",
                    "saldo" => "balance",
                ),
                "editPoints" => array(1,),
                "labelValidate" => "Silahkan memilih sumber rekening bank yang akan dipindahkan sebelum melanjutkan transaksi.",
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
//                    "cabang_id=placeID",
                    "id<>rekID",
                    "jenis2=.1",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
                "usedFields" => array(
                    "folders_nama" => "BANK",
                    "nama" => "account",
                ),
                "editPoints" => array(1,),
                "labelValidate" => "Silahkan memilih target rekening bank sebelum melanjutkan transaksi.",
            ),

//            "referensi_nomer" => array(
//                "elementType" => "dataModel",
//                "inputType" => "combo",
//                "label" => "Referensi Nomer SO",
////                "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
//                "mdlName" => "MdlTransaksiData",//ini klonengan mdltransaksi
//                "key" => "id",
//                "mdlFilter" => array(
//                    "jenis=.5822so",
//                    "link_id=.0",
//                ),
//                "labelSrc" => "nomer",
//                "usedFields" => array(
//                    "id_master" => "mid",
//                    "id" => "referensi order",
//                    "fulldate" => "tgl order",
//                    "nomer" => "nomer order",
//                    "customers_id" => "id konsumen",
//                    "customers_nama" => "nama konsumen",
//                ),
//                "editPoints" => array(1,),
//                "labelValidate" => "Silahkan memilih SO yang sudah disetujui sesuai Konsumen.",
//                "noValidate" => true,
//            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1757re",
                "label" => "EDIT request balance interchange",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1757rrj",
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
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
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
                "labelSrcFields" => array(
                    "folders_nama", "nama", "alias",
                ),
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
    //penambahan plafon hutang bank
    "4470" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "penambahan plafon rekening koran",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "penambahan plafon rekening koran",
                "actionLabel" => "make plafon",
                "source" => "",
                "target" => "4470",
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
        ),
        //        "template" => "application/template/transaksi.html",
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlRekeningKoran",
        "selectorSrcModel" => "MdlRekeningKoran",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "jenis=.rekening_koran",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih bank",
        "selectorParamFields" => array(
            "id" => "id",
            // "nama" => "nama",
            // "lastPlafon" => "lastPlafon",
            // "newPlafon" => "newPlafon",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorProcessor" => "_processSelectPlafonHutangBank/select",
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
            "dtime" => "date",
            "cabang_nama" => "branch",
            "nomer_top" => "add plafon number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
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
        "receiptElements" => array(),
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
    ),
    //pengurangan plafon hutang bank
    "4970" => array(
        "icon" => "fa fa-cart-arrow-down",
        //         "label"                => "pengurangan plafon hutang bank",
        "label" => "pengurangan plafon rekening koran",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "reduction of plafon hutang bank",
                "actionLabel" => "make plafon hutang bank",
                "source" => "",
                "target" => "4970",
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
        ),
        //        "template" => "application/template/transaksi.html",
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlRekeningKoran",
        "selectorSrcModel" => "MdlRekeningKoran",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih bank",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            // "lastPlafon" => "lastPlafon",
            // "newPlafon" => "newPlafon",
        ),
        "selectorViewedFields" => array(
            "nama",
            // "lastPlafon" => "lastPlafon",
            // "newPlafon" => "newPlafon",
        ),
        "selectorProcessor" => "_processSelectPlafonHutangBank/select",
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
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
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
            "lastPlafon" => "target",
            "newPlafon" => "sumber",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
        ),
        "receiptElements" => array(),
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
    ),
    //--------- ke atas sudah modul ---------------------------


);