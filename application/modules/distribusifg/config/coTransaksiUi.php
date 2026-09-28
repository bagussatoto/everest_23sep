<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    "583" => array(
        "icon" => "fa fa-truck",
        "label" => "fg distribution",
        "place" => "center",
        "counter_global" => "_company_cabangID_modul_stepCode", //=> "key globla counter",
        "counter_global_part" => array(
            'jenis',
            "dtime",
            'cabangID',
            // 'cabang2_id',
            'cabang2ID',
            '_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID',
        ),
        "steps" => array(
            1 => array(
                "label" => "fg distribution request",
                "actionLabel" => "request",
                "source" => "",
                "target" => "583r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "autoSelectTarget" => true,
            ),
            2 => array(
                "label" => "distribution (scan qr/barcode)",
                "actionLabel" => "save scan qr/barcode",
                "source" => "583r",
                "target" => "583sc",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                //                "allowEdit" => false,
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "distribution authorization(auto)",
                "actionLabel" => "approve distribution",
                "source" => "583sc",
                "target" => "583",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                //                "allowEdit" => false,
                "autoNextStep" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("jual"),
            "key_label" => array(
                "jual" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //            "enabled" => true,
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
            //            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "silahkan diisi dengan item ID, nama, kode...",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
            "id",
            "keterangan",
            "kode",
            "kategori_nama",
            "sub_kategori_nama",
            "satuan",
            "jumlah",
        ),
        "selectorLinkMutasi" => array(
            "enabled" => true,
            "link" => "Ledger/viewMoveDetails/RekeningPembantuProduk/1010030030/",//persediaan produk
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "jenis=.cabang",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFieldsCek" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "requestReferenceNomer" => "referensi SO",
            "customerName" => "konsumen",
            "salesmanName" => "salesman",

            "pihakMainID" => "status gudang id",
            "pihakMainName" => "status gudang nama",
            "pihakMainJenis" => "status gudang jenis",

            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),

        "shortHistoryFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "reference_jenis_master" => "referensi jenis",
            "requestReferenceNomer" => "referensi SO",
            "customerName" => "konsumen",
            "salesmanName" => "salesman",

            "next_pic" => "Next step otorisator",
            "description" => "notes/catatan",
            "description_special" => "notes/catatan<br>tambahan",
            // "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "583r" => "request number",
            "583" => "approval number",
            //            "585r" => "request number",
            "585" => "receipt number",
            "item_fields" => "isi",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortStatusFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        "shortItemsFields2" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "produk_nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "produk_ord_jml" => "order qty",
            "produk_ord_diterima" => "sent qty",
            "valid_qty" => "outstanding",
            "produk_ord_dibeli" => "purchased qty",
        ),
        //end
        "historyFields" => array(
            1 => array(
                "no" => "no",
                //                "jenis_label" => "activity",
                "dtime" => "tanggal",
                // "suppliers_nama" => "supplier",
                "cabang2_nama" => "cabang tujuan",
                "nomer" => "no. pre distribusi",
                "item_fields" => "isi",
                // "free_produk" => "promo supplier",
                "oleh_nama" => "PIC",
                //                "transaksi_nilai" => "amount",
                "reference_jenis_master" => "referensi jenis",
                "requestReferenceNomer" => "referensi SO",
                "customerName" => "konsumen",
                "salesmanName" => "salesman",

                // "pihakMainID" => "status gudang id",
                "pihakMainName" => "status gudang nama",
                "pihakMainJenis" => "status gudang jenis",

                // "next_pic" => "Next step otorisator",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
                // "harga" => "amount",
                // "disc" => "discount",
                // // "nett1" => "sub amount",
                // "ppn" => "ppn",
                // "nett" => "total amount",
                // "keterangan" => "keterangan",
                // "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
                //                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "no. PRE distribusi",
                "nomer" => "no. ACC distribusi",
                "item_fields" => "isi",
                // "free_produk" => "promo supplier",
                "oleh_nama" => "pic",
                "reference_jenis_master" => "referensi jenis",
                "requestReferenceNomer" => "referensi SO",
                "customerName" => "konsumen",
                "salesmanName" => "salesman",

                // "pihakMainID" => "status gudang id",
                "pihakMainName" => "status gudang nama",
                "pihakMainJenis" => "status gudang jenis",

                // "next_pic" => "Next step otorisator",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
            ),
            3 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "tanggal",
                // "suppliers_nama" => "supplier",
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "no. PRE distribusi",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "no. ACC distribusi",
                ),
                "nomer" => "no. distribusi",
                "item_fields" => "isi",
                // "free_produk" => "promo supplier",
                // "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "pic",
                //                "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",

                // "keterangan" => "keterangan",
                // "print_label" => "tool",

                "customerName" => "konsumen",
                "salesmanName" => "salesman",

                // "pihakMainID" => "status gudang id",
                "pihakMainName" => "status gudang nama",
                "pihakMainJenis" => "status gudang jenis",

                // "next_pic" => "Next step otorisator",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",

                //                "print_barcode_pembelian" => "print QR",
                // "print_barcode_pembelian" => array(
                //     "label" => "print Serial",
                //     "key" => array(
                //         "print_barcode_pembelian",
                //         "print_barcode_pembelian_2",
                //     ),
                // ),
            ),
            // 4 => array(
            //     "no" => "no",
            //     // "jenis_label" => "activity",
            //     "dtime" => "date",
            //     "suppliers_nama" => "supplier",
            //     "nomer_top" => "PRE distri number",
            //     "ids_his" => array(
            //         "step" => 2,
            //         "key" => "nomer",
            //         "label" => "PO number",
            //     ),
            //     "ids_his_3" => array(
            //         "step" => 3,
            //         "key" => "nomer",
            //         "label" => "PRE GRN number",
            //     ),
            //     "nomer" => "distribusi number",
            //     "item_fields" => "isi",
            //     "free_produk" => "promo supplier",
            //     "harga" => "amount",
            //     "disc" => "discount",
            //     "ppn" => "ppn",
            //     "nett" => "total amount",
            //     "description_main_followup" => "VENDOR'S <br>INVOICE REFERRAL",
            //     "oleh_nama" => "person",
            //     "keterangan" => "keterangan",
            //     "print_label" => "tool",
            // ),
            // 5 => array(
            //     "no" => "no",
            //     // "jenis_label" => "activity",
            //     "dtime" => "date",
            //     "suppliers_nama" => "supplier",
            //     "nomer_top" => "PRE PO number",
            //     "ids_his" => array(
            //         "step" => 2,
            //         "key" => "nomer",
            //         "label" => "PO number",
            //     ),
            //     "nomer_pre_grn" => array(
            //         "step" => 3,
            //         "key" => "nomer",
            //         "label" => "PRE GRN number",
            //     ),
            //     "nomer_grn" => array(
            //         "step" => 4,
            //         "key" => "nomer",
            //         "label" => "GRN number",
            //     ),
            //     "nomer" => "Realisasi ppn<br>number",
            //     "item_fields" => "isi",
            //     "description_main_followup" => "INV<br>from vendor",
            //     "oleh_nama" => "person",
            //     "eFaktur" => "e-faktur",
            //     "dateFaktur" => "e-faktur date",
            //     "ppn" => "ppn",
            //     "ppn_realisasi" => "ppn<br>realisasi",
            //     "selisih_ppn_realisasi" => "selisih ppn<br>realisasi",
            //
            //     //                "transaksi_nilai" => "amount",
            //     //                "harga" => "amount",
            //     //                "disc" => "discount",
            //     //                "ppn" => "ppn",
            //     //                "nett" => "total amount",
            //
            //     "keterangan" => "keterangan",
            //     "print_label" => "tool",
            // ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "current_stok" => "stock<BR>available",
                "intransit_stok" => "stock<BR>intransit",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            3 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldRef" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_order" => "Order Qty",
                "produk_sent" => "Qty Sent",
                "current_stok" => "stock<BR>available",
                "intransit_stok" => "stock<BR>intransit",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "jenis" => "jenis",
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            //            "stok" => "stock<BR>after",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
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
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
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
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            //            "produk_order" => "produk_order",
            //            "produk_sent" => "produk_sent",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                //                "jml",
            ),
            3 => array(
                //            "harga",
                //            "ppn",
                //                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
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

        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),

        "connectTo" => "585",
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),
        "pairMakers" => array(
            1 => array(
                //                "stokProduk" => array(
                //                    "helperName" => "he_cek_stock_produk_locker",
                //                    "functionName" => "cekStockProdukLocker",
                //                    "params" => array(
                //                        "cabang_id" => "placeID",
                //                        "gudang_id" => "gudangID",
                //                        "jenis" => ".produk",
                //                        "state" => ".active",
                //                    ),
                //                    "gate" => "items",
                //                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                    ),
                    "gate" => "items",
                ),
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
                        "jenis",
                    ),
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        //                        "jenis" => ".produk",
                        //                        "state" => ".active",
                    ),
                    //                    "kolom" => array(
                    ////                        "no_part",
                    //                    ),
                    "gate" => "items",
                ),
                "stokIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                    ),
                    "gate" => "items",
                ),
                "stokAktive" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
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
        ),
        "pairInjectors" => array(
            1 => array(
                //                "stokProduk" => array(
                //                    "items" => array(
                //                        "targetKey" => "id",
                //                        "targetColumn" => "stok",
                //                    ),
                //                ),
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "intransit_stok",
                    ),
                ),
                "stokIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_intransit",
                    ),
                ),
                "stokAktive" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_available",
                    ),
                ),
            ),
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "cabang_nama" => "sender",
            "cabang2_nama" => "recipient",
            //            "suppliers_nama" =>"vendor",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "qty",
            //            "harga" => "harga beli",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "583re",
                "label" => "EDIT distribution request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "583rrj",
                "label" => "REJECT distribution authorization",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "583scrj",
                "label" => "REJECT scan qr/barcode",
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
        //----
        "shortRequestFields2" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "pihakTipeName" => "tipe penjualan",
            "marketplaceName" => "marketplace",
            "jenis_master" => "referensi jenis",
            "nomer_top" => "SO number",
            // sumber dari kolom id_his
            "nomer_soa" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "SOA number",
            ),
            "item_fields" => "item",
            //            "nomer_prepl" => array(
            //                "step" => 3,
            //                "key" => "nomer",
            //                "label" => "PRE-PL number",
            //            ),
            //            "nomer_pl" => array(
            //                "step" => 4,
            //                "key" => "nomer",
            //                "label" => "PL number",
            //            ),
            //            "nomer_inv" => array(
            //                "step" => 5,
            //                "key" => "nomer",
            //                "label" => "INV number",
            //            ),
            "seller_nama" => array(
                "step" => 1,
                "key" => "olehName",
                "label" => "sales admin",
            ),
            "oleh_nama" => "person",
            "salesman_nama" => "salesman",
            //            "jual" => "bruto",
            //            "disc" => "diskon tambahan",
            //            "ppn" => "ppn",
            //            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            // "shippingMethod__label" => "dikirim/diambil sendiri",
            "shippingMethod__label" => "status", // permintaan client
            "gudang_status_jenis" => "dikirim dari",
            "pengirim_nama" => "nama pengirim",
            //            "next_pic" => "Next step otorisator",

        ),
        "shortRequestFields2Config" => array(
            "enabled" => true,
            "filter" => array(
                //                "transaksi.jenis=5822so",
                "transaksi.jenis in ('5822so','5823so')",
                "transaksi.gudang_status_jenis=pusat",
                "transaksi_data.produk_ord_kurang>0",
            ),
            "linkSwapper" => "Create/swapFrom/",
            "lockerTransaksi" => true,
            "labelWarning" => "* Transaksi sedang diedit di cabang.<br>Proses tidak bisa dilanjutkan.",
            "labelHeader" => "JOBLIST YANG PERLU DIKIRIM DARI GUDANG DC/PUSAT KE KONSUMEN ",
            "referenceValidator" => array(
                "key" => "requestReferenceID",
                "labelWarning" => "Sesi Pengiriman baru belum bisa dilakukan karena sesi pengiriman lama masih ada (anda menghapus per-item). Silahkan klik tombol refresh disini.",

            ),
        ),
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "customerID" => "pihakID",
            "customerName" => "pihakName",
            "salesmanID" => "pihakMain2ID",
            "salesmanName" => "pihakMain2Name",
            "pihakID" => "placeID",
            "pihakName" => "placeName",
            "pihakMain2ID" => "pihakMain2ID",
            "pihakMain2Name" => "pihakMain2Name",
            "pihakMainID" => "pihakMainID",
            "pihakMainName" => "pihakMainName",
            "pihakMainJenis" => "pihakMainJenis",
        ),
        "shopingCartReload" => true,
        "shopingCartReferenceValidator" => array(
            "enabled" => true,
            "key" => "requestReferenceID",
            "allowIncrement" => false,
            "fields" => "disabled",
        ),
        "shopingCartStockValidate" => array(
            1 => true,
        ),
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        //----
        "shortDirectFields2" => array(
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "customers_nama" => "konsumen",
            //            "nomer_top" => "SO number",
            // sumber dari kolom id_his
            "nomer_poa" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "nomer PO",
            ),
            "reference_nomer" => "nomer SO",

            //            "seller_nama" => array(
            //                "step" => 1,
            //                "key" => "olehName",
            //                "label" => "sales admin",
            //            ),
            "oleh_nama" => "person",
            //            "salesman_nama" => "salesman",
            "item_fields" => "isi",
            //            "paymentMethod__label" => "payment method",
            //            "shippingMethod__label" => "dikirim/diambil sendiri",
            //            "pengirim_nama" => "nama pengirim",
        ),
        "shortDirectFields2Config" => array(
            "enabled" => true,
            "filter" => array(
                "transaksi.jenis=467",
                "transaksi.reference_terima_barang=1",
                //                "transaksi.gudang_status_jenis=pusat",
                "transaksi_data.produk_ord_kurang>0",
            ),
            "linkSwapper" => "Create/swapDirectFrom/",
        ),
        "itemDirectSwapper" => "_processSelectProduct/multiSelect",
        "directSwappedKeys" => array(
            "customerID" => "reference_customers_id",// konsumen pihakID
            "customerName" => "reference_customers_nama",// konsumen pihakName
            "salesmanID" => "reference_salesman_id",// salesman
            "salesmanName" => "reference_salesman_id",// salesman
            "pihakID" => "reference_cabang_id",// cabang tujuan distribusi placeID
            "pihakName" => "reference_cabang_nama",// cabang tujuan distribusi placeName

            "pihakMain2ID" => "reference_salesman_id",// salesman
            "pihakMain2Name" => "reference_salesman_id",// salesman
            "pihakMainID" => "reference_gudang_status_id",// dikirim dari
            "pihakMainName" => "reference_gudang_status_nama",// dikirim dari
            "pihakMainJenis" => "reference_gudang_status_jenis",// dikirim dari
            "terima_barang" => "terima_barang",// dikirim dari
        ),
        "scanQrCart" => array(
            "deleteSerial" => true,
        ),
        //----
        "deliveryParsial" => array(
            2 => array(
                "enabled" => false,
            ),
        ),
        //----
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",// barang reguler
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",

                "scanCheckerEnabled" => true,
                "scanCheckerLabel" => "Pengisian serial atau jumlah {produk_nama} belum lengkap. Silahkan diperiksa lagi.",
            ),
        ),
        "autoOtorisasiSesiValidate" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "selectorValidator" => array(
            1 => array(
                array(
                    "reference" => "requestReferenceID",
                    "keys" => array(
                        "pihakMainID",
                        "pihakMainName",
                        "pihakMainJenis",
                    ),
                    "label" => "Syarat Barang dikirim dari Gudang Pusat/DC tidak lengkap. Silahkan hubungi cabang untuk koreksi atau hubungi admin.",
                ),
                //                array(
                //                    "keys" => array(
                //                        "pihakMain2ID",
                //                        "pihakMain2Name",
                //                    ),
                //                    "label" => "Anda harus pilih Salesman untuk input penjualan ini. Silahkan dikoreksi lagi.",
                //                ),
            ),
            2 => array(
                array(
                    "reference" => "requestReferenceID",
                    "keys" => array(
                        "pihakMainID",
                        "pihakMainName",
                        "pihakMainJenis",
                    ),
                    "label" => "Syarat Barang dikirim dari Gudang Pusat/DC tidak lengkap. Silahkan hubungi cabang untuk koreksi atau hubungi admin.",
                ),
                //                array(
                //                    "keys" => array(
                //                        "pihakMain2ID",
                //                        "pihakMain2Name",
                //                    ),
                //                    "label" => "Anda harus pilih Salesman untuk input penjualan ini. Silahkan dikoreksi lagi.",
                //                ),
            ),
        ),
        "showInformasiStok" => true,
        //----
        //----
        "catatan" => array(
            1 => array(
                "reject" => array(
                    0 => "Transaksi distribusi ini akan mundur 1 langkah dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan mundur 1 langkah dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
                "rejectall" => array(
                    0 => "Transaksi distribusi ini akan dimatikan sampai dengan request distribusinya dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan dimatikan sampai dengan request distribusinya dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
            ),
            2 => array(
                "reject" => array(
                    0 => "Transaksi distribusi ini akan mundur 1 langkah dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan mundur 1 langkah dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
                "rejectall" => array(
                    0 => "Transaksi distribusi ini akan dimatikan sampai dengan request distribusinya dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan dimatikan sampai dengan request distribusinya dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
            ),
        ),
        "rejectOption" => array(
            1 => array(
                "reject" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
                "rejectall" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
            ),
            2 => array(
                //                "reject" => array(
                //                    "checkbox_link" => "",
                //                    "label" => "Batalkan Sales Order <strong>nomer_sales_order</strong>",
                //                ),
                "rejectall" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
            ),
        ),
        //----
        "produkUnitPartReloadData" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartCheckerItems" => true,
        /**
         * untuk pair dari so yang akan difollowup distribusi
         */
        "followUpLiveEditField2" => array(
            "shippingMethod__label" => array(
                "element" => "shippingMethod",
                "input" => "option",
                "key" => "shippingMethod",
                "mdlName" => "MdlShippingMethodStatic",
                "selector" => "_followupLiveEdit/patchReg/shippingMethod",
            ),
            "gudang_status_jenis" => array(
                "input" => "option",
                "keyInjectMain" => array(
                    "pihakMainID" => "id",
                    "pihakMainName" => "nama",
                    "pihakMainJenis" => "nama",
                ),
                "keyUpdateTransaksi" => array(
                    "gudang_status_id" => "id",
                    "gudang_status_nama" => 'nama',
                    "gudang_status_jenis" => 'jenis',
                ),
                "key" => "gudang_status_id",
                "element" => "gudangStatusDetails",
                "mdlName" => "MdlGudangStatus",
                "selector" => "_followupLiveEdit/patchReg/gudangStatusDetails",
            ),
        ),
        "gateReplacerItems" => array(
            2 => array(
                "enabled" => true,
                "kolom" => array(
                    "hpp",
                    "hpp_riil",
                ),
            ),
            3 => array(
                "enabled" => true,
                "kolom" => array(
                    "hpp",
                    "hpp_riil",
                ),
            ),
            4 => array(
                "enabled" => true,
                "kolom" => array(
                    "hpp",
                    "hpp_riil",
                ),
            ),
        ),
        //-----
        "copyKolomAutoDistribusi" => array(
            "requestReferenceSoID" => "requestReferenceSoID",
            "requestReferenceSoNomer" => "requestReferenceSoNomer",
            "requestReferenceSoIDTop" => "requestReferenceSoIDTop",
            "requestReferenceSoNomerTop" => "requestReferenceSoNomerTop",
            "requestReferenceSoJenis" => "requestReferenceSoJenis",
            "requestReferenceSoJenisTop" => "requestReferenceSoJenisTop",
            "requestReferenceSoJenisMaster" => "requestReferenceSoJenisMaster",
            "requestReferenceCustomerID" => "requestReferenceCustomerID",
            "requestReferenceCustomerName" => "requestReferenceCustomerName",
            "requestReferenceCabangID" => "requestReferenceCabangID",
            "requestReferenceCabangName" => "requestReferenceCabangName",
            "requestReferenceGudangID" => "requestReferenceGudangID",
            "requestReferenceGudangName" => "requestReferenceGudangName",
        ),
        "copyKolomItemsNonSerial" => array(
            2 => array(
                "enabled" => true,
                "kolom" => array(
                    "jml_scan" => "jml",
                ),
            ),
        ),
        //-----
        "allowedPurchaseOrder" => true,// false: untuk mematikan tombol pre-po pada daftar joblist distribusi
        "allowedAllSalesOrder" => array(
            "enabled" => false,// tombol tampil jika -> false: sales order full produk, true: sales order dengan paket/jasa.
            "label" => "Joblist terdapat Paket atau Jasa (pembelian ke supplier tidak bisa dilakukan).",
        ),

        "shoppingCartFieldsLink" => array(
            1 => array(
                "jml_intransit" => array(
                    "cabang_id" => "placeID",
                    "gudang_id" => "gudangID",
                ),

            ),
        ),

    ),
    "585" => array(
        "icon" => "fa fa-ship",
        "label" => "stock reception",
        "place" => "branch",
        "counter_global" => "_company_cabangID_modul_stepCode", //=> "key globla counter",
        "counter_global_part" => array(
            'jenis',
            "dtime",
            'cabangID',
            // 'cabang2_id',
            'cabang2ID',
            '_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID',
        ),
        "steps" => array(
            1 => array(
                "label" => "stock initiation",
                "actionLabel" => "init reception",
                "source" => "",
                "target" => "585r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",

            ),
            2 => array(
                "runCoreAkunting" => true,
                "label" => "stock reception",
                //                "label" => "distribusi",
                "actionLabel" => "receive",
                "source" => "585r",
                "target" => "585",
                "userGroup" => "o_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
                // "allowScaner" => true,
                "allowScaner" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
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
            "satuan",
            "jumlah",
        ),
        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "item_fields" => "detil item",
            "oleh_nama" => "person",

            "requestReferenceNomer" => "referensi SO",
            "customerName" => "konsumen",
            "salesmanName" => "salesman",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "print_label" => "print",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "Nomer",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
                "print_label" => "print",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "jenis" => "jenis",
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
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartSubDetailFields" => array(
            1 => array(
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
        ),
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
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "settlementHistoryFields" => array(
            "fulldate" => "time",
            "nomer" => "receipt number",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "revertException" => true,
        "pairMakers" => array(
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
        ),
        "previewCtr" => "Create",
        "scanQrValidator" => array(
            "enabled" => true,
            "gateSource" => "items3_sum",
            "gateTarget" => "items5_sum",
        ),
        "scanQrCart" => array(
            "deleteSerial" => false,
            "label" => "Langkah anda adalah menerima distribusi dari DC/Pusat. Anda tidak diijinkan menghapus nomer serial yang sudah terdaftar.",
        ),
        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target warehouse",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
    ),


    // config return distribusi by nota distribusi
    "983" => array(
        "icon" => "fa fa-truck",
        "label" => "stock return (receipt number)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "return request",
                "actionLabel" => "request return",
                "source" => "",
                "target" => "983r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "distribution (scan qr/barcode)",
                "actionLabel" => "save scan qr/barcode",
                "source" => "983r",
                "target" => "983sc",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label" => "return distribusi",
                "actionLabel" => "approve request",
                "source" => "983sc",
                "target" => "983",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNotaItem",
        "selectorSrcModel" => "MdlNotaItem",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //            ),
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            "returned=.0",
            "jenis=.585",
            "cabang_id=placeID",
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
        "editHandlerMethod" => "edit",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "id=.-1",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
            "description" => "notes/catatan",
            "description_special" => "notes/catatan<br>tambahan",
            "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "583r" => "request number",
            "583" => "approval number",
            //            "585r" => "request number",
            "585" => "receipt number",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "keterangan",
                ),
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "produk_nama",
            "kode" => "produk_kode",
            "label" => "produk_label",
            "satuan" => "satuan",
            "stok" => "stock",

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
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
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
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang cabang",
                //                "mdlName" => "MdlGudang",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang dc",
                //                "mdlName" => "MdlGudang",
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

        "connectTo" => "985",
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "983re",
                "label" => "EDIT stock return (receipt number)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "983rrj",
                "label" => "REJECT stock return (receipt number)",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "983scrj",
                "label" => "REJECT scan qr/barcode",
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
        "shopingCartReload" => true,
        "showInformasiStok" => true,
        //----
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",// barang reguler
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",

                "scanCheckerEnabled" => true,
                "scanCheckerLabel" => "Pengisian serial atau jumlah {produk_nama} belum lengkap. Silahkan diperiksa lagi.",
            ),
        ),
        //----
        "produkUnitPartReloadData" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartCheckerItems" => true,
    ),
    "985" => array(
        "icon" => "fa fa-ship",
        "label" => "stock reception (stock return receipt number)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "stock initiation",
                "actionLabel" => "init reception",
                "source" => "",
                "target" => "985r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "return stock reception",
                //                "label" => "return distribusi",
                "actionLabel" => "receive",
                "source" => "985r",
                "target" => "985",
                "userGroup" => "c_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
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
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "description" => "notes/catatan",
            "description_special" => "notes/catatan<br>tambahan",
            "keterangan" => "keterangan",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "print_label" => "print",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "GRN Number",
                "oleh_nama" => "person",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "print_label" => "print",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
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

        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => "item name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => "item name",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
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
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "revertException" => true,
        "previewCtr" => "Create",
    ),
    // config return distribusi by produk
    "1983" => array(
        "icon" => "fa fa-truck",
        "label" => "stock return (by product)",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request return stok ke dc",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "1983r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "autoSelectTarget" => true,
            ),
            2 => array(
                "label" => "return stok ke dc (scan qr/barcode)",
                "actionLabel" => "simpan scan qr/barcode",
                "source" => "1983r",
                "target" => "1983sc",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "return stok ke dc",
                "actionLabel" => "approve return stok ke dc",
                "source" => "1983sc",
                "target" => "1983",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "autoNextStep" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //            ),
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
            "jenis" => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters" => array(
            //            "returned=.0",
            //            "jenis=.585",
            //            "cabang_id=placeID",
            //            "cabang_id=placeID",
            //            "gudang_id=gudangID",
            //            "state=.active",
            //            "jumlah>.0",
            //            "produk.jenis=.item",
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
            "stock_locker.state=.active",
            "stock_locker.jumlah>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            //            "id" => "id",
            //            "nama" => "nomer",
            "id" => "produk_id",
            "nama" => "nama",
            "produk_kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "label",
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
            //            "keterangan", "kode", "satuan", "jumlah",
            //            "nama", "kode", "satuan", "jumlah"
        ),
        //        "selectorProcessor" => "_processSelectNotaItem/select",
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "id=.-1",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            "next_pic" => "Next step otorisator",
            "description" => "notes/catatan",
            "description_special" => "notes/catatan<br>tambahan",
            "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "cabang pengirim",
            "cabang_nama" => "cabang penerima",
            "1983r" => "request number",
            "1983" => "approval number",
            //            "585r" => "request number",
            "1985" => "receipt number",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_kode" => "product code",
                "current_stok" => "stock<BR>available",
                "intransit_stok" => "stock<BR>intransit",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "keterangan" => "part number",
                "produk_kode" => "product code",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",

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
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "sub_qty_indoor" => "sub_qty_indoor",
            "sub_qty_outdoor" => "sub_qty_outdoor",
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
            3 => array(//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        "shoppingCartSubDetailFields" => array(
            2 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
            3 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "referenceFields" => array(
            "referenceID" => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod" => "pembayaran",
        ),
        "pairMakers" => array(
            1 => array(
                //                "stokProduk" => array(
                //                    "helperName" => "he_cek_stock_produk_locker",
                //                    "functionName" => "cekStockProdukLocker",
                //                    "params" => array(
                //                        "cabang_id" => "placeID",
                //                        "gudang_id" => "gudangID",
                //                        "jenis" => ".produk",
                //                        "state" => ".active",
                //                    ),
                //                    "gate" => "items",
                //                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                    ),
                    "gate" => "items",
                ),
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
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        //                        "jenis" => ".produk",
                        //                        "state" => ".active",
                    ),
                    //                    "kolom" => array(
                    ////                        "no_part",
                    //                    ),
                    "gate" => "items",
                ),
                "stokIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                    ),
                    "gate" => "items",
                ),
                "stokAktive" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
                ),
            ),
        ),
        "pairInjectors" => array(
            1 => array(
                //                "stokProduk" => array(
                //                    "items" => array(
                //                        "targetKey" => "id",
                //                        "targetColumn" => "stok",
                //                    ),
                //                ),
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "intransit_stok",
                    ),
                ),
                "stokIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_intransit",
                    ),
                ),
                "stokAktive" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_available",
                    ),
                ),
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang cabang",
                //                "mdlName" => "MdlGudang",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang dc",
                //                "mdlName" => "MdlGudang",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "allowedMainEdit" => array("1"),
        "connectTo" => "1985",
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1983re",
                "label" => "EDIT stock return request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1983rrj",
                "label" => "REJECT stock return request",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1983scrj",
                "label" => "REJECT scan qr/barcode",
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
        "shopingCartReload" => true,
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        "showInformasiStok" => true,
        //----
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",// barang reguler
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",

                "scanCheckerEnabled" => true,
                "scanCheckerLabel" => "Pengisian serial atau jumlah {produk_nama} belum lengkap. Silahkan diperiksa lagi.",
            ),
        ),
        //----
        "produkUnitPartReloadData" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartCheckerItems" => true,

        "shoppingCartFieldsLink" => array(
            1 => array(
                "jml_intransit" => array(
                    "cabang_id" => "placeID",
                    "gudang_id" => "gudangID",
                ),

            ),
        ),
    ),
    "1985" => array(
        "icon" => "fa fa-ship",
        "label" => "Penerimaan Return Stok",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "Pre Penerimaan Return Stok",
                "label_nota" => "Pre Penerimaan Return Stok",
                "actionLabel" => "Pre Penerimaan Return Stok",
                "source" => "",
                "target" => "1985r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "Penerimaan Return Stok",
                "label_nota" => "Penerimaan Return Stok",
                "actionLabel" => "terima",
                "source" => "1985r",
                "target" => "1985",
                "userGroup" => "c_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
                "allowScaner" => true,
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
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
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "cabang pengirim",
            //            "cabang_nama" => "cabang penerima",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "description" => "notes/catatan",
            "description_special" => "notes/catatan<br>tambahan",
            "keterangan" => "keterangan",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "Nomer",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "print_label" => "print",
                "keterangan" => "keterangan",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "Nomer",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
                "print_label" => "print",
                "print_barcode_pembelian" => array(
                    "label" => "print Serial",
                    "key" => array(
                        "print_barcode_return",
                        "print_barcode_return_2",
                    ),
                ),
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
                "print_barcode_return" => "id",
                "print_barcode_return_2" => "id",
            ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),

        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),


        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "Descriptions",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
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
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
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
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartSubDetailFields" => array(
            1 => array(
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

        ),

        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "revertException" => true,
        "previewCtr" => "Create",
        "scanQrValidator" => array(
            "enabled" => true,
            "gateSource" => "items3_sum",
            "gateTarget" => "items5_sum",
        ),
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
    ),

    //config repack produk komposit
    "773" => array(
        "icon" => "fa fa-cube",
        "label" => "product komposit/paket/bundling",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request produk paket/bundling",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "773r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "produk paket/bundling (scan qr/barcode)",
                "actionLabel" => "save scan qr/barcode",
                "source" => "773r",
                "target" => "773sc",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "packing produk paket/bundling",
                "actionLabel" => "approve",
                "source" => "773sc",
                "target" => "773",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlProdukKomposit",
        "selectorSrcModel" => "MdlProdukKomposit",
        "selectorSrcModelPaired" => "MdlProdukKompositKomposisi",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            //            "enabled" => false,
            //            "mdlName" => "MdlLockerStock",
        ),
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
            "kode",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProductKomposit/select",
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
            "dtime" => "date",
            "cabang_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
            "next_pic" => "Next step otorisator",
            "description" => "notes/catatan",
            "description_special" => "notes/catatan<br>tambahan",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "gudang_nama" => "warehouse",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "recipient",
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "gudang_nama" => "warehouse",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "recipient",
                "nomer_top" => "request number",
                "nomer" => "scan number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "gudang_nama" => "warehouse",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "dtime" => "date",
                "cabang_nama" => "recipient",
                "nomer_top" => "request number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "scan number",
                ),
                "nomer" => "approval number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                "gudang_nama" => "warehouse",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
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
                "review_details" => "id",
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
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item name",
                "produk_kode" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item source name",
                "stok" => "stock",
                "jml" => "qty",
                "sisa" => "sisa",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama" => "item source name",
                "stok" => "stock",
                "jml" => "qty",
                // "sisa" => "sisa",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama" => "item source name",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "jenis" => "jenis",
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            //            "stok" => "stock<BR>after",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
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
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
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
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(//                "jml",
            ),
            3 => array(//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
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
        "shoppingCartPairedItem" => array(
            "enabled" => false,
            "mdlName" => "MdlProduk",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array("id<>id"),
            "targetGateName" => "items2_sum",
        ),
        "componentsAss" => array(
            "model" => "MdlProdukKompositKomposisi",
            "modelSrc" => "MdlProduk",
        ),
        "followupItemEditable" => "_followupLiveEdit/updateItemFieldKomposit/",
        "followupItemRemove" => "_followupLiveEdit/removeItemKomposit/",
        "pairMakers" => array(
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items2_sum",
                ),
                "priceProduk" => array(
                    "helperName" => "he_cek_price_produk",
                    "functionName" => "cekPriceProduk",
                    "params" => array(
                        "cabang_id" => ".-1",
                    ),
                ),
            ),
        ),
        "pairInjectors" => array(
            2 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "priceProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "harga_last",
                    ),
                ),
            ),
        ),
        "kompositValidate" => array(
            "enabled" => true,
            "mdlName" => "MdlProdukKompositKomposisi",
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "773re",
                "label" => "EDIT repack produk komposit request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "773rrj",
                "label" => "REJECT repack produk komposit request",
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
    ),

    // PROJECT
    "5833" => array(
        "icon" => "fa fa-truck",
        "label" => "distribusi ke cabang project",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request distribusi ke project",
                "actionLabel" => "distribusi ke project",
                "source" => "",
                "target" => "5833r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "otorisasi distribusi ke project",
                //                "label" => "authorization",
                "actionLabel" => "approve distribusi ke project",
                "source" => "5833r",
                "target" => "5833",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                //                "allowEdit" => false,
            ),
        ),
        //        "template" => "template/transaksi.html",
        "template" => "template/transaksi_project.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("jual"),
            "key_label" => array(
                "jual" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
            //            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "silahkan diisi dengan item ID, nama, kode...",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
            "id",
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "jenis=.project",
        ),
        "pihakProcessor" => "_processPihak/select",

        // PIHAK IV
        "pihakModelProjek" => "MdlProdukProject",
        "pihakProjekCaller" => "_selectorPihak/selectPihakProjek",
        "pihakProjekLabel" => "produk project",
        "pihakProjekViewedFields" => array(
            "kode",
            "nama",
            "transaksi_no_app",
        ),
        "pihakProjekFilters" => array(
            "status=.1",
            "trash=.0",
            //            "customer_id=customerProjek",
            "transaksi_id_app>.0",
            "closing_status=.0",
        ),
        "pihakProjekProcessor" => "_processPihak/selectProjek",
        //        "pihakExternNota" => true,


        "shortHistoryFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "detil item",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "583r" => "request number",
            "583" => "approval number",
            //            "585r" => "request number",
            "585" => "receipt number",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortStatusFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "code",
            "produk_nama" => "product",
            "produk_ord_jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "current_stok" => "stock<BR>available",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            //            "stok" => "stock<BR>after",
            "current_stok" => "current<BR>stock",
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
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),

        "receiptElements" => array(
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
            "produkProjek" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Produk Project",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array("id=pihakProjekID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Nama",
                    "transaksi_id_app" => "SO ID",
                    "transaksi_no_app" => "SO Number",
                ),
                "editPoints" => array(),
                "noValidate" => true,
            ),
        ),

        "connectTo" => "5855",
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
                ),
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
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        //                        "jenis" => ".produk",
                        //                        "state" => ".active",
                    ),
                    //                    "kolom" => array(
                    ////                        "no_part",
                    //                    ),
                    "gate" => "items",
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
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
                    ),
                ),
            ),
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "cabang_nama" => "sender",
            "cabang2_nama" => "recipient",
            //            "suppliers_nama" =>"vendor",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),

        "shoppingCartRowValidators" => array(
            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5833re",
                "label" => "EDIT request distribusi ke project",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5833rrj",
                "label" => "REJECT otorisasi distribusi ke project",
            ),
        ),
    ),
    "5855" => array(
        "icon" => "fa fa-ship",
        "label" => "penerimaan bahan baku di project",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "distribusi ke project",
                "actionLabel" => "distribusi ke project",
                "source" => "",
                "target" => "5855r",
                "userGroup" => "sys",
                "stateLabel" => "pending acceptance",
                "stateColor" => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label" => "penerimaan bahan baku di project",
                "actionLabel" => "terima bahan baku",
                "source" => "5855r",
                "target" => "5855",
                "userGroup" => "o_gudang",
                "stateLabel" => "stock received",
                "stateColor" => "#009900",
                "stateCaption" => "received by",
            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
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
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProductStock/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            // "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "item_fields" => "detil item",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "code",
            "produk_nama" => "product",
            "produk_ord_jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "historyFields" => array(
            1 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "print_label" => "print",
            ),
            2 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "sender",
                // "nomer_top" => "SO number",
                "nomer" => "GRN Number",
                "oleh_nama" => "person",
                "print_label" => "print",
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
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),

        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "item name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "item name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
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
        "shoppingCartNumFields" => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "branch",
        ),
        "settlementHistoryFields" => array(
            "fulldate" => "time",
            "nomer" => "receipt number",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "revertException" => "true",
        "pairMakers" => array(
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
        ),
        "previewCtr" => "Create",
    ),
    //penjualan ke diri sendiri
    "5844" => array(
        "icon" => "fa fa-truck",
        "label" => "penjualan ke diri sendiri",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request",
                "actionLabel" => "request",
                "source" => "",
                "target" => "5844r",
                "userGroup" => "c_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "approval",
                //                "label" => "authorization",
                "actionLabel" => "approve",
                "source" => "5844r",
                "target" => "5844ro",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                //                "allowEdit" => false,
                "autoNextStep" => true,
            ),
            3 => array(
                "label" => "(scan qr/barcode)",
                //                "label" => "authorization",
                "actionLabel" => "save scan qr/barcode",
                "source" => "5844ro",
                "target" => "5844",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowScaner" => true,
                //                "allowEdit" => false,
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("hpp"),
            //            "key_label" => array(
            //                "hpp" => "harga",
            //            ),
            //            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.gudang_id=gudangID",
            //            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "silahkan diisi dengan item ID, nama, kode...",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
            "id",
            "keterangan",
            "kode",
            "kategori_nama",
            "sub_kategori_nama",
            "satuan",
            "jumlah",
        ),
        "selectorLinkMutasi" => array(
            "enabled" => true,
            "link" => "Ledger/viewMoveDetails/RekeningPembantuProduk/1010030030/",//persediaan produk
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFieldsCek" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "pihakPembebanan__label" => "cabang tujuan",
            "pihakTargetAset__label" => "penggunaan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            //            "requestReferenceNomer" => "referensi SO",
            //            "customerName" => "konsumen",
            //            "salesmanName" => "salesman",
            //
            //            "pihakMainID" => "status gudang id",
            //            "pihakMainName" => "status gudang nama",
            //            "pihakMainJenis" => "status gudang jenis",

            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortHistoryFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
            //            "reference_jenis_master" => "referensi jenis",
            //            "requestReferenceNomer" => "referensi SO",
            //            "customerName" => "konsumen",
            //            "salesmanName" => "salesman",
            "pihakTargetAset__nama" => "pemakaian",
            "asetKategory__nama" => "kategori pemakaian",
            "pihakPembebanan__nama" => "cabang pembebanan",
            "next_pic" => "Next step otorisator",
            "description" => "notes/catatan",
            "description_special" => "notes/catatan<br>tambahan",
            // "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "5844r" => "request number",
            "5844ro" => "approval number",
            //            "585r" => "request number",
            "5844" => "receipt number",
            "item_fields" => "isi",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortStatusFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "status_next" => "status",
            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "Product Name",
                "addKey" => "keterangan",
            ),
            "produk_kode" => "product code",
            "no_part" => "part number",
            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "historyFields" => array(
            1 => array(
                "no" => "no",
                //                "jenis_label" => "activity",
                "dtime" => "tanggal",
                // "suppliers_nama" => "supplier",
                "cabang2_nama" => "cabang tujuan",
                "nomer" => "no. pre distribusi",
                "item_fields" => "isi",
                // "free_produk" => "promo supplier",
                "oleh_nama" => "PIC",
                //                "transaksi_nilai" => "amount",
                "reference_jenis_master" => "referensi jenis",
                "requestReferenceNomer" => "referensi SO",
                "customerName" => "konsumen",
                "salesmanName" => "salesman",

                // "pihakMainID" => "status gudang id",
                "pihakMainName" => "status gudang nama",
                "pihakMainJenis" => "status gudang jenis",

                "pihakTargetAset__nama" => "pemakaian",
                "asetKategory__nama" => "kategori pemakaian",
                "pihakPembebanan__nama" => "cabang pembebanan",
                // "next_pic" => "Next step otorisator",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
                // "harga" => "amount",
                // "disc" => "discount",
                // // "nett1" => "sub amount",
                // "ppn" => "ppn",
                // "nett" => "total amount",
                // "keterangan" => "keterangan",
                // "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
                //                "jenis_label" => "activity",
                "dtime" => "tanggal",
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "no. PRE distribusi",
                "nomer" => "no. ACC distribusi",
                "item_fields" => "isi",
                // "free_produk" => "promo supplier",
                "oleh_nama" => "pic",
                "reference_jenis_master" => "referensi jenis",
                "requestReferenceNomer" => "referensi SO",
                "customerName" => "konsumen",
                "salesmanName" => "salesman",

                // "pihakMainID" => "status gudang id",
                "pihakMainName" => "status gudang nama",
                "pihakMainJenis" => "status gudang jenis",

                "pihakTargetAset__nama" => "pemakaian",
                "asetKategory__nama" => "kategori pemakaian",
                "pihakPembebanan__nama" => "cabang pembebanan",
                // "next_pic" => "Next step otorisator",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
            ),
            3 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "tanggal",
                // "suppliers_nama" => "supplier",
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "no. PRE distribusi",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "no. ACC distribusi",
                ),
                "nomer" => "no. distribusi",
                "item_fields" => "isi",
                // "free_produk" => "promo supplier",
                // "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "pic",
                //                "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",

                // "keterangan" => "keterangan",
                // "print_label" => "tool",

                "customerName" => "konsumen",
                "salesmanName" => "salesman",

                // "pihakMainID" => "status gudang id",
                "pihakMainName" => "status gudang nama",
                "pihakMainJenis" => "status gudang jenis",

                "pihakTargetAset__nama" => "pemakaian",
                "asetKategory__nama" => "kategori pemakaian",
                "pihakPembebanan__nama" => "cabang pembebanan",
                // "next_pic" => "Next step otorisator",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",

                //                "print_barcode_pembelian" => "print QR",
                // "print_barcode_pembelian" => array(
                //     "label" => "print Serial",
                //     "key" => array(
                //         "print_barcode_pembelian",
                //         "print_barcode_pembelian_2",
                //     ),
                // ),
            ),
            // 4 => array(
            //     "no" => "no",
            //     // "jenis_label" => "activity",
            //     "dtime" => "date",
            //     "suppliers_nama" => "supplier",
            //     "nomer_top" => "PRE distri number",
            //     "ids_his" => array(
            //         "step" => 2,
            //         "key" => "nomer",
            //         "label" => "PO number",
            //     ),
            //     "ids_his_3" => array(
            //         "step" => 3,
            //         "key" => "nomer",
            //         "label" => "PRE GRN number",
            //     ),
            //     "nomer" => "distribusi number",
            //     "item_fields" => "isi",
            //     "free_produk" => "promo supplier",
            //     "harga" => "amount",
            //     "disc" => "discount",
            //     "ppn" => "ppn",
            //     "nett" => "total amount",
            //     "description_main_followup" => "VENDOR'S <br>INVOICE REFERRAL",
            //     "oleh_nama" => "person",
            //     "keterangan" => "keterangan",
            //     "print_label" => "tool",
            // ),
            // 5 => array(
            //     "no" => "no",
            //     // "jenis_label" => "activity",
            //     "dtime" => "date",
            //     "suppliers_nama" => "supplier",
            //     "nomer_top" => "PRE PO number",
            //     "ids_his" => array(
            //         "step" => 2,
            //         "key" => "nomer",
            //         "label" => "PO number",
            //     ),
            //     "nomer_pre_grn" => array(
            //         "step" => 3,
            //         "key" => "nomer",
            //         "label" => "PRE GRN number",
            //     ),
            //     "nomer_grn" => array(
            //         "step" => 4,
            //         "key" => "nomer",
            //         "label" => "GRN number",
            //     ),
            //     "nomer" => "Realisasi ppn<br>number",
            //     "item_fields" => "isi",
            //     "description_main_followup" => "INV<br>from vendor",
            //     "oleh_nama" => "person",
            //     "eFaktur" => "e-faktur",
            //     "dateFaktur" => "e-faktur date",
            //     "ppn" => "ppn",
            //     "ppn_realisasi" => "ppn<br>realisasi",
            //     "selisih_ppn_realisasi" => "selisih ppn<br>realisasi",
            //
            //     //                "transaksi_nilai" => "amount",
            //     //                "harga" => "amount",
            //     //                "disc" => "discount",
            //     //                "ppn" => "ppn",
            //     //                "nett" => "total amount",
            //
            //     "keterangan" => "keterangan",
            //     "print_label" => "tool",
            // ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "current_stok" => "stock<BR>available",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
            2 => array(
                "id" => "pID",
                "nama" => "product name",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            //            "stok" => "stock<BR>after",
            "current_stok" => "current<BR>stock",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
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
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                //                                "hpp" => "harga perolehan",
                "hpp_average" => "harga perolehan",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                //                            "hpp",
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp_average",
            2 => "jml*hpp_average",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
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
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Amount",
                //                "disc" => "Disc",
                "ppn" => "VAT",
                "grandtotal" => "Grand Total",
            ),
            2 => array(
                "harga" => "Amount",
                //                "disc" => "Disc",
                //                "ppn" => "VAT",
                //                "grand_total" => "Grand Total",
            ),
        ),
        "receiptElements" => array(
            "pihakTargetAset" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Klasifikasi Aset & Biaya",
                "mdlName" => "MdlStaticAsetBiaya",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "cabang pembebanan",
                    "kode" => "kode cabang",
                ),
                "editPoints" => array(1,),
            ),

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
        ),
        "relativeElements" => array(
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
            "pihakTargetAset" => array(
                "1" => array(
                    "pihakPembebanan" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "penempatan aset",
                        "mdlName" => "MdlCabang",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "cabang pembebanan",
                            "kode" => "kode cabang",
                        ),
                        "editPoints" => array(1,),
                    ),
                    "asetKategory" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "kategori aset",
                        "mdlName" => "MdlFolderAset",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "kategori",

                            "coa_code" => "coa",
                        ),
                        "editPoints" => array(1,),

                    ),
                    "aset" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "kelompok aset",
                        "mdlName" => "MdlAsetBerwujud",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "tipe",
                            //                            "kode" => "kode cabang",
                        ),
                        "editPoints" => array(1,),
                    ),
                    "gudangPembebanan" => array(
                        "elementType" => "dataModel",
                        "inputType" => "hidden",
                        "label" => "target warehouse",
                        "mdlName" => "MdlGudangDefaultAndCenter",
                        "mdlFilter" => array("cabang_id=pihakPembebanan"),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "",
                        ),
                        "editPoints" => array(1, 2, 3),
                    ),

                ),
                "2" => array(
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
                    ),
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
                    "gudangPembebanan" => array(
                        "elementType" => "dataModel",
                        "inputType" => "hidden",
                        "label" => "gudang",
                        "mdlName" => "MdlGudangDefaultAndCenter",
                        "mdlFilter" => array("cabang_id=pihakPembebanan"),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "",
                        ),
                        "editPoints" => array(1, 2, 3),
                    ),
                ),
            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "ppn" => array(
                        "label" => "ppn",
                        "defaultValue" => "",
                        "maxValue" => "",
                        "minValue" => "",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "shopingCartDetailFields" => array(
            "2" => array(
                "fields" => array(
                    "nama" => "nama",
                    "label" => "label",
                    "merk" => "merk",
                    "serial_no" => "serial_no",
                    "kode" => "produk_kode",
                    //                    "deskripsi" => "deskripsi",
                    //                   "harga" =>"harga",
                ),
                "editable" => array(
                    //                    "label" => "label",
                    //                    "merk" => "merk",
                    //                    "serial_no" => "nomer seri",
                    //                    "kode" => "kode",
                    //                    "deskripsi" => "deskripsi",
                ),
                //                "gate" => "detail",
                "gate" => "detail",
            ),
        ),
        "shopingCartDetailFieldAset" => array(
            "pihakTargetAset" => array(
                "1" => true,
                "2" => false,
            ),
        ),
        "addDetailData" => array(
            2 => array("mdlName" => "MdlAsetDetail"),
        ),
        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),
        "pairMakers" => array(
            1 => array(
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                    ),
                    "gate" => "items",
                ),
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
                        "jenis",
                    ),
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        //                        "jenis" => ".produk",
                        //                        "state" => ".active",
                    ),
                    //                    "kolom" => array(
                    ////                        "no_part",
                    //                    ),
                    "gate" => "items",
                ),
                "hargaAverage" => array(
                    "helperName" => "he_cek_price_produk",
                    "functionName" => "cekAveragePriceProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items",
                ),
                "stokIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                    ),
                    "gate" => "items",
                ),
                "stokAktive" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".active",
                    ),
                    "gate" => "items",
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
        ),
        "pairInjectors" => array(
            1 => array(
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
                    ),
                ),
                "hargaAverage" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "hpp_average",
                    ),
                ),
                "stokIntransit" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_intransit",
                    ),
                ),
                "stokAktive" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "jml_available",
                    ),
                ),
            ),
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "cabang_nama" => "sender",
            "cabang2_nama" => "recipient",
            //            "suppliers_nama" =>"vendor",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),

        "shoppingCartRowValidators" => array(
            //            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5844re",
                "label" => "EDIT distribution request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5844rrj",
                "label" => "REJECT distribution authorization",
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
        //----
        "shortRequestFields2" => array(
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "pihakTipeName" => "tipe penjualan",
            "marketplaceName" => "marketplace",
            "jenis_master" => "referensi jenis",
            "nomer_top" => "SO number",
            // sumber dari kolom id_his
            "nomer_soa" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "SOA number",
            ),
            "item_fields" => "item",
            //            "nomer_prepl" => array(
            //                "step" => 3,
            //                "key" => "nomer",
            //                "label" => "PRE-PL number",
            //            ),
            //            "nomer_pl" => array(
            //                "step" => 4,
            //                "key" => "nomer",
            //                "label" => "PL number",
            //            ),
            //            "nomer_inv" => array(
            //                "step" => 5,
            //                "key" => "nomer",
            //                "label" => "INV number",
            //            ),
            "seller_nama" => array(
                "step" => 1,
                "key" => "olehName",
                "label" => "sales admin",
            ),
            "oleh_nama" => "person",
            "salesman_nama" => "salesman",
            //            "jual" => "bruto",
            //            "disc" => "diskon tambahan",
            //            "ppn" => "ppn",
            //            "grand_pembulatan" => "total amount",
            "paymentMethod__label" => "payment method",
            // "shippingMethod__label" => "dikirim/diambil sendiri",
            "shippingMethod__label" => "status", // permintaan client
            "gudang_status_jenis" => "dikirim dari",
            "pengirim_nama" => "nama pengirim",
            //            "next_pic" => "Next step otorisator",

        ),
        "shortRequestFields2Config" => array(
            "enabled" => true,
            "filter" => array(
                //                "transaksi.jenis=5822so",
                "transaksi.jenis in ('5822so','5823so')",
                "transaksi.gudang_status_jenis=pusat",
                "transaksi_data.produk_ord_kurang>0",
            ),
            "linkSwapper" => "Create/swapFrom/",
            "lockerTransaksi" => true,
            "labelWarning" => "* Transaksi sedang diedit di cabang.<br>Proses tidak bisa dilanjutkan.",
            "labelHeader" => "JOBLIST YANG PERLU DIKIRIM DARI GUDANG DC/PUSAT KE KONSUMEN ",
            "referenceValidator" => array(
                "key" => "requestReferenceID",
                "labelWarning" => "Sesi Pengiriman baru belum bisa dilakukan karena sesi pengiriman lama masih ada (anda menghapus per-item). Silahkan klik tombol refresh disini.",

            ),
        ),
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "swappedKeys" => array(
            "customerID" => "pihakID",
            "customerName" => "pihakName",
            "salesmanID" => "pihakMain2ID",
            "salesmanName" => "pihakMain2Name",
            "pihakID" => "placeID",
            "pihakName" => "placeName",
            "pihakMain2ID" => "pihakMain2ID",
            "pihakMain2Name" => "pihakMain2Name",
            "pihakMainID" => "pihakMainID",
            "pihakMainName" => "pihakMainName",
            "pihakMainJenis" => "pihakMainJenis",
        ),
        "shopingCartReload" => true,
        "shopingCartReferenceValidator" => array(
            "enabled" => true,
            "key" => "requestReferenceID",
            "allowIncrement" => false,
            "fields" => "disabled",
        ),
        "shopingCartStockValidate" => array(
            1 => true,
        ),
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        //----
        "shortDirectFields2" => array(
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "customers_nama" => "konsumen",
            //            "nomer_top" => "SO number",
            // sumber dari kolom id_his
            "nomer_poa" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "nomer PO",
            ),
            "reference_nomer" => "nomer SO",

            //            "seller_nama" => array(
            //                "step" => 1,
            //                "key" => "olehName",
            //                "label" => "sales admin",
            //            ),
            "oleh_nama" => "person",
            //            "salesman_nama" => "salesman",
            "item_fields" => "isi",
            //            "paymentMethod__label" => "payment method",
            //            "shippingMethod__label" => "dikirim/diambil sendiri",
            //            "pengirim_nama" => "nama pengirim",
        ),
        "shortDirectFields2Config" => array(
            "enabled" => true,
            "filter" => array(
                "transaksi.jenis=467",
                "transaksi.reference_terima_barang=1",
                //                "transaksi.gudang_status_jenis=pusat",
                "transaksi_data.produk_ord_kurang>0",
            ),
            "linkSwapper" => "Create/swapDirectFrom/",
        ),
        "itemDirectSwapper" => "_processSelectProduct/multiSelect",
        "directSwappedKeys" => array(
            "customerID" => "reference_customers_id",// konsumen pihakID
            "customerName" => "reference_customers_nama",// konsumen pihakName
            "salesmanID" => "reference_salesman_id",// salesman
            "salesmanName" => "reference_salesman_id",// salesman
            "pihakID" => "reference_cabang_id",// cabang tujuan distribusi placeID
            "pihakName" => "reference_cabang_nama",// cabang tujuan distribusi placeName

            "pihakMain2ID" => "reference_salesman_id",// salesman
            "pihakMain2Name" => "reference_salesman_id",// salesman
            "pihakMainID" => "reference_gudang_status_id",// dikirim dari
            "pihakMainName" => "reference_gudang_status_nama",// dikirim dari
            "pihakMainJenis" => "reference_gudang_status_jenis",// dikirim dari
            "terima_barang" => "terima_barang",// dikirim dari
        ),
        "scanQrCart" => array(
            "deleteSerial" => true,
        ),
        //----
        "deliveryParsial" => array(
            2 => array(
                "enabled" => false,
            ),
        ),
        //----
        "serialNumberValidator" => array(
            3 => array(
                "enabled" => true,
                "source" => "items2",// barang reguler
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",

                "scanCheckerEnabled" => true,
                "scanCheckerLabel" => "Pengisian serial atau jumlah {produk_nama} belum lengkap. Silahkan diperiksa lagi.",
            ),
        ),
        "autoOtorisasiSesiValidate" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "selectorValidator" => array(
            1 => array(
                array(
                    "reference" => "requestReferenceID",
                    "keys" => array(
                        "pihakMainID",
                        "pihakMainName",
                        "pihakMainJenis",
                    ),
                    "label" => "Syarat Barang dikirim dari Gudang Pusat/DC tidak lengkap. Silahkan hubungi cabang untuk koreksi atau hubungi admin.",
                ),
                //                array(
                //                    "keys" => array(
                //                        "pihakMain2ID",
                //                        "pihakMain2Name",
                //                    ),
                //                    "label" => "Anda harus pilih Salesman untuk input penjualan ini. Silahkan dikoreksi lagi.",
                //                ),
            ),
            2 => array(
                array(
                    "reference" => "requestReferenceID",
                    "keys" => array(
                        "pihakMainID",
                        "pihakMainName",
                        "pihakMainJenis",
                    ),
                    "label" => "Syarat Barang dikirim dari Gudang Pusat/DC tidak lengkap. Silahkan hubungi cabang untuk koreksi atau hubungi admin.",
                ),
                //                array(
                //                    "keys" => array(
                //                        "pihakMain2ID",
                //                        "pihakMain2Name",
                //                    ),
                //                    "label" => "Anda harus pilih Salesman untuk input penjualan ini. Silahkan dikoreksi lagi.",
                //                ),
            ),
        ),
        "showInformasiStok" => true,
        //----
        //----
        "catatan" => array(
            1 => array(
                "reject" => array(
                    0 => "Transaksi distribusi ini akan mundur 1 langkah dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan mundur 1 langkah dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
                "rejectall" => array(
                    0 => "Transaksi distribusi ini akan dimatikan sampai dengan request distribusinya dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan dimatikan sampai dengan request distribusinya dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
            ),
            2 => array(
                "reject" => array(
                    0 => "Transaksi distribusi ini akan mundur 1 langkah dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan mundur 1 langkah dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
                "rejectall" => array(
                    0 => "Transaksi distribusi ini akan dimatikan sampai dengan request distribusinya dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan dimatikan sampai dengan request distribusinya dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
            ),
        ),
        "rejectOption" => array(
            1 => array(
                "reject" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
                "rejectall" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
            ),
            2 => array(
                //                "reject" => array(
                //                    "checkbox_link" => "",
                //                    "label" => "Batalkan Sales Order <strong>nomer_sales_order</strong>",
                //                ),
                "rejectall" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
            ),
        ),
        //----
        "produkUnitPartReloadData" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartCheckerItems" => true,
        "connectTo" => "110",
        "connectoView" => false,//supaya gak perlu load karena sudah beda modul
        "connectoValidate" => array(
            3 => "grandtotal",
        ),
        "replacerConnectTo" => array(
            "cabang2ID" => "-1",
            "cabang2Name" => "pusat",
            "place2ID" => "-1",
            "place2Name" => "pusat",
            "gudang2ID" => "-1",
            "gudang2Name" => "default center warehouse",
            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
            "pihakID" => "placeID",
            "pihakName" => "placeName",
        ),

        "shoppingCartFieldsLink" => array(
            1 => array(
                "jml_intransit" => array(
                    "cabang_id" => "placeID",
                    "gudang_id" => "gudangID",
                ),

            ),
        ),
    ),

    "777_3" => array(
        "icon" => "fa fa-money",
        "label" => "koreksi harga persediaan",
        "place" => "center",
        "counter_global" => "_company_cabangID_modul_stepCode", //=> "key globla counter",
        "counter_global_part" => array(
            'jenis',
            "dtime",
            'cabangID',
            // 'cabang2_id',
            'cabang2ID',
            '_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID',
        ),
        "steps" => array(
            1 => array(
                "label" => "koreksi harga persediaan",
                "actionLabel" => "request",
                "source" => "",
                "target" => "777_3r",
                "userGroup" => "c_finance",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "autoSelectTarget" => false,
            ),
            2 => array(
                "label" => "Otorisasi koreksi harga persediaan",
                "actionLabel" => "Approve",
                "source" => "777_3r",
                "target" => "777_3",
                "userGroup" => "c_holding",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowScaner" => false,
            ),
        ),
//        "template"             => "template/transaksi.html",
        "template" => "template/transaksi_nopihak.html",
//        "selectorModel" => "MdlLockerStock",
        "selectorModel" => "MdlFifoAverage",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
//            "model"     => "MdlFifoAverage",
//            "label"     => array("hpp"),
//            "key_label" => array(
//                "jual" => "harga",
//            ),
//            "mainSrc"   => "hpp",
        ),
        "lockerCheck" => array(
            //"enabled" => true,
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
        ),
//        "selectorFilters" => array(
//            "stock_locker.cabang_id=placeID",
//            "stock_locker.gudang_id=gudangID",
//            "stock_locker.jumlah>.0",
//            "stock_locker.state=.active",
//        ),
        "selectorFilters" => array(
            "fifo_avg.cabang_id=placeID",
            "fifo_avg.gudang_id=gudangID",
            "fifo_avg.hpp=.1",//hanya dari opname, selain opname tidak boleh
            "fifo_avg.jml>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "silahkan diisi dengan item ID, nama, kode (yang berasal dari opname)",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
            "produk_kode" => "kode",
        ),
        "selectorViewedFields" => array(
            "id",
            "keterangan",
            "kode",
            "kategori_nama",
            "sub_kategori_nama",
            "satuan",
            "jumlah",
        ),
        "selectorLinkMutasi" => array(
            "enabled" => true,
            "link" => "Ledger/viewMoveDetails/RekeningPembantuProduk/1010030030/",//persediaan produk
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "cabang",
        "pihakFilters" => array(
            "id<>cabang_id",
            "jenis=.cabang",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFieldsCek" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
//            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
//            "requestReferenceNomer" => "referensi SO",
//            "customerName" => "konsumen",
//            "salesmanName" => "salesman",
//
//            "pihakMainID" => "status gudang id",
//            "pihakMainName" => "status gudang nama",
//            "pihakMainJenis" => "status gudang jenis",

            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),

        "shortHistoryFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
//            "cabang2_nama" => "cabang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "pic",
//            "reference_jenis_master" => "referensi jenis",
//            "requestReferenceNomer" => "referensi SO",
//            "customerName" => "konsumen",
//            "salesmanName" => "salesman",

            "next_pic" => "Next step otorisator",
            "description" => "notes/catatan",
            "description_special" => "notes/catatan<br>tambahan",
            // "keterangan" => "keterangan",
        ),
        "shortStepHistoryFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "777_3r" => "request number",
            "777_3" => "approval number",
            //            "585r" => "request number",
            "585" => "receipt number",
            "item_fields" => "isi",

            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "shortStatusFields" => array(
            //            "jenis_label" => "activity", //dimatikan by chepy tgl 24 nov 2022 karena nama sudah ada di tab atas
            "dtime" => "date",
            "status_next" => "status",
//            "suppliers_nama" => "vendor",
            //            "customers_nama" => "customer",
            "nomer_top" => "number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
            "nett" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            "hpp" => "harga persediaan(unit)",
            "harga" => "harga persediaan koreksi(unit)",
        ),
        "shortItemsFields2" => array(
            "produk_kode" => "sku",
            "barcode" => "barcode",
            "produk_nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "produk_ord_jml" => "order qty",
            "produk_ord_diterima" => "sent qty",
            "valid_qty" => "outstanding",
            "produk_ord_dibeli" => "purchased qty",
        ),
        //end
        "historyFields" => array(
            1 => array(
                "no" => "no",
                //                "jenis_label" => "activity",
                "dtime" => "tanggal",
                // "suppliers_nama" => "supplier",
//                "cabang2_nama" => "cabang tujuan",
                "nomer" => "Nomer",
                "item_fields" => "isi",
                // "free_produk" => "promo supplier",
                "oleh_nama" => "PIC",
                //                "transaksi_nilai" => "amount",
//                "reference_jenis_master" => "referensi jenis",
//                "requestReferenceNomer" => "referensi SO",
//                "customerName" => "konsumen",
//                "salesmanName" => "salesman",
//
//                // "pihakMainID" => "status gudang id",
//                "pihakMainName" => "status gudang nama",
//                "pihakMainJenis" => "status gudang jenis",

                // "next_pic" => "Next step otorisator",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
                // "harga" => "amount",
                // "disc" => "discount",
                // // "nett1" => "sub amount",
                // "ppn" => "ppn",
                // "nett" => "total amount",
                // "keterangan" => "keterangan",
                // "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
                //                "jenis_label" => "activity",
                "dtime" => "tanggal",
//                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "no. Request",
                "nomer" => "no. ACC perubahan harga perediaan",
                "item_fields" => "isi",
                // "free_produk" => "promo supplier",
                "oleh_nama" => "pic",
//                "reference_jenis_master" => "referensi jenis",
//                "requestReferenceNomer" => "referensi SO",
//                "customerName" => "konsumen",
//                "salesmanName" => "salesman",
//
//                // "pihakMainID" => "status gudang id",
//                "pihakMainName" => "status gudang nama",
//                "pihakMainJenis" => "status gudang jenis",

                // "next_pic" => "Next step otorisator",
                "description" => "notes/catatan",
                "description_special" => "notes/catatan<br>tambahan",
                "keterangan" => "keterangan",
            ),

        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
//            "cabang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "satuan" => "uom",
                "current_stok" => "stock<BR>available",
//                "intransit_stok" => "stock<BR>intransit",
//                "jml" => "qty",
//                "stok" => "last<BR>stock",

                "hpp" => "harga persediaan per unit",
                "harga" => "harga koreksi per unit",
            ),
            2 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "satuan" => "uom",
                "current_stok" => "stock<BR>available",
//                "intransit_stok" => "stock<BR>intransit",
//                "jml" => "qty",
//                "stok" => "last<BR>stock",

                "hpp" => "harga persediaan",
                "harga" => "harga koreksi",
            ),

        ),
        "shoppingCartFieldRef" => array(
            1 => array(
                "id" => "pID",
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "produk_order" => "Order Qty",
                "produk_sent" => "Qty Sent",
                "current_stok" => "stock<BR>available",
                "intransit_stok" => "stock<BR>intransit",
                "jml" => "qty",
                "stok" => "last<BR>stock",
                "satuan" => "uom",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "jenis" => "jenis",
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            //            "stok" => "stock<BR>after",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
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
            "produk_jenis_id" => "produk_jenis_id",
            "produk_jenis_nama" => "produk_jenis_nama",
            "barcode" => "barcode",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            //-------------------
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
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            "heater_id" => "heater_id",
            "heater_nama" => "heater_nama",
            "heater_barcode" => "heater_barcode",
            //            "produk_order" => "produk_order",
            //            "produk_sent" => "produk_sent",
            //-------------------
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "hpp" => "harga persediaan per unit",
                "harga" => "harga koreksi per unit",
            ),
            2 => array(
                "hpp" => "harga persediaan per unit",
                "harga" => "harga koreksi per unit",
            ),

        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",

            ),
            2 => array(
                "harga",

            ),

        ),
        "shoppingCartAmountValue" => array(
            1 => "current_stok*harga",
            2 => "current_stok*harga",
            3 => "current_stok*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
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
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total",
                //                "disc" => "Disc",
//                "ppn" => "VAT",
//                "grandtotal" => "Grand Total",
            ),
            2 => array(
                "harga" => "total",
                //                "disc" => "Disc",
                //                "ppn" => "VAT",
                //                "grand_total" => "Grand Total",
            ),
        ),
        "receiptElements" => array(
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
        ),

        "resumeFieldNames" => array(
            "selectFields" => "cabang2_nama",
            "title" => "brach ",
        ),
        "pairMakers" => array(
            1 => array(
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        //                        "cabang_id" => ".-1",
                        //                        "gudang_id" => ".-1",
                        //                        "state" => ".active",
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        //                        "jenis" => ".produk",
                        //                        "state" => ".active",
                    ),
                    //                    "kolom" => array(
                    ////                        "no_part",
                    //                    ),
                    "gate" => "items",
                ),
                "nilaiPersediaan" => array(
                    "helperName" => "he_cek_stock_produk_hpp_avg",
                    "functionName" => "cekStockProdukHppAvg",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
//                        "state" => ".active",
                    ),
                    "kolom" => array(
                        "hpp",
                    ),
                ),
