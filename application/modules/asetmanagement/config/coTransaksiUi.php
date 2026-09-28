<?php

/**
 * Created by PhpStorm.
 * User: chepy
 * Date: 10/23/2021
 * Time: 13:16 PM
 */

//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion

$config["coTransaksiUi"] = array(

    //config pembelian aset/ Equipment dioffkan belum suport CLI
    "421" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "Aset purcashing",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "aset purcashing request",
                "actionLabel" => "save",
                "source" => "",
                "target" => "421r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "purcash. authorization",
                "actionLabel" => "approve request",
                "source" => "421r",
                "target" => "421",
                "userGroup" => "c_purchasing_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "goods receive note",
                "actionLabel" => "receive & make GRN",
                "source" => "421",
                "target" => "423",
                "userGroup" => "c_gudang",
                "stateLabel" => "GRN made",
                "stateColor" => "#ff7700",
                "stateCaption" => "receive by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
//            4 => array(
//                "label" => "realisasi ppn masukan",
//                "actionLabel" => "approve ppn masukan",
//                "buttonLabel" => "approve",
//                "source" => "423",
//                "target" => "111",
//                "userGroup" => "c_finance",
//                "stateLabel" => "approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "receive by",
//                "allowEdit" => true,
//                "allowIncrement" => true,
//            ),
        ),
        "template" => "template/transaksi_pihak4.html",
        "selectorModel" => "MdlAsetBerwujud",
        "selectorSrcModel" => "MdlAsetBerwujud",
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
            "jenis=.item",
            "folders=pihakMainID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "aset",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        //        "selectorProcessor" => "Selectors/_processSelectBiaya/select",
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        //tambahan pihak rules misal selector ppn
        "pihakModelMainRules" => "MdlPpnStatic",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "jenis ppn",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrcRules" => array(//            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",
        //tambahan pihak2
        "mainselectorModel" => array(),
        "pihakModelMain" => "MdlFolderAset",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "kategory",
        "pihakMainFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PRE-PO number",
            //            "nomer" => "receipt number",
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
            "item_fields"            => "isi",
            "oleh_nama" => "person",
            "transaksi_nilai" => "price",
            "ppn" => "ppn",
            "other" => "other (+)",
            "grand_total" => "total amount",
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
                "item_fields"            => "isi",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "item_fields"            => "isi",
                "oleh_nama" => "person",
                "transaksi_nilai" => "price",
                "ppn" => "ppn",
                "other" => "other (+)",
                "grand_total" => "total amount",
                "print_label" => "tool",
            ),
            3 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "item_fields"            => "isi",
                "oleh_nama" => "person",
                "transaksi_nilai" => "price",
                "ppn" => "ppn",
                "other" => "other (+)",
                "grand_total" => "total amount",
                "print_label" => "tool",
            ),


        ),
        "shortItemsFields"            => array(
//            "produk_kode" => "sku",
            "nama"        => array(
                "label"  => "product",
                "addKey" => "keterangan",
            ),
            "jml"         => "qty",
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
            //            "ppn_persen" => "ppn_persen",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
                //                "ppn_persen" => "vat(%)",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "hargapluspajak" => "harga beli<br><r>setelah</r> pajak",
                "harga" => "harga beli<br><r>sebelum</r> pajak",
                "dpp_persen_pengganti" => "dpp pengganti<br>(%)",
                "dpp_nilai_pengganti" => "dpp pengganti<br>(Rp)",
                //                "non_ppn" => "Non PPN<br>PPN (-)",
                "other" => "other (+)",
//                "subtotal" => "subtotal",
            ),
            2 => array(
                "harga" => "harga beli sebelum pajak",
                "dpp_persen_pengganti" => "dpp pengganti(%)",
                "dpp_nilai_pengganti" => "dpp pengganti (Rp)",
                "other" => "other (+)",
            ),
            3 => array(
                "harga" => "harga beli sebelum pajak",
                "dpp_persen_pengganti" => "dpp pengganti(%)",
                "dpp_nilai_pengganti" => "dpp pengganti (Rp)",
                "other" => "other (+)",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga_other" => "Total Amount",
                // "ppv" => "index",
//                "dpp_vat" => "DPP VAT",
                "ppn" => "VAT",
                //                "non_ppn" => "Total Non PPN",
                //                "other" => "Total Other",
                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga_other" => "Total Amount",
                // "ppv" => "index",
//                "dpp_vat" => "DPP VAT",
                "ppn" => "VAT",
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
                "hargapluspajak",
                "harga",
                "jml",
                "non_ppn",
                "other",
                "ppn_persen",
                "dpp_persen_pengganti",
                "dpp_nilai_pengganti",
                "reference",
//                "subtotal",
            ),
            2 => array(),
            3 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga+(jml*other)",
            2 => "jml*harga+(jml*other)",
            3 => "jml*harga+(jml*other)",
        ),
        "shoppingCartImageEnabled" => false,
        "shoppingCartImageType" => "images",
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),

        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
            "pihakMainName" => "kategori aset tetap",
            "pihakMainRulesID" => "jenis pajak (termasuk Pajak Masukan atau tidak termasuk Pajak Masukan)",
        ),
        "shopingCartDetailFields" => array(
            "3" => array(
                "fields" => array(
                    "nama" => "nama",
                    "label" => "label",
                    "merk" => "merk",
                    "serial_no" => "nomer seri",
                    "kode" => "kode",
                    //                    "deskripsi" => "deskripsi",
                    //                   "harga" =>"harga",
                ),
                "editable" => array(
                    "label" => "label",
                    "merk" => "merk",
                    "serial_no" => "nomer seri",
                    "kode" => "kode",
                    //                    "deskripsi" => "deskripsi",
                ),
                "gate" => "detail",
            ),
        ),
        "shopingcartAddDpp" => array(
            1 => array(
                "ppn" => array(
                    "dpp_pengganti" => "DPP(Tax Basis)"
                ),
            ),
            2 => array(
                "ppn" => array(
                    "dpp_pengganti" => "DPP(Tax Basis)"
                ),
            ),
            3 => array(
                "ppn" => array(
                    "dpp_pengganti" => "DPP(Tax Basis)"
                ),
            ),
            4 => array(
                "ppn" => array(
                    "dpp_pengganti" => "DPP(Tax Basis)"
                ),
            ),
            5 => array(
                "ppn" => array(
                    "dpp_pengganti" => "DPP(Tax Basis)"
                ),
            ),


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

            "fasilitasPajak" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Fasilitas pajak",
                "mdlName" => "MdlFasilitasPajakStatic",
                "mdlFilter" => array(),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",

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

            //            "shippingDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "Shipping Date",
            //                "inputType" => "date",
            //                "defaultValue" => date("Y-m-d"),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),
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
                "mdlName" => "MdlPaymentMethodCredit",
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
            //            "ppnExt" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "type of vat",
            //                "mdlName" => "MdlPpnStatic",
            //                "key" => "id",
            ////                "defaultValue" => "1",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "type",
            //                ),
            //                "editPoints" => array(1,),
            //            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "addDetailData" => array(
            3 => array("mdlName" => "MdlAsetDetail"),
        ),
        "ppnCekValidate" => array(
            "ppnPersenCheck" => "Silahkan menentukan Pajak Vendor (PPN atau NON PPN)",
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
        "previewCtr" => "Create",
    ),//done

    //config memasukan aset ke modal
    "422" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "Penambahan aset (dari modal didetailkan)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "penambahan aset",
                "actionLabel" => "save",
                "source" => "",
                "target" => "422r",
                "userGroup" => "sys",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "entry detail aset",
                "actionLabel" => "entry data/reject",
                "source" => "422r",
                "target" => "422o",
                "userGroup" => "c_gudang",
                "stateLabel" => "entry detail",
                "stateColor" => "#ff7700",
                "stateCaption" => "entry by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "receive aset",
                "actionLabel" => "receive/reject",
                "buttonLabel" => "receive",
                //                "actionLabel" => "receive",
                "source" => "422o",
                "target" => "422",
                "userGroup" => "c_gudang",
                "stateLabel" => "RECEIVE made",
                "stateColor" => "#ff7700",
                "stateCaption" => "receive by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        "template" => "template/transaksi_pihakExtern2.html",
        "selectorModel" => "MdlAsetBerwujud",
        "selectorSrcModel" => "MdlAsetBerwujud",
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
            "jenis=.item",
            "folders=pihakMainID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih jenis aset",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        //        "selectorProcessor" => "Selectors/_processSelectBiaya/select",
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",


        //tambahan pihak rules misal selector ppn
        //        "pihakModelMainRules" => "MdlPpnStatic",
        //        "pihakMainCallerRules" => "Selectors/_selectorPihakMainRules/selectPihak",
        //        "pihakMainLabelRules" => "jenis ppn",
        //        "pihakMainFiltersRules" => array(
        //            //            "id=cabang_id",
        //            //            "id<>cabang_id",
        ////            "id=.-1",
        //        ),
        //        "pihakMainValueSrcRules" => array(//            "pihakMdlName" => "mdl_name",
        //        ),
        //        "pihakMainProcessorRules" => "Selectors/_processPihakMainRules/select",
        //tambahan pihak2

        "mainselectorModel" => array(),

        "pihakModelMain" => "MdlFolderAset",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "kategory",
        "pihakMainFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

