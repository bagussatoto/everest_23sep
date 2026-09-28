<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    //pembatalan jurnal non stok

    "9911" => array(
        "icon" => "fa fa-eraser",
        "label" => "pembatalan transaksi",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "Pembatalan transaksi",
                "actionLabel" => "save",
                "source" => "",
                "target" => "9911",
                "userGroup" => "c_holding",
                "stateLabel" => "canceled transaksi",
                "stateColor" => "#dd3300",
                "stateCaption" => "canceled by",
            ),


        ),
        //        "template" => "template/transaksi2.html",
        "template" => "template/transaksi_extern.html",
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectedPrice" => array(
            //            "returned=.0",
            //            "jenis=pihakID",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //"cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //"produk_ord_jml_return=.0",
            "jenis=pihakExternID",
            "jenis_master=pihakExternMasterID",
//            "trash_4=.0",//biar tetap tampil walau tidak bisa dipilih biar tidak miss info
            "cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nota (minimal 4 karakter)",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectNotaRevert/select",
        //        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "vendorDetails__label" => "supplier",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pihakExternName" => "transaksi",
            "referenceNomer" => "number",
            //            "nilai_cancel" => "amount",
            "transaksi_nilai" => "amount",
            "description" => "catatan",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pihakExternName" => "transaksi",
            "referenceNomer" => "number",
            //            "nilai_cancel" => "amount",
            "transaksi_nilai" => "amount",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "vendorDetails__label" => "supplier",
                "transaksi_id" => "trid",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "pihakExternName" => "transaksi",
                "referenceNomer" => "number",
                //                "nilai_cancel" => "amount",
                "transaksi_nilai" => "amount",
                "description" => "catatan",

                "print_label" => "tool",
            ),

        ),
        //
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),

        ),
        "selectorFields" => array("id", "nama", "satuan"),

        //region ini ada tapi dicuekin biar gak muncuk error
        "pihakModel" => "MdlRevertJurnal",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih jenis transaksi",
        "pihakProcessor" => "_processPihak/select",
        "pihakFields" => array("id", "nama"),
        //endregion

        "pihakModelExtern" => "MdlRevertJurnal",
        "pihakExternCaller" => "_selectorPihak/selectPihakExtern",
        "pihakExternLabel" => "pilih jenis transaksi",
        "pihakExternFilters" => array(),
        "pihakExternProcessor" => "_processPihak/selectExtern",

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "purchase number",
                "jml" => "qty",
            ),
            2 => array(
                "nama" => "purchase number",
                "jml" => "qty",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "produk_nama",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "nilai_bayar" => "nilai_bayar",
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
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
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "amount",
            ),
            2 => array(
                "sisa" => "amount",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartRowValidators" => array(
            //            "pihakID" => "customer ID",
            //            "pihakName" => "customer name",
            "pihakExternID" => "jenis transaksi yang dibatalkan",
            "pihakExternName" => "jenis transaksi yang dibatalkan",
            "description" => "deskripsi alasan pembatalan",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
            2 => "sisa",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "grand total",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
            2 => array(
                "sisa" => "grand total",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => false,
        "receiptElements" => array(
            "transaksi" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "receipt number",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array("id=referenceID"),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "nomer",
                    "dtime" => "date",
                    "oleh_nama" => "by",
                    "jenis" => "jenis",
                    "cabang_id" => "cabangID",
                    "cabang_nama" => "cabangName",
                ),
                "editPoints" => array(1),
            ),
        ),
        "relativeElements" => array(),
        "pairRegistries" => array(
            "main",
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
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
                    "out_detail" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "validationRules" => array(),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "",
                        "addPoints" => array(1),
                        "editPoints" => array(4),
                    ),
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "",
                        "addPoints" => array(1, 4),
                        "editPoints" => array(4),
                    ),
                ),
                //                "ongkir_tanpa_ppn_by_company" =>array(
                //                    "shipping_service" => array(
                //                        "label" => "shipping service",
                //                        "defaultValue" => "",
                //                        "maxValue" => "",
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                //                        'disabled' => "",
                //                        "addPoints" => array(1,),
                //                    ),
                //
                //                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        ),
        "allowedMainEdit" => array("1"),

        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
        "previewCtr" => "Create",
        "transaksi_pembatalanChecker" => array(
            "467" => array(
                "sourceMain" => "extracted_serial_diff",
                "sourceDetail" => "diff_serial",
                "sourceDetailProduk" => "diff_serial_produk",
                "sourceAllDetailProduk" => "all_serial_produk",
                "label" => "daftar serial yang tidak tersedia/sudah digunakan (pembatalan tidak bisa dilanjutkan) dari GRN yang dipilih.",
            ),
        ),
        "relativeElementsAllowed" => array(
            "3333" => true,
            "16677" => true,
            "16678" => true,
            "1676" => true,
            "5844" => true,
            //-----
            "462" => true,
            "483" => true,
            "487" => true,
        ),

