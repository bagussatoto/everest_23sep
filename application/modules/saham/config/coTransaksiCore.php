<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(

    //  config pemindahan rekening kas (center)
    "6666" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "place2ID" => "placeID",
                "place2Name" => "placeName",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "saldo_akhir" => "saldo_awal-nilai_source",
            ),
            "detail2" => array(//===sumber nilai berupa rincian

            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                "saldo_akhir" => "saldo_awal+nilai_target",
            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
//            "hpp_sumber" => "hpp",
//                        "harga" => "harga",
        ),
        "valueBuilders2" => array(
//            "hpp_target" => "hpp",
//            "harga_target" => "harga",
//            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
        ),
        "valueBuilders_rsltItems" => array(
//            "hpp_sumber" => "hpp",
//            "harga" => "harga",
        ),
        "preProcessor" => array(),
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
            "mainValues" => array(
                "hpp" => "hpp",
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
                "hpp" => "harga",
            ),
            "detail2" => array(
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
            "detailValues2" => array(
                "hpp" => "harga",
            ),
            "detail_rsltItems" => array(
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
            "rsltItemsValues" => array(
                "hpp" => "hpp",
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
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "6666" => array(
                "master" => array(
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "-harga",//kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "rekID",
//                            "extern_nama" => "rekName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "harga",//kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account",
//                            "extern_nama" => "cash_account__nama",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                ),
                "detail" => array(
                    // mengurangi modal yang diambikl
                    array(
                        "comName" => "RekeningPembantuModalItem",
                        "loop" => array(
                            "3010020" => "-sub_nilai_source",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
//                            "produk_qty" => "-jml",
                            "produk_nilai" => "nilai_source",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // menambah modal per-pembantu
                    array(
                        "comName" => "RekeningPembantuModalItem",
                        "loop" => array(
                            "3010020" => "sub_nilai_target",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
//                            "produk_qty" => "-jml",
                            "produk_nilai" => "nilai_target",
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
//            "6666r" => array(
//                "master" => array(
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_source",
//                            "nama" => "cash_account_source__label",
//                            //                            "nilai" => "-subtotal",
//                            "nilai" => "-harga",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".hold",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_source",
//                            "nama" => "cash_account_source__label",
//                            //                            "nilai" => "subtotal",
//                            "nilai" => "harga",
//                            //                            "transaksi_id" => "currentID",//ini gagal di grab
//                            "transaksi_id" => "transaksi_id",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "Jurnal_activity",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".1",
//                            //                            "step_number" => "step_number",
//                            "nilai" => ".1",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Jurnal_activityMain",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".1",
//                            //                            "step_number" => "step_number",
//                            "nilai" => ".1",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//            ),
//            "6666" => array(
//                "master" => array(
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".hold",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_source",
//                            "nama" => "cash_account_source__label",
//                            "nilai" => "-harga",
//                            "transaksi_id" => "currentID",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".moved",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_source",
//                            "nama" => "cash_account_source__label",
//                            "nilai" => "harga",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            //                            "produk_id" => "cash_account_target",
//                            //                            "nama" => "cash_account_target__label",
//                            "produk_id" => "cash_account",
//                            "nama" => "cash_account__nama",
//                            "nilai" => "harga",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "Jurnal_activity",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".2",
//                            //                            "step_number" => "step_number",
//                            "nilai" => ".1",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Jurnal_activityMain",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".2",
//                            //                            "step_number" => "step_number",
//                            "nilai" => ".1",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|pihakID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),//done
    "757" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "placeID",
                //                "pihakName" => "placeName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                "place2ID" => "placeID",
                "place2Name" => "placeName",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                //                "rekID" => "rekID",
                //                "rekName" => "rekName",
                //                "rekID2" => "rekID2",
                //                "rekName2" => "rekName2",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "detail2" => array(//===sumber nilai berupa rincian

            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian

            ),
            "rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "sub_hpp",
            //            "harga" => "sub_harga",
        ),
        "valueBuilders2" => array(
            "hpp_target" => "sub_hpp",
            "harga_target" => "sub_harga",
            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
        ),
        "valueBuilders_rsltItems" => array(
            "hpp_sumber" => "sub_hpp",
            //            "harga" => "sub_harga",
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
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detailValues" => array(
                "hpp" => "harga",
            ),
            "detail2" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
            ),
            "detailValues2" => array(
                "hpp" => "harga",
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
            "rsltItemsValues" => array(
                "hpp" => "hpp",
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
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "757" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-harga",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_source",
                            "extern_nama" => "cash_account_source__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "harga",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target",
                            "extern_nama" => "cash_account_target__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "757r" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "-harga",
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
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "harga",
                            //                            "transaksi_id" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
                            //                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
                            //                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
            ),
            "757" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "-harga",
                            "transaksi_id" => "masterID",
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
                            "gudang_id" => ".0",
                            "state" => ".moved",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "harga",
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
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_target",
                            "nama" => "cash_account_target__label",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
                            //                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
                            //                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),//done

);