<?php

$config["coTransaksiUi"] = array(
    // barang terjual
    "3311" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "Kompensasi Penurunan Harga (Barang Terjual)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PRE Kompensasi Penurunan Harga (Barang Terjual)",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "3311r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "Kompensasi Penurunan Harga (Barang Terjual)",
                "actionLabel" => "otorisasi Kompensasi Penurunan Harga",
                "source" => "3311r",
                "target" => "3311",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowPrintQr" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProdukPerSupplier",
        "selectorSrcModel" => "MdlProduk",
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
            "produk_per_supplier.suppliers_id=pihakID",
            "produk_per_supplier.cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih barang",
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
            "no_part",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "selectorProcessorBi" => "_processSelectProduct/multiSelectBiNew",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih supplier",
        "pihakFilters" => array(
//            "country=.ID",
//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakAddValidate" => array(
            "mode" => "!=",
            "filter" => array(
                "country" => "ID",
            ),
            "label" => array(
                "country" => "FG purchasing (lokal) harus memilih vendor lokal atau perbaiki data vendor.",
            ),

        ),
        //---------------------------------------------------
        "shoppingCartNoteEnabled" => true, //tambahan notes per items dishoppingcart
        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "supplier",
            "nomer_top" => "Request number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "Approval number",
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
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "nilai kompensasi",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
            "kompensasiMethod__name" => "Metode kompensasi",
            "cash_account__nama" => "Akun Bank",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier",
                "nomer_top" => "Request number",
                "oleh_nama" => "person",
                "harga" => "nilai kompensasi",
//                "disc" => "discount",
                // "nett1" => "sub amount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "kompensasiMethod__name" => "Metode kompensasi",
                "cash_account__nama" => "Akun Bank",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier",
                "nomer_top" => "Request number",
                "nomer" => "Approval number",
                "oleh_nama" => "person",
                "harga" => "nilai kompensasi",
                //                "transaksi_nilai" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "kompensasiMethod__name" => "Metode kompensasi",
                "cash_account__nama" => "Akun Bank",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),

        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
//            3 => array("print_label" => "nomer"),
//            4 => array("print_label" => "nomer"),
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
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Nama Produk",
                "produk_kode" => "kode",
                "no_part" => "nomer part",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            2 => array(
                "nama" => "Nama Produk",
                "produk_kode" => "kode",
                "no_part" => "nomer part",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),

        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
                "harga" => "nilai",
            ),
            2 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
                "harga" => "nilai",
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
//            3 => "(jml*harga)",// hpp
//            4 => "(jml*harga)",// hpp
//            5 => "(jml*harga)",// hpp
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
                "harga" => "Total",
                // "ppv" => "index",
//                "ppn" => "VAT",
//                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga" => "Total",
                // "ppv" => "index",
//                "ppn" => "VAT",
//                "hpp_nppn" => "Grand Total",
            ),
        ),
        "shoppingCartSubDetailFields" => array(
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
            4 => array(
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
            5 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
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
            "main", "items", "tableIn_master_values"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUPPLIER ",
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

            "kompensasiMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Metode Kompensasi",
                "mdlName" => "MdlKompensasiMethodStatic",
                "key" => "id",
//                "defaultValue" => "credit",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1,),
            ),

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
            "kompensasiMethod" => array(
                "1" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1, 4),
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
//            5 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//        ),
        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        // ======== =========
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
//        "ppnCekValidate" => array(
//            "ppnPersenCheck" => "Silahkan menentukan Pajak Vendor (PPN atau NON PPN)",
//        ),
        //----
//        "ppnCekDefaultValue" => array(
//            "enabled" => true,
//            "ppnPersenCheck" => 1,// 1 dan 0
//            "keyDetail" => array(
//                "srcMain" => "ppnPersenCheck",
//                "srcItem" => "ppnFactor",
//                "overWriteMain" => "ppnFactor",
//            ),
//        ),
//        "itemSettingDiskonPenjualan" => array(
////            1 => false,
//            4 => true,
//        ),
//        "itemPriceValidator" => array(
//            4 => array(
//                "enabled" => true,
//            ),
//        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3311re",
                "label" => "EDIT PRE PURCHASE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3311rrj",
                "label" => "REJECT PRE PURCHASE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "3311rj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
    ),
    // stok tersisa
    "3322" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "Kompensasi Penurunan Harga (Stok Tersisa)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PRE Kompensasi Penurunan Harga (Stok Tersisa)",
                "actionLabel" => "simpan",
                "source" => "",
                "target" => "3322r",
                "userGroup" => "c_holding",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "Kompensasi Penurunan Harga (Stok Tersisa)",
                "actionLabel" => "otorisasi Kompensasi Penurunan Harga",
                "source" => "3322r",
                "target" => "3322",
                "userGroup" => "c_holding",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
                "allowPrintQr" => true,
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProdukPerSupplier",
        "selectorSrcModel" => "MdlProduk",
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
            "produk_per_supplier.suppliers_id=pihakID",
            "produk_per_supplier.cabang_id=placeID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih barang",
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
            "no_part",
            "satuan",
        ),
        "selectorProcessor" => "_processSelectProduct/select",
        "selectorProcessorBi" => "_processSelectProduct/multiSelectBiNew",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih supplier",
        "pihakFilters" => array(
//            "country=.ID",
//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakAddValidate" => array(
            "mode" => "!=",
            "filter" => array(
                "country" => "ID",
            ),
            "label" => array(
                "country" => "FG purchasing (lokal) harus memilih vendor lokal atau perbaiki data vendor.",
            ),

        ),
        //---------------------------------------------------
        "shoppingCartNoteEnabled" => true, //tambahan notes per items dishoppingcart
        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "supplier",
            "nomer_top" => "Request number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "Approval number",
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
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "nilai kompensasi",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
            "kompensasiMethod__name" => "Metode kompensasi",
            "cash_account__nama" => "Akun Bank",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier",
                "nomer_top" => "Request number",
                "oleh_nama" => "person",
                "harga" => "nilai kompensasi",
//                "disc" => "discount",
                // "nett1" => "sub amount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "kompensasiMethod__name" => "Metode kompensasi",
                "cash_account__nama" => "Akun Bank",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier",
                "nomer_top" => "Request number",
                "nomer" => "Approval number",
                "oleh_nama" => "person",
                "harga" => "nilai kompensasi",
                //                "transaksi_nilai" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "kompensasiMethod__name" => "Metode kompensasi",
                "cash_account__nama" => "Akun Bank",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),

        ),
        "extHistoryFields" => array(
            1 => array("print_label" => "nomer"),
            2 => array("print_label" => "nomer"),
//            3 => array("print_label" => "nomer"),
//            4 => array("print_label" => "nomer"),
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
            "current_stok" => "current<BR>stock",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Nama Produk",
                "produk_kode" => "kode",
                "no_part" => "nomer part",
                "current_stok" => "stock<BR>available",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            2 => array(
                "nama" => "Nama Produk",
                "produk_kode" => "kode",
                "no_part" => "nomer part",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),

        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
                "harga" => "nilai",
            ),
            2 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
                "harga" => "nilai",
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
//            3 => "(jml*harga)",// hpp
//            4 => "(jml*harga)",// hpp
//            5 => "(jml*harga)",// hpp
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
                "harga" => "Total",
                // "ppv" => "index",
//                "ppn" => "VAT",
//                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga" => "Total",
                // "ppv" => "index",