//        "pihakModelMain" => "MdlFolderAset",
//        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
//        "pihakMainLabel" => "kategory",
//        "pihakMainFilters" => array(
//            //            "id=cabang_id",
//            //            "id<>cabang_id",
//            //            "id=.-1",
//        ),
//        "pihakMainValueSrc2" => array(
//            "pihakMdlName" => "mdl_name",
//        ),
//        "pihakMainProcessor" => "_processPihakMain/select",

        //

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
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
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
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
                //                "ppn_persen" => "vat(%)",
            ),
            2 => array(
                "nama" => "item name",
                "jml" => "qty",
                "reference" => "reference",
            ),
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
                "reference" => "reference",
            ),
        ),
        "shoppingCartFields2" => array(
            //            1 => array(
            //                "nama" => "item name",
            //                "jml" => "qty",
            //                "reference" => "reference",
            ////                "ppn_persen" => "vat(%)",
            //            ),
            //            2 => array(
            //                "nama" => "item name",
            //                "jml" => "qty",
            //                "reference" => "reference",
            //            ),
            3 => array(
                "nama" => "nama",
                "label" => "label",
                "merk" => "merk",
                "serial_no" => "nomer seri",
                "kode" => "kode",
                //                    "deskripsi" => "deskripsi",
                //                   "harga" =>"harga",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
            3 => array(
                "harga" => "Price",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga" => "Total Amount",
                // "ppv" => "index",
                //                "ppn" => "VAT",
                //                "hpp_nppn" => "Grand Total",
            ),
            3 => array(
                "harga" => "Total Amount",
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
                "ppn_persen",
                "reference",
            ),
            2 => array(),
            3 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
            3 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => false,
        "shoppingCartImageType" => "images",
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),

        "shoppingCartRowValidators" => array(
            //            "pihakID" => "pihak ID",
            //            "pihakName" => "pihak name",
        ),
        "shopingCartDetailFields" => array(
            "2" => array(
                "fields" => array(
                    "nama" => "nama",
                    "label" => "label",
                    "merk" => "merk",
                    "serial_no" => "nomer seri",
                    "kode" => "kode",
                    //                    "deskripsi" => "deskripsi",
                    //                   "harga" =>"harga",
                ),
                "editable" => array(
                    "label" => "label",
                    "merk" => "merk",
                    "serial_no" => "nomer seri",
                    "kode" => "kode",
                    //                    "deskripsi" => "deskripsi",
                ),
                "gate" => "detail",
            ),
        ),
        "receiptElements" => array(
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

            //            "shippingDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "Shipping Date",
            //                "inputType" => "date",
            //                "defaultValue" => date("Y-m-d"),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),
            //            "dueDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "due date",
            //                "inputType" => "date",
            //                "defaultValue" => $date->format('Y-m-d'),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),
            //            "paymentMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "Payment Method",
            //                "mdlName" => "MdlPaymentMethodCredit",
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
            //            "ppnExt" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "type of vat",
            //                "mdlName" => "MdlPpnStatic",
            //                "key" => "id",
            ////                "defaultValue" => "1",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "type",
            //                ),
            //                "editPoints" => array(1,),
            //            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "addDetailData" => array(
            2 => array("mdlName" => "MdlAsetDetail"),
        ),
        "previewCtr" => "Create",
    ),

    //dimatikan dulu 29/04/2020
    //config hutang aktiva tetap
    "4821" => array(
        "icon" => "fa fa-money",
        "label" => "Aset A/P payment",
        //        "place" => "branch",
        "paymentConfig" => true,
        "place" => "center",
        "steps" => array(
            //            1 => array(
            //                "label"       => "pembayaran hutang",
            //                "actionLabel" => "pembayaran hutang",
            //                "source"      => "",
            //                "target"      => "489r",
            //                "userGroup"   => "sys",
            //                "stateLabel"  => "ready to be paid",
            //                "stateColor"  => "#dd3300",
            //            ),
            1 => array(
                "label" => "account payable payment",
                "actionLabel" => "process payment",
                "source" => "",
                "target" => "4821",
                "userGroup" => "c_finance",
                "stateLabel" => "completed",
                "stateColor" => "#009900",
                "stateCaption" => "PT. INDOSAN BERKAT BERSAMA",
                "stateFooter" => "paid by",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlNota",
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.467",
            "transaksi_nilai_sisa>.0",
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

        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
//            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "details" => "detail",
            "oleh_nama" => "person",
            "cash_account__label" => "account",

            "sisa" => "amount",
            "nilai_bayar" => "paid",
            "new_sisa" => "remain amount",
            "print_label" => "tool",
        ),
        "compactHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
        ),
        "extHistoryFields" => array(
            1 => array(
                //                "review_details" => "id",
                "print_label" => "nomer",
            ),
        ),
        "extHistoryFields2" => array(
            1 => array(
                "details" => "nama",
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

        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "creditValue" => "diskon",
            "extern_nilai2" => "extern_nilai2",
            "extern_nilai3" => "extern_nilai3",
            "extern_nilai4" => "extern_nilai4",
            "ppn" => "ppn",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "sisa" => "sisa",
                "extern_nilai2" => "DPP pph23*",
                "extern_nilai3" => "DPP ppn",
                "ppn" => "ppn",
                "extern_nilai4" => "Other (+)",
            ),

        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "nilai_round" => "due amount",
//                "sisa" => "due amount",
                //                "non_pph" =>"non pph23",
                //                "valid_dpp" =>"dpp pph23",
                //                "pph23_nilai" => "pph 23",
                //
                //                "creditAmount" => "supplier credit amount",
                ////                "harus_bayar" => "amount remains to pay",
                //                "payment_out" => "amount of payment",
                //
                //                "new_sisa" => "remain to pay (from list)",
            ),

        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,
        "tagihanSrc" => "sisa",
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
            //            "additional" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "additional",
            //                "mdlName" => "MdlStaticPayment",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "additional jenis",
            //                    //                            "currency" => "currency",
            //                ),
            //                "editPoints" => array(1,),
            //            ),
            //            "pph23Method" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "method of pph 23",
            //                "mdlName" => "MdlPph23Method",
            //                "key" => "id",
            ////                "defaultValue" => "item",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "method",
            //                    "tarif" => "tarif (%)",
            //                ),
            //                "editPoints" => array(1,),
            //                "targetMethod" => array(
            //                    "npwp" => "ReComPph23Npwp_purchasing",
            //                    "non_npwp" => "ReComPph23NonNpwp_purchasing",
            //                    "none" => "ReComPph23None_purchasing",
            //                ),
            //            ),

            //            "creditAmount" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "supplier credit amount",
            //                "mdlName" => "MdlSupplierCredit",
            //                "mdlFilter" => array(
            //                    "extern_id=pihakID",
            //                    "cabang_id=cabangID",
            //                ),
            //                "key" => "kredit",
            //                "labelSrc" => "kredit",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "noPrefetch" => true,
            //            ),
            "uangMuka" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Deposit(Uang muka)",
                "mdlName" => "MdlSupplierCreditUangMuka",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "sisa>.0",
                ),
                "key" => "id",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "vendor",
                    "extern_id" => "pihakID",
                    "note" => "note",
                    "id" => "rel_id",
                    "transaksi_id" => "transaksi_id",
                    "jenis" => "jenis",
                    "extern2_id" => "label_id",
                    "extern2_nama" => "label",

                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComUangMuka",
                    "calculate" => array(
                        "source" => "sisa",
                        "target" => "uang_muka_dipakai",
                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),
            "creditAmount" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "credit note",
                "mdlName" => "MdlPaymentAntiSource",
                "mdlFilter" => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    //                    "target_jenis=jenisTr",
                    "label=.piutang pembelian",
                    "sisa>.0",
                ),
                "key" => "sisa",
                "labelSrc" => "sisa",
                "usedFields" => array(
                    "extern_nama" => "vendor name",
                    //                    "transaksi_id" => "return ID",
                    //                    "nomer" => "return number",
                    "sisa" => "avail credit",
                    //                    "jenis" => "jenis",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "noPrefetch" => true,
                "pairMethod" => array(
                    "recom" => "ReComCreditNote",
                    "calculate" => array(
                        "source" => "creditNote",
                        "target" => "credit_note_dipakai",
                        "pair_source" => "sisa",//sunbe sumber yang dibandingkan
                    ),

                    //                    "customer" => "ReComDiscCustomer",
                ),
            ),
            //            "cashMethode" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "Metode rekening",
            //                "mdlName" => "MdlCashAccountStatic",
            //                "mdlFilter" => array(
            ////                    "extern_id=pihakID",
            ////                    "cabang_id=cabangID",
            ////                    "sisa>.0",
            //                ),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "method",
            ////                    "extern_id" => "pihakID",
            //
            //                ),
            //                "editPoints" => array(1,),
            //                "noValidate" => true,
            //                "noPrefetch" => true,
            ////                "pairMethod" => array(
            ////                    "recom" => "ReComUangMuka",
            ////                    "calculate" => array(
            ////                        "source" => "uangMuka",
            ////                        "target" => "uang_muka_dipakai",
            ////                        "pair_source" => "nilai_sisa",//sunbe sumber yang dibandingkan
            ////                    ),
            //
            //
            //            ),

            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "showNull" => true,
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

                    "label" => "cash account", "rekening" => array(
                        "kas", "plafon hutang bank",
                    ),
                    "fieldID" => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName" => "MdlBankAccount_cash_and_in_and_koran",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account",
                    "saldo" => "balance",
                    "folders" => "acountMasterID",
                    "folders_nama" => "accountMaster",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
                "pairMethod" => array(
                    "recom" => "ReComCashMethode",
                    "calculate" => array(
                        "source" => "cash_account",
                        "prefix" => "cashMethode",
                        "target" => "",
                    ),
                ),
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
                "saldoRekening" => array(
                    "helperName" => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params" => array(
                        "cabang_id" => "placeID",
                    ),
                    "target" => array("main", "out_master"),
                ),
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            //            "nilai_entry" => "amount of payment",
            //            "cash_account__saldo" => "saldo bank kurang",
        ),
        "shoppingCartUnionValidators" => array(
            array(
                "creditAmount" => "credit note",
                "uang_muka_dipakai" => "deposit",
                "nilai_entry" => "payment",//sementara untuk lolosin bayar pakai keuntungan kurs
                //                "cash_account__saldo" => "saldo bank kurang",
            ),
        ),
        "shopingCartUnionComparison" => array(
            array(
                "nilai_entry" => "payment belum diisi",
                "cash_account__saldo" => "cash account belum dipilih",
            ),

        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    //                    "amount" => array(
                    //                        "label" => "total amount+",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue" => "sisa",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    //                    "non_pph" => array(
                    //                        "label" => "non pph+",
                    //                        "defaultValue" => ".0",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('extern_nilai2_1').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('extern_nilai2_1').innerHTML;}",//dppp belum kesimpen jadinya off dulu
                    ////                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('nilai_entry').innerHTML) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('nilai_entry').innerHTML;}",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "valid_dpp" => array(
                    //                        "label" => "DPP PPh 23",
                    //                        "defaultValue" => "valid_dpp",
                    //                        "maxValue" => "valid_dpp",
                    //                        "minValue" => "valid_dpp",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "pph23_nilai" => array(
                    //                        "label" => "PPh 23",
                    //                        "defaultValue" => "pph23_nilai",
                    //                        "maxValue" => "pph23_nilai",
                    //                        "minValue" => "pph23_nilai",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "uangMuka" => array(
                    //                        "label" => "paid by deposit",
                    //                        "defaultValue" => "uangMuka",
                    //                        "maxValue" => "uangMuka",
                    //                        "minValue" => "uangMuka",
                    //                        "keyPressAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),

                    "sisa" => array(
                        "label" => "sisa",
                        "defaultValue" => "sisa",
                        "maxValue" => "sisa",
                        "minValue" => "sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),
                    "uang_muka_dipakai" => array(
                        "label" => "paid by deposit",
                        "defaultValue" => "0",
                        "maxValue" => "uang_muka_dipakai",
                        "minValue" => "uang_muka_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "credit_note_dipakai" => array(
                        "label" => "credit note",
                        "defaultValue" => "credit_note_dipakai",
                        "maxValue" => "credit_note_dipakai",
                        "minValue" => "credit_note_dipakai",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "nilai_entry" => array(
                        "label" => "payment",
                        "defaultValue" => ".0",
                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('sisa').value;}",
//                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('new_sisa').value;}",
                        "addPoints" => array(1,),
                    ),

                    //                    "payment_out" => array(
                    //                        "label" => "cash payout",
                    //                        "defaultValue" => ".0",
                    //                        "maxValue" => "new_sisa",
                    //                        "minValue" => "",
                    //                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('new_sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('new_sisa').value;}",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "payment_out" => array(
                    //                        "label" => "payment",
                    //                        "defaultValue" => "pay_out",
                    //                        "maxValue" => "new_sisa",
                    //                        "minValue" => "",
                    //                        'disabled' => "disabled",
                    ////                        "keyupAction" => "if(parseFloat(removeCommas(this.value))>parseFloat(removeCommas(document.getElementById('new_sisa').value)) || parseFloat(removeCommas(this.value))<0){this.value=document.getElementById('new_sisa').value;}",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "new_sisa" => array(
                        "label" => "balance of invoice",
                        "defaultValue" => "new_sisa",
                        "maxValue" => "new_sisa",
                        "minValue" => "new_sisa",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "relativeElements" => array(
            "pph23Method" => array(
                "1" => array(
                    "pph23Method__desc" => array(
                        "elementType" => "dataField",
                        "label" => "SKB",
                        "inputType" => "text",
                        "defaultValue" => "",
                        "editPoints" => array(1),
                    ),
                ),
            ),
            //            "cashMethode" => array(
            //                "reguler" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "cash account",
            //                        "showNull" => true,
            //                        "nullSrc" => "balance",
            //                        "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
            //                        "pairedModel" => array(
            //                            "mdlName" => "ComLockerValue",
            //                            "mdlMethod" => "fetchBalances",
            //                            "mdlFilter" => array(
            //                                "cabang_id" => "placeID",
            //                                "state" => ".active",
            //                            ),
            //                            "key" => "produk_id",
            //                            "rekening" => "kas",
            //                            "fieldID" => "nilai",
            //                            "fieldLabel" => "saldo",
            //                        ),
            //                        "mdlName" => "MdlBankAccount_cash_and_in",
            //                        "mdlFilter" => array(
            //                            "cabang_id=placeID",
            //                        ),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "account",
            //                            "saldo" => "balance",
            //                            "folders" => "acountMasterID",
            //                            "folders_nama" => "accountMaster",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //                "rekening_koran" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "rekening koran",
            //                        "pairedModel" => array(
            //                            "mdlName" => "ComLockerValue",
            //                            "mdlMethod" => "fetchBalances",
            //                            "mdlFilter" => array(
            //                                "cabang_id" => "placeID",
            //                                "state" => ".active",
            //                            ),
            //                            "key" => "produk_id",
            //                            "rekening" => "plafon hutang bank",
            //                            "fieldID" => "nilai",
            //                            "fieldLabel" => "saldo",
            //                        ),
            //                        "mdlName" => "MdlRekeningKoran",
            //                        "mdlFilter" => array(
            //                            "cabang_id=placeID",
            ////                     "id=pihakRelId",
            //                        ),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "account",
            //                            "saldo" => "balance",
            //                            "folders" => "acountMasterID",
            //                            "folders_nama" => "accountMaster",
            //                        ),
            //                        "editPoints" => array(1),
            //                        "noValidate" => true,
            //
            //                        //perhitungan rekening koran hutang vs kas(CN rekening koran)
            ////                        "pairMethod" => array(
            ////                            "recom" => "ReComRekeningKoran",
            ////                            "calculate" => array(
            ////                                "jenis_source" => "cashMethode",
            ////                                "source" => "nilai_entry",
            ////                                "target" => "credit_note_dipakai",
            ////                                "pair_source" => "nilai_entry",//sumber yang dibandingkan
            ////                                "id" => "cash_account",
            ////                                "mdlName" => "ComRekeningPembantuKas",
            ////                            ),
            ////                        ),
            //                    ),
            //                ),
            //            ),

            //            "branchTarget" =>array(
            //                "-1" =>array(
            //                    "externMain"=>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "transfer expense to",
            //                        "mdlName" => "MdlBiayaMethodGeneral",
            //                        "mdlFilter" => array(),
            //                        "key" => "nama",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "kategori biaya",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //            ),
            //            "externMain" =>array(
            //                "biaya umum" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaUmum",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "targetMethod" => array(
            ////                            "biaya usaha" => "ReComBiayaUsaha_payment",
            //                            "biaya umum" => "ReComBiayaUmum_payment",
            ////                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
            ////                            "none" => "ReComPph23None_purchasing",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //                ),
            //                "biaya usaha" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaUsaha",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "targetMethod" => array(
            //                            "biaya usaha" => "ReComBiayaUsaha_payment",
            ////                            "biaya umum" => "ReComBiayaUmum_payment",
            ////                            "non_npwp" => "ReComPph23NonNpwp_purchasing",
            ////                            "none" => "ReComPph23None_purchasing",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //
            //                ),
            //                "biaya produksi" =>array(
            //                    "dtaDetail" =>array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "expense details",
            //                        "mdlName" => "MdlDtaBiayaProduksi",
            //                        "mdlFilter" => array(),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "description" => "",
            //                        "usedFields" => array(
            //                            "nama" => "beban biaya",
            //                        ),
            //                        "editPoints" => array(1,),
            //                        "noValidate" => true,
            //                    ),
            //
            //                ),
            //            ),
        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "receipt number",
            "nomer_top" => "receipt ref.",
            "refNum" => "return ref.",
            "fulldate" => "date",
            "tagihan" => "due amount",
            "refValue" => "returned",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "vendor",
            "tagihan" => "due amount",
            "terbayar" => "paid",
            "diskon" => "discount",
            "sisa" => "due remain",
        ),
        "resumeFieldNames" => array(
            "selectFields" => "suppliers_nama",
            "title" => "vendor",
        ),
        "previewCtr" => "Create",
        //        "dueDateReader" => true,
    ),

    //distribusi aset pusat
    "2483" => array(
        "icon" => "fa fa-money",
        "label" => "aset tetap distribution",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "aset distribution",
                "actionLabel" => "aset distribution",
                "source" => "",
                "target" => "2483r",
                //                "userGroup"    => "disabled",
                "userGroup" => "c_gudang",
                "stateLabel" => "sending",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
                "allowReject" => true,
            ),
            2 => array(
                "label" => "distribution authorization",
                "actionLabel" => "approve distribution",
                "source" => "2483r",
                "target" => "2483",
                "userGroup" => "c_gudang_out",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_pettycash.html",
        //        "selectorModel" => "MdlLockerStockAktiva",
        "selectorModel" => "MdlAsetDetail",
        //        "selectorSrcModel" => "MdlLockerStockAktiva",
        "selectorSrcModel" => "MdlAsetDetail",

        "selectedPrice" => array(
            "model" => "MdlHargaAktivaTetap",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockAktiva",
        ),
        "selectorFilters" => array(
            "jenis=.item",
            "folders=pihakMainID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "aktiva tetap name",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "serial_no" => "serial_no",
            "alias" => "alias",
            //            "label" => "label",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "alias",
            "kode",
        ),
        "selectorProcessor" => "_processSelectAktiva/select",
        //        "selectorProcessor" => "Selectors/_processSelectBiaya/select",
        //        "selectorProcessor" => "Selectors/_processSelectProduk/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCabang",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "branch",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "pihakModelMain" => "MdlFolderAset",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "kategory",
        "pihakMainFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "distributor",
            "cabang2_nama" => "cabang tujuan",
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
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "PO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang2_nama" => "cabang tujuan",
                "nomer_top" => "PO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
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
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "distributor",
            "cabang2_nama" => "cabang tujuan",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
        ),
        "shortStepHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "sender",
            "cabang_nama" => "recipient",
            "2483r" => "request number",
            "2483" => "approval number",
            "2485" => "receipt number",
            "oleh_nama" => "person",
            "next_pic" => "next step otorisator",
        ),
        "selectorFields" => array("id", "nama", "alias"),
        "pihakFields" => array("id", "nama", "alias"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "merk" => "merk",
            "serial_no" => "serial_no",
            "kode" => "kode",
            //            "reference" => "reference",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "aktiva",
                "merk" => "merk",
                "kode" => "kode",
                "serial_no" => "serial",
                "jml" => "qty",
            ),
            2 => array(
                "nama" => "aktiva",
                "merk" => "merk",
                "kode" => "kode",
                "serial_no" => "serial",
                "jml" => "qty",
            ),
            3 => array(
                "nama" => "aktiva",
                "merk" => "merk",
                "kode" => "kode",
                "serial_no" => "serial",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(//                "harga" => "price",
            ),
            2 => array(//                "harga" => "price",
            ),
            3 => array(//                "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartEditableFields" => array(
            1 => array(),
            2 => array(),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
            3 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),

        "shoppingCartFieldValidators" => array(
            //            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "allowedMainEdit" => array(),

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
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "pairMakers" => array(
            1 => array(
                "stokAktiva" => array(
                    "helperName" => "he_cek_stock_aktiva_locker",
                    "functionName" => "cekStockAktivaTetap",
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
                "stokAktiva" => array(
                    "items" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "connectTo" => "2485",
        "validLocker" => true,
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "previewCtr" => "Create",
    ),//done

    //penerimaan distribusi aset di cabang
    "2485" => array(
        "icon" => "fa fa-money",
        "label" => "aset distribution",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "distribution iniation",
                "actionLabel" => "create",
                "source" => "",
                "target" => "2485r",
                //                "userGroup"    => "disabled",
                "userGroup" => "sys",
                "stateLabel" => "ordered",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "aset reception",
                "actionLabel" => "receive",
                "source" => "2485r",
                "target" => "2485",
                "userGroup" => "o_gudang",
                //                "userGroup"      => "disabled",
                "stateLabel" => "ready to received",
                "stateColor" => "#ff7700",
                "stateCaption" => "receive by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),

        ),
        //        "template"             => "application/template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlAktivaTetap",
        "selectorSrcModel" => "MdlAktivaTetap",
        "selectedPrice" => array(
            "model" => "MdlHargaAktiva",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "jenis=.item",
            "folders=.1",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "aktiva tetap name",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        //        "selectorProcessor" => "Selectors/_processSelectRekeningImporter/select",
        "editHandlerMethod" => "select",
        //        "editHandlerMethod"    => "edit",

        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakFilters" => array(//            "id=cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",


        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "distributor",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
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
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang2_nama" => "distributor",
            "nomer_top" => "PO number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
            "merk" => "merk",
            "serial_no" => "serial_no",
            "reference" => "reference",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "aktiva",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "aktiva",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            3 => array(
                "nama" => "aktiva",
                //                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(//                "harga" => "price",
            ),
            2 => array(//                "harga" => "price",
            ),
            3 => array(
                "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
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
            3 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "allowedMainEdit" => array(),

        "receiptElements" => array(
            //            "cabang" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "target branch",
            //                "mdlName" => "MdlCabang",
            //                "mdlFilter" => array("id<>placeID"),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "usedFields" => array(
            //                    "nama" => "branch",
            //                ),
            //                "editPoints" => array(1, 2),
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
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "previewCtr" => "Create",
    ),//done

    //config depresiasi
    "8786" => array(
        "icon" => "fa fa-money",
        "label" => "penyusutan aset berwujud (PUSAT)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request depresiasi (PUSAT)",
                "actionLabel" => "request for expense (PUSAT)",
                "source" => "",
                "target" => "8786r",
                "userGroup" => "sys",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "authorization depresiasi (PUSAT)",
                "actionLabel" => "approve request penyusutan (PUSAT)",
                "source" => "8786r",
                "target" => "8786",
                "userGroup" => "o_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        //        "template" => "application/template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlSetupDepresiasi",
        "selectorSrcModel" => "MdlSetupDepresiasi",
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
            "nama",
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
            "item_fields" => "isi",
            "transaksi_nilai" => "amount",
            "oleh_nama" => "person",
//            "keterangan" => "keterangan",
            "description" => "keterangan",
        ),
        "shortItemsFields" => array(
            "id" => "pid",
            "nama" => "details",
            "jml" => "qty",
            "harga" => "amount",
        ),

        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "pid" => "id",
            "nama" => "nama",
            "label" => "label",
            "kode" => "kode",
            "merk" => "merk",
            "serial_no" => "serial_no",
            //            "reference" => "reference",
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
        "pairMakers" => array(
            2 => array(
                "preBiaya" => array(
                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    "functionName" => "cekPairProduksiPreBiaya",
                    "source" => "items",
                    "key" => "pihakMainChild",
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
        "previewCtr" => "Create",
        "tambahanJudul" => "APPROVAL PENYUSUTAN atas AUTO REQUEST",
        "pairRegistries" => array(
            "main", "items"
        ),
    ),
    "8787" => array(
        "icon" => "fa fa-money",
        "label" => "penyusutan aset berwujud",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request depresiasi",
                "actionLabel" => "request for expense",
                "source" => "",
                "target" => "8787r",
                "userGroup" => "sys",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "authorization depresiasi",
                "actionLabel" => "approve request penyusutan",
                "source" => "8787r",
                "target" => "8787",
                "userGroup" => "o_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        //        "template" => "application/template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlSetupDepresiasi",
        "selectorSrcModel" => "MdlSetupDepresiasi",
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
            "nama",
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
            "item_fields" => "isi",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
            "oleh_nama" => "person",
        ),
        "shortItemsFields" => array(
            "id" => "pid",
            "nama" => "details",
            "jml" => "qty",
            "harga" => "amount",
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),
        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",

            //            "reference" => "reference",
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
        "pairMakers" => array(
            2 => array(
                "preBiaya" => array(
                    //                    "helperName" => "he_pair_produksi_prebiaya_helper",
                    //                    "functionName" => "cekPairProduksiPreBiaya",
                    "helperName" => "he_pair_produksi_prebiaya_depresiasi_helper",
                    "functionName" => "cekPairProduksiPreBiayaDepresiasi",
                    "source" => "items",
                    "key" => "pihakMainChild",
                ),
            ),
        ),
        "pairInjectors" => array(
            //            2 => array(
            //                "preBiaya" => array(
            //                    "items" => array(
            //                        "targetKey" => "id",
            //                        "targetColumn" => "costName",
            //                    ),
            //                ),
            //            ),
        ),
        "previewCtr" => "Create",
        "tambahanJudul" => "APPROVAL PENYUSUTAN atas AUTO REQUEST",
        "pairRegistries" => array(
            "main", "items"
        ),
    ),
    "8788" => array(
        "icon" => "fa fa-money",
        "label" => "penyusutan aset tak berwujud",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request depresiasi",
                "actionLabel" => "request for expense",
                "source" => "",
                "target" => "8788r",
                "userGroup" => "sys",
                "stateLabel" => "request",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "authorization depresiasi",
                "actionLabel" => "approve request",
                "source" => "8788r",
                "target" => "8788",
                "userGroup" => "o_finance",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
        //        "template" => "application/template/transaksi_nopihak.html",
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlSetupDepresiasi",
        "selectorSrcModel" => "MdlSetupDepresiasi",
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
            "nama",
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
            "item_fields" => "isi",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
            "oleh_nama" => "person",
        ),
        "shortItemsFields" => array(
            "id" => "pid",
            "nama" => "details",
            "jml" => "qty",
            "harga" => "amount",
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
        "previewCtr" => "Create",
        "tambahanJudul" => "APPROVAL PENYUSUTAN atas AUTO REQUEST",
        "pairRegistries" => array(
            "main", "items"
        ),
    ),

    //config jual asset

    "8789" => array(
        "icon" => "fa fa-money",
        "label" => "aset sales",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "aset sales request",
                "actionLabel" => "create",
                "source" => "",
                "target" => "8789r",
                "userGroup" => "o_finance",
                "stateLabel" => "creadted",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepare by",
            ),
            2 => array(
                "label" => "INVOICE",
                "actionLabel" => "approve & create invoice",
                "source" => "8789r",
                "target" => "8789",
                "userGroup" => "o_finance_spv",
                "stateLabel" => "sent",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_pihak4.html",
        "selectorModel" => "MdlLockerStockAktiva",
        "selectorSrcModel" => "MdlAsetDetail",
        "selectedPrice" => array(
            "model" => "MdlHargaAktivaTetap",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "nilai",
            ),
            "mainSrc" => "hpp",
        ),
        "selectedPrice2" => array(
            "model" => "MdlLockerValue",
            "label" => array("aktiva"),
            "key_label" => array(
                "aktiva" => "nilai",
            ),
            "mainSrc" => "aktiva",
        ),
        "lockerCheck" => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockAktiva",
        ),
        "selectorFilters" => array(
            "cabang_id=placeID",
            "jenis=.aktiva",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorFilters2" => array(
            "folders=pihakMainID",
        ),
        "selectorCaller" => "_selectorItemAktiva/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih aset yg akan dijual",
        "selectorParamFields" => array(
            "id" => "produk_id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(),
        //        "selectorProcessor" => "Selectors/_processSelectBiaya/select",
        "selectorProcessor" => "_processSelectAktiva/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakFilters" => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",

        "pihakModelMainRules" => "MdlPpnKeluaranStatic",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "jenis ppn keluaran",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrcRules" => array(//            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",

        "pihakModelMain" => "MdlFolderAset",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "kategory",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(
            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            //            "cabang_nama" => "distributor",
            //            "cabang2_nama" => "cabang tujuan",
            "nomer_top" => "request sales aset number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                "nomer" => "receipt number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
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
            "initPrices" => "jual",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "aktiva",
                "jml" => "qty",
            ),
            2 => array(
                "nama" => "aktiva",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga_perolehan" => "h a r g a<br>perolehan",
                "harga_sisa_tmp" => "n i l a i<br>buku",
                "harga" => "h a r g a<br>jual",
                "nilai_untung" => "untung",
                "nilai_rugi" => "r u g i",
            ),
            2 => array(
                "harga_perolehan" => "h a r g a<br>perolehan",
                "harga_sisa_tmp" => "n i l a i<br>buku",
                "harga" => "h a r g a<br>jual",
                "nilai_untung" => "untung",
                "nilai_rugi" => "r u g i",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
            ),
            2 => array("harga",),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
        ),
        "shoppingCartFieldValidators" => array(
            //            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowOptionalValidators" => array(
            "shippingService" => array(
                "ongkir_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
                "ongkir_tanpa_ppn_by_cust" => array(
                    "shipping_service" => "ongkir",
                ),
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "amount",
                //                "disc" => "disc",
//                "ongkir" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "new_net2" => "total amount",
                "ppn" => "vat",
                //                "dp_value" => "downpayment",
                "tagihan_ui" => "grand total",
            ),
            2 => array(
                "harga" => "amount",
                //                "disc" => "disc",
//                "ongkir" => "shipping service",
                //                "nilai_pembulatan" => "pembulatan",
                "new_net2" => "total amount",
                "ppn" => "vat",
                "tagihan_ui" => "grand total",
            ),
        ),
        "allowedMainEdit" => array(),
        "additionalRows" => array(
//            "shippingService" => array(
//                "ongkir_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
//                        'disabled' => "",
//                        "addPoints" => array(1),
//                        "editPoints" => array(4),
//                    ),
//                ),
//                "ongkir_tanpa_ppn_by_cust" => array(
//                    "shipping_service" => array(
//                        "label" => "shipping service",
//                        "defaultValue" => "",
//                        "maxValue" => "",
//                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(removeCommas(document.getElementById('harga_nett2').value)-parseFloat(removeCommas(this.value)))",
//                        'disabled' => "",
//                        "addPoints" => array(1, 4),
//                        "editPoints" => array(4),
//                    ),
//                ),
//            ),
        ),
        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "Name",
                    "alamat_1" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "nik",
                    "nik" => "NIK",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            //            "shippingService" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "shipping service",
            //                "mdlName" => "MdlOngkir",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "description" => "",
            //                "usedFields" => array(
            //                    "name" => "",
            //                ),
            //                "editPoints" => array(1),
            //            ),
//            "billingDetails" => array(
//                "elementType" => "dataModel",
//                "elementJoint" => array(
//                    "method" => "lookUpJointCustomer",
//                    "mdlFilter" => "id=pihakID",
//                    "usedFields" => array(
//                        "no_ktp" => "nik",
//                        "nik" => "nik",
//                        "npwp" => "npwp",
//                    ),
//                ),
//                "inputType" => "radio",
//                "label" => "BILLING DETAILS",
//                "mdlName" => "MdlCustomerBillAddress",
//                "mdlFilter" => array("extern_id=pihakID"),
//                //                "mdlName" => "MdlCustomer_and_pre",
//                //                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                //                "labelSrc" => "alias",
            //                "labelSrc" => "alias",
//                "usedFields" => array(
//                    //                    "alias" => "Name",
            //                    "alias" => "Name",
//                    "alamat" => "Addr",
//                    "kelurahan" => "Kel",
//                    "kecamatan" => "Kec",
//                    "kabupaten" => "Kab",
//                    "propinsi" => "Prop",
//                    "tlp" => "Phone",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Handphone",
            //                    "npwp" => "NPWP",
//                    "no_ktp" => "NIK",
//                ),
//                "editPoints" => array("5"),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlCustomerAddress",
//                "mdlFilter" => array(
//                    "extern_id=pihakID",
//                ),
//                "key" => "id",
//                "labelSrc" => "alias",
//                "usedFields" => array(
//                    "alias" => "Attn",
//                    "alamat" => "Address",
//                    "kecamatan" => "Kec",
//                    "kabupaten" => "Kab",
//                    "propinsi" => "propinsi",
//                    "tlp" => "Phone",
//                    "tlp_2" => "Handphone",
//                    //                    "npwp" => "NPWP",
//                    //                    "propinsi" =>"",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
//            ),

            //            "tos" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "defaultValue" => "20",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //                "usedFields" => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
            //            "shippingDate" => array(
            //                "elementType" => "dataField",
            //                // "inputType" => "combo",
            //                "label" => "shipping date",
            //                "inputType" => "date",
            //                "defaultValue" => date("Y-m-d"),
            //                //                "editPoints" => array(1),
            //                "editPoints" => array(1, 2, 3, 4, 5),
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
            //                "editPoints" => array(1, 2, 3, 4),
            //            ),
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
                "mdlName" => "MdlPaymentMethod2",
                //                "mdlName" => "MdlPaymentMethodCredit",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key" => "id",
//                "defaultValue" => "cash",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
                "noValidate" => true,
            ),
//            "cash_account" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "cash account",
//                "mdlName" => "MdlBankAccount_cash_and_in",
//                "mdlFilter" => array(
//                    "cabang_id=placeID",
//                    "jenis2=.1",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "account number",
//                    "alias" => "holder alias",
//
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
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
                        "label" => "cash account",
//                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_cash",
                        "mdlFilter" => array(
//                            "cabang_id=placeID",
//                            "jenis2=.1",
                        ),
                        "key" => "id",
                        "labelSrc" => "nama",
                        "usedFields" => array(
                            "nama" => "account number",
                            "alias" => "holder alias",

                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
                         * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
                         */

//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "cashless" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "EDC",
//mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlEdc",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "nama" => "alias",
                            "folders_nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
                "transfer" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
//                        "inputType" => "radio",
                        "inputType" => "combo",
                        "label" => "rekening bank",
//                                        "mdlName" => "MdlBankAccount_cash_and_in",
                        "mdlName" => "MdlBankAccount_in",
                        "key" => "id",
                        "labelSrc" => "nama",
                        "mdlFilter" => array(),
                        "usedFields" => array(
                            "folders_nama" => "bank",
                            "nama" => "rekening",
                            "machine_id" => "MID",
                            "jenis" => "jenis",
                            "folders" => "relID"
                        ),
                        "editPoints" => array(1,),
                        "noValidate" => true,
                        /*
 * pairMethod tak matiin dulu gak tau buat apa widi 1/11/2024
 */
//                        "pairMethod" => array(
//                            "recom" => "ReComCashAccountJenis",
//                            "calculate" => array(
//                                "source" => "cash_account",
//                                "filter" => array(
//                                    "cabang_id=placeID",
//                                ),
//                                "result" => array(
//                                    "nilai_setoran_tunai" => "nilai_entry",
//                                ),
//                            ),
//                        ),
                    ),
                ),
            ),
        ),
        "relativeOptions" => array(
//            "paymentMethod" => array(
//                "credit" => array(
//                    "discount" => array(
//                        "label" => "open discount",
//                        "defaultValue" => ".0",
//                        "maxValue" => "nett2*50/100",
//                        "auth" => array(
//                            "groupID" => "o_finance",
//                        ),
//                        "addPoints" => array(1, 2),
//                    ),
//                    "dp" => array(
//                        "label" => "down payment",
//                        "defaultValue" => ".0",
//                        "maxValue" => "nett2*50/100",
//                        "auth" => array(
//                            "groupID" => "o_finance",
//                        ),
//                        "addPoints" => array(1,),
//                    ),
//                ),
//                "cash" => array(
//                    "discount" => array(
//                        "label" => "open discount",
//                        "defaultValue" => ".0",
//                        "maxValue" => "nett2*50/100",
//                        "auth" => array(
//                            "groupID" => "o_finance",
//                        ),
//                        "addPoints" => array(1, 2),
//                    ),
//                    "dp" => array(
//                        "label" => "down payment",
//                        "defaultValue" => ".0",
//                        "maxValue" => "nett2*50/100",
//                        "auth" => array(
//                            "groupID" => "o_finance",
//                        ),
//                        "addPoints" => array(1,),
//                    ),
//                ),
//                "cia" => array(
//                    "nilai_cia" => array(
//                        "label" => "cash amount",
//                        "defaultValue" => "grand_pembulatan",
//                        "minValue" => "grand_pembulatan",
//                        "maxValue" => "grand_pembulatan",
//                        "auth" => array(
//                            "groupID" => "o_finance",
//                        ),
//                        "addPoints" => array(1,),
//                    ),
//                    "discount" => array(
//                        "label" => "open discount",
//                        "defaultValue" => ".0",
//                        "maxValue" => "nett2*50/100",
//                        "auth" => array(
//                            "groupID" => "o_finance",
//                        ),
//                        "addPoints" => array(1, 2),
//                    ),
//                ),
//            ),
        ),
        "connectTo" => "110",
        "connectoValidate" => array(
            2 => "ppn",
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
        "previewCtr" => "Create",
    ),
    //config SEWA
    "424" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "sewa",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "sewa ",
                "actionLabel" => "save",
                "source" => "",
                "target" => "424r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
//                "allowIncrement" => true,
            ),
            2 => array(
                "label" => "otorisasi sewa",
                "actionLabel" => "approve request",
                "source" => "424r",
                "target" => "424",
                "userGroup" => "c_purchasing_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "terima sewa",
                "actionLabel" => "terima",
                "source" => "424",
                "target" => "425",
                "userGroup" => "c_gudang",
                "stateLabel" => "GRN made",
                "stateColor" => "#ff7700",
                "stateCaption" => "receive by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
        ),
//        "template" => "template/transaksi_pihak4.html",
        "template" => "template/transaksi_pihak3.html",
        "selectorModel" => "MdlSewaDetail",
        "selectorSrcModel" => "MdlSewaDetail",
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
            //            "jenis=.item",
            //            "folders=pihakMainID",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "objek sewa",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            //            "nama",
            //                        "id",
        ),
        "selectorProcessor" => "_processSelectBiaya/select",
        //        "selectorProcessor" => "Selectors/_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakFilters" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrc" => array(//            "ppnFactor" => "ppn",
        ),
        "pihakProcessor" => "_processPihak/select",
        //tambahan pihak rules misal selector ppn
