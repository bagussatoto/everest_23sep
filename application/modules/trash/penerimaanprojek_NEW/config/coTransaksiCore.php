<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

$config["coTransaksiCore"] = array(
    // config penerimaan piutang customer (uang masuk dari konsumen)
    "7499_ORI" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
        ),
        "formatNota" => "stepCode|placeID|customerID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "customerID" => "pihakID",
                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "kelebihanBayar" => array(
                    // pas
                    "0" => array(
                        "deposit_konsumen" => ".0",
                        "pendapatan_lain_lain" => ".0",
                        "nilai_cash" => "(nilai_entry-nilai_biaya)",
                        //                        "nilai_cash" => "nilai_entry",
                    ),
                    // deposit
                    "1" => array(
                        "deposit_konsumen" => "lebih_bayar",
                        "pendapatan_lain_lain" => ".0",
                        "nilai_cash" => "(nilai_entry-nilai_biaya)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                    // pendapatan lain-lain
                    "2" => array(
                        "deposit_konsumen" => ".0",
                        "pendapatan_lain_lain" => "lebih_bayar",
                        "nilai_cash" => "(nilai_entry-nilai_biaya)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai",
            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli
            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round-(deposit_konsumen+pendapatan_lain_lain)",

            // lebih bayar di switch by chepy 11-jan 2021
            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+totalCredit-nilai_round",
            //            "lebih_bayar" => "nilai_entry+nilai_biaya+totalCredit-nilai_round",
            // "lebih_bayar" => "nilai_entry+selisih_round-harus_bayar",

            "amount" => "sisa",
            "credit_amount" => "credit_note_dipakai",

        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),

        "additionalSource" => true,
        "additionalItemSourceKey" => array(
            "top" => "nilai_bayar",
            "bottom" => "tagihan",//harga_nett2
        ),
        "additionalItemSource" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "laba_kotor",
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
            //            "new_sisa" => "sisa-nilai_bayar",

        ),
        "additionalMainBuilders" => array(//==main
            //            "harus_bayar" => "sisa-totalCredit-nilai_biaya-uang_muka_dipakai",
            "harus_bayar" => "nilai_round-totalCredit-nilai_biaya",
            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
            // "selisih_round" => "sisa-nilai_round",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar)",
        ),

        "preProcessor" => array(
            "7499" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            //                            "nilai" => "credit_note_dipakai+nilai_entry", // nilai pembayaran total
                            "nilai" => "credit_note_dipakai+nilai_cash+nilai_biaya+uang_muka_dipakai+pph22_nilai+ppn_nilai_dibayar+selisih_round", // nilai pembayaran total
                            "jenis" => ".piutang dagang",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_sisa" => "nilai_sisa",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
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
            "mainValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",

                "harus_bayar" => "harus_bayar",
                "creditAmount" => "creditAmount",
                "nilai_entry" => "nilai_entry",
                "new_sisa" => "new_sisa",
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
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
                //                "new_sisa" => "new_sisa",
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
            "7499" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
                            "kas" => "nilai_cash",
                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
                            "biaya usaha" => "nilai_biaya",
                            "selisih pembulatan" => "selisih_round",
                            "pph22" => "pph22_nilai",
                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
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
                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
                            "kas" => "nilai_cash",
                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
                            "biaya usaha" => "nilai_biaya",
                            "selisih pembulatan" => "selisih_round",
                            "pph22" => "pph22_nilai",
                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // ====== =============

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "pendapatan lain_lain" => "pendapatan_lain_lain",
                            "hutang ke konsumen" => "deposit_konsumen",
                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
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
                            "pendapatan lain_lain" => "pendapatan_lain_lain",
                            "hutang ke konsumen" => "deposit_konsumen",
                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            //                          "piutang dagang" => "-(creditAmount+nilai_entry)",
                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "pph22" => "pph22_nilai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            //                            "kas" => "nilai_entry",
                            "kas" => "nilai_cash",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //update tambahan biaya usaha suport pelanggan langsung di define tanpa pilihan agar tidak nyasar
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "biaya usaha" => "nilai_biaya",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".3",//id dta biaya usaha
                            "extern_nama" => ".support pelanggan",///nama data biaya usaha
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //tambahan juranal langsung untuk pph 22 dibayar bendahara negara digeser kepusat
                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "pph22" => "-pph22_nilai",
                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
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
                            "pph22" => "-pph22_nilai",
                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
                        ),
                        "static" => array(
                            "cabang_id" => "-1",//langsung di define karena tidak pakai connecting
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "pph22" => "-pph22_nilai",
                        ),
                        "static" => array(
                            "cabang_id" => "-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => "PUSAT",
                            "extern_id" => ".-1",
                            "extern_nama" => "PUSAT",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //region pusat

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
                            "pph22" => "pph22_nilai",
                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
                        ),
                        "static" => array(
                            "cabang_id" => "-1",
                            "cabang_nama" => "PUSAT",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
                            "pph22" => "pph22_nilai",
                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
                        ),
                        "static" => array(
                            "cabang_id" => "-1",
                            "cabang_nama" => "PUSAT",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
                        ),
                        "static" => array(
                            "cabang_id" => "-1",//langsung di define karena tidak pakai connecting
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "pph22" => "pph22_nilai",
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "7499" => array(
                "master" => array(
                    // anti source deposit berkurang
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "creditAmount__transaksi_id",
                            "jenis" => "creditAmount__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang dagang",
                            "terbayar" => "credit_note_dipakai",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // anti source deposit bertambah
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang dagang",
                            "sisa" => "deposit_konsumen",
                        ),
                        "reversable" => true,
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
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            //                            "nomer"        => "referenceNomer",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            "label" => ".uang muka",
                            "terbayar" => "uang_muka_dipakai",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                    ),

                    //nulis payment source disini karena tidak bisa di pasang di heTransaksiMisc karena antar cabang
                    array(
                        "comName" => "PaymentSourceAntarCabang",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nama" => "pihakName",
                            "label" => ".ppn dibayar bendahara negara",
                            "ppn" => "ppn_nilai_dibayar",
                            "extern_nilai2" => "dpp_nilai",//dpp ppn
                            "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "ppn_nilai_dibayar",
                            "sisa" => "ppn_nilai_dibayar",
                            "target_jenis" => ".0000",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_id" => "olehName",
                            "srcValue" => "ppn_nilai_dibayar",
                            // "srcValue"=>".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentSourceAntarCabang",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nama" => "pihakName",
                            "label" => ".pph22",
                            "ppn" => "ppn_nilai_dibayar",
                            "extern_nilai2" => "dpp_nilai",//dpp ppn
                            "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "pph22_nilai",
                            "sisa" => "pph22_nilai",
                            "target_jenis" => ".1110",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_id" => "olehName",
                            "srcValue" => "pph22_nilai",
                            // "srcValue"=>".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
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
                            "label" => ".piutang dagang",
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
                        "comName" => "ReleaserDueDate",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            //                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_nomer" => "nomer",
                            //                            "terbayar" => "nilai_bayar",
                            //                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    "7488" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
        ),
        "formatNota" => "stepCode|placeID|customerID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "customerID" => "pihakID",
                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "kelebihanBayar" => array(
                    // pas
                    "0" => array(
                        "deposit_konsumen" => ".0",
                        "pendapatan_lain_lain" => ".0",
                        "nilai_cash" => "(nilai_entry-nilai_biaya)",
                        //                        "nilai_cash" => "nilai_entry",
                    ),
                    // deposit
                    "1" => array(
                        "deposit_konsumen" => "lebih_bayar",
                        "pendapatan_lain_lain" => ".0",
                        "nilai_cash" => "(nilai_entry-nilai_biaya)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                    // pendapatan lain-lain
                    "2" => array(
                        "deposit_konsumen" => ".0",
                        "pendapatan_lain_lain" => "lebih_bayar",
                        "nilai_cash" => "(nilai_entry-nilai_biaya)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai",
            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli
            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round-(deposit_konsumen+pendapatan_lain_lain)",

            // lebih bayar di switch by chepy 11-jan 2021
            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+totalCredit-nilai_round",
            //            "lebih_bayar" => "nilai_entry+nilai_biaya+totalCredit-nilai_round",
            // "lebih_bayar" => "nilai_entry+selisih_round-harus_bayar",

            "amount" => "sisa",
            "credit_amount" => "credit_note_dipakai",

        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),

        "additionalSource" => true,
        "additionalItemSourceKey" => array(
            "top" => "nilai_bayar",
            "bottom" => "tagihan",//harga_nett2
        ),
        "additionalItemSource" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "laba_kotor",
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
            //            "new_sisa" => "sisa-nilai_bayar",

        ),
        "additionalMainBuilders" => array(//==main
            //            "harus_bayar" => "sisa-totalCredit-nilai_biaya-uang_muka_dipakai",
            "harus_bayar" => "nilai_round-totalCredit-nilai_biaya",
            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
            // "selisih_round" => "sisa-nilai_round",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar)",
        ),

        "preProcessor" => array(
            "7488" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "credit_note_dipakai+nilai_cash+nilai_biaya+uang_muka_dipakai+pph22_nilai+ppn_nilai_dibayar+selisih_round", // nilai pembayaran total
                            "jenis" => ".1010020060",// piutang retensi
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_sisa" => "nilai_sisa",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
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
            "mainValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",

                "harus_bayar" => "harus_bayar",
                "creditAmount" => "creditAmount",
                "nilai_entry" => "nilai_entry",
                "new_sisa" => "new_sisa",
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
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
                //                "new_sisa" => "new_sisa",
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
            "7488" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai)",// hutang ke konsumen
                            "1010010010" => "nilai_cash",// kas
                            "1010020060" => "-nilai_dipakai_1010020060",// piutang retensi
                            "6010" => "nilai_biaya",// biaya usaha
                            "7010110" => "selisih_round",// selisih pembulatan
                            "1010040020" => "pph22_nilai",// pph22 dibayar dimuka
                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
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
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai)",// hutang ke konsumen
                            "1010010010" => "nilai_cash",// kas
                            "1010020060" => "-nilai_dipakai_1010020060",// piutang retensi
                            "6010" => "nilai_biaya",// biaya usaha
                            "7010110" => "selisih_round",// selisih pembulatan
                            "1010040020" => "pph22_nilai",// pph22 dibayar dimuka
                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // ====== =============

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "7010150" => "pendapatan_lain_lain",// laba lain_lain
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                            "1010010010" => "deposit_konsumen+pendapatan_lain_lain",// kas
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
                            "7010150" => "pendapatan_lain_lain",// pendapatan lain_lain
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                            "1010010010" => "deposit_konsumen+pendapatan_lain_lain",// kas
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010020060" => "-nilai_dipakai_1010020060",// piutang retensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai)",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040020" => "pph22_nilai",// pph22 dibayar dimuka
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "nilai_cash",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "deposit_konsumen+pendapatan_lain_lain",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //update tambahan biaya usaha suport pelanggan langsung di define tanpa pilihan agar tidak nyasar
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "nilai_biaya",// biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".3",//id dta biaya usaha
                            "extern_nama" => ".support pelanggan",///nama data biaya usaha
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //tambahan juranal langsung untuk pph 22 dibayar bendahara negara digeser kepusat
                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010040020" => "-pph22_nilai",// pph22
                            "1010040080" => "-ppn_nilai_dibayar",// ppn dibayar bendahara negara
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar)",// hutang ke pusat
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
                            "1010040020" => "-pph22_nilai",// pph22
                            "1010040080" => "-ppn_nilai_dibayar",// ppn dibayar bendahara negara
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar)",// hutang ke pusat
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010040080" => "-ppn_nilai_dibayar",// ppn dibayar bendahara negara
                        ),
                        "static" => array(
                            "cabang_id" => "-1",//langsung di define karena tidak pakai connecting
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040020" => "-pph22_nilai",// pph22
                        ),
                        "static" => array(
                            "cabang_id" => "-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar)",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => "PUSAT",
                            "extern_id" => ".-1",
                            "extern_nama" => "PUSAT",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //region pusat

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
                            "1010040020" => "pph22_nilai",// pph22
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar)",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "-1",
                            "cabang_nama" => "PUSAT",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
                            "1010040020" => "pph22_nilai",// pph22
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar)",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "-1",
                            "cabang_nama" => "PUSAT",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
                        ),
                        "static" => array(
                            "cabang_id" => "-1",//langsung di define karena tidak pakai connecting
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040020" => "pph22_nilai",// pph22
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar)",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "7488" => array(
                "master" => array(
                    // anti source deposit berkurang
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "creditAmount__transaksi_id",
                            "jenis" => "creditAmount__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang retensi",
                            "terbayar" => "credit_note_dipakai",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // anti source deposit bertambah
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang retensi",
                            "sisa" => "deposit_konsumen",
                        ),
                        "reversable" => true,
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
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            //                            "nomer"        => "referenceNomer",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            "label" => ".uang muka",
                            "terbayar" => "uang_muka_dipakai",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                    ),

                    //nulis payment source disini karena tidak bisa di pasang di heTransaksiMisc karena antar cabang
                    array(
                        "comName" => "PaymentSourceAntarCabang",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nama" => "pihakName",
                            "label" => ".ppn dibayar bendahara negara",
                            "ppn" => "ppn_nilai_dibayar",
                            "extern_nilai2" => "dpp_nilai",//dpp ppn
                            "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "ppn_nilai_dibayar",
                            "sisa" => "ppn_nilai_dibayar",
                            "target_jenis" => ".0000",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_id" => "olehName",
                            "srcValue" => "ppn_nilai_dibayar",
                            // "srcValue"=>".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentSourceAntarCabang",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nama" => "pihakName",
                            "label" => ".pph22",
                            "ppn" => "ppn_nilai_dibayar",
                            "extern_nilai2" => "dpp_nilai",//dpp ppn
                            "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "pph22_nilai",
                            "sisa" => "pph22_nilai",
                            "target_jenis" => ".1110",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_id" => "olehName",
                            "srcValue" => "pph22_nilai",
                            // "srcValue"=>".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
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
                            "label" => ".piutang retensi",
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
                        "comName" => "ReleaserDueDate",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            //                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_nomer" => "nomer",
                            //                            "terbayar" => "nilai_bayar",
                            //                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),


    "7499" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
        ),
        "formatNota" => "stepCode|placeID|customerID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "customerID" => "pihakID",
                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
