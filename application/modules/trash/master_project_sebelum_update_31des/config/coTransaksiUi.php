<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion

$config["coTransaksiUi"] = array(
    // realisasi project
    "5882" => array(
        "icon" => "fa fa-opencart",
        "label" => "realisasi project",
        "place" => "branch",//=> "center",
        "steps" => array(
//            1 => array(
//                "label" => "SALES PRE ORDER PROJECT",
//                "actionLabel" => "make order",
//                "source" => "",
//                "target" => "588spo",
//                "userGroup" => "o_seller",
//                "stateLabel" => "pending approval",
//                "stateColor" => "#dd3300",
//                "stateCaption" => "Prepare by",
//            ),
            1 => array(
                "label" => "REALISASI PROJECT",
                "actionLabel" => "realisasi project",
                "source" => "",
                "target" => "5882",
                "userGroup" => "o_gudang",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
            ),

//            2 => array(
//                "label" => "PACKING LIST PROJECT",
//                "actionLabel" => "process shipment",
//                "source" => "5882so",
//                "target" => "5882spd", // shipped
//                "userGroup" => "o_gudang",
//                "stateLabel" => "shipped",
//                "stateColor" => "#009900",
//                "stateCaption" => "shipped by",
//                "allowIncrement" => true,
//                "allowEdit" => true,
//            ),
//            3 => array(
//                "label" => "CLOSING PROJECT",
//                "actionLabel" => "close project",
//                "source" => "5882spd",
//                "target" => "5882", // invoice
//                "userGroup" => "o_finance",
//                "stateLabel" => "close",
//                "stateColor" => "#009900",
//                "stateCaption" => "completed by",
//                "allowJoin" => false,
//                //                "allowEdit" => true,
//                "followupMulti" => true,
//            ),

        ),
        "template" => "template/transaksi_project.html",
//        "selectorModel" => "MdlProjectWorkOrder",
//        "selectorSrcModel" => "MdlProjectWorkOrder",
//        "selectorSubSrcModel" => "MdlProjectKomposisiWorkorder",
////        "selectorSrcModel" => "MdlProjectKomposisiWorkorder",
        "selectorModel" => "MdlProjectWorkOrderSub",
        "selectorSrcModel" => "MdlProjectWorkOrderSub",
        "selectorSubSrcModel" => "MdlProjectKomposisiWorkorderSub",
//        "selectorSrcModel" => "MdlProjectKomposisiWorkorderSub",
        "selectorModelEdit" => "MdlProduk2",
        "selectorSrcModelEdit" => "MdlProduk2",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual", "ppv", "disc", "disc_percent"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //                "ppv" => "ppv",
            //                "disc" => "disc",
            //                "disc_percent" => "disc (%)",
            //            ),
            //            "mainSrc" => "jual",
        ),
        "selectedPrice2" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "hpp",
                "jual",
            ),
            "key_label" => array(
                "hpp" => "hpp",
                "jual" => "harga",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
//            "customer_id=pihakID",
//            "transaksi_id=.0",

        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih work order",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            //            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectBiaya/selectProject",
        //bagian penambah items 2 di shopingCart
        "selectorLabel2" => "tambahkan item",
        "selectorModel2" => "MdlProduk2",
        "selectorCaller2" => "_selectorItem/selectAddItems2",//penampil selector item2
        "selectorProcessor2" => "_projectItemEditor/addItem",
        "selectorParamFields2" => array(
            "id" => "id",
            "nama" => "nama",
            "kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields2" => array(
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),

        "shoppingCartFieldSrc2" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "jenis" => "jenis",
            "jml_wo" => "jml_wo",
            "qty_debet" => "qty_debet",
            "qty_kredit" => "qty_kredit",
            "qty_saldo" => "qty_saldo",
            "sisa_alokasi" => "sisa_alokasi",
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item name",
                "produk_kode" => "code",
//                "no_part" => "part number",
                "stok" => "stok",
                "jml_wo" => "alokasi",// jatah di qty dari work order
                "qty_kredit" => "qty<br>sudah diambil",// jatah di qty dari work order
                "jml" => "qty<br>diambil",// qty yang diambil saat ini
                "sisa_alokasi" => "sisa alokasi",// sisa jatah yang bisa diambil
                "satuan" => "uom",
            ),
            2 => array(
//                "nama" => "item name",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "jml" => "qty",
//                "satuan" => "uom",
            ),
        ),
        "selectorDefaultMinValue2" => "1",
        "selectorSrcModel2" => "MdlHargaProduk",
        "shoppingCartNumFields2" => array(
            1 => array(
//                "harga" => "price",
//                "order_qty" => "qty order",
//                "dikirim_qty" => "qty kirim",
//                "jml" => "qty",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
                // "ppn"   => "VAT",
            ),
            2 => array(
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "harga" => "price",
//                "jml" => "qty",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                // "ppn"   => "VAT",
            ),

        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
//                "disc_percent",
//                "disc",
                "harga",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),
            3 => array(
                //                "harga",
                "jml",
                "produk_ord_jml",
            ),

        ),
        "shoppingCartAmountValue2" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*",
            //            5 => "jml*(harga-disc)",
        ),
        "pairTasklist" => true,
        //----  untuk items2 -----------------

        //items sub 3
        "shopingCartDetailFields2" => array(
            1 => array(
                "produk_nama" => "SRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => "SRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            3 => array(
                "produk_nama" => "SRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            4 => array(
                "produk_nama" => "SRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
        ),
        "shopingCartDetailFields2_sub" => array(
            2 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            3 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            4 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
        ),

        //---------

        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
//        "pihakModel"         => "MdlCustomerProject",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
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
            "nett1" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",

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
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                // "nett1" => "sub amount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            3 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer" => "PL number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            4 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer" => "INV number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "jual" => "amount",
                // "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
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
//                "produk_kode" => "part number",
//                "stok" => "stok",
//                "jml_wo" => "qty work order",
//                "jml" => "qty",
                "pihakProjekName" => "project",
                "nama" => "perintah kerja",
                "progress_nama" => "status",
//                "produk_kode" => "part number",
//                "stok" => "stok",
//                "jml_wo" => "qty work order",
//                "jml" => "qty",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
                "jml_wo" => "qty work order",
                "jml" => "qty",
            ),

            3 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "jml_wo" => "jml_wo",
            "qty_kredit" => "qty_kredit",
            "qty_saldo" => "qty_saldo",
            "pihakProjekName" => "pihakProjekName",
            "progress_id" => "progress_id",
            "progress_nama" => "progress_nama",
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
            //-------------------
        ),

        "shoppingCartNumFields" => array(
            1 => array(
//                "harga" => "price",
//                                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
            ),
            2 => array(
//                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
            ),
            3 => array(
//                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
            ),

        ),
        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "produk_ord_jml",
//                "disc_percent",
//                "disc",
//            ),
//            2 => array(
//                //                "jml",
//                //                "produk_ord_jml",
//                "harga",
//                //                "disc_percent",
//                //                "disc",
//            ),
            1 => array(
                //                "harga",
                "jml",
                //                "produk_ord_jml",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
//            1 => array(
//                "base"    => "disc_percent",
//                "members" => array(
//                    "disc_percent",
//                    "disc",
//                ),
//            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
//            "pihakID" => "customer ID",
//            "pihakName" => "customer name",
            "projectID" => "nama project",//validasi project ada disession main
        ),
        "shoppingCartRowOptionalValidators" => array(),
        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            //            1 => "jml*(harga-disc)",//nett2
//            //            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        // "shoppingCartSubamount2" => array(1 => true,),

        "shopingCartEdit_mode" => array(
            "requested" => "valid_qty",

        ),

        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => true,
        ),
        "shoppingCartSumFields" => array(
//            1 => array(
//                "harga" => "amount",
//                "ppn_out_bulat" => "PPN/vat",
//                "new_net3" => "total amount",
//
//            ),
//            2 => array(
//                "harga" => "amount",
//                //                "disc" => "disc",
//                //                "ongkir_ui" => "shipping service",
//                //                "grand_total_ui" => "Net amount",
//                "grand_ppn" => "PPN/vat",
//                "new_net3" => "total amount",
//
//            ),
//            3 => array(
//                "harga" => "amount",
//                "grand_ppn" => "PPN/vat",
//                "new_net3" => "total amount",
//            ),
        ),
        "receiptMesurementRows" => array(),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "receiptElements" => array(
            "projectDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "PROJECT DETAILS",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array(
                    "id=projectID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "transaksi_no" => "nomer so",
                    "customer_id" => "id konsumen",
                    "customer_nama" => "nama konsumen",
                ),
                "editPoints" => array(1),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array(
                    "id=projectDetails__customer_id"
                ),
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
//            "workOrderDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "WORK ORDER DETAILS",
//                "mdlName" => "MdlProjectWorkOrder",
//                "mdlFilter" => array(
//                    "id=pihakProjekWorkOrderID"
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                    "employee_nama" => "pengawas",
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
//                "editPoints" => array(1, 4, 5),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlCustomerAddress",
//                "mdlFilter" => array("extern_id=pihakID"),
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
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "payment method",
//                "mdlName" => "MdlPaymentMethod",
//                "key" => "id",
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
//                //                "mdlFilter"   => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
            "workOrderDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "WORK ORDER",
                "mdlName" => "MdlProjectWorkOrder",
                "mdlFilter" => array(
                    "id=pihakProjekWorkOrderID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "employee_nama" => "pengawas",
                ),
                "editPoints" => array(1),
            ),
            "workOrderSubDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUB WORK ORDER",
                "mdlName" => "MdlProjectWorkOrderSub",
                "mdlFilter" => array(
                    "id=pihakProjekWorkOrderSubID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "employee_nama" => "pengawas",
                ),
                "editPoints" => array(1),
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "validateMeasurement" => array(),
        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items2_sum"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),

        ),
        "validationRules" => array(),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
        ),
        "additionalRows" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "allowedMainEdit" => array("1", "2"),
        "addMainStep" => array(
            "1784" => array(
                "jenis_master" => "584",
                "jenis" => "584",
                "target" => "1784",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
//        "connectTo" => "110",
//        "connectoValidate" => array(
//            2 => "nilai_credit",
//        ),
//        "replacerConnectTo" => array(
//            "cabang2ID" => "-1",
//            "cabang2Name" => "PUSAT",
//            "place2ID" => "-1",
//            "place2Name" => "PUSAT",
//            "gudang2ID" => "-1",
//            "gudang2Name" => "default center warehouse",
//            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
//        ),
        "previewCtr" => "Create",
        "editSubItem" => array(
            1 => true,
            2 => true,
        ),
        "itemSwaper" => array(
            4 => array(
                "id" => "id",
                "produk_id" => "id",
                "nama" => "nomer",
                "jml" => ".1",
                "dtime" => "dtime",
            ),
        ),
        "itemSwaperToMain" => array(
            4 => array(
                "projectID" => "id",
                "projectCode" => "produk_kode",
                "projectName" => "nama",
                "projectHarga" => "harga",
                "projectPpn" => "ppn",
                "projectNett" => "harga",
                "projectGrandtotal" => "nett2",
            ),
        ),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "projectName" => "Project",
                    "projectHarga" => "Nilai Project",
                    "tarifGaransi" => "Garansi (%)",
                    "garansi_nilai" => "Garansi (IDR)",
                    "dateGaransi" => "Masa Garansi ",
                ),
                "editableFields" => array(
                    "dateGaransi" => "date",
                ),
            ),
        ),
        "additionalPackinglist" => array(
            4 => array(
                "enabled" => true,
                "header" => array(
                    "produk_nama" => "item name",
                    "produk_kode" => "code",
//                    "stok_center" => "stok dc",
//                    "stok" => "stok available",
                    "qty" => "qty",
                    "satuan" => "uom",
                ),
            ),
        ),
        "validateClosing" => array(
            4 => array(
                "dateGaransi" => "Tanggal garansi belum ditentukan. Silahkan ditentukan terdahulu.",
            ),
        ),
        "validateClosingKey" => array(
            4 => array(
                "garansi_nilai",
            ),
        ),
        "validateClosingExtractedSubItems" => array(
            4 => array(
                "checklistnote_cek" => "Silahkan checklist Lanjutkan Close Project.",
            ),
        ),

