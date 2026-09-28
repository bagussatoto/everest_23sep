<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(

    // stok opname produk pusat
    "1119" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "qty" => "jml",
                "hpp" => "harga",
                "debet" => "hpp*qty_debet",
                "kredit" => "hpp*qty_kredit",
                "qty_selisih" => "qty_opname-stok",
            ),
            "rsltItems" => array(//                "kredit_rsltItems" => "hpp*qty",
            ),
        ),

        "valueBuilders" => array(
//            "shipsvc_ppn_value" => "(shipping_service*10/100)",
//            "dp_value" => "(dp*100)/(100+10)",
//            "dp_ppn_value" => "dp_value*(10/100)",
//            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
//            "grand_total" => "nett1+install_tax+install+ongkir",
//            "grand_ppn" => "ongkir_ppn+ppn",
//            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
//            "new_net1" => "nett1+ongkir",
//            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
//            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
//            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
//            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+grand_ppn-dp-nilai_cia",
//             "total_ui" =>"nilai_tambah_hutang_ke_konsumen-nilai_tambah_ppn_out",
//            "dp_value" => "dp-ppn_dp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
//            "hpp" => "sub_hpp",

            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",

//            "berat_gross" => "sub_berat_gross",
//            "volume_gross" => "sub_volume_gross",

            //            "grand_hutang" => "",

        ),
        "externalValues" => array(
            //            "shipping_service" => array(
            ////                "mdlName" => "MdlCourier",
            //                "label" => "freight cost",
            //                "startAt" => 1,
            //                "useAt" => 5,
            //                "taxFactor" => "freight_ppn",
            //                "viewedAtReceipt" => true,
            //            ),

//            "install" => array(
//                "label" => "installation",
//                "startAt" => 1,
//                "useAt" => 5,
//                "taxFactor" => 10,
//                "viewedAtReceipt" => true,
//            ),
        ),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock",
            //            ),
        ),
        "preProcessor" => array(
            "1119" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp_avg" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadiOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "kredit" => "hpp",
                                "kredit_rsltItems" => "hpp",
                                "jml" => "jml",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",


            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "detailValues"    => array(
//                "harga" => "harga",
//                "hpp"   => "hpp",
//                "disc"  => "disc",
//                "ppn"   => "ppn",
//                "nett1" => "nett1",
//                "nett2" => "nett2",
//
//                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "rsltItemsValues" => array(
//                //                "harga"  => "harga",
//                "hpp" => "hpp",
//                //                "diskon" => "diskon",
//                //                "ppn"    => "ppn",
//                //                "nett"   => "nett",
//
//                //                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(
            "1119" => array(
                "master" => array(

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan produk" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan produk" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan produk" => "-kredit_rsltItems",
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan produk" => "-kredit_rsltItems",
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail kerugian
                    array(
                        "comName" => "RekeningPembantuKerugian",
                        "loop" => array(
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(

                    //region rekening pembantu produk, mengurangi stok
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //endregion

                    //region rekening pembantu produk, menambah produk
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk" => "sub_debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //endregion
                ),
            ),
        ),
        "postProcessor" => array(
            "1119r" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "1119ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "1119" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="menambah stok">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty_debet",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadi",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty_debet",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "qty_debet",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                    //<editor-fold desc="mengurangi stok">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty_kredit",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty_kredit",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),

        ),

    ),
    // stok opname produk cabang
    "2229" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "qty" => "jml",
                "hpp" => "harga",
                "debet" => "hpp*qty_debet",
                "kredit" => "hpp*qty_kredit",
                "qty_selisih" => "qty_opname-stok",
            ),
            "rsltItems" => array(//                "kredit_rsltItems" => "hpp*qty",
            ),
        ),

        "valueBuilders" => array(
//            "shipsvc_ppn_value" => "(shipping_service*10/100)",
//            "dp_value" => "(dp*100)/(100+10)",
//            "dp_ppn_value" => "dp_value*(10/100)",
//            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
//            "grand_total" => "nett1+install_tax+install+ongkir",
//            "grand_ppn" => "ongkir_ppn+ppn",
//            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
//            "new_net1" => "nett1+ongkir",
//            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
//            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
//            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
//            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+grand_ppn-dp-nilai_cia",
//             "total_ui" =>"nilai_tambah_hutang_ke_konsumen-nilai_tambah_ppn_out",
//            "dp_value" => "dp-ppn_dp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
//            "hpp" => "sub_hpp",

            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",

//            "berat_gross" => "sub_berat_gross",
//            "volume_gross" => "sub_volume_gross",

            //            "grand_hutang" => "",

        ),
        "externalValues" => array(
            //            "shipping_service" => array(
            ////                "mdlName" => "MdlCourier",
            //                "label" => "freight cost",
            //                "startAt" => 1,
            //                "useAt" => 5,
            //                "taxFactor" => "freight_ppn",
            //                "viewedAtReceipt" => true,
            //            ),

//            "install" => array(
//                "label" => "installation",
//                "startAt" => 1,
//                "useAt" => 5,
//                "taxFactor" => 10,
//                "viewedAtReceipt" => true,
//            ),
        ),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock",
            //            ),
        ),
        "preProcessor" => array(
            "2229" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp_avg" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadiOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "kredit" => "hpp",
                                "kredit_rsltItems" => "hpp",
                                "jml" => "jml",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",


            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "detailValues"    => array(
//                "harga" => "harga",
//                "hpp"   => "hpp",
//                "disc"  => "disc",
//                "ppn"   => "ppn",
//                "nett1" => "nett1",
//                "nett2" => "nett2",
//
//                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "rsltItemsValues" => array(
//                //                "harga"  => "harga",
//                "hpp" => "hpp",
//                //                "diskon" => "diskon",
//                //                "ppn"    => "ppn",
//                //                "nett"   => "nett",
//
//                //                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(
            "2229" => array(
                "master" => array(

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan produk" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan produk" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan produk" => "-kredit_rsltItems",
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan produk" => "-kredit_rsltItems",
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // khusus solo masuk ke efisiensi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "debet_rev",
                            "laba lain lain" => "-debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "efisiensi biaya" => "debet_rev",
                            "laba lain lain" => "-debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "-kredit_rsltItems_rev",
                            "kerugian" => "-kredit_rsltItems_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "efisiensi biaya" => "-kredit_rsltItems_rev",
                            "kerugian" => "-kredit_rsltItems_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".999",
//                            "extern_nama" => ".persediaan supplies",
                            "extern_id" => ".888",
                            "extern_nama" => ".laba(rugi) opname produk",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "-kredit_rsltItems_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".999",
//                            "extern_nama" => ".persediaan supplies",
                            "extern_id" => ".888",
                            "extern_nama" => ".laba(rugi) opname produk",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //----------------------
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "-debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail kerugian
                    array(
                        "comName" => "RekeningPembantuKerugian",
                        "loop" => array(
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail kerugian
                    array(
                        "comName" => "RekeningPembantuKerugian",
                        "loop" => array(
                            "kerugian" => "-kredit_rsltItems_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(

                    //region rekening pembantu produk, mengurangi stok
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //endregion

                    //region rekening pembantu produk, menambah produk
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk" => "sub_debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //endregion
                ),
            ),
        ),
        "postProcessor" => array(
            "2229r" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "2229ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "2229" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="menambah stok">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty_debet",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadi",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty_debet",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "qty_debet",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                    //<editor-fold desc="mengurangi stok">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty_kredit",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty_kredit",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),

        ),

    ),

    // stok opname supplies pusat
    "1118" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "qty" => "jml",
                "hpp" => "harga",
                "debet" => "hpp*qty_debet",
                "kredit" => "hpp*qty_kredit",
                "qty_selisih" => "qty_opname-stok",
            ),
            "rsltItems" => array(//                "kredit_rsltItems" => "hpp*qty",
            ),
        ),

        "valueBuilders" => array(
//            "shipsvc_ppn_value" => "(shipping_service*10/100)",
//            "dp_value" => "(dp*100)/(100+10)",
//            "dp_ppn_value" => "dp_value*(10/100)",
//            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
//            "grand_total" => "nett1+install_tax+install+ongkir",
//            "grand_ppn" => "ongkir_ppn+ppn",
//            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
//            "new_net1" => "nett1+ongkir",
//            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
//            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
//            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
//            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+grand_ppn-dp-nilai_cia",
//             "total_ui" =>"nilai_tambah_hutang_ke_konsumen-nilai_tambah_ppn_out",
//            "dp_value" => "dp-ppn_dp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
//            "hpp" => "sub_hpp",

            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",

//            "berat_gross" => "sub_berat_gross",
//            "volume_gross" => "sub_volume_gross",

            //            "grand_hutang" => "",

        ),
        "externalValues" => array(
            //            "shipping_service" => array(
            ////                "mdlName" => "MdlCourier",
            //                "label" => "freight cost",
            //                "startAt" => 1,
            //                "useAt" => 5,
            //                "taxFactor" => "freight_ppn",
            //                "viewedAtReceipt" => true,
            //            ),

//            "install" => array(
//                "label" => "installation",
//                "startAt" => 1,
//                "useAt" => 5,
//                "taxFactor" => 10,
//                "viewedAtReceipt" => true,
//            ),
        ),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock",
            //            ),
        ),
        "preProcessor" => array(
            "1118" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSuppliesOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp_avg" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PreFifoSuppliesOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "jml",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                                "kredit" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",


            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),

            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),
        "components" => array(
            "1118" => array(
                "master" => array(

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan supplies" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-kredit",
                            "kerugian" => "kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan supplies" => "-kredit",
                            "kerugian" => "kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname supplies", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail kerugian
                    array(
                        "comName" => "RekeningPembantuKerugian",
                        "loop" => array(
                            "kerugian" => "kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname supplies", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(

                    //region rekening pembantu produk, mengurangi stok
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //endregion

                    //region rekening pembantu produk, menambah produk
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "sub_debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
        ),
        "postProcessor" => array(
            "1118r" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "1118ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "1118" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="menambah produk">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "qty_debet",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty_debet",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "qty_debet",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="mengurangi produk">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty_kredit",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty_kredit",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),

        ),

    ),
    // stok opname supplies cabang bom
    "2228" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "qty" => "jml",
                "hpp" => "harga",
                "debet" => "hpp*qty_debet",
                "kredit" => "hpp*qty_kredit",
                "qty_selisih" => "qty_opname-stok",
            ),
            "rsltItems" => array(//                "kredit_rsltItems" => "hpp*qty",
            ),
        ),
        "valueBuilders" => array(
//            "shipsvc_ppn_value" => "(shipping_service*10/100)",
//            "dp_value" => "(dp*100)/(100+10)",
//            "dp_ppn_value" => "dp_value*(10/100)",
//            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
//            "grand_total" => "nett1+install_tax+install+ongkir",
//            "grand_ppn" => "ongkir_ppn+ppn",
//            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
//            "new_net1" => "nett1+ongkir",
//            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
//            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
//            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
//            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+grand_ppn-dp-nilai_cia",
//             "total_ui" =>"nilai_tambah_hutang_ke_konsumen-nilai_tambah_ppn_out",
//            "dp_value" => "dp-ppn_dp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
//            "hpp" => "sub_hpp",

            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",

//            "berat_gross" => "sub_berat_gross",
//            "volume_gross" => "sub_volume_gross",

            //            "grand_hutang" => "",

        ),
        "externalValues" => array(),
        "preValidator" => array(),
        "preProcessor" => array(
            "2228" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSuppliesOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp_avg" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSuppliesOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "kredit" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "jml",
                                "qty" => "qty",
                                "subtotal" => "subtotal",

                                "kredit_rev" => "hpp",
                                "hpp_rev" => "hpp",
                                "jml_rev" => "jml",
                                "qty_rev" => "qty",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",


            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),

            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),
        "components" => array(
            "2228" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "debet",
                            "efisiensi biaya" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan supplies" => "debet",
                            "efisiensi biaya" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-kredit",
                            "efisiensi biaya" => "-kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan supplies" => "-kredit",
                            "efisiensi biaya" => "-kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // pembantu efisiensi produksi bom
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".999",
//                            "extern_nama" => ".persediaan supplies",
                            "extern_id" => ".777",
                            "extern_nama" => ".laba(rugi) opname supplies",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "-kredit_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".999",
//                            "extern_nama" => ".persediaan supplies",
                            "extern_id" => ".777",
                            "extern_nama" => ".laba(rugi) opname supplies",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(

                    //region rekening pembantu produk, mengurangi stok
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "kredit",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //endregion

                    //region rekening pembantu produk, menambah produk
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "sub_debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //endregion

//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiaya",
//                        "loop" => array(
//                            "efisiensi biaya" => "-sub_kredit_rev",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => ".999",
//                            "extern2_nama" => ".persediaan supplies",
//                            "produk_qty" => "-jml_rev",
//                            "produk_nilai" => "kredit_rev",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiaya",
//                        "loop" => array(
//                            "efisiensi biaya" => "sub_debet_rev",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => ".999",
//                            "extern2_nama" => ".persediaan supplies",
//                            "produk_qty" => "qty_debet_rev",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "2228r" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "2228ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "2228" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="menambah stok">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "qty_debet",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty_debet",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "qty_debet",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="mengurangi stok">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty_kredit",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty_kredit",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),

        ),

    ),
    // stok opname supplies cabang non bom
    "2227" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "qty" => "jml",
                "hpp" => "harga",
                "debet" => "hpp*qty_debet",
                "kredit" => "hpp*qty_kredit",
                "qty_selisih" => "qty_opname-stok",
            ),
            "rsltItems" => array(//                "kredit_rsltItems" => "hpp*qty",
            ),
        ),

        "valueBuilders" => array(
//            "shipsvc_ppn_value" => "(shipping_service*10/100)",
//            "dp_value" => "(dp*100)/(100+10)",
//            "dp_ppn_value" => "dp_value*(10/100)",
//            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
//            "grand_total" => "nett1+install_tax+install+ongkir",
//            "grand_ppn" => "ongkir_ppn+ppn",
//            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
//            "new_net1" => "nett1+ongkir",
//            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
//            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
//            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
//            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+grand_ppn-dp-nilai_cia",
//             "total_ui" =>"nilai_tambah_hutang_ke_konsumen-nilai_tambah_ppn_out",
//            "dp_value" => "dp-ppn_dp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
//            "hpp" => "sub_hpp",

            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",

//            "berat_gross" => "sub_berat_gross",
//            "volume_gross" => "sub_volume_gross",

            //            "grand_hutang" => "",

        ),
        "externalValues" => array(),
        "preValidator" => array(),
        "preProcessor" => array(
            "2227" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSuppliesOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp_avg" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSuppliesOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "kredit" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "jml",
                                "qty" => "qty",
                                "subtotal" => "subtotal",

                                "kredit_rev" => "hpp",
                                "hpp_rev" => "hpp",
                                "jml_rev" => "jml",
                                "qty_rev" => "qty",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",


            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),

            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),
        "components" => array(
            "2227" => array(
                "master" => array(
                    // == persedian supplies vs quality ==
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "debet",
                            "quality" => "-debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan supplies" => "debet",
                            "quality" => "-debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-kredit",
                            "quality" => "kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan supplies" => "-kredit",
                            "quality" => "kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // == quality vs efisiensi ==
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "quality" => "debet",
                            "efisiensi biaya" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "quality" => "debet",
                            "efisiensi biaya" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "quality" => "-kredit",
                            "efisiensi biaya" => "-kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "quality" => "-kredit",
                            "efisiensi biaya" => "-kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // pembantu efisiensi produksi bom
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4",
                            "extern_nama" => ".quality",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "-kredit_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4",
                            "extern_nama" => ".quality",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //region rekening pembantu produk, mengurangi stok
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_kredit",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "kredit",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //endregion

                    //region rekening pembantu produk, menambah produk
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "sub_debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //endregion


                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "efisiensi biaya" => "-sub_kredit_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",
                            "extern_nama" => ".bahan baku",
                            "extern2_id" => ".4",
                            "extern2_nama" => ".quality",
                            "produk_qty" => "-jml_rev",
                            "produk_nilai" => "kredit_rev",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "efisiensi biaya" => "sub_debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",
                            "extern_nama" => ".bahan baku",
                            "extern2_id" => ".4",
                            "extern2_nama" => ".quality",
                            "produk_qty" => "qty_debet_rev",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "2227r" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "2227ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "2227" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="menambah stok">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "qty_debet",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty_debet",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "qty_debet",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>


                    //<editor-fold desc="mengurangi stok">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty_kredit",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty_kredit",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                ),
            ),

        ),

    ),
    // stok opname produk cabang
    "3339" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "qty" => "jml",
                "hpp" => "harga",
                "debet" => "hpp*qty_debet",
                "kredit" => "hpp*qty_kredit",
                "qty_selisih" => "qty_opname-stok",
            ),
            "rsltItems" => array(//                "kredit_rsltItems" => "hpp*qty",
            ),
        ),

        "valueBuilders" => array(
//            "shipsvc_ppn_value" => "(shipping_service*10/100)",
//            "dp_value" => "(dp*100)/(100+10)",
//            "dp_ppn_value" => "dp_value*(10/100)",
//            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
//            "grand_total" => "nett1+install_tax+install+ongkir",
//            "grand_ppn" => "ongkir_ppn+ppn",
//            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
//            "new_net1" => "nett1+ongkir",
//            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
//            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
//            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
//            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+grand_ppn-dp-nilai_cia",
//             "total_ui" =>"nilai_tambah_hutang_ke_konsumen-nilai_tambah_ppn_out",
//            "dp_value" => "dp-ppn_dp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
//            "hpp" => "sub_hpp",

            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",

//            "berat_gross" => "sub_berat_gross",
//            "volume_gross" => "sub_volume_gross",

            //            "grand_hutang" => "",

        ),
        "externalValues" => array(
            //            "shipping_service" => array(
            ////                "mdlName" => "MdlCourier",
            //                "label" => "freight cost",
            //                "startAt" => 1,
            //                "useAt" => 5,
            //                "taxFactor" => "freight_ppn",
            //                "viewedAtReceipt" => true,
            //            ),

//            "install" => array(
//                "label" => "installation",
//                "startAt" => 1,
//                "useAt" => 5,
//                "taxFactor" => 10,
//                "viewedAtReceipt" => true,
//            ),
        ),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock",
            //            ),
        ),
        "preProcessor" => array(
            "3339" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp_avg" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadiOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "kredit" => "hpp",
                                "kredit_rsltItems" => "hpp",
                                "jml" => "jml",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",


            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "detailValues"    => array(
//                "harga" => "harga",
//                "hpp"   => "hpp",
//                "disc"  => "disc",
//                "ppn"   => "ppn",
//                "nett1" => "nett1",
//                "nett2" => "nett2",
//
//                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "rsltItemsValues" => array(
//                //                "harga"  => "harga",
//                "hpp" => "hpp",
//                //                "diskon" => "diskon",
//                //                "ppn"    => "ppn",
//                //                "nett"   => "nett",
//
//                //                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(
            "3339" => array(
                "master" => array(

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan produk" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan produk" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan produk" => "-kredit_rsltItems",
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan produk" => "-kredit_rsltItems",
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail kerugian
                    array(
                        "comName" => "RekeningPembantuKerugian",
                        "loop" => array(
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),

                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk" => "sub_debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "3339r" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "3339ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "3339" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="menambah stok">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty_debet",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadi",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty_debet",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "qty_debet",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                    //<editor-fold desc="mengurangi stok">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty_kredit",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty_kredit",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),

        ),

    ),
    "5559" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "qty" => "jml",
                "hpp" => "harga",
                "debet" => "hpp*qty_debet",
                "kredit" => "hpp*qty_kredit",
                "qty_selisih" => "qty_opname-stok",
            ),
            "rsltItems" => array(//                "kredit_rsltItems" => "hpp*qty",
            ),
        ),

        "valueBuilders" => array(
//            "shipsvc_ppn_value" => "(shipping_service*10/100)",
//            "dp_value" => "(dp*100)/(100+10)",
//            "dp_ppn_value" => "dp_value*(10/100)",
//            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
//            "grand_total" => "nett1+install_tax+install+ongkir",
//            "grand_ppn" => "ongkir_ppn+ppn",
//            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
//            "new_net1" => "nett1+ongkir",
//            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
//            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
//            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
//            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+grand_ppn-dp-nilai_cia",
//             "total_ui" =>"nilai_tambah_hutang_ke_konsumen-nilai_tambah_ppn_out",
//            "dp_value" => "dp-ppn_dp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
//            "hpp" => "sub_hpp",

            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",

//            "berat_gross" => "sub_berat_gross",
//            "volume_gross" => "sub_volume_gross",

            //            "grand_hutang" => "",

        ),
        "externalValues" => array(
            //            "shipping_service" => array(
            ////                "mdlName" => "MdlCourier",
            //                "label" => "freight cost",
            //                "startAt" => 1,
            //                "useAt" => 5,
            //                "taxFactor" => "freight_ppn",
            //                "viewedAtReceipt" => true,
            //            ),

//            "install" => array(
//                "label" => "installation",
//                "startAt" => 1,
//                "useAt" => 5,
//                "taxFactor" => 10,
//                "viewedAtReceipt" => true,
//            ),
        ),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock",
            //            ),
        ),
        "preProcessor" => array(
            "5559" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp_avg" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadiRakitanOpname",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_kredit",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "kredit" => "hpp",
                                "kredit_rsltItems" => "hpp",
                                "jml" => "jml",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",


            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "detailValues"    => array(
//                "harga" => "harga",
//                "hpp"   => "hpp",
//                "disc"  => "disc",
//                "ppn"   => "ppn",
//                "nett1" => "nett1",
//                "nett2" => "nett2",
//
//                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "rsltItemsValues" => array(
//                //                "harga"  => "harga",
//                "hpp" => "hpp",
//                //                "diskon" => "diskon",
//                //                "ppn"    => "ppn",
//                //                "nett"   => "nett",
//
//                //                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(
            "5559" => array(
                "master" => array(

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan produk rakitan" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan produk rakitan" => "debet",
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan produk rakitan" => "-kredit_rsltItems",
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "persediaan produk rakitan" => "-kredit_rsltItems",
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // khusus solo masuk ke efisiensi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "debet_rev",
                            "laba lain lain" => "-debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "efisiensi biaya" => "debet_rev",
                            "laba lain lain" => "-debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "-kredit_rsltItems_rev",
                            "kerugian" => "-kredit_rsltItems_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "efisiensi biaya" => "-kredit_rsltItems_rev",
                            "kerugian" => "-kredit_rsltItems_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".999",
//                            "extern_nama" => ".persediaan supplies",
                            "extern_id" => ".888",
                            "extern_nama" => ".laba(rugi) opname produk",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "-kredit_rsltItems_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".999",
//                            "extern_nama" => ".persediaan supplies",
                            "extern_id" => ".888",
                            "extern_nama" => ".laba(rugi) opname produk",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "-debet_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail kerugian
                    array(
                        "comName" => "RekeningPembantuKerugian",
                        "loop" => array(
                            "kerugian" => "kredit_rsltItems",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail kerugian
                    array(
                        "comName" => "RekeningPembantuKerugian",
                        "loop" => array(
                            "kerugian" => "-kredit_rsltItems_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",// laba rugi lain-lain ppv
                            "extern_nama" => ".opname produk", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk rakitan" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),

                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "persediaan produk rakitan" => "sub_debet",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "5559r" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "5559ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "5559" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="menambah stok">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty_debet",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoProdukJadiRakitan",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "qty_debet",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_debet",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockProduksi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk rakitan",
                            "state" => ".active",
                            "jumlah" => "qty_debet",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty_debet",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                    //<editor-fold desc="mengurangi stok">
                    array(
                        "comName" => "LockerStockProduksi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk rakitan",
                            "state" => ".active",
                            "jumlah" => "-qty_kredit",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty_kredit",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),

        ),

    ),

);