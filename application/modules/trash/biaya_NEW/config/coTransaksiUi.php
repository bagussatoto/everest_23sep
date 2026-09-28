<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion


$config["coTransaksiUi"] = array(
//pembiayaan supplies by nota belum fix belum ada stok
    "2676" => array(
        "icon" => "fa fa-money",
        "label" => "Otorisasi biaya produksi",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya produksi ",
                "actionLabel" => "request for expense",
                "source" => "",
                "target" => "2676r",
                "userGroup" => "sys",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "authorization biaya produksi",
                "actionLabel" => "approve request",
                "source" => "2676r",
                "target" => "2676",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlDtaBiayaProduksi",
        "selectorSrcModel" => "MdlDtaBiayaProduksi",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya produksi",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "nomer_top" => "request number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                //                "nomer_top" => "request number",
                "nomer" => "request number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "nomer_top" => "request number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
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
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

        "receiptElements" => array(
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
        // ini default tadinya off.
        // sekarang di-on-kan untuk mendapatkan pairingan dengan kategory biaya solo
        // (quality, direct labour, delivery cost).
        "pairMakers" => array(
            2 => array(
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items",
                ),
            ),
        ),
        "pairInjectors" => array(
            2 => array(
                "preBiaya" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "costName",
                    ),
                ),
            ),
        ),

        "revertException" => true,
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2676re",
                "label" => "EDIT request biaya produksi",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2676rrj",
                "label" => "REJECT request biaya produksi",
            ),
        ),
    ),//done

    //otorisasi biaya produksi
    "7762" => array(
//        "transaksiMode" => "forward",
        "icon" => "fa fa-circle",
        "label" => "pembiayaan supplies",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pembiayaan supplies",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "7762r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
//                "runCoreAkunting" => false,
            ),
            2 => array(
                "label" => "approval pembiayaan supplies",
                "actionLabel" => "approve request",
                "source" => "7762r",
                "target" => "7762",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
//                "runCoreAkunting" => true,
            ),
        ),
        "template" => "template/transaksi_supplies_biaya.html",
//        "selectorModel" => "MdlNotaItem",
//        "selectorSrcModel" => "MdlNotaItem",
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
//
//            "cabang_id=placeID", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
//            "jenis=.461",
//            "sinkron=.0",
            "stock_locker.cabang_id=placeID", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nomer",
//        ),
//        "selectorViewedFields" => array(
//            "nomer",
//            "nomer_top",
//        ),
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
//        "selectorProcessor" => "_processSelectNotaItem/select",
        "selectorProcessor" => "_processSelectSupplies/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "status=.1",
            "trash=.0",
            "jenis=.cabang",
//            "id<>cabang_id",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakModel2" => "MdlPettycashStatic",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "kategori biaya",
        "pihakFilters2" => array(),
        "pihakProcessor2" => "_processPihak/select2",
        "pihakMainValueSrc" => array(
            "pihak2Coa_code" => "coa_code",
        ),
        "pihakModel3" => "MdlDtaBiayaUsaha",
        "pihakCaller3" => "_selectorPihak/selectPihak3",
        "pihakLabel3" => "detail biaya",
        "pihakFilters3" => array(),
        "pihakProcessor3" => "_processPihak/select3",
        "pihakMainValueSrc3" => array(
            "pihak3Coa_code" => "coa_code",
        ),
        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "branchDetails__label" => "cabang pembebanan",
            "nomer" => "nomer request",
            "item_fields" => "isi",
            "harga" => "amount",
            "category_expense__nama" => "kategori biaya",
            "pihak3Name" => "sub-biaya",
            "oleh_nama" => "pic",
            "description" => "catatan",
            "next_pic" => "Next step otorisator",
            //------------------------
            "keterangan" => "keterangan",
//            "print_label" => "tool",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "tanggal",
                "branchDetails__label" => "cabang pembebanan",
                "nomer" => "nomer request",
                "item_fields" => "isi",
                "harga" => "amount",
                "category_expense__nama" => "kategori biaya",
                "pihak3Name" => "sub-biaya",
                "oleh_nama" => "pic",
                "description" => "catatan",
                "keterangan" => "keterangan",
                "next_pic" => "Next step otorisator",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "tanggal",
                "branchDetails__label" => "cabang pembebanan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer approve",
                "item_fields" => "isi",
                "harga" => "amount",
                "category_expense__nama" => "kategori biaya",
                "pihak3Name" => "sub-biaya",
                "oleh_nama" => "pic",
                "description" => "catatan",
                "keterangan" => "keterangan",
                "next_pic" => "Next step otorisator",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "branchDetails__label" => "cabang pembebanan",
            "nomer_top" => "nomer request",
            "nomer" => "nomer approve",
            "item_fields" => "isi",

            "oleh_nama" => "person",
            "harga" => "amount",
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "stok" => "stok tersedia",
                "stok_booked" => "booked",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Price",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                //                "ppn" => "VAT",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(//                "jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
            "pihak2ID" => "category expense",
            "pihak2Name" => "category expense",
            "pihak3ID" => "detail expense",
            "pihak3Name" => "detail expense",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga)",
            2 => "jml*(harga)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Amount",
                //                "disc" => "Disc",
                //                "ppn" => "VAT",
                //                "grand_total" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                //                "disc" => "Disc",
                //                "ppn" => "VAT",
                //                "grand_total" => "Grand Total",
            ),
        ),
        "shoppingCartAvoidRemove" => false,
        "resumeFieldNames" => array(
            "selectFields" => "cabang_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "BRANCH DETAILS",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                ),
                //                "editPoints" => array(1),
            ),
            "category_expense" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "KATEGORI BIAYA",
                "mdlName" => "MdlPettycashStatic",
                "mdlFilter" => array("id=pihak2ID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                ),
                "editPoints" => array(1),
            ),
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
//            "transaksi" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "service receipt note",
//                "mdlName" => "MdlTransaksiData",
//                "mdlFilter" => array("id=referenceID"),
//                "key" => "id",
//                "labelSrc" => "nomer",
//                "usedFields" => array(
//                    "nomer" => "nomer",
//                    "suppliers_nama" => "vendor",
//                ),
//                "editPoints" => array(1),
//            ),
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
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7762re",
                "label" => "EDIT pembiayaan supplies",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7762rrj",
                "label" => "REJECT pembiayaan supplies",
            ),
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),//skip

    //  config request expense/biaya usaha cabang
    "677" => array(
        "icon" => "fa fa-money",
        "label" => "biaya usaha",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request biaya usaha",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "677r",
                "userGroup" => "o_kasir",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
//            2 => array(
//                "label" => "authorization biaya usaha",
//                "actionLabel" => "approve request",
//                "source" => "677r",
//                "target" => "677",
//                "userGroup" => "o_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        //        "template" => "template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlDtaBiayaUsaha",
        "selectorSrcModel" => "MdlDtaBiayaUsaha",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya usaha",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakDisabled" => "disabled",
        "pihakVisibility" => "hidden",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "nomer_top" => "nomer request",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "transaksi_nilai" => "nilai",
            "oleh_nama" => "pic",
            //------------------------
            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),

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
        "connectTo" => "2677",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "677r" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "nilai",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),
    //otorisasi biaya usaha cabang
    "2677" => array(
        "icon" => "fa fa-money",
        "label" => "Otorisasi biaya usaha",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya usaha",
                "actionLabel" => "request",
                "source" => "",
                "target" => "2677r",
                "userGroup" => "c_finance",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "Otorisasi biaya usaha",
                "actionLabel" => "approve request",
                "source" => "2677r",
                "target" => "2677",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        //        "template" => "template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlDtaBiayaUsaha",
        "selectorSrcModel" => "MdlDtaBiayaUsaha",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya usaha",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "nomer_top" => "nomer request",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "transaksi_nilai" => "nilai",
            "oleh_nama" => "pic",
            //------------------------
            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "tanggal",
                "cabang_nama" => "cabang",
                "nomer" => "nomer request",
                "item_fields" => "isi",
                "transaksi_nilai" => "nilai",
                "oleh_nama" => "pic",
                //------------------------
                "pym_src_status_keterangan" => "status bayar",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "tanggal",
                "cabang_nama" => "cabang",
                "nomer_top" => "nomer request",
                "nomer" => "nomer",
                "item_fields" => "isi",
                "transaksi_nilai" => "nilai",
                "oleh_nama" => "pic",
                //------------------------
                "pym_src_status_keterangan" => "status bayar",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
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
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "harga" => "subtotal",
            ),
            2 => array(
                "harga" => "subtotal",
            ),
        ),
        "shoppingCartSubFields2" => array(
            1 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartSubNumFields2" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartFields3" => array(
            1 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields3" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),

        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

        "receiptElements" => array(
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
        "revertException" => true,
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        "multiOtorisasi" => array(
            "enabled" => false,

        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),//done
    //  config request biaya gaji...
    "1674" => array(
        "icon" => "fa fa-money",
        "label" => "biaya gaji PUSAT (take home pay)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya gaji",
                "actionLabel" => "request biaya gaji",
                "source" => "",
                "target" => "1674r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "otorisasi biaya gaji",
                "actionLabel" => "approve request",
                "source" => "1674r",
                "target" => "1674",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlGaji",
        "selectorSrcModel" => "MdlGaji",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "tipe=.gaji",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "local expense name",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectRekeningGaji/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakResetor" => false,
        "pihakLabel" => "salary expense",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakLoadAuto" => true,
        "pihakDisabled" => "disabled",

        "shortHistoryFields" => array(
            "dtime" => "date",
            "pihakPembebananLabel" => "cabang pembebanan",
            "nomer_top" => "Request number",
            "nomer" => "approval number",
            "item_fields" => "isi",
            "biaya_option__label" => "kategori biaya",
//            "biaya_gaji_main" => "biaya gaji",
//            "biaya_bpjs_perusahaan" => "biaya bpjs",
//            "biaya_pph21_perusahaan" => "biaya pph 21",
            "hutang_gaji_main" => "hutang gaji",
            "hutang_bpjs_main" => "hutang bpjs",
            "hutang_pph21_main" => "hutang pph 21",
            "harga" => "total amount",
            "oleh_nama" => "person",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
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
            "disabled" => "disabled",
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
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(
                "harga",
                // "jml",
                "reference",
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
        "shoppingCartSumFields" => array(
            1 => array(
                "hutang_gaji" => "Hutang Gaji",
//                "hutang_bpjs_main" => "Hutang BPJS",
//                "hutang_pph21_main" => "hutang PPh 21",
                // "grand_total" => "Grand Total",
            ),
            2 => array(
                "hutang_gaji" => "Hutang Gaji",
//                "hutang_bpjs_main" => "Hutang BPJS",
//                "hutang_pph21_main" => "hutang PPh 21",
                // "grand_total" => "Grand Total",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch/center ID",
            "pihakName" => "branch/center name",

        ),
        "followupItemEditable" => "_followupLiveEdit/updateItemExpense/",
        "receiptElements" => array(
            "biaya_option" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "kategori biaya (biaya usaha/umum)",
                "mdlName" => "MdlBiayaMethodOptionGaji",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "coa_code" => "kode biaya",//coa_code biaya usaha/umum
                    "biaya_pph21_id" => "kode biaya pph21",
                    "biaya_bpjs_id" => "kode biaya bpjs",
                    "biaya_gaji_id" => "kode biaya gaji",
                ),
                "editPoints" => array(1),
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
        "autoSelectItem" => true,
        "autoSelectItemNonProject" => true,
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1674re",
                "label" => "EDIT salary expense request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1674rrj",
                "label" => "REJECT salary expense request",
            ),
        ),

        "allowedMainEdit" => array("1"),
    ),
    "11674" => array(
        "icon" => "fa fa-money",
        "label" => "biaya gaji CABANG (take home pay)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request biaya gaji (cabang)",
                "actionLabel" => "request biaya gaji (cabang)",
                "source" => "",
                "target" => "11674r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
//            2 => array(
//                "label" => "otorisasi biaya gaji",
//                "actionLabel" => "approve request",
//                "source" => "1674r",
//                "target" => "1674",
//                "userGroup" => "c_holding",
//                "stateLabel" => "approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlGaji",
        "selectorSrcModel" => "MdlGaji",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "tipe=.gaji",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "local expense name",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectRekeningGaji/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakResetor" => false,
        "pihakLabel" => "salary expense",
        "pihakFilters" => array(
//            "id=.-1",
            "id=cabang_id",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakLoadAuto" => true,
        "pihakDisabled" => "disabled",

        "shortHistoryFields" => array(
            "dtime" => "date",
            "pihakPembebananLabel" => "cabang pembebanan",
            "nomer_top" => "Request number",
            "nomer" => "approval number",
            "item_fields" => "isi",
            "biaya_option__label" => "kategori biaya",
//            "biaya_gaji_main" => "biaya gaji",
//            "biaya_bpjs_perusahaan" => "biaya bpjs",
//            "biaya_pph21_perusahaan" => "biaya pph 21",
            "hutang_gaji_main" => "hutang gaji",
            "hutang_bpjs_main" => "hutang bpjs",
            "hutang_pph21_main" => "hutang pph 21",
            "harga" => "total amount",
            "oleh_nama" => "person",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
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
            "disabled" => "disabled",
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
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(
                "harga",
                // "jml",
                "reference",
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
        "shoppingCartSumFields" => array(
            1 => array(
                "hutang_gaji" => "Hutang Gaji",
//                "hutang_bpjs_main" => "Hutang BPJS",
//                "hutang_pph21_main" => "hutang PPh 21",
                // "grand_total" => "Grand Total",
            ),
            2 => array(
                "hutang_gaji" => "Hutang Gaji",
//                "hutang_bpjs_main" => "Hutang BPJS",
//                "hutang_pph21_main" => "hutang PPh 21",
                // "grand_total" => "Grand Total",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch/center ID",
            "pihakName" => "branch/center name",

        ),
        "followupItemEditable" => "_followupLiveEdit/updateItemExpense/",
        "receiptElements" => array(
            "biaya_option" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "kategori biaya (biaya usaha/umum)",
                "mdlName" => "MdlBiayaMethodOptionGaji",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "coa_code" => "kode biaya",//coa_code biaya usaha/umum
                    "biaya_pph21_id" => "kode biaya pph21",
                    "biaya_bpjs_id" => "kode biaya bpjs",
                    "biaya_gaji_id" => "kode biaya gaji",
                ),
                "editPoints" => array(1),
            ),

            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
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
                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
        "autoSelectItem" => true,
        "autoSelectItemNonProject" => true,
        //----
        "connectTo" => "21674",
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1674re",
                "label" => "EDIT salary expense request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1674rrj",
                "label" => "REJECT salary expense request",
            ),
        ),

        "allowedMainEdit" => array("1"),
    ),
    "21674" => array(
        "icon" => "fa fa-money",
        "label" => "otorisasi biaya gaji (cabang)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya gaji (cabang)",
                "actionLabel" => "request biaya gaji (cabang)",
                "source" => "",
                "target" => "21674r",
                "userGroup" => "sys",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "otorisasi biaya gaji (cabang)",
                "actionLabel" => "approve request (cabang)",
                "source" => "21674r",
                "target" => "21674",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlGaji",
        "selectorSrcModel" => "MdlGaji",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "tipe=.gaji",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "local expense name",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectRekeningGaji/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakResetor" => false,
        "pihakLabel" => "salary expense",
        "pihakFilters" => array(
//            "id=.-1",
            "id=cabang_id",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "dtime" => "date",
            "pihakPembebananLabel" => "cabang pembebanan",
            "nomer_top" => "Request number",
//            "nomer" => "approval number",
            "item_fields" => "isi",
            "biaya_option__label" => "kategori biaya",
//            "biaya_gaji_main" => "biaya gaji",
//            "biaya_bpjs_perusahaan" => "biaya bpjs",
//            "biaya_pph21_perusahaan" => "biaya pph 21",
            "hutang_gaji_main" => "hutang gaji",
            "hutang_bpjs_main" => "hutang bpjs",
            "hutang_pph21_main" => "hutang pph 21",
            "harga" => "total amount",
            "oleh_nama" => "person",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
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
            "disabled" => "disabled",
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
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(
                "harga",
                // "jml",
                "reference",
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
        "shoppingCartSumFields" => array(
            1 => array(
                "hutang_gaji" => "Hutang Gaji",
//                "hutang_bpjs_main" => "Hutang BPJS",
//                "hutang_pph21_main" => "hutang PPh 21",
                // "grand_total" => "Grand Total",
            ),
            2 => array(
                "hutang_gaji" => "Hutang Gaji",
//                "hutang_bpjs_main" => "Hutang BPJS",
//                "hutang_pph21_main" => "hutang PPh 21",
                // "grand_total" => "Grand Total",
            ),
        ),
        "shoppingCartFieldValidators" => array(//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch/center ID",
            "pihakName" => "branch/center name",

        ),
        "followupItemEditable" => "_followupLiveEdit/updateItemExpense/",
        "receiptElements" => array(
            "biaya_option" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "kategori biaya (biaya usaha/umum)",
                "mdlName" => "MdlBiayaMethodOptionGaji",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "coa_code" => "kode biaya",//coa_code biaya usaha/umum
                    "biaya_pph21_id" => "kode biaya pph21",
                    "biaya_bpjs_id" => "kode biaya bpjs",
                    "biaya_gaji_id" => "kode biaya gaji",
                ),
                "editPoints" => array(1),
            ),

        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
        "autoSelectItem" => true,
        "autoSelectItemNonProject" => true,
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "21674re",
                "label" => "EDIT salary expense request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "21674rrj",
                "label" => "REJECT salary expense request",
            ),
        ),

        "allowedMainEdit" => array("1"),
    ),
    // bpjs, pph21 (pusat)
    "7674" => array(
        "icon" => "fa fa-money",
        "label" => "biaya bpjs dan pph21 (pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya bpjs dan pph21",
                "actionLabel" => "request biaya bpjs dan pph21",
                "source" => "",
                "target" => "7674r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "otorisasi biaya bpjs dan pph21",
                "actionLabel" => "approve request",
                "source" => "7674r",
                "target" => "7674",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlGajiBpjsPph",
        "selectorSrcModel" => "MdlGajiBpjsPph",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "tipe=.gaji",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "local expense name",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectRekeningGaji/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakResetor" => false,
        "pihakLabel" => "salary expense",
        "pihakFilters" => array(//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "dtime" => "date",
            "pihakPembebananLabel" => "cabang pembebanan",
            "nomer_top" => "Request number",
            "nomer" => "approval number",
            "item_fields" => "isi",
            "biaya_option__label" => "kategori biaya",
//            "biaya_gaji_main" => "biaya gaji",
//            "biaya_bpjs_perusahaan" => "biaya bpjs",
//            "biaya_pph21_perusahaan" => "biaya pph 21",
//            "hutang_gaji_main" => "hutang gaji",-----------------------
            "hutang_bpjs_main" => "hutang bpjs",
            "hutang_pph21_main" => "hutang pph 21",
            "harga" => "total amount",
            "oleh_nama" => "person",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "rekening" => "rekening",
            "reference" => "reference",
            "disabled" => "disabled",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
//            "extern_coa"=>"extern_coa",
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
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(
                "harga",
                // "jml",
                "reference",
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
        "shoppingCartSumFields" => array(
            1 => array(
//                "hutang_gaji" => "Hutang Gaji",
                "hutang_bpjs_main" => "Hutang BPJS",
                "hutang_pph21_main" => "hutang PPh 21",
                // "grand_total" => "Grand Total",
            ),
            2 => array(
//                "hutang_gaji" => "Hutang Gaji",
                "hutang_bpjs_main" => "Hutang BPJS",
                "hutang_pph21_main" => "hutang PPh 21",
                // "grand_total" => "Grand Total",
            ),
        ),
        "shoppingCartFieldValidators" => array(//            "harga" => "price",

        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch/center ID",
            "pihakName" => "branch/center name",

        ),
        "followupItemEditable" => "_followupLiveEdit/updateItemExpense/",
        "receiptElements" => array(
            "biaya_option" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "kategori biaya (biaya usaha/umum)",
                "mdlName" => "MdlBiayaMethodOptionGaji",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "coa_code" => "kode biaya",//coa_code biaya usaha/umum
                    "biaya_pph21_id" => "kode biaya pph21",
                    "biaya_bpjs_id" => "kode biaya bpjs",
                    "biaya_gaji_id" => "kode biaya gaji",
                ),
                "editPoints" => array(1),
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
        "autoSelectItem" => true,
        "autoSelectItemNonProject" => true,
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7674re",
                "label" => "EDIT biaya bpjs dan pph21 request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "7674rrj",
                "label" => "REJECT biaya bpjs dan pph21 request",
            ),
        ),

        "allowedMainEdit" => array("1"),
    ),


    //request biaya umum cabang
    "675" => array(
        "icon" => "fa fa-money",
        "label" => "biaya umum",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request biaya umum",
                "actionLabel" => "request for expense",
                "source" => "",
                "target" => "675r",
                "userGroup" => "o_kasir",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
//            2 => array(
//                "label" => "authorization biaya umum",
//                "actionLabel" => "approve request",
//                "source" => "675r",
//                "target" => "675",
//                "userGroup" => "o_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        //        "template" => "template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlDtaBiayaUmum",
        "selectorSrcModel" => "MdlDtaBiayaUmum",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya umum",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakDisabled" => "disabled",
        "pihakVisibility" => "hidden",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "nomer_top" => "nomer request",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "transaksi_nilai" => "nilai",
            "oleh_nama" => "pic",
            //------------------------
            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang",
            "pihakName" => "pihak name",
        ),

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
        "connectTo" => "2675",
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "675re",
                "label" => "EDIT request biaya umum",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "675rrj",
                "label" => "REJECT request biaya umum",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "675r" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "nilai",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),
    "2675" => array(
        "icon" => "fa fa-money",
        "label" => "Otorisasi request biaya umum",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya umum",
                "actionLabel" => "request for expense",
                "source" => "",
                "target" => "2675r",
                "userGroup" => "c_finance",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "Otorisasi biaya umum",
                "actionLabel" => "approve request",
                "source" => "2675r",
                "target" => "2675",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlDtaBiayaUmum",
        "selectorSrcModel" => "MdlDtaBiayaUmum",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya umum",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "local expense vendor",
        "pihakFilters" => array(//            "id=cabang_id",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "nomer_top" => "nomer request",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "transaksi_nilai" => "nilai",
            "oleh_nama" => "pic",
            //------------------------
            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "tanggal",
                "cabang_nama" => "cabang",
                "nomer" => "nomer request",
                "item_fields" => "isi",
                "transaksi_nilai" => "nilai",
                "oleh_nama" => "pic",
                //------------------------
                "pym_src_status_keterangan" => "status bayar",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "tanggal",
                "cabang_nama" => "cabang",
                "nomer_top" => "nomer request",
                "nomer" => "nomer",
                "item_fields" => "isi",
                "transaksi_nilai" => "nilai",
                "oleh_nama" => "pic",
                //------------------------
                "pym_src_status_keterangan" => "status bayar",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
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
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "subtotal",
            ),
            2 => array(
                "harga" => "subtotal",
            ),
        ),
        "shoppingCartSubFields2" => array(
            1 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartSubNumFields2" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartFields3" => array(
            1 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields3" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),

        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

        "receiptElements" => array(
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

        "pairMakers" => array(
            2 => array(
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items",
                ),
            ),
        ),
        "pairInjectors" => array(
            2 => array(
                "preBiaya" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "costName",
                    ),
                ),
            ),
        ),
        "revertException" => true,
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2675re",
                "label" => "EDIT request biaya umum",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2675rrj",
                "label" => "REJECT request biaya umum",
            ),
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),//done
    //biaya umum pusat done
    "1675" => array(
        "icon" => "fa fa-money",
        "label" => "biaya umum (pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya umum",
                "actionLabel" => "request for expense",
                "source" => "",
                "target" => "1675r",
                "userGroup" => "o_kasir",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
//            2 => array(
//                "label" => "authorization biaya umum",
//                "actionLabel" => "approve request",
//                "source" => "1675r",
//                "target" => "1675",
//                "userGroup" => "o_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        //        "template" => "template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlDtaBiayaUmum",
        "selectorSrcModel" => "MdlDtaBiayaUmum",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya umum",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakDisabled" => "disabled",
        "pihakVisibility" => "hidden",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "amount",
            "oleh_nama" => "pic",
            //------------------------
            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ",
            //            "pihakName" => "cabang",
        ),

        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "branchTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target pembebanan ",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1675re",
                "label" => "EDIT request biaya umum",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1675rrj",
                "label" => "REJECT request biaya umum",
            ),
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),
    //biaya besar belum negacu pada pembebanan
    "4675" => array(
        "icon" => "fa fa-money",
        "label" => "biaya (pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya",
                "actionLabel" => "request for expense",
                "source" => "",
                "target" => "4675r",
                "userGroup" => "c_finance",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "otorisasi biaya",
                "actionLabel" => "approve request",
                "source" => "4675r",
                "target" => "4675",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        //        "template" => "template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlJasa",
        "selectorSrcModel" => "MdlJasa",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            //            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",

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
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ",
            //            "pihakName" => "cabang",
        ),

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
    ),
    //biaya usaha pusat
    "1677" => array(
        "icon" => "fa fa-money",
        "label" => "biaya usaha (pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya usaha",
                "actionLabel" => "request",
                "source" => "",
                "target" => "1677r",
                "userGroup" => "o_kasir",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
//            2 => array(
//                "label" => "authorization biaya usaha",
//                "actionLabel" => "approve request",
//                "source" => "1677r",
//                "target" => "1677",
//                "userGroup" => "o_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        //        "template" => "template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlDtaBiayaUsaha",
        "selectorSrcModel" => "MdlDtaBiayaUsaha",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya usaha",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array( //extra label untuk dropdown selector biaya
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakDisabled" => "disabled",
        "pihakVisibility" => "hidden",

        "shortHistoryFields" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
//            "nomer_top" => "request number",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "amount",
            "oleh_nama" => "pic",
            //------------------------
            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "historyFields" => array(
            1 => array(
                "dtime" => "date",
                "cabang_nama" => "branch",
//            "nomer_top" => "request number",
                "nomer" => "nomer",
                "item_fields" => "isi",
                "harga" => "amount",
                "oleh_nama" => "pic",
                //------------------------
                "pym_src_status_keterangan" => "status bayar",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),

        "extHistoryFields" => array(
            1 => array(
//                "review_details" => "id",
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
            "allowed_ext" => "allowed_ext",
            "reference" => "reference",
        ),
        "shoppingCartFieldSrc2_sum" => array(
            "nama" => "nama",
            "jenis_label" => "jenis_label",
            "nomer_reference" => "nama",
            "dtime" => "dtime",
            "fulldate" => "fulldate",
            "customers_id" => "customers_id",
            "customers_nama" => "customers_nama",
            "cabang_id" => "cabang_id",
            "cabang_nama" => "cabang_nama",
            "keterangan" => "keterangan",
            "transaksi_nilai" => "transaksi_nilai",
            "bank_id" => "bank_id",
            "bank_nama" => "bank_nama",
            "bank_rekening_id" => "bank_rekening_id",
            "bank_rekening_nama" => "bank_rekening_nama",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "nilai",
            ),
            2 => array(
                "harga" => "nilai",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),

        "shoppingCartFields2_sum" => array(
            1 => array(
                "nomer_reference" => "deskripsi",
                "customers_nama" => "konsumen",
                "fulldate" => "tanggal",
                "transaksi_nilai" => "nilai transaksi",
                "jenis_label" => "label",
            ),
            2 => array(
                "nomer_reference" => "deskripsi",
                "customers_nama" => "konsumen",
                "fulldate" => "tanggal",
                "transaksi_nilai" => "nilai transaksi",
                "jenis_label" => "label",
            ),
        ),

        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang",
            //            "pihakName" => "pihak name",
        ),
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
            "referenceNota" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                //                "inputType"   => "radio",
                "label" => "referensi",
                "mdlName" => "MdlKas_in",
                "mdlFilter" => array("nilai=allowed_ext"),
                "key" => "id",
                "labelSrc" => "untuk_jenis",
                "usedFields" => array(
                    "untuk_jenis" => "nilai",
                    "nilai" => "nilai",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
            "referenceNota" => array(
                3 => array(
//                    "referensi_so" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "invoice penerimaan",
//                        "mdlName" => "MdlTransaksi2",//ini klonengan mdltransaksi
//                        "key" => "id",
////                        "defaultValue"=>"transaksi_id_ref",
//                        "mdlFilter" => array(
//                            "id=transaksi_id_ref"
//                        ),
//                        "labelSrc" => "nomer",
//                        "usedFields" => array(
////                            "id_master" => "mid",
////                            "id" => "referensi",
//                            "fulldate" => "tgl penerimaan",
//                            "nomer" => "nomer penerimaan",
//                            "oleh_nama" => "pic",
//                        ),
//                        "editPoints" => array(1,),
//                        "noPrefetch" => false,
//
//                    ),
                    "cashaccount_ref" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "cash account/akun rekening bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "id=cash_account_ref",
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
                        "editPoints" => array(1),
                    ),

                ),
            ),
        ),
        "relativeOptions" => array(),
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //----opsi BIAYA BANK
        "optionFreelancerShow" => array(
            "key" => "allowed_ext",
            "pajakOption" => array("1",),
        ),
        "optionFreelancerModel" => "MdlBankAccount_cash_and_in",
        "optionFreelancerCaller" => "_selectorPihak/selectOptionFreelancer",
        "optionFreelancerProcessor" => "_processPihak/selectOptionFreelancer",
//        "optionFreelancerReset" => array(// mereset element dan gerbang nilai/session
//            "kompensasiMethod",
//            "cash_account",
//        ),
        "optionFreelancerLabel" => "Pilih Akun BANK untuk Biaya BANK.",
        "optionFreelancerValueSrc" => array(
            "cash_account_ref" => "id",
            "nama" => "nama",
            "folders_nama" => "folders_nama",
        ),
        //-------------------------
        "pihakModelMain" => "MdlTransaksi2",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "pilih referensi transaksi anda...",
        "pihakMainFilters" => array(
//            "jenis=.4464",
        ),
        "pihakMainFiltersRulesAdd" => array(
            "enabled" => true,// tgl 10 april 2025, komisi freelancer pph21, biaya konsultasi pph23, cashback pph23 bisa dipilih bebas.
            "filter" => array(
//                "jenis=.4464",
//                "jenis=.749",
                "jenis in (4464,749)",
                "biaya_bank_status=.0",
                "trash_4=.0",
                "bank_rekening_id=cashaccount_ref",
            ),
//            "exception" => array(
//                "3",// lain-lain, tidak masuk filter atau keluar semua, silahkan dipilih (komisi freelancer, biaya konsultasi, cashback)
//            ),
//
        ),
        "pihakMainValueSrc2" => array(
//            "taxesMethod" => "taxes_name",
//            "taxesMethodCoa" => "coa_code",
        ),
        "pihakMainViewedFields2" => array(
            "nomer",
            "customers_nama",
            "fulldate",
            "transaksi_nilai",
        ),
        "pihakMainProcessor" => "_processPihakMainRules/selectReference",
        "pihakMainReferencelabel" => "Referensi Transaksi Biaya Bank",
        "editHandlerMethod2_sum" => "selectReference",
        "pihakMainValueValidator" => array(
            "enabled" => true,
            "keyCek" => "allowed_ext",
            "gateTarget" => "items2_sum",
            "label" => "Input Biaya Bank wajib dengan referensi transaksi. Silahkan pilih referensi dahulu.",
        ),
    ),
    // config supplies yang dibiayakan
    "762" => array(
        "icon" => "fa fa-circle",
        "label" => "pembiayaan supplies",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pembiayaan supplies",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "762r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "approval pembiayaan supplies",
                "actionLabel" => "approve biaya",
                "source" => "762r",
                "target" => "762",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        //        "template" => "template/transaksi.html",
        "template" => "template/transaksi_supplies_biaya.html",
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
        "pihakFilters" => array(
            "id=cabang_id",
        ),
        "pihakProcessor" => "_processPihak/select",

        "pihakModel2" => "MdlPettycashStatic",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "kategori biaya",
        "pihakFilters2" => array(),
        "pihakProcessor2" => "_processPihak/select2",

        "pihakModel3" => "MdlDtaBiayaUsaha",
        "pihakCaller3" => "_selectorPihak/selectPihak3",
        "pihakLabel3" => "detail biaya",
        "pihakFilters3" => array(),
        "pihakProcessor3" => "_processPihak/select3",


        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
            "pihak2ID" => "category expense",
            "pihak2Name" => "category expense",
            "pihak3ID" => "detail expense",
            "pihak3Name" => "detail expense",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartAvoidRemove" => false,
        "resumeFieldNames" => array(
            "selectFields" => "cabang_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        "receiptElements" => array(
            "category_expense" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CATEGORY EXPENSE",
                "mdlName" => "MdlPettycashStatic",
                "mdlFilter" => array("id=pihak2ID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                ),
                "editPoints" => array(1),
            ),
            //            "detail_expense" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "DETAIL EXPENSE",
            //                "mdlName" => "MdlPettycashStatic",
            //                "mdlFilter" => array("id=pihak2ID"),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "name",
            //                ),
            //                "editPoints" => array(1),
            //            ),
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
                "hppSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_hpp",
                    "functionName" => "cekStockSuppliesHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
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
                "hppSupplies" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "harga",
                    ),
                ),
            ),

        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "762re",
                "label" => "EDIT pembiayaan supplies",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "762rrj",
                "label" => "REJECT pembiayaan supplies",
            ),
        ),
    ),
    "9982" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "transfer biaya (dari po jasa)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request",
                "actionLabel" => "request transfer",
                "source" => "",
                "target" => "9982r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "approval",
                "actionLabel" => "approve transfer biaya",
                "source" => "9982r",
                "target" => "9982",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),
        ),