//        "connectTo" => "5882",// pelaksanaan project
        "workOrderValidate" => array(
            "enabled" => false,
            "validateRule" => array(
                1 => array(
                    "model" => "MdlProdukProject",
                    "label" => "uang muka",
                    "filter" => array(
                        "id=produk_id",
                        "uang_muka_approved>.0",
                    ),
                    "warningLabel" => "work order ini belum aktif, saat ini sedang ditindaklanjuti oleh pihak finance.",
                ),
//                2 => array(
//                    "model" => "MdlProjectKomposisiWorkorder",
//                    "label" => "komposisi bahan baku work order",
//                    "filter" => array(
//                        "produk_id=produk_id",
//                        "fase_id=id",
//                        "jenis=.produk",
//                    ),
//                    "warningLabel" => "work order ini belum aktif, daftar kebutuhan bahan baku belum terdaftar. Segera hubungi admin.",
//                ),
            ),
        ),
    ),

    // membuat quotation dan approve manjadi so
    "588__" => array(
        "icon" => "fa fa-opencart",
        "label" => "new project",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "PROJECT QUOTATION",
                "actionLabel" => "make quotation",
                "source" => "",
                "target" => "588spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowEdit" => true,
            ),
            2 => array(
                "label" => "APPROVAL PROJECT QUOTATION",
                "actionLabel" => "approve quotation",
                "source" => "588spo",
                "target" => "588so",
                "userGroup" => "o_seller_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "followupMulti" => false,
            ),

//            3 => array(
//                "label" => "PACKING LIST PROJECT",
//                "actionLabel" => "process shipment",
//                "source" => "588so",
//                "target" => "588spd", // shipped
//                "userGroup" => "o_gudang",
//                "stateLabel" => "shipped",
//                "stateColor" => "#009900",
//                "stateCaption" => "shipped by",
//                "allowIncrement" => false,
//                "allowEdit" => true,
//            ),
//            4 => array(
//                "label" => "CLOSING PROJECT",
//                "actionLabel" => "close project",
//                "source" => "588spd",
//                "target" => "588", // invoice
//                "userGroup" => "o_finance",
//                "stateLabel" => "close",
//                "stateColor" => "#009900",
//                "stateCaption" => "completed by",
//                "allowJoin" => false,
//                //                "allowEdit" => true,
//            ),

        ),
        "template" => "template/transaksi.html",
        "selectorModel" => "MdlProdukProject",
        "selectorSrcModel" => "MdlProdukProject",
        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual", "ppv", "disc", "disc_percent"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //                "ppv" => "ppv",
            //                "disc" => "disc",
            //                "disc_percent" => "disc (%)",
            //            ),
            //            "mainSrc" => "jual",
        ),
        "selectedPrice2" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "hpp",
                "jual",
            ),
            "key_label" => array(
                "hpp" => "hpp",
                "jual" => "harga",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "customer_id=pihakID",
            "transaksi_id=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih project",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            //            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectBiaya/selectDefine",
        //bagian penambah items 2 di shopingCart
        "selectorLabel2" => "tambahkan item",
        "selectorModel2" => "MdlProduk2",
        "selectorCaller2" => "_selectorItem/selectAddItems2",//penampil selector item2
        "selectorProcessor2" => "_projectItemEditor/addItem",
        "selectorParamFields2" => array(
            "id" => "id",
            "nama" => "nama",
            "kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields2" => array(
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "shoppingCartFieldSrc2" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",

            "volume" => "volume",
            "berat" => "berat",
            "lebar" => "lebar",
            "tinggi" => "tinggi",
            "panjang" => "panjang",
            "jenis" => "jenis",
        ),
        "shoppingCartFields2" => array(
//            1 => array(
//                "nama" => "item name",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "satuan" => "uom",
//            ),
//            2 => array(
//                "nama" => "item name",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "jml" => "qty",
//                "satuan" => "uom",
//            ),
//            3 => array(
//                "nama" => "item name",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "jml" => "qty",
//                "satuan" => "uom",
//            ),
        ),
        "selectorDefaultMinValue2" => "1",
        "selectorSrcModel2" => "MdlHargaProduk",
        "shoppingCartNumFields2" => array(
//            1 => array(
//                "harga" => "price",
//                "order_qty" => "qty order",
//                "dikirim_qty" => "qty kirim",
//                "jml" => "qty",
////                "disc_percent" => "disc (%)",
////                "disc" => "disc (IDR)",
//                // "ppn"   => "VAT",
//            ),
//            2 => array(
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "harga" => "price",
//                "jml" => "qty",
//                //                "disc_percent" => "disc (%)",
//                //                "disc" => "disc (IDR)",
//                // "ppn"   => "VAT",
//            ),
//            3 => array(
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "jml" => "qty",
//
//            ),

        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
//                "disc_percent",
//                "disc",
                "harga",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),
            3 => array(
                //                "harga",
                "jml",
                "produk_ord_jml",
            ),

        ),
        "shoppingCartAmountValue2" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*",
            //            5 => "jml*(harga-disc)",
        ),
        //----  untuk items2 -----------------
        //items sub 3
        "shopingCartDetailFields2" => array(
            1 => array(
                "produk_nama" => "GRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => "GRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            3 => array(
                "produk_nama" => "GRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            4 => array(
                "produk_nama" => "GRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
        ),
        "shopingCartDetailFields2_sub" => array(
            2 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            3 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            4 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
        ),

        //---------

        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
//        "pihakModel"         => "MdlCustomerProject",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nama" => "project",
            "nomer_top" => "SO number",
            "nomer" => "SO Approve number",
            "oleh_nama" => "person",
            "harga" => "bruto",
//                "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "netto",
            "tanggalStart" => "Tanggal Mulai Pengerjaan",
            "tenggatWaktu" => "Tenggat Waktu",
            "description" => "deskripsi",
            "note" => "spesifikasi",
//            "print_label" => "tool",
//            "rencana" => "lihat rencana",
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
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nama" => "project",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "oleh_nama" => "person",
                "harga" => "bruto",
//                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "netto",
                "tanggalStart" => "Tanggal Mulai Pengerjaan",
                "tenggatWaktu" => "Tenggat Waktu",
                "description" => "deskripsi",
                "note" => "spesifikasi",
                "print_label" => "tool",
                "rencana" => "lihat rencana",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "SO Approve number",
                "oleh_nama" => "person",
                "harga" => "bruto",
//                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "netto",
                "tanggalStart" => "Tanggal Mulai Pengerjaan",
                "tenggatWaktu" => "Tenggat Waktu",
                "description" => "deskripsi",
                "note" => "spesifikasi",
                "print_label" => "tool",
                "rencana" => "lihat rencana",
            ),
//            3 => array(
////                "jenis_label" => "activity",
//                "dtime" => "date",
//                "cabang_nama" => "branch",
//                "customers_nama" => "customer",
//                "nomer_top" => "SO number",
//                "nomer_soa" => array(
//                    "step" => 2,
//                    "key" => "nomer",
//                    "label" => "SOA number",
//                ),
//                "nomer" => "PL number",
//                "oleh_nama" => "person",
//                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett2" => "total amount",
//                "print_label" => "tool",
//            ),
//            4 => array(
//                "jenis_label" => "activity",
//                "dtime" => "date",
//                "cabang_nama" => "branch",
//                "customers_nama" => "customer",
//                "nomer_top" => "SO number",
//                "nomer" => "INV number",
//                "oleh_nama" => "person",
//                //            "transaksi_nilai" => "amount",
//                "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett2" => "total amount",
//                "print_label" => "tool",
//            ),
//            5 => array(
//                // "jenis_label" => "activity",
//                "dtime" => "date",
//                "cabang_nama" => "branch",
//                "customers_nama" => "customer",
//                "nomer_top" => "SO number",
//                //                "nomer" => "receipt number",
//                "oleh_nama" => "person",
//                //            "transaksi_nilai" => "amount",
//                "jual" => "amount",
//                // "harga" => "amount",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett2" => "total amount",
//                "print_label" => "tool",
//            ),
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
                "nama" => "nama project",
//                "produk_kode" => "part number",
            ),
            2 => array(
                "nama" => "nama project",
//                "produk_kode" => "part number",
            ),
            3 => array(
                "nama" => "nama project",
//                "produk_kode" => "part number",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
        ),

        "shoppingCartNumFields" => array(
            1 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),
            3 => array(
                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                "ppn" => "VAT",
            ),

        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "nama",
