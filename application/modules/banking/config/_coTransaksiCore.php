<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(
    //penambahan hutang bank
    "444" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
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

                "bank_id" => "pihakID",
                "bank_nama" => "pihakName",
                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "bank",
            ),
        ),

        "components" => array(
            "444" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang bank" => "harga",
                            "kas" => "harga",
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
                            "hutang bank" => "harga",
                            "kas" => "harga",
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
                        "comName" => "RekeningPembantuBank",
                        "loop" => array(
                            "hutang bank" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "pihakID",//id folder rekening koran
                            "extern2_nama" => "pihakName",//label folder rekening koran
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
                        "loop" => array(
                            //                            "h" => "harga",//
                            "hutang bank" => "harga",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",//id relasi rekening koran
                            "extern2_id" => "pihakID",//id folder rekening koran
                            "extern2_nama" => "pihakName",//label folder rekening koran
                            "extern_nama" => ".non rekening koran",//lbel relasi rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//rekening pembantu level 2

                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuRekeningKoran",//rekening pembantu level 3
                        "loop" => array(
                            //                            "rekening koran" => "harga",//
                            "non rekening koran" => "harga",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",//id rekening koran
                            "extern2_id" => "pihakID",//lbel rekening koran
                            "extern2_nama" => "pihakName",//lbel rekening koran
                            "extern_nama" => "nama",//lbel rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "produk_nilai" => "harga",
                            "produk_qty" => ".1",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "444" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
    //config penambahan hutang terhadadap pihak 3
    "447" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
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

                "bank_id" => "pihakID",
                "bank_nama" => "pihakName",
                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "bank",
            ),
        ),

        "components" => array(
            "447" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "kas" => "harga",
                            "hutang ke pihak lain" => "harga",
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
                            "kas" => "harga",
                            "hutang ke pihak lain" => "harga",
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuHutangPihak3Item",
                        "loop" => array(
                            "hutang ke pihak lain" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
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
            "447" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSourceDetail",//untuk nilis ke payment source karena gerbang dari detail, di trnasksi misc di off kan ya bro
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".hutang ke pihak lain",
                            "jenis" => "jenisTr",
                            "target_jenis" => ".4411",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "0",
                            "tagihan" => "harga",
                            "sisa" => "harga",
                            "nomer" => "nomer",
                            "reference_jenis" => "jenisTr",
                            "extern_nilai_2" => "harga",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    //config penambahan modal pemegang saham
    "445" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
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

                "bank_id" => "pihakID",
                "bank_nama" => "pihakName",
                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "bank",
            ),
        ),

        "components" => array(
            "445" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "modal" => "harga",
                            "kas" => "harga",
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
                            "modal" => "harga",
                            "kas" => "harga",
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuModalItem",
                        "loop" => array(
                            "modal" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
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
            "445" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
    //config penambahan hutang kepemegang saham
    "446" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
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

                "bank_id" => "pihakID",
                "bank_nama" => "pihakName",
                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "bank",
            ),
        ),
        "components" => array(
            "446" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "kas" => "harga",
                            "hutang ke pemegang saham" => "harga",
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
                            "kas" => "harga",
                            "hutang ke pemegang saham" => "harga",
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuHutangSahamItem",
                        "loop" => array(
                            "hutang ke pemegang saham" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
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
            "446" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSourceDetail",//untuk nilis ke payment source karena gerbang dari detail, di trnasksi misc di off kan ya bro
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".hutang ke pemegang saham",
                            "jenis" => "jenisTr",
                            "target_jenis" => ".4448",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "0",
                            "tagihan" => "harga",
                            "sisa" => "harga",
                            "nomer" => "nomer",
                            "reference_jenis" => "jenisTr",
                            "extern_nilai_2" => "harga",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    //  config pemindahan rekening kas (center)
    "1757" => array(
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

            ),
            "detail2" => array(//===sumber nilai berupa rincian

            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian

            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "hpp_sumber" => "hpp",
            //            "harga" => "harga",
        ),
        "valueBuilders2" => array(
            "hpp_target" => "hpp",
            "harga_target" => "harga",
            "nilai_selisih" => "hpp_target-hpp_sumber",
        ),
        "valueBuilders2_sum" => array(
            //            "hpp"   => "sub_hpp",
            //            "harga" => "sub_harga",
        ),
        "valueBuilders_rsltItems" => array(
            "hpp_sumber" => "hpp",
            "harga" => "harga",
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
            "1757" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "rekID",
                            "extern_nama" => "rekName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__nama",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1757r" => array(
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
                            //                            "nilai" => "-subtotal",
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
                            //                            "nilai" => "subtotal",
                            "nilai" => "harga",
                            //                            "transaksi_id" => "currentID",//ini gagal di grab
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
            "1757" => array(
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
                            "transaksi_id" => "currentID",
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
                            //                            "produk_id" => "cash_account_target",
                            //                            "nama" => "cash_account_target__label",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
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
    ),
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
                            "kas" => "-harga",
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
                            "kas" => "harga",
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
    ),
    //  config penambahan plafon hutang bank
    "4470" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID|olehID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "cabang2ID" => "cabang2ID",
                //                "cabang2Name" => "cabang2Name",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                //                "gudang2ID" => "gudang2ID",
                //                "gudang2Name" => "gudang2Name",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "lastPlafon" => "lastPlafon",
                "newPlafon" => "lastPlafon+addPlafon",
                "selisihPlafon" => "newPlafon-lastPlafon",
            ),
        ),
        "valueBuilders" => array(
            //            "hpp_sumber" => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "nett" => "sub_nett",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
            //            "selisihPlafon" => "selisihPlafon",
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

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nilai_bayar",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "bank_rekening_id" => "cash_id",
                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => ".1",
                "produk_ord_hrg" => "nilai_bayar",
            ),
            "detailValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                "lastPlafon" => "lastPlafon",
                "newPlafon" => "newPlafon",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "plafon",
            ),
        ),
        "components" => array(
            1 => array(
                "master" => array(),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            1 => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "selisihPlafon",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                        "reversable" => true,
                    ),

                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockPlafonBankMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "debet" => "selisihPlafon",
                            "produk_nilai" => "selisihPlafon",
                            "gudang_id" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //untuk update / insert plafon baru ke table plafon
                    array(
                        "comName" => "plafonData",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "nilai" => "selisihPlafon",
                            "gudang_id" => ".0",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
        ),
    ),
    //  config pengurangan plafon rekening koran
    //belum fix masih aneh
    "4970" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID|olehID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "cabang2ID" => "cabang2ID",
                //                "cabang2Name" => "cabang2Name",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                //                "gudang2ID" => "gudang2ID",
                //                "gudang2Name" => "gudang2Name",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "lastPlafon" => "pettycash_plafon__saldo",
                //                "lastPlafon" => "lastPlafon",
                "newPlafon" => "lastPlafon-addPlafon",
                //                "selisihPlafon" => "lastPlafon-(newPlafon)",
                "selisihPlafon" => "addPlafon",
            ),
        ),
        "valueBuilders" => array(
            //            "hpp_sumber" => "sub_hpp",
            //            "harga" => "sub_harga",
            //            "nett" => "sub_nett",
            //            "lastPlafon" => "lastPlafon",
            //            "newPlafon" => "newPlafon",
            //            "selisihPlafon" => "selisihPlafon",
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

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nilai_bayar",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "bank_rekening_id" => "cash_id",
                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => ".1",
                "produk_ord_hrg" => "addPlafon",
            ),
            "detailValues" => array(
                "hpp" => "addPlafon",
                "harga" => "addPlafon",
                "lastPlafon" => "lastPlafon",
                "newPlafon" => "newPlafon",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "plafon",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(
            "4970" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".hutang bank",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-selisihPlafon",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                        "reversable" => true,
                    ),
                ),
            ),
        ),
    ),
    //-----------up sudah modul -----

);