//                "stokAktive" => array(
//                    "helperName" => "he_cek_stock_produk_locker",
//                    "functionName" => "cekStockProdukLocker",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                        "jenis" => ".produk",
//                        "state" => ".active",
//                    ),
//                    "gate" => "items",
//                ),

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
        ),
        "pairInjectors" => array(
            1 => array(
                "nilaiPersediaan" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "hpp",
                    ),
                ),
//                "stokAktive" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "jml_available",
//                    ),
//                ),
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
                    ),
                ),

            ),
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "cabang_nama" => "sender",
            "cabang2_nama" => "recipient",
            //            "suppliers_nama" =>"vendor",
//            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc"        => "discount",
            //            "grand_total"     => "nett",
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "qty",
            "harga" => "harga koreksi",
        ),
        "shoppingCartRowValidators" => array(
//            "pihakID" => "cabang tujuan",
            //            "pihakName" => "",
        ),
        "lastStepPartialApprove" => true,
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "583re",
                "label" => "EDIT distribution request",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "777_3rrj",
                "label" => "REJECT koreksi harga persediaan",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "777_3rj",
                "label" => "REJECT otorisasi",
            ),
        ),
        //----
        "itemSwapper" => "_processSelectProduct/multiSelect",
        "shopingCartReload" => true,


        "autoOtorisasiSesiValidate" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "selectorValidator" => array(
            1 => array(
                array(
                    "reference" => "requestReferenceID",
                    "keys" => array(
                        "pihakMainID",
                        "pihakMainName",
                        "pihakMainJenis",
                    ),
                    "label" => "Syarat Barang dikirim dari Gudang Pusat/DC tidak lengkap. Silahkan hubungi cabang untuk koreksi atau hubungi admin.",
                ),
                //                array(
                //                    "keys" => array(
                //                        "pihakMain2ID",
                //                        "pihakMain2Name",
                //                    ),
                //                    "label" => "Anda harus pilih Salesman untuk input penjualan ini. Silahkan dikoreksi lagi.",
                //                ),
            ),
            2 => array(
                array(
                    "reference" => "requestReferenceID",
                    "keys" => array(
                        "pihakMainID",
                        "pihakMainName",
                        "pihakMainJenis",
                    ),
                    "label" => "Syarat Barang dikirim dari Gudang Pusat/DC tidak lengkap. Silahkan hubungi cabang untuk koreksi atau hubungi admin.",
                ),
                //                array(
                //                    "keys" => array(
                //                        "pihakMain2ID",
                //                        "pihakMain2Name",
                //                    ),
                //                    "label" => "Anda harus pilih Salesman untuk input penjualan ini. Silahkan dikoreksi lagi.",
                //                ),
            ),
        ),
        "showInformasiStok" => true,
        //----
        //----
        "catatan" => array(
            1 => array(
                "reject" => array(
                    0 => "Transaksi distribusi ini akan mundur 1 langkah dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan mundur 1 langkah dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
                "rejectall" => array(
                    0 => "Transaksi distribusi ini akan dimatikan sampai dengan request distribusinya dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan dimatikan sampai dengan request distribusinya dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
            ),
            2 => array(
                "reject" => array(
                    0 => "Transaksi distribusi ini akan mundur 1 langkah dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan mundur 1 langkah dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
                "rejectall" => array(
                    0 => "Transaksi distribusi ini akan dimatikan sampai dengan request distribusinya dan tidak bisa digunakan lagi.",
                    1 => "Transaksi distribusi ini memiliki referensi sales order <strong>nomer_sales_order</strong>, akan dimatikan sampai dengan request distribusinya dan kembali ke Daftar Joblist yang perlu dikirim dari Gudang DC/Pusat.",
                ),
            ),
        ),
        "rejectOption" => array(
            1 => array(
                "reject" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
                "rejectall" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
            ),
            2 => array(
                //                "reject" => array(
                //                    "checkbox_link" => "",
                //                    "label" => "Batalkan Sales Order <strong>nomer_sales_order</strong>",
                //                ),
                "rejectall" => array(
                    "checkbox_link" => "_followupLiveEdit/updateChecklistReject/",
                    "label" => "Tic disini bila akan membatalkan Sales Order <strong>nomer_sales_order</strong>",
                ),
            ),
        ),
        //----
        "produkUnitPartReloadData" => array(
            1 => true,
            2 => true,
        ),
        "shoppingCartCheckerItems" => true,


        "shoppingCartFieldsLink" => array(
            1 => array(
                "jml_intransit" => array(
                    "cabang_id" => "placeID",
                    "gudang_id" => "gudangID",
                ),

            ),
        ),

    ),
);