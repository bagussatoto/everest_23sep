<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    "1466" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "FG purchasing (project)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PRE PURCHASE ORDER (project)",
                "label_nota" => "PRE PURCHASE ORDER (project)",
                "actionLabel" => "make purchasing order",
                "source" => "",
                "target" => "1466r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "PURCHASE ORDER (project)",
                "label_nota" => "PURCHASE ORDER (project)",
                "actionLabel" => "approved purchasing",
                "source" => "1466r",
                "target" => "1466",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowPrintQr" => true,
                "autoNextStep" => false,//minta di off kan karena sumnber maslah jik aotomatis
            ),
            3 => array(
                "label" => "PRE GOODS RECEIVED NOTE (project)",
                "label_nota" => "PRE GOODS RECEIVED NOTE (project)",
                "actionLabel" => "undo/reject/PRE GRN",
                "buttonLabel" => "receive & make PRE GRN",
                "source" => "1466",
                "target" => "1467r",
                "userGroup" => "c_gudang",
                "stateLabel" => "GRN made",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
                "allowEdit" => true,
                "allowJoin" => true,
                "allowScaner" => false,//true
                "settlementMainFields" => array(
                    "dtime" => array(
                        "label" => "tanggal",
                        "format" => "formatField",
                        "format_footer" => false,
                    ),
                    "nomer" => array(
                        "label" => "invoice",
                        "format" => "formatField",
                    ),
                    // "supplierName" => array(
                    //     "label" => "vendor",
                    // ),
                    "grand_total" => array(
                        "label" => "nilai",
                        "format" => "formatField",
                        "sumFields" => true,
                    ),
                ),
                "settlementItemFields" => array(
                    "nama" => array(
                        "label" => "produk",
                        "format" => "formatField",
                    ),
                    "qty" => array(
                        "label" => "qty",
                        "attr" => "width='40px'",
                        "format" => "formatField",
                    ),
                    // "harga2" => array(
                    //     "attr"   => "width='60px' class='text-right'",
                    //     "label" => "harga",
                    //     "format" => "formatField",
                    // ),
                    // "ppn" => array(
                    //     "attr"   => "width='40px' class='text-right'",
                    //     "label" => "ppn",
                    //     "format" => "formatField",
                    // ),
                    "hpp_nppn" => array(
                        "attr" => "width='40px' class='text-right'",
                        "label" => "harga",
                        "format" => "formatField",
                    ),
                    "sub_hpp_nppn" => array(
                        "attr" => "width='60px' class='text-right'",
                        "label" => "sub",
                        "format" => "formatField",
                    ),
                ),
            ),
            4 => array(
                "label" => "GOODS RECEIVED NOTE (project)",
                "label_nota" => "GOODS RECEIVED NOTE (project)",
                "actionLabel" => "undo/reject/GRN",
                "buttonLabel" => "receive & make GRN",
                "source" => "1467r",
                "target" => "1467",
                "userGroup" => "c_gudang",
                "stateLabel" => "GRN made",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
                "allowEdit" => true,
                "allowJoin" => true,
                "settlementMainFields" => array(
                    "dtime" => array(
                        "label" => "tanggal",
                        "format" => "formatField",
                        "format_footer" => false,
                    ),
                    "nomer" => array(
                        "label" => "invoice",
                        "format" => "formatField",
                    ),
                    // "supplierName" => array(
                    //     "label" => "vendor",
                    // ),
                    "grand_total" => array(
                        "label" => "nilai",
                        "format" => "formatField",
                        "sumFields" => true,
                    ),
                ),
                "settlementItemFields" => array(
                    "nama" => array(
                        "label" => "produk",
                        "format" => "formatField",
                    ),
                    "qty" => array(
                        "label" => "qty",
                        "attr" => "width='40px'",
                        "format" => "formatField",
                    ),
                    // "harga2" => array(
                    //     "attr"   => "width='60px' class='text-right'",
                    //     "label" => "harga",
                    //     "format" => "formatField",
                    // ),
                    // "ppn" => array(
                    //     "attr"   => "width='40px' class='text-right'",
                    //     "label" => "ppn",
                    //     "format" => "formatField",
                    // ),
                    "hpp_nppn" => array(
                        "attr" => "width='40px' class='text-right'",
                        "label" => "harga",
                        "format" => "formatField",
                    ),
                    "sub_hpp_nppn" => array(
                        "attr" => "width='60px' class='text-right'",
                        "label" => "sub",
                        "format" => "formatField",
                    ),
                ),
            ),