//                "produk_kode",
                "harga",
                "produk_ord_jml",
                "disc_percent",
                "disc",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
                "harga",
                //                "disc_percent",
                //                "disc",
            ),
            3 => array(
                //                "harga",
                //                "jml",
                //                "produk_ord_jml",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
//            1 => array(
//                "base"    => "disc_percent",
//                "members" => array(
//                    "disc_percent",
//                    "disc",
//                ),
//            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
//            "projectID" => "nama project",//validasi project ada disession main
        ),
        "shoppingCartRowOptionalValidators" => array(),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            //            1 => "jml*(harga-disc)",//nett2
            //            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        // "shoppingCartSubamount2" => array(1 => true,),

        "shopingCartEdit_mode" => array(
            "requested" => "valid_qty",

        ),

        "shoppingCartHideSubamount" => array(
            1 => false,
            2 => false,
            3 => true,
            4 => true,
            5 => false,
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "amount",
                "ppn_out_bulat" => "PPN/vat",
                "new_net3" => "total amount",

            ),
            2 => array(
                "harga" => "amount",
                //                "disc" => "disc",
                //                "ongkir_ui" => "shipping service",
                //                "grand_total_ui" => "Net amount",
                "grand_ppn" => "PPN/vat",
                "new_net3" => "total amount",

            ),
            3 => array(
                "harga" => "amount",
                "grand_ppn" => "PPN/vat",
                "new_net3" => "total amount",
            ),
        ),
        "receiptMesurementRows" => array(),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "receiptElements" => array(

            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
//                "mdlName"     => "MdlCustomerProject",
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
            "billingDetails" => array(
                "elementType" => "dataModel",
                "elementJoint" => array(
                    "method" => "lookUpJointCustomer",
                    "mdlFilter" => "id=pihakID",
                    "usedFields" => array(
                        "no_ktp" => "nik",
                        "nik" => "nik",
                        "npwp" => "npwp",
                    ),
                ),
                "inputType" => "radio",
                "label" => "BILLING DETAILS",
                "mdlName" => "MdlCustomerBillAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                //                "mdlName" => "MdlCustomer_and_pre",
                //                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                //                "labelSrc" => "alias",
                "labelSrc" => "alias",
                "usedFields" => array(
                    //                    "alias" => "Name",
                    "alias" => "Name",
                    "alamat" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "NIK",
                ),
                "editPoints" => array(1, 4, 5),
            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlCustomerAddress",
//                "mdlFilter" => array("extern_id=pihakID"),
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
            "tanggalStart" => array(
                "elementType" => "dataField",
                "label" => "Tanggal Mulai Pengerjaan",
                "inputType" => "date",
//                "inputType" => "hidden",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(1),
                "noValidate" => true,
            ),
            "tenggatWaktu" => array(
                "elementType" => "dataField",
                "label" => "Tenggat Waktu",
                "inputType" => "date",
//                "inputType" => "hidden",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(1),
                "noValidate" => false,
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",//radio
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
                "defaultValue" => "credit",
            ),
            "branch" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",//radio
                "label" => "Cabang pelaksana project",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("jenis=.project"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "noValidate" => false,
                "editPoints" => array(),
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
        "relativeElements" => array(
            // "paymentMethod" => array(
            //     // "cash"   => array(
            //     //     "cash_account" => array(
            //     //         "elementType" => "dataModel",
            //     //         "inputType"   => "radio",
            //     //         "label"       => "bank account",
            //     //         "mdlName"     => "MdlBankAccount_in",
            //     //         "mdlFilter"   => array(
            //     //             "cabang_id=placeID",
            //     //             "currency_id=.0",
            //     //         ),
            //     //         "key"         => "id",
            //     //         "labelSrc"    => "nama",
            //     //         "usedFields"  => array(
            //     //             "nama" => "",
            //     //         ),
            //     //         "editPoints"  => array(1, 4),
            //     //     ),
            //     // ),
            //     // "cia"    => array(
            //     //     "cash_account" => array(
            //     //         "elementType" => "dataModel",
            //     //         "inputType"   => "radio",
            //     //         "label"       => "bank account",
            //     //         "mdlName"     => "MdlBankAccount_in",
            //     //         "mdlFilter"   => array(
            //     //             "cabang_id=placeID",
            //     //             "currency_id=.0",
            //     //         ),
            //     //         "key"         => "id",
            //     //         "labelSrc"    => "nama",
            //     //         "usedFields"  => array(
            //     //             "nama" => "",
            //     //         ),
            //     //         "editPoints"  => array(1,),
            //     //     ),
            //     // ),
            //     // "credit" => array(
            //     //     "top" => array(
            //     //         "elementType" => "dataModel",
            //     //         "inputType"   => "radio",
            //     //         "label"       => "term of payment",
            //     //         "mdlName"     => "MdlTop",
            //     //         "mdlFilter"   => array(),
            //     //         "key"         => "kode",
            //     //         "labelSrc"    => "nama",
            //     //         "description" => "",
            //     //         "usedFields"  => array(
            //     //             "nama" => "",
            //     //         ),
            //     //         "editPoints"  => array(1,),
            //     //     ),
            //     // ),
            //     //                "debit_card" => array(
            //     //                    "debit_account" => array(
            //     //                        "elementType" => "dataModel",
            //     //                        "inputType" => "radio",
            //     //                        "label" => "debit account",
            //     //                        "mdlName" => "MdlBankAccount",
            //     //                        "key" => "id",
            //     //                        "labelSrc" => "name",
            //     //                        "usedFields" => array(
            //     //                            "name" => "",
            //     //                        ),
            //     //                        "editPoints" => array(1,),
            //     //                    ),
            //     //                    "cash_account" => array(
            //     //                        "elementType" => "dataModel",
            //     //                        "inputType" => "radio",
            //     //                        "label" => "bank account",
            //     //                        "mdlName" => "MdlBankAccount",
            //     //                        "key" => "id",
            //     //                        "labelSrc" => "nama",
            //     //                        "usedFields" => array(
            //     //                            "nama" => "",
            //     //                        ),
            //     //                        "editPoints" => array(1,),
            //     //                    ),
            //     //                ),
            //     //                "credit_card" => array(
            //     //                    "credit_account" => array(
            //     //                        "elementType" => "dataModel",
            //     //                        "inputType" => "radio",
            //     //                        "label" => "credit account",
            //     //                        "mdlName" => "MdlCreditCard",
            //     //                        "key" => "id",
            //     //                        "labelSrc" => "name",
            //     //                        "usedFields" => array(
            //     //                            "name" => "",
            //     //                        ),
            //     //                        "editPoints" => array(1,),
            //     //                    ),
            //     //                    "cash_account" => array(
            //     //                        "elementType" => "dataModel",
            //     //                        "inputType" => "radio",
            //     //                        "label" => "bank account",
            //     //                        "mdlName" => "MdlBankAccount",
            //     //                        "key" => "id",
            //     //                        "labelSrc" => "nama",
            //     //                        "usedFields" => array(
            //     //                            "nama" => "",
            //     //                        ),
            //     //                        "editPoints" => array(1,),
            //     //                    ),
            //     //                ),
            // ),
        ),
        "relativeOptions" => array(),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "validateMeasurement" => array(),
        "validateReceiveElement" => array(
//            1 => array(
//                "billingDetails" => array(
//                    "npwp" => "NPWP Customer harap di isi dengan benar",
//                    "no_ktp" => "KTP Customer harap di isi dengan benar",
//                )
//            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items2_sum"
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
                    "gate" => "items2_sum",
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
            4 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "gate" => "items2_sum",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),

        ),
        "validationRules" => array(),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
        ),
        "additionalRows" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "allowedMainEdit" => array("1", "2"),
        "addMainStep" => array(
            "1784" => array(
                "jenis_master" => "584",
                "jenis" => "584",
                "target" => "1784",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
//        "connectTo" => "110",
//        "connectoValidate" => array(
//            2 => "nilai_credit",
//        ),
//        "replacerConnectTo" => array(
//            "cabang2ID" => "-1",
//            "cabang2Name" => "PUSAT",
//            "place2ID" => "-1",
//            "place2Name" => "PUSAT",
//            "gudang2ID" => "-1",
//            "gudang2Name" => "default center warehouse",
//            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
//        ),
        "previewCtr" => "Create",
        "editSubItem" => array(
            1 => true,
            2 => true,
        ),
        "itemSwaper" => array(
            4 => array(
                "id" => "id",
                "produk_id" => "id",
                "nama" => "nomer",
                "jml" => ".1",
                "dtime" => "dtime",
            ),
        ),
        "itemSwaperToMain" => array(
            4 => array(
                "projectID" => "id",
                "projectCode" => "produk_kode",
                "projectName" => "nama",
                "projectHarga" => "harga",
                "projectPpn" => "ppn",
                "projectNett" => "harga",
                "projectGrandtotal" => "nett2",
            ),
        ),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "projectName" => "Project",
                    "projectHarga" => "Nilai Project",
                    "tarifGaransi" => "Garansi (%)",
                    "garansi_nilai" => "Garansi (IDR)",
                    "dateGaransi" => "Masa Garansi ",
                ),
                "editableFields" => array(
                    "dateGaransi" => "date",
                ),
            ),
        ),
        "additionalPackinglist" => array(
            4 => array(
                "enabled" => true,
                "header" => array(
                    "produk_nama" => "item name",
                    "produk_kode" => "code",
//                    "stok_center" => "stok dc",
//                    "stok" => "stok available",
                    "qty" => "qty",
                    "satuan" => "uom",
                ),
            ),
        ),
        "validateClosing" => array(
            4 => array(
                "dateGaransi" => "Tanggal garansi belum ditentukan. Silahkan ditentukan terdahulu.",
            ),
        ),
        "validateClosingKey" => array(
            4 => array(
                "garansi_nilai",
            ),
        ),
        "validateClosingExtractedSubItems" => array(
            4 => array(
                "checklistnote_cek" => "Silahkan checklist Lanjutkan Close Project.",
            ),
        ),

//        "connectTo" => "5882",// pelaksanaan project
//        "addDetailData" => array(
//            2 => array("mdlName" => "MdlProdukProject"),
//        ),
        //----

        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "588spoe",
                "label" => "EDIT PROJECT QUOTATION",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "588sporj",
                "label" => "REJECT PROJECT QUOTATION",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "588sorj",
                "label" => "REJECT APPROVAL PROJECT QUOTATION",
            ),
//            3 => array(
//                "enabled" => true,
//                "connectTo" => "582pkdrj",
//                "label" => "REJECT PRE PACKING",
//            ),
        ),
    ),

    "588" => array(
        "icon" => "fa fa-opencart",
        "label" => "new project",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "CREATE PROJECT QUOTATION",
                "actionLabel" => "save",
                "source" => "",
                "target" => "588spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
                "allowEdit" => true,
                "allowCancel" => true,
            ),
            2 => array(
                "label" => "QUOTATION",
                "preActionLabel" => "PREVIEW QUOTATION",
                "actionLabel" => "setuju & next",
                "source" => "588spo",
                "target" => "588so",
                "userGroup" => "o_project_spv",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
                "followupMulti" => false,
                "allowCancel" => true,
            ),
            3 => array(
                "label" => "STARTED PROJECT",
                "preActionLabel" => "PREVIEW PROJECT",
                "actionLabel" => "start project",
                "source" => "588so",
                "target" => "588st", // shipped
                "userGroup" => "o_project_spv",
                "stateLabel" => "started",
                "stateColor" => "#009900",
                "stateCaption" => "started",
                "allowIncrement" => false,
                "allowEdit" => true,
                "allowCancel" => true,
            ),
            4 => array(
                "label" => "(TAHAP 1) Serah Terima Sementara",
                "preActionLabel" => "PREVIEW STARTED PROJECT",
                "actionLabel" => "(TAHAP 1) Serah Terima Sementara",
                "source" => "588st",
                "target" => "588sta",
                "userGroup" => "o_project_spv",
                "stateLabel" => "success",
                "stateColor" => "#009900",
                "stateCaption" => "success",
                "allowIncrement" => false,
                "allowEdit" => true,
                "allowCancel" => true,
                "allowReject" => true,
            ),
            5 => array(
                "label" => "(FINAL) Serah Terima Akhir Pekerjaan",
                "preActionLabel" => "PREVIEW SERAH TERIMA AKHIR",
                "actionLabel" => "(FINAL) Serah Terima Akhir Pekerjaan",
                "source" => "588sta",
                "target" => "588",
                "userGroup" => "o_project_spv",
                "stateLabel" => "success",
                "stateColor" => "#009900",
                "stateCaption" => "success",
//                "allowIncrement" => false,
//                "allowEdit" => true,
//                "allowCancel" => true,
            ),
        ),
        "template" => "template/transaksi.html",
//        "selectorModel" => "MdlProdukProject",
        "selectorModel" => "MdlProduk2",
//        "selectorSrcModel" => "MdlProdukProject",
        "selectorSrcModel" => "MdlProduk2",
        "selectedPrice" => array(
            "model" => "MdlHargaProduk",
            "label" => array("jual", "jual_nppn", "jual_reseller", "jual_online"),
            "key_label" => array(
                "jual_nppn" => "harga",
                "jual_reseller" => "harga_reseller",
                "jual_online" => "harga_online",
            ),
            "mainSrc" => "jual",
        ),
        "selectedPrice2" => array(
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "hpp", "hpp_nppv", "jual", "jual_nppn", "jual_reseller", "jual_online", "hpp_supplier"
            ),
            "key_label" => array(
                "hpp_supplier" => "hpp_supplier",
//                "jual" => "harga",
            ),
            "mainSrc" => "hpp_supplier",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
