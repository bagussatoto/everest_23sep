<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
//    "466" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "FG purchasing",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "PRE PURCHASE ORDER",
//                "actionLabel" => "make purchasing order",
//                "source" => "",
//                "target" => "466r",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Prepare by",
//            ),
//            2 => array(
//                "label" => "PURCHASE ORDER",
//                "actionLabel" => "approved purchasing",
//                "source" => "466r",
//                "target" => "466",
//                "userGroup" => "c_purchasing_adm",
//                "stateLabel" => "purchased",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "Approval by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
//            3 => array(
//                "label" => "GOODS RECEIVED NOTE",
//                "actionLabel" => "undo/reject/GRN",
//                "buttonLabel" => "receive & make GRN",
//                "source" => "466",
//                "target" => "467",
//                "userGroup" => "c_gudang",
//                "stateLabel" => "GRN made",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//                "allowEdit" => true,
//                "allowJoin" => true,
//                "settlementMainFields" => array(
//                    "dtime" => array(
//                        "label" => "tanggal",
//                        "format" => "formatField",
//                        "format_footer" => false,
//                    ),
//                    "nomer" => array(
//                        "label" => "invoice",
//                        "format" => "formatField",
//                    ),
//                    // "supplierName" => array(
//                    //     "label" => "vendor",
//                    // ),
//                    "grand_total" => array(
//                        "label" => "nilai",
//                        "format" => "formatField",
//                        "sumFields" => true,
//                    ),
//                ),
//                "settlementItemFields" => array(
//                    "nama" => array(
//                        "label" => "produk",
//                        "format" => "formatField",
//                    ),
//                    "qty" => array(
//                        "label" => "qty",
//                        "attr" => "width='40px'",
//                        "format" => "formatField",
//                    ),
//                    // "harga2" => array(
//                    //     "attr"   => "width='60px' class='text-right'",
//                    //     "label" => "harga",
//                    //     "format" => "formatField",
//                    // ),
//                    // "ppn" => array(
//                    //     "attr"   => "width='40px' class='text-right'",
//                    //     "label" => "ppn",
//                    //     "format" => "formatField",
//                    // ),
//                    "hpp_nppn" => array(
//                        "attr" => "width='40px' class='text-right'",
//                        "label" => "harga",
//                        "format" => "formatField",
//                    ),
//                    "sub_hpp_nppn" => array(
//                        "attr" => "width='60px' class='text-right'",
//                        "label" => "sub",
//                        "format" => "formatField",
//                    ),
//                ),
//            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "buttonLabel" => "approve",
//                "source" => "467",
//                "target" => "111",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//                "allowEdit" => false,
//                "allowJoin" => false,
//            ),
//        ),
//        "template" => "template/transaksi.html",
//        "selectorModel" => "MdlProdukPerSupplier",
//        "selectorSrcModel" => "MdlProduk",
//        "selectedPrice" => array(
//            "model" => "MdlHargaProdukPerSupplier",
//            "mdlFilter" => array("suppliers_id=pihakID"),
//            "label" => array("hpp"),
//            "key_label" => array(
//                "hpp" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(),
//        "selectorFilters" => array(
//            "suppliers_id=pihakID",
//            "cabang_id=placeID",
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "produk_id",
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "no_part" => "no_part",
//            "satuan" => "satuan",
//        ),
//        "selectorViewedFields" => array(
//            "keterangan",
//            "kode",
//            "no_part",
//            "satuan",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "vendor",
//        "pihakFilters" => array(
////            "country=.ID",
////            "id=.-1",
//        ),
//        "pihakMainValueSrc" => array(
//            "ppnFactor" => "ppn",
//        ),
//        "pihakProcessor" => "_processPihak/select",
//        "pihakAddValidate" => array(
//            "mode" => "!=",
//            "filter" => array(
//                "country" => "ID",
//            ),
//            "label" => array(
//                "country" => "FG purchasing (lokal) harus memilih vendor lokal atau perbaiki data vendor.",
//            ),
//
//        ),
//        //---------------------------------------------------
//        "shoppingCartNoteEnabled" => true, //tambahan notes per items dishoppingcart
//        "shortHistoryFields" => array(
//            //            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer_top" => "PRE-PO number",
//            // sumber dari kolom id_his
//            "nomer_po" => array(
//                "step" => 2,
//                "key" => "nomer",
//                "label" => "PO number",
//            ),
//            "nomer_grn" => array(
//                "step" => 3,
//                "key" => "nomer",
//                "label" => "GRN number",
//            ),
//            "nomer_ppn" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
//            //            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "next_pic" => "Next step otorisator",
//            "keterangan" => "keterangan",
//        ),
//        "historyFields" => array(
//            1 => array(
//                "no" => "no",
////                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                //                "nomer" => "receipt number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                // "nett1" => "sub amount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "keterangan" => "keterangan",
//                "print_nvalas" => "tool",
//            ),
//            2 => array(
//                "no" => "no",
////                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "nomer" => "PO number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "keterangan" => "keterangan",
//                "print_nvalas" => "tool",
//            ),
//            3 => array(
//                "no" => "no",
//                // "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer" => "Receipt number",
//                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                //                "harga" => "amount",
//                //                "disc" => "discount",
//                //                "ppn" => "ppn",
//                //                "nett" => "total amount",
//
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            4 => array(
//                "no" => "no",
//                // "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer_grn" => array(
//                    "step" => 3,
//                    "key" => "nomer",
//                    "label" => "GRN number",
//                ),
//                "nomer" => "Receipt number",
//                "description_main_followup" => "INV<br>from vendor",
//                "oleh_nama" => "person",
//                "eFaktur" => "e-faktur",
//                "ppn" => "ppn",
//                //                "transaksi_nilai" => "amount",
//                //                "harga" => "amount",
//                //                "disc" => "discount",
//                //                "ppn" => "ppn",
//                //                "nett" => "total amount",
//
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//        ),
//        "extHistoryFields" => array(
//            1 => array("print_label" => "nomer"),
//            2 => array("print_label" => "nomer"),
//            3 => array("print_label" => "nomer"),
//            4 => array("print_label" => "nomer"),
//        ),
//        "compactHistoryFields" => array(
//            "suppliers_nama" => "vendor",
//            "transaksi_nilai" => "amount",
//        ),
//        "shortStatusFields" => array(
//            //            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "status_next" => "status",
//            "suppliers_nama" => "vendor",
//            "nomer_top" => "Req PO number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            //            "trash_4" => "trash 4",
//        ),
//
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCartHideSubamount" => array(
//            1 => false,
//            2 => false,
//            3 => true,
//            4 => false,
//        ),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "no_part" => "no_part",
//            "label" => "label",
//            "satuan" => "satuan",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Descriptions",
//                "produk_kode" => "product code",
//                "no_part" => "part number",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "nama" => "Descriptions",
//                "produk_kode" => "product code",
//                "no_part" => "part number",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            3 => array(
//                "nama" => "Descriptions",
//                "produk_kode" => "product code",
//                "no_part" => "part number",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            4 => array(
//                "nama" => "Descriptions",
//                "produk_kode" => "Part-No",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
//                "harga" => "Unit Price",
//                //                "ppn" => "VAT",
//            ),
//            2 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
//                "harga" => "Unit Price",
//                //                "ppn" => "VAT",
//            ),
//            3 => array(
//                //                "harga" => "Price",
//                //                "ppn" => "VAT",
//            ),
//            4 => array(
//                "harga" => "Unit Price",
//                //                "ppn" => "VAT",
//            ),
//        ),
//        "shoppingCartNoteEditabled" => array(
//            2 => true,
//            3 => true,
//        ),
//        "shoppingCartNoteType" => "textarea",
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "jml",
//                "produk_ord_jml",
//            ),
//            2 => array(
//                "harga",
//                "jml",
//                "produk_ord_jml",
//            ),
//            3 => array(
//                "jml",
//                "produk_ord_jml",
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "(jml*harga)",// hpp
//            2 => "(jml*harga)",// hpp
//            3 => "(jml*harga)",// hpp
//            4 => "(jml*harga)",// hpp
//        ),
//        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                "harga" => "Total Amount",
//                // "ppv" => "index",
//                "ppn" => "VAT",
//                "hpp_nppn" => "Grand Total",
//            ),
//            //            3 => array(
//            //                "harga" => "Total Amount",
//            //                // "ppv" => "index",
//            //                "ppn" => "VAT",
//            //                "hpp_nppn" => "Grand Total",
//            //            ),
//        ),
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
//        "allowedMainEdit" => array("1"),
//        "pairRegistries" => array(
//            "main", "items", "tableIn_master_values"
//        ),
//        "receiptElements" => array(
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "VENDOR DETAILS",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "",
//                    "alamat_1" => "",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    "contact_person" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "capacity" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "capacity",
//                "mdlName" => "MdlCapacity",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY ADDRESS",
//                "mdlName" => "MdlSupplierAddress",
//                "key" => "id",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "extern_name" => "",
//                    "alamat" => "",
//                    "tlp" => "Phone",
//                    "alias" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "tos" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Term Of Shipment",
//                "mdlName" => "MdlTos",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "description" => "",
//                "usedFields" => array(
//                    "nama" => "",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),
//
//            "shippingDate" => array(
//                "elementType" => "dataField",
//                "label" => "Shipping Date",
//                "inputType" => "date",
//                "defaultValue" => date("Y-m-d"),
//                "editPoints" => array(1, 2, 3, 4, 5),
//            ),
//
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Payment Method",
//                "mdlName" => "MdlPaymentMethod1",
//                //                "mdlName" => "MdlPaymentMethodCredit",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "defaultValue" => "credit",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//            // ====akan tampil di UI ===== //
//            "ppv_index" => array(
//                "elementType" => "dataModel",
//                "inputType" => "hidden",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
//                    "kode=.lokal",
//                    "jenis2=.produk",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
//
//        ),
//        "relativeElements" => array(
//            "paymentMethod" => array(
//                //                "cash" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "combo",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //
//                //
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                //                "cia" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount_out",
//                //
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //
//                //
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                "credit" => array(
//                    "top" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "term of payment",
//                        "mdlName" => "MdlTop",
//                        "mdlFilter" => array(),
//                        "key" => "kode",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                    ),
//                ),
//            ),
//        ),
//        "relativeOptions" => array(
//            "paymentMethod" => array(
//                "cia" => array(
//                    "nilai_cia" => array(
//                        "label" => "cash amount",
//                        "defaultValue" => "nett",
//                        "minValue" => "nett",
//                        "maxValue" => "nett",
//                    ),
//
//                ),
//                "cbd" => array(
//                    "nilai_cbd" => array(
//                        "label" => "cash amount",
//                        "defaultValue" => "nett",
//                        "minValue" => "nett",
//                        "maxValue" => "nett",
//                    ),
//
//                ),
//                "tt_adv" => array(
//                    "nilai_tt_adv" => array(
//                        "label" => "cash amount",
//                        "defaultValue" => "nett",
//                        "minValue" => "nett",
//                        "maxValue" => "nett",
//                    ),
//
//                ),
//            ),
//        ),
//        "resumeFieldNames" => array(
//            "selectFields" => "suppliers_nama",
//            "title" => "vendor",
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "suppliers_nama" => "vendor",
//            "jenis_label" => "activity",
//            "transaksi_nilai" => "orig. value",
//            "add_disc" => "discount",
//            "grand_total" => "nett",
//        ),
//        "validatePaymentSource" => array(
//            "3" => "MdlLockerValue",
//        ),
//        "addMainStep" => array(
//            //            "489" => array(
//            //                "jenis_master" => "467",
//            //                "jenis" => "467",
//            //                "target" => "489",
//            //                "status_4" => "1",
//            //                "trash_4" => "0",
//            //            ),
//        ),
//        "addMainSource" => array(
//            4 => array(
//                "fields" => array(
//                    "nomer" => "INV",
//                    "harga" => "DPP",
//                    "ppn" => "PPN (belum ada faktur)",
//                    "ppn_realisasi" => "PPN Realisasi",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "e-faktur",
//                ),
//                "editableFields" => array(
//                    "harga" => "number",
//                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
//                ),
//            ),
//        ),
//        "receiptEdit" => array(
//            4 => true,
//        ),
//        // berada di midValidate() Transaksi
//        "efakturValidator" => array(
//            4 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
//                    "eFaktur" => "nomer e-faktur belum diisikan.",
//                ),
//                "source" => array(
//                    "ppn", // lebih dari 0
//                    //                "ppnfactor",
//                ),
//            ),
//        ),
//        // ======== =========
//        "followupMainNoteValidator" => array(
//            3 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
//                ),
//                "source" => array(
//                    "description_main_followup",
//                ),
//            ),
//        ),
//        "followupMainNote" => array(
//            3 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => true,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//            4 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//
//        ),
//        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
//        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
//        // ======== =========
//        "xShipmentConfig" => array(
//            1 => array(
//                "enabled" => true,
//                "label" => "close/fullfillment purchasing order",
//                "targetJenisMaster" => "1967",
//                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
//                "allowedGroups" => array(
//                    "c_holding",
//                    "c_purchasing",
//                    "c_purchasing_spv",
//                    //                    "c_gudang",
//                    //                    "c_gudang_spv",
//                    //                    "c_finance"
//                ),
//            ),
//            2 => array(
//                "enabled" => true,
//                "label" => "close/fullfillment purchasing order",
//                "targetJenisMaster" => "1967",
//                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
//                "allowedGroups" => array(
//                    "c_holding",
//                    "c_purchasing",
//                    "c_purchasing_spv",
//                    //                    "c_gudang",
//                    //                    "c_gudang_spv",
//                    //                    "c_finance"
//                ),
//            ),
//        ),
//        "pairMakers" => array(
//            1 => array(
//                "hargaLastPurchase" => array(
//                    "helperName" => "he_cek_price_produk_last_purchase",
//                    "functionName" => "cekPriceProdukLastPurchase",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        //                        "gudang_id" => ".0",
//                        "jenis_value" => ".hpp",
//                    ),
//                ),
//            ),
//            2 => array(
//                "dataProduk" => array(
//                    "helperName" => "he_pair_data_produk",
//                    "functionName" => "cekPairDataProduk",
//                    "params" => array(
//                        //                        "cabang_id" => ".-1",
//                        //                        "gudang_id" => ".-1",
//                        //                        "state" => ".active",
//                    ),
//                    "kolom" => array(
//                        "no_part",
//                    ),
//                ),
//            ),
//            3 => array(
//                "dataProduk" => array(
//                    "helperName" => "he_pair_data_produk",
//                    "functionName" => "cekPairDataProduk",
//                    "params" => array(
//                        //                        "cabang_id" => ".-1",
//                        //                        "gudang_id" => ".-1",
//                        //                        "state" => ".active",
//                    ),
//                    "kolom" => array(
//                        "no_part",
//                    ),
//                ),
//            ),
//        ),
//        "pairInjectors" => array(
//            1 => array(
//                "hargaLastPurchase" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "harga_last_purchase",
//                    ),
//                ),
//            ),
//        ),
//        "previewCtr" => "Create",
//        "canceledLabel" => array(
//            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
//            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//        ),
//        "ppnCekValidate" => array(
//            "ppnPersenCheck" => "Silahkan menentukan Pajak Vendor (PPN atau NON PPN)",
//        ),
//    ),
//    "967" => array(
//        "icon" => "fa fa-rotate-left",
//        "label" => "FG purchases return ",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "return request",
//                "actionLabel" => "make return request",
//                "source" => "",
//                "target" => "967r",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "prepared by",
//            ),
//            2 => array(
//                "label" => "return authorization",
//                //                "label" => "return pembelian",
//                "actionLabel" => "approve return request",
//                "source" => "967r",
//                "target" => "967",
//                "userGroup" => "c_purchasing_adm",
//                "stateLabel" => "approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//            ),
//        ),
//        "template" => "template/transaksi_pettycash.html",
//
//        //        "selectorModel" => "MdlNotaItem", // relatif by nota atau by produk
//        //        "selectorSrcModel" => "MdlNotaItem", // relatif by nota atau by produk
//
//        "selectorModel" => "{pihakMdlName}", // relatif by nota atau by produk
//        "selectorSrcModel" => "{pihakMdlNameSrc}", // relatif by nota atau by produk
//
//        "selectedPrice" => array(
//            //            "model" => "MdlHargaProduk",
//            //            "label" => "hpp",
//        ),
//        "selectedMainPrice" => array(
//            "barang" => array(
//                "model" => "MdlHargaProdukPerSupplier",
//                "mdlFilter" => array("suppliers_id=pihakID"),
//                "label" => array("hpp"),
//                "key_label" => array(
//                    "hpp" => "harga",
//                ),
//                "mainSrc" => "hpp",
//            ),
//        ),
//
//        "lockerCheck" => array(
//            "enabled" => false,
//            "mdlName" => "MdlLockerStock",
//            "jenis" => "produk",
//            "jenis_locker" => "stock",
//        ),
//        "selectorFilters" => array(
//            "returned=.0",
//            "jenis=.467",
//            "suppliers_id=pihakID",
//        ),
//        "selectorMainFilters" => array(
//            "barang" => array(
//                "suppliers_id=pihakID",
//                "cabang_id=placeID",
//            ),
//            "nota" => array(
//                "returned=.0",
//                "jenis=.467",
//                "suppliers_id=pihakID",
//            ),
//
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nomer",
//        ),
//        "selectorViewedFields" => array(
//            "nomer",
//            "dtime",
//        ),
//        "selectorProcessor" => "_processSelectNotaItem/select",
//
//        "selectorMainProcessor" => array(
//            "nota" => "_processSelectNotaItem/select",
//            "barang" => "_processSelectProduct/select",
//        ),
//        "selectorMainParamFields" => array(
//            "barang" => array(
//                "id" => "produk_id",
//                "nama" => "nama",
//                "produk_kode" => "kode",
//                "satuan" => "satuan",
////                "jumlah" => "jumlah",
//            ),
//            "nota" => array(
//                "id" => "id",
//                "nama" => "nomer",
//            ),
//        ),
//        "selectorMainViewedFields" => array(
//            "barang" => array(
//                "keterangan",
//                "kode",
//                "satuan",
//                //                "jumlah",
//            ),
//            "nota" => array(
//                "nomer",
//                "dtime",
//            ),
//        ),
//
//        "editHandlerMethod" => "edit",
//        "editMainHandlerMethod" => array(
//            "nota" => "edit",
//            "barang" => "select",
//        ),
//
//
//        // ada pilihan metode return pembelian (by nota atau by produk)
//        "pihakModelMain" => "MdlProdukReturnMethodStatic",
//        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
//        "pihakMainLabel" => "metode return pembelian",
//        "pihakMainFilters" => array(),
//        "pihakMainValueSrc2" => array(
//            "pihakMdlName" => "mdl_name",
//            "pihakMdlNameSrc" => "mdl_name_src",
//        ),
//        "pihakMainProcessor" => "_processPihakMain/select",
//        // ada pilihan metode return pembelian (by nota atau by produk)
//
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "vendor",
//        "pihakMainValueSrc" => array(
//            "ppnFactor" => "ppn",
//        ),
//        "pihakProcessor" => "_processPihak/select",
//
//        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "request number",
//            "referenceNomer" => "inv number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "next_pic" => "Next step otorisator",
//        ),
//        "historyFields" => array(
//            1 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer" => "request number",
//                "referenceNomer" => "inv number",
//                "review_details" => "review",
//                "oleh_nama" => "person",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pihakMainName" => "return method",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            2 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "request number",
//                "nomer" => "return number",
//                "referenceNomer" => "inv number",
//                "review_details" => "review",
//                "oleh_nama" => "person",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pihakMainName" => "return method",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//        ),
//        "extHistoryFields" => array(
//            1 => array(
//                "review_details" => "id",
//                "print_label" => "nomer"),
//            2 => array(
//                "review_details" => "id",
//                "print_label" => "nomer"),
//        ),
//        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => array(
//                "produk_nama",
//                "nama",
//            ), // coba di array-kan
//            "code" => "produk_kode", // coba di array-kan
//            "produk_kode" => "kode",
//            "label" => array(
//                "produk_label",
//                "label",
//            ),
//            "satuan" => "satuan",
//
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "item name",
//                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//            ),
//            2 => array(
//                "nama" => "item name",
//                //            "avail" => "current stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga" => "Price",
//                "ppn" => "VAT",
//                //            "avail" => "current stock",
//            ),
//            2 => array(
//                "harga" => "Price",
//                "ppn" => "VAT",
//                //            "avail" => "current stock",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "jml",
//                //                "harga",
//            ),
//            2 => array(
//                "jml",
//            ),
//        ),
//        "shoppingCartMainEditableFields" => array(
//            1 => array(
//                "nota" => array(
//                    "jml",
//                ),
//                "barang" => array(
//                    "jml",
//                    "harga",
//                ),
//            ),
//            2 => array(
//                "nota" => array(
//                    "jml",
//                ),
//                "barang" => array(
//                    "jml",
//                ),
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga+ppn)",
//            2 => "jml*(harga+ppn)",
//        ),
//
//        "shoppingCartFieldValidators" => array(
//            "jml" => "jumlah return pembelian",
//            "harga" => "harga beli",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//        ),
//
//        "availPayments" => array(
//
//            "inherit" => array(
//                "label" => "inherit",
//                "valueGate" => "nilai_inherit",
//                "valueSrc" => "nett",
//            ),
//        ),
//        "referenceFields" => array(
//            "referenceID" => "transaksi_id",
//            "referenceJenis" => "jenis",
//            "referenceNomer" => "nomer",
//            "paymentMethod" => "pembayaran",
//        ),
//        "pairMakers" => array(
//            1 => array(
//                "stokProduk" => array(
//                    //                    "helperName" => "he_cek_stock_produk",
//                    //                    "functionName" => "cekStockProduk",
//                    //                    "params" => array(
//                    //                        "cabang_id" => "placeID",
//                    //                        "gudang_id" => "gudangID",
//                    //                    ),
//                    "helperName" => "he_cek_stock_produk_locker",
//                    "functionName" => "cekStockProdukLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "state" => ".active",
//                    ),
//                    "target" => array("items"),
//                ),
//            ),
//        ),
//        "pairInjectors" => array(
//            1 => array(
//                "stokProduk" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//            ),
//        ),
//        "receiptElements" => array(
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "vendor details",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "name",
//                    "npwp" => "tax-ID",
//                    "alamat_1" => "address",
//                    "tlp_1" => "phone",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//
//
//        ),
//        "pairRegistries" => array(
//            "tableIn_master_values", "main", "items"
//        ),
//        "shoppingCartSessionDeleter" => array(
//            "items" => array(),
//            "tableIn_detail_values" => array(),
//            "tableIn_detail" => array(),
//            "main" => array(
//                "seluruhnya",
//                "referenceID",
//                "referenceJenis",
//                "referenceNomer",
//            ),
//        ),
//        "previewCtr" => "Create",
//    ),
//
//    "1967" => array(
//        "icon" => "fa fa-rotate-left",
//        "label" => "close/fullfillment Purchasing Order",
//        "place" => "center",//=> "center",
//        "steps" => array(
//            1 => array(
//                "label" => "request close/fullfillment purchasing order",
//                "actionLabel" => "make close/fullfillment transaksi",
//                "source" => "",
//                "target" => "1967",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "request close/fullfillment transaksi",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "prepare by",
//                "isCancelPacking" => true,
//            ),
//            2 => array(
//                "label" => "otorisasi close/fullfillment purchasing order",
//                "actionLabel" => "approve close/fullfillment transaksi",
//                "source" => "1967",
//                "target" => "1967a",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "purchasing canceled",
//                "stateColor" => "#ff7700",
//                "isCancelPacking" => true,
//            ),
//        ),
//        "template" => "template/transaksi_fullfill.html",
//        "isDisableMakeTrans" => true,
//        "selectorModel" => "MdlNotaItem",
//        "selectorSrcModel" => "MdlNotaItem",
//        "selectedPrice" => array(
//            //            "model" => "MdlHargaProduk",
//            //            "label" => "hpp",
//        ),
//        "lockerCheck" => array(
//            "enabled" => false,
//            "mdlName" => "MdlLockerStock",
//            "jenis" => "produk",
//            "jenis_locker" => "stock",
//        ),
//        "selectorFilters" => array(
//            "returned=.0",
//            "jenis=.467",
//            "suppliers_id=pihakID",
//            "tail_number=.3",
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nomer",
//        ),
//        "selectorViewedFields" => array(
//            "nomer",
//            "dtime",
//        ),
//        "selectorProcessor" => "_processSelectNotaItem/select",
//        "editHandlerMethod" => "cancel",
//        "pihakModel" => "MdlCustomer_and_pre",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "customer",
//        "pihakMainValueSrc" => array(
//            "ppnFactor" => "ppn",
//        ),
//        "pihakProcessor" => "_processPihak/select",
//        "shortHistoryFields" => array(
//            //            "jenis_label"    => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "request number",
//            "transaksiDatas__nomer" => "PO Number",
//            "oleh_nama" => "person",
//            "next_pic" => "Next step otorisator",
//        ),
//        "compactHistoryFields" => array(
//            //            "jenis_label"    => "activity",
//            "dtime" => "date",
//            //            "suppliers_nama" => "customer",
//            //            "nomer"          => "receipt number",
//            "oleh_nama" => "person",
//            //            "harga"          => "amount",
//            //            "disc"           => "discount",
//            //            "ppn"            => "ppn",
//            //            "nett2" => "total amount",
//        ),
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "jual",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "produk_nama",
//            "produk_kode" => "produk_kode",
//            "label" => "produk_label",
//            "satuan" => "satuan",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Description",
//                "produk_kode" => "Product No.",
//                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut PO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>PO</span>",
//                //                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
//                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH GRN</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>GRN</span>",
//                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
//                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
//                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
//                "satuan" => "uom",
//                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
//            ),
//            2 => array(
//                "nama" => "Description",
//                "produk_kode" => "Product No.",
//                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut PO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>PO</span>",
//                //                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
//                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH GRN</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>GRN</span>",
//                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
//                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
//                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
//                "satuan" => "uom",
//                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                //                "harga"        => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "stok" => "stok",
//                //                "disc"         => "disc (IDR)",
//                //                "ppn"          => "VAT",
//                //            "avail" => "current stock",
//            ),
//            2 => array(
//                //                "harga"        => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc"         => "disc (IDR)",
//                //                "ppn"          => "VAT",
//                //            "avail" => "current stock",
//            ),
//            3 => array(
//                //                "harga"        => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc"         => "disc (IDR)",
//                //                "ppn"          => "VAT",
//                //            "avail" => "current stock",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "jml",
//            ),
//            2 => array(
//                "jml",
//            ),
//            3 => array(
//                "jml",
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga)",
//            2 => "jml*(harga)",
//            //            3 => "jml*(harga+ppn)",
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                //                "harga" => "Amount",
//                //                "disc"  => "Disc",
//                //                "ppn"   => "VAT",
//                //                "nett2" => "Grand Total",
//            ),
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => false,
//            2 => false,
//        ),
//        "shoppingCartFieldValidators" => array(
//            //            "jml"   => "quantity",
//            //            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "supplier ID",
//            "pihakName" => "supplier name",
//        ),
//        "applets" => array(),
//        "availPayments" => array(
//            //            "cash"   => array(
//            //                "label"     => "cash",
//            //                "valueGate" => "nilai_cash",
//            //                "valueSrc"  => "nett",
//            //            ),
//            //            "credit" => array(
//            //                "label"     => "credit",
//            //                "valueGate" => "nilai_credit",
//            //                "valueSrc"  => "nett",
//            //            ),
//            "inherit" => array(
//                "label" => "inherit",
//                "valueGate" => "nilai_inherit",
//                "valueSrc" => "grand_total",
//            ),
//        ),
//        "referenceFields" => array(
//            "referenceID" => "transaksi_id",
//            "referenceJenis" => "jenis",
//            "referenceNomer" => "nomer",
//            "paymentMethod" => "pembayaran",
//        ),
//        "referenceJenisTr" => "466",
//        "itemAddConfig" => false,
//        "receiptElements" => array(
//            "transaksiDatas" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Purchasing Order",
//                "mdlName" => "MdlTransaksiData",
//                "mdlFilter" => array(
//                    "id=currentID",
//                ),
//                "key" => "id",
//                "labelSrc" => "nomer",
//                "usedFields" => array(
//                    "nomer" => "Nomer",
//                    "oleh_nama" => "BY",
//                ),
//                "editPoints" => array(1),
//            ),
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
//            "reasonDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "REASON DETAILS",
//                "mdlName" => "MdlAlasanBatal",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "nilai",
//                "usedFields" => array(
//                    "nilai" => "Alasan",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),
//        ),
//        "resumeFieldNames" => array(
//            "selectFields" => "suppliers_nama",
//            "title" => "vendor",
//        ),
//        "pairRegistries" => array(
//            "tableIn_master_values", "main", "items"
//        ),
//
//        // ======== =========
//        "xShipmentConfig" => array(
//            2 => array(
//                "enabled" => false,
//                "label" => "close/fullfillment purchasing order",
//                "targetJenisMaster" => "1967",
//                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
//
//                "shipment" => "467", // ini jadi GRN
//                "packing" => "none", //
//                "cancel" => "1967",
//            ),
//        ),
//        "referenceRevert" => array(
//            "enabled" => true,
//            "referenceID" => "transaksiDatas", // ambil dari gerbang registry main
//            // untuk mengembalikan valid_qty, jadi valid_qty + cancel_qty (sesuai nota)
//            "items" => array(
//                "valid_qty" => "valid_qty",
//            ),
//        ),
//        "previewCtr" => "Create",
//    ),
//
//    "461" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "supplies purchasing",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "PURCHASING PRE ORDER",
//                "actionLabel" => "make purchasing order",
//                "source" => "",
//                "target" => "461ro",
//                "userGroup" => "c_purchasing", // admin
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Prepared by",
//            ),
//            2 => array(
//                "label" => "PURCHASING ORDER",
//                "actionLabel" => "approve purchasing",
//                "source" => "461ro",
//                "target" => "461r",
//                "userGroup" => "c_purchasing_adm",
//                "stateLabel" => "purchased",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "Approval by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
//            3 => array(
//                "label" => "GOODS RECEIVE NOTE",
//                //                "actionLabel" => "receive & make GRN",
//                "actionLabel" => "undo/reject/GRN",
//                "buttonLabel" => "receive & make GRN",
//                "source" => "461r",
//                "target" => "461",
//                "userGroup" => "c_gudang",
//                "stateLabel" => "GRN made",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//                "allowEdit" => true,
//            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "buttonLabel" => "approve",
//                "source" => "461",
//                "target" => "112",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//                "allowEdit" => false,
//            ),
//        ),
//        "template" => "template/transaksi.html",
//        "selectorModel" => "MdlSupplies",
//        "selectorSrcModel" => "MdlSupplies",
//        "selectedPrice" => array(
//            "model" => "MdlHargaSupplies",
//            "label" => array("hpp"),
//            "key_label" => array(
//                "hpp" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(),
//        "selectorFilters" => array(),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//            //            "jumlah"=>"jumlah",
//        ),
//        "selectorViewedFields" => array(
//            "nama",
//            "satuan",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",
//        "itemSwapper" => "_processSelectProduct/multiSelect",
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "vendor",
//        "pihakMainValueSrc" => array(
//            "ppnFactor" => "ppn",
//        ),
//        "pihakProcessor" => "_processPihak/select",
//
//        "shortHistoryFields" => array(
//            //            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "references_num" => "purchasing request",
//            "nomer_top" => "PRE-PO number",
//            // sumber dari kolom id_his
//            "nomer_po" => array(
//                "step" => 2,
//                "key" => "nomer",
//                "label" => "PO number",
//            ),
//            "nomer_grn" => array(
//                "step" => 3,
//                "key" => "nomer",
//                "label" => "GRN number",
//            ),
//            "nomer_ppn" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
//            //            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
////            "ppnPersenCheck" => "*",
//            "next_pic" => "Next step otorisator",
//            "keterangan" => "keterangan",
//        ),
//        "shortStatusFields" => array(
//            //            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "status_next" => "status",
//            "suppliers_nama" => "vendor",
//            //            "customers_nama" => "customer",
//            "references_num" => "purchasing request",
//            "nomer_top" => "PO number",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            //            "trash_4" => "trash 4",
//            //            "id" => "ID",
//        ),
//        "historyFields" => array(
//            1 => array(
//                "no" => "no",
//                //                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "references_num" => "purchasing request",
//                "nomer_top" => "PRE PO number",
//                "oleh_nama" => "person",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            2 => array(
//                "no" => "no",
//                //                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "references_num" => "purchasing request",
//                "nomer_top" => "PRE PO number",
//                "nomer" => "PO number",
//                "oleh_nama" => "person",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            3 => array(
//                "no" => "no",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "references_num" => "purchasing request",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer" => "Receipt number",
//
//                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
//                "oleh_nama" => "person",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            4 => array(
//                "no" => "no",
//                //                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "references_num" => "purchasing request",
//                "nomer_top" => "PRE PO number",
//                "nomer_po" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer_grn" => array(
//                    "step" => 3,
//                    "key" => "nomer",
//                    "label" => "GRN number",
//                ),
//                "nomer" => "Realisasi PPn Number",
//                "description_main_followup" => "INV<br>from vendor",
//                "oleh_nama" => "person",
//                "eFaktur" => "e-faktur",
//                "ppn" => "ppn",
//                //                "transaksi_nilai" => "amount",
//                //                "harga" => "amount",
//                //                "disc" => "discount",
//                //                "ppn" => "ppn",
//                //                "nett" => "total amount",
//
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//        ),
//        "extHistoryFields" => array(
//            1 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            2 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            3 => array(
//                "print_label" => "nomer",
//            ),
//            4 => array(
//                "print_label" => "nomer",
//            ),
//            5 => array(
//                "print_label" => "nomer",
//            ),
//        ),
//        "compactHistoryFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "code" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            //"berat"         => "berat",
//            //          "lebar"         => "lebar",
//            //        "panjang"       => "panjang",
//            //      "tinggi"        => "tinggi",
//            //    "volume"        => "volume",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            2 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            3 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//            4 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga" => "Unit<br>Price",
//                "discPersen" => "DISC<br>(%)",
//                "disc" => "DISC<br>(Rp)",
//                "ppnPersen" => "VAT<br>(%)",
////                "ppnFactor" => "VAT<br>(%)",
//                "ppn" => "VAT<br>(Rp)",
//            ),
//            2 => array(
//                "harga" => "Unit<br>Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "ppnPersen" => "VAT(%)",
//                "ppn" => "VAT(Rp)",
//            ),
//            3 => array(
//                "harga" => "Unit<br>Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "ppnPersen" => "VAT(%)",
//                "ppn" => "VAT(Rp)",
//            ),
//            4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "ppnPersen" => "VAT(%)",
//                "ppn" => "VAT(Rp)",
//            ),
//        ),
//        "shoppingCartNoteEnabled" => true,
//
//        "shoppingCartSubamount2" => array(1 => true,),
//
//        "shoppingCartNoteType" => "textarea",
//        "shoppingCartNoteEditabled" => array(
//            2 => true,
//            3 => true,
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "jml",
//                "ppnPersen",
//                "discPersen",
//            ),
//            2 => array(
//                "harga",
//                "jml",
//                "ppnPersen",
//                "discPersen",
//            ),
//            3 => array(
//                "jml",
//            ),
//        ),
//        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga-disc+ppn)",
//            2 => "jml*(harga-disc+ppn)",
//            3 => "jml*(harga-disc+ppn)",
//            4 => "jml*(harga-disc+ppn)",
//        ),
//        "shoppingCartHeaderElement" => array(
//            1 => array(
//                "ppn" => array(
//                    "label" => "TENTUKAN PPN",
//                    "class" => "col-xs-6 no-padding",
//                    "subElements" => array(
//                        "non_ppn" => array(
//                            "label" => "Non PPN",
//                            "srcMain" => "ppnPersenCheck",
////                            "overWriteMain" => "ppnVendor",
////                            "srcItem" => "ppnPersen",
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
////                            "overWriteMain" => "ppnVendor",
////                            "srcItem" => "ppnPersen",
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
//        "pairRegistries" => array(
//            "tableIn_master_values", "main", "items"
//        ),
//        "receiptElements" => array(
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "VENDOR",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "",
//                    "alamat_1" => "",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    //                    "alias" => "Attn",
//                    "contact_person" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlSupplierAddress",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "extern_name" => "",
//                    "alamat" => "",
//                    "tlp" => "Phone",
//                    "alias" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "shippingDate" => array(
//                "elementType" => "dataField",
//                "label" => "shipping date",
//                "inputType" => "date",
//                "defaultValue" => date("Y-m-d"),
//                "editPoints" => array(1, 2, 3, 4, 5),
//            ),
//            //            "paymentMethod" => array(
//            //                "elementType" => "dataModel",
//            //                "inputType" => "radio",
//            //                "label" => "payment method",
//            ////                "mdlName" => "MdlPaymentMethod",
//            //                "mdlName" => "MdlPaymentMethodCredit",
//            //                //                "mdlFilter"   => array("extern_id=pihakID"),
//            //                "key" => "id",
//            //                "labelSrc" => "name",
//            //                "usedFields" => array(
//            //                    "name" => "method",
//            //                ),
//            //                "editPoints" => array(1,),
//            //            ),
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Payment Method",
//                "mdlName" => "MdlPaymentMethod1",
//                //                "mdlName" => "MdlPaymentMethodCredit",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "defaultValue" => "credit",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//            // ====akan tampil di UI ===== //
//            "ppv_index" => array(
//                "elementType" => "dataModel",
//                "inputType" => "hidden",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
//                    "kode=.lokal",
//                    "jenis2=.supplies",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
//        ),
//        "relativeElements" => array(
//            "paymentMethod" => array(
//                "cash" => array(
//                    "cash_account" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "cash account",
//                        "mdlName" => "MdlBankAccount",
//
//                        "key" => "id",
//                        "labelSrc" => "nama",
//                        "usedFields" => array(
//                            "nama" => "top",
//
//
//                        ),
//                        "editPoints" => array(1,),
//                    ),
//                ),
//                "credit" => array(
//                    "top" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "term of payment",
//                        "mdlName" => "MdlTop",
//                        "mdlFilter" => array(),
//                        "key" => "kode",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "top",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                    ),
//                ),
//            ),
//        ),
//        "relativeOptions" => array(
//            "paymentMethod" => array(
//
//                "cia" => array(
//                    "nilai_cia" => array(
//                        "label" => "cash amount",
//                        "defaultValue" => "nett",
//                        "minValue" => "nett",
//                        "maxValue" => "nett",
//                    ),
//
//                ),
//            ),
//        ),
//        //---------------------------
//        "receiptElementsInjector" => array(
//            "ppv_index" => array(
//                "elementType" => "dataModel",
//                "inputType" => "hidden",
//                "label" => "index",
//                "mdlName" => "MdlPpv",
//                "mdlFilter" => array(
//                    "kode=.lokal",
//                    "jenis2=.supplies",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nilai" => "index",
//                ),
//                "editPoints" => array(1,),
//            ),
//        ),
//        //---------------------------
//        "resumeFieldNames" => array(
//            "selectFields" => "suppliers_nama",
//            "title" => "vendor",
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "suppliers_nama" => "vendor",
//            "jenis_label" => "activity",
//            "transaksi_nilai" => "orig. value",
//            "add_disc" => "discount",
//            "grand_total" => "nett",
//        ),
//        "validatePaymentSource" => array(
//            "3" => "MdlLockerValue",
//        ),
//        "addMainStep" => array(
//            "487" => array(
//                "jenis_master" => "461",
//                "jenis" => "461",
//                "target" => "487",
//                "status_4" => "1",
//                "trash_4" => "0",
//            ),
//        ),
//        "allowedMainEdit" => array("1"),
//        "aliasMainTrans" => "1763",
//        "swappedKeySrc" => array("pihakID", "pihakName"),
//        "comPrePurchase" => array(
//            1 => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "AutoPurchaseSupplies",
//                        "loop" => array(
//                            "produk_id" => "jml",
//                        ),
//                        "static" => array(),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//            ),
//            // 2 => array(
//            //     "master" => array(),
//            //     "detail" => array(
//            //         array(
//            //             "comName" => "AutoPurchaseSupplies",
//            //             "loop" => array(
//            //                 "produk_id" => "jml",
//            //             ),
//            //             "static" => array(),
//            //             "srcGateName" => "items",
//            //             "srcRawGateName" => "items",
//            //         ),
//            //     ),
//            // ),
//        ),
//        "tabHistoryFields" => array(
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => false,
//            ),
//            "transaksi_id" => array(
//                "label" => "By Transaksi",
//                "allowFollowup" => true,
//            ),
//        ),
//
//        "tabRequestCode" => array(
//            "masterCode" => "1763",
//            "stateCode" => "1763r",
//            "stepNumber" => "1",
//            "allowMultiSelect" => false,
//        ),
//        "ppnDisabled" => array(
//            "enabled" => true,
//            "notes" => "PPN masukan belum diapprove oleh Finance.",
//        ),
//        "addMainSource" => array(
//            4 => array(
//                "fields" => array(
//                    "nomer" => "INV",
//                    "harga" => "DPP",
//                    "ppn" => "PPN (belum ada faktur)",
//                    "ppn_realisasi" => "PPN Realisasi",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "e-faktur",
//                ),
//                "editableFields" => array(
//                    "harga" => "number",
//                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
//                ),
//            ),
//        ),
//        "receiptEdit" => array(
//            4 => true,
//        ),
//        // berada di midValidate() Transaksi
//        "efakturValidator" => array(
//            4 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
//                    "eFaktur" => "nomer e-faktur belum diisikan.",
//                ),
//                "source" => array(
//                    "ppn", // lebih dari 0
//                    //                "ppnfactor",
//                ),
//            ),
//        ),
//        // ======== =========
//        "xShipmentConfig" => array(
//            1 => array(
//                "enabled" => true,
//                "label" => "close/fullfillment purchasing order",
//                "targetJenisMaster" => "1961",
//                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
//                "allowedGroups" => array(
//                    "c_holding",
//                    "c_purchasing",
//                    "c_purchasing_spv",
//                    //                    "c_gudang",
//                    //                    "c_gudang_spv",
//                    //                    "c_finance"
//                ),
//            ),
//            2 => array(
//                "enabled" => true,
//                "label" => "close/fullfillment purchasing order",
//                "targetJenisMaster" => "1961",
//                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
//                "allowedGroups" => array(
//                    "c_holding",
//                    "c_purchasing",
//                    "c_purchasing_spv",
//                    //                    "c_gudang",
//                    //                    "c_gudang_spv",
//                    //                    "c_finance"
//                ),
//            ),
//        ),
//        "followupMainNoteValidator" => array(
//            3 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
//                ),
//                "source" => array(
//                    "description_main_followup",
//                ),
//            ),
//        ),
//
//        "followupMainNote" => array(
//            3 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => true,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//            4 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//
//        ),
//        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
//        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
//        "previewCtr" => "Create",
//        "ppnCekValidate" => array(
//            "ppnPersenCheck" => "Silahkan menentukan Pajak Vendor (PPN atau NON PPN)",
//        ),
//    ),
//
//    "1763" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "# supplies request(dont check auto by system)",
//        "place" => "center",
//        "hideMenu" => true,
//        "steps" => array(
//            1 => array(
//                "label" => "new supplies pre purchase request",
//                "actionLabel" => "new supplies request",
//                "source" => "",
//                "target" => "1763r",
//                "userGroup" => "sys",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "prepared by",
//            ),
//            2 => array(
//                //                "label" => "supplies distribution",
//                "label" => "authorization",
//                "actionLabel" => "approve supplies pre purchase request",
//                "source" => "1763r",
//                "target" => "1763",
//                "userGroup" => "sys",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "approved by",
//                "allowEdit" => true,
//                "allowFollowup" => false,
//            ),
//        ),
//        "template" => "template/transaksi.html",
//        "selectorModel" => "MdlLockerStockSupplies",
//        "selectorSrcModel" => "MdlSupplies",
//        "selectedPrice" => array(
//            "model" => "MdlHargaSupplies",
//            "label" => array("jual"),
//            "key_label" => array(
//                "jual" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockSupplies",
//        ),
//        "lockerCheckAppr" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockSupplies",
//            "jenis" => "supplies",
//            "jenis_locker" => "stock",
//        ),
//        "qtips" => array(
//            "nama" => "Product",
//            "stockNeed" => "request",
//            "stock" => "avail",
//            "valid_qty" => "purch",
//        ),
//        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "gudang_id=gudangID",
//            "jumlah>.0",
//            "state=.active",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "produk_id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//            "jumlah" => "jumlah",
//            "produk_kode" => "kode",
//        ),
//        "selectorViewedFields" => array(
//            "keterangan",
//            "kode",
//            "satuan",
//            "jumlah",
//        ),
//        "swappedKeys" => array("pihakID", "pihakName"),
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlCabang",
//        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
//        "pihakLabel" => "cabang",
//        "pihakFilters" => array(
//            "id<>cabang_id",
//        ),
//        "pihakProcessor" => "Selectors/_processPihak/select",
//        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "cabang2_nama" => "recipient",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "cabang2_nama" => "recipient",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "product name",
//                "produk_kode" => "product code",
//                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//                //            "harga" => "harga",
//            ),
//            2 => array(
//                "nama" => "product name",
//                "produk_kode" => "product code",
//                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//                //            "harga" => "harga",
//            ),
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            "stok" => "stock",
//            //"berat"         => "berat",
//            //          "lebar"         => "lebar",
//            //        "panjang"       => "panjang",
//            //      "tinggi"        => "tinggi",
//            //    "volume"        => "volume",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                //                "hpp" => "hpp",
//                //            "harga" => "price",
//            ),
//            2 => array(
//                //                "hpp" => "hpp",
//                //            "harga" => "price",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                //            "harga",
//                //            "ppn",
//                "jml",
//            ),
//            2 => array(
//                //            "harga",
//                //            "ppn",
//                "jml",
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*hpp",
//            2 => "jml*hpp",
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => true,
//            2 => true,
//        ),
//
//        "pairChild" => array(
//            "461"
//        ),
//
//        "receiptElements" => array(
//            "gudang" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "target warehouse",
//                "mdlName" => "MdlGudangDefault",
//                "mdlFilter" => array("cabang_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//        ),
//
//        "resumeFieldNames" => array(
//            "selectFields" => "cabang2_nama",
//            "title" => "brach ",
//        ),
//        "pairMakers" => array(
//            2 => array(
//                "stokSupplies" => array(
//                    "helperName" => "he_cek_stock_supplies_locker",
//                    "functionName" => "cekStockSuppliesLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "state" => ".active",
//                    ),
//                ),
//            ),
//        ),
//        "pairInjectors" => array(
//            2 => array(
//                "stokSupplies" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//            ),
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "cabang_nama" => "sender",
//            "cabang2_nama" => "recipient",
//            //            "suppliers_nama" =>"vendor",
//            "jenis_label" => "activity",
//            //            "transaksi_nilai" => "orig. value",
//            //            "add_disc"        => "discount",
//            //            "grand_total"     => "nett",
//        ),
//        "validationRules" => array(
//            "items" => array(
//                "target" => "stok",
//                "source" => "jml",
//            ),
//        ),
//        "glanceHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt",
//            "cabang2_nama" => "branch",
//            "oleh_nama" => "person",
//        ),
//        "allowedMainEdit" => array("1"),
//        "tabHistoryFields" => array(
//            "transaksi_id" => array(
//                "label" => "By Transaksi",
//                "allowFollowup" => false,
//            ),
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => true,
//            ),
//        ),
//        "tabFieldsItems" => array(
//
//            "transaksi_id" => array(
//                "select" => "tick",
//                "dtime" => "tanggal",
//                "nomer" => "PRE PO Number",
//                "nomer_top" => "Supplies Request No",
//                "arrProduk" => "Produk",
//                "cabang2_nama" => "Cabang",
//                "oleh_nama" => "PIC",
//                "action" => "Action",
//            ),
//            "produk_id" => array(
//                //                "select" => "All",
//                "dtime" => "tanggal",
//                "produk_nama" => "Produk Nama",
//                "nomer_top" => "Transaksi No",
//                "produk_ord_jml" => "PRE PO Jml",
//                //                "purchased" => "On Purchase",
//                //                "valid_qty" => "Outstanding",
//
//            ),
//        ),
//        "itemSwapper" => "_processSelectProduct/multiSelect",
//        // ======== =========
//        "xShipmentConfig" => array(
//            1 => array(
//                "enabled" => true,
//                "label" => "close/fullfillment auto pre purchase request",
//                "targetJenisMaster" => "11763",
//                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
//                "allowedGroups" => array(
//                    "c_holding",
//                    "c_gudang",
//                    "c_gudang_spv",
//                    "c_finance",
//                    "c_purchasing",
//                    "c_purchasing_adm",
//                    "c_purchasing_spv",
//                ),
//            ),
//        ),
//        "previewCtr" => "Create",
//    ),
//    "11763" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "# close/fullfillment supplies request(dont check auto by system)",
//        "place" => "center",
//        "hideMenu" => true,
//        "steps" => array(
//            1 => array(
//                "label" => "close/fullfillment supplies pre purchase request",
//                "actionLabel" => "close/fullfillment supplies request",
//                "source" => "",
//                "target" => "11763",
//                "userGroup" => "_c_gudang_spv",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "prepared by",
//                "isCancelPacking" => true,
//            ),
//            //            2 => array(
//            //                //                "label" => "supplies distribution",
//            //                "label" => "authorization",
//            //                "actionLabel" => "approve supplies pre purchase request",
//            //                "source" => "1763r",
//            //                "target" => "1763",
//            //                "userGroup" => "c_gudang_spv",
//            //                "stateLabel" => "approved",
//            //                "stateColor" => "#009900",
//            //                "stateCaption" => "approved by",
//            //                "allowEdit" => true,
//            //                "allowFollowup" => false,
//            //            ),
//        ),
//        "template" => "template/transaksi_fullfill.html",
//        "isDisableMakeTrans" => true,
//        "selectorModel" => "MdlLockerStockSupplies",
//        "selectorSrcModel" => "MdlSupplies",
//        "selectedPrice" => array(
//            "model" => "MdlHargaSupplies",
//            "label" => array("jual"),
//            "key_label" => array(
//                "jual" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockSupplies",
//        ),
//        "lockerCheckAppr" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockSupplies",
//            "jenis" => "supplies",
//            "jenis_locker" => "stock",
//        ),
//        "qtips" => array(
//            "nama" => "Product",
//            "stockNeed" => "request",
//            "stock" => "avail",
//            "valid_qty" => "purch",
//        ),
//        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "gudang_id=gudangID",
//            "jumlah>.0",
//            "state=.active",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "produk_id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//            "jumlah" => "jumlah",
//            "produk_kode" => "kode",
//        ),
//        "selectorViewedFields" => array(
//            "keterangan",
//            "kode",
//            "satuan",
//            "jumlah",
//        ),
//        "swappedKeys" => array("pihakID", "pihakName"),
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlCabang",
//        "pihakCaller" => "Selectors/_selectorPihak/selectPihak",
//        "pihakLabel" => "cabang",
//        "pihakFilters" => array(
//            "id<>cabang_id",
//        ),
//        "pihakProcessor" => "Selectors/_processPihak/select",
//        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "cabang2_nama" => "recipient",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "cabang2_nama" => "recipient",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "product name",
//                "produk_kode" => "product code",
//                //                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//                //            "harga" => "harga",
//            ),
//            2 => array(
//                "nama" => "product name",
//                "produk_kode" => "product code",
//                //                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "uom",
//                //            "harga" => "harga",
//            ),
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "produk_kode" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            "stok" => "stock",
//            //"berat"         => "berat",
//            //          "lebar"         => "lebar",
//            //        "panjang"       => "panjang",
//            //      "tinggi"        => "tinggi",
//            //    "volume"        => "volume",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                //                "hpp" => "hpp",
//                //            "harga" => "price",
//            ),
//            2 => array(
//                //                "hpp" => "hpp",
//                //            "harga" => "price",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                //            "harga",
//                //            "ppn",
//                "jml",
//            ),
//            2 => array(
//                //            "harga",
//                //            "ppn",
//                "jml",
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*hpp",
//            2 => "jml*hpp",
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => true,
//            2 => true,
//        ),
//
//        //        "pairChild" => array(
//        //            "461"
//        //        ),
//
//        "receiptElements" => array(
//            //            "gudang" => array(
//            //                "elementType" => "dataModel",
//            //                "inputType" => "radio",
//            //                "label" => "target warehouse",
//            //                "mdlName" => "MdlGudangDefault",
//            //                "mdlFilter" => array("cabang_id=.-1"),
//            //                "key" => "id",
//            //                "labelSrc" => "name",
//            //                "usedFields" => array(
//            //                    "name" => "",
//            //                ),
//            //                "editPoints" => array(1, 2, 3),
//            //            ),
//        ),
//
//        "resumeFieldNames" => array(
//            "selectFields" => "cabang2_nama",
//            "title" => "brach ",
//        ),
//        "pairMakers" => array(
//            2 => array(
//                "stokSupplies" => array(
//                    "helperName" => "he_cek_stock_supplies_locker",
//                    "functionName" => "cekStockSuppliesLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "state" => ".active",
//                    ),
//                ),
//            ),
//        ),
//        "pairInjectors" => array(
//            2 => array(
//                "stokSupplies" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//            ),
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "cabang_nama" => "sender",
//            "cabang2_nama" => "recipient",
//            //            "suppliers_nama" =>"vendor",
//            "jenis_label" => "activity",
//            //            "transaksi_nilai" => "orig. value",
//            //            "add_disc"        => "discount",
//            //            "grand_total"     => "nett",
//        ),
//        "validationRules" => array(
//            "items" => array(
//                "target" => "stok",
//                "source" => "jml",
//            ),
//        ),
//        "glanceHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt",
//            "cabang2_nama" => "branch",
//            "oleh_nama" => "person",
//        ),
//        "allowedMainEdit" => array("1"),
//        "tabHistoryFields" => array(
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => true,
//            ),
//            "transaksi_id" => array(
//                "label" => "By Transaksi",
//                "allowFollowup" => false,
//            ),
//        ),
//        "tabFieldsItems" => array(
//            "produk_id" => array(
//                "select" => "All",
//                "dtime" => "tanggal",
//                "produk_nama" => "Produk Nama",
//                "nomer_top" => "Transaksi No",
//                "produk_ord_jml" => "PRE PO Jml",
//                //                "purchased" => "On Purchase",
//                //                "valid_qty" => "Outstanding",
//
//            ),
//            "transaksi_id" => array(
//                //                "select" => "tic",
//                "dtime" => "tanggal",
//                "nomer" => "PRE PO Number",
//                "nomer_top" => "Supplies Request No",
//                "arrProduk" => "Produk",
//                "cabang2_nama" => "Cabang",
//                "oleh_nama" => "PIC",
//                "action" => "Action",
//            ),
//        ),
//        "itemSwapper" => "_processSelectProduct/multiSelect",
//        "previewCtr" => "Create",
//    ),
//
//    //  config return pembelian supplies
//    "961" => array(
//        "icon" => "fa fa-rotate-left",
//        "label" => "supplies purchases return ",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "return request",
//                "actionLabel" => "make return request",
//                "source" => "",
//                "target" => "961r",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "prepared by",
//            ),
//            2 => array(
//                "label" => "return authorization",
//                //                "label" => "return pembelian",
//                "actionLabel" => "approve return request",
//                "source" => "961r",
//                "target" => "961",
//                "userGroup" => "c_purchasing_adm",
//                "stateLabel" => "approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "approved by",
//            ),
//        ),
//        "template" => "template/transaksi_pettycash.html",
//        "selectorModel" => "{pihakMdlName}", // relatif by nota atau by produk
//        "selectorSrcModel" => "{pihakMdlNameSrc}", // relatif by nota atau by produk
//
//        "selectedPrice" => array(
//            //            "model" => "MdlHargaProduk",
//            //            "label" => "hpp",
//        ),
//        "selectedMainPrice" => array(
//            "barang" => array(
//                "model" => "MdlHargaSupplies",
//                "mdlFilter" => array(//                    "suppliers_id=pihakID",
//                ),
//                "label" => array("hpp"),
//                "key_label" => array(
//                    "hpp" => "harga",
//                ),
//                "mainSrc" => "hpp",
//            ),
//        ),
//
//        "lockerCheck" => array(
//            "enabled" => false,
//            "mdlName" => "MdlLockerStockSupplies",
//            "jenis" => "supplies",
//            "jenis_locker" => "stock",
//        ),
//        "selectorFilters" => array(
//            "returned=.0",
//            "jenis=.461",
//            "suppliers_id=pihakID",
//        ),
//        "selectorMainFilters" => array(
//            "barang" => array(
//                "cabang_id=placeID",
//                "gudang_id=gudangID",
//                "jumlah>.0",
//                "state=.active",
//            ),
//            "nota" => array(
//                "returned=.0",
//                "jenis=.461",
//                "suppliers_id=pihakID",
//            ),
//
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//
//        "selectorParamFields" => array(
//            "id" => "id",
//            //            "nama" => "nomer",
//            //            "satuan" => "satuan",
//            //            "jumlah"=>"jumlah",
//        ),
//        "selectorViewedFields" => array(
//            "nomer",
//            "dtime",
//        ),
//        "selectorProcessor" => "_processSelectNotaItem/select",
//
//        "selectorMainProcessor" => array(
//            "nota" => "_processSelectNotaItem/select",
//            "barang" => "_processSelectProduct/select",
//        ),
//        "selectorMainParamFields" => array(
//            "barang" => array(
//                "id" => "produk_id",
//                "nama" => "nama",
//                //                "produk_kode" => "kode",
//                "satuan" => "satuan",
//                //                "jumlah" => "jumlah",
//            ),
//            "nota" => array(
//                "id" => "id",
//                "nama" => "nomer",
//            ),
//        ),
//        "selectorMainViewedFields" => array(
//            "barang" => array(
//                "nama",
//                //                "kode",
//                "satuan",
//                //                "jumlah",
//            ),
//            "nota" => array(
//                "nomer",
//                "dtime",
//            ),
//        ),
//
//        "editHandlerMethod" => "edit",
//        "editMainHandlerMethod" => array(
//            "nota" => "edit",
//            "barang" => "select",
//        ),
//
//        // ada pilihan metode return pembelian (by nota atau by produk)
//        "pihakModelMain" => "MdlSuppliesReturnMethodStatic",
//        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
//        "pihakMainLabel" => "metode return pembelian",
//        "pihakMainFilters" => array(),
//        "pihakMainValueSrc2" => array(
//            "pihakMdlName" => "mdl_name",
//            "pihakMdlNameSrc" => "mdl_name_src",
//        ),
//        "pihakMainProcessor" => "_processPihakMain/select",
//        // ada pilihan metode return pembelian (by nota atau by produk)
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "vendor",
//        "pihakMainValueSrc" => array(
//            "ppnFactor" => "ppn",
//        ),
//        "pihakProcessor" => "_processPihak/select",
//
//        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "next_pic" => "Next step otorisator",
//        ),
//        "compactHistoryFields" => array(
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//        ),
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => array(
//                "produk_nama",
//                "nama",
//            ), // coba di array-kan
//            "code" => "produk_kode",
//            "label" => "produk_label",
//            "satuan" => "satuan",
//
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "item name",
//                "stok" => "stock",
//                "jml" => "qty",
//                "satuan" => "satuan",
//            ),
//            2 => array(
//                "nama" => "item name",
//                //            "avail" => "current stock",
//                "jml" => "qty",
//                "satuan" => "satuan",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga" => "Price",
//                "ppn" => "VAT",
//                //            "avail" => "current stock",
//            ),
//            2 => array(
//                "harga" => "Price",
//                "ppn" => "VAT",
//                //            "avail" => "current stock",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "jml",
//            ),
//            2 => array(
//                "jml",
//            ),
//        ),
//        "shoppingCartMainEditableFields" => array(
//            1 => array(
//                "nota" => array(
//                    "jml",
//                ),
//                "barang" => array(
//                    "jml",
//                    "harga",
//                ),
//            ),
//            2 => array(
//                "nota" => array(
//                    "jml",
//                ),
//                "barang" => array(
//                    "jml",
//                ),
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga+ppn)",
//            2 => "jml*(harga+ppn)",
//        ),
//
//        "shoppingCartFieldValidators" => array(
//            "jml" => "jumlah return pembelian",
//            "harga" => "harga beli",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//        ),
//
//        "referenceFields" => array(
//            "referenceID" => "transaksi_id",
//            "referenceJenis" => "jenis",
//            "referenceNomer" => "nomer",
//            "paymentMethod" => "pembayaran",
//        ),
//        "pairMakers" => array(
//            1 => array(
//                "stokSupplies" => array(
//                    "helperName" => "he_cek_stock_supplies",
//                    "functionName" => "cekStockSupplies",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                    ),
//                ),
//            ),
//        ),
//        "pairInjectors" => array(
//            1 => array(
//                "stokSupplies" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "stok",
//                    ),
//                ),
//            ),
//        ),
//        "receiptElements" => array(
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "vendor details",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "name",
//                    "npwp" => "tax-ID",
//                    "alamat_1" => "address",
//                    "tlp_1" => "phone",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "reasonDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Alasan",
//                "mdlName" => "MdlAlasanBatal",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "nilai",
//                "description" => "",
//                "usedFields" => array(
//                    "nilai" => "",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),
//
//        ),
//
//        "shoppingCartSessionDeleter" => array(
//            "items" => array(),
//            "tableIn_detail_values" => array(),
//            "tableIn_detail" => array(),
//            "main" => array(
//                "seluruhnya",
//                "referenceID",
//                "referenceJenis",
//                "referenceNomer",
//            ),
//        ),
//        "previewCtr" => "Create",
//    ),
//    "1961" => array(
//        "icon" => "fa fa-rotate-left",
//        "label" => "close/fullfillment Supplies Purchasing",
//        "place" => "center",//=> "center",
//        "steps" => array(
//            1 => array(
//                "label" => "request close/fullfillment purchasing",
//                "actionLabel" => "make close/fullfillment",
//                "source" => "",
//                "target" => "1961",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "request close/fullfillment transaksi",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "prepare by",
//                "isCancelPacking" => true,
//            ),
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
//        ),
//        "template" => "template/transaksi_fullfill.html",
//        "isDisableMakeTrans" => true,
//        "selectorModel" => "MdlNotaItem",
//        "selectorSrcModel" => "MdlNotaItem",
//        "selectedPrice" => array(
//            //            "model" => "MdlHargaProduk",
//            //            "label" => "hpp",
//        ),
//        "lockerCheck" => array(
//            "enabled" => false,
//            "mdlName" => "MdlLockerStock",
//            "jenis" => "produk",
//            "jenis_locker" => "stock",
//        ),
//        "selectorFilters" => array(
//            "returned=.0",
//            "jenis=.467",
//            "suppliers_id=pihakID",
//            "tail_number=.3",
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nomer",
//        ),
//        "selectorViewedFields" => array(
//            "nomer",
//            "dtime",
//        ),
//        "selectorProcessor" => "_processSelectNotaItem/select",
//        "editHandlerMethod" => "cancel",
//        "pihakModel" => "MdlCustomer_and_pre",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "customer",
//        "pihakMainValueSrc" => array(
//            "ppnFactor" => "ppn",
//        ),
//        "pihakProcessor" => "_processPihak/select",
//        "shortHistoryFields" => array(
//            //            "jenis_label"    => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "request number",
//            "transaksiDatas__nomer" => "PO Number",
//            "oleh_nama" => "person",
//            "next_pic" => "Next step otorisator",
//        ),
//        "compactHistoryFields" => array(
//            //            "jenis_label"    => "activity",
//            "dtime" => "date",
//            //            "suppliers_nama" => "customer",
//            //            "nomer"          => "receipt number",
//            "oleh_nama" => "person",
//            //            "harga"          => "amount",
//            //            "disc"           => "discount",
//            //            "ppn"            => "ppn",
//            //            "nett2" => "total amount",
//        ),
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//        "shoppingCart" => array(
//            "initPrices" => "jual",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "produk_nama",
//            "produk_kode" => "produk_kode",
//            "label" => "produk_label",
//            "satuan" => "satuan",
//            "berat_gross" => "berat_gross",
//            "lebar_gross" => "lebar_gross",
//            "panjang_gross" => "panjang_gross",
//            "tinggi_gross" => "tinggi_gross",
//            "volume_gross" => "volume_gross",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Description",
//                //                "produk_kode" => "Product No.",
//                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut PO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>PO</span>",
//                //                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
//                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH GRN</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>GRN</span>",
//                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
//                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
//                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
//                "satuan" => "uom",
//                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
//            ),
//            2 => array(
//                "nama" => "Description",
//                //                "produk_kode" => "Product No.",
//                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut PO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>PO</span>",
//                //                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
//                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH GRN</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>GRN</span>",
//                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
//                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
//                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
//                "satuan" => "uom",
//                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                //                "harga"        => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "stok" => "stok",
//                //                "disc"         => "disc (IDR)",
//                //                "ppn"          => "VAT",
//                //            "avail" => "current stock",
//            ),
//            2 => array(
//                //                "harga"        => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc"         => "disc (IDR)",
//                //                "ppn"          => "VAT",
//                //            "avail" => "current stock",
//            ),
//            3 => array(
//                //                "harga"        => "Price",
//                //                "disc_percent" => "disc (%)",
//                //                "disc"         => "disc (IDR)",
//                //                "ppn"          => "VAT",
//                //            "avail" => "current stock",
//            ),
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "jml",
//            ),
//            2 => array(
//                "jml",
//            ),
//            3 => array(
//                "jml",
//            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga)",
//            2 => "jml*(harga)",
//            //            3 => "jml*(harga+ppn)",
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                //                "harga" => "Amount",
//                //                "disc"  => "Disc",
//                //                "ppn"   => "VAT",
//                //                "nett2" => "Grand Total",
//            ),
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => false,
//            2 => false,
//        ),
//        "shoppingCartFieldValidators" => array(
//            //            "jml"   => "quantity",
//            //            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "supplier ID",
//            "pihakName" => "supplier name",
//        ),
//        "applets" => array(),
//        "availPayments" => array(
//            //            "cash"   => array(
//            //                "label"     => "cash",
//            //                "valueGate" => "nilai_cash",
//            //                "valueSrc"  => "nett",
//            //            ),
//            //            "credit" => array(
//            //                "label"     => "credit",
//            //                "valueGate" => "nilai_credit",
//            //                "valueSrc"  => "nett",
//            //            ),
//            "inherit" => array(
//                "label" => "inherit",
//                "valueGate" => "nilai_inherit",
//                "valueSrc" => "grand_total",
//            ),
//        ),
//        "referenceFields" => array(
//            "referenceID" => "transaksi_id",
//            "referenceJenis" => "jenis",
//            "referenceNomer" => "nomer",
//            "paymentMethod" => "pembayaran",
//        ),
//        "referenceJenisTr" => "466",
//        "itemAddConfig" => false,
//        "receiptElements" => array(
//            "transaksiDatas" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Purchasing Order",
//                "mdlName" => "MdlTransaksiData",
//                "mdlFilter" => array(
//                    "id=currentID",
//                ),
//                "key" => "id",
//                "labelSrc" => "nomer",
//                "usedFields" => array(
//                    "nomer" => "Nomer",
//                    "oleh_nama" => "BY",
//                ),
//                "editPoints" => array(1),
//            ),
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
//            "reasonDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "REASON DETAILS",
//                "mdlName" => "MdlAlasanBatal",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "nilai",
//                "usedFields" => array(
//                    "nilai" => "Alasan",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),
//        ),
//        "resumeFieldNames" => array(
//            "selectFields" => "suppliers_nama",
//            "title" => "vendor",
//        ),
//        "pairRegistries" => array(
//            "tableIn_master_values", "main", "items"
//        ),
//
//        // ======== =========
//        "xShipmentConfig" => array(
//            2 => array(
//                "enabled" => false,
//                "label" => "close/fullfillment purchasing order",
//                "targetJenisMaster" => "1961",
//                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
//
//                "shipment" => "461", // ini jadi GRN
//                "packing" => "none", //
//                "cancel" => "1961",
//            ),
//        ),
//        "referenceRevert" => array(
//            "enabled" => true,
//            "referenceID" => "transaksiDatas", // ambil dari gerbang registry main
//            // untuk mengembalikan valid_qty, jadi valid_qty + cancel_qty (sesuai nota)
//            "items" => array(
//                "valid_qty" => "valid_qty",
//            ),
//        ),
//        "previewCtr" => "Create",
//    ),
//
//    // config po jasa
//    "463" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "service purchasing",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "PURCHASE PRE ORDER",
//                "actionLabel" => "make purchasing order",
//                "source" => "",
//                "target" => "463ro",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Prepare by",
//            ),
//            2 => array(
//                "label" => "PURCHASE ORDER",
//                "actionLabel" => "approve purchasing and dpp ppn",
//                "source" => "463ro",
//                "target" => "463o",
//                "userGroup" => "c_purchasing_adm",
//                "stateLabel" => "purchased",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "Approval by",
//                //                "paymentSrc" => array(
//                //                    "enabled" => true,
//                //                    "filter" => array(
//                //                        "label='incoming cash'",
//                //                    ),
//                //                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
//                //                    "label" => "Cash in Advance belum difollow up. Segera hubungi pihak Finance.",
//                //                ),
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
//            3 => array(
//                "label" => "SERVICE RECEIVED NOTE",
//                //                "actionLabel" => "make service receipt note",
//                "actionLabel" => "undo/reject/GRN",
//                "buttonLabel" => "make service receipt note",
//                "source" => "463o",
//                "target" => "463",
//                "userGroup" => "c_holding",
//                "stateLabel" => "service receipt note made",
//                "stateColor" => "#009900",
//                "stateCaption" => "Receipt by",
//                //                "allowEdit" => true,
//                //                "allowIncrement" => true,
//            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "source" => "463",
//                "target" => "113",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//            ),
//        ),
//        "template" => "template/transaksi.html",
//        "selectorModel" => "MdlJasa",
//        "selectorSrcModel" => "MdlJasa",
//        "selectedPrice" => array(),
//        "lockerCheck" => array(),
//        "selectorFilters" => array(),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//        ),
//        "selectorViewedFields" => array(
//            "nama",
//            "satuan",
//        ),
//        "selectorProcessor" => "_processSelectProductException/select",
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "vendor",
//        "pihakProcessor" => "_processPihak/select",
//        //        "pihakMainValueSrc" => array(
//        //            "npwp" => "npwp",
//        //        ),
//
////        "pihakValidate" => array(
////            "wajib_pajak" => array(
////                "model" => "MdlWajibPajak",
////                "result" => array(
////                    1 => array(
////                        "kolom" => "npwp",
////                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    2 => array(
////                        "kolom" => "no_ktp",
////                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    3 => array(
//////                        "kolom" => "no_ktp",
////                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    "none" => array(
//////                        "kolom" => "no_ktp",
////                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                ),
////            ),
////        ),
//
//        "shortHistoryFields" => array(
//            //            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer_top" => "PRE PO number",
//            // sumber dari kolom id_his
//            "nomer_po" => array(
//                "step" => 2,
//                "key" => "nomer",
//                "label" => "PO number",
//            ),
//            "nomer_grn" => array(
//                "step" => 3,
//                "key" => "nomer",
//                "label" => "SRN number",
//            ),
//            "nomer_ppn" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
//            //            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "pph23MethodPotongan__label" => "status pph 23",
//            "next_pic" => "Next step otorisator",
//            "keterangan" => "keterangan",
//        ),
//        "shortStatusFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "status_next" => "status",
//            "suppliers_nama" => "vendor",
//            //            "customers_nama" => "customer",
//            "nomer_top" => "PO number",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "pph23MethodPotongan__label" => "status pph 23",
//            //            "trash_4" => "trash 4",
//            //            "id" => "ID",
//        ),
//        "historyFields" => array(
//            1 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PO number",
//                //                "nomer" => "receipt number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            2 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "nomer" => "PO number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            3 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer" => "receipt number",
//                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            4 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer_srn" => array(
//                    "step" => 3,
//                    "key" => "nomer",
//                    "label" => "SRN number",
//                ),
//                "description_main_followup" => "INV<br>from vendor",
//                "nomer" => "realisasi ppn number",
//                "oleh_nama" => "person",
//
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "eFaktur" => "e-faktur",
//                //                "ppn" => "ppn",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//        ),
//        "extHistoryFields" => array(
//            1 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            2 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            3 => array(
//                "print_label" => "nomer",
//            ),
//            4 => array(
//                "print_label" => "nomer",
//            ),
//            5 => array(
//                "print_label" => "nomer",
//            ),
//        ),
//        "compactHistoryFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "pph23MethodPotongan__label" => "status pph 23",
//        ),
//
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "code" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            "dpp_persen" => "dpp_persen",
//            "pph" => "pph",
//        ),
//        "shopingCartCompareFields" => array(
//            1 => array(
//                "main" => "pph",
//                "slave" => "dpp_persen",
//                //                "target" =>"valid_pph_key",
//            ),
//
//            2 => array(
//                "main" => "pph",
//                "slave" => "dpp_persen",
//                //                "target" =>"valid_pph_key",
//            ),
//
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            2 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            3 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            4 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "Prosentase DPP",
//                "dppPPn" => "nilai dpp",
//                "ppn" => "PPN 11%",
//            ),
//            2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "Prosentase DPP",
//                "dppPPn" => "nilai dpp",
//                "ppn" => "PPN 11%",
//            ),
//            3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "Prosentase DPP",
//                "dppPPn" => "nilai dpp",
//                "ppn" => "PPN 11%",
//            ),
//            4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "Prosentase DPP",
//                "dppPPn" => "nilai dpp",
//                "ppn" => "PPN 11%",
//            ),
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            2 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            3 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            4 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//        ),
//        "shoppingCartNoteEnabled" => true,
//        "shoppingCartNoteType" => "textarea",
//        "shoppingCartNoteEditabled" => array(
//            2 => true,
//            3 => true,
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            2 => array(
//                "harga",
//                //                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            3 => array(
//                "harga",
//                //                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            4 => array(
//                "dpp_persen",
//                //                                "jml",
//                //                "harga",
//            ),
//        ),
//        "shopingCartParamForceEditable" => array(
//            //ini untuk force editable fields
//            1 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            2 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            3 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            4 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//        ),
//        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//            //            "nilai_dpp_ppn" =>"DPP PPN"
//        ),
//        "shoppingCartAmountValue" => array(
//            //            1 => "jml*(harga_disc+ppn)",
//            //            2 => "jml*(harga_disc+ppn)",
//            //            3 => "jml*(harga_disc+ppn)",
//            //            4 => "jml*(harga_disc+ppn)",
//            1 => "jml*(harga)",
//            2 => "jml*(harga)",
//            3 => "jml*(harga)",
//            4 => "jml*(harga)",
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => false,
//            2 => false,
//            3 => false,
//            4 => false,
//        ),
//        "shopingCartEditableCompare" => array(
//            "dpp_persen" => array(
//                "npwp_allowed" => array(
//                    0 => false,
//                    1 => true
//                ),
//
//            ),
//        ),
//        "pairRegistries" => array(
//            "tableIn_master_values", "main", "items"
//        ),
//        "receiptElements" => array(
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "VENDOR",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "",
//                    "alamat_1" => "",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    //                    "alias" => "Attn",
//                    "contact_person" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "pph23MethodPotongan" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "method of pph 23",
//                "mdlName" => "MdlPph23MethodPotongan",
//                "key" => "id",
//                //                "defaultValue" => "item",
//                "disabled_select" => array(
//                    "gate" => "valid_pph_key",
//                    "value" => array(
//                        "0" => "disabled",
//                        "1" => "",
//                    ),
//                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
//                ),
//
//
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "method",
//                    //                    "tarif" => "tarif (%)",
//                ),
//                "editPoints" => array(1, 2),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlSupplierAddress",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "extern_name" => "",
//                    "alamat" => "",
//                    "tlp" => "Phone",
//                    "alias" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Payment Method",
//                "mdlName" => "MdlPaymentMethod1",
//                //                "mdlName" => "MdlPaymentMethodCredit",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "defaultValue" => "credit",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//
//            "dummyElement" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "auto-validation",
//                "mdlName" => "MdlDummyElement",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//        ),
//        "relativeElements" => array(
//            "paymentMethod" => array(
//                //                "cash" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                //                "cia" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                "credit" => array(
//                    "top" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "term of payment",
//                        "mdlName" => "MdlTop",
//                        "mdlFilter" => array(),
//                        "key" => "kode",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                    ),
//                ),
//            ),
//        ),
//        "relativeOptions" => array(
//            //            "paymentMethod" => array(
//            //                "credit" => array(
//            //                    "discount" => array(
//            //                        "label" => "open discount",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_holding",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1, 2),
//            //                    ),
//            //                    "dp" => array(
//            //                        "label" => "down payment",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            //                ),
//            //                "cash" => array(
//            //                    "discount" => array(
//            //                        "label" => "open discount",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_holding",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1, 2),
//            //                    ),
//            //                    "dp" => array(
//            //                        "label" => "down payment",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            //                ),
//            //                "cia" => array(
//            //                    "nilai_cia" => array(
//            //                        "label" => "cash amount",
//            ////                        "defaultValue" => "nett2",
//            ////                        "minValue" => "nett2",
//            ////                        "maxValue" => "nett2",
//            ////                        "defaultValue" => "new_net3",
//            //                        "defaultValue" => "nett",
//            //                        "minValue" => "nett",
//            //                        "maxValue" => "nett",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "c_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            ////                    "discount" => array(
//            ////                        "label" => "open discount",
//            ////                        "defaultValue" => ".0",
//            ////                        "maxValue" => "nett2*50/100",
//            ////                        "auth" => array(
//            ////                            //                            "groupID" => "c_admin",
//            ////                            "groupID" => "o_finance",
//            ////                        ),
//            ////                        "addPoints" => array(1, 2),
//            ////                    ),
//            //
//            //                ),
//            //
//            //            ),
//        ),
//        "additionalRows" => array(
//            "dummyElement" => array(
//                "yes" => array(
//                    "dppPPn" => array(
//                        "label" => "Dpp",
//                        "defaultValue" => "dppPPn",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
//                            ",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "ppn" => array(
//                        "label" => "Ppn 11%",
//                        "defaultValue" => "ppn",
//                        "maxValue" => "ppn_value",
//                        "minValue" => "ppn_value",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//
//                    "payment_out" => array(
//                        "label" => "Grand total",
//                        "defaultValue" => "payment_out",
//                        "maxValue" => "payment_out",
//                        "minValue" => "payment_out",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//
//                ),
//            ),
//        ),
//        "resumeFieldNames" => array(
//            "selectFields" => "suppliers_nama",
//            "title" => "vendor",
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "suppliers_nama" => "vendor",
//            "jenis_label" => "activity",
//            "transaksi_nilai" => "orig. value",
//            "add_disc" => "discount",
//            "grand_total" => "nett",
//        ),
//        "validatePaymentSource" => array(
//            "3" => "MdlLockerValue",
//        ),
//        "allowedMainEdit" => array("1", "4"),
//        "addMainSource" => array(
//            4 => array(
//                "fields" => array(
//                    "nomer" => "INV",
//                    "dppPPn" => "DPP",
//                    "ppn" => "PPN (belum ada faktur)",
//                    "ppn_realisasi" => "PPN Realisasi",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "e-faktur",
//                ),
//                "editableFields" => array(
//                    "harga" => "number",
//                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
//                ),
//            ),
//        ),
//        "receiptEdit" => array(
//            4 => true,
//        ),
//        // berada di midValidate() Transaksi
//        "efakturValidator" => array(
//            4 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
//                    "eFaktur" => "nomer e-faktur belum diisikan.",
//                ),
//                "source" => array(
//                    "ppn", // lebih dari 0
//                    //                "ppnfactor",
//                ),
//            ),
//        ),
//        "detailForceMain" => array(
//            2 => array(
//                "source" => "pph",
//                "target" => "valid_pph_key",
//                "elemenReset" => "MdlPph23MethodPotongan",
//                "current_element" => "pph23MethodPotongan",
//            ),
//        ),
//        // ======== =========
//        "followupMainNoteValidator" => array(
//            3 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
//                ),
//                "source" => array(
//                    "description_main_followup",
//                ),
//            ),
//        ),
//        "followupMainNote" => array(
//            3 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => true,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//            4 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//
//        ),
//        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
//        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
//        // ======== =========
//        "previewCtr" => "Create",
//        "canceledLabel" => array(
//            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
//            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//        ),
//    ),
//    "1463" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "service purchasing(pusat)",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "PURCHASE PRE ORDER",
//                "actionLabel" => "make purchasing order",
//                "source" => "",
//                "target" => "1463r",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Prepare by",
//            ),
//            2 => array(
//                "label" => "PURCHASE ORDER",
//                "actionLabel" => "approve purchasing",
//                "source" => "1463r",
//                "target" => "1463o",
//                "userGroup" => "c_purchasing_adm",
//                "stateLabel" => "purchased",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "Approval by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
//            3 => array(
//                "label" => "SERVICE RECEIVED NOTE",
//                //                "actionLabel" => "make service receipt note",
//                "actionLabel" => "undo/reject/GRN",
//                "buttonLabel" => "make service receipt note",
//                "source" => "1463o",
//                "target" => "1463",
//                "userGroup" => "c_holding",
//                "stateLabel" => "service receipt note made",
//                "stateColor" => "#009900",
//                "stateCaption" => "Receipt by",
//            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "source" => "1463",
//                "target" => "113",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//            ),
//        ),
//        "template" => "template/transaksi.html",
//        "selectorModel" => "MdlExpense",
//        "selectorSrcModel" => "MdlExpense",
//        "selectedPrice" => array(
//            "model" => "MdlHargaSupplies",
//            "label" => array("hpp"),
//            "key_label" => array(
//                "hpp" => "harga",
//            ),
//            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(),
//        "selectorFilters" => array(
//            "tipe=.import",
//        ),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//        ),
//        "selectorViewedFields" => array(
//            "nama",
//            "satuan",
//        ),
//        "selectorProcessor" => "_processSelectProduct/select",
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "vendor",
//        "pihakProcessor" => "_processPihak/select",
////        "pihakValidate" => array(
////            "wajib_pajak" => array(
////                "model" => "MdlWajibPajak",
////                "result" => array(
////                    1 => array(
////                        "kolom" => "npwp",
////                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    2 => array(
////                        "kolom" => "no_ktp",
////                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    3 => array(
//////                        "kolom" => "no_ktp",
////                        "label" => "status NPWP/NON NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    "none" => array(
//////                        "kolom" => "no_ktp",
////                        "label" => "status NPWP/NON NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                ),
////            ),
////        ),
//        "shortHistoryFields" => array(
//            //            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer_top" => "PO number",
//            // sumber dari kolom id_his
//            "nomer_po" => array(
//                "step" => 2,
//                "key" => "nomer",
//                "label" => "PO number",
//            ),
//            "nomer_grn" => array(
//                "step" => 3,
//                "key" => "nomer",
//                "label" => "SRN number",
//            ),
//            "nomer_ppn" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
//            //            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "pph23MethodPotongan__label" => "status pph 23",
//            "keterangan" => "keterangan",
//        ),
//        "shortStatusFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "status_next" => "status",
//            "suppliers_nama" => "vendor",
//            //            "customers_nama" => "customer",
//            "nomer_top" => "PO number",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "pph23MethodPotongan__label" => "status pph 23",
//            //            "trash_4" => "trash 4",
//            //            "id" => "ID",
//        ),
//        "historyFields" => array(
//            1 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PO number",
//                //                "nomer" => "receipt number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            2 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "nomer" => "PO number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            3 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "nomer" => "receipt number",
//                "description_main_followup" => "INV<br>from vendor",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            4 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "nomer" => "receipt number",
//                "description_main_followup" => "INV<br>from vendor",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "eFaktur" => "e-faktur",
//                //                "ppn" => "ppn",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//        ),
//        "extHistoryFields" => array(
//            1 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            2 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            3 => array(
//                "print_label" => "nomer",
//            ),
//            4 => array(
//                "print_label" => "nomer",
//            ),
//            5 => array(
//                "print_label" => "nomer",
//            ),
//        ),
//        "compactHistoryFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "pph23MethodPotongan__label" => "status pph 23",
//        ),
//
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "code" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            "dpp_persen" => "dpp_persen",
//            "pph" => "pph",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            2 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            3 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            4 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga" => "Unit Price",
//                //                "discPersen" => "DISC(%)",
//                //                "disc" => "DISC(Rp)",
//                "ppnPersen" => "VAT(%)",// ppnFactor
//                "ppn" => "VAT(Rp)",
//            ),
//            2 => array(
//                "harga" => "Unit Price",
//                //                "discPersen" => "DISC(%)",
//                //                "disc" => "DISC(Rp)",
//                "ppnPersen" => "VAT(%)*",// ppnFactor
//                "ppn" => "VAT(Rp)",
//            ),
//            3 => array(
//                "harga" => "Unit Price",
//                //                "discPersen" => "DISC(%)",
//                //                "disc" => "DISC(Rp)",
//                "ppnPersen" => "VAT(%)",// ppnFactor
//                "ppn" => "VAT(Rp)",
//            ),
//            4 => array(
//                "harga" => "Unit Price",
//                //                "discPersen" => "DISC(%)",
//                //                "disc" => "DISC(Rp)",
//                "ppnPersen" => "VAT(%)",// ppnFactor
//                "ppn" => "VAT(Rp)",
//            ),
//        ),
//        "shoppingCartNoteEnabled" => true,
//        "shoppingCartNoteType" => "textarea",
//        "shoppingCartNoteEditabled" => array(
//            2 => true,
//            3 => true,
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "jml",
//                //                "ppnFactor",
//                "ppnPersen",
//                "discPersen",
//            ),
//            2 => array(
//                "harga",
//                "jml",
//                //                "ppnFactor",
//                "ppnPersen",
//                "discPersen",
//            ),
//            3 => array(
//                "harga",
//                "jml",
//                //                "ppnFactor",
//                "ppnPersen",
//                "discPersen",
//            ),
//        ),
//        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga-disc+ppn)",
//            2 => "jml*(harga-disc+ppn)",
//            3 => "jml*(harga-disc+ppn)",
//            4 => "jml*(harga-disc+ppn)",
//        ),
//
//        "pairRegistries" => array(
//            "tableIn_master_values", "main", "items"
//        ),
//        "receiptElements" => array(
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "VENDOR",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "",
//                    "alamat_1" => "",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    //                    "alias" => "Attn",
//                    "contact_person" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "pph23MethodPotongan" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "method of pph 23",
//                "mdlName" => "MdlPph23MethodPotongan",
//                "key" => "id",
//                //                "defaultValue" => "item",
//                "disabled_select" => array(
//                    "gate" => "valid_pph_key",
//                    "value" => array(
//                        "0" => "disabled",
//                        "1" => "",
//                    ),
//                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
//                ),
//
//
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "method",
//                    //                    "tarif" => "tarif (%)",
//                ),
//                "editPoints" => array(1, 2),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlSupplierAddress",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "extern_name" => "",
//                    "alamat" => "",
//                    "tlp" => "Phone",
//                    "alias" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            //            "paymentMethod" => array(
//            //                "elementType" => "dataModel",
//            //                "inputType" => "radio",
//            //                "label" => "payment method",
//            //                "mdlName" => "MdlPaymentMethodCredit",
//            //                "key" => "id",
//            //                "labelSrc" => "name",
//            //                "usedFields" => array(
//            //                    "name" => "",
//            //                ),
//            //                "editPoints" => array(1,),
//            //            ),
//
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Payment Method",
//                "mdlName" => "MdlPaymentMethod1",
//                //                "mdlName" => "MdlPaymentMethodCredit",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "defaultValue" => "credit",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//        ),
//        "relativeElements" => array(
//            "paymentMethod" => array(
//                //                "cash" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                //                "cia" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                "credit" => array(
//                    "top" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "term of payment",
//                        "mdlName" => "MdlTop",
//                        "mdlFilter" => array(),
//                        "key" => "kode",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                    ),
//                ),
//            ),
//        ),
//        "relativeOptions" => array(
//            //            "paymentMethod" => array(
//            //                "cia" => array(
//            //                    "nilai_cia" => array(
//            //                        "label" => "cash amount",
//            //                        "defaultValue" => "nett",
//            //                        "minValue" => "nett",
//            //                        "maxValue" => "nett",
//            //                    ),
//            //                ),
//            //            ),
//        ),
//        "resumeFieldNames" => array(
//            "selectFields" => "suppliers_nama",
//            "title" => "vendor",
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "suppliers_nama" => "vendor",
//            "jenis_label" => "activity",
//            "transaksi_nilai" => "orig. value",
//            "add_disc" => "discount",
//            "grand_total" => "nett",
//        ),
//        "validatePaymentSource" => array(
//            "3" => "MdlLockerValue",
//        ),
//        "allowedMainEdit" => array("1"),
//        "addMainSource" => array(
//            4 => array(
//                "fields" => array(
//                    "nomer" => "INV",
//                    "harga" => "DPP",
//                    "ppn" => "PPN (belum ada faktur)",
//                    "ppn_realisasi" => "PPN Realisasi",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "e-faktur",
//                ),
//                "editableFields" => array(
//                    "harga" => "number",
//                    "ppn_realisasi" => "number",
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
//                ),
//            ),
//        ),
//        "receiptEdit" => array(
//            4 => true,
//        ),
//        // berada di midValidate() Transaksi
//        "efakturValidator" => array(
//            4 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
//                    "eFaktur" => "nomer e-faktur belum diisikan.",
//                ),
//                "source" => array(
//                    "ppn", // lebih dari 0
//                    //                "ppnfactor",
//                ),
//            ),
//        ),
//        // ======== =========
//        "followupMainNoteValidator" => array(
//            3 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
//                ),
//                "source" => array(
//                    "description_main_followup",
//                ),
//            ),
//        ),
//        "followupMainNote" => array(
//            3 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => true,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//            4 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//
//        ),
//        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
//        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
//        // ======== =========
//        "previewCtr" => "Create",
//        "canceledLabel" => array(
//            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
//            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//        ),
//    ),

    "460" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "FG purchasing(import)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PRE PURCHASE ORDER",
                "actionLabel" => "make purchasing order",
                "source" => "",
                "target" => "460r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "PURCHASE ORDER",
                "actionLabel" => "approved purchasing",
                "source" => "460r",
                "target" => "460a",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "GOODS RECEIVED NOTE",
                "actionLabel" => "undo/reject/GRN",
                "buttonLabel" => "receive & make GRN",
                "source" => "460a",
                "target" => "460",
                "userGroup" => "c_gudang",
                "stateLabel" => "GRN made",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
                "allowEdit" => true,
                "allowJoin" => true,
            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "buttonLabel" => "approve",
//                "source" => "460",
//                "target" => "111",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//                "allowEdit" => false,
//                "allowJoin" => false,
//            ),
        ),
        "template" => "template/transaksi_import.html",
        "selectorModel" => "MdlProdukPerSupplier",
        "selectorSrcModel" => "MdlProduk",
        "selectedPrice" => array(
            "model" => "MdlHargaProdukPerSupplier",
            "mdlFilter" => array("suppliers_id=pihakID"),
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "produk_per_supplier.suppliers_id=pihakID",
            "produk_per_supplier.cabang_id=placeID",
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
            "keterangan",
            "kode",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        // ==============
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakFilters" => array(
//            "country<>.ID",
//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        // ==============
        "pihakModel2" => "MdlCurrency",
        "pihakCaller2" => "_selectorPihak/selectPihak2",
        "pihakLabel2" => "pilih mata uang asing",
        "pihakProcessor2" => "_processPihak/select2",
        "pihakFilters2" => array(),
        "pihakAddValidate" => array(
            "mode" => "==",
            "filter" => array(
                "country" => "ID",
            ),
            "label" => array(
                "country" => "FG purchasing (import) harus memilih vendor luar negeri atau perbaiki data vendor.",
            ),

        ),
        // ==============
        "shoppingCartNoteEnabled" => true, //tambahan notes per items dishoppingcart
        "shortHistoryFields" => array(
//            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "currencyDetails__label" => "currency",
            "currencyDetails__exchange" => "kurs",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PRE-PO number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "GRN number",
            ),
//            "nomer_ppn" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
//            "nomer" => "receipt number",
            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
////            "ppn" => "ppn",
//            "nett" => "total amount",
            "exchange__harga" => "amount",
            "exchange__disc" => "discount",
            "exchange__nett" => "total amount",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "currencyDetails__label" => "currency",
                "currencyDetails__exchange" => "kurs",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                // "nett1" => "sub amount",
////                "ppn" => "ppn",
//                "nett" => "total amount",
                "exchange__harga" => "amount",
                "exchange__disc" => "discount",
                "exchange__nett" => "total amount",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "currencyDetails__label" => "currency",
                "currencyDetails__exchange" => "kurs",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
//                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
////                "ppn" => "ppn",
//                "nett" => "total amount",
                "exchange__harga" => "amount",
                "exchange__disc" => "discount",
                "exchange__nett" => "total amount",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            3 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "currencyDetails__label" => "currency",
                "currencyDetails__exchange" => "kurs",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer" => "Receipt number",
                "description_main_followup" => "INV<br>from vendor",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",

                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
//            4 => array(
//                "no" => "no",
//                // "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer_grn" => array(
//                    "step" => 3,
//                    "key" => "nomer",
//                    "label" => "GRN number",
//                ),
//                "nomer" => "Receipt number",
//                "oleh_nama" => "person",
//                "eFaktur" => "e-faktur",
////                "ppn" => "ppn",
//                //                "transaksi_nilai" => "amount",
//                //                "harga" => "amount",
//                //                "disc" => "discount",
//                //                "ppn" => "ppn",
//                //                "nett" => "total amount",
//
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array("print_label" => "nomer"),
            4 => array("print_label" => "nomer"),
        ),
        "compactHistoryFields" => array(
            "suppliers_nama" => "vendor",
            "transaksi_nilai" => "amount",
        ),
        "shortStatusFields" => array(
//            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            "nomer_top" => "Req PO number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
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
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Descriptions",
                "produk_kode" => "Part-No",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "Descriptions",
                "produk_kode" => "Part-No",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "nama" => "Descriptions",
                "produk_kode" => "Part-No",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            4 => array(
                "nama" => "Descriptions",
                "produk_kode" => "Part-No",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
            4 => array(
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
        ),
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "produk_ord_jml",
            ),
            2 => array(
                "harga",
                "jml",
                "produk_ord_jml",
            ),
            3 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartNotFormatedFields" => array(
            1 => array(
                "harga",
                "hpp_nppn",
                "subtotal",
                "jml",
                "produk_ord_jml",
            ),
            2 => array(
                "harga",
                "hpp_nppn",
                "subtotal",
//                "jml",
//                "produk_ord_jml",
            ),
            3 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "(jml*harga)",// hpp
            2 => "(jml*harga)",// hpp
            3 => "(jml*harga)",// hpp
            4 => "(jml*harga)",// hpp
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                // "ppv" => "index",
//                "ppn" => "VAT",
                "hpp_nppn" => "Grand Total",
            ),
            //            3 => array(
            //                "harga" => "Total Amount",
            //                // "ppv" => "index",
            //                "ppn" => "VAT",
            //                "hpp_nppn" => "Grand Total",
            //            ),
        ),
        "allowedMainEdit" => array("1"),
        "pairRegistries" => array(
            "main", "tableIn_master_values",
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR DETAILS",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    //                    "npwp" => "NPWP",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "currencyDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "kurs",
                "mdlName" => "MdlCurrency",
                "mdlFilter" => array(
//                    "jenis=.2",
                    "id=pihak2ID",
                ),//idr tidak perlu tampil
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                    "exchange" => "kurs",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "capacity" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "capacity",
                "mdlName" => "MdlCapacity",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),

            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY ADDRESS",
                "mdlName" => "MdlSupplierAddress",
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "extern_name" => "",
                    "alamat" => "",
                    "tlp" => "Phone",
                    "alias" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "tos" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Term Of Shipment",
                "mdlName" => "MdlTos",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "description" => "",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),

            "shippingDate" => array(
                "elementType" => "dataField",
                "label" => "Shipping Date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
            //            "dueDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "due date",
            //                "inputType" => "date",
            //                "defaultValue" => $date->format('Y-m-d'),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethod1",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
                "defaultValue" => "credit",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),
            // ====akan tampil di UI ===== //
            "ppv_index" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "index",
                "mdlName" => "MdlPpv",
                "mdlFilter" => array(
                    "kode=.import",
                    "jenis2=.produk",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nilai" => "index",
                ),
                "editPoints" => array(1,),
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
        "relativeOptions" => array(
            "paymentMethod" => array(
                "cia" => array(
                    "nilai_cia" => array(
                        "label" => "cash amount",
                        "defaultValue" => "nett",
                        "minValue" => "nett",
                        "maxValue" => "nett",
                    ),

                ),
                "cbd" => array(
                    "nilai_cbd" => array(
                        "label" => "cash amount",
                        "defaultValue" => "nett",
                        "minValue" => "nett",
                        "maxValue" => "nett",
                    ),

                ),
                "tt_adv" => array(
                    "nilai_tt_adv" => array(
                        "label" => "cash amount",
                        "defaultValue" => "nett",
                        "minValue" => "nett",
                        "maxValue" => "nett",
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

        "gateExchange" => array(
            array(
                "enabled" => true,
                "source" => "pihak2Exchange",
                "postfix" => "exchange",
                "blacklist" => array(
                    "exchange", "sub_exchange", // selalu ditambah __
                ),
            ),
        ),
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
        //-----------------
        "closedRequest" => array(
            1 => true,
        ),
        "xShipmentConfig" => array(
            2 => array(
                "enabled" => true,
                "label" => "close/fullfillment purchasing order",
                "targetJenisMaster" => "1960",
                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
                "allowedGroups" => array(
                    "c_holding",
                    "c_purchasing",
                    "c_purchasing_spv",
                    //                    "c_gudang",
                    //                    "c_gudang_spv",
                    //                    "c_finance"
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "460re",
                "label" => "EDIT PRE PURCHASE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "460rrj",
                "label" => "REJECT PRE PURCHASE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "460arj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
    ),
    //  config return pembelian finish goods import
    "960" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "FG purchases return (import)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "return request",
                "actionLabel" => "make return request",
                "source" => "",
                "target" => "960r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "return authorization",
                "actionLabel" => "approve return request",
                "source" => "960r",
                "target" => "960",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi.html",
//        "template" => "template/transaksi_pettycash.html",

        "selectorModel" => "MdlNotaItem", // relatif by nota atau by produk
        "selectorSrcModel" => "MdlNotaItem", // relatif by nota atau by produk

//        "selectorModel" => "{pihakMdlName}", // relatif by nota atau by produk
//        "selectorSrcModel" => "{pihakMdlNameSrc}", // relatif by nota atau by produk

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "selectedMainPrice" => array(
            "barang" => array(
                "model" => "MdlHargaProdukPerSupplier",
                "mdlFilter" => array("suppliers_id=pihakID"),
                "label" => array("hpp"),
                "key_label" => array(
                    "hpp" => "harga",
                ),
                "mainSrc" => "hpp",
            ),
        ),

        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "trash_4=.0",
            "returned=.0",
            "jenis=.460",
            "suppliers_id=pihakID",
        ),
//        "selectorMainFilters" => array(
//            "barang" => array(
//                "suppliers_id=pihakID",
//                "cabang_id=placeID",
//            ),
//            "nota" => array(
//                "returned=.0",
//                "jenis=.467",
//                "suppliers_id=pihakID",
//            ),
//
//        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "nota inv purchasing",

        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),
        "selectorProcessor" => "_processSelectNotaItem/select",

        "selectorMainProcessor" => array(
            "nota" => "_processSelectNotaItem/select",
            "barang" => "_processSelectProduct/select",
        ),
        "selectorMainParamFields" => array(
            "barang" => array(
                "id" => "produk_id",
                "nama" => "nama",
                "produk_kode" => "kode",
                "satuan" => "satuan",
//                "jumlah" => "jumlah",
            ),
            "nota" => array(
                "id" => "id",
                "nama" => "nomer",
            ),
        ),
        "selectorMainViewedFields" => array(
            "barang" => array(
                "keterangan",
                "kode",
                "satuan",
                //                "jumlah",
            ),
            "nota" => array(
                "nomer",
                "dtime",
            ),
        ),

        "editHandlerMethod" => "edit",
        "editMainHandlerMethod" => array(
            "nota" => "edit",
            "barang" => "select",
        ),


        // ada pilihan metode return pembelian (by nota atau by produk)
//        "pihakModelMain" => "MdlProdukReturnMethodStatic",
//        "pihakMainCaller" => "Selectors/_selectorPihakMain/selectPihak",
//        "pihakMainLabel" => "metode return pembelian",
//        "pihakMainFilters" => array(),
//        "pihakMainValueSrc2" => array(
//            "pihakMdlName" => "mdl_name",
//            "pihakMdlNameSrc" => "mdl_name_src",
//        ),
//        "pihakMainProcessor" => "Selectors/_processPihakMain/select",
        // ada pilihan metode return pembelian (by nota atau by produk)

        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakFilters" => array(
//            "country<>.ID",
//            "id=.-1",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakAddValidate" => array(
            "mode" => "==",
            "filter" => array(
                "country" => "ID",
            ),
            "label" => array(
                "country" => "FG purchases Return (import) harus memilih vendor luar negeri atau perbaiki data vendor.",
            ),

        ),

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
//            "ppn" => "ppn",
            "nett" => "total amount",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer" => "request number",
                "review_details" => "review",
                "oleh_nama" => "person",
                "harga" => "amount",
                "disc" => "discount",
//                "ppn" => "ppn",
                "nett" => "total amount",
                "pihakMainName" => "return method",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "nomer" => "return number",
                "review_details" => "review",
                "oleh_nama" => "person",
                "harga" => "amount",
                "disc" => "discount",
//                "ppn" => "ppn",
                "nett" => "total amount",
                "pihakMainName" => "return method",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "review_details" => "id",
                "print_label" => "nomer"),
            2 => array(
                "review_details" => "id",
                "print_label" => "nomer"),
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
            "nama" => array(
                "produk_nama",
                "nama",
            ), // coba di array-kan
            "code" => "produk_kode", // coba di array-kan
            "produk_kode" => "kode",
            "label" => array(
                "produk_label",
                "label",
            ),
            "satuan" => "satuan",

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
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item name",
                //            "avail" => "current stock",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
//                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
//                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                //                "harga",
            ),
            2 => array(
                "jml",
            ),
        ),
        "shoppingCartMainEditableFields" => array(
            1 => array(
                "nota" => array(
                    "jml",
                ),
                "barang" => array(
                    "jml",
                    "harga",
                ),
            ),
            2 => array(
                "nota" => array(
                    "jml",
                ),
                "barang" => array(
                    "jml",
                ),
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
        ),


        "shoppingCartFieldValidators" => array(
            "jml" => "jumlah return pembelian",
            "harga" => "harga beli",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),

        "availPayments" => array(

            "inherit" => array(
                "label" => "inherit",
                "valueGate" => "nilai_inherit",
                "valueSrc" => "nett",
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
                    //                    "helperName" => "he_cek_stock_produk",
                    //                    "functionName" => "cekStockProduk",
                    //                    "params" => array(
                    //                        "cabang_id" => "placeID",
                    //                        "gudang_id" => "gudangID",
                    //                    ),
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "target" => array("items"),
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
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "vendor details",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
                    "npwp" => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1" => "phone",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "currencyDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "kurs",
                "mdlName" => "MdlCurrency",
                "mdlFilter" => array(
                    "id=currencyDetails",
                ),//idr tidak perlu tampil
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "name",
//                    "exchange" => "kurs",
                ),
                "editPoints" => array(1, 2, 3),
            ),

        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "shoppingCartSessionDeleter" => array(
            "items" => array(),
            "tableIn_detail_values" => array(),
            "tableIn_detail" => array(),
            "main" => array(
                "seluruhnya",
                "referenceID",
                "referenceJenis",
                "referenceNomer",
            ),
        ),
        "gateExchange" => array(
            array(
                "enabled" => true,
                "source" => "pihak2Exchange",
                "postfix" => "exchange",
                "blacklist" => array(
                    "exchange", "sub_exchange", // selalu ditambah __
                ),
            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "960re",
                "label" => "EDIT return request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "960rrj",
                "label" => "REJECT return request",
            ),
        ),
    ),
    "1960" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "close/fullfillment Purchasing Order (Import)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request close/fullfillment purchasing order (import)",
                "actionLabel" => "make close/fullfillment transaksi",
                "source" => "",
                "target" => "1960",
                "userGroup" => "c_purchasing",
                "stateLabel" => "request close/fullfillment transaksi",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
                "isCancelPacking" => true,
            ),
            2 => array(
                "label" => "otorisasi close/fullfillment purchasing order (import)",
                "actionLabel" => "approve close/fullfillment transaksi",
                "source" => "1960",
                "target" => "1960a",
                "userGroup" => "c_purchasing",
                "stateLabel" => "purchasing canceled",
                "stateColor" => "#ff7700",
                "isCancelPacking" => true,
            ),
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
        "pihakModel" => "MdlSupplier",
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
                "produk_kode" => "Product No.",
                "max_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah produk menurut PO'><i class='fa fa-question-circle'></i></span><br><span class='text-primary'>PO</span>",
                //                "packed_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'><span class='text-yellow text-bold'>ON PACKING</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>packed</span>",
                "sent_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <span class='text-green text-bold'>SUDAH GRN</span>\"><i class='fa fa-question-circle'></i></span><br><span class='text-green'>GRN</span>",
                "req_cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>PROCESS DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel req</span>",
                "cancel_jml" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah yang <r><b>SUDAH DICANCEL</b></r>\"><i class='fa fa-question-circle'></i></span><br><span class='text-red'>canceled</span>",
                "jml" => "qty <span data-toggle='tooltip' data-html='true' data-title='Jumlah yang <r><b>AKAN DICANCEL</b></r>'><i class='fa fa-question-circle'></i></span><br><span class='text-red'>cancel</span>",
                "satuan" => "uom",
                "outstanding" => "qty <span data-toggle='tooltip' data-html='true' data-title=\"Jumlah Out Standing jika melanjutkan CANCEL, <span class='text-red text-bold'>NOL</span> menandakan produk telah terpenuhi\"><i class='fa fa-question-circle'></i></span><br><span class='text-yellow'>outstanding</span>",
            ),
            2 => array(
                "nama" => "Description",
                "produk_kode" => "Product No.",
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
        "referenceJenisTr" => "460",
        "itemAddConfig" => false,
        "receiptElements" => array(
            "transaksiDatas" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Purchasing Order",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array(
                    "id=currentID",
                ),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "nomer" => "Nomer",
                    "oleh_nama" => "BY",
                    "jenis" => "jenis",
                    "nomer_top" => "Nomer referensi",
                    "id_master" => "ID Referensi",
                    "jenis_master" => "jenis Referensi",
                    "id_top" => "Pre PO",
                ),
                "editPoints" => array(1),
            ),
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR DETAILS",
                "mdlName" => "MdlSupplier",
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
            "reasonDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "REASON DETAILS",
                "mdlName" => "MdlAlasanBatal",
                "mdlFilter" => array(
                    "jenis=.item",
                    "untuk=.po",
                ),
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
            2 => array(
                "enabled" => false,
                "label" => "close/fullfillment purchasing order",
                "targetJenisMaster" => "1960",
                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
                "shipment" => "460", // ini jadi GRN
                "packing" => "none", //
                "cancel" => "1960",
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
        "descriptionValidate" => array(
            "enabled" => true,
            "key" => array(
                "description" => "detail alasan harus diisikan di formulir deskripsi.",
            ),
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1960e",
                "label" => "EDIT request close/fullfillment purchasing order (import)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1960rj",
                "label" => "REJECT request close/fullfillment purchasing order (import)",
            ),
        ),
    ),

    // purchasing project cost