//        "pihakModelMainRules" => "MdlPpnStatic",
//        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
//        "pihakMainLabelRules" => "jenis ppn",
//        "pihakMainFiltersRules" => array(
//            //            "id=cabang_id",
//            //            "id<>cabang_id",
//            //            "id=.-1",
//        ),
//        "pihakMainValueSrcRules" => array(//            "pihakMdlName" => "mdl_name",
//        ),
//        "pihakMainProcessorRules" => "_processPihakMainRules/select",
        "pihakModelMainRules" => "MdlSewaKategoriStatic",
        "pihakMainCallerRules" => "_selectorPihakMainRules/selectPihak",
        "pihakMainLabelRules" => "jenis/kategori sewa",
        "pihakMainFiltersRules" => array(
            //            "id=cabang_id",
            //            "id<>cabang_id",
            //            "id=.-1",
        ),
        "pihakMainValueSrcRules" => array(//            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessorRules" => "_processPihakMainRules/select",

        //tambahan pihak2
        "mainselectorModel" => array(),
        "pihakModelMain" => "MdlPphStatic",
        //        "pihakMainCaller" => "Selectors/_processSelectBiaya/select",
        "pihakMainCaller" => "_selectorPihakMain/selectPihak",
        "pihakMainLabel" => "jenis pph",
        "pihakMainFilters" => array(),
        "pihakMainValueSrc2" => array(//            "pihakMdlName" => "mdl_name",
        ),
        "pihakMainProcessor" => "_processPihakMain/select",
//        "pihakMainRecoms" => array(
//            "filters" => array(
//                "id=pihakID",
//            ),
//            "mdlName" => "MdlSupplier",
//            "usedFields" => "npwp",
//            "targetField" => "tarif_pph",
//            "selectMethod" => array(
//                "pph23" => true,
//                "pph4_2" => false,
//            ),
//        ),
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PO Sewa number",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO Sewa number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO Sewa number",
                "oleh_nama" => "person",
                "transaksi_nilai" => "amount",
                "print_label" => "tool",
            ),
        ),
        "extHistoryFields" => array(
            1 => array(
                "print_label" => "nomer",
            ),
            2 => array(
                "print_label" => "nomer",
            ),
        ),
        "selectorFields" => array("id", "nama"),
        "pihakFields" => array("id", "nama"),

        "shoppingCart" => array(
            "initPrices" => "sewa",
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "label" => "label",
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
            3 => array(
                "nama" => "item name",
                "jml" => "qty",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "harga sewa",
                //                "pph4_2" => "pph4(2)",
//                "tarif_pph_item" => "tarif pph(%)",
//                "harga_disc" => "harga setelah potong pajak",
            ),
            2 => array(
                "harga" => "harga sewa",
                //                "pph4_2" => "pph4(2)",
//                "tarif_pph_item" => "tarif pph(%)",
//                "harga_disc" => "harga setelah potong pajak",
            ),
            3 => array(
                "harga" => "harga sewa",
                //                "pph4_2" => "pph4(2)",
//                "tarif_pph_item" => "tarif pph(%)",
//                "harga_disc" => "harga setelah potong pajak",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
//                "pph_value" => "pph",
                "ppn" => "VAT",
                "hpp_nppn" => "Grand Total",
            ),
            2 => array(
                "harga" => "Total Amount",
//                "pph_value" => "pph",
                "ppn" => "VAT",
                "hpp_nppn" => "Grand Total",
            ),
            3 => array(
                "harga" => "Total Amount",
//                "pph_value" => "pph",
                "ppn" => "VAT",
                "hpp_nppn" => "Grand Total",
            ),
        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
            3 => true,
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "tarif_pph_item",
                "ppn_persen",
                "harga_disc",
                //                "ppn_persen",
            ),
            2 => array(),
            3 => array(//                "jml",
            ),
        ),
        "shoppingCartAmountValue" => array(
            1 => "jml*harga",
            2 => "jml*harga",
            3 => "jml*harga",
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "pihak ID",
            "pihakName" => "pihak name",
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                //                "harga" => "document.getElementById('{pph4_2}').value=(parseFloat(removeCommas(this.value))*10)/100",
                //                "harga_disc" => "document.getElementById('{harga}').value=((parseFloat(removeCommas(this.value))/9)*100)+(parseFloat(removeCommas(this.value)))",
                "harga_disc" => "document.getElementById('{harga}').value=((parseFloat(removeCommas(this.value))/removeCommas(document.getElementById('pph_key_val').value))*100)",
            ),
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
        "ppnCekDefaultValue" => array(
            "enabled" => true,
            "ppnPersenCheck" => 0,// 1 dan 0
            "keyDetail" => array(
                "srcMain" => "ppnPersenCheck",
                "srcItem" => "ppnFactor",
                "overWriteMain" => "ppnFactor",
            ),
//            "required_value" => "ppn",
        ),
