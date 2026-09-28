<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(

    // stok opname produk pusat
    "1119" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname produk (pusat)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "1119r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 1",
                "actionLabel" => "approve request",
                "source" => "1119r",
                "target" => "1119ro",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 2",
                "actionLabel" => "approve ",
                "source" => "1119ro",
                "target" => "1119", // packed
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProduk",
        "selectorSrcModel" => "MdlProduk",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuProduk",
            "rekening" => "persediaan produk",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
            "no_part" => "no_part",
        ),
        "selectorViewedFields" => array(
            "keterangan",
            "kode",
            "kategori_nama",
//            "barcode",
            "sub_kategori_nama",
//            "no_part",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
//        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "request number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
//                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
//                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
//                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "request number",
                "nomer" => "otorisasi 1",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer_oto" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "otorisasi 1",
                ),
                "nomer" => "otorisasi 2",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

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

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stock inventory",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),
            2 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),
            3 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "no_part" => "no_part",
//            "label" => "label",
//            "satuan" => "satuan",
//            "ppn" => "harga*(10/100)",
//            "stok" => "stock",
//            "debet" => "harga*qty_debet",
//            "kredit" => "harga*qty_kredit",

            "nama" => "nama",
            "produk_kode" => "kode",
            "kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "outdoor_sku" => "outdoor_sku",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_sku_1" => "indoor_sku_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_sku_2" => "indoor_sku_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_sku_3" => "indoor_sku_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "indoor_sku_4" => "indoor_sku_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(this.value)*parseFloat(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(this.value)/parseFloat(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "qty_opname" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
//            4 => true,
//            5 => true,
        ),
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
//                "harga" => "amount",
//                "disc" => "disc",
//                "ongkir_ui" => "shipping service",
//                "grand_total_ui" => "total amount",
//                "grand_ppn" => "vat",
//                "new_net3" => "grand total",
            ),
            2 => array(
//                "shipping_service" => "Shipping Service",
//                "grand_total_ui" => "Total Amount",
//                "grand_ppn" => "VAT",
//                //                "tagihan_ui" => "Grand Total",
//                "new_net3" => "Grand Total",
            ),
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
                "nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
                "produk_nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
                "produk_nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
            ),
        ),
        "receiptElements" => array(
            // ====akan tampil di UI ===== //

//            "ppv_index" => array(
//                "elementType" => "dataModel",
////                "inputType" => "hidden",
//                "inputType" => "radio",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
////                    "kode=.lokal",
//                    "jenis2=.produk",
//                ),
//                "key" => "id",
//                "labelSrc" => "kode",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
//                        "defaultValue" => "nett2",
//                        "minValue" => "nett2",
//                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
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
                    "source" => "items7_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items7_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),
            ),
//            5 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
////                    "gate" => "produk_sku_serial",
//                    "gate" => "produk_serial",
//                ),
//
//            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items5_sum"
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
//                "stokHpp" => array(
//                    "helperName" => "he_cek_stock_produk_hpp",
//                    "functionName" => "cekStockProdukHpp",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                    ),
//                ),
            ),
//            2 => array(
//                "stokProduk" => array(
//                    "helperName" => "he_cek_stock_produk",
//                    "functionName" => "cekStockProduk",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                    ),
//                ),
//            ),
//            3 => array(
//                "stokProduk" => array(
//                    "helperName" => "he_cek_stock_produk",
//                    "functionName" => "cekStockProduk",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                    ),
//                ),
//                "hppProduk" => array(
//                    "helperName" => "he_cek_price_produk",
//                    "functionName" => "cekPriceProduk",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "jenis_value" => ".hpp",
//                    ),
//                ),
//            ),
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
//                "stokHpp" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "harga",
//                    ),
//                ),
            ),
//            2 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "stokHpp" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "harga",
////                    ),
////                ),
//            ),
//            3 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "hppProduk" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "hpp",
////                    ),
////                ),
//            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
//        "connectedDiscount" => array(
//            "enabled" => false,
//            "mdlNameRelation" => "MdlConnectedDiscount",
//            "mdlNameSource" => "MdlAddDiscount",
//            //            "jenis" => "produk",
//            //            "jenis_locker" => "stock",
//        ),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,
        // "uploadFields" => array(
        //     "label" => "upload data",
        //     "action" => "UploaderXls/opname/produk",
        //     "cCode" => "_TR_1119",
        // ),

        "downloadFields" => array(
            "label" => "Produk Download & Upload Opname",
            "action" => "opname/Opname/view/Produk/persediaan_produk",
            "cCode" => "_TR_1119",
            "jenisTr" => "1119",
            // "attr" => "disabled",
            "addClass" => "btn-primary"
//           "btnDisabled" => true,
        ),

        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1119re",
                "label" => "EDIT STOCK OPNAME",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1119rrj",
                "label" => "REJECT STOCK OPNAME",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1119rorj",
                "label" => "REJECT STOCK OPNAME AUTHORIZATION 1",
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
        "pairedItemLiveEdit" => array(
            "target" => "items4_sum",
        ),
        "checkOpnameSerialMinusValidate" => array(
            "enabled" => true,
            "gateValidate" => "items5",
        ),
    ),
    // stok opname produk cabang
    "2229" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname produk (branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "2229r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 1",
                "actionLabel" => "approve request",
                "source" => "2229r",
                "target" => "2229ro",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 2",
                "actionLabel" => "approve ",
                "source" => "2229ro",
                "target" => "2229", // packed
                "userGroup" => "o_gudang",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProduk",
        "selectorSrcModel" => "MdlProduk",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuProduk",
            "rekening" => "010304",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
            "no_part" => "no_part",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "no_part",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
//        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "Request number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "Request number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
//                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
//                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
//                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "Request number",
                "nomer" => "otorisasi 1",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "Request number",
                "nomer_oto" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "otorisasi 1",
                ),
                "nomer" => "otorisasi 2",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

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

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stock inventory",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),
            2 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),
            3 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "no_part" => "no_part",