//    "3463" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "service project purchasing",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "SERVICE PROJECT PURCHASE PRE ORDER",
//                "actionLabel" => "make purchasing order",
//                "source" => "",
//                "target" => "3463ro",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Prepare by",
//            ),
//            2 => array(
//                "label" => "SERVICE PROJECT PURCHASE ORDER",
//                "actionLabel" => "approve purchasing and dpp ppn",
//                "source" => "3463ro",
//                "target" => "3463o",
//                "userGroup" => "c_purchasing_adm",
//                "stateLabel" => "purchased",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "Approval by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
//            3 => array(
//                "label" => "SERVICE PROJECT RECEIVED NOTE",
//                "actionLabel" => "undo/reject/GRN",
//                "buttonLabel" => "make service receipt note",
//                "source" => "3463o",
//                "target" => "3463",
//                "userGroup" => "c_holding",
//                "stateLabel" => "service receipt note made",
//                "stateColor" => "#009900",
//                "stateCaption" => "Receipt by",
//            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "source" => "3463",
//                "target" => "3113",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "PT. Everest Electronic",
//            ),
//        ),
//        "template" => "template/transaksi_4.html",
//        "selectedPrice" => array(),
//        "lockerCheck" => array(),
//
//        "selectorModel" => "MdlJasa",
//        "selectorSrcModel" => "MdlJasa",
//        "selectorFilters" => array(),
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "item",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "nama" => "nama",
//            "satuan" => "satuan",
//        ),
//        "selectorViewedFields" => array(
//            "nama",
//            "satuan",
//        ),
//        "selectorProcessor" => "_processSelectProductException/select",
//        "editHandlerMethod" => "select",
//        // PIHAK I
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "vendor",
//        "pihakProcessor" => "_processPihak/select",
//        // PIHAK II
//        "pihakModelMain" => "MdlCabang",
//        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
//        "pihakMainLabel" => "outlet / cabang",
//        "pihakMainFilters" => array(
//            "id<>.-1",
//
//        ),
//        "pihakMainProcessor" => "_processPihakMain/select",
//        // PIHAK III
//        "pihakModelExtern" => "MdlCustomerProjek",
//        "pihakExternCaller" => "_selectorPihak/selectPihakExtern",
//        "pihakExternLabel" => "customer project",
//        "pihakExternViewedFields" => array(
//            "nama",
//        ),
//        "pihakExternFilters" => array(
//            "status=.1",
//            "trash=.0",
//            "kategori_nama=.projek",
//        ),
//        "pihakExternProcessor" => "_processPihak/selectExtern",
////        "pihakExternNota" => true,
//
//        //        "pihakMainValueSrc" => array(
//        //            "npwp" => "npwp",
//        //        ),
////        "pihakValidate" => array(
////            "wajib_pajak" => array(
////                "model" => "MdlWajibPajak",
////                "result" => array(
////                    1 => array(
////                        "kolom" => "npwp",
////                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    2 => array(
////                        "kolom" => "no_ktp",
////                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    3 => array(
//////                        "kolom" => "no_ktp",
////                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                    "none" => array(
//////                        "kolom" => "no_ktp",
////                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
////                    ),
////                ),
////            ),
////        ),
//
//        "shortHistoryFields" => array(
//            //            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "customerName" => "customer",
//            "nomer_top" => "PRE PO number",
//            // sumber dari kolom id_his
//            "nomer_po" => array(
//                "step" => 2,
//                "key" => "nomer",
//                "label" => "PO number",
//            ),
//            "nomer_grn" => array(
//                "step" => 3,
//                "key" => "nomer",
//                "label" => "SRN number",
//            ),
//            "nomer_ppn" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
//            //            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "pph23MethodPotongan__label" => "status pph 23",
//            "next_pic" => "Next step otorisator",
//            "keterangan" => "keterangan",
//        ),
//        "shortStatusFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "status_next" => "status",
//            "suppliers_nama" => "vendor",
//            "customerName" => "customer",
//            "nomer_top" => "PO number",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//            "pph23MethodPotongan__label" => "status pph 23",
//            //            "trash_4" => "trash 4",
//            //            "id" => "ID",
//        ),
//        "historyFields" => array(
//            1 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "customerName" => "customer",
//                "nomer_top" => "PO number",
//                //                "nomer" => "receipt number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            2 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "customerName" => "customer",
//                "nomer_top" => "PRE PO number",
//                "nomer" => "PO number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            3 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "customerName" => "customer",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer" => "receipt number",
//                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            4 => array(
//                "no" => "no",
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "suppliers_nama" => "vendor",
//                "customerName" => "customer",
//                "nomer_top" => "PRE PO number",
//                "ids_his" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "PO number",
//                ),
//                "nomer_srn" => array(
//                    "step" => 3,
//                    "key" => "nomer",
//                    "label" => "SRN number",
//                ),
//                "description_main_followup" => "INV<br>from vendor",
//                "nomer" => "realisasi ppn number",
//                "oleh_nama" => "person",
//
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
//                "eFaktur" => "e-faktur",
//                //                "ppn" => "ppn",
//                "pph23MethodPotongan__label" => "status pph 23",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//        ),
//        "extHistoryFields" => array(
//            1 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            2 => array(
//                //                "review_details" =>"id",
//                "print_label" => "nomer",
//            ),
//            3 => array(
//                "print_label" => "nomer",
//            ),
//            4 => array(
//                "print_label" => "nomer",
//            ),
//            5 => array(
//                "print_label" => "nomer",
//            ),
//        ),
//        "compactHistoryFields" => array(
//            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "vendor",
//            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "pph23MethodPotongan__label" => "status pph 23",
//        ),
//
//        "selectorFields" => array("id", "nama", "satuan"),
//        "pihakFields" => array("id", "nama"),
//
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//            "code" => "kode",
//            "label" => "label",
//            "satuan" => "satuan",
//            "dpp_persen" => "dpp_persen",
//            "pph" => "pph",
//        ),
//        "shopingCartCompareFields" => array(
//            1 => array(
//                "main" => "pph",
//                "slave" => "dpp_persen",
//                //                "target" =>"valid_pph_key",
//            ),
//
//            2 => array(
//                "main" => "pph",
//                "slave" => "dpp_persen",
//                //                "target" =>"valid_pph_key",
//            ),
//
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            2 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            3 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//            4 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//                "satuan" => "Satuan",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                "dppPPn" => "dpp ppn",
//                "ppn" => "PPN(Rp)",
//            ),
//            2 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                "dppPPn" => "dpp ppn",
//                "ppn" => "PPN(Rp)",
//            ),
//            3 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                "dppPPn" => "dpp ppn",
//                "ppn" => "PPN(Rp)",
//            ),
//            4 => array(
//                "harga" => "Unit Price",
//                "discPersen" => "DISC(%)",
//                "disc" => "DISC(Rp)",
//                "harga_disc" => "Netto",
//                "dpp_persen" => "DPP PPN(%)",
//                "dppPPn" => "dpp ppn",
//                "ppn" => "PPN(Rp)",
//            ),
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            2 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            3 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//            4 => array(
//                "harga" => "Total Amount",
//                "disc" => "DISC",
//                //                "ppn" => "VAT",
//                //                "nett" => "Total",
//            ),
//        ),
//        "shoppingCartNoteEnabled" => true,
//        "shoppingCartNoteType" => "textarea",
//        "shoppingCartNoteEditabled" => array(
//            2 => true,
//            3 => true,
//        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            2 => array(
//                "harga",
//                //                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            3 => array(
//                "harga",
//                //                "jml",
//                "dpp_persen",
//                //                "ppn_persen",
//                "discPersen",
//            ),
//            4 => array(
//                "dpp_persen",
//                //                                "jml",
//                //                "harga",
//            ),
//        ),
//        "shopingCartParamForceEditable" => array(
//            //ini untuk force editable fields
//            1 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            2 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            3 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//            4 => array(
//                "allow_params_edit" => "dpp_persen"
//            ),
//        ),
//        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//            //            "nilai_dpp_ppn" =>"DPP PPN"
//        ),
//        "shoppingCartAmountValue" => array(
//            //            1 => "jml*(harga_disc+ppn)",
//            //            2 => "jml*(harga_disc+ppn)",
//            //            3 => "jml*(harga_disc+ppn)",
//            //            4 => "jml*(harga_disc+ppn)",
//            1 => "jml*(harga)",
//            2 => "jml*(harga)",
//            3 => "jml*(harga)",
//            4 => "jml*(harga)",
//        ),
//        "shoppingCartHideSubamount" => array(
//            1 => false,
//            2 => false,
//            3 => false,
//            4 => false,
//        ),
//        "shopingCartEditableCompare" => array(
//            "dpp_persen" => array(
//                "npwp_allowed" => array(
//                    0 => false,
//                    1 => true
//                ),
//
//            ),
//        ),
//        "pairRegistries" => array(
//            "tableIn_master_values", "main", "items"
//        ),
//        "receiptElements" => array(
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "VENDOR",
//                "mdlName" => "MdlSupplier",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "",
//                    "alamat_1" => "",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    //                    "alias" => "Attn",
//                    "contact_person" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "customerProjek" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Customer Project",
//                "mdlName" => "MdlCustomerProjek",
//                "mdlFilter" => array("id=pihakExternID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "Nama",
//                    "alamat_1" => "Alamat",
//                    "country" => "Country",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    "npwp" => "npwp",
//                ),
//                "editPoints" => array(),
//            ),
//            "branch" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Branch",
//                "mdlName" => "MdlCabang",
//                "mdlFilter" => array("id=pihakMainID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                ),
//                "noValidate" => false,
//                "editPoints" => array(),
//            ),
//            "pph23MethodPotongan" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "method of pph 23",
//                "mdlName" => "MdlPph23MethodPotongan",
//                "key" => "id",
//                //                "defaultValue" => "item",
//                "disabled_select" => array(
//                    "gate" => "valid_pph_key",
//                    "value" => array(
//                        "0" => "disabled",
//                        "1" => "",
//                    ),
//                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
//                ),
//
//
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "method",
//                    //                    "tarif" => "tarif (%)",
//                ),
//                "editPoints" => array(1, 2),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlSupplierAddress",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "extern_name" => "",
//                    "alamat" => "",
//                    "tlp" => "Phone",
//                    "alias" => "Attn",
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Payment Method",
//                "mdlName" => "MdlPaymentMethod1",
//                //                "mdlName" => "MdlPaymentMethodCredit",
//                //                "mdlFilter"   => array("extern_id=pihakID"),
//                "key" => "id",
//                "defaultValue" => "credit",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "",
//                ),
//                "editPoints" => array(1,),
//            ),
//            "dummyElement" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "auto-validation",
//                "mdlName" => "MdlDummyElement",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
//        ),
//        "relativeElements" => array(
//            "paymentMethod" => array(
//                //                "cash" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                //                "cia" => array(
//                //                    "cash_account" => array(
//                //                        "elementType" => "dataModel",
//                //                        "inputType" => "radio",
//                //                        "label" => "cash account",
//                //                        "mdlName" => "MdlBankAccount",
//                //                        "key" => "id",
//                //                        "labelSrc" => "nama",
//                //                        "usedFields" => array(
//                //                            "nama" => "",
//                //                        ),
//                //                        "editPoints" => array(1,),
//                //                    ),
//                //                ),
//                "credit" => array(
//                    "top" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "term of payment",
//                        "mdlName" => "MdlTop",
//                        "mdlFilter" => array(),
//                        "key" => "kode",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                    ),
//                ),
//            ),
//        ),
//        "relativeOptions" => array(
//            //            "paymentMethod" => array(
//            //                "credit" => array(
//            //                    "discount" => array(
//            //                        "label" => "open discount",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_holding",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1, 2),
//            //                    ),
//            //                    "dp" => array(
//            //                        "label" => "down payment",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            //                ),
//            //                "cash" => array(
//            //                    "discount" => array(
//            //                        "label" => "open discount",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_holding",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1, 2),
//            //                    ),
//            //                    "dp" => array(
//            //                        "label" => "down payment",
//            //                        "defaultValue" => ".0",
//            //                        "maxValue" => "nett2*50/100",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "o_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            //                ),
//            //                "cia" => array(
//            //                    "nilai_cia" => array(
//            //                        "label" => "cash amount",
//            ////                        "defaultValue" => "nett2",
//            ////                        "minValue" => "nett2",
//            ////                        "maxValue" => "nett2",
//            ////                        "defaultValue" => "new_net3",
//            //                        "defaultValue" => "nett",
//            //                        "minValue" => "nett",
//            //                        "maxValue" => "nett",
//            //                        "auth" => array(
//            //                            //                            "groupID" => "c_finance",
//            //                            "groupID" => "c_finance",
//            //                        ),
//            //                        "addPoints" => array(1,),
//            //                    ),
//            ////                    "discount" => array(
//            ////                        "label" => "open discount",
//            ////                        "defaultValue" => ".0",
//            ////                        "maxValue" => "nett2*50/100",
//            ////                        "auth" => array(
//            ////                            //                            "groupID" => "c_admin",
//            ////                            "groupID" => "o_finance",
//            ////                        ),
//            ////                        "addPoints" => array(1, 2),
//            ////                    ),
//            //
//            //                ),
//            //
//            //            ),
//        ),
//        "additionalRows" => array(
//            "dummyElement" => array(
//                "yes" => array(
//                    "dppPPn" => array(
//                        "label" => "Dpp ppn",
//                        "defaultValue" => "dppPPn",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
//                            ",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "ppn" => array(
//                        "label" => "Ppn",
//                        "defaultValue" => "ppn",
//                        "maxValue" => "ppn_value",
//                        "minValue" => "ppn_value",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//
//                    "payment_out" => array(
//                        "label" => "Grand total",
//                        "defaultValue" => "payment_out",
//                        "maxValue" => "payment_out",
//                        "minValue" => "payment_out",
//                        "keyPressAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//
//                ),
//            ),
//        ),
//        "resumeFieldNames" => array(
//            "selectFields" => "suppliers_nama",
//            "title" => "vendor",
//        ),
//        "settlementHistoryFields" => array(
//            "dtime" => "time",
//            "nomer" => "receipt number",
//            "suppliers_nama" => "vendor",
//            "jenis_label" => "activity",
//            "transaksi_nilai" => "orig. value",
//            "add_disc" => "discount",
//            "grand_total" => "nett",
//        ),
//        "validatePaymentSource" => array(
//            "3" => "MdlLockerValue",
//        ),
//        "allowedMainEdit" => array("1", "4"),
//        "addMainSource" => array(
//            4 => array(
//                "fields" => array(
//                    "nomer" => "INV",
//                    "dppPPn" => "DPP",
//                    "ppn" => "PPN",
//                    "dateFaktur" => "Tgl faktur ",
//                    "eFaktur" => "e-faktur",
//                ),
//                "editableFields" => array(
//                    "eFaktur" => "text",
//                    "dateFaktur" => "date",
//                ),
//            ),
//        ),
//        "receiptEdit" => array(
//            4 => true,
//        ),
//        // berada di midValidate() Transaksi
//        "efakturValidator" => array(
//            4 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "dateFaktur" => "tanggal e-faktur belum diisikan.",
//                    "eFaktur" => "nomer e-faktur belum diisikan.",
//                ),
//                "source" => array(
//                    "ppn", // lebih dari 0
//                    //                "ppnfactor",
//                ),
//            ),
//        ),
//        "detailForceMain" => array(
//            2 => array(
//                "source" => "pph",
//                "target" => "valid_pph_key",
//                "elemenReset" => "MdlPph23MethodPotongan",
//                "current_element" => "pph23MethodPotongan",
//            ),
//        ),
//        // ======== =========
//        "followupMainNoteValidator" => array(
//            3 => array(
//                "enabled" => true,
//                "kolom" => array(
//                    "description_main_followup" => "nomer invoice dari vendor belum diisikan.",
//                ),
//                "source" => array(
//                    "description_main_followup",
//                ),
//            ),
//        ),
//        "followupMainNote" => array(
//            3 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => true,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//            4 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//
//        ),
//        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
//        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
//        // ======== =========
//        "previewCtr" => "Create",
//        "canceledLabel" => array(
//            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
//            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
//                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
//        ),
//    ),

);