//            "customer_id=pihakID",
//            "transaksi_id=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih unit project",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            //            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectBiaya/",
        "editHandlerMethod" => "selectDefine",
        //bagian penambah items 2 di shopingCart
        "selectorLabel2" => "tambahkan item",
        "selectorModel2" => "MdlProduk2",
        "selectorCaller2" => "_selectorItem/selectAddItems2",//penampil selector item2
        "selectorProcessor2" => "_projectItemEditor/addItem",
        "selectorParamFields2" => array(
            "id" => "id",
            "nama" => "nama",
            "kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields2" => array(
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),
        "shoppingCartFieldSrc2" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(11/100)",
            "berat_gross" => "berat_gross",
            "lebar_gross" => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross" => "tinggi_gross",
            "volume_gross" => "volume_gross",

            "volume" => "volume",
            "berat" => "berat",
            "lebar" => "lebar",
            "tinggi" => "tinggi",
            "panjang" => "panjang",
            "jenis" => "jenis",
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "material",
                "satuan" => "satuan",
//                "produk_kode" => "code",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
            2 => array(
                "nama" => "material",
                "satuan" => "satuan",
//                "produk_kode" => "code",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
            3 => array(
                "nama" => "material",
                "satuan" => "satuan",
//                "produk_kode" => "code",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
        ),//untuk field komposisi produk
        "shoppingCartFields3" => array(
            1 => array(
                "nama" => "biaya",
                "satuan" => "satuan",
//                "produk_kode" => "code",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
            ),
//            2 => array(
//                "nama" => "item name",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "jml" => "qty",
//                "satuan" => "uom",
//            ),
//            3 => array(
//                "nama" => "item name",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "jml" => "qty",
//                "satuan" => "uom",
//            ),
        ),//untuk field biaya
        "selectorDefaultMinValue2" => "1",
        "selectorSrcModel2" => "MdlHargaProduk",
        "shoppingCartNumFields2" => array(
            1 => array(
                "jml" => "jumlah",
                "hpp_supplier" => "harga perolehan",
                "harga" => "harga anggaran",
                "subtotal" => "subtotal",
            ),
            2 => array(
                "jml" => "jumlah",
                "hpp_supplier" => "nilai anggaran",
                "harga" => "nilai project",
                "subtotal" => "subtotal",
            ),
            3 => array(
                "jml" => "jumlah",
                "hpp_supplier" => "nilai anggaran",
                "harga" => "nilai project",
                "subtotal" => "subtotal",
            ),
        ),
        "shoppingCartNumFields3" => array(
            1 => array(
                "jml" => "jumlah",
//                "produk_ord_jml" => "nilai anggaran",
                "anggaran" => "harga perolehan",
                "harga" => "harga anggaran",
//                "sub_anggaran" => "subtotal anggaran",
                "subtotal" => "subtotal",
            ),
            2 => array(
                "jml" => "jumlah",
//                "produk_ord_jml" => "nilai anggaran",

                "anggaran" => "nilai anggaran",
                "harga" => "nilai project",
                "subtotal" => "subtotal",
            ),
            3 => array(
                "jml" => "jumlah",
//                "produk_ord_jml" => "nilai anggaran",

                "anggaran" => "nilai anggaran",
                "harga" => "nilai project",
                "subtotal" => "subtotal",
            ),
        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "harga",
                "jual",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),
//            3 => array(
//                //                "harga",
//                "jml",
//                "produk_ord_jml",
//            ),
        ),
        "shoppingCartEditableFields3" => array(
            1 => array(
                "jml",
//                "produk_ord_jml",
                "harga",
                "jual",
            ),
            2 => array(
                "jml",
//                "produk_ord_jml",
                "harga",
                "jual",
            ),
//            3 => array(
//                //                "harga",
//                "jml",
////                "produk_ord_jml",
//                "harga",
//                "jual",
//            ),
        ),
        "shoppingCartAmountValue2" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml*(harga-disc)",//nett2
            4 => "jml*(harga-disc)",//nett2
            5 => "jml*(harga-disc)",//nett2
            //            5 => "jml*(harga-disc)",
        ),
        //----  untuk items2 -----------------
        "shoppingCartPairedItemRecorder" => "recordPairedItem",
        "shoppingCartPairedItem" => array(
            "enabled" => true,
            "mdlName" => "MdlProdukRelasiProject",
            "sub_gate" => "produk",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array(
                "id<>id",
//                "allow_project=.1",
//                "satuan_nilai<satuan_nilai",
//                "produk_part_ukuran_id=produk_part_ukuran_id"
            ),
            "targetGateName" => "items2_sum",
        ),
        "shoppingCartPairedItem2" => array(
            "enabled" => true,
            "mdlName" => "MdlDtaBiayaProduksi",
            "sub_gate" => "biaya",
            "srcKey" => "id",
            "srcLabel" => array("nama"),
            "mdlFilter" => array(
//                "id<>id",
//                "kategori_id=kategori_id",
//                "satuan_nilai<satuan_nilai",
//                "produk_part_ukuran_id=produk_part_ukuran_id"
            ),
            "targetGateName" => "items2_sum",
        ),


        //items sub 3
        "shopingCartDetailFields2" => array(
            1 => array(
                "hpp" => "harga beli",
                "harga" => "harga jual",
                "jml" => "jumlah",
                "subtotal" => "subtotal",
            ),
            2 => array(
                "hpp" => "harga beli",
                "harga" => "harga jual",
                "jml" => "jumlah",
                "subtotal" => "subtotal",
            ),
            3 => array(
                "hpp" => "harga beli",
                "harga" => "harga jual",
                "jml" => "jumlah",
                "subtotal" => "subtotal",
            ),
            4 => array(
                "hpp" => "harga beli",
                "harga" => "harga jual",
                "jml" => "jumlah",
                "subtotal" => "subtotal",
            ),
            5 => array(
                "hpp" => "harga beli",
                "harga" => "harga jual",
                "jml" => "jumlah",
                "subtotal" => "subtotal",
            ),
        ),
        "shopingCartDetailFields2_sub" => array(
            2 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            3 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            4 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            5 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
        ),
        //---------
        "swappedKeys" => array("pihakID", "pihakName"),
//        "editHandlerMethod" => "select",
//        "pihakModel"         => "MdlCustomerProject",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakView" => "npwp",
        "pihakProcessor" => "_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
//            "transaksi_id" => "trxID",
            "dtime" => "date",
//            "cabang_nama" => "branch",
            "customers_nama" => "customer",
            "nama" => "project",
            "nomer_top" => "SO number",
//            "nomer" => "SO Approve number",
//            "oleh_nama" => "person",
//            "harga" => "bruto",
//            "ppn" => "ppn",
//            "nett2" => "netto",
//            "tanggalStart" => "Tanggal Mulai Pengerjaan",
//            "tenggatWaktu" => "Tenggat Waktu",
//            "description" => "deskripsi",
            "note" => "spesifikasi",
//            "disc" => "discount",
//            "print_label" => "tool",
//            "rencana" => "lihat rencana",
            "description" => "catatan",
            "keterangan" => "keterangan",
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
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nama" => "project",
//                "review_details" => "review",
                "nomer_top" => "SO number",
                "oleh_nama" => "person",
//                "harga" => "bruto",
//                "disc" => "discount",
//                "ppn" => "ppn",
//                "nett2" => "netto",
//                "tanggalStart" => "Tanggal Mulai Pengerjaan",
//                "tenggatWaktu" => "Tenggat Waktu",
//                "description" => "deskripsi",
                "note" => "spesifikasi",
//                "print_label" => "tool",
//                "rencana" => "lihat rencana",
                "description" => "catatan",
                "keterangan" => "keterangan",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
//                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "SO Approve number",
                "oleh_nama" => "person",
                "harga" => "bruto",
//                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "netto",
//                "tanggalStart" => "Tanggal Mulai Pengerjaan",
//                "tenggatWaktu" => "Tenggat Waktu",
//                "description" => "deskripsi",
                "note" => "spesifikasi",
//                "print_label" => "tool",
//                "rencana" => "lihat rencana",
                "description" => "catatan",
                "keterangan" => "keterangan",
            ),
            3 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "project_id" => "project ID",
                "project_nama" => "project Name",
//                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "Project number",
                "oleh_nama" => "person",
                "harga" => "bruto",
//                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "netto",
//                "tanggalStart" => "Tanggal Mulai Pengerjaan",
//                "tenggatWaktu" => "Tenggat Waktu",
//                "description" => "deskripsi",
                "note" => "spesifikasi",
//                "print_label" => "tool",
//                "rencana" => "lihat rencana",
                "description" => "catatan",
                "keterangan" => "keterangan",
            ),
            4 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "project_id" => "project ID",
                "project_nama" => "project Name",
//                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "Project number",
                "oleh_nama" => "person",
                "harga" => "bruto",
//                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "netto",
//                "tanggalStart" => "Tanggal Mulai Pengerjaan",
//                "tenggatWaktu" => "Tenggat Waktu",
//                "description" => "deskripsi",
                "note" => "spesifikasi",
//                "print_label" => "tool",
//                "rencana" => "lihat rencana",
                "description" => "catatan",
                "keterangan" => "keterangan",
            ),
            5 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "project_id" => "project ID",
                "project_nama" => "project Name",
//                "review_details" => "review",
                "nomer_top" => "SO number",
                "nomer" => "Project number",
                "oleh_nama" => "person",
                "harga" => "bruto",
//                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "netto",
//                "tanggalStart" => "Tanggal Mulai Pengerjaan",
//                "tenggatWaktu" => "Tenggat Waktu",
//                "description" => "deskripsi",
                "note" => "spesifikasi",
//                "print_label" => "tool",
//                "rencana" => "lihat rencana",
                "description" => "catatan",
                "keterangan" => "keterangan",
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
                "nama" => "nama project",
//                "produk_kode" => "part number",
            ),
            2 => array(
                "nama" => "nama project",
//                "produk_kode" => "part number",
            ),
            3 => array(
                "nama" => "nama project",
//                "produk_kode" => "part number",

            ),
            4 => array(
                "nama" => "nama project",
//                "produk_kode" => "part number",

            ),
            5 => array(
                "nama" => "nama project",
//                "produk_kode" => "part number",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(11/100)",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "harga" => "price",
//                "ppn" => "VAT*",
            ),
            2 => array(
//                "harga" => "price",
//                "ppn" => "VAT**",
            ),
            3 => array(
                "harga" => "price",
                "ppn" => "VAT",
                "sub_nett2" => "subtotal",
            ),
            4 => array(
//                "harga" => "price",
//                "ppn" => "VAT***",
            ),
            5 => array(
//                "harga" => "price",
//                "ppn" => "VAT***",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array(
                "nama",
//                "produk_kode",
//                "harga",
//                "produk_ord_jml",
//                "disc_percent",
//                "disc",
            ),
            2 => array(
                //                "jml",
                //                "produk_ord_jml",
//                "harga",
                //                "disc_percent",
                //                "disc",
            ),
            3 => array(
//                                "harga",
                //                "jml",
                //                "produk_ord_jml",
            ),
            4 => array(
                //                "harga",
                //                "jml",
                //                "produk_ord_jml",
            ),
            5 => array(
                //                "harga",
                //                "jml",
                //                "produk_ord_jml",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
//            1 => array(
//                "base"    => "disc_percent",
//                "members" => array(
//                    "disc_percent",
//                    "disc",
//                ),
//            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
//            "projectID" => "nama project",//validasi project ada disession main
        ),
        "shoppingCartRowMidValidatorsStep" => array(
            3 => array(
                "pihakID" => "customer ID",
                "pihakName" => "customer nama/label",
//            "projectID" => "nama project",//validasi project ada disession main
                "grandTotal" => "nilai project",
            ),

        ),
        "shoppingCartRowOptionalValidators" => array(),
        "shoppingCartAmountValue" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            //            1 => "jml*(harga-disc)",//nett2
            //            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        // "shoppingCartSubamount2" => array(1 => true,),
        "shopingCartEdit_mode" => array(
            "requested" => "valid_qty",
        ),
        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => false,
            5 => false,
        ),
        "shoppingCartHideSubamount2" => array(
            1 => false,
            2 => false,
            3 => false,
            4 => false,
            5 => false,
        ),
//        "shoppingCartSubDetailFields" => array(
//            1 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
//                ),
//                "produk_nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
//                ),
//            ),
//            2 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
//                ),
//                "produk_nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
//                ),
//            ),
//            3 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
//                ),
//                "produk_nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
//                ),
//            ),
//            4 => array(
//                "nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
//                ),
//                "produk_nama" => array(
//                    "source" => "items3_sum",
//                    "tipe" => "textarea",
//                    "gate" => "produk_serial",
//                ),
//            ),
//        ),
        "shoppingCartSumFields" => array(
            1 => array(
//                "harga_bruto2" => "harga bruto (Excl.PPN)",
//                "diskon_pembulatan" => "Diskon/Pembulatan",
//                "harga_items2" => "Harga Netto",
//                "ppn_items2" => "PPN/vat",
//                "harga_nppn" => "subtotal",
            ),
            2 => array(
                "harga_items2" => "amount",
                "ppn_items2" => "PPN/vat",
                "harga_nppn" => "total amount",
            ),
            3 => array(
//                "harga_items2" => "amount",
//                "ppn_items2" => "PPN/vat",
//                "harga_nppn" => "total amount",
                "harga" => "amount",
                "grand_ppn" => "PPN/vat",
                "new_net3" => "total amount",
            ),
            4 => array(
//                "harga" => "amount",
//                "grand_ppn" => "PPN/vat",
//                "new_net3" => "total amount",
            ),
            5 => array(
//                "harga" => "amount",
//                "grand_ppn" => "PPN/vat",
//                "new_net3" => "total amount",
            ),
        ),
        "receiptMesurementRows" => array(),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
            3 => false,
            4 => false,
            5 => false,
        ),
        "shoppingCartImageEnabled" => true,