//            "label" => "label",
//            "satuan" => "satuan",
//            "ppn" => "harga*(10/100)",
//            "stok" => "stock",
//            "debet" => "harga*qty_debet",
//            "kredit" => "harga*qty_kredit",

            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "outdoor_sku" => "outdoor_sku",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_sku_1" => "indoor_sku_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_sku_2" => "indoor_sku_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_sku_3" => "indoor_sku_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "indoor_sku_4" => "indoor_sku_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(this.value)*parseFloat(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(this.value)/parseFloat(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "qty_opname" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
//            4 => true,
//            5 => true,
        ),
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
//                "harga" => "amount",
//                "disc" => "disc",
//                "ongkir_ui" => "shipping service",
//                "grand_total_ui" => "total amount",
//                "grand_ppn" => "vat",
//                "new_net3" => "grand total",
            ),
            2 => array(
//                "shipping_service" => "Shipping Service",
//                "grand_total_ui" => "Total Amount",
//                "grand_ppn" => "VAT",
//                //                "tagihan_ui" => "Grand Total",
//                "new_net3" => "Grand Total",
            ),
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
                "nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
                "produk_nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
                "produk_nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
            ),
        ),
        "receiptElements" => array(
            // ====akan tampil di UI ===== //

//            "ppv_index" => array(
//                "elementType" => "dataModel",
////                "inputType" => "hidden",
//                "inputType" => "radio",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
////                    "kode=.lokal",
//                    "jenis2=.produk",
//                ),
//                "key" => "id",
//                "labelSrc" => "kode",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
//                        "defaultValue" => "nett2",
//                        "minValue" => "nett2",
//                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items5_sum"
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
                "stokHpp" => array(
                    "helperName" => "he_cek_stock_produk_hpp",
                    "functionName" => "cekStockProdukHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            2 => array(
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
                "hppProduk" => array(
                    "helperName" => "he_cek_price_produk",
                    "functionName" => "cekPriceProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "jenis_value" => ".hpp",
                    ),
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpname",
                    "source" => "rsltItems",
                    "sourceKey" => array(
                        "kredit", "qty_kredit", "debet", "kredit_rsltItems"
                    ),
                ),
                "preBiayaMain" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_main_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpnameMain",
                    "source" => "main",
                    "sourceKey" => array(
                        "kredit", "debet", "kredit_rsltItems"
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
//                "stokHpp" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "harga",
//                    ),
//                ),
            ),
//            3 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "hppProduk" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "hpp",
////                    ),
////                ),
//            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,
        // "uploadFields" => array(
        //     "label" => "upload data",
        //     "action" => "UploaderXls/opname/produk",
        //     "cCode" => "_TR_2229",
        // ),
        "downloadFields" => array(
            "label" => "Download & Upload Produk Opname",
            "action" => "opname/Opname/view/Produk/persediaan_produk",
            "cCode" => "_TR_2229",
            "jenisTr" => "2229",
            // "attr" => "disabled",
            //           "btnDisabled" => true,
            "addClass" => "btn-primary"
        ),
        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2229re",
                "label" => "EDIT STOCK OPNAME",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2229rrj",
                "label" => "REJECT STOCK OPNAME",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "2229rorj",
                "label" => "REJECT STOCK OPNAME AUTHORIZATION 1",
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

        "downloadFields" => array(
            "label" => "Download & Upload Opname",
            "action" => "opname/Opname/view/Produk/persediaan_produk",
            "cCode" => "_TR_2229",
            "jenisTr" => "2229",
            // "attr" => "disabled",
//           "btnDisabled" => true,
        ),

    ),
    // stok opname supplies cabang bom
    "2228" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname supplies (branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME BOM",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "2228r",
                "userGroup" => "p_gudang",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME BOM AUTHORIZATION 1",
                "actionLabel" => "approve request",
                "source" => "2228r",
                "target" => "2228ro",
                "userGroup" => "p_gudang",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME BOM AUTHORIZATION 2",
                "actionLabel" => "approve ",
                "source" => "2228ro",
                "target" => "2228", // packed
                "userGroup" => "p_gudang_spv",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuSupplies",
            "rekening" => "persediaan supplies",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
        //        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "request number",
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "approval 1 number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "approval 2 number",
            ),
            "oleh_nama" => "person",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "request number",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "request number",
                "nomer" => "approval 1 number",
                "oleh_nama" => "person",

                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "approval 1 number",
                ),
                "nomer" => "approval 2 number",
                "oleh_nama" => "person",

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
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stock inventory",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stok",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stok",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "stok" => "stock",
            "debet" => "harga*qty_debet",
            "kredit" => "harga*qty_kredit",

            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
            //
            //            "volume" => "volume",
            //            "berat" => "berat",
            //            "lebar" => "lebar",
            //            "tinggi" => "tinggi",
            //            "panjang" => "panjang",

            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            //            "jml" => "quantity",
            //            "qty_opname" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",

        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => true,
        ),
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
                //                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                //                "grand_ppn" => "vat",
                //                "new_net3" => "grand total",
            ),
            2 => array(
                //                "shipping_service" => "Shipping Service",
                //                "grand_total_ui" => "Total Amount",
                //                "grand_ppn" => "VAT",
                //                //                "tagihan_ui" => "Grand Total",
                //                "new_net3" => "Grand Total",
            ),
        ),

        "receiptElements" => array(
            // ====akan tampil di UI ===== //

//            "ppv_index" => array(
//                "elementType" => "dataModel",
////                "inputType" => "hidden",
//                "inputType" => "radio",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
////                    "kode=.lokal",
//                    "jenis2=.supplies",
//                ),
//                "key" => "id",
//                "labelSrc" => "kode",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
                        //                        "defaultValue" => "nett2",
                        //                        "minValue" => "nett2",
                        //                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpname",
                    "source" => "items",
                    "sourceKey" => array(
                        "debet", "kredit", "qty_debet", "qty_kredit",
                    ),
                ),
                "stokHpp" => array(
                    "helperName" => "he_cek_stock_supplies_hpp",
                    "functionName" => "cekStockSuppliesHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokHpp" => array(
                    "helperName" => "he_cek_stock_supplies_hpp",
                    "functionName" => "cekStockSuppliesHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpname",
                    "source" => "rsltItems",
                    "sourceKey" => array(
                        "kredit", "qty_kredit", //"hpp", "jml", "qty",
                    ),
                    "params" => array(),
                ),
                "preBiayaMain" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_main_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpnameMain",
                    "source" => "main",
                    "sourceKey" => array(
                        "kredit", "debet", "kredit_rsltItems"
                    ),
                ),
                "stokHpp" => array(
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
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
//                "stokHpp" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "harga",
//                    ),
//                ),

            ),
