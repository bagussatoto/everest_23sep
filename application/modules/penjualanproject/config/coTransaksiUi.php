<?php
//region urusan tanggal-menanggal
date_default_timezone_set('asia/jakarta');
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
$date->format('Y-m-d') . "\n";
//endregion


$config["coTransaksiUi"] = array(

    "588" => array(
        "icon" => "fa fa-opencart",
        "label" => "project",
        "place" => "branch",//=> "center",
        "steps" => array(
            1 => array(
                "label" => "SALES PRE ORDER PROJECT",
                "actionLabel" => "make order",
                "source" => "",
                "target" => "588spo",
                "userGroup" => "o_seller",
                "stateLabel" => "pending approval",
                "stateColor" => "#dd3300",
                "stateCaption" => "Prepare by",
            ),
            2 => array(
                "label" => "SALES ORDER PROJECT",
                "actionLabel" => "approve order",
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
        "selectorProcessor" => "_processSelectBiaya/select",
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
            1 => array(
                "nama" => "item name",
                "produk_kode" => "code",
                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item name",
                "produk_kode" => "code",
                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "nama" => "item name",
                "produk_kode" => "code",
                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "selectorDefaultMinValue2" => "1",
        "selectorSrcModel2" => "MdlHargaProduk",
        "shoppingCartNumFields2" => array(
            1 => array(
                "harga" => "price",
                "order_qty" => "qty order",
                "dikirim_qty" => "qty kirim",
                "jml" => "qty",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
                // "ppn"   => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "jml" => "qty",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                // "ppn"   => "VAT",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",

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
                "nama" => "produk name",
                "produk_kode" => "part number",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
            ),

            3 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "max_jml" => "SO",

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
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
            "projectID" => "nama project",//validasi project ada disession main
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
            //            "discountMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "discount method",
            //                "mdlName" => "MdlDiskonMethod",
            //                "key" => "id",
            //                "defaultValue" => "item",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "method",
            //                ),
            //                "editPoints" => array(1,),
            //                "targetMethod" => array(
            //                    "item" => "ReComDiscItem",
            //                    "customer" => "ReComDiscCustomer",
            //                ),
            //            ),
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
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "DELIVERY DETAILS",
                "mdlName" => "MdlCustomerAddress",
                "mdlFilter" => array("extern_id=pihakID"),
                "key" => "id",
                "labelSrc" => "alias",
                "usedFields" => array(
                    "alias" => "Attn",
                    "alamat" => "Address",
                    "kecamatan" => "Kec",
                    "kabupaten" => "Kab",
                    "propinsi" => "propinsi",
                    "tlp" => "Phone",
                    "tlp_2" => "Handphone",
                    //                    "npwp" => "NPWP",
                    //                    "propinsi" =>"",
                ),
                "editPoints" => array(1, 2, 3, 4),
            ),
            //            "dueDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "due date",
            ////                "inputType" => "date",
            //                "inputType" => "hidden",
            //                "defaultValue" => $date->format('Y-m-d'),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
                "label" => "payment method",
                "mdlName" => "MdlPaymentMethod",
                "key" => "id",
                "labelSrc" => "name",
                "usedFields" => array(
                    "name" => "",
                ),
                "editPoints" => array(1,),
            ),
            "branch" => array(
                "elementType" => "dataModel",
                "inputType" => "radio",
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

        "connectTo" => "5882",// pelaksanaan project
    ),



    "5882" => array(
        "icon" => "fa fa-opencart",
        "label" => "project",
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
                "label" => "SALES ORDER PROJECT",
                "actionLabel" => "approve order",
                "source" => "",
                "target" => "5882so",
                "userGroup" => "sys",
                "stateLabel" => "approved",
                "stateColor" => "#ff7700",
                "stateCaption" => "Acknowledge by",
                "allowEdit" => true,
            ),

            2 => array(
                "label" => "PACKING LIST PROJECT",
                "actionLabel" => "process shipment",
                "source" => "5882so",
                "target" => "5882spd", // shipped
                "userGroup" => "o_gudang",
                "stateLabel" => "shipped",
                "stateColor" => "#009900",
                "stateCaption" => "shipped by",
                "allowIncrement" => true,
                "allowEdit" => true,
            ),
            3 => array(
                "label" => "CLOSING PROJECT",
                "actionLabel" => "close project",
                "source" => "5882spd",
                "target" => "5882", // invoice
                "userGroup" => "o_finance",
                "stateLabel" => "close",
                "stateColor" => "#009900",
                "stateCaption" => "completed by",
                "allowJoin" => false,
                //                "allowEdit" => true,
                "followupMulti" => true,
            ),

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
        "selectorProcessor" => "_processSelectBiaya/select",
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
            1 => array(
                "nama" => "item name",
                "produk_kode" => "code",
                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "satuan" => "uom",
            ),
            2 => array(
                "nama" => "item name",
                "produk_kode" => "code",
                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),
            3 => array(
                "nama" => "item name",
                "produk_kode" => "code",
                "no_part" => "part number",
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",
                "satuan" => "uom",
            ),
        ),
        "selectorDefaultMinValue2" => "1",
        "selectorSrcModel2" => "MdlHargaProduk",
        "shoppingCartNumFields2" => array(
            1 => array(
                "harga" => "price",
                "order_qty" => "qty order",
                "dikirim_qty" => "qty kirim",
                "jml" => "qty",
//                "disc_percent" => "disc (%)",
//                "disc" => "disc (IDR)",
                // "ppn"   => "VAT",
            ),
            2 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "harga" => "price",
                "jml" => "qty",
                //                "disc_percent" => "disc (%)",
                //                "disc" => "disc (IDR)",
                // "ppn"   => "VAT",
            ),
            3 => array(
                "stok_center" => "stok dc",
                "stok" => "stok available",
                "jml" => "qty",

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
                "nama" => "produk name",
                "produk_kode" => "part number",
            ),
            2 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "stok" => "stok",
            ),

            3 => array(
                "nama" => "produk name",
                "produk_kode" => "part number",
                "max_jml" => "SO",

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
            "harga" => "price",
        ),
        "shoppingCartRowValidators" => array(
            "pihakID" => "customer ID",
            "pihakName" => "customer name",
            "projectID" => "nama project",//validasi project ada disession main
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
//            "customerDetails" => array(
//                "elementType" => "dataModel",
//                "inputType" => "radio",
//                "label" => "CUSTOMER DETAILS",
////                "mdlName"     => "MdlCustomerProject",
//                "mdlName" => "MdlCustomer_and_pre",
//                "mdlFilter" => array("id=pihakID"),
//                "key" => "id",
//                "labelSrc" => "nama",
//                "usedFields" => array(
//                    "nama" => "Name",
//                    "alamat_1" => "Addr",
//                    "kelurahan" => "Kel",
//                    "kecamatan" => "Kec",
//                    "kabupaten" => "Kab",
//                    "propinsi" => "Prop",
//                    "tlp" => "Phone",
//                    "tlp_1" => "Phone",
//                    "tlp_2" => "Handphone",
//                    "npwp" => "NPWP",
//                    "no_ktp" => "nik",
//                    "nik" => "NIK",
//                ),
//                "editPoints" => array(1, 2, 3, 4),
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
        ),
        "relativeElements" => array(
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
    ),


);