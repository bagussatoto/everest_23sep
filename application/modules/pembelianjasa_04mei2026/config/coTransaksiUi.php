<?php
//region urusan tanggal-menanggal
// date_default_timezone_set('asia/jakarta');
// $date = new DateTime(date("Y-m-d")); // Y-m-d
// $date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion

//tambahin filter "461ro untuk selectornota taxes 681
$config["coTransaksiUi"] = array(

    // config po jasa
    "463" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "service purchasing",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PURCHASE PRE ORDER",
                "label_nota" => "SERVICES PURCHASE PRE ORDER",
                "actionLabel" => "make purchasing order",
                "source" => "",
                "target" => "463ro",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "PURCHASE ORDER",
                "label_nota" => "SERVICES PURCHASE ORDER",
                "actionLabel" => "approve purchasing and dpp ppn",
                "source" => "463ro",
                "target" => "463o",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                //                "paymentSrc" => array(
                //                    "enabled" => true,
                //                    "filter" => array(
                //                        "label='incoming cash'",
                //                    ),
                //                    //                    "label" => "This order requires approval from the Finance Department. You don't need to follow up on this order.",
                //                    "label" => "Cash in Advance belum difollow up. Segera hubungi pihak Finance.",
                //                ),
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "SERVICES RECEIVE NOTE",
                "label_nota" => "SERVICES RECEIVE NOTE",
                //                "actionLabel" => "make service receipt note",
                "actionLabel" => "undo/reject/GRN",
                "buttonLabel" => "make service receipt note",
                "source" => "463o",
                "target" => "463",
                "userGroup" => "c_holding",
                "stateLabel" => "service receipt note made",
                "stateColor" => "#009900",
                "stateCaption" => "Receipt by",
                //                "allowEdit" => true,
                //                "allowIncrement" => true,
            ),
            4 => array(
                "label" => "realisasi ppn masukan",
                "label_nota" => "realisasi ppn masukan",
                "actionLabel" => "approve ppn masukan",
                "source" => "463",
                "target" => "113",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlJasa",
        "selectorSrcModel" => "MdlJasa",
        "selectedPrice" => array(),
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
        "selectorProcessor" => "_processSelectProductException/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
        "pihakMainValueSrc" => array(
            "npwp" => "npwp",
        ),

//        "pihakValidate" => array(
//            "wajib_pajak" => array(
//                "model" => "MdlWajibPajak",
//                "result" => array(
//                    1 => array(
//                        "kolom" => "npwp",
//                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    2 => array(
//                        "kolom" => "no_ktp",
//                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    3 => array(
////                        "kolom" => "no_ktp",
//                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    "none" => array(
////                        "kolom" => "no_ktp",
//                        "label" => "(NPWP/NON NPWP) belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                ),
//            ),
//        ),

        "shortHistoryFields" => array(
            //            "no" => "no",
//            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PRE PO number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "SRN number",
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
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            "next_pic" => "Next step otorisator",
            "keterangan" => "keterangan",
        ),
        "shortStatusFields" => array(
            "no" => "no",
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
            "pph23MethodPotongan__label" => "status pph 23",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "item_fields" => "isi",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
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
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer" => "receipt number",
                "item_fields" => "isi",
                "description_main_followup" => "VENDOR'S INVOICE REFERRAL",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                "no" => "no",
//                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "ids_his" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "PO number",
                ),
                "nomer_srn" => array(
                    "step" => 3,
                    "key" => "nomer",
                    "label" => "SRN number",
                ),
                "item_fields" => "isi",
                "description_main_followup" => "INV<br>from vendor",
                "nomer" => "realisasi ppn number",
                "oleh_nama" => "person",

                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "eFaktur" => "e-faktur",
                //                "ppn" => "ppn",
                "pph23MethodPotongan__label" => "status pph 23",
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
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pph23MethodPotongan__label" => "status pph 23",
        ),
        "shortItemsFields" => array(
//            "kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "product",
                "addKey" => "keterangan",
            ),
            "jml" => "qty",
            "harga" => "Unit Price",
            "discPersen" => "DISC(%)",
            "disc" => "DISC(Rp)",
            "harga_disc" => "Netto",
            "dpp_persen" => "Prosentase DPP",
            "dppPPn" => "nilai dpp",
            "ppn" => "PPN 12%",
            "nett" => "subtotal",
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
            "dpp_persen" => "dpp_persen",
            "pph" => "pph",
        ),
        "shopingCartCompareFields" => array(
            1 => array(
                "main" => "pph",
                "slave" => "dpp_persen",
                //                "target" =>"valid_pph_key",
            ),

            2 => array(
                "main" => "pph",
                "slave" => "dpp_persen",
                //                "target" =>"valid_pph_key",
            ),

        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            3 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            4 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "harga_disc" => "Netto",
                "dpp_persen" => "Prosentase DPP",
//                "dppPPn" => "nilai dpp",
                "dppPPn_ui" => "nilai dpp",
                "ppn" => "PPN 12%",
            ),
            2 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "harga_disc" => "Netto",
                "dpp_persen" => "Prosentase DPP",
