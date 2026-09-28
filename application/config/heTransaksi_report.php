<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 2/14/2019
 * Time: 7:18 PM
 */

$config["heTransaksi_report_identifiers"] = array(
    "oleh_id" => "person",
    "customers_id" => "customer",
    "suppliers_id" => "vendor",
    "cabang_id" => "branch",
    "cabang2_id" => "recipient branch",
    "gudang_id" => "warehouse",
    "gudang2_id" => "recipient warehouse",
    "bank_id" => "bank",
    "bank_rekening_id" => "bank account",
    "ekspedisi_id" => "courier",
    "kontainer_approve_id" => "container",
    "seller_id" => "seller",
    "produk_id" => "product",
);

$config["heTransaksi_report"] = array(
    "466" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            "suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            //            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "oleh_id",
        "longHistoryFields" => array(

            "produk_nama+produk_kode" => "item name",
            "produk_ord_jml" => "qty",
            "produk_ord_hrg" => "@price",
            "suppliers_nama" => "vendor",

            "nomer_top+nomer" => "receipt number",
            "oleh_nama+dtime" => "person",

        ),
        "defaultStep" => "466r",
    ),
    "583" => array(),
    "585" => array(),
    "582" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            //"suppliers_id" => "Vendor",
            "seller_id" => "Seller",
            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
            "paymentMethod_id" => "paymethod",
            "paymentMethod_debit_card_cash_account_id" => "receiver account",
        ),
        "defaultFilter" => "customers_id",
        "longHistoryFields" => array(
            "produk_nama" => "item name",
            "produk_ord_jml" => "qty",
            "produk_ord_hrg" => "@price",
            "customers_nama" => "customer",
            "pembayaran_sys" => "paymentMethod",
            "paymentMethod_nama" => "paid by",
            "paymentMethod_cash_cash_account_nama" => "paid via",
            "paymentMethod_credit_card_cash_account_nama" => "cc paid via",
            "paymentMethod_debit_card_cash_account_nama" => "debit paid via",
            "nomer" => "receipt number",
            "bank_rekening_nama" => "receiver account",
            "oleh_nama+dtime" => "person",

        ),
        "defaultStep" => "582so",
        "historicalReport" => array(
            "582spo" => array(
                "tabs" => array(
                    1 => array(
                        "label" => "all",
                        "target" => "ActivityReport/viewHistory/582/582spo/1",
                        "default" => "1",
                    ),
                    2 => array(
                        "label" => "success",
                        "target" => "ActivityReport/viewHistory/582/582spo/2",
                        "param" => array(
                            "step_current" => "> 0",
                        ),
                    ),
                    3 => array(
                        "label" => "deleted",
                        "target" => "ActivityReport/viewHistory/582/582spo/3",
                    ),
                    4 => array(
                        "label" => "finished",
                        "target" => "ActivityReport/viewHistory/582/582spo/4",
                    ),
                    5 => array(
                        "label" => "unfinish",
                        "target" => "ActivityReport/viewHistory/582/582spo/5",
                    ),
                ),
                "fields" => array(
                    "id" => array(),
                    "jenis" => array(),
                    "dtime" => array(
                        "label" => "tanggal",
                        "format" => "formatField",
                    ),
                    "nomer" => array(
                        "label" => "nomer",
                        "attr" => "class='text-center'",
                        "format" => "formatField",

                    ),
                    "oleh_nama" => array(
                        "label" => "seller",
                        "link" => "Katalog/modal/",
                    ),
                    "customers_nama" => array(
                        "label" => "customer",
                    ),
                    "step_current" => array(
                        "label" => "current",
                    ),
                ),
            ),
        ),
        "reportingNett" => array(
            "tabs" => array(
                // 1 => array(
                //     "jenis"   => "582spo",
                //     "label"   => "Sales Order",
                //     "target"  => "ActivityReport/viewHistory/582/582spo/1",
                //     "default" => "1",
                // ),
                // 2 => array(
                //     "jenis"   => "582so",
                //     "label"  => "approval",
                //     "target" => "ActivityReport/viewHistory/582/582spo/2",
                //     "param"  => array(
                //         "step_current" => "> 0",
                //     ),
                // ),
                // 3 => array(
                //     "jenis"   => "582pkd",
                //     "label"  => "pre packing",
                //     "target" => "ActivityReport/viewHistory/582/582spo/3",
                // ),
                4 => array(
                    "jenis" => "582spd",
                    "label" => "packing list/penjualan",
                    "target" => "ActivityReport/viewHistory/582/582spo/4",
                    "default" => "1",
                ),
                // 5 => array(
                //     "jenis"   => "582",
                //     "label"  => "invoicing",
                //     "target" => "ActivityReport/viewHistory/582/582spo/5",
                // ),
            ),
            "fields" => array(
                "id" => array(),
                "jenis" => array(),
                "dtime" => array(
                    "label" => "tanggal",
                    "format" => "formatField",
                ),
                "nomer" => array(
                    "label" => "nomer",
                    "attr" => "class='text-center'",
                    "format" => "formatField",

                ),
                "oleh_nama" => array(
                    "label" => "seller",
                    // "link"  => "Katalog/modal/",
                ),
                "customers_nama" => array(
                    "label" => "customer",
                ),
                // "step_current"   => array(
                //     "label" => "current",
                // ),
            ),
            "returns" => array(
                "jenis_master" => "982",

            ),
        ),
    ),


    // config po supplies
    "461" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            "suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            //            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "oleh_id",
        "defaultStep" => "461ro",
    ),
    // config po jasa
    "463" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            "suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            //            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "oleh_id",
        "defaultStep" => "463ro",
    ),
    // config pr (request)
    "761" => array(),


    // config pembayaran hutang ke supplier (finish goods)
    "489" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            "suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            //            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "suppliers_id",
        "defaultStep" => "489",
    ),
    // config pembayaran hutang ke supplier (supplies)
    "487" => array(),
    // config pembayaran biaya umum
    "462" => array(),
    // config penerimaan piutang customer (uang masuk)
    "749" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            //"suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "customers_id",
        "longHistoryFields" => array(
            "produk_nama" => "item name",
            "produk_ord_jml" => "qty",
            "produk_ord_hrg" => "value",
            //            "transaksi_nilai"          => "@price",
            "customers_nama" => "customer",

            "nomer_top+nomer" => "receipt number",
            "oleh_nama+dtime" => "person",

        ),
        "defaultStep" => "749",
    ),
    // config supplies yang dibiayakan
    "762" => array(),


    //  config return pembelian finish goods
    "967" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            "suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            //            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "suppliers_id",
        "defaultStep" => "967",
    ),
    //  config return pembelian supplies
    "961" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            "suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            //            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "suppliers_id",
        "defaultStep" => "961",
    ),
    //  config return penjualan
    "982" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            //"suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            //"gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "customers_id",
        "defaultStep" => "982",
    ),


    //  config pemindahan finish goods (ke tidak dijual)
    "587" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            //            "suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            //            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            "gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "produk_id",
        "defaultStep" => "587",
    ),
    //  config pemindahan finish goods (ke dijual)
    "687" => array(
        "availFilters" => array(
            "produk_id" => "Produk",
            //            "suppliers_id" => "Vendor",
            //"sellers_id" => "Seller",
            //            "customers_id" => "Customer",
            "oleh_id" => "Petugas",
            "cabang_id" => "Cabang",
            "gudang_id" => "Warehouse",
        ),
        "defaultFilter" => "produk_id",
        "defaultStep" => "687",
    ),
    //  config konversi finish goods
    "334" => array(),
);