//            2 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "stokHpp" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "harga",
////                    ),
////                ),
//            ),
//            3 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "stokHpp" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "harga",
////                    ),
////                ),
//            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),

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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,

        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        "uploadFields" => array(
            "label" => "upload data",
            "action" => "UploaderXls/opname/supplies",
            "cCode" => "_TR_2228",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2228re",
                "label" => "EDIT STOCK OPNAME BOM",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2228rrj",
                "label" => "REJECT STOCK OPNAME BOM",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "2228rorj",
                "label" => "REJECT STOCK OPNAME BOM AUTHORIZATION 1",
            ),
        ),

        "downloadFields" => array(
            "label" => "Download & Upload Opname",
            "action" => "opname/Opname/view/Supplies/persediaan_supplies",
            "cCode" => "_TR_2228",
            "jenisTr" => "2228",
            // "attr" => "disabled",
//           "btnDisabled" => true,
        ),

    ),
    // stok opname supplies cabang non bom
    "2227" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname supplies non bom (branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME NON BOM",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "2227r",
                "userGroup" => "p_gudang_spv",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME NON BOM AUTHORIZATION 1",
                "actionLabel" => "approve request",
                "source" => "2227r",
                "target" => "2227ro",
                "userGroup" => "p_gudang_spv",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME NON BOM AUTHORIZATION 2",
                "actionLabel" => "approve ",
                "source" => "2227ro",
                "target" => "2227", // packed
                "userGroup" => "p_gudang_spv",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuSupplies",
            "rekening" => "persediaan supplies",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
        //        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "request number",
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "approval 1 number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "approval 2 number",
            ),
            "oleh_nama" => "person",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "request number",
                "oleh_nama" => "person",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "request number",
                "nomer" => "approval 1 number",
                "oleh_nama" => "person",

                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "approval 1 number",
                ),
                "nomer" => "approval 2 number",
                "oleh_nama" => "person",

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
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stock inventory",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stok",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stok",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "stok" => "stock",
            "debet" => "harga*qty_debet",
            "kredit" => "harga*qty_kredit",

            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
            //
            //            "volume" => "volume",
            //            "berat" => "berat",
            //            "lebar" => "lebar",
            //            "tinggi" => "tinggi",
            //            "panjang" => "panjang",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            //            "jml" => "quantity",
            //            "qty_opname" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",

        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => true,
        ),
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
                //                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                //                "grand_ppn" => "vat",
                //                "new_net3" => "grand total",
            ),
            2 => array(
                //                "shipping_service" => "Shipping Service",
                //                "grand_total_ui" => "Total Amount",
                //                "grand_ppn" => "VAT",
                //                //                "tagihan_ui" => "Grand Total",
                //                "new_net3" => "Grand Total",
            ),
        ),

        "receiptElements" => array(
            // ====akan tampil di UI ===== //

//            "ppv_index" => array(
//                "elementType" => "dataModel",
////                "inputType" => "hidden",
//                "inputType" => "radio",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
////                    "kode=.lokal",
//                    "jenis2=.supplies",
//                ),
//                "key" => "id",
//                "labelSrc" => "kode",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
                        //                        "defaultValue" => "nett2",
                        //                        "minValue" => "nett2",
                        //                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpname",
                    "source" => "items",
                    "sourceKey" => array(
                        "debet", "kredit", "qty_debet", "qty_kredit",
                    ),
                ),
                "stokHpp" => array(
                    "helperName" => "he_cek_stock_supplies_hpp",
                    "functionName" => "cekStockSuppliesHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokHpp" => array(
                    "helperName" => "he_cek_stock_supplies_hpp",
                    "functionName" => "cekStockSuppliesHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpname",
                    "source" => "rsltItems",
                    "sourceKey" => array(
                        "kredit", "qty_kredit", //"hpp", "jml", "qty",
                    ),
                ),
                "stokHpp" => array(
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
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokHpp" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_hpp",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokHpp" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_hpp",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokHpp" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_hpp",
                    ),
                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),

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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,

        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        "uploadFields" => array(
            "label" => "upload data",
            "action" => "UploaderXls/opname/supplies",
            "cCode" => "_TR_2227",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2227re",
                "label" => "EDIT STOCK OPNAME NON BOM",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2227rrj",
                "label" => "REJECT STOCK OPNAME NON BOM",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "2227rorj",
                "label" => "REJECT STOCK OPNAME NON BOM AUTHORIZATION 1",
            ),
        ),
    ),
    // stok opname produk solo, non rakitan
    "3339" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname produk (NON RAKITAN)",
        "place" => "branch",//=> "center",
        "placeExtended" => "factory",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME (NON RAKITAN)",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "3339r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 1 (NON RAKITAN)",
                "actionLabel" => "approve request",
                "source" => "3339r",
                "target" => "3339ro",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 2 (NON RAKITAN)",
                "actionLabel" => "approve ",
                "source" => "3339ro",
                "target" => "3339", // packed
                "userGroup" => "o_gudang",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProduk",
        "selectorSrcModel" => "MdlProduk",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuProduk",
            "rekening" => "persediaan produk",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
            "no_part" => "no_part",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "no_part",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