//        "template" => "template/transaksi2.html",
        "template" => "template/transaksi_nopihak.html",

        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck" => array(
            //            "enabled" => false,
            //            "mdlName" => "MdlLockerStock",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            //            "returned=.0",
            "jenis=.463",
            "sinkron=.0",
            //            "customers_id=pihakID",
            //            "tail_number=.5",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih po jasa yang akan dipindah",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
            "supplier_nama" => "supplier_nama",
            "oleh_nama" => "oleh_nama",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectNotaItem/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih cabang pembebanan biaya",
        "pihakProcessor" => "_processPihak/select",
        "pihakFilters" => array(
            "id<>.-1"
        ),

        "pihakModel2" => "MdlDtaBiayaUsaha",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "pilih kategori biaya",
        "pihakProcessor2" => "_processPihak/select2",
        "pihakFilters2" => array(),

        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "referenceNomer" => "SRN",
            "nomer" => "receipt number",
            "oleh_nama" => "person",

            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "next_pic" => "Next step otorisator",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",

            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "pairTransaksiNomer_1" => "pre po number",
                "pairTransaksiNomer_2" => "po number",
                "referenceNomer" => "SRN",
                "nomer" => "receipt number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "pairTransaksiNomer_1" => "pre po number",
                "pairTransaksiNomer_2" => "po number",
                "referenceNomer" => "SRN",
                "nomer" => "receipt number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
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
            //            "satuan" => "satuan",
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                //                "produk_kode" => "part number",
                //                "jml" => "qty",
                //                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item name",
                //                "produk_kode" => "part number",
                //                "jml" => "qty",
                //                "satuan" => "uom",
            ),
            //            3 => array(
            //                "nama" => "item name",
            //                "produk_kode" => "part number",
            //                "jml" => "qty",
            //                "satuan" => "uom",
            //            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(//                "jml",
            ),
            //            2 => array(
            //                "jml",
            //            ),
            //            3 => array(
            //                "jml",
            //            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "grand_total" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "grand_total" => "Grand Total",
            ),
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
//            "pihakID" => "cabang ID",
//            "pihakName" => "cabang name",
//            "pihak2ID" => "pembantu biaya usaha",
//            "pihak2Name" => "pembantu biaya usaha",
        ),
        "applets" => array(
            //            "alamat_kirim" => array(
            //                "label" => "alamat kirim",
            //                "mdlName" => "MdlSupplierAddress",
            ////                "mdlFilter" => array("extern_id=pihakID"),
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "description" => "alamat+kelurahan+kecamatan+kabupaten+propinsi+kodepos",
            //            ),
            //            "tos" => array(
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
            //            "capacity" => array(
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
        ),
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
        "receiptElements" => array(

            "biayaKategori" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pilih kategori biaya",
                "mdlName" => "MdlStaticBiaya",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
            ),
            "transaksi" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "service receipt note",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array("id=referenceID"),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "nomer",
                    "suppliers_nama" => "vendor",
                    "dtime" => "date",
                    "oleh_nama" => "oleh_nama",
                    "cabang2_id" => "cabang2_id",
                    "cabang2_nama" => "cabang2_nama",
                ),
                "editPoints" => array(1),
            ),
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "Pilih cabang tujuan biaya",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=transaksi__cabang2_id"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                ),
                //                "editPoints" => array(1),
            ),
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=branchDetails"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
        ),
        "relativeElements" => array(
            "biayaKategori" => array(
                "1" => array(
                    "biaya_detail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "Biaya usaha",
                        "mdlName" => "MdlDtaBiayaUsaha",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "2" => array(
                    "biaya_detail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "Biaya umum",
                        "mdlName" => "MdlDtaBiayaUmum",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "pairRegistries" => array(
            //            "tableIn_master_values",
            "main", "items"
        ),
        "pairReceiptItemRegistries" => array(// mengambil dari registri items, kolomnya ini
            "pihak2ID",
            "pihak2Name",
        ),
        "pairTransaksi" => array(
            "kolom" => array(
                "pairTransaksiNomer" => "nomer",
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9982re",
                "label" => "EDIT transfer expense to marketing expense",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9982rrj",
                "label" => "REJECT transfer expense to marketing expense",
            ),
        ),
        "master_item_label" => array(
            "transaksi__dtime" => "tanggal transaksi",
            "transaksi__suppliers_nama" => "vendor",
            "transaksi__nomer" => "nomor transaksi",
            "transaksi__oleh_nama" => "pic",

        ),//untuk menmpilkan nota yang diplih
    ),// transfer biaya ke biaya usaha
    "9983" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "transfer expense to general expense",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request transfer expense to general expense",
                "actionLabel" => "make transfer",
                "source" => "",
                "target" => "9983r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "approval transfer expense to general expense",
                "actionLabel" => "approve transfer expense",
                "source" => "9983r",
                "target" => "9983",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),
        ),
        //        "template" => "template/transaksi.html",
        "template" => "template/transaksi2.html",

        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck" => array(
            //            "enabled" => false,
            //            "mdlName" => "MdlLockerStock",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            //            "returned=.0",
            "jenis=.463",
            "sinkron=.0",
            //            "customers_id=pihakID",
            //            "tail_number=.5",
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
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabangNonProduksi",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "nama cabang",
        "pihakProcessor" => "_processPihak/select",
        "pihakFilters" => array(
            "id<>.-1"
        ),

        "pihakModel2" => "MdlDtaBiayaUmum",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "nama detail biaya umum",
        "pihakProcessor2" => "_processPihak/select2",
        "pihakFilters2" => array(),

        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            "referenceNomer1" => "po number",
            "referenceNomer" => "SRN",
            "nomer" => "receipt number",
            "oleh_nama" => "person",

            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",

            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "pairTransaksiNomer_1" => "pre po number",
                "pairTransaksiNomer_2" => "po number",
                "referenceNomer" => "SRN",
                "nomer" => "receipt number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "pairTransaksiNomer_1" => "pre po number",
                "pairTransaksiNomer_2" => "po number",
                "referenceNomer" => "SRN",
                "nomer" => "receipt number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
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
            //            "satuan" => "satuan",
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                //                "produk_kode" => "part number",
                //                "jml" => "qty",
                //                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item name",
                //                "produk_kode" => "part number",
                //                "jml" => "qty",
                //                "satuan" => "uom",
            ),
            //            3 => array(
            //                "nama" => "item name",
            //                "produk_kode" => "part number",
            //                "jml" => "qty",
            //                "satuan" => "uom",
            //            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(//                "jml",
            ),
            //            2 => array(
            //                "jml",
            //            ),
            //            3 => array(
            //                "jml",
            //            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "grand_total" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "grand_total" => "Grand Total",
            ),
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
            "pihak2ID" => "pembantu biaya usaha",
            "pihak2Name" => "pembantu biaya usaha",
        ),
        "applets" => array(
            //            "alamat_kirim" => array(
            //                "label" => "alamat kirim",
            //                "mdlName" => "MdlSupplierAddress",
            ////                "mdlFilter" => array("extern_id=pihakID"),
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "description" => "alamat+kelurahan+kecamatan+kabupaten+propinsi+kodepos",
            //            ),
            //            "tos" => array(
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
            //            "capacity" => array(
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
        ),
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
        //        "referenceJenisTr" => "582",
        "receiptElements" => array(
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "BRANCH DETAILS",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                ),
                //                "editPoints" => array(1),
            ),
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "biaya" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "general expense",
                "mdlName" => "MdlDtaBiayaUmum",
                "mdlFilter" => array("id=pihak2ID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
            ),
            "transaksi" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "service receipt note",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array("id=referenceID"),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "nomer",
                    "suppliers_nama" => "vendor",
                ),
                "editPoints" => array(1),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "pairRegistries" => array(
            //            "tableIn_master_values",
            "main", "items",
        ),
        //        "connectTo" => "9983",
        "pairReceiptItemRegistries" => array(// mengambil dari registri items, kolomnya ini
            "pihak2ID",
            "pihak2Name",
        ),
        "pairTransaksi" => array(
            "kolom" => array(
                "pairTransaksiNomer" => "nomer",
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9983re",
                "label" => "EDIT request transfer expense to general expense",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9983rrj",
                "label" => "REJECT request transfer expense to general expense",
            ),
        ),
    ),// transfer biaya ke biaya umum
    "9984" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "transfer expense to production expense",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "transfer expense to production expense",
                "actionLabel" => "make transfer",
                "source" => "",
                "target" => "9984r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "approval transfer expense to production expense",
                "actionLabel" => "approve transfer expense",
                "source" => "9984r",
                "target" => "9984",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),
        ),
        //        "template" => "template/transaksi.html",
        "template" => "template/transaksi2.html",

        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck" => array(
            //            "enabled" => false,
            //            "mdlName" => "MdlLockerStock",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            //            "returned=.0",
            "jenis=.463",
            "sinkron=.0",
            //            "customers_id=pihakID",
            //            "tail_number=.5",
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
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabangProduksi",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "nama cabang",
        "pihakProcessor" => "_processPihak/select",
        "pihakFilters" => array(
            "id<>.-1"
        ),

        "pihakModel2" => "MdlDtaBiayaProduksi",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "nama detail biaya",
        "pihakProcessor2" => "_processPihak/select2",
        "pihakFilters2" => array(),

        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "branch",
            //            "nomer" => "",
            "referenceNomer" => "SRN",
            "nomer" => "receipt number",
            "oleh_nama" => "person",

            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "next_pic" => "Next step otorisator",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",

            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "pairTransaksiNomer_1" => "pre po number",
                "pairTransaksiNomer_2" => "po number",
                "referenceNomer" => "SRN",
                "nomer" => "receipt number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "branch",
                "pairTransaksiNomer_1" => "pre po number",
                "pairTransaksiNomer_2" => "po number",
                "referenceNomer" => "SRN",
                "nomer" => "receipt number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
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
            //            "satuan" => "satuan",
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                //                "produk_kode" => "part number",
                //                "jml" => "qty",
                //                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item name",
                //                "produk_kode" => "part number",
                //                "jml" => "qty",
                //                "satuan" => "uom",
            ),
            //            3 => array(
            //                "nama" => "item name",
            //                "produk_kode" => "part number",
            //                "jml" => "qty",
            //                "satuan" => "uom",
            //            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(//                "jml",
            ),
            //            2 => array(
            //                "jml",
            //            ),
            //            3 => array(
            //                "jml",
            //            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "grand_total" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "grand_total" => "Grand Total",
            ),
        ),

        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
            "pihak2ID" => "pembantu biaya usaha",
            "pihak2Name" => "pembantu biaya usaha",
        ),
        "applets" => array(
            //            "alamat_kirim" => array(
            //                "label" => "alamat kirim",
            //                "mdlName" => "MdlSupplierAddress",
            ////                "mdlFilter" => array("extern_id=pihakID"),
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "description" => "alamat+kelurahan+kecamatan+kabupaten+propinsi+kodepos",
            //            ),
            //            "tos" => array(
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
            //            "capacity" => array(
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
        ),
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
        //        "referenceJenisTr" => "582",
        "receiptElements" => array(
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "BRANCH DETAILS",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                ),
                //                "editPoints" => array(1),
            ),
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "biaya" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "marketing expense",
                "mdlName" => "MdlDtaBiayaProduksi",
                "mdlFilter" => array("id=pihak2ID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
            ),
            "transaksi" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "service receipt note",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array("id=referenceID"),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "nomer",
                    "suppliers_nama" => "vendor",
                ),
                "editPoints" => array(1),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "pairRegistries" => array(
            //            "tableIn_master_values",
            "main", "items"
        ),
        //        "connectTo" => "9983",
        "pairReceiptItemRegistries" => array(// mengambil dari registri items, kolomnya ini
            "pihak2ID",
            "pihak2Name",
        ),
        "pairTransaksi" => array(
            "kolom" => array(
                "pairTransaksiNomer" => "nomer",
            ),
        ),

        "pairMakers" => array(
            1 => array(
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items",
                    "key" => "pihak2Name",
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
        "previewCtr" => "Create",

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9984re",
                "label" => "EDIT transfer expense to production expense",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9984rrj",
                "label" => "REJECT transfer expense to production expense",
            ),
        ),
    ),// transfer biaya ke biaya produksi
    "9985" => array(
        "icon" => "fa fa-circle",
        "label" => "transfer marketing expense to other expense",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "pemindahan biaya usaha",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "9985r",
                "userGroup" => "o_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "approval pemindahan biaya usaha",
                "actionLabel" => "approve pemindahan biaya usaha",
                "source" => "9985r",
                "target" => "9985",
                "userGroup" => "o_finance",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_supplies_biaya.html",
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectedPrice" => array(
            //            "model" => "MdlHargaSupplies",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStockSupplies",
        ),
        "selectorFilters" => array(
            "cabang2_id=placeID", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            "jenis=.9982",
            "sinkron=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "nomer_top",
        ),
        "selectorProcessor" => "_processSelectNotaItem/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
            //            "id<>cabang_id",
        ),
        "pihakProcessor" => "_processPihak/select",

        "pihakModel2" => "MdlPettycashStatic",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "kategori biaya",
        "pihakFilters2" => array(//            "nama<>biaya usaha",
        ),
        "pihakProcessor2" => "_processPihak/select2",

        "pihakModel3" => "MdlDtaBiayaUsaha",
        "pihakCaller3" => "_selectorPihak/selectPihak3",
        "pihakLabel3" => "detail biaya",
        "pihakFilters3" => array(),
        "pihakProcessor3" => "_processPihak/select3",


        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                //                "stok" => "stock",
                "jml" => "qty",
                //                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Price",
                "disc_percent" => "disc (%)",
                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(//                "jml",
            ),
            2 => array(//                "jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
            "pihak2ID" => "category expense",
            "pihak2Name" => "category expense",
            "pihak3ID" => "detail expense",
            "pihak3Name" => "detail expense",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "grand_total" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                "disc" => "Disc",
                "ppn" => "VAT",
                "grand_total" => "Grand Total",
            ),
        ),
        "shoppingCartAvoidRemove" => false,
        "resumeFieldNames" => array(
            "selectFields" => "cabang_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        "receiptElements" => array(
            "branchDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "BRANCH DETAILS",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                ),
                //                "editPoints" => array(1),
            ),
            //            "category_expense" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "CATEGORY EXPENSE",
            //                "mdlName" => "MdlPettycashStatic",
            //                "mdlFilter" => array("id=pihak2ID"),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "name",
            //                ),
            //                "editPoints" => array(1),
            //            ),
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1),
            ),
            "transaksi" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "service receipt note",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array("id=referenceID"),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "nomer",
                    //                    "suppliers_nama" => "vendor",
                ),
                "editPoints" => array(1),
            ),
        ),
        //        "relativeElements" => array(
        //            "category_expense" => array(
        //                "600000" => array(
        //                    "production_exp" => array(
        //                        "elementType" => "dataModel",
        //                        "inputType" => "combo",
        //                        "label" => "production expense",
        //                        "mdlName" => "MdlDtaBiayaProduksi",
        //                        "mdlFilter" => array(),
        //                        "key" => "id",
        //                        "labelSrc" => "nama",
        //                        "usedFields" => array(
        //                            "nama" => "nama",
        //                        ),
        //                        "editPoints" => array(1,),
        //                    ),
        //                ),
        //                "700000" => array(
        //                    "marketing_exp" => array(
        //                        "elementType" => "dataModel",
        //                        "inputType" => "combo",
        //                        "label" => "marketing expense",
        //                        "mdlName" => "MdlDtaBiayaUsaha",
        //                        "mdlFilter" => array(),
        //                        "key" => "id",
        //                        "labelSrc" => "nama",
        //                        "usedFields" => array(
        //                            "nama" => "nama",
        //                        ),
        //                        "editPoints" => array(1,),
        //                    ),
        //                ),
        //                "780000" => array(
        //                    "general_exp" => array(
        //                        "elementType" => "dataModel",
        //                        "inputType" => "combo",
        //                        "label" => "general expense",
        //                        "mdlName" => "MdlDtaBiayaUmum",
        //                        "mdlFilter" => array(),
        //                        "key" => "id",
        //                        "labelSrc" => "nama",
        //                        "usedFields" => array(
        //                            "nama" => "nama",
        //                        ),
        //                        "editPoints" => array(1,),
        //                    ),
        //                ),
        //            ),
        //        ),
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

        "itemsInjector" => array(
            "enabled" => true,
            "kolom" => array(
                "pihak3IDSrc" => "pihak2ID",
                "pihak3NameSrc" => "pihak2Name",
            ),
        ),
        "previewCtr" => "Create",

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9985re",
                "label" => "EDIT pemindahan biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9985rrj",
                "label" => "REJECT pemindahan biaya usaha",
            ),
        ),
    ),// transfer dari biaya usaha ke biaya produksi/umum
    //koreksi biaya ke ppv
    "9922" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "koreksi biaya ke ppv",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "koreksi biaya ke ppv",
                "actionLabel" => "koreksi",
                "source" => "",
                "target" => "9922r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "otorisasi koreksi biaya ke ppv",
                "actionLabel" => "approve koreksi",
                "source" => "9922r",
                "target" => "9922",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        //        "template" => "template/transaksi.html",
        "selectorModel" => "MdlJasa",
        "selectorSrcModel" => "MdlJasa",
        "selectedPrice" => array(
            //            "model" => "MdlHargaSupplies",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
        ),
        //        "selectorProcessor" => "_processSelectProduct/select",
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            //            "suppliers_nama" => "vendor",
            "nomer_top" => "Request number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett" => "total amount",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            //            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "Request number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                //            "suppliers_nama" => "vendor",
                "nomer_top" => "Request number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                //            "disc" => "discount",
                //            "ppn" => "ppn",
                //            "nett" => "total amount",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                //            "suppliers_nama" => "vendor",
                "nomer_top" => "Request number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                //            "disc" => "discount",
                //            "ppn" => "ppn",
                //            "nett" => "total amount",
                //------------------------
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
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
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
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
                //                "satuan" => "Satuan",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
                //                "satuan" => "Satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Amount",

            ),
            2 => array(
                "harga" => "Amount",

            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
            ),
            2 => array(
                "harga" => "Total Amount",
            ),

        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                //                "jml",
                //                "ppnFactor",
                //                "ppnPersen",
                //                "discPersen",
            ),
            2 => array(),

        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "amount",
        ),
        "shoppingCartRowValidators" => array(
            //            "pihakID" => "vendor ID",
            //            "pihakName" => "vendor name",
            //            "nilai_dpp_ppn" =>"DPP PPN"
        ),
        "shoppingCartFieldMidValidatorsComparison" => array(
            "harga" => "sumber",
            "expense__nilai" => "target",
        ),

        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc)",
            2 => "jml*(harga-disc)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "expense" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "EXPENSE",
                "mdlName" => "MdlLockerValue",
                "mdlFilter" => array(
                    "state=.active",
                    "jenis=.biaya",
                    "produk_id=.0",
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "jenis",
                "usedFields" => array(
                    "nilai" => "saldo",
                ),
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
        "allowedMainEdit" => array("1"),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9922re",
                "label" => "EDIT koreksi biaya ke ppv",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9922rrj",
                "label" => "REJECT koreksi biaya ke ppv",
            ),
        ),
    ),
    //request biaya bunga kepemegang saham dimatikan karena sudah digeser  ke modal pemegang saham
    // "4449" => array(
    //     "icon" => "fa fa-money",
    //     "label" => "Auto Loan Interest",
    //     "place" => "center",//=> "center",
    //     "steps" => array(
    //         1 => array(
    //             "label" => "Request Payment Loan Interest",
    //             "actionLabel" => "Request Loan Interest",
    //             "source" => "",
    //             "target" => "4449r",
    //             "userGroup" => "sys",
    //             "stateLabel" => "pending approval",
    //             "stateColor" => "#dd3300",
    //             "stateCaption" => "Prepare by",
    //         ),
    //         2 => array(
    //             "label" => "Approved A/P Loan Interest",
    //             "actionLabel" => "approve & create A/P Loan Interest",
    //             "source" => "4449r",
    //             "target" => "4449",
    //             "userGroup" => "c_finance",
    //             "stateLabel" => "approved",
    //             "stateColor" => "#ff7700",
    //             "stateCaption" => "Acknowledge by",
    //             "allowEdit" => true,
    //         ),
    //     ),
    //     "shoppingCartMeasurement" => array(),
    //     "template" => "template/transaksi.html",
    //     "selectorModel" => "MdlProduk2",
    //     "selectorSrcModel" => "MdlProduk2",
    //     "selectedPrice" => array(
    //         "model" => "MdlHargaProduk",
    //         "label" => array("jual", "ppv", "disc", "disc_percent"),
    //         "key_label" => array(
    //             "jual" => "harga",
    //             "ppv" => "ppv",
    //             "disc" => "disc",
    //             "disc_percent" => "disc (%)",
    //         ),
    //         "mainSrc" => "jual",
    //     ),
    //     "lockerCheck" => array(),
    //     "selectorFilters" => array(
    //         //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
    //         //            "jumlah>0",
    //         //            "state='active'",
    //     ),
    //     "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
    //     "selectorLabel" => "item",
    //     "selectorParamFields" => array(
    //         "id" => "id",
    //         "nama" => "nama",
    //         "produk_kode" => "kode",
    //         "satuan" => "satuan",
    //     ),
    //     "selectorViewedFields" => array(
    //         //            "nama", "satuan",// "jumlah"
    //         //            "keterangan", "kode", "satuan",// "jumlah"
    //         "nama",
    //         "kode",
    //         "satuan",// "jumlah"
    //     ),
    //     "selectorProcessor" => "_processSelectProduct/select",
    //     "itemSwapper" => "_processSelectProduct/multiSelect",
    //     "swappedKeys" => array("pihakID", "pihakName"),
    //     "editHandlerMethod" => "select",
    //     "pihakModel" => "MdlCustomer_and_pre",
    //     "pihakCaller" => "_selectorPihak/selectPihak",
    //     "pihakLabel" => "customer",
    //     "pihakProcessor" => "_processPihak/select",
    //     "shortHistoryFields" => array(
    //         "jenis_label" => "activity",
    //         "dtime" => "date",
    //         "cabang_nama" => "branch",
    //         "customers_nama" => "a/n vendor",
    //         "nomer_top2" => "No. Pinjaman",
    //         "nilai_sisa" => "Nilai Pinjaman",
    //         "persen_bunga" => "bunga(%)<br>(tahunan)",
    //         "nilai_bunga" => "Nilai Bunga<br>(bulanan)",
    //         //            "nomer_top" => "No. Bunga",
    //         "nomer" => "receipt number",
    //         "oleh_nama" => "person",
    //         "nilai_pph23" => "nilai pph23<br>(15% dr nilai bunga)",
    //         //            "grand_total" => "total amount",
    //         "grand_total" => "total amount",
    //         //            "disc" => "discount",
    //         //            "ppn" => "ppn",
    //         //            "subtotal" => "total amount",
    //     ),
    //     "shortStatusFields" => array(
    //         "jenis_label" => "activity",
    //         "dtime" => "date",
    //         "status_next" => "status",
    //         "cabang_nama" => "branch",
    //         "customers_nama" => "kreditur",
    //         "nomer_top" => "SO number",
    //         "nomer" => "receipt number",
    //         "oleh_nama" => "person",
    //         "harga" => "amount",
    //         "nett2" => "total amount",
    //     ),
    //     "historyFields" => array(
    //         1 => array(
    //             "no" => "no",
    //             "dtime" => "date",
    //             "cabang_nama" => "branch",
    //             "customers_nama" => "kreditur",
    //             //                "customerDetails__kabupaten" => "kota",
    //             "review_details" => "review",
    //             "nomer_top" => "order number",
    //             "oleh_nama" => "person",
    //             "harga" => "amount",
    //             //                "disc" => "discount",
    //             //                "nett1" => "netto",
    //             //                "ongkir" => "shipping service",
    //             //                "grand_ppn" => "ppn",
    //             //                "new_net3" => "total amount",
    //             "print_label" => "tool",
    //         ),
    //         2 => array(
    //             "no" => "no",
    //             "dtime" => "date",
    //             "cabang_nama" => "branch",
    //             "customers_nama" => "kreditur",
    //             "review_details" => "review",
    //             "nomer_top" => "order number",
    //             "sales_name" => "sales",
    //             "oleh_nama" => "person",
    //             "harga" => "amount",
    //             //                "disc" => "discount",
    //             //                "nett1" => "netto",
    //             //                "ongkir" => "shipping service",
    //             //                "grand_ppn" => "ppn",
    //             //                "new_net3" => "total amount",
    //             "print_label" => "tool",
    //         ),
    //     ),
    //     "extHistoryFields" => array(
    //         1 => array(
    //             "review_details" => "id",
    //             "print_label" => "nomer",
    //         ),
    //         2 => array(
    //             "review_details" => "id",
    //             "print_label" => "nomer",
    //         ),
    //     ),
    //     "selectorFields" => array("id", "nama", "satuan"),
    //     "pihakFields" => array("id", "nama"),
    //     "shoppingCartFields" => array(
    //         1 => array(
    //             "nama" => "produk name",
    //             "jml" => "qty",
    //             //                "satuan" => "uom",
    //
    //         ),
    //         2 => array(
    //             "nama" => "produk name",
    //             "jml" => "qty",
    //             //                "satuan" => "uom",
    //         ),
    //     ),
    //     "shoppingCartFieldSrc" => array(
    //         "nama" => "nama",
    //         "produk_kode" => "kode",
    //         "label" => "label",
    //         "satuan" => "satuan",
    //         "ppn" => "harga*(10/100)",
    //         "berat_gross" => "berat_gross",
    //         "lebar_gross" => "lebar_gross",
    //         "panjang_gross" => "panjang_gross",
    //         "tinggi_gross" => "tinggi_gross",
    //         "volume_gross" => "volume_gross",
    //         "volume" => "volume",
    //         "berat" => "berat",
    //         "lebar" => "lebar",
    //         "tinggi" => "tinggi",
    //         "panjang" => "panjang",
    //     ),
    //     "shoppingCartNumFields" => array(
    //         1 => array(
    //             "harga" => "nilai bunga<br>(bulanan)",
    //         ),
    //         2 => array(
    //             "harga" => "nilai bunga<br>(bulanan)",
    //         ),
    //     ),
    //     "shoppingCartEditableFields" => array(
    //         1 => array(),
    //         2 => array(),
    //     ),
    //     "shoppingCartUnionSelectors" => array(),
    //     "shoppingCartKeyUpEvents" => array(),
    //     "shoppingCartFieldValidators" => array(),
    //     "shoppingCartRowValidators" => array(
    //         "pihakID" => "customer ID",
    //         "pihakName" => "customer name",
    //     ),
    //     "shoppingCartRowOptionalValidators" => array(),
    //     "shoppingCartAmountValue" => array(
    //         1 => "jml*(harga-disc+ppn)",//nett2
    //         2 => "jml*(harga-disc+ppn)",
    //     ),
    //     "shoppingCartHideSubamount" => array(
    //         1 => false,
    //         2 => false,
    //     ),
    //     "shoppingCartSumFields" => array(
    //         1 => array(
    //             "nilai_pph23" => "pph23 15%",
    //             "grand_total" => "grand total",
    //         ),
    //         2 => array(
    //             "nilai_pph23" => "pph23 15%",
    //             "grand_total" => "grand total",
    //         ),
    //     ),
    //     "receiptMesurementRows" => array(),
    //     "receiptElements" => array(
    //         //            "cash_account" => array(
    //         //                "elementType" => "dataModel",
    //         //                "inputType" => "radio",
    //         //                "label" => "cash account",
    //         //                "pairedModel" => array(
    //         //                    "mdlName" => "ComRekeningPembantuKas",
    //         //                    "mdlMethod" => "fetchBalances",
    //         //                    "mdlFilter" => array(
    //         //                        "cabang_id=placeID",
    //         //                    ),
    //         //                    "key" => "extern_id",
    //         //                    "rekening" => "kas",
    //         //                    "fieldID" => "debet",
    //         //                    "fieldLabel" => "saldo",
    //         //                ),
    //         //                "mdlName" => "MdlBankAccount_cash_and_in",
    //         //                "mdlFilter" => array(
    //         //                    "cabang_id=placeID",
    //         //                ),
    //         //                "key" => "id",
    //         //                "labelSrc" => "nama",
    //         //                "usedFields" => array(
    //         //                    "nama" => "account",
    //         //                    "saldo" => "balance",
    //         //                ),
    //         //                "editPoints" => array(1,2),
    //         //                "noValidate" => false,
    //         //            ),
    //     ),
    //     "relativeElements" => array(),
    //     "relativeOptions" => array(),
    //     "updateDueDate" => array(),
    //     "updateDownpayment" => array(),
    //     "validateDueDate" => array(),
    //
    //     "pairRegistries" => array(
    //         "tableIn_master_values",
    //         "main", "items"
    //     ),
    //     "pairMakers" => array(),
    //     "pairInjectors" => array(),
    //     "validationRules" => array(),
    //     "connectedDiscount" => array(),
    //     "additionalRows" => array(),
    //     "resumeFieldNames" => array(
    //         "selectFields" => "customers_nama",
    //         "title" => "customer",
    //     ),
    //     "settlementHistoryFields" => array(
    //         "dtime" => "time",
    //         "nomer" => "receipt number",
    //         "customers_nama" => "customer",
    //         "jenis_label" => "activity",
    //         "harga" => "orig. value",
    //         "nett1" => "nett",
    //         "nett2" => "total",
    //     ),
    //     "allowedMainEdit" => array("1"),
    //     "addData" => array(
    //         2 => array(
    //             "MdlNameMain" => "DComUpdateDetailsLoan",
    //             "MdlNameChild" => "MdlSetupLoanInterest",
    //             "gate" => "items",
    //             "fieldName" => array(
    //                 "extern_id" => "id",//diisi vendor /pihak
    //                 "transaksi_id" => "transaksi_id",//transaksi id
    //                 "nomer" => "nomer",//transaksi nomor
    //                 "extern_nama" => "nama",//pihak nama
    //                 "extern_value" => "harga",//nilai
    //                 "extern_value_2" => "persen_bunga",//bunga
    //             ),
    //         ),
    //     ),
    //     "previewCtr" => "Create",
    // ),

    //request imbalan jasa
    "119" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "imbalan jasa",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request imbalan jasa",
                "actionLabel" => "save",
                "source" => "",
                "target" => "119r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "authorization imbalan jasa",
                "actionLabel" => "approve request",
                "source" => "119r",
                "target" => "119",
                "userGroup" => "c_purchasing_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),

        ),
        "template" => "template/transaksi_pihak4.html",
        "selectorModel" => "MdlBiayaJasaStatic",
        "selectorSrcModel" => "MdlBiayaJasaStatic",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "allowed_ext=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "biaya",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        //        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        //region tambahan pihak rules misal selector ppn/pph
        "pihakModelMainRules" => "MdlBiayaJasaTaxesStatic",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "jenis pajak",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrcRules" => array(
            "taxesMethod" => "taxes_name",
            "taxesMethodCoa" => "coa_code",

        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",
        //endregion

        //region tambahan pihak2
        "mainselectorModel" => array(),
        "pihakModelMain" => "MdlBiayaJasaStatic",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "kategory biaya",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
            "comName_items" => "comName_items",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        //endregion

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
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                //------------------------
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                //------------------------
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
                "nama" => "item name",
                "jml" => "qty",
                "reference" => "reference",
                //                "pph_persen_ext" => "tarif(%)",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "reference" => "reference",
                //                "pph_persen_ext" => "tarif(%)",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                "ppnPersen" => "pph21 (%)",
                "ppn" => "pph21 (IDR)",
            ),
            2 => array(
                "harga" => "Price",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                // "ppv" => "index",
                "ppn" => "pph21",
                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga" => "Total Amount",
                // "ppv" => "index",
                "ppn" => "pph21",
                "hpp_nppn" => "Grand Total",
            ),
            3 => array(
                //                "harga" => "Total Amount",
                //                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
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
                "ppn",
                "ppnPersen",
                "reference",
            ),
            2 => array(),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => false,
        "shoppingCartImageType" => "images",
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang",
            "pihakName" => "cabang",
            "pihakMainName" => "jenis biaya",
            "pihakMainRulesName" => "jenis pajak",


        ),
        "receiptElements" => array(),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "ppnPersen",
                "members" => array(
                    "ppnPersen",
                    "ppn",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "ppnPersen" => "document.getElementById('{ppn}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').value))/100)",
                "ppn" => "document.getElementById('{ppnPersen}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').value))*100)",
            ),
        ),
        "keyupAction" => true,
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "harus_bayar" => array(
                        "label" => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue" => "(sisa-creditAmount-creditValue)",
                        "minValue" => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(removeCommas(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "119re",
                "label" => "EDIT request imbalan jasa",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "119rrj",
                "label" => "REJECT request imbalan jasa",
            ),
        ),
    ),
    //  config request expense/biaya produksi
    "676" => array(
        "icon" => "fa fa-money",
        "label" => "biaya produksi",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request biaya produksi",
                "actionLabel" => "request for expense",
                "source" => "",
                "target" => "676r",
                "userGroup" => "p_produksi",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "authorization biaya produksi",
                "actionLabel" => "approve request",
                "source" => "676r",
                "target" => "676",
                "userGroup" => "p_produksi_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        //        "template" => "template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlDtaBiayaProduksi",
        "selectorSrcModel" => "MdlDtaBiayaProduksi",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya produksi",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "expense name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang",
            // "pihakName" => "pihak name",
        ),

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
        "connectTo" => "2676",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "676re",
                "label" => "EDIT request biaya produksi",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "676rrj",
                "label" => "REJECT request biaya produksi",
            ),
        ),
    ),

    "2762" => array(
        "icon" => "fa fa-circle",
        "label" => "pembiayaan supplies (branch)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "pembiayaan supplies",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "2762r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "approval pembiayaan supplies",
                "actionLabel" => "approve biaya",
                "source" => "2762r",
                "target" => "2762",
                "userGroup" => "o_gudang",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        //        "template" => "template/transaksi.html",
        "template" => "template/transaksi_supplies_biaya.html",
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
        "pihakFilters" => array(
            "id=cabang_id",
        ),
        "pihakProcessor" => "_processPihak/select",

        "pihakModel2" => "MdlPettycashStatic",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "kategori biaya",
        "pihakFilters2" => array(),
        "pihakProcessor2" => "_processPihak/select2",
        "pihakMainValueSrc" => array(
            "pihakMainNameCoa" => "coa_code",
        ),

        "pihakModel3" => "MdlDtaBiayaUsaha",
        "pihakCaller3" => "_selectorPihak/selectPihak3",
        "pihakLabel3" => "detail biaya",
        "pihakFilters3" => array(),
        "pihakProcessor3" => "_processPihak/select3",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch ID",
            "pihakName" => "branch name",
            "pihak2ID" => "category expense",
            "pihak2Name" => "category expense",
            "pihak3ID" => "detail expense",
            "pihak3Name" => "detail expense",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartAvoidRemove" => false,
        "resumeFieldNames" => array(
            "selectFields" => "cabang_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),

        "receiptElements" => array(
            "category_expense" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CATEGORY EXPENSE",
                "mdlName" => "MdlPettycashStatic",
                "mdlFilter" => array("id=pihak2ID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                ),
                "editPoints" => array(1),
            ),
            //            "detail_expense" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "DETAIL EXPENSE",
            //                "mdlName" => "MdlPettycashStatic",
            //                "mdlFilter" => array("id=pihak2ID"),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "name",
            //                ),
            //                "editPoints" => array(1),
            //            ),
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
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items",
                    "key" => "pihak3Name",
                ),
                "hppSupplies" => array(
                    "helperName" => "he_cek_stock_supplies_hpp",
                    "functionName" => "cekStockSuppliesHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
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
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items",
                    "key" => "pihak3Name",
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
                "preBiaya" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "costName",
                    ),
                ),
                "hppSupplies" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "harga",
                    ),
                ),
            ),
            2 => array(
                "stokSupplies" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "preBiaya" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "costName",
                    ),
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2762re",
                "label" => "EDIT pembiayaan supplies",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2762rrj",
                "label" => "REJECT pembiayaan supplies",
            ),
        ),
    ),
    //pendapatan lain lain
    "742" => array(
        "icon" => "fa fa-money",
        "label" => "pendapatan lain-lain",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "pendapatan lain-lain",
                "actionLabel" => "save",
                "source" => "",
                "target" => "742",
                "userGroup" => "c_finance",
                "stateLabel" => "save",
                "stateColor" => "#dd3300",
                "stateCaption" => "entry by",
            ),

        ),
        "template" => "template/transaksi_nopihak.html",
        //        "template" => "application/template/transaksi.html",
        "selectorModel" => "MdlDtaSubPendapatan",
        "selectorSrcModel" => "MdlDtaSubPendapatan",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama pendapatan",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        //        "selectorProcessor" => "Selectors/_processSelectBiaya/select",
        "selectorProcessor" => "_processSelectPendapatan/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            //            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
                "nama" => "name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

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

            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",

                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "allowedMainEdit" => array(),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "742e",
                "label" => "EDIT pendapatan lain-lain",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "742rj",
                "label" => "REJECT pendapatan lain-lain",
            ),
        ),
    ),
    //biaya lain lain
    "743" => array(
        "icon" => "fa fa-money",
        "label" => "biaya lain-lain",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "biaya lain-lain",
                "actionLabel" => "save",
                "source" => "",
                "target" => "743",
                "userGroup" => "c_finance",
                "stateLabel" => "save",
                "stateColor" => "#dd3300",
                "stateCaption" => "entry by",
            ),

        ),
        "template" => "template/transaksi_nopihak.html",
        //        "template" => "application/template/transaksi.html",
        "selectorModel" => "MdlDtaBebanLainLain",
        "selectorSrcModel" => "MdlDtaBebanLainLain",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(//            "tipe=.local",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nama biaya",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        //        "selectorProcessor" => "Selectors/_processSelectBiaya/select",
        "selectorProcessor" => "_processSelectPendapatan/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            //            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
                "nama" => "name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "name",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(),

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

            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",

                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "allowedMainEdit" => array(),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "743e",
                "label" => "EDIT biaya lain-lain",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "743rj",
                "label" => "REJECT biaya lain-lain",
            ),
        ),
    ),
    //salarry expense pusat
    "2674" => array(
        "icon" => "fa fa-money",
        "label" => "salary expense (pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "salary expense request",
                "actionLabel" => "request for salary expense",
                "source" => "",
                "target" => "2674r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "salary expense authorization",
                "actionLabel" => "approve request",
                "source" => "2674r",
                "target" => "2674",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        //        "template" => "application/template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlGaji",
        "selectorSrcModel" => "MdlGaji",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "tipe=.gaji",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "local expense name",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
        "selectorProcessor" => "_processSelectRekeningGaji/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "salary expense",
        "pihakFilters" => array(
            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "hutang_gaji" => "amount",
            "hutang_pph21" => "pph 21",
            "harga" => "total amount",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
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
                "nama" => "name",
            ),
            2 => array(
                "nama" => "name",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "amount",
            ),
            2 => array(
                "harga" => "amount",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "branch/center ID",
            "pihakName" => "branch/center name",

        ),
        "followupItemEditable" => "_followupLiveEdit/updateItemExpense/",
        "receiptElements" => array(
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

            //            "extern1" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "extern 1",
            //                "mdlName" => "MdlExtern",
            //                "mdlFilter" => array("relName=srcRel"),
            //                "key" => "extern_id",
            //                "labelSrc" => "extern_nama",
            //                "usedFields" => array(
            //                    "extern_nama" => "account name",
            //                ),
            //                "editPoints" => array(1),
            //            ),
            //            "extern2" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "extern 2",
            //                "mdlName" => "MdlExtern",
            //                "mdlFilter" => array("relName=srcRel"),
            //                "key" => "extern_id",
            //                "labelSrc" => "extern_nama",
            //                "usedFields" => array(
            //                    "extern_nama" => "account name",
            //                ),
            //                "editPoints" => array(1),
            //            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
        "autoSelectItem" => true,
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2674re",
                "label" => "EDIT salary expense request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2674rrj",
                "label" => "REJECT salary expense request",
            ),
        ),
    ),


    //biaya project
    "3674" => array(
        "icon" => "fa fa-money",
        "label" => "request biaya project",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "reques biaya project",
                "actionLabel" => "reques biaya project",
                "source" => "",
                "target" => "3674r",
                "userGroup" => "sys", //original
//                "userGroup" => "o_finance", //untuk testing manual
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "followup biaya project",
                "actionLabel" => "followup biaya project",
                "source" => "3674r",
                "target" => "3674",
                "userGroup" => "o_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
                "allowEdit" => true,
                "autoNextStep" => true,// true maka otomatis otorisasi, false maka otorisasi manual"autoNe"
            ),
        ),