//                "ppn" => "VAT",
//                "hpp_nppn" => "Grand Total",
            ),
        ),
        "shoppingCartSubDetailFields" => array(
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
            4 => array(
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
            5 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
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
            "main", "items", "tableIn_master_values"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUPPLIER ",
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

            "kompensasiMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Metode Kompensasi",
                "mdlName" => "MdlKompensasiMethodStatic",
                "key" => "id",
//                "defaultValue" => "credit",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1,),
            ),

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
            "kompensasiMethod" => array(
                "1" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1, 4),
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
//            5 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
//        ),
        //        "followupMainEditable" => "_followupLiveEdit/updateMainFieldByStep/",
        "followupMainEditable" => "_followupLiveEdit/updateMainField/",
        // ======== =========
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
                "stokBefore" => array(
                    "helperName" => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                    "gate" => "items",
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
                "stokBefore" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "current_stok",
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
//        "ppnCekValidate" => array(
//            "ppnPersenCheck" => "Silahkan menentukan Pajak Vendor (PPN atau NON PPN)",
//        ),
        //----
//        "ppnCekDefaultValue" => array(
//            "enabled" => true,
//            "ppnPersenCheck" => 1,// 1 dan 0
//            "keyDetail" => array(
//                "srcMain" => "ppnPersenCheck",
//                "srcItem" => "ppnFactor",
//                "overWriteMain" => "ppnFactor",
//            ),
//        ),
//        "itemSettingDiskonPenjualan" => array(
////            1 => false,
//            4 => true,
//        ),
//        "itemPriceValidator" => array(
//            4 => array(
//                "enabled" => true,
//            ),
//        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3322re",
                "label" => "EDIT PRE PURCHASE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3322rrj",
                "label" => "REJECT PRE PURCHASE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "3322rj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
    ),

    // credit note dari supplier per-nota pembelian    // realisasi klaim dari piutang ke kas/voucher
//    "3333_" => array(
//        "icon" => "fa fa-cart-arrow-down",
//        "label" => "REALISASI KLAIM KE SUPPLIER",
//        "place" => "center",
//        "steps" => array(
//            1 => array(
//                "label" => "REALISASI KLAIM KE SUPPLIER",
//                "actionLabel" => "simpan REALISASI KLAIM KE SUPPLIER",
//                "source" => "",
//                "target" => "3333",
//                "userGroup" => "c_purchasing",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Prepare by",
//            ),
//
//        ),
//        "template" => "template/transaksi_3_1.html",
//        "selectorModel" => "MdlLockerStockDiskonVendor",
//        "selectorSrcModel" => "MdlDiskonPembelianSupplier",
////        "validLocker"=>"true",
//        "selectedPrice" => array(
////            "model" => "MdlHargaProdukPerSupplier",
////            "mdlFilter" => array("suppliers_id=pihakID"),
////            "label" => array("hpp"),
////            "key_label" => array(
////                "hpp" => "harga",
////            ),
////            "mainSrc" => "hpp",
//        ),
//        "lockerCheck" => array(
////            "enabled" => true,
////            "mdlName" => "MdlLockerStockDiskonVendor",
//        ),
//        "selectorFilters" => array(
//            "cabang_id=placeID",
//            "jenis=.diskon",
//            "supplier_id=pihakID",
//            "produk_id=pihakMainID",
//            "nilai>.0",
//        ),
//
//        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
//        "selectorLabel" => "pilih nota pembelian",
//        "selectorParamFields" => array(
//            "id" => "id",
//            "pym_src_id" => "transaksi_id",
//            "nama" => "nomer",
//            "transaksi_id" => "transaksi_id",
//            "extern_id" => "extern_id",
//            "extern_nama" => "extern_nama",
//            "label" => "label",
//        ),
//        "selectorViewedFields" => array(
//            "fulldate",
//            "nomer",
//            "extern_nama",
//        ),
//        "selectorProcessor" => "_processSelectNota/select",//_processSelectNotaItem
//        "selectorProcessorBi" => "_processSelectProduct/multiSelectBiNew",
//        "editHandlerMethod" => "select",
//        "pihakModel" => "MdlSupplier",
//        "pihakCaller" => "_selectorPihak/selectPihak",
//        "pihakLabel" => "pilih supplier",
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
//        "mainselectorModel" => array(
//            "MdlDtaBiayaProduksi" => array(
//                "label" => "biaya produksi",
//                "allowed_branch" => array(25)
//            ),
//            "MdlDtaBiayaUsaha" => array(
//                "label" => "biaya usaha",
//                "allowed_branch" => array(1, 21),
//            ),
//            "MdlDtaBiayaUmum" => array(
//                "label" => "biaya umum",
//                "allowed_branch" => array(1, 21, 25),
//            ),
//
//        ),
////        "pihakModelMain" => "MdlDiskonPembelianSupplier",
//        "pihakModelMain" => "MdlSupplierDiskon",
//        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
//        "pihakMainLabel" => "jenis klaim kepada supplier",
//        "pihakMainFilters" => array(
////            "supplier_id=pihakID",
////            "is_pembayaran=.0",
//        ),
//        "pihakMainValueSrc2" => array(
//            "pihakMdlName" => "mdl_name",
//        ),
//        "pihakMainProcessor" => "_processPihakMain/select",
//        "pihakMainPair" => array(
//            "MdlName" => "MdlLockerStockDiskonVendor",
//            "filter" => array(
//                "cabang_id=cabangID",
//                "supplier_id=pihakID",
//                "jumlah>.0",
//            ),
//            //            "viewdFields" => array("kode", "serial_no"),
//        ),
//        "pihakMainViewedFields" => array(
////            "per_supplier_diskon_nama",
//            "nama",
////            "per_supplier_diskon_alias",
////            "nama",
////            "nilai",
////            "kode",
////            "serial_no",
//        ),
//        "pihakNameMainDiskon" => true,
////        "pihakNameMainDiskonIdSelector" => "per_supplier_diskon_id",
////        "pihakNameMainDiskonIdProcessor" => "per_supplier_diskon_id",
//        "pihakNameMainDiskonIdSelector" => "id",
//        "pihakNameMainDiskonIdProcessor" => "id",
//        //---------------------------------------------------
//
//        "shoppingCartNoteEnabled" => false, //tambahan notes per items dishoppingcart
//        "shortHistoryFields" => array(
//            //            "no" => "no",
//            "jenis_label" => "activity",
//            "dtime" => "date",
//            "suppliers_nama" => "Supplier",
//            "nomer_top" => "Nomer",
//            // sumber dari kolom id_his
//            "nomer_po" => array(
//                "step" => 2,
//                "key" => "nomer",
//                "label" => "PO number",
//            ),
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
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
//            //            "nomer" => "receipt number",
//            "oleh_nama" => "person",
//            "harga" => "amount",
////            "disc" => "discount",
////            "ppn" => "ppn",
////            "nett" => "total amount",
//            "next_pic" => "Next step otorisator",
//            "keterangan" => "keterangan",
//        ),
//        "historyFields" => array(
//            1 => array(
//                "no" => "no",
//                "dtime" => "date",
//                "suppliers_nama" => "Supplier",
//                "nomer_top" => "Nomer",
//                //                "nomer" => "receipt number",
//                "oleh_nama" => "person",
//                //                "transaksi_nilai" => "amount",
//                "harga" => "amount",
////                "disc" => "discount",
//                // "nett1" => "sub amount",
////                "ppn" => "ppn",
////                "nett" => "total amount",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//                "print_barcode_pembelian" => array(
//                    "label" => "print Serial",
//                    "key" => array(
//                        "print_barcode_pembelian",
//                        "print_barcode_pembelian_2",
//                    ),
//                ),
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
//                "nomer" => "Pre GRN number",
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
//                "ids_his_3" => array(
//                    "step" => 3,
//                    "key" => "nomer",
//                    "label" => "PRE GRN number",
//                ),
//                "nomer" => "GRN number",
//                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
//                "oleh_nama" => "person",
//                "keterangan" => "keterangan",
//                "print_label" => "tool",
//            ),
//            5 => array(
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
//                "nomer_pre_grn" => array(
//                    "step" => 3,
//                    "key" => "nomer",
//                    "label" => "PRE GRN number",
//                ),
//                "nomer_grn" => array(
//                    "step" => 4,
//                    "key" => "nomer",
//                    "label" => "GRN number",
//                ),
//                "nomer" => "Receipt number",
//                "description_main_followup" => "INV<br>from vendor",
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
//                "print_label" => "nomer",
//                "print_barcode_pembelian" => "id",
//                "print_barcode_pembelian_2" => "id",),
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
//
//        "shoppingCartHideSubamount" => array(
//            1 => false,
//            2 => false,
//        ),
//        "shoppingCart" => array(
//            "initPrices" => "beli",
//        ),
//        "shoppingCartFieldSrc" => array(
//            "id" => "transaksi_id",
//            "nama" => "nomer",
//            "produk_kode" => "kode",
//            "no_part" => "no_part",
//            "label" => "label",
//            "satuan" => "satuan",
//            "extern_id" => "extern_id",
//            "extern_nama" => "extern_nama",
//            "extern2_id" => "extern2_id",
//            "extern2_nama" => "extern2_nama",
//            "diskon_supplier_nilai" => "nilai_unit",
//            "harga" => "nilai_unit",
//            "jml" => "jumlah",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "pembelian",
//                "extern2_nama" => "produk",
//                "extern_nama" => "jenis diskon",
////                "produk_kode" => "product code",
////                "no_part" => "part number",
//                "jml" => "Qty",
////                "satuan" => "UOM",
//            ),
//            2 => array(
//                "nama" => "Descriptions",
//                "produk_kode" => "product code",
//                "no_part" => "part number",
//                "jml" => "Qty",
//                "satuan" => "UOM",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
////                "harga_last_purchase" => "Last Purchase<br>Price",
//                "diskon_supplier_nilai" => "nilai klaim",
//                //                "ppn" => "VAT",
//            ),
//            2 => array(
//                "diskon_supplier_nilai" => "nilai klaim",
//            ),
//        ),
//        "shoppingCartNoteEditabled" => array(
////            2 => true,
////            3 => true,
//        ),
//        "shoppingCartNoteType" => "textarea",
//        "shoppingCartEditableFields" => array(
////            1 => array(
////                "harga",
////                "jml",
////                "produk_ord_jml",
////            ),
////            2 => array(
////                "harga",
////                "jml",
////                "produk_ord_jml",
////            ),
////            3 => array(
////                "jml",
////                "produk_ord_jml",
////            ),
//        ),
//        "shoppingCartAmountValue" => array(
//            1 => "sub_diskon_supplier_nilai",
//            2 => "sub_diskon_supplier_nilai",
//        ),
//        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
////            "harga" => "price",
//        ),
//        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
//        ),
//        "shoppingCartSumFields" => array(
//            1 => array(
//                "diskon_supplier_nilai" => "Total",
//                // "ppv" => "index",
////                "ppn" => "VAT",
////                "hpp_nppn" => "Grand Total",
//            ),
//            //            3 => array(
//            //                "harga" => "Total Amount",
//            //                // "ppv" => "index",
//            //                "ppn" => "VAT",
//            //                "hpp_nppn" => "Grand Total",
//            //            ),
//        ),
//        "shoppingCartSubDetailFields" => array(
//            1 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
//                ),
//                "produk_nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
//                ),
//            ),
////            4 => array(
////                "nama" => array(
////                    "source" => "items3_sum",
////                    "tipe" => "textarea",
////                    "gate" => "produk_sku_serial",
////                ),
////                "produk_nama" => array(
////                    "source" => "items3_sum",
////                    "tipe" => "textarea",
////                    "gate" => "produk_sku_serial",
////                ),
////            ),
////            5 => array(
////                "nama" => array(
////                    "source" => "items3_sum",
////                    "tipe" => "textarea",
////                    "gate" => "produk_sku_serial",
////                ),
////
////            ),
//        ),
//
//        "shoppingCartHeaderElement" => array(
////            1 => array(
////                "ppn" => array(
////                    "label" => "TENTUKAN PPN",
////                    "class" => "col-xs-6 no-padding",
////                    "subElements" => array(
////                        "non_ppn" => array(
////                            "label" => "Non PPN",
////                            "srcMain" => "ppnPersenCheck",
////                            "overWriteMain" => "ppnFactor",
////                            "srcItem" => "ppnFactor",
////                            "value" => "0",
////                            "style" => array( //wajib di tentukan
////                                "element" => "div",
////                                "class" => "",
////                                "id" => "",
////                                "name" => "",
////                            )
////                        ),
////                        "ppn" => array(
////                            "label" => "PPN",
////                            "srcMain" => "ppnPersenCheck",
////                            "overWriteMain" => "ppnFactor",
////                            "srcItem" => "ppnFactor",
////                            "value" => "1",
////                            "style" => array( //wajib di tentukan
////                                "element" => "div",
////                                "class" => "",
////                                "id" => "",
////                                "name" => "",
////                            )
////                        ),
////                    )
////                ),
////            )
//        ),
//        "tabHistoryFields" => array(
////            "produk_id" => array(
////                "label" => "By Produk",
////                "allowFollowup" => false,
////            ),
////            "suppliers_id" => array(
////                "label" => "By Vendor",
////                "allowFollowup" => true,
////            ),
//
//        ),
//        "tabFieldsItems" => array(
////            "suppliers_id" => array(
////                "kode" => "kode",
////                "nama" => "Produk Nama",
////                "satuan" => "Satuan",
////                "omset" => "Omset",
////                "average" => "Average harian",
////                "stok" => "Stok",
////                "buffer" => "buffer(qty)",
////                // "moq" => "Moq",
////                "ideal_stok" => "proyeksi stok(qty)",
////                "new_order" => "Rekomendasi order",
////            ),
////            "produk_id" => array(
////                //                "select" => "All",
////                "kode" => "kode",
////                "nama" => "Produk Nama",
////                "satuan" => "Satuan",
////                "omset" => "Omset",
////                "average" => "Average harian",
////                "stok" => "Stok",
////                "buffer" => "buffer<br>(qty)",
////                // "moq" => "Moq",
////                "ideal_stok" => "proyeksi stok(qty)",
////                "new_order" => "Rekomendasi order",
////                // ""=>"",
////                //                "purchased" => "On Purchase",
////                //                "valid_qty" => "Outstanding",
////
////            ),
//        ),
//        "allowedMainEdit" => array("1"),
//        "pairRegistries" => array(
//            "main", "items", "tableIn_master_values"
//        ),
//        "receiptElements" => array(
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
////            "transaksiDetails" => array(
////                "elementType" => "dataModel",
////                "inputType" => "radio",
////                "label" => "Transaksi Pembelian ",
////                "mdlName" => "MdlTransaksiData",
////                "mdlFilter" => array(
////                    "id=referenceID",
////                ),
////                "key" => "id",
////                "labelSrc" => "nomer",
////                "usedFields" => array(
////                    "suppliers_nama" => "Supplier",
////                    "nomer" => "nomer",
////                    "nomer_top" => "nomer pre",
////                ),
////                "editPoints" => array(1, 2, 3),
////            ),
//            "kompensasiMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Metode Diskon pembelian",
////                "mdlName" => "MdlKompensasiMethodStatic",
//                "mdlName" => "MdlKompensasiMethodRealisasiStatic",
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
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
//                ),
//                "editPoints" => array(1),
//            ),
//        ),
//        "relativeElements" => array(
//            "kompensasiMethod" => array(
//                "1" => array(
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
////                    "pph23Methode" => array(
////                        "elementType" => "dataModel",
////                        "inputType" => "radio",
////                        "label" => "dipotong/tidak dipotong pph23",
////                        "mdlName" => "MdlPph23MethodPotongan",
////                        "mdlFilter" => array(),
////                        "key" => "id",
////                        "labelSrc" => "nama",
////                        "usedFields" => array(
////                            "nama" => "nama",
////                            "tarif" => "tarif",
////                        ),
////                        "editPoints" => array(1),
////                    ),
//                ),
////                "2" => array(
////                    "vendorRebate" => array(
////                        "elementType" => "dataModel",
////                        "inputType" => "radio",
////                        "label" => "Diskon pembelian",
////                        "mdlName" => "MdlSupplierDiskon",
//////                "mdlFilter" => array(
//////                    "id=referenceID",
//////                ),
//////
////                        "key" => "id",
////                        "labelSrc" => "nama",
////                        "usedFields" => array(
////                            "nama" => "nama",
////                            "coa_code" => "kode",
////                        ),
////                        "editPoints" => array(1, 2, 3),
////                    ),
////                ),
//            ),
//        ),
//        "relativeOptions" => array(),
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
//            5 => array(
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
//            5 => true,
//        ),
//        "additionalRows" => array(
//            "dummyElement" => array(
//                "yes" => array(
////                    "nilai_entry" => array(
////                        "label" => "nilai diskon dari supplier",
////                        "defaultValue" => ".0",
////                        "addPoints" => array(1),
////                    ),
//                ),
//            ),
//        ),
//        // berada di midValidate() Transaksi
//        "efakturValidator" => array(
//            5 => array(
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
//            5 => array(
//                "previews" => true,
//                "enabled" => true,
//                "editabled" => false,
//                "label" => "INVOICE FROM VENDOR (*)",
//            ),
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
////            1 => array(
////                "diskonSupplier" => array(
////                    "helperName" => "he_cek_diskon_supplier_transaksi",
////                    "functionName" => "cekDiskonSupplierTransaksi",
////                    "params" => array(
////                        "cabang_id" => "placeID",
////                        "extern3_id" => "pihakID",
////                        "extern2_id" => "pihakMainID",
//////                        "extern_id" => "id",
////                    ),
////                ),
////            ),
//        ),
//        "pairInjectors" => array(
////            1 => array(
////                "diskonSupplier" => array(
////                    "items" => array(
////                        "targetKey" => "id",
////                        "targetColumn" => "diskon_supplier_nilai",
////                    ),
////                ),
////            ),
//        ),
//        //ini untuk build session jika dipicu dari data receiptElement
//        "pairElementGateBuilder" => array(
//            1 => array(
//                "kompensasiMethod" => array(
//                    "pairModel"=>"MdlProduk2",
//                    "pairModelKey"=>array("id"=>"extern2_id"),
//                    "pairSrcFields"=>array(
//                        "produk_kode" => "kode",
//                        "no_part" => "no_part",
//                        "label" => "label",
//                        "kode" => "kode",
//                        "satuan" => "satuan",
//                        "berat_gross" => "berat_gross",
//                        "lebar_gross" => "lebar_gross",
//                        "panjang_gross" => "panjang_gross",
//                        "tinggi_gross" => "tinggi_gross",
//                        "volume_gross" => "volume_gross",
//                        "barcode" => "barcode",
//                        "jml_serial" => "jml_serial",
//                        "kategori_id" => "kategori_id",
////                        "produk_sku" => "kode",
//                        "kategori_nama" => "kategori_nama",
//                        "produk_part_kategori_id" => "produk_part_kategori_id",
//                        "produk_part_kategori_nama" => "produk_part_kategori_nama",
//                        "produk_part_jenis_id" => "produk_part_jenis_id",
//                        "produk_part_jenis_nama" => "produk_part_jenis_nama",
//                        "produk_part_ukuran_id" => "produk_part_ukuran_id",
//                        "produk_part_ukuran_nama" => "produk_part_ukuran_nama",
//
//                    ),
//                    "helperName" => "",
//                    "functionName" => "",
//                    "index_key"=>"extern_id",
//                    "params" => array(
//                        "id" => "extern_id",
//                        "name" => "extern_nama",
//                        "produk_id" => "extern_id",
//                        "produk_nama" => "extern_nama",
//                        "extern_id" => "extern2_id",
//                        "extern_nama" => "extern2_nama",
//                        "jml" => "jml",
//                        "qty" => "qty",
//                        "produk_rel_harga" => "harga",
////                        "satuan" => "satuan",
////                        "produk_kode" => "produk_kode",
////                        "no_part" => "no_part",
////                        "label" => "label",
//                        "refID" => "transaksi_id",
//                    ),
//                    "srcGate" => "items",
//                    "targetGate" => "items5_sum",
//                    "targetGate2"=>array(
//                        "target"=>"items2",
//                        "produkUnitPart" => array(
//                            "outdoor" => array(
//                                "outdoor_id" => "outdoor_sku",
//                            ),
//                            "indoor" => array(
//                                "indoor_id_1" => "indoor_sku_1",
//                                "indoor_id_2" => "indoor_sku_2",
//                                "indoor_id_3" => "indoor_sku_3",
//                                "indoor_id_4" => "indoor_sku_4",
//                            ),
//                            "heater" => array(
//                                "heater_id" => "heater_nama",
//                            ),
//                            "part" => array(
//                                "produk_part_id_1" => "produk_part_nama_1",
//                                "produk_part_id_2" => "produk_part_nama_2",
//                                "produk_part_id_3" => "produk_part_nama_3",
//                            ),
//                        ),
//
//                    ),
//                    "trigerValue" => array(
//                        "kompensasiMethod" => "4"//ini fix sesuai yang akan dibuatkan gerbangnya
//                    ),
//
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
//        //----
//        "ppnCekDefaultValue" => array(
//            "enabled" => true,
//            "ppnPersenCheck" => 1,// 1 dan 0
//            "keyDetail" => array(
//                "srcMain" => "ppnPersenCheck",
//                "srcItem" => "ppnFactor",
//                "overWriteMain" => "ppnFactor",
//            ),
//        ),
//        "itemSettingDiskonPenjualan" => array(
////            1 => false,
//            4 => true,
//        ),
//        "itemPriceValidator" => array(
//            4 => array(
//                "enabled" => true,
//            ),
//        ),
//        //----
//        "connectToEdit" => array(
//            1 => array(
//                "enabled" => true,
//                "connectTo" => "3333re",
//                "label" => "EDIT PRE CREDIT NOTE",
//            ),
//        ),
//        "connectToReject" => array(
//            1 => array(
//                "enabled" => true,
//                "connectTo" => "3333rrj",
//                "label" => "REJECT PRE CREDIT NOTE",
//            ),
//            2 => array(
//                "enabled" => true,
//                "connectTo" => "3333rj",
//                "label" => "REJECT CREDIT NOTE",
//            ),
//        ),
//        "availDiskonPath" => "addons/Bi/viewAvailDiscVendor",
//    ),
    "3333_OLD" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "REALISASI KLAIM DISKON KE SUPPLIER",
        "label_keterangan" => "",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REALISASI KLAIM DISKON KE SUPPLIER",
                "actionLabel" => "simpan REALISASI KLAIM DISKON KE SUPPLIER",
                "source" => "",
                "target" => "3333",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),

        ),
        "template" => "template/transaksi_3_1.html",
        "selectorModel" => "MdlLockerStockDiskonVendor",
        "selectorSrcModel" => "MdlDiskonPembelianSupplier",