//        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "Request number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "Request number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
//                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
//                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
//                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "Request number",
                "nomer" => "otorisasi 1",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "Request number",
                "nomer_oto" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "otorisasi 1",
                ),
                "nomer" => "otorisasi 2",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

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

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "produk name",
                "produk_kode" => "produk code",
                "no_part" => "part number",
//                "stok" => "stock inventory",
//                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "produk code",
                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "nama" => "produk name",
                "produk_kode" => "produk code",
                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "stok" => "stock",
            "debet" => "harga*qty_debet",
            "kredit" => "harga*qty_kredit",

//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//
//            "volume" => "volume",
//            "berat" => "berat",
//            "lebar" => "lebar",
//            "tinggi" => "tinggi",
//            "panjang" => "panjang",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(this.value)*parseFloat(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(this.value)/parseFloat(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "qty_opname" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
//            4 => true,
//            5 => true,
        ),
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
//                "harga" => "amount",
//                "disc" => "disc",
//                "ongkir_ui" => "shipping service",
//                "grand_total_ui" => "total amount",
//                "grand_ppn" => "vat",
//                "new_net3" => "grand total",
            ),
            2 => array(
//                "shipping_service" => "Shipping Service",
//                "grand_total_ui" => "Total Amount",
//                "grand_ppn" => "VAT",
//                //                "tagihan_ui" => "Grand Total",
//                "new_net3" => "Grand Total",
            ),
        ),

        "receiptElements" => array(
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
//                        "defaultValue" => "nett2",
//                        "minValue" => "nett2",
//                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
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
            2 => array(
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
                "hppProduk" => array(
                    "helperName" => "he_cek_price_produk",
                    "functionName" => "cekPriceProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "jenis_value" => ".hpp",
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
//            3 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "hppProduk" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "hpp",
////                    ),
////                ),
//            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,
        "uploadFields" => array(
            "label" => "upload data",
            "action" => "UploaderXls/opname/produk",
            "cCode" => "_TR_3339",
        ),

        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3339re",
                "label" => "EDIT STOCK OPNAME (NON RAKITAN)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3339rrj",
                "label" => "REJECT STOCK OPNAME (NON RAKITAN)",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "3339rorj",
                "label" => "REJECT STOCK OPNAME AUTHORIZATION 1 (NON RAKITAN)",
            ),
        ),
    ),
    // stok opname produk solo, rakitan
    "5559" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname rakitan (pabrik)",
        "place" => "branch",//=> "center",
        "placeExtended" => "factory",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME RAKITAN",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "5559r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 1 (RAKITAN)",
                "actionLabel" => "approve request",
                "source" => "5559r",
                "target" => "5559ro",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 2 (RAKITAN)",
                "actionLabel" => "approve ",
                "source" => "5559ro",
                "target" => "5559", // packed
                "userGroup" => "o_gudang",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProdukRakitan",
        "selectorSrcModel" => "MdlProdukRakitan",
        "selectedPrice" => array(
            "model" => "MdlHargaProdukRakitan",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuProduk",
            "rekening" => "persediaan produk rakitan",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
            "no_part" => "no_part",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "no_part",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
//        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "Request number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "Request number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
//                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
//                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
//                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "Request number",
                "nomer" => "otorisasi 1",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "Request number",
                "nomer_oto" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "otorisasi 1",
                ),
                "nomer" => "otorisasi 2",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

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

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "produk name",
                "produk_kode" => "produk code",
                "no_part" => "part number",