//        "template" => "template/transaksi.html",
        "template" => "template/transaksi2.html",
        "selectorModel" => "MdlDtaBiayaProject",
        "selectorModelAuto" => "MdlProjectKomponenBiayaDetailsRabSub",
        "selectorSrcModel" => "MdlDtaBiayaProject",
        "selectorSrcModelAuto" => "MdlProjectKomponenBiayaDetailsRabSub",
        "selectorSrcModelDetails" => "MdlProjectKomposisiWorkorderSub",
//        "selectorSrcModel" => "MdlDtaBiayaProject",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
//            "tipe=.gaji",
//            "project_id=pihakProjekID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "local expense name",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "employee_nama",
        ),
        "selectorProcessor" => "_processSelectRekeningGaji/select",
        "selectorProcessor2" => "_processPihak/selectSubBiaya",
        "editHandlerMethod" => "select",
        "shoppingCartPairedItemRecorder" => "recordPairedItem",

        "pihakModel" => "MdlProdukProject",
        "pihakCaller" => "_selectorPihak/selectPihakProjek",
        "pihakLabel" => "pilih project",
        "pihakViewedFields" => array(
            "spek",
//            "nama",
//            "transaksi_no_app",
        ),
        "pihakFilters" => array(
            "status=.1",
            "trash=.0",
//            "customer_id=customerProjek",
            "transaksi_id>.0",
            "closing_status=.0",
            "project_start=.1",
//            "uang_muka_approved>.0",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/selectProjek",

        // WO
        "pihakModel2" => "MdlTasklistProject",
        "pihakCaller2" => "_selectorPihak/selectPihakWoProjek",
        "pihakLabel2" => "workorder project",
        "pihakProcessor2" => "_processPihak/selectWoProjek",
        "pihakFilters2" => array(
            "status=.1",
            "trash=.0",
            "produk_id=pihakProjekID",
        ),
        "autoSelectItem2" => true,
        "pihakModelBiaya2" => "MdlProjectKomponenBiayaDetailsRabSub",
        "pihakModelWoProjek" => "MdlTasklistProject",
        "pihakWoProjekCaller" => "_selectorPihak/selectPihakWoProjek",
        "pihakWoProjekLabel" => "workorder project",
        "pihakWoProjekViewedFields" => array(
            "produk_nama",
            "employee_nama",
            "no_spk",
            "post_biaya_no",
        ),
        "pihakWoProjekFilters" => array(
            "status=.1",
            "trash=.0",
            "produk_id=pihakProjekID",
        ),
        "pihakWoProjekProcessor" => "_processPihak/selectWoProjek",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
//            "mdlName" => "MdlProduk2",
            "mdlName" => "MdlDtaBiayaProject",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2",
        ),
        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "hutang_gaji" => "amount",