//        "shoppingCartImageType" => "images",
        "shoppingCartImageType" => "files",
        "shoppingCartValueValidate" => array(
            3 => array(
                "enabled" => true,
                "keys" => array(
                    "dpp_ppn" => "DPP Project tidak boleh 0. Silahkan diperiksa lagi.",
                    "new_grand_ppn" => "PPN Project tidak boleh 0. Silahkan diperiksa lagi.",
                    "grandTotal" => "Nilai Project termasuk PPN tidak boleh 0. Silahkan diperiksa lagi.",
                ),
            ),
        ),

        "receiptElements" => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
//                "mdlName"     => "MdlCustomerProject",
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
                "editPoints" => array(),
            ),
            "billingDetails" => array(
                "elementType" => "dataModel",
                "elementJoint" => array(
                    "method" => "lookUpJointCustomer",
                    "mdlFilter" => "id=pihakID",
                    "usedFields" => array(
                        "no_ktp" => "nik",
                        "nik" => "nik",
                        "npwp" => "npwp",
                    ),
                ),
                "inputType" => "radio",
                "label" => "BILLING DETAILS",
                "mdlName" => "MdlCustomerBillAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                //                "mdlName" => "MdlCustomer_and_pre",
                //                "mdlFilter" => array("id=pihakID"),
                "key" => "id",
                //                "labelSrc" => "alias",
                "labelSrc" => "alias",
                "usedFields" => array(
                    //                    "alias" => "Name",
                    "alias" => "Name",
                    "alamat" => "Addr",
                    "kelurahan" => "Kel",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "Prop",
                    "tlp" => "Phone",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Handphone",
                    "npwp" => "NPWP",
                    "no_ktp" => "NIK",
                ),
                "editPoints" => array(),
            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlCustomerAddress",
//                "mdlFilter" => array("extern_id=pihakID"),
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
            "tanggalStart" => array(
                "elementType" => "dataField",
                "label" => "Tanggal Mulai Pengerjaan",
                "inputType" => "date",
//                "inputType" => "hidden",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(),
                "noValidate" => true,
            ),
            "tenggatWaktu" => array(
                "elementType" => "dataField",
                "label" => "Tanggal Batas Akhir Project",
                "inputType" => "date",
//                "inputType" => "hidden",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints" => array(),
                "noValidate" => false,
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",//radio
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(),
                "defaultValue" => "credit",
            ),
            "branch" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",//radio
                "label" => "Cabang pelaksana project",
                "mdlName" => "MdlCabang",
//                "mdlFilter" => "id=placeID",
                "mdlFilter" => array("id=placeID"),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                ),
                "noValidate" => false,
                "editPoints" => array(),
            ),
            "dummyElement" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "auto-validation",
                "mdlName" => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "name",
                ),
                "editPoints" => array(),
            ),
        ),
        "relativeElements" => array(
            // "paymentMethod" => array(
            //     // "cash"   => array(
            //     //     "cash_account" => array(
            //     //         "elementType" => "dataModel",
            //     //         "inputType"   => "radio",
            //     //         "label"       => "bank account",
            //     //         "mdlName"     => "MdlBankAccount_in",
            //     //         "mdlFilter"   => array(
            //     //             "cabang_id=placeID",
            //     //             "currency_id=.0",
            //     //         ),
            //     //         "key"         => "id",
            //     //         "labelSrc"    => "nama",
            //     //         "usedFields"  => array(
            //     //             "nama" => "",
            //     //         ),
            //     //         "editPoints"  => array(1, 4),
            //     //     ),
            //     // ),
            //     // "cia"    => array(
            //     //     "cash_account" => array(
            //     //         "elementType" => "dataModel",
            //     //         "inputType"   => "radio",
            //     //         "label"       => "bank account",
            //     //         "mdlName"     => "MdlBankAccount_in",
            //     //         "mdlFilter"   => array(
            //     //             "cabang_id=placeID",
            //     //             "currency_id=.0",
            //     //         ),
            //     //         "key"         => "id",
            //     //         "labelSrc"    => "nama",
            //     //         "usedFields"  => array(
            //     //             "nama" => "",
            //     //         ),
            //     //         "editPoints"  => array(1,),
            //     //     ),
            //     // ),
            //     // "credit" => array(
            //     //     "top" => array(
            //     //         "elementType" => "dataModel",
            //     //         "inputType"   => "radio",
            //     //         "label"       => "term of payment",
            //     //         "mdlName"     => "MdlTop",
            //     //         "mdlFilter"   => array(),
            //     //         "key"         => "kode",
            //     //         "labelSrc"    => "nama",
            //     //         "description" => "",
            //     //         "usedFields"  => array(
            //     //             "nama" => "",
            //     //         ),
            //     //         "editPoints"  => array(1,),
            //     //     ),
            //     // ),
            //     //                "debit_card" => array(
            //     //                    "debit_account" => array(
            //     //                        "elementType" => "dataModel",
            //     //                        "inputType" => "radio",
            //     //                        "label" => "debit account",
            //     //                        "mdlName" => "MdlBankAccount",
            //     //                        "key" => "id",
            //     //                        "labelSrc" => "name",
            //     //                        "usedFields" => array(
            //     //                            "name" => "",
            //     //                        ),
            //     //                        "editPoints" => array(1,),
            //     //                    ),
            //     //                    "cash_account" => array(
            //     //                        "elementType" => "dataModel",
            //     //                        "inputType" => "radio",
            //     //                        "label" => "bank account",
            //     //                        "mdlName" => "MdlBankAccount",
            //     //                        "key" => "id",
            //     //                        "labelSrc" => "nama",
            //     //                        "usedFields" => array(
            //     //                            "nama" => "",
            //     //                        ),
            //     //                        "editPoints" => array(1,),
            //     //                    ),
            //     //                ),
            //     //                "credit_card" => array(
            //     //                    "credit_account" => array(
            //     //                        "elementType" => "dataModel",
            //     //                        "inputType" => "radio",
            //     //                        "label" => "credit account",
            //     //                        "mdlName" => "MdlCreditCard",
            //     //                        "key" => "id",
            //     //                        "labelSrc" => "name",
            //     //                        "usedFields" => array(
            //     //                            "name" => "",
            //     //                        ),
            //     //                        "editPoints" => array(1,),
            //     //                    ),
            //     //                    "cash_account" => array(
            //     //                        "elementType" => "dataModel",
            //     //                        "inputType" => "radio",
            //     //                        "label" => "bank account",
            //     //                        "mdlName" => "MdlBankAccount",
            //     //                        "key" => "id",
            //     //                        "labelSrc" => "nama",
            //     //                        "usedFields" => array(
            //     //                            "nama" => "",
            //     //                        ),
            //     //                        "editPoints" => array(1,),
            //     //                    ),
            //     //                ),
            // ),
        ),
        "relativeOptions" => array(),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "validateMeasurement" => array(),
        "validateReceiveElement" => array(
//            1 => array(
//                "billingDetails" => array(
//                    "npwp" => "NPWP Customer harap di isi dengan benar",
//                    "no_ktp" => "KTP Customer harap di isi dengan benar",
//                )
//            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items2_sum"
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
                    "gate" => "items2_sum",
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
            4 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "gate" => "items2_sum",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
            5 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "gate" => "items2_sum",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                ),
                "stokProduk_center" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => ".-1",
                        "gudang_id" => ".-1",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
            3 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),
        ),
        "validationRules" => array(),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(

                    "total_anggaran" => array(
                        "label" => "Total Harga Perolehan (HPP)",
                        "defaultValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "harga_non_ppn" => array(
                        "label" => "Total Harga Anggaran",
                        "defaultValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "harga_jual_project" => array(
                        "label" => "Harga Jual Project<br>Excl.PPN",
                        "defaultValue" => ".0",
                        "keyupAction" => "$(this).val(addCommas(removeCommas(this.value)));",
//                        'disabled' => "false",
                        "addPoints" => array(1,),
                    ),

                    "harga_jual_project_ppn" => array(
                        "label" => "PPN (11%)",
                        "defaultValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1),
                    ),
                    "harga_jual_project_nppn" => array(
                        "label" => "GrandTotal",
                        "defaultValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

                    "perkiraan_rl_hpp" => array(
                        "label" => "Perkiraan R/L HPP",
                        "defaultValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),
                    "perkiraan_rl_ang" => array(
                        "label" => "Perkiraan R/L Anggaran",
                        "defaultValue" => ".0",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                    ),

//                    "harga_bruto2" => array(
//                        "label" => "harga bruto (Excl.PPN)",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "diskon_pembulatan" => array(
//                        "label" => "Diskon/Pembulatan",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "harga_items2" => array(
//                        "label" => "Harga Netto",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "ppn_items2" => array(
//                        "label" => "PPN/vat",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "",
//                        'disabled' => "disabled",
//                        "addPoints" => array(1,),
//                    ),
//                    "harga_nppn" => array(
//                        "label" => "subtotal",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "",
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
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "allowedMainEdit" => array("1", "2"),
        "addMainStep" => array(
//            "1784" => array(
//                "jenis_master" => "584",
//                "jenis" => "584",
//                "target" => "1784",
//                "status_4" => "1",
//                "trash_4" => "0",
//            ),
        ),

//        "connectTo" => "110",
//        "connectoValidate" => array(
//            2 => "nilai_credit",
//        ),
//        "replacerConnectTo" => array(
//            "cabang2ID" => "-1",
//            "cabang2Name" => "PUSAT",
//            "place2ID" => "-1",
//            "place2Name" => "PUSAT",
//            "gudang2ID" => "-1",
//            "gudang2Name" => "default center warehouse",
//            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
//        ),
        "previewCtr" => "Create",
        "editSubItem" => array(
            1 => true,
            2 => true,
        ),
        "itemSwaper" => array(
            4 => array(
                "id" => "id",
                "produk_id" => "id",
                "nama" => "nomer",
                "jml" => ".1",
                "dtime" => "dtime",
            ),
        ),
        "itemSwaperToMain" => array(
            4 => array(
                "projectID" => "id",
                "projectCode" => "produk_kode",
                "projectName" => "nama",
                "projectHarga" => "harga",
                "projectPpn" => "ppn",
                "projectNett" => "harga",
                "projectGrandtotal" => "nett2",
            ),
        ),
        "addMainSource" => array(
            3 => array(
                "fields" => array(
                    "projectName" => "Project",
                    "projectHarga" => "Nilai Project",
                    "ppn" => "PPN (11%)",
                    "nett2" => "Netto Project",
                    "no_kontrak" => "Ref. No Kontrak",
                    "tgl_kontrak" => "Tgl Kontrak",
                    "startDate" => "Tgl Mulai Kontrak",
                    "endDate" => "Tgl Berakhir Kontrak",
//                    "tarifGaransi" => "Garansi (%)",
//                    "garansi_nilai" => "Garansi (IDR)",
//                    "dateGaransi" => "Masa Garansi ",
                ),
                "editableFields" => array(
//                    "dateGaransi" => "date",
                    "no_kontrak" => "text",
                    "tgl_kontrak" => "date",
                    "startDate" => "date",
                    "endDate" => "date",
                ),
            ),
            4 => array(
                "fields" => array(
                    "projectName" => "Project",
                    "projectHarga" => "Nilai Project",
                    "tarifGaransi" => "Garansi (%)",
                    "garansi_nilai" => "Garansi (IDR)",
                    "dateGaransi" => "Masa Garansi ",
                ),
                "editableFields" => array(
                    "dateGaransi" => "date",
                    "tarifGaransi" => "text",
                    "garansi_nilai" => "text",
                ),
            ),
        ),
        "additionalPackinglist" => array(
            4 => array(
                "enabled" => true,
                "header" => array(
                    "produk_nama" => "item name",
                    "produk_kode" => "code",
//                    "stok_center" => "stok dc",
//                    "stok" => "stok available",
                    "qty" => "qty",
                    "satuan" => "uom",
                ),
            ),
        ),
        "validateClosing" => array(
            4 => array(
                "dateGaransi" => "Tanggal garansi belum ditentukan. Silahkan ditentukan terdahulu.",
            ),
        ),
        "validateClosingKey" => array(
            4 => array(
                "garansi_nilai",
            ),
        ),
        "validateClosingExtractedSubItems" => array(
            4 => array(
                "checklistnote_cek" => "Silahkan checklist Lanjutkan Close Project.",
            ),
        ),
//        "connectTo" => "5882",// pelaksanaan project
//        "addDetailData" => array(
//            2 => array("mdlName" => "MdlProdukProject"),
//        ),
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "588spoe",
                "label" => "EDIT PROJECT QUOTATION",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "588sporj",
                "label" => "REJECT PROJECT QUOTATION",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "588sorj",
                "label" => "REJECT APPROVAL PROJECT QUOTATION",
            ),
            3 => array(
                "enabled" => true,
                "connectTo" => "588strj",
                "label" => "REJECT ALL STEP PROJECT",
            ),

//            3 => array(
//                "enabled" => true,
//                "connectTo" => "582pkdrj",
//                "label" => "REJECT PRE PACKING",
//            ),
        ),
        "cabangValidator" => array(
            "enabled" => true,
            "label" => "Transaksi salah, karena Approval/Otorisasi Project terdeteksi disimpan di DC/Pusat. Perhatikan login anda atau Login Ulang.",
        ),
    ),

    // uang muka konsumen, memiliki referensi ke project
    "4469" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "uang muka (dp tanpa ppn)",
        "place" => "branch",
        "paymentConfig" => true,
        "isPaymentRadioSelect" => true,
        "steps" => array(
            1 => array(
                "label" => "uang muka (dp tanpa ppn)",
                "actionLabel" => "uang muka",
                "source" => "",
                "target" => "4469",
                "userGroup" => "o_finance",
                "stateLabel" => "uang muka",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
//            2 => array(
//                "label" => "approval uang muka (dp tanpa ppn)",
//                "actionLabel" => "approve uang muka",
//                "source" => "4469r",
//                "target" => "4469",
//                "userGroup" => "o_finance",
//                "stateLabel" => "uang muka approved",
//                "stateColor" => "#ff7700",
//                "stateCaption" => "Approval by",
//            ),
        ),
        "template" => "template/transaksi_uang_muka.html",
        "selectorModel" => "MdlUangMuka",
        "selectorSrcModel" => "MdlUangMuka",
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
            "status=.1",
            "trash=.0",
            "ppn=.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
//        "selectorProcessor" => "_processSelectBiaya/selectUangMuka",
        "selectorProcessor" => "_processSelectNota/select",
        "editHandlerMethod" => "select",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
        "pihakProcessor" => "_processPihak/selectUangMuka",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
//            "harga" => "amount",
            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
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

        "shoppingCartFields" => array(
            1 => array(
                "nama" => "item name",
//                "extern2_nama" => "metode pph23",
                "jml" => "qty",
            ),
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item name",
                "jml" => "qty",
                "harga" => "price",
                "ppn" => "PPN",
            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nomer",
            "tagihan" => "tagihan",
            "terbayar" => "terbayar",
            "sisa" => "sisa",
            "extern_nilai2" => "extern_nilai2",
            "extern_nilai3" => "extern_nilai3",
            "extern_nilai4" => "extern_nilai4",
            "extern_nilai5" => "extern_nilai5",
            "ppn" => "ppn",
            "extern2_id" => "extern2_id",
            "extern2_nama" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "extern_nilai5" => "DPP pph21",
//                "extern_nilai2" => "DPP pph23",
//                "extern_nilai3" => "DPP ppn",
//                "ppn" => "ppn",
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "sisa" => "nilai project",
            ),

        ),
        "shoppingCartEditableFields" => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove" => true,

        "shoppingCart" => array(
            "initPrices" => "beli",
        ),