//        "keyupAction" => true,
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
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),

            "sewaPeriode" => array(
                "elementType" => "dataField",
                // "inputType" => "combo",
                "label" => "periode sewa(bulan)",
                "inputType" => "number",
                "defaultValue" => "12",
                //                "editPoints" => array(1),
                "editPoints" => array(1, 2, 3, 4, 5),
                "noValidate" => false,
            ),
            "sewaDtime_start" => array(
                "elementType" => "dataField",
                // "inputType" => "combo",
                "label" => "mulai digunakan",
                "inputType" => "date",
                "defaultValue" => date("Y-m-d"),
                //                "editPoints" => array(1),
                "editPoints" => array(1, 2, 3, 4, 5),
                "noValidate" => false,
            ),
            "branchTarget" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "target pembebanan ",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id>.0"),
                "key" => "id",
                "labelSrc" => "nama",
                //                "defaultValue" => "1",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "editPoints" => array(1, 2, 3),
//                "noValidate" => true,
                "noValidate" => false,
                "targetMethod" => array(
                    "-1" => "ReComSelectBranch_sewa",
                    "1" => "ReComSelectBranch_sewa",
//                    "21" => "ReComSelectBranch_sewa",
//                    "25" => "ReComSelectBranch_sewa",
//                    "26" => "ReComSelectBranch_sewa",
//                    "27" => "ReComSelectBranch_sewa",
//                    "28" => "ReComSelectBranch_sewa",
//                    "29" => "ReComSelectBranch_sewa",
//                    "30" => "ReComSelectBranch_sewa",
//                    "31" => "ReComSelectBranch_sewa",
                    "33" => "ReComSelectBranch_sewa",
                ),
            ),
