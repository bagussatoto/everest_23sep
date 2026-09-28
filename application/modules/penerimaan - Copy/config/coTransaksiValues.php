<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(

    // config penerimaan piutang customer (uang masuk dari konsumen)
    "749" => array(
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
                // "ppn"=>"ppn",

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
            "uangMukaProject__saldoPakai" => "uangMukaProject__sisa+uangMukaProject__ppn_sisa",
            "uang_muka_project_dpp_dipakai" => "(100/(100+ppnFactor))*uang_muka_dipakai_ppn",
            "uang_muka_project_ppn_dipakai" => "uang_muka_dipakai_ppn-uang_muka_project_dpp_dipakai",
            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai_ppn+uang_muka_dipakai",
            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli
            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round-(deposit_konsumen+pendapatan_lain_lain)",

            // lebih bayar di switch by chepy 11-jan 2021
            //            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+(totalCredit-nilai_round)",
            "lebih_bayar" => "(nilai_entry+nilai_biaya+pph22_nilai+ppn_nilai_dibayar+totalCredit)-nilai_round",
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
            "uangMukaProject__saldoPakai" => "uangMukaProject__saldoUangMukaRound",
        ),

        "additionalSource" => true,
        "additionalItemSourceKey" => array(
            "top" => "nilai_bayar",
            "bottom" => "tagihan",//harga_nett2
        ),
        "additionalItemSource" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            //            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            //            "ppn" => "ppn",
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
            "nilai_sisa" => "nilai_round",
            //            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
            // "selisih_round" => "sisa-nilai_round",//dipindah divaluebuilder karena kena exponen tidak bisa pakai lib calculate
            "cek_nilai" => "(selisih_round*-1)+sisa",
            // "cek_nilai_sel"=>"selisih_round*-1",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            //            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar)",
            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya)",
            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya)",
        ),
        "preProcessor" => array(
            "749" => array(
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
                            "jenis" => ".1010020010",//piutang dagang
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
                    array(
                        "comName" => "PpnBendaharaSync",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "refID", // id invoicing
                            "jenis" => ".110",//piutang dagang
                            "target_jenis" => ".114",//piutang dagang
                            "jenisTr" =>"jenisTr",
                            "targetSession"=>array(
                                "param"=>"main",
                                "target"=>array(
                                    "extern2_id","extern2_nama","extern_date2","extern_label2"
                                ),
                            ),//untuk inject ke main
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",

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
            "749" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai)",// hutang ke konsumen
                            "1010010010" => "nilai_cash",// kas
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
            "749" => array(
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
                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",

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
                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "ppn_nilai_dibayar",
                            "sisa" => "ppn_nilai_dibayar",
                            "target_jenis" => ".0000",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "srcValue" => "ppn_nilai_dibayar",
                            "extern2_id"=>"faktur_extern2_id",
                            "extern2_nama"=>"faktur_extern2_nama",
                            "extern_date2"=>"faktur_extern_date2",
                            "extern_label2"=>"faktur_extern_label2",
                            "customers_id"=>"pihakID",
                            "customers_nama"=>"pihakName",
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
                            "label" => ".pph22 dibayar dimuka",
                            "ppn" => "ppn_nilai_dibayar",
                            "extern_nilai2" => "dpp_nilai",//dpp ppn
                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "pph22_nilai",
                            "sisa" => "pph22_nilai",
                            "target_jenis" => ".1110",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "srcValue" => "pph22_nilai",
                            "extern2_id"=>"faktur_extern2_id",
                            "extern2_nama"=>"faktur_extern2_nama",
                            "extern_date2"=>"faktur_extern_date2",
                            "extern_label2"=>"faktur_extern_label2",
                            "customers_id"=>"pihakID",
                            "customers_nama"=>"pihakName",
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
                            "extern_id" => "id",// id,nomer invoive INV
                            "extern_nama" => "name",
                            //                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_nomer" => "nomer",
                            //                            "terbayar" => "nilai_bayar",
                            //                            "sisa" => "new_sisa",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    // config penerimaan piutang customer valas(uang masuk dari konsumen)
    "1749" => array(
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
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "nilai_bayar_change" => "nilai_bayar_valas*valas_nilai",
            ),
        ),
        "valueBuilders" => array(
//            "totalCredit" => "creditAmount+creditValue",
//            "nilai_bayar_persen" => "(nilai_entry/tagihan_valas*100)",
//            "nilai_bayar" => "(tagihan*nilai_bayar_persen/100)",
//            "valasFactor" => "nilai_bayar/nilai_entry",
//            "new_sisa" => "sisa-nilai_bayar",
//            "new_sisa_valas" => "sisa_valas-nilai_entry",
            "nilai_bayar_valas" => "nilai_entry",
            "new_sisa" => "sisa-nilai_bayar_change",
        ),
        "valuePopulator" => array(
//            "valueSrc" => "nilai_bayar",
//            "acuanSrc" => ".sisa",
            "valueSrc" => "nilai_bayar_valas",
            "acuanSrc" => ".sisa_valas",
        ),

        "additionalSource" => true,
        "additionalItemSourceKey" => array(
            "top" => "nilai_bayar",
            "bottom" => "harga_nett2",
        ),
        "additionalItemSource" => array(//            "nilai_bayar_valas" => "nilai_bayar/valasFactor",
        ),
        "additionalItemResult" => array(),

        "populators" => array(
//            "nilai_bayar" => array(
//                "mainSrc" => array(
//                    "key" => "nilai_bayar",
//                ),
//                "itemTarget" => array(
//                    "key" => "nilai_bayar",
//                    "maxAmountSrc" => "sisa",
//                ),
//
//            ),
//            "nilai_entry" => array(
//                "mainSrc" => array(
//                    "key" => "nilai_entry",
//                ),
//                "itemTarget" => array(
//                    "key" => "bayar_valas",
//                    "maxAmountSrc" => "sisa_valas",
//                ),
//            ),

            "nilai_bayar_valas" => array(
                "mainSrc" => array(
                    "key" => "nilai_bayar_valas",
                ),
                "itemTarget" => array(
                    "key" => "nilai_bayar_valas",
                    "maxAmountSrc" => "sisa_valas",
                ),
            ),
//            "nilai_entry" => array(
//                "mainSrc" => array(
//                    "key" => "nilai_entry",
//                ),
//                "itemTarget" => array(
//                    "key" => "bayar_valas",
//                    "maxAmountSrc" => "sisa_valas",
//                ),
//            ),

        ),
        "additionalBuilders" => array(//==per-item
            //            "new_sisa" => "sisa-nilai_bayar",
            //            "new_sisa_valas" =>"harus_bayar-nilai_valas_terbayar",
            "new_sisa" => "sisa-nilai_bayar_change",
            "new_sisa_valas" => "sisa_valas-nilai_bayar_valas",
        ),
        "additionalMainBuilders" => array(//==per-item
//            "harus_bayar" => "sisa_valas-totalCredit",
            "harus_bayar_valas" => "sisa_valas",
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
                //                "hpp" => "hpp",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                //                "satuan" => "satuan","note" => "note",
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
            "1749" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "020409" => "-creditAmount",// hutang valas ke konsumen
                            "010204" => "-nilai_bayar_change",// piutang valas
                            "010102" => "nilai_bayar_change",// valas
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
//                            "020409" => "-creditAmount",// hutang valas ke konsumen
                            "010204" => "-nilai_bayar_change",// piutang valas
                            "010102" => "nilai_bayar_change",// valas
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
                        "comName" => "RekeningPembantuCustomerValas",
                        "loop" => array(
                            "010204" => "-nilai_bayar_change",// piutang valas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "qty" => "-nilai_entry",
                            "extern2_id" => "valasDetails",
                            "extern2_nama" => "valasDetails__label",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuValas",
                        "loop" => array(
                            "010102" => "nilai_bayar_change",// valas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valasDetails",
                            "extern_nama" => "valasDetails__label",
                            "jenis" => "jenisTr",
                            "qty" => "nilai_bayar_valas",
                            "produk_nilai" => "nilai_bayar_change",
                            //                            "extern2_id" => "valasID",
                            //                            "extern2_nama" => "valasName",
                            "gudang_id" => "gudangID",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),

            ),
        ),
        "postProcessor" => array(
            "1749" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "creditAmount__transaksi_id",
                            "jenis" => "creditAmount__jenis",
                            //                            "nomer"        => "referenceNomer",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "",
                            "extern2_nama" => "",
                            "label" => ".piutang valas",
                            "terbayar" => "creditAmount",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // locker kas reguler pusat
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".valas",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
                            "produk_id" => "valasDetails",
                            "nama" => "valasDetails__label",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
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
                            "label" => ".piutang valas",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar_change",
                            "sisa" => "new_sisa",
                            "bayar_valas" => "nilai_bayar_valas",
                            "sisa_valas" => "new_sisa_valas",

                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    array(
                        "comName" => "FifoValasAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "valasDetails",
//                            "jml" => "sub_bayar_valas",
//                            "hpp" => "valasFactor",
//                            "jml_nilai" => "nilai_bayar",
                            "jml" => "nilai_bayar_valas",
                            "hpp" => "valas_nilai",
                            "jml_nilai" => "nilai_bayar_change",

                            "nama" => "valasDetails__label",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoValas",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "valasDetails",
                            "produk_nama" => "valasDetails__label",
                            "unit" => "nilai_bayar_valas",
                            "hpp" => "valas_nilai",
                            "jml_nilai" => "nilai_bayar_change",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),

            ),
        ),
    ),
    //penerimaan piutang jasa kirim
    "2749" => array(
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
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //                        "harus_bayar" => "sisa-totalCredit",
            "nilai_bayar" => "nilai_entry+totalCredit",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
        ),

        "additionalSource" => true,
        "additionalItemSourceKey" => array(
            "top" => "nilai_bayar",
            "bottom" => "harga_nett2",
        ),
        "additionalItemSource" => array(
            "harga_nett2" => "harga_nett2",
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "harga_nett2-hpp",
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "harga_nett2",
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
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
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
            "2749" => array(),
        ),
        "postProcessor" => array(
            "2749" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "creditAmount__transaksi_id",
                            "jenis" => "creditAmount__jenis",
                            //                            "nomer"        => "referenceNomer",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang dagang",
                            "terbayar" => "creditAmount",
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

                ),

            ),
        ),
    ),

    //penerimaan pembayaran piutang penjualan jasa
    /*tidak punya coConfigLayout*/
    "1784" => array(
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
                "harga" => "extern_nilai2",
                "nett1" => "nilai_masuk",
                "pph_23_net" => "pph_23",
                //                "ppn" =>""
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //                        "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit+pph_23",
            "nilai_bayar" => "nilai_entry+totalCredit+pph_23",
            "new_nilai_pph23" => "pph_23-pph_23",
            "nett1_bulat" => "extern_nilai2",
            "grand_ppn" => "ppn",
            "ppn_out_bulat" => "ppn",
            "nilai_pembulatan" => "0",
            "grand_pembulatan" => "nilai_entry",
            "new_tagihan" => "nilai_entry",
            "grand_total_ui" => "nett1_bulat",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
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
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit-pph_23",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
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
            "1784" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "020403" => "-creditAmount",// hutang ke konsumen
                            "010205" => "-(creditAmount+nilai_entry+pph_23)",// piutang dagang jasa
                            "010101" => "nilai_entry",// kas
                            "01040100003" => "pph_23",// pph 23 dibayar di muka
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
                            "020403" => "-creditAmount",// hutang ke konsumen
                            "010205" => "-(creditAmount+nilai_entry+pph_23)",// piutang dagang jasa
                            "010101" => "nilai_entry",// kas
                            "01040100003" => "pph_23",// pph 23 dibayar di muka
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
                            "010205" => "-(creditAmount+nilai_entry+pph_23)",// piutang dagang jasa
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
                            "020403" => "-creditAmount",// hutang ke konsumen
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
                            "010101" => "nilai_entry",// kas
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


                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1784" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "creditAmount__transaksi_id",
                            "jenis" => "creditAmount__jenis",
                            //                            "nomer"        => "referenceNomer",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang dagang",
                            "terbayar" => "creditAmount",
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
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".piutang dagang jasa",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "pph_23" => "new_nilai_pph23",
                            "pph_23_terbayar" => "pph_23",
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
    // config
//    "7499" => array(
//        "counters" => array(
//            "stepCode|placeID",
//            "stepCode|olehID",
//            "stepCode|placeID|olehID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//        ),
//        "formatNota" => "stepCode|placeID|customerID",
//        "valueGates" => array(//==sumber nilai yang dikirim kemana2
//            "master" => array(//==sumber nilai utama
//
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
//                //                "refs" => "refs",
//                //                "refs_intext" => "refs_intext",
//            ),
//            "detail" => array(//===sumber nilai berupa rincian
//
//            ),
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
//        ),
//        "valueBuilders" => array(
//            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai",
//            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli
//            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
//            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round-(deposit_konsumen+pendapatan_lain_lain)",
//
//            // lebih bayar di switch by chepy 11-jan 2021
//            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+totalCredit-nilai_round",
//            //            "lebih_bayar" => "nilai_entry+nilai_biaya+totalCredit-nilai_round",
//            // "lebih_bayar" => "nilai_entry+selisih_round-harus_bayar",
//
//            "amount" => "sisa",
//            "credit_amount" => "credit_note_dipakai",
//
//        ),
//        "valuePopulator" => array(
//            //            array(
//            "valueSrc" => "nilai_bayar",
//            "acuanSrc" => ".sisa",
//            //            ),
//        ),
//        "additionalRound" => array(
//            "sisa" => "nilai_round",
//        ),
//
//        "additionalSource" => true,
//        "additionalItemSourceKey" => array(
//            "top" => "nilai_bayar",
//            "bottom" => "tagihan",//harga_nett2
//        ),
//        "additionalItemSource" => array(
//            "harga_nett2" => "tagihan",//harga_nett2
//            "hpp" => "hpp",
//            "ppn" => "ppn",
//            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
//        ),
//        "additionalItemResult" => array(
//            "harga_nett2" => "tagihan",//harga_nett2
//            "hpp" => "hpp",
//            "ppn" => "ppn",
//            "laba_kotor" => "laba_kotor",
//        ),
//
//
//        "populators" => array(
//            "nilai_bayar" => array(
//                "mainSrc" => array(
//                    "key" => "nilai_bayar",
//                ),
//                "itemTarget" => array(
//                    "key" => "nilai_bayar",
//                    "maxAmountSrc" => "sisa",
//                ),
//            ),
//        ),
//        "additionalBuilders" => array(//==per-item
//            //            "new_sisa" => "sisa-nilai_bayar",
//
//        ),
//        "additionalMainBuilders" => array(//==main
//            //            "harus_bayar" => "sisa-totalCredit-nilai_biaya-uang_muka_dipakai",
//            "harus_bayar" => "nilai_round-totalCredit-nilai_biaya",
//            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
//            // "selisih_round" => "sisa-nilai_round",
//            //            "nilai_bayar" => "nilai_entry+totalCredit",
//            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar)",
//        ),
//
//        "preProcessor" => array(
//            "7499" => array(
//                "master" => array(
//                    array(
//                        "comName" => "RekeningValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            //                            "nilai" => "credit_note_dipakai+nilai_entry", // nilai pembayaran total
//                            "nilai" => "credit_note_dipakai+nilai_cash+nilai_biaya+uang_muka_dipakai+pph22_nilai+ppn_nilai_dibayar+selisih_round", // nilai pembayaran total
//                            "jenis" => ".piutang dagang",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "nilai_dipakai" => "nilai_dipakai",
//                                "nilai_sisa" => "nilai_sisa",
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(),
//            ),
//        ),
//        "tableIn" => array(
//            "master" => array(
//                "jenis_master" => "jenisTrMaster",
//                "jenis_top" => "jenisTrTop",
//                "jenis" => "jenisTr",
//                "jenis_label" => "jenisTrName",
//                "div_id" => "divID",
//                "div_nama" => "divName",
//                "dtime" => "dtime",
//                "fulldate" => "fulldate",
//                "oleh_id" => "olehID",
//                "oleh_nama" => "olehName",
//
//                "customers_id" => "pihakID",
//                "customers_nama" => "pihakName",
//
//                "cabang_id" => "placeID",
//                "cabang_nama" => "placeName",
//                "transaksi_nilai" => "nilai_bayar",
//                "transaksi_jenis" => "jenisTr",
//                "keterangan" => "description",
//
//                "bank_rekening_id" => "cash_id",
//                "bank_rekening_nama" => "bank_rekening_nama",
//
//                "ids_ref" => "refs",
//                "ids_ref_intext" => "refs_intext",
//            ),
//            "mainValues" => array(
//                "tagihan" => "tagihan",
//                "terbayar" => "terbayar",
//                "sisa" => "sisa",
//                "nilai_bayar" => "nilai_bayar",
//
//                "harus_bayar" => "harus_bayar",
//                "creditAmount" => "creditAmount",
//                "nilai_entry" => "nilai_entry",
//                "new_sisa" => "new_sisa",
//            ),
//            "detail" => array(
//                "dtime" => "dtime",
//                "produk_id" => "id",
//                "produk_kode" => "code",
//                "produk_label" => "label",
//                "produk_nama" => "name",
//                "produk_ord_jml" => ".1",
//                "produk_ord_hrg" => "nilai_bayar",
//
//            ),
//            "detailValues" => array(
//                "tagihan" => "tagihan",
//                "terbayar" => "terbayar",
//                "sisa" => "sisa",
//                "nilai_bayar" => "nilai_bayar",
//                //                "new_sisa" => "new_sisa",
//            ),
//        ),
//        "tableIn_static" => array(
//            "master" => array(
//                "trash" => 0,
//            ),
//            "detail" => array(
//                "trash" => 0,
//                "produk_jenis" => "invoice",
//            ),
//        ),
//        "components" => array(
//            "7499" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
//                            "kas" => "nilai_cash",
//                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
//                            "biaya usaha" => "nilai_biaya",
//                            "selisih pembulatan" => "selisih_round",
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
//                            "kas" => "nilai_cash",
//                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
//                            "biaya usaha" => "nilai_biaya",
//                            "selisih pembulatan" => "selisih_round",
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // ====== =============
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "pendapatan lain_lain" => "pendapatan_lain_lain",
//                            "hutang ke konsumen" => "deposit_konsumen",
//                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "pendapatan lain_lain" => "pendapatan_lain_lain",
//                            "hutang ke konsumen" => "deposit_konsumen",
//                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            //                          "piutang dagang" => "-(creditAmount+nilai_entry)",
//                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            //                            "kas" => "nilai_entry",
//                            "kas" => "nilai_cash",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account",
//                            "extern_nama" => "cash_account__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account",
//                            "extern_nama" => "cash_account__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //update tambahan biaya usaha suport pelanggan langsung di define tanpa pilihan agar tidak nyasar
//                    array(
//                        "comName" => "RekeningPembantuBiayaUsahaMain",
//                        "loop" => array(
//                            "biaya usaha" => "nilai_biaya",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".3",//id dta biaya usaha
//                            "extern_nama" => ".support pelanggan",///nama data biaya usaha
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //tambahan juranal langsung untuk pph 22 dibayar bendahara negara digeser kepusat
//                    //region cabang
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "-pph22_nilai",
//                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
//                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "-pph22_nilai",
//                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
//                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",//langsung di define karena tidak pakai connecting
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "-pph22_nilai",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => ".-1",
//                            "cabang2_nama" => "PUSAT",
//                            "extern_id" => ".-1",
//                            "extern_nama" => "PUSAT",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //endregion
//
//                    //region pusat
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",
//                            "cabang_nama" => "PUSAT",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",
//                            "cabang_nama" => "PUSAT",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",//langsung di define karena tidak pakai connecting
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
//                    //endregion
//                ),
//                "detail" => array(),
//            ),
//        ),
//        "postProcessor" => array(
//            "7499" => array(
//                "master" => array(
//                    // anti source deposit berkurang
//                    array(
//                        "comName" => "PaymentAntiSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "creditAmount__transaksi_id",
//                            "jenis" => "creditAmount__jenis",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".piutang dagang",
//                            "terbayar" => "credit_note_dipakai",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // anti source deposit bertambah
//                    array(
//                        "comName" => "PaymentAntiSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "transaksi_id" => ".0",
//                            "jenis" => ".0",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".piutang dagang",
//                            "sisa" => "deposit_konsumen",
//                        ),
//                        "reversable" => true,
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
//                            "produk_id" => "cash_account",
//                            "nama" => "cash_account__label",
//                            "nilai" => "nilai_entry",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "PaymentUangMuka",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "uangMuka__transaksi_id",
//                            "jenis" => "uangMuka__jenis",
//                            //                            "nomer"        => "referenceNomer",
//                            "extern_id" => "uangMuka__extern_id",
//                            "extern_nama" => "uangMuka__extern_nama",
//                            "label" => ".uang muka",
//                            "terbayar" => "uang_muka_dipakai",
//                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//
//                    ),
//
//                    //nulis payment source disini karena tidak bisa di pasang di heTransaksiMisc karena antar cabang
//                    array(
//                        "comName" => "PaymentSourceAntarCabang",
//                        "loop" => array(),
//                        "static" => array(
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "nama" => "pihakName",
//                            "label" => ".ppn dibayar bendahara negara",
//                            "ppn" => "ppn_nilai_dibayar",
//                            "extern_nilai2" => "dpp_nilai",//dpp ppn
//                            "extern_date2" => "dtime",//tgl faktur ppn masukan
//                            "npwp" => "customerDetails__npwp", // npwp
//                            "cabang_id" => ".-1",
//                            "tagihan" => "ppn_nilai_dibayar",
//                            "sisa" => "ppn_nilai_dibayar",
//                            "target_jenis" => ".0000",
//                            "jenis" => "transaksi_jenis",
//                            "reference_jenis" => "transaksi_jenis",
//                            "oleh_id" => "olehID",
//                            "oleh_id" => "olehName",
//                            "srcValue" => "ppn_nilai_dibayar",
//                            // "srcValue"=>".0",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "PaymentSourceAntarCabang",
//                        "loop" => array(),
//                        "static" => array(
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "nama" => "pihakName",
//                            "label" => ".pph22 dibayar dimuka",
//                            "ppn" => "ppn_nilai_dibayar",
//                            "extern_nilai2" => "dpp_nilai",//dpp ppn
//                            "extern_date2" => "dtime",//tgl faktur ppn masukan
//                            "npwp" => "customerDetails__npwp", // npwp
//                            "cabang_id" => ".-1",
//                            "tagihan" => "pph22_nilai",
//                            "sisa" => "pph22_nilai",
//                            "target_jenis" => ".1110",
//                            "jenis" => "transaksi_jenis",
//                            "reference_jenis" => "transaksi_jenis",
//                            "oleh_id" => "olehID",
//                            "oleh_id" => "olehName",
//                            "srcValue" => "pph22_nilai",
//                            // "srcValue"=>".0",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(
//                    array(
//                        "comName" => "PaymentSrcItem",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "label" => ".piutang dagang",
//                            "target_jenis" => "jenisTr",
//                            "transaksi_id" => "refID",
//                            "terbayar" => "nilai_bayar",
//                            "sisa" => "new_sisa",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    array(
//                        "comName" => "ReleaserDueDate",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            //                            "target_jenis" => "jenisTr",
//                            "transaksi_id" => "transaksi_id",
//                            "transaksi_nomer" => "nomer",
//                            //                            "terbayar" => "nilai_bayar",
//                            //                            "sisa" => "new_sisa",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//            ),
//        ),
//    ),

//    "7488__" => array(
//        "counters" => array(
//            "stepCode|placeID",
//            "stepCode|olehID",
//            "stepCode|placeID|olehID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//        ),
//        "formatNota" => "stepCode|placeID|customerID",
//        "valueGates" => array(//==sumber nilai yang dikirim kemana2
//            "master" => array(//==sumber nilai utama
//
//                "customerID" => "pihakID",
//                "customerName" => "pihakName",
//                //                "refs" => "refs",
//                //                "refs_intext" => "refs_intext",
//            ),
//            "detail" => array(//===sumber nilai berupa rincian
//
//            ),
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
//        ),
//        "valueBuilders" => array(
//            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai",
//            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli
//            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
//            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round-(deposit_konsumen+pendapatan_lain_lain)",
//
//            // lebih bayar di switch by chepy 11-jan 2021
//            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+totalCredit-nilai_round",
//            //            "lebih_bayar" => "nilai_entry+nilai_biaya+totalCredit-nilai_round",
//            // "lebih_bayar" => "nilai_entry+selisih_round-harus_bayar",
//
//            "amount" => "sisa",
//            "credit_amount" => "credit_note_dipakai",
//
//        ),
//        "valuePopulator" => array(
//            //            array(
//            "valueSrc" => "nilai_bayar",
//            "acuanSrc" => ".sisa",
//            //            ),
//        ),
//        "additionalRound" => array(
//            "sisa" => "nilai_round",
//        ),
//
//        "additionalSource" => true,
//        "additionalItemSourceKey" => array(
//            "top" => "nilai_bayar",
//            "bottom" => "tagihan",//harga_nett2
//        ),
//        "additionalItemSource" => array(
//            "harga_nett2" => "tagihan",//harga_nett2
//            "hpp" => "hpp",
//            "ppn" => "ppn",
//            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
//        ),
//        "additionalItemResult" => array(
//            "harga_nett2" => "tagihan",//harga_nett2
//            "hpp" => "hpp",
//            "ppn" => "ppn",
//            "laba_kotor" => "laba_kotor",
//        ),
//
//
//        "populators" => array(
//            "nilai_bayar" => array(
//                "mainSrc" => array(
//                    "key" => "nilai_bayar",
//                ),
//                "itemTarget" => array(
//                    "key" => "nilai_bayar",
//                    "maxAmountSrc" => "sisa",
//                ),
//            ),
//        ),
//        "additionalBuilders" => array(//==per-item
//            //            "new_sisa" => "sisa-nilai_bayar",
//
//        ),
//        "additionalMainBuilders" => array(//==main
//            //            "harus_bayar" => "sisa-totalCredit-nilai_biaya-uang_muka_dipakai",
//            "harus_bayar" => "nilai_round-totalCredit-nilai_biaya",
//            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
//            // "selisih_round" => "sisa-nilai_round",
//            //            "nilai_bayar" => "nilai_entry+totalCredit",
//            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar)",
//        ),
//
//        "preProcessor" => array(
//            "7488" => array(
//                "master" => array(
//                    array(
//                        "comName" => "RekeningValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            //                            "nilai" => "credit_note_dipakai+nilai_entry", // nilai pembayaran total
//                            "nilai" => "credit_note_dipakai+nilai_cash+nilai_biaya+uang_muka_dipakai+pph22_nilai+ppn_nilai_dibayar+selisih_round", // nilai pembayaran total
//                            "jenis" => ".piutang dagang",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "nilai_dipakai" => "nilai_dipakai",
//                                "nilai_sisa" => "nilai_sisa",
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(),
//            ),
//        ),
//        "tableIn" => array(
//            "master" => array(
//                "jenis_master" => "jenisTrMaster",
//                "jenis_top" => "jenisTrTop",
//                "jenis" => "jenisTr",
//                "jenis_label" => "jenisTrName",
//                "div_id" => "divID",
//                "div_nama" => "divName",
//                "dtime" => "dtime",
//                "fulldate" => "fulldate",
//                "oleh_id" => "olehID",
//                "oleh_nama" => "olehName",
//
//                "customers_id" => "pihakID",
//                "customers_nama" => "pihakName",
//
//                "cabang_id" => "placeID",
//                "cabang_nama" => "placeName",
//                "transaksi_nilai" => "nilai_bayar",
//                "transaksi_jenis" => "jenisTr",
//                "keterangan" => "description",
//
//                "bank_rekening_id" => "cash_id",
//                "bank_rekening_nama" => "bank_rekening_nama",
//
//                "ids_ref" => "refs",
//                "ids_ref_intext" => "refs_intext",
//            ),
//            "mainValues" => array(
//                "tagihan" => "tagihan",
//                "terbayar" => "terbayar",
//                "sisa" => "sisa",
//                "nilai_bayar" => "nilai_bayar",
//
//                "harus_bayar" => "harus_bayar",
//                "creditAmount" => "creditAmount",
//                "nilai_entry" => "nilai_entry",
//                "new_sisa" => "new_sisa",
//            ),
//            "detail" => array(
//                "dtime" => "dtime",
//                "produk_id" => "id",
//                "produk_kode" => "code",
//                "produk_label" => "label",
//                "produk_nama" => "name",
//                "produk_ord_jml" => ".1",
//                "produk_ord_hrg" => "nilai_bayar",
//
//            ),
//            "detailValues" => array(
//                "tagihan" => "tagihan",
//                "terbayar" => "terbayar",
//                "sisa" => "sisa",
//                "nilai_bayar" => "nilai_bayar",
//                //                "new_sisa" => "new_sisa",
//            ),
//        ),
//        "tableIn_static" => array(
//            "master" => array(
//                "trash" => 0,
//            ),
//            "detail" => array(
//                "trash" => 0,
//                "produk_jenis" => "invoice",
//            ),
//        ),
//        "components" => array(
//            "7488" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
//                            "kas" => "nilai_cash",
//                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
//                            "biaya usaha" => "nilai_biaya",
//                            "selisih pembulatan" => "selisih_round",
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
//                            "kas" => "nilai_cash",
//                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
//                            "biaya usaha" => "nilai_biaya",
//                            "selisih pembulatan" => "selisih_round",
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // ====== =============
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "pendapatan lain_lain" => "pendapatan_lain_lain",
//                            "hutang ke konsumen" => "deposit_konsumen",
//                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "pendapatan lain_lain" => "pendapatan_lain_lain",
//                            "hutang ke konsumen" => "deposit_konsumen",
//                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            //                          "piutang dagang" => "-(creditAmount+nilai_entry)",
//                            "piutang dagang" => "-nilai_dipakai_piutang_dagang",
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "hutang ke konsumen" => "-(credit_note_dipakai+uang_muka_dipakai)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            //                            "kas" => "nilai_entry",
//                            "kas" => "nilai_cash",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account",
//                            "extern_nama" => "cash_account__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "kas" => "deposit_konsumen+pendapatan_lain_lain",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account",
//                            "extern_nama" => "cash_account__label",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //update tambahan biaya usaha suport pelanggan langsung di define tanpa pilihan agar tidak nyasar
//                    array(
//                        "comName" => "RekeningPembantuBiayaUsahaMain",
//                        "loop" => array(
//                            "biaya usaha" => "nilai_biaya",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".3",//id dta biaya usaha
//                            "extern_nama" => ".support pelanggan",///nama data biaya usaha
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //tambahan juranal langsung untuk pph 22 dibayar bendahara negara digeser kepusat
//                    //region cabang
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "-pph22_nilai",
//                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
//                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "-pph22_nilai",
//                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
//                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "ppn dibayar bendahara negara" => "-ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",//langsung di define karena tidak pakai connecting
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "-pph22_nilai",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "hutang ke pusat" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => ".-1",
//                            "cabang2_nama" => "PUSAT",
//                            "extern_id" => ".-1",
//                            "extern_nama" => "PUSAT",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //endregion
//
//                    //region pusat
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",
//                            "cabang_nama" => "PUSAT",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",
//                            "cabang_nama" => "PUSAT",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "ppn dibayar bendahara negara" => "ppn_nilai_dibayar",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",//langsung di define karena tidak pakai connecting
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "pph22 dibayar dimuka" => "pph22_nilai",
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "piutang cabang" => "-(pph22_nilai+ppn_nilai_dibayar)",
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
//                    //endregion
//                ),
//                "detail" => array(),
//            ),
//        ),
//        "postProcessor" => array(
//            "7488" => array(
//                "master" => array(
//                    // anti source deposit berkurang
//                    array(
//                        "comName" => "PaymentAntiSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "creditAmount__transaksi_id",
//                            "jenis" => "creditAmount__jenis",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".piutang dagang",
//                            "terbayar" => "credit_note_dipakai",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // anti source deposit bertambah
//                    array(
//                        "comName" => "PaymentAntiSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "transaksi_id" => ".0",
//                            "jenis" => ".0",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".piutang dagang",
//                            "sisa" => "deposit_konsumen",
//                        ),
//                        "reversable" => true,
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
//                            "produk_id" => "cash_account",
//                            "nama" => "cash_account__label",
//                            "nilai" => "nilai_entry",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "PaymentUangMuka",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "uangMuka__transaksi_id",
//                            "jenis" => "uangMuka__jenis",
//                            //                            "nomer"        => "referenceNomer",
//                            "extern_id" => "uangMuka__extern_id",
//                            "extern_nama" => "uangMuka__extern_nama",
//                            "label" => ".uang muka",
//                            "terbayar" => "uang_muka_dipakai",
//                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//
//                    ),
//
//                    //nulis payment source disini karena tidak bisa di pasang di heTransaksiMisc karena antar cabang
//                    array(
//                        "comName" => "PaymentSourceAntarCabang",
//                        "loop" => array(),
//                        "static" => array(
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "nama" => "pihakName",
//                            "label" => ".ppn dibayar bendahara negara",
//                            "ppn" => "ppn_nilai_dibayar",
//                            "extern_nilai2" => "dpp_nilai",//dpp ppn
//                            "extern_date2" => "dtime",//tgl faktur ppn masukan
//                            "npwp" => "customerDetails__npwp", // npwp
//                            "cabang_id" => ".-1",
//                            "tagihan" => "ppn_nilai_dibayar",
//                            "sisa" => "ppn_nilai_dibayar",
//                            "target_jenis" => ".0000",
//                            "jenis" => "transaksi_jenis",
//                            "reference_jenis" => "transaksi_jenis",
//                            "oleh_id" => "olehID",
//                            "oleh_id" => "olehName",
//                            "srcValue" => "ppn_nilai_dibayar",
//                            // "srcValue"=>".0",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "PaymentSourceAntarCabang",
//                        "loop" => array(),
//                        "static" => array(
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "nama" => "pihakName",
//                            "label" => ".pph22 dibayar dimuka",
//                            "ppn" => "ppn_nilai_dibayar",
//                            "extern_nilai2" => "dpp_nilai",//dpp ppn
//                            "extern_date2" => "dtime",//tgl faktur ppn masukan
//                            "npwp" => "customerDetails__npwp", // npwp
//                            "cabang_id" => ".-1",
//                            "tagihan" => "pph22_nilai",
//                            "sisa" => "pph22_nilai",
//                            "target_jenis" => ".1110",
//                            "jenis" => "transaksi_jenis",
//                            "reference_jenis" => "transaksi_jenis",
//                            "oleh_id" => "olehID",
//                            "oleh_id" => "olehName",
//                            "srcValue" => "pph22_nilai",
//                            // "srcValue"=>".0",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(
//                    array(
//                        "comName" => "PaymentSrcItem",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "label" => ".piutang dagang",
//                            "target_jenis" => "jenisTr",
//                            "transaksi_id" => "refID",
//                            "terbayar" => "nilai_bayar",
//                            "sisa" => "new_sisa",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    array(
//                        "comName" => "ReleaserDueDate",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            //                            "target_jenis" => "jenisTr",
//                            "transaksi_id" => "transaksi_id",
//                            "transaksi_nomer" => "nomer",
//                            //                            "terbayar" => "nilai_bayar",
//                            //                            "sisa" => "new_sisa",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//            ),
//        ),
//    ),

    "7488_" => array(
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
                // "ppn"=>"ppn",

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
            //            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+(totalCredit-nilai_round)",
            "lebih_bayar" => "(nilai_entry+nilai_biaya+pph22_nilai+ppn_nilai_dibayar+totalCredit)-nilai_round",
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
            //            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            //            "ppn" => "ppn",
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
            "nilai_sisa" => "nilai_round",
            //            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
            // "selisih_round" => "sisa-nilai_round",//dipindah divaluebuilder karena kena exponen tidak bisa pakai lib calculate
            "cek_nilai" => "(selisih_round*-1)+sisa",
            // "cek_nilai_sel"=>"selisih_round*-1",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            //            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar)",
            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya)",
            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya)",
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
                            //                            "nilai" => "credit_note_dipakai+nilai_entry", // nilai pembayaran total
                            "nilai" => "credit_note_dipakai+nilai_cash+nilai_biaya+uang_muka_dipakai+pph22_nilai+ppn_nilai_dibayar+selisih_round", // nilai pembayaran total
                            "jenis" => ".1010020010",//piutang dagang
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
                    array(
                        "comName" => "PpnBendaharaSync",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "refID", // id invoicing
                            "jenis" => ".110",//piutang dagang
                            "target_jenis" => ".114",//piutang dagang
                            "jenisTr" => "jenisTr",
                            "targetSession" => array(
                                "param" => "main",
                                "target" => array(
                                    "extern2_id", "extern2_nama", "extern_date2", "extern_label2"
                                ),
                            ),//untuk inject ke main
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",

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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",

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
                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "ppn_nilai_dibayar",
                            "sisa" => "ppn_nilai_dibayar",
                            "target_jenis" => ".0000",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "srcValue" => "ppn_nilai_dibayar",
                            "extern2_id" => "faktur_extern2_id",
                            "extern2_nama" => "faktur_extern2_nama",
                            "extern_date2" => "faktur_extern_date2",
                            "extern_label2" => "faktur_extern_label2",
                            "customers_id" => "pihakID",
                            "customers_nama" => "pihakName",
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
                            "label" => ".pph22 dibayar dimuka",
                            "ppn" => "ppn_nilai_dibayar",
                            "extern_nilai2" => "dpp_nilai",//dpp ppn
                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "pph22_nilai",
                            "sisa" => "pph22_nilai",
                            "target_jenis" => ".1110",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "srcValue" => "pph22_nilai",
                            "extern2_id" => "faktur_extern2_id",
                            "extern2_nama" => "faktur_extern2_nama",
                            "extern_date2" => "faktur_extern_date2",
                            "extern_label2" => "faktur_extern_label2",
                            "customers_id" => "pihakID",
                            "customers_nama" => "pihakName",
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
                            "extern_id" => "id",// id,nomer invoive INV
                            "extern_nama" => "name",
                            //                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_nomer" => "nomer",
                            //                            "terbayar" => "nilai_bayar",
                            //                            "sisa" => "new_sisa",
                            "jenis" => "jenisTr",
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
                // "ppn"=>"ppn",

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
            //            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+(totalCredit-nilai_round)",
            "lebih_bayar" => "(nilai_entry+nilai_biaya+pph22_nilai+ppn_nilai_dibayar+totalCredit)-nilai_round",
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
            //            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            //            "ppn" => "ppn",
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
            "nilai_sisa" => "nilai_round",
            //            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
            // "selisih_round" => "sisa-nilai_round",//dipindah divaluebuilder karena kena exponen tidak bisa pakai lib calculate
            "cek_nilai" => "(selisih_round*-1)+sisa",
            // "cek_nilai_sel"=>"selisih_round*-1",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            //            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar)",
            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya)",
            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya)",
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
                            //                            "nilai" => "credit_note_dipakai+nilai_entry", // nilai pembayaran total
                            "nilai" => "credit_note_dipakai+nilai_cash+nilai_biaya+uang_muka_dipakai+pph22_nilai+ppn_nilai_dibayar+selisih_round", // nilai pembayaran total
                            "jenis" => ".1010020010",//piutang dagang
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
                    array(
                        "comName" => "PpnBendaharaSync",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "refID", // id invoicing
                            "jenis" => ".110",//piutang dagang
                            "target_jenis" => ".114",//piutang dagang
                            "jenisTr" =>"jenisTr",
                            "targetSession"=>array(
                                "param"=>"main",
                                "target"=>array(
                                    "extern2_id","extern2_nama","extern_date2","extern_label2"
                                ),
                            ),//untuk inject ke main
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",

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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",

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
                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "ppn_nilai_dibayar",
                            "sisa" => "ppn_nilai_dibayar",
                            "target_jenis" => ".0000",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "srcValue" => "ppn_nilai_dibayar",
                            "extern2_id"=>"faktur_extern2_id",
                            "extern2_nama"=>"faktur_extern2_nama",
                            "extern_date2"=>"faktur_extern_date2",
                            "extern_label2"=>"faktur_extern_label2",
                            "customers_id"=>"pihakID",
                            "customers_nama"=>"pihakName",
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
                            "label" => ".pph22 dibayar dimuka",
                            "ppn" => "ppn_nilai_dibayar",
                            "extern_nilai2" => "dpp_nilai",//dpp ppn
                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "pph22_nilai",
                            "sisa" => "pph22_nilai",
                            "target_jenis" => ".1110",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "srcValue" => "pph22_nilai",
                            "extern2_id"=>"faktur_extern2_id",
                            "extern2_nama"=>"faktur_extern2_nama",
                            "extern_date2"=>"faktur_extern_date2",
                            "extern_label2"=>"faktur_extern_label2",
                            "customers_id"=>"pihakID",
                            "customers_nama"=>"pihakName",
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
                            "extern_id" => "id",// id,nomer invoive INV
                            "extern_nama" => "name",
                            //                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_nomer" => "nomer",
                            //                            "terbayar" => "nilai_bayar",
                            //                            "sisa" => "new_sisa",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    "74677" => array(
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
                // "ppn"=>"ppn",

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
            //            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+(totalCredit-nilai_round)",
            "lebih_bayar" => "(nilai_entry+nilai_biaya+pph22_nilai+ppn_nilai_dibayar+totalCredit)-nilai_round",
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
            //            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            //            "ppn" => "ppn",
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
            "nilai_sisa" => "nilai_round",
            //            "nilai_sisa" => "nilai_round-totalCredit-nilai_biaya",
            // "selisih_round" => "sisa-nilai_round",//dipindah divaluebuilder karena kena exponen tidak bisa pakai lib calculate
            "cek_nilai" => "(selisih_round*-1)+sisa",
            // "cek_nilai_sel"=>"selisih_round*-1",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            //            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar)",
            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya)",
            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya)",
        ),
        "preProcessor" => array(
            "74677" => array(
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
                            "jenis" => ".1010020010",//piutang dagang
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
                    array(
                        "comName" => "PpnBendaharaSync",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "refID", // id invoicing
                            "jenis" => ".110",//piutang dagang
                            "target_jenis" => ".114",//piutang dagang
                            "jenisTr" =>"jenisTr",
                            "targetSession"=>array(
                                "param"=>"main",
                                "target"=>array(
                                    "extern2_id","extern2_nama","extern_date2","extern_label2"
                                ),
                            ),//untuk inject ke main
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",

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
            "74677" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai)",// hutang ke konsumen
                            "1010010010" => "nilai_cash",// kas
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
            "74677" => array(
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
                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",

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
                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "ppn_nilai_dibayar",
                            "sisa" => "ppn_nilai_dibayar",
                            "target_jenis" => ".0000",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "srcValue" => "ppn_nilai_dibayar",
                            "extern2_id"=>"faktur_extern2_id",
                            "extern2_nama"=>"faktur_extern2_nama",
                            "extern_date2"=>"faktur_extern_date2",
                            "extern_label2"=>"faktur_extern_label2",
                            "customers_id"=>"pihakID",
                            "customers_nama"=>"pihakName",
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
                            "label" => ".pph22 dibayar dimuka",
                            "ppn" => "ppn_nilai_dibayar",
                            "extern_nilai2" => "dpp_nilai",//dpp ppn
                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
                            "npwp" => "customerDetails__npwp", // npwp
                            "cabang_id" => ".-1",
                            "tagihan" => "pph22_nilai",
                            "sisa" => "pph22_nilai",
                            "target_jenis" => ".1110",
                            "jenis" => "transaksi_jenis",
                            "reference_jenis" => "transaksi_jenis",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "srcValue" => "pph22_nilai",
                            "extern2_id"=>"faktur_extern2_id",
                            "extern2_nama"=>"faktur_extern2_nama",
                            "extern_date2"=>"faktur_extern_date2",
                            "extern_label2"=>"faktur_extern_label2",
                            "customers_id"=>"pihakID",
                            "customers_nama"=>"pihakName",
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
                            "extern_id" => "id",// id,nomer invoive INV
                            "extern_nama" => "name",
                            //                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_nomer" => "nomer",
                            //                            "terbayar" => "nilai_bayar",
                            //                            "sisa" => "new_sisa",
                            "jenis" => "jenisTr",
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