//        "shoppingCartFieldSrc" => array(
//            "nama" => "nama",
//        ),
//        "shoppingCartFields" => array(
//            1 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//            ),
//            2 => array(
//                "nama" => "Description",
//                "jml" => "Qty",
//            ),
//        ),
//        "shoppingCartNumFields" => array(
//            1 => array(
//                "harga" => "Unit Price",
//            ),
//            2 => array(
//                "harga" => "Unit Price",
//            ),
//        ),
        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),
//        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//            ),
//            2 => array(
//                "harga",
//            ),
//        ),
        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
        ),
//        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga-disc+ppn)",
//            2 => "jml*(harga-disc+ppn)",
//        ),
        "shoppingCartReferenceFields" => array(
            "nomer" => "nomer transaksi",
//            "nomer_top" => "receipt ref.",
//            "refNum" => "return ref.",
            "fulldate" => "date",
//            "tagihan" => "due amount",
//            "refValue" => "returned",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "harga",
            "notes" => "keterangan",
        ),
        "shoppingCartReferenceExternFields" => array(
            "extern_nama" => "---",
//            "tagihan" => "due amount",
//            "terbayar" => "paid",
//            "diskon" => "discount",
            "sisa" => "harga",
        ),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(

                    "sisa" => array(
                        "label" => "sisa",
                        "defaultValue" => "sisa",
                        "keyupAction" => "",
                        'disabled' => "disabled",
                        "addPoints" => array(1,),
                        'hideRow' => "true",
                    ),

                    "nilai_entry" => array(
                        "label" => "uang muka diterima",
                        "defaultValue" => ".0",
                        "keyupAction" => "

     if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('sisa').value)) || parseInt(removeCommas(this.value))<0){this.value=(document.getElementById('sisa').value);}
                            ",
                        //                        'disabled'     => "disabled",
                        "addPoints" => array(1,),
                    ),

//                    "nilai_entry" => array(
//                        "label" => "payment",
//                        "defaultValue" => ".0",
//                        "keyupAction" => "
//    if(parseInt(removeCommas(this.value))>parseInt(removeCommas(document.getElementById('harus_bayar').value)) || parseInt(removeCommas(this.value))<0){this.value=(document.getElementById('harus_bayar').value);}
//
//                            ",
//                        //                        'disabled'     => "disabled",
//                        "addPoints" => array(1,),
//                    ),


                ),
            ),
        ),

        "pairRegistries" => array(
            "main",
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
                    "nama" => "",
                    "alamat_1" => "",
                    "country" => "Country",
                    "tlp_1" => "Phone",
                    "tlp_2" => "Fax",
                    "npwp" => "NPWP",
                    "alias" => "Attn",
                    "contact_person" => "Attn",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "projectDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "PROJECT DETAILS",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array(
                    "customer_id=pihakID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "transaksi_no" => "nomer so",
                    "harga" => "harga",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "cash_account" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "cash account",
                "mdlName" => "MdlBankAccount_cash_and_in",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "account number",
                    "alias" => "holder alias",
                    //                    "saldo" => "balance",
                ),
                "editPoints" => array(1, 2,),
//                "noValidate" => true,
                "pairMethod" => array(
                    "recom" => "ReComCashAccountJenis",
                    "calculate" => array(
                        "source" => "cash_account",
                        "filter" => array(
                            "cabang_id=placeID",
                        ),
                        "result" => array(
                            "nilai_setoran_tunai" => "nett",
                        ),
                    ),
                ),
                "labelValidate" => "Silahkan memilih rekening bank tujuan penerimaan uang muka sebelum melanjutkan transaksi.",
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
        "relativeElements" => array(
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
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464re",
                "label" => "EDIT request uang muka (dp tanpa ppn)",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
        ),
    ),

    /*
 * budgeting project auto exec pakai CLI ketia sduah ada wo
 */
    "5883" => array(
        "icon" => "fa fa-opencart",
        "label" => "realisasi project",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "REALISASI PROJECT",
                "actionLabel" => "realisasi project",
                "source" => "",
                "target" => "5883",
                "userGroup" => "sys",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
            ),
        ),
        "template" => "template/transaksi_project.html",

        "selectorModel" => "MdlProjectWorkOrderSub",
        "selectorSrcModel" => "MdlProjectWorkOrderSub",
        "selectorSubSrcModel" => "MdlProjectKomposisiWorkorderSub",