//            "sewaJenis" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "Jenis Sewa",
//                "mdlName" => "MdlFolderAset",
//                "key" => "coa_code",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "name" => "nama",
//                    "coa_code" => "coa code",
//                ),
//                "editPoints" => array(1),
//            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Payment Method",
                "mdlName" => "MdlPaymentMethodCredit",
                "key" => "id",
                "defaultValue" => "credit",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
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
        "relativeElements" => array(
            "branchTarget" => array(
                "1" => array(
                    "externMain" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "transfer expense to",
                        "mdlName" => "MdlBiayaMethodSales",
                        "mdlFilter" => array(),
                        "key" => "coa_code",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            //key => label
                            "nama" => "kategori biaya",
                            "coa_code" => "coa kode",
                        ),
                        "editPoints" => array(1),
                        "noValidate" => false,
                    ),
                ),
                "33" => array(
                    "externMain" => array(
                        "elementType" => "dataModel",
                        "inputType" => "radio",
                        "label" => "transfer expense to",
                        "mdlName" => "MdlBiayaMethodSales",
                        "mdlFilter" => array(),
                        "key" => "coa_code",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            //key => label
                            "nama" => "kategori biaya",
                            "coa_code" => "coa kode",
                        ),
                        "editPoints" => array(1),
                        "noValidate" => false,
                    ),
                ),
