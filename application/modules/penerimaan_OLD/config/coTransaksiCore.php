<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

$config["coTransaksiCore"] = array(

    // config penerimaan piutang customer (uang masuk dari konsumen)
    "749" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|marketplaceID",
            "stepCode|placeID|marketplaceID",
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
                        "nilai_cash" => "(nilai_entry)",
                        /*
                         * README 20/03/2024
                         * mengurangi 2 x sudah dihitung oleh ui shopingcart tinggal pakai
                         */
//                        "nilai_cash" => "(nilai_entry-nilai_biaya)"
//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)",
                        //                        "nilai_cash" => "nilai_entry",
                    ),
                    // deposit
                    "1" => array(
                        "deposit_konsumen" => "lebih_bayar",
                        "pendapatan_lain_lain" => ".0",
                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                    // pendapatan lain-lain
                    "2" => array(
                        "deposit_konsumen" => ".0",
                        "pendapatan_lain_lain" => "lebih_bayar",
                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                ),
                "cash_account__jenis" => array(
                    "account_in" => array(
                        "cash_account_id" => "cash_account",
                        "cash_account_nama" => "cash_account__label",
                    ),
                    "account_cash" => array(
                        "cash_account_id" => "cash_account",
                        "cash_account_nama" => "cash_account__label",
                    ),
                    "edc" => array(
                        "cash_account_id" => "cash_account__folders",
                        "cash_account_nama" => "cash_account__folders_nama",
                    ),
                ),
                "customerDetails__parent_cek" => array(
                    "0" => array(
                        "nilai_dijadikan_credit_note" => ".0",
                        "nilai_dijadikan_credit_note_pph" => ".0",
                    ),
                    "1" => array(
//                        "nilai_dijadikan_credit_note" => ".0",
//                        "nilai_dijadikan_credit_note_pph" => "nilai_dijadikan_credit_note-pph23",
                        "nilai_dijadikan_credit_note_pph" => "sisa_after_kas",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
//            "credit_amount" => "credit_note_dipakai",
            "credit_note_dipakai" => "credit_amount",
            "point_konsumen_nilai_dipakai" => "pointSetting__nilai*point_konsumen_qtt",
            "uangMukaProject__saldoPakai" => "uangMukaProject__sisa+uangMukaProject__ppn_sisa",
            "uang_muka_project_dpp_dipakai" => "(100/(100+ppnFactor))*uang_muka_dipakai_ppn",
            "uang_muka_project_ppn_dipakai" => "uang_muka_dipakai_ppn-uang_muka_project_dpp_dipakai",
            "totalCredit_1" => "credit_note_dipakai+creditValue",
//            "totalCredit" => "totalCredit_1+uang_muka_dipakai", //sebelum penambahan +uang_muka_dipakai_ppn
            "totalCredit" => "totalCredit_1+uang_muka_dipakai_ppn+uang_muka_dipakai",
            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli
//            "sisa_after_um" => "sisa-uang_muka_dipakai", //sebelum penambahan -uang_muka_dipakai_ppn
            "sisa_after_um" => "sisa-uang_muka_dipakai_ppn-uang_muka_dipakai",
            "sisa_after_return" => "sisa_after_um-credit_amount",
            "sisa_after_pointnilai" => "sisa_after_return-point_konsumen_nilai",
            "sisa_after_ppn_bendahara" => "sisa_after_pointnilai-ppn_nilai_dibayar",
            "sisa_after_biaya_merchant" => "sisa_after_ppn_bendahara-nilai_biaya",// baris netto
            "sisa_after_pph23" => "sisa_after_biaya_merchant-pph23",// setelah pph23
            "sisa_after_kas" => "sisa_after_pph23-nilai_entry",// setelah kas

            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
//            "nilai_bayar" => "nilai_dijadikan_credit_note_pph+nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round+point_konsumen_nilai_dipakai+pph23-(deposit_konsumen+pendapatan_lain_lain)",
            "pph22_dan_ppn_dibayar" => "pph22_nilai+ppn_nilai_dibayar",
            "deposit_dan_pendapatan" => "deposit_konsumen+pendapatan_lain_lain",
//            "nilai_bayar" => "nilai_dijadikan_credit_note_pph+nilai_entry+totalCredit+nilai_biaya+pph22_dan_ppn_dibayar+selisih_round+point_konsumen_nilai_dipakai+pph23-deposit_dan_pendapatan",

            // NILAI_BAYAR yang dipendekkan.....
            "nilai_bayar_1" => "nilai_dijadikan_credit_note_pph+nilai_entry",//nilai_entry
            "nilai_bayar_2" => "nilai_bayar_1+totalCredit",
            "nilai_bayar_3" => "nilai_bayar_2+nilai_biaya",
            "nilai_bayar_4" => "nilai_bayar_3+pph22_dan_ppn_dibayar",
            "nilai_bayar_5" => "nilai_bayar_4+selisih_round",
            "nilai_bayar_6" => "nilai_bayar_5+point_konsumen_nilai_dipakai",
            "nilai_bayar_7" => "nilai_bayar_6+pph23",
            "nilai_bayar_8" => "nilai_bayar_7-deposit_dan_pendapatan",
            "nilai_bayar" => "nilai_bayar_8",
            //----------------------------------

            // lebih bayar di switch by chepy 11-jan 2021
//            "lebih_bayar" => "(nilai_dijadikan_credit_note_pph+nilai_entry+nilai_biaya+pph22_nilai+ppn_nilai_dibayar+totalCredit+point_konsumen_nilai_dipakai+pph23)-nilai_round",
            // LEBIH BAYAR dipendekkan.....
            "lebih_bayar_1" => "nilai_dijadikan_credit_note_pph+nilai_entry",
            "lebih_bayar_2" => "lebih_bayar_1+nilai_biaya",
            "lebih_bayar_3" => "lebih_bayar_2+pph22_nilai",
            "lebih_bayar_4" => "lebih_bayar_3+ppn_nilai_dibayar",
            "lebih_bayar_5" => "lebih_bayar_4+totalCredit",
            "lebih_bayar_6" => "lebih_bayar_5+point_konsumen_nilai_dipakai",
            "lebih_bayar_7" => "lebih_bayar_6+pph23",
            "lebih_bayar_8" => "lebih_bayar_7-nilai_round",
            "lebih_bayar" => "lebih_bayar_8",
            //-----------------------------
            "amount" => "sisa",
            //---------------------
            "dpp_pengganti" => "(11/12)*dpp_final",
            "ppn_final" => "(12/100)*dpp_pengganti",
            "dpp_nppn_final" => "dpp_final+ppn_final",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
            "uangMuka__saldoUangMuka" => "uangMuka__saldoUangMukaround",//maxvalue biar bulat nilainya karena ada di UI shopingcart
            "uangMukaProject__saldoPakai" => "uangMukaProject__saldoUangMukaRound",//maxvalue biar bulat nilainya karena ada di UI shopingcart
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
        "populators" => array(// model ini defaultnya ke gerbang items
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
        "populatorsGate" => "items",// model ini defaultnya ke gerbang items

        "additionalBuilders" => array(//==per-item
//            "new_sisa" => "sisa-nilai_bayar",

        ),
        "additionalMainBuilders" => array(//==main
            "harus_bayar_1" => "nilai_round-totalCredit",
            "harus_bayar_2" => "harus_bayar_1-nilai_biaya",
            "harus_bayar" => "harus_bayar_2-point_konsumen_nilai_dipakai",
            "nilai_sisa" => "nilai_round",
            "cek_nilai" => "(selisih_round*-1)+sisa",

//            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_dijadikan_credit_note+nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai+pph23)",
            "round_sisa_1" => "selisih_round*-1",
            "round_sisa" => "round_sisa_1+sisa",

            "pengurang_1" => "pph22_nilai+totalCredit",//nilai_entry, nilai_dijadikan_credit_note
            "pengurang_2" => "pengurang_1+ppn_nilai_dibayar",
            "pengurang_3" => "pengurang_2+nilai_biaya",
            "pengurang_4" => "pengurang_3+point_konsumen_nilai_dipakai",
            "pengurang_5" => "pengurang_4+pph23",
            "pengurang_6" => "pengurang_5+nilai_entry",
            "pengurang" => "pengurang_6+nilai_dijadikan_credit_note",

            "new_sisa" => "round_sisa-pengurang",

//            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai)",
            "new_sisa_before_entry" => "round_sisa-pengurang_4",

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
//                            "nilai" => "credit_note_dipakai+nilai_cash+nilai_biaya+uang_muka_dipakai+pph22_nilai+ppn_nilai_dibayar+selisih_round+point_konsumen_nilai_dipakai+pph23", // nilai pembayaran total
                            "nilai" => "nilai_bayar", // nilai pembayaran total
                            "jenis" => ".1010020010",//piutang dagang

                            "tipe_penjualan" => "tipe_penjualan",
                            "tipe_penjualan_coa" => ".1010020090",
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

//                "bank_rekening_id" => "cash_id",
//                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
                "bank_id" => "cash_account__folders",
                "bank_nama" => "cash_account__folders_nama",
                "bank_rekening_id" => "cash_account",
                "bank_rekening_nama" => "cash_account__label",
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
                "tseting" => "",
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

                    //=========================
                    // PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "-uang_muka_dipakai",// piutang cabang
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "-uang_muka_dipakai",// piutang cabang
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-uang_muka_dipakai",// piutang cabang
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
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // CABANG
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2010050" => "uang_muka_dipakai",// hutang ke konsumen
                            "2040010" => "-uang_muka_dipakai",// hutang ke pusat
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
                            "2010050" => "uang_muka_dipakai",// hutang ke konsumen
                            "2040010" => "-uang_muka_dipakai",// hutang ke pusat
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-uang_muka_dipakai",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //=========================


                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //"2010050" => "-(uang_muka_project_dpp_dipakai+credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-(uang_muka_dipakai_ppn+credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            //"2030060" => "-uang_muka_project_ppn_dipakai",// ppn keluaran belum ada faktur
                            "1010010010" => "nilai_cash",// kas
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang reguler
                            "1010020090" => "-nilai_dipakai_1010020090",// piutang dagang marketplace
                            "6010" => "nilai_biaya",// biaya usaha
                            "7010110" => "selisih_round",// selisih pembulatan
                            "1010040020" => "pph22_nilai",// pph22 dibayar dimuka
                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            //----------
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                            "validate" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
//                            "2010050" => "-(uang_muka_project_dpp_dipakai+credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-(uang_muka_dipakai_ppn+credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            //"2030060" => "-uang_muka_project_ppn_dipakai",// ppn keluaran belum ada faktur
                            "1010010010" => "nilai_cash",// kas
                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang reguler
                            "1010020090" => "-nilai_dipakai_1010020090",// piutang dagang marketplace
                            "6010" => "nilai_biaya",// biaya usaha
                            "7010110" => "selisih_round",// selisih pembulatan
                            "1010040020" => "pph22_nilai",// pph22 dibayar dimuka
                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            //----------
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
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
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // rekening pembantu piutang usaha reguler
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

                    // rekening pembantu piutang usaha marketplace
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010020090" => "-nilai_dipakai_1010020090",// piutang usaha marketplace
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "marketplaceID",// marketplace
                            "extern_nama" => "marketplaceName",// marketplace
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
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // hutang ke konsumen ayng lama TIDAK DIPAKAI, ??? return, uang muka, point ???
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // rekening pembantu hutang ke konsumen, uang muka
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, return penjualan
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050040",
                            "extern_nama" => ".Return Penjualan",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, point konsumen
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-point_konsumen_nilai_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050030",
                            "extern_nama" => ".Point",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka, konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, return penjualan, konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050040",
                            "extern2_nama" => ".Return Penjualan",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, point konsumen, konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-point_konsumen_nilai_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050030",
                            "extern2_nama" => ".Point",
                            "qty" => "-point_konsumen_qtt",
                            "jml" => "-point_konsumen_qtt",
                            "harga" => "pointSetting__nilai",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // pemakaian uang muka konsumen pelunasan project
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-uang_muka_project_dpp_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050060",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "-uang_muka_project_dpp_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050060",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2030060" => "-uang_muka_project_ppn_dipakai",// ppn keluaran belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
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
                            "1010010010" => "nilai_cash+deposit_konsumen+pendapatan_lain_lain",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",
                            "extern_nama" => "cash_account_nama",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "deposit_konsumen+pendapatan_lain_lain",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_id",
//                            "extern_nama" => "cash_account_nama",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
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
                    //pembantu pph23
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040030" => "pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "pph23",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //tambahan jurnal langsung untuk pph 22 dibayar bendahara negara digeser kepusat

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010040020" => "-pph22_nilai",// pph22
                            "1010040080" => "-ppn_nilai_dibayar",// ppn dibayar bendahara negara
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
                            "1010040030" => "-pph23",// pph23 dibayar dimuka
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
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
                            "1010040030" => "-pph23",// pph23 dibayar dimuka
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
                            "cabang_id" => "placeID",//langsung di define karena tidak pakai connecting
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => "PUSAT",
                            "extern_id" => ".-1",
                            "extern_nama" => "PUSAT",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu pph23
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040030" => "-pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "pph23",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //hutang keskonsumen dibawa ke atas
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "deposit_konsumen",// hutang ke pusat
                            "2010050" => "-deposit_konsumen",// hutang ke konsumen
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
                            "2040010" => "deposit_konsumen",// hutang ke pusat
                            "2010050" => "-deposit_konsumen",// hutang ke konsumen
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
                            "2040010" => "deposit_konsumen",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => "PUSAT",
                            "extern_id" => ".-1",
                            "extern_nama" => "PUSAT",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
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
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
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
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
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
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu pph23
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040030" => "pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "pph23",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //hutang kekonsumen pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "deposit_konsumen",// piutang cabang
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "deposit_konsumen",// piutang cabang
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "deposit_konsumen",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //endregion
                ),
                "detail" => array(
//                     rekening pembantu piutang usaha marketplace detail
                    array(
                        "comName" => "RekeningPembantuCustomerDetailItem",
                        "loop" => array(
                            "1010020090" => "-nilai_bayar_marketplace",// piutang usaha marketplace
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "extern_id",// konsumen
                            "extern_nama" => "extern_nama",// konsumen
                            "extern2_id" => "extern3_id",// marketplace
                            "extern2_nama" => "extern3_nama",// marketplace
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "749" => array(
                "master" => array(
                    // anti source deposit dari return penjualan berkurang
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


                    // payment anti source return penjualan (cache dan mutasi), berkurang
                    array(
                        "comName" => "PaymentAntisourceCustomer",
                        "loop" => array(
                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "-credit_note_dipakai",
                            "label" => ".piutang dagang",
                            "extern_label2" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // locker kas
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // payment source uang muka tanpa ppn dikeluarkan dari dc
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
                            "cabang_id" => ".-1",
                            "cabang_nama" => ".PUSAT",
                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            "label" => ".uang muka konsumen",
                            "terbayar" => "uang_muka_dipakai",
                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // payment source uang muka (cache dan mutasi)
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "-uang_muka_dipakai",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",

//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
                        ),
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

                    // menambah kredit limit konsumen
                    array(
                        "comName" => "TransaksiKreditLimit",
                        "loop" => array(
                            "749" => "nilai_bayar",// hanya penjualan kredit....
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "produk_qty" => ".0",
                            "produk_nilai" => "nilai_bayar",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // payment source uang muka tanpa ppn (lebih bayar) langsung dibawa kepusat tanpa identitas cabang
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang_nama" => ".PUSAT",
                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka konsumen",
                            "tambah" => "deposit_konsumen",
                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    array(
                        "comName" => "PaymentSourceReferenceMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka konsumen",
                            "extern2_id" => "so_id",// transaksi id referensi so project
                            "extern2_nama" => "so_num",// transaksi nomer referensi so project
                            "referensi_po_id" => "so_id",// transaksi id referensi so project
                            "terbayar" => "uang_muka_project_dpp_dipakai",
                            "gateSource" => ".main",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                    array(
//                        "comName" => "PaymentSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "jenis" => "uangMukaProject__jenis",
//                            "label" => ".uang muka konsumen",
//                            "transaksi_id" => "uangMukaProject__transaksi_id",
////                            "terbayar" => "uang_muka_dipakai_ppn",
//                            "terbayar" => "uang_muka_project_dpp_dipakai",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
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
                            "tabel_id" => "tabel_id",
                            "extern3_id" => "marketplaceID",//id marketplace
                            "extern3_nama" => "marketplaceName",//nama marketplace
                            "extern4_id" => "tipe_penjualan",//id marketplace
                            "extern4_nama" => "tipe_penjualan_nama",//nama marketplace
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

                    // data project yang sudah diapprove
                    array(
                        "comName" => "ProdukProjectDibayarItem",// memasukkan pembayaran dibayar diawal
                        "loop" => array(
                            "project" => "nilai_bayar",
                        ),
                        "static" => array(
//                            "id" => "projectDetails",
//                            "nama" => "projectDetails__nama",
                            "id" => "projectID",
                            "nama" => "projectName",
                            "kode" => "produk_kode",
                            "uang_muka_approved" => "nilai_bayar",
                            "customer_id" => "customerID",
                            "customer_nama" => "customerName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // work order dibawa ke pembelian (pertama kali bayar diterima)

                    array(
                        "comName" => "TransaksiStatusItem",
                        "loop" => array(
                            "749" => "nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "customers_id" => "pihakID",
//                            "customers_nama" => "pihakName",
//                            "id_master" => "masterID",
//                            "transaksi_nilai" => "harga",
//                            "diskon_nilai" => "diskon_kategori_unit",
//                            "ppn_nilai" => "new_grand_ppn",
//                            "transaksi_net" => "grandTotal",
                            "transaksi_dibayar" => "nilai_bayar",
//                            "transaksi_reject" => ".0",
//                            "transaksi_fullfillment" => ".0",
//                            "transaksi_nett" => "grandTotal",
//                            "transaksi_saldo" => "grandTotal",
                            "transaksi_id" => "referenceID__2",
                            "transaksi_no" => "referenceNomer__2",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                        "reversable" => true,
                    ),
                ),
            ),
        ),
        //------------------------------
        "preProcessorAuto" => array(
            "749" => array(
                "master" => array(
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040020",//hutang biaya ke pusat
                            "nilai" => "nilai_entry+nilai_dijadikan_credit_note_pph",
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
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040010",//hutang ke pusat
                            "nilai" => "nilai_sisa_2040020",
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
        "componentsAuto" => array(
            "749" => array(
                "master" => array(
                    //region bagian cabang
                    90 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    91 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    92 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",// diisi id bank
                            "extern_nama" => "cash_account_nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    93 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    94 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    100 => array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010010030" => "-nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion>

                    //region bagian pusat
                    95 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "nilai_entry",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "nilai_entry",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    97 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "cash_account_id",// diisi id bank
                            "extern_nama" => "cash_account_nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    99 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    101 => array(
                        "comName" => "RekeningPembantuCreditNote",
                        "loop" => array(
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "customerDetails__parent",
                            "extern_nama" => "customerDetails__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //rekening koran
//                    array(
//                        "comName" => "RekeningPembantuBank",
//                        "loop" => array(
//                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target__folders",//id bank
//                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "cash_account_target__folders",
//                            "extern2_nama" => "cash_account_target__folders_nama",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
//                        "loop" => array(
//                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",//id relasi rekening koran
//                            "extern_nama" => ".2020020010",//lbel relasi rekening koran // rekening koran
//                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
//                        "loop" => array(
//                            "2020020010" => "-nilai_koran_full",// rekening koran
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",//id rekening koran
//                            "extern_nama" => "cash_account_target__label",//label rekening koran
//                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "produk_nilai" => "nilai_koran_full",
//                            "produk_qty" => ".-1",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregkening koran


//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",// diisi id bank
//                            "extern_nama" => "cash_account_target__label",// diisi nama bank
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //rekening koran
//                    array(
//                        "comName" => "RekeningPembantuBank",
//                        "loop" => array(
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target__folders",//id bank
//                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "cash_account_target__folders",
//                            "extern2_nama" => "cash_account_target__folders_nama",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
//                        "loop" => array(
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",//id relasi rekening koran
//                            "extern_nama" => ".2020020010",//lbel relasi rekening koran // rekening koran
//                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
//                        "loop" => array(
//                            "2020020010" => "nilai_koran",//
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",//id rekening koran
//                            "extern_nama" => "cash_account_target__label",//label rekening koran
//                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "produk_nilai" => "nilai_koran_full",
//                            "produk_qty" => ".1",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregkening koran

                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessorAuto" => array(
            "749" => array(
                "master" => array(
                    // locker kas cabang
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "-nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // locker kas reguler pusat
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // locker kas rekening koran pusat
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_cash",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // menambah available rekening koran
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".plafon hutang bank",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_koran_full",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerStockPlafonBankMutasiMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "debet" => "nilai_koran_full",
//                            "produk_nilai" => "nilai_koran_full",
//                            "gudang_id" => ".0",
//                            "jenis" => "jenisTr",
//                            "transaksi_jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".plafon hutang bank",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "-nilai_koran",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerStockPlafonBankMutasiMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "debet" => "-nilai_koran",
//                            "produk_nilai" => "-nilai_koran",
//                            "gudang_id" => ".0",
//                            "jenis" => "jenisTr",
//                            "transaksi_jenis" => "jenisTr",
//                            //                            "transaksi_id"        => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    array(
                        "comName" => "PaymentSrcMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang setoran",
                            "target_jenis" => ".759",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "nilai_entry",
                            "sisa" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
        ),
        //------------------------------

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
                            "1010020040" => "-nilai_bayar_change",// piutang valas
                            "1010010020" => "nilai_bayar_change",// valas
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
                            "1010020040" => "-nilai_bayar_change",// piutang valas
                            "1010010020" => "nilai_bayar_change",// valas
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
                            "1010020040" => "-nilai_bayar_change",// piutang valas
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
                            "1010010020" => "nilai_bayar_change",// valas
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

//                "bank_rekening_id" => "cash_id",
//                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
                "bank_id" => "cash_account__folders",
                "bank_nama" => "cash_account__folders_nama",
                "bank_rekening_id" => "cash_account",
                "bank_rekening_nama" => "cash_account__label",
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

//                "bank_rekening_id" => "cash_id",
//                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
                "bank_id" => "cash_account__folders",
                "bank_nama" => "cash_account__folders_nama",
                "bank_rekening_id" => "cash_account",
                "bank_rekening_nama" => "cash_account__label",
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
                            "2010050" => "-creditAmount",// hutang ke konsumen
                            "1010020050" => "-(creditAmount+nilai_entry+pph_23)",// piutang dagang jasa
                            "1010010010" => "nilai_entry",// kas
                            "1010040030" => "pph_23",// pph 23 dibayar di muka
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
                            "2010050" => "-creditAmount",// hutang ke konsumen
                            "1010020050" => "-(creditAmount+nilai_entry+pph_23)",// piutang dagang jasa
                            "1010010010" => "nilai_entry",// kas
                            "1010040030" => "pph_23",// pph 23 dibayar di muka
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
                            "1010020050" => "-(creditAmount+nilai_entry+pph_23)",// piutang dagang jasa
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
                            "2010050" => "-creditAmount",// hutang ke konsumen
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
                            "1010010010" => "nilai_entry",// kas
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
//                    //region pusat
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
//                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "7488" => array(
                "master" => array(
                    // anti source deposit berkurang
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
                ),
                "detail" => array(
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
                ),
            ),
        ),
    ),
    "7488__" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|marketplaceID",
            "stepCode|placeID|marketplaceID",
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
                        "nilai_cash" => "(nilai_entry)",

                        /*
                         * README 20/03/2024
                         * mengurangi 2 x sudah dihitung oleh ui shopingcart tinggal pakai
                         */
//                        "nilai_cash" => "(nilai_entry-nilai_biaya)"

//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)",
                        //                        "nilai_cash" => "nilai_entry",
                    ),
                    // deposit
                    "1" => array(
                        "deposit_konsumen" => "lebih_bayar",
                        "pendapatan_lain_lain" => ".0",
                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                    // pendapatan lain-lain
                    "2" => array(
                        "deposit_konsumen" => ".0",
                        "pendapatan_lain_lain" => "lebih_bayar",
                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                ),
                "cash_account__jenis" => array(
                    "account_in" => array(
                        "cash_account_id" => "cash_account",
                        "cash_account_nama" => "cash_account__label",
                    ),
                    "account_cash" => array(
                        "cash_account_id" => "cash_account",
                        "cash_account_nama" => "cash_account__label",
                    ),
                    "edc" => array(
                        "cash_account_id" => "cash_account__folders",
                        "cash_account_nama" => "cash_account__folders_nama",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
//            "credit_amount" => "credit_note_dipakai",
            "credit_note_dipakai" => "credit_amount",
            "point_konsumen_nilai_dipakai" => "pointSetting__nilai*point_konsumen_qtt",
            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai",
            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli

            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
//            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round-(deposit_konsumen+pendapatan_lain_lain)",
            "nilai_bayar" => "nilai_dijadikan_credit_note+nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round+point_konsumen_nilai_dipakai+pph23-(deposit_konsumen+pendapatan_lain_lain)",

            // lebih bayar di switch by chepy 11-jan 2021
//            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+(totalCredit-nilai_round)",
            "lebih_bayar" => "(nilai_entry+nilai_biaya+pph22_nilai+ppn_nilai_dibayar+totalCredit+point_konsumen_nilai_dipakai+pph23)-nilai_round",
            //            "lebih_bayar" => "nilai_entry+nilai_biaya+totalCredit-nilai_round",
            // "lebih_bayar" => "nilai_entry+selisih_round-harus_bayar",

            "amount" => "sisa",


        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
            "uangMuka__saldoUangMuka" => "uangMuka__saldoUangMukaround",//maxvalue biar bulat nilainya karena ada di UI shopingcart
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
        "populators" => array(// model ini defaultnya ke gerbang items
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
        "populatorsGate" => "items",// model ini defaultnya ke gerbang items

        "additionalBuilders" => array(//==per-item
//            "new_sisa" => "sisa-nilai_bayar",

        ),
        "additionalMainBuilders" => array(//==main
            "harus_bayar" => "nilai_round-totalCredit-nilai_biaya-point_konsumen_nilai_dipakai",
            "nilai_sisa" => "nilai_round",
            "cek_nilai" => "(selisih_round*-1)+sisa",
//            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai+pph23)",
//            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai)",
            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_dijadikan_credit_note+nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai+pph23)",
            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai)",
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
                            "nilai" => "nilai_bayar", // nilai pembayaran total
                            "jenis" => ".1010020060",//piutang dagang

//                            "tipe_penjualan" => "tipe_penjualan",
//                            "tipe_penjualan_coa" => ".1010020090",
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

//                "bank_rekening_id" => "cash_id",
//                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
                "bank_id" => "cash_account__folders",
                "bank_nama" => "cash_account__folders_nama",
                "bank_rekening_id" => "cash_account",
                "bank_rekening_nama" => "cash_account__label",
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
                "tseting" => "",
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
                            "1010020060" => "-nilai_bayar", // piutang retensi
                            "1010010010" => "nilai_bayar",// kas
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
                            "1010020060" => "-nilai_bayar", // piutang retensi
                            "1010010010" => "nilai_bayar",// kas
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
                            "1010020060" => "-nilai_bayar", // piutang retensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerProject",
                        "loop" => array(
                            "1010020060" => "-nilai_bayar", // piutang retensi
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "nilai_bayar",// kas
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
            "7488" => array(
                "master" => array(
                    // anti source deposit dari return penjualan berkurang
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


                    // payment anti source return penjualan (cache dan mutasi), berkurang
                    array(
                        "comName" => "PaymentAntisourceCustomer",
                        "loop" => array(
                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "-credit_note_dipakai",
                            "label" => ".piutang dagang",
                            "extern_label2" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                    // payment anti source return penjualan (cache dan mutasi), bertambah
//                    array(
//                        "comName" => "PaymentAntisourceCustomer",
//                        "loop" => array(
//                            "2010050" => "deposit_konsumen",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "gudang_id" => ".0",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "nilai" => "deposit_konsumen",
//                            "label" => ".piutang dagang",
//                            "extern_label2" => ".customer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // locker kas
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // payment source uang muka tanpa ppn
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
//                            "label" => ".uang muka",
                            "label" => ".uang muka konsumen",
                            "terbayar" => "uang_muka_dipakai",
                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // payment source uang muka (cache dan mutasi)
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "-uang_muka_dipakai",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",

//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
                        ),
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

//                    // data project yang sudah diapprove
//                    array(
//                        "comName" => "ProdukProjectDibayar",// memasukkan pembayaran dibayar diawal
//                        "loop" => array(
//                            "project" => "nilai_entry",
//                        ),
//                        "static" => array(
////                            "id" => "projectDetails",
////                            "nama" => "projectDetails__nama",
//                            "id" => "projectID",
//                            "nama" => "projectName",
//                            "kode" => "produk_kode",
//                            "uang_muka_approved" => "nilai_entry",
//                            "customer_id" => "customerID",
//                            "customer_nama" => "customerName",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // menambah kredit limit konsumen
                    array(
                        "comName" => "TransaksiKreditLimit",
                        "loop" => array(
                            "7488" => "nilai_bayar",// hanya penjualan kredit....
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "produk_qty" => ".0",
                            "produk_nilai" => "nilai_bayar",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // payment source uang muka tanpa ppn (lebih bayar)
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka konsumen",
                            "tambah" => "deposit_konsumen",
                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(
                    // tabel payment source piutang retensi
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".retensi",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                            "tabel_id" => "tabel_id",
                            "extern3_id" => "projectID",//id project
                            "extern3_nama" => "projectName",//nama project
                            "extern4_id" => "tipe_penjualan",//id marketplace
                            "extern4_nama" => "tipe_penjualan_nama",//nama marketplace
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

                    // data project yang sudah diapprove
                    array(
                        "comName" => "ProdukProjectDibayarItem",// memasukkan pembayaran dibayar diawal
                        "loop" => array(
                            "project" => "nilai_bayar",
                        ),
                        "static" => array(
//                            "id" => "projectDetails",
//                            "nama" => "projectDetails__nama",
                            "id" => "projectID",
                            "nama" => "projectName",
                            "kode" => "produk_kode",
                            "uang_muka_approved" => "nilai_bayar",
                            "customer_id" => "customerID",
                            "customer_nama" => "customerName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // work order dibawa ke pembelian (pertama kali bayar diterima)


                ),
            ),
        ),
        //------------------------------
        "preProcessorAuto" => array(
            "7488" => array(
                "master" => array(
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040020",//hutang biaya ke pusat
                            "nilai" => "nilai_entry+nilai_dijadikan_credit_note",
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
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040010",//hutang ke pusat
                            "nilai" => "nilai_sisa_2040020",
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
        "componentsAuto" => array(
            "7488" => array(
                "master" => array(
                    //region bagian cabang
                    90 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    91 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    92 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",// diisi id bank
                            "extern_nama" => "cash_account_nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    93 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    94 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    100 => array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion>

                    //region bagian pusat
                    95 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "nilai_entry",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "nilai_entry",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    97 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "cash_account_id",// diisi id bank
                            "extern_nama" => "cash_account_nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    99 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    101 => array(
                        "comName" => "RekeningPembantuCreditNote",
                        "loop" => array(
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "customerDetails__parent",
                            "extern_nama" => "customerDetails__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //rekening koran
//                    array(
//                        "comName" => "RekeningPembantuBank",
//                        "loop" => array(
//                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target__folders",//id bank
//                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "cash_account_target__folders",
//                            "extern2_nama" => "cash_account_target__folders_nama",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
//                        "loop" => array(
//                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",//id relasi rekening koran
//                            "extern_nama" => ".2020020010",//lbel relasi rekening koran // rekening koran
//                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
//                        "loop" => array(
//                            "2020020010" => "-nilai_koran_full",// rekening koran
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",//id rekening koran
//                            "extern_nama" => "cash_account_target__label",//label rekening koran
//                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "produk_nilai" => "nilai_koran_full",
//                            "produk_qty" => ".-1",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregkening koran


//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",// diisi id bank
//                            "extern_nama" => "cash_account_target__label",// diisi nama bank
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //rekening koran
//                    array(
//                        "comName" => "RekeningPembantuBank",
//                        "loop" => array(
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target__folders",//id bank
//                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "cash_account_target__folders",
//                            "extern2_nama" => "cash_account_target__folders_nama",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
//                        "loop" => array(
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",//id relasi rekening koran
//                            "extern_nama" => ".2020020010",//lbel relasi rekening koran // rekening koran
//                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
//                        "loop" => array(
//                            "2020020010" => "nilai_koran",//
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",//id rekening koran
//                            "extern_nama" => "cash_account_target__label",//label rekening koran
//                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "produk_nilai" => "nilai_koran_full",
//                            "produk_qty" => ".1",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregkening koran

                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessorAuto" => array(
            "7488" => array(
                "master" => array(
                    // locker kas cabang
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "-nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // locker kas reguler pusat
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // locker kas rekening koran pusat
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_cash",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // menambah available rekening koran
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".plafon hutang bank",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_koran_full",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerStockPlafonBankMutasiMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "debet" => "nilai_koran_full",
//                            "produk_nilai" => "nilai_koran_full",
//                            "gudang_id" => ".0",
//                            "jenis" => "jenisTr",
//                            "transaksi_jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".plafon hutang bank",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "-nilai_koran",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerStockPlafonBankMutasiMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "debet" => "-nilai_koran",
//                            "produk_nilai" => "-nilai_koran",
//                            "gudang_id" => ".0",
//                            "jenis" => "jenisTr",
//                            "transaksi_jenis" => "jenisTr",
//                            //                            "transaksi_id"        => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    array(
                        "comName" => "PaymentSrcMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang setoran",
                            "target_jenis" => ".759",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "nilai_entry",
                            "sisa" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
        ),
        //------------------------------

    ),
    "7488" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|marketplaceID",
            "stepCode|placeID|marketplaceID",
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
                        "nilai_cash" => "(nilai_entry)",

                        /*
                         * README 20/03/2024
                         * mengurangi 2 x sudah dihitung oleh ui shopingcart tinggal pakai
                         */
//                        "nilai_cash" => "(nilai_entry-nilai_biaya)"

//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)",
                        //                        "nilai_cash" => "nilai_entry",
                    ),
                    // deposit
                    "1" => array(
                        "deposit_konsumen" => "lebih_bayar",
                        "pendapatan_lain_lain" => ".0",
                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                    // pendapatan lain-lain
                    "2" => array(
                        "deposit_konsumen" => ".0",
                        "pendapatan_lain_lain" => "lebih_bayar",
                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                ),
                "cash_account__jenis" => array(
                    "account_in" => array(
                        "cash_account_id" => "cash_account",
                        "cash_account_nama" => "cash_account__label",
                    ),
                    "account_cash" => array(
                        "cash_account_id" => "cash_account",
                        "cash_account_nama" => "cash_account__label",
                    ),
                    "edc" => array(
                        "cash_account_id" => "cash_account__folders",
                        "cash_account_nama" => "cash_account__folders_nama",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
//            "credit_amount" => "credit_note_dipakai",
            "credit_note_dipakai" => "credit_amount",
            "point_konsumen_nilai_dipakai" => "pointSetting__nilai*point_konsumen_qtt",
            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai",
            // "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",//asli
            "sisa_after_um" => "sisa-uang_muka_dipakai",
            "sisa_after_return" => "sisa_after_um-credit_amount",
            "sisa_after_pointnilai" => "sisa_after_return-point_konsumen_nilai",
            "sisa_after_ppn_bendahara" => "sisa_after_pointnilai-ppn_nilai_dibayar",
            "sisa_after_biaya_merchant" => "sisa_after_ppn_bendahara-nilai_biaya",// baris netto
            "sisa_after_pph23" => "sisa_after_biaya_merchant-pph23",// setelah pph23
            "sisa_after_kas" => "sisa_after_pph23-nilai_entry",// setelah kas

            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
//            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round-(deposit_konsumen+pendapatan_lain_lain)",
//            "nilai_bayar" => "nilai_dijadikan_credit_note+nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round+point_konsumen_nilai_dipakai+pph23-(deposit_konsumen+pendapatan_lain_lain)",
            "pph22_dan_ppn_dibayar" => "pph22_nilai+ppn_nilai_dibayar",
            "deposit_dan_pendapatan" => "deposit_konsumen+pendapatan_lain_lain",

            // NILAI_BAYAR yang dipendekkan.....
            "nilai_bayar_1" => "nilai_dijadikan_credit_note_pph+nilai_entry",
            "nilai_bayar_2" => "nilai_bayar_1+totalCredit",
            "nilai_bayar_3" => "nilai_bayar_2+nilai_biaya",
            "nilai_bayar_4" => "nilai_bayar_3+pph22_dan_ppn_dibayar",
            "nilai_bayar_5" => "nilai_bayar_4+selisih_round",
            "nilai_bayar_6" => "nilai_bayar_5+point_konsumen_nilai_dipakai",
            "nilai_bayar_7" => "nilai_bayar_6+pph23",
            "nilai_bayar_8" => "nilai_bayar_7-deposit_dan_pendapatan",
            "nilai_bayar" => "nilai_bayar_8",
            //----------------------------------

            // lebih bayar di switch by chepy 11-jan 2021
//            "lebih_bayar" => "(nilai_entry-nilai_biaya-pph22_nilai-ppn_nilai_dibayar)+(totalCredit-nilai_round)",
//            "lebih_bayar" => "(nilai_entry+nilai_biaya+pph22_nilai+ppn_nilai_dibayar+totalCredit+point_konsumen_nilai_dipakai+pph23)-nilai_round",
            //            "lebih_bayar" => "nilai_entry+nilai_biaya+totalCredit-nilai_round",
            // "lebih_bayar" => "nilai_entry+selisih_round-harus_bayar",
            // LEBIH BAYAR dipendekkan.....
            "lebih_bayar_1" => "nilai_dijadikan_credit_note_pph+nilai_entry",
            "lebih_bayar_2" => "lebih_bayar_1+nilai_biaya",
            "lebih_bayar_3" => "lebih_bayar_2+pph22_nilai",
            "lebih_bayar_4" => "lebih_bayar_3+ppn_nilai_dibayar",
            "lebih_bayar_5" => "lebih_bayar_4+totalCredit",
            "lebih_bayar_6" => "lebih_bayar_5+point_konsumen_nilai_dipakai",
            "lebih_bayar_7" => "lebih_bayar_6+pph23",
            "lebih_bayar_8" => "lebih_bayar_7-nilai_round",
            "lebih_bayar" => "lebih_bayar_8",
            "amount" => "sisa",
            //---------------------
            "dpp_pengganti" => "(11/12)*dpp_final",
            "ppn_final" => "(12/100)*dpp_pengganti",
            "dpp_nppn_final" => "dpp_final+ppn_final",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
            "uangMuka__saldoUangMuka" => "uangMuka__saldoUangMukaround",//maxvalue biar bulat nilainya karena ada di UI shopingcart
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
        "populators" => array(// model ini defaultnya ke gerbang items
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
        "populatorsGate" => "items",// model ini defaultnya ke gerbang items

        "additionalBuilders" => array(//==per-item
//            "new_sisa" => "sisa-nilai_bayar",

        ),
        "additionalMainBuilders" => array(//==main
            "harus_bayar" => "nilai_round-totalCredit-nilai_biaya-point_konsumen_nilai_dipakai",
            "nilai_sisa" => "nilai_round",
            "cek_nilai" => "(selisih_round*-1)+sisa",
//            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai+pph23)",
//            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai)",
//            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_dijadikan_credit_note+nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai+pph23)",
//            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai)",

            "round_sisa_1" => "selisih_round*-1",
            "round_sisa" => "round_sisa_1+sisa",
            "pengurang_1" => "nilai_dijadikan_credit_note+totalCredit",//nilai_entry
            "pengurang_2" => "pengurang_1+pph22_nilai",
            "pengurang_3" => "pengurang_2+ppn_nilai_dibayar",
            "pengurang_4" => "pengurang_3+nilai_biaya",
            "pengurang_5" => "pengurang_4+point_konsumen_nilai_dipakai",
            "pengurang_6" => "pengurang_5+pph23",
            "pengurang" => "pengurang_6+nilai_entry",

            "new_sisa" => "round_sisa-pengurang",
            "new_sisa_before_entry" => "round_sisa-pengurang_6",
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
                            "nilai" => "nilai_bayar", // nilai pembayaran total
                            "jenis" => ".1010020060",//piutang dagang

//                            "tipe_penjualan" => "tipe_penjualan",
//                            "tipe_penjualan_coa" => ".1010020090",
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

//                "bank_rekening_id" => "cash_id",
//                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
                "bank_id" => "cash_account__folders",
                "bank_nama" => "cash_account__folders_nama",
                "bank_rekening_id" => "cash_account",
                "bank_rekening_nama" => "cash_account__label",
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
                "tseting" => "",
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

                    //=========================
                    // PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "-uang_muka_dipakai",// piutang cabang
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "-uang_muka_dipakai",// piutang cabang
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-uang_muka_dipakai",// piutang cabang
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
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // CABANG
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2010050" => "uang_muka_dipakai",// hutang ke konsumen
                            "2040010" => "-uang_muka_dipakai",// hutang ke pusat
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
                            "2010050" => "uang_muka_dipakai",// hutang ke konsumen
                            "2040010" => "-uang_muka_dipakai",// hutang ke pusat
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-uang_muka_dipakai",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //=========================


                    // reguler
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010020060" => "-nilai_bayar", // piutang retensi
                            "1010010010" => "nilai_cash",// kas

                            "6010" => "nilai_biaya",// biaya usaha
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
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
                            "1010020060" => "-nilai_bayar", // piutang retensi
                            "1010010010" => "nilai_cash",// kas

                            "6010" => "nilai_biaya",// biaya usaha
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // lebih bayar
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
                            "1010020060" => "-nilai_bayar", // piutang retensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerProject",
                        "loop" => array(
                            "1010020060" => "-nilai_bayar", // piutang retensi
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "(nilai_cash+deposit_konsumen+pendapatan_lain_lain)",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",
                            "extern_nama" => "cash_account_nama",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "deposit_konsumen+pendapatan_lain_lain",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_id",
//                            "extern_nama" => "cash_account_nama",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //----
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
                    //pembantu pph23
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040030" => "pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "pph23",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // rekening pembantu hutang ke konsumen, uang muka
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, return penjualan
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050040",
                            "extern_nama" => ".Return Penjualan",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, point konsumen
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-point_konsumen_nilai_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050030",
                            "extern_nama" => ".Point",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka, konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, return penjualan, konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050040",
                            "extern2_nama" => ".Return Penjualan",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, point konsumen, konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
//                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "2010050" => "-point_konsumen_nilai_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050030",
                            "extern2_nama" => ".Point",
                            "qty" => "-point_konsumen_qtt",
                            "jml" => "-point_konsumen_qtt",
                            "harga" => "pointSetting__nilai",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010040020" => "-pph22_nilai",// pph22
                            "1010040080" => "-ppn_nilai_dibayar",// ppn dibayar bendahara negara
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
                            "1010040030" => "-pph23",// pph23 dibayar dimuka
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
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
                            "1010040030" => "-pph23",// pph23 dibayar dimuka
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
                            "cabang_id" => "placeID",//langsung di define karena tidak pakai connecting
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => "PUSAT",
                            "extern_id" => ".-1",
                            "extern_nama" => "PUSAT",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu pph23
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040030" => "-pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "pph23",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //hutang keskonsumen dibawa ke atas
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "deposit_konsumen",// hutang ke pusat
                            "2010050" => "-deposit_konsumen",// hutang ke konsumen
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
                            "2040010" => "deposit_konsumen",// hutang ke pusat
                            "2010050" => "-deposit_konsumen",// hutang ke konsumen
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
                            "2040010" => "deposit_konsumen",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => "PUSAT",
                            "extern_id" => ".-1",
                            "extern_nama" => "PUSAT",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "-deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
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
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
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
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
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
                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu pph23
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040030" => "pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "pph23",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //hutang kekonsumen pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "deposit_konsumen",// piutang cabang
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "deposit_konsumen",// piutang cabang
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "deposit_konsumen",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "deposit_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
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
                    // anti source deposit dari return penjualan berkurang
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


                    // payment anti source return penjualan (cache dan mutasi), berkurang
                    array(
                        "comName" => "PaymentAntisourceCustomer",
                        "loop" => array(
                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "-credit_note_dipakai",
                            "label" => ".piutang dagang",
                            "extern_label2" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // locker kas
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // payment source uang muka tanpa ppn dikeluarkan dari dc
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
                            "cabang_id" => ".-1",
                            "cabang_nama" => ".PUSAT",
                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            "label" => ".uang muka konsumen",
                            "terbayar" => "uang_muka_dipakai",
                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // payment source uang muka (cache dan mutasi)
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "-uang_muka_dipakai",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",

//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
                        ),
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


                    // menambah kredit limit konsumen
                    array(
                        "comName" => "TransaksiKreditLimit",
                        "loop" => array(
                            "7488" => "nilai_bayar",// hanya penjualan kredit....
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "produk_qty" => ".0",
                            "produk_nilai" => "nilai_bayar",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // payment source uang muka tanpa ppn (lebih bayar)
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
//                            "cabang_id" => "placeID",
                            "cabang_id" => ".-1",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka konsumen",
                            "tambah" => "deposit_konsumen",
                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(
                    // tabel payment source piutang retensi
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".retensi",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                            "tabel_id" => "tabel_id",
                            "extern3_id" => "projectID",//id project
                            "extern3_nama" => "projectName",//nama project
                            "extern4_id" => "tipe_penjualan",//id marketplace
                            "extern4_nama" => "tipe_penjualan_nama",//nama marketplace
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

                    // data project yang sudah diapprove
                    array(
                        "comName" => "ProdukProjectDibayarItem",// memasukkan pembayaran dibayar diawal
                        "loop" => array(
                            "project" => "nilai_bayar",
                        ),
                        "static" => array(
//                            "id" => "projectDetails",
//                            "nama" => "projectDetails__nama",
                            "id" => "projectID",
                            "nama" => "projectName",
                            "kode" => "produk_kode",
                            "uang_muka_approved" => "nilai_bayar",
                            "customer_id" => "customerID",
                            "customer_nama" => "customerName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // work order dibawa ke pembelian (pertama kali bayar diterima)


                ),
            ),
        ),
        //------------------------------
        "preProcessorAuto" => array(
            "7488" => array(
                "master" => array(
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040020",//hutang biaya ke pusat
                            "nilai" => "nilai_entry+nilai_dijadikan_credit_note",
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
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040010",//hutang ke pusat
                            "nilai" => "nilai_sisa_2040020",
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
        "componentsAuto" => array(
            "7488" => array(
                "master" => array(
                    //region bagian cabang
                    90 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    91 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    92 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",// diisi id bank
                            "extern_nama" => "cash_account_nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    93 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    94 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    100 => array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion>

                    //region bagian pusat
                    95 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "nilai_entry",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "nilai_entry",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    97 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "cash_account_id",// diisi id bank
                            "extern_nama" => "cash_account_nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    99 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    101 => array(
                        "comName" => "RekeningPembantuCreditNote",
                        "loop" => array(
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "customerDetails__parent",
                            "extern_nama" => "customerDetails__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //rekening koran
//                    array(
//                        "comName" => "RekeningPembantuBank",
//                        "loop" => array(
//                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target__folders",//id bank
//                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "cash_account_target__folders",
//                            "extern2_nama" => "cash_account_target__folders_nama",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
//                        "loop" => array(
//                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",//id relasi rekening koran
//                            "extern_nama" => ".2020020010",//lbel relasi rekening koran // rekening koran
//                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
//                        "loop" => array(
//                            "2020020010" => "-nilai_koran_full",// rekening koran
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",//id rekening koran
//                            "extern_nama" => "cash_account_target__label",//label rekening koran
//                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "produk_nilai" => "nilai_koran_full",
//                            "produk_qty" => ".-1",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregkening koran


//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",// diisi id bank
//                            "extern_nama" => "cash_account_target__label",// diisi nama bank
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //rekening koran
//                    array(
//                        "comName" => "RekeningPembantuBank",
//                        "loop" => array(
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target__folders",//id bank
//                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "cash_account_target__folders",
//                            "extern2_nama" => "cash_account_target__folders_nama",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
//                        "loop" => array(
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",//id relasi rekening koran
//                            "extern_nama" => ".2020020010",//lbel relasi rekening koran // rekening koran
//                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
//                        "loop" => array(
//                            "2020020010" => "nilai_koran",//
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",//id rekening koran
//                            "extern_nama" => "cash_account_target__label",//label rekening koran
//                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "produk_nilai" => "nilai_koran_full",
//                            "produk_qty" => ".1",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregkening koran

                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessorAuto" => array(
            "7488" => array(
                "master" => array(
                    // locker kas cabang
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "-nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // locker kas reguler pusat
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // locker kas rekening koran pusat
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_cash",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // menambah available rekening koran
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".plafon hutang bank",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_koran_full",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerStockPlafonBankMutasiMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "debet" => "nilai_koran_full",
//                            "produk_nilai" => "nilai_koran_full",
//                            "gudang_id" => ".0",
//                            "jenis" => "jenisTr",
//                            "transaksi_jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".plafon hutang bank",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "-nilai_koran",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerStockPlafonBankMutasiMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "debet" => "-nilai_koran",
//                            "produk_nilai" => "-nilai_koran",
//                            "gudang_id" => ".0",
//                            "jenis" => "jenisTr",
//                            "transaksi_jenis" => "jenisTr",
//                            //                            "transaksi_id"        => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    array(
                        "comName" => "PaymentSrcMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang setoran",
                            "target_jenis" => ".759",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "nilai_entry",
                            "sisa" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
        ),
        //------------------------------

    ),
    "74677" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|marketplaceID",
            "stepCode|placeID|marketplaceID",
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
                        "nilai_cash" => "(nilai_entry)",

                        /*
                         * README 20/03/2024
                         * mengurangi 2 x sudah dihitung oleh ui shopingcart tinggal pakai
                         */
//                        "nilai_cash" => "(nilai_entry-nilai_biaya)"

//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)",
                        //                        "nilai_cash" => "nilai_entry",
                    ),
                    // deposit
                    "1" => array(
                        "deposit_konsumen" => "lebih_bayar",
                        "pendapatan_lain_lain" => ".0",
                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                    // pendapatan lain-lain
                    "2" => array(
                        "deposit_konsumen" => ".0",
                        "pendapatan_lain_lain" => "lebih_bayar",
                        "nilai_cash" => "nilai_entry-lebih_bayar",
//                        "nilai_cash" => "(nilai_entry-nilai_biaya-pph23)-lebih_bayar",
                        //                        "nilai_cash" => "nilai_entry-lebih_bayar",
                    ),
                ),
                "cash_account__jenis" => array(
                    "account_in" => array(
                        "cash_account_id" => "cash_account",
                        "cash_account_nama" => "cash_account__label",
                    ),
                    "account_cash" => array(
                        "cash_account_id" => "cash_account",
                        "cash_account_nama" => "cash_account__label",
                    ),
                    "edc" => array(
                        "cash_account_id" => "cash_account__folders",
                        "cash_account_nama" => "cash_account__folders_nama",
                    ),
                ),
                "customerDetails__parent_cek" => array(
                    "0" => array(
                        "nilai_dijadikan_credit_note" => ".0",
                        "nilai_dijadikan_credit_note_pph" => ".0",
                    ),
                    "1" => array(
//                        "nilai_dijadikan_credit_note" => ".0",
//                        "nilai_dijadikan_credit_note_pph" => "nilai_dijadikan_credit_note-pph23",
                        "nilai_dijadikan_credit_note_pph" => "sisa_after_kas",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
//            "credit_amount" => "credit_note_dipakai",
            "credit_note_dipakai" => "credit_amount",
            "point_konsumen_nilai_dipakai" => "pointSetting__nilai*point_konsumen_qtt",
            "totalCredit" => "credit_note_dipakai+creditValue+uang_muka_dipakai",
            "sisa_after_um" => "sisa-uang_muka_dipakai",
            "sisa_after_return" => "sisa_after_um-credit_amount",
            "sisa_after_pointnilai" => "sisa_after_return-point_konsumen_nilai",
            "sisa_after_ppn_bendahara" => "sisa_after_pointnilai-ppn_nilai_dibayar",
            "sisa_after_biaya_merchant" => "sisa_after_ppn_bendahara-nilai_biaya",// baris netto
            "sisa_after_pph23" => "sisa_after_biaya_merchant-pph23",// setelah pph23
            "sisa_after_kas" => "sisa_after_pph23-nilai_entry",// setelah kas

            //tambahan ppn dibayar bendahara negara dan ppn juga dibayar bendahara negara
            "nilai_bayar" => "nilai_dijadikan_credit_note_pph+nilai_entry+totalCredit+nilai_biaya+(pph22_nilai+ppn_nilai_dibayar)+selisih_round+point_konsumen_nilai_dipakai+pph23-(deposit_konsumen+pendapatan_lain_lain)",

            // lebih bayar di switch by chepy 11-jan 2021
            "lebih_bayar" => "(nilai_dijadikan_credit_note_pph+nilai_entry+nilai_biaya+pph22_nilai+ppn_nilai_dibayar+totalCredit+point_konsumen_nilai_dipakai+pph23)-nilai_round",

            "amount" => "sisa",
            //---------------------
            "dpp_pengganti" => "(11/12)*dpp_final",
            "ppn_final" => "(12/100)*dpp_pengganti",
            "dpp_nppn_final" => "dpp_final+ppn_final",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
            "uangMuka__saldoUangMuka" => "uangMuka__saldoUangMukaround",//maxvalue biar bulat nilainya karena ada di UI shopingcart
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
        "populators" => array(// model ini defaultnya ke gerbang items
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
        "populatorsGate" => "items",// model ini defaultnya ke gerbang items

        "additionalBuilders" => array(//==per-item
//            "new_sisa" => "sisa-nilai_bayar",

        ),
        "additionalMainBuilders" => array(//==main
            "harus_bayar" => "nilai_round-totalCredit-nilai_biaya-point_konsumen_nilai_dipakai",
            "nilai_sisa" => "nilai_round",
            "cek_nilai" => "(selisih_round*-1)+sisa",
            "new_sisa" => "((selisih_round*-1)+sisa)-(nilai_dijadikan_credit_note+nilai_entry+totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai+pph23)",
            "new_sisa_before_entry" => "((selisih_round*-1)+sisa)-(totalCredit+pph22_nilai+ppn_nilai_dibayar+nilai_biaya+point_konsumen_nilai_dipakai)",
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
                            "nilai" => "nilai_bayar", // nilai pembayaran total
                            "jenis" => ".1010020010",//piutang dagang

                            "tipe_penjualan" => "tipe_penjualan",
                            "tipe_penjualan_coa" => ".1010020090",
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

//                "bank_rekening_id" => "cash_id",
//                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
                "bank_id" => "cash_account__folders",
                "bank_nama" => "cash_account__folders_nama",
                "bank_rekening_id" => "cash_account",
                "bank_rekening_nama" => "cash_account__label",
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
                "tseting" => "",
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
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "1010010010" => "nilai_cash",// kas
                            "1010020070" => "-nilai_dipakai_1010020010",// piutang pph23
//                            "1010020090" => "-nilai_dipakai_1010020090",// piutang dagang marketplace
//                            "6010" => "nilai_biaya",// biaya usaha
                            "7010110" => "selisih_round",// selisih pembulatan
//                            "1010040020" => "pph22_nilai",// pph22 dibayar dimuka
//                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
//                            "1010040030" => "pph23",// pph23 dibayar dimuka
//                            //----------
//                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
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
                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
                            "1010010010" => "nilai_cash",// kas
                            "1010020070" => "-nilai_dipakai_1010020010",// piutang pph23
//                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang reguler
//                            "1010020090" => "-nilai_dipakai_1010020090",// piutang dagang marketplace
//                            "6010" => "nilai_biaya",// biaya usaha
//                            "7010110" => "selisih_round",// selisih pembulatan
//                            "1010040020" => "pph22_nilai",// pph22 dibayar dimuka
//                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
//                            "1010040030" => "pph23",// pph23 dibayar dimuka
//                            //----------
//                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
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
                        "comName" => "RekeningPembantuCustomerLain",
                        "loop" => array(
                            "1010020070" => "-nilai_dipakai_1010020010",// ppn dibayar bendahara negara
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",//langsung di define karena tidak pakai connecting
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".1",
                            "extern2_nama" => "piutang pph23",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
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
                            "extern_id" => "cash_account_id",
                            "extern_nama" => "cash_account_nama",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // ====== =============
//
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "7010150" => "pendapatan_lain_lain",// pendapatan lain_lain
//                            "2010050" => "deposit_konsumen",// hutang ke konsumen
//                            "1010010010" => "deposit_konsumen+pendapatan_lain_lain",// kas
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
//                            "7010150" => "pendapatan_lain_lain",// pendapatan lain_lain
//                            "2010050" => "deposit_konsumen",// hutang ke konsumen
//                            "1010010010" => "deposit_konsumen+pendapatan_lain_lain",// kas
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
//                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // rekening pembantu piutang usaha reguler
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "1010020010" => "-nilai_dipakai_1010020010",// piutang dagang
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
//                    // rekening pembantu piutang usaha marketplace
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "1010020090" => "-nilai_dipakai_1010020090",// piutang usaha marketplace
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "marketplaceID",// marketplace
//                            "extern_nama" => "marketplaceName",// marketplace
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
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
//                    // rekening pembantu hutang ke konsumen, uang muka
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
////                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
//                            "extern_id" => ".2010050050",
//                            "extern_nama" => ".Uang Muka Konsumen",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening pembantu hutang ke konsumen, return penjualan
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
////                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
//                            "extern_id" => ".2010050040",
//                            "extern_nama" => ".Return Penjualan",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening pembantu hutang ke konsumen, point konsumen
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
////                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                            "2010050" => "-point_konsumen_nilai_dipakai",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
//                            "extern_id" => ".2010050030",
//                            "extern_nama" => ".Point",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening pembantu hutang ke konsumen, uang muka, konsumenID
//                    array(
//                        "comName" => "RekeningPembantuCustomerDetail",
//                        "loop" => array(
////                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => ".2010050050",
//                            "extern2_nama" => ".Uang Muka Konsumen",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening pembantu hutang ke konsumen, return penjualan, konsumenID
//                    array(
//                        "comName" => "RekeningPembantuCustomerDetail",
//                        "loop" => array(
////                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => ".2010050040",
//                            "extern2_nama" => ".Return Penjualan",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening pembantu hutang ke konsumen, point konsumen, konsumenID
//                    array(
//                        "comName" => "RekeningPembantuCustomerDetail",
//                        "loop" => array(
////                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                            "2010050" => "-point_konsumen_nilai_dipakai",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => ".2010050030",
//                            "extern2_nama" => ".Point",
//                            "qty" => "-point_konsumen_qtt",
//                            "jml" => "-point_konsumen_qtt",
//                            "harga" => "pointSetting__nilai",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
//                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
////                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                            "2010050" => "deposit_konsumen",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
//                            "extern_id" => ".2010050050",
//                            "extern_nama" => ".Uang Muka Konsumen",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
//                    array(
//                        "comName" => "RekeningPembantuCustomerDetail",
//                        "loop" => array(
////                            "2010050" => "-(credit_note_dipakai+uang_muka_dipakai+point_konsumen_nilai_dipakai)",// hutang ke konsumen
//                            "2010050" => "deposit_konsumen",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => ".2010050050",
//                            "extern2_nama" => ".Uang Muka Konsumen",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "1010040020" => "pph22_nilai",// pph22 dibayar dimuka
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
//                            "1010010010" => "nilai_cash",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_id",
//                            "extern_nama" => "cash_account_nama",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "deposit_konsumen+pendapatan_lain_lain",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_id",
//                            "extern_nama" => "cash_account_nama",
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
//                            "6010" => "nilai_biaya",// biaya usaha
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
//                    //pembantu pph23
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "1010040030" => "pph23",//hutang pph23
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "harga" => "pph23",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //tambahan jurnal langsung untuk pph 22 dibayar bendahara negara digeser kepusat
//
//                    //region cabang
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040020" => "-pph22_nilai",// pph22
//                            "1010040080" => "-ppn_nilai_dibayar",// ppn dibayar bendahara negara
//                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
//                            "1010040030" => "-pph23",// pph23 dibayar dimuka
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
//                            "1010040020" => "-pph22_nilai",// pph22
//                            "1010040080" => "-ppn_nilai_dibayar",// ppn dibayar bendahara negara
//                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
//                            "1010040030" => "-pph23",// pph23 dibayar dimuka
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
//                            "1010040080" => "-ppn_nilai_dibayar",// ppn dibayar bendahara negara
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",//langsung di define karena tidak pakai connecting
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
//                            "1010040020" => "-pph22_nilai",// pph22
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
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "2040010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// hutang ke pusat
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => ".-1",
//                            "cabang2_nama" => "PUSAT",
//                            "extern_id" => ".-1",
//                            "extern_nama" => "PUSAT",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //pembantu pph23
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "1010040030" => "-pph23",//hutang pph23
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "harga" => "pph23",
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
//                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
//                            "1010040020" => "pph22_nilai",// pph22
//                            "1010040030" => "pph23",// pph23 dibayar dimuka
//                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
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
//                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
//                            "1010040020" => "pph22_nilai",// pph22
//                            "1010040030" => "pph23",// pph23 dibayar dimuka
//                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
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
//                            "1010040080" => "ppn_nilai_dibayar",// ppn dibayar bendahara negara
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
//                            "1010040020" => "pph22_nilai",// pph22
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
//                            "1010060010" => "-(pph22_nilai+ppn_nilai_dibayar+pph23)",// piutang cabang
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //pembantu pph23
//                    array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "1010040030" => "pph23",//hutang pph23
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "harga" => "pph23",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //endregion
                ),
                "detail" => array(
//                     rekening pembantu piutang usaha marketplace detail
//                    array(
//                        "comName" => "RekeningPembantuCustomerDetailItem",
//                        "loop" => array(
//                            "1010020090" => "-nilai_bayar_marketplace",// piutang usaha marketplace
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "extern_id",// konsumen
//                            "extern_nama" => "extern_nama",// konsumen
//                            "extern2_id" => "extern3_id",// marketplace
//                            "extern2_nama" => "extern3_nama",// marketplace
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "74677" => array(
                "master" => array(
//                    // anti source deposit dari return penjualan berkurang
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
//                    // payment anti source return penjualan (cache dan mutasi), berkurang
//                    array(
//                        "comName" => "PaymentAntisourceCustomer",
//                        "loop" => array(
//                            "2010050" => "-credit_note_dipakai",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "gudang_id" => ".0",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "nilai" => "-credit_note_dipakai",
//                            "label" => ".piutang dagang",
//                            "extern_label2" => ".customer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
                    // locker kas
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//
//                    // payment source uang muka tanpa ppn
//                    array(
//                        "comName" => "PaymentUangMuka",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "uangMuka__transaksi_id",
//                            "jenis" => "uangMuka__jenis",
//                            "extern_id" => "uangMuka__extern_id",
//                            "extern_nama" => "uangMuka__extern_nama",
////                            "label" => ".uang muka",
//                            "label" => ".uang muka konsumen",
//                            "terbayar" => "uang_muka_dipakai",
//                            "extern_label2" => "uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // payment source uang muka (cache dan mutasi)
//                    array(
//                        "comName" => "PaymentUangMukaCustomer",
//                        "loop" => array(
//                            "2010050" => "-uang_muka_dipakai",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "gudang_id" => ".0",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "nilai" => "-uang_muka_dipakai",
//                            "label" => ".uang muka",
//                            "extern_label2" => ".customer",
//
////                            "state" => ".active",
////                            "jenis" => ".kas",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
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
//                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
//                            "npwp" => "customerDetails__npwp", // npwp
//                            "cabang_id" => ".-1",
//                            "tagihan" => "ppn_nilai_dibayar",
//                            "sisa" => "ppn_nilai_dibayar",
//                            "target_jenis" => ".0000",
//                            "jenis" => "transaksi_jenis",
//                            "reference_jenis" => "transaksi_jenis",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "srcValue" => "ppn_nilai_dibayar",
//                            "extern2_id" => "faktur_extern2_id",
//                            "extern2_nama" => "faktur_extern2_nama",
//                            "extern_date2" => "faktur_extern_date2",
//                            "extern_label2" => "faktur_extern_label2",
//                            "customers_id" => "pihakID",
//                            "customers_nama" => "pihakName",
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
//                            // "extern_date2" => "dtime",//tgl faktur ppn masukan
//                            "npwp" => "customerDetails__npwp", // npwp
//                            "cabang_id" => ".-1",
//                            "tagihan" => "pph22_nilai",
//                            "sisa" => "pph22_nilai",
//                            "target_jenis" => ".1110",
//                            "jenis" => "transaksi_jenis",
//                            "reference_jenis" => "transaksi_jenis",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "srcValue" => "pph22_nilai",
//                            "extern2_id" => "faktur_extern2_id",
//                            "extern2_nama" => "faktur_extern2_nama",
//                            "extern_date2" => "faktur_extern_date2",
//                            "extern_label2" => "faktur_extern_label2",
//                            "customers_id" => "pihakID",
//                            "customers_nama" => "pihakName",
//                            // "srcValue"=>".0",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // menambah kredit limit konsumen
//                    array(
//                        "comName" => "TransaksiKreditLimit",
//                        "loop" => array(
//                            "749" => "nilai_bayar",// hanya penjualan kredit....
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "produk_qty" => ".0",
//                            "produk_nilai" => "nilai_bayar",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
//                    // payment source uang muka tanpa ppn (lebih bayar)
//                    array(
//                        "comName" => "PaymentUangMuka",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "uangMuka__transaksi_id",
//                            "jenis" => "uangMuka__jenis",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".uang muka konsumen",
//                            "tambah" => "deposit_konsumen",
//                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),


                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".piutang pph23",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                            "tabel_id" => "tabel_id",
                            "extern3_id" => "marketplaceID",//id marketplace
                            "extern3_nama" => "marketplaceName",//nama marketplace
                            "extern4_id" => "tipe_penjualan",//id marketplace
                            "extern4_nama" => "tipe_penjualan_nama",//nama marketplace
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
        //------------------------------
        "preProcessorAuto" => array(
            "74677" => array(
                "master" => array(
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040020",//hutang biaya ke pusat
                            "nilai" => "nilai_entry+nilai_dijadikan_credit_note_pph",
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
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040010",//hutang ke pusat
                            "nilai" => "nilai_sisa_2040020",
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
        "componentsAuto" => array(
            "74677" => array(
                "master" => array(
                    //region bagian cabang
                    90 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    91 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    92 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",// diisi id bank
                            "extern_nama" => "cash_account_nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    93 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    94 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    100 => array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010010030" => "-nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion>

                    //region bagian pusat
                    95 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "nilai_entry",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "nilai_entry",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    97 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "cash_account_id",// diisi id bank
                            "extern_nama" => "cash_account_nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    99 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    101 => array(
                        "comName" => "RekeningPembantuCreditNote",
                        "loop" => array(
                            "1010010030" => "nilai_dijadikan_credit_note_pph",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "customerDetails__parent",
                            "extern_nama" => "customerDetails__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //rekening koran
//                    array(
//                        "comName" => "RekeningPembantuBank",
//                        "loop" => array(
//                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target__folders",//id bank
//                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "cash_account_target__folders",
//                            "extern2_nama" => "cash_account_target__folders_nama",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
//                        "loop" => array(
//                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",//id relasi rekening koran
//                            "extern_nama" => ".2020020010",//lbel relasi rekening koran // rekening koran
//                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
//                        "loop" => array(
//                            "2020020010" => "-nilai_koran_full",// rekening koran
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",//id rekening koran
//                            "extern_nama" => "cash_account_target__label",//label rekening koran
//                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "produk_nilai" => "nilai_koran_full",
//                            "produk_qty" => ".-1",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregkening koran


//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "nilai_cash",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",// diisi id bank
//                            "extern_nama" => "cash_account_target__label",// diisi nama bank
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //rekening koran
//                    array(
//                        "comName" => "RekeningPembantuBank",
//                        "loop" => array(
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target__folders",//id bank
//                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "cash_account_target__folders",
//                            "extern2_nama" => "cash_account_target__folders_nama",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
//                        "loop" => array(
//                            "2020020" => "nilai_koran",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",//id relasi rekening koran
//                            "extern_nama" => ".2020020010",//lbel relasi rekening koran // rekening koran
//                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
//                        "loop" => array(
//                            "2020020010" => "nilai_koran",//
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",//id rekening koran
//                            "extern_nama" => "cash_account_target__label",//label rekening koran
//                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
//                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "produk_nilai" => "nilai_koran_full",
//                            "produk_qty" => ".1",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregkening koran

                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessorAuto" => array(
            "74677" => array(
                "master" => array(
                    // locker kas cabang
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "-nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // locker kas reguler pusat
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_id",
                            "nama" => "cash_account_nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // locker kas rekening koran pusat
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_cash",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // menambah available rekening koran
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".plafon hutang bank",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_koran_full",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerStockPlafonBankMutasiMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "debet" => "nilai_koran_full",
//                            "produk_nilai" => "nilai_koran_full",
//                            "gudang_id" => ".0",
//                            "jenis" => "jenisTr",
//                            "transaksi_jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".plafon hutang bank",
//                            "produk_id" => "cash_account_target",
//                            "nama" => "cash_account_target__label",
//                            "nilai" => "-nilai_koran",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerStockPlafonBankMutasiMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "debet" => "-nilai_koran",
//                            "produk_nilai" => "-nilai_koran",
//                            "gudang_id" => ".0",
//                            "jenis" => "jenisTr",
//                            "transaksi_jenis" => "jenisTr",
//                            //                            "transaksi_id"        => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    array(
                        "comName" => "PaymentSrcMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang setoran",
                            "target_jenis" => ".759",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "nilai_entry",
                            "sisa" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
        ),
        //------------------------------

    ),
);