$config["heMovement"] = array(
    "fg" => array(
        "label" => "finished goods",
        "com" => "ComRekeningPembantuProduk",
        "rek" => "persediaan_produk",
        "mdl" => "MdlProduk",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/persediaan%20produk/",
            ),
            "nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
        ),
        "mainHeaders_1" => array(
            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='3'",
            "available goods " => "class='text-center text-uppercase bg-info' colspan='3'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='3'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "subHeaders" => array(
            "qty" => "class='text-center text-uppercase bg-info'",
            "price" => "class='text-center text-uppercase bg-info'",
            "amount" => "class='text-center text-uppercase bg-info'",
        ),
    ),
    "sp" => array(
        "label" => "supplies",
        "com" => "ComRekeningPembantuSupplies",
        "rek" => "persediaan_supplies",
        "mdl" => "MdlSupplies",
        "mdlFields" => array(
            "id" => array(),
            // "kode" => array(
            //     "label" => "code",
            //     "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
            //     "attr" => "class='text-center'",
            // ),
            "nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails/RekeningPembantuSupplies/persediaan%20supplies/",
            ),
        ),
        "mainHeaders_1" => array(
            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='3'",
            "available goods " => "class='text-center text-uppercase bg-info' colspan='3'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='3'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "subHeaders" => array(
            "qty" => "class='text-center text-uppercase bg-info'",
            "price" => "class='text-center text-uppercase bg-info'",
            "amount" => "class='text-center text-uppercase bg-info'",
        ),
    ),
    "rk" => array(
        "label" => "asambled",
        "com" => "ComRekeningPembantuProduk",
        "rek" => "persediaan_produk_rakitan",
        "mdl" => "MdlProdukRakitan",
        "mdlFields" => array(
            "id" => array(),
            "kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
                "link" => "Ledger/viewMoveDetails/RekeningPembantuProduk/persediaan%20produk/",
            ),
            "nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
        ),
        "mainHeaders_1" => array(
            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='3'",
            "available goods " => "class='text-center text-uppercase bg-info' colspan='3'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='3'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "subHeaders" => array(
            "qty" => "class='text-center text-uppercase bg-info'",
            "price" => "class='text-center text-uppercase bg-info'",
            "amount" => "class='text-center text-uppercase bg-info'",
        ),
    ),
);