//            5 => array(
//                "label" => "realisasi ppn masukan (project)",
//                "actionLabel" => "approve ppn masukan",
//                "buttonLabel" => "approve",
//                "source" => "1467",
//                "target" => "1111",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#009900",
//                "stateCaption" => "",
//                "allowEdit" => false,
//                "allowJoin" => false,
//            ),
        ),
        "template" => "template/transaksi.html",
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
            "produk.status=.1",
            "produk.trash=.0",
            "produk.jenis=.item",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih item terdaftar",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "keterangan",
            "kode",
            "kategori_nama",
            "sub_kategori_nama",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "selectorProcessorBi" => "_processSelectProduct/multiSelectBiNew",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih supplier terdaftar",
        "pihakFilters" => array(
//            "country=.ID",
//            "id=.-1",
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
            "nomer_ppn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "realisasi ppn number",
            ),
            "item_fields" => "isi",
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "gudangTujuanLabel" => "gudang penerimaan<br>barang",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                //                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                // "nett1" => "sub amount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "gudangTujuanLabel" => "gudang penerimaan<br>barang",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "gudangTujuanLabel" => "gudang penerimaan<br>barang",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            3 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer" => "Receipt number",
                "item_fields" => "isi",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",

                "gudangTujuanLabel" => "gudang penerimaan<br>barang",
                "keterangan" => "keterangan",
                "print_label" => "tool",
                "print_barcode_pembelian" => array(
                    "label" => "print Serial",
                    "key" => array(
                        "print_barcode_pembelian",
                        "print_barcode_pembelian_2",
                    ),
                ),
            ),
            4 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer_grn" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "GRN number",
                ),
                "nomer" => "Receipt number",
                "item_fields" => "isi",
                "description_main_followup" => "INV<br>from vendor",
                "oleh_nama" => "person",
                "eFaktur" => "e-faktur",
                "ppn" => "ppn",
                "ppn_realisasi" => "ppn<br>realisasi",
                "selisih_ppn_realisasi" => "selisih ppn<br>realisasi",

                //                "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",

                "gudangTujuanLabel" => "gudang penerimaan<br>barang",
                "keterangan" => "keterangan",
                "description" => "catatan",
                "print_label" => "tool",
            ),
        ),

        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
            3 => array(
                "print_label" => "nomer",
                "print_barcode_pembelian" => "id",
                "print_barcode_pembelian_2" => "id",
            ),
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
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
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
            "jenis_barang" => "jenis",
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "barcode",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "sku",
                "keterangan" => "part number",
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
                "keterangan" => "part number",
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
                "keterangan" => "part number",
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
                "keterangan" => "part number",
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
                "keterangan" => "part number",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga_last_purchase" => "Last Purchase<br>Price",
                "harga" => "Unit Price",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga_last_purchase" => "Last Purchase<br>Price",
                "harga" => "Unit Price",
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
            "pihakID" => "vendor/supplier ID",
            "pihakName" => "vendor/supplier name",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                // "ppv" => "index",
                "ppn" => "VAT",
                "hpp_nppn" => "Grand Total",
            ),
            //            3 => array(
            //                "harga" => "Total Amount",
            //                // "ppv" => "index",
            //                "ppn" => "VAT",
            //                "hpp_nppn" => "Grand Total",
            //            ),
        ),
        "shoppingCartHeaderElement" => array(
            1 => array(
                "ppn" => array(
                    "label" => "TENTUKAN PPN",
                    "class" => "col-xs-6 no-padding",
                    "subElements" => array(
                        "non_ppn" => array(
                            "label" => "Non PPN",
                            "srcMain" => "ppnPersenCheck",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => "0",
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
                            "value" => "1",
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                    )
                ),
            )
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

        "tabHistoryFields" => array(
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => true,
//            ),
            "supplier_id" => array(
                "label" => "By Supplier",
                "allowFollowup" => true,
            ),
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
                "id" => "bID",
                "nama" => "nama bahan baku",
                "satuan" => "satuan",
                "sales_order" => "kebutuhan",
                "stock" => "stok tersedia",
//                "stok_buffer"      => "buffer",
                "purchase_request" => "request",
                "purchase_order" => "outstanding PO",
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
            "supplier_id" => array(
//                "select"           => "All",
                "id" => "bID",
                "nama" => "nama bahan baku",
                "satuan" => "satuan",
                "sales_order" => "kebutuhan",
                "stock" => "stok tersedia",
//                "stok_buffer"      => "buffer",
                "purchase_request" => "request",
                "purchase_order" => "outstanding PO",
                "new_order" => "kekurangan",
                "supplier_nama" => "supplier",
            ),
            "supplier_id_attr" => array(
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
                "supplier_nama" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "nama",
                    "link" => "_processSelectProduct/multiSelectBi/1466",
                    "link_modal" => false,
                ),
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
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUPPLIER DETAILS",
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

            "shippingDate" => array(
                "elementType" => "dataField",
                "label" => "Shipping Date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),

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
                    "kode=.lokal",
                    "jenis2=.produk",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nilai" => "index",
                ),
                "editPoints" => array(1,),
            ),
            "gudangProject" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",//radio
                "label" => "gudang project pusat",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "jenis=.gudang project"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
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
            ),
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
                    "description_main_followup" => "nomer invoice dari supplier/vendor belum diisikan.",
                ),
                "source" => array(
                    "description_main_followup",
                ),
                "maximal_karakter" => 50,
                "maximal_karakter_label" => "isikan maksimal maximal_karakter karakter.",
            ),
        ),
        "followupMainNote" => array(
            3 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => true,
                "label" => "INVOICE FROM SUPPLIER/VENDOR (*)",
                "maximal_karakter" => 50,
                "maximal_karakter_label" => "isikan maksimal maximal_karakter karakter.",
            ),
            4 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => false,
                "label" => "INVOICE FROM SUPPLIER/VENDOR (*)",
            ),
            5 => array(
                "previews" => true,
                "enabled" => true,
                "editabled" => false,
                "label" => "INVOICE FROM SUPPLIER/VENDOR (*)",
            ),
        ),
        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        // ======== =========
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => true,
                "label" => "close/fullfillment purchasing order",
                "targetJenisMaster" => "19967",
                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
                "allowedGroups" => array(
                    "c_holding",
                    "c_purchasing",
                    "c_purchasing_spv",
                ),
            ),
            2 => array(
                "enabled" => true,
                "label" => "close/fullfillment purchasing order",
                "targetJenisMaster" => "19967",
                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",
                "allowedGroups" => array(
                    "c_holding",
                    "c_purchasing",
                    "c_purchasing_spv",
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
                        //                        "gudang_id" => ".0",
                        "jenis_value" => ".hpp",
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
        "ppnPersenCheckValidate" => array(
            2 => array(
                "enabled" => true,
                "key" => "ppnPersenCheck",
                "sessionCek" => "ppn",
                "labelWarning" => array(
                    0 => "PO ini ditentukan tanpa PPN. Otorisasi ini menimbulkan PPN, segera koreksi, login ulang atau hubungi admin.",// bila 0 maka ppn harus 0
                    1 => "PO ini ditentukan dengan PPN. Otorisasi ini tidak menimbulkan PPN, segera koreksi, login ulang atau hubungi admin.",// bila 0 maka ppn harus 0
                ),
            ),
            3 => array(
                "enabled" => true,
                "key" => "ppnPersenCheck",
                "sessionCek" => "ppn",
                "labelWarning" => array(
                    0 => "PO ini ditentukan tanpa PPN. Otorisasi ini menimbulkan PPN, segera koreksi, login ulang atau hubungi admin.",// bila 0 maka ppn harus 0
                    1 => "PO ini ditentukan dengan PPN. Otorisasi ini tidak menimbulkan PPN, segera koreksi, login ulang atau hubungi admin.",// bila 0 maka ppn harus 0
                ),
            ),
            4 => array(
                "enabled" => true,
                "key" => "ppnPersenCheck",
                "sessionCek" => "ppn",
                "labelWarning" => array(
                    0 => "PO ini ditentukan tanpa PPN. Otorisasi ini menimbulkan PPN, segera koreksi, login ulang atau hubungi admin.",// bila 0 maka ppn harus 0
                    1 => "PO ini ditentukan dengan PPN. Otorisasi ini tidak menimbulkan PPN, segera koreksi, login ulang atau hubungi admin.",// bila 0 maka ppn harus 0
                ),
            ),
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
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1466re",
                "label" => "EDIT PRE PURCHASE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1466rrj",
                "label" => "REJECT PRE PURCHASE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1466rj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
        //----
        "tabRequestCode" => array(
            "masterCode" => "1466",
            "stateCode" => "1466",
            "stepNumber" => "1",
            "allowMultiSelect" => false,
            "selectProduk" => true,
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
        //----
        "serialNumberGenerateValidator" => array(
            3 => array(
                "enabled" => true,
                "source" => "items2",
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",
//                "mdlName" => "MdlProdukPerSerialNumber",

            ),
        ),
        "validateRelasiUangMuka" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "undoneItemsIndexAll" => true,
        //----
        "produkUnitPartReloadData" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        //-----------------
        "closedRequest" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "shoppingCartCheckerItems" => true,
        "validateSkuKomponen" => array(
            2 => array(
                "enabled" => true,
                "source" => "outdoor_sku",
                "target" => array(
                    "indoor_sku_1",
                    "indoor_sku_2",
                    "indoor_sku_3",
                    "indoor_sku_4",
                ),
                "label" => "Produk {nama}, SKU Outdoor sama dengan SKU Indoor. Silahkan diperiksa lagi data produk anda.",
            ),
            3 => array(
                "enabled" => true,
                "source" => "outdoor_sku",
                "target" => array(
                    "indoor_sku_1",
                    "indoor_sku_2",
                    "indoor_sku_3",
                    "indoor_sku_4",
                ),
                "label" => "Produk {nama}, SKU Outdoor sama dengan SKU Indoor. Silahkan diperiksa lagi data produk anda.",
            ),
        ),
        "itemSettingDiskonPenjualan" => array(
            2 => array(
                "enabled" => false,
                "visibility" => "hidden",
            ),
            4 => array(
                "enabled" => false,
                "visibility" => "hidden",
            ),
        ),
        "produkUnitDisc" => array(
            //contoh beli ac 5 unit gratis pipa 1 meter
//            1=>array(
            "mdlName" => "MdlDiskonPembelianPairSupplier",
            "srcFields" => array(
                "id" => "id",
                "produk_id" => "produk_id",
                "produk_nama" => "produk_nama",
                "supplier_id" => "supplier_id",
                "produk_rel_id" => "produk_rel_id",
                "per_supplier_diskon_id" => "per_supplier_diskon_id",
                "per_supplier_diskon_nama" => "per_supplier_diskon_nama",
                "produk_rel_nama" => "produk_rel_nama",
                "produk_rel_satuan_id" => "produk_rel_satuan_id",
                "produk_rel_satuan_nama" => "produk_rel_satuan_nama",
                "start_date" => "start_date",
                "expired_date" => "expired_date",
                "produk_rel_harga" => "produk_rel_harga",
                "produk_rel_qty" => "produk_rel_qty",
                "qty_min" => "qty_min",

            ),
            "viewFields" => array(
                "produk_nama" => "promo pembelian",
                "qty_min" => "pembelian minimum",
                "produk_rel_nama" => "produk",
                "qty" => "qty",
                "produk_rel_satuan_nama" => "satuan",
                "produk_rel_harga" => "harga",
                "subtotal" => "subtotal",
            ),
            "srcKey" => "jml",
            "target" => "items5_sum",
//                "target"=>"items4_sum",
//            ),
//            2=>array(),
//            3=>array(
//                "mdlName"=>"MdlDiskonPembelianPairSupplier",
//                "srcFields"=>array(
//                    "id"=>"id",
//                    "qty"=>"qty",
//                    "satuan"=>"satuan",
//                    "harga"=>"harga",
//                ),
//                "viewFields"=>array(
//                    "qty"=>"qty",
//                    "satuan"=>"satuan",
//                    "harga"=>"harga",
//                ),
//            ),
        ),
        "produkUnitDiscNota" => array(
            //contoh beli ac 5 unit gratis pipa 1 meter
//            1=>array(
            "mdlName" => "MdlDiskonPembelianPairSupplier",
            "srcFields" => array(
                "id" => "id",
                "produk_id" => "produk_id",
                "produk_nama" => "produk_nama",
                "supplier_id" => "supplier_id",
                "produk_rel_id" => "produk_rel_id",
                "per_supplier_diskon_id" => "per_supplier_diskon_id",
                "per_supplier_diskon_nama" => "per_supplier_diskon_nama",
                "produk_rel_nama" => "produk_rel_nama",
                "produk_rel_satuan_id" => "produk_rel_satuan_id",
                "produk_rel_satuan_nama" => "produk_rel_satuan_nama",
                "start_date" => "start_date",
                "expired_date" => "expired_date",
                "produk_rel_harga" => "produk_rel_harga",
                "produk_rel_qty" => "produk_rel_qty",
                "qty_min" => "qty_min",

            ),
            "viewFields" => array(
//                "produk_nama" => "produk",// produk yang dibeli
//                "qty_min" => "pembelian minimum",
                "produk_rel_nama" => "produk",// produk bonusnya
                "qty" => "qty",
//                "produk_rel_satuan_nama" => "satuan",
//                "produk_rel_harga" => "harga",
//                "subtotal" => "subtotal",
//
            ),
            "srcKey" => "jml",
            "target" => "items5_sum",
//                "target"=>"items4_sum",
//            ),
//            2=>array(),
//            3=>array(
//                "mdlName"=>"MdlDiskonPembelianPairSupplier",
//                "srcFields"=>array(
//                    "id"=>"id",
//                    "qty"=>"qty",
//                    "satuan"=>"satuan",
//                    "harga"=>"harga",
//                ),
//                "viewFields"=>array(
//                    "qty"=>"qty",
//                    "satuan"=>"satuan",
//                    "harga"=>"harga",
//                ),
//            ),
        ),
        "calcStockPath" => "addons/Bi/viewProdukSales_2",
        "additionalElement" => array(
            3 => array(
                "mdlName" => "MdlProdukRelBonus",
                "fields" => array(
                    "id" => "id",
                    "nama" => "nama",
                    "qty" => "kode",
                    "harga" => "kode",
                ),
            ),
        ),
        "receiptDetailFieldsByGudang" => array(
            3 => array(
                "mdl" => "MdlGudang",
                "editabled" => true,
                "viewOnly" => false,
                "filter" => array(
                    "jenis=.reguler",
                    "tipe_gudang_id=.2",
                    "kategori_gudang_id=.2",
                    "supplier_id=pihakID",
                ),
                "exception" => array(
                    "key" => "requestReferenceSoID",//jika > 0 maka auto gudang dc/pusat
                    "auto" => array(
                        "gudangTujuan" => "9",
                        "gudangTujuanID" => "9",
                        "gudangTujuanNama" => "Gudang Project DC/PUSAT",
                        "gudangTujuanName" => "Gudang Project DC/PUSAT",
                        "gudangTujuanLabel" => "Gudang Project DC/PUSAT",
                    ),
                    "label" => "Pembelian ini adalah Dropship. Anda tidak perlu pilih gudang.",
                    "disabled" => true,
                ),
                "validate" => true,
                "labelWarning" => "Gudang Tujuan Penerimaan Barang (GRN) WAJIB ditentukan. Silahkan koreksi lagi.",
            ),
            4 => array(
                "mdl" => "MdlGudang",
                "editabled" => false,
                "viewOnly" => true,
                "filter" => array(
                    "jenis=.reguler",
                    "tipe_gudang_id=.2",
                    "kategori_gudang_id=.2",
                    "supplier_id=pihakID",
                ),
                "validate" => true,
                "labelWarning" => "Gudang Tujuan Penerimaan Barang (GRN) WAJIB ditentukan. Silahkan koreksi lagi.",
                "defaultGudang" => array(
                    "key" => "gudangTujuanID",//jika > 0 maka auto gudang dc/pusat
                    "auto" => array(
                        "gudangTujuan" => "9",
                        "gudangTujuanID" => "9",
                        "gudangTujuanNama" => "Gudang Project DC/PUSAT",
                        "gudangTujuanName" => "Gudang Project DC/PUSAT",
                        "gudangTujuanLabel" => "Gudang Project DC/PUSAT",
                    ),
                ),
            ),
        ),
    ),
    "9967" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "FG purchases return (project)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "return request (project)",
                "label_nota" => "Request FG purchases return (project)",
                "actionLabel" => "make return request",
                "source" => "",
                "target" => "9967r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "scan QR/Barcode (project)",
                "label_nota" => "FG purchases return (project)",
                "actionLabel" => "scan QR/Barcode",
                "source" => "9967r",
                "target" => "9967sc",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "return authorization (project)",
                "label_nota" => "FG purchases return (project)",
                "actionLabel" => "approve return request",
                "source" => "9967sc",
                "target" => "9967",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_pettycash.html",

        //        "selectorModel" => "MdlNotaItem", // relatif by nota atau by produk
        //        "selectorSrcModel" => "MdlNotaItem", // relatif by nota atau by produk

        "selectorModel" => "{pihakMdlName}", // relatif by nota atau by produk
        "selectorSrcModel" => "{pihakMdlNameSrc}", // relatif by nota atau by produk

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
            "returned=.0",
            "jenis=.1467",
            "suppliers_id=pihakID",
            "trash_4<>.1",
        ),
        "selectorMainFilters" => array(
            "barang" => array(
                "produk_per_supplier.suppliers_id=pihakID",
                "produk_per_supplier.cabang_id=placeID",
            ),
            "nota" => array(
                "returned=.0",
                "jenis=.1467",
                "suppliers_id=pihakID",
            ),

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
        "pihakModelMain" => "MdlProdukReturnMethodStatic",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "metode return pembelian",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
            "pihakMdlNameSrc" => "mdl_name_src",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        // ada pilihan metode return pembelian (by nota atau by produk)

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
            "nomer" => "request number",
//            "referenceNomer" => "inv number",
            "oleh_nama" => "person",
            "item_fields" => "isi",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
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
//                "referenceNomer" => "inv number",
                "review_details" => "review",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
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
//                "referenceNomer" => "inv number",
                "review_details" => "review",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pihakMainName" => "return method",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "request number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "scan number",
                ),
                "nomer" => "return number",
//                "referenceNomer" => "inv number",
                "review_details" => "review",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
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
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
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
            "no_part" => "no_part",
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
            "jenis_barang" => "jenis",
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
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
            3 => array(
                "nama" => "item name",
                //            "avail" => "current stock",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "ppn" => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Price",
                "ppn" => "VAT",
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
            3 => array(),
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
            3 => "jml*(harga+ppn)",
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_serial",
                ),
            ),
        ),


        "shoppingCartFieldValidators" => array(
            "jml" => "jumlah return pembelian",
            "harga" => "harga beli",
        ),
        "shoppingCartFieldStokValidators" => array(
            "jml" => "jumlah return pembelian melebihi jumlah stok yang tersedia.",
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
                        "gudang_id" => "gudangProjectID",
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
                "label" => "SUPPLIER DETAILS",
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

            "gudangProject" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",//radio
                "label" => "gudang project pusat",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "jenis=.gudang project"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
            ),

        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items3_sum"
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
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9967re",
                "label" => "EDIT RETURN REQUEST",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "9967rrj",
                "label" => "REJECT RETURN REQUEST",
            ),
        ),
        //----
        "shopingCartReload" => true,
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
        "serialNumberLocker" => array(
            "enabled" => false,
        ),
        "scanQrCart" => array(
            "deleteSerial" => true,
        ),
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",
            ),
        ),
        //----
        "undoneItemsIndexAll" => true,
    ),
    "19967" => array(
        "icon" => "fa fa-rotate-left",
        "label" => "close/fullfillment Purchasing Order",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request close/fullfillment purchasing order",
                "label_nota" => "request close/fullfillment purchasing order",
                "actionLabel" => "make close/fullfillment transaksi",
                "source" => "",
                "target" => "19967",
                "userGroup" => "c_purchasing",
                "stateLabel" => "request close/fullfillment transaksi",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
                "isCancelPacking" => true,
            ),
            2 => array(
                "label" => "otorisasi close/fullfillment purchasing order",
                "label_nota" => "close/fullfillment purchasing order",
                "actionLabel" => "approve close/fullfillment transaksi",
                "source" => "19967",
                "target" => "19967a",
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
        "pihakModel" => "MdlCustomer_and_pre",
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
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "jual",
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
            "jenis_barang" => "jenis",
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
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
        "referenceJenisTr" => "466",
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
            // reason detail sudah tidak dipakai. diganti dengan deskripsi manual. 4 feb 2024

//            "reasonDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "REASON DETAILS",
//                "mdlName" => "MdlAlasanBatal",
//                "mdlFilter" => array(
//                    "jenis=.item",
//                    "untuk=.po",
//                ),
//                "key" => "id",
//                "labelSrc" => "nilai",
//                "usedFields" => array(
//                    "nilai" => "Alasan",
//                ),
//                "editPoints" => array(1),
//            ),
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
                "targetJenisMaster" => "1967",
                "warning" => "You may cancel this transaction with the remaining items. continue cancel this transaction?",

                "shipment" => "467", // ini jadi GRN
                "packing" => "none", //
                "cancel" => "1967",
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
                "connectTo" => "1967e",
                "label" => "EDIT request close/fullfillment purchasing order",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1967rj",
                "label" => "REJECT request close/fullfillment purchasing order",
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
        ),
    ),

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
//                "stateCaption" => "",
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
//                "stateCaption" => "",
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
////                "nett" => "total amount",
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
////                "description_main_followup" => "INV<br>from vendor",
//                "oleh_nama" => "person",
//                "eFaktur" => "e-faktur",
//                "ppn" => "ppn",
//                "ppn_realisasi" => "ppn<br>realisasi",
//                "selisih_ppn_realisasi" => "selisih ppn<br>realisasi",
//
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
//        //----
//        "connectToEdit" => array(
//            1 => array(
//                "enabled" => true,
//                "connectTo" => "461roe",
//                "label" => "EDIT PURCHASING PRE ORDER",
//            ),
//        ),
//        "connectToReject" => array(
//            1 => array(
//                "enabled" => true,
//                "connectTo" => "461rorj",
//                "label" => "REJECT PURCHASING PRE ORDER",
//            ),
//            2 => array(
//                "enabled" => true,
//                "connectTo" => "461rrj",
//                "label" => "REJECT PURCHASING ORDER",
//            ),
//        ),
//        //-----------------
//        "closedRequest" => array(
//            1 => true,
//        ),
//    ),
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
//        //----
//        "connectToEdit" => array(
//            1 => array(
//                "enabled" => true,
//                "connectTo" => "961re",
//                "label" => "EDIT return request",
//            ),
//        ),
//        "connectToReject" => array(
//            1 => array(
//                "enabled" => true,
//                "connectTo" => "961rrj",
//                "label" => "REJECT return request",
//            ),
//        ),
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
//                    "jenis" => "jenis",
//                    "nomer_top" => "Nomer referensi",
//                    "id_master" => "ID Referensi",
//                    "jenis_master" => "jenis Referensi",
//                    "id_top" => "Pre PO ID",
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
//                "mdlFilter" => array(
//                    "jenis=.item",
//                    "untuk=.po",
//                ),
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
//        "descriptionValidate" => array(
//            "enabled" => true,
//            "key" => array(
//                "description" => "detail alasan harus diisikan di formulir deskripsi.",
//            ),
//        ),
//        //----
//        "connectToEdit" => array(
//            1 => array(
//                "enabled" => true,
//                "connectTo" => "1961e",
//                "label" => "EDIT request close/fullfillment purchasing",
//            ),
//        ),
//        "connectToReject" => array(
//            1 => array(
//                "enabled" => true,
//                "connectTo" => "1961rj",
//                "label" => "REJECT request close/fullfillment purchasing",
//            ),
//        ),
//    ),


    "1461" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "supplies purchasing",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PURCHASING PRE ORDER",
                "label_nota" => "SUPPLIES PURCHASE PRE ORDER",
                "actionLabel" => "make purchasing order",
                "source" => "",
                "target" => "1461ro",
                "userGroup" => "c_purchasing", // admin
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepared by",
            ),
            2 => array(
                "label" => "PURCHASING ORDER",
                "label_nota" => "SUPPLIES PURCHASE ORDER",
                "actionLabel" => "approve purchasing",
                "source" => "1461ro",
                "target" => "1461r",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "GOODS RECEIVE NOTE",
                "label_nota" => "supplies RECEIVE NOTE",
                //                "actionLabel" => "receive & make GRN",
                "actionLabel" => "undo/reject/GRN",
                "buttonLabel" => "receive & make GRN",
                "source" => "1461r",
                "target" => "1461",
                "userGroup" => "c_gudang",
                "stateLabel" => "GRN made",
                "stateColor" => "#009900",
                "stateCaption" => "",
                "allowEdit" => true,
            ),

        ),
        "template" => "template/transaksi_supplies.html",
        "selectorModel" => "MdlSupplies",