//            "hutang_pph21" => "pph 21",
            "piutang_cabang" => "total amount",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
//            "biaya_nama" => "nama",
//            "label" => "label",
//            "reference" => "reference",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "name",
            ),
            2 => array(
                "nama" => "name",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "nama barang/ jasa",
            ),
            2 => array(
                "nama" => "nama barang/ jasa",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "harga" => "harga",
//                "jml" => "qty",
            ),
            2 => array(
                "harga_anggaran" => "harga anggaran",
                "harga" => "harga rill",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNumFields2" => array(
            1 => array(
                "harga" => "harga",
                "jml" => "qty",
            ),
            2 => array(
                "harga" => "harga",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
//                "harga",
//                "jml",
//                "reference",
            ),
            2 => array(
//                "harga",
                // "jml",
//                "reference",
            ),
        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "harga",
                "jml",
//                "reference",
            ),
            2 => array(
//                "harga_anggaran",
                "harga",
                "jml",
//                "reference",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
        ),
        "shoppingCartHideSubamount2" => array(
            1 => false,
            2 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "direct_labor" => "direct labor*",
                "delivery_cost" => "delivery cost*",
                "quality" => "quality*",
                "piutang_cabang" => "total*",
                // "hutang_bpjs" => "bpjs",
                // "hutang_pph21" => "pph 21",
                // "grand_total" => "Grand Total",
            ),
            2 => array(
                "direct_labor" => "direct labor**",
                "quality" => "quality",
                "delivery_cost" => "delivery cost",
                "piutang_cabang" => "total",
                // "harga" => "Amount",
                // "disc" => "Disc",
                // "ppn" => "VAT",
                // "grand_total" => "Grand Total",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakProjekID" => "project",
            "pihakWoProjek" => "project SPK",
//            "pihakID" => "branch/center ID",
//            "pihakName" => "branch/center name",
        ),
        "followupItemEditable" => "_followupLiveEdit/updateItemExpense/",
        "receiptElements" => array(
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
            //            "extern1" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "extern 1",
            //                "mdlName" => "MdlExtern",
            //                "mdlFilter" => array("relName=srcRel"),
            //                "key" => "extern_id",
            //                "labelSrc" => "extern_nama",
            //                "usedFields" => array(
            //                    "extern_nama" => "account name",
            //                ),
            //                "editPoints" => array(1),
            //            ),
            //            "extern2" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "extern 2",
            //                "mdlName" => "MdlExtern",
            //                "mdlFilter" => array("relName=srcRel"),
            //                "key" => "extern_id",
            //                "labelSrc" => "extern_nama",
            //                "usedFields" => array(
            //                    "extern_nama" => "account name",
            //                ),
            //                "editPoints" => array(1),
            //            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "previewCtr" => "Create",
        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
//        "autoSelectItem" => true,
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

//        "shoppingCartPairedItemBreakDownPartValidator" => array(
//            1 => array(
//                "enabled" => true,
//                "source" => "items",
//                "target" => "items2",
//            ),
//        ),

        /*
         * connect to untuk transaksi yang belum punya ppn akan faktur belum ready
     */

//        "connectTo" => "3675",
        "connectoValidate" => array(
            2 => "pihakWoProjek",
        ),
        "replacerConnectTo" => array(
//            "cabang2ID" => "-1",
//            "cabang2Name" => "pusat",
//            "place2ID" => "-1",
//            "place2Name" => "pusat",
//            "gudang2ID" => "-1",
//            "gudang2Name" => "default center warehouse",
//            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),
//        "clonerTransaction"=>array(
//            1 => array(
//                "main" => array(
//                    "cloner" => true,
//                ),
//                "itemToMaster" => array(
////                    "nama" => "transaksi_jenis2_label",
////                    "produk_kode" => "transaksi_jenis2_kode",
////                    "sub_harga" => "transaksi_jenis2_value",
//                    //"sub_ppn" => "transaksi_jenis2_value_ppn",
//                    //                    "sub_harga_nppn"=>"transaksi_jenis2_value_nppn",
//                    //                    "transaksi_jenis" =>"paket",
//                ),
//                "staticItemToMaster" => array(
////                    "transaksi_jenis2" => "paket",
//                ),
//                "details" => array(
////                    "harga" => "harga",
////                    // "jual_nppn" => "jual_nppn",
////                    "hpp" => "hpp",
////                    "disc" => "disc",
////                    // "ppn" => "ppn",
////                    "harga1" => "harga1",
////                    "harga_nett1" => "harga_nett1",
////                    "harga2" => "harga2",
////                    "harga_nett2" => "harga_nett2",
//                ),
//                "resetGate" => array(
////                    "items2",
////                    "items2_sum",
////                    "receiptSumFields2",
////                    "receiptDetailFields2",
//                ),
//            ),
//        ),
        //----
        "connectToEdit" => array(
            2 => array(
                "enabled" => true,
                "connectTo" => "1674re",
                "label" => "EDIT salary expense request",
            ),
        ),
        "connectToReject" => array(
            2 => array(
                "enabled" => true,
                "connectTo" => "1674rrj",
                "label" => "REJECT salary expense request",
            ),
        ),
    ),
    //--------- ke atas sudah modul ---------------------------


    //  config request cashback penjualan (biaya)
    "6677_OLD" => array(
        "icon" => "fa fa-money",
        "label" => "cashback penjualan (biaya)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request cashback penjualan (biaya)",
//                "label_nota21" => "otorisasi komisi penjualan",
//                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "6677",
                "userGroup" => "o_kasir",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
        ),
//        "template" => "template/transaksi.html",
        "template" => "template/transaksi_pihak2.html",
        //-------------------------
        "selectorModel" => "MdlTransaksiData",
        "selectorSrcModel" => "MdlTransaksiData",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "customers_id=pihakID",
            "jenis=.4822",
            "trash_4=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih invoice konsumen",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
            "fulldate" => "fulldate",
            "dtime" => "dtime",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectBiaya/selectInv",
        "editHandlerMethod" => "selectInv",
        "pairSelectorItem" => array(
            "enabled" => true,
            "mdl" => "MdlLockerTransaksi",
            "filter" => array(
                "jenis=.komisi",
                "cabang_id=placeID",
                "jumlah=.1",
            ),
        ),
        //-------------------------
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih konsumen...",
        "pihakFilters" => array(//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(),
        "pihakProcessor" => "_processPihak/select",
        "pihakKoreksiData" => array(
            "enabled" => true,
            "kolom" => array(
                "folder_id" => "Jenis Konsumen {konsumen_nama} belum ditentukan (Corporate atau Perorangan). ",
            ),
            "link" => base_url() . "statik/Data/edit/Customer/",
        ),
        //-------------------------
        "pihakModelMainRules" => "MdlEmployeeFreelanceCabang",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "pilih freelancer anda...",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
//

        ),
        "pihakMainFiltersRulesAdd" => array(
            "enabled" => false,// tgl 10 april 2025, komisi freelancer pph21, biaya konsultasi pph23, cashback pph23 bisa dipilih bebas.
            "filter" => array(
                "jenis_konsumen=pihakJenis",
            ),
            "exception" => array(
                "3",// lain-lain, tidak masuk filter atau keluar semua, silahkan dipilih (komisi freelancer, biaya konsultasi, cashback)
            ),
        ),
        "pihakMainValueSrcRules" => array(
//            "taxesMethod" => "taxes_name",
//            "taxesMethodCoa" => "coa_code",
//
//
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",
        "pihakModelMainRulesAuto" => array(
            "key" => "pph21",
            "gate" => "pajakOption",
            "mdl" => "MdlEmployeeFreelanceCabang",
        ),
        "noResetGate" => false,
        //-------------------------
        "autoSelectPPh" => array(
            "enabled" => true,
            "key" => "customerDetails__npwp",
            "key_deteksi" => "pihakMainID",
            "tipe_biaya" => array(
                "72" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph23",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
                        "nilai_kas_cn" => "jumlah diterima perusahaan",
                    ),
                ),
                "73" => array(
                    "elName" => "pph21Methode",
                    "mdlName" => "MdlPph21MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph21",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 21.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2.50%, tidak punya NPWP: 3%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "Tidak bisa didetailkan nama per-orang karena jika detail sudah masuk modul HRIS.",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 21 (%)",
                        "nilai_pph_original" => "PPh 21",
                        "nilai_kas_cn" => "jumlah diterima freelancer",
                    ),
                ),
                "74" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodCashback",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption2",
                    "subKey" => "pph23_15",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
                        "nilai_kas_cn" => "jumlah diterima perusahaan",
                    ),
                ),

            ),
            "resetor" => array(
                "pph21Methode",
                "pph21Methode__label",
                "pph21Methode__name",
                "pph21Methode__tarif",
                "pph23Methode",
                "pph23Methode__label",
                "pph23Methode__name",
                "pph23Methode__tarif",
            ),
        ),
        //----


        //region tambahan pihak2
        "autoLoadPihakMain" => true,
        "mainselectorModel" => array(),
        "pihakModelMain" => "MdlDtaBiayaUsahaKomisi",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "pilih biaya komisi perorangan/perusahaan",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
            "comName_items" => "comName_items",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        //endregion


        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            //------------------------