$config["heMovementBaru"] = array(
    // finish goods
    "fg_old" => array(
        "label" => "finished goods",
        "com" => "ComRekeningPembantuProduk",
        "rek" => "persediaan_produk",
        "mdl" => "MdlProduk",
        "tblMutasi" => "__rek_pembantu_produk__persediaan_produk",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/persediaan%20produk/",
            ),
            "nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/persediaan%20produk/",
            ),
        ),
        "mainHeaders_1" => array(
            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-success' colspan='6'",
            // "available goods " => "class='text-center text-uppercase bg-info' colspan='3'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='6'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "headers" => array(
            "awal" => array(
                "label" => "begining balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
            "input" => array(
                "label" => "incoming",
                "attr" => "class='text-center text-uppercase bg-success' colspan",
                "subHeader" => array(
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "output" => array(
                "label" => "outgoing",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "akhir" => array(
                "label" => "ending balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
        ),
        "additionalHeaders" => array(
            "jenisTransaksiEksternal" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                    "output" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_bom" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                    "output" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_ng" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_biaya" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                ),
            ),
        ),
        "jenisTransaksiEksternal" => array("982", "582spd", "382spd", "467", "967", "460", "960"),
        "jenisTransaksi_bom" => array("776", "976", "3685"),
        "jenisTransaksi_ng" => array("587", "687", "1587", "1687"),
        "jenisTransaksi_biaya" => array(),
    ),
    // finish goods
    "fg" => array(
        "label" => "finished goods",
        "com" => "ComRekeningPembantuProduk",
        "rek" => "1010030030",
        "mdl" => "MdlProduk",
        "tblMutasi" => "__rek_pembantu_produk__1010030030",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/1010030030/",
            ),
            "nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/1010030030/",
            ),
        ),
        "mainHeaders_1" => array(
            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-success' colspan='6'",
            // "available goods " => "class='text-center text-uppercase bg-info' colspan='3'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='6'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "headers" => array(
            "awal" => array(
                "label" => "begining balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
            "input" => array(
                "label" => "incoming",
                "attr" => "class='text-center text-uppercase bg-success' colspan",
                "subHeader" => array(
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "output" => array(
                "label" => "outgoing",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "akhir" => array(
                "label" => "ending balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
        ),
        "additionalHeaders" => array(
            "jenisTransaksiEksternal" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                    "output" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_bom" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                    "output" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_ng" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_biaya" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                ),
            ),
        ),
        "jenisTransaksiEksternal" => array("982", "582spd", "382spd", "467", "967", "460", "960"),
        "jenisTransaksi_bom" => array("776", "976", "3685"),
        "jenisTransaksi_ng" => array("587", "687", "1587", "1687"),
        "jenisTransaksi_biaya" => array(),
    ),

    // supplies
    "sp_old" => array(
        "label" => "supplies (bahan baku)",
        "com" => "ComRekeningPembantuSupplies",
        "rek" => "persediaan_supplies",
        "mdl" => "MdlSupplies",
        "tblMutasi" => "__rek_pembantu_supplies__persediaan_supplies",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "nama" => array(
                "label" => "supplies",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuSupplies/persediaan%20supplies/",
            ),
        ),
        "mainHeaders_1" => array(

            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='6'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='6'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "headers" => array(
            "awal" => array(
                "label" => "begining balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
            "input" => array(
                "label" => "incoming",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    // internal
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "output" => array(
                "label" => "outgoing",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    // internal
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "akhir" => array(
                "label" => "ending balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
        ),
        "additionalHeaders" => array(
            "jenisTransaksiEksternal" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                    "output" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_bom" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                    "output" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_ng" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_biaya" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                ),
            ),
        ),
        "jenisTransaksiEksternal" => array("461", "961"),
        "jenisTransaksi_bom" => array("776a", "776", "976", "2228"),
        "jenisTransaksi_ng" => array(),
        "jenisTransaksi_biaya" => array("762"),
    ),
    "sp" => array(
        "label" => "supplies (bahan baku)",
        "com" => "ComRekeningPembantuSupplies",
        "rek" => "1010030010",
        "mdl" => "MdlSupplies",
        "tblMutasi" => "__rek_pembantu_supplies__1010030010",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "nama" => array(
                "label" => "supplies",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuSupplies/1010030010/",
            ),
        ),
        "mainHeaders_1" => array(

            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='6'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='6'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "headers" => array(
            "awal" => array(
                "label" => "begining balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
            "input" => array(
                "label" => "incoming",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    // internal
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "output" => array(
                "label" => "outgoing",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    // internal
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "akhir" => array(
                "label" => "ending balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
        ),
        "additionalHeaders" => array(
            "jenisTransaksiEksternal" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                    "output" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_bom" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                    "output" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_ng" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_biaya" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                ),
            ),
        ),
        "jenisTransaksiEksternal" => array("461", "961"),
        "jenisTransaksi_bom" => array("776a", "776", "976", "2228"),
        "jenisTransaksi_ng" => array(),
        "jenisTransaksi_biaya" => array("762"),
    ),

    // supplies
    "sp_proses_old" => array(
        "label" => "supplies (produksi)",
        "com" => "ComRekeningPembantuSuppliesProses",
        "rek" => "persediaan supplies proses",
        "mdl" => "MdlSupplies",
        "tblMutasi" => "__rek_pembantu_supplies_proses__persediaan_supplies_proses",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "nama" => array(
                "label" => "supplies",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuSuppliesProses/persediaan%20supplies%20proses/",
            ),
        ),
        "mainHeaders_1" => array(
            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='6'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='6'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "headers" => array(
            "awal" => array(
                "label" => "begining balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
            "input" => array(
                "label" => "incoming",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    // internal
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "output" => array(
                "label" => "outgoing",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    // internal
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "akhir" => array(
                "label" => "ending balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
        ),
        "additionalHeaders" => array(
            "jenisTransaksiEksternal" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                    "output" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_bom" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                    "output" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_ng" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_biaya" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                ),
            ),
        ),
        "jenisTransaksiEksternal" => array(),
        "jenisTransaksi_bom" => array("776a", "776", "976"),
        "jenisTransaksi_ng" => array(),
        "jenisTransaksi_biaya" => array(),
    ),
    "sp_proses" => array(
        "label" => "supplies (produksi)",
        "com" => "ComRekeningPembantuSuppliesProses",
        "rek" => "1010030050",
        "mdl" => "MdlSupplies",
        "tblMutasi" => "__rek_pembantu_supplies_proses__1010030050",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "nama" => array(
                "label" => "supplies",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuSuppliesProses/1010030050/",
            ),
        ),
        "mainHeaders_1" => array(
            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='6'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='6'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "headers" => array(
            "awal" => array(
                "label" => "begining balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
            "input" => array(
                "label" => "incoming",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    // internal
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "output" => array(
                "label" => "outgoing",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    // internal
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),

                ),
            ),
            "akhir" => array(
                "label" => "ending balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
        ),
        "additionalHeaders" => array(
            "jenisTransaksiEksternal" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                    "output" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_bom" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                    "output" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_ng" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_biaya" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                ),
            ),
        ),
        "jenisTransaksiEksternal" => array(),
        "jenisTransaksi_bom" => array("776a", "776", "976"),
        "jenisTransaksi_ng" => array(),
        "jenisTransaksi_biaya" => array(),
    ),


    // rakitan
    "rk_old" => array(
        "label" => "rakitan",
        "com" => "ComRekeningPembantuProduk",
        "rek" => "persediaan_produk_rakitan",
        "mdl" => "MdlProdukRakitan",
        "tblMutasi" => "__rek_pembantu_produk__persediaan_produk_rakitan",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/persediaan%20produk%20rakitan/",
            ),
            "nama" => array(
                "label" => "rakitan",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/persediaan%20produk%20rakitan/",
            ),
        ),
        "mainHeaders_1" => array(

            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='6'",
            // "available goods " => "class='text-center text-uppercase bg-info' colspan='3'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='6'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "headers" => array(
            "awal" => array(
                "label" => "begining balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
            "input" => array(
                "label" => "incoming",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                ),
            ),
            "output" => array(
                "label" => "outgoing",
                "attr" => "class='text-center text-uppercase bg-info' colspan'",
                "subHeader" => array(
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                ),
            ),
            "akhir" => array(
                "label" => "ending balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
        ),
        "additionalHeaders" => array(
            "jenisTransaksiEksternal" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                    "output" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_bom" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                    "output" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_ng" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_biaya" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                ),
            ),
        ),
        "jenisTransaksiEksternal" => array(),
        "jenisTransaksi_bom" => array("776", "976", "3685"),
        "jenisTransaksi_ng" => array(),
        "jenisTransaksi_biaya" => array(),
    ),
    "rk" => array(
        "label" => "rakitan",
        "com" => "ComRekeningPembantuProduk",
        "rek" => "1010030070",
        "mdl" => "MdlProdukRakitan",
        "tblMutasi" => "__rek_pembantu_produk__1010030070",
        "mdlFields" => array(
            "id" => array(
                "label" => "pID",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
            ),
            "kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-center'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/1010030070/",
            ),
            "nama" => array(
                "label" => "rakitan",
                "attrHeader" => "class='text-center text-uppercase bg-info' rowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails_v1/RekeningPembantuProduk/1010030070/",
            ),
        ),
        "mainHeaders_1" => array(

            "begining balance" => "class='text-center text-uppercase bg-info' colspan='3'",
            "incoming" => "class='text-center text-uppercase bg-info' colspan='6'",
            // "available goods " => "class='text-center text-uppercase bg-info' colspan='3'",
            "outgoing" => "class='text-center text-uppercase bg-info' colspan='6'",
            "ending balance" => "class='text-center text-uppercase bg-info' colspan='3'",
        ),
        "headers" => array(
            "awal" => array(
                "label" => "begining balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
            "input" => array(
                "label" => "incoming",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                ),
            ),
            "output" => array(
                "label" => "outgoing",
                "attr" => "class='text-center text-uppercase bg-info' colspan'",
                "subHeader" => array(
                    "unit_ho" => array(
                        "label" => "qty ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "harga_ho" => array(
                        "label" => "price ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                    "nilai_ho" => array(
                        "label" => "amount ho",
                        "attr" => "class='text-center text-uppercase bg-success'",
                    ),
                ),
            ),
            "akhir" => array(
                "label" => "ending balance",
                "attr" => "class='text-center text-uppercase bg-info' colspan",
                "subHeader" => array(
                    "unit" => array(
                        "label" => "qty",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "harga" => array(
                        "label" => "price",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                    "nilai" => array(
                        "label" => "amount",
                        "attr" => "class='text-center text-uppercase bg-grey-2'",
                    ),
                ),
            ),
        ),
        "additionalHeaders" => array(
            "jenisTransaksiEksternal" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                    "output" => array(
                        "unit" => array(
                            "label" => "qty ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "harga" => array(
                            "label" => "price ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                        "nilai" => array(
                            "label" => "amount ext",
                            "attr" => "class='text-center text-uppercase bg-info'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_bom" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                    "output" => array(
                        "unit_bom" => array(
                            "label" => "qty bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "harga_bom" => array(
                            "label" => "price bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                        "nilai_bom" => array(
                            "label" => "amount bom",
                            "attr" => "class='text-center text-uppercase bg-danger'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_ng" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_ng" => array(
                            "label" => "qty N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "harga_ng" => array(
                            "label" => "price N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                        "nilai_ng" => array(
                            "label" => "amount N.G.",
                            "attr" => "class='text-center text-uppercase bg-warning'",
                        ),
                    ),
                ),
            ),
            "jenisTransaksi_biaya" => array(
                "subHeader" => array(
                    "input" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                    "output" => array(
                        "unit_exp" => array(
                            "label" => "qty expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "harga_exp" => array(
                            "label" => "price expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                        "nilai_exp" => array(
                            "label" => "amount expense",
                            "attr" => "class='text-center text-uppercase bbg-warning'",
                        ),
                    ),
                ),
            ),
        ),
        "jenisTransaksiEksternal" => array(),
        "jenisTransaksi_bom" => array("776", "976", "3685"),
        "jenisTransaksi_ng" => array(),
        "jenisTransaksi_biaya" => array(),
    ),

);

$config["report"] = array(
    "pre_penjualan" => array(
        "title" => "pre sales",
        "mdlFields" => array(
            "id" => array(),
            "cabang_nama" => array(
                "label" => "cabang",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "subject_nama" => array(
                "label" => "seller",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "unit_ot" => array(
                "label" => "sale qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_ot" => array(
                "label" => "sale value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            // "unit_in"      => array(
            //     "label"      => "sale return",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-right'",
            //     // "link"       =>"",
            // ),
            // "nilai_in"     => array(
            //     "label"      => "retun value (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField",
            //     // "link"       =>"",
            // ),
            "unit_af" => array(
                "label" => "qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "nilai_af" => array(
                "label" => "value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
        ),
    ),
    "pre_penjualan_cabang" => array(
        "title" => "SUMMARY OF BRANCH",
        "mdlFields" => array(
            // "subject_id" => array(),
            // "subject_nama" => array(
            //     "label"      => "sseles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            // "object_id" => array(),
            // "object_nama" => array(
            //     "label"      => "seles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "cabang_id" => array(),
            "cabang_nama" => array(
                "label" => "cabang",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"#",
            ),
            "nilai_ot" => array(
                "label" => "sales (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
                // "link"       =>"",
            ),
            "nilai_in" => array(
                "label" => "cancel (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "pre_penjualan_seller" => array(
        "title" => "SUMMARY OF sales man",
        "mdlFields" => array(
            "subject_id" => array(),
            "subject_nama" => array(
                "label" => "sales man",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "cabang_id" => array(),
            "cabang_nama" => array(),
            "nilai_ot" => array(
                "label" => "pre SO (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_in" => array(
                "label" => "cancel (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "pre_penjualan_produk" => array(
        "title" => "SUMMARY OF product",
        "mdlFields" => array(
            "object_id" => array(),
            "object_nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
            ),
            "cabang_id" => array(),
            "cabang_nama" => array(),

            "unit_ot" => array(
                "label" => "sales (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot" => array(
                "label" => "sales (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "unit_in" => array(
                "label" => "cancel (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_in" => array(
                "label" => "cancel (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "unit_af" => array(
                "label" => "sub total (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),

    "penjualan" => array(
        "title" => "sales",
        "mdlFields" => array(
            "id" => array(),
            "cabang_nama" => array(
                "label" => "cabang",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "subject_nama" => array(
                "label" => "seller",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "unit_ot" => array(
                "label" => "sale qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_ot" => array(
                "label" => "sale value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_in" => array(
                "label" => "sale return",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_in" => array(
                "label" => "retun value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_af" => array(
                "label" => "qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "nilai_af" => array(
                "label" => "value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
        ),
    ),
    "penjualan_cabang" => array(
        "title" => "SUMMARY OF BRANCH",
        "mdlFields" => array(
            // "subject_id" => array(),
            // "subject_nama" => array(
            //     "label"      => "sseles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            // "object_id" => array(),
            // "object_nama" => array(
            //     "label"      => "seles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "cabang_id" => array(),
            "cabang_nama" => array(
                "label" => "cabang",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"#",
            ),
            "nilai_ot" => array(
                "label" => "sales (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
                // "link"       =>"",
            ),
            "nilai_in" => array(
                "label" => "return (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "penjualan_seller" => array(
        "title" => "SUMMARY OF sales man",
        "mdlFields" => array(
            "subject_id" => array(),
            "subject_nama" => array(
                "label" => "seles man",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "cabang_id" => array(),
            "cabang_nama" => array(),
            "nilai_ot" => array(
                "label" => "sales (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_in" => array(
                "label" => "return (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "penjualan_produk" => array(
        "title" => "SUMMARY OF product",
        "mdlFields" => array(
            "object_id" => array(),
            "object_nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
            ),
            "cabang_id" => array(),
            "cabang_nama" => array(),

            "unit_ot" => array(
                "label" => "sales (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot" => array(
                "label" => "sales (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "unit_in" => array(
                "label" => "return (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_in" => array(
                "label" => "return (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "unit_af" => array(
                "label" => "sub total (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),

    "penjualan_cabang_compared" => array(
        "title" => "SUMMARY OF BRANCH",
        "mdlFieldsSide" => array(
            "cabang_id" => array(),
            "cabang_nama" => array(
                "label" => "cabang",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"#",
            ),
        ),
        "mdlFields" => array(
//            "cabang_id" => array(),
//            "cabang_nama" => array(
//                "label" => "cabang",
//                "attrHeader" => "class='text-center text-uppercase bg-info'",
//                "attr" => "class='text-left'",
//                // "link"       =>"#",
//            ),
//            "nilai_ot" => array(
//                "label" => "sales (IDR)",
//                "attrHeader" => "class='text-center text-uppercase bg-info'",
//                "attr" => "class='text-left'",
//                "format" => "formatField",
//                "sum_rows" => true,
//                // "link"       =>"",
//            ),
//            "nilai_in" => array(
//                "label" => "return (IDR)",
//                "attrHeader" => "class='text-center text-uppercase bg-info'",
//                "attr" => "class='text-right'",
//                "format" => "formatField",
//                "sum_rows" => true,
//            ),
            "nilai_af" => array(
                "label" => "sales netto (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "penjualan_seller_compared" => array(
        "title" => "SUMMARY OF sales man",
        "mdlFieldsSide" => array(
            "subject_id" => array(),
            "subject_nama" => array(
                "label" => "salesman",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
        ),
        "mdlFields" => array(
            "subject_id" => array(),
            "subject_nama" => array(
                "label" => "salesman",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "cabang_id" => array(),
            "cabang_nama" => array(),
//            "nilai_ot" => array(
//                "label" => "sales (IDR)",
//                "attrHeader" => "class='text-center text-uppercase bg-info'",
//                "attr" => "class='text-left'",
//                "format" => "formatField",
//                "sum_rows" => true,
//            ),
//            "nilai_in" => array(
//                "label" => "return (IDR)",
//                "attrHeader" => "class='text-center text-uppercase bg-info'",
//                "attr" => "class='text-left'",
//                "format" => "formatField",
//                "sum_rows" => true,
//            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "realisasi_so" => array(
        "title" => "realisasi penjualan",
        "mdlFields" => array(
            "id" => array(),
            "cabang_nama" => array(
                "label" => "cabang",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "subject_nama" => array(
                "label" => "seller",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "unit_ot_spo" => array(
                "label" => "so qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_ot_spo" => array(
                "label" => "sale value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_ot_cl" => array(
                "label" => "sale return",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_ot_cl" => array(
                "label" => "retun value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_ot_spd" => array(
                "label" => "sale",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_ot_spd" => array(
                "label" => "sales value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_af_spo" => array(
                "label" => "qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "nilai_af_spo" => array(
                "label" => "value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
        ),
    ),
    "realisasi_so_cabang" => array(
        "title" => "SUMMARY OF BRANCH",
        "mdlFields" => array(
            // "subject_id" => array(),
            // "subject_nama" => array(
            //     "label"      => "sseles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            // "object_id" => array(),
            // "object_nama" => array(
            //     "label"      => "seles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "cabang_id" => array(),
            "cabang_nama" => array(
                "label" => "cabang",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"#",
            ),
            // "nilai_ot_spo"    => array(
            //     "label"      => "SO (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     "format"     => "formatField",
            //     "sum_rows"   => true,
            //     // "link"       =>"",
            // ),
            // "nilai_ot_cl"    => array(
            //     "label"      => "dibatalkan so (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField",
            //     "sum_rows"   => true,
            // ),
            "nilai_ne_spo" => array(
                "label" => "nett so (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot_spd" => array(
                "label" => "dikirim (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af_spo" => array(
                "label" => "pending (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "realisasi_so_seller" => array(
        "title" => "SUMMARY OF sales man",
        "mdlFields" => array(
            "subject_id" => array(),
            "subject_nama" => array(
                "label" => "seles man",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "cabang_id" => array(),
            "cabang_nama" => array(),
            // "nilai_ot_spo" => array(
            //     "label"      => "SO (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     "format"     => "formatField",
            //     "sum_rows"   => true,
            // ),
            // "nilai_ot_cl"  => array(
            //     "label"      => "dibatalkan (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     "format"     => "formatField",
            //     "sum_rows"   => true,
            // ),
            "nilai_ne_spo" => array(
                "label" => "so (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot_spd" => array(
                "label" => "penjualan (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af_spo" => array(
                "label" => "pending (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "realisasi_so_produk" => array(
        "title" => "SUMMARY OF product",
        "mdlFields" => array(
            "object_id" => array(),
            "object_nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "object_kode" => array(
                "label" => "code",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
            ),
            "cabang_id" => array(),
            "cabang_nama" => array(),

            "unit_ot_spo" => array(
                "label" => "so (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot_spo" => array(
                "label" => "SO (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "unit_ot_cl" => array(
                "label" => "dibatalkan (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot_cl" => array(
                "label" => "dibatalkan (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "unit_ot_spd" => array(
                "label" => "dikirim (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot_spd" => array(
                "label" => "dikirim (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "unit_af_spo" => array(
                "label" => "pending (QTY)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af_spo" => array(
                "label" => "pending (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),

    "realisasi_allso_movement" => array(
        "title" => "SUMMARY OF ",
        "navigasi" => array(
            "cabang" => array(
                "label" => "cabang",
                "link" => "ActivityReport/viewSalesRealizations/cabang",
            ),
            "subject" => array(
                "label" => "seller",
                "link" => "ActivityReport/viewSalesRealizations/subject",
            ),
            // "object" => array(
            //     "label" => "produk",
            //     "link" => "ActivityReport/viewSalesRealizations/object",
            // ),

        ),
        "mdlFields" => array(
            // "subject_id" => array(),
            // "subject_nama" => array(
            //     "label"      => "sseles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            // "object_id" => array(),
            // "object_nama" => array(
            //     "label"      => "seles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "cabang_id" => array(),
            "dtime" => array(
                "label" => "month",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatFieldMonth",
                // "link"       =>"#",
            ),
            "subject_nama" => array(
                "label" => "--",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"#",
            ),
            // "nilai_ot_spo"    => array(
            //     "label"      => "SO (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     "format"     => "formatField",
            //     "sum_rows"   => true,
            //     // "link"       =>"",
            // ),
            // "nilai_ot_cl"    => array(
            //     "label"      => "dibatalkan so (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField",
            //     "sum_rows"   => true,
            // ),
            "nilai_be" => array(
                "label" => "so awal (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "sum_rows"   => true,
            ),
            "nilai_in" => array(
                "label" => "so (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot" => array(
                "label" => "pre-pl  (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "so akhir (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "realisasi_so_movement" => array(
        "title" => "SUMMARY OF TEST",
        "mdlFields" => array(
            // "subject_id" => array(),
            // "subject_nama" => array(
            //     "label"      => "sseles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            // "object_id" => array(),
            // "object_nama" => array(
            //     "label"      => "seles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "cabang_id" => array(),
            "subject_nama" => array(
                "label" => "--",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"#",
            ),
            // "nilai_ot_spo"    => array(
            //     "label"      => "SO (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     "format"     => "formatField",
            //     "sum_rows"   => true,
            //     // "link"       =>"",
            // ),
            // "nilai_ot_cl"    => array(
            //     "label"      => "dibatalkan so (IDR)",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-right'",
            //     "format"     => "formatField",
            //     "sum_rows"   => true,
            // ),
            "nilai_be" => array(
                "label" => "so awal (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "sum_rows"   => true,
            ),
            "nilai_in" => array(
                "label" => "so (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_ot" => array(
                "label" => "pre-pl  (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "so akhir (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),


    "pembelian_supplies" => array(
        "title" => "purchasing supplies",
        "mdlFields" => array(
            "id" => array(),
            // "cabang_nama"  => array(
            //     "label"      => "cabang",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "subject_nama" => array(
                "label" => "vendor",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            // "object_kode"  => array(
            //     "label"      => "code",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "object_nama" => array(
                "label" => "supplies",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "unit_ot" => array(
                "label" => "sale qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_ot" => array(
                "label" => "sale value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_in" => array(
                "label" => "sale return",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_in" => array(
                "label" => "retun value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_af" => array(
                "label" => "qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "nilai_af" => array(
                "label" => "value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
        ),
    ),
    "pembelian_cabang" => array(
        "title" => "branch purchase summary",
        "mdlFields" => array(
            // "subject_id" => array(),
            // "subject_nama" => array(
            //     "label"      => "sseles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            // "object_id" => array(),
            // "object_nama" => array(
            //     "label"      => "seles man",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "cabang_id" => array(),
            "cabang_nama" => array(
                "label" => "cabang",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"#",
            ),
            "nilai_ot" => array(
                "label" => "purchase (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
                // "link"       =>"",
            ),
            "nilai_in" => array(
                "label" => "return (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "pembelian_vendor" => array(
        "title" => "vendor purchase summary",
        "mdlFields" => array(
            "subject_id" => array(),
            "subject_nama" => array(
                "label" => "vendor",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "cabang_id" => array(),
            "cabang_nama" => array(),
            "nilai_ot" => array(
                "label" => "purchase (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_in" => array(
                "label" => "return (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
            "nilai_af" => array(
                "label" => "sub total (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
                "sum_rows" => true,
            ),
        ),
    ),
    "pembelian_produk" => array(
        "title" => "purchasing FG",
        "mdlFields" => array(
            "id" => array(),
            // "cabang_nama"  => array(
            //     "label"      => "cabang",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "subject_nama" => array(
                "label" => "vendor",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            // "object_kode"  => array(
            //     "label"      => "code",
            //     "attrHeader" => "class='text-center text-uppercase bg-info'",
            //     "attr"       => "class='text-left'",
            //     // "link"       =>"",
            // ),
            "object_nama" => array(
                "label" => "product",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                // "link"       =>"",
            ),
            "unit_ot" => array(
                "label" => "purchase qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_ot" => array(
                "label" => "purchase value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_in" => array(
                "label" => "purchase return",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                // "link"       =>"",
            ),
            "nilai_in" => array(
                "label" => "retun value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "unit_af" => array(
                "label" => "qty",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
            "nilai_af" => array(
                "label" => "value (IDR)",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                // "link"       =>"",
            ),
        ),
    ),
);

$config["history"] = array(
    "invoice" => array(
        "title" => "invoicing",
        "mdlFields" => array(
            "id" => array(),
            "dtime" => array(
                "label" => "date",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField"
            ),
            "cabang_nama" => array(
                "label" => "branch",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
            ),
            "customers_nama" => array(
                "label" => "customer",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
            ),
            "oleh_nama" => array(
                "label" => "pic",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
            ),
            "nomer" => array(
                "label" => "invoice",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-left'",
                "format" => "formatField",
            ),
            "transaksi_nilai" => array(
                "label" => "value",
                "attrHeader" => "class='text-center text-uppercase bg-info'",
                "attr" => "class='text-right'",
                "format" => "formatField",
                "sumrow" => true,
            ),


        ),
    ),
);
$config["realisasi"] = array(
    "main" => array(

        "cabang_nama" => "cabang",
        "customers_nama" => "customer",
        "nomer" => "Sales Order",
    ),
    "fields" => array(
        "id" => "id",
        "id_top" => "id_top",
        "nomer" => "nomer",
        "trash" => "trash",
        "trash_4" => "trash_4",//pembatalan
        "customers_nama" => "customers_nama",
        "fulldate" => "date",
        "dtime" => "dtime",
        "oleh_id" => "oleh_id",
//        "transaksi_jenis" =>"transaksi_jenis",
        "cabang_nama" => "cabang_nama",
        "indexing_registry" => "indexing_registry",
    ),
    //auto diselect main aja di controller
    "pair_registry" => array(
        "olehID" => "olehID",
//        "pihakName" =>"customer",
//        "dtime" =>"tanggal",
//        "cabangName" =>"cabang",
        "grand_net" => "nilai",
    ),
    "main_transaksi" => array(
//        "customerName" =>"Customer",
//        "branch" =>"cabang",
//        "582spo" =>"SO Date",
        "582so" => "SO Approval",
        "582so_dtime" => "SOA Date",
//        "582pkd" =>"Pre packing",
        "582spd" => "Packing list",
        "582spd_dtime" => "Packing list date",
//        "582" =>"invoicing",
        "prosessing_time" => "processing time(days)",
        "remark" => "remark",
    ),
    "valueGate" => array(
        "prosessing_time" => "582so-582spd"
    ),
);

$config["heEfisiensi"] = array(
    // efisiensi biaya
    "bomm" => array(
        "label" => "Efisiensi Produksi (BOM)",
        "com" => "ComRekeningPembantuEfisiensiBiayaMain",
        "rek" => "efisiensi biaya",
        "mdl" => "MdlProdukRakitanPreBiaya",
        "tblMutasi" => "__rek_pembantu_efisiensi__efisiensi_biaya",
        "tblMutasiDetail" => "__rek_pembantu_subefisiensi__efisiensi_biaya",
        "mdlFields" => array(
//            "id" => array(
//                "label" => "pID",
//                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
//                "attr" => "class='text-center'",
//            ),
            "nama" => array(
                "label" => "kategori biaya",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails/RekeningPembantuEfisiensiBiayaMain/efisiensi%20biaya/",
                "linkDetail" => "",
            ),
            "debet" => array(
                "label" => "riil",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "kredit" => array(
                "label" => "anggaran",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "balance" => array(
                "label" => "efisiensi produksi (bom)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
        ),
        "mdlFieldsDetail" => array(
//            "id" => array(
//                "label" => "pID",
//                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
//                "attr" => "class='text-center'",
//            ),
            "nama" => array(
                "label" => "kategori biaya",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails/RekeningPembantuEfisiensiBiaya/efisiensi%20biaya/",
                "linkDetail" => "",
            ),
            "qty_debet" => array(
                "label" => "riil (qty)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "debet" => array(
                "label" => "riil",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "qty_kredit" => array(
                "label" => "anggaran (qty)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "kredit" => array(
                "label" => "anggaran",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "qty_balance" => array(
                "label" => "efisiensi produksi (qty bom)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "balance" => array(
                "label" => "efisiensi produksi (bom)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
        ),
        "mainHeaders" => array(
            "qty_riil" => "class='text-center text-uppercase bg-success' ccolspan='6'",
            "riil" => "class='text-center text-uppercase bg-success' ccolspan='6'",
            "qty_anggaran" => "class='text-center text-uppercase bg-info' ccolspan='6'",
            "anggaran" => "class='text-center text-uppercase bg-info' ccolspan='6'",
            "qty_balance" => "class='text-center text-uppercase bg-info' ccolspan='3'",
            "balance" => "class='text-center text-uppercase bg-info' ccolspan='3'",
        ),
        "additionalRek" => array(
            "com" => "ComRekening",
            "rekening" => "persediaan supplies",
            "tblMutasi" => "__rek_master__persediaan_supplies",
            "tblMutasiDetail" => "__rek_pembantu_supplies__persediaan_supplies",
            "jenisTransaksi" => array("776"),
            "position" => "kredit",
        ),
        "additionalRek2" => array(
            "com" => "ComRekening",
            "rekening" => "persediaan supplies proses",
            "tblMutasi" => "__rek_master__persediaan_supplies_proses",
            "tblMutasiDetail" => "__rek_pembantu_supplies_proses__persediaan_supplies_proses",
            "jenisTransaksi" => array("776"),
            "position" => "kredit",
        ),
        "positionRek" => array(
            "laba(rugi) perubahan grade supplies" => "debet",
            "laba(rugi) opname supplies" => "debet",
            "laba(rugi) perubahan grade produk" => "kredit",
            "laba(rugi) opname produk" => "kredit",
        ),
        "view" => array(
            "1" => "delivery cost",
            "2" => "direct labor",
            "4" => "quality",
            "persediaan supplies" => "persediaan supplies",
            "555" => "laba(rugi) konversi produk",
            "888" => "laba(rugi) opname produk",
            "777" => "laba(rugi) opname bahan baku",
            "666" => "laba(rugi) konversi supplies",
        ),
    ),
    "bom" => array(
        "label" => "Efisiensi Produksi (BOM)",
        "com" => "ComRekeningPembantuEfisiensiBiayaMain",
        "rek" => "3020010",
        "mdl" => "MdlProdukRakitanPreBiaya",
        "tblMutasi" => "__rek_pembantu_efisiensi__3020010",
        "tblMutasiDetail" => "__rek_pembantu_subefisiensi__3020010",
        "mdlFields" => array(
//            "id" => array(
//                "label" => "pID",
//                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
//                "attr" => "class='text-center'",
//            ),
            "nama" => array(
                "label" => "kategori biaya",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails/RekeningPembantuEfisiensiBiayaMain/3020010/",
                "linkDetail" => "",
            ),
            "debet" => array(
                "label" => "riil",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "kredit" => array(
                "label" => "anggaran",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "balance" => array(
                "label" => "efisiensi produksi (bom)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
        ),
        "mdlFieldsDetail" => array(
//            "id" => array(
//                "label" => "pID",
//                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
//                "attr" => "class='text-center'",
//            ),
            "nama" => array(
                "label" => "kategori biaya",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
                "link" => "Ledger/viewMoveDetails/RekeningPembantuEfisiensiBiaya/3020010/",
                "linkDetail" => "",
            ),
            "qty_debet" => array(
                "label" => "riil (qty)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "debet" => array(
                "label" => "riil",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "qty_kredit" => array(
                "label" => "anggaran (qty)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "kredit" => array(
                "label" => "anggaran",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "qty_balance" => array(
                "label" => "efisiensi produksi (qty bom)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
            "balance" => array(
                "label" => "efisiensi produksi (bom)",
                "attrHeader" => "class='text-center text-uppercase bg-info' rrowspan='2'",
                "attr" => "class='text-left text-uppercase'",
            ),
        ),
        "mainHeaders" => array(
            "qty_riil" => "class='text-center text-uppercase bg-success' ccolspan='6'",
            "riil" => "class='text-center text-uppercase bg-success' ccolspan='6'",
            "qty_anggaran" => "class='text-center text-uppercase bg-info' ccolspan='6'",
            "anggaran" => "class='text-center text-uppercase bg-info' ccolspan='6'",
            "qty_balance" => "class='text-center text-uppercase bg-info' ccolspan='3'",
            "balance" => "class='text-center text-uppercase bg-info' ccolspan='3'",
        ),
        "additionalRek" => array(
            "com" => "ComRekening",
            "rekening" => "1010030010",
            "tblMutasi" => "__rek_master__1010030010",
            "tblMutasiDetail" => "__rek_pembantu_supplies__1010030010",
            "jenisTransaksi" => array("776"),
            "position" => "kredit",
        ),
        "additionalRek2" => array(
            "com" => "ComRekening",
            "rekening" => "1010030050",
            "tblMutasi" => "__rek_master__1010030050",
            "tblMutasiDetail" => "__rek_pembantu_supplies_proses__1010030050",
            "jenisTransaksi" => array("776"),
            "position" => "kredit",
        ),
        "positionRek" => array(
            "laba(rugi) perubahan grade supplies" => "debet",
            "laba(rugi) opname supplies" => "debet",
            "laba(rugi) perubahan grade produk" => "kredit",
            "laba(rugi) opname produk" => "kredit",
        ),
        "view" => array(
            "1" => "delivery cost",
            "2" => "direct labor",
            "4" => "quality",
//            "persediaan supplies" => "persediaan supplies",
            "1010030010" => "persediaan supplies",
            "555" => "laba(rugi) konversi produk",
            "888" => "laba(rugi) opname produk",
            "777" => "laba(rugi) opname bahan baku",
            "666" => "laba(rugi) konversi supplies",
        ),
    ),


);