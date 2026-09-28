<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(

    "9999" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

            ),
            "detail" => array(//===sumber nilai berupa rincian
                // "saldo_rekening_after"=>"saldo_rekening_before-debet",
            ),
        ),
        "valueBuilders" => array(
            //            "debet" => "debet",
            //            "kredit" => "kredit",
        ),
        "preProcessor" => array(),
        "preInjectedjurnal" => array(
            "9999" => array(
                "master" => array(
                    array(
                        "buildComponent" => true,
                        // "builderCom"=>array("Jurnal","Rekening"),
                        "comName" => "JurnalAdjustmentTahunan",
                        "loop" => array(),
                        "static" => array(
                            "cabang2_id" => "placeID",
                            "gudang2_id" => "gudangID",
                            "cabang_id" => "pihakID",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "olehID" => "olehID",
                            "olehName" => "olehName",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                        "resultGate" => "rsltItems",
                        "recapStatic" => array(
                            "pihakID" => "pihakID",
                            "gudang2ID" => "gudang2ID",
                            "placeID" => "placeID",
                            "gudangID" => "gudangID",
                            "jenisTr" => "jenisTr",
                            "olehID" => "olehID",
                            "olehName" => "olehName",
                        ),
                    ),
                ),
                "detail" => array(
                    array(
                        "buildComponent" => true,
                        "comName" => "JurnalAdjustmentTahunanItems",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "extern_id",
                            "extern_nama" => "extern_nama",
                            "cabang2_id" => "cabang_id",
                            "gudang2_id" => "cabang_id",
                            "cabang_id" => "pihakID",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "olehID" => "olehID",
                            "olehName" => "olehName",
                            "comName" => "comName",
                        ),
                        "srcGateName" => "items7_sum",
                        "srcRawGateName" => "items7_sum",
                        "resultGate" => "rsltItems2",
                    ),
                ),
            ),
        ),
        "AutoPostProc" => array(
            "9999" => array(
                "master" => array(
                    array(
                        "comName" => "NeracaAdjTmp",
                        "loop" => array(),
                        "static" => array(
//                            "cabang2_id" => "pihakID",
//                            "gudang2_id" => "gudang2ID",
//                            "cabang_id"=>"placeID",
//                            "gudang_id"=>"gudangID",
                            "cabang2_id" => "placeID",
                            "gudang2_id" => "gudangID",
                            "cabang_id" => "pihakID",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "thn" => "dateTarget",
                            "periode" => ".tahunan",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
                "detail" => array(),
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
                "transaksi_nilai" => "srcDefValue",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "mainValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                // "hpp" => "harga",
                // "satuan" => "satuan",
            ),
            "detailValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "account",
            ),
        ),
        "components" => array(/*
             * dibuild otomatis dari preInjectedJurnal ->master
             */
        ),
        "postProcessor" => array(/*
             * pakai AutoPostProc
             */
        ),
        "runBuilderLaporanPrev" => array(
            "9999" => true,
        )//untuk njalanin engine pembentuk laporan tahun sebelumnya
    ),
    "9990" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

            ),
            "detail" => array(//===sumber nilai berupa rincian
                // "saldo_rekening_after"=>"saldo_rekening_before-debet",
            ),
        ),
        "valueBuilders" => array(
            //            "debet" => "debet",
            //            "kredit" => "kredit",
        ),
        "preProcessor" => array(),
        "preInjectedjurnal" => array(
            "9990" => array(
                "master" => array(
                    array(
                        "buildComponent" => true,
                        // "builderCom"=>array("Jurnal","Rekening"),
                        "comName" => "JurnalAdjustmentTahunan",
                        "loop" => array(),
                        "static" => array(
                            "cabang2_id" => "placeID",
                            "gudang2_id" => "gudangID",
                            "cabang_id" => "pihakID",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "olehID" => "olehID",
                            "olehName" => "olehName",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                        "resultGate" => "rsltItems",
                        "recapStatic" => array(
                            "pihakID" => "pihakID",
                            "gudang2ID" => "gudang2ID",
                            "placeID" => "placeID",
                            "gudangID" => "gudangID",
                            "jenisTr" => "jenisTr",
                            "olehID" => "olehID",
                            "olehName" => "olehName",
                        ),
                    ),
                ),
                "detail" => array(
                    array(
                        "buildComponent" => true,
                        "comName" => "JurnalAdjustmentTahunanItems",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "extern_id",
                            "extern_nama" => "extern_nama",
                            "cabang2_id" => "cabang_id",
                            "gudang2_id" => "cabang_id",
                            "cabang_id" => "pihakID",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "olehID" => "olehID",
                            "olehName" => "olehName",
                            "comName" => "comName",
                        ),
                        "srcGateName" => "items7_sum",
                        "srcRawGateName" => "items7_sum",
                        "resultGate" => "rsltItems2",
                    ),
                ),
            ),
        ),
        "AutoPostProc" => array(
            /*
             * dimatiin karena tidak perlu nulis ke tabel adjustmentneraca tmp karena tidak akan dibuild ulang laporan
             */
//             "9999"=>array(
//                 "master"=>array(
//                     array(
//                         "comName" => "NeracaAdjTmp",
//                         "loop" => array(),
//                         "static" => array(
// //                            "cabang2_id" => "pihakID",
// //                            "gudang2_id" => "gudang2ID",
// //                            "cabang_id"=>"placeID",
// //                            "gudang_id"=>"gudangID",
//                             "cabang2_id" => "placeID",
//                             "gudang2_id" => "gudangID",
//                             "cabang_id"=>"pihakID",
//                             "gudang_id"=>"gudang2ID",
//                             "jenis"=>"jenisTr",
//                             "oleh_id"=>"olehID",
//                             "oleh_nama"=>"olehName",
//                             "thn"=>"dateTarget",
//                             "periode"=>".tahunan",
//                         ),
//                         "srcGateName" => "items3_sum",
//                         "srcRawGateName" => "items3_sum",
//                     ),
//                 ),
//                 "detail"=>array(),
//             ),
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
                "transaksi_nilai" => "srcDefValue",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "mainValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                // "hpp" => "harga",
                // "satuan" => "satuan",
            ),
            "detailValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "account",
            ),
        ),
        "components" => array(/*
             * dibuild otomatis dari preInjectedJurnal ->master
             */
        ),
        "postProcessor" => array(/*
             * pakai AutoPostProc
             */
        ),
        "runBuilderLaporanPrev" => array(
            "9990" => false,
        )//karena hanya melakukan adjustment di tahun berjalan saja karena salah posting jurnal
    ),


);