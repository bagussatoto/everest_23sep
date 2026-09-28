<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    "6699" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "input stok",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PRE input stok",
                "actionLabel" => "simpan input stok",
                "source" => "",
                "target" => "6699r",
                "userGroup" => "sys",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "input stok",
                "actionLabel" => "setujui input stok",
                "source" => "6699r",
                "target" => "6699",
                "userGroup" => "sys",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowPrintQr" => true,
            ),
//            3 => array(
//                "label" => "PRE GOODS RECEIVED NOTE",
//                "actionLabel" => "undo/reject/PRE GRN",
//                "buttonLabel" => "receive & make PRE GRN",
//                "source" => "466",
//                "target" => "467r",
//                "userGroup" => "c_gudang",
//                "stateLabel" => "GRN made",
//                "stateColor" => "#009900",
//                "stateCaption" => "",
//                "allowEdit" => true,
//                "allowJoin" => true,
//                "allowScaner" => true,
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
//                "label" => "GOODS RECEIVED NOTE",
//                "actionLabel" => "undo/reject/GRN",
//                "buttonLabel" => "receive & make GRN",
//                "source" => "467r",
//                "target" => "467",
//                "userGroup" => "c_gudang",
//                "stateLabel" => "GRN made",
//                "stateColor" => "#009900",
//                "stateCaption" => "",
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
//            5 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "buttonLabel" => "approve",
//                "source" => "467",
//                "target" => "111",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "",
//                "allowEdit" => false,
//                "allowJoin" => false,
//            ),
        ),