//        "validLocker"=>"true",
        "selectedPrice" => array(
//            "model" => "MdlHargaProdukPerSupplier",
//            "mdlFilter" => array("suppliers_id=pihakID"),
//            "label" => array("hpp"),
//            "key_label" => array(
//                "hpp" => "harga",
//            ),
//            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
//            "enabled" => true,
//            "mdlName" => "MdlLockerStockDiskonVendor",
        ),
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.diskon",
            "supplier_id=pihakID",
            "produk_id=pihakMainID",
            "nilai>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih nota/nomer GRN atau nomer input klaim dadakan",
        "selectorParamFields" => array(
            "id" => "id",
            "pym_src_id" => "transaksi_id",
            "nama" => "nomer",
            "transaksi_id" => "transaksi_id",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "label" => "label",
        ),
        "selectorViewedFields" => array(
            "fulldate",
            "nomer",
            "extern_nama",
            "extern2_nama",
        ),
        "selectorProcessor" => "_processSelectNota/select",//_processSelectNotaItem
        "selectorProcessorBi" => "_processSelectProduct/multiSelectBiNew",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih supplier",
        "pihakFilters" => array(
//            "country=.ID",
//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakAddValidate" => array(
            "mode" => "!=",
            "filter" => array(
                "country" => "ID",
            ),
            "label" => array(
                "country" => "FG purchasing (lokal) harus memilih vendor lokal atau perbaiki data vendor.",
            ),

        ),
        //---------------------------------------------------
        "mainselectorModel" => array(
            "MdlDtaBiayaProduksi" => array(
                "label" => "biaya produksi",
                "allowed_branch" => array(25)
            ),
            "MdlDtaBiayaUsaha" => array(
                "label" => "biaya usaha",
                "allowed_branch" => array(1, 21),
            ),
            "MdlDtaBiayaUmum" => array(
                "label" => "biaya umum",
                "allowed_branch" => array(1, 21, 25),
            ),

        ),
