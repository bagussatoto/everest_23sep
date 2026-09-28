<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(
    //  config request, approve pettycash
    "671" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "gudangID" => "gudang",
                "gudangName" => "gudang__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
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
                //                "reference" => "reference",
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
            //            "hpp_sumber" => "sub_hpp",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
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
                "note" => "note",
                "reference" => "reference",
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
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(
            "671r" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".pettycash",
                            //                            "produk_id" => "placeID",
                            "produk_id" => "pettycash_account",
                            "nama" => "placeName",
                            "nilai" => "-harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".pettycash",
                            //                            "produk_id" => "placeID",
                            "produk_id" => "pettycash_account",
                            "nama" => "placeName",
                            "nilai" => "harga",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
    "672" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                //
                //                "place2ID" => "place2ID",
                //                "place2Name" => "place2Name",
                //                "cabang2ID" => "cabang2ID",
                //                "cabang2Name" => "cabang2Name",
                //                "gudang2ID" => "gudang2ID",
                //                "gudang2Name" => "gudang2Name",
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
                //                "reference" => "reference",
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
            //            "hpp_sumber" => "sub_hpp",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
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
                "note" => "note",
                "reference" => "reference",
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
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(),
    ),
    "771" => array(
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
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "cabang2ID" => "place2ID",
                //                "cabang2Name" => "place2Name",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                //                "gudang2ID" => "gudang2ID",
                //                "gudang2Name" => "gudang2Name",
                //                "masterID" => "masterID",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "refID" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "tagihan" => "tagihan",
                //                "terbayar" => "terbayar",
                //                "sisa" => "sisa",
                //                "nilai_bayar" => "nilai_bayar",
                //                "new_sisa" => "new_sisa",
                //
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                                "masterID" => "masterID",
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "nilai_bayar" => "bayar_total+totalCredit-diskon_factor",
            "nilai_bayar" => "nilai_entry+totalCredit",
            //            "additionalFactor" => "additional_value*additional",
        ),
        "valuePopulator" => array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
        ),

        "populators" => array(
            "nilai_bayar" => array(
                "mainSrc" => array(
                    "key" => "nilai_bayar",
                ),
                "itemTarget" => array(
                    "key" => "nilai_bayar",
                    "maxAmountSrc" => "sisa",
                ),
            ),
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-bayar_total",
            //            "new_sisa" => "sisa-additionalFactor",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "additionalFactor+sisa-totalCredit",
        ),

        "preProcessor" => array(
            // "671r" => array(
            //     "master" => array(),
            //     "detail" => array(
            //         array(
            //             "comName" => "ProduksiPreBiaya",
            //             "loop" => array(),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "gudang_id" => "gudangID",
            //                 "cabang2_id" => "place2ID",
            //                 "gudang2_id" => "gudang2ID",
            //                 "produk_id" => "id",
            //                 "nama" => "name",
            //                 "nilai" => "harga",
            //                 "jenisTr" => "jenisTr",
            //             ),
            //             "resultParams" => array(
            //                 "items2_sum" => array(
            //                     //                                "costName" => "pre_biaya_nama",
            //                     "costNilai" => "nilai",
            //                 ),
            //             ),
            //             "srcGateName" => "items2_sum",
            //             "srcRawGateName" => "items2_sum",
            //         ),
            //     ),
            // ),
            "771" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "ProduksiPreBiaya",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "cabang2_id" => "place2ID",
                            "gudang2_id" => "gudang2ID",
                            "produk_id" => "id",
                            "nama" => "name",
                            "nilai" => "harga",
                            "jenisTr" => "jenisTr",
                        ),
                        "resultParams" => array(
                            "items2_sum" => array(
                                //                                "costName" => "pre_biaya_nama",
                                "costNilai" => "nilai",
                            ),
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
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

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "invoice",
            ),
        ),
        "components" => array(
            // "671r" => array(
            //     "master" => array(
            //         //<editor-fold desc="DC">
            //         array(
            //             "comName" => "Jurnal",
            //             "loop" => array(
            //                 "kas" => "-nilai_entry",
            //                 "piutang biaya cabang" => "nilai_bayar",
            //                 //                            "biaya" => "biaya",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "cabang2_id" => "pihakID",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "Rekening",
            //             "loop" => array(
            //                 "kas" => "-nilai_entry",
            //                 "piutang biaya cabang" => "nilai_bayar",
            //                 //                            "biaya" => "biaya",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "cabang2_id" => "pihakID",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "RekeningPembantuKas",
            //             "loop" => array(
            //                 "kas" => "-nilai_entry",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "extern_id" => "cash_account",// diisi id bank
            //                 "extern_nama" => "cash_account__label",// diisi nama bank
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "RekeningPembantuAntarcabang",
            //             "loop" => array(
            //                 "piutang biaya cabang" => "nilai_bayar",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "extern_id" => "pihakID",
            //                 "extern_nama" => "pihakName",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         //</editor-fold>
            //
            //         //<editor-fold desc="Cabang">
            //         array(
            //             "comName" => "Jurnal",
            //             "loop" => array(
            //                 "{pihakMainName}" => "nilai_bayar",
            //                 "hutang biaya ke pusat" => "nilai_bayar",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "cabang2_id" => "placeID",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "Rekening",
            //             "loop" => array(
            //                 "{pihakMainName}" => "nilai_bayar",
            //                 "hutang biaya ke pusat" => "nilai_bayar",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "cabang2_id" => "placeID",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "RekeningPembantuAntarcabang",
            //             "loop" => array(
            //                 "hutang biaya ke pusat" => "nilai_bayar",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "cabang2_id" => "placeID",
            //                 "extern_id" => "placeID",
            //                 "extern_nama" => "placeName",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //
            //
            //         array(
            //             "comName" => "Jurnal",
            //             "loop" => array(
            //                 //                            "{pihakMainName}" => "-nilai_bayar",
            //                 "{pihakMainName_rev}" => "-nilai_bayar_rev",
            //                 "{costName_1}" => "costNilai_1",
            //                 "{costName_2}" => "costNilai_2",
            //                 "{costName_3}" => "costNilai_3",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "cabang2_id" => "placeID",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "Rekening",
            //             "loop" => array(
            //                 //                            "{pihakMainName}" => "-nilai_bayar",
            //                 "{pihakMainName_rev}" => "-nilai_bayar_rev",
            //                 "{costName_1}" => "costNilai_1",
            //                 "{costName_2}" => "costNilai_2",
            //                 "{costName_3}" => "costNilai_3",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "cabang2_id" => "placeID",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //
            //         // cost vs efisiensi
            //         array(
            //             "comName" => "Jurnal",
            //             "loop" => array(
            //                 "efisiensi biaya" => "-nilai_bayar_rev",
            //                 "{costName_1}" => "-costNilai_1",
            //                 "{costName_2}" => "-costNilai_2",
            //                 "{costName_3}" => "-costNilai_3",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "cabang2_id" => "placeID",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "Rekening",
            //             "loop" => array(
            //                 "efisiensi biaya" => "-nilai_bayar_rev",
            //                 "{costName_1}" => "-costNilai_1",
            //                 "{costName_2}" => "-costNilai_2",
            //                 "{costName_3}" => "-costNilai_3",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "cabang2_id" => "placeID",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //
            //         array(
            //             "comName" => "RekeningPembantuEfisiensiBiayaMain",
            //             "loop" => array(
            //                 "efisiensi biaya" => "-costNilai_1",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "extern_id" => "costID_1",
            //                 "extern_nama" => "costName_1",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "RekeningPembantuEfisiensiBiayaMain",
            //             "loop" => array(
            //                 "efisiensi biaya" => "-costNilai_2",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "extern_id" => "costID_2",
            //                 "extern_nama" => "costName_2",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         array(
            //             "comName" => "RekeningPembantuEfisiensiBiayaMain",
            //             "loop" => array(
            //                 "efisiensi biaya" => "-costNilai_3",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "extern_id" => "costID_3",
            //                 "extern_nama" => "costName_3",
            //                 "jenis" => "jenisTr",
            //                 "transaksi_no" => "nomer",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //         ),
            //         //</editor-fold>
            //
            //     ),
            //     "detail" => array(
            //         array(
            //             "comName" => "{relativeCom}",
            //             "loop" => array(
            //                 "{rekName}" => "subtotal",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "cabang2ID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "produk_qty" => "jml",
            //                 "produk_nilai" => "harga",
            //                 //                            "gudang_id" => ".0",
            //                 "gudang_id" => "gudang2ID",
            //                 "jenis" => "jenisTr",
            //             ),
            //             "srcGateName" => "items2_sum",
            //             "srcRawGateName" => "items2_sum",
            //         ),
            //
            //         array(
            //             "comName" => "{relativeCom}",
            //             "loop" => array(
            //                 "{rekName}" => "-subtotal_rev", // selain cabang produksi, maka nilainya 0 saja
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "cabang2ID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "produk_qty" => "jml",
            //                 "produk_nilai" => "harga",
            //                 //                            "gudang_id" => ".0",
            //                 "gudang_id" => "gudang2ID",
            //                 "jenis" => "jenisTr",
            //             ),
            //             "srcGateName" => "items2_sum",
            //             "srcRawGateName" => "items2_sum",
            //         ),
            //         array(
            //             "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            //             "loop" => array(
            //                 "{costName_1}" => "costNilai_1",
            //                 "{costName_2}" => "costNilai_2",
            //                 "{costName_3}" => "costNilai_3",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "cabang2ID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "produk_qty" => "jml",
            //                 "produk_nilai" => "harga",
            //                 //                            "gudang_id" => ".0",
            //                 "gudang_id" => "gudang2ID",
            //                 "jenis" => "jenisTr",
            //             ),
            //             "srcGateName" => "items2_sum",
            //             "srcRawGateName" => "items2_sum",
            //         ),
            //
            //         array(
            //             "comName" => "RekeningPembantuBiayaKomposisiProduksi",
            //             "loop" => array(
            //                 "{costName_1}" => "-costNilai_1",
            //                 "{costName_2}" => "-costNilai_2",
            //                 "{costName_3}" => "-costNilai_3",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "cabang2ID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "produk_qty" => "jml",
            //                 "produk_nilai" => "harga",
            //                 //                            "gudang_id" => ".0",
            //                 "gudang_id" => "gudang2ID",
            //                 "jenis" => "jenisTr",
            //             ),
            //             "srcGateName" => "items2_sum",
            //             "srcRawGateName" => "items2_sum",
            //         ),
            //
            //         array(
            //             "comName" => "RekeningPembantuEfisiensiBiaya",
            //             "loop" => array(
            //                 "efisiensi biaya" => "-costNilai_1",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "cabang2ID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "extern2_id" => "costID_1",
            //                 "extern2_nama" => "costName_1",
            //                 "produk_qty" => "jml",
            //                 "produk_nilai" => "harga",
            //                 "gudang_id" => "gudang2ID",
            //                 "jenis" => "jenisTr",
            //             ),
            //             "srcGateName" => "items2_sum",
            //             "srcRawGateName" => "items2_sum",
            //         ),
            //         array(
            //             "comName" => "RekeningPembantuEfisiensiBiaya",
            //             "loop" => array(
            //                 "efisiensi biaya" => "-costNilai_2",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "cabang2ID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "extern2_id" => "costID_2",
            //                 "extern2_nama" => "costName_2",
            //                 "produk_qty" => "jml",
            //                 "produk_nilai" => "harga",
            //                 "gudang_id" => "gudang2ID",
            //                 "jenis" => "jenisTr",
            //             ),
            //             "srcGateName" => "items2_sum",
            //             "srcRawGateName" => "items2_sum",
            //         ),
            //         array(
            //             "comName" => "RekeningPembantuEfisiensiBiaya",
            //             "loop" => array(
            //                 "efisiensi biaya" => "-costNilai_3",
            //             ),
            //             "static" => array(
            //                 "cabang_id" => "cabang2ID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "extern2_id" => "costID_3",
            //                 "extern2_nama" => "costName_3",
            //                 "produk_qty" => "jml",
            //                 "produk_nilai" => "harga",
            //                 "gudang_id" => "gudang2ID",
            //                 "jenis" => "jenisTr",
            //             ),
            //             "srcGateName" => "items2_sum",
            //             "srcRawGateName" => "items2_sum",
            //         ),
            //     ),
            // ),
            "771" => array(
                "master" => array(
                    //<editor-fold desc="DC">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "kas" => "-nilai_entry",
                            "piutang biaya cabang" => "nilai_bayar",
                            //                            "biaya" => "biaya",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "kas" => "-nilai_entry",
                            "piutang biaya cabang" => "nilai_bayar",
                            //                            "biaya" => "biaya",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "-nilai_entry",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "piutang biaya cabang" => "nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Cabang">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainName}" => "nilai_bayar",
                            "hutang biaya ke pusat" => "nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "{pihakMainName}" => "nilai_bayar",
                            "hutang biaya ke pusat" => "nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "hutang biaya ke pusat" => "nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "{pihakMainName}" => "-nilai_bayar",
                            "{pihakMainName_rev}" => "-nilai_bayar_rev",
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            //                            "{pihakMainName}" => "-nilai_bayar",
                            "{pihakMainName_rev}" => "-nilai_bayar_rev",
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // cost vs efisiensi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "efisiensi biaya" => "-nilai_bayar_rev",
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "efisiensi biaya" => "-nilai_bayar_rev",
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "-costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "extern_id" => "costID_1",
                            "extern_nama" => "costName_1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "-costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "extern_id" => "costID_2",
                            "extern_nama" => "costName_2",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                ),
                "detail" => array(
                    array(
                        "comName" => "{relativeCom}",
                        "loop" => array(
                            "{rekName}" => "subtotal",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    array(
                        "comName" => "{relativeCom}",
                        "loop" => array(
                            "{rekName}" => "-subtotal_rev", // selain cabang produksi, maka nilainya 0 saja
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            //                            "gudang_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "efisiensi biaya" => "-costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_1",
                            "extern2_nama" => "costName_1",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "efisiensi biaya" => "-costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_2",
                            "extern2_nama" => "costName_2",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "efisiensi biaya" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_3",
                            "extern2_nama" => "costName_3",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            // "671r" => array(
            //     "master" => array(
            //         array(
            //             "comName" => "LockerValue",
            //             "loop" => array(),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "gudang_id" => ".0",
            //                 "state" => ".active",
            //                 "jenis" => ".pettycash",
            //                 "produk_id" => "pettycash_account",
            //                 "nama" => "pettycash_account__label",
            //                 "nilai" => "nilai_entry",
            //                 "transaksi_id" => ".0",
            //                 "nomer" => ".0",
            //                 "oleh_id" => ".0",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //             "reversable" => true,
            //         ),
            //         array(
            //             "comName" => "LockerValue",
            //             "loop" => array(),
            //             "static" => array(
            //                 "cabang_id" => "pihakID",
            //                 "gudang_id" => ".0",
            //                 "state" => ".hold",
            //                 "jenis" => ".pettycash",
            //                 "produk_id" => "pettycash_account",
            //                 "nama" => "pettycash_account__label",
            //                 "nilai" => "-nilai_entry",
            //                 "transaksi_id" => "id_master",
            //                 //                            "nomer" => ".0",
            //                 "oleh_id" => ".0",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //             "reversable" => true,
            //         ),
            //
            //         array(
            //             "comName" => "LockerValue",
            //             "loop" => array(),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "gudang_id" => ".0",
            //                 "state" => ".active",
            //                 "jenis" => ".kas",
            //                 "produk_id" => "cash_account",
            //                 "nama" => "cash_account__label",
            //                 "nilai" => "-nilai_bayar",
            //                 "transaksi_id" => ".0",
            //                 "nomer" => ".0",
            //                 "oleh_id" => ".0",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //             "reversable" => true,
            //         ),
            //         array(
            //             "comName" => "LockerValue",
            //             "loop" => array(),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "gudang_id" => ".0",
            //                 "state" => ".payment",
            //                 "jenis" => ".kas",
            //                 "produk_id" => "cash_account",
            //                 "nama" => "cash_account__label",
            //                 "nilai" => "nilai_bayar",
            //                 "transaksi_id" => ".0",
            //                 "nomer" => ".0",
            //                 "oleh_id" => ".0",
            //             ),
            //             "srcGateName" => "main",
            //             "srcRawGateName" => "main",
            //             "reversable" => true,
            //         ),
            //     ),
            //     "detail" => array(
            //         array(
            //             "comName" => "PaymentSrcItem",
            //             "loop" => array(),
            //             "static" => array(
            //                 "cabang_id" => "placeID",
            //                 "extern_id" => "id",
            //                 "extern_nama" => "name",
            //                 "label" => ".refill pettycash",
            //                 "target_jenis" => "jenisTr",
            //                 "transaksi_id" => "refID",
            //                 "terbayar" => "nilai_bayar",
            //                 "sisa" => "new_sisa",
            //             ),
            //             "reversable" => true,
            //             "srcGateName" => "items",
            //             "srcRawGateName" => "items",
            //         ),
            //
            //
            //     ),
            // ),
            "771" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".pettycash",
                            "produk_id" => "pettycash_account",
                            "nama" => "pettycash_account__label",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".pettycash",
                            "produk_id" => "pettycash_account",
                            "nama" => "pettycash_account__label",
                            "nilai" => "-nilai_entry",
                            "transaksi_id" => "id_master",
                            //                            "nomer" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),

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
                            "nilai" => "-nilai_bayar",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".payment",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "nilai_bayar",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".refill pettycash",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                ),
            ),
        ),
    ),

    //  config request, approve pettycash
    "1671" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "gudang2ID" => "pihakID",
                "gudang2Name" => ".0",
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
                //                "reference" => "reference",
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
            //            "hpp_sumber" => "sub_hpp",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
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
                "note" => "note",
                "reference" => "reference",
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
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(
            "1671r" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".pettycash",
                            "produk_id" => "pettycash_account",
                            "nama" => "pettycash_account__label",
                            "nilai" => "-harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".pettycash",
                            "produk_id" => "pettycash_account",
                            "nama" => "pettycash_account__label",
                            "nilai" => "harga",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
    "1672" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",

                "place2ID" => "place2ID",
                "place2Name" => "place2Name",
                "cabang2ID" => "cabang2ID",
                "cabang2Name" => "cabang2Name",
                "gudang2ID" => "gudang2ID",
                "gudang2Name" => "gudang2Name",
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
                //                "reference" => "reference",
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
            //            "hpp_sumber" => "sub_hpp",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
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
                "note" => "note",
                "reference" => "reference",
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
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(),
    ),
    "1771" => array(
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
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "cabang2ID" => "place2ID",
                //                "cabang2Name" => "place2Name",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                //                "gudang2ID" => "gudang2ID",
                //                "gudang2Name" => "gudang2Name",
                //                "masterID" => "masterID",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "refID" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                "tagihan" => "tagihan",
                //                "terbayar" => "terbayar",
                //                "sisa" => "sisa",
                //                "nilai_bayar" => "nilai_bayar",
                //                "new_sisa" => "new_sisa",
                //
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                                "masterID" => "masterID",
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "nilai_bayar" => "bayar_total+totalCredit-diskon_factor",
            "nilai_bayar" => "nilai_entry+totalCredit",
            //            "additionalFactor" => "additional_value*additional",
        ),
        "valuePopulator" => array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
        ),

        "populators" => array(
            "nilai_bayar" => array(
                "mainSrc" => array(
                    "key" => "nilai_bayar",
                ),
                "itemTarget" => array(
                    "key" => "nilai_bayar",
                    "maxAmountSrc" => "sisa",
                ),
            ),
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-nilai_bayar",
            //            "new_sisa" => "sisa-bayar_total",
            //            "new_sisa" => "sisa-additionalFactor",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "additionalFactor+sisa-totalCredit",
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

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

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

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "invoice",
            ),
        ),
        "components" => array(
            "1771" => array(
                "master" => array(
                    //<editor-fold desc="DC">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "kas" => "-nilai_entry",
                            "{pihakMainName}" => "nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "kas" => "-nilai_entry",
                            "{pihakMainName}" => "nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "-nilai_entry",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //</editor-fold>


                    //<editor-fold desc="RL dan Neraca">
                    array(
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                ),
                "detail" => array(
                    array(
                        "comName" => "{relativeCom}",
                        "loop" => array(
                            "{rekName}" => "subtotal",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "1771" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".pettycash",
                            "produk_id" => "pettycash_account",
                            "nama" => "pettycash_account__label",
                            "nilai" => "nilai_bayar",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),

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
                            "nilai" => "-nilai_bayar",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".refill pettycash",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".pettycash",
                            "produk_id" => "pettycash_account",
                            "nama" => "pettycash_account__label",
                            "nilai" => "-nilai_bayar",
                            //                            "transaksi_id" => "masterID",
                            "transaksi_id" => "id_master",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "gudang_id" => ".0",
                            "state" => ".refilled",
                            "jenis" => ".pettycash",
                            "produk_id" => "pettycash_account",
                            "nama" => "pettycash_account__label",
                            "nilai" => "nilai_bayar",
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

    //  config penambahan plafon pettycash
    "770" => array(
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
            "770" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "-selisihPlafon",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "paymentMethod_cash",// diisi id bank
                            "extern_nama" => "paymentMethod_cash__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuKas",
                    //                        "loop" => array(
                    //                            "kas" => "newPlafon",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "paymentMethod_pettycash",
                    //                            "extern_nama" => ".0",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "pettycash" => "selisihPlafon",
                            //                            "kas" => "selisihPlafon",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "paymentMethod_pettycash",
                            "extern_nama" => "paymentMethod_pettycash__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //<editor-fold desc="com-rugilaba dan neraca">
                    array(
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "770" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".pettycash",
                            "produk_id" => "paymentMethod_pettycash",
                            "nama" => "paymentMethod_pettycash__label",
                            "nilai" => "selisihPlafon",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
    //  config pengurangan plafon pettycash
    "970" => array(
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
        "components" => array(
            "970" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "selisihPlafon",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "paymentMethod_cash",// diisi id bank
                            "extern_nama" => "paymentMethod_cash__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "pettycash" => "-selisihPlafon",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pettycash_account",
                            "extern_nama" => "pettycash_account__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //<editor-fold desc="com-rugilaba dan neraca">
                    array(
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "970" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".pettycash",
                            "produk_id" => "pettycash_account",
                            "nama" => "pettycash_account__label",
                            "nilai" => "-selisihPlafon",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
);