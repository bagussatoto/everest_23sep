<?php
/**
 * Created by PhpStorm.
 * User: chepy
 * Date: 10/21/2021
 * Time: 16:16 PM
 */
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion
//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(
    //  config pemindahan finish goods (ke tidak dijual)
    "587" => array(
        "icon" => "fa fa-truck",
        "label" => "pindah produk ke gudang rusak (branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request pindah gudang",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "587r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse",
            ),
            2 => array(
//                "label" => "otorisasi pindah antar gudang",
//                "actionLabel" => "approve request",
                "label" => "scan QR/Barcode",
                "actionLabel" => "simpan QR/Barcode",
                "source" => "587r",
                "target" => "587ra",
                "userGroup" => "o_gudang_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse",
                "allowPrintQr" => true,
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "terima pindah gudang",
                "actionLabel" => "terima",
                "source" => "587ra",
                "target" => "587",
                "userGroup" => "w_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
                "subplace" => "warehouse_ng",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            "keterangan",
            "kode",
            "jumlah",
            "satuan",
            "kategori_nama",
            "sub_kategori_nama",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
            "jenis=.gudang rusak",
        ),
        "pihakAddStaticEntry" => array(
            "id" => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakConfirm" => true,

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
        ),
        //end
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
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
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
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
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
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
            4 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            3 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            4 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
            4 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
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

        "shoppingCartInfo" => array(
            "label_info" => "Anda melakukan pindah stok dari gudang <strong>gudangName</strong> ke gudang <strong> gudang2Name </strong>.",
            "replacer" => array(
                "gudangName",
                "gudang2Name",
            ),
        ),

        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target warehouse",
                //                "mdlName" => "MdlGudangDefault_center",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "name" => "nama",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "warehouse ID target",
            "pihakName" => "warehouse name target",
        ),

        "shoppingCartInfo" => array(
            "label_info" => "Anda melakukan pindah stok dari gudang <strong>gudangName</strong> ke gudang <strong> gudang2Name </strong>.",
            "replacer" => array(
                "gudangName",
                "gudang2Name",
            ),
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                        "transaksi_id!=" => ".0",
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
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "587re",
                "label" => "EDIT REQUEST PINDAH GUDANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "587rrj",
                "label" => "REJECT REQUEST PINDAH GUDANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "587rarj",
                "label" => "REJECT OTORISASI PINDAH GUDANG",
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
        "shopingCartReload" => true,
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",// barang reguler
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",

                "scanCheckerEnabled" => true,
                "scanCheckerLabel" => "Pengisian serial atau jumlah {produk_nama} belum lengkap. Silahkan diperiksa lagi.",
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "scannerQrConfig" => array(
            "enabled" => true,
            "replacerSelector" => array(
                "selectorGudangID" => "gudangID",
                "selectorGudangName" => "gudangName",
            ),
            "label" => "Silahkan scan/input Barcode/QRCode Serial/SKU produk dari {gudangName}.",
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
    //  config pemindahan finish goods (ke dijual)
    "687" => array(
        "icon" => "fa fa-truck",
        "label" => "pindah produk ke gudang dc (branch)",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request pindah gudang",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "687r",
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse_ng",
            ),
            2 => array(
                "label" => "scan QR/Barcode",
                "actionLabel" => "simpan QR/Barcode",
                "source" => "687r",
                "target" => "687ra",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse_ng",
                "allowPrintQr" => true,
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "terima pindah gudang",
                "actionLabel" => "terima",
                "source" => "687ra",
                "target" => "687",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
                "subplace" => "warehouse",
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
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudang2ID",
        ),
        "selectorLockerProcessFilters" => array(
            "state=.active",
            "cabang_id=placeID",
            "gudang_id=gudang2ID",
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
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
            "kategori_nama",
            "sub_kategori_nama",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang asal barang...",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id>.0",
//            "id<>gudang_id",
        ),
        "pihakAddStaticEntry" => array(
            "id" => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakConfirm" => true,

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
        ),
        //end
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
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
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
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
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
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
            4 => array(
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            4 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
            4 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "warehouse ID target",
            "pihakName" => "warehouse name target",
        ),

        "shoppingCartInfo" => array(
            "label_info" => "Anda melakukan pindah stok dari gudang <strong>gudang2Name</strong> ke gudang <strong> gudangName </strong>.",
            "replacer" => array(
                "gudangName",
                "gudang2Name",
            ),
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                        "transaksi_id!=" => ".0",
                    ),
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
                    ),
//                    "kolom" => array(
////                        "no_part",
//                    ),
//
                    "gate" => "items",
                ),
                "stokIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
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
                        "gudang_id" => "gudang2ID",
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
        "receiptElements" => array(
            "warehouseSource" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "warehouse ",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array("id=gudangID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "warehouseTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target warehouse ",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
        ),
        "previewCtr" => "Create",

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "687re",
                "label" => "EDIT REQUEST PINDAH GUDANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "687rrj",
                "label" => "REJECT REQUEST PINDAH GUDANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "687rarj",
                "label" => "REJECT OTORISASI PINDAH GUDANG",
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
        "shopingCartReload" => true,
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",// barang reguler
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",

                "scanCheckerEnabled" => true,
                "scanCheckerLabel" => "Pengisian serial atau jumlah {produk_nama} belum lengkap. Silahkan diperiksa lagi.",
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "scannerQrConfig" => array(
            "enabled" => true,
            "replacerSelector" => array(
                "selectorGudangID" => "gudang2ID",
                "selectorGudangName" => "gudang2Name",
            ),
            "label" => "Silahkan scan/input Barcode/QRCode Serial/SKU produk dari {gudang2Name}.",
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
                    "gudang_id" => "gudang2ID",
                ),

            ),
        ),
    ),

    //  config pemindahan antar gudang di center
    "1587" => array(
        "icon" => "fa fa-truck",
//        "label" => "pindah produk ke gudang rusak/project (center)",
        "label" => "pindah gudang (center)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request pindah gudang",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "1587r",
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
//                "label" => "otorisasi pindah antar gudang",
//                "actionLabel" => "approve request",
                "label" => "scan QR/Barcode",
                "actionLabel" => "simpan QR/Barcode",
                "source" => "1587r",
                "target" => "1587ra",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowPrintQr" => true,
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "terima pindah gudang",
                "actionLabel" => "terima",
                "source" => "1587ra",
                "target" => "1587",
                "userGroup" => "w_gudang",//w_gudang
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2", // MdlProdukRakitan
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            "keterangan",
            "kode",
            "jumlah",
            "satuan",
            "kategori_nama",
            "sub_kategori_nama",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
//            "jenis=.gudang rusak",
        ),
        // "pihakAddStaticEntry" => array(
        //     "id" => "gudang_id",
        //     "label" => "gudang_nama",
        // ),
        "pihakProcessor" => "_processPihak/select",
        "pihakConfirm" => true,

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "gudang_nama" => "gudang asal",
            "gudang2_nama" => "gudang tujuan",
            "oleh_nama" => "pic",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
        ),
        //end
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "gudang2_nama" => "warehouse",
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
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
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
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
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
            4 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            3 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            4 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
            4 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
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

        "shoppingCartInfo" => array(
            "label_info" => "Anda melakukan pindah stok dari gudang <strong>gudangName</strong> ke gudang <strong> gudang2Name </strong>.",
            "replacer" => array(
                "gudangName",
                "gudang2Name",
            ),
        ),

        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang tujuan",
                //                "mdlName" => "MdlGudangDefault_center",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "name" => "nama",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                        "transaksi_id!=" => ".0",
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
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1587re",
                "label" => "EDIT REQUEST PINDAH GUDANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1587rrj",
                "label" => "REJECT REQUEST PINDAH GUDANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1587rarj",
                "label" => "REJECT OTORISASI PINDAH GUDANG",
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
        "shopingCartReload" => true,
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",// barang reguler
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",

                "scanCheckerEnabled" => true,
                "scanCheckerLabel" => "Pengisian serial atau jumlah {produk_nama} belum lengkap. Silahkan diperiksa lagi.",
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "scannerQrConfig" => array(
            "enabled" => true,
            "replacerSelector" => array(
                "selectorGudangID" => "gudangID",
                "selectorGudangName" => "gudangName",
            ),
            "label" => "Silahkan scan/input Barcode/QRCode Serial/SKU produk dari {gudangName}.",
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
    //  config pemindahan finish goods (ke dijual)
    "1687" => array(
        "icon" => "fa fa-truck",
        "label" => "pindah produk ke gudang dc (center)",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request pindah gudang",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "1687r",
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "scan QR/Barcode",
                "actionLabel" => "simpan QR/Barcode",
                "source" => "1687r",
                "target" => "1687ra",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowPrintQr" => true,
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "terima pindah gudang",
                "actionLabel" => "terima",
                "source" => "1687ra",
                "target" => "1687",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
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
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudang2ID",
        ),
        "selectorLockerProcessFilters" => array(
            "state=.active",
            "cabang_id=placeID",
            "gudang_id=gudang2ID",
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
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
            "kategori_nama",
            "sub_kategori_nama",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang asal barang...",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id>.0",
        ),
        "pihakAddStaticEntry" => array(
            "id" => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakConfirm" => true,


        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "tanggal",
            "cabang_nama" => "branch",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "gudang_nama" => "gudang asal",
            "gudang2_nama" => "gudang tujuan",
            "oleh_nama" => "pic",
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
        ),
        //end
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
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
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
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
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
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
            4 => array(
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            4 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
            4 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "warehouse ID target",
            "pihakName" => "warehouse name target",
        ),

        "shoppingCartInfo" => array(
            "label_info" => "Anda melakukan pindah stok dari gudang <strong>gudang2Name</strong> ke gudang <strong> gudangName </strong>.",
            "replacer" => array(
                "gudangName",
                "gudang2Name",
            ),
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
                        "jenis" => ".produk",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                        "transaksi_id!=" => ".0",
                    ),
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
                    ),
                    "gate" => "items",
                ),
                "stokIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
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
                        "gudang_id" => "gudang2ID",
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
        "receiptElements" => array(
            "warehouseSource" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang asal ",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
//                    "id=gudangID",
                    "cabang_id=placeID",
                    "jenis=.gudang project",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "warehouseTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang tujuan",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
        ),
        "previewCtr" => "Create",

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1687re",
                "label" => "EDIT REQUEST PINDAH GUDANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1687rrj",
                "label" => "REJECT REQUEST PINDAH GUDANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1687rarj",
                "label" => "REJECT OTORISASI PINDAH GUDANG",
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
        "shopingCartReload" => true,
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        "serialNumberValidator" => array(
            2 => array(
                "enabled" => true,
                "source" => "items2",// barang reguler
                "label" => "Jumlah scan serial {produk_nama} tidak sesuai. Silahkan diperiksa hasil scan nomer serial anda.",

                "scanCheckerEnabled" => true,
                "scanCheckerLabel" => "Pengisian serial atau jumlah {produk_nama} belum lengkap. Silahkan diperiksa lagi.",
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "scannerQrConfig" => array(
            "enabled" => true,
            "replacerSelector" => array(
                "selectorGudangID" => "gudang2ID",
                "selectorGudangName" => "gudang2Name",
            ),
            "label" => "Silahkan scan/input Barcode/QRCode Serial/SKU produk dari {gudang2Name}.",
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
                    "gudang_id" => "gudang2ID",
                ),

            ),
        ),
    ),


    //  config pemindahan antar gudang besar ke gudang fase di cabang produksi
    "5587" => array(
        "icon" => "fa fa-truck",
        "label" => "pindah bahan baku ke gudang fase produksi",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "request pindah gudang",
                "actionLabel" => "request",
                "source" => "",
                "target" => "5587r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse",
            ),
            2 => array(
                "label" => "terima barang masuk",
                "actionLabel" => "terima barang masuk",
                "source" => "5587r",
                "target" => "5587",
                "userGroup" => "o_gudang",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse",
            ),