//        "pihakModelMain" => "MdlDiskonPembelianSupplier",
        "pihakModelMain" => "MdlSupplierDiskon",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "jenis klaim kepada supplier",
        "pihakMainFilters" => array(
//            "supplier_id=pihakID",
//            "is_pembayaran=.0",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        "pihakMainPair" => array(
            "MdlName" => "MdlLockerStockDiskonVendor",
            "filter" => array(
                "cabang_id=cabangID",
                "supplier_id=pihakID",
                "jumlah>.0",
            ),
            //            "viewdFields" => array("kode", "serial_no"),
        ),
        "pihakMainViewedFields" => array(
//            "per_supplier_diskon_nama",
            "nama",
//            "per_supplier_diskon_alias",
//            "nama",
//            "nilai",
//            "kode",
//            "serial_no",
        ),
        "pihakNameMainDiskon" => true,
//        "pihakNameMainDiskonIdSelector" => "per_supplier_diskon_id",
//        "pihakNameMainDiskonIdProcessor" => "per_supplier_diskon_id",
        "pihakNameMainDiskonIdSelector" => "id",
        "pihakNameMainDiskonIdProcessor" => "id",
        "pihakNameMainDiskonConfirm" => true,
        //---------------------------------------------------

        "shoppingCartNoteEnabled" => false, //tambahan notes per items dishoppingcart
        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "supplier/vendor",
            "nomer_top" => "Nomer",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_pre_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "PRE GRN number",
            ),
            "nomer_grn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "GRN number",
            ),
            "nomer_ppn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "realisasi ppn number",
            ),
            //            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nilai_pph23" => "nilai pph ps 23",
            "nilai_credit_note" => "nilai creditnote",
            "nilai_cash" => "kas diterima",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
            "pihakMainName" => "jenis diskon",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "Nomer",
                "item_fields" => "isi",
                "nomer_referensi" => array(
                    "step" => 1,
                    "key" => "nomer_referensi",
                    "label" => "Nomer Referensi",
                ),
                "pihakMainName" => "jenis diskon",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "nilai_pph23" => "nilai pph ps 23",
                "nilai_credit_note" => "nilai creditnote",
                "nilai_cash" => "kas diterima",
//                "disc" => "discount",
                // "nett1" => "sub amount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "kompensasiMethod__label" => "metode klaim",
                "cash_account__label" => "akun bank",
                "pph23Methode__label" => "status pph ps 23",
                "keterangan" => "keterangan",
                "description" => "catatan",
                "print_label" => "tool",
                "print_barcode_pembelian" => array(
                    "label" => "print Serial",
                    "key" => array(
                        "print_barcode_pembelian",
                        "print_barcode_pembelian_2",
                    ),
                ),
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            3 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "PRE PO number",
                "nomer_referensi" => array(
                    "step" => 1,
                    "key" => "nomer_referensi",
                    "label" => "Nomer Referensi",
                ),
                "nomer" => "Pre GRN number",
                "item_fields" => "isi",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",

                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "ids_his_3" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE GRN number",
                ),
                "nomer" => "GRN number",
                "item_fields" => "isi",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            5 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer_pre_grn" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE GRN number",
                ),
                "nomer_grn" => array(
                    "step" => 4,
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

                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "print_label" => "nomer",
                "print_barcode_pembelian" => "id",
                "print_barcode_pembelian_2" => "id",),
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
        ),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "id" => "transaksi_id",
            "nama" => "nomer",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
            "diskon_supplier_nilai" => "nilai_unit",
            "harga" => "nilai_unit",
            "jml" => "jumlah",
//            "subtotal" => "nilai",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "pembelian",
                "extern2_nama" => "produk",
                "extern_nama" => "jenis diskon",
//                "produk_kode" => "product code",
//                "no_part" => "part number",
                "jml" => "Qty",