//            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "extHistoryFields" => array(
            1 => array(
                "print_label" => "nomer",
            ),
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "label" => "label",
            "reference" => "reference",
            //----
            "nomer" => "nomer",
            "idtime" => "dtime",
            "ifulldate" => "fulldate",
            //----
            "inv_new_net3" => "inv_new_net3",
            "inv_grand_ppn" => "inv_grand_ppn",
            "inv_new_net1" => "inv_new_net1",
            "inv_dpp_pengganti" => "inv_dpp_pengganti",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nomer inv",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "nama" => "nomer inv",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",

            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "pph__tarif" => "PPh (%)",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "pph__tarif" => "PPh (%)",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
//                "harga",
//                "jml",
                "nilai_kas_cn",
            ),
            2 => array(),
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
//                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
//                "nett1" => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",

//                "harga" => "document.getElementById('{nilai_kas_cn}').value=((100-parseFloat(removeCommas(document.getElementById('{pph23Methode__tarif}').innerHTML))/100)*parseFloat(removeCommas(document.getElementById('{harga}').value)))",
                "harga" => "document.getElementById('{nilai_kas_cn}').value=(((100-parseFloat(removeCommas(document.getElementById('{pph__tarif}').innerHTML)))/100)*parseFloat(removeCommas(document.getElementById('{harga}').value)))",
                "nilai_kas_cn" => "document.getElementById('{harga}').value=((100/(100-parseFloat(removeCommas(document.getElementById('{pph__tarif}').innerHTML))))*parseFloat(removeCommas(document.getElementById('{nilai_kas_cn}').value)))",

            ),
        ),
        "keyupAction" => true,
        "shoppingCartFieldSrcAdditional" => array(//4464
//            "inv_new_net3" => "new_net3",
//            "inv_grand_ppn" => "grand_ppn",
//            "inv_new_net1" => "new_net1",
            "inv_new_net3" => "sub_nett",
            "inv_grand_ppn" => "sub_ppn",
            "inv_new_net1" => "sub_nett-sub_ppn",
            "inv_dpp_pengganti" => "dpp_pengganti",
        ),

        "shoppingCartFieldSrcAdditional2" => array(//5822spd, 5823spd
            "inv_grand_ppn" => "grand_ppn",
            "inv_new_net1" => "new_net1",
//            "inv_new_net3" => "new_net3",//
            "inv_new_net3" => "new_net1+grand_ppn",//
//            "inv_new_net3" => "sub_nett",
//            "inv_grand_ppn" => "sub_ppn",
//            "inv_new_net1" => "sub_nett-sub_ppn",
            "inv_dpp_pengganti" => "dpp_pengganti",
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "biaya cashback/komisi",
            "nilai_kas_cn" => "kas/creditnote yang diberikan ke konsumen",
            "pph__tarif" => "tarif pph",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),

        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Konsumen",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
//                    "kredit_limit" => "KREDIT LIMIT",
                    "folder_nama" => "jenis konsumen",
                ),
                "editPoints" => array(1, 2),
                "reloadLink" => "_processPihak/select/",
            ),

            "biayaDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Biaya",
                "mdlName" => "MdlDtaBiayaUsahaKomisi",
                "mdlFilter" => array("id=pihakMainID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",

                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
            "kompensasiMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Metode Cashback",
                "mdlName" => "MdlKompensasiCashbackPenjualan",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    1 => "ReComKompensasiMethod",
                    2 => "ReComKompensasiMethod",
                ),
            ),


            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),

            "pajakOption" => array(
                "elementType" => "dataModel",
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption2",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
                // bila dpp pph > 0, maka menjadi mandatori.
                // bila dpp pph <= 0, maka menjadi tidak mandatori.
//                "noValidateReplacer" => array(
//                    "key" => "dppPPh",
//                ),
            ),

        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                1 => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1, 4),
                    ),

                ),
                2 => array(),
            ),
            "pajakOption" => array(
                "pph21" => array(
                    "pph21Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_penjualan",
                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
//                            "sket" => "ReComPph21None_purchasing",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph21Methode",
                        ),
                    ),
                    "freelancerDetails" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Detail Freelancer",
                        "mdlName" => "MdlEmployeeFreelanceCabang",
                        "mdlFilter" => array(
                            "id=pihakMainRulesID",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "alamat_1" => "alamat",
                            "alamat_2" => "alamat",
                            "kelurahan" => "Kel",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "Prop",
                            "tlp" => "Tlp",
                            "tlp_1" => "Tlp",
                            "tlp_2" => "Handphone",
                            "npwp" => "NPWP",
                            "no_ktp" => "nik",
                            "nik" => "NIK",
                        ),
                        "editPoints" => array(1, 2),
                        "reloadLink" => "_processPihak/select/",
                    ),
                ),
                "pph23" => array(
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                ),
                "pph23_15" => array(
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodCashback",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "connectTo" => "16677",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "pihakName" => "konsumen",
            "6677" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
        ),

    ),
    "6677" => array(
        "icon" => "fa fa-money",
        "label" => "cashback penjualan (biaya)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request cashback penjualan (biaya)",
//                "label_nota21" => "otorisasi komisi penjualan",
//                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "6677",
                "userGroup" => "o_kasir",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
        ),
//        "template" => "template/transaksi.html",
        "template" => "template/transaksi_pihak2.html",
        //-------------------------
        "selectorModel" => "MdlTransaksiData",
        "selectorSrcModel" => "MdlTransaksiData",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "customers_id=pihakID",
            "jenis=.4822",
            "trash_4=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih invoice konsumen",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
            "fulldate" => "fulldate",
            "dtime" => "dtime",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectBiaya/selectInv",
        "editHandlerMethod" => "selectInv",
        "pairSelectorItem" => array(
            "enabled" => true,
            "mdl" => "MdlLockerTransaksi",
            "filter" => array(
                "jenis=.komisi",
                "cabang_id=placeID",
                "jumlah=.1",
            ),
        ),
        //-------------------------
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih konsumen...",
        "pihakFilters" => array(//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(),
        "pihakProcessor" => "_processPihak/select",
        "pihakKoreksiData" => array(
            "enabled" => true,
            "kolom" => array(
                "folder_id" => "Jenis Konsumen {konsumen_nama} belum ditentukan (Corporate atau Perorangan). ",
            ),
            "link" => base_url() . "statik/Data/edit/Customer/",
        ),
        //-------------------------
        "pihakModelMainRules" => "MdlEmployeeFreelanceCabang",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "pilih freelancer anda...",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
//

        ),
        "pihakMainFiltersRulesAdd" => array(
            "enabled" => false,// tgl 10 april 2025, komisi freelancer pph21, biaya konsultasi pph23, cashback pph23 bisa dipilih bebas.
            "filter" => array(
                "jenis_konsumen=pihakJenis",
            ),
            "exception" => array(
                "3",// lain-lain, tidak masuk filter atau keluar semua, silahkan dipilih (komisi freelancer, biaya konsultasi, cashback)
            ),
        ),
        "pihakMainValueSrcRules" => array(
//            "taxesMethod" => "taxes_name",
//            "taxesMethodCoa" => "coa_code",
//
//
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",
        "pihakModelMainRulesAuto" => array(
            "key" => "pph21",
            "gate" => "pajakOption",
            "mdl" => "MdlEmployeeFreelanceCabang",
        ),
        "noResetGate" => false,
        //-------------------------
        "autoSelectPPh" => array(
            "enabled" => true,
            "key" => "customerDetails__npwp",
            "key_deteksi" => "pihakMainID",
            "tipe_biaya" => array(
                "72" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph23",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti jenis biaya akan menghapus data yang sudah diisi di formulir.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
                        "nilai_kas_cn" => "jumlah diterima perusahaan",
                    ),
                ),
                "73" => array(
                    "elName" => "pph21Methode",
                    "mdlName" => "MdlPph21MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph21",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 21.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2.50%, tidak punya NPWP: 3%).",
                    "sub_label_info_pph" => "Mengganti jenis biaya akan menghapus data yang sudah diisi di formulir.",
                    "sub_label_freelancer" => "Tidak bisa didetailkan nama per-orang karena jika detail sudah masuk modul HRIS.",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 21 (%)",
                        "nilai_pph_original" => "PPh 21",
                        "nilai_kas_cn" => "jumlah diterima freelancer",
                    ),
                ),
                "74" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodCashback",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption2",
                    "subKey" => "pph23_15",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti jenis biaya akan menghapus data yang sudah diisi di formulir.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
                        "nilai_kas_cn" => "jumlah diterima perusahaan",
                    ),
                ),

            ),
            "resetor" => array(
                "pph21Methode",
                "pph21Methode__label",
                "pph21Methode__name",
                "pph21Methode__tarif",
                "pph23Methode",
                "pph23Methode__label",
                "pph23Methode__name",
                "pph23Methode__tarif",
            ),
        ),
        //----opsi freelancer MEMBER/NON MEMBER
        "optionFreelancerShow" => array(
            "key" => "pajakOption",
            "pajakOption" => array("pph23", "pph23_15"),
        ),
        "optionFreelancerModel" => "MdlEmployeeFreelanceOption",
        "optionFreelancerCaller" => "_selectorPihak/selectOptionFreelancer",
        "optionFreelancerProcessor" => "_processPihak/selectOptionFreelancer",
        "optionFreelancerReset" => array(// mereset element dan gerbang nilai/session
            "kompensasiMethod",
            "cash_account",
        ),
        "optionFreelancerLabel" => "Pilih Status Freelancer (Member / Non Member)",
        //----
        "addData" => array(
            "enabled" => true,
            "key" => "optionFreelancerID",// 1 = member (tampil), 2 non member (tidak tampil)
            "link" => "statik/Data/add/EmployeeFreelanceCabang",
        ),
        //----


        //region tambahan pihak2
        "autoLoadPihakMain" => true,
        "mainselectorModel" => array(),
        "pihakModelMain" => "MdlDtaBiayaUsahaKomisi",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "pilih biaya komisi perorangan/perusahaan",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
            "comName_items" => "comName_items",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        //endregion


        "shortHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "pihakName" => "konsumen",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            //------------------------
//            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "extHistoryFields" => array(
            1 => array(
                "print_label" => "nomer",
            ),
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "label" => "label",
            "reference" => "reference",
            //----
            "nomer" => "nomer",
            "idtime" => "dtime",
            "ifulldate" => "fulldate",
            //----
            "inv_new_net3" => "inv_new_net3",
            "inv_grand_ppn" => "inv_grand_ppn",
            "inv_new_net1" => "inv_new_net1",
            "inv_dpp_pengganti" => "inv_dpp_pengganti",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nomer inv",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "nama" => "nomer inv",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",

            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "pph__tarif" => "PPh (%)",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "pph__tarif" => "PPh (%)",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
//                "harga",
//                "jml",
                "nilai_kas_cn",
            ),
            2 => array(),
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
//                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
//                "nett1" => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",

//                "harga" => "document.getElementById('{nilai_kas_cn}').value=((100-parseFloat(removeCommas(document.getElementById('{pph23Methode__tarif}').innerHTML))/100)*parseFloat(removeCommas(document.getElementById('{harga}').value)))",
                "harga" => "document.getElementById('{nilai_kas_cn}').value=(((100-parseFloat(removeCommas(document.getElementById('{pph__tarif}').innerHTML)))/100)*parseFloat(removeCommas(document.getElementById('{harga}').value)))",
                "nilai_kas_cn" => "document.getElementById('{harga}').value=((100/(100-parseFloat(removeCommas(document.getElementById('{pph__tarif}').innerHTML))))*parseFloat(removeCommas(document.getElementById('{nilai_kas_cn}').value)))",

            ),
        ),
        "keyupAction" => true,
        "shoppingCartFieldSrcAdditional" => array(//4464
//            "inv_new_net3" => "new_net3",
//            "inv_grand_ppn" => "grand_ppn",
//            "inv_new_net1" => "new_net1",
            "inv_new_net3" => "sub_nett",
            "inv_grand_ppn" => "sub_ppn",
            "inv_new_net1" => "sub_nett-sub_ppn",
            "inv_dpp_pengganti" => "dpp_pengganti",
        ),

        "shoppingCartFieldSrcAdditional2" => array(//5822spd, 5823spd
            "inv_grand_ppn" => "grand_ppn",
            "inv_new_net1" => "new_net1",
//            "inv_new_net3" => "new_net3",//
            "inv_new_net3" => "new_net1+grand_ppn",//
//            "inv_new_net3" => "sub_nett",
//            "inv_grand_ppn" => "sub_ppn",
//            "inv_new_net1" => "sub_nett-sub_ppn",
            "inv_dpp_pengganti" => "dpp_pengganti",
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "biaya cashback/komisi",
            "nilai_kas_cn" => "kas/creditnote yang diberikan ke konsumen",
            "pph__tarif" => "tarif pph",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),

        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Konsumen",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
//                    "kredit_limit" => "KREDIT LIMIT",
                    "folder_nama" => "jenis konsumen",
                ),
                "editPoints" => array(1, 2),
                "reloadLink" => "_processPihak/select/",
            ),
            "biayaDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Biaya",
                "mdlName" => "MdlDtaBiayaUsahaKomisi",
                "mdlFilter" => array("id=pihakMainID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",

                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
//            "kompensasiMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Metode Cashback",
//                "mdlName" => "MdlKompensasiCashbackPenjualan",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1,),
//                "targetMethod" => array(
//                    1 => "ReComKompensasiMethod",
//                    2 => "ReComKompensasiMethod",
//                ),
//            ),

            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),

            "pajakOption" => array(
                "elementType" => "dataModel",
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption2",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
                // bila dpp pph > 0, maka menjadi mandatori.
                // bila dpp pph <= 0, maka menjadi tidak mandatori.
//                "noValidateReplacer" => array(
//                    "key" => "dppPPh",
//                ),
            ),

        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                1 => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1, 4),
                    ),

                ),
                2 => array(),
            ),
            "freelancerOption" => array(
                1 => array(),
                2 => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                ),
            ),
            "pajakOption" => array(
                "pph21" => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                    "pph21Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_penjualan",
                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
//                            "sket" => "ReComPph21None_purchasing",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph21Methode",
                        ),
                    ),
                    "freelancerDetails" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Detail Freelancer",
                        "mdlName" => "MdlEmployeeFreelanceCabang",
                        "mdlFilter" => array(
                            "id=pihakMainRulesID",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "alamat_1" => "alamat",
                            "alamat_2" => "alamat",
                            "kelurahan" => "Kel",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "Prop",
                            "tlp" => "Tlp",
                            "tlp_1" => "Tlp",
                            "tlp_2" => "Handphone",
                            "npwp" => "NPWP",
                            "no_ktp" => "nik",
                            "nik" => "NIK",
                        ),
                        "editPoints" => array(1, 2),
                        "reloadLink" => "_processPihak/select/",
                    ),
//                    "freelancerOption" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Opsi Freelance (Member / Non Member)",
//                        "mdlName" => "MdlEmployeeFreelanceOption",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1),
//                        "resetElement" => array(
//                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
//                        ),
//                    ),
                ),
                "pph23" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "mdlFilter" => array(
                            "id=optionFreelancerID",
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
//                        "resetElement" => array(
////                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
//                        ),
                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
////                            "id=pihakMainRulesID",
//                            "employee_type=.employee_freelance",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
                ),
                "pph23_15" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodCashback",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "mdlFilter" => array(
                            "id=optionFreelancerID",
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
//                        "resetElement" => array(
////                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
//                        ),
                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
