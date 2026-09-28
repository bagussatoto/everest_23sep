<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 7:14 PM
 */

//region urusan tanggal-menanggal
$date = new DateTime(date("Y-m-d")); // Y-m-d
$date->add(new DateInterval('P30D'));
//$date->format('Y-m-d') . "\n";
//endregion


$config["heTransaksi_ui"] = array(
    "466" => array(
        "icon"                       => "fa fa-cart-arrow-down",
        "label"                      => "FG purchasing",
        "place"                      => "center",
        "steps"                      => array(
            1 => array(
                "label"        => "purchasing order",
                "actionLabel"  => "make purchasing order",
                "source"       => "",
                "target"       => "466r",
                "userGroup"    => "c_purchasing",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"          => "purch. authorization",
                "actionLabel"    => "approve purchasing",
                "source"         => "466r",
                "target"         => "466",
                "userGroup"      => "c_purchasing_adm",
                "stateLabel"     => "purchased",
                "stateColor"     => "#ff7700",
                "stateCaption"   => "approved by",
                "allowEdit"      => true,
                "allowIncrement" => true,
            ),
            3 => array(
                "label"        => "goods received note",
                "actionLabel"  => "receive & make GRN",
                "source"       => "466",
                "target"       => "467",
                "userGroup"    => "c_gudang",
                "stateLabel"   => "GRN made",
                "stateColor"   => "#009900",
                "stateCaption" => "received by",
                "allowEdit"    => true,
                "allowJoin"    => true,
            ),
        ),
        "template"                   => "application/template/transaksi.html",
        "selectorModel"              => "MdlProdukPerSupplier",
        "selectorSrcModel"           => "MdlProduk",
        "selectedPrice"              => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"                => array(),
        "selectorFilters"            => array(
            "suppliers_id=pihakID",
        ),
        "selectorCaller"             => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"              => "item",
        "selectorParamFields"        => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            //            "jumlah"=>"jumlah",
        ),
        "selectorViewedFields"       => array(
            "nama", "satuan",
            //            "produk_nama", "produk_id"// "satuan",
        ),
        "selectorProcessor"          => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"          => "select",
        "pihakModel"                 => "MdlSupplier",
        "pihakCaller"                => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                 => "vendor",
        "pihakMainValueSrc"          => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"             => "Selectors/_processPihak/select",
        "shortHistoryFields"         => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "suppliers_nama"  => "vendor",
            "nomer_top"       => "PO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "compactHistoryFields"       => array(

            //            "dtime" => "date",
            "suppliers_nama"  => "vendor",
            //            "nomer_top" => "PO number",
            //            "nomer" => "receipt number",
            //            "oleh_nama" => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields"             => array("id", "nama", "satuan"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "harga" => "price",
                //                "ppn" => "VAT",
            ),
            2 => array(
                "harga" => "price",
                //                "ppn" => "VAT",
            ),
            3 => array(
                //                "harga" => "Price",
                //                "ppn" => "VAT",
            ),
        ),
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
        "shoppingCartAmountValue"    => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
            3 => "jml*(harga+ppn)",
        ),

        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),

        "receiptElements"  => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "capacity"      => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "capacity",
                "mdlName"     => "MdlCapacity",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3),
            ),

            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "delivery details",
                "mdlName"     => "MdlSupplierAddress",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "alias",
                "usedFields"  => array(
                    "alias"     => "ATTN",
                    "alamat"    => "address",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi"  => "propinsi",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "tos"             => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "term of shipment",
                "mdlName"     => "MdlTos",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "description" => "",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),

            "shippingDate"  => array(
                "elementType"  => "dataField",
                "label"        => "shipping date",
                "inputType"    => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints"   => array(1, 2, 3, 4, 5),
            ),
            "dueDate"       => array(
                "elementType"  => "dataField",
                "label"        => "due date",
                "inputType"    => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints"   => array(1, 2, 3, 4, 5),
            ),
            "paymentMethod" => array(
                "elementType"  => "dataModel",
                "inputType"    => "radio",
                "label"        => "payment method",
                "mdlName"      => "MdlPaymentMethod",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key"          => "id",
                "defaultValue" => "credit",
                "labelSrc"     => "name",
                "usedFields"   => array(
                    "name" => "",


                ),
                "editPoints"   => array(1,),
            ),

        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash"   => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "cash account",
                        "mdlName"     => "MdlBankAccount",

                        "key"        => "id",
                        "labelSrc"   => "nama",
                        "usedFields" => array(
                            "nama" => "",


                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "cia"    => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "cash account",
                        "mdlName"     => "MdlBankAccount",

                        "key"        => "id",
                        "labelSrc"   => "nama",
                        "usedFields" => array(
                            "nama" => "",


                        ),
                        "editPoints" => array(1,),
                    ),
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "radio",
                        "label"       => "term of payment",
                        "mdlName"     => "MdlTop",
                        "mdlFilter"   => array(),
                        "key"         => "kode",
                        "labelSrc"    => "nama",
                        "description" => "",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    )
                ),
            ),
        ),
        "relativeOptions"  => array(
            "paymentMethod" => array(

                "cia" => array(
                    "nilai_cia" => array(
                        "label"        => "cash amount",
                        "defaultValue" => "nett",
                        "minValue"     => "nett",
                        "maxValue"     => "nett",
                    ),

                ),
            ),
        ),

    ),
    // config po supplies
    "461" => array(
        "icon"                        => "fa fa-cart-arrow-down",
        "label"                       => "supplies purchasing",
        "place"                       => "center",
        "steps"                       => array(
            1 => array(
                "label"        => "purchasing order",
                "actionLabel"  => "make purchasing order",
                "source"       => "",
                "target"       => "461ro",
                "userGroup"    => "c_purchasing", // admin
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "purch. authorization",
                "actionLabel"  => "approve purchasing",
                "source"       => "461ro",
                "target"       => "461r",
                "userGroup"    => "c_purchasing_adm",
                "stateLabel"   => "purchased",
                "stateColor"   => "#ff7700",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label"        => "goods received note",
                "actionLabel"  => "receive & make GRN",
                "source"       => "461r",
                "target"       => "461",
                "userGroup"    => "c_gudang",
                "stateLabel"   => "GRN made",
                "stateColor"   => "#009900",
                "stateCaption" => "received by",
                "allowEdit"    => true,
            ),
        ),
        "template"                    => "application/template/transaksi.html",
        "selectorModel"               => "MdlSupplies",
        "selectorSrcModel"            => "MdlSupplies",
        "selectedPrice"               => array(
            "model"     => "MdlHargaSupplies",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"                 => array(),
        "selectorFilters"             => array(),
        "selectorCaller"              => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"               => "item",
        "selectorParamFields"         => array(
            "id"     => "id",
            "nama"   => "nama",
            "satuan" => "satuan",
            //            "jumlah"=>"jumlah",
        ),
        "selectorViewedFields"        => array(
            "nama", "satuan",
        ),
        "selectorProcessor"           => "Selectors/_processSelectProduct/select",
        "itemSwapper"                 => "Selectors/_processSelectProduct/multiSelect",
        "editHandlerMethod"           => "select",
        "pihakModel"                  => "MdlSupplier",
        "pihakCaller"                 => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                  => "vendor",
        "pihakMainValueSrc"           => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"              => "Selectors/_processPihak/select",
        "shortHistoryFields"          => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields"        => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"              => array("id", "nama", "satuan"),
        "pihakFields"                 => array("id", "nama"),
        "shoppingCart"                => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
            2 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
            3 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
        ),
        "shoppingCartNoteEnabled"     => true,
        "shoppingCartEditableFields"  => array(
            1 => array(
                "harga",
                "jml",
            ),
            2 => array(
                "harga",
                "jml",
            ),
            3 => array(
                //                "harga",
                "jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartAmountValue"     => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
            3 => "jml*(harga+ppn)",
        ),

        "receiptElements"  => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "capacity"      => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "capacity",
                "mdlName"     => "MdlCapacity",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3),
            ),

            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "delivery details",
                "mdlName"     => "MdlSupplierAddress",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "alias",
                "usedFields"  => array(
                    "alias"     => "ATTN",
                    "alamat"    => "address",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi"  => "propinsi",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "tos"             => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "term of shipment",
                "mdlName"     => "MdlTos",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "description" => "",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            //            "top"             => array(
            //                "elementType" => "dataModel",
            //            "inputType"=>"combo",
            //                "label"       => "term of payment",
            //                "mdlName"     => "MdlTop",
            //                "mdlFilter"   => array(),
            //                "key"         => "id",
            //                "labelSrc"    => "nama",
            //                "description" => "",
            //                "usedFields"  => array(
            //                    "nama" => "",
            //                ),
            //                "editPoints"  => array(1, 2, 3, 4),
            //            ),
            "shippingDate"    => array(
                "elementType"  => "dataField",
                "label"        => "shipping date",
                "inputType"    => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints"   => array(1, 2, 3, 4, 5),
            ),
            "dueDate"         => array(
                "elementType"  => "dataField",
                "label"        => "due date",
                "inputType"    => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints"   => array(1, 2, 3, 4, 5),
            ),

            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "payment method",
                "mdlName"     => "MdlPaymentMethod",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "",


                ),
                "editPoints"  => array(1,),
            ),

        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash"   => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "cash account",
                        "mdlName"     => "MdlBankAccount",

                        "key"        => "id",
                        "labelSrc"   => "nama",
                        "usedFields" => array(
                            "nama" => "",


                        ),
                        "editPoints" => array(1,),
                    )
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "term of payment",
                        "mdlName"     => "MdlTop",
                        "mdlFilter"   => array(),
                        "key"         => "kode",
                        "labelSrc"    => "nama",
                        "description" => "",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    )
                ),
            ),
        ),
        "relativeOptions"  => array(
            "paymentMethod" => array(

                "cia" => array(
                    "nilai_cia" => array(
                        "label"        => "cash amount",
                        "defaultValue" => "nett",
                        "minValue"     => "nett",
                        "maxValue"     => "nett",
                    ),

                ),
            ),
        ),

        "requestCode" => array(
            "masterCode"       => "761",
            "stateCode"        => "761r",
            "stepNumber"       => "2",
            "allowMultiSelect" => true,
        ),
    ),
    // config pr (request)
    "761" => array(
        "icon"                        => "fa fa-quote-left",
        "label"                       => "supplies purchasing request",
        "place"                       => "center",
        "steps"                       => array(
            1 => array(
                "label"        => "purchasing request",
                "actionLabel"  => "make purchasing request",
                "source"       => "",
                "target"       => "761ro", // request order
                "userGroup"    => "c_holding",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "purch. authorization",
                "actionLabel"  => "approve purchasing request",
                "source"       => "761ro",
                "target"       => "761r",
                "userGroup"    => "c_holding",//===ini dilanjutkan di purchasing
                "stateLabel"   => "awaiting for process",
                "stateColor"   => "#ff7700",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label"        => "request processing",
                "actionLabel"  => "process",
                "source"       => "761r",
                "target"       => "761ros",
                "userGroup"    => "sys",
                "stateLabel"   => "fulfill",
                "stateColor"   => "#009900",
                "stateCaption" => "fullfilled by",
            ),
            4 => array(
                "label"        => "request fulfill",
                "actionLabel"  => "do fulfill",
                "source"       => "761ros",
                "target"       => "761",
                "userGroup"    => "adminssss",
                "stateLabel"   => "fulfill",
                "stateColor"   => "#009900",
                "stateCaption" => "fullfilled by",
            ),
        ),
        "template"                    => "application/template/transaksi_nopihak.html",
        "selectorModel"               => "MdlSupplies",
        "selectorSrcModel"            => "MdlSupplies",
        "selectedPrice"               => array(
            "model"     => "MdlHargaSupplies",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"                 => array(),
        "selectorFilters"             => array(),
        "selectorCaller"              => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"               => "item",
        "selectorParamFields"         => array(
            "id"     => "id",
            "nama"   => "nama",
            "satuan" => "satuan",
            //            "jumlah"=>"jumlah",
        ),
        "selectorViewedFields"        => array(
            "nama", "satuan",
        ),
        "selectorProcessor"           => "Selectors/_processSelectProduct/select",
        "itemSwapper"                 => "Selectors/_processSelectProduct/multiSelect",
        "editHandlerMethod"           => "select",
        "pihakModel"                  => "MdlSupplier",
        "pihakCaller"                 => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                  => "vendor",
        "pihakProcessor"              => "Selectors/_processPihak/select",
        "shortHistoryFields"          => array(
            "jenis_label" => "activity",
            "dtime"       => "date",
            //            "suppliers_nama" => "vendor",
            "nomer"       => "receipt number",
            "oleh_nama"   => "person",
        ),
        "compactHistoryFields"        => array(
            "jenis_label" => "activity",
            "dtime"       => "date",
            //            "suppliers_nama" => "vendor",
            "nomer"       => "receipt number",
            "oleh_nama"   => "person",
        ),
        "selectorFields"              => array("id", "nama", "satuan"),
        "pihakFields"                 => array("id", "nama"),
        "shoppingCart"                => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            4 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields"       => array(
            //            "harga" => "price",
            //            "ppn" => "VAT",
        ),
        "shoppingCartNoteEnabled"     => true,
        "shoppingCartEditableFields"  => array(
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
        "shoppingCartAmountValue"     => array(),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga"=>"harga beli",
        ),
    ),
    // config po jasa
    "463" => array(
        "icon"                        => "fa fa-cart-arrow-down",
        "label"                       => "service purchasing",
        "place"                       => "center",
        "steps"                       => array(
            1 => array(
                "label"        => "purchasing order",
                "actionLabel"  => "make purchasing order",
                "source"       => "",
                "target"       => "463ro",
                "userGroup"    => "c_purchasing",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "purch. authorization",
                "actionLabel"  => "approve purchasing",
                "source"       => "463ro",
                "target"       => "463o",
                "userGroup"    => "c_purchasing_adm",
                "stateLabel"   => "purchased",
                "stateColor"   => "#ff7700",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label"        => "service receipt note",
                "actionLabel"  => "make service receipt note",
                "source"       => "463o",
                "target"       => "463",
                "userGroup"    => "c_holding",
                "stateLabel"   => "service receipt note made",
                "stateColor"   => "#009900",
                "stateCaption" => "receipt by",
            ),
        ),
        "template"                    => "application/template/transaksi.html",
        "selectorModel"               => "MdlJasa",
        "selectorSrcModel"            => "MdlJasa",
        "selectedPrice"               => array(
            "model"     => "MdlHargaSupplies",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"                 => array(),
        "selectorFilters"             => array(),
        "selectorCaller"              => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"               => "item",
        "selectorParamFields"         => array(
            "id"     => "id",
            "nama"   => "nama",
            "satuan" => "satuan",
            //            "jumlah"=>"jumlah",
        ),
        "selectorViewedFields"        => array(
            "nama", "satuan",
        ),
        "selectorProcessor"           => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"           => "select",
        "pihakModel"                  => "MdlSupplier",
        "pihakCaller"                 => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                  => "vendor",
        "pihakProcessor"              => "Selectors/_processPihak/select",
        "shortHistoryFields"          => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields"        => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"              => array("id", "nama", "satuan"),
        "pihakFields"                 => array("id", "nama"),
        "shoppingCart"                => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
            2 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
            3 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
        ),
        "shoppingCartNoteEnabled"     => true,
        "shoppingCartEditableFields"  => array(
            1 => array(
                "harga",
                //            "ppn",
                "jml",
            ),
            2 => array(
                "harga",
                //            "ppn",
                "jml",
            ),
            3 => array(
                "harga",
                //            "ppn",
                "jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartAmountValue"     => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
            3 => "jml*(harga+ppn)",
        ),

        "receiptElements"  => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "payment method",
                "mdlName"     => "MdlPaymentMethod",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "",


                ),
                "editPoints"  => array(1,),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash"   => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "cash account",
                        "mdlName"     => "MdlBankAccount",

                        "key"        => "id",
                        "labelSrc"   => "nama",
                        "usedFields" => array(
                            "nama" => "",


                        ),
                        "editPoints" => array(1,),
                    )
                ),
                "cia"    => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "cash account",
                        "mdlName"     => "MdlBankAccount",

                        "key"        => "id",
                        "labelSrc"   => "nama",
                        "usedFields" => array(
                            "nama" => "",


                        ),
                        "editPoints" => array(1,),
                    )
                ),
                "credit" => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "term of payment",
                        "mdlName"     => "MdlTop",
                        "mdlFilter"   => array(),
                        "key"         => "kode",
                        "labelSrc"    => "nama",
                        "description" => "",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    )
                ),
            ),
        ),
        "relativeOptions"  => array(
            "paymentMethod" => array(

                "cia" => array(
                    "nilai_cia" => array(
                        "label"        => "cash amount",
                        "defaultValue" => "nett",
                        "minValue"     => "nett",
                        "maxValue"     => "nett",
                    ),

                ),
            ),
        ),
    ),


    "583" => array(
        "icon"                 => "fa fa-truck",
        "label"                => "stock distribution",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "distribution request",
                "actionLabel"  => "distribute",
                "source"       => "",
                "target"       => "583r",
                "userGroup"    => "c_holding",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "authorization",
                "actionLabel"  => "approve distribution",
                "source"       => "583r",
                "target"       => "583",
                "userGroup"    => "c_holding",
                "stateLabel"   => "sent",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),

        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlLockerStock",
        "selectorSrcModel"     => "MdlProduk",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("jual"),
            "key_label" => array(
                "jual" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"          => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "gudang_id=gudangID",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama", "satuan", "jumlah",
        ),

        "selectorProcessor"    => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlCabang",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "cabang",
        "pihakFilters"         => array(
            "id<>cabang_id",
        ),
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"      => array(
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
        "shoppingCartAmountValue"    => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount"  => true,

        "receiptElements" => array(
            "gudang" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "target warehouse",
                "mdlName"     => "MdlGudangDefault",
                "mdlFilter"   => array("cabang_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),

        "connectTo" => "585",
    ),
    "585" => array(
        "icon"                 => "fa fa-ship",
        "label"                => "stock reception",
        "place"                => "branch",
        "steps"                => array(
            1 => array(
                "label"        => "stock initiation",
                "actionLabel"  => "init reception",
                "source"       => "",
                "target"       => "585r",
                "userGroup"    => "sys",
                "stateLabel"   => "pending acceptance",
                "stateColor"   => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label"        => "stock reception",
                "actionLabel"  => "receive",
                "source"       => "585r",
                "target"       => "585",
                "userGroup"    => "o_gudang",
                "stateLabel"   => "stock received",
                "stateColor"   => "#009900",
                "stateCaption" => "received by",
            ),

        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlLockerStock",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama", "satuan", "jumlah",
        ),

        "selectorProcessor"    => "Selectors/_processSelectProductStock/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlCabang",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "cabang",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),


        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"      => array(
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
        "shoppingCartAmountValue"    => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartHideSubamount"  => true,
    ),
    "581" => array(
        "icon"                        => "fa fa-opencart",
        "label"                       => "sales order",
        "place"                       => "center",
        "steps"                       => array(
            1 => array(
                "label"        => "sales order",
                "actionLabel"  => "make order",
                "source"       => "",
                "target"       => "581r",
                "userGroup"    => "o_seller",
                "stateLabel"   => "awating for process",
                "stateColor"   => "#dd3300",
                "stateCaption" => "made by",
            ),

            2 => array(
                "label"        => "request processing",
                "actionLabel"  => "process",
                "source"       => "581r",
                "target"       => "581s",
                "userGroup"    => "sys",
                "stateLabel"   => "fulfilled",
                "stateColor"   => "#009900",
                "stateCaption" => "fullfilled by",
            ),
            //            3 => array(
            //                "label" => "request fulfill",
            //                "actionLabel" => "do fulfill",
            //                "source" => "581s",
            //                "target" => "581",
            //                "userGroup" => "adminssss",
            //                "stateLabel" => "fulfill",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "fullfilled by",
            //            ),

        ),
        "template"                    => "application/template/transaksi.html",
        "selectorModel"               => "MdlProduk",
        "selectorSrcModel"            => "MdlProduk",
        "selectedPrice"               => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("jual", "ppv"),
            "key_label" => array(
                "jual" => "harga",
                "ppv"  => "ppv",
            ),
            "mainSrc"   => "jual",
        ),
        "lockerCheck"                 => array(),
        "selectorFilters"             => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller"              => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"               => "item",
        "selectorParamFields"         => array(
            "id"     => "id",
            "nama"   => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields"        => array(
            "nama", "satuan",// "jumlah"
        ),
        "selectorProcessor"           => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"           => "select",
        "pihakModel"                  => "MdlCustomer",
        "pihakCaller"                 => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                  => "customer",
        "pihakProcessor"              => "Selectors/_processPihak/select",
        "shortHistoryFields"          => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "customers_nama"  => "customer",
            "nomer_top"       => "SO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
        ),
        "selectorFields"              => array("id", "nama", "satuan"),
        "pihakFields"                 => array("id", "nama"),
        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),

            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            4 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            5 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"   => "nama",
            "code"   => "kode",
            "label"  => "label",
            "satuan" => "satuan",
            "ppn"    => "harga*(10/100)",

            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            //            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
            2 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),

            3 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
        ),
        "shoppingCartEditableFields"  => array(
            1 => array(
                "jml",
                "produk_ord_jml",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),

            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartAmountValue"     => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",

            3 => "jml",
            4 => "jml",
            5 => "jml*(harga+ppn)",
        ),
        "extTool"                     => array(
            "label"     => "disc calculator",
            "url"       => "/debug/tools/c.php",
            "sentField" => "harga",
            "sentParam" => "items",
            "gotParam"  => "items",
            "gotField"  => "harga",
            "externSrc" => "pihakID",
            "backUrl"   => "Selectors/_processSelectProduct/updateValues",
        ),


        "receiptElements"  => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "customer details",
                "mdlName"     => "MdlCustomer",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "paymentMethod"   => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "payment method",
                "mdlName"     => "MdlPaymentMethod2",
                //                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "",


                ),
                "editPoints"  => array(1,),
            ),
        ),
        "relativeElements" => array(
            "paymentMethod" => array(
                "cash" => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "cash account",
                        "mdlName"     => "MdlBankAccount",

                        "key"        => "id",
                        "labelSrc"   => "nama",
                        "usedFields" => array(
                            "nama" => "",


                        ),
                        "editPoints" => array(1,),
                    )
                ),

            ),
        ),
        "relativeOptions"  => array(
            "paymentMethod" => array(
                "cash" => array(
                    "discount" => array(
                        "label"        => "open discount",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett*50/100",
                        "auth"         => array(
                            "groupID" => "admin"
                        ),
                    ),

                ),
            ),
        ),


        "allowTmpSave" => true,
    ),

    "983"  => array(
        "icon"                 => "fa fa-truck",
        "label"                => "stock distribution return",
        "place"                => "branch",
        "steps"                => array(
            1 => array(
                "label"        => "return request",
                "actionLabel"  => "request return",
                "source"       => "",
                "target"       => "983r",
                "userGroup"    => "o_gudang",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "return authorization",
                "actionLabel"  => "approve request",
                "source"       => "983r",
                "target"       => "983",
                "userGroup"    => "o_gudang",
                "stateLabel"   => "sent",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlNotaItem",
        "selectorSrcModel"     => "MdlNotaItem",
        "selectedPrice"        => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //            ),
        ),
        "lockerCheck"          => array(
            "enabled"      => false,
            "mdlName"      => "MdlLockerStock",
            "jenis"        => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters"      => array(
            "returned=.0",
            "jenis=.585",
            "cabang_id=placeID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),
        "selectorProcessor"    => "Selectors/_processSelectNotaItem/select",
        "editHandlerMethod"    => "edit",
        "pihakModel"           => "MdlCabang",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "cabang",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"          => "produk_nama",
            "code"          => "produk_kode",
            "label"         => "produk_label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartEditableFields"  => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue"     => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
        "shoppingCartFieldValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "referenceFields"             => array(
            "referenceID"    => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod"  => "pembayaran",
        ),

        "receiptElements" => array(
            "gudang"  => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "gudang cabang",
                "mdlName"     => "MdlGudang",
                "mdlFilter"   => array("cabang_id=placeID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "gudang2" => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "gudang dc",
                "mdlName"     => "MdlGudang",
                "mdlFilter"   => array("cabang_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),

        "connectTo" => "985",
    ),
    "985"  => array(
        "icon"                 => "fa fa-ship",
        "label"                => "stock reception (distribution return)",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "stock initiation",
                "actionLabel"  => "init reception",
                "source"       => "",
                "target"       => "985r",
                "userGroup"    => "sys",
                "stateLabel"   => "pending acceptance",
                "stateColor"   => "#dd3300",
                "stateCaption" => "received by",
            ),
            2 => array(
                "label"        => "stock reception",
                "actionLabel"  => "receive",
                "source"       => "985r",
                "target"       => "985",
                "userGroup"    => "c_gudang",
                "stateLabel"   => "stock received",
                "stateColor"   => "#009900",
                "stateCaption" => "received by",
            ),

        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlLockerStock",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama", "satuan", "jumlah",
        ),

        "selectorProcessor"  => "Selectors/_processSelectProductStock/select",
        "editHandlerMethod"  => "select",
        "pihakModel"         => "MdlCabang",
        "pihakCaller"        => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"         => "cabang",
        "pihakProcessor"     => "Selectors/_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"     => array("id", "nama", "satuan"),
        "pihakFields"        => array("id", "nama"),
        "shoppingCart"       => array(
            "initPrices" => "beli",
        ),


        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                "hpp" => "hpp",
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
        "shoppingCartAmountValue"    => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),
    ),


    // config pembayaran hutang ke supplier (finish goods)
    "489"  => array(
        "icon"                 => "fa fa-money",
        "label"                => "FG A/P payment",
        "place"                => "center",
        "steps"                => array(
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
                "label"        => "account payable payment",
                "actionLabel"  => "process payment",
                "source"       => "",
                "target"       => "489",
                "userGroup"    => "c_finance",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.467",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlSupplier",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "vendor",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",
        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "shoppingCartEditableFields"   => array(),
        "shoppingCartAmountValue"      => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove"      => true,
        "tagihanSrc"                   => "harus_bayar",
        "receiptElements"              => array(
            "vendorDetails"      => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "cash account",
                "pairedModel" => array(
                    "mdlName"    => "ComRekeningPembantuKas",
                    "mdlMethod"  => "fetchBalances",
                    "mdlFilter"  => array(
                        "cabang_id" => "placeID",
                    ),
                    "key"        => "extern_id",
                    "rekening"   => "kas",
                    "fieldID"    => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName"     => "MdlBankAccount",
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account",
                    "saldo" => "balance",
                ),
                "editPoints"  => array(1,),
            ),
            "creditAmount"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "supplier credit amount",
                "mdlName"     => "MdlSupplierCredit",
                "mdlFilter"   => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                ),
                "key"         => "kredit",
                "labelSrc"    => "kredit",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
            ),
            "dummyElement"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "pairMakers"                   => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName"   => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params"       => array(
                        "cabang_id" => "placeID",
                    ),
                    "target"       => array("main", "out_master"),
                ),
            ),
        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows"               => array(
            "dummyElement" => array(
                "yes" => array(
                    "origAmount"    => array(
                        "label"        => "original amount",
                        "defaultValue" => "tagihan",
                        "maxValue"     => "tagihan",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "addDiscount"   => array(
                        "label"        => "additional discount",
                        "defaultValue" => "diskon",
                        "maxValue"     => "diskon",
                        'disabled'     => "disabled",
                        "role"         => "minus",
                        "addPoints"    => array(1,),
                    ),
                    //                    "amount"       => array(
                    //                        "label"        => "total amount",
                    //                        "defaultValue" => "sisa",
                    //                        "maxValue"     => "sisa",
                    //                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "credit_amount" => array(
                        "label"        => "credit amount (from return)",
                        "defaultValue" => "creditAmount",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                        "role"         => "minus",
                    ),
                    //                    "credit_note"   => array(
                    //                        "label"        => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled'     => "disabled",
                    //                        "addPoints"    => array(1,),
                    //                    ),
                    "harus_bayar"   => array(
                        "label"        => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue"     => "(sisa-creditAmount-creditValue)",
                        "minValue"     => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled'       => "disabled",
                        "addPoints"      => array(1,),
                    ),
                    "nilai_entry"   => array(
                        "label"        => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction"  => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;}
     
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                ),
            ),
        ),

    ),
    // config pembayaran hutang ke supplier (supplies)
    "487"  => array(
        "icon"                 => "fa fa-money",
        "label"                => "supplies A/P payment",
        "place"                => "center",
        "steps"                => array(
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
                "label"        => "account payable payment",
                "actionLabel"  => "process payment",
                "source"       => "",
                "target"       => "487",
                "userGroup"    => "c_finance",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.461",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlSupplier",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "vendor",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",
        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields"   => array(),
        "shoppingCartAmountValue"      => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove"      => true,
        "receiptElements"              => array(
            "vendorDetails"      => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "cash account",
                "pairedModel" => array(
                    "mdlName"    => "ComRekeningPembantuKas",
                    "mdlMethod"  => "fetchBalances",
                    "mdlFilter"  => array(
                        "cabang_id" => "placeID",
                    ),
                    "key"        => "extern_id",
                    "rekening"   => "kas",
                    "fieldID"    => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName"     => "MdlBankAccount",
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account",
                    "saldo" => "balance",
                ),
                "editPoints"  => array(1,),
            ),
            "creditAmount"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "supplier credit amount",
                "mdlName"     => "MdlSupplierCredit",
                "mdlFilter"   => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                ),
                "key"         => "kredit",
                "labelSrc"    => "kredit",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
            ),
            "dummyElement"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "pairMakers"                   => array(
            1 => array(
                "stock" => array(
                    "helperName"   => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows"               => array(
            "dummyElement" => array(
                "yes" => array(
                    "origAmount"    => array(
                        "label"        => "orig. amount",
                        "defaultValue" => "tagihan",
                        "maxValue"     => "tagihan",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "addDiscount"   => array(
                        "label"        => "additional discount",
                        "defaultValue" => "diskon",
                        "maxValue"     => "diskon",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "amount"        => array(
                        "label"        => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue"     => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_amount" => array(
                        "label"        => "credit amount",
                        "defaultValue" => "creditAmount",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_note"   => array(
                        "label"        => "credit note",
                        "defaultValue" => "creditValue",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "harus_bayar"   => array(
                        "label"        => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue"     => "(sisa-creditAmount-creditValue)",
                        "minValue"     => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled'       => "disabled",
                        "addPoints"      => array(1,),

                    ),
                    "nilai_entry"   => array(
                        "label"        => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction"  => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;} 
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                ),
            ),
        ),
    ),
    // config pembayaran biaya umum
    "462"  => array(
        "icon"                 => "fa fa-money",
        "label"                => "service A/P payment",
        "place"                => "center",
        "steps"                => array(
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
                "label"       => "account payable payment",
                "actionLabel" => "process payment",
                "source"      => "",
                "target"      => "462",
                "userGroup"   => "c_finance",
                "stateLabel"  => "completed",
                "stateColor"  => "#009900",
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.463",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlSupplier",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "vendor",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",
        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "shoppingCartEditableFields"   => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue"      => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove"      => true,
        "receiptElements"              => array(
            "vendorDetails"      => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "cash account",
                "pairedModel" => array(
                    "mdlName"    => "ComRekeningPembantuKas",
                    "mdlMethod"  => "fetchBalances",
                    "mdlFilter"  => array(
                        "cabang_id" => "placeID",
                    ),
                    "key"        => "extern_id",
                    "rekening"   => "kas",
                    "fieldID"    => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName"     => "MdlBankAccount",
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account",
                    "saldo" => "balance",
                ),
                "editPoints"  => array(1,),
            ),
            "creditAmount"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "supplier credit amount",
                "mdlName"     => "MdlSupplierCredit",
                "mdlFilter"   => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                ),
                "key"         => "kredit",
                "labelSrc"    => "kredit",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
            ),
            "dummyElement"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "pairMakers"                   => array(
            1 => array(
                "stock" => array(
                    "helperName"   => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows"               => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount"        => array(
                        "label"        => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue"     => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_amount" => array(
                        "label"        => "credit amount",
                        "defaultValue" => "creditAmount",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_note"   => array(
                        "label"        => "credit note",
                        "defaultValue" => "creditValue",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "harus_bayar"   => array(
                        "label"        => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue"     => "(sisa-creditAmount-creditValue)",
                        "minValue"     => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled'       => "disabled",
                        "addPoints"      => array(1,),

                    ),
                    "nilai_entry"   => array(
                        "label"        => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction"  => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;} 
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                ),
            ),
        ),
    ),
    // config penerimaan piutang customer (uang masuk)
    "749"  => array(
        "icon"                 => "fa fa-money",
        "label"                => "A/R receivement",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "A/R receivement",
                "actionLabel"  => "process receivement",
                "source"       => "",
                "target"       => "749",
                "userGroup"    => "c_kasir",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "template"             => "application/template/transaksi_payment.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.582",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlCustomer",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "customer",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",

        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields"   => array(),
        "shoppingCartAmountValue"      => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove"      => true,
        "tagihanSrc"                   => "harus_bayar",
        "receiptElements"              => array(
            "customerDetails"    => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "customer details",
                "mdlName"     => "MdlCustomer",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "cash account",
                "mdlName"     => "MdlBankAccount",

                "key"        => "id",
                "labelSrc"   => "nama",
                "usedFields" => array(
                    "nama" => "",


                ),
                "editPoints" => array(1,),
            ),
            "creditAmount"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "customer credit amount",
                "mdlName"     => "MdlCustomerCredit",
                "mdlFilter"   => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                ),
                "key"         => "kredit",
                "labelSrc"    => "kredit",
                "usedFields"  => array(
                    "extern_nama" => "",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
                "noPrefetch"  => true,
            ),
            "dummyElement"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows"               => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount"        => array(
                        "label"        => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue"     => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_amount" => array(
                        "label"        => "credit amount",
                        "defaultValue" => "creditAmount",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_note"   => array(
                        "label"        => "credit note",
                        "defaultValue" => "creditValue",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "harus_bayar"   => array(
                        "label"        => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue"     => "(sisa-creditAmount-creditValue)",
                        "minValue"     => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled'       => "disabled",
                        "addPoints"      => array(1,),

                    ),
                    "nilai_entry"   => array(
                        "label"        => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction"  => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;} 
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                ),
            ),
        ),
    ),
    // config supplies yang dibiayakan
    "762"  => array(
        "icon"                        => "fa fa-circle",
        "label"                       => "pembiayaan supplies",
        "place"                       => "center",
        "steps"                       => array(
            1 => array(
                "label"        => "pembiayaan supplies",
                "actionLabel"  => "make order",
                "source"       => "",
                "target"       => "762r",
                "userGroup"    => "c_holding",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "approval pembiayaan supplies",
                "actionLabel"  => "approve biaya",
                "source"       => "762r",
                "target"       => "762",
                "userGroup"    => "c_holding",
                "stateLabel"   => "approved",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template"                    => "application/template/transaksi.html",
        "selectorModel"               => "MdlLockerStockSupplies",
        "selectorSrcModel"            => "MdlSupplies",
        "selectedPrice"               => array(
            "model"     => "MdlHargaSupplies",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"                 => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStockSupplies",
        ),
        "selectorFilters"             => array(
            "cabang_id=placeID", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            "jumlah>.0",
            "state=.active",
        ),
        "selectorCaller"              => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"               => "item",
        "selectorParamFields"         => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields"        => array(
            "nama", "satuan", "jumlah",
        ),
        "selectorProcessor"           => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"           => "select",
        "pihakModel"                  => "MdlCabang",
        "pihakCaller"                 => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                  => "cabang",
        "pihakProcessor"              => "Selectors/_processPihak/select",
        "shortHistoryFields"          => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "compactHistoryFields"        => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"              => array("id", "nama", "satuan"),
        "pihakFields"                 => array("id", "nama"),
        "shoppingCart"                => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
            2 => array(
                //                "hpp" => "hpp",
                "harga" => "price",
                //            "ppn" => "VAT",
            ),
        ),
        "shoppingCartEditableFields"  => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartAmountValue"     => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "shoppingCartAvoidRemove"     => true,
    ),
    //==keluar kas ke suppliers
    "400"  => array(
        "icon"                 => "fa fa-money",
        "label"                => "cash out",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "cash out",
                "actionLabel"  => "process cash out",
                "source"       => "",
                "target"       => "400",
                "userGroup"    => "c_finance",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "paid by",
            ),
        ),
        "template"             => "application/template/transaksi_payment.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.467",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlSupplier",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "vendor",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",
        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "shoppingCartEditableFields"   => array(
            //            "harga",
            //            "ppn",
            //"jml",
        ),
        "shoppingCartAmountValue"      => array(
            1 => "sisa",
        ),
        "receiptElements"              => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "cash_account"  => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "account used to pay",
                //                "pairedModel" => array(
                //                    "mdlName"    => "ComRekeningPembantuKas",
                //                    "mdlMethod"  => "fetchBalances",
                //                    "mdlFilter"  => array(
                //                        "cabang_id" => "placeID",
                //                    ),
                //                    "key"        => "extern_id",
                //                    "rekening"   => "kas",
                //                    "fieldID"    => "debet",
                //                    "fieldLabel" => "saldo",
                //                ),
                "mdlName"     => "MdlBankAccountSaldo",
                "mdlFilter"   => array(
                    "bank.cabang_id=placeID",
                ),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account number",
                    "alias" => "holder alias",
                    "debet" => "balance",
                ),
                "editPoints"  => array(1,),
            ),
            "creditAmount"  => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "supplier credit amount",
                "mdlName"     => "MdlSupplierCredit",
                "mdlFilter"   => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                ),
                "key"         => "debet",
                "labelSrc"    => "debet",
                "usedFields"  => array(
                    "extern_nama" => "vendor name",
                    "debet"       => "balance",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
            ),
            "creditValue"   => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "given credit note",
                "mdlName"     => "MdlCreditNote",
                "mdlFilter"   => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "jenis=.467",
                    "label=.credit note",
                    "transaksi_id=refID",
                ),
                "key"         => "remain",
                "labelSrc"    => "remain",
                "usedFields"  => array(
                    "extern_nama" => "vendor name",
                    "debet"       => "balance",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
            ),
            "dummyElement"  => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),

        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows"               => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount"        => array(
                        "label"        => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue"     => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_amount" => array(
                        "label"        => "credit amount",
                        "defaultValue" => "creditAmount",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_note"   => array(
                        "label"        => "credit note",
                        "defaultValue" => "creditValue",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "harus_bayar"   => array(
                        "label"        => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue"     => "(sisa-creditAmount-creditValue)",
                        "minValue"     => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled'       => "disabled",
                        "addPoints"      => array(1,),

                    ),
                    "nilai_entry"   => array(
                        "label"        => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction"  => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;} 
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                ),
            ),
        ),

    ),
    //==masuk kas
    "700"  => array(
        "icon"                 => "fa fa-money",
        "label"                => "cash in",
        "place"                => "center",
        "steps"                => array(

            1 => array(
                "label"        => "cash in",
                "actionLabel"  => "receive cash in",
                "source"       => "",
                "target"       => "700",
                "userGroup"    => "c_finance",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "template"             => "application/template/transaksi_payment.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.582",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlCustomer",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "customer",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",

        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa" => "sisa",
            ),

        ),
        "shoppingCartEditableFields"   => array(
            //            "harga",
            //            "ppn",
            //            "jml",
            //
        ),
        //        "shoppingCartAmountValue" => "jml*(harga+ppn)",
        "shoppingCartAmountValue"      => array(
            1 => "sisa",
        ),
        "receiptElements"              => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "customer details",
                "mdlName"     => "MdlCustomer",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "cash_account"    => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "account used to pay",
                //                "pairedModel" => array(
                //                    "mdlName"    => "ComRekeningPembantuKas",
                //                    "mdlMethod"  => "fetchBalances",
                //                    "mdlFilter"  => array(
                //                        "cabang_id" => "placeID",
                //                    ),
                //                    "key"        => "extern_id",
                //                    "rekening"   => "kas",
                //                    "fieldID"    => "debet",
                //                    "fieldLabel" => "saldo",
                //                ),
                "mdlName"     => "MdlBankAccountSaldo",
                "mdlFilter"   => array(
                    "bank.cabang_id=placeID",
                ),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account number",
                    "alias" => "holder alias",
                    "debet" => "balance",
                ),
                "editPoints"  => array(1,),
            ),
            "creditAmount"    => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "supplier credit amount",
                "mdlName"     => "MdlSupplierCredit",
                "mdlFilter"   => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                ),
                "key"         => "debet",
                "labelSrc"    => "debet",
                "usedFields"  => array(
                    "extern_nama" => "vendor name",
                    "debet"       => "balance",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
            ),
            "creditValue"     => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "given credit note",
                "mdlName"     => "MdlCreditNote",
                "mdlFilter"   => array(
                    "extern_id=pihakID",
                    "cabang_id=cabangID",
                    "jenis=.467",
                    "label=.credit note",
                    "transaksi_id=refID",
                ),
                "key"         => "remain",
                "labelSrc"    => "remain",
                "usedFields"  => array(
                    "extern_nama" => "vendor name",
                    "debet"       => "balance",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
            ),
            "dummyElement"    => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows"               => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount"        => array(
                        "label"        => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue"     => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_amount" => array(
                        "label"        => "credit amount",
                        "defaultValue" => "creditAmount",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_note"   => array(
                        "label"        => "credit note",
                        "defaultValue" => "creditValue",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "harus_bayar"   => array(
                        "label"        => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue"     => "(sisa-creditAmount-creditValue)",
                        "minValue"     => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled'       => "disabled",
                        "addPoints"      => array(1,),

                    ),
                    "nilai_entry"   => array(
                        "label"        => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction"  => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;} 
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                ),
            ),
        ),
    ),

    //  config return pembelian finish goods
    "967"  => array(
        "icon"                       => "fa fa-rotate-left",
        "label"                      => "FG purchasing return ",
        "place"                      => "center",
        "steps"                      => array(
            1 => array(
                "label"        => "return request",
                "actionLabel"  => "make return request",
                "source"       => "",
                "target"       => "967r",
                "userGroup"    => "c_purchasing",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "return authorization",
                "actionLabel"  => "approve return request",
                "source"       => "967r",
                "target"       => "967",
                "userGroup"    => "c_purchasing_adm",
                "stateLabel"   => "approved",
                "stateColor"   => "#ff7700",
                "stateCaption" => "approved by",
            ),
            //            3 => array(
            //                "label" => "goods received note",
            //                "actionLabel" => "receive & make GRN",
            //                "source" => "466",
            //                "target" => "467",
            //                "userGroup" => "c_holding",
            //                "stateLabel" => "GRN made",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "received by",
            //            ),
        ),
        "template"                   => "application/template/transaksi.html",
        "selectorModel"              => "MdlNotaItem",
        "selectorSrcModel"           => "MdlNotaItem",
        "selectedPrice"              => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck"                => array(
            "enabled"      => false,
            "mdlName"      => "MdlLockerStock",
            "jenis"        => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters"            => array(
            "returned=.0",
            "jenis=.467",
            "suppliers_id=pihakID",
        ),
        "selectorCaller"             => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"              => "item",
        "selectorParamFields"        => array(
            "id"   => "id",
            "nama" => "nomer",
            //            "satuan" => "satuan",
            //            "jumlah"=>"jumlah",
        ),
        "selectorViewedFields"       => array(
            "nomer", "dtime",
        ),
        "selectorProcessor"          => "Selectors/_processSelectNotaItem/select",
        "editHandlerMethod"          => "edit",
        "pihakModel"                 => "MdlSupplier",
        "pihakCaller"                => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                 => "vendor",
        "pihakMainValueSrc"          => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"             => "Selectors/_processPihak/select",
        "shortHistoryFields"         => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields"       => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"             => array("id", "nama", "satuan"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "produk_nama",
            "code"          => "produk_kode",
            "label"         => "produk_label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                //            "avail" => "current stock",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                //            "avail" => "current stock",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "harga" => "Price",
                "ppn"   => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "ppn"   => "VAT",
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
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
        ),

        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),

        "availPayments"   => array(

            "inherit" => array(
                "label"     => "inherit",
                "valueGate" => "nilai_inherit",
                "valueSrc"  => "nett",
            ),
        ),
        "referenceFields" => array(
            "referenceID"    => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod"  => "pembayaran",
        ),
        "pairMakers"      => array(
            1 => array(
                "stokProduk" => array(
                    "helperName"   => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                    "target"       => array("items", "out_detail"),
                ),
            ),
        ),
        "receiptElements" => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),


        ),
    ),
    //  config return pembelian supplies
    "961"  => array(
        "icon"                       => "fa fa-rotate-left",
        "label"                      => "supplies purchasing return ",
        "place"                      => "center",
        "steps"                      => array(
            1 => array(
                "label"        => "return request",
                "actionLabel"  => "make return request",
                "source"       => "",
                "target"       => "961r",
                "userGroup"    => "c_purchasing",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "return authorization",
                "actionLabel"  => "approve return request",
                "source"       => "961r",
                "target"       => "961",
                "userGroup"    => "c_purchasing_adm",
                "stateLabel"   => "approved",
                "stateColor"   => "#ff7700",
                "stateCaption" => "approved by",
            ),
        ),
        "template"                   => "application/template/transaksi.html",
        "selectorModel"              => "MdlNotaItem",
        "selectorSrcModel"           => "MdlNotaItem",
        "selectedPrice"              => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck"                => array(
            "enabled"      => false,
            "mdlName"      => "MdlLockerStockSupplies",
            "jenis"        => "supplies",
            "jenis_locker" => "stock",
        ),
        "selectorFilters"            => array(
            "returned=.0",
            "jenis=.461",
            "suppliers_id=pihakID",
        ),
        "selectorCaller"             => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"              => "item",
        "selectorParamFields"        => array(
            "id"   => "id",
            "nama" => "nomer",
            //            "satuan" => "satuan",
            //            "jumlah"=>"jumlah",
        ),
        "selectorViewedFields"       => array(
            "nomer", "dtime",
        ),
        "selectorProcessor"          => "Selectors/_processSelectNotaItem/select",
        "editHandlerMethod"          => "edit",
        "pihakModel"                 => "MdlSupplier",
        "pihakCaller"                => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                 => "vendor",
        "pihakMainValueSrc"          => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"             => "Selectors/_processPihak/select",
        "shortHistoryFields"         => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields"       => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"             => array("id", "nama", "satuan"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "produk_nama",
            "code"          => "produk_kode",
            "label"         => "produk_label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                //            "avail" => "current stock",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                //            "avail" => "current stock",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "harga" => "Price",
                "ppn"   => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "ppn"   => "VAT",
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
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*(hpp+ppn)",
            2 => "jml*(hpp+ppn)",
        ),

        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),

        "referenceFields" => array(
            "referenceID"    => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod"  => "pembayaran",
        ),
        "pairMakers"      => array(
            1 => array(
                "stokSupplies" => array(
                    "helperName"   => "he_cek_stock_supplies",
                    "functionName" => "cekStockSupplies",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
    ),


    //  config pemindahan finish goods (ke tidak dijual)
    "587"  => array(
        "icon"                 => "fa fa-truck",
        "label"                => "destock (to other warehouse)",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "destock request",
                "actionLabel"  => "make destock request",
                "source"       => "",
                "target"       => "587r",
                "userGroup"    => "w_gudang",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "authorization",
                "actionLabel"  => "approve destock request",
                "source"       => "587r",
                "target"       => "587ra",
                "userGroup"    => "w_gudang_spv",
                "stateLabel"   => "sent",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label"        => "destock reception",
                "actionLabel"  => "receive destocked items",
                "source"       => "587ra",
                "target"       => "587",
                "userGroup"    => "w_gudang",
                "stateLabel"   => "sent",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlLockerStock",
        "selectorSrcModel"     => "MdlProduk",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"          => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
            "gudang_id=gudangID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama", "satuan", "jumlah",
        ),

        "selectorProcessor"    => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlGudang",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "gudang",
        "pihakFilters"         => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakAddStaticEntry"  => array(
            "id"    => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                "hpp" => "hpp",
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
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),

        //        "connectTo" => "589",
    ),
    //  config pemindahan finish goods (ke dijual)
    "687"  => array(
        "icon"                 => "fa fa-truck",
        "label"                => "restock (into active warehouse)",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "restock request",
                "actionLabel"  => "make restock request",
                "source"       => "",
                "target"       => "687r",
                "userGroup"    => "w_gudang",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "authorization",
                "actionLabel"  => "approve restock request",
                "source"       => "687r",
                "target"       => "687ra",
                "userGroup"    => "w_gudang_spv",
                "stateLabel"   => "sent",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label"        => "restock reception",
                "actionLabel"  => "receive restocked items",
                "source"       => "687ra",
                "target"       => "687",
                "userGroup"    => "w_gudang",
                "stateLabel"   => "sent",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlLockerStock",
        "selectorSrcModel"     => "MdlProduk",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("jual"),
            "key_label" => array(
                "jual" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"          => array(
            "enabled" => true,
            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
            "gudang_id=gudangID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama", "satuan", "jumlah",
        ),

        "selectorProcessor"    => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlGudang",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "gudang",
        "pihakFilters"         => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakAddStaticEntry"  => array(
            "id"    => "gudang_id",
            "label" => "gudang_nama",
        ),
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                "hpp" => "hpp",
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
        "shoppingCartAmountValue"    => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            3 => "jml*hpp",
        ),
    ),
    //  config konversi finish goods
    "334"  => array(
        "icon"                 => "fa fa-cube",
        "label"                => "product grade conversion",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "conversion request",
                "actionLabel"  => "make conversion request",
                "source"       => "",
                "target"       => "334r",
                "userGroup"    => "w_gudang",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "authorization",
                "actionLabel"  => "approve conversion request",
                "source"       => "334r",
                "target"       => "334",
                "userGroup"    => "w_gudang_spv",
                "stateLabel"   => "sent",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
            //			3 => array(
            //				"label"        => "reception",
            //				"actionLabel"  => "terima barang konversi",
            //				"source"       => "334ra",
            //				"target"       => "334",
            //				"userGroup"    => "gudang0",
            //				"stateLabel"   => "complete",
            //				"stateColor"   => "#009900",
            //				"stateCaption" => "approved by",
            //			),
        ),
        "template"             => "application/template/transaksi_nopihak.html",
        "selectorModel"        => "MdlLockerStock",
        "selectorSrcModel"     => "MdlProduk",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"          => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
            "gudang_id=gudangID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor"    => "Selectors/_processSelectProductConvertion/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlGudang",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "gudang",
        "pihakFilters"         => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"             => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"           => array(
            "nama"          => "nama",
            "code"          => "kode",
            "label"         => "label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"          => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2"         => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled"        => false,
        //        "shoppingCartPairedItemEnabled" => true,
        "shoppingCartPairedItemRecorder" => "recordPaireditem",
        "shoppingCartPairedItem"         => array(
            "enabled"   => true,
            "mdlName"   => "MdlProduk",
            "srcKey"    => "id",
            "srcLabel"  => array("nama"),
            "mdlFilter" => array("id<>id"),
        ),
        "shoppingCartEditableFields"     => array(
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
        "shoppingCartAmountValue"        => array(
            1 => "jml",
            2 => "jml",
        ),
        "shoppingCartHideSubamount"      => true,

        "shoppingCartFieldValidators"    => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators" => array(
            "jml" => "quantity",
            //            "harga" => "price",
            "hpp" => "hpp",
        ),
    ),
    //  config konversi finish goods (satuan)
    "1334" => array(
        "icon"                 => "fa fa-cube",
        "label"                => "product conversion",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "conversion request",
                "actionLabel"  => "make conversion request",
                "source"       => "",
                "target"       => "1334r",
                "userGroup"    => "c_gudang",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "conversion",
                "actionLabel"  => "approve conversion request",
                "source"       => "1334r",
                "target"       => "1334",
                "userGroup"    => "c_gudang",
                "stateLabel"   => "sent",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template"             => "application/template/transaksi_nopihak.html",
        "selectorModel"        => "MdlLockerStock",
        "selectorSrcModel"     => "MdlProduk",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"          => array(
            //			"enabled" => true,
            //			"mdlName" => "MdlLockerStock",
        ),
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jumlah>.0",
            "state=.active",
            "gudang_id=gudangID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "produk_id",
            "nama"   => "nama",
            "satuan" => "satuan",
            "jumlah" => "jumlah",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            "jumlah",
        ),

        "selectorProcessor"    => "Selectors/_processSelectProductConvertionSatuan/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlGudang",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "gudang",
        "pihakFilters"         => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"      => array(
            1 => array(
                "nama"   => "item name source",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name source",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFields2"     => array(
            1 => array(
                "nama"   => "item name target",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name target",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc"    => array(
            "nama"   => "nama",
            "code"   => "kode",
            "label"  => "label",
            "satuan" => "satuan",
        ),
        "shoppingCartNumFields"   => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNumFields2"  => array(
            1 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
            3 => array(
                //				"hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled" => false, // ini notes per-items
        //        "shoppingCartPairedItemEnabled" => true,

        "shoppingCartPairedItemRecorder" => "recordPaireditemSatuan",
        "shoppingCartPairedItem"         => array(
            "enabled"   => true,
            "mdlName"   => "MdlProduk",
            "srcKey"    => "id",
            "srcLabel"  => array("nama"),
            "mdlFilter" => array("id<>id"),
        ),
        "shoppingCartFieldsPairedItem"   => array(
            1 => array(
                //                "nama" => "item name target",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),

        "shoppingCartEditableFields" => array(
            1 => array(
                //            "harga",
                //            "ppn",
                "jml",
            ),
            //            2 => array(
            ////            "harga",
            ////            "ppn",
            //                "jml",
            //            ),
            //            3 => array(
            ////            "harga",
            ////            "ppn",
            //                "jml",
            //            ),
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml",
            2 => "jml",
        ),
        "shoppingCartHideSubamount"  => true,

        "shoppingCartFieldValidators"              => array(
            "jml" => "quantity",
            //            "harga" => "price",
        ),
        "shoppingCartFieldMidValidators"           => array(
            "jml" => "quantity",
            //            "harga" => "price",
            //            "hpp" => "hpp",
        ),
        "shoppingCartFieldMidValidatorsPairedItem" => array(
            "hpp_sumber" => "sumber",
            "hpp_target" => "target",
        ),
    ),
    //  config assembling / produksi
    "776"  => array(
        "icon"                 => "fa fa-cube",
        "label"                => "product assembling",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "assembling request",
                "actionLabel"  => "make assembling request",
                "source"       => "",
                "target"       => "776r",
                "userGroup"    => "c_holding",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "authorization",
                "actionLabel"  => "approve & process assembling request",
                "source"       => "776r",
                "target"       => "776",
                "userGroup"    => "c_holding",
                "stateLabel"   => "complete",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
            //            3 => array(
            //                "label"        => "reception",
            //                "actionLabel"  => "terima barang konversi",
            //                "source"       => "334ra",
            //                "target"       => "334",
            //                "userGroup"    => "gudang0",
            //                "stateLabel"   => "complete",
            //                "stateColor"   => "#009900",
            //                "stateCaption" => "approved by",
            //            ),
        ),
        "template"             => "application/template/transaksi_nopihak.html",
        "selectorModel"        => "MdlProdukRakitan",
        "selectorSrcModel"     => "MdlProdukRakitan",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"          => array(
            //            "enabled" => false,
            //            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters"      => array(),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "id",
            "nama"   => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
        ),

        "selectorProcessor"          => "Selectors/_processSelectProductAssembling/select",
        "editHandlerMethod"          => "select",
        "pihakModel"                 => "MdlGudang",
        "pihakCaller"                => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                 => "gudang",
        "pihakFilters"               => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor"             => "Selectors/_processPihak/select",
        "shortHistoryFields"         => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "compactHistoryFields"       => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"             => array("id", "nama", "satuan"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"   => "nama",
            "code"   => "kode",
            "label"  => "label",
            "satuan" => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            //            "berat_gross"   => "berat_gross",
            //            "lebar_gross"   => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross"  => "tinggi_gross",
            //            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            //            3 => array(
            ////                "hpp" => "hpp",
            //                //            "harga" => "price",
            //            ),
        ),
        "shoppingCartNoteEnabled"    => false,
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
            //            3 => array(
            ////            "harga",
            ////            "ppn",
            //                "jml",
            //            ),
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
            //            3 => "jml*hpp",
        ),

        "componentsAss" => array(
            "model"    => "MdlProdukKomposisi",
            "modelSrc" => "MdlSupplies",
        ),
    ),
    //  config return assembling / produksi
    "976"  => array(
        "icon"                 => "fa fa-cube",
        "label"                => "product de-assembling",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "de-assembling request",
                "actionLabel"  => "make de-assembling request",
                "source"       => "",
                "target"       => "976r",
                "userGroup"    => "c_holding",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "authorization",
                "actionLabel"  => "approve & process de-assembling request",
                "source"       => "976r",
                "target"       => "976",
                "userGroup"    => "c_holding",
                "stateLabel"   => "complete",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template"             => "application/template/transaksi_nopihak.html",
        "selectorModel"        => "MdlNotaItemAssembly",
        "selectorSrcModel"     => "MdlNotaItemAssembly",
        "selectedPrice"        => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "beli",
        ),
        "lockerCheck"          => array(
            //            "enabled" => false,
            //            "mdlName" => "MdlLockerStock",
        ),
        "selectorFilters"      => array(
            "returned=.0",
            "jenis=.776",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer",
            "dtime",
        ),

        "selectorProcessor"          => "Selectors/_processSelectNotaItemAssembling/select",
        "editHandlerMethod"          => "edit",
        "pihakModel"                 => "MdlGudang",
        "pihakCaller"                => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                 => "gudang",
        "pihakFilters"               => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor"             => "Selectors/_processPihak/select",
        "shortHistoryFields"         => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "compactHistoryFields"       => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"             => array("id", "nama", "satuan"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
                //            "harga" => "harga",
            ),
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"   => "produk_nama",
            "code"   => "kode",
            "label"  => "label",
            "satuan" => "satuan",
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
            2 => array(
                //                "hpp" => "hpp",
                //            "harga" => "price",
            ),
        ),
        "shoppingCartNoteEnabled"    => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
            ),
            2 => array(
                "jml",
            ),
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*hpp",
            2 => "jml*hpp",
        ),

        "componentsAss" => array(
            "model"    => "MdlProdukKomposisi",
            "modelSrc" => "MdlSupplies",
        ),
    ),


    "582"  => array(
        "icon"                        => "fa fa-opencart",
        "label"                       => "sales",
        "place"                       => "center",
        "steps"                       => array(
            1 => array(
                "label"        => "sales pre order",
                "actionLabel"  => "make order",
                "source"       => "",
                "target"       => "582spo",
                "userGroup"    => "c_seller",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "order authorization",
                "actionLabel"  => "approve order",
                "source"       => "582spo",
                "target"       => "582so",
                "userGroup"    => "c_seller_spv",
                "stateLabel"   => "ordered",
                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optStateLabel"    => "pending disc. approval",
                "stateColor"   => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit"    => true,
            ),

            3 => array(
                "label"        => "pre packing",
                "actionLabel"  => "process packing",
                "source"       => "582so",
                "target"       => "582pkd", // packed
                "userGroup"    => "c_seller_spv",
                "stateLabel"   => "packed",
                "stateColor"   => "#009900",
                "stateCaption" => "packed by",
                "allowEdit"    => true,
            ),
            4 => array(
                "label"        => "shipment",
                "actionLabel"  => "process shipment",
                "source"       => "582pkd",
                "target"       => "582spd", // shipped
                "userGroup"    => "c_seller_spv",
                "stateLabel"   => "shipped",
                "stateColor"   => "#009900",
                "stateCaption" => "shipped by",

            ),
            5 => array(
                "label"        => "invoicing",
                "actionLabel"  => "create invoice",
                "source"       => "582spd",
                "target"       => "582", // invoice
                "userGroup"    => "c_seller_spv",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "completed by",
                "allowJoin"    => true,
            ),
        ),
        "template"                    => "application/template/transaksi.html",
        "selectorModel"               => "MdlProduk",
        "selectorSrcModel"            => "MdlProduk",
        "selectedPrice"               => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("jual", "ppv", "disc"),
            "key_label" => array(
                "jual" => "harga",
                "ppv"  => "ppv",
                "disc" => "disc",
            ),
            "mainSrc"   => "jual",
        ),
        "lockerCheck"                 => array(),
        "selectorFilters"             => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller"              => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"               => "item",
        "selectorParamFields"         => array(
            "id"     => "id",
            "nama"   => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields"        => array(
            "nama", "satuan",// "jumlah"
        ),
        "selectorProcessor"           => "Selectors/_processSelectProduct/select",
        "itemSwapper"                 => "Selectors/_processSelectProduct/multiSelect",
        "swappedKeys"                 => array("pihakID", "pihakName"),
        "editHandlerMethod"           => "select",
        "pihakModel"                  => "MdlCustomer",
        "pihakCaller"                 => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                  => "customer",
        "pihakProcessor"              => "Selectors/_processPihak/select",
        "shortHistoryFields"          => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "customers_nama"  => "customer",
            "nomer_top"       => "SO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
        ),
        "selectorFields"              => array("id", "nama", "satuan"),
        "pihakFields"                 => array("id", "nama"),
        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),

            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            4 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            5 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"   => "nama",
            "code"   => "kode",
            "label"  => "label",
            "satuan" => "satuan",
            "ppn"    => "harga*(10/100)",


            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            //            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                "harga" => "price",
                "disc"  => "disc",
                "ppn"   => "VAT",
            ),
            2 => array(
                "stok"  => "stok",
                "harga" => "price",
                "ppn"   => "VAT",
            ),

            3 => array(
                //                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                "harga" => "price",
                "ppn"   => "VAT",
            ),
        ),
        "shoppingCartEditableFields"  => array(
            1 => array(
                "jml",
                "produk_ord_jml",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),

            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartAmountValue"     => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",

            3 => "jml",
            4 => "jml",
            5 => "jml*(harga+ppn)",
        ),
        //        "extTool" => array(
        //            "label" => "disc calculator",
        //            "url" => "/debug/tools/c.php",
        //            "sentField" => "harga",
        //            "sentParam" => "items",
        //            "gotParam" => "items",
        //            "gotField" => "harga",
        //            "externSrc" => "pihakID",
        //            "backUrl" => "Selectors/_processSelectProduct/updateValues",
        //        ),

        "receiptElements"  => array(
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "customer details",
                "mdlName"     => "MdlCustomer",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "billingDetails"  => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "billing details",
                "mdlName"     => "MdlCustomerAddress",
                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "alias",
                "usedFields"  => array(
                    "alias"     => "ATTN",
                    "alamat"    => "address",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi"  => "propinsi",

                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "delivery details",
                "mdlName"     => "MdlCustomerAddress",
                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "alias",
                "usedFields"  => array(
                    "alias"     => "ATTN",
                    "alamat"    => "address",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi"  => "propinsi",

                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),

            "tos"          => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "term of shipment",
                "mdlName"     => "MdlTos",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "description" => "",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "shippingDate" => array(
                "elementType"  => "dataField",
                "inputType"    => "combo",
                "label"        => "shipping date",
                "inputType"    => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints"   => array(1, 2, 3, 4, 5),
            ),
            "capacity"     => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "capacity",
                "mdlName"     => "MdlCapacity",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            //            "inWords",
            "dueDate"      => array(
                "elementType"  => "dataField",
                "label"        => "due date",
                "inputType"    => "date",
                "defaultValue" => $date->format('Y-m-d'),
                "editPoints"   => array(1, 2, 3, 4, 5),
            ),


            "paymentMethod" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "payment method",
                "mdlName"     => "MdlPaymentMethod",
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "",
                ),
                "editPoints"  => array(1,),
            ),

        ),
        "relativeElements" => array(
            "paymentMethod"  => array(
                "cash"       => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
                "cia"        => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
                "credit"     => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "term of payment",
                        "mdlName"     => "MdlTop",
                        "mdlFilter"   => array(),
                        "key"         => "kode",
                        "labelSrc"    => "nama",
                        "description" => "",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
                "debit_card" => array(
                    "debit_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "debit account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "name",
                        "usedFields"  => array(
                            "name" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                    "cash_account"  => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),

                "credit_card" => array(
                    "credit_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "credit account",
                        "mdlName"     => "MdlCreditCard",
                        "key"         => "id",
                        "labelSrc"    => "name",
                        "usedFields"  => array(
                            "name" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                    "cash_account"   => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
            ),
            "credit_account" => array(
                "visa_card"   => array(
                    "card_number"  => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "card number",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "valid trough",
                        "inputType"    => "date",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "card_name"    => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "name on card",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),


                ),
                "master_card" => array(
                    "card_number"  => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "card number",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "valid trough",
                        "inputType"    => "date",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "card_name"    => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "name on card",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                ),

            ),

        ),
        "relativeOptions"  => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label"        => "open discount",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett2*50/100",
                        "auth"         => array(
                            "groupID" => "admin"
                        ),
                        "addPoints"    => array(1, 2),
                    ),
                    "dp"       => array(
                        "label"        => "down payment",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett2*50/100",
                        "auth"         => array(
                            "groupID" => "finance"
                        ),
                        "addPoints"    => array(1,),
                    ),
                ),
                "cash"   => array(
                    "discount" => array(
                        "label"        => "open discount",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett2*50/100",
                        "auth"         => array(
                            "groupID" => "admin"
                        ),
                        "addPoints"    => array(2),
                    ),

                ),
                "cia"    => array(
                    "nilai_cia" => array(
                        "label"        => "cash amount",
                        "defaultValue" => "nett2",
                        "minValue"     => "nett2",
                        "maxValue"     => "nett2",
                        "auth"         => array(
                            "groupID" => "finance"
                        ),
                        "addPoints"    => array(1,),
                    ),
                    "discount"  => array(
                        "label"        => "open discount",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett2*50/100",
                        "auth"         => array(
                            "groupID" => "admin"
                        ),
                        "addPoints"    => array(1, 2),
                    ),

                ),

            ),
        ),
        "requestCode"      => array(
            "masterCode"       => "581",
            "stateCode"        => "581r",
            "stepNumber"       => "1",
            "allowMultiSelect" => false,
        ),

        "pairMakers"        => array(
            2 => array(
                "stokProduk" => array(
                    "helperName"   => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "pairInjectors"     => array(
            2 => array(
                "stokProduk" => array(
                    "items"      => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "validationRules"   => array(
            "items" => array(
                "target" => "stok",
                "source" => "jml",
            ),
        ),
        "connectedDiscount" => array(
            "enabled"         => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource"   => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
    ),
    //  config return penjualan
    "982"  => array(
        "icon"                       => "fa fa-rotate-left",
        "label"                      => "sales return",
        "place"                      => "branch",
        "steps"                      => array(
            1 => array(
                "label"        => "return request",
                "actionLabel"  => "make return request",
                "source"       => "",
                "target"       => "982r",
                "userGroup"    => "o_seller",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),

            2 => array(
                "label"        => "return authorization",
                "actionLabel"  => "approve return request",
                "source"       => "982r",
                "target"       => "982g",
                "userGroup"    => "o_seller_spv",
                "stateLabel"   => "approved",
                "stateColor"   => "#ff7700",
                "stateCaption" => "approved by",
            ),
            3 => array(
                "label"        => "goods received note",
                "actionLabel"  => "receive & make GRN",
                "source"       => "982g",
                "target"       => "982",
                "userGroup"    => "o_seller_spv",
                "stateLabel"   => "GRN made",
                "stateColor"   => "#ff7700",
                "stateCaption" => "received by",
            ),
        ),
        "template"                   => "application/template/transaksi.html",
        "selectorModel"              => "MdlNotaItem",
        "selectorSrcModel"           => "MdlNotaItem",
        "selectedPrice"              => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => "hpp",
        ),
        "lockerCheck"                => array(
            "enabled"      => false,
            "mdlName"      => "MdlLockerStock",
            "jenis"        => "produk",
            "jenis_locker" => "stock",
        ),
        "selectorFilters"            => array(
            "returned=.0",
            "jenis=.582spd",
            "customers_id=pihakID",
        ),
        "selectorCaller"             => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"              => "item",
        "selectorParamFields"        => array(
            "id"   => "id",
            "nama" => "nomer",
            //            "satuan" => "satuan",
            //            "jumlah"=>"jumlah",
        ),
        "selectorViewedFields"       => array(
            "nomer", "dtime",
        ),
        "selectorProcessor"          => "Selectors/_processSelectNotaItem/select",
        "editHandlerMethod"          => "edit",
        "pihakModel"                 => "MdlCustomer",
        "pihakCaller"                => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"                 => "customer",
        "pihakMainValueSrc"          => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"             => "Selectors/_processPihak/select",
        "shortHistoryFields"         => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields"       => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"             => array("id", "nama", "satuan"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "jual",
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"          => "produk_nama",
            "code"          => "produk_kode",
            "label"         => "produk_label",
            "satuan"        => "satuan",
            //"berat"         => "berat",
            //          "lebar"         => "lebar",
            //        "panjang"       => "panjang",
            //      "tinggi"        => "tinggi",
            //    "volume"        => "volume",
            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama"   => "item name",
                //            "avail"  => "current stock",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                //            "avail"  => "current stock",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            3 => array(
                "nama"   => "item name",
                //            "avail"  => "current stock",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "harga" => "Price",
                "ppn"   => "VAT",
                //            "avail" => "current stock",
            ),
            2 => array(
                "harga" => "Price",
                "ppn"   => "VAT",
                //            "avail" => "current stock",
            ),
            3 => array(
                "harga" => "Price",
                "ppn"   => "VAT",
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
        "shoppingCartAmountValue"    => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
            3 => "jml*(harga+ppn)",
        ),

        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "customer ID",
            "pihakName" => "customer name",
        ),
        "applets"                     => array(
            //            "alamat_kirim" => array(
            //                "label" => "alamat kirim",
            //                "mdlName" => "MdlSupplierAddress",
            ////                "mdlFilter" => array("extern_id=pihakID"),
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "alias",
            //                "description" => "alamat+kelurahan+kecamatan+kabupaten+propinsi+kodepos",
            //            ),
            //            "tos" => array(
            //                "label" => "term of shipment",
            //                "mdlName" => "MdlTos",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
            //            "capacity" => array(
            //                "label" => "capacity",
            //                "mdlName" => "MdlCapacity",
            //                "mdlFilter" => array(),
            //                "key" => "id",
            //                "labelSrc" => "nama",
            //                "description" => "",
            //            ),
        ),
        "availPayments"               => array(
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
                "label"     => "inherit",
                "valueGate" => "nilai_inherit",
                "valueSrc"  => "grand_total",
            ),
        ),
        "referenceFields"             => array(
            "referenceID"    => "transaksi_id",
            "referenceJenis" => "jenis",
            "referenceNomer" => "nomer",
            "paymentMethod"  => "pembayaran",
        ),
        "referenceJenisTr"            => "582",
    ),

    //export
    "382"  => array(
        "icon"                 => "fa fa-opencart",
        "label"                => "export",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "sales pre order",
                "actionLabel"  => "make order",
                "source"       => "",
                "target"       => "382spo",
                "userGroup"    => "c_export",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "order authorization",
                "actionLabel"  => "approve order",
                "source"       => "382spo",
                "target"       => "382so",
                "userGroup"    => "c_export_spv",
                "stateLabel"   => "ordered",
                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
                //				"optStateLabel"    => "pending disc. approval",
                "stateColor"   => "#ff7700",
                "stateCaption" => "approved by",
                "allowEdit"    => true,
            ),

            3 => array(
                "label"        => "pre packing",
                "actionLabel"  => "process packing",
                "source"       => "382so",
                "target"       => "382pkd", // packed
                "userGroup"    => "c_export_spv",
                "stateLabel"   => "packed",
                "stateColor"   => "#009900",
                "stateCaption" => "packed by",
                "allowEdit"    => true,
            ),
            4 => array(
                "label"        => "shipment",
                "actionLabel"  => "process shipment",
                "source"       => "382pkd",
                "target"       => "382spd", // shipped
                "userGroup"    => "c_export_spv",
                "stateLabel"   => "shipped",
                "stateColor"   => "#009900",
                "stateCaption" => "shipped by",

            ),
            5 => array(
                "label"        => "invoicing",
                "actionLabel"  => "create invoice",
                "source"       => "382spd",
                "target"       => "382", // invoice
                "userGroup"    => "c_export_spv",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "completed by",
                "allowJoin"    => true,
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlProduk",
        "selectorSrcModel"     => "MdlProduk",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("jual", "ppv", "disc"),
            "key_label" => array(
                "jual" => "harga",
                "ppv"  => "ppv",
                "disc" => "disc",
            ),
            "mainSrc"   => "jual",
        ),
        "lockerCheck"          => array(),
        "selectorFilters"      => array(
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "id",
            "nama"   => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama", "satuan",// "jumlah"
        ),
        "selectorProcessor"    => "Selectors/_processSelectProduct/select",
        "itemSwapper"          => "Selectors/_processSelectProduct/multiSelect",
        "swappedKeys"          => array("pihakID", "pihakName"),
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlCustomer",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "customer",

        "pihakProcessor"              => "Selectors/_processPihak/select",
        "shortHistoryFields"          => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "customers_nama"  => "customer",
            "nomer_top"       => "SO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
        ),
        "selectorFields"              => array("id", "nama", "satuan"),
        "pihakFields"                 => array("id", "nama"),
        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),

            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            4 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            5 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"   => "nama",
            "code"   => "kode",
            "label"  => "label",
            "satuan" => "satuan",
            "ppn"    => "harga*(10/100)",


            "berat_gross"   => "berat_gross",
            "lebar_gross"   => "lebar_gross",
            "panjang_gross" => "panjang_gross",
            "tinggi_gross"  => "tinggi_gross",
            //            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                //                "harga" => "price",
                "valas_nilai"     => "price",
                "dics_valas"      => "disc",
                "sub_nett2_valas" => "sub-total"
                //                "ppn" => "VAT",
            ),
            2 => array(
                "stok"        => "stok",
                "valas_nilai" => "price",
                //                "sub_nett2_valas" => "sub-total"
            ),

            3 => array(
                //                "stok" => "stok",
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            4 => array(
                //                "harga" => "price",
                //                "ppn"   => "VAT",
            ),
            5 => array(
                "valas_nilai" => "price",
                "disc"        => "disc",
                //                "sub_nett2_valas" => "sub-total"

            ),
        ),
        "shoppingCartEditableFields"  => array(
            1 => array(
                "jml",
                "produk_ord_jml",
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),

            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml"         => "quantity",
            "harga"       => "price",
            "valas_nilai" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartAmountValue"     => array(
            1 => "jml*(harga+ppn)",
            2 => "jml*(harga+ppn)",
            3 => "jml",
            4 => "jml",
            5 => "jml*(harga+ppn)",
        ),
        "shoppingCartHideSubamount"   => true,
        "receiptElements"             => array(

            "valasDetails"    => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "valas",
                "mdlName"     => "MdlCurrency",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "description" => "",
                "usedFields"  => array(
                    "nama"     => "currency",
                    "exchange" => "exchange rate",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "customerDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "combo",
                "label"       => "customer details",
                "mdlName"     => "MdlCustomer",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    //                    "npwp" => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "billingDetails"  => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "billing details",
                "mdlName"     => "MdlCustomerAddress",
                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "alias",
                "usedFields"  => array(
                    "alias"     => "ATTN",
                    "alamat"    => "address",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi"  => "propinsi",

                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "deliveryDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "delivery details",
                "mdlName"     => "MdlCustomerAddress",
                "mdlFilter"   => array("extern_id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "alias",
                "usedFields"  => array(
                    "alias"     => "ATTN",
                    "alamat"    => "address",
                    "kecamatan" => "kecamatan",
                    "kabupaten" => "kabupaten",
                    "propinsi"  => "propinsi",

                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            //            "paymentMethod" => array(
            //                "elementType" => "dataModel",
            //                "inputType" => "radio",
            //                "label" => "payment method",
            //                "mdlName" => "MdlPaymentMethod3",
            //                "key" => "id",
            //                "labelSrc" => "name",
            //                "usedFields" => array(
            //                    "name" => "",
            //                ),
            //                "editPoints" => array(1,),
            //            ),
            "tos"             => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "term of shipment",
                "mdlName"     => "MdlTos",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "description" => "",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "shippingDate"    => array(
                "elementType"  => "dataField",
                "inputType"    => "combo",
                "label"        => "shipping date",
                "inputType"    => "date",
                "defaultValue" => date("Y-m-d"),
                "editPoints"   => array(1, 2, 3, 4, 5),
            ),
            "capacity"        => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "capacity",
                "mdlName"     => "MdlCapacity",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            //            "inWords",
            //            "dueDate" => array(
            //                "elementType" => "dataField",
            //                "label" => "due date",
            //                "inputType" => "date",
            //                "defaultValue" => $date->format('Y-m-d'),
            //                "editPoints" => array(1, 2, 3, 4, 5),
            //            ),

        ),
        "relativeElements"            => array(
            "paymentMethod"  => array(
                "cash"       => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
                "cia"        => array(
                    "cash_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
                "credit"     => array(
                    "top" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "term of payment",
                        "mdlName"     => "MdlTop",
                        "mdlFilter"   => array(),
                        "key"         => "kode",
                        "labelSrc"    => "nama",
                        "description" => "",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
                "debit_card" => array(
                    "debit_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "debit account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "name",
                        "usedFields"  => array(
                            "name" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                    "cash_account"  => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),

                "credit_card" => array(
                    "credit_account" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "credit account",
                        "mdlName"     => "MdlCreditCard",
                        "key"         => "id",
                        "labelSrc"    => "name",
                        "usedFields"  => array(
                            "name" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                    "cash_account"   => array(
                        "elementType" => "dataModel",
                        "inputType"   => "combo",
                        "label"       => "bank account",
                        "mdlName"     => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),

            ),
            "credit_account" => array(
                "visa_card"   => array(
                    "card_number"  => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "card number",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "valid trough",
                        "inputType"    => "date",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "card_name"    => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "name on card",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),


                ),
                "master_card" => array(
                    "card_number"  => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "card number",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "valid trough",
                        "inputType"    => "date",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "card_name"    => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "name on card",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                ),

            ),

        ),
        "relativeOptions"             => array(
            "paymentMethod" => array(
                "credit" => array(
                    "discount" => array(
                        "label"        => "open discount",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett2*50/100",
                        "auth"         => array(
                            "groupID" => "admin"
                        ),
                        "addPoints"    => array(1, 2),
                    ),
                    "dp"       => array(
                        "label"        => "down payment",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett2*50/100",
                        "auth"         => array(
                            "groupID" => "finance"
                        ),
                        "addPoints"    => array(1,),
                    ),
                ),
                "cash"   => array(
                    "discount" => array(
                        "label"        => "open discount",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett2*50/100",
                        "auth"         => array(
                            "groupID" => "admin"
                        ),
                        "addPoints"    => array(2),
                    ),

                ),
                "cia"    => array(
                    "nilai_cia" => array(
                        "label"        => "cash amount",
                        "defaultValue" => "nett2",
                        "minValue"     => "nett2",
                        "maxValue"     => "nett2",
                        "auth"         => array(
                            "groupID" => "finance"
                        ),
                        "addPoints"    => array(1,),
                    ),
                    "discount"  => array(
                        "label"        => "open discount",
                        "defaultValue" => ".0",
                        "maxValue"     => "nett2*50/100",
                        "auth"         => array(
                            "groupID" => "admin"
                        ),
                        "addPoints"    => array(1, 2),
                    ),

                ),

            ),
        ),
        "requestCode"                 => array(
            "masterCode"       => "382",
            "stateCode"        => "382r",
            "stepNumber"       => "1",
            "allowMultiSelect" => false,
        ),

        "pairMakers"        => array(
            2 => array(
                "stokProduk" => array(
                    "helperName"   => "he_cek_stock_produk",
                    "functionName" => "cekStockProduk",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "pairInjectors"     => array(
            2 => array(
                "stokProduk" => array(
                    "items"      => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "validationRules"   => array(
            "items" => array(
                "target" => "stok",
                "source" => "jml",
            ),
        ),
        "connectedDiscount" => array(
            "enabled"         => true,
            "mdlNameRelation" => "MdlConnectedDiscount",
            "mdlNameSource"   => "MdlAddDiscount",
            //            "jenis" => "produk",
            //            "jenis_locker" => "stock",
        ),
    ),

    //penerimaan piutang valas
    "1749" => array(
        "icon"                 => "fa fa-money",
        "label"                => "A/R receivement export",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "A/R receivement",
                "actionLabel"  => "process receivement",
                "source"       => "",
                "target"       => "1749",
                "userGroup"    => "c_export",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "template"             => "application/template/transaksi_payment.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.382",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlCustomer",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "customer",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"           => "nomer",
            "tagihan"        => "tagihan",
            "terbayar"       => "terbayar",
            "sisa"           => "sisa",
            "tagihan_valas"  => "tagihan_valas",
            "terbayar_valas" => "terbayar_valas",
            "sisa_valas"     => "sisa_valas",
        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa_valas" => "sisa",
            ),
        ),
        "shoppingCartEditableFields"   => array(),
        "shoppingCartAmountValue"      => array(
            1 => "sisa_valas",

        ),
        "shoppingCartAvoidRemove"      => true,
        "tagihanSrc"                   => "harus_bayar",
        "receiptElements"              => array(
            "customerDetails"    => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "customer details",
                "mdlName"     => "MdlCustomer",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3, 4),
            ),
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "cash account",
                "mdlName"     => "MdlBankAccount_in",
                "mdlFilter"   => array("currency_id=valasID"),
                "key"         => "id",
                "labelSrc"    => "alias",
                "usedFields"  => array(
                    "nama"     => "account",
                    "currency" => "currency",
                ),
                "editPoints"  => array(1,),
            ),
            "paymentMethod"      => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "payment method",
                "mdlName"     => "MdlPaymentMethod3",
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "",
                ),
                "editPoints"  => array(1,),
            ),
            "dummyElement"       => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "relativeElements"             => array(
            "paymentMethod"  => array(
                "lc" => array(
                    "lc_account" => array(
                        "elementType" => "dataField",
                        "inputType"   => "text",
                        "label"       => "LC Number",
                        //                        "mdlName" => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
                "tt" => array(
                    "tt_account" => array(
                        "elementType" => "dataField",
                        "inputType"   => "text",
                        "label"       => "TT Notes",
                        //                        "mdlName" => "MdlBankAccount",
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),

            ),
            "credit_account" => array(
                "visa_card"   => array(
                    "card_number"  => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "card number",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "valid trough",
                        "inputType"    => "date",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "card_name"    => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "name on card",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),


                ),
                "master_card" => array(
                    "card_number"  => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "card number",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "valid_period" => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "valid trough",
                        "inputType"    => "date",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                    "card_name"    => array(
                        "elementType"  => "dataField",
                        "inputType"    => "combo",
                        "label"        => "name on card",
                        "inputType"    => "text",
                        "defaultValue" => "",
                        "editPoints"   => array(1,),
                    ),
                ),

            ),

        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows"               => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount"        => array(
                        "label"        => "total amount",
                        "defaultValue" => "sisa_valas",
                        "maxValue"     => "sisa_valas",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_amount" => array(
                        "label"        => "credit amount",
                        "defaultValue" => "creditAmount",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "credit_note"   => array(
                        "label"        => "credit note",
                        "defaultValue" => "creditValue",
                        //                        "keyupAction" => "",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    "harus_bayar"   => array(
                        "label"        => "amount remains to pay",
                        "defaultValue" => "(sisa_valas-creditAmount-creditValue)",
                        "maxValue"     => "(sisa_valas-creditAmount-creditValue)",
                        "minValue"     => "(sisa_valas-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled'       => "disabled",
                        "addPoints"      => array(1,),

                    ),
                    "nilai_entry"   => array(
                        "label"        => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction"  => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;} 
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                ),
            ),
        ),
        "shoppingCartReferenceFields"  => array(
            "nomer"          => "expense req. number",
            //            "nomer_top" => "receipt ref.",
            //            "refNum"    => "return ref.",
            "fulldate"       => "date",
            "valas_nama"     => "currency",
            "tagihan_valas"  => "expense amount",
            //            "refValue"  => "returned",
            "terbayar_valas" => "paid",
            //            "diskon"    => "discount",
            "sisa_valas"     => "remain",


        ),
    ),


    //penjualan valas
    "383"  => array(
        "icon"                 => "fa fa-opencart",
        "label"                => "valas exchange",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "valas exchange",
                "actionLabel"  => "make order",
                "source"       => "",
                "target"       => "383",
                "userGroup"    => "c_export",
                "stateLabel"   => "complete",
                "stateColor"   => "#dd3300",
                "stateCaption" => "-",
            ),
            //            2 => array(
            //                "label" => "valas exchange",
            //                "actionLabel" => "approve order",
            //                "source" => "383",
            //                "target" => "382so",
            //                "userGroup" => "c_export_spv",
            //                "stateLabel" => "ordered",
            //                //				"optTarget"        => "582spod", // sales pre-order diskon, ada DP/Cash in Advance (ke penerimaan uang)
            //                //				"optCriteriaField" => "total_diskon", // cek diskon bila lebih dari ketentuan maka 582spod, ada DP/Cash in Advance (ke penerimaan uang)
            //                //				"optStateLabel"    => "pending disc. approval",
            //                "stateColor" => "#ff7700",
            //                "stateCaption" => "approved by",
            //                "allowEdit" => true,
            //            ),
            //
            //            3 => array(
            //                "label" => "pre packing",
            //                "actionLabel" => "process packing",
            //                "source" => "382so",
            //                "target" => "382pkd", // packed
            //                "userGroup" => "c_export_spv",
            //                "stateLabel" => "packed",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "packed by",
            //                "allowEdit" => true,
            //            ),
            //            4 => array(
            //                "label" => "shipment",
            //                "actionLabel" => "process shipment",
            //                "source" => "382pkd",
            //                "target" => "382spd", // shipped
            //                "userGroup" => "c_export_spv",
            //                "stateLabel" => "shipped",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "shipped by",
            //
            //            ),
            //            5 => array(
            //                "label" => "invoicing",
            //                "actionLabel" => "create invoice",
            //                "source" => "382spd",
            //                "target" => "382", // invoice
            //                "userGroup" => "c_export_spv",
            //                "stateLabel" => "completed",
            //                "stateColor" => "#009900",
            //                "stateCaption" => "completed by",
            //                "allowJoin" => true,
            //            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlCurrency",
        "selectorSrcModel"     => "MdlCurrency",
        "selectedPrice"        => array(
            //            "model" => "MdlHargaProduk",
            //            "label" => array("jual", "ppv", "disc"),
            //            "key_label" => array(
            //                "jual" => "harga",
            //                "ppv" => "ppv",
            //                "disc" => "disc",
            //            ),
            //            "mainSrc" => "jual",
        ),
        "lockerCheck"          => array(),
        "selectorFilters"      => array(
            "id=currency_id",
            //            "cabang_id='1'", // mengambil dari $this->session->login(cabang_id) JANGAN LUPA DIGANTI YA..
            //            "jumlah>0",
            //            "state='active'",
        ),
        "pihakMainValueSrc"    => array("currency_id" => "currency_id"),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"     => "id",
            "nama"   => "nama",
            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            "satuan",
            // "jumlah"
        ),
        "selectorProcessor"    => "Selectors/_processSelectProduct/select",
        "itemSwapper"          => "Selectors/_processSelectProduct/multiSelect",
        "swappedKeys"          => array("pihakID", "pihakName"),
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlBankAccount_in",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "bank",
        "pihakFilters"         => array(//            "currency<>Rp"
        ),


        "pihakProcessor"              => "Selectors/_processPihak/select",
        "shortHistoryFields"          => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "customers_nama"  => "customer",
            "nomer_top"       => "SO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
        ),
        "selectorFields"              => array("id", "nama", "satuan"),
        "pihakFields"                 => array("id", "nama"),
        "shoppingCartFields"          => array(
            1 => array(
                "nama"   => "valas",
                "jml"    => "nilai valas",
                "satuan" => "satuan",
            ),
            2 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),

            3 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            4 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
            5 => array(
                "nama"   => "item name",
                "jml"    => "qty",
                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc"        => array(
            "nama"   => "nama",
            "kode"   => "kode",
            "label"  => "label",
            "satuan" => "satuan",
            "ppn"    => "harga*(10/100)",


            //            "berat_gross" => "berat_gross",
            //            "lebar_gross" => "lebar_gross",
            //            "panjang_gross" => "panjang_gross",
            //            "tinggi_gross" => "tinggi_gross",
            //            "volume_gross"  => "volume_gross",
        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                "harga"    => "price",
                "subtotal" => "sub-total"

            ),
            //            2 => array(
            //                "stok" => "stok",
            //                "valas_nilai" => "price",
            //                "sub_nett2_valas" => "sub-total"
            //            ),
            //
            //            3 => array(
            ////                "stok" => "stok",
            ////                "harga" => "price",
            ////                "ppn"   => "VAT",
            //            ),
            //            4 => array(
            ////                "harga" => "price",
            ////                "ppn"   => "VAT",
            //            ),
            //            5 => array(
            //                "valas_nilai" => "price",
            //                "disc" => "disc",
            //                "sub_nett2_valas" => "sub-total"
            //
            //            ),
        ),
        "shoppingCartEditableFields"  => array(
            1 => array(
                "jml",
                "produk_ord_jml",
                "harga"
            ),
            2 => array(
                "jml",
                "produk_ord_jml",
            ),

            3 => array(
                "jml",
                "produk_ord_jml",
            ),
            4 => array(
                "jml",
                "produk_ord_jml",
            ),
            5 => array(
                "jml",
                "produk_ord_jml",
            ),
        ),
        "shoppingCartFieldValidators" => array(
            "jml"   => "quantity",
            "harga" => "price",
            //            "valas_nilai" => "price",
        ),
        "shoppingCartRowValidators"   => array(
            "pihakID"   => "customer ID",
            "pihakName" => "customer name",
        ),
        "shoppingCartAmountValue"     => array(
            1 => "jml*harga",
            //            2 => "jml*(harga+ppn)",
            //            3 => "jml",
            //            4 => "jml",
            //            5 => "jml*(harga+ppn)",
        ),
        "shoppingCartHideSubamount"   => false,
        "receiptElements"             => array(
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "Moved to account",
                "mdlName"     => "MdlBankAccount_in",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "account",
                    "currency" => "currency",
                ),
                "editPoints"  => array(1,),
            ),

        ),
        "relativeElements"            => array(
            //            "paymentMethod" => array(
            //                "cash" => array(
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
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
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //                "credit" => array(
            //                    "top" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
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
            //                    ),
            //                ),
            //                "debit_card" => array(
            //                    "debit_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "debit account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "name",
            //                        "usedFields" => array(
            //                            "name" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //
            //                "credit_card" => array(
            //                    "credit_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "credit account",
            //                        "mdlName" => "MdlCreditCard",
            //                        "key" => "id",
            //                        "labelSrc" => "name",
            //                        "usedFields" => array(
            //                            "name" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "cash_account" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "combo",
            //                        "label" => "bank account",
            //                        "mdlName" => "MdlBankAccount",
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
            //            "credit_account" => array(
            //                "visa_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //
            //
            //                ),
            //                "master_card" => array(
            //                    "card_number" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "card number",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "valid_period" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "valid trough",
            //                        "inputType" => "date",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                    "card_name" => array(
            //                        "elementType" => "dataField",
            //                        "inputType" => "combo",
            //                        "label" => "name on card",
            //                        "inputType" => "text",
            //                        "defaultValue" => "",
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //
            //            ),

        ),
        "relativeOptions"             => array(
            //            "paymentMethod" => array(
            //                "credit" => array(
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "admin"
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //                    "dp" => array(
            //                        "label" => "down payment",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "finance"
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
            //                            "groupID" => "admin"
            //                        ),
            //                        "addPoints" => array(2),
            //                    ),
            //
            //                ),
            //                "cia" => array(
            //                    "nilai_cia" => array(
            //                        "label" => "cash amount",
            //                        "defaultValue" => "nett2",
            //                        "minValue" => "nett2",
            //                        "maxValue" => "nett2",
            //                        "auth" => array(
            //                            "groupID" => "finance"
            //                        ),
            //                        "addPoints" => array(1,),
            //                    ),
            //                    "discount" => array(
            //                        "label" => "open discount",
            //                        "defaultValue" => ".0",
            //                        "maxValue" => "nett2*50/100",
            //                        "auth" => array(
            //                            "groupID" => "admin"
            //                        ),
            //                        "addPoints" => array(1, 2),
            //                    ),
            //
            //                ),
            //
            //            ),
        ),
        "requestCode"                 => array(
            "masterCode"       => "382",
            "stateCode"        => "382r",
            "stepNumber"       => "1",
            "allowMultiSelect" => false,
        ),

        "pairMakers"        => array(
            1 => array(
                "stokValas" => array(
                    "helperName"   => "he_cek_stock_valas",
                    "functionName" => "cekStockValas",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "pairInjectors"     => array(
            2 => array(
                "stokValas" => array(
                    "items"      => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                    "out_detail" => array(
                        "targetKey"    => "id",
                        "targetColumn" => "stok",
                    ),
                ),
            ),
        ),
        "validationRules"   => array(
            "items" => array(
                "target" => "stok",
                "source" => "jml",
            ),
        ),
        "connectedDiscount" => array(),
    ),

    //  config pemindahan rekening kas
    "757"  => array(
        "icon"             => "fa fa-cube",
        "label"            => "cash balance interchange",
        "place"            => "center",
        "steps"            => array(
            1 => array(
                "label"        => "request balance interchange",
                "actionLabel"  => "make request",
                "source"       => "",
                "target"       => "757r",
                "userGroup"    => "c_holding",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"        => "authorization",
                "actionLabel"  => "approve request",
                "source"       => "757r",
                "target"       => "757",
                "userGroup"    => "c_holding",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "approved by",
            ),
        ),
        "template"         => "application/template/transaksi_nopihak.html",
        "selectorModel"    => "MdlBankAccountSaldo",
        "selectorSrcModel" => "MdlBankAccountSaldo",
        "selectedPrice"    => array(),
        "lockerCheck"      => array(),
        "accountCheck"     => array(
            "enabled"   => true,
            "mdlName"   => "MdlBankAccountSaldo",
            "mdlFilter" => array(
                "bank.cabang_id=placeID",
                "bank.id=rekID",
            ),
        ),

        "selectorFilters"      => array(
            "bank.cabang_id=placeID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item rekening",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nama",
            //            "jumlah" => "debet",
        ),
        "selectorViewedFields" => array(
            //            "id",
            "nama",
            "debet",
        ),
        "selectorProcessor"    => "Selectors/_processSelectRekening/select",
        "editHandlerMethod"    => "select",

        "pihakModel"     => "MdlGudang",
        "pihakCaller"    => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"     => "gudang",
        "pihakFilters"   => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor" => "Selectors/_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label"  => "activity",
            "dtime"        => "date",
            "cabang2_nama" => "recipient",
            "nomer"        => "receipt number",
            "oleh_nama"    => "person",
        ),
        "selectorFields"     => array("id", "nama"),
        "pihakFields"        => array("id", "nama"),
        "shoppingCart"       => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"          => array(
            1 => array(
                "nama" => "source account",

            ),
            2 => array(
                "nama" => "source account",

            ),
        ),
        "shoppingCartFields2"         => array(
            1 => array(
                "nama" => "target account",

            ),
            2 => array(
                "nama" => "target account",

            ),
        ),
        "shoppingCartFieldSrc"        => array(
            "nama" => "nama",

        ),
        "shoppingCartNumFields"       => array(
            1 => array(
                "harga" => "transfer amount",
                //                "jml" => "qty",
            ),
            2 => array(
                "harga" => "transfer amount",
                //                "jml" => "qty",
            ),
        ),
        "shoppingCartNumFields2"      => array(
            1 => array(
                "harga" => "receiving amount",
                //                "jml" => "qty",
            ),
            2 => array(
                "harga" => "receiving amount",
                //                "jml" => "qty",
            ),
        ),
        "shoppingCartNoteEnabled"     => false,
        //        "shoppingCartPairedItemRecorder" => "recordPaireditem",
        //        "shoppingCartPairedItem" => array(
        //            "enabled" => true,
        //            "mdlName" => "MdlBankAccount",
        //            "mdlFilter" => array(
        //                "cabang_id=placeID",
        //                "id<>id"
        //            ),
        //            "srcKey" => "id",
        //            "srcLabel" => array("nama"),
        //        ),
        "shoppingCartFieldValidators" => array(
            "harga" => "source value",
        ),

        //        "shoppingCartPairedSelectedItem" => array(
        //            "enabled" => true,
        //            "mdlName" => "ComRekeningPembantuKas",
        //            "srcKey" => "extern_id",
        //            "srcLabel" => array("nama"),
        //            "mdlFilter" => array(
        //                "cabang_id=placeID",
        //                "periode=forever",
        //                "rekening=kas",
        //                ),
        //        ),

        "receiptElements" => array(
            "cash_account_source" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "account source",
                "mdlName"     => "MdlBankAccountSaldo",
                "mdlFilter"   => array(
                    "bank.cabang_id=placeID",
                    "bank.id=rekID",
                ),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account number",
                    "alias" => "holder alias",
                    "debet" => "balance",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => false,
            ),
            "cash_account_target" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "account target",
                "mdlName"     => "MdlBankAccount",
                "mdlFilter"   => array(
                    "bank.cabang_id=placeID",
                    "bank.id<>rekID",
                ),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account number",
                    "alias" => "holder alias",
                    "debet" => "balance",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => false,
            ),
        ),

        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
            ),
            2 => array(
                "harga",
            ),
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "cloner"                     => array(
            "srcGateName" => "items",
            "cloneLabel"  => array("harga"),
        ),
        "mainCloner"                 => array(
            "items"  => array(
                "rekID"   => "id",
                "rekName" => "nama",
            ),
            "items2" => array(
                "rek2ID"   => "id",
                "rek2Name" => "nama",
            ),
        ),
    ),
    //  config pettycash
    "671"  => array(
        "icon"                 => "fa fa-cart-arrow-down",
        "label"                => "pettycash",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "pettycash",
                "actionLabel"  => "save",
                "source"       => "",
                "target"       => "671r",
                "userGroup"    => "o_kasir",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"          => "pettycash. authorization",
                "actionLabel"    => "approve request",
                "source"         => "671r",
                "target"         => "671",
                "userGroup"      => "o_kasir",
                "stateLabel"     => "make claim",
                "stateColor"     => "#ff7700",
                "stateCaption"   => "approved by",
                "allowEdit"      => true,
                "allowIncrement" => true,
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlPettycash",
        "selectorSrcModel"     => "MdlPettycash",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"          => array(),
        "selectorFilters"      => array(//            "suppliers_id=pihakID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "pettycash",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor"    => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"    => "select",

        "pihakModel"        => "MdlCabang",
        "pihakCaller"       => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"        => "cabang",
        "pihakFilters"      => array(//            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"    => "Selectors/_processPihak/select",

        "shortHistoryFields"         => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "suppliers_nama"  => "vendor",
            "nomer_top"       => "PO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields"             => array("id", "nama"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"      => "nama",
            "label"     => "label",
            "reference" => "reference",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama"      => "item name",
                "jml"       => "qty",
                "reference" => "reference",
            ),
            2 => array(
                "nama"      => "item name",
                "jml"       => "qty",
                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
        ),
        "shoppingCartNoteEnabled"    => true,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators"   => array(),

        "receiptElements"  => array(
            "pettycashSaldo" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "pettycash amount",
                "pairedModel" => array(
                    "mdlName"    => "ComLockerValue",
                    "mdlMethod"  => "fetchBalances",
                    "mdlFilter"  => array(
                        "cabang_id" => "placeID",
                    ),
                    "key"        => "produk_id",
                    "rekening"   => "pettycash",
                    "fieldID"    => "nilai",
                    "fieldLabel" => "saldo",
                ),
                "mdlName"     => "MdlPettycashAccount",
                "mdlFilter"   => array(),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account",
                    "saldo" => "balance",
                ),
                "editPoints"  => array(1,),
                "noValidate"  => true,
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions"  => array(),

        "connectTo" => "672",
    ),
    "672"  => array(
        "icon"                 => "fa fa-cart-arrow-down",
        "label"                => "pettycash (p)",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "pettycash",
                "actionLabel"  => "save",
                "source"       => "",
                "target"       => "672r",
                "userGroup"    => "sys",
                "stateLabel"   => "pending approval",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label"          => "pettycash. authorization",
                "actionLabel"    => "approve claim pettycash",
                "source"         => "672r",
                "target"         => "672",
                "userGroup"      => "c_holding",
                "stateLabel"     => "make claim",
                "stateColor"     => "#ff7700",
                "stateCaption"   => "approved by",
                "allowEdit"      => true,
                "allowIncrement" => true,
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlPettycash",
        "selectorSrcModel"     => "MdlPettycash",
        "selectedPrice"        => array(
            "model"     => "MdlHargaProduk",
            "label"     => array("hpp"),
            "key_label" => array(
                "hpp" => "harga",
            ),
            "mainSrc"   => "hpp",
        ),
        "lockerCheck"          => array(),
        "selectorFilters"      => array(//            "suppliers_id=pihakID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "pettycash",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nama",
            //            "satuan" => "satuan",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "satuan",
        ),
        "selectorProcessor"    => "Selectors/_processSelectProduct/select",
        "editHandlerMethod"    => "select",

        "pihakModel"        => "MdlCabang",
        "pihakCaller"       => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"        => "cabang",
        "pihakFilters"      => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"    => "Selectors/_processPihak/select",

        "shortHistoryFields"         => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "suppliers_nama"  => "vendor",
            "nomer_top"       => "PO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields"             => array("id", "nama"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"      => "nama",
            "label"     => "label",
            "reference" => "reference",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
                //                "reference" => "reference",
            ),
            2 => array(
                "nama" => "item name",
                "jml"  => "qty",
                //                "reference" => "reference",
            ),
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "harga" => "Price",
            ),
            2 => array(
                "harga" => "Price",
            ),
        ),
        "shoppingCartNoteEnabled"    => true,
        "shoppingCartEditableFields" => array(
            1 => array(
                "harga",
                "jml",
                "reference",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),

        "shoppingCartFieldValidators" => array(
            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators"   => array(),

        "receiptElements"  => array(),
        "relativeElements" => array(),
        "relativeOptions"  => array(),
    ),

    "771" => array(
        "icon"                 => "fa fa-money",
        "label"                => "Refill Pettycash",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "refill pettycash",
                "actionLabel"  => "process refill",
                "source"       => "",
                "target"       => "771",
                "userGroup"    => "c_finance",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "-",
            ),
        ),
        "template"             => "application/template/transaksi_payment.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.671",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"  => "Selectors/_processSelectNota/select",
        "editHandlerMethod"  => "select",
        "pihakModel"         => "MdlCustomer",
        "pihakCaller"        => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"         => "customer",
        "pihakProcessor"     => "Selectors/_processPihak/select",
        "shortHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"     => array("id", "nama", "satuan"),
        "pihakFields"        => array("id", "nama"),
        "shoppingCart"       => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"         => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"       => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",

        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                "sisa" => "sisa",
            ),
        ),
        "shoppingCartEditableFields" => array(),
        "shoppingCartAmountValue"    => array(
            1 => "sisa",
        ),
        "shoppingCartAvoidRemove"    => true,
        "tagihanSrc"                 => "harus_bayar",
        "receiptElements"            => array(
            "paymentMethod_cash" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "cash account",
                "pairedModel" => array(
                    "mdlName"    => "ComRekeningPembantuKas",
                    "mdlMethod"  => "fetchBalances",
                    "mdlFilter"  => array(
                        "cabang_id" => "placeID",
                    ),
                    "key"        => "extern_id",
                    "rekening"   => "kas",
                    "fieldID"    => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName"     => "MdlBankAccount",
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account",
                    "saldo" => "balance",
                ),
                "editPoints"  => array(1,),
            ),
        ),
    ),
    "770" => array(
        "icon"                 => "fa fa-cart-arrow-down",
        "label"                => "penambahan plafon pettycash",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "addition of pettycash",
                "actionLabel"  => "make plafon pettycash",
                "source"       => "",
                "target"       => "770",
                "userGroup"    => "c_holding",
                "stateLabel"   => "complete",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
        ),
        //        "template" => "application/template/transaksi.html",
        "template"             => "application/template/transaksi_nopihak.html",
        "selectorModel"        => "MdlCabang",
        "selectorSrcModel"     => "MdlCabang",
        "selectedPrice"        => array(),
        "lockerCheck"          => array(),
        "selectorFilters"      => array(),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "cabang",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorProcessor"    => "Selectors/_processSelectPlafonPettycash/select",
        "editHandlerMethod"    => "select",

        "pihakModel"        => "MdlCabang",
        "pihakCaller"       => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"        => "cabang",
        "pihakFilters"      => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"    => "Selectors/_processPihak/select",

        "shortHistoryFields"         => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "suppliers_nama"  => "vendor",
            "nomer_top"       => "PO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields"             => array("id", "nama"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"       => "nama",
            "label"      => "label",
            "reference"  => "reference",
            "lastPlafon" => "lastPlafon",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
                //                "lastPlafon" => "lastPlafon",
                //                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "nama" => "item name",
                "jml"  => "qty",
                //                "lastPlafon" => "lastPlafon",
                //                "newPlafon" => "newPlafon",
            ),
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                //                "harga" => "Price",
                "lastPlafon" => "lastPlafon",
                "newPlafon"  => "additional",
            ),
            2 => array(
                //                "harga" => "Price",
                "lastPlafon" => "lastPlafon",
                "newPlafon"  => "additional",
            ),
        ),
        "shoppingCartNoteEnabled"    => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                //                "harga",
                "jml",
                "reference",
                "newPlafon",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue"    => array(
            //            1 => "(newPlafon+lastPlafon)",
            //            2 => "(newPlafon+lastPlafon)",
        ),

        "shoppingCartFieldValidators" => array(
            //            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators"   => array(),

        "receiptElements"  => array(
            "paymentMethod_cash"      => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "cash account",
                "pairedModel" => array(
                    "mdlName"    => "ComRekeningPembantuKas",
                    "mdlMethod"  => "fetchBalances",
                    "mdlFilter"  => array(
                        "cabang_id" => "placeID",
                    ),
                    "key"        => "extern_id",
                    "rekening"   => "kas",
                    "fieldID"    => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName"     => "MdlBankAccount",
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account",
                    "saldo" => "saldo",
                ),
                "editPoints"  => array(1,),
            ),
            "paymentMethod_pettycash" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "pettycash account",
                "mdlName"     => "MdlPettycashAccount",

                "key"        => "id",
                "labelSrc"   => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions"  => array(),
        "cloner"           => array(
            "srcGateName" => "items",
            "cloneLabel"  => array(
                "id",
                "nama",
            ),
        ),
        "mainCloner"       => array(
            "items" => array(
                "cabang2ID"   => "id",
                "cabang2Name" => "nama",
            ),
            //            "items2" => array(
            //                "rek2ID" => "id",
            //                "rek2Name" => "nama",
            //            ),
        ),
    ),
    "970" => array(
        "icon"                 => "fa fa-cart-arrow-down",
        "label"                => "pengurangan plafon pettycash",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "reduction of pettycash",
                "actionLabel"  => "make plafon pettycash",
                "source"       => "",
                "target"       => "970",
                "userGroup"    => "c_holding",
                "stateLabel"   => "complete",
                "stateColor"   => "#dd3300",
                "stateCaption" => "prepared by",
            ),
        ),
        //        "template" => "application/template/transaksi.html",
        "template"             => "application/template/transaksi_nopihak.html",
        "selectorModel"        => "MdlCabang",
        "selectorSrcModel"     => "MdlCabang",
        "selectedPrice"        => array(),
        "lockerCheck"          => array(),
        "selectorFilters"      => array(),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "cabang",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorViewedFields" => array(
            "nama",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
        ),
        "selectorProcessor"    => "Selectors/_processSelectPlafonPettycash/select",
        "editHandlerMethod"    => "select",

        "pihakModel"        => "MdlCabang",
        "pihakCaller"       => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"        => "cabang",
        "pihakFilters"      => array(
            "id<>cabang_id",
        ),
        "pihakMainValueSrc" => array(
            "ppnFactor" => "ppn",
        ),
        "pihakProcessor"    => "Selectors/_processPihak/select",

        "shortHistoryFields"         => array(
            "jenis_label"     => "activity",
            "dtime"           => "date",
            "suppliers_nama"  => "vendor",
            "nomer_top"       => "PO number",
            "nomer"           => "receipt number",
            "oleh_nama"       => "person",
            "transaksi_nilai" => "amount",
            //            "ppn"      => "ppn",
            //            "nett"      => "netto",
        ),
        "selectorFields"             => array("id", "nama"),
        "pihakFields"                => array("id", "nama"),
        "shoppingCart"               => array(
            "initPrices" => "beli",
        ),
        "shoppingCartFieldSrc"       => array(
            "nama"       => "nama",
            "label"      => "label",
            "reference"  => "reference",
            "lastPlafon" => "lastPlafon",
        ),
        "shoppingCartFields"         => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
                //                "lastPlafon" => "lastPlafon",
                //                "newPlafon" => "newPlafon",
            ),
            2 => array(
                "nama" => "item name",
                "jml"  => "qty",
                //                "lastPlafon" => "lastPlafon",
                //                "newPlafon" => "newPlafon",
            ),
        ),
        "shoppingCartNumFields"      => array(
            1 => array(
                //                "harga" => "Price",
                "lastPlafon" => "lastPlafon",
                "newPlafon"  => "reduce",
            ),
            2 => array(
                //                "harga" => "Price",
                "lastPlafon" => "lastPlafon",
                "newPlafon"  => "reduce",
            ),
        ),
        "shoppingCartNoteEnabled"    => false,
        "shoppingCartEditableFields" => array(
            1 => array(
                //                "harga",
                "jml",
                "reference",
                "newPlafon",
            ),
            2 => array(),
        ),
        "shoppingCartAmountValue"    => array(
            //            1 => "(lastPlafon-newPlafon)",
            //            2 => "(lastPlafon-newPlafon)",
        ),

        "shoppingCartFieldValidators" => array(
            //            "harga" => "price",
            //            "reference" => "reference",
        ),
        "shoppingCartRowValidators"   => array(),

        "receiptElements"  => array(
            "paymentMethod_cash"      => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "cash account",
                "pairedModel" => array(
                    "mdlName"    => "ComRekeningPembantuKas",
                    "mdlMethod"  => "fetchBalances",
                    "mdlFilter"  => array(
                        "cabang_id" => "placeID",
                    ),
                    "key"        => "extern_id",
                    "rekening"   => "kas",
                    "fieldID"    => "debet",
                    "fieldLabel" => "saldo",
                ),
                "mdlName"     => "MdlBankAccount",
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account",
                    "saldo" => "saldo",
                ),
                "editPoints"  => array(1,),
            ),
            "paymentMethod_pettycash" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "pettycash account",
                "mdlName"     => "MdlPettycashAccount",

                "key"        => "id",
                "labelSrc"   => "nama",
                "usedFields" => array(
                    "nama" => "",
                ),
                "editPoints" => array(1,),
            ),
        ),
        "relativeElements" => array(),
        "relativeOptions"  => array(),
        "cloner"           => array(
            "srcGateName" => "items",
            "cloneLabel"  => array(
                "id",
                "nama",
            ),
        ),
        "mainCloner"       => array(
            "items" => array(
                "cabang2ID"   => "id",
                "cabang2Name" => "nama",
            ),
            //            "items2" => array(
            //                "rek2ID" => "id",
            //                "rek2Name" => "nama",
            //            ),
        ),
    ),

    //  config jurnal penyesuaian
    "999" => array(
        "icon"             => "fa fa-cube",
        "label"            => "adjustment journaling",
        "place"            => "center",
        "steps"            => array(
            1 => array(
                "label"        => "adjustment journaling",
                "actionLabel"  => "make adjustment",
                "source"       => "",
                "target"       => "999",
                "userGroup"    => "root",
                "stateLabel"   => "done",
                "stateColor"   => "#dd3300",
                "stateCaption" => "made by",
            ),
        ),
        "template"         => "application/template/transaksi_nopihak2.html",
        "selectorModel"    => "MdlRekeningKredit",
        "selectorSrcModel" => "MdlRekeningKredit",

        "selectorModel2"    => "MdlRekeningDebet",
        "selectorSrcModel2" => "MdlRekeningDebet",

        "selectedPrice"        => array(),
        "lockerCheck"          => array(),
        "selectorFilters"      => array(//            "bank.cabang_id=placeID",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorCaller2"      => "Selectors/_selectorItem/selectItem2",// bikin shopping cart background
        "selectorLabel"        => "from account",
        "selectorLabel2"       => "to account",
        "selectorParamFields"  => array(
            "id"   => "id",
            "name" => "name",
        ),
        "selectorViewedFields" => array(
            "name",
            "defPosition",
        ),
        "selectorProcessor"    => "Selectors/_processSelectRekeningAdjustment/select",
        "selectorProcessor2"   => "Selectors/_processSelectRekeningAdjustment/select2",
        "editHandlerMethod"    => "edit",

        "pihakModel"     => "MdlGudang",
        "pihakCaller"    => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"     => "gudang",
        "pihakFilters"   => array(
            "cabang_id=cabang_id",
            "id<>gudang_id",
        ),
        "pihakProcessor" => "Selectors/_processPihak/select",

        "shortHistoryFields" => array(
            "jenis_label" => "activity",
            "dtime"       => "date",
            //            "cabang2_nama" => "recipient",
            "nomer"       => "receipt number",
            "oleh_nama"   => "person",
        ),
        "selectorFields"     => array("id", "nama"),
        "pihakFields"        => array("id", "nama"),
        "shoppingCart"       => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"      => array(
            1 => array(
                "nama" => "source account",

            ),
            2 => array(
                "nama" => "source account",

            ),
        ),
        "shoppingCartFields2"     => array(
            1 => array(
                "nama" => "target account",

            ),
            2 => array(
                "nama" => "target account",

            ),
        ),
        "shoppingCartFieldSrc"    => array(
            "name" => "name",
            "nama" => "nama",

        ),
        "shoppingCartNumFields"   => array(
            1 => array(
                "jml"    => "(don't change)",
                "debet"  => "debet",
                "kredit" => "kredit",
            ),
            2 => array(
                "jml"    => "(don't change)",
                "debet"  => "debet",
                "kredit" => "kredit",
            ),
        ),
        "shoppingCartNumFields2"  => array(
            1 => array(
                "harga" => "receiving amount",
                "jml"   => "qty",
            ),
            2 => array(
                "harga" => "receiving amount",
                "jml"   => "qty",
            ),
        ),
        "shoppingCartNoteEnabled" => false,
        "shoppingCartAvoidRemove" => true,
        //        "shoppingCartPairedItem"  => array(
        //            "enabled"   => true,
        //            "mdlName"   => "MdlBankAccount",
        //            "mdlFilter" => array(
        //                "cabang_id=placeID"
        //            ),
        //            "srcKey"    => "id",
        //            "srcLabel"  => array("nama"),
        //            "mdlFilter" => array("id=id"),
        //        ),

        //        "shoppingCartPairedSelectedItem" => array(
        //            "enabled" => true,
        //            "mdlName" => "ComRekeningPembantuKas",
        //            "srcKey" => "extern_id",
        //            "srcLabel" => array("nama"),
        //            "mdlFilter" => array(
        //                "cabang_id=placeID",
        //                "periode=forever",
        //                "rekening=kas",
        //                ),
        //        ),

        "shoppingCartEditableFields" => array(
            1 => array(
                "jml",
                "debet",
                "kredit",
            ),
        ),
        "shoppingCartAmountValue"    => array(
            1 => "jml*harga",
            2 => "jml*harga",
        ),
        "receiptElements"            => array(
            "extern1" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "source sub-account",
                "mdlName"     => "MdlExtern",
                "mdlFilter"   => array("relName=srcRel"),
                "key"         => "extern_id",
                "labelSrc"    => "extern_nama",
                "usedFields"  => array(
                    "extern_nama" => "account name",
                ),
                "editPoints"  => array(1),
            ),
            "extern2" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "target sub-account",
                "mdlName"     => "MdlExtern",
                "mdlFilter"   => array("relName=targetRel"),
                "key"         => "extern_id",
                "labelSrc"    => "extern_nama",
                "usedFields"  => array(
                    "extern_nama" => "account name",
                ),
                "editPoints"  => array(1),
            ),
        ),
        //        "relativeElements" => array(
        //            "opSubAccount" => array(
        //                "elementType" => "dataModel",
        //                "inputType"   => "radio",
        //                "label"       => "opposite account",
        //                "mdlName"     => "MdlRekeningChild",
        ////                "mdlFilter"   => array("defPosition!=defPosition"),
        //                "key"         => "id",
        //                "labelSrc"    => "name",
        //                "usedFields"  => array(
        //                    "name" => "child account",
        //                ),
        //                "editPoints"  => array(1),
        //            ),
        //
        //        ),

    ),
    //  config potongan pembayaran hutang (dari supplier)
    "499" => array(
        "icon"                 => "fa fa-money",
        "label"                => "(FG) credit note",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "credit note",
                "actionLabel"  => "assign credit note",
                "source"       => "",
                "target"       => "499",
                "userGroup"    => "c_purchasing",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "created by",
            ),
        ),
        "template"             => "application/template/transaksi.html",
        "selectorModel"        => "MdlPaymentSource",
        "selectorSrcModel"     => "MdlPaymentSource",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.467",
            "extern_id=pihakID",
            "sisa>.0",
            "diskon=.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "receipt note",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "sisa",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNotaCreditNote/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlSupplier",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "vendor",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "suppliers_nama" => "vendor",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "purchasing number",
                "jml"  => "qty",

                //                "satuan" => "satuan",
            ),
        ),
        "shoppingCartFieldSrc"         => array(
            "nomer"          => "nomer",
            "availAmount"    => "sisa",
            "srcTransaksiID" => "transaksi_id",
            //            "terbayar" => "terbayar",
            //            "sisa"     => "sisa",
        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "availAmount" => "available value",
            ),

        ),
        "shoppingCartEditableFields"   => array(
            1 => array(
                //                "jml",
                //                "produk_ord_jml",
            ),
        ),
        "shoppingCartAmountValue"      => array(//            1 => "sisa",
        ),
        "shoppingCartSumFields"        => array(
            1 => array(
                //                "sisa" => "debt amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "shoppingCartAvoidRemove"      => true,
        "shoppingCartHideSubamount"    => true,
        "tagihanSrc"                   => "harus_bayar",
        "receiptElements"              => array(
            "vendorDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "vendor details",
                "mdlName"     => "MdlSupplier",
                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"     => "name",
                    "npwp"     => "tax-ID",
                    "alamat_1" => "address",
                    "tlp_1"    => "phone",
                ),
                "editPoints"  => array(1, 2, 3),
            ),
            "creditValue"   => array(
                "elementType"  => "dataField",
                "label"        => "credit value",
                "inputType"    => "number",
                "defaultValue" => "0",
                "labelSrc"     => "amount",
                "maxValue"     => "availAmount",
                "editPoints"   => array(1, 2, 3, 4, 5),
            ),


        ),
        "pairMakers"                   => array(
            1 => array(
                "saldoRekening" => array(
                    "helperName"   => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                    "target"       => array("main", "out_master"),
                ),
            ),
        ),
        "mainValueInjectors"           => array(
            "amount"       => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar"  => "harus_bayar",
        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"     => "vendor ID",
            "pihakName"   => "vendor name",
            "creditValue" => "credit value",
        ),
        "shoppingCartRowNumValidators" => array(
            "creditValue" => "credit value",
        ),

    ),
    //  config penyetoran
    "759" => array(
        "icon"                 => "fa fa-money",
        "label"                => "Penyetoran Kas",
        "place"                => "branch",
        "steps"                => array(
            1 => array(
                "label"       => "setoran kas",
                "actionLabel" => "penyetoran",
                "source"      => "",
                "target"      => "759r",
                "userGroup"   => "o_kasir",
                "stateLabel"  => "prepare by",
                "stateColor"  => "#dd3300",
            ),
        ),
        "template"             => "application/template/transaksi_payment.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.582",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlCustomer",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "customer",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                "jml"  => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",

        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields"   => array(),
        "shoppingCartAmountValue"      => array(
            1 => "sisa",
        ),
        "shoppingCartSumFields"        => array(
            1 => array(
                //                "sisa" => "debt amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "shoppingCartAvoidRemove"      => true,
        "tagihanSrc"                   => "harus_bayar",
        "receiptElements"              => array(
            "centerDetails" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "center details",
                "mdlName"     => "MdlCabang",
                "mdlFilter"   => array("id<0"),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "name",
                ),
                "editPoints"  => array(1,),
            ),
            "gudang2ID"     => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "stock warehouse",
                "mdlName"     => "MdlGudangDefault_center",
                //                "mdlFilter" => array(),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",
                ),
                "editPoints"  => array(1, 2, 3),
            ),

            "cash_account_source" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "branch cash account",
                "mdlName"     => "MdlBankAccountSaldo", // MdlBankAccount_out
                "mdlFilter"   => array(
                    "bank.cabang_id=placeID",
                ),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama"  => "account number",
                    "alias" => "holder alias",
                    "debet" => "balance",
                ),
                "editPoints"  => array(1,),
            ),
            "dummyElement"        => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "relativeElements"             => array(
            "centerDetails" => array(
                "-1" => array(
                    "cash_account_target" => array(
                        "elementType" => "dataModel",
                        "inputType"   => "radio",
                        "label"       => "center cash account",
                        "mdlName"     => "MdlBankAccount", // MdlBankAccount_in
                        "mdlFilter"   => array(
                            "cabang_id=centerDetails",
                        ),
                        "key"         => "id",
                        "labelSrc"    => "nama",
                        "usedFields"  => array(
                            "nama" => "account",
                        ),
                        "editPoints"  => array(1,),
                    ),
                ),
            ),
            //            "branch" => array(
            //                "1" => array(
            //                    "cash_account_source" => array(
            //                        "elementType" => "dataModel",
            //                        "inputType" => "radio",
            //                        "label" => "branch cash account",
            //                        "mdlName" => "MdlBankAccountSaldo", // MdlBankAccount_out
            //                        "mdlFilter" => array(
            //                            "bank.cabang_id=placeID",
            //                        ),
            //                        "key" => "id",
            //                        "labelSrc" => "nama",
            //                        "usedFields" => array(
            //                            "nama" => "account number",
            //                            "alias" => "holder alias",
            //                            "debet" => "balance",
            //                        ),
            //                        "editPoints" => array(1,),
            //                    ),
            //                ),
            //            ),
        ),
        "pairMakers"                   => array(
            1 => array(
                "stock" => array(
                    "helperName"   => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "mainValueInjectors"           => array(
            "amount"       => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar"  => "harus_bayar",
        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),
        "additionalRows"               => array(
            "dummyElement" => array(
                "yes" => array(
                    "amount"      => array(
                        "label"        => "total amount",
                        "defaultValue" => "sisa",
                        "maxValue"     => "sisa",
                        //                        "keyupAction"  => "document.getElementById('harga_nett3').value= (parseFloat(document.getElementById('harga_nett2').value)-parseFloat(this.value))",
                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                    //                    "credit_amount" => array(
                    //                        "label" => "credit amount",
                    //                        "defaultValue" => "creditAmount",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    //                    "credit_note" => array(
                    //                        "label" => "credit note",
                    //                        "defaultValue" => "creditValue",
                    //                        //                        "keyupAction" => "",
                    //                        'disabled' => "disabled",
                    //                        "addPoints" => array(1,),
                    //                    ),
                    "harus_bayar" => array(
                        "label"        => "amount remains to pay",
                        "defaultValue" => "(sisa-creditAmount-creditValue)",
                        "maxValue"     => "(sisa-creditAmount-creditValue)",
                        "minValue"     => "(sisa-creditAmount-creditValue)",
                        //                        "keyupAction"=>"var gt=document.getElementById('grand_total').value;gt=gt.replace(/,/g,'');document.getElementById('kembali').value=(parseFloat(document.getElementById('bayar').value)-parseFloat(gt))",
                        //                        "keyupAction" => "var gt=this.min,bayar=this.value,kembali=document.getElementById('kembali'); kembali.value=parseFloat(bayar)-parseFloat(gt);if(parseFloat(bayar)<parseFloat(gt)){kembali.style.color='red',kembali.style.fontWeight='700'}else{kembali.style.color='green',kembali.style.fontWeight='700'}",

                        "keyPressAction" => "",
                        'disabled'       => "disabled",
                        "addPoints"      => array(1,),

                    ),
                    "nilai_entry" => array(
                        "label"        => "amount of payment",
                        "defaultValue" => ".0",
                        "keyupAction"  => "
    if(parseInt(this.value)>parseInt(document.getElementById('harus_bayar').value) || parseInt(this.value)<0){this.value=document.getElementById('harus_bayar').value;} 
                            "
                    ,
                        //                        'disabled'     => "disabled",
                        "addPoints"    => array(1,),
                    ),
                ),
            ),
        ),


        "connectTo" => "758",
    ),
    "758" => array(
        "icon"                 => "fa fa-money",
        "label"                => "Penerimaan Setoran Kas",
        "place"                => "center",
        "steps"                => array(
            1 => array(
                "label"        => "setoran kas",
                "actionLabel"  => "setoran kas",
                "source"       => "",
                "target"       => "758r",
                "userGroup"    => "sys",
                "stateLabel"   => "pending acceptance",
                "stateColor"   => "#dd3300",
                "stateCaption" => "initiated by",
            ),
            2 => array(
                "label"        => "Penerimaan Setoran Kas",
                "actionLabel"  => "receive",
                "source"       => "758r",
                "target"       => "758",
                "userGroup"    => "c_holding",
                "stateLabel"   => "completed",
                "stateColor"   => "#009900",
                "stateCaption" => "received by",
            ),
        ),
        "template"             => "application/template/transaksi_payment.html",
        "selectorModel"        => "MdlNota",
        "selectorFilters"      => array(
            "cabang_id=placeID",
            "jenis=.582",
            "transaksi_nilai_sisa>.0",
        ),
        "selectorCaller"       => "Selectors/_selectorItem/selectItem",// bikin shopping cart background
        "selectorLabel"        => "item",
        "selectorParamFields"  => array(
            "id"   => "id",
            "nama" => "nomer",
        ),
        "selectorViewedFields" => array(
            "nomer", "dtime",
        ),

        "selectorProcessor"    => "Selectors/_processSelectNota/select",
        "editHandlerMethod"    => "select",
        "pihakModel"           => "MdlCustomer",
        "pihakCaller"          => "Selectors/_selectorPihak/selectPihak",
        "pihakLabel"           => "customer",
        "pihakProcessor"       => "Selectors/_processPihak/select",
        "shortHistoryFields"   => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "compactHistoryFields" => array(
            "jenis_label"    => "activity",
            "dtime"          => "date",
            "customers_nama" => "customer",
            "nomer"          => "receipt number",
            "oleh_nama"      => "person",
        ),
        "selectorFields"       => array("id", "nama", "satuan"),
        "pihakFields"          => array("id", "nama"),
        "shoppingCart"         => array(
            "initPrices" => "beli",
        ),

        "shoppingCartFields"           => array(
            1 => array(
                "nama" => "item name",
                //                "jml" => "qty",
            ),

        ),
        "shoppingCartFieldSrc"         => array(
            "nama"     => "nomer",
            "tagihan"  => "tagihan",
            "terbayar" => "terbayar",
            "sisa"     => "sisa",

        ),
        "shoppingCartNumFields"        => array(
            1 => array(
                "sisa" => "due remain",
            ),
        ),
        "shoppingCartEditableFields"   => array(),
        "shoppingCartAmountValue"      => array(
            2 => "nilai_bayar",
        ),
        "shoppingCartSumFields"        => array(
            2 => array(
                //                "sisa" => "debt amount",
                //                "creditAmount" => "paid using credit",
                //                "nilai_entry" => "paid using cash account",
                //                "nilai_bayar" => "total amount of payment",
                //                "new_sisa" => "remain debt (from list)",
            ),
        ),
        "shoppingCartAvoidRemove"      => true,
        "tagihanSrc"                   => "harus_bayar",
        "receiptElements"              => array(
            "cash_account"        => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "branch cash account",
                "mdlName"     => "MdlBankAccount_out",
                "mdlFilter"   => array(
                    "bank.cabang_id=placeID",
                ),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "account",
                ),
                "editPoints"  => array(1,),
            ),
            "cash_account_tujuan" => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "center cash account",
                "mdlName"     => "MdlBankAccount_in",
                "mdlFilter"   => array(
                    "cabang_id=place2ID",
                ),
                "key"         => "id",
                "labelSrc"    => "nama",
                "usedFields"  => array(
                    "nama" => "account",
                ),
                "editPoints"  => array(1,),
            ),
            "dummyElement"        => array(
                "elementType" => "dataModel",
                "inputType"   => "radio",
                "label"       => "auto-validation",
                "mdlName"     => "MdlDummyElement",
                //                "mdlFilter"   => array("id=pihakID"),
                "key"         => "id",
                "labelSrc"    => "name",
                "usedFields"  => array(
                    "name" => "name",

                ),
                "editPoints"  => array(1, 2, 3),
            ),
        ),
        "pairMakers"                   => array(
            1 => array(
                "stock" => array(
                    "helperName"   => "he_cek_saldo_kas",
                    "functionName" => "cekStockSaldoKas",
                    "params"       => array(
                        "cabang_id" => "placeID",
                        //                        "gudang_id" => "gudangID",
                    ),
                ),
            ),
        ),
        "mainValueInjectors"           => array(
            "amount"       => "sisa",
            "creditAmount" => "creditAmount",
            "harus_bayar"  => "harus_bayar",
        ),
        "shoppingCartRowValidators"    => array(
            "pihakID"   => "vendor ID",
            "pihakName" => "vendor name",
        ),
        "shoppingCartRowNumValidators" => array(
            "nilai_entry" => "amount of payment",
        ),

    ),

);