//                "stok" => "stock inventory",
//                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "produk code",
                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "nama" => "produk name",
                "produk_kode" => "produk code",
                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "stok" => "stock",
            "debet" => "harga*qty_debet",
            "kredit" => "harga*qty_kredit",

//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//
//            "volume" => "volume",
//            "berat" => "berat",
//            "lebar" => "lebar",
//            "tinggi" => "tinggi",
//            "panjang" => "panjang",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(this.value)*parseFloat(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(this.value)/parseFloat(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "qty_opname" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
//            4 => true,
//            5 => true,
        ),
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
//                "harga" => "amount",
//                "disc" => "disc",
//                "ongkir_ui" => "shipping service",
//                "grand_total_ui" => "total amount",
//                "grand_ppn" => "vat",
//                "new_net3" => "grand total",
            ),
            2 => array(
//                "shipping_service" => "Shipping Service",
//                "grand_total_ui" => "Total Amount",
//                "grand_ppn" => "VAT",
//                //                "tagihan_ui" => "Grand Total",
//                "new_net3" => "Grand Total",
            ),
        ),

        "receiptElements" => array(
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
//                        "defaultValue" => "nett2",
//                        "minValue" => "nett2",
//                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_rakitan",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_rakitan",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_rakitan",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
//                "hppProduk" => array(
//                    "helperName" => "he_cek_price_produk",
//                    "functionName" => "cekPriceProduk",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "jenis_value" => ".hpp",
//                    ),
//                ),
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpname",
                    "source" => "rsltItems",
                    "sourceKey" => array(
                        "kredit", "qty_kredit", "debet", "kredit_rsltItems"
                    ),
                ),
                "preBiayaMain" => array(
                    "helperName" => "he_pair_produksi_prebiaya_opname_main_helper",
                    "functionName" => "cekPairProduksiPreBiayaOpnameMain",
                    "source" => "main",
                    "sourceKey" => array(
                        "kredit", "debet", "kredit_rsltItems"
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
//            3 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "hppProduk" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "hpp",
////                    ),
////                ),
//            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,
        "uploadFields" => array(
            "label" => "upload data",
            "action" => "UploaderXls/opname/produk",
            "cCode" => "_TR_5559",
        ),

        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5559re",
                "label" => "EDIT STOCK OPNAME RAKITAN",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5559rrj",
                "label" => "REJECT STOCK OPNAME RAKITAN",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "5559rorj",
                "label" => "REJECT STOCK OPNAME AUTHORIZATION 1 (RAKITAN)",
            ),
        ),
    ),
    // stok opname supplies pusat
    "1118" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname supplies (center)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "1118r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 1",
                "actionLabel" => "approve request",
                "source" => "1118r",
                "target" => "1118ro",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 2",
                "actionLabel" => "approve ",
                "source" => "1118ro",
                "target" => "1118", // packed
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuSupplies",
            "rekening" => "persediaan supplies",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
//        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
            //            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "nett1" => "netto",
                //                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                //                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
                //                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                //                "ongkir" => "shipping service",
                //                "grand_ppn" => "ppn",
                //                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer_oto" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "otorisasi 1",
                ),
                "nomer" => "otorisasi 2",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                //                "ongkir" => "shipping service",
                //                "grand_ppn" => "ppn",
                //                "new_net3" => "total amount",

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

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stock inventory",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stok",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stok",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "stok" => "stock",
            "debet" => "harga*qty_debet",
            "kredit" => "harga*qty_kredit",

            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
            //
            //            "volume" => "volume",
            //            "berat" => "berat",
            //            "lebar" => "lebar",
            //            "tinggi" => "tinggi",
            //            "panjang" => "panjang",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "qty_opname" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