////                            "id=pihakMainRulesID",
//                            "employee_type=.employee_freelance",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "connectTo" => "16677",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "pihakName" => "konsumen",
            "6677" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
        ),
        "freelancerOptionInfo" => array(
            1 => "Khusus Freelancer Member/Terdaftar, pembayaran komisi melalui menu Transaksi, Pembayaran, Komisi AP Payment di DC/PUSAT.",
        ),
        "freelancerData" => array(
            "label" => "Jumlah komisi yang diterima freelancer:",
            "optionFreelancerID" => 1,// 1 = member (tampil)
            "gate" => "items4_sum",
            "headers" => array(
                "remove" => "-",
                "id" => "ID",
                "nama" => "NAMA",
                "no_ktp" => "NIK",
                "nilai_kas_cn_detail" => "Nilai Yang Diterima Freelancer",
            ),
            "headersSum" => array(
                "nilai_kas_cn_detail",
            ),
            "editableFields" => array(
                "nama" => array(
                    "selector" => "_selectorPihak/selectPihakFreelancer/6677/MdlEmployeeFreelanceCabang",
                    "process" => "_processPihak/selectPihakFreelancer/6677/MdlEmployeeFreelanceCabang",
                ),
                "nilai_kas_cn_detail" => array(
                    "selector" => "",
                    "process" => "_shoppingCart/recordFieldAdditional/6677",
                ),
            ),
            "link_baris" => "_shoppingCart/tambahBarisFreelancer",
            "link_remove" => "_shoppingCart/removeBarisFreelancer",
        ),
        "freelancerValueValidator" => array(
            "enabled" => true,
            "optionFreelancerID" => 1,// 1 = member (tampil)
            "source" => "nilai_kas_cn",
            "target" => "nilai_kas_cn_detail",
        ),
        "linkMenu" => array(
            1 => array(
                "link" => "Create/index/6677",
                "label" => "cashback penjualan (biaya)",
            ),
            2 => array(
                "link" => "Create/index/6678",
                "label" => "cashback project (biaya)",
            ),

        ),
        "linkMenuHistory" => array(
            1 => array(
                "link" => "History/viewHistory/6677",
                "label" => "History cashback penjualan",
            ),
            2 => array(
                "link" => "History/viewHistory/6678",
                "label" => "History cashback project",
            ),

        ),
    ),
    "16677" => array(
        "icon" => "fa fa-money",
        "label" => "otorisasi cashback penjualan (biaya)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request cashback penjualan (biaya)",
                "label_nota21" => "otorisasi komisi penjualan",
                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "16677r",
                "userGroup" => "sys",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "otorisasi cashback penjualan (biaya)",
                "label_nota21" => "otorisasi komisi penjualan",
                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "approve",
                "source" => "16677r",
                "target" => "16677",
                "userGroup" => "c_holding",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
                "autoNextStep" => true,// true maka otomatis otorisasi, false maka otorisasi manual
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlTransaksiData",
        "selectorSrcModel" => "MdlTransaksiData",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "customers_id=pihakID",
            "jenis=.4822",
            "trash_4=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih invoice konsumen",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
            "fulldate" => "fulldate",
            "dtime" => "dtime",
        ),
        "selectorViewedFields" => array(
            "fulldate",
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih konsumen...",
        "pihakFilters" => array(//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(),
        "pihakProcessor" => "_processPihak/select",

        //-------------------------
        "autoSelectPPh" => array(
            "enabled" => true,
            "key" => "customerDetails__npwp",
            "key_deteksi" => "pihakMainID",
            "tipe_biaya" => array(
                "72" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph23",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
//                        "nilai_kas_cn" => "",
                    ),
                ),
                "73" => array(
                    "elName" => "pph21Methode",
                    "mdlName" => "MdlPph21MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph21",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 21.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2.50%, tidak punya NPWP: 3%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "Tidak bisa didetailkan nama per-orang karena jika detail sudah masuk modul HRIS.",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 21 (%)",
                        "nilai_pph_original" => "PPh 21",
                        "nilai_kas_cn" => "jumlah diterima freelancer",
                    ),
                ),
                "74" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodCashback",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption2",
                    "subKey" => "pph23_15",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
//                        "nilai_kas_cn" => "",
                    ),
                ),

            ),
            "resetor" => array(
                "pph21Methode",
                "pph21Methode__label",
                "pph21Methode__name",
                "pph21Methode__tarif",
                "pph23Methode",
                "pph23Methode__label",
                "pph23Methode__name",
                "pph23Methode__tarif",
            ),
        ),
        //----

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "nomer_top" => "nomer request",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            //------------------------
//            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
//            "nama" => "nomer",
//            "label" => "label",
//            "reference" => "reference",
//            //----
//            "nomer" => "nomer",
//            "idtime" => "dtime",
//            "ifulldate" => "fulldate",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(//            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
//                "harga",
//                "jml",
//                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),

//        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Konsumen",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
//                    "kredit_limit" => "KREDIT LIMIT",
                    "folder_nama" => "jenis konsumen",
                ),
                "editPoints" => array(1, 2),
                "reloadLink" => "_processPihak/select/",
            ),
            "biayaDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Biaya",
                "mdlName" => "MdlDtaBiayaUsahaKomisi",
                "mdlFilter" => array("id=pihakMainID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",

                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
//            "kompensasiMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Metode Cashback",
//                "mdlName" => "MdlKompensasiCashbackPenjualan",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1,),
//                "targetMethod" => array(
//                    1 => "ReComKompensasiMethod",
//                    2 => "ReComKompensasiMethod",
//                ),
//            ),


            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),

            "pajakOption" => array(
                "elementType" => "dataModel",
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption2",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
                // bila dpp pph > 0, maka menjadi mandatori.
                // bila dpp pph <= 0, maka menjadi tidak mandatori.
//                "noValidateReplacer" => array(
//                    "key" => "dppPPh",
//                ),
            ),

        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                1 => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1, 4),
                    ),

                ),
                2 => array(),
            ),
            "freelancerOption" => array(
                1 => array(),
                2 => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                ),
            ),
            "pajakOption" => array(
                "pph21" => array(
                    "pph21Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_penjualan",
                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
//                            "sket" => "ReComPph21None_purchasing",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph21Methode",
                        ),
                    ),
                    "freelancerDetails" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Detail Freelancer",
                        "mdlName" => "MdlEmployeeFreelanceCabang",
                        "mdlFilter" => array(
                            "id=pihakMainRulesID",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "alamat_1" => "alamat",
                            "alamat_2" => "alamat",
                            "kelurahan" => "Kel",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "Prop",
                            "tlp" => "Tlp",
                            "tlp_1" => "Tlp",
                            "tlp_2" => "Handphone",
                            "npwp" => "NPWP",
                            "no_ktp" => "nik",
                            "nik" => "NIK",
                        ),
                        "editPoints" => array(1, 2),
                        "reloadLink" => "_processPihak/select/",
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
                        "resetElement" => array(
                            "pph23Methode",
                            "kompensasiMethod",
                            "cash_account",
                        ),
                    ),
                ),
                "pph23" => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                            "freelancerOption",
                        ),
                    ),
                ),
                "pph23_15" => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodCashback",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                            "freelancerOption",
                        ),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),

//        "connectTo" => "2677",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "16677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "16677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "677r" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
        ),
        "linkMenu" => array(
            1 => array(
                "link" => "Transaksi/index/16677",
                "label" => "otorisasi cashback penjualan (biaya)",
            ),
            2 => array(
                "link" => "Transaksi/index/16678",
                "label" => "otorisasi cashback project (biaya)",
            ),

        ),
        "linkMenuHistory" => array(
            1 => array(
                "link" => "History/viewHistory/16677",
                "label" => "History cashback penjualan",
            ),
            2 => array(
                "link" => "History/viewHistory/16678",
                "label" => "History cashback project",
            ),

        ),
    ),

    //komisi project
    //komisi project
    "6678_OLD" => array(
        "icon" => "fa fa-money",
        "label" => "cashback project (biaya)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request cashback project (biaya)",
//                "label_nota21" => "otorisasi komisi penjualan",
//                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "6678",
                "userGroup" => "o_kasir",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
        ),
//        "template" => "template/transaksi.html",
        "template" => "template/transaksi_pihak2.html",
        //-------------------------
        "selectorModel" => "MdlProdukProject",
        "selectorSrcModel" => "MdlProdukProject",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "customer_id=pihakID",
            "cabang_id=placeID",
//            "trash_4=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih project",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "fulldate" => "fulldate",
            "dtime" => "dtime",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectBiaya/selectProject",
        "editHandlerMethod" => "selectProject",
        "pairSelectorItem" => array(
            "enabled" => true,
//            "mdl" => "MdlLockerTransaksi",
            "mdl" => "MdlLockerProject",
            "filter" => array(
                "jenis=.komisi",
                "cabang_id=placeID",
                "jumlah=.1",
            ),
        ),
        //-------------------------
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih konsumen...",
        "pihakFilters" => array(//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(),
        "pihakProcessor" => "_processPihak/select",
        "pihakKoreksiData" => array(
            "enabled" => true,
            "kolom" => array(
                "folder_id" => "Jenis Konsumen {konsumen_nama} belum ditentukan (Corporate atau Perorangan). ",
            ),
            "link" => base_url() . "statik/Data/edit/Customer/",
        ),
        //-------------------------
        "pihakModelMainRules" => "MdlEmployeeFreelanceCabang",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "pilih freelancer anda...",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
//

        ),
        "pihakMainFiltersRulesAdd" => array(
            "enabled" => false,// tgl 10 april 2025, komisi freelancer pph21, biaya konsultasi pph23, cashback pph23 bisa dipilih bebas.
            "filter" => array(
                "jenis_konsumen=pihakJenis",
            ),
            "exception" => array(
                "3",// lain-lain, tidak masuk filter atau keluar semua, silahkan dipilih (komisi freelancer, biaya konsultasi, cashback)
            ),
        ),
        "pihakMainValueSrcRules" => array(
//            "taxesMethod" => "taxes_name",
//            "taxesMethodCoa" => "coa_code",
//
//
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",
        "pihakModelMainRulesAuto" => array(
            "key" => "pph21",
            "gate" => "pajakOption",
            "mdl" => "MdlEmployeeFreelanceCabang",
        ),
        "noResetGate" => false,
        //-------------------------
        "autoSelectPPh" => array(
            "enabled" => true,
            "key" => "customerDetails__npwp",
            "key_deteksi" => "pihakMainID",
            "tipe_biaya" => array(
                "5" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph23",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
                        "nilai_kas_cn" => "jumlah diterima perusahaan",
                    ),
                ),
                "6" => array(
                    "elName" => "pph21Methode",
                    "mdlName" => "MdlPph21MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph21",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 21.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2.50%, tidak punya NPWP: 3%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "Tidak bisa didetailkan nama per-orang karena jika detail sudah masuk modul HRIS.",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 21 (%)",
                        "nilai_pph_original" => "PPh 21",
                        "nilai_kas_cn" => "jumlah diterima freelancer",
                    ),
                ),
                "7" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodCashback",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption2",
                    "subKey" => "pph23_15",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
                        "nilai_kas_cn" => "jumlah diterima perusahaan",
                    ),
                ),

            ),
            "resetor" => array(
                "pph21Methode",
                "pph21Methode__label",
                "pph21Methode__name",
                "pph21Methode__tarif",
                "pph23Methode",
                "pph23Methode__label",
                "pph23Methode__name",
                "pph23Methode__tarif",
            ),
        ),
        //----opsi freelancer MEMBER/NON MEMBER
        "optionFreelancerShow" => array(
            "key" => "pajakOption",
            "pajakOption" => array("pph23", "pph23_15"),
        ),
        "optionFreelancerModel" => "MdlEmployeeFreelanceOption",
        "optionFreelancerCaller" => "_selectorPihak/selectOptionFreelancer",
        "optionFreelancerProcessor" => "_processPihak/selectOptionFreelancer",
        "optionFreelancerReset" => array(// mereset element dan gerbang nilai/session
            "kompensasiMethod",
            "cash_account",
        ),
        "optionFreelancerLabel" => "Pilih Status Freelancer (Member / Non Member)",
        //----
        "addData" => array(
            "enabled" => true,
            "key" => "optionFreelancerID",// 1 = member (tampil), 2 non member (tidak tampil)
            "link" => "statik/Data/add/EmployeeFreelanceCabang",
        ),
        //----


        //region tambahan pihak2
        "autoLoadPihakMain" => true,
        "mainselectorModel" => array(),
//        "pihakModelMain" => "MdlDtaBiayaUsahaKomisi",
        "pihakModelMain" => "MdlDtaBiayaProjectKomisi",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "pilih biaya komisi perorangan/perusahaan",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
            "comName_items" => "comName_items",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        //endregion


        "shortHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "pihakName" => "konsumen",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            //------------------------
//            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "extHistoryFields" => array(
            1 => array(
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
            "reference" => "quot_nomer",
            //----
            "nomer" => "nomer",
            "idtime" => "dtime",
            "ifulldate" => "fulldate",
            //----
            "inv_new_net3" => "harga_nppn",
            "inv_grand_ppn" => "ppn",
//            "inv_new_net1" => "harga",
            "hpp" => "harga",
            "cat_id" => "cat_id",
            "cat_nama" => "cat_nama",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nomer inv",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "nilai project",
            ),
            2 => array(
                "nama" => "nomer inv",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",

            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "pph__tarif" => "PPh (%)",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "pph__tarif" => "PPh (%)",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
//                "harga",
//                "jml",
                "nilai_kas_cn",
            ),
            2 => array(),
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
//                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
//                "nett1" => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",

//                "harga" => "document.getElementById('{nilai_kas_cn}').value=((100-parseFloat(removeCommas(document.getElementById('{pph23Methode__tarif}').innerHTML))/100)*parseFloat(removeCommas(document.getElementById('{harga}').value)))",
                "harga" => "document.getElementById('{nilai_kas_cn}').value=(((100-parseFloat(removeCommas(document.getElementById('{pph__tarif}').innerHTML)))/100)*parseFloat(removeCommas(document.getElementById('{harga}').value)))",
                "nilai_kas_cn" => "document.getElementById('{harga}').value=((100/(100-parseFloat(removeCommas(document.getElementById('{pph__tarif}').innerHTML))))*parseFloat(removeCommas(document.getElementById('{nilai_kas_cn}').value)))",

            ),
        ),
        "keyupAction" => true,
        "shoppingCartFieldSrcAdditional" => array(//4464
//            "inv_new_net3" => "new_net3",
//            "inv_grand_ppn" => "grand_ppn",
//            "inv_new_net1" => "new_net1",
            "inv_new_net3" => "sub_nett",
            "inv_grand_ppn" => "sub_ppn",
            "inv_new_net1" => "sub_nett-sub_ppn",
            "inv_dpp_pengganti" => "dpp_pengganti",
        ),

        "shoppingCartFieldSrcAdditional2" => array(//5822spd, 5823spd
            "inv_grand_ppn" => "grand_ppn",
            "inv_new_net1" => "new_net1",
//            "inv_new_net3" => "new_net3",//
            "inv_new_net3" => "new_net1+grand_ppn",//
//            "inv_new_net3" => "sub_nett",
//            "inv_grand_ppn" => "sub_ppn",
//            "inv_new_net1" => "sub_nett-sub_ppn",
            "inv_dpp_pengganti" => "dpp_pengganti",
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "biaya cashback/komisi",
            "nilai_kas_cn" => "kas/creditnote yang diberikan ke konsumen",
            "pph__tarif" => "tarif pph",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),

        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Konsumen",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
//                    "kredit_limit" => "KREDIT LIMIT",
                    "folder_nama" => "jenis konsumen",
                ),
                "editPoints" => array(1, 2),
                "reloadLink" => "_processPihak/select/",
            ),
            "biayaDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Biaya",
//                "mdlName" => "MdlDtaBiayaUsahaKomisi",
                "mdlName" => "MdlDtaBiayaProjectKomisi",
                "mdlFilter" => array("id=pihakMainID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",

                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
//            "kompensasiMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Metode Cashback",
//                "mdlName" => "MdlKompensasiCashbackPenjualan",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1,),
//                "targetMethod" => array(
//                    1 => "ReComKompensasiMethod",
//                    2 => "ReComKompensasiMethod",
//                ),
//            ),

            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),

            "pajakOption" => array(
                "elementType" => "dataModel",
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption2",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
                // bila dpp pph > 0, maka menjadi mandatori.
                // bila dpp pph <= 0, maka menjadi tidak mandatori.
//                "noValidateReplacer" => array(
//                    "key" => "dppPPh",
//                ),
            ),

        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                1 => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1, 4),
                    ),

                ),
                2 => array(),
            ),
            "freelancerOption" => array(
                1 => array(),
                2 => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                ),
            ),
            "pajakOption" => array(
                "pph21" => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                    "pph21Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_penjualan",
                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
//                            "sket" => "ReComPph21None_purchasing",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph21Methode",
                        ),
                    ),
                    "freelancerDetails" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Detail Freelancer",
                        "mdlName" => "MdlEmployeeFreelanceCabang",
                        "mdlFilter" => array(
                            "id=pihakMainRulesID",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "alamat_1" => "alamat",
                            "alamat_2" => "alamat",
                            "kelurahan" => "Kel",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "Prop",
                            "tlp" => "Tlp",
                            "tlp_1" => "Tlp",
                            "tlp_2" => "Handphone",
                            "npwp" => "NPWP",
                            "no_ktp" => "nik",
                            "nik" => "NIK",
                        ),
                        "editPoints" => array(1, 2),
                        "reloadLink" => "_processPihak/select/",
                    ),
//                    "freelancerOption" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Opsi Freelance (Member / Non Member)",
//                        "mdlName" => "MdlEmployeeFreelanceOption",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1),
//                        "resetElement" => array(
//                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
//                        ),
//                    ),
                ),
                "pph23" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "mdlFilter" => array(
                            "id=optionFreelancerID",
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
                        "resetElement" => array(
////                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
                        ),
                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
////                            "id=pihakMainRulesID",
//                            "employee_type=.employee_freelance",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
                ),
                "pph23_15" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodCashback",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "mdlFilter" => array(
                            "id=optionFreelancerID",
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
                        "resetElement" => array(
////                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
                        ),
                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
////                            "id=pihakMainRulesID",
//                            "employee_type=.employee_freelance",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "connectTo" => "16678",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "pihakName" => "konsumen",
            "6677" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
        ),
        "freelancerOptionInfo" => array(
            1 => "Khusus Freelancer Member/Terdaftar, pembayaran komisi melalui menu Transaksi, Pembayaran, Komisi AP Payment di DC/PUSAT.",
        ),
        "freelancerData" => array(
            "label" => "Jumlah komisi yang diterima freelancer:",
            "optionFreelancerID" => 1,// 1 = member (tampil)
            "gate" => "items4_sum",
            "headers" => array(
                "remove" => "-",
                "id" => "ID",
                "nama" => "NAMA",
                "no_ktp" => "NIK",
                "nilai_kas_cn_detail" => "Nilai Yang Diterima Freelancer",
            ),
            "headersSum" => array(
                "nilai_kas_cn_detail",
            ),
            "editableFields" => array(
                "nama" => array(
                    "selector" => "_selectorPihak/selectPihakFreelancer/6678/MdlEmployeeFreelanceCabang",
                    "process" => "_processPihak/selectPihakFreelancer/6678/MdlEmployeeFreelanceCabang",
                ),
                "nilai_kas_cn_detail" => array(
                    "selector" => "",
                    "process" => "_shoppingCart/recordFieldAdditional/6678",
                ),
            ),
            "link_baris" => "_shoppingCart/tambahBarisFreelancer",
            "link_remove" => "_shoppingCart/removeBarisFreelancer",
        ),
        "freelancerValueValidator" => array(
            "enabled" => true,
            "optionFreelancerID" => 1,// 1 = member (tampil)
            "source" => "nilai_kas_cn",
            "target" => "nilai_kas_cn_detail",
        ),
        "linkMenu" => array(
            1 => array(
                "link" => "Create/index/6677",
                "label" => "cashback penjualan (biaya)",
            ),
            2 => array(
                "link" => "Create/index/6678",
                "label" => "cashback project (biaya)",
            ),

        ),
        "linkMenuHistory" => array(
            1 => array(
                "link" => "History/viewHistory/6677",
                "label" => "History cashback penjualan",
            ),
            2 => array(
                "link" => "History/viewHistory/6678",
                "label" => "History cashback project",
            ),

        ),
    ),
    "16678_OLD" => array(
        "icon" => "fa fa-money",
        "label" => "otorisasi cashback project (biaya)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request cashback project (biaya)",
                "label_nota21" => "otorisasi cashback project",
                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "16678r",
                "userGroup" => "sys",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "otorisasi cashback project (biaya)",
                "label_nota21" => "otorisasi cashback project",
                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "approve",
                "source" => "16678r",
                "target" => "16678",
                "userGroup" => "c_holding",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
                "autoNextStep" => false,// true maka otomatis otorisasi, false maka otorisasi manual
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlTransaksiData",
        "selectorSrcModel" => "MdlTransaksiData",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "customers_id=pihakID",
            "jenis=.4822",
            "trash_4=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih invoice konsumen",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
            "fulldate" => "fulldate",
            "dtime" => "dtime",
        ),
        "selectorViewedFields" => array(
            "fulldate",
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih konsumen...",
        "pihakFilters" => array(//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(),
        "pihakProcessor" => "_processPihak/select",

        //-------------------------
        "autoSelectPPh" => array(
            "enabled" => true,
            "key" => "customerDetails__npwp",
            "key_deteksi" => "pihakMainID",
            "tipe_biaya" => array(
                "72" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph23",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
//                        "nilai_kas_cn" => "",
                    ),
                ),
                "73" => array(
                    "elName" => "pph21Methode",
                    "mdlName" => "MdlPph21MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph21",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 21.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2.50%, tidak punya NPWP: 3%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "Tidak bisa didetailkan nama per-orang karena jika detail sudah masuk modul HRIS.",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 21 (%)",
                        "nilai_pph_original" => "PPh 21",
                        "nilai_kas_cn" => "jumlah diterima freelancer",
                    ),
                ),
                "74" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodCashback",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption2",
                    "subKey" => "pph23_15",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
//                        "nilai_kas_cn" => "",
                    ),
                ),

            ),
            "resetor" => array(
                "pph21Methode",
                "pph21Methode__label",
                "pph21Methode__name",
                "pph21Methode__tarif",
                "pph23Methode",
                "pph23Methode__label",
                "pph23Methode__name",
                "pph23Methode__tarif",
            ),
        ),
        //----

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "nomer_top" => "nomer request",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            //------------------------
//            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
//            "nama" => "nomer",
//            "label" => "label",
//            "reference" => "reference",
//            //----
//            "nomer" => "nomer",
//            "idtime" => "dtime",
//            "ifulldate" => "fulldate",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(//            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
//                "harga",
//                "jml",
//                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),