//        "selectorSrcModel" => "MdlProjectKomposisiWorkorderSub",
        "selectorModelEdit" => "MdlProduk2",
        "selectorSrcModelEdit" => "MdlProduk2",

        "selectedPrice" => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual", "ppv", "disc", "disc_percent"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //                "ppv" => "ppv",
            //                "disc" => "disc",
            //                "disc_percent" => "disc (%)",
            //            ),
            //            "mainSrc" => "jual",
        ),
        "selectedPrice2" => array(
            //            "model" => "MdlHargaProduk", // hanya ambil harga produk (fg)
            "model" => "MdlHargaProduk2", // ambil harga produk fg dan rakitan
            "label" => array(
                "hpp",
                "jual",
            ),
            "key_label" => array(
                "hpp" => "hpp",
                "jual" => "harga",
            ),
            "mainSrc" => "jual",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
//            "customer_id=pihakID",
//            "transaksi_id=.0",

        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "pilih work order",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
            "produk_kode" => "kode",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "kode",
            //            "satuan",// "jumlah"
        ),
        "selectorProcessor" => "_processSelectBiaya/selectProject",
        //bagian penambah items 2 di shopingCart
        "selectorLabel2" => "tambahkan item",
        "selectorModel2" => "MdlProduk2",
        "selectorCaller2" => "_selectorItem/selectAddItems2",//penampil selector item2
        "selectorProcessor2" => "_projectItemEditor/addItem",
        "selectorParamFields2" => array(
            "id" => "id",
            "nama" => "nama",
            "kode" => "kode",
            "satuan" => "satuan",
        ),
        "selectorViewedFields2" => array(
            "nama",
            "kode",
            "satuan",// "jumlah"
        ),

        "shoppingCartFieldSrc2" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "jenis" => "jenis",
            "jml_wo" => "jml_wo",
            "qty_debet" => "qty_debet",
            "qty_kredit" => "qty_kredit",
            "qty_saldo" => "qty_saldo",
            "sisa_alokasi" => "sisa_alokasi",
        ),
        "shoppingCartFields2" => array(
            1 => array(
                "nama" => "item name",
                "produk_kode" => "code",
//                "no_part" => "part number",
                "stok" => "stok",
                "jml_wo" => "alokasi",// jatah di qty dari work order
                "qty_kredit" => "qty<br>sudah diambil",// jatah di qty dari work order
                "jml" => "qty<br>diambil",// qty yang diambil saat ini
                "sisa_alokasi" => "sisa alokasi",// sisa jatah yang bisa diambil
                "satuan" => "uom",
            ),
            2 => array(
//                "nama" => "item name",
//                "produk_kode" => "code",
//                "no_part" => "part number",
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "jml" => "qty",
//                "satuan" => "uom",
            ),
        ),
        "selectorDefaultMinValue2" => "1",
        "selectorSrcModel2" => "MdlHargaProduk",
        "shoppingCartNumFields2" => array(
            1 => array(
//                "harga" => "price",
//                "order_qty" => "qty order",
//                "dikirim_qty" => "qty kirim",
//                "jml" => "qty",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
                // "ppn"   => "VAT",
            ),
            2 => array(
//                "stok_center" => "stok dc",
//                "stok" => "stok available",
//                "harga" => "price",
//                "jml" => "qty",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                // "ppn"   => "VAT",
            ),

        ),
        "shoppingCartEditableFields2" => array(
            1 => array(
                "jml",
                "produk_ord_jml",
//                "disc_percent",
//                "disc",
                "harga",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),
            3 => array(
                //                "harga",
                "jml",
                "produk_ord_jml",
            ),

        ),
        "shoppingCartAmountValue2" => array(
            1 => "jml*(harga-disc)",//nett2
            2 => "jml*(harga-disc)",
            3 => "jml",
            4 => "jml",
            5 => "jml*",
            //            5 => "jml*(harga-disc)",
        ),
        "pairTasklist" => true,
        //----  untuk items2 -----------------

        //items sub 3
        "shopingCartDetailFields2" => array(
            1 => array(
                "produk_nama" => "SRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            2 => array(
                "produk_nama" => "SRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            3 => array(
                "produk_nama" => "SRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
            4 => array(
                "produk_nama" => "SRN Service",
                "produk_ord_harga" => "harga",
                // "satuan" => "uom",
            ),
        ),
        "shopingCartDetailFields2_sub" => array(
            2 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            3 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
            4 => array(
                "nama" => "Description",
                "harga" => "Price",
            ),
        ),

        //---------

        "swappedKeys" => array("pihakID", "pihakName"),
        "editHandlerMethod" => "select",
//        "pihakModel"         => "MdlCustomerProject",
        "pihakModel" => "MdlCustomer_and_pre",
        "pihakCaller" => "_selectorPihak/selectPihak",
        "pihakLabel" => "customer",
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
            "nett1" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",

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
            "harga" => "amount",
            "disc" => "discount",
            "ppn" => "ppn",
            "nett2" => "total amount",
            //            "trash_4" => "trash 4",
            //            "id" => "ID",
        ),
        "historyFields" => array(
            1 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                // "nett1" => "sub amount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            2 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "review_details" => "review",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            3 => array(
//                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer_soa" => array(
                    "step" => 2,
                    "key" => "nomer",
                    "label" => "SOA number",
                ),
                "nomer" => "PL number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            4 => array(
                "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                "nomer" => "INV number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
                "print_label" => "tool",
            ),
            5 => array(
                // "jenis_label" => "activity",
                "dtime" => "date",
                "cabang_nama" => "branch",
                "customers_nama" => "customer",
                "nomer_top" => "SO number",
                //                "nomer" => "receipt number",
                "oleh_nama" => "person",
                //            "transaksi_nilai" => "amount",
                "jual" => "amount",
                // "harga" => "amount",
                "disc" => "discount",
                "ppn" => "ppn",
                "nett2" => "total amount",
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
//                "produk_kode" => "part number",
//                "stok" => "stok",
//                "jml_wo" => "qty work order",
//                "jml" => "qty",
                "pihakProjekName" => "project",
                "nama" => "perintah kerja",
                "progress_nama" => "status",
//                "produk_kode" => "part number",
//                "stok" => "stok",
//                "jml_wo" => "qty work order",
//                "jml" => "qty",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
                "jml_wo" => "qty work order",
                "jml" => "qty",
            ),

            3 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "max_jml" => "SO",
                "jml" => "qty",

            ),
        ),
        "shoppingCartFieldSrc" => array(
            "nama" => "nama",
            "produk_kode" => "kode",
            "label" => "label",
            "satuan" => "satuan",
            "ppn" => "harga*(10/100)",
            "jml_wo" => "jml_wo",
            "qty_kredit" => "qty_kredit",
            "qty_saldo" => "qty_saldo",
            "pihakProjekName" => "pihakProjekName",
            "progress_id" => "progress_id",
            "progress_nama" => "progress_nama",
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
            //-------------------
        ),

        "shoppingCartNumFields" => array(
            1 => array(
//                "harga" => "price",
//                                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
            ),
            2 => array(
//                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
            ),
            3 => array(
//                "harga" => "price",
                //                "disc" => "disc",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
//                "ppn" => "VAT",
            ),

        ),
        "shoppingCartEditableFields" => array(
//            1 => array(
//                "harga",
//                "produk_ord_jml",
//                "disc_percent",
//                "disc",
//            ),
//            2 => array(
//                //                "jml",
//                //                "produk_ord_jml",
//                "harga",
//                //                "disc_percent",
//                //                "disc",
//            ),
            1 => array(
                //                "harga",
                "jml",
                //                "produk_ord_jml",
            ),

        ),
        "shoppingCartUnionSelectors" => array(
//            1 => array(
//                "base"    => "disc_percent",
//                "members" => array(
//                    "disc_percent",
//                    "disc",
//                ),
//            ),
        ),
        "shoppingCartKeyUpEvents" => array(
            1 => array(
                "disc_percent" => "document.getElementById('{disc}').value=((parseFloat(removeCommas(this.value))*parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))/100)",
                "disc" => "document.getElementById('{disc_percent}').value=((parseFloat(removeCommas(this.value))/parseFloat(removeCommas(document.getElementById('{harga}').innerHTML))*100)",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
//            "pihakID" => "customer ID",
//            "pihakName" => "customer name",
            "projectID" => "nama project",//validasi project ada disession main
        ),
        "shoppingCartRowOptionalValidators" => array(),
        "shoppingCartAmountValue" => array(
//            1 => "jml*(harga-disc)",//nett2
//            2 => "jml*(harga-disc)",
//            //            1 => "jml*(harga-disc)",//nett2
//            //            2 => "jml*(harga-disc)",
//            3 => "jml",
//            4 => "jml",
//            5 => "jml*(harga-disc)",
            //            5 => "jml*(harga-disc)",
        ),
        // "shoppingCartSubamount2" => array(1 => true,),

        "shopingCartEdit_mode" => array(
            "requested" => "valid_qty",

        ),

        "shoppingCartHideSubamount" => array(
            1 => true,
            2 => true,
            3 => true,
            4 => true,
            5 => true,
        ),
        "shoppingCartSumFields" => array(
//            1 => array(
//                "harga" => "amount",
//                "ppn_out_bulat" => "PPN/vat",
//                "new_net3" => "total amount",
//
//            ),
//            2 => array(
//                "harga" => "amount",
//                //                "disc" => "disc",
//                //                "ongkir_ui" => "shipping service",
//                //                "grand_total_ui" => "Net amount",
//                "grand_ppn" => "PPN/vat",
//                "new_net3" => "total amount",
//
//            ),
//            3 => array(
//                "harga" => "amount",
//                "grand_ppn" => "PPN/vat",
//                "new_net3" => "total amount",
//            ),
        ),
        "receiptMesurementRows" => array(),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartNoteEditabled" => array(
            2 => true,
        ),
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "receiptElements" => array(
            "projectDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "PROJECT DETAILS",
                "mdlName" => "MdlProdukProject",
                "mdlFilter" => array(
                    "id=projectID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "transaksi_no" => "nomer so",
                    "customer_id" => "id konsumen",
                    "customer_nama" => "nama konsumen",
                ),
                "editPoints" => array(1),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "CUSTOMER DETAILS",
                "mdlName" => "MdlCustomer_and_pre",
                "mdlFilter" => array(
                    "id=projectDetails__customer_id"
                ),
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
//            "workOrderDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "WORK ORDER DETAILS",
//                "mdlName" => "MdlProjectWorkOrder",
//                "mdlFilter" => array(
//                    "id=pihakProjekWorkOrderID"
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "nama",
//                    "employee_nama" => "pengawas",
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
//                "editPoints" => array(1, 4, 5),
//            ),
//            "deliveryDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "DELIVERY DETAILS",
//                "mdlName" => "MdlCustomerAddress",
//                "mdlFilter" => array("extern_id=pihakID"),
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
//            "paymentMethod" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "payment method",
//                "mdlName" => "MdlPaymentMethod",
//                "key" => "id",
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
//                //                "mdlFilter"   => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
            "workOrderDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "WORK ORDER",
                "mdlName" => "MdlProjectWorkOrder",
                "mdlFilter" => array(
                    "id=pihakProjekWorkOrderID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "employee_nama" => "pengawas",
                ),
                "editPoints" => array(1),
            ),
            "workOrderSubDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "SUB WORK ORDER",
                "mdlName" => "MdlProjectWorkOrderSub",
                "mdlFilter" => array(
                    "id=pihakProjekWorkOrderSubID"
                ),
                "key" => "id",
                "labelSrc" => "nama",
                "usedFields" => array(
                    "nama" => "nama",
                    "employee_nama" => "pengawas",
                ),
                "editPoints" => array(1),
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions" => array(),
        "updateDueDate" => array(
            4 => array(
                "source" => array("top" => "key"),
                "target" => array("dueDate" => "value"),
            ),
        ),
        "updateDownpayment" => array(
            3 => true,
            4 => true,
            5 => true,
        ),
        "validateDueDate" => array(
            4 => true,
        ),
        "validateMeasurement" => array(),
        "validateReceiveElement" => array(
            1 => array(
                "billingDetails" => array(
                    "npwp" => "NPWP Customer harap di isi dengan benar",
                    "no_ktp" => "KTP Customer harap di isi dengan benar",
                )
            ),
        ),
        "pairRegistries" => array(
            "tableIn_master_values", "main", "items", "items2_sum"
        ),
        "pairMakers" => array(
            1 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "helperName" => "he_cek_stock_produk_locker",
                    "functionName" => "cekStockProdukLocker",
                    "params" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "state" => ".active",
                        "jenis" => ".produk",
                    ),
                    "gate" => "items2_sum",
                ),
                "dataProduk" => array(
                    "helperName" => "he_pair_data_produk",
                    "functionName" => "cekPairDataProduk",
                    "gate" => "items2_sum",
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
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
            2 => array(
                "stokProduk" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok",
                    ),
                ),
                "stokProduk_center" => array(
                    "items2_sum" => array(
                        "targetKey" => "id",
                        "targetColumn" => "stok_center",
                    ),
                ),
            ),

        ),
        "validationRules" => array(),
        "connectedDiscount" => array(
            "enabled" => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource" => "MdlAddDiscount",
        ),
        "additionalRows" => array(),
        "resumeFieldNames" => array(
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "allowedMainEdit" => array("1", "2"),
        "addMainStep" => array(
            "1784" => array(
                "jenis_master" => "584",
                "jenis" => "584",
                "target" => "1784",
                "status_4" => "1",
                "trash_4" => "0",
            ),
        ),
//        "connectTo" => "110",
//        "connectoValidate" => array(
//            2 => "nilai_credit",
//        ),
//        "replacerConnectTo" => array(
//            "cabang2ID" => "-1",
//            "cabang2Name" => "PUSAT",
//            "place2ID" => "-1",
//            "place2Name" => "PUSAT",
//            "gudang2ID" => "-1",
//            "gudang2Name" => "default center warehouse",
//            "efaktur_source" => "nomer",//untuk ambil jika lintas cabang
//        ),
        "previewCtr" => "Create",
        "editSubItem" => array(
            1 => true,
            2 => true,
        ),
        "itemSwaper" => array(
            4 => array(
                "id" => "id",
                "produk_id" => "id",
                "nama" => "nomer",
                "jml" => ".1",
                "dtime" => "dtime",
            ),
        ),
        "itemSwaperToMain" => array(
            4 => array(
                "projectID" => "id",
                "projectCode" => "produk_kode",
                "projectName" => "nama",
                "projectHarga" => "harga",
                "projectPpn" => "ppn",
                "projectNett" => "harga",
                "projectGrandtotal" => "nett2",
            ),
        ),
        "addMainSource" => array(
            4 => array(
                "fields" => array(
                    "projectName" => "Project",
                    "projectHarga" => "Nilai Project",
                    "tarifGaransi" => "Garansi (%)",
                    "garansi_nilai" => "Garansi (IDR)",
                    "dateGaransi" => "Masa Garansi ",
                ),
                "editableFields" => array(
                    "dateGaransi" => "date",
                ),
            ),
        ),
        "additionalPackinglist" => array(
            4 => array(
                "enabled" => true,
                "header" => array(
                    "produk_nama" => "item name",
                    "produk_kode" => "code",
//                    "stok_center" => "stok dc",
//                    "stok" => "stok available",
                    "qty" => "qty",
                    "satuan" => "uom",
                ),
            ),
        ),
        "validateClosing" => array(
            4 => array(
                "dateGaransi" => "Tanggal garansi belum ditentukan. Silahkan ditentukan terdahulu.",
            ),
        ),
        "validateClosingKey" => array(
            4 => array(
                "garansi_nilai",
            ),
        ),
        "validateClosingExtractedSubItems" => array(
            4 => array(
                "checklistnote_cek" => "Silahkan checklist Lanjutkan Close Project.",
            ),
        ),

//        "connectTo" => "5882",// pelaksanaan project
        "workOrderValidate" => array(
            "enabled" => false,
            "validateRule" => array(
                1 => array(
                    "model" => "MdlProdukProject",
                    "label" => "uang muka",
                    "filter" => array(
                        "id=produk_id",
                        "uang_muka_approved>.0",
                    ),
                    "warningLabel" => "work order ini belum aktif, saat ini sedang ditindaklanjuti oleh pihak finance.",
                ),
//                2 => array(
//                    "model" => "MdlProjectKomposisiWorkorder",
//                    "label" => "komposisi bahan baku work order",
//                    "filter" => array(
//                        "produk_id=produk_id",
//                        "fase_id=id",
//                        "jenis=.produk",
//                    ),
//                    "warningLabel" => "work order ini belum aktif, daftar kebutuhan bahan baku belum terdaftar. Segera hubungi admin.",
//                ),
            ),
        ),
    ),
    "5886" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "biaya lain-lain project",
        "place" => "branch",
        "steps" => array(
            1 => array(
                "label" => "request biaya lain-lain project",
                "actionLabel" => "request",
                "source" => "",
                "target" => "5886r",
                "userGroup" => "o_finance",
                "stateLabel" => "create",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlProdukProject",
        "selectorSrcModel" => "MdlProdukProject",
        "selectedPrice" => array(
//            "model" => "MdlHargaSupplies",
//            "label" => array("hpp"),
//            "key_label" => array(
//                "hpp" => "harga",
//            ),
//            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "status=.1",
            "trash=.0",
            "project_start_id<>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
//        "selectorProcessor" => "_processSelectProduct/select",
        "selectorProcessor" => "_processSelectBiaya/selectProject",
        "editHandlerMethod" => "select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
//            "harga" => "amount",
            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
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

        "shoppingCartFields" => array(
            1 => array(
                "customer_nama" => "konsumen",
                "nama" => "project",
                "harga_nppn" => "nilai project",
            ),
        ),
        "shoppingCartFields2" => array(
//            1 => array(
//                "nama" => "item name",
//                "jml" => "qty",
//                "harga" => "price",
//                "ppn" => "PPN",
//            ),
        ),
        "shoppingCartFieldSrc" => array(
            "customer_id" => "customer_id",
            "customer_nama" => "customer_nama",
            "nama" => "nama",
            "tanggal_kontrak" => "tanggal_kontrak",
            "harga_nppn" => "harga_nppn",
//            "sisa" => "sisa",
//            "extern_nilai2" => "extern_nilai2",
//            "extern_nilai3" => "extern_nilai3",
//            "extern_nilai4" => "extern_nilai4",
//            "extern_nilai5" => "extern_nilai5",
//            "ppn" => "ppn",
//            "extern2_id" => "extern2_id",
//            "extern2_nama" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "extern_nilai5" => "DPP pph21",
//                "extern_nilai2" => "DPP pph23",
//                "extern_nilai3" => "DPP ppn",
//                "ppn" => "ppn",
                "harga" => "harga",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array("harga",),

            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "harga",
            2 => "harga",
        ),
        "shoppingCartAvoidRemove" => true,

        "shoppingCart" => array(//            "initPrices" => "beli",
        ),

        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
        ),

        "shoppingCartReferenceFields" => array(),
        "shoppingCartReferenceExternFields" => array(),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(),
            ),
        ),

        "connectTo" => "5887",
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
            "cabang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "cabang dc",
                "mdlName" => "MdlCabang",
                "mdlFilter" => array("id=.-1"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType" => "hidden",
                "label" => "gudang dc",
                "mdlName" => "MdlGudangDefault_center",
                "mdlFilter" => array("cabang_id=.-1"),
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1, 2, 3),
            ),
        ),
        "relativeElements" => array(
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
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5886re",
                "label" => "EDIT request biaya usaha",
            ),
        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5866rrj",
                "label" => "REJECT request biaya usaha",
            ),
        ),
        "shortStepHistoryFields" => array(
            "dtime" => "tanggal",
            "cabang2_nama" => "dari cabang",
            "cabang_nama" => "ke cabang",
            "5886r" => "nomer",
//            "583" => "approval number",
//            "585" => "receipt number",
            "item_fields" => "isi",
            "harga" => "nilai",
            "oleh_nama" => "pic",
            "next_pic" => "next step otorisator",
        ),
        "shortItemsFields" => array(
//            "produk_kode" => "sku",
//            "barcode" => "barcode",
            "nama" => array(
                "label" => "nama",
                "addKey" => "keterangan",
            ),
            "harga" => "nilai",
//            "jml" => "qty",
            // "produk_ord_diterima"=>"send",
            // "valid_qty"=>"outstanding",
        ),
    ),
    "5887" => array(
        "icon" => "fa fa-cart-arrow-down",
        "label" => "biaya lain-lain project",
        "place" => "center",
        "steps" => array(
            1 => array(
                "label" => "request biaya lain-lain project",
                "actionLabel" => "request",
                "source" => "",
                "target" => "5887r",
                "userGroup" => "sys",
                "stateLabel" => "create",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "otorisasi biaya lain-lain project",
                "actionLabel" => "request",
                "source" => "5887r",
                "target" => "5887",
                "userGroup" => "c_finance",
                "stateLabel" => "approve",
                "stateColor" => "#dd3300",
                "stateCaption" => "approved by",
            ),
        ),
        "template" => "template/transaksi_nopihak.html",
        "selectorModel" => "MdlProdukProject",
        "selectorSrcModel" => "MdlProdukProject",
        "selectedPrice" => array(
//            "model" => "MdlHargaSupplies",
//            "label" => array("hpp"),
//            "key_label" => array(
//                "hpp" => "harga",
//            ),
//            "mainSrc" => "hpp",
        ),
        "lockerCheck" => array(),
        "selectorFilters" => array(
            "status=.1",
            "trash=.0",
            "project_start_id<>.0",
        ),
        "selectorCaller" => "_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel" => "item",
        "selectorParamFields" => array(
            "id" => "id",
            "nama" => "nama",
        ),
        "selectorViewedFields" => array(
            "nama",
        ),
//        "selectorProcessor" => "_processSelectProduct/select",
        "selectorProcessor" => "_processSelectBiaya/selectProject",
        "editHandlerMethod" => "select",
        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "cash_account__label" => "bank account",
//            "harga" => "amount",
            "nett" => "amount",
            "next_pic" => "Next step otorisator",
        ),
        "shortStatusFields" => array(
            "jenis_label" => "activity",
            "dtime" => "date",
            "status_next" => "status",
            "customers_nama" => "customer",
            "nomer" => "receipt number",
            "oleh_nama" => "person",
            "harga" => "amount",
            "nett" => "total amount",
            "keterangan" => "keterangan",
        ),

        "historyFields" => array(
            1 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
                "keterangan" => "keterangan",
                "print_label" => "tool",
            ),
            2 => array(
//                "jenis_label" => "activity",
                "no" => "no",
                "dtime" => "date",
                "customers_nama" => "customer",
                "nomer_top" => "REQ Number",
                "nomer" => "Number",
                "oleh_nama" => "person",
                "cash_account__label" => "bank account",
//                "harga" => "amount",
                "nett" => "amount",
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

        "shoppingCartFields" => array(
            1 => array(
                "customer_nama" => "konsumen",
                "nama" => "project",
                "harga_nppn" => "nilai project",
            ),
        ),
        "shoppingCartFields2" => array(
//            1 => array(
//                "nama" => "item name",
//                "jml" => "qty",
//                "harga" => "price",
//                "ppn" => "PPN",
//            ),
        ),
        "shoppingCartFieldSrc" => array(
            "customer_id" => "customer_id",
            "customer_nama" => "customer_nama",
            "nama" => "nama",
            "tanggal_kontrak" => "tanggal_kontrak",
            "harga_nppn" => "harga_nppn",
//            "sisa" => "sisa",
//            "extern_nilai2" => "extern_nilai2",
//            "extern_nilai3" => "extern_nilai3",
//            "extern_nilai4" => "extern_nilai4",
//            "extern_nilai5" => "extern_nilai5",
//            "ppn" => "ppn",
//            "extern2_id" => "extern2_id",
//            "extern2_nama" => "extern2_nama",
        ),
        "shoppingCartNumFields" => array(
            1 => array(
//                "extern_nilai5" => "DPP pph21",
//                "extern_nilai2" => "DPP pph23",
//                "extern_nilai3" => "DPP ppn",
//                "ppn" => "ppn",
                "harga" => "harga",
            ),
        ),
        "shoppingCartSumFields" => array(
            1 => array(
                "harga" => "total",
            ),
        ),
        "shoppingCartEditableFields" => array(
            1 => array("harga",),

            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue" => array(
            1 => "harga",
            2 => "harga",
        ),
        "shoppingCartAvoidRemove" => true,

        "shoppingCart" => array(//            "initPrices" => "beli",
        ),

        "shoppingCartNoteEnabled" => true,
        "shoppingCartNoteType" => "textarea",
        "shoppingCartImageEnabled" => true,
        "shoppingCartImageType" => "images",
        "shoppingCartNoteEditabled" => array(
            1 => true,
            2 => true,
        ),

        "shoppingCartFieldValidators" => array(
//            "jml" => "quantity",
//            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
//            "pihakID" => "vendor ID",
//            "pihakName" => "vendor name",
        ),

        "shoppingCartReferenceFields" => array(),
        "shoppingCartReferenceExternFields" => array(),
        "additionalRows" => array(
            "dummyElement" => array(
                "yes" => array(),
            ),
        ),
        "pairRegistries" => array(
            "main", "items"
        ),
        "receiptElements" => array(
//            "cash_account" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "cash account",
//                "showNull" => true,
//                "nullSrc" => "balance",
//                "nullValue" => "<span class='text-red text-bold'>{saldo kosong}</span>",
//                "pairedModel" => array(
//                    "mdlName" => "ComLockerValue",
//                    "mdlMethod" => "fetchBalances",
//                    "mdlFilter" => array(
//                        "cabang_id" => "placeID",
//                        "state" => ".active",
//                    ),
//                    "key" => "produk_id",
//                    //                    "rekening" => "kas",// kolom jenis di locker
//                    "rekening" => array(
//                        "kas", "plafon hutang bank",
//                    ),
//                    "fieldID" => "nilai",
//                    "fieldLabel" => "saldo",
//                ),
//                "mdlName" => "MdlBankAccount_cash_and_in_and_koran",
//                "mdlFilter" => array(//                    "bank.cabang_id=placeID",
//                ),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "account",
//                    "saldo" => "balance",
//                    "folders" => "acountMasterID",
//                    "folders_nama" => "accountMaster",
//                ),
//                "editPoints" => array(1,),
//                "noValidate" => true,
//                "pairMethod" => array(
//                    "recom" => "ReComCashMethode",
//                    "calculate" => array(
//                        "source" => "cash_account",
//                        "prefix" => "cashMethode",
//                        "target" => "",
//                    ),
//                ),
//                "labelValidate" => "Silahkan memilih sumber pembayaran sebelum melanjutkan transaksi.",
//            ),
//            "dummyElement" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "auto-validation",
//                "mdlName" => "MdlDummyElement",
//                //                "mdlFilter"   => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "name",
//                "usedFields" => array(
//                    "name" => "name",
//
//                ),
//                "editPoints" => array(1, 2, 3),
//            ),
        ),
        "relativeElements" => array(
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
            "selectFields" => "customers_nama",
            "title" => "customer",
        ),
        "settlementHistoryFields" => array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "customers_nama" => "customer",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        ),
        "validatePaymentSource" => array(//            "3" => "MdlLockerValue",
        ),
        "allowedMainEdit" => array(1, 2,),
        "previewCtr" => "Create",
        //----
        "connectToEdit" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "5886re",
                "label" => "EDIT request biaya lain project",
            ),

        ),
        "connectToReject" => array(
            1 => array(
                "enabled" => true,
                "connectTo" => "4464rrj",
                "label" => "REJECT request uang muka (dp tanpa ppn)",
            ),
            2 => array(
                "enabled" => true,
                "connectTo" => "5866rrj",
                "label" => "REJECT otorisasi biaya lain projecty",
            ),
        ),
    ),
);