//                "satuan" => "UOM",
            ),
            2 => array(
                "nama" => "Descriptions",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
                "diskon_supplier_nilai" => "nilai klaim",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "diskon_supplier_nilai" => "nilai klaim",
            ),
        ),
        "shoppingCartNoteEditabled" => array(
//            2 => true,
//            3 => true,
        ),
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
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
        ),
        "shoppingCartAmountValue" => array(
//            1 => "sub_diskon_supplier_nilai",
//            2 => "sub_diskon_supplier_nilai",
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "diskon_supplier_nilai" => "Total",
                "nilai_pph23" => "(pph23)",
                "grandtotal_netto" => "grand total",
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
            1 => array(
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
//            4 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
//                ),
//                "produk_nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
//                ),
//            ),
//            5 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
//                ),
//
//            ),
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
        "shoppingCartKlaimDiskonInfo" => array(
            "label_info" => "Anda akan klaim pihakMainLabel ke supplier vendorDetails__nama",
            "replacer" => array(
                "pihakMainLabel",
                "vendorDetails__nama",
            ),
        ),

        "tabHistoryFields" => array(
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => false,
//            ),
//            "suppliers_id" => array(
//                "label" => "By Vendor",
//                "allowFollowup" => true,
//            ),

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
//
//            ),
        ),
        "allowedMainEdit" => array("1"),
        "pairRegistries" => array(
            "main", "items",
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUPPLIER ",
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
            "kompensasiMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Metode Diskon pembelian",
//                "mdlName" => "MdlKompensasiMethodStatic",
                "mdlName" => "MdlKompensasiMethodRealisasiStatic",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1,),

            ),
            "pph23Methode" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "dipotong/tidak dipotong pph23",
//              "mdlName" => "MdlPph23MethodPotongan",
                "mdlName" => "MdlPph23MethodPotonganMode",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "nama",
                    "tarif" => "tarif (%)",
                ),
                "editPoints" => array(1),
            ),
            "dummyElement" => array(
                "hiddenBox" => true,
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
            ),
        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                "1" => array(
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
//                "2" => array(
////                    "pph23Methode" => array(
////                        "elementType" => "dataModel",
////                        "inputType" => "radio",
////                        "label" => "dipotong/tidak dipotong pph23",
//////                        "mdlName" => "MdlPph23MethodPotongan",
////                        "mdlName" => "MdlPph23MethodPotonganMode",
////                        "mdlFilter" => array(),
////                        "key" => "id",
////                        "labelSrc" => "name",
////                        "usedFields" => array(
////                            "name" => "nama",
////                            "tarif" => "tarif (%)",
////                        ),
////                        "editPoints" => array(1),
////                    ),
//                ),
//                "4" => array(
////                    "pph23Methode" => array(
////                        "elementType" => "dataModel",
////                        "inputType" => "radio",
////                        "label" => "dipotong/tidak dipotong pph23",
//////                        "mdlName" => "MdlPph23MethodPotongan",
////                        "mdlName" => "MdlPph23MethodPotonganMode",
////                        "mdlFilter" => array(),
////                        "key" => "id",
////                        "labelSrc" => "name",
////                        "usedFields" => array(
////                            "name" => "nama",
//////                            "tarif" => "tarif (%)",
////                        ),
////                        "editPoints" => array(1),
////                    ),
//                ),
            ),
            "pph23Methode" => array(
                1 => array(
                    "pph23MethodeTarif" => array(
                        "hiddenSelect" => true,
                        "hiddenBox" => true,
                        "elementType" => "dataModel",
                        "inputType" => "hidden",
                        "type" => "hidden",
                        "label" => "tarif pph23 (%)",
//                        "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganTarif",
                        "mdlFilter" => array(
                            "id=pph23Methode"
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "nama",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1),
                    ),
                ),
                2 => array(
                    "pph23MethodeTarif" => array(
//                        "hiddenSelect" => true,
                        "hiddenBox" => true,
                        "elementType" => "dataModel",
                        "inputType" => "hidden",
                        "label" => "tarif pph23 (%)",
//                        "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganTarif",
                        "mdlFilter" => array(
                            "id=pph23Methode"
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "nama",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1),
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
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
//                    "nilai_entry" => array(
//                        "label" => "nilai diskon dari supplier",
//                        "defaultValue" => ".0",
//                        "addPoints" => array(1),
//                    ),
                ),
            ),
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
//            1 => array(
//                "diskonSupplier" => array(
//                    "helperName" => "he_cek_diskon_supplier_transaksi",
//                    "functionName" => "cekDiskonSupplierTransaksi",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "extern3_id" => "pihakID",
//                        "extern2_id" => "pihakMainID",
////                        "extern_id" => "id",
//                    ),
//                ),
//            ),
        ),
        "pairInjectors" => array(
//            1 => array(
//                "diskonSupplier" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "diskon_supplier_nilai",
//                    ),
//                ),
//            ),
        ),
        //ini untuk build session jika dipicu dari data receiptElement
        "pairElementGateBuilder" => array(
            1 => array(
                "kompensasiMethod" => array(
                    "pairModel" => "MdlProduk2",
                    "pairModelKey" => array("id" => "extern2_id"),
                    "pairSrcFields" => array(
                        "produk_kode" => "kode",
                        "no_part" => "no_part",
                        "label" => "label",
                        "kode" => "kode",
                        "satuan" => "satuan",
                        "berat_gross" => "berat_gross",
                        "lebar_gross" => "lebar_gross",
                        "panjang_gross" => "panjang_gross",
                        "tinggi_gross" => "tinggi_gross",
                        "volume_gross" => "volume_gross",
                        "barcode" => "barcode",
                        "jml_serial" => "jml_serial",
                        "kategori_id" => "kategori_id",
//                        "produk_sku" => "kode",
                        "kategori_nama" => "kategori_nama",
                        "produk_part_kategori_id" => "produk_part_kategori_id",
                        "produk_part_kategori_nama" => "produk_part_kategori_nama",
                        "produk_part_jenis_id" => "produk_part_jenis_id",
                        "produk_part_jenis_nama" => "produk_part_jenis_nama",
                        "produk_part_ukuran_id" => "produk_part_ukuran_id",
                        "produk_part_ukuran_nama" => "produk_part_ukuran_nama",

                    ),
                    "helperName" => "",
                    "functionName" => "",
                    "index_key" => "extern_id",
                    "params" => array(
                        "id" => "extern_id",
                        "name" => "extern_nama",
                        "produk_id" => "extern_id",
                        "produk_nama" => "extern_nama",
                        "extern_id" => "extern2_id",
                        "extern_nama" => "extern2_nama",
                        "jml" => "jml",
                        "qty" => "qty",
//                        "produk_rel_harga" => "harga",
                        "produk_rel_harga" => "produk_rel_harga_after_pph",
//                        "satuan" => "satuan",
//                        "produk_kode" => "produk_kode",
//                        "no_part" => "no_part",
//                        "label" => "label",
                        "refID" => "transaksi_id",
                        "diskon_supplier_nilai" => "diskon_supplier_nilai",
                        "nilai_items_pph23" => "nilai_items_pph23",
                        "sub_nilai_items_pph23" => "sub_nilai_items_pph23",
                    ),
                    "srcGate" => "items",
                    "targetGate" => "items5_sum",
                    "targetGate2" => array(
                        "target" => "items2",
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
                    "trigerValue" => array(
                        "kompensasiMethod" => "4"//ini fix sesuai yang akan dibuatkan gerbangnya
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
                "enabled" => true,
            ),
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3333re",
                "label" => "EDIT PRE CREDIT NOTE",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3333rrj",
                "label" => "REJECT PRE CREDIT NOTE",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "3333rj",
                "label" => "REJECT CREDIT NOTE",
            ),
        ),
        "availDiskonPath" => "addons/Bi/viewAvailDiscVendor",
        //----
        "viewKlaimSupplier" => array(
            "enabled" => true,
            "jenisSource" => array("467"),
            "historyFields" => array(
                1 => array(
//                    "no" => "no",
                    "dtime" => "date",
                    "suppliers_nama" => "supplier/vendor",
                    "nomer" => "Nomer GRN",
                    "po" => array(
                        "step" => 2,
                        "key" => "nomer",
                        "label" => "nomer PO",
                    ),
                    "oleh_nama" => "person",

                    "diskon_1_nilai" => "diskon 1",
                    "diskon_2_nilai" => "diskon 2",
                    "diskon_3_nilai" => "diskon 3",
                    "diskon_4_nilai" => "diskon 4",
                    "diskon_5_nilai" => "diskon 5",
                    "diskon_6_nilai" => "diskon 6",
                    "diskon_8_nilai" => "diskon 7",
                    "diskon_7_nilai" => "free produk",
//                    "sub_total" => "subtotal",

//                    "kompensasiMethod__label" => "metode klaim",
//                    "cash_account__label" => "akun bank",
//                    "pph23Methode__label" => "status pph ps 23",
//

                    "keterangan" => "keterangan",
//                    "print_label" => "tool",
//                    "print_barcode_pembelian" => array(
//                        "label" => "print Serial",
//                        "key" => array(
//                            "print_barcode_pembelian",
//                            "print_barcode_pembelian_2",
//                        ),
//                    ),
                ),

            ),
            "historySumFields" => array(
                "diskon_1_nilai" => "diskon 1",
                "diskon_2_nilai" => "diskon 2",
                "diskon_3_nilai" => "diskon 3",
                "diskon_4_nilai" => "diskon 4",
                "diskon_5_nilai" => "diskon 5",
                "diskon_6_nilai" => "diskon 6",
                "diskon_8_nilai" => "diskon 7",
                "diskon_7_nilai" => "free produk",
            ),
            "historyKlaimFields" => array(
                "diskon_1_nilai" => "nilai_diklaim_1",
                "diskon_2_nilai" => "nilai_diklaim_2",
                "diskon_3_nilai" => "nilai_diklaim_3",
                "diskon_4_nilai" => "nilai_diklaim_4",
                "diskon_5_nilai" => "nilai_diklaim_5",
                "diskon_6_nilai" => "nilai_diklaim_6",
                "diskon_8_nilai" => "nilai_diklaim_8",
                "diskon_7_nilai" => "nilai_diklaim_7",
            ),
            "pairedModel" => array(
                "comName" => "ComRekeningPembantuPiutangSupplierDetailTransMain",
                "filter" => array(
                    "periode=forever",
                    "rekening=1010020030",
                ),
            ),
            "addPairedModel" => array(
                "mdlName" => "MdlSupplier",

            ),
            "addPairedModelLocker" => array(
                "mdlName" => "MdlLockerStockDiskonVendor",
                "filter" => array(
                    "jenis=diskon",
                    "state=active",
                ),
            ),
            "addTabHeaderLimit" => 10,
            "labelAlias" => array(
                1 => "ada",
                2 => "tidak ada",
                3 => "belum diklaim",
//                3 => "",
                4 => "klaim sebagian",
                5 => "sudah klaim (habis)",
            ),
        ),        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "extern2_nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
//            "jml" => "qty",
            "sub_diskon_supplier_nilai" => "nilai klaim",
        ),

        "linkMenu" => array(
            1 => array(
                "link" => "Create/index/3344",
                "label" => "TAMBAHAN DISKON",
            ),
            2 => array(
                "link" => "Create/index/3333",
                "label" => "REALISASI KLAIM KE SUPPLIER",
            ),
        ),
    ),

    // diskon supplier, dadakan
    "3344" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "TAMBAHAN DISKON",
        "label_keterangan" => "Tambahan Diskon: diskon yang diberikan diluar skema diskon dari supplier.",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PRE TAMBAHAN DISKON",
                "actionLabel" => "simpan PRE TAMBAHAN DISKON",
                "source" => "",
                "target" => "3344r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "TAMBAHAN DISKON",
                "actionLabel" => "approved TAMBAHAN DISKON",
                "source" => "3344r",
                "target" => "3344",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
            ),

        ),
        "template" => "template/transaksi_3.html",
        "selectorModel" => "{pihakMdlName}", // relatif by nota atau by produk
        "selectorSrcModel" => "{pihakMdlNameSrc}", // relatif by nota atau by produk

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
            "cabang_id=placeID",
            "jenis=.467",
            "suppliers_id=pihakID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih nota pembelian",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
        ),
        "selectorProcessor" => "_processSelectNotaItem/select",
        "selectorProcessorBi" => "_processSelectProduct/multiSelectBiNew",
        //--------------------------------------------
        "selectorMainFilters" => array(
            "barang" => array(
//                "extern_id=pihakID",
//                "cabang_id=placeID",
            ),
            "nota" => array(
//                "returned=.0",
                "jenis=.467",
                "suppliers_id=pihakID",
            ),
            "diskon_lain" => array(
//                "extern_id=pihakID",
//                "cabang_id=placeID",
                "jenis=.nonreguler",
            ),
        ),
        "selectorMainProcessor" => array(
            "nota" => "_processSelectNotaItem/select",
            "barang" => "_processSelectProduct/select",
            "diskon_lain" => "_processSelectProduct/select",
//            "diskon_lain" => "_processSelectProduct/select_diskon_lain",
        ),
        "selectorMainParamFields" => array(
            "barang" => array(
//                "id" => "produk_id",
                "id" => "id",
                "nama" => "nama",
                "produk_kode" => "kode",
                "satuan" => "satuan",
//                "jumlah" => "jumlah",
            ),
            "nota" => array(
                "id" => "id",
                "nama" => "nomer",
            ),
            "diskon_lain" => array(
//                "id" => "produk_id",
                "id" => "id",
                "nama" => "nama",

//                "produk_kode" => "kode",
//                "satuan" => "satuan",
//                "jumlah" => "jumlah",
//
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
            "diskon_lain" => array(
                "keterangan",
                "kode",
                "satuan",
                //                "jumlah",
            ),
        ),
        "pihakModelMain" => "MdlProdukDiskonSupplierMethodStatic",//MdlProdukReturnMethodStatic
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "metode klain nota/barang",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
            "pihakMdlNameSrc" => "mdl_name_src",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        //--------------------------------------------


        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih supplier",
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
        "pihakMdlName" => "MdlProduk",
        "pihakMdlNameSrc" => "MdlProduk",
        //---------------------------------------------------
        "shoppingCartNoteEnabled" => true, //tambahan notes per items dishoppingcart
        "shortHistoryFields" => array(
            //            "no" => "no",
//            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "supplier/vendor",
            "nomer_top" => "request number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "approval number",
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
//                "step" => 4,
//                "key" => "nomer",
//                "label" => "realisasi ppn number",
//            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "diskon_nama" => "jenis diskon",
            "item_fields" => "isi",
            "harga_kompensasi" => "amount",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
//
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "request number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "diskon_nama" => "jenis diskon",
                "item_fields" => "isi",
                "harga_kompensasi" => "amount",
//                "disc" => "discount",
//                // "nett1" => "sub amount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "request number",
                "nomer" => "approval number",
                "oleh_nama" => "person",
                "diskon_nama" => "jenis diskon",
                //                "transaksi_nilai" => "amount",
                "item_fields" => "isi",
                "harga_kompensasi" => "amount",
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
        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "produk_kode" => "sku",
            "referensi_item_supplier" => "ref",
            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "note" => "keterangan",
            "jml" => "qty",
            "harga_kompensasi" => "nilai",
            "sub_harga_kompensasi" => "subtotal",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
        //end
        "selectorFields" => array("id", "nama", "satuan"),
        "pihakFields" => array("id", "nama"),

        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
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
        ),
        "shoppingCartFields" => array(
            1 => array(
                "barcode" => "sku",
                "referensi_item_supplier" => "ref",
                "nama" => "Descriptions",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            2 => array(
                "barcode" => "sku",
                "referensi_item_supplier" => "ref",
                "nama" => "Descriptions",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            3 => array(
                "barcode" => "sku",
                "nama" => "Descriptions",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            4 => array(
                "barcode" => "sku",
                "nama" => "Descriptions",
                "produk_kode" => "product code",
                "no_part" => "part number",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
            5 => array(
                "barcode" => "sku",
                "nama" => "Descriptions",
                "produk_kode" => "Part-No",
                "jml" => "Qty",
                "satuan" => "UOM",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
                "harga_kompensasi" => "nilai",
                "sub_harga_kompensasi" => "subtotal",
            ),
            2 => array(
//                "harga_last_purchase" => "Last Purchase<br>Price",
                "harga_kompensasi" => "nilai",
                "sub_harga_kompensasi" => "subtotal",
            ),
        ),
        "shoppingCartNoteEditabled" => array(
//            1 => true,
//            3 => true,
        ),
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
            1 => array(
//                "referensi_item_supplier",
                "harga_kompensasi",
                "jml",
            ),
        ),
        "editableFieldstr" => array(
            1 => array(
                "referensi_item_supplier",
//                "harga_kompensasi",
//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "(jml*harga_kompensasi)",// hpp
            2 => "(jml*harga_kompensasi)",// hpp
            3 => "(jml*harga_kompensasi)",// hpp
            4 => "(jml*harga_kompensasi)",// hpp
            5 => "(jml*harga_kompensasi)",// hpp
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga_kompensasi" => "nilai diskon",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga_kompensasi" => "Total",
                // "ppv" => "index",
//                "ppn" => "VAT",
//                "hpp_nppn" => "Grand Total",
//
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
                    "gate" => "produk_sku_serial",
                ),
                "produk_nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
                ),
            ),
            4 => array(
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
            5 => array(
                "nama" => array(
                    "source" => "items3_sum",
                    "tipe" => "textarea",
                    "gate" => "produk_sku_serial",
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
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => false,
//            ),
//            "suppliers_id" => array(
//                "label" => "By Vendor",
//                "allowFollowup" => true,
//            ),

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
//
//            ),
        ),
        "allowedMainEdit" => array("1"),
        "pairRegistries" => array(
            "main", "items", "tableIn_master_values"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUPPLIER ",
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
            "transaksiDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Transaksi Pembelian ",
                "mdlName" => "MdlTransaksiData",
                "mdlFilter" => array(
                    "id=referenceID",
                ),
                "key" => "id",
                "labelSrc" => "nomer",
                "usedFields" => array(
                    "suppliers_nama" => "Supplier",
                    "nomer" => "nomer",
                    "nomer_top" => "nomer pre",
                ),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "kompensasiMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Metode Diskon pembelian",
                "mdlName" => "MdlKompensasiMethod",
                "mdlFilter" => array("id=.3"),// tembakan id
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "coa_code" => "kode",
                ),
                "editPoints" => array(1),
            ),
            "kompensasiTargetMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Pendapatan lain lain",
                "mdlName" => "MdlKompensasiTargetMethod",
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "coa_code" => "kode",
                ),
                "editPoints" => array(1),
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
                "editPoints" => array(1),
            ),
        ),
        "relativeElements" => array(
            "kompensasiMethod" => array(
                "1" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
                            "cabang_id=placeID",
                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                        ),
                        "editPoints" => array(1),
                    ),
                ),
                "2" => array(
                    "vendorRebate" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Diskon pembelian",
                        "mdlName" => "MdlSupplierDiskon",
                        "mdlFilter" => array("id=.6"),// tembakan id
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "coa_code" => "kode",
                        ),
                        "editPoints" => array(1),
                    ),
                ),
                "3" => array(
                    "vendorRebate" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "Diskon pembelian",
                        "mdlName" => "MdlSupplierDiskon",
                        "mdlFilter" => array("jenis=.nonreguler"),// tembakan id
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "nama",
                            "coa_code" => "kode",
                        ),
                        "editPoints" => array(1),
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
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(),
            ),
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
                "enabled" => true,
            ),
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3344re",
                "label" => "EDIT PRE CREDIT NOTE",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3344rrj",
                "label" => "REJECT PRE CREDIT NOTE",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "3344rj",
                "label" => "REJECT CREDIT NOTE",
            ),
        ),

        "linkMenu" => array(
            1 => array(
                "link" => "Create/index/3344",
                "label" => "TAMBAHAN DISKON",
            ),
            2 => array(
                "link" => "Create/index/3333",
                "label" => "REALISASI KLAIM KE SUPPLIER",
            ),
        ),
    ),

    "3333" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "REALISASI KLAIM DISKON KE SUPPLIER",
        "label_keterangan" => "",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "REALISASI KLAIM DISKON KE SUPPLIER",
                "actionLabel" => "simpan REALISASI KLAIM DISKON KE SUPPLIER",
                "source" => "",
                "target" => "3333",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),

        ),
        "template" => "template/transaksi_3_1.html",
        //---------------------------------------------------

        "selectorModel" => "MdlLockerStockDiskonVendor",
        "selectorSrcModel" => "MdlDiskonPembelianSupplier",
        "selectedPrice" => array(),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.diskon",
            "supplier_id=pihakID",
            "transaksi_id=pihakMainID",
            "nilai>.0",
        ),
        "selectorProccessFilters" => array(
            "cabang_id=placeID",
            "jenis=.diskon",
            "supplier_id=pihakID",
            "transaksi_id=pihakMainID",
            "nilai>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem_2",// bikin shopping cart background
        "selectorLabel" => "jenis klaim kepada supplier",
        "selectorParamFields" => array(
            "id" => "id",
            "pym_src_id" => "transaksi_id",
            "nama" => "nomer",
            "transaksi_id" => "transaksi_id",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "label" => "label",
        ),
        "selectorViewedFields" => array(
            "extern_nama",
            "nilai",
        ),
        "selectorViewedFieldsAkumulasi" => array(
            "nilai",
        ),
        "selectorProcessor" => "_processSelectNota/select_2",//_processSelectNotaItem
        "selectorProcessorBi" => "_processSelectProduct/multiSelectBiNew",
        "editHandlerMethod" => "select_2",
//        "selectorShoppingcart" => false,
        "selectorToMain" => array(
            "enabled" => true,
            "gate" => array(
                "pihakMainID_diskon" => "extern_id",
                "pihakMainName_diskon" => "extern_nama",
                "pihakMainLabel_diskon" => "extern_nama",
            ),
        ),
        "selectorToMode" => array(
//            "mode" => 1,// per-satu baris locker_diskon
            "mode" => 2,// akumulasi dengan key id_diskon
        ),
        //---------------------------------------------------
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "pilih supplier",
        "pihakFilters" => array(
//            "country=.ID",
//            "id=.-1",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        "pihakAddValidate" => array(
            "mode" => "!=",
            "filter" => array(
                "country" => "ID",
            ),
            "label" => array(
                "country" => "FG purchasing (lokal) harus memilih vendor lokal atau perbaiki data vendor.",
            ),

        ),
        //---------------------------------------------------
        "mainselectorModel" => array(
            "MdlDtaBiayaProduksi" => array(
                "label" => "biaya produksi",
                "allowed_branch" => array(25)
            ),
            "MdlDtaBiayaUsaha" => array(
                "label" => "biaya usaha",
                "allowed_branch" => array(1, 21),
            ),
            "MdlDtaBiayaUmum" => array(
                "label" => "biaya umum",
                "allowed_branch" => array(1, 21, 25),
            ),

        ),
        "pihakModelMain" => "MdlLockerStockDiskonVendor",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak2",
        "pihakMainLabel" => "pilih nota/nomer GRN(467) atau nomer input klaim tambahan(3344)",
        "pihakMainFilters" => array(
            "supplier_id=pihakID",
            "nilai>.0"
        ),
        "pihakMainProccessFilters" => array(
            "supplier_id=pihakID",
            "nilai>.0"
        ),

//        "pihakMainGroup" => array(
//            "enabled" => true,
//        ),
//        "pihakMainValueSrc2" => array(
//            "pihakMdlName" => "mdl_name",
//        ),
        "pihakMainProcessor" => "_processPihakMain/select_2",
//        "pihakMainPair" => array(
//            "MdlName" => "MdlLockerStockDiskonVendor",
//            "filter" => array(
//                "cabang_id=cabangID",
//                "supplier_id=pihakID",
//                "jumlah>.0",
//            ),
//        ),
        "pihakMainViewedFields" => array(
//            "nama",
            "nomer",
        ),
        "pihakNameMainDiskon" => true,
//        "pihakNameMainDiskonIdSelector" => "id",
//        "pihakNameMainDiskonIdProcessor" => "id",
        "pihakNameMainDiskonIdSelector" => "transaksi_id",
        "pihakNameMainDiskonIdProcessor" => "transaksi_id",
        "pihakNameMainDiskonConfirm" => true,
        //---------------------------------------------------

        "shoppingCartNoteEnabled" => false, //tambahan notes per items dishoppingcart
        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "supplier/vendor",
            "nomer_top" => "Nomer",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_pre_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "PRE GRN number",
            ),
            "nomer_grn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "GRN number",
            ),
            "nomer_ppn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "realisasi ppn number",
            ),
            //            "nomer" => "receipt number",
            "item_fields" => "isi",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nilai_pph23" => "nilai pph ps 23",
            "nilai_credit_note" => "nilai creditnote",
            "nilai_cash" => "kas diterima",