//            3 => array(
//                "label" => "terima pindah gudang",
//                "actionLabel" => "terima pindah gudang",
//                "source" => "587ra",
//                "target" => "587",
//                "userGroup" => "w_gudang",
//                "stateLabel" => "sent",
//                "stateColor" => "#009900",
//                "stateCaption" => "receive by",
//                "subplace" => "warehouse_ng",
//            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
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
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),
        "selectorFieldsReplacer" => array(
            "keterangan",
            "kode",
            "satuan",
            "satuan_id",
            "nama",
            "folders",
            "folders_nama",
        ),
        "selectorProcessor" => "_processSelectSupplies/select",
        "editHandlerMethod" => "select",

        "pihakModel" => "MdlGudangFase",
        "pihakCaller" => "_selectorPihak/selectPihakGudang",
        "pihakLabel" => "gudang fase produksi",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
//            "id<>gudang_id",
        ),
        "pihakAddStaticEntry" => array(
            "id" => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor" => "_processPihak/selectGudang",
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),
        "addPihakFields" => array("produk_nama"),

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "gudang asal",
            "gudang2_nama" => "gudang tujuan",
            "nomer" => "nomer transaksi",
            "item_fields" => "isi",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "dtime" => "date",
                "cabang_nama" => "branch",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer" => "nomer transaksi",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
            ),
            2 => array(
                "dtime" => "date",
                "cabang_nama" => "branch",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer_top" => "nomer referensi",
                "nomer" => "nomer transaksi",
                "item_fields" => "isi",
                "oleh_nama" => "pic",
            ),
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

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            //"berat"         => "berat",
            //"lebar"         => "lebar",
            //"panjang"       => "panjang",
            //"tinggi"        => "tinggi",
            //"volume"        => "volume",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
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
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "warehouse ID target",
            "pihakName" => "warehouse name target",
        ),

        "receiptElements" => array(
            "gudang_asal" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang asal",
                "mdlName" => "MdlGudangDefault",
                "mdlFilter" => array(
                    "cabang_id=placeID"
                ),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "gudang_tujuan" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang tujuan",
                "mdlName" => "MdlGudangFase",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "gudang_id=pihakID",
                ),
                "key" => "gudang_id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "gudang",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".supplies",
                    ),
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
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
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
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "587re",
                "label" => "EDIT REQUEST PINDAH GUDANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "587rrj",
                "label" => "REJECT REQUEST PINDAH GUDANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "587rarj",
                "label" => "REJECT OTORISASI PINDAH GUDANG",
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
        "pairRegistries" => array(
            "main", "items"
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
    //  config pemindahan antar gudang fase ke gudang besar di cabang produksi
    "6687" => array(
        "icon" => "fa fa-truck",
        "label" => "return bahan baku ke gudang besar",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "return bahan baku ke gudang besar",
                "actionLabel" => "simpan return",
                "source" => "",
                "target" => "6687r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
                "subplace" => "warehouse_ng",
            ),
            2 => array(
                "label" => "terima return bahan baku di gudang besar",
                "actionLabel" => "approve return",
                "source" => "6687r",
                "target" => "6687",
                "userGroup" => "o_gudang",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "subplace" => "warehouse_ng",
            ),
//            3 => array(
//                "label" => "restock reception",
//                "actionLabel" => "receive restocked items",
//                "source" => "687ra",
//                "target" => "687",
//                "userGroup" => "o_gudang",
//                "stateLabel" => "sent",
//                "stateColor" => "#009900",
//                "stateCaption" => "receive by",
//                "subplace" => "warehouse",
//            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
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
            "mdlName" => "MdlLockerStockSupplies",
        ),
        "selectorFilters" => array(
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),
        "selectorFieldsReplacer" => array(
            "keterangan",
            "kode",
            "satuan",
            "satuan_id",
            "nama",
            "folders",
            "folders_nama",
        ),

        "selectorProcessor" => "_processSelectSupplies/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudangDefault",