//        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Konsumen",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
//                    "kredit_limit" => "KREDIT LIMIT",
                    "folder_nama" => "jenis konsumen",
                ),
                "editPoints" => array(1, 2),
                "reloadLink" => "_processPihak/select/",
            ),
            "biayaDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Biaya",
                "mdlName" => "MdlDtaBiayaUsahaKomisi",
                "mdlFilter" => array("id=pihakMainID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",

                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
//            "kompensasiMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Metode Cashback",
//                "mdlName" => "MdlKompensasiCashbackPenjualan",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1,),
//                "targetMethod" => array(
//                    1 => "ReComKompensasiMethod",
//                    2 => "ReComKompensasiMethod",
//                ),
//            ),


            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),

            "pajakOption" => array(
                "elementType" => "dataModel",
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption2",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
                // bila dpp pph > 0, maka menjadi mandatori.
                // bila dpp pph <= 0, maka menjadi tidak mandatori.
//                "noValidateReplacer" => array(
//                    "key" => "dppPPh",
//                ),
            ),

        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                1 => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1, 4),
                    ),

                ),
                2 => array(),
            ),
            "freelancerOption" => array(
                1 => array(),
                2 => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                ),
            ),
            "pajakOption" => array(
                "pph21" => array(
                    "pph21Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_penjualan",
                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
//                            "sket" => "ReComPph21None_purchasing",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph21Methode",
                        ),
                    ),
                    "freelancerDetails" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Detail Freelancer",
                        "mdlName" => "MdlEmployeeFreelanceCabang",
                        "mdlFilter" => array(
                            "id=pihakMainRulesID",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "alamat_1" => "alamat",
                            "alamat_2" => "alamat",
                            "kelurahan" => "Kel",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "Prop",
                            "tlp" => "Tlp",
                            "tlp_1" => "Tlp",
                            "tlp_2" => "Handphone",
                            "npwp" => "NPWP",
                            "no_ktp" => "nik",
                            "nik" => "NIK",
                        ),
                        "editPoints" => array(1, 2),
                        "reloadLink" => "_processPihak/select/",
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
                        "resetElement" => array(
                            "pph23Methode",
                            "kompensasiMethod",
                            "cash_account",
                        ),
                    ),
                ),
                "pph23" => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                            "freelancerOption",
                        ),
                    ),
                ),
                "pph23_15" => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodCashback",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                            "freelancerOption",
                        ),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),

//        "connectTo" => "2677",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "16677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "16677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "677r" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
        ),
        "linkMenu" => array(
            1 => array(
                "link" => "Transaksi/index/16677",
                "label" => "Otorisasi cashback penjualan (biaya)",
            ),
            2 => array(
                "link" => "Transaksi/index/16678",
                "label" => "Otorisasi cashback project (biaya)",
            ),

        ),
        "linkMenuHistory" => array(
            1 => array(
                "link" => "History/viewHistory/16677",
                "label" => "History cashback penjualan",
            ),
            2 => array(
                "link" => "History/viewHistory/16678",
                "label" => "History cashback project",
            ),

        ),
    ),

    "6678" => array(
        "icon" => "fa fa-money",
        "label" => "cashback project (biaya)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request cashback project (biaya)",
//                "label_nota21" => "otorisasi komisi penjualan",
//                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "6678",
                "userGroup" => "o_kasir",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
        ),
//        "template" => "template/transaksi.html",
        "template" => "template/transaksi_pihak2.html",
        //-------------------------
        "selectorModel" => "MdlProdukProject",
        "selectorSrcModel" => "MdlProdukProject",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "customer_id=pihakID",
            "cabang_id=placeID",
//            "trash_4=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih project",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "fulldate" => "fulldate",
            "dtime" => "dtime",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectBiaya/selectProject",
        "editHandlerMethod" => "selectProject",
        "pairSelectorItem" => array(
            "enabled" => true,
//            "mdl" => "MdlLockerTransaksi",
            "mdl" => "MdlLockerProject",
            "filter" => array(
                "jenis=.komisi",
                "cabang_id=placeID",
                "jumlah=.1",
            ),
        ),
        "autoLoadSelectorItem" => true,
        //-------------------------
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih konsumen...",
        "pihakFilters" => array(//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(),
        "pihakProcessor" => "_processPihak/select",
        "pihakKoreksiData" => array(
            "enabled" => true,
            "kolom" => array(
                "folder_id" => "Jenis Konsumen {konsumen_nama} belum ditentukan (Corporate atau Perorangan). ",
            ),
            "link" => base_url() . "statik/Data/edit/Customer/",
        ),
        //-------------------------
        "pihakModelMainRules" => "MdlEmployeeFreelanceCabang",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "pilih freelancer anda...",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
//

        ),
        "pihakMainFiltersRulesAdd" => array(
            "enabled" => false,// tgl 10 april 2025, komisi freelancer pph21, biaya konsultasi pph23, cashback pph23 bisa dipilih bebas.
            "filter" => array(
                "jenis_konsumen=pihakJenis",
            ),
            "exception" => array(
                "3",// lain-lain, tidak masuk filter atau keluar semua, silahkan dipilih (komisi freelancer, biaya konsultasi, cashback)
            ),
        ),
        "pihakMainValueSrcRules" => array(
//            "taxesMethod" => "taxes_name",
//            "taxesMethodCoa" => "coa_code",
//
//
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",
        "pihakModelMainRulesAuto" => array(
            "key" => "pph21",
            "gate" => "pajakOption",
            "mdl" => "MdlEmployeeFreelanceCabang",
        ),
        "noResetGate" => false,
        //-------------------------
        "autoSelectPPh" => array(
            "enabled" => true,
            "key" => "customerDetails__npwp",
            "key_deteksi" => "pihakMainID",
            "tipe_biaya" => array(
                "5" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph23",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti jenis biaya akan menghapus data yang sudah diisi di formulir.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
                        "nilai_kas_cn" => "jumlah diterima perusahaan",
                    ),
                ),
                "6" => array(
                    "elName" => "pph21Methode",
                    "mdlName" => "MdlPph21MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph21",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 21.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2.50%, tidak punya NPWP: 3%).",
                    "sub_label_info_pph" => "Mengganti jenis biaya akan menghapus data yang sudah diisi di formulir.",
                    "sub_label_freelancer" => "Tidak bisa didetailkan nama per-orang karena jika detail sudah masuk modul HRIS.",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 21 (%)",
                        "nilai_pph_original" => "PPh 21",
                        "nilai_kas_cn" => "jumlah diterima freelancer",
                    ),
                ),
                "7" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodCashback",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption2",
                    "subKey" => "pph23_15",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti jenis biaya akan menghapus data yang sudah diisi di formulir.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
                        "nilai_kas_cn" => "jumlah diterima perusahaan",
                    ),
                ),

            ),
            "resetor" => array(
                "pph21Methode",
                "pph21Methode__label",
                "pph21Methode__name",
                "pph21Methode__tarif",
                "pph23Methode",
                "pph23Methode__label",
                "pph23Methode__name",
                "pph23Methode__tarif",
            ),
        ),
        //----opsi freelancer MEMBER/NON MEMBER
        "optionFreelancerShow" => array(
            "key" => "pajakOption",
            "pajakOption" => array("pph23", "pph23_15"),
        ),
        "optionFreelancerModel" => "MdlEmployeeFreelanceOption",
        "optionFreelancerCaller" => "_selectorPihak/selectOptionFreelancer",
        "optionFreelancerProcessor" => "_processPihak/selectOptionFreelancer",
        "optionFreelancerReset" => array(// mereset element dan gerbang nilai/session
            "kompensasiMethod",
            "cash_account",
        ),
        "optionFreelancerLabel" => "Pilih Status Freelancer (Member / Non Member)",
        //----
        "addData" => array(
            "enabled" => true,
            "key" => "optionFreelancerID",// 1 = member (tampil), 2 non member (tidak tampil)
            "link" => "statik/Data/add/EmployeeFreelanceCabang",
        ),
        //----


        //region tambahan pihak2
        "autoLoadPihakMain" => true,
        "mainselectorModel" => array(),
//        "pihakModelMain" => "MdlDtaBiayaUsahaKomisi",
        "pihakModelMain" => "MdlDtaBiayaProjectKomisi",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "pilih biaya komisi perorangan/perusahaan",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
            "comName_items" => "comName_items",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        //endregion


        "shortHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "pihakName" => "konsumen",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            //------------------------
//            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "extHistoryFields" => array(
            1 => array(
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
            "reference" => "quot_nomer",
            //----
            "nomer" => "nomer",
            "idtime" => "dtime",
            "ifulldate" => "fulldate",
            //----
            "inv_new_net3" => "harga_nppn",
            "inv_grand_ppn" => "ppn",
//            "inv_new_net1" => "harga",
            "hpp" => "harga",
            "cat_id" => "cat_id",
            "cat_nama" => "cat_nama",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nomer inv",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "nilai project",
            ),
            2 => array(
                "nama" => "nomer inv",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",

            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "pph__tarif" => "PPh (%)",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "pph__tarif" => "PPh (%)",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
//                "harga",
//                "jml",
                "nilai_kas_cn",
            ),
            2 => array(),
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
//                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)", //,this.value=addCommas(removeCommas(this.value))
//                "nett1" => "document.getElementById('{disc}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))));document.getElementById('{disc_percent}').value=(((parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))-parseFloat(removeCommas(this.value)))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML)))*100)",

//                "harga" => "document.getElementById('{nilai_kas_cn}').value=((100-parseFloat(removeCommas(document.getElementById('{pph23Methode__tarif}').innerHTML))/100)*parseFloat(removeCommas(document.getElementById('{harga}').value)))",
                "harga" => "document.getElementById('{nilai_kas_cn}').value=(((100-parseFloat(removeCommas(document.getElementById('{pph__tarif}').innerHTML)))/100)*parseFloat(removeCommas(document.getElementById('{harga}').value)))",
                "nilai_kas_cn" => "document.getElementById('{harga}').value=((100/(100-parseFloat(removeCommas(document.getElementById('{pph__tarif}').innerHTML))))*parseFloat(removeCommas(document.getElementById('{nilai_kas_cn}').value)))",

            ),
        ),
        "keyupAction" => true,
        "shoppingCartFieldSrcAdditional" => array(//4464
//            "inv_new_net3" => "new_net3",
//            "inv_grand_ppn" => "grand_ppn",
//            "inv_new_net1" => "new_net1",
            "inv_new_net3" => "sub_nett",
            "inv_grand_ppn" => "sub_ppn",
            "inv_new_net1" => "sub_nett-sub_ppn",
            "inv_dpp_pengganti" => "dpp_pengganti",
        ),

        "shoppingCartFieldSrcAdditional2" => array(//5822spd, 5823spd
            "inv_grand_ppn" => "grand_ppn",
            "inv_new_net1" => "new_net1",
//            "inv_new_net3" => "new_net3",//
            "inv_new_net3" => "new_net1+grand_ppn",//
//            "inv_new_net3" => "sub_nett",
//            "inv_grand_ppn" => "sub_ppn",
//            "inv_new_net1" => "sub_nett-sub_ppn",
            "inv_dpp_pengganti" => "dpp_pengganti",
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "biaya cashback/komisi",
            "nilai_kas_cn" => "kas/creditnote yang diberikan ke konsumen",
            "pph__tarif" => "tarif pph",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),

        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Konsumen",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
//                    "kredit_limit" => "KREDIT LIMIT",
                    "folder_nama" => "jenis konsumen",
                ),
                "editPoints" => array(1, 2),
                "reloadLink" => "_processPihak/select/",
            ),
            "biayaDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Biaya",
//                "mdlName" => "MdlDtaBiayaUsahaKomisi",
                "mdlName" => "MdlDtaBiayaProjectKomisi",
                "mdlFilter" => array("id=pihakMainID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",

                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
//            "kompensasiMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Metode Cashback",
//                "mdlName" => "MdlKompensasiCashbackPenjualan",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1,),
//                "targetMethod" => array(
//                    1 => "ReComKompensasiMethod",
//                    2 => "ReComKompensasiMethod",
//                ),
//            ),

            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),

            "pajakOption" => array(
                "elementType" => "dataModel",
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption2",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
                // bila dpp pph > 0, maka menjadi mandatori.
                // bila dpp pph <= 0, maka menjadi tidak mandatori.
//                "noValidateReplacer" => array(
//                    "key" => "dppPPh",
//                ),
            ),

        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                1 => array(
//                    "cash_account" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "tunai / akun bank",
//                        "mdlName" => "MdlBankAccount_cash_and_in",
//                        "mdlFilter" => array(
////                            "cabang_id=placeID",
////                            "jenis2=.1",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                        ),
//                        "editPoints" => array(1, 4),
//                        "noValidate" => true,
//                    ),
                ),
                2 => array(),
            ),
            "freelancerOption" => array(
                1 => array(),
                2 => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "mdlName" => "MdlKompensasiCashbackPenjualan2",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                            "notif" => "keterangan",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                ),
            ),
            "pajakOption" => array(
                "pph21" => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan2",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                            "notif" => "keterangan",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                    "pph21Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_penjualan",
                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
//                            "sket" => "ReComPph21None_purchasing",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph21Methode",
                        ),
                    ),
                    "freelancerDetails" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Detail Freelancer",
                        "mdlName" => "MdlEmployeeFreelanceCabang",
                        "mdlFilter" => array(
                            "id=pihakMainRulesID",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "alamat_1" => "alamat",
                            "alamat_2" => "alamat",
                            "kelurahan" => "Kel",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "Prop",
                            "tlp" => "Tlp",
                            "tlp_1" => "Tlp",
                            "tlp_2" => "Handphone",
                            "npwp" => "NPWP",
                            "no_ktp" => "nik",
                            "nik" => "NIK",
                        ),
                        "editPoints" => array(1, 2),
                        "reloadLink" => "_processPihak/select/",
                    ),
//                    "freelancerOption" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Opsi Freelance (Member / Non Member)",
//                        "mdlName" => "MdlEmployeeFreelanceOption",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1),
//                        "resetElement" => array(
//                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
//                        ),
//                    ),
                ),
                "pph23" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "mdlFilter" => array(
                            "id=optionFreelancerID",
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
                        "resetElement" => array(
////                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
                        ),
                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
////                            "id=pihakMainRulesID",
//                            "employee_type=.employee_freelance",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
                ),
                "pph23_15" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodCashback",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "mdlFilter" => array(
                            "id=optionFreelancerID",
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
                        "resetElement" => array(
////                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
                        ),
                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
////                            "id=pihakMainRulesID",
//                            "employee_type=.employee_freelance",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "connectTo" => "16678",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "pihakName" => "konsumen",
            "6677" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
        ),
        "freelancerOptionInfo" => array(
            1 => "Khusus Freelancer Member/Terdaftar, pembayaran komisi melalui menu Transaksi, Pembayaran, Komisi AP Payment di DC/PUSAT.",
        ),
        "freelancerData" => array(
            "label" => "Jumlah komisi yang diterima freelancer:",
            "optionFreelancerID" => 1,// 1 = member (tampil)
            "gate" => "items4_sum",
            "headers" => array(
                "remove" => "-",
                "id" => "ID",
                "nama" => "NAMA",
                "no_ktp" => "NIK",
                "nilai_kas_cn_detail" => "Nilai Yang Diterima Freelancer",
            ),
            "headersSum" => array(
                "nilai_kas_cn_detail",
            ),
            "editableFields" => array(
                "nama" => array(
                    "selector" => "_selectorPihak/selectPihakFreelancer/6678/MdlEmployeeFreelanceCabang",
                    "process" => "_processPihak/selectPihakFreelancer/6678/MdlEmployeeFreelanceCabang",
                ),
                "nilai_kas_cn_detail" => array(
                    "selector" => "",
                    "process" => "_shoppingCart/recordFieldAdditional/6678",
                ),
            ),
            "link_baris" => "_shoppingCart/tambahBarisFreelancer",
            "link_remove" => "_shoppingCart/removeBarisFreelancer",
        ),
        "freelancerValueValidator" => array(
            "enabled" => true,
            "optionFreelancerID" => 1,// 1 = member (tampil)
            "source" => "nilai_kas_cn",
            "target" => "nilai_kas_cn_detail",
        ),
        "linkMenu" => array(
            1 => array(
                "link" => "Create/index/6677",
                "label" => "cashback penjualan (biaya)",
            ),
            2 => array(
                "link" => "Create/index/6678",
                "label" => "cashback project (biaya)",
            ),

        ),
        "linkMenuHistory" => array(
            1 => array(
                "link" => "History/viewHistory/6677",
                "label" => "History cashback penjualan",
            ),
            2 => array(
                "link" => "History/viewHistory/6678",
                "label" => "History cashback project",
            ),

        ),
    ),
    "16678" => array(
        "icon" => "fa fa-money",
        "label" => "otorisasi cashback project (biaya)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request cashback project (biaya)",
                "label_nota21" => "otorisasi cashback project",
                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "16678r",
                "userGroup" => "sys",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "otorisasi cashback project (biaya)",
                "label_nota21" => "otorisasi cashback project",
                "label_nota23" => "otorisasi biaya konsultasi teknis",
                "actionLabel" => "approve",
                "source" => "16678r",
                "target" => "16678",
                "userGroup" => "c_holding",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
                "autoNextStep" => false,// true maka otomatis otorisasi, false maka otorisasi manual
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlTransaksiData",
        "selectorSrcModel" => "MdlTransaksiData",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "customers_id=pihakID",
            "jenis=.4822",
            "trash_4=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih invoice konsumen",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
            "fulldate" => "fulldate",
            "dtime" => "dtime",
        ),
        "selectorViewedFields" => array(
            "fulldate",
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih konsumen...",
        "pihakFilters" => array(//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(),
        "pihakProcessor" => "_processPihak/select",

        //-------------------------
        "autoSelectPPh" => array(
            "enabled" => true,
            "key" => "customerDetails__npwp",
            "key_deteksi" => "pihakMainID",
            "tipe_biaya" => array(
                "72" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph23",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
//                        "nilai_kas_cn" => "",
                    ),
                ),
                "73" => array(
                    "elName" => "pph21Methode",
                    "mdlName" => "MdlPph21MethodPenjualan",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption",
                    "subKey" => "pph21",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 21.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2.50%, tidak punya NPWP: 3%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "Tidak bisa didetailkan nama per-orang karena jika detail sudah masuk modul HRIS.",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 21 (%)",
                        "nilai_pph_original" => "PPh 21",
                        "nilai_kas_cn" => "jumlah diterima freelancer",
                    ),
                ),
                "74" => array(
                    "elName" => "pph23Methode",
                    "mdlName" => "MdlPph23MethodCashback",
                    "subElName" => "pajakOption",
                    "subMdlName" => "MdlPajakPPhOption2",
                    "subKey" => "pph23_15",
                    "label_info_pph" => "Anda pilih: {pihakMainName}, pajak yang dipakai PPH 23.",
//                    "label_info_pph_2" => "Tarif PPh mengikuti Data Konsumen (punya NPWP: 2%, tidak punya NPWP: 4%).",
                    "sub_label_info_pph" => "Mengganti biaya akan mereset formulir yang sudah diisi.",
                    "sub_label_freelancer" => "",
                    "labelFieldsReplacer" => array(
                        "pph__tarif" => "PPh 23 (%)",
                        "nilai_pph_original" => "PPh 23",
//                        "nilai_kas_cn" => "",
                    ),
                ),

            ),
            "resetor" => array(
                "pph21Methode",
                "pph21Methode__label",
                "pph21Methode__name",
                "pph21Methode__tarif",
                "pph23Methode",
                "pph23Methode__label",
                "pph23Methode__name",
                "pph23Methode__tarif",
            ),
        ),
        //----

        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "nomer_top" => "nomer request",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            //------------------------
//            "pym_src_status_keterangan" => "status bayar",
            //------------------------
            "keterangan" => "keterangan",
            "print_label" => "tool",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