//            "disc" => "discount",
//            "ppn" => "ppn",
//            "nett" => "total amount",
            "pihakMainName" => "jenis diskon",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "Nomer",
                "item_fields" => "isi",
                "nomer_referensi" => array(
                    "step" => 1,
                    "key" => "nomer_referensi",
                    "label" => "Nomer Referensi",
                ),
//                "pihakMainName" => "jenis diskon",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "nilai_pph23" => "nilai pph ps 23",
                "nilai_credit_note" => "nilai creditnote",
                "nilai_cash" => "kas diterima/<br>(dibayar)",
//                "disc" => "discount",
                // "nett1" => "sub amount",
//                "ppn" => "ppn",
//                "nett" => "total amount",
                "kompensasiMethod__label" => "metode klaim",
                "cash_account__label" => "akun bank",
                "pph23Methode__label" => "status pph ps 23",
                "keterangan" => "keterangan",
                "description" => "catatan",
                "print_label" => "tool",
                "print_barcode_pembelian" => array(
                    "label" => "print Serial",
                    "key" => array(
                        "print_barcode_pembelian",
                        "print_barcode_pembelian_2",
                    ),
                ),
            ),
            2 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "keterangan" => "keterangan",
                "print_nvalas" => "tool",
            ),
            3 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "PRE PO number",
                "nomer_referensi" => array(
                    "step" => 1,
                    "key" => "nomer_referensi",
                    "label" => "Nomer Referensi",
                ),
                "nomer" => "Pre GRN number",
                "item_fields" => "isi",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                //                "harga" => "amount",
                //                "disc" => "discount",
                //                "ppn" => "ppn",
                //                "nett" => "total amount",

                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "ids_his_3" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE GRN number",
                ),
                "nomer" => "GRN number",
                "item_fields" => "isi",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            5 => array(
                "no" => "no",
                // "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "supplier/vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer_pre_grn" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "PRE GRN number",
                ),
                "nomer_grn" => array(
                    "step" => 4,
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

                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "print_label" => "nomer",
                "print_barcode_pembelian" => "id",
                "print_barcode_pembelian_2" => "id",),
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
        ),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "diskon_id" => "produk_id",
            "diskon_nama" => "nama",
            "id" => "transaksi_id",
            "nama" => "nomer",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