//        "selectorModel" => "MdlProdukPerSupplier",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
//            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            "kategori_nama",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "itemSwapper" => "_processSelectProduct/multiSelect",
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
            "suppliers_nama" => "vendor",
            "references_num" => "purchasing request",
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
            "item_fields" => "isi",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn*",
            "nett" => "total amount",
            "oleh_nama" => "person",
//            "ppnPersenCheck" => "*",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStatusFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "references_num" => "purchasing request",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "oleh_nama" => "person",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                //                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
//                "references_num" => "purchasing request",
                "nomer_top" => "PRE PO number",
                "item_fields" => "isi",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                //                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
//                "references_num" => "purchasing request",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "item_fields" => "isi",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
//                "references_num" => "purchasing request",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer" => "Receipt number",
                "item_fields" => "isi",
//                "nett" => "total amount",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
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
////                "description_main_followup" => "INV<br>from vendor",
//                "oleh_nama" => "person",
//                "eFaktur" => "e-faktur",
//                "ppn" => "ppn",
//                "ppn_realisasi" => "ppn<br>realisasi",
//                "selisih_ppn_realisasi" => "selisih ppn<br>realisasi",
//
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
        ),
        "shortItemsFields" => array(
            "kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
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
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            4 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Unit<br>Price",
                "discPersen" => "DISC<br>(%)",
                "disc" => "DISC<br>(Rp)",
                "ppnPersen" => "VAT<br>(%)",
//                "ppnFactor" => "VAT<br>(%)",
                "ppn" => "VAT<br>(Rp)",
            ),
            2 => array(
                "harga" => "Unit<br>Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",
                "ppn" => "VAT(Rp)",
            ),
            3 => array(
                "harga" => "Unit<br>Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",
                "ppn" => "VAT(Rp)",
            ),
            4 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",
                "ppn" => "VAT(Rp)",
            ),
        ),
        "shoppingCartNoteEnabled" => true,

        "shoppingCartSubamount2" => array(1 => true,),

        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "ppnPersen",
                "discPersen",
            ),
            2 => array(
                "harga",
                "jml",
                "ppnPersen",
                "discPersen",
            ),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc+ppn)",
            2 => "jml*(harga-disc+ppn)",
            3 => "jml*(harga-disc+ppn)",
            4 => "jml*(harga-disc+ppn)",
        ),
        "shoppingCartHeaderElement" => array(
            1 => array(
                "ppn" => array(
                    "label" => "TENTUKAN PPN",
                    "class" => "col-xs-6 no-padding",
                    "subElements" => array(
                        "non_ppn" => array(
                            "label" => "Non PPN",
                            "srcMain" => "ppnPersenCheck",
//                            "overWriteMain" => "ppnVendor",
//                            "srcItem" => "ppnPersen",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => "0",
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
//                            "overWriteMain" => "ppnVendor",
//                            "srcItem" => "ppnPersen",
                            "overWriteMain" => "ppnFactor",
                            "srcItem" => "ppnFactor",
                            "value" => "1",
                            "style" => array( //wajib di tentukan
                                "element" => "div",
                                "class" => "",
                                "id" => "",
                                "name" => "",
                            )
                        ),
                    )
                ),
            )
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
//            "main", "items"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR/SUPPLIER",
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
                    //                    "alias" => "Attn",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY DETAILS",
                "mdlName" => "MdlSupplierAddress",
                //                "mdlFilter"   => array("extern_id=pihakID"),
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
            "shippingDate" => array(
                "elementType" => "dataField",
                "label" => "shipping date",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints" => array(1, 2, 3, 4, 5),
            ),
            //            "paymentMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "payment method",
            ////                "mdlName" => "MdlPaymentMethod",
            //                "mdlName" => "MdlPaymentMethodCredit",
            //                //                "mdlFilter"   => array("extern_id=pihakID"),
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "method",
            //                ),
            //                "editPoints" => array(1,),
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
                    "kode=.lokal",
                    "jenis2=.supplies",
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
            ),
        ),
        "relativeOptions" => array(
            "paymentMethod" => array(
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
            ),
        ),
        //---------------------------
        "receiptElementsInjector" => array(
            "ppv_index" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "index",
                "mdlName" => "MdlPpv",
                "mdlFilter" => array(
                    "kode=.lokal",
                    "jenis2=.supplies",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nilai" => "index",
                ),
                "editPoints" => array(1,),
            ),
        ),
        //---------------------------
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
            "487" => array(
                "jenis_master" => "461",
                "jenis" => "461",
                "target" => "487",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
        "allowedMainEdit" => array("1"),
        "aliasMainTrans" => "1763",
        "swappedKeySrc" => array("pihakID", "pihakName"),
        "comPrePurchase" => array(
            1 => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "AutoPurchaseSupplies",
                        "loop" => array(
                            "produk_id" => "jml",
                        ),
                        "static" => array(),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            // 2 => array(
            //     "master" => array(),
            //     "detail" => array(
            //         array(
            //             "comName" => "AutoPurchaseSupplies",
            //             "loop" => array(
            //                 "produk_id" => "jml",
            //             ),
            //             "static" => array(),
            //             "srcGateName" => "items",
            //             "srcRawGateName" => "items",
            //         ),
            //     ),
            // ),
        ),
        "tabHistoryFields" => array(
            "produk_id" => array(
                "label" => "By Produk",
                "allowFollowup" => false,
            ),
            "transaksi_id" => array(
                "label" => "By Transaksi",
                "allowFollowup" => true,
            ),
        ),

        "tabRequestCode" => array(
            "masterCode" => "1763",
            "stateCode" => "1763r",
            "stepNumber" => "1",
            "allowMultiSelect" => false,
        ),
        "ppnDisabled" => array(
            "enabled" => true,
            "notes" => "PPN masukan belum diapprove oleh Finance.",
        ),
        "addMainSource" => array(
            4 => array(
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
        // ======== =========
        "xShipmentConfig" => array(
            1 => array(
                "enabled" => true,
                "label" => "close/fullfillment purchasing order",
                "targetJenisMaster" => "1961",
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
                "targetJenisMaster" => "1961",
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
        "previewCtr" => "Create",
        "ppnCekValidate" => array(
            "ppnPersenCheck" => "Silahkan menentukan Pajak Vendor (PPN atau NON PPN)",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1461roe",
                "label" => "EDIT PURCHASING PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1461rorj",
                "label" => "REJECT PURCHASING PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1461rrj",
                "label" => "REJECT PURCHASING ORDER",
            ),
        ),
        //-----------------
        "closedRequest" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "undoneItemsIndexAll" => true,
    ),



);