//        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
//            "cabang_id=.-1",
            "cabang_id=cabang_id",
//            "id<>gudang_id",
        ),
        "pihakAddStaticEntry" => array(
            "id" => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor" => "_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
                "dtime" => "date",
                "cabang_nama" => "branch",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer" => "nomer transaksi",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            2 => array(
                "dtime" => "date",
                "cabang_nama" => "branch",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer_top" => "nomer referensi",
                "nomer" => "nomer transaksi",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
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
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
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
            "barcode" => "barcode",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
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
                //            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
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
        "shoppingCartRowValidators" => array(
            "pihakID" => "warehouse ID target",
            "pihakName" => "warehouse name target",
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".supplies",
                    ),
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
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
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
        "receiptElements" => array(
//            "gudang_asal" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "gudang asal",
//                "mdlName" => "MdlGudangFase",
//                "mdlFilter" => array(
//                    "cabang_id=placeID",
//                    "gudang_id=gudangID",
//                ),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "nama" => "nama",
//                ),
//                "editPoints" => array(1),
//                "noValidate" => true,
//            ),
//            "gudang_tujuan" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "gudang tujuan",
//                "mdlName" => "MdlGudangDefault",
//                "mdlFilter" => array(
//                    "cabang_id=.-1"
//                ),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//                ),
//                "editPoints" => array(1),
//                "noValidate" => true,
//            ),
        ),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "687re",
                "label" => "EDIT REQUEST PINDAH GUDANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "687rrj",
                "label" => "REJECT REQUEST PINDAH GUDANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "687rarj",
                "label" => "REJECT OTORISASI PINDAH GUDANG",
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
        "pairRegistries" => array(
            "main", "items"
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


    //----------------------------
    //  config pengembalian barang dari gudang reguler ke gudang project
    "2587" => array(
        "icon" => "fa fa-truck",
        "label" => "pengembalian barang dari gudang reguler ke gudang project",
        "place" => "center",
        "steps" => array(
            1 => array(// pihak peminjam (gudang reguler)
                "label" => "request pengembalian barang",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "2587r",
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(// pihak peminjam (gudang reguler)
                "label" => "otorisasi pengembalian barang",
                "actionLabel" => "approve request",
                "source" => "2587r",
                "target" => "2587ra",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowPrintQr" => true,
                "allowScaner" => true,
            ),
            3 => array(// pihak pemberi pinjaman (gudang project)
                "label" => "penerimaan pengembalian barang",
                "actionLabel" => "terima barang masuk",
                "source" => "2587ra",
                "target" => "2587",
                "userGroup" => "w_gudang",//w_gudang
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStock",
        "selectorSrcModel" => "MdlProduk2", // MdlProdukRakitan
        "selectedPrice" => array(
            "model" => "MdlHargaProdukLastTransfer",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
        ),
        // ini digunakan untuk filter locker_cek
//        "selectorLockerProcessFilters" => array(
//            "state=.active",
//            "cabang_id=placeID",
//            "gudang_id=gudangProjectID",
//        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            //            "nama", "satuan", "jumlah",
            "keterangan",
            "kode",
            "jumlah",
            "satuan",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "jenis=.gudang project",
//            "id<>gudang_id",
        ),
        // "pihakAddStaticEntry" => array(
        //     "id" => "gudang_id",
        //     "label" => "gudang_nama",
        // ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "gudang_nama" => "gudang asal",
            "gudang2_nama" => "gudang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer" => "nomer",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer approval",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            3 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer penerimaan",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            4 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer penerimaan",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "gudang2_nama" => "warehouse",
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

        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
//                "produk_kode" => "Part-No",
                "qty_pinjam" => "qty pinjam",
                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
//                "produk_kode" => "Part-No",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
//                "produk_kode" => "Part-No",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
//                "produk_kode" => "Part-No",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "qty_pinjam" => "qty_pinjam",
            "barcode" => "barcode",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
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
            4 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            3 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            4 => array(//            "harga",
                //            "ppn",
//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
            4 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
        ),
        "receiptElements" => array(
            "warehouseSource" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang asal",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "warehouseTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang tujuan",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "name" => "nama",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokPinjam" => array(
                    "helperName" => "he_cek_stock_pinjam",
                    "functionName" => "cekStockPinjam",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
//                        "state" => ".active",
//                        "jenis" => ".produk",
                    ),
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
                "stokProduk" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokPinjam" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "qty_pinjam",
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
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2587re",
                "label" => "EDIT REQUEST PENGEMBALIAN BARANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2587rrj",
                "label" => "REJECT REQUEST PENGEMBALIAN BARANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "2587rarj",
                "label" => "REJECT OTORISASI PENGEMBALIAN BARANG",
            ),
        ),

        "tabHistoryFields" => array(
            "produk_id" => array(
                "label" => "By Produk",
                "allowFollowup" => true,
            ),
//            "supplier_id" => array(
//                "label" => "By Supplier",
//                "allowFollowup" => true,
//            ),
        ),
        "tabFieldsItems" => array(
            "produk_id" => array(
//                "select" => "All",
                "id" => "pID",
                "barcode" => "sku",
                "nama" => "nama produk",
                "satuan" => "satuan",
                "qty_kredit" => "jumlah",
//                "sales_order" => "kebutuhan",
//                "stock" => "stok tersedia",
////                "stok_buffer"      => "buffer",
//                "purchase_request" => "request",
//                "purchase_order" => "outstanding PO",
//                "new_order" => "kekurangan",

            ),
            "produk_id_attr" => array(
                "select" => array(),
                "id" => array(),
                "nama" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "nama",
                    "link" => "_processSelectProduct/multiSelectBi/2587",
                    "link_modal" => false,
                ),
                "satuan" => array(),
//                "stock" => array(
//                    "format" => "formatField_he_format",
//                    "format_key" => "harga",
//                ),
//                "sales_order" => array(
//                    "format" => "formatField_he_format",
//                    "format_key" => "harga",
//                    "link" => "bi/Penjualan/supplies",
//                    "link_head" => "nama",
//                ),
//                "stok_buffer" => array(
//                    "format" => "formatField_he_format",
//                    "format_key" => "harga",
//                ),
//                "purchase_request" => array(
//                    "format" => "formatField_he_format",
//                    "format_key" => "harga",
//                ),
//                "purchase_order" => array(
//                    "format" => "formatField_he_format",
//                    "format_key" => "harga",
//                    "link" => "bi/Pembelian/Supplies",
//                    "link_head" => "nama",
//                ),
//                "outstanding" => array(),
//                "new_order" => array(
//                    "format" => "formatField_he_format",
//                    "format_key" => "harga",
//                    "attr" => "class='text-bold bg-danger'",
//                ),
                "qty_kredit" => array(
                    "format" => "formatField_he_format",
                    "format_key" => "debet",
                    "link" => "_processSelectProduct/multiSelectBi/2587",
                    "link_modal" => false,
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
        "tabRequestCode" => array(
            "masterCode" => "2587",
            "stateCode" => "2587",
            "stepNumber" => "1",
            "allowMultiSelect" => false,
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
        "pairRegistries" => array(
            "main", "items"
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
                    "gudang_id" => "gudangProjectID",
                ),

            ),
        ),

    ),
    //  config pinjam barang dari gudang project ke gudang reguler
    "2687" => array(
        "icon" => "fa fa-truck",
        "label" => "pinjam barang dari gudang project ke gudang reguler",
        "place" => "center",//=> "center",
        "steps" => array(
            1 => array(// pihak peminjam (gudang reguler)
                "label" => "request pinjam barang",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "2687r",
                "userGroup" => "o_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(// pihak pemberi pinjaman (gudang project)
                "label" => "otorisasi pinjam barang",
                "actionLabel" => "approve request",
                "source" => "2687r",
                "target" => "2687ra",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowPrintQr" => true,
                "allowScaner" => true,
            ),
            3 => array(// pihak peminjam (gudang reguler)
                "label" => "penerimaan pinjam barang",
                "actionLabel" => "terima barang masuk",
                "source" => "2687ra",
                "target" => "2687",
                "userGroup" => "o_gudang",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",//
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
            "enabled" => false,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudang2ID",
        ),
        // ini digunakan untuk filter locker_cek
        "selectorLockerProcessFilters" => array(
            "state=.active",
            "cabang_id=placeID",
            "gudang_id=gudang2ID",
//            "gudang_id=gudangProjectID",
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
            "keterangan",
            "kode",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudangDefault_center",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
//            "cabang_id=cabang_id",
//            "id<>gudang_id",
        ),
        "pihakAddStaticEntry" => array(
            "id" => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "cabang",
            "gudang_nama" => "gudang asal",
            "gudang2_nama" => "gudang tujuan",
            "nomer" => "nomer",
            "item_fields" => "isi",
            "oleh_nama" => "person",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer" => "nomer",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer approval",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            3 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer penerimaan",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            4 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "cabang",
                "gudang_nama" => "gudang asal",
                "gudang2_nama" => "gudang tujuan",
                "nomer_top" => "nomer request",
                "nomer" => "nomer penerimaan",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "gudang_nama" => "from",
            "gudang2_nama" => "recipient",
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
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "stok" => "stock",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "barcode" => "barcode",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "qty_outdoor" => "qty_outdoor",
            "qty_indoor" => "qty_indoor",
            "keterangan" => "keterangan",
            "static_keterangan" => "static_keterangan",
            "produk_part_id_1" => "produk_part_id_1",
            "produk_part_nama_1" => "produk_part_nama_1",
            "produk_part_barcode_1" => "produk_part_barcode_1",
            "produk_part_id_2" => "produk_part_id_2",
            "produk_part_nama_2" => "produk_part_nama_2",
            "produk_part_barcode_2" => "produk_part_barcode_2",
            //-------------------
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
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
            4 => array(
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
            3 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            4 => array(
                //            "harga",
                //            "ppn",
//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
            4 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "warehouse ID target",
            "pihakName" => "warehouse name target",
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangProjectID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokIntransit" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudang2ID",
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
                        "gudang_id" => "gudang2ID",
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
        "receiptElements" => array(
            "warehouseSource" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang asal ",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array("id=gudangProjectID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "warehouseTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "gudang tujuan",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=placeID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
        ),
        "previewCtr" => "Create",

        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2687re",
                "label" => "EDIT REQUEST PINJAM BARANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "2687rrj",
                "label" => "REJECT REQUEST PINJAM BARANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "2687rarj",
                "label" => "REJECT OTORISASI PINJAM BARANG",
            ),
        ),
        "defaultPihak" => array(
            "enabled" => true,
            "mdlName" => "MdlGudang",
            "filter" => array(
                "cabang_id=placeID",
                "jenis=.gudang project",
            ),
            "gate" => array(
                "pihakID" => "id",
                "pihakName" => "nama",
                "pihakNama" => "nama",
                "gudangProjectID" => "id",
                "gudangProjectName" => "nama",
                "gudangProjectNama" => "nama",
            )
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
        "pairRegistries" => array(
            "main", "items"
        ),
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        "scannerQrConfig" => array(
            "enabled" => true,
            "replacerSelector" => array(
                "selectorGudangID" => "gudang2ID",
                "selectorGudangName" => "gudang2Name",

            ),
            "label" => "Silahkan scan/input Barcode/QRCode Serial/SKU produk dari Gudang Project.",
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
                    "gudang_id" => "gudang2ID",
                ),

            ),
        ),

    ),

    //  config pemindahan supplies antar gudang di center
    "1588" => array(
        "icon" => "fa fa-truck",
//        "label" => "pindah produk ke gudang rusak/project (center)",
        "label" => "pindah gudang supplies (from DC)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request pindah gudang",
                "actionLabel" => "simpan request",
                "source" => "",
                "target" => "1588r",
                "userGroup" => "w_gudang",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
//                "label" => "otorisasi pindah antar gudang",
//                "actionLabel" => "approve request",
                "label" => "scan QR/Barcode",
                "actionLabel" => "simpan QR/Barcode",
                "source" => "1588r",
                "target" => "1588ra",
                "userGroup" => "w_gudang_spv",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowPrintQr" => true,
                "allowScaner" => true,
            ),
            3 => array(
                "label" => "terima pindah gudang",
                "actionLabel" => "terima",
                "source" => "1588ra",
                "target" => "1588",
                "userGroup" => "w_gudang",//w_gudang
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "receive by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlLockerStockSupplies",
        "selectorSrcModel" => "MdlSupplies",
        "selectedPrice" => array(
//            "model" => "MdlHargaProduk",
//            "label" => array("hpp"),
//            "key_label" => array(
//                "hpp" => "harga",
//            ),
//            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockSupplies",
            "customFilter" => array(
                "biaya_id is null",
            ),
        ),
        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jumlah>.0",
//            "state=.active",
//            "gudang_id=gudangID",
            "stock_locker.cabang_id=placeID",
            "stock_locker.jumlah>.0",
            "stock_locker.state=.active",
            "stock_locker.gudang_id=gudangID",
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
            "keterangan",
            "kode",
            "jumlah",
            "satuan",
            "kategori_nama",
            "sub_kategori_nama",
        ),

        "selectorProcessor" => "_processSelectSupplies/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlGudang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "gudang",
        "pihakFilters" => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
//            "jenis=.gudang rusak",
        ),
        // "pihakAddStaticEntry" => array(
        //     "id" => "gudang_id",
        //     "label" => "gudang_nama",
        // ),
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "gudang_nama" => "from",
            "gudang2_nama" => "target warehouse",
            "nomer_top" => "receipt number",
            "nomer" => "scan number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "next_pic" => "Next step otorisator",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "gudang_nama" => "from",
                "gudang2_nama" => "target warehouse",
                "nomer_top" => "request number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "gudang_nama" => "from",
                "gudang2_nama" => "target warehouse",
                "nomer_top" => "request number",
                "nomer" => "scan number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
            3 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "gudang_nama" => "from",
                "gudang2_nama" => "target warehouse",
                "nomer_top" => "request number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "scan number",
                ),
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
            ),
        ),
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "kode" => "sku",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
        ),
        //end
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "recipient",
            "gudang2_nama" => "warehouse",
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
                "satuan" => "satuan",
            ),
            2 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            3 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
                    "addKey" => "static_keterangan",
                ),
                "produk_kode" => "product code",
                "keterangan" => "part number",
                "jml" => "qty",
                "satuan" => "satuan",

            ),
            4 => array(
                "barcode" => "sku",
                "nama" => array(
                    "label" => "product name",
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
            "code" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "stok" => "stock",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",
            "barcode" => "barcode",
            "current_stok" => "current<BR>stock",
            "intransit_stok" => "intransit_stok",
            "jml_intransit" => "jml_intransit",
            "jml_available" => "jml_available",
            //-------------------
            "outdoor_id" => "outdoor_id",
            "outdoor_nama" => "outdoor_nama",
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
            4 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            3 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
            4 => array(//            "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
            4 => "jml*hpp",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
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
                "label" => "gudang tujuan",
                //                "mdlName" => "MdlGudangDefault_center",
                "mdlName" => "MdlGudang",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "id=pihakID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "name" => "nama",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),

        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".supplies",
                    ),
                ),
                "stokProdukIntransit" => array(
                    "helperName" => "he_cek_stock_supplies_locker",
                    "functionName" => "cekStockSuppliesLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "jenis" => ".supplies",
                        "state" => ".hold",
                        "jumlah>" => ".0",
                        "transaksi_id!=" => ".0",
                    ),
                    "gate" => "items",
                ),
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
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
                        "jenis" => ".supplies",
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
                        "jenis" => ".supplies",
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
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1587re",
                "label" => "EDIT REQUEST PINDAH GUDANG",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1587rrj",
                "label" => "REJECT REQUEST PINDAH GUDANG",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1587rarj",
                "label" => "REJECT OTORISASI PINDAH GUDANG",
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
        "shopingCartReload" => true,
        "serialNumberLocker" => array(
            "enabled" => true,
        ),
        "pairRegistries" => array(
            "main", "items"
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


);