//            "diskon_supplier_nilai" => "nilai_unit",
//            "diskon_supplier_nilai" => "nilai",
            "harga" => "nilai_unit",
            "jml" => "jumlah",
        ),
        "shoppingCartFieldAkumulasiSrc" => array(
//            "diskon_supplier_nilai" => "nilai",//in dubuat oleh valuegates
            "harga" => "nilai",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "pembelian",
                "extern_nama" => "jenis diskon",
                "extern2_nama" => "isi",
                "jml" => "Qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "nilai klaim(unit)",
//                "diskon_supplier_nilai" => "nilai klaim",
            ),
            2 => array(
//                "diskon_supplier_nilai" => "nilai klaim",
                "harga" => "nilai klaim",
            ),
        ),
        "shoppingCartNoteEditabled" => array(
//            2 => true,
//            3 => true,
        ),
        "shoppingCartNoteType" => "textarea",
        "shoppingCartEditableFields" => array(
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
        ),
        "shoppingCartAmountValue" => array(
//            1 => "sub_diskon_supplier_nilai",
//            2 => "sub_diskon_supplier_nilai",
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartSumFields" => array(
            1 => array(
//                "diskon_supplier_nilai" => "Total",//before
                "harga" => "Total",
                "nilai_pph23" => "(pph23)",
                "grandtotal_netto" => "grand total",
            ),
        ),
        "shoppingCartSubDetailFields" => array(
            1 => array(
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
//            4 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
//                ),
//                "produk_nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
//                ),
//            ),
//            5 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_sku_serial",
//                ),
//
//            ),
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
        "shoppingCartKlaimDiskonInfo" => array(
            "label_info" => "Anda akan klaim pihakMainLabel ke supplier vendorDetails__nama. <br>Klaim Free Produk dilakukan terpisah, tidak bisa digabung dengan Diskon 1/2/3/Diskon Tambahan.",
            "replacer" => array(
                "pihakMainLabel",
                "vendorDetails__nama",
            ),
        ),
        "shoppingCartUnsetHeader" => array(
            "467" => array(
                "extern2_nama",
            ),
            "3344" => array(),
        ),

        "tabHistoryFields" => array(
//            "produk_id" => array(
//                "label" => "By Produk",
//                "allowFollowup" => false,
//            ),
//            "suppliers_id" => array(
//                "label" => "By Vendor",
//                "allowFollowup" => true,
//            ),

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
//
//            ),
        ),
        "allowedMainEdit" => array("1"),
        "pairRegistries" => array(
            "main", "items",
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUPPLIER ",
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
            "kompensasiMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Metode Diskon pembelian",
                "mdlName" => "MdlKompensasiMethodRealisasiStatic",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1,),
                "targetMethod" => array(
                    1 => "ReComKompensasiMethod",
                    2 => "ReComKompensasiMethod",
                    4 => "ReComKompensasiMethodPersediaan",
                    5 => "ReComKompensasiMethod",
                ),
            ),
//            "pph23Methode" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "dipotong/tidak dipotong pph23",
////              "mdlName" => "MdlPph23MethodPotongan",
//                "mdlName" => "MdlPph23MethodPotonganMode",
//                "mdlFilter" => array(),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "nama",
//                    "tarif" => "tarif (%)",
//                ),
//                "editPoints" => array(1),
//            ),
            "dummyElement" => array(
                "hiddenBox" => true,
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(1),
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
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "dipotong/tidak dipotong pph23",
//              "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganMode",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "nama",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1),
                    ),
                ),
                2 => array(
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "dipotong/tidak dipotong pph23",
//              "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganMode",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "nama",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1),
                    ),
                ),
                3 => array(
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "dipotong/tidak dipotong pph23",
//              "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganMode",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "nama",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1),
                    ),
                ),
                4 => array(
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "dipotong/tidak dipotong pph23",
//              "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganMode",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "nama",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1),
                    ),
                ),
                5 => array(
                    "logam_mulia" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "logam mulia dan permata",
                        "mdlName" => "MdlDtaLogamMulia",
                        "mdlFilter" => array(),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "showNull" => true,
                        "usedFields" => array(
                            "nama" => "nama",
                            "qty" => "jumlah (gram)",
                        ),
                        "editableUsedFields" => array(
                            "qty" => array(
                                "tipe" => "number",// kolom => tipe data
                                "default_value" => "default__logam_mulia__qty",
                            ),
                        ),
                        "editPoints" => array(1),
                    ),
                    "pph23Methode" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "status pph23",