//        "addTableInMasterToMain" => array(
//            "cash_account" => "cash_account",
//            "cash_account__label" => "cash_account__label",
//            "cash_account__nama" => "cash_account__nama",
//            "cash_account__saldo" => "cash_account__saldo",
//            "cash_account__folders" => "cash_account__folders",
//        ),

        "koreksi_serial" => array(
            "467" => array(
                "enabled" => true,
                "label" => "daftar serial dari GRN yang dipilih.",
                "ipaddr" => array(
                    "202.65.117.72",
                    "192.168.5.4",
                ),
                "link" => "_shoppingCart/koreksiSerial",
                "targetKoreksi" => "items7_sum",
                "addPostProcessor" => array(
                    // rekening pembantu produk serial intransit
                    99 => array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "kategori_id" => "kategori_id",//ini untuk skip produk jasa
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
                "movedLink" => "_shoppingCart/viewMoveSerial",
            ),
            "1467" => array(
                "enabled" => true,
                "label" => "daftar serial dari GRN yang dipilih.",
                "ipaddr" => array(
                    "202.65.117.72",
                    "192.168.5.4",
                ),
                "link" => "_shoppingCart/koreksiSerial",
                "targetKoreksi" => "items7_sum",
                "addPostProcessor" => array(
                    // rekening pembantu produk serial intransit
                    99 => array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "kategori_id" => "kategori_id",//ini untuk skip produk jasa
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
                "movedLink" => "_shoppingCart/viewMoveSerial",
            ),
        ),
        "showLastStockFields" => array(
            "id" => "pid",
            "barcode" => "barcode",
            "nama" => "Descriptions",
            "produk_kode" => "sku",
            "keterangan" => "part",
            "satuan" => "UOM",
            "jml" => "Qty",
            "last_stock_avail" => "stok",
        ),

//        "warning_item" => "Isikan minimal 4 karakter pada formulir diatas.",
        "warning_item" => "Isikan minimal 4 karakter.",

        "componentsGantiRekening" => array(
            "19467" => array(
                "label" => "Pembatalan Otorisasi Pengembalian Uang ke Konsumen akan memindahkan saldo Uang Muka berelasi ke Uang Muka atas nama {customerName}",
            ),
        ),

    ),

    //pembatalan jurnal non stok cabang

    "9912" => array(
        "icon" => "fa fa-eraser",
        "label" => "pembatalan transaksi",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "Pembatalan transaksi",
                "actionLabel" => "save",
                "source" => "",
                "target" => "9912",
                "userGroup" => "o_finance",
                "stateLabel" => "canceled transaksi",
                "stateColor" => "#dd3300",
                "stateCaption" => "canceled by",
                "allowRemove" => false,
            ),
        ),
        //        "template" => "template/transaksi2.html",
        "template" => "template/transaksi_extern.html",
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectedPrice" => array(
            //            "returned=.0",
            //            "jenis=pihakID",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //"cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //"produk_ord_jml_return=.0",
            "jenis=pihakExternID",
            "jenis_master=pihakExternMasterID",