//                "21" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                            "coa_code" => "coa kode",
//                        ),
//                        "editPoints" => array(1),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUsaha_payment",
//                            "biaya_usaha" => "ReComBiayaUmum_payment",
//                        ),
//                    ),
//                ),
//                "25" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlProdukRakitanPreBiaya",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                            "coa_code" => "coa kode",
//                        ),
//                        "editPoints" => array(1),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaProduksi_payment",
//                        ),
//                    ),
//                ),
//                "26" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                            "coa_code" => "coa kode",
//                        ),
//                        "editPoints" => array(1),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUsaha_payment",
//                            "biaya_usaha" => "ReComBiayaUmum_payment",
//                        ),
//                    ),
//                ),
//                "27" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                            "coa_code" => "coa kode",
//                        ),
//                        "editPoints" => array(1),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUsaha_payment",
//                            "biaya_usaha" => "ReComBiayaUmum_payment",
//                        ),
//                    ),
//                ),
//                "28" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                            "coa_code" => "coa kode",
//                        ),
//                        "editPoints" => array(1),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUsaha_payment",
//                            "biaya_usaha" => "ReComBiayaUmum_payment",
//                        ),
//                    ),
//                ),
//                "29" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                            "coa_code" => "coa kode",
//                        ),
//                        "editPoints" => array(1),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUsaha_payment",
//                            "biaya_usaha" => "ReComBiayaUmum_payment",
//                        ),
//                    ),
//                ),
//                "30" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                            "coa_code" => "coa kode",
//                        ),
//                        "editPoints" => array(1),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUsaha_payment",
//                            "biaya_usaha" => "ReComBiayaUmum_payment",
//                        ),
//                    ),
//                ),
//                "31" => array(
//                    "externMain" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "radio",
//                        "label" => "transfer expense to",
//                        "mdlName" => "MdlBiayaMethodSales",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "kategori biaya",
//                            "coa_code" => "coa kode",
//                        ),
//                        "editPoints" => array(1),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            "biaya_umum" => "ReComBiayaUsaha_payment",
//                            "biaya_usaha" => "ReComBiayaUmum_payment",
//                        ),
//                    ),
//                ),
            ),
            "externMain" => array(
                "6030" => array(
                    "dtaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "expense details",
                        "mdlName" => "MdlDtaBiayaUmum",
                        "mdlFilter" => array(),
                        "key" => "coa_code",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "beban biaya",
                            "coa_code" => "coa code", // gak keluar, ada yang tau??
                        ),
                        "editPoints" => array(1),
                        "noValidate" => false,
                        "targetMethod2" => array(
                            "0603" => "ReComBiayaUmum_payment",
                        ),

                    ),
                ),
                "6010" => array(
                    "dtaDetail" => array(
                        "elementType" => "dataModel",
                        "inputType" => "combo",
                        "label" => "expense details",
                        "mdlName" => "MdlDtaBiayaUsaha",
                        "mdlFilter" => array(),
                        "key" => "coa_code",
                        "labelSrc" => "nama",
                        "description" => "",
                        "usedFields" => array(
                            "nama" => "beban biaya",
                            "coa_code" => "coa code", // gak keluar, ada yang tau??
                        ),
                        "editPoints" => array(1),
                        "noValidate" => false,
                        "targetMethod2" => array(
                            "0602" => "ReComBiayaUsaha_payment",
                        ),
                    ),

                ),