//            4 => true,
//            5 => true,
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartSumFields" => array(
            1 => array(
                //                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                //                "grand_ppn" => "vat",
                //                "new_net3" => "grand total",
            ),
            2 => array(
                //                "shipping_service" => "Shipping Service",
                //                "grand_total_ui" => "Total Amount",
                //                "grand_ppn" => "VAT",
                //                //                "tagihan_ui" => "Grand Total",
                //                "new_net3" => "Grand Total",
            ),
        ),

        "receiptElements" => array(
            // ====akan tampil di UI ===== //

//            "ppv_index" => array(
//                "elementType" => "dataModel",
////                "inputType" => "hidden",
//                "inputType" => "radio",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
////                    "kode=.lokal",
//                    "jenis2=.produk",
//                ),
//                "key" => "id",
//                "labelSrc" => "kode",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
                        //                        "defaultValue" => "nett2",
                        //                        "minValue" => "nett2",
                        //                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "pairRegistries" => array(
            "tableIn_master_values",
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokHpp" => array(
                    "helperName" => "he_cek_stock_supplies_hpp",
                    "functionName" => "cekStockSuppliesHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                //                "hppProduk" => array(
                //                    "helperName" => "he_cek_price_produk",
                //                    "functionName" => "cekPriceProduk",
                //                    "params" => array(
                //                        "cabang_id" => "placeID",
                //                        "jenis_value" => ".hpp",
                //                    ),
                //                ),
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
//                "stokHpp" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "harga",
//                    ),
//                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
                ),
                //                "hppProduk" => array(
                //                    "items" => array(
                //                        "targetKey" => "id",
                //                        "targetColumn" => "hpp",
                //                    ),
                //                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),

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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,
        "previewCtr" => "Create",

        "downloadFields" => array(
            "label" => "Download & Upload Stock Opname Supplies",
            "action" => "opname/Opname/view/Supplies/persediaan_supplies",
            "cCode" => "_TR_1118",
            "jenisTr" => "1118",
            "addClass" => "btn-primary"
            // "attr" => "disabled",
//           "btnDisabled" => true,
        ),

        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1118re",
                "label" => "EDIT STOCK OPNAME",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1118rrj",
                "label" => "REJECT STOCK OPNAME",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1118rorj",
                "label" => "REJECT STOCK OPNAME AUTHORIZATION 1",
            ),
        ),
    ),
    "7779" => array(),

    // stok opname supplies pusat project
    "4418" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname supplies (project)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "4418r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 1",
                "actionLabel" => "approve request",
                "source" => "4418r",
                "target" => "4418ro",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 2",
                "actionLabel" => "approve ",
                "source" => "4418ro",
                "target" => "4418", // packed
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuSupplies",
            "rekening" => "persediaan supplies",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
//        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
            //            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "harga" => "amount",
            //            "disc" => "discount",
            //            "ppn" => "ppn",
            //            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "nett1" => "netto",
                //                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
                //                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
                //                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                //                "ongkir" => "shipping service",
                //                "grand_ppn" => "ppn",
                //                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer_oto" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "otorisasi 1",
                ),
                "nomer" => "otorisasi 2",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

                //                "ongkir" => "shipping service",
                //                "grand_ppn" => "ppn",
                //                "new_net3" => "total amount",

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

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stock inventory",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stok",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "id" => "pID",
                "nama" => "produk name",
                //                "produk_kode" => "part number",
                //                "stok" => "stok",
                //                "jml" => "qty",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "stok" => "stock",
            "debet" => "harga*qty_debet",
            "kredit" => "harga*qty_kredit",

            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross" => "volume_gross",
            //
            //            "volume" => "volume",
            //            "berat" => "berat",
            //            "lebar" => "lebar",
            //            "tinggi" => "tinggi",
            //            "panjang" => "panjang",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
                //                "qty_debet" => "masuk",
                //                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "qty_opname" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
//            4 => true,
//            5 => true,
        ),
        "shoppingCartAvoidRemove" => true,
        "shoppingCartSumFields" => array(
            1 => array(
                //                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "total amount",
                //                "grand_ppn" => "vat",
                //                "new_net3" => "grand total",
            ),
            2 => array(
                //                "shipping_service" => "Shipping Service",
                //                "grand_total_ui" => "Total Amount",
                //                "grand_ppn" => "VAT",
                //                //                "tagihan_ui" => "Grand Total",
                //                "new_net3" => "Grand Total",
            ),
        ),

        "receiptElements" => array(
            // ====akan tampil di UI ===== //

//            "ppv_index" => array(
//                "elementType" => "dataModel",
////                "inputType" => "hidden",
//                "inputType" => "radio",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
////                    "kode=.lokal",
//                    "jenis2=.produk",
//                ),
//                "key" => "id",
//                "labelSrc" => "kode",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
                        //                        "defaultValue" => "nett2",
                        //                        "minValue" => "nett2",
                        //                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "pairRegistries" => array(
            "tableIn_master_values",
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                "stokHpp" => array(
                    "helperName" => "he_cek_stock_supplies_hpp",
                    "functionName" => "cekStockSuppliesHpp",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
                //                "hppProduk" => array(
                //                    "helperName" => "he_cek_price_produk",
                //                    "functionName" => "cekPriceProduk",
                //                    "params" => array(
                //                        "cabang_id" => "placeID",
                //                        "jenis_value" => ".hpp",
                //                    ),
                //                ),
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
//                "stokHpp" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "harga",
//                    ),
//                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
                ),
                //                "hppProduk" => array(
                //                    "items" => array(
                //                        "targetKey" => "id",
                //                        "targetColumn" => "hpp",
                //                    ),
                //                ),
            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),

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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,
        "previewCtr" => "Create",

        "downloadFields" => array(
            "label" => "Download & Upload Stock Opname Supplies",
            "action" => "opname/Opname/view/Supplies/persediaan_supplies",
            "cCode" => "_TR_4418",
            "jenisTr" => "4418",
            "addClass" => "btn-primary"
            // "attr" => "disabled",
//           "btnDisabled" => true,
        ),

        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4418re",
                "label" => "EDIT STOCK OPNAME",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4418rrj",
                "label" => "REJECT STOCK OPNAME",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "4418rorj",
                "label" => "REJECT STOCK OPNAME AUTHORIZATION 1",
            ),
        ),
    ),
    // stok opname produk pusat project
    "4419" => array(
        "icon" => "fa fa-opencart",
        "label" => "stok opname produk (project)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "STOCK OPNAME",
                "actionLabel" => "make request",
                "source" => "",
                "target" => "4419r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval 1",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowRemove" => false,
            ),
            2 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 1",
                "actionLabel" => "approve request",
                "source" => "4419r",
                "target" => "4419ro",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval 2",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
            3 => array(
                "label" => "STOCK OPNAME AUTHORIZATION 2",
                "actionLabel" => "approve ",
                "source" => "4419ro",
                "target" => "4419", // packed
                "userGroup" => "c_holding",
                "stateLabel" => "complete",
                "stateColor" => "#009900",
                "stateCaption" => "Complete by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowRemove" => false,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProduk",
        "selectorSrcModel" => "MdlProduk",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp",),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
            "mdlFilter" => array(
                "cabang_id=placeID",
            ),
        ),
        "opnameHpp" => array(
            "src_model" => "Coms",
            "model" => "ComRekeningPembantuProduk",
            "rekening" => "persediaan produk",
            "mainSrc" => array(
                "harga" => "harga",
            ),
            "mdlFilter" => array(
                "cabang_id=placeID",
                "gudang_id=gudangID",
            ),
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
            "no_part" => "no_part",
        ),
        "selectorViewedFields" => array(
            "keterangan",
            "kode",
            "kategori_nama",
//            "barcode",
            "sub_kategori_nama",
//            "no_part",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/selectNoQty",
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "pihakID",
            "pihakName",
        ),
        "editHandlerMethod" => "selectNoQty",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id=cabang_id",
        ),