//            "nama" => "nomer",
//            "label" => "label",
//            "reference" => "reference",
//            //----
//            "nomer" => "nomer",
//            "idtime" => "dtime",
//            "ifulldate" => "fulldate",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
            2 => array(
                "nama" => "nama",
                //                "jml"  => "qty",
                "idtime" => "tanggal invoice",
                "inv_new_net1" => "dpp",
//                "inv_dpp_pengganti" => "dpp pengganti",
                "inv_grand_ppn" => "ppn",
                "inv_new_net3" => "dpp+ppn",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
            2 => array(
                "harga" => "biaya cashback",
                "nilai_pph_original" => "PPh",
                "nilai_kas_cn" => "kas/creditnote",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(//            2 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
//                "harga",
//                "jml",
//                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),

//        "allowedMainEdit" => array("1"),
//        "receiptElements" => array(
//            "customerDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Detail Konsumen",
//                "mdlName" => "MdlCustomer",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                    "alamat_1" => "alamat",
//                    "alamat_2" => "alamat",
//                    "kelurahan" => "Kel",
//                    "kecamatan" => "Kec",
//                    "kabupaten" => "Kab",
//                    "propinsi" => "Prop",
//                    "tlp" => "Tlp",
//                    "tlp_1" => "Tlp",
//                    "tlp_2" => "Handphone",
//                    "npwp" => "NPWP",
//                    "no_ktp" => "nik",
//                    "nik" => "NIK",
//                    //---------------
////                    "kredit_limit" => "KREDIT LIMIT",
//                    "folder_nama" => "jenis konsumen",
//                ),
//                "editPoints" => array(1, 2),
//                "reloadLink" => "_processPihak/select/",
//            ),
//            "biayaDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Detail Biaya",
//                "mdlName" => "MdlDtaBiayaUsahaKomisi",
//                "mdlFilter" => array("id=pihakMainID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//
//                ),
//                "editPoints" => array(1,),
////                "reloadLink" => "_processPihak/select/",
//            ),
////            "kompensasiMethod" => array(
////                "elementType" => "dataModel",
////                "inputType" => "radio",
////                "label" => "Metode Cashback",
////                "mdlName" => "MdlKompensasiCashbackPenjualan",
////                "key" => "id",
////                "labelSrc" => "name",
////                "usedFields" => array(
////                    "name" => "name",
////                ),
////                "editPoints" => array(1,),
////                "targetMethod" => array(
////                    1 => "ReComKompensasiMethod",
////                    2 => "ReComKompensasiMethod",
////                ),
////            ),
//
//
//            "cabang2" => array(
//                "elementType" => "dataModel",
//                "inputType" => "hidden",
//                "label" => "cabang dc",
//                "mdlName" => "MdlCabang",
//                "mdlFilter" => array("id=." . CB_ID_PUSAT),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "nama" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//            "gudang2" => array(
//                "elementType" => "dataModel",
//                "inputType" => "hidden",
//                "label" => "gudang dc",
//                "mdlName" => "MdlGudangDefault_center",
//                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "nama" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//
//            "pajakOption" => array(
//                "elementType" => "dataModel",
////                "inputType" => "radio",
//                "inputType" => "hidden",
//                "label" => "pph 21 / pph 23 option",
//                "mdlName" => "MdlPajakPPhOption2",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1),
////                "noValidate" => true,
//                // bila dpp pph > 0, maka menjadi mandatori.
//                // bila dpp pph <= 0, maka menjadi tidak mandatori.
////                "noValidateReplacer" => array(
////                    "key" => "dppPPh",
////                ),
//            ),
//
//        ),
//        "relativeElements" => array(
//            "kompensasiMethod" => array(
//                1 => array(
//                    "cash_account" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "tunai / akun bank",
//                        "mdlName" => "MdlBankAccount_cash_and_in",
//                        "mdlFilter" => array(
////                            "cabang_id=placeID",
////                            "jenis2=.1",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                        ),
//                        "editPoints" => array(1, 4),
//                    ),
//
//                ),
//                2 => array(),
//            ),
//            "freelancerOption" => array(
//                1 => array(),
//                2 => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
//                ),
//            ),
//            "pajakOption" => array(
//                "pph21" => array(
//                    "pph21Methode" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "method of pph 21",
//                        "mdlName" => "MdlPph21MethodPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "method",
//                            "tarif" => "tarif (%)",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            "npwp" => "ReComPph21Npwp_penjualan",
//                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
////                            "sket" => "ReComPph21None_purchasing",
//                        ),
//                        "targetMethodAll" => false,
//                        "resetElement" => array(
//                            "pph21Methode",
//                        ),
//                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
//                            "id=pihakMainRulesID",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
//                    "freelancerOption" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Opsi Freelance (Member / Non Member)",
//                        "mdlName" => "MdlEmployeeFreelanceOption",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1),
//                        "resetElement" => array(
//                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
//                        ),
//                    ),
//                ),
//                "pph23" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
//                    "pph23Methode" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "method of pph 23",
//                        "mdlName" => "MdlPph23MethodPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "method",
//                            "tarif" => "tarif (%)",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            "npwp" => "ReComPph23Npwp_penjualan",
//                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
//                        ),
//                        "targetMethodAll" => false,
//                        "resetElement" => array(
//                            "pph23Methode",
//                            "freelancerOption",
//                        ),
//                    ),
//                ),
//                "pph23_15" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
//                    "pph23Methode" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "method of pph 23",
//                        "mdlName" => "MdlPph23MethodCashback",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "method",
//                            "tarif" => "tarif (%)",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            "npwp" => "ReComPph23Npwp_penjualan",
//                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
//                        ),
//                        "targetMethodAll" => false,
//                        "resetElement" => array(
//                            "pph23Methode",
//                            "freelancerOption",
//                        ),
//                    ),
//                ),
//            ),
//        ),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Konsumen",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
//                    "kredit_limit" => "KREDIT LIMIT",
                    "folder_nama" => "jenis konsumen",
                ),
                "editPoints" => array(1, 2),
                "reloadLink" => "_processPihak/select/",
            ),
            "biayaDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Biaya",
//                "mdlName" => "MdlDtaBiayaUsahaKomisi",
                "mdlName" => "MdlDtaBiayaProjectKomisi",
                "mdlFilter" => array("id=pihakMainID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",

                ),
                "editPoints" => array(1,),
//                "reloadLink" => "_processPihak/select/",
            ),
//            "kompensasiMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Metode Cashback",
//                "mdlName" => "MdlKompensasiCashbackPenjualan",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1,),
//                "targetMethod" => array(
//                    1 => "ReComKompensasiMethod",
//                    2 => "ReComKompensasiMethod",
//                ),
//            ),

            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=." . CB_ID_PUSAT),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),

            "pajakOption" => array(
                "elementType" => "dataModel",
//                "inputType" => "radio",
                "inputType" => "hidden",
                "label" => "pph 21 / pph 23 option",
                "mdlName" => "MdlPajakPPhOption2",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
                // bila dpp pph > 0, maka menjadi mandatori.
                // bila dpp pph <= 0, maka menjadi tidak mandatori.
//                "noValidateReplacer" => array(
//                    "key" => "dppPPh",
//                ),
            ),

        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                1 => array(
//                    "cash_account" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "tunai / akun bank",
//                        "mdlName" => "MdlBankAccount_cash_and_in",
//                        "mdlFilter" => array(
////                            "cabang_id=placeID",
////                            "jenis2=.1",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                        ),
//                        "editPoints" => array(1, 4),
//                        "noValidate" => true,
//                    ),
                ),
                2 => array(),
            ),
            "freelancerOption" => array(
                1 => array(),
                2 => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
                        "mdlName" => "MdlKompensasiCashbackPenjualan2",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                            "notif" => "keterangan",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                ),
            ),
            "pajakOption" => array(
                "pph21" => array(
                    "kompensasiMethod" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Metode Cashback",
                        "mdlName" => "MdlKompensasiCashbackPenjualan2",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                            "notif" => "keterangan",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            1 => "ReComKompensasiMethod",
                            2 => "ReComKompensasiMethod",
                        ),
                    ),
                    "pph21Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph21Npwp_penjualan",
                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
//                            "sket" => "ReComPph21None_purchasing",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph21Methode",
                        ),
                    ),
                    "freelancerDetails" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Detail Freelancer",
                        "mdlName" => "MdlEmployeeFreelanceCabang",
                        "mdlFilter" => array(
                            "id=pihakMainRulesID",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "alamat_1" => "alamat",
                            "alamat_2" => "alamat",
                            "kelurahan" => "Kel",
                            "kecamatan" => "Kec",
                            "kabupaten" => "Kab",
                            "propinsi" => "Prop",
                            "tlp" => "Tlp",
                            "tlp_1" => "Tlp",
                            "tlp_2" => "Handphone",
                            "npwp" => "NPWP",
                            "no_ktp" => "nik",
                            "nik" => "NIK",
                        ),
                        "editPoints" => array(1, 2),
                        "reloadLink" => "_processPihak/select/",
                    ),
//                    "freelancerOption" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Opsi Freelance (Member / Non Member)",
//                        "mdlName" => "MdlEmployeeFreelanceOption",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1),
//                        "resetElement" => array(
//                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
//                        ),
//                    ),
                ),
                "pph23" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "mdlFilter" => array(
                            "id=optionFreelancerID",
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
                        "resetElement" => array(
////                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
                        ),
                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
////                            "id=pihakMainRulesID",
//                            "employee_type=.employee_freelance",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
                ),
                "pph23_15" => array(
//                    "kompensasiMethod" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Metode Cashback",
//                        "mdlName" => "MdlKompensasiCashbackPenjualan",
//                        "key" => "id",
//                        "labelSrc" => "name",
//                        "usedFields" => array(
//                            "name" => "name",
//                        ),
//                        "editPoints" => array(1,),
//                        "targetMethod" => array(
//                            1 => "ReComKompensasiMethod",
//                            2 => "ReComKompensasiMethod",
//                        ),
//                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodCashback",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
                            "npwp" => "ReComPph23Npwp_penjualan",
                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                    "freelancerOption" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Opsi Freelance (Member / Non Member)",
                        "mdlName" => "MdlEmployeeFreelanceOption",
                        "mdlFilter" => array(
                            "id=optionFreelancerID",
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "name",
                        ),
                        "editPoints" => array(1),
                        "resetElement" => array(
////                            "pph23Methode",
//                            "kompensasiMethod",
//                            "cash_account",
                        ),
                    ),
//                    "freelancerDetails" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "Detail Freelancer",
//                        "mdlName" => "MdlEmployeeFreelanceCabang",
//                        "mdlFilter" => array(
////                            "id=pihakMainRulesID",
//                            "employee_type=.employee_freelance",
//                        ),
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "nama",
//                            "alamat_1" => "alamat",
//                            "alamat_2" => "alamat",
//                            "kelurahan" => "Kel",
//                            "kecamatan" => "Kec",
//                            "kabupaten" => "Kab",
//                            "propinsi" => "Prop",
//                            "tlp" => "Tlp",
//                            "tlp_1" => "Tlp",
//                            "tlp_2" => "Handphone",
//                            "npwp" => "NPWP",
//                            "no_ktp" => "nik",
//                            "nik" => "NIK",
//                        ),
//                        "editPoints" => array(1, 2),
//                        "reloadLink" => "_processPihak/select/",
//                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),

//        "connectTo" => "2677",
        "pairRegistries" => array(
            "main", "items"
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "16677re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "16677rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "pihakMainName" => "jenis Komisi/cashback",
            "677r" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
            "kompensasiMethod__label" => "kas/creditnote",
            "cash_account__label" => "akun kas",
            "pajakOption__label" => "jenis pph",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "biaya cashback",
            "nilai_pph_original" => "pph",
            "nilai_kas_cn" => "netto",
        ),
        "linkMenu" => array(
            1 => array(
                "link" => "Transaksi/index/16677",
                "label" => "Otorisasi cashback penjualan (biaya)",
            ),
            2 => array(
                "link" => "Transaksi/index/16678",
                "label" => "Otorisasi cashback project (biaya)",
            ),

        ),
        "linkMenuHistory" => array(
            1 => array(
                "link" => "History/viewHistory/16677",
                "label" => "History cashback penjualan",
            ),
            2 => array(
                "link" => "History/viewHistory/16678",
                "label" => "History cashback project",
            ),

        ),
    ),

    "1676" => array(
        "icon" => "fa fa-opencart",
        "label" => "pengeluaran logam mulia",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "Request",
                "actionLabel" => "request",
                "source" => "",
                "target" => "1676r",
                "userGroup" => "c_finance",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "complated by",
            ),
            2 => array(
                "label" => "otorisasi",
                "actionLabel" => "approve order",
                "source" => "1676r",
                "target" => "1676",
                "userGroup" => "c_finance",
                "stateLabel" => "complate",
                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optStateLabel"    => "pending disc. approval",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
            ),

        ),
        "template" => "template/transaksi_logam_mulia.html",
        "selectorModel" => "MdlDtaLogamMulia",
        "selectorSrcModel" => "MdlDtaLogamMulia",
        "selectedPrice" => array(),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerLogamMulia",
        ),
        "selectorFilters" => array(
//            "stock_locker_logam_mulia.cabang_id=placeID",
//            "stock_locker_logam_mulia.gudang_id=gudangID",
//            "stock_locker_logam_mulia.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih jenis logam mulia",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
//            "nama",
            "coa_code",
        ),
        "showHiddenCode" => array(
            "label" => "tampilkan PID/Kode Item",
            "key" => array(
                "coa_code",
            ),
            "targetKey" => "showHiddenCode",
            "targetAction" => "Create/showOption/",
        ),
        "selectorProcessor" => "_processSelectBiaya/selectLogamMulia",
        "editHandlerMethod" => "selectLogamMulia",

        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakResetor" => false,
        "pihakLabel" => "pilih penerima logam mulia",
        "pihakFilters" => array(
            "id>.0",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "dtime" => "date",
            "pihakPembebanan__label" => "cabang pembebanan",
            "customerDetails__nama" => "konsumen",
            "nomer_top" => "Request number",
            "nomer" => "approval number",
            "item_fields" => "isi",
            "harga" => "total amount",
            "nilai_pph21" => "pph ps 21 (RP)",
            "nilai_pph23" => "pph ps 23 (RP)",
            "pphMethodeStatus__label" => "pph",
            "biaya_detail__label" => "kategori biaya",
            "cash_account__folders_nama" => "bank",
            "cash_account__label" => "akun kas/bank",
            "oleh_nama" => "person",
            //------------------------
            "keterangan" => "keterangan",
//            "print_label" => "tool",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
            "nama" => array(
                "label" => "logam mulia",
                "addKey" => "keterangan",
            ),
            "jml" => "jml(gr)",
            "harga" => "nilai",

        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "jml" => "jml",
            "harga" => "harga",
            "stok" => "stok",
            "stock_intransit" => "stock_intransit",
            "stock_active" => "stock_active",

//            "label" => "label",
//            "rekening" => "rekening",
//            "reference" => "reference",
//            "disabled" => "disabled",
//            "kategori_id" => "kategori_id",
//            "kategori_nama" => "kategori_nama",
//            "extern_coa"=>"extern_coa",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item logam mulia",
                "stok" => "saldo (gr)",
                "stock_intransit" => "intransit (gr)",
                "stock_active" => "teredia (gr)",
                "jml" => "jml (gr)",
                "harga" => "harga",
            ),
            2 => array(
                "nama" => "item logam mulia",
                "stok" => "saldo (gr)",
                "stock_intransit" => "intransit (gr)",
                "stock_active" => "teredia (gr)",
                "jml" => "jml (gr)",
                "harga" => "harga",
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
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(
                "harga",
                // "jml",
                "reference",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nilai_pph21" => "pph ps 21",
                "nilai_pph23" => "pph ps 23",
                "grand_total" => "Grand Total",
            ),
            2 => array(
                "nilai_pph21" => "pph ps 21",
                "nilai_pph23" => "pph ps 23",
                "grand_total" => "Grand Total",
            ),
        ),
        "shoppingCartFieldValidators" => array(//            "harga" => "price",

        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "konsumen ID",
            "pihakName" => "konsumen ",
        ),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Detail Konsumen",
                "mdlName" => "MdlCustomer",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "alamat_1" => "alamat",
                    "alamat_2" => "alamat",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Tlp",
                    "tlp_1" => "Tlp",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                    //---------------
                ),
                "editPoints" => array(1),
                "reloadLink" => "_processPihak/select/",
                "noValidate" => false,
            ),
            "pihakPembebanan" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pembebanan",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "cabang pembebanan",
                    "kode" => "kode cabang",
                ),
                "editPoints" => array(1,),
                "noValidate" => false,
            ),
            "biayaKategori" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "pilih kategori biaya",
                "mdlName" => "MdlStaticBiaya",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "kategori biaya",
                ),
                "editPoints" => array(1),
            ),
            "pajakOption" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
//                "inputType" => "hidden",
                "label" => "pph 21/23 option",
                "mdlName" => "MdlPajakPPhOption",// berisi pph21 dan pph23
//                "mdlName" => "MdlPajakPPhOption3",// berisi pph21 dan pph23
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
//                "noValidate" => true,
                // bila dpp pph > 0, maka menjadi mandatori.
                // bila dpp pph <= 0, maka menjadi tidak mandatori.
//                "noValidateReplacer" => array(
//                    "key" => "dppPPh",
//                ),
            ),
            "pphMethodeStatus" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "status pajak",
                "mdlName" => "MdlPph23MethodPotonganMode3",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "nama",
                    "tarif" => "tarif (%)",
                    "kode_bayar_sendiri" => "kode 1",
                    "kode_termasuk_klaim" => "kode 2",
                ),
                "hiddenUsedFields" => array(
                    "kode_bayar_sendiri",
                    "kode_termasuk_klaim",
                ),
                "editPoints" => array(1),
                "noPrefetch" => false,
            ),
        ),
        "relativeElements" => array(
            "biayaKategori" => array(
                "1" => array(
                    "biaya_detail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "Biaya usaha",
                        "mdlName" => "MdlDtaBiayaUsaha",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "2" => array(
                    "biaya_detail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "Biaya umum",
                        "mdlName" => "MdlDtaBiayaUmum",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
            ),
            "pajakOption" => array(
                "pph21" => array(
                    "pph21Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 21",
                        "mdlName" => "MdlPph21MethodPenjualanJumlahLain",
//                        "mdlName" => "MdlPph21MethodPenjualan",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
//                            "nilai_pph_edit" => "jumlah lain",
                        ),
                        "usedFieldsAdd" => array(
                            "jumlah_lain" => array(
                                "nilai_pph_edit" => "jumlah lain",
                            ),
                        ),
                        "editableUsedFields" => array(
                            "nilai_pph_edit" => array(
                                "tipe" => "number",// kolom => tipe data
                                "default_value" => "default__pph21Methode__nilai_pph_edit",
                            ),
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
//                            "npwp" => "ReComPph21Npwp_penjualan",
//                            "non_npwp" => "ReComPph21NonNpwp_penjualan",
//                            "sket" => "ReComPph21None_purchasing",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph21Methode",
                        ),
                    ),
                ),
                "pph23" => array(
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodBiaya",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
//                            "npwp" => "ReComPph23Npwp_penjualan",
//                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                ),
                "pph23_15" => array(
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "method of pph 23",
                        "mdlName" => "MdlPph23MethodCashback",
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "method",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1,),
                        "targetMethod" => array(
//                            "npwp" => "ReComPph23Npwp_penjualan",
//                            "non_npwp" => "ReComPph23NonNpwp_penjualan",
                        ),
                        "targetMethodAll" => false,
                        "resetElement" => array(
                            "pph23Methode",
                        ),
                    ),
                ),
            ),
            "pphMethodeStatus" => array(
                "2" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "folders" => "acountMasterID",
                            "folders_nama" => "accountMaster",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(),
        "pairRegistries" => array("main", "items"),

        "pairMakers" => array(
            1 => array(
                "stokLogamMulia" => array(
                    "helperName" => "he_cek_stock_logam_mulia",
                    "functionName" => "cekStockLogamMulia",
                    "params" => array(
                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokLogamMuliaIntransit" => array(
                    "helperName" => "he_cek_stock_logam_mulia_locker",
                    "functionName" => "cekStockLogamMuliaLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "state" => ".hold",
//                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokLogamMuliaTersedia" => array(
                    "helperName" => "he_cek_stock_logam_mulia_locker",
                    "functionName" => "cekStockLogamMuliaLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "state" => ".active",
//                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "stokLogamMulia" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokLogamMuliaIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stock_intransit",
                    ),
                ),
                "stokLogamMuliaTersedia" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stock_active",
                    ),
                ),
            ),
        ),
        "validationRules" => array(
            "items" => array(
                "target" => "stok",
                "source" => "jml",
            ),
        ),
        "connectedDiscount" => array(),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "383e",
                "label" => "EDIT money exchange",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "383rj",
                "label" => "REJECT money exchange",
            ),
        ),
        //----
        "validateElementsCustom" => array(
            "pph21Methode" => array(
                "enabled" => true,
                "keyCek" => "nilai_pph_edit",
                "source" => "harga",
                "prosentaseMin" => "5",// dalam persen
                "prosentaseMax" => "35",// dalam persen
            ),
        ),
        //----
    ),


);