//                "dppPPn" => "nilai dpp",
                "dppPPn_ui" => "nilai dpp",
                "ppn" => "PPN 12%",
            ),
            3 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "harga_disc" => "Netto",
                "dpp_persen" => "Prosentase DPP",
                //                "dppPPn" => "nilai dpp",
                "dppPPn_ui" => "nilai dpp",
                "ppn" => "PPN 12%",
            ),
            4 => array(
                "harga" => "Unit Price",
                "discPersen" => "DISC(%)",
                "disc" => "DISC(Rp)",
                "harga_disc" => "Netto",
                "dpp_persen" => "Prosentase DPP",
                //                "dppPPn" => "nilai dpp",
                "dppPPn_ui" => "nilai dpp",
                "ppn" => "PPN 12%",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
                //                "ppn" => "VAT",
                //                "nett" => "Total",
            ),
            2 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
                //                "ppn" => "VAT",
                //                "nett" => "Total",
            ),
            3 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
                //                "ppn" => "VAT",
                //                "nett" => "Total",
            ),
            4 => array(
                "harga" => "Total Amount",
                "disc" => "DISC",
                //                "ppn" => "VAT",
                //                "nett" => "Total",
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
                "jml",
                "dpp_persen",
                //                "ppn_persen",
                "discPersen",
            ),
            2 => array(
                "harga",
                //                "jml",
                "dpp_persen",
                //                "ppn_persen",
                "discPersen",
            ),
            3 => array(
                "harga",
                //                "jml",
                "dpp_persen",
                //                "ppn_persen",
                "discPersen",
            ),
            4 => array(
                "dpp_persen",
                //                                "jml",
                //                "harga",
            ),
        ),
        "shopingCartParamForceEditable" => array(
            //ini untuk force editable fields
            1 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            2 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            3 => array(
                "allow_params_edit" => "dpp_persen"
            ),
            4 => array(
                "allow_params_edit" => "dpp_persen"
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "vendor ID",
            "pihakName" => "vendor name",
            //            "nilai_dpp_ppn" =>"DPP PPN"
        ),
        "shoppingCartAmountValue" => array(
            //            1 => "jml*(harga_disc+ppn)",
            //            2 => "jml*(harga_disc+ppn)",
            //            3 => "jml*(harga_disc+ppn)",
            //            4 => "jml*(harga_disc+ppn)",
            1 => "jml*(harga)",
            2 => "jml*(harga)",
            3 => "jml*(harga)",
            4 => "jml*(harga)",
        ),
        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => false,
            4 => false,
        ),
        "shopingCartEditableCompare" => array(
            "dpp_persen" => array(
                "npwp_allowed" => array(
                    0 => false,
                    1 => true
                ),

            ),
        ),
        "shopingCartErrorEditable" => array(
            "npwp" => "vendor/supplier tidak memiliki npwp. prosentase dpp tidak dapat diubah. Silahkan lengkapi data npwp vendor untuk menggunakan dpp pengganti"
//            "npwp" => "vendor/supplier tidak memiliki npwp.",
        ),
        "shopingcartAddDpp" => array(
            1 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            2 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            3 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            4 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            5 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),


        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "allowedMainEdit" => array("1"),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "country" => "Country",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    "npwp" => "NPWP",
                    //                    "alias" => "Attn",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "pph23MethodPotongan" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "method of pph 23",
                "mdlName" => "MdlPph23MethodPotongan",
                "key" => "id",
                //                "defaultValue" => "item",
                "disabled_select" => array(
                    "gate" => "valid_pph_key",
                    "value" => array(
                        "0" => "disabled",
                        "1" => "",
                    ),
                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
                ),


                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                    //                    "tarif" => "tarif (%)",
                ),
                "editPoints" => array(1, 2),
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
            "pihakPembebanan" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "Cabang Pembebanan Biaya",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array(
                    "jenis=.cabang",
                ),
                "key" => "id",
