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
                "dpp_nilai" => "(harga*(100/(100+ppnFactor))*ppnPersenCheck)",
                "dpp_nilai_pengganti" => "(11/12)*dpp_nilai",
                "ppn_nilai" => "dpp_nilai*(ppnFactor/100)",
                "non_ppn_nilai" => "(harga-(dpp_nilai+ppn_nilai)",
            ),
//            "master_dependent" => array(
//                "ppnPersenCheck" => array(
//                    "1" => array(
//                        "biaya_nilai" => "dpp_nilai",
//                        "biaya_dpp" => "dpp_nilai",
//                        "dpp_final" => "dpp_nilai",
//                        "ppn_final" => "ppn_nilai",
//                        "biaya_ppn" => "ppn_nilai",
////                        "ppn"=>"dpp_nilai*(ppnFactor/100)",
//                    ),
//                    "0" => array(
//                        "biaya_nilai" => "harga",
//                        "biaya_dpp" => ".0",
//                        "biaya_ppn" => ".0",
//                        "dpp_final" => ".0",
//                        "ppn_final" => ".0",
//
//                    ),
//                ),
//            ),
        ),
        "valueBuilders" => array(
            "biaya_dpp" => "dpp_nilai",
            "biaya_dpp_pengganti" => "dpp_nilai_pengganti",
            "biaya_ppn" => "ppn_nilai",
            "biaya_nonppn" => "non_ppn_nilai",
            "pettycash_account__terpakai" => "pettycash_account__plafon-pettycash_account__saldo",

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
                        "comName" => "RekeningPembantuPettycash",
                        "loop" => array(
                            "1010010040" => "-harga",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pettycash_account",// diisi id Pettycash
                            "extern_nama" => "pettycash_account__label",// diisi nama Pettycash
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
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
            "detail" => array(
                "dpp_nilai" => "(harga*(100/(100+ppnFactor))*ppnPersenCheck)",
                "dpp_nilai_pengganti" => "(11/12)*dpp_nilai",
                "ppn_nilai" => "dpp_nilai*(ppnFactor/100)",
                "non_ppn_nilai" => "(harga-(dpp_nilai+ppn_nilai)",
            ),
        ),
        "valueBuilders" => array(
            "biaya_dpp" => "dpp_nilai",
            "biaya_dpp_pengganti" => "dpp_nilai_pengganti",
            "biaya_ppn" => "ppn_nilai",
            "biaya_nonppn" => "non_ppn_nilai",
            //-----
            "locker_nilai" => "plafon_koreksi*-1",
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
        "postProcessor" => array(
            "672" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuPettycash",
                        "loop" => array(
                            "1010010040" => "plafon_koreksi",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pettycash_account",// diisi id Pettycash
                            "extern_nama" => "pettycash_account__label",// diisi nama Pettycash
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".pettycash",
                            //                            "produk_id" => "placeID",
                            "produk_id" => "pettycash_account",
                            "nama" => "place2Name",
                            "nilai" => "plafon_koreksi",
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
                            "cabang_id" => "place2ID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".pettycash",
                            //                            "produk_id" => "placeID",
                            "produk_id" => "pettycash_account",
                            "nama" => "placeName",
                            "nilai" => "locker_nilai",
                            "transaksi_id" => "masterID",
//                            "nomer" => "nomer",
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

        /*
 * untuk nutup request transaksi jika diapprove sebagian,ataupun nilai dikoreksi transkasi dianggap selesai
 * dan tidak ada outstanding
 */
        "closedRequestValue" => array(
            2 => array(
                "enabled" => true,
                "key" => "harga",
            ),
        ),
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
            "detail" => array(
//            //--------
//                "ppn_nilai_biaya" => "ppn_nilai",
//                "dpp_nilai_biaya" => "sisa-ppn_nilai_biaya",
            ),
            "detail2_sum" => array(
                "dpp_nilai" => "(harga*(100/(100+ppnFactor))*ppnPersenCheck)",
                "dpp_nilai_pengganti" => "(11/12)*dpp_nilai",
                "ppn_nilai" => "dpp_nilai*(ppnFactor/100)",
                "non_ppn_nilai" => "(harga-(dpp_nilai+ppn_nilai)",
                "ppn_sudah_faktur" => "ppn_nilai",
                "ppn_nilai_sudah_faktur" => "ppn_nilai",
            ),
            "master_dependent" => array(
                "jenisReferensi" => array(
                    "1" => array(
                        "um_nilai" => "dpp_nilai",
                        "titipan_nilai" => ".0",
                        "biaya_nilai" => "dpp_nilai",
                    ),
                    "3" => array(
                        "um_nilai" => ".0",
                        "titipan_nilai" => "dpp_nilai_biaya",
                        "biaya_nilai" => "dpp_nilai_biaya",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "nilai_entry" => "harus_bayar",
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "nilai_entry+totalCredit",

            "ppn_nilai_biaya" => "ppn_nilai",
            "dpp_nilai_biaya" => "sisa-ppn_nilai_biaya",
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
//            "new_sisa" => "sisa-bayar_total",
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "additionalFactor+sisa-totalCredit",
        ),

        "preProcessor" => array(
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
        "components_OLD" => array(
            "771" => array(
                "master" => array(
                    //<editor-fold desc="DC">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "-nilai_entry",// kas
                            "1010060040" => "nilai_bayar",// piutang biaya cabang
                            //-----
                            "1010040060" => ".0",// ppn masukan sudah ada faktur
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
                            "1010010010" => "-nilai_entry",// kas
                            "1010060040" => "nilai_bayar",// piutang biaya cabang
                            //-----
                            "1010040060" => ".0",// ppn masukan sudah ada faktur
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
                            "1010010010" => "-nilai_entry",// kas
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
                            "1010060040" => "nilai_bayar",// piutang biaya cabang
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
                            "{pihakMainNameCoa}" => "nilai_bayar",
                            "2040020" => "nilai_bayar",// hutang biaya ke pusat
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
                            "{pihakMainNameCoa}" => "nilai_bayar",
                            "2040020" => "nilai_bayar",// hutang biaya ke pusat
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
                            "2040020" => "nilai_bayar",// hutang biaya ke pusat
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
                            "{pihakMainNameCoa_rev}" => "-nilai_bayar_rev",
                            // "{costNameCoa_1}" => "costNilai_1",
                            // "{costNameCoa_2}" => "costNilai_2",
                            // "{costNameCoa_3}" => "costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
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
                            "{pihakMainNameCoa_rev}" => "-nilai_bayar_rev",
                            // "{costNameCoa_1}" => "costNilai_1",
                            // "{costNameCoa_2}" => "costNilai_2",
                            // "{costNameCoa_3}" => "costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
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
                            "3020010" => "-nilai_bayar_rev",// efisiensi biaya
                            // "{costNameCoa_1}" => "-costNilai_1",
                            // "{costNameCoa_2}" => "-costNilai_2",
                            // "{costNameCoa_3}" => "-costNilai_3",
                            "{costIdCoa_1}" => "-costNilai_1",
                            "{costIdCoa_2}" => "-costNilai_2",
                            "{costIdCoa_3}" => "-costNilai_3",
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
                            "3020010" => "-nilai_bayar_rev",// efisiensi biaya
                            // "{costNameCoa_1}" => "-costNilai_1",
                            // "{costNameCoa_2}" => "-costNilai_2",
                            // "{costNameCoa_3}" => "-costNilai_3",
                            "{costIdCoa_1}" => "-costNilai_1",
                            "{costIdCoa_2}" => "-costNilai_2",
                            "{costIdCoa_3}" => "-costNilai_3",
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
                            "3020010" => "-costNilai_1",// efisiensi biaya
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
                            "3020010" => "-costNilai_2",// efisiensi biaya
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
                            "3020010" => "-costNilai_3",// efisiensi biaya
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
                            "{pihakMainNameCoa}" => "subtotal",
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
                            "{pihakMainNameCoa}" => "-subtotal_rev", // selain cabang produksi, maka nilainya 0 saja
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
                            // "{costName_1}" => "costNilai_1",
                            // "{costName_2}" => "costNilai_2",
                            // "{costName_3}" => "costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
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
                            // "{costName_1}" => "-costNilai_1",
                            // "{costName_2}" => "-costNilai_2",
                            // "{costName_3}" => "-costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
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
                            "3020010" => "-costNilai_1",// efisiensi biaya
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
                            "3020010" => "-costNilai_2",// efisiensi biaya
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
                            "3020010" => "-costNilai_3",// efisiensi biaya
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
        "components" => array(
            "771" => array(
                "master" => array(
                    //<editor-fold desc="DC">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "-nilai_entry",// kas
                            "1010060040" => "dpp_nilai_biaya",// piutang biaya cabang
                            //-----
                            "1010040060" => "ppn_nilai_biaya",// ppn masukan sudah ada faktur
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
                            "1010010010" => "-nilai_entry",// kas
                            "1010060040" => "dpp_nilai_biaya",// piutang biaya cabang
                            //-----
                            "1010040060" => "ppn_nilai_biaya",// ppn masukan sudah ada faktur
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
                            "1010010010" => "-nilai_entry",// kas
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
                            "1010060040" => "dpp_nilai_biaya",// piutang biaya cabang
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
                            "{pihakMainNameCoa}" => "dpp_nilai_biaya",
                            "2040020" => "dpp_nilai_biaya",// hutang biaya ke pusat
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
                            "{pihakMainNameCoa}" => "dpp_nilai_biaya",
                            "2040020" => "dpp_nilai_biaya",// hutang biaya ke pusat
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
                            "2040020" => "dpp_nilai_biaya",// hutang biaya ke pusat
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
                            "{pihakMainNameCoa_rev}" => "-nilai_bayar_rev",
                            // "{costNameCoa_1}" => "costNilai_1",
                            // "{costNameCoa_2}" => "costNilai_2",
                            // "{costNameCoa_3}" => "costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
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
                            "{pihakMainNameCoa_rev}" => "-nilai_bayar_rev",
                            // "{costNameCoa_1}" => "costNilai_1",
                            // "{costNameCoa_2}" => "costNilai_2",
                            // "{costNameCoa_3}" => "costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
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
                            "3020010" => "-nilai_bayar_rev",// efisiensi biaya
                            // "{costNameCoa_1}" => "-costNilai_1",
                            // "{costNameCoa_2}" => "-costNilai_2",
                            // "{costNameCoa_3}" => "-costNilai_3",
                            "{costIdCoa_1}" => "-costNilai_1",
                            "{costIdCoa_2}" => "-costNilai_2",
                            "{costIdCoa_3}" => "-costNilai_3",
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
                            "3020010" => "-nilai_bayar_rev",// efisiensi biaya
                            // "{costNameCoa_1}" => "-costNilai_1",
                            // "{costNameCoa_2}" => "-costNilai_2",
                            // "{costNameCoa_3}" => "-costNilai_3",
                            "{costIdCoa_1}" => "-costNilai_1",
                            "{costIdCoa_2}" => "-costNilai_2",
                            "{costIdCoa_3}" => "-costNilai_3",
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
                            "3020010" => "-costNilai_1",// efisiensi biaya
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
                            "3020010" => "-costNilai_2",// efisiensi biaya
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
                            "3020010" => "-costNilai_3",// efisiensi biaya
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

                    //jurnal koreksi memindah biaya ke um di cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainNameCoa}" => "-biaya_nilai",
                            "2040020" => "-biaya_nilai",// hutang biaya ke pusat
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
                            "{pihakMainNameCoa}" => "-biaya_nilai",
                            "2040020" => "-biaya_nilai",// hutang biaya ke pusat
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
                            "2040020" => "biaya_nilai",// hutang biaya ke pusat
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

                    //jurnal koreksi memindah biaya ke um di pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060040" => "-biaya_nilai",// piutang biaya cabang
                            //-----
                            "1010050040" => "titipan_nilai",// titipan non relasi
                            "1010050030" => "um_nilai",// um ppn
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
                            "1010060040" => "-biaya_nilai",// piutang biaya cabang
                            //-----
                            "1010050040" => "titipan_nilai",// titipan non relasi
                            "1010050030" => "um_nilai",// um ppn
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
                    //pembantu antar cabang
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060040" => "-biaya_nilai",// piutang biaya cabang
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
                    //pembantu um
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050030" => "um_nilai",// uang muka dibayar dengan ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "supplier_id",
                            "extern_nama" => "supplier_nama",
                            "extern2_id" => "referensiSo",
                            "extern2_nama" => "referensiSo__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050030" => "um_nilai",// uang muka ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "supplier_id",
                            "extern_nama" => "supplier_nama",
                            "extern2_id" => "referensiSo",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "referensiSo__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //-----------------
                    //pembantu titipan non realsi
                    //pembantu uang muka tanpa ppn tanpa  relasi PO supplier
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050040" => "titipan_nilai",// uang muka dibayar tanpa ppn non relasi PO
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "supplierTitipan__id",
                            "extern_nama" => "supplierTitipan__nama",
                            "extern2_id" => ".0",
                            "extern2_nama" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050040" => "titipan_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "supplierTitipan__id",
                            "extern_nama" => "supplierTitipan__nama",
                            "extern2_id" => ".0",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => ".0",
                            "extern3_id" => "option_nota",
                            "extern3_nama" => "option_nota__nama",
                            "extern4_nama" => "option_nota__jenis",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "{relativeCom}",
                        "loop" => array(
                            "{pihakMainNameCoa}" => "dpp_nilai_biaya",
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
                            "{pihakMainNameCoa}" => "-biaya_nilai",
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
                            "{pihakMainNameCoa}" => "-subtotal_rev", // selain cabang produksi, maka nilainya 0 saja
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
                            // "{costName_1}" => "costNilai_1",
                            // "{costName_2}" => "costNilai_2",
                            // "{costName_3}" => "costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
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
                            // "{costName_1}" => "-costNilai_1",
                            // "{costName_2}" => "-costNilai_2",
                            // "{costName_3}" => "-costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
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
                            "3020010" => "-costNilai_1",// efisiensi biaya
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
                            "3020010" => "-costNilai_2",// efisiensi biaya
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
                            "3020010" => "-costNilai_3",// efisiensi biaya
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
            "771" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuPettycash",
                        "loop" => array(
                            "1010010040" => "nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "extern_id" => "pettycash_account",// diisi id Pettycash
                            "extern_nama" => "pettycash_account__label",// diisi nama Pettycash
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


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

                    // faktur yang diinput, pindah ke postprocc
                    array(
                        "comName" => "PaymentSourceFakturItems",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => "supplier_id",
                            "extern_nama" => "supplier_nama",
                            "label" => ".ppn realisasi",
                            "target_jenis" => ".0000",
//                            "transaksi_id" => "refID",
//                            "sisa" => "new_sisa",
                            "jenis" => "jenisTr",
                            "reference_jenis" => "jenisTr",
                            "tagihan" => "ppn_nilai",
                            "sisa" => "ppn_nilai",
                            "extern_label2" => "eFaktur",
                            "ppn" => "ppn_nilai",
                            "ppn_sisa" => "ppn_nilai",
                            "ppn_sudah_faktur" => "ppn_nilai",
                            "extern_nilai2" => "dpp_nilai",
                            "extern_date2" => "dateFaktur",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
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
                "dpp_nilai" => "(harga*(100/(100+ppnFactor))*ppnPersenCheck)",
                "dpp_nilai_pengganti" => "(11/12)*dpp_nilai",
                "ppn_nilai" => "dpp_nilai*(ppnFactor/100)",
                "non_ppn_nilai" => "(harga-(dpp_nilai+ppn_nilai)",
            ),
//            "master_dependent" => array(
//                "ppnPersenCheck" => array(
//                    "1" => array(
//                        "biaya_nilai" => "dpp_nilai",
//                        "biaya_dpp" => "dpp_nilai",
//                        "dpp_final" => "dpp_nilai",
//                        "ppn_final" => "ppn_nilai",
//                        "biaya_ppn" => "ppn_nilai",
////                        "ppn"=>"dpp_nilai*(ppnFactor/100)",
//                    ),
//                    "0" => array(
//                        "biaya_nilai" => "harga",
//                        "biaya_dpp" => ".0",
//                        "biaya_ppn" => ".0",
//                        "dpp_final" => ".0",
//                        "ppn_final" => ".0",
//
//                    ),
//                ),
//            ),
        ),
        "valueBuilders" => array(
            "biaya_dpp" => "dpp_nilai",
            "biaya_dpp_pengganti" => "dpp_nilai_pengganti",
            "biaya_ppn" => "ppn_nilai",
            "biaya_nonppn" => "non_ppn_nilai",
            "pettycash_account__terpakai" => "pettycash_account__plafon-pettycash_account__saldo",

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
                        "comName" => "RekeningPembantuPettycash",
                        "loop" => array(
                            "1010010040" => "-harga",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pettycash_account",// diisi id Pettycash
                            "extern_nama" => "pettycash_account__label",// diisi nama Pettycash
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
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
            "detail" => array(
                "dpp_nilai" => "(harga*(100/(100+ppnFactor))*ppnPersenCheck)",
                "dpp_nilai_pengganti" => "(11/12)*dpp_nilai",
                "ppn_nilai" => "dpp_nilai*(ppnFactor/100)",
                "non_ppn_nilai" => "(harga-(dpp_nilai+ppn_nilai)",
            ),
        ),
        "valueBuilders" => array(
            "biaya_dpp" => "dpp_nilai",
            "biaya_dpp_pengganti" => "dpp_nilai_pengganti",
            "biaya_ppn" => "ppn_nilai",
            "biaya_nonppn" => "non_ppn_nilai",
            //-----
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
            "detail" => array(
//                "ppn_nilai_biaya" => "ppn_nilai",
//                "dpp_nilai_biaya" => "sisa-ppn_nilai_biaya",
            ),
            "detail2_sum" => array(
                "dpp_nilai" => "(harga*(100/(100+ppnFactor))*ppnPersenCheck)",
                "dpp_nilai_pengganti" => "(11/12)*dpp_nilai",
                "ppn_nilai" => "dpp_nilai*(ppnFactor/100)",
                "non_ppn_nilai" => "(harga-(dpp_nilai+ppn_nilai)",
                "ppn_sudah_faktur" => "ppn_nilai",
                "ppn_nilai_sudah_faktur" => "ppn_nilai",
            ),
        ),
        "valueBuilders" => array(
            "nilai_entry" => "harus_bayar",
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "nilai_entry+totalCredit",

            "ppn_nilai_biaya" => "ppn_nilai",
            "dpp_nilai_biaya" => "sisa-ppn_nilai_biaya",
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
                            "1010010010" => "-nilai_entry",// kas
                            "{pihakMainNameCoa}" => "dpp_nilai_biaya",
                            //-----
                            "1010040060" => "ppn_nilai_biaya",// ppn masukan sudah ada faktur
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
                            "1010010010" => "-nilai_entry",// kas
                            "{pihakMainNameCoa}" => "dpp_nilai_biaya",
                            //-----
                            "1010040060" => "ppn_nilai_biaya",// ppn masukan sudah ada faktur
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
                            "1010010010" => "-nilai_entry",// kas
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


                ),
                "detail" => array(
                    array(
                        "comName" => "{relativeCom}",
                        "loop" => array(
                            "{rekName}" => "dpp_nilai_biaya",
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
                        "comName" => "RekeningPembantuPettycash",
                        "loop" => array(
                            "1010010040" => "nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pettycash_account",// diisi id Pettycash
                            "extern_nama" => "pettycash_account__label",// diisi nama Pettycash
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
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

                    // faktur yang diinput, pindah ke postprocc
                    array(
                        "comName" => "PaymentSourceFakturItems",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => "supplier_id",
                            "extern_nama" => "supplier_nama",
                            "label" => ".ppn realisasi",
                            "target_jenis" => ".0000",
//                            "transaksi_id" => "refID",
//                            "sisa" => "new_sisa",
                            "jenis" => "jenisTr",
                            "reference_jenis" => "jenisTr",
                            "tagihan" => "ppn_nilai",
                            "sisa" => "ppn_nilai",
                            "extern_label2" => "eFaktur",
                            "ppn" => "ppn_nilai",
                            "ppn_sisa" => "ppn_nilai",
                            "ppn_sudah_faktur" => "ppn_nilai",
                            "extern_nilai2" => "dpp_nilai",
                            "extern_date2" => "dateFaktur",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
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
                            "1010010010" => "-selisihPlafon",// kas
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
                            "1010010040" => "selisihPlafon",// pettycash
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

                    array(
                        "comName" => "RekeningPembantuPettycash",
                        "loop" => array(
                            "1010010040" => "selisihPlafon",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "paymentMethod_pettycash",// diisi id Pettycash
                            "extern_nama" => "paymentMethod_pettycash__label",// diisi nama Pettycash
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
            "770" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "paymentMethod_cash",
                            "nama" => "paymentMethod_cash__label",
                            "nilai" => "-selisihPlafon",
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
                            "1010010010" => "selisihPlafon",// kas
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
                            "1010010040" => "-selisihPlafon",// pettycash
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

                    array(
                        "comName" => "RekeningPembantuPettycash",
                        "loop" => array(
                            "1010010040" => "-selisihPlafon",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "pettycash_account",// diisi id Pettycash
                            "extern_nama" => "pettycash_account__label",// diisi nama Pettycash
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
            "970" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "paymentMethod_cash",
                            "nama" => "paymentMethod_cash__label",
                            "nilai" => "selisihPlafon",
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