//        "pihakProcessor" => "_processPihakOpname/select",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            //            "transaksi_nilai" => "amount",
            // "jual" => "amount",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",

        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nomer_top" => "SO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "request number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
//                "ongkir" => "shipping service",
                // "ongkir" => "shiping",
                // "ppn" => "ppn",
//                "grand_ppn" => "ppn",
                // "nett2" => "total amount",
//                "new_net3" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "request number",
                "nomer" => "otorisasi 1",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

                "print_label" => "tool",
            ),
            3 => array(
                // "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "request number",
                "nomer_oto" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "otorisasi 1",
                ),
                "nomer" => "otorisasi 2",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "nett1" => "netto",
                // "ppn" => "ppn",
                // "nett2" => "total amount",

//                "ongkir" => "shipping service",
//                "grand_ppn" => "ppn",
//                "new_net3" => "total amount",

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

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCartFields" => array(
            1 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stock inventory",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),
            2 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),
            3 => array(
//                "nama" => "produk name",
//                "produk_kode" => "produk code",
//                "no_part" => "part number",
//                "stok" => "stok",
//                "jml" => "qty",
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
                "satuan" => "uom",
            ),

        ),
        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "no_part" => "no_part",
//            "label" => "label",
//            "satuan" => "satuan",
//            "ppn" => "harga*(10/100)",
//            "stok" => "stock",
//            "debet" => "harga*qty_debet",
//            "kredit" => "harga*qty_kredit",

            "nama" => "nama",
            "produk_kode" => "kode",
            "kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
            "part_id_1" => "part_id_1",
            "part_nama_1" => "part_nama_1",
            "part_barcode_1" => "part_barcode_1",
            "part_id_2" => "part_id_2",
            "part_nama_2" => "part_nama_2",
            "part_barcode_2" => "part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "outdoor_sku" => "outdoor_sku",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_sku_1" => "indoor_sku_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_sku_2" => "indoor_sku_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_sku_3" => "indoor_sku_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "indoor_sku_4" => "indoor_sku_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            //-------------------
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            2 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
            3 => array(
                "harga" => "price",
                "stok" => "stok buku",
//                "qty_debet" => "masuk",
//                "qty_kredit" => "keluar",
                "qty_opname" => "stok riil",
                "qty_debet" => "selisih (+)",
                "qty_kredit" => "selisih (-)",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "qty_opname",
                "harga",
            ),
            2 => array(
                "qty_opname",
                "harga",
            ),
            3 => array(
                "qty_opname",
                "harga",
            ),
        ),
        "shoppingCartUnionSelectors" => array(
            1 => array(
                "base" => "disc_percent",
                "members" => array(
                    "disc_percent",
                    "disc",
                ),
            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(this.value)*parseFloat(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(this.value)/parseFloat(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "qty_opname" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartFieldOpnameValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartFieldOpnameEntryValidators" => array(
            "qty_opname" => "quantity",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang ID",
            "pihakName" => "cabang name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",//nett2
            2 => "jml*(harga-disc+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)+ppn",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
//            4 => true,
//            5 => true,
        ),
        "shoppingCartAvoidRemove" => false,
        "shoppingCartSumFields" => array(
            1 => array(
//                "harga" => "amount",
//                "disc" => "disc",
//                "ongkir_ui" => "shipping service",
//                "grand_total_ui" => "total amount",
//                "grand_ppn" => "vat",
//                "new_net3" => "grand total",
            ),
            2 => array(
//                "shipping_service" => "Shipping Service",
//                "grand_total_ui" => "Total Amount",
//                "grand_ppn" => "VAT",
//                //                "tagihan_ui" => "Grand Total",
//                "new_net3" => "Grand Total",
            ),
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
                "nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
                "produk_nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
                "produk_nama" => array(
                    "source" => "items5_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
                    "gate" => "serial",
                ),
            ),
        ),
        "receiptElements" => array(
            // ====akan tampil di UI ===== //

//            "ppv_index" => array(
//                "elementType" => "dataModel",
////                "inputType" => "hidden",
//                "inputType" => "radio",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
////                    "kode=.lokal",
//                    "jenis2=.produk",
//                ),
//                "key" => "id",
//                "labelSrc" => "kode",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
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
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1, 4),
                    ),
                ),
                "cia" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "bank account",
                        "mdlName" => "MdlBankAccount_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "currency_id=.0",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "",
                        ),
                        "editPoints" => array(1,),
                    ),
                ),
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
                    ),
                ),
                //                "debit_card" => array(
                //                    "debit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "debit account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "credit_card" => array(
                //                    "credit_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "credit account",
                //                        "mdlName" => "MdlCreditCard",
                //                        "key" => "id",
                //                        "labelSrc" => "name",
                //                        "usedFields" => array(
                //                            "name" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "bank account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cash" => array(
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_holding",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),
                    "dp" => array(
                        "label" => "down payment",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                ),
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
//                        "defaultValue" => "nett2",
//                        "minValue" => "nett2",
//                        "maxValue" => "nett2",
                        "defaultValue" => "new_net3",
                        "minValue" => "new_net3",
                        "maxValue" => "new_net3",
                        "auth" => array(
                            //                            "groupID" => "c_finance",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1,),
                    ),
                    "discount" => array(
                        "label" => "open discount",
                        "defaultValue" => ".0",
                        "maxValue" => "nett2*50/100",
                        "auth" => array(
                            //                            "groupID" => "c_admin",
                            "groupID" => "o_finance",
                        ),
                        "addPoints" => array(1, 2),
                    ),

                ),

            ),
        ),

        "followupItemEditable" => "_followupLiveEdit/updateItemFieldOpname/",
        "followupMainEditable" => "_followupLiveEdit/updateMainFieldOpname/",

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items5_sum"
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
//                "stokHpp" => array(
//                    "helperName" => "he_cek_stock_produk_hpp",
//                    "functionName" => "cekStockProdukHpp",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                    ),
//                ),
            ),