//            "master_dependent" => array(
//                "kelebihanBayar" => array(
//                    // pas
//                    "0" => array(
//                        "deposit_konsumen" => ".0",
//                        "pendapatan_lain_lain" => ".0",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya)",
//                        //                        "nilai_cash" => "nilai_entry",
//                    ),
//                    // deposit
//                    "1" => array(
//                        "deposit_konsumen" => "lebih_bayar",
//                        "pendapatan_lain_lain" => ".0",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya)-lebih_bayar",
//                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                    ),
//                    // pendapatan lain-lain
//                    "2" => array(
//                        "deposit_konsumen" => ".0",
//                        "pendapatan_lain_lain" => "lebih_bayar",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya)-lebih_bayar",
//                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                    ),
//                ),
//            ),
        ),
        "valueBuilders" => array(
//            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai",
            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli
            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
//            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round-(deposit_konsumen+pendapatan_lain_lain)",
            "nilai_bayar" => "nilai_entry",

            // lebih bayar di switch by chepy 11-jan 2021
//            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+totalCredit-nilai_round",
            //            "lebih_bayar" => "nilai_entry+nilai_biaya+totalCredit-nilai_round",
            // "lebih_bayar" => "nilai_entry+selisih_round-harus_bayar",

            "amount" => "sisa",
//            "credit_amount" => "credit_note_dipakai",

            "penjualan" => "nilai_entry",
            "penjualan_bulat" => "dpp_ppn",
//            "ppn" => "nilai_entry*(11/100)",
//            "piutang_dagang" => "penjualan+ppn",
//            "termin_nppn" => "penjualan+ppn",
            //-------
//            "dpp_ppn" => "nilai_entry",
            "grand_total" => "nilai_entry",
            "grand_ppn" => "ppn",
            "new_net1" => "nilai_entry",
            "tagihan" => "nilai_entry+ppn",
            "grand_total_ui" => "nilai_entry",
            "dpp_pengganti" => "nilai_entry*(11/12)",
            "dpp_pengganti_factor" => "11/12",
        ),
        //-------------------
        "injectorPajak" => array(
            "source" => "grand_total_ui",
        ),
        "pairPajak" => array(
            "ppn" => "ppn",
            "grand_ppn" => "ppn",
            "new_grand_ppn" => "ppn",
            "dpp_ppn" => "dppPpn",
            "grandTotal" => "grandTotal",
            "new_net3" => "grandTotal",
            "ppn_out_bulat" => "ppn",
            "grand_pembulatan" => "grandTotal",
        ),
        //-------------------
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),

        "additionalSource" => false,
        "additionalItemSourceKey" => array(
            "top" => "nilai_bayar",
            "bottom" => "tagihan",//harga_nett2
        ),
        "additionalItemSource" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "laba_kotor",
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
            //            "new_sisa" => "sisa-nilai_bayar",

        ),
        "additionalMainBuilders" => array(//==main
            //            "harus_bayar" => "sisa-totalCredit-nilai_biaya-uang_muka_dipakai",
//            "harus_bayar" => "nilai_round-totalCredit-nilai_biaya",
            "harus_bayar" => "subtotal",
            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
            // "selisih_round" => "sisa-nilai_round",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "new_sisa" => "harus_bayar-(nilai_entry*1.11)",
        ),

        "preProcessor" => array(
            "7499" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningProject",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "projectID",
                            "extern_nama" => "projectName",
                            "extern2_id" => "customerDetails",
                            "extern2_nama" => "customerDetails__label",
                            "nilai" => "nilai_entry",
                            "jenis" => ".project",
                            "jenisTr" => "jenisTr",
                            "termin_nppn" => "new_net3",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
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
            "mainValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",

                "harus_bayar" => "harus_bayar",
                "creditAmount" => "creditAmount",
                "nilai_entry" => "nilai_entry",
                "new_sisa" => "new_sisa",
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
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
                //                "new_sisa" => "new_sisa",
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
            "7499" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010020010" => "new_net3",// piutang dagang
//                            "4010" => "penjualan_bulat",// penjualan projek, menggunakan gerbang yang bulat, bukan yang masih desimal (14 desember 2022)
//                            "2030060" => "ppn",// ppn out
//

                            "4030" => "-new_net3", // penjualan kontijensi
                            "4010" => "penjualan_bulat",// penjualan
                            "2030060" => "ppn",// ppn out

                            "1010020010" => "new_net3", // piutang usaha
                            "1010070030" => "-new_net3", // piutang usaha kontijensi
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
//                            "1010020010" => "new_net3",// piutang dagang
//                            "4010" => "penjualan_bulat",// penjualan projek, menggunakan gerbang yang bulat, bukan yang masih desimal (14 desember 2022)
//                            "2030060" => "ppn",// ppn out
//

                            "4030" => "-new_net3", // penjualan kontijensi
                            "4010" => "penjualan_bulat",// penjualan
                            "2030060" => "ppn",// ppn out

                            "1010020010" => "new_net3", // piutang usaha
                            "1010070030" => "-new_net3", // piutang usaha kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                    // ====== =============
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010020010" => "new_net3", // piutang usaha
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerProject",
                        "loop" => array(
                            "1010070030" => "-new_net3", // piutang usaha kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "projectID",//project
                            "extern2_nama" => "projectName",//project
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2030060" => "ppn",// ppn out
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // MENGURANGI PEMBANTU PENJUALAN KONTIJENSI
                    array(
                        "comName" => "RekeningPembantuPenjualan",
                        "loop" => array(
                            "4030" => "-new_net3", // penjualan kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4030030",
                            "extern_nama" => ".penjualan kontijensi project",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPenjualanProject",
                        "loop" => array(
                            "4030" => "-new_net3", // penjualan kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "projectID",//project
                            "extern_nama" => "projectName",//project
                            "extern2_id" => ".4030030",
                            "extern2_nama" => ".penjualan kontijensi project",
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // MENAMBAH PEMBANTU PENJUALAN
                    array(
                        "comName" => "RekeningPembantuPenjualan",
                        "loop" => array(
                            "4010" => "penjualan_bulat",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4010030",
                            "extern_nama" => ".penjualan project",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPenjualanProject",
                        "loop" => array(
                            "4010" => "penjualan_bulat",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "projectID",//project
                            "extern_nama" => "projectName",//project
                            "extern2_id" => ".4010030",
                            "extern2_nama" => ".penjualan project",
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    // pembantu penjualan projekID
////                    array(
////                        "comName" => "RekeningPembantuPenjualanProject",
////                        "loop" => array(
//////                            "4010030" => "penjualan",// penjualan projek
////                            "4010" => "penjualan",// penjualan projek
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
//////                            "extern_id" => "pihakID",
//////                            "extern_nama" => "pihakName",
//////                            "extern2_id" => "projectID",
//////                            "extern2_nama" => "projectName",
////                            "extern_id" => ".4010030",
////                            "extern_nama" => ".project",
////                            "extern2_id" => "pihakID",
////                            "extern2_nama" => "pihakName",
////                            "extern3_id" => "projectID",
////                            "extern3_nama" => "projectName",
////                            "jenis" => "jenisTr",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//                    // pembantu penjualan project
//                    array(
//                        "comName" => "RekeningPembantuPenjualan",// project
//                        "loop" => array(
////                            "4010" => "penjualan",// penjualan
//                            "4010" => "penjualan_bulat",// penjualan
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".4010030",
//                            "extern_nama" => ".project",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "harga" => "penjualan",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // pembantu penjualan project - konsumen
//                    array(
//                        "comName" => "RekeningPembantuPenjualanKonsumen",// lokal - konsumen
//                        "loop" => array(
////                            "4010" => "penjualan",// penjualan
//                            "4010" => "penjualan_bulat",// penjualan
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".4010030",
//                            "extern_nama" => ".project",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "harga" => "penjualan",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // pembantu penjualan project - seller
//                    array(
//                        "comName" => "RekeningPembantuPenjualanSeller",
//                        "loop" => array(
////                            "4010" => "penjualan",// penjualan
//                            "4010" => "penjualan_bulat",// penjualan
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".4010030",
//                            "extern_nama" => ".project",
//                            "extern2_id" => "sellerID",
//                            "extern2_nama" => "sellerName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "harga" => "penjualan",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "7499" => array(
                "master" => array(// mutasi project
                    array(
                        "comName" => "TransaksiProject",
                        "loop" => array(
                            "project" => "-penjualan",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "projectID",
                            "extern_nama" => "projectName",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
                            "terbayar" => "-penjualan",
//                            "label" => ".piutang dagang",
//                            "target_jenis" => "jenisTr",
//                            "transaksi_id" => "refID",
//                            "terbayar" => "nilai_bayar",
//                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItemProject",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".piutang dagang",
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
);