<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(

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

            ),
        ),
        "valueBuilders" => array(
            //            "debet" => "debet",
            //            "kredit" => "kredit",
        ),
        "preProcessor" => array(),
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
                "hpp" => "harga",
                "satuan" => "satuan",
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
        "components" => array(
            "999" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{srcAccount}" => "srcDefValue",
                            "{targetAccount}" => "targetDefValue",
                            "{target3Account}" => "target3DefValue",
                            "{target4Account}" => "target4DefValue",
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
                            "{srcAccount}" => "srcDefValue",
                            "{targetAccount}" => "targetDefValue",
                            "{target3Account}" => "target3DefValue",
                            "{target4Account}" => "target4DefValue",
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
                        "comName" => "{srcRel}",
                        "loop" => array(
                            "{srcAccount}" => "srcDefValue",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "extern1",
                            "extern_nama" => "extern1__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "{targetRel}",
                        "loop" => array(
                            "{targetAccount}" => "targetDefValue",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "extern2",
                            "extern_nama" => "extern2__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    array(
//                        "comName" => "{target3Rel}",
//                        "loop" => array(
//                            "{target3Account}" => "target3DefValue",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "extern3",
//                            "extern_nama" => "extern3__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

//                    array(
//                        "comName" => "{target4Rel}",
//                        "loop" => array(
//                            "{target4Account}" => "target4DefValue",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "extern4",
//                            "extern_nama" => "extern4__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "piutang biaya cabang" => "kredit",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cabang",
//                            "extern_nama" => "cabang__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(),
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

            ),
        ),
        "valueBuilders" => array(
            //            "debet" => "debet",
            //            "kredit" => "kredit",
        ),
        "preProcessor" => array(),
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
                "hpp" => "harga",
                "satuan" => "satuan",
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
        "components" => array(
            "999" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{srcAccount}" => "srcDefValue",
                            "{targetAccount}" => "targetDefValue",
                            "{target3Account}" => "target3DefValue",
                            "{target4Account}" => "target4DefValue",
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
                            "{srcAccount}" => "srcDefValue",
                            "{targetAccount}" => "targetDefValue",
                            "{target3Account}" => "target3DefValue",
                            "{target4Account}" => "target4DefValue",
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
                        "comName" => "{srcRel}",
                        "loop" => array(
                            "{srcAccount}" => "srcDefValue",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "extern1",
                            "extern_nama" => "extern1__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "{targetRel}",
                        "loop" => array(
                            "{targetAccount}" => "targetDefValue",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "extern2",
                            "extern_nama" => "extern2__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    array(
//                        "comName" => "{target3Rel}",
//                        "loop" => array(
//                            "{target3Account}" => "target3DefValue",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "extern3",
//                            "extern_nama" => "extern3__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

//                    array(
//                        "comName" => "{target4Rel}",
//                        "loop" => array(
//                            "{target4Account}" => "target4DefValue",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "extern4",
//                            "extern_nama" => "extern4__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "piutang biaya cabang" => "kredit",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cabang",
//                            "extern_nama" => "cabang__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(),
    ),

);