//                "03020100001" => array(
//                    "dtaDetail" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "expense details",
//                        "mdlName" => "MdlDtaBiayaProduksi",
//                        "mdlFilter" => array(//                            "pre_biaya_id=externMain",
//                        ),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "beban biaya",
//                            "coa_code" => "beban biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            1 => "ReComBiayaProduksi_payment"
//                        ),
//                    ),
//                ),
//                "03020100002" => array(
//                    "dtaDetail" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "expense details",
//                        "mdlName" => "MdlDtaBiayaProduksi",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "beban biaya",
//                            "coa_code" => "beban biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            2 => "ReComBiayaProduksi_payment"
//                        ),
//
//                    ),
//                ),
//                "03020100003" => array(
//                    "dtaDetail" => array(
//                        "elementType" => "dataModel",
//                        "inputType" => "combo",
//                        "label" => "expense details",
//                        "mdlName" => "MdlDtaBiayaProduksi",
//                        "mdlFilter" => array(),
//                        "key" => "coa_code",
//                        "labelSrc" => "nama",
//                        "description" => "",
//                        "usedFields" => array(
//                            "nama" => "beban biaya",
//                            "coa_code" => "beban biaya",
//                        ),
//                        "editPoints" => array(1,),
//                        "noValidate" => true,
//                        "targetMethod2" => array(
//                            4 => "ReComBiayaProduksi_payment"
//                        ),
//                    ),
//
//                ),
            ),
        ),
        "relativeOptions" => array(),
        "allowDirectDistributionSewa" => array(
            3 => true
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "pph_key_val" => array(
                        "label" => "pph key",
                        "defaultValue" => "pph_key_val",
                        "keyupAction" => "",
                        "hideRow" => "true",
                        "addPoints" => array(1,),
                    ),
                ),
            ),
        ),
        "previewCtr" => "Create",
        "allowedMainEdit" => array("1"),
    ),

);