//            "trash_4=.0",//yang cancel biar tetap tampil
            "cabang_id=placeID",// baris ini jangan dimatikan supaya tidak nyasar cabang saat cari nota yang akan dibatalkan
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nota (minimal 4 karakter)",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectNotaRevert/select",
        //        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customerName" => "konsumen",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pihakExternName" => "transaksi",
            "referenceNomer" => "number",
            //            "nilai_cancel" => "amount",
            "transaksi_nilai" => "amount",
            "description" => "catatan",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pihakExternName" => "transaksi",
            "referenceNomer" => "number",
            //            "nilai_cancel" => "amount",
            "transaksi_nilai" => "amount",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customerName" => "konsumen",
                "transaksi_id" => "trid",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "pihakExternName" => "transaksi",
                "referenceNomer" => "number",
                //                "nilai_cancel" => "amount",
                "transaksi_nilai" => "amount",
                "description" => "catatan",

                "print_label" => "tool",
            ),

        ),
        //
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
            2 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),

        ),
        "selectorFields" => array("id", "nama", "satuan"),

        //region ini ada tapi dicuekin biar gak muncuk error
        "pihakModel" => "MdlRevertJurnal",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih jenis transaksi",
        "pihakProcessor" => "_processPihak/select",
        "pihakFields" => array("id", "nama"),
        //endregion

        "pihakModelExtern" => "MdlRevertJurnalCabang",
        "pihakExternCaller" => "_selectorPihak/selectPihakExtern",
        "pihakExternLabel" => "pilih jenis transaksi",
        "pihakExternFilters" => array(),
        "pihakExternProcessor" => "_processPihak/selectExtern",

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "purchase number",
                "jml" => "qty",
            ),
            2 => array(
                "nama" => "purchase number",
                "jml" => "qty",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "produk_nama",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "nilai_bayar" => "nilai_bayar",
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "sisa" => "amount",
                "nilai_bayar" => "amount",
            ),
            2 => array(
                "sisa" => "amount",
            ),
        ),
        "shoppingCartEditableFields" => array(),

        "shoppingCartRowValidators" => array(
            "pihakExternID" => "jenis transaksi yang dibatalkan",
            "pihakExternName" => "jenis transaksi yang dibatalkan",
            "description" => "deskripsi alasan pembatalan",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
            2 => "sisa",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => false,
            3 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nilai_bayar" => "grand total",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
            2 => array(
                "sisa" => "grand total",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartAvoidRemoveAll_items" => false,
        "receiptElements" => array(
            "transaksi" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "receipt number",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array("id=referenceID"),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "nomer",
                    "dtime" => "date",
                    "oleh_nama" => "by",
                    "jenis" => "jenis",
                    "cabang_id" => "cabangID",
                    "cabang_nama" => "cabangName",
                    "pembayaran" => "pembayaran",
                ),
                "editPoints" => array(1),
            ),

        ),
        "relativeElements" => array(),
        //----
        "receiptElementsTambahan" => array(
            "5822" => array(
                "key" => "reference_pembayaran",
                "value" => "cash",
                "element" => array(
                    "alasan_pembatalan" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "alasan pembatalan",
                        "mdlName" => "MdlAlasanPembatalan",
//                        "mdlFilter" => array(
//                            "id=referenceID"
//                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "label",
                            "reject_jual_tunai" => "penerimaan penjualan tunai auto batal",
                            "notif" => "keterangan",
                        ),
                        "editPoints" => array(1),
                        "disabledOption" => false,
                    ),
                ),

            ),
            "5823" => array(
                "key" => "reference_pembayaran",
                "value" => "cash",
                "element" => array(
                    "alasan_pembatalan" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "alasan pembatalan",
                        "mdlName" => "MdlAlasanPembatalan",
//                        "mdlFilter" => array(
//                            "id=referenceID"
//                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "label",
                            "reject_jual_tunai" => "penerimaan penjualan tunai auto batal",
                            "notif" => "keterangan",
                        ),
                        "editPoints" => array(1),
                        "disabledOption" => false,
                    ),
                ),
            ),
        ),
        //----
        "pihakTambahan" => array(
            "5822" => array(
                "source" => array(
                    "pihakExternID" => "4464",
                    "pihakExternMasterID" => "4464",
                    "pihakExternName" => "PENERIMAAN PENJUALAN TUNAI",
                    "pihakExternValueSrc" => "nilai_bayar",
                    "pihakExternRevertStep" => false,
                    "pihakExternDetailGate" => "",
                    "pihakExternRevertRequest" => false,
                ),
            ),
            "5823" => array(
                "source" => array(
                    "pihakExternID" => "4464",
                    "pihakExternMasterID" => "4464",
                    "pihakExternName" => "PENERIMAAN PENJUALAN TUNAI",
                    "pihakExternValueSrc" => "nilai_bayar",
                    "pihakExternRevertStep" => false,
                    "pihakExternDetailGate" => "",
                    "pihakExternRevertRequest" => false,
                ),
            ),
        ),
        //----
        "pairRegistries" => array(
            "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
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
                    "out_detail" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "validationRules" => array(),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "",
                        "addPoints" => array(1),
                        "editPoints" => array(4),
                    ),
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                        'disabled' => "",
                        "addPoints" => array(1, 4),
                        "editPoints" => array(4),
                    ),
                ),
                //                "ongkir_tanpa_ppn_by_company" =>array(
                //                    "shipping_service" => array(
                //                        "label" => "shipping service",
                //                        "defaultValue" => "",
                //                        "maxValue" => "",
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
                //                        'disabled' => "",
                //                        "addPoints" => array(1,),
                //                    ),
                //
                //                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        ),
        "allowedMainEdit" => array("1"),

        "previewJurnal" => array(
            "enabled" => true,
            "src" => "revert",
            "mainGate" => "master",
            "comName" => "Jurnal",

        ),
        "previewCtr" => "Create",