//                "defaultValue" => "credit",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "id" => "ID",
                    "nama" => "Nama",
                    "kode" => "Kode",
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
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
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
            //            "paymentMethod" => array(
            //                "credit" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_holding",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
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
            //                            //                            "groupID" => "c_holding",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "o_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                ),
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            ////                        "defaultValue" => "nett2",
            ////                        "minValue" => "nett2",
            ////                        "maxValue" => "nett2",
            ////                        "defaultValue" => "new_net3",
            //                        "defaultValue" => "nett",
            //                        "minValue" => "nett",
            //                        "maxValue" => "nett",
            //                        "auth" => array(
            //                            //                            "groupID" => "c_finance",
            //                            "groupID" => "c_finance",
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            ////                    "discount" => array(
            ////                        "label" => "open discount",
            ////                        "defaultValue" => ".0",
            ////                        "maxValue" => "nett2*50/100",
            ////                        "auth" => array(
            ////                            //                            "groupID" => "c_admin",
            ////                            "groupID" => "o_finance",
            ////                        ),
            ////                        "addPoints" => array(1, 2),
            ////                    ),
            //
            //                ),
            //
            //            ),
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(
                    "dppPPn_ui" => array(
                        "label" => "Dpp",
                        "defaultValue" => "dppPPn_ui",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harga').value)) || parseInt(removeCommas(this.value))<0){this.value=document.getElementById('harga').value;}
//                            ",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    /**
                     * dppPPn tetap aktif antisipasi error gerbang yang tidak perlu,
                     * posisi hidden karena secara ui yang tampil dppPPn_ui
                     */
                    "dppPPn" => array(
                        "label" => "Dpp",
                        "defaultValue" => "dppPPn",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        'hideRow' => true,

                        "addPoints" => array(1,),
                    ),
                    "ppn" => array(
                        "label" => "Ppn 12%",
                        "defaultValue" => "ppn",
                        "maxValue" => "ppn_value",
                        "minValue" => "ppn_value",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "payment_out" => array(
                        "label" => "Grand total",
                        "defaultValue" => "payment_out",
                        "maxValue" => "payment_out",
                        "minValue" => "payment_out",
                        "keyPressAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
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
        "allowedMainEdit" => array("1", "4"),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "nomer" => "INV",
                    "dppPPn" => "DPP",
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
        "detailForceMain" => array(
            2 => array(
                "source" => "pph",
                "target" => "valid_pph_key",
                "elemenReset" => "MdlPph23MethodPotongan",
                "current_element" => "pph23MethodPotongan",
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
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "463roe",
                "label" => "EDIT PURCHASE PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "463rorj",
                "label" => "REJECT PURCHASE PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "463orj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
        "validateRelasiUangMuka" => array(
            2 => array(
                "enabled" => true,
            ),
        ),
        //----
        "undoneItemsIndexAll" => true,
    ),
    "1463" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "service purchasing(pusat)",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "PURCHASE PRE ORDER",
                "actionLabel" => "make purchasing order",
                "source" => "",
                "target" => "1463r",
                "userGroup" => "c_purchasing",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "PURCHASE ORDER",
                "actionLabel" => "approve purchasing",
                "source" => "1463r",
                "target" => "1463o",
                "userGroup" => "c_purchasing_adm",
                "stateLabel" => "purchased",
                "stateColor" => "#ff7700",
                "stateCaption" => "Approval by",
                "allowEdit" => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label" => "SERVICE RECEIVED NOTE",
                //                "actionLabel" => "make service receipt note",
                "actionLabel" => "undo/reject/GRN",
                "buttonLabel" => "make service receipt note",
                "source" => "1463o",
                "target" => "1463",
                "userGroup" => "c_holding",
                "stateLabel" => "service receipt note made",
                "stateColor" => "#009900",
                "stateCaption" => "Receipt by",
            ),
            4 => array(
                "label" => "realisasi ppn masukan",
                "actionLabel" => "approve ppn masukan",
                "source" => "1463",
                "target" => "113",
                "userGroup" => "c_finance",
                "stateLabel" => "approved",
                "stateColor" => "#009900",
                "stateCaption" => "PT. Everest Electronic",
            ),
        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlExpense",
        "selectorSrcModel" => "MdlExpense",
        "selectedPrice" => array(
            "model" => "MdlHargaSupplies",
            "label" => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "tipe=.import",
        ),
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
        "selectorProcessor" => "_processSelectProduct/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlSupplier",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "vendor",
        "pihakProcessor" => "_processPihak/select",
//        "pihakValidate" => array(
//            "wajib_pajak" => array(
//                "model" => "MdlWajibPajak",
//                "result" => array(
//                    1 => array(
//                        "kolom" => "npwp",
//                        "label" => "NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    2 => array(
//                        "kolom" => "no_ktp",
//                        "label" => "NIK belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    3 => array(
////                        "kolom" => "no_ktp",
//                        "label" => "status NPWP/NON NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                    "none" => array(
////                        "kolom" => "no_ktp",
//                        "label" => "status NPWP/NON NPWP belum ditentukan. Silahkan perbaiki data Vendor.",
//                    ),
//                ),
//            ),
//        ),
        "shortHistoryFields" => array(
            //            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer_top" => "PO number",
            // sumber dari kolom id_his
            "nomer_po" => array(
                "step" => 2,
                "key" => "nomer",
                "label" => "PO number",
            ),
            "nomer_grn" => array(
                "step" => 3,
                "key" => "nomer",
                "label" => "SRN number",
            ),
            "nomer_ppn" => array(
                "step" => 4,
                "key" => "nomer",
                "label" => "realisasi ppn number",
            ),
            //            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett" => "total amount",
            "pph23MethodPotongan__label" => "status pph 23",
            "keterangan" => "keterangan",
        ),
        "shortStatusFields" => array(
            "no" => "no",
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
            "pph23MethodPotongan__label" => "status pph 23",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "PO number",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            3 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "receipt number",
                "description_main_followup" => "INV<br>from vendor",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "pph23MethodPotongan__label" => "status pph 23",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            4 => array(
                "no" => "no",
                "jenis_label" => "activity",
                "dtime" => "date",
                "suppliers_nama" => "vendor",
                "nomer_top" => "PRE PO number",
                "nomer" => "receipt number",
                "description_main_followup" => "INV<br>from vendor",
                "oleh_nama" => "person",
                //                "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett" => "total amount",
                "eFaktur" => "e-faktur",
                //                "ppn" => "ppn",
                "pph23MethodPotongan__label" => "status pph 23",
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
            "no" => "no",
            "jenis_label" => "activity",
            "dtime" => "date",
            "suppliers_nama" => "vendor",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "pph23MethodPotongan__label" => "status pph 23",
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
            "dpp_persen" => "dpp_persen",
            "pph" => "pph",
        ),
        "shoppingCartFields" => array(
            1 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            2 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            3 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
            4 => array(
                "nama" => "Description",
                "jml" => "Qty",
                "satuan" => "Satuan",
            ),
        ),
        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",// ppnFactor
                "ppn" => "VAT(Rp)",
            ),
            2 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)*",// ppnFactor
                "ppn" => "VAT(Rp)",
            ),
            3 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",// ppnFactor
                "ppn" => "VAT(Rp)",
            ),
            4 => array(
                "harga" => "Unit Price",
                //                "discPersen" => "DISC(%)",
                //                "disc" => "DISC(Rp)",
                "ppnPersen" => "VAT(%)",// ppnFactor
                "ppn" => "VAT(Rp)",
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
                "jml",
                //                "ppnFactor",
                "ppnPersen",
                "discPersen",
            ),
            2 => array(
                "harga",
                "jml",
                //                "ppnFactor",
                "ppnPersen",
                "discPersen",
            ),
            3 => array(
                "harga",
                "jml",
                //                "ppnFactor",
                "ppnPersen",
                "discPersen",
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
        "shopingcartAddDpp" => array(
            1 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            2 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            3 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            4 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),
            5 => array(
                "ppn" => array(
                    "dpp_pengganti" => "Tax Basis"
                ),
            ),


        ),

        "pairRegistries" => array(
            "tableIn_master_values", "main", "items"
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "VENDOR",
                "mdlName" => "MdlSupplier",
                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "",
                    "alamat_1" => "",
                    "country" => "Country",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    //                    "npwp" => "NPWP",
                    //                    "alias" => "Attn",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "pph23MethodPotongan" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "method of pph 23",
                "mdlName" => "MdlPph23MethodPotongan",
                "key" => "id",
                //                "defaultValue" => "item",
                "disabled_select" => array(
                    "gate" => "valid_pph_key",
                    "value" => array(
                        "0" => "disabled",
                        "1" => "",
                    ),
                    "disabled_msg" => "tidak dapat dipilih karena jasa sudah mengandung ppn",
                ),


                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "method",
                    //                    "tarif" => "tarif (%)",
                ),
                "editPoints" => array(1, 2),
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
            //            "paymentMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "payment method",
            //                "mdlName" => "MdlPaymentMethodCredit",
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "",
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
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                //                "cash" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
                //                        ),
                //                        "editPoints" => array(1,),
                //                    ),
                //                ),
                //                "cia" => array(
                //                    "cash_account" => array(
                //                        "elementType" => "dataModel",
                //                        "inputType" => "radio",
                //                        "label" => "cash account",
                //                        "mdlName" => "MdlBankAccount",
                //                        "key" => "id",
                //                        "labelSrc" => "nama",
                //                        "usedFields" => array(
                //                            "nama" => "",
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
            //            "paymentMethod" => array(
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            //                        "defaultValue" => "nett",
            //                        "minValue" => "nett",
            //                        "maxValue" => "nett",
            //                    ),
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
        "allowedMainEdit" => array("1"),
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
        // ======== =========
        "previewCtr" => "Create",
        "canceledLabel" => array(
            1 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
            2 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_actionLabel} ulang di {cabang_nama}",
            3 => "Transaksi {transaksi_nama} nomer {nomer} telah dibatalkan oleh {cancel_name} pada {cancel_dtime}.
                    <br>Silahkan melakukan {transaksi_nama} ulang di {cabang_nama}",
        ),
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1463re",
                "label" => "EDIT PURCHASE PRE ORDER",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "1463rrj",
                "label" => "REJECT PURCHASE PRE ORDER",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "1463orj",
                "label" => "REJECT PURCHASE ORDER",
            ),
        ),
        //----
        "undoneItemsIndexAll" => true,
    ),


);