//              "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganMode2",
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
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "tunai / akun bank",
                        "nullSrc" => "balance",
                        "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
                        "pairedModel" => array(
                            "mdlName" => "ComLockerValue",
                            "mdlMethod" => "fetchBalances",
                            "mdlFilter" => array(
                                "cabang_id" => "placeID",
                                "state" => ".active",
                            ),
                            "key" => "produk_id",
                            "rekening" => array(
                                "kas",
                            ),
                            "fieldID" => "nilai",
                            "fieldLabel" => "saldo",
                        ),
                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "account",
                            "saldo" => "balance",
                            "folders" => "acountMasterID",
                            "folders_nama" => "accountMaster",
                        ),
                        "editPoints" => array(1),
                        "noValidate" => true,
                    ),
                ),
            ),
            "pph23Methode" => array(
                1 => array(
                    "pph23MethodeTarif" => array(
                        "hiddenSelect" => true,
                        "hiddenBox" => true,
                        "elementType" => "dataModel",
                        "inputType" => "hidden",
                        "type" => "hidden",
                        "label" => "tarif pph23 (%)",
//                        "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganTarif",
                        "mdlFilter" => array(
                            "id=pph23Methode"
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "nama",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1),
                    ),
                ),
                2 => array(
                    "pph23MethodeTarif" => array(
//                        "hiddenSelect" => true,
                        "hiddenBox" => true,
                        "elementType" => "dataModel",
                        "inputType" => "hidden",
                        "label" => "tarif pph23 (%)",
//                        "mdlName" => "MdlPph23MethodPotongan",
                        "mdlName" => "MdlPph23MethodPotonganTarif",
                        "mdlFilter" => array(
                            "id=pph23Methode"
                        ),
                        "key" => "id",
                        "labelSrc" => "name",
                        "usedFields" => array(
                            "name" => "nama",
                            "tarif" => "tarif (%)",
                        ),
                        "editPoints" => array(1),
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
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
//                    "nilai_entry" => array(
//                        "label" => "nilai diskon dari supplier",
//                        "defaultValue" => ".0",
//                        "addPoints" => array(1),
//                    ),
                ),
            ),
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
//            1 => array(
//                "diskonSupplier" => array(
//                    "helperName" => "he_cek_diskon_supplier_transaksi",
//                    "functionName" => "cekDiskonSupplierTransaksi",
//                    "params" => array(
//                        "cabang_id" => "placeID",
//                        "extern3_id" => "pihakID",
//                        "extern2_id" => "pihakMainID",
////                        "extern_id" => "id",
//                    ),
//                ),
//            ),
        ),
        "pairInjectors" => array(
//            1 => array(
//                "diskonSupplier" => array(
//                    "items" => array(
//                        "targetKey" => "id",
//                        "targetColumn" => "diskon_supplier_nilai",
//                    ),
//                ),
//            ),
        ),
        //ini untuk build session jika dipicu dari data receiptElement
        "pairElementGateBuilder" => array(
            1 => array(
                "kompensasiMethod" => array(
                    "pairModel" => "MdlProduk2",
                    "pairModelKey" => array(
                        "id" => "extern2_id"
                    ),
                    "pairModelKeyReplacer" => array(
                        "key_src" => "diskon_id",
                        "key" => array(
                            7 => "extern_id",
                        ),
                    ),
                    "pairSrcFields" => array(
                        "produk_kode" => "kode",
                        "no_part" => "no_part",
                        "label" => "label",
                        "kode" => "kode",
                        "satuan" => "satuan",
                        "berat_gross" => "berat_gross",
                        "lebar_gross" => "lebar_gross",
                        "panjang_gross" => "panjang_gross",
                        "tinggi_gross" => "tinggi_gross",
                        "volume_gross" => "volume_gross",
                        "barcode" => "barcode",
                        "jml_serial" => "jml_serial",
                        "kategori_id" => "kategori_id",
//                        "produk_sku" => "kode",
                        "kategori_nama" => "kategori_nama",
                        "produk_part_kategori_id" => "produk_part_kategori_id",
                        "produk_part_kategori_nama" => "produk_part_kategori_nama",
                        "produk_part_jenis_id" => "produk_part_jenis_id",
                        "produk_part_jenis_nama" => "produk_part_jenis_nama",
                        "produk_part_ukuran_id" => "produk_part_ukuran_id",
                        "produk_part_ukuran_nama" => "produk_part_ukuran_nama",

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
                    "helperName" => "",
                    "functionName" => "",
                    "index_key" => "extern_id",
                    "params" => array(
                        "id" => "extern_id",
                        "name" => "extern_nama",
                        "produk_id" => "extern_id",
                        "produk_nama" => "extern_nama",
                        "extern_id" => "extern2_id",
                        "extern_nama" => "extern2_nama",
                        "diskon_id" => "diskon_id",
                        "diskon_nama" => "diskon_nama",
//                        "jml" => "jml",
//                        "qty" => "qty",
//                        "produk_rel_harga" => "harga",
                        "produk_rel_harga" => "produk_rel_harga_after_pph",
//                        "satuan" => "satuan",
//                        "produk_kode" => "produk_kode",
//                        "no_part" => "no_part",
//                        "label" => "label",
                        "refID" => "transaksi_id",
                        "diskon_supplier_nilai" => "diskon_supplier_nilai",
                        "nilai_items_pph23" => "nilai_items_pph23",
                        "sub_nilai_items_pph23" => "sub_nilai_items_pph23",
                    ),
                    "paramsQty" => array(
                        "jml" => "jml",
                        "qty" => "qty",
                    ),
                    "srcGate" => "items",
                    "targetGate" => "items5_sum",
                    "targetGate2" => array(
                        "target" => "items2",
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
                    "trigerValue" => array(
                        "kompensasiMethod" => "4"//ini fix sesuai yang akan dibuatkan gerbangnya
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
                "enabled" => true,
            ),
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3333re",
                "label" => "EDIT PRE CREDIT NOTE",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "3333rrj",
                "label" => "REJECT PRE CREDIT NOTE",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "3333rj",
                "label" => "REJECT CREDIT NOTE",
            ),
        ),
        "availDiskonPath" => "addons/Bi/viewAvailDiscVendor",
        //----
        "viewKlaimSupplier" => array(
            "enabled" => true,
            "jenisSource" => array("467"),
            "historyFields" => array(
                1 => array(
//                    "no" => "no",
                    "dtime" => "date",
                    "suppliers_nama" => "supplier/vendor",
                    "nomer" => "Nomer GRN",
                    "po" => array(
                        "step" => 2,
                        "key" => "nomer",
                        "label" => "nomer PO",
                    ),
                    "oleh_nama" => "person",

                    "diskon_1_nilai" => "diskon 1",
                    "diskon_2_nilai" => "diskon 2",
                    "diskon_3_nilai" => "diskon 3",
                    "diskon_4_nilai" => "diskon 4",
                    "diskon_5_nilai" => "diskon 5",
                    "diskon_6_nilai" => "diskon 6",
                    "diskon_8_nilai" => "diskon 7",
                    "diskon_7_nilai" => "free produk",
//                    "sub_total" => "subtotal",

//                    "kompensasiMethod__label" => "metode klaim",
//                    "cash_account__label" => "akun bank",
//                    "pph23Methode__label" => "status pph ps 23",
//

                    "keterangan" => "keterangan",
//                    "print_label" => "tool",
//                    "print_barcode_pembelian" => array(
//                        "label" => "print Serial",
//                        "key" => array(
//                            "print_barcode_pembelian",
//                            "print_barcode_pembelian_2",
//                        ),
//                    ),
                ),

            ),
            "historySumFields" => array(
                "diskon_1_nilai" => "diskon 1",
                "diskon_2_nilai" => "diskon 2",
                "diskon_3_nilai" => "diskon 3",
                "diskon_4_nilai" => "diskon 4",
                "diskon_5_nilai" => "diskon 5",
                "diskon_6_nilai" => "diskon 6",
                "diskon_8_nilai" => "diskon 7",
                "diskon_7_nilai" => "free produk",
            ),
            "historyKlaimFields" => array(
                "diskon_1_nilai" => "nilai_diklaim_1",
                "diskon_2_nilai" => "nilai_diklaim_2",
                "diskon_3_nilai" => "nilai_diklaim_3",
                "diskon_4_nilai" => "nilai_diklaim_4",
                "diskon_5_nilai" => "nilai_diklaim_5",
                "diskon_6_nilai" => "nilai_diklaim_6",
                "diskon_8_nilai" => "nilai_diklaim_8",
                "diskon_7_nilai" => "nilai_diklaim_7",
            ),
            "pairedModel" => array(
                "comName" => "ComRekeningPembantuPiutangSupplierDetailTransMain",
                "filter" => array(
                    "periode=forever",
                    "rekening=1010020030",
                ),
            ),
            "addPairedModel" => array(
                "mdlName" => "MdlSupplier",

            ),
            "addPairedModelLocker" => array(
                "mdlName" => "MdlLockerStockDiskonVendor",
                "filter" => array(
                    "jenis=diskon",
                    "state=active",
                ),
            ),
            "addTabHeaderLimit" => 10,
            "labelAlias" => array(
                1 => "ada",
                2 => "tidak ada",
                3 => "belum diklaim",
//                3 => "",
                4 => "klaim sebagian",
                5 => "sudah klaim (habis)",
            ),
        ),        //untuk penampil item di index followup tanpa klik dan hover
        "shortItemsFields" => array(
            "nama" => array(
                "label" => "product",
            ),
            "diskon_nama" => array(
                "label" => "diskon",
            ),
            "extern2_nama" => array(
                "label" => "isi",
            ),
            "jml" => "qty",
            "sub_diskon_supplier_nilai" => "nilai klaim",
        ),

        "linkMenu" => array(
            1 => array(
                "link" => "Create/index/3344",
                "label" => "TAMBAHAN DISKON",
            ),
            2 => array(
                "link" => "Create/index/3333",
                "label" => "REALISASI KLAIM KE SUPPLIER",
            ),
        ),
        //----
        "sessionBackSave" => array(
            "enabled" => true,
            "gateName" => "lastSession",
            "gate" => array(
                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "pihakName2" => "pihakName2",
                "pihakMainID" => "pihakMainID",
                "pihakMainName" => "pihakMainName",
                "pihakMainLabel" => "pihakMainLabel",
            ),
        ),
        "loaderTrigger" => array(
            "enabled" => true,
            "link" => "_selectorItem/selectItem_2/3333/MdlLockerStockDiskonVendor?search=",
            "linkSelected" => "_processSelectNota/select_2/3333",
        ),
        "switcherKeyItems" => array(
            "key" => array(
                6 => "extern2_id",
                7 => "extern2_id",
            ),
        ),
        //----
        "kompensasiMethodValidator" => array(
            "5" => array(// ini logam mulia, wajib ada qty > 0
                "enabled" => true,
                "kolom" => array(
                    "logam_mulia__qty" => "jumlah logam mulia yang diterima wajib diisi.",
                ),
            ),
        ),
        "klaimValidator" => array(
            "enabled" => true,
            "source" => "harga",
            "target" => "diskon_total_maksimal",
            "label" => "Diskon yang akan anda klaim tidak valid. Silahkan periksa kembali Pembelian, Diskon Tambahan atau hubungi admin.",
        ),
    ),

);