//        "warning_item" => "Isikan minimal 4 karakter pada formulir diatas.",
        "warning_item" => "Isikan minimal 4 karakter.",
        //-----------------
        "shoppingCartKeterangan" => array(
            "4467" => array(
                "label_head" => "Apakah Anda yakin ingin membatalkan uang muka  <strong>{referenceNomer}</strong> ?",
                "label_sub" => "<br><b>Pilih tindakan selanjutnya:</b><br><b>1. Ubah Jadi Titipan:</b> Batalkan nomor ini, lalu input ulang sebagai \"Titipan an. Konsumen\". <br><b>2. Uang Dikembalikan:</b> Batalkan nomor ini jika konsumen batal menyerahkan uang muka.",
            ),

        ),


    ),

    // pengahpusan piutang
    "9749" => array(
        "icon" => "fa fa-money",
        "label" => "Penghapusan Piutang",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "Request Penghapusan Piutang",
                "actionLabel" => "process request",
                "source" => "",
                "target" => "9749r",
                "userGroup" => "o_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "Otorisasi Penghapusan Piutang",
                "actionLabel" => "approve",
                "source" => "9749r",
                "target" => "9749",
                "userGroup" => "o_finance_spv",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "confirmed by",
            ),
        ),
        "paymentConfig" => false,
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlPaymentSource",
        "selectorSrcModel" => "MdlPaymentSource",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "extern_id=pihakID",
            "target_jenis=.749",
            "sisa>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            //            "dtime",
        ),
        //        "selectorProcessor" => "_processSelectProduct/select",
        "selectorProcessor" => "_processSelectNota/selectId",
        "editHandlerMethod" => "selectId",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            //            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer_top" => "request number",
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "Approval number",
            ),
            //            "details" => "detail",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
            "next_pic" => "Next step otorisator",
            //            "print_label" => "tool",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                //            "jenis_label" => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                //                "nomer_po" => array(
                //                    "step" => 2,
                //                    "key" => "nomer",
                //                    "label" => "Approval number",
                //                ),
                //            "details" => "detail",
                "oleh_nama" => "person",
                "nilai_entry" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                //            "jenis_label" => "activity",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer_po" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "Approval number",
                ),
                //            "details" => "detail",
                "oleh_nama" => "person",
                "nilai_entry" => "amount",
                "print_label" => "tool",
            ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "request number",
            "oleh_nama" => "person",
            "nilai_entry" => "amount",
        ),
        "extHistoryFields" => array(
            1 => array(
                "print_label" => "nomer",
            ),
            2 => array(
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
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nomer" => "nomer",
            "nama" => "nomer",
            "name" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "refID" => "transaksi_id",

        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa tagihan",
            ),
            2 => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa tagihan",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
            2 => "sisa",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "subtotal" => "total tagihan",
            ),
            2 => array(
                "subtotal" => "total tagihan",
            ),
        ),


        "pairRegistries" => array(
            "tableIn_master_values",
        ),
        "shoppingCartAvoidRemove" => false,
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "tagihanSrc" => "harus_bayar",
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "customer details",
                // "mdlName"     => "MdlCustomer_and_pre",
                "mdlName" => "MdlCustomerAll",//gak mandang trash dan status
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Address",
                    "tlp_1" => "Phone",
                    "npwp" => "NPWP",
                ),
                "editPoints" => array(1, 2, 3, 4),
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
                "stock" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "mainValueInjectors" => array(
            "amount" => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar" => "harus_bayar",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartRowNumValidators" => array(// "nilai_entry" => "nominal penghapusan",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit amount",
                "nilai_entry" => "amount value",
                "nilai_biaya" => "amount value",
            ),
        ),
        "shopingCartUnionComparison" => array(
            //            array(
            //                "nilai_entry" => "payment belum diisi",
            //                "cash_account" => "cash account belum dipilih",
            ////
            ////                "lebih_bayar" => "kelebihan bayar nol (0)",
            ////                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
            //            ),
            //            array(
            ////                "nilai_entry" => "payment belum diisi",
            ////                "cash_account" => "cash account belum dipilih",
            ////
            //                "lebih_bayar" => "kelebihan bayar nol (0)",
            //                "kelebihanBayar" => "method kelebihan bayar belum dipilih",
            //            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "sales number",
            "nomer_top" => "sales ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "customer",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "nilai_entry" => array(
                        "label" => "penghapusan piutang",
                        "maxValue" => "nilai_entry",
                        // "minValue" => "0",
                        "defaultValue" => "sisa",
                        'disabled' => "disabled",
                        "keyupAction" => "",
                        "addPoints" => array(1,),
                    ),
                    //                    "harus_bayar" => array(
                    //                        "label" => "total invoice (netto)",
                    //                        "defaultValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                    //                        "maxValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                    //                        "minValue" => "(sisa-credit_note_dipakai-creditValue-nilai_biaya)",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "nilai_entry" => array(
                    //                        "label" => "penghapusan",
                    //                        "maxValue" => "sisa",
                    //                        // "minValue" => "0",
                    //                        "defaultValue" => ".0",
                    //                       "keyupAction" => "
                    //     if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=addCommas(document.getElementById('harus_bayar').value);}
                    //
                    //                             ",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "new_sisa" => array(
                        "label" => "sisa tagihan",
                        "defaultValue" => "new_sisa",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    // "harus_bayar" => array(
                    //          "label" => "Grand total",
                    //          "defaultValue" => "harus_bayar",
                    //          "maxValue" => "harus_bayar",
                    //          "minValue" => "harus_bayar",
                    //          //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                    //          //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",
                    //          "hideRow" => "true",
                    //          "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                ),
            ),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        //        "sourceTarget" => "749",
        "columnRecorderTarget" => true,// tambahan discription/note/catatan bawah
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9749re",
                "label" => "EDIT Request Penghapusan Piutang",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9749rrj",
                "label" => "REJECT Request Penghapusan Piutang",
            ),
        ),
//        "warning_item" => "Isikan minimal 4 karakter pada formulir diatas.",
        "warning_item" => "Isikan minimal 4 karakter.",
    ),
);