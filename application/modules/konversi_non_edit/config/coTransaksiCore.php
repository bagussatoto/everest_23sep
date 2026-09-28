<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

$config["coTransaksiCore"] = array(
    //konversi
    "1334" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //
                //                "produk_ids" => "produk_ids",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
//            "hpp_target" => "harga",
//            "nilai_selisih" => "harga-hpp",
//            "hpp_target" => "hpp_injector",
            "hpp_target" => "hpp_sumber",
            "nilai_selisih" => "hpp_injector-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "1334" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageConvertion",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                //                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//
//                            "jenisTr" => "jenisTr",
//                            "id_src" => "id_src",
//                            "jml_per_satuan" => "jml_per_satuan",
//                            "targetID" => "targetID",
//                            "targetName" => "targetName",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                //                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
        ),
        "afterPreProcessorInjector" => array(
            "enabled" => true,
            "gateSource" => "items",
            "gateTarget" => "items2_sum",
            "keys" => array(
                "sub_hpp" => "total_hpp_injector",
                "sub_hpp_riil" => "total_hpp_riil_injector",
                "sub_ppv_riil" => "total_ppv_riil_injector",
            ),
            "keysTarget" => array(
                "hpp_injector" => "total_hpp_injector/jml",
                "hpp_riil" => "total_hpp_riil_injector/jml",
                "ppv_riil" => "total_ppv_riil_injector/jml",
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "1334" => array(
                "master" => array(

//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "-hpp_sumber",// persediaan produk
//                            "7010060" => "-hpp_sumber",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "hpp_target",// persediaan produk
//                            "7010060" => "hpp_target",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010030030" => "-hpp_sumber",// persediaan produk
//                            "7010060" => "-hpp_sumber",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010030030" => "hpp_target",// persediaan produk
//                            "7010060" => "hpp_target",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),


                ),
                "detail" => array(
                    //<editor-fold desc="subkomponen gudang finish goods (sumber konversi)">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="subkomponen gudang finish goods (hasil konversi), tidak dijual">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp_injector",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            //"produk_nilai" => "hpp",
                            "produk_nilai" => "hpp_injector",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //</editor-fold>
                ),
            ),
        ),
        "postProcessor" => array(
            "1334r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "1334" => array(
                "master" => array(),
                "detail" => array(
                    //region sumber konversi
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".moved",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                    //region target konversi
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
//                            "hpp" => "harga",
//                            "jml_nilai" => "sub_harga",
                            "hpp" => "hpp_injector",
                            "jml_nilai" => "sub_hpp_injector",
                            "nama" => "nama",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "jml",
//                            "produk_id" => "id",
//                            "produk_nama" => "nama",
////                            "hpp" => "harga",
////                            "jml_nilai" => "sub_harga",
//                            "hpp" => "hpp_injector",
//                            "jml_nilai" => "sub_hpp_injector",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done
    // konversi supplies ke produk (center)
    "2334" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //
                //                "produk_ids" => "produk_ids",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
            "hpp_target" => "harga",
            "nilai_selisih" => "harga-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "2334" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                    //--------------------
//                    array(
//                        "comName" => "Sync2GatesInjector",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                            "jenisTr" => "jenisTr",
//                            "rowPreFifo" => "rowPreFifo",
//
//                            "target" => ".rsltItems",
//                            "source" => ".items2_sum",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id_target" => "id",
//                                "name_target" => "nama",
//                            ),
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "2334" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "1010030010" => "-hpp",// persediaan supplies
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
                            "1010030030" => "hpp",// persediaan produk
                            "1010030010" => "-hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    //jurnal ppv pusat
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "2010100010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "2010100010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // detail laba lain-lain
//                    array(
//                        "comName" => "RekeningPembantuLRLainlain",
//                        "loop" => array(
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => ".3",// laba rugi lain-lain ppv
//                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id_target",
                            "extern_nama" => "nama_target",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
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
            "2334r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "2334" => array(
                "master" => array(),
                "detail" => array(
                    //region sumber konversi

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".converted",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //endregion

                    //region target konversi

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id_target",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "nama" => "name_target",
                            "name" => "name_target",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "jml",
//                            "produk_id" => "id_target",
//                            "produk_nama" => "name_target",
//                            "hpp" => "harga",
//                            "jml_nilai" => "sub_harga",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //endregion
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done
    // konversi produk ke supplies (center)
    "2336" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //
                //                "produk_ids" => "produk_ids",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
            "hpp_target" => "harga",
            "nilai_selisih" => "harga-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "2336" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    //--------------------
//                    array(
//                        "comName" => "Sync2GatesInjector",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                            "jenisTr" => "jenisTr",
//                            "rowPreFifo" => "rowPreFifo",
//
//                            "target" => ".rsltItems",
//                            "source" => ".items2_sum",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id_target" => "id",
//                                "name_target" => "nama",
//                            ),
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "2336" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "hpp",// persediaan supplies
                            "1010030030" => "-hpp",// persediaan produk
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
                            "1010030010" => "hpp",// persediaan supplies
                            "1010030030" => "-hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id_target",
                            "extern_nama" => "name_target",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "harga",
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
            "2336r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "2336" => array(
                "master" => array(),
                "detail" => array(
                    //region sumber konversi

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".converted",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                    //region target konversi

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "jml",
                            "produk_id" => "id_target",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "nama" => "name_target",
                            "name" => "name_target",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "jml",
//                            "produk_id" => "id_target",
//                            "produk_nama" => "name_target",
//                            "hpp" => "harga",
//                            "jml_nilai" => "sub_harga",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //endregion
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done
    // konversi produk ke supplies (branch)
    "2337" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //
                //                "produk_ids" => "produk_ids",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
            "hpp_target" => "harga",
            "nilai_selisih" => "harga-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "2337" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    //--------------------
//                    array(
//                        "comName" => "Sync2GatesInjector",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                            "jenisTr" => "jenisTr",
//                            "rowPreFifo" => "rowPreFifo",
//
//                            "target" => ".rsltItems",
//                            "source" => ".items2_sum",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id_target" => "id",
//                                "name_target" => "nama",
//                            ),
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "2337" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "hpp",// persediaan supplies
                            "1010030030" => "-hpp",// persediaan produk
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
                            "1010030010" => "hpp",// persediaan supplies
                            "1010030030" => "-hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id_target",
                            "extern_nama" => "name_target",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "harga",
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
            "2337r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "2337" => array(
                "master" => array(),
                "detail" => array(
                    //region sumber konversi

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".converted",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //endregion

                    //region target konversi

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "jml",
                            "produk_id" => "id_target",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "nama" => "name_target",
                            "name" => "name_target",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "jml",
//                            "produk_id" => "id_target",
//                            "produk_nama" => "name_target",
//                            "hpp" => "harga",
//                            "jml_nilai" => "sub_harga",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //endregion
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done
    //  config konversi grade finish goods ke finish goods baru
    "334" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //
                //                "produk_ids" => "produk_ids",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
//            "hpp_target" => "harga",
//            "nilai_selisih" => "harga-hpp",
            "hpp_target" => "hpp_injector",
            "nilai_selisih" => "hpp_injector-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "334" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageConvertion",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                //                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//
//                            "jenisTr" => "jenisTr",
//                            "id_src" => "id_src",
//                            "jml_per_satuan" => "jml_per_satuan",
//                            "targetID" => "targetID",
//                            "targetName" => "targetName",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                //                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
        ),
        "afterPreProcessorInjector" => array(
            "enabled" => true,
            "gateSource" => "rsltItems",
            "gateTarget" => "items2_sum",
            "keys" => array(
                "sub_hpp" => "total_hpp_injector",
                "sub_hpp_riil" => "total_hpp_riil_injector",
                "sub_ppv_riil" => "total_ppv_riil_injector",
            ),
            "keysTarget" => array(
                "hpp_injector" => "total_hpp_injector/jml",
                "hpp_riil" => "total_hpp_riil_injector/jml",
                "ppv_riil" => "total_ppv_riil_injector/jml",
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "334" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp_sumber",// persediaan produk
                            "7010060" => "-hpp_sumber",// laba(rugi) perubahan grade produk
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
                            "1010030030" => "hpp_target",// persediaan produk
                            "7010060" => "hpp_target",// laba(rugi) perubahan grade produk
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
                            "1010030030" => "-hpp_sumber",// persediaan produk
                            "7010060" => "-hpp_sumber",// laba(rugi) perubahan grade produk
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
                            "1010030030" => "hpp_target",// persediaan produk
                            "7010060" => "hpp_target",// laba(rugi) perubahan grade produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // bila solo masuk ke efisiensi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020010" => "-hpp_sumber_rev",// efisiensi biaya
                            "7010060" => "hpp_sumber_rev",// laba(rugi) perubahan grade produk
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
                            "3020010" => "-hpp_sumber_rev",// efisiensi biaya
                            "7010060" => "hpp_sumber_rev",// laba(rugi) perubahan grade produk
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
                            "3020010" => "hpp_target_rev",// efisiensi biaya
                            "7010060" => "-hpp_target_rev",// laba(rugi) perubahan grade produk
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
                            "3020010" => "hpp_target_rev",// efisiensi biaya
                            "7010060" => "-hpp_target_rev",// laba(rugi) perubahan grade produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    //<editor-fold desc="subkomponen gudang finish goods (sumber konversi)">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="subkomponen gudang finish goods (hasil konversi), tidak dijual">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp_injector",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            //                            "produk_nilai" => "hpp",
                            "produk_nilai" => "hpp_injector",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //</editor-fold>
                ),
            ),
        ),
        "postProcessor" => array(
            "334r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "334" => array(
                "master" => array(),
                "detail" => array(

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".moved",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
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
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // hasil konversi...
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
//                            "hpp" => "harga",
//                            "jml_nilai" => "sub_harga",
                            "hpp" => "hpp_injector",
                            "jml_nilai" => "sub_hpp_injector",
                            "nama" => "nama",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "jml",
//                            "produk_id" => "id",
//                            "produk_nama" => "nama",
////                            "hpp" => "harga",
////                            "jml_nilai" => "sub_harga",
//                            "hpp" => "hpp_injector",
//                            "jml_nilai" => "sub_hpp_injector",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done selain solo, solo belum beres karena pembantu efisiesnsi biaya belum punya pembantu!
    //  config konversi supplies (satuan), branch
    "335" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|placeID|gudangID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "detail2" => array(//===sumber nilai berupa rincian
                "hpp" => "harga",
            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
            "hpp_target" => "hpp",
            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "335" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                            ),
                            //                            "items2" => array(
                            //                                "src_harga" => "sub_hpp",
                            //                                "src_hpp" => "sub_hpp",
                            //                                "id_src" => "produk_id",
                            //                                //                                "src_qty" => "produk_qty",
                            //                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",

                            "jenisTr" => "jenisTr",
                            "id_src" => "id_src",
                            "jml_per_satuan" => "jml_per_satuan",
                            "targetID" => "targetID",
                            "targetName" => "targetName",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "qty",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "Sync2Gates",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "(jml_per_satuan*jml)",
                            "produk_hrg" => "hpp",
                            "gudang_id" => "gudangID",

                            "extern_id_src" => "id_src",
                            "produk_hrg_src" => "subtotal",

                            "jml_per_satuan" => "jml_per_satuan",
                            "targetID" => "targetID",
                            "targetName" => "targetName",
                            "rowPreFifo" => "rowPreFifo",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "target_harga" => "hpp",
                                "target_hpp" => "hpp",
                                "target_subtotal" => "subtotal",
                            ),
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
        ),
        "preProcessorInjector" => array(
            "placeID" => "cabang_id",
            "gudangID" => "gudang_id",
            "jenisTr" => "jenisTr",
            "id_src" => "id_src",

            "jml_per_satuan" => "jml_per_satuan",
            "targetID" => "targetID",
            "targetName" => "targetName",

        ),
        "tableIn" => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName", "div_id" => "divID", "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
        ),
        "components" => array(
            "335" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),

                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "target_subtotal",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "targetID",
                            "extern_nama" => "targetName",
                            "produk_qty" => "(jml_per_satuan*jml)",
                            "produk_nilai" => "target_hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "335r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            //"transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "335" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".moved",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
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
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //region fifo target konversi
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "(jml_per_satuan*jml)",
                            "produk_id" => "targetID",
                            "hpp" => "target_hpp",
                            "jml_nilai" => "target_subtotal",
                            "nama" => "targetName",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                        //                        "srcGateName" => "items2",
                        //                        "srcRawGateName" => "items2",
                    ),
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "(jml_per_satuan*jml)",
                            "produk_id" => "targetID",
                            "produk_nama" => "targetName",
                            "hpp" => "target_hpp",
                            "jml_nilai" => "target_subtotal",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                        //                        "srcGateName" => "items2",
                        //                        "srcRawGateName" => "items2",
                    ),
                    //endregion


                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|placeID|gudangID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|placeID|gudangID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//konversi branch dimatikan
    // konversi supplies ke produk (branch)
    "2335" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //
                //                "produk_ids" => "produk_ids",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
            "hpp_target" => "harga",
            "nilai_selisih" => "harga-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "2335" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    //--------------------
//                    array(
//                        "comName" => "Sync2GatesInjector",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                            "jenisTr" => "jenisTr",
//                            "rowPreFifo" => "rowPreFifo",
//
//                            "target" => ".rsltItems",
//                            "source" => ".items2_sum",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id_target" => "id",
//                                "name_target" => "nama",
//                            ),
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "2335" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "1010030010" => "-hpp",// persediaan supplies
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
                            "1010030030" => "hpp",// persediaan produk
                            "1010030010" => "-hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    //jurnal ppv pusat
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "2010100010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "2010100010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // detail laba lain-lain
//                    array(
//                        "comName" => "RekeningPembantuLRLainlain",
//                        "loop" => array(
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => ".3",// laba rugi lain-lain ppv
//                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id_target",
                            "extern_nama" => "nama_target",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
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
            "2335r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "2335" => array(
                "master" => array(),
                "detail" => array(
                    //region sumber konversi

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".converted",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //endregion

                    //region target konversi

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id_target",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "nama" => "name_target",
                            "name" => "name_target",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "jml",
//                            "produk_id" => "id_target",
//                            "produk_nama" => "name_target",
//                            "hpp" => "harga",
//                            "jml_nilai" => "sub_harga",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //endregion
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//konversi branch dimatikan


    "1337" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //
                //                "produk_ids" => "produk_ids",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
//            "hpp_target" => "harga",
//            "nilai_selisih" => "harga-hpp",
            "hpp_target" => "hpp_injector",
            "nilai_selisih" => "hpp_injector-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "1337" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSuppliesConvertion",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                //                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                //                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
        ),
        "afterPreProcessorInjector" => array(
            "enabled" => true,
            "gateSource" => "rsltItems",
            "gateTarget" => "items2_sum",
            "keys" => array(
                "sub_hpp" => "total_hpp_injector",
                "sub_hpp_riil" => "total_hpp_riil_injector",
                "sub_ppv_riil" => "total_ppv_riil_injector",
            ),
            "keysTarget" => array(
                "hpp_injector" => "total_hpp_injector/jml",
                "hpp_riil" => "total_hpp_riil_injector/jml",
                "ppv_riil" => "total_ppv_riil_injector/jml",
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "1337" => array(
                "master" => array(
                    //<editor-fold desc="Com-jurnal dan rekening">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-hpp_sumber",// persediaan supplies
                            "7010070" => "-hpp_sumber",// laba(rugi) perubahan grade supplies
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
                            "1010030010" => "hpp_target",// persediaan supplies
                            "7010070" => "hpp_target",// laba(rugi) perubahan grade supplies
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
                            "1010030010" => "-hpp_sumber",// persediaan supplies
                            "7010070" => "-hpp_sumber",// laba(rugi) perubahan grade supplies
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
                            "1010030010" => "hpp_target",// persediaan supplies
                            "7010070" => "hpp_target",// laba(rugi) perubahan grade supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                ),
                "detail" => array(
                    //<editor-fold desc="subkomponen gudang supplies (sumber konversi)">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="subkomponen gudang supplies (hasil konversi), tidak dijual">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "sub_hpp_injector",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            //                            "produk_nilai" => "hpp",
                            //"produk_nilai" => "harga",
                            "produk_nilai" => "hpp_injector",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //</editor-fold>
                ),
            ),
        ),
        "postProcessor" => array(
            "1337r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "1337" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="sumber konversi">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".moved",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
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
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="target konversi">
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "jml",
                            "produk_id" => "id",
                            //"hpp" => "harga",
                            //"jml_nilai" => "sub_harga",
                            "hpp" => "hpp_injector",
                            "jml_nilai" => "sub_hpp_injector",
                            "nama" => "nama",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "jml",
//                            "produk_id" => "id",
//                            "produk_nama" => "nama",
//                            //"hpp" => "harga",
//                            //"jml_nilai" => "sub_harga",
//                            "hpp" => "hpp_injector",
//                            "jml_nilai" => "sub_hpp_injector",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //</editor-fold>
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done

    //config adjustment supplies pada aset
    "7620" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",
        ),
        "formatNota" => "stepCode|placeID|cabangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
                "customer_id" => ".-1",
                "customers_nama" => ".PT Indosan Berkat Bersama",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "grandTotal" => "hpp+ppn",
        ),
        "valueBuilders_rsltItems" => array(),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock"
            //            ),
            //            3 => array(
            //                "LockerStock"
            //            ),
        ),
        "preProcessor" => array(
            "7620" => array(
                "master" => array(
                    array(
                        "comName" => "SuppliesToAset",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",

                            "nilai" => "grandTotal", // nilai piutang pembelian total dari antisource yang dipilih...
                            "jenis" => ".cabang",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "gudang" => "gudang",
                                "gudang_nama" => "gudang_nama",
                                //                                "nilai_sisa" => "nilai_sisa",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
        ),
        "injectorPajak" => array(
            "source" => "hpp",
        ),
        "pairPajak" => array(
            "ppn" => "ppn",
            "grand_ppn" => "ppn",
            "new_grand_ppn" => "ppn",
            "dpp_ppn" => "dppPpn",
            // "grand_total_ui"=>"grandTotal",
            "grandTotal" => "grandTotal",
            "new_net3" => "grandTotal",
            // "nett1_bulat"=>"hasil",
            "ppn_out_bulat" => "ppn",
            "grand_pembulatan" => "grandTotal",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "detailValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
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
            "7620" => array(
                "master" => array(
                    //region jurnal aktiva pada supplies pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-hpp",// persediaan supplies
                            "{pihakMainRulesID_coa}" => "grandTotal",// kendaraan,bangunan,perlatan produksi
                            // "1010040050" => "ppn",// ppn masukan belum ada faktur
                            "2030060" => "ppn",// ppn keluran belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030010" => "-hpp",// persediaan supplies
                            "{pihakMainRulesID_coa}" => "grandTotal",// aktiva tetap
                            "2030060" => "ppn",// aktiva tetap
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                     array(
//                         "comName" => "RekeningPembantuAktivaTetap",
//                         "loop" => array(
//                             "1020" => "grandTotal",// aktiva tetap
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
// //                            "extern_id" => "pihakMainRulesID",// diisi id folder aktiva
// //                            "extern_nama" => "pihakMainRulesName",// diisi nama bank falder nama aktiva
//                             "extern_id" => "pihakMainRulesID_coa",// diisi id folder aktiva
//                             "extern_nama" => "pihakMainRulesName_coa",// diisi nama bank falder nama aktiva
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujudMain",
                        "loop" => array(
//                            "{pihakMainRulesName}" => "grand_total",
                            "{pihakMainRulesID_coa}" => "grandTotal",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakMainID",// diisi id folder aktiva
                            "extern_nama" => "pihakMainName",// diisi nama bank falder nama aktiva
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    //jurnal ppv pusat
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "2010100010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "2010100010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // detail laba lain-lain
//                    array(
//                        "comName" => "RekeningPembantuLRLainlain",
//                        "loop" => array(
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => ".3",// laba rugi lain-lain ppv
//                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //endregion

                    //region jurnal piutang cabang pada aktiva tetap
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060020" => "nilai_dipakai_cabang",// piutang aktiva tetap cabang
                            "{pihakMainRulesID_coa}" => "-nilai_dipakai_cabang",// aktiva tetap
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060020" => "nilai_dipakai_cabang",// piutang aktiva tetap cabang
                            "{pihakMainRulesID_coa}" => "-nilai_dipakai_cabang",// aktiva tetap
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060020" => "nilai_dipakai_cabang",// piutang aktiva tetap cabang
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAktivaTetap",
                        "loop" => array(
                            "{pihakMainRulesID_coa}" => "-nilai_dipakai_cabang",// aktiva tetap
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakMainRulesID_coa",// diisi id folder aktiva
                            "extern_nama" => "pihakMainRulesName_coa",// diisi nama bank falder nama aktiva
                            "produk_nilai" => "-nilai_dipakai_cabang",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujudMain",
                        "loop" => array(
                            "{pihakMainRulesID_coa}" => "-nilai_dipakai_cabang",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakMainID",// diisi id folder aktiva
                            "extern_nama" => "pihakMainName",// diisi nama bank falder nama aktiva
                            "produk_nilai" => "-nilai_dipakai_cabang",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainRulesID_coa}" => "nilai_dipakai_cabang",// aktiva tetap
                            "2040030" => "nilai_dipakai_cabang",//hutang aktiva tetap(AT)// hutang aktiva tetap pada dc
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "{pihakMainRulesID_coa}" => "nilai_dipakai_cabang",// aktiva tetap
                            "2040030" => "nilai_dipakai_cabang",//hutang aktiva tetap(AT)// hutang aktiva tetap pada dc
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                     array(
//                         "comName" => "RekeningPembantuAktivaTetap",
//                         "loop" => array(
//                             "1020" => "nilai_dipakai_cabang",// aktiva tetap
//                         ),
//                         "static" => array(
//                             "cabang_id" => "pihakID",
// //                            "extern_id" => "pihakMainRulesID",// diisi id bank
// //                            "extern_nama" => "pihakMainRulesName",// diisi nama bank
//                             "extern_id" => "pihakMainRulesID_coa",// diisi id bank
//                             "extern_nama" => "pihakMainRulesName_coa",// diisi nama bank
//                             "produk_nilai" => "nilai_dipakai_cabang",
//                             "gudang_id" => "gudang_cabang",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujudMain",
                        "loop" => array(
                            "{pihakMainRulesID_coa}" => "nilai_dipakai_cabang",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "extern_id" => "pihakMainID",// diisi item aktiva
                            "extern_nama" => "pihakMainName",// diisi nama item akiva inova xxx
                            "produk_nilai" => "nilai_dipakai_cabang",
                            "gudang_id" => "gudang_cabang",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040030" => "nilai_dipakai_cabang",//hutang aktiva tetap(AT)// hutang aktiva tetap pada dc
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "cabangID",
                            "cabang2_nama" => "cabangName",
                            "extern_id" => "cabangID",
                            "extern_nama" => "cabangName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion
                ),
                "detail" => array(

                    //<editor-fold desc="Com-rekening pembantu, detail">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //                    array(
                    //                        "comName" => "{pihak2Com}",
                    //                        "loop" => array(
                    //                            "{pihak2Name}" => "sub_hpp",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "pihak3ID",
                    //                            "extern_nama" => "pihak3Name",
                    //                            "jenis" => "jenisTr",
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),

                    //</editor-fold>


                ),
            ),
            "7620f" => array(
                "master" => array(
                    //region seleish ppn 10 vs 11 %
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2030060" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
                            "1020" => "-selisih_ppn_realisasi",//aktiva
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
                            "2030060" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
                            "1020" => "-selisih_ppn_realisasi",//hutang dagang
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
                        "comName" => "RekeningPembantuAktivaTetap",
                        "loop" => array(
                            "1020" => "-selisih_ppn_realisasi",//hutang dagang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakMainID_coa",//coa code
                            "extern_nama" => "pihakMainName_coa",//coa alias
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2030060" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PT Indosan Berkat Bersama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2030060" => "-ppn_realisasi",////ppn out belum ada faktur
                            "2030070" => "ppn_realisasi",//ppn out realisasi
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
                            "2030060" => "-ppn_realisasi",//ppn out belum ada faktur
                            "2030070" => "ppn_realisasi",//ppn out realisasi
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2030060" => "-ppn_realisasi",//ppn out belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PT Indosan Berkat Bersama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujud",
                        "loop" => array(
                            "{pihakMainID_coa}" => "-selisih_ppn_realisasi",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "note" => "note",
                            "produk_nilai" => "nilai_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "7620r" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Postproc locker stock">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            //"transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),
            "7620" => array(
                "master" => array(
                    //pusat supplies to aktiva in
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "pihakMainID",
                            "nama" => "pihakMainName",
                            "nilai" => "grandTotal",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //                    //aktiva sold
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "pihakMainID",
                            "nama" => "pihakMainName",
                            "nilai" => "-nilai_dipakai_cabang",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".sold",
                            "jenis" => ".aktiva",
                            "produk_id" => "pihakMainID",
                            "nama" => "pihakMainName",
                            "nilai" => "nilai_dipakai_cabang",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //cabang in aktiva cabang
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "gudang_id" => "gudang_cabang",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "pihakMainID",
                            "nama" => "pihakMainName",
                            "nilai" => "nilai_dipakai_cabang",
                            //                            "transaksi_id" => "transaksi_id",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pasang setup depresiasi
                    array(
                        "comName" => "SetupDepresiasiMain",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakMainID",
                            "extern_nama" => "pihakMainName",
                            "cabang_id" => "pihakID",
                            "gudang_id" => "gudang_cabang",
                            "economic_life_time" => ".0",
                            "jenis" => ".assets",
                            "harga_perolehan" => "nilai_dipakai_cabang",
                            //                            "mode" =>"update",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    //<editor-fold desc="Com-locker stock">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
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
                            "state" => ".sold",
                            "jumlah" => "qty",
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
                            "qty_debet" => "-qty",
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
            "7620f" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-selisih_ppn_realisasi",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Signature",
                        "loop" => array(
                            "transaksi_id" => "references",
                        ),
                        "static" => array(

                            "nomer" => "nomer",
                            "step_number" => ".3",
                            "step_code" => ".761ro",
                            "step_name" => ".request process",
                            "group_code" => ".sys",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "keterangan" => ".autostep by other transaction",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabangID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabangID",
    ),
    //config konversi supplies to asset baru
    "7622" => array(
        /*
         * ppn masukan di klaim sekan sendiri,
         * untuk pemakaian sendiri tidak ada ppn masukan dan keluran dengan pertimbagan NPW dan lokasi sama
         */
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",
        ),
        "formatNota" => "stepCode|placeID|cabangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "nett1" => "hpp",
                // "ppn" => "(hpp*(ppnFactor/100))",
                // "nett2" => "(nett1+ppn)",
                "subtotal" => "jml*nett1",

            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",
                "hpp" => "hpp",
            ),
        ),
        "valueBuilders" => array(
            //            "" => "",
            //            "" => "",
        ),
        "valueBuilders_rsltItems" => array(),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock"
            //            ),
            //            3 => array(
            //                "LockerStock"
            //            ),
        ),
        "preInjectValue" => array(
            3 => array(
                "master" => array(
                    array(
                        "comName" => "InjectValues",
                        "loop" => array(),
                        "static" => array(
                            "nilai" => "hpp",
                            "ppnFactor" => "ppnFactor",
                            "hpp_nppn" => "grandTotal",
                            "jenis" => ".cabang",
                        ),

                        "resultParams" => array(
                            "main" => array("nilai_dipakai", "nett2"),
                            "items2" => array("hpp", "harga", "sub_harga", "nett", "nett2", "subtotal"),
                            "items2_sum" => array("hpp", "harga", "sub_harga", "nett", "nett2", "subtotal", "nilai_dipakai"),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "gate" => array(
                    "main" => array("hpp", "harga", "subtotal", "nett1", "nett2", "grandTotal", "ppn"),
                ),
                "resetFields" => array("ppn", "hpp", "harga", "subtotal", "nett2", "nett", "nett1"),
                "calculate" => array(
                    // "ppn" => "ppn",
                    "harga" => "harga",
                    "nett" => "hpp",
                    "hpp" => "hpp",
                    "subtotal" => "subtotal",
                    "nett2" => "grandTotal",
                    // "nett2" => "nett2",
                ),
                "target" => array(
                    "session_target" => array("items2", "items2_sum"),
                    "fields" => array("ppn", "hpp", "harga", "subtotal", "nett2", "nett", "nett1"),
                ),
            ),

        ),
        "injectorPajak" => array(
            "source" => "harga",
        ),
        "pairPajak" => array(
            "ppn" => "ppn",
            "grand_ppn" => "ppn",
            "new_grand_ppn" => "ppn",
            "dpp_ppn" => "dppPpn",
            // "grand_total_ui"=>"grandTotal",
            "grandTotal" => "grandTotal",
            "new_net3" => "grandTotal",
            // "nett1_bulat"=>"hasil",
            "ppn_out_bulat" => "ppn",
            "grand_pembulatan" => "grandTotal",
        ),
        "preProcessor" => array(
            "7622" => array(
                "master" => array(
                    //                    array(
                    //                        "comName" => "SuppliesToAset",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "pihakID",
                    //                            "extern_nama" => "pihakName",
                    //
                    //                            "nilai" => "hpp", // nilai piutang pembelian total dari antisource yang dipilih...
                    //                            "jenis" => ".cabang",
                    //                        ),
                    //                        "resultParams" => array(
                    //                            "main" => array(
                    //                                "nilai_dipakai" => "nilai_dipakai",
                    //                                "gudang" => "gudang",
                    //                                "gudang_nama" => "gudang_nama",
                    ////                                "nilai_sisa" => "nilai_sisa",
                    //                            ),
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //                    array(
                    //                        "comName" => "LockerValue",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "gudang_id" => "gudangID",
                    //                            "state" => ".active",
                    //                            "jenis" => ".ppn out",
                    //                            "produk_id" => ".-1",
                    //                            "nama" => ".PT Indosan Bekat Bersama",
                    //                            "nilai" => "ppn",
                    ////                            "transaksi_id" => "masterID",
                    //                            "transaksi_id" => "currentID",
                    //                            "oleh_id" => ".0",
                    ////                            "paymentMethod" => "paymentMethod",
                    //                        ),
                    //                        "resultParams" => array(
                    //                            "main" => array(
                    //                                "nilai_dipakai" => "nilai_dipakai",
                    //                                "nilai_tambah" => "nilai_tambah",
                    //                            ),
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                                "subtotal" => "subtotal",
//                            ),
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "detailValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
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
            "7622" => array(
                "master" => array(
                    //region baru
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakID_coa}" => "grandTotal",// aktiva tetap
                            "1010030010" => "-hpp",// persediaan supplies
                            // "1010040050" => "ppn",// ppn in belum ada faktur ,,salah kamar harusnya ppn keluaran
                            "2030060" => "ppn",// ppn out beluma da faktur
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
                            "{pihakID_coa}" => "grandTotal",// aktiva tetap
                            "1010030010" => "-hpp",// persediaan supplies
                            "2030060" => "ppn",// ppn out beluma da faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    /*
                     * pembantu pakai item karena sudah satu level
                     */
                    // array(
                    //     "comName" => "RekeningPembantuAktivaTetap",
                    //     "loop" => array(
                    //         "{pihakID_coa}" => "grandTotal",// kendaraan ,
                    //     ),
                    //     "static" => array(
                    //         "cabang_id" => "placeID",
                    //         "extern_id" => "pihakMainID_coa",// isinya kendaraan, bangunan, tanah,
                    //         "extern_nama" => "pihakMainName_coa",
                    //         "produk_nilai" => "grandTotal",
                    //         "gudang_id" => "gudangID",
                    //         "jenis" => "jenisTr",
                    //         "transaksi_no" => "nomer",
                    //     ),
                    //     "srcGateName" => "main",
                    //     "srcRawGateName" => "main",
                    // ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2030060" => "ppn",// ppn out
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PT Indosan Berkat Bersama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //                    //jurnal ppv pusat
                    //                    array(
                    //                        "comName" => "Jurnal",
                    //                        "loop" => array(
                    //                            "2010100010" => "-ppv_riil",// hutang lain ppv
                    //                            "7010150" => "ppv_riil",// laba lain lain
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => ".-1",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //                    array(
                    //                        "comName" => "Rekening",
                    //                        "loop" => array(
                    //                            "2010100010" => "-ppv_riil",// hutang lain ppv
                    //                            "7010150" => "ppv_riil",// laba lain lain
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => ".-1",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //                    // detail laba lain-lain
                    //                    array(
                    //                        "comName" => "RekeningPembantuLRLainlain",
                    //                        "loop" => array(
                    //                            "7010150" => "ppv_riil",// laba lain lain
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => ".-1",
                    //                            "extern_id" => ".3",// laba rugi lain-lain ppv
                    //                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujud",
                        "loop" => array(
                            "{pihakMainID_coa}" => "nilai_dipakai",// isinya kendaraan, bangunan, tanah,
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "note" => "note",
                            "produk_nilai" => "nilai_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //<editor-fold desc="Com-rekening pembantu, detail">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>


                ),
            ),
            "7622f" => array(
                "master" => array(
                    //region seleish ppn 10 vs 11 %
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2030060" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
                            "{pihakID_coa}" => "-selisih_ppn_realisasi",//aktiva
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
                            "2030060" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
                            "{pihakID_coa}" => "-selisih_ppn_realisasi",//hutang dagang
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
                        "comName" => "RekeningPembantuAktivaTetap",
                        "loop" => array(
                            "{pihakID_coa}" => "-selisih_ppn_realisasi",//hutang dagang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakMainID_coa",//coa code
                            "extern_nama" => "pihakMainName_coa",//coa alias
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2030060" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PT Indosan Berkat Bersama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2030060" => "-ppn_realisasi",////ppn out belum ada faktur
                            "2030070" => "ppn_realisasi",//ppn out realisasi
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
                            "2030060" => "-ppn_realisasi",//ppn out belum ada faktur
                            "2030070" => "ppn_realisasi",//ppn out realisasi
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2030060" => "-ppn_realisasi",//ppn out belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PT Indosan Berkat Bersama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujud",
                        "loop" => array(
                            "{pihakMainID_coa}" => "-selisih_ppn_realisasi",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "note" => "note",
                            "produk_nilai" => "nilai_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "7622r" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Postproc locker stock">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            //"transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),
            "7622" => array(
                "master" => array(
                    array(
                        "comName" => "Signature",
                        "loop" => array(
                            "transaksi_id" => "references",
                        ),
                        "static" => array(

                            "nomer" => "nomer",
                            "step_number" => ".3",
                            "step_code" => ".761ro",
                            "step_name" => ".request process",
                            "group_code" => ".sys",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "keterangan" => ".autostep by other transaction",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    //<editor-fold desc="Com-locker stock">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
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
                            "state" => ".sold",
                            "jumlah" => "qty",
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
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                    //region aktiva
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "nilai_dipakai",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "PriceAktivaTetap",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "note" => "note",
                            "nilai" => "nilai_dipakai",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".aktiva",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".aktiva",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "note" => "note",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty",
                            "produk_nilai" => "nett2",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //endregion
                ),
            ),
            "7622f" => array(
                "master" => array(
                    array(
                        "comName" => "Signature",
                        "loop" => array(
                            "transaksi_id" => "references",
                        ),
                        "static" => array(

                            "nomer" => "nomer",
                            "step_number" => ".3",
                            "step_code" => ".761ro",
                            "step_name" => ".request process",
                            "group_code" => ".sys",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "keterangan" => ".autostep by other transaction",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //region aktiva
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-selisih_ppn_realisasi",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //endregion
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabangID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabangID",
    ),//done


    "1336" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
            "stepCode|referenceID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "subtotal" => "jml*hpp_avg",
            ),
            "detail2" => array(),
            "detail2_sum" => array(),
            "rsltItems" => array(),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
//            "hpp_target" => "harga",
//            "nilai_selisih" => "harga-hpp",
            "hpp_target" => "hpp_injector",
            "nilai_selisih" => "hpp_injector-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "1336sc" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberExtractorKonversi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
            ),
            "1336" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukKonversi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "target" => ".items4_sum",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageConvertion",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
//                        "resultParams" => array(
//                            "items" => array(
//                                //                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//
//                            "jenisTr" => "jenisTr",
//                            "id_src" => "id_src",
//                            "jml_per_satuan" => "jml_per_satuan",
//                            "targetID" => "targetID",
//                            "targetName" => "targetName",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                //                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                ),
            ),
        ),
        "afterPreProcessorInjector" => array(
            "enabled" => true,
            "gateSource" => "rsltItems",
            "gateTarget" => "items2_sum",
            "keys" => array(
                "sub_hpp" => "total_hpp_injector",
                "sub_hpp_riil" => "total_hpp_riil_injector",
                "sub_ppv_riil" => "total_ppv_riil_injector",
            ),
            "keysTarget" => array(
                "hpp_injector" => "total_hpp_injector/jml",
                "hpp_riil" => "total_hpp_riil_injector/jml",
                "ppv_riil" => "total_ppv_riil_injector/jml",
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "1336" => array(
                "master" => array(
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "-hpp_sumber",// persediaan produk
//                            "7010060" => "-hpp_sumber",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "hpp_target",// persediaan produk
//                            "7010060" => "hpp_target",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010030030" => "-hpp_sumber",// persediaan produk
//                            "7010060" => "-hpp_sumber",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010030030" => "hpp_target",// persediaan produk
//                            "7010060" => "hpp_target",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                ),
                "detail" => array(
                    // persediaan produk berkurang
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp_avg",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp_avg",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // persediaan produk bertambah, hasil konversi
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp_avg",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp_avg",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),


                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items6_sum",
                        "srcRawGateName" => "items6_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "1336r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "1336" => array(
                "master" => array(),
                "detail" => array(
                    //region sumber konversi
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".moved",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                    //region target konversi
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp_avg",
                            "jml_nilai" => "sub_hpp_avg",
                            "nama" => "nama",
                            "hpp_riil" => "hpp_avg",
                            "jml_nilai_riil" => "sub_hpp_avg",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp_avg",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    //endregion


                    // serial number produk
                    array(
                        "comName" => "ProdukSerialNumber",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "produk_konversi_id",
                            "produk_nama" => "produk_konversi_nama",
                            "produk_serial_number" => "serial_number",
                            "produk_sku" => "produk_sku",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "gudangID",
                            //---------------
                            "transaksi_reference_id" => "referenceID",
                            "transaksi_reference_no" => "referenceNomer",
                            "transaksi_reference_dtime" => "referenceDate",
                            "transaksi_reference_fulldate" => "referenceFulldate",
                            "transaksi_reference_count" => "referenceCount",
                            "transaksi_count" => "transaksi_count",
                            "transaksi_jenis_count" => "transaksi_jenis_count",
                            "part_keterangan" => "part_keterangan",
                        ),
                        "srcGateName" => "items6_sum",
                        "srcRawGateName" => "items6_sum",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberUpdate",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "produk_konversi_id",
                            "produk_nama" => "produk_konversi_nama",
                            "produk_serial_number" => "serial_number",
                            "produk_sku" => "produk_sku",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done
    "336" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
            "stepCode|referenceID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "subtotal" => "jml*hpp_avg",
            ),
            "detail2" => array(),
            "detail2_sum" => array(),
            "rsltItems" => array(),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
//            "hpp_target" => "harga",
//            "nilai_selisih" => "harga-hpp",
            "hpp_target" => "hpp_injector",
            "nilai_selisih" => "hpp_injector-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "336sc" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberExtractorKonversi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
            ),
            "336" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukKonversi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "target" => ".items4_sum",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageConvertion",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
//                        "resultParams" => array(
//                            "items" => array(
//                                //                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//
//                            "jenisTr" => "jenisTr",
//                            "id_src" => "id_src",
//                            "jml_per_satuan" => "jml_per_satuan",
//                            "targetID" => "targetID",
//                            "targetName" => "targetName",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                //                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                ),
            ),
        ),
        "afterPreProcessorInjector" => array(
            "enabled" => true,
            "gateSource" => "rsltItems",
            "gateTarget" => "items2_sum",
            "keys" => array(
                "sub_hpp" => "total_hpp_injector",
                "sub_hpp_riil" => "total_hpp_riil_injector",
                "sub_ppv_riil" => "total_ppv_riil_injector",
            ),
            "keysTarget" => array(
                "hpp_injector" => "total_hpp_injector/jml",
                "hpp_riil" => "total_hpp_riil_injector/jml",
                "ppv_riil" => "total_ppv_riil_injector/jml",
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "336" => array(
                "master" => array(
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "-hpp_sumber",// persediaan produk
//                            "7010060" => "-hpp_sumber",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "hpp_target",// persediaan produk
//                            "7010060" => "hpp_target",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010030030" => "-hpp_sumber",// persediaan produk
//                            "7010060" => "-hpp_sumber",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010030030" => "hpp_target",// persediaan produk
//                            "7010060" => "hpp_target",// laba(rugi) perubahan grade produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                ),
                "detail" => array(
                    // persediaan produk berkurang
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp_avg",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp_avg",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // persediaan produk bertambah, hasil konversi
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp_avg",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp_avg",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items6_sum",
                        "srcRawGateName" => "items6_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "336r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "336" => array(
                "master" => array(),
                "detail" => array(
                    //region sumber konversi
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".moved",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                    //region target konversi
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp_avg",
                            "jml_nilai" => "sub_hpp_avg",
                            "nama" => "nama",
                            "hpp_riil" => "hpp_avg",
                            "jml_nilai_riil" => "sub_hpp_avg",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),


                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp_avg",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    //endregion


                    // serial number produk
                    array(
                        "comName" => "ProdukSerialNumber",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "produk_konversi_id",
                            "produk_nama" => "produk_konversi_nama",
                            "produk_serial_number" => "serial_number",
                            "produk_sku" => "produk_sku",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items6_sum",
                        "srcRawGateName" => "items6_sum",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberUpdate",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "produk_konversi_id",
                            "produk_nama" => "produk_konversi_nama",
                            "produk_serial_number" => "serial_number",
                            "produk_sku" => "produk_sku",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done


    "1339" => array(
        "counters" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",
            "stepCode|referenceID",
        ),
        "formatNota" => "stepCode|placeID|gudangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "placeID",
                "pihakName" => "placeName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "subtotal" => "jml*hpp_avg",
                "sisa_dipakai" => "satuan_nilai-total_dipakai",
            ),
            "detail2" => array(),
            "detail2_sum" => array(),
            "rsltItems" => array(),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
//            "hpp_target" => "harga",
//            "nilai_selisih" => "harga-hpp",
            "hpp_target" => "hpp_injector",
            "nilai_selisih" => "hpp_injector-hpp",
        ),
        "valueBuilders2" => array(
            //            "hpp_target" => "sub_hpp",
            //            "harga_target" => "sub_harga",
            //            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "hpp_target" => "hpp",
        ),
        "valueBuilders_rsltItems" => array(
            //            "hpp_sumber" => "harga",
            //            "harga" => "sub_harga",
            //            "nilai_selisih" => "harga-hpp",
        ),
        "preProcessor" => array(
            "1339sc" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberExtractorKonversiPotong",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
            ),
            "1339" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukKonversiHitung",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "target" => ".items4_sum",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "ProdukKonversi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "target" => ".items4_sum",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // mengeluarkan produk sumber konversi
                    array(
                        "comName" => "FifoAverageConvertion",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                //                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "afterPreProcessorInjector" => array(
            "enabled" => true,
            "gateSource" => "rsltItems",
            "gateTarget" => "items2_sum",
            "keys" => array(
                "sub_hpp" => "total_hpp_injector",
                "sub_hpp_riil" => "total_hpp_riil_injector",
                "sub_ppv_riil" => "total_ppv_riil_injector",
            ),
            "keysTarget" => array(
                "hpp_injector" => "total_hpp_injector/jml",
                "hpp_riil" => "total_hpp_riil_injector/jml",
                "ppv_riil" => "total_ppv_riil_injector/jml",
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detail2_sum" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
            "detail2" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "detail2_sum" => array(
                "trash" => 0,
                "produk_jenis" => "produk_target",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "1339" => array(
                "master" => array(),
                "detail" => array(
                    // persediaan produk berkurang, sumber konversi
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp_avg",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp_avg",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // persediaan produk bertambah, hasil konversi
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "hpp_spec_jml",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp_spec_satuan",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

//                    // rekening pembantu produk serial
//                    array(
//                        "comName" => "RekeningPembantuProdukPerSerial",
//                        "loop" => array(
//                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "extern_id" => ".0",
//                            "extern_nama" => "produk_serial",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "produk_sku_part_nama",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => ".1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "supplierID" => "pihakID",
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "1339r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "1339sc" => array(
                "master" => array(),
                "detail" => array(
                    // serial number produk hasil konversi masuk
                    array(
                        "comName" => "ProdukSerialNumber",
                        "loop" => array(),
                        "static" => array(
//                            "jenis"=>".produk",
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "produk_serial_number" => "serial_number",
//                            "produk_sku" => "produk_sku",
                            "produk_sku" => "kode",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
//                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_nama" => "kode",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "gudangID",
                            "transaksi_reference_id" => "transaksi_id",
                            "transaksi_reference_no" => "nomer",
                            "transaksi_reference_dtime" => "transaksi_reference_dtime",
                            "transaksi_reference_fulldate" => "transaksi_reference_fulldate",
                            "transaksi_reference_count" => "referenceCount",
                            "transaksi_count" => "transaksi_count",
                            "transaksi_jenis_count" => "transaksi_jenis_count",
                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
                        "srcGateName" => "items6_sum",
                        "srcRawGateName" => "items6_sum",
                    ),
                ),
            ),
            "1339" => array(
                "master" => array(),
                "detail" => array(
                    //region sumber konversi
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".moved",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion

                    //region target konversi
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp_spec_satuan",
                            "jml_nilai" => "hpp_spec_jml",
                            "nama" => "nama",
                            "hpp_riil" => "hpp_spec_satuan",
                            "jml_nilai_riil" => "hpp_spec_jml",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp_avg",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    //endregion

                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID|gudangID",
        "countersReject" => array(
            "stepCode|placeID|gudangID",
            "stepCode|placeID|olehID",
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|gudangID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID|gudangID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|gudangID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|gudangID",
    ),//done

);