//        "template" => "template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
//        "selectorModel" => "MdlProdukPerSupplier",
        "selectorModel" => "MdlProduk",
        "selectorSrcModel" => "MdlProduk",
        "selectedPrice" => array(
            "model" => "MdlHargaProdukPerSupplier",
            "mdlFilter" => array(//                "suppliers_id=pihakID"
            ),
            "label" => array("hpp_supplier"),
            "key_label" => array(
                "hpp_supplier" => "harga",
            ),
            "mainSrc" => "hpp_supplier",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "produk.kategori_id=kategori_id",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih barang",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "keterangan",
            "kode",
            "no_part",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "selectorProcessorBi" => "_processSelectProduct/multiSelectBiNew",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih gudang tujuan",
        "pihakFilters" => array(
//            "country=.ID",
            "cabang_id=.-1",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakAddValidate" => array(
//            "mode" => "!=",
//            "filter" => array(
//                "country" => "ID",
//            ),
//            "label" => array(
//                "country" => "FG purchasing (lokal) harus memilih vendor lokal atau perbaiki data vendor.",
//            ),

        ),
        //---------------------------------------------------
        "shoppingCartNoteEnabled" => true, //tambahan notes per items dishoppingcart
        "shortHistoryFields" => array(
            //            "no" => "no",
//            "jenis_label" => "activity",
            "dtime" => "date",
//            "suppliers_nama" => "vendor",
            "nomer_top" => "Nomer PRE Input Stok",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "Nomer Input Stok",
            ),
//            "nomer_pre_grn" => array(
//                "step" => 3,
//                "key" => "nomer",
//                "label" => "PRE GRN number",
//            ),
//            "nomer_grn" => array(
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "GRN number",
//            ),
//            "nomer_ppn" => array(
//                "step" => 5,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
//                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE Input Stok",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
//                "disc" => "discount",
                // "nett1" => "sub amount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
//                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE Input Stok",
                "nomer" => "Input Stok",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
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
            4 => true,
            5 => false,
        ),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
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
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            4 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            5 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part",
//                "sub_qty_indoor" => "indoor",
//                "sub_qty_outdoor" => "outdoor",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga_pricelist" => "Pricelist",
                "harga" => "Harga Beli<br>tanpa ppn",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga_pricelist" => "Pricelist",
                "harga" => "Harga Beli<br>tanpa ppn",
                //                "ppn" => "VAT",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
            4 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
            5 => array(
                "harga" => "Harga Beli<br>tanpa ppn",
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
//                "harga",
//                "jml",
//                "produk_ord_jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "(jml*harga)",// hpp
            2 => "(jml*harga)",// hpp
            3 => "(jml*harga)",// hpp
            4 => "(jml*harga)",// hpp
            5 => "(jml*harga)",// hpp
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total",
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
        "shoppingCartSubDetailFields" => array(
            3 => array(
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
            4 => array(
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
            5 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
                    "gate" => "produk_serial",
                ),

            ),
        ),

        "shoppingCartHeaderElement" => array(
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
        ),
        "tabHistoryFields" => array(
            "produk_id" => array(
                "label" => "By Produk",
                "allowFollowup" => false,
            ),
            "suppliers_id" => array(
                "label" => "By Vendor",
                "allowFollowup" => true,
            ),

        ),
        "tabFieldsItems" => array(
            "suppliers_id" => array(
                "kode" => "kode",
                "nama" => "Produk Nama",
                "satuan" => "Satuan",
                "omset" => "Omset",
                "average" => "Average harian",
                "stok" => "Stok",
                "buffer" => "buffer(qty)",
                // "moq" => "Moq",
                "ideal_stok" => "proyeksi stok(qty)",
                "new_order" => "Rekomendasi order",
            ),
            "produk_id" => array(
                //                "select" => "All",
                "kode" => "kode",
                "nama" => "Produk Nama",
                "satuan" => "Satuan",
                "omset" => "Omset",
                "average" => "Average harian",
                "stok" => "Stok",
                "buffer" => "buffer<br>(qty)",
                // "moq" => "Moq",
                "ideal_stok" => "proyeksi stok(qty)",
                "new_order" => "Rekomendasi order",
                // ""=>"",
                //                "purchased" => "On Purchase",
                //                "valid_qty" => "Outstanding",

            ),
        ),
        "allowedMainEdit" => array("1"),
        "pairRegistries" => array(
            "main",
            "items",
            "tableIn_master_values",
            "items3_sum",
            "items4_sum",
        ),
        "receiptElements" => array(
            "gudangTujuanDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "GUDANG TUJUAN ",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
//                    "alamat_1" => "",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Fax",
//                    //                    "npwp" => "NPWP",
//                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1),
                "noValidate" => false,
            ),
//            "vendorDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "SUPPLIER ",
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

//            "shippingDate" => array(
//                "elementType" => "dataField",
//                "label" => "Shipping Date",
//                "inputType" => "date",
//                "defaultValue" => date("Y-m-d"),
//                "editPoints" => array(1, 2, 3, 4, 5),
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
            // ====akan tampil di UI ===== //
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

        ),
        "relativeElements" => array(
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
        ),
        "relativeOptions" => array(
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
            5 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "harga" => "DPP",
                    "ppn" => "PPN (belum ada faktur)",
                    "ppn_realisasi" => "PPN Realisasi",
                    "dateFaktur" => "Tgl faktur ",
                    "eFaktur" => "e-faktur",
                ),
                "editableFields" => array(
                    "harga" => "number",
                    "ppn_realisasi" => "number",
                    "eFaktur" => "text",
                    "dateFaktur" => "date",
                ),
            ),
        ),
        "receiptEdit" => array(
            5 => true,
        ),
        // berada di midValidate() Transaksi
        "efakturValidator" => array(
            5 => array(
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
            5 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => false,
                "label" => "INVOICE FROM VENDOR (*)",
            ),
        ),
        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        // ======== =========
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => true,
                "label" => "close/fullfillment purchasing order",
                "targetJenisMaster" => "1967",
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
            2 => array(
                "enabled" => true,
                "label" => "close/fullfillment purchasing order",
                "targetJenisMaster" => "1967",
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
        "pairMakers" => array(
            1 => array(
                "hargaLastPurchase" => array(
                    "helperName" => "he_cek_price_produk_last_purchase",
                    "functionName" => "cekPriceProdukLastPurchase",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "jenis_value" => ".hpp",
                    ),
                ),
                "hargaPricelist" => array(
                    "helperName" => "he_cek_pricelist",
                    "functionName" => "cekPricelist",
                    "params" => array(
                        "suppliers_id" => "pihakID",
                        "cabang_id" => "placeID",
                        "jenis_value" => ".hpp_supplier",
                    ),
                ),
            ),
            2 => array(
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
            3 => array(
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "no_part",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                "hargaLastPurchase" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "harga_last_purchase",
                    ),
                ),
                "hargaPricelist" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "harga_pricelist",
                    ),
                ),
            ),
        ),
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
        ),
        "ppnCekValidate" => array(
            "ppnPersenCheck" => "Silahkan menentukan Pajak Vendor (PPN atau NON PPN)",
        ),
        //----
        "ppnCekDefaultValue" => array(
            "enabled" => true,
            "ppnPersenCheck" => 1,// 1 dan 0
            "keyDetail" => array(
                "srcMain" => "ppnPersenCheck",
                "srcItem" => "ppnFactor",
                "overWriteMain" => "ppnFactor",
            ),
        ),
        "itemSettingDiskonPenjualan" => array(
//            1 => false,
            4 => true,
        ),
        "itemPriceValidator" => array(
            4 => array(
                "enabled" => false,
            ),
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "466re",
                "label" => "EDIT PRE PURCHASE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "466rrj",
                "label" => "REJECT PRE PURCHASE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "466rj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
        "shoppingcartKeterangan" => array(
            "enabled" => true,
            "label" => "* Abu-abu tidak perlu diisi.",
        ),
    ),
);