//            2 => array(
//                "stokProduk" => array(
//                    "helperName" => "he_cek_stock_produk",
//                    "functionName" => "cekStockProduk",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                    ),
//                ),
//            ),
//            3 => array(
//                "stokProduk" => array(
//                    "helperName" => "he_cek_stock_produk",
//                    "functionName" => "cekStockProduk",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                    ),
//                ),
//                "hppProduk" => array(
//                    "helperName" => "he_cek_price_produk",
//                    "functionName" => "cekPriceProduk",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "jenis_value" => ".hpp",
//                    ),
//                ),
//            ),
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
//                "stokHpp" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "harga",
//                    ),
//                ),
            ),
//            2 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "stokHpp" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "harga",
////                    ),
////                ),
//            ),
//            3 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                    "out_detail" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
////                "hppProduk" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "hpp",
////                    ),
////                ),
//            ),
        ),
        "validationRules" => array(
            //            "items" => array(
            //                "target" => "stok",
            //                "source" => "jml",
            //            ),
        ),
//        "connectedDiscount" => array(
//            "enabled" => false,
//            "mdlNameRelation" => "MdlConnectedDiscount",
//            "mdlNameSource" => "MdlAddDiscount",
//            //            "jenis" => "produk",
//            //            "jenis_locker" => "stock",
//        ),
        "additionalRows" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => array(
                        "label" => "shipping service",
                        "defaultValue" => "",
                        "maxValue" => "",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
                //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
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
        "addMainStep" => array(
            "749" => array(
                "jenis_master" => "582",
                "jenis" => "582",
                "target" => "749",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "keyupAction" => true,
        // "uploadFields" => array(
        //     "label" => "upload data",
        //     "action" => "UploaderXls/opname/produk",
        //     "cCode" => "_TR_4419",
        // ),

        "downloadFields" => array(
            "label" => "Produk Download & Upload Opname",
            "action" => "opname/Opname/view/Produk/persediaan_produk",
            "cCode" => "_TR_4419",
            "jenisTr" => "4419",
            // "attr" => "disabled",
            "addClass" => "btn-primary"
//           "btnDisabled" => true,
        ),

        "checkOpname" => true,
        "checkOpnameValidate" => true,
        "checkNote" => array(
            "enabled" => true,
            "label_1" => "Data yang diinput {total_baris} baris.",
            "label_2" => "Total quantity {total_qty} unit.",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4419re",
                "label" => "EDIT STOCK OPNAME",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4419rrj",
                "label" => "REJECT STOCK OPNAME",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "4419rorj",
                "label" => "REJECT STOCK OPNAME AUTHORIZATION 1",
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
        "pairedItemLiveEdit" => array(
            "target" => "items4_sum",
        ),
        "checkOpnameSerialMinusValidate" => array(
            "enabled" => true,
            "gateValidate" => "items5",
        ),
    ),
);