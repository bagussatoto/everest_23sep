<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(
    //  config penyetoran
    "759" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|customerID",
            //            "stepCode|placeID|customerID",
        ),
        "formatNota" => "stepCode|placeID|olehID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
                "place2ID" => "centerDetails",
                "place2Name" => "centerDetails__label",
                "gudang2Name" => "gudang2ID__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "cashMethode" => array(
                    "rekening_koran" => array(
                        "rekening_koran_value" => "nilai_entry",
                        "kas_value" => "0",
                    ),
                    "reguler" => array(
                        "rekening_koran_value" => "0",
                        "kas_value" => "nilai_entry",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
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
            "bottom" => "tagihan",//harga_nett2
        ),
        "additionalItemSource" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "__harga_nett2",
            "hpp" => "__hpp",
            "ppn" => "__ppn",
            "laba_kotor" => "__laba_kotor",
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
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

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
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
                "new_sisa" => "new_sisa",
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
        "components" => array(),
        "postProcessor" => array(
            "759r" => array(
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
                            "nilai" => "-nilai_entry",
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
                            //                            "state" => ".payment",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "nilai_entry",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
                            "nilai" => ".1",
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
                            "label" => ".hutang setoran",
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
    "758" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|customerID",
            //            "stepCode|placeID|customerID",
        ),
        "formatNota" => "stepCode|placeID|olehID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            //            "totalCredit"=>"creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "nilai_bayar" => "nilai_entry",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
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

        "preProcessor" => array(
            "758" => array(
                "master" => array(
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".hutang biaya ke pusat",
                            "nilai" => "nilai_entry",
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
                            "cabang_id" => "place2ID",
                            //                            "gudang_id" => "gudangID",
                            //                            "state" => ".active",
                            "jenis" => ".hutang ke pusat",
                            //                            "produk_id" => "pihakID",
                            //                            "nama" => "pihakName",
                            //                            "nilai" => "ppn",
                            "nilai" => "nilai_sisa_hutang_biaya_ke_pusat",
                            //                            "transaksi_id" => "masterID",
                            //                            "oleh_id" => ".0",
                            //                            "paymentMethod" => "paymentMethod",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_sisa" => "nilai_sisa",
                                //                                "nilai_tambah" => "nilai_tambah",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // rekening koran
                    array(
                        "comName" => "RekeningKoran",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account_target",
                            "extern_nama" => "cash_account_target__label",
                            "nilai" => "nilai_entry",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".hutang bank",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_cash" => "nilai_cash",
                                "nilai_koran" => "nilai_koran",
                                "nilai_cash_full" => "nilai_cash_full",
                                "nilai_koran_full" => "nilai_koran_full",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
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
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

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
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
                "new_sisa" => "new_sisa",
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
            "758" => array(
                "master" => array(
                    //<editor-fold desc="bagian cabang">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "030202" => "-nilai_sisa_hutang_ke_pusat",// laba ditempatkan pusat
                            "020402" => "-nilai_dipakai_hutang_biaya_ke_pusat",// hutang biaya ke pusat
                            "020401" => "-nilai_dipakai_hutang_ke_pusat",// hutang ke pusat
                            "010101" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "030202" => "-nilai_sisa_hutang_ke_pusat",// laba ditempatkan pusat
                            "020402" => "-nilai_dipakai_hutang_biaya_ke_pusat",// hutang biaya ke pusat
                            "020401" => "-nilai_dipakai_hutang_ke_pusat",// hutang ke pusat
                            "010101" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "010101" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "cash_account_source",// diisi id bank
                            "extern_nama" => "cash_account_source__label",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "020401" => "-nilai_dipakai_hutang_ke_pusat",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "020402" => "-nilai_dipakai_hutang_biaya_ke_pusat",// hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //</editor-fold>

                    //<editor-fold desc="bagian pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "030202" => "nilai_sisa_hutang_ke_pusat",// laba ditempatkan pusat
                            "010804" => "-nilai_dipakai_hutang_biaya_ke_pusat",// piutang biaya cabang
                            "010801" => "-nilai_dipakai_hutang_ke_pusat",// piutang cabang
                            "010101" => "nilai_cash_full",// kas
                            "020202" => "-nilai_koran_full",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "030202" => "nilai_sisa_hutang_ke_pusat",// laba ditempatkan pusat
                            "010804" => "-nilai_dipakai_hutang_biaya_ke_pusat",// piutang biaya cabang
                            "010801" => "-nilai_dipakai_hutang_ke_pusat",// piutang cabang
                            "010101" => "nilai_cash_full",// kas
                            "020202" => "-nilai_koran_full",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "010101" => "nilai_cash_full",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target",// diisi id bank
                            "extern_nama" => "cash_account_target__label",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "010801" => "-nilai_dipakai_hutang_ke_pusat",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "010804" => "-nilai_dipakai_hutang_biaya_ke_pusat",// piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
                    array(
                        "comName" => "RekeningPembantuBank",
                        "loop" => array(
                            "020202" => "-nilai_koran_full",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target__folders",//id bank
                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "cash_account_target__folders",
                            "extern2_nama" => "cash_account_target__folders_nama",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
                        "loop" => array(
                            "020202" => "-nilai_koran_full",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",//id relasi rekening koran
                            "extern_nama" => ".02020200001",//lbel relasi rekening koran // rekening koran
                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
                        "loop" => array(
                            "02020200001" => "-nilai_koran_full",// rekening koran
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target",//id rekening koran
                            "extern_nama" => "cash_account_target__label",//label rekening koran
                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran

                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "produk_nilai" => "nilai_koran_full",
                            "produk_qty" => ".-1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregkening koran


                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010101" => "nilai_cash",// kas
                            "020202" => "nilai_koran",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "010101" => "nilai_cash",// kas
                            "020202" => "nilai_koran",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "010101" => "nilai_cash",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target",// diisi id bank
                            "extern_nama" => "cash_account_target__label",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
                    array(
                        "comName" => "RekeningPembantuBank",
                        "loop" => array(
                            "020202" => "nilai_koran",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target__folders",//id bank
                            "extern_nama" => "cash_account_target__folders_nama",//lbel bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "cash_account_target__folders",
                            "extern2_nama" => "cash_account_target__folders_nama",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
                        "loop" => array(
                            "020202" => "nilai_koran",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",//id relasi rekening koran
                            "extern_nama" => ".02020200001",//lbel relasi rekening koran // rekening koran
                            "extern2_id" => "cash_account_target__folders",//id folder rekening koran
                            "extern2_nama" => "cash_account_target__folders_nama",//label folder rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
                        "loop" => array(
                            "02020200001" => "nilai_koran",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target",//id rekening koran
                            "extern_nama" => "cash_account_target__label",//label rekening koran
                            "extern2_id" => "cash_account_target__folders",//folder rekening koran
                            "extern2_nama" => "cash_account_target__folders_nama",//folder rekening koran

                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "produk_nilai" => "nilai_koran_full",
                            "produk_qty" => ".1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregkening koran

                    //</editor-fold>
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "758" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "gudang_id" => ".0",
                            //                            "state" => ".payment",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "-nilai_entry",
                            //                            "transaksi_id" => ".0",
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
                            "cabang_id" => "cabang2ID",
                            "gudang_id" => ".0",
                            "state" => ".sold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "nilai_entry",
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
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_target",
                            "nama" => "cash_account_target__label",
                            "nilai" => "nilai_cash_full",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // locker kas rekening koran pusat
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
                            "nilai" => "nilai_cash",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // menambah available rekening koran
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account_target",
                            "nama" => "cash_account_target__label",
                            "nilai" => "nilai_koran_full",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target",
                            "extern_nama" => "cash_account_target__label",
                            "debet" => "nilai_koran_full",
                            "produk_nilai" => "nilai_koran_full",
                            "gudang_id" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account_target",
                            "nama" => "cash_account_target__label",
                            "nilai" => "-nilai_koran",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_target",
                            "extern_nama" => "cash_account_target__label",
                            "debet" => "-nilai_koran",
                            "produk_nilai" => "-nilai_koran",
                            "gudang_id" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_jenis" => "jenisTr",
                            //                            "transaksi_id"        => "jenisTr",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
    // config uang muka
    "4643" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "disc" => "(discPersen*harga)/100",
                //                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "464" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010101" => "-nett",// kas
                            "010402" => "nett",// uang muka dibayar
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
                            "010101" => "-nett",// kas
                            "010402" => "nett",// uang muka dibayar
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
                            "1010010010" => "-kas_nilai",// kas
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
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050010" => "um_noppn_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
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
                            "1010050010" => "dpp_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so__id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "referensi_so__nomer",
                            "extern3_id" => "option_nota",
                            "extern3_nama" => "option_nota__nama",
                            "extern4_nama" => "option_nota__jenis",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050030" => "um_ppn_nilai",// uang muka dibayar dengan ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so",
                            "extern2_nama" => "referensi_so__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "ppn_nilai",//ppn in belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
//            "464a" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
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
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
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
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "postProcessor" => array(
            "4643" => array(
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
                            "nilai" => "-kas_nilai", // nilai_entry
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
                            "state" => ".payment",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "kas_nilai", // nilai_entry
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
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
    // config uang muka
    "4644" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "disc" => "(discPersen*harga)/100",
                //                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4644" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010101" => "-nett",// kas
                            "010402" => "nett",// uang muka dibayar
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
                            "010101" => "-nett",// kas
                            "010402" => "nett",// uang muka dibayar
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
                            "1010010010" => "-kas_nilai",// kas
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
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050010" => "um_noppn_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
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
                            "1010050010" => "dpp_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so__id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "referensi_so__nomer",
                            "extern3_id" => "option_nota",
                            "extern3_nama" => "option_nota__nama",
                            "extern4_nama" => "option_nota__jenis",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050030" => "um_ppn_nilai",// uang muka dibayar dengan ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so",
                            "extern2_nama" => "referensi_so__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "ppn_nilai",//ppn in belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
//            "464a" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
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
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
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
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "postProcessor" => array(
            "4644" => array(
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
                            "nilai" => "-kas_nilai", // nilai_entry
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
                            "state" => ".payment",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "kas_nilai", // nilai_entry
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
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
    // config uang muka
    "4656" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "disc" => "(discPersen*harga)/100",
                //                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4656" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010101" => "-nett",// kas
                            "010402" => "nett",// uang muka dibayar
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
                            "010101" => "-nett",// kas
                            "010402" => "nett",// uang muka dibayar
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
                            "1010010010" => "-kas_nilai",// kas
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
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050010" => "um_noppn_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
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
                            "1010050010" => "dpp_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so__id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "referensi_so__nomer",
                            "extern3_id" => "option_nota",
                            "extern3_nama" => "option_nota__nama",
                            "extern4_nama" => "option_nota__jenis",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050030" => "um_ppn_nilai",// uang muka dibayar dengan ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so",
                            "extern2_nama" => "referensi_so__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "ppn_nilai",//ppn in belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
//            "464a" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
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
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
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
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040060" => "ppn_nilai",//ppn in sudah ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040050" => "-ppn_nilai",//ppn in belum ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "postProcessor" => array(
            "4656" => array(
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
                            "nilai" => "-kas_nilai", // nilai_entry
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
                            "state" => ".payment",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "kas_nilai", // nilai_entry
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
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),

    "4645" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "disc" => "(discPersen*harga)/100",
                //                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
                "nett_include_ppn" => "harga",
                "dpp" => "harga/(1+(ppnFactor/100))",
                "dpp_pengganti" => "(11/12)*dpp",
//                "ppn"=>"dpp*(ppnFactor/100)",
                "ppn_pengganti" => "dpp*(ppnFactor/100)",
            ),
            "master_dependent" => array(

//                "selectedType_konsumen" => array(
//                    "include_ppn" => array(
//                        "dpp_nilai" => "harga*(100/(100+ppnFactor))",
//                        "ppn" => "dpp_nilai*(ppnFactor/100)",
//                        "total_ui" => "dpp_nilai",
//                        "new_grand_ppn" => "ppn",
//                        "grand_ppn" => "ppn",
//                        "ppn_out_bulat" => "ppn",
//                        "nett1" => "dpp_nilai",
//                        "nett1_bulat" => "dpp_nilai",
//                        "grand_total_ui" => "dpp_nilai",
//                        "tagihan" => "harga",
//                        "dpp_ppn" => "dpp_nilai",
//                        //-----
//                        "kas_nilai" => "tagihan",
//                        "ppn_nilai" => "ppn",
//                        "um_ppn_nilai" => "dpp_nilai",
////                        "um_noppn_nilai" => ".0",
//                        "um_noppn_nilai" => "(uang_muka_tanpa_ppn_source_dipakai*-1)",
//                        "ppn_realisasi" => "ppn",
//                        "ppn_gate" => ".1",
//                    ),
//                    "exclude_ppn" => array(
//                        "dpp_nilai" => "harga",
//                        "ppn" => "0",
//                        "total_ui" => "harga",
//                        "nett1" => "harga",
//                        "dpp_ppn" => "0",
//                        "grand_ppn" => "0",
//                        "grand_total_ui" => "harga",
//                        //-----
//                        "kas_nilai" => "nett1",
//                        "ppn_nilai" => "ppn",
//                        "um_ppn_nilai" => ".0",
//                        "um_noppn_nilai" => "dpp_nilai",
//                        "ppn_realisasi" => "ppn",
//                        "ppn_gate" => ".0",
////                        "dpp_nilai_pengganti" => "0",
////                        "ppn_pengganti" => "0",
//                    ),
//                ),
//                "selectedType_uangmuka" => array(
//                    "uang_muka_produk" => array(
//                        "dpp_nilai_pengganti" => "dpp_nilai*ppn_gate",
//                        "dpp_pengganti" => "dpp_nilai*ppn_gate",
//                        "ppn_pengganti" => "(dpp_pengganti*ppnFactor/100)*ppn_gate",
//                        "ppn_nilai_pengganti" => "ppn*ppn_gate",
//                    ),
//                    "uang_muka_jasa" => array(
////                        "dpp_nilai_pengganti" => "(harga*((100+ppnFactor)/100))/10",
//                        "dpp_nilai_pengganti" => "dpp_pengganti*ppn_gate",
//                        "ppn_pengganti" => "(dpp_nilai_pengganti*(ppnFactor/100))*ppn_gate",
//                        "ppn_nilai_pengganti" => "(dpp_nilai_pengganti*(ppnFactor/100))*ppn_gate",
//                        //overwrite uang muka dan ppn untuk jasa
//                        "um_ppn_nilai" => "(harga-ppn_nilai_pengganti)*ppn_gate",
//                        "ppn_nilai" => "ppn_nilai_pengganti*ppn_gate",
//                        "ppn_realisasi" => "ppn_nilai",
////                        "um_noppn_nilai" => "(harga*ppn_gate)*-1",
//                    ),
//                ),
//                "referensiNota" => array(
//                    //titipan masuk payment uang_muka_source
//                    1 => array(
//                        "nilai_payment_source" => "0",
//                        "nilai_uang_muka_source" => "dpp_nilai",
//                        "selectedType_konsumen" => ".exclude_ppn",
//                    ),
//                    //uangmuka ppn masuk payment_source
//                    2 => array(
//                        "nilai_payment_source" => "dpp_nilai",
//                        "nilai_uang_muka_source" => "0",
////                        "selectedType_konsumen" => ".include_ppn",
//
//                    ),
//                ),
            ),
        ),
        "valueBuilders" => array(

            "grand_total" => "harga",
            "tagihan" => "harga",
            "hutang_ke_konsumen" => "dpp_nilai",
            "kas_nilai" => "harga-add_source_uang_muka_dipakai-add_source_creditnote_dipakai",
            "ppn_belum_faktur" => "ppn_pengganti*ppn_pending",
            "ppn_sudah_faktur" => "ppn_pengganti-ppn_belum_faktur",
//            "add_source_uang_muka_dipakai" => "uang_muka_tanpa_ppn_source_dipakai",


        ),
        "additionalMainBuilders" => array(),
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4645" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "-kas_nilai",// kas
                            "1010010030" => "-add_source_creditnote_dipakai",// kreditnote
                            "1010050040" => "-add_source_uang_muka_dipakai",// uang muka dibayar tanpa ppn, non relasi po
                            "1010050030" => "dpp",// uang muka dibayar dengan ppn
                            "1010040060" => "ppn_sudah_faktur",//ppn in ada faktur
                            "1010040050" => "ppn_belum_faktur",//ppn in belum faktur
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
                            "1010010010" => "-kas_nilai",// kas
                            "1010010030" => "-add_source_creditnote_dipakai",// kreditnote
                            "1010050040" => "-add_source_uang_muka_dipakai",// uang muka dibayar tanpa ppn, non relasi po
                            "1010050030" => "dpp",// uang muka dibayar dengan ppn
                            "1010040060" => "ppn_sudah_faktur",//ppn in ada faktur
                            "1010040050" => "ppn_belum_faktur",//ppn in belum faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //mengurangi saldo titipan
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050040" => "-add_source_uang_muka_dipakai",// uang muka tanpa relasi po
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
                    //pembantu uang muka yang tidak terelasi dengan PO
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050040" => "-add_source_uang_muka_dipakai",// uang muka tanpa relasi po
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".0",
                            "extern2_nama" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu um ppn

                    //rekeningpembantu belum ppn ada faktur
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "ppn_belum_faktur",//ppn in belum ada faktur
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
                    //pembantu creditnote klaim ke supplier
                    array(
                        "comName" => "RekeningPembantuCreditNote",// RekeningPembantuSupplier
                        "loop" => array(
                            "1010010030" => "-add_source_creditnote_dipakai",// credit note
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
                    //rekening pembantu kas
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-kas_nilai",// kas
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
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuUangMuka",
                        "loop" => array(
                            "1010050030" => "dpp",// uang muka dibayar dengan ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "id",
                            "extern2_nama" => "nama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuUangMukaReference",
                        "loop" => array(
                            "1010050030" => "dpp",// uang muka ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "nama",
//                            "extern3_id" => "option_nota",
//                            "extern3_nama" => "option_nota__nama",
//                            "extern4_nama" => "option_nota__jenis",
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
            "4645" => array(
                "master" => array(
                    //mengurangi saldo uang_muka_source  titipan non relasi
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "uangMukaNonRelasi__transaksi_id",
                            "jenis" => "uangMukaNonRelasi__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".0",
                            "extern2_nama" => ".0",
                            "label" => ".uang muka nonrelasi",
                            "terbayar" => "add_source_uang_muka_dipakai",
                            "extern_label2" => "uangMukaNonRelasi__extern_label2",//ini update untuk pembeda vendor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //terbit untuk konsolidasi faktur ppn masukan vs ppn keluaran
                    array(
                        "comName" => "PaymentSourceFaktur",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".ppn realisasi",
                            "target_jenis" => ".0000",
                            "jenis" => "jenisTr",
                            "reference_jenis" => "jenisTr",
                            "tagihan" => "ppn",
                            "sisa" => "ppn",
                            "extern_label2" => "eFaktur",
                            "ppn" => "ppn",
                            "ppn_sisa" => "ppn",
                            "ppn_sudah_faktur" => "ppn",
                            "extern_nilai2" => "dpp",
                            "extern_date2" => "dateFaktur",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    /**
                     * insert ke paymentsource um_ppn yang seharusnya jalan dari heTransaksimisc(dipasang disini karena terbit per PO, jika di misc hanya bisa satu.
                     */
                    array(
                        "comName" => "PaymentSrcUmItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "id" => "pihakID",
                            "nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "id",
                            "extern2_nama" => "nama",
                            "extern_date2" => "reference_date",
                            "extLabel" => ".vendor",
                            "sisa" => "dpp",
                            "dpp_ppn" => "dpp",
                            "ppn" => "ppn",
                            "target_jenis" => ".0464",
                            "label" => ".uang muka supplier",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    /**
                     * update transaksi
                     * isi nomer faktur
                     */
                    array(
                        "comName" => "TransaksiGlobalUpdate",
                        "loop" => array(
                            "efaktur_dtime" => "dateFaktur",
                            "efaktur" => "eFaktur",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "id" => "id",
                            "nama" => "nama",

                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
    "9994" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
                "selectedType_konsumen" => ".exclude_ppn",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "disc" => "(discPersen*harga)/100",
                //                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
            "master_dependent" => array(
//                "selectedType_konsumen" => array(
//                    "include_ppn" => array(
//                        "dpp_nilai" => "harga*(100/(100+ppnFactor))",
//                        "ppn" => "dpp_nilai*(ppnFactor/100)",
//                        "total_ui" => "dpp_nilai",
//                        "new_grand_ppn" => "ppn",
//                        "grand_ppn" => "ppn",
//                        "ppn_out_bulat" => "ppn",
//                        "nett1" => "dpp_nilai",
//                        "nett1_bulat" => "dpp_nilai",
//                        "grand_total_ui" => "dpp_nilai",
//                        "tagihan" => "harga",
//                        "dpp_ppn" => "dpp_nilai",
//                        //-----
//                        "kas_nilai" => "tagihan",
//                        "ppn_nilai" => "ppn",
//                        "um_ppn_nilai" => "dpp_nilai",
////                        "um_noppn_nilai" => ".0",
//                        "um_noppn_nilai" => "(uang_muka_tanpa_ppn_source_dipakai*-1)",
//                        "ppn_realisasi" => "ppn",
//                        "ppn_gate" => ".1",
//                    ),
//                    "exclude_ppn" => array(
//                        "dpp_nilai" => "harga",
//                        "ppn" => "0",
//                        "total_ui" => "harga",
//                        "nett1" => "harga",
//                        "dpp_ppn" => "0",
//                        "grand_ppn" => "0",
//                        "grand_total_ui" => "harga",
//                        //-----
//                        "kas_nilai" => "nett1",
//                        "ppn_nilai" => "ppn",
//                        "um_ppn_nilai" => ".0",
//                        "um_noppn_nilai" => "dpp_nilai",
//                        "ppn_realisasi" => "ppn",
//                        "ppn_gate" => ".0",
////                        "dpp_nilai_pengganti" => "0",
////                        "ppn_pengganti" => "0",
//                    ),
//                ),
//                "selectedType_uangmuka" => array(
//                    "uang_muka_produk" => array(
//                        "dpp_nilai" => "harga",
//                        "ppn" => "0",
//                        "total_ui" => "harga",
//                        "nett1" => "harga",
//                        "dpp_ppn" => "0",
//                        "grand_ppn" => "0",
//                        "grand_total_ui" => "harga",
//                        //-----
//                        "kas_nilai" => "nett1",
//                        "ppn_nilai" => "ppn",
//                        "um_ppn_nilai" => ".0",
//                        "um_noppn_nilai" => "dpp_nilai",
//                        "ppn_realisasi" => "ppn",
//                        "ppn_gate" => ".0",
//                        "dpp_nilai_pengganti" => "dpp_nilai*ppn_gate",
//                        "dpp_pengganti" => "dpp_nilai*ppn_gate",
//                        "ppn_pengganti" => "(dpp_pengganti*ppnFactor/100)*ppn_gate",
//                        "ppn_nilai_pengganti" => "ppn*ppn_gate",
//                    ),
//                    "uang_muka_jasa" => array(
//                        "dpp_nilai" => "harga",
//                        "ppn" => "0",
//                        "total_ui" => "harga",
//                        "nett1" => "harga",
//                        "dpp_ppn" => "0",
//                        "grand_ppn" => "0",
//                        "grand_total_ui" => "harga",
//                        //-----
//                        "kas_nilai" => "nett1",
//                        "ppn_nilai" => "ppn",
//                        "um_ppn_nilai" => ".0",
//                        "um_noppn_nilai" => "dpp_nilai",
//                        "ppn_realisasi" => "ppn",
//                        "ppn_gate" => ".0",
//                        "dpp_nilai_pengganti" => "dpp_nilai*ppn_gate",
//                        "dpp_pengganti" => "dpp_nilai*ppn_gate",
//                        "ppn_pengganti" => "(dpp_pengganti*ppnFactor/100)*ppn_gate",
//                        "ppn_nilai_pengganti" => "ppn*ppn_gate",
//                    ),
//                ),
//                "referensiNota" => array(
//                    //titipan masuk payment uang_muka_source
//                    1 => array(
//                        "nilai_payment_source" => "0",
//                        "nilai_uang_muka_source" => "dpp_nilai",
//                        "selectedType_konsumen" => ".exclude_ppn",
//                    ),
//                    //uangmuka ppn masuk payment_source
//                    2 => array(
//                        "nilai_payment_source" => "dpp_nilai",
//                        "nilai_uang_muka_source" => "0",
////                      "selectedType_konsumen" => ".include_ppn",
//                    ),
//                ),
                //jika ada referensi nota
                "optionReference" => array(
                    1 => array(
                        "dpp_nilai" => "harga",
                        "ppn" => "0",
                        "total_ui" => "harga",
                        "nett1" => "harga",
                        "dpp_ppn" => "0",
                        "grand_ppn" => "0",
                        "grand_total_ui" => "harga",
                        //-----
                        "kas_nilai" => "nett1",
                        "ppn_nilai" => ".0",
                        "um_ppn_nilai" => ".0",
                        "um_noppn_nilai" => ".0",
                        "um_noppn_nonrelasi_nilai" => "dpp_nilai",
                        "ppn_realisasi" => ".0",
                        "ppn_gate" => ".0",
//                        "dpp_nilai_pengganti" => "0",
//                        "ppn_pengganti" => "0",
                        "nilai_payment_source" => "0",
                        "nilai_uang_muka_source" => "0",
                        "nilai_uang_muka__nonrelasi_source" => "dpp_nilai",
//                        "selectedType_konsumen" => ".exclude_ppn",
                    ),
                    2 => array(
                        "dpp_nilai" => "harga",
                        "ppn" => "0",
                        "total_ui" => "harga",
                        "nett1" => "harga",
                        "dpp_ppn" => "0",
                        "grand_ppn" => "0",
                        "grand_total_ui" => "harga",
                        //-----
                        "kas_nilai" => "nett1",
                        "ppn_nilai" => "ppn",
                        "um_ppn_nilai" => ".0",
                        "um_noppn_nilai" => "dpp_nilai",
                        "um_noppn_nonrelasi_nilai" => ".0",
                        "ppn_realisasi" => "ppn",
                        "ppn_gate" => ".0",
//                        "dpp_nilai_pengganti" => "0",
//                        "ppn_pengganti" => "0",
                        "nilai_payment_source" => ".0",
                        "nilai_uang_muka_source" => "dpp_nilai",
                        "nilai_uang_muka__nonrelasi_source" => ".0",
//                        "selectedType_konsumen" => ".exclude_ppn",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
//            "grand_total" => "nett",
//            "tagihan" => "grand_total",
            "grand_total" => "dpp_nilai+ppn",
            "tagihan" => "grand_total",
            "hutang_ke_konsumen" => "dpp_nilai",
            //"kas_nilai" => "grand_total-uang_muka_tanpa_ppn_source_dipakai",
            "kas_nilai" => "harga-uang_muka_tanpa_ppn_source_dipakai",
            "add_source_uang_muka_dipakai" => "uang_muka_tanpa_ppn_source_dipakai",
//            "ppn_realisasi" => "",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "9994" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3010020" => "kas_nilai",// modal
                            "1010050010" => "um_noppn_nilai",// uang muka dibayar tanpa ppn
                            "1010050030" => "um_ppn_nilai",// uang muka dibayar dengan ppn
                            "1010050040" => "um_noppn_nonrelasi_nilai",// uang muka dibayar tanpa ppn non relasi
                            "1010040050" => "ppn_nilai",//ppn in belum ada faktur
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
                            "3010020" => "kas_nilai",// modal
                            "1010050010" => "um_noppn_nilai",// uang muka dibayar tanpa ppn
                            "1010050030" => "um_ppn_nilai",// uang muka dibayar dengan ppn
                            "1010050040" => "um_noppn_nonrelasi_nilai",// uang muka dibayar tanpa ppn non relasi
                            "1010040050" => "ppn_nilai",//ppn in belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "-kas_nilai",// kas
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

                    //pembantu uang muka tanpa ppn dengan relasi PO supplier
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050010" => "um_noppn_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".0",
                            "extern2_nama" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu uang muka tanpa ppn tanpa  relasi PO supplier
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050040" => "um_noppn_nonrelasi_nilai",// uang muka dibayar tanpa ppn non relasi PO
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".0",
                            "extern2_nama" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening pembantu uang muka tanpa ppn persupplier relasi PO
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050010" => "um_noppn_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so__id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "referensi_so__nomer",
                            "extern3_id" => "option_nota",
                            "extern3_nama" => "option_nota__nama",
                            "extern4_nama" => "option_nota__jenis",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening pembantu uang muka tanpa ppn persupplier tanpa relasi PO
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050040" => "um_noppn_nonrelasi_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so__id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "referensi_so__nomer",
                            "extern3_id" => "option_nota",
                            "extern3_nama" => "option_nota__nama",
                            "extern4_nama" => "option_nota__jenis",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050030" => "um_ppn_nilai",// uang muka dibayar dengan ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensi_so",
                            "extern2_nama" => "referensi_so__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "ppn_nilai",//ppn in belum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "pihakName",
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
            "9994" => array(
                "master" => array(

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
//                            "nilai" => "-kas_nilai", // nilai_entry
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
//                            "state" => ".payment",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account",
//                            "nama" => "cash_account__label",
//                            "nilai" => "kas_nilai", // nilai_entry
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),


                ),
                "detail" => array(),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
    // config uang muka
    "464" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "disc" => "(discPersen*harga)/100",
                //                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "464" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010101" => "-nett",// kas
                            "010402" => "nett",// uang muka dibayar
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
                            "010101" => "-nett",// kas
                            "010402" => "nett",// uang muka dibayar
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
                            "010101" => "-nett",// kas
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
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "010402" => "nett",// uang muka dibayar
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
                    //                    array(
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "piutang uang muka" => "nilai_tambah_ppn_in",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "id",
                    //                            "extern_nama" => "pihakName",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                ),
                "detail" => array(
                    //                    array(
                    //                        "comName" => "RekeningPembantuUangMuka",
                    //                        "loop" => array(
                    //                            "uang muka dibayar" => "nett",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "id",
                    //                            "extern_nama" => "name",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "464" => array(
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
                            "nilai" => "-nett", // nilai_entry
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
                            "state" => ".payment",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "nett", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //ini off juga balik ke transksi misc uang muka
                    //                    array(
                    //                        "comName" => "UangMukaSourceDetail",//untuk nilis ke payment source karena gerbang dari detail, di trnasksi misc di off kan ya bro
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "cabangName",
                    //                            "extern_id" => "pihakID",
                    //                            "extern_nama" => "pihakName",
                    //                            "label" => ".uang muka",
                    //                            "jenis" => "jenisTr",
                    //                            "target_jenis" => ".1464",
                    //                            "transaksi_id" => "transaksi_id",
                    //                            "terbayar" => "0",
                    //                            "tagihan" => "harga",
                    //                            "sisa" => "harga",
                    //                            "nomer" => "nomer",
                    //                            "reference_jenis" => "jenisTr",
                    //                            "extern_nilai_2" => "harga",
                    //                            "oleh_id" => "olehID",
                    //                            "oleh_nama" => "olehName",
                    //                            "extern2_id" => "id",
                    //                            "extern2_nama" => "nama"
                    //
                    //                        ),
                    //                        "reversable" => true,
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                ),
            ),
        ),
    ),
    //ganti relasi PO uang muka
    "465" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "disc" => "(discPersen*harga)/100",
                //                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
            "master_dependent" => array(
                "selectedType_konsumen" => array(
                    "include_ppn" => array(
                        "dpp_nilai" => "harga*(100/(100+ppnFactor))",
                        "ppn" => "dpp_nilai*(ppnFactor/100)",
                        "total_ui" => "dpp_nilai",
                        "new_grand_ppn" => "ppn",
                        "grand_ppn" => "ppn",
                        "ppn_out_bulat" => "ppn",
                        "nett1" => "dpp_nilai",
                        "nett1_bulat" => "dpp_nilai",
                        "grand_total_ui" => "dpp_nilai",
                        "tagihan" => "harga",
                        "dpp_ppn" => "dpp_nilai",
                        //-----
                        "kas_nilai" => "tagihan",
                        "ppn_nilai" => "ppn",
                        "um_ppn_nilai" => "dpp_nilai",
//                        "um_noppn_nilai" => ".0",
                        "um_noppn_nilai" => "(uang_muka_tanpa_ppn_source_dipakai*-1)",
                        "ppn_realisasi" => "ppn",
                        "ppn_gate" => ".1",
                    ),
                    "exclude_ppn" => array(
                        "dpp_nilai" => "harga",
                        "ppn" => "0",
                        "total_ui" => "harga",
                        "nett1" => "harga",
                        "dpp_ppn" => "0",
                        "grand_ppn" => "0",
                        "grand_total_ui" => "harga",
                        //-----
                        "kas_nilai" => "nett1",
                        "ppn_nilai" => "ppn",
                        "um_ppn_nilai" => ".0",
                        "um_noppn_nilai" => "dpp_nilai",
                        "ppn_realisasi" => "ppn",
                        "ppn_gate" => ".0",
//                        "dpp_nilai_pengganti" => "0",
//                        "ppn_pengganti" => "0",
                    ),
                ),
                "selectedType_uangmuka" => array(
                    "uang_muka_produk" => array(
                        "dpp_nilai_pengganti" => "dpp_nilai*ppn_gate",
                        "dpp_pengganti" => "dpp_nilai*ppn_gate",
                        "ppn_pengganti" => "(dpp_pengganti*ppnFactor/100)*ppn_gate",
                        "ppn_nilai_pengganti" => "ppn*ppn_gate",
                    ),
                    "uang_muka_jasa" => array(
//                        "dpp_nilai_pengganti" => "(harga*((100+ppnFactor)/100))/10",
                        "dpp_nilai_pengganti" => "dpp_pengganti*ppn_gate",
                        "ppn_pengganti" => "(dpp_nilai_pengganti*(ppnFactor/100))*ppn_gate",
                        "ppn_nilai_pengganti" => "(dpp_nilai_pengganti*(ppnFactor/100))*ppn_gate",
                        //overwrite uang muka dan ppn untuk jasa
                        "um_ppn_nilai" => "(harga-ppn_nilai_pengganti)*ppn_gate",
                        "ppn_nilai" => "ppn_nilai_pengganti*ppn_gate",
                        "ppn_realisasi" => "ppn_nilai",
//                        "um_noppn_nilai" => "(harga*ppn_gate)*-1",
                    ),
                ),
                "referensiNota" => array(
                    //titipan masuk payment uang_muka_source
                    1 => array(
                        "nilai_payment_source" => "0",
                        "nilai_uang_muka_source" => "dpp_nilai",
                        "selectedType_konsumen" => ".exclude_ppn",
                    ),
                    //uangmuka ppn masuk payment_source
                    2 => array(
                        "nilai_payment_source" => "dpp_nilai",
                        "nilai_uang_muka_source" => "0",
//                        "selectedType_konsumen" => ".include_ppn",

                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
//            "grand_total" => "nett",
//            "tagihan" => "grand_total",
            "grand_total" => "dpp_nilai+ppn",
            "tagihan" => "grand_total",
            "hutang_ke_konsumen" => "dpp_nilai",
            //"kas_nilai" => "grand_total-uang_muka_tanpa_ppn_source_dipakai",
            "kas_nilai" => "harga",
            "add_source_uang_muka_dipakai" => "uang_muka_tanpa_ppn_source_dipakai",
//            "ppn_realisasi" => "",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "465" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050030" => "harga",// uang muka ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referensiSo",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "referensiSo__nomer",
//                            "extern3_id" => "option_nota",
//                            "extern3_nama" => "option_nota__nama",
//                            "extern4_nama" => "option_nota__jenis",
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
            "465" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuUangMukaDetailReference",
                        "loop" => array(
                            "1010050030" => "-harga",// uang muka ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "extern2_id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "extern2_nama",
//                            "extern3_id" => "option_nota",
//                            "extern3_nama" => "option_nota__nama",
//                            "extern4_nama" => "option_nota__jenis",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PaymentSourceItem2",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "extern2_id",
                            "extern2_nama" => "extern2_nama",
                            "label" => ".uang muka supplier",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "harga",
                            "sisa" => "new_sisa",
//                            "tabel_id" => "tabel_id",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
    //  uang muka valas
    "4466" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
                //                "disc" => "(discPersen*harga)/100",
                //                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn" => "harga_disc+ppn",
                "harga" => ".1",
                //                "extern_nilai2" => "pihak2Exchange",
                "subtotal" => "sub_harga",

                //                "valas_nilai_dipakai" => "nilai_bayar_valas*harga",
                //                "valas_sisa_value" => "valas_new_sisa*harga",
                //                "valas_sisa" => "valas_sisa_value/jml",
                //                "valas_dipakai" => "valas_nilai_dipakai/jml",
            ),
            "master_dependent" => array(
                "pihak3ID" => array(
                    "cash" => array(
                        "ppv" => "biaya_transfer+biaya_lain_lain_novalas",
                        "biaya_lain_total" => "biaya_lain_lain_novalas",
                        "kurs_actual" => "valas_kurang_nilai/total_amount",
                        "total_bayar" => "valas_kurang_nilai+biaya_transfer+biaya_lain_lain_novalas",
                    ),
                    "valas" => array(
                        "ppv" => "biaya_transfer",
                        "biaya_lain_total" => ".0",
                    ),
                ),
                "cashMethode" => array(
                    "reguler" => array(
                        "kas_add" => "biaya_lain_lain_novalas+biaya_transfer",
                        "rekening_koran_add" => ".0",
                    ),
                    "rekening koran" => array(
                        "kas_add" => ".0",
                        "rekening_koran_add" => "biaya_lain_lain_novalas+biaya_transfer",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total",
            "total_amount" => "harga-valas_nilai_dipakai",
            //----------------------------------------------
            "valas_amount" => "harga",
            "valas_kurang" => "valas_amount-valas_nilai_bayar",
            //"valas_kurang_nilai" => "valas_kurang*kurs_actual",
            "valas_uang_muka_nilai" => "kas_value+valas_hpp",
        ),

        "populators" => array(
            //            "nilai_bayar" => array(
            //                "mainSrc" => array(
            //                    "key" => "nilai_bayar",
            //                ),
            //                "itemTarget" => array(
            //                    "key" => "nilai_bayar",
            //                    "maxAmountSrc" => "sisa",
            //                ),
            //            ),
            "valas_nilai_bayar" => array(
                "mainSrc" => array(
                    "key" => "valas_nilai_bayar",
                ),
                "itemTarget" => array(
                    "key" => "valas_nilai_bayar",
                    "maxAmountSrc" => "jml",
                ),
            ),

            "valas_kurang" => array(
                "mainSrc" => array(
                    "key" => "valas_kurang",
                ),
                "itemTarget" => array(
                    "key" => "valas_kurang",
                    "maxAmountSrc" => "valas_new_sisa",
                ),
            ),
        ),
        "additionalBuilders" => array(
            "valas_new_sisa" => "jml-nilai_bayar_valas",
        ),

        "preProcessor" => array(
            "4466r" => array(
                "master" => array(
                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "valas_kurang_nilai",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
            ),
            "4466" => array(
                "master" => array(
                    // preprocc fifo stok valas
                    array(
                        "comName" => "FifoValasAverageMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valas_account",
                            "extern_nama" => "valas_account__label",
                            "produk_qty" => "valas_nilai_bayar",
                            "gudang_id" => ".0",
                            "cash_methode" => ".valas",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "FifoValasMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valas_account",
                            "extern_nama" => "valas_account__label",
                            "produk_qty" => "valas_nilai_bayar",
                            "gudang_id" => ".0",
                            "cash_methode" => ".valas",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "jml" => "qty",
                                "qty" => "qty",
                                "valas_harga" => "hpp",
                                "valas_hpp" => "hpp",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "switchResultParams" => true,
                    ),

                    // inject selisih kurs
                    //                    array(
                    //                        "comName" => "SelisihKurs",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "uang_muka_stock_valas" => "valas_harga", // fifo valas
                    ////                            "new_exchange" => "valas_harga", // fifo valas
                    //                            "jenisTr" => "jenisTr",
                    //                            "cashMethodeOption" => ".valas",
                    //                            "additional" => ".0",
                    //                            "additional_value" => ".0",
                    //                            "nilai_entry" => "valas_sisa",
                    //                            "jenis" => ".uang muka",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "valas_kurang_nilai",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4466" => array(
                "master" => array(
                    //------------------------------------------------
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "uang muka valas" => "valas_uang_muka_nilai",
                            "valas" => "-valas_harga",
                            "{add_jenis}" => "additional_value_total",
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
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "uang muka valas" => "valas_uang_muka_nilai",
                            "valas" => "-valas_harga",
                            "{add_jenis}" => "additional_value_total",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // kas utama
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "-kas_value",
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

                    //rekening koran utama
                    array(
                        "comName" => "RekeningPembantuBank",
                        "loop" => array(
                            "hutang bank" => "rekening_koran_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account__folders",//id bank
                            "extern_nama" => "cash_account__folders_nama",//lbel bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "cash_account__folders",
                            "extern2_nama" => "cash_account__folders_nama",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
                        "loop" => array(
                            "hutang bank" => "rekening_koran_value",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",//id relasi rekening koran
                            "extern2_id" => "cash_account__folders",//id folder rekening koran
                            "extern2_nama" => "cash_account__folders_nama",//label folder rekening koran
                            "extern_nama" => ".rekening koran",//lbel relasi rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
                        "loop" => array(
                            "rekening koran" => "rekening_koran_value",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",//id rekening koran
                            "extern_nama" => "cash_account__nama",//label rekening koran
                            "extern2_id" => "cash_account__folders",//folder rekening koran
                            "extern2_nama" => "cash_account__folders_nama",//folder rekening koran

                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "produk_nilai" => "rekening_koran_value",
                            "produk_qty" => ".1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregkening koran

                    // uang muka valas by vendor, dari stok + beli langsung
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "uang muka valas" => "valas_uang_muka_nilai",
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
                    // uang muka valas by vendor, by valas, dari beli langsung
                    array(
                        "comName" => "RekeningPembantuUangMukaExternMain",
                        "loop" => array(
                            "uang muka valas" => "kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "pihak2ID",
                            "extern2_nama" => "pihak2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "qty" => "valas_kurang",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //------------------------------------------------
                    //------------------------------------------------
                    //-tambahan jurnal biaya transfer dan biaya lain-lain-----------------------------------------------
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "beban lain lain" => "biaya_lain_lain_novalas",
                            "biaya transfer" => "biaya_transfer",
                            "kas" => "-kas_add",
                            "hutang bank" => "rekening_koran_add",
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
                            "beban lain lain" => "biaya_lain_lain_novalas",
                            "biaya transfer" => "biaya_transfer",
                            "kas" => "-kas_add",
                            "hutang bank" => "rekening_koran_add",
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
                            "kas" => "-kas_add",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran utama
                    array(
                        "comName" => "RekeningPembantuBank",
                        "loop" => array(
                            "hutang bank" => "rekening_koran_add",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account__folders",//id bank
                            "extern_nama" => "cash_account__folders_nama",//lbel bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "cash_account__folders",
                            "extern2_nama" => "cash_account__folders_nama",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
                        "loop" => array(
                            "hutang bank" => "rekening_koran_add",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",//id relasi rekening koran
                            "extern2_id" => "cash_account__folders",//id folder rekening koran
                            "extern2_nama" => "cash_account__folders_nama",//label folder rekening koran
                            "extern_nama" => ".rekening koran",//lbel relasi rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuRekeningKoranMain",//rekening pembantu level 3
                        "loop" => array(
                            "rekening koran" => "rekening_koran_add",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",//id rekening koran
                            "extern_nama" => "cash_account__nama",//label rekening koran
                            "extern2_id" => "cash_account__folders",//folder rekening koran
                            "extern2_nama" => "cash_account__folders_nama",//folder rekening koran

                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "produk_nilai" => "rekening_koran_add",
                            "produk_qty" => ".1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregkening koran

                    //------------------------------------------------
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "hutang lain ppv" => "-ppv",
                            "laba lain lain" => "-ppv",
                            "biaya transfer" => "-biaya_transfer",
                            "beban lain lain" => "-biaya_lain_total",
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
//                            "hutang lain ppv" => "-ppv",
                            "laba lain lain" => "-ppv",
                            "biaya transfer" => "-biaya_transfer",
                            "beban lain lain" => "-biaya_lain_total",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "-ppv",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".3",// laba rugi lain-lain ppv
                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //------------------------------------------------
                ),
                "detail" => array(
                    // rekening uang muka valas by vendor, by valas, dari stok valas
                    array(
                        "comName" => "RekeningPembantuUangMukaExternItem",
                        "loop" => array(
                            "uang muka valas" => "sub_valas_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "pihak2ID",
                            "extern2_nama" => "pihak2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "qty" => "qty",
                            "harga" => "valas_hpp",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    // stok valas pusat berkurang
                    array(
                        "comName" => "RekeningPembantuValas",
                        "loop" => array(
                            "valas" => "-sub_valas_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "jenis" => "jenisTr",
                            "qty" => "-jml",
                            "produk_nilai" => "valas_harga",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "4466r" => array(
                "master" => array(
                    //                    // ----- kas
                    //                    array(
                    //                        "comName"        => "LockerValue",
                    //                        "loop"           => array(),
                    //                        "static"         => array(
                    //                            "cabang_id"    => "placeID",
                    //                            "gudang_id"    => ".0",
                    //                            "state"        => ".active",
                    //                            "jenis"        => ".kas",
                    //                            "produk_id"    => "cash_account",
                    //                            "nama"         => "cash_account__label",
                    //                            "nilai"        => "-(kas_value+kas_add)", // nilai_entry
                    //                            "transaksi_id" => ".0",
                    //                            "oleh_id"      => ".0",
                    //                        ),
                    //                        "srcGateName"    => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable"     => true,
                    //                    ),
                    //                    array(
                    //                        "comName"        => "LockerValue",
                    //                        "loop"           => array(),
                    //                        "static"         => array(
                    //                            "cabang_id" => "placeID",
                    //                            "gudang_id" => ".0",
                    //                            "state"     => ".hold",
                    //                            "jenis"     => ".kas",
                    //                            "produk_id" => "cash_account",
                    //                            "nama"      => "cash_account__label",
                    //                            "nilai"     => "(kas_value+kas_add)", // nilai_entry
                    //                            //                            "transaksi_id" => ".0",
                    //                            "oleh_id"   => ".0",
                    //                        ),
                    //                        "srcGateName"    => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable"     => true,
                    //                    ),
                    //
                    //                    // ----- rekening koran
                    //                    array(
                    //                        "comName"        => "LockerValue",
                    //                        "loop"           => array(),
                    //                        "static"         => array(
                    //                            "cabang_id"    => "cabangID",
                    //                            "gudang_id"    => ".0",
                    //                            "state"        => ".active",
                    //                            "jenis"        => ".plafon hutang bank",
                    //                            "produk_id"    => "cash_account",
                    //                            "nama"         => "cash_account__nama",
                    //                            "nilai"        => "-(rekening_koran_value+rekening_koran_add)",
                    //                            "transaksi_id" => ".0",
                    //                            "oleh_id"      => ".0",
                    //                        ),
                    //                        "srcGateName"    => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable"     => true,
                    //                    ),
                    //                    array(
                    //                        "comName"        => "LockerValue",
                    //                        "loop"           => array(),
                    //                        "static"         => array(
                    //                            "cabang_id" => "cabangID",
                    //                            "gudang_id" => ".0",
                    //                            "state"     => ".hold",
                    //                            "jenis"     => ".plafon hutang bank",
                    //                            "produk_id" => "cash_account",
                    //                            "nama"      => "cash_account__nama",
                    //                            "nilai"     => "rekening_koran_value+rekening_koran_add",
                    //                            //                            "transaksi_id" => ".0",
                    //                            "oleh_id"   => ".0",
                    //                        ),
                    //                        "srcGateName"    => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable"     => true,
                    //                    ),
                    //                    array(
                    //                        "comName"        => "LockerStockPlafonBankMutasiMain",
                    //                        "loop"           => array(),
                    //                        "static"         => array(
                    //                            "cabang_id"       => "placeID",
                    //                            "extern_id"       => "cash_account",
                    //                            "extern_nama"     => "cash_account__label",
                    //                            "debet"           => "-(rekening_koran_value+rekening_koran_add)",
                    //                            "produk_nilai"    => "-(rekening_koran_value+rekening_koran_add)",
                    //                            "gudang_id"       => ".0",
                    //                            "jenis"           => "jenisTr",
                    //                            "transaksi_jenis" => "jenisTr",
                    //                        ),
                    //                        "srcGateName"    => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //
                    //                    // ----- mengurangi stok valas bila pakai stok valas.
                    //                    array(
                    //                        "comName"        => "LockerValue",
                    //                        "loop"           => array(),
                    //                        "static"         => array(
                    //                            "cabang_id"    => "cabangID",
                    //                            "gudang_id"    => ".0",
                    //                            "state"        => ".active",
                    //                            "jenis"        => ".valas",
                    //                            "produk_id"    => "valas_account",
                    //                            "nama"         => "valas_account__label",
                    //                            "nilai"        => "-valas_nilai_bayar",
                    //                            "transaksi_id" => ".0",
                    //                            "oleh_id"      => ".0",
                    //                        ),
                    //                        "srcGateName"    => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable"     => true,
                    //                    ),
                    //                    array(
                    //                        "comName"        => "LockerValue",
                    //                        "loop"           => array(),
                    //                        "static"         => array(
                    //                            "cabang_id" => "cabangID",
                    //                            "gudang_id" => ".0",
                    //                            "state"     => ".hold",
                    //                            "jenis"     => ".valas",
                    //                            "produk_id" => "valas_account",
                    //                            "nama"      => "valas_account__label",
                    //                            "nilai"     => "valas_nilai_bayar",
                    //                            //                            "transaksi_id" => ".0",
                    //                            "oleh_id"   => ".0",
                    //                        ),
                    //                        "srcGateName"    => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable"     => true,
                    //                    ),

                ),
                "detail" => array(),
            ),
            "4466" => array(
                "master" => array(
                    // ------- -------
                    // ----- kas
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
                            "nilai" => "-(kas_value+kas_add)", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    //                    array(
                    //                        "comName" => "LockerValue",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "gudang_id" => ".0",
                    //                            "state" => ".hold",
                    //                            "jenis" => ".kas",
                    //                            "produk_id" => "cash_account",
                    //                            "nama" => "cash_account__label",
                    //                            "nilai" => "(kas_value+kas_add)", // nilai_entry
                    //                            //                            "transaksi_id" => ".0",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable" => true,
                    //                    ),

                    // ----- rekening koran
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "-(rekening_koran_value+rekening_koran_add)",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    //                    array(
                    //                        "comName" => "LockerValue",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "cabangID",
                    //                            "gudang_id" => ".0",
                    //                            "state" => ".hold",
                    //                            "jenis" => ".plafon hutang bank",
                    //                            "produk_id" => "cash_account",
                    //                            "nama" => "cash_account__nama",
                    //                            "nilai" => "rekening_koran_value+rekening_koran_add",
                    //                            //                            "transaksi_id" => ".0",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable" => true,
                    //                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-(rekening_koran_value+rekening_koran_add)",
                            "produk_nilai" => "-(rekening_koran_value+rekening_koran_add)",
                            "gudang_id" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // ----- mengurangi stok valas bila pakai stok valas.
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".valas",
                            "produk_id" => "valas_account",
                            "nama" => "valas_account__label",
                            "nilai" => "-valas_nilai_bayar",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    //                    array(
                    //                        "comName" => "LockerValue",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "cabangID",
                    //                            "gudang_id" => ".0",
                    //                            "state" => ".hold",
                    //                            "jenis" => ".valas",
                    //                            "produk_id" => "valas_account",
                    //                            "nama" => "valas_account__label",
                    //                            "nilai" => "valas_nilai_bayar",
                    //                            //                            "transaksi_id" => ".0",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable" => true,
                    //                    ),


                    // ------- -------
                    // ----- kas

                    //                    array(
                    //                        "comName" => "LockerValue",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "gudang_id" => ".0",
                    //                            "state" => ".hold",
                    //                            "jenis" => ".kas",
                    //                            "produk_id" => "cash_account",
                    //                            "nama" => "cash_account__label",
                    //                            "nilai" => "-(kas_value+kas_add)", // nilai_entry
                    ////                            "transaksi_id" => "masterID",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
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
                            "nilai" => "kas_value+kas_add", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // ----- rekening koran

                    //                    array(
                    //                        "comName" => "LockerValue",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "cabangID",
                    //                            "gudang_id" => ".0",
                    //                            "state" => ".hold",
                    //                            "jenis" => ".plafon hutang bank",
                    //                            "produk_id" => "cash_account",
                    //                            "nama" => "cash_account__nama",
                    //                            "nilai" => "-(rekening_koran_value+rekening_koran_add)",
                    ////                            "transaksi_id" => "masterID",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                        "reversable" => true,
                    //                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".payment",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value+rekening_koran_add",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),

                    // ----- mengurangi stok locker valas bila pakai stok valas.

                    //                    array(
                    //                        "comName" => "LockerValue",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "cabangID",
                    //                            "gudang_id" => ".0",
                    //                            "state" => ".hold",
                    //                            "jenis" => ".valas",
                    //                            "produk_id" => "valas_account",
                    //                            "nama" => "valas_account__label",
                    //                            "nilai" => "-valas_nilai_bayar",
                    ////                            "transaksi_id" => "masterID",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".payment",
                            "jenis" => ".valas",
                            "produk_id" => "valas_account",
                            "nama" => "valas_account__label",
                            "nilai" => "valas_nilai_bayar",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // ----- membuat payment source uang muka valas
                    array(
                        "comName" => "UangMukaValasSourceMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka valas",
                            "jenis" => "jenisTr",
                            "target_jenis" => ".14464",
                            "transaksi_id" => "transaksi_id",
                            //---------
                            "nilai" => "valas_uang_muka_nilai",
                            "nilai_valas" => "total_amount", // qty
                            //---------
                            "nomer" => "nomer",
                            "reference_jenis" => "jenisTr",
                            "extern_nilai_2" => "harga",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "extern2_id" => "pihak2ID",
                            "extern2_nama" => "pihak2Name",
                            "extern_label2" => ".vendor",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    // fifo valas masuk by vendor by valas, dari stok valas
                    array(
                        "comName" => "FifoValasExternAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "id",
                            "nama" => "name",
                            "jml" => "qty",
                            "hpp" => "valas_hpp",
                            "jml_nilai" => "sub_valas_hpp",

                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "FifoValasExtern",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "id",
                            "produk_nama" => "name",

                            "unit" => "qty",
                            "hpp" => "valas_hpp",
                            "jml_nilai" => "sub_valas_hpp",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),

                    // ----- menambah fifo uang muka valas by vendor by valas, dari beli langsung.
                    array(
                        "comName" => "FifoValasExternAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "pihak2ID",
                            "nama" => "pihak2Name",
                            "jml" => "valas_kurang",
                            "hpp" => "kurs_actual",
                            "jml_nilai" => "valas_kurang*kurs_actual",

                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoValasExtern",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "pihak2ID",
                            "produk_nama" => "pihak2Name",

                            "unit" => "valas_kurang",
                            "hpp" => "kurs_actual",
                            "jml_nilai" => "valas_kurang*kurs_actual",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    // locker value by vendor by valas
                    array(
                        "comName" => "LockerValueExternItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".valas",
                            "produk_id" => "pihak2ID",
                            "nama" => "pihak2Name",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "qty",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    ////                    ----- membuat payment source uang muka valas, dari beli langsung
                    //                    array(
                    //                        "comName" => "UangMukaValasSourceDetail",//untuk nulis ke payment source karena gerbang dari detail, di trnasksi misc di off kan ya bro
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "cabangName",
                    //                            "extern_id" => "pihakID",
                    //                            "extern_nama" => "pihakName",
                    //                            "label" => ".uang muka valas",
                    //                            "jenis" => "jenisTr",
                    //                            "target_jenis" => ".14464",
                    //                            "transaksi_id" => "transaksi_id",
                    //                            //---------
                    //                            "nilai" => "valas_kurang*kurs_actual",
                    //                            "nilai_valas" => "valas_kurang", // qty
                    //                            //---------
                    //                            "nomer" => "nomer",
                    //                            "reference_jenis" => "jenisTr",
                    //                            "extern_nilai_2" => "harga",
                    //                            "oleh_id" => "olehID",
                    //                            "oleh_nama" => "olehName",
                    //                            "extern2_id" => "pihak2ID",
                    //                            "extern2_nama" => "pihak2Name",
                    //                            "extern_label2" => ".vendor",
                    //                        ),
                    //                        "reversable" => true,
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                    //                    ----- membuat payment source uang muka valas, dari stock valas
                    //                    array(
                    //                        "comName" => "UangMukaValasSourceDetail",//untuk nulis ke payment source karena gerbang dari detail, di trnasksi misc di off kan ya bro
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "cabangName",
                    //                            "extern_id" => "pihakID",
                    //                            "extern_nama" => "pihakName",
                    //                            "label" => ".uang muka valas",
                    //                            "jenis" => "jenisTr",
                    //                            "target_jenis" => ".14464",
                    //                            "transaksi_id" => "transaksi_id",
                    //                            //---------
                    //                            "nilai" => "qty*valas_hpp",
                    //                            "nilai_valas" => "qty",
                    //                            //---------
                    //                            "nomer" => "nomer",
                    //                            "reference_jenis" => "jenisTr",
                    //                            "extern_nilai_2" => "harga",
                    //                            "oleh_id" => "olehID",
                    //                            "oleh_nama" => "olehName",
                    //                            "extern2_id" => "pihak2ID",
                    //                            "extern2_nama" => "pihak2Name",
                    //                            "extern_label2" => ".vendor",
                    //                        ),
                    //                        "reversable" => true,
                    //                        "srcGateName" => "rsltItems",
                    //                        "srcRawGateName" => "rsltItems",
                    //                    ),
                ),
            ),
        ),
    ),
    //transfer kas to brance
    "453" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                "gudang2ID" => "gudang_target",
                "gudang2Name" => "gudang_target__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "disc"          => "(discPersen*harga)/100",
                //                "harga_disc"    => "harga-disc",
                //                "harga_other"   => "harga_disc+other",
                //                "ppn"           => "(ppn_persen_dipakai*harga_disc)/100",
                ////                "ppn" => "(ppnPersen*harga_disc)/100",
                //                "hpp_nppn"      => "harga_disc+ppn+other",
                //                "nett"          => "hpp_nppn",
                //                "srcAccount"    => "nama",
                //                "harga_dipakai" => "hpp_nppn-ppn",
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            //            "tagihan"     => "grand_total-discount-dp",

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
                "transaksi_nilai" => "harga",
                "grand_total" => "hpp_nppn",
                "other" => "other",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

                "cabang2_id" => "pihakID",
                "cabang2_nama" => "pihakName",
                //                "place2_id"    => "place2ID",
                //                "place2_nama"  => "place2Name",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
                //                "srcAccount" =>"name",
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

        "components" => array(),
        "postProcessor" => array(
            "453r" => array(
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
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "453" => array(
                "master" => array(),
                "detail" => array(),
            ),
        ),
    ),
    "454" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
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

                "cabang2ID" => "cabang2ID",
                "cabang2Name" => "cabang2Name",
                "place2ID" => "place2ID",
                "place2Name" => "place2Name",
                "gudang2ID" => "gudang2ID",
                "gudang2Name" => "gudang2Name",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                "harga_other" => "harga_disc+other",
                "ppn" => "(ppn_persen_dipakai*harga_disc)/100",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                "hpp_nppn" => "harga_disc+ppn+other",
                "nett" => "hpp_nppn",
                "srcAccount" => "nama",
                "harga_dipakai" => "hpp_nppn-ppn",
            ),
            "master_dependent" => array(
                "pihakMainRulesID" => array(
                    "pm" => array(
                        "nilai_ppn" => "ppn",
                        "nilai_persediaan" => "harga_other",
                        "dpp_vat" => "harga_disc",
                        //                        "ppnFactor" =>"10",
                    ),
                    "non_pm" => array(
                        "nilai_ppn" => ".0",
                        "nilai_persediaan" => "nett",
                        "dpp_vat" => ".0",
                        //                        "ppnFactor" =>"0",
                    ),


                ),
                "paymentMethod" => array(
                    "credit" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "cbd" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "cia" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "tt_adv" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                ),

            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",

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
                "transaksi_nilai" => "harga",
                "grand_total" => "hpp_nppn",
                "other" => "other",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
                //                "srcAccount" =>"name",
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
            "454" => array(
                "master" => array(
                    // jurnal rekening cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "020401" => "harga",// hutang ke pusat
                            "010101" => "harga",// kas
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
                            "020401" => "harga",// hutang ke pusat
                            "010101" => "harga",// kas
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
                            "020401" => "harga",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "010101" => "harga",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "cash_account_target",
                            "extern_nama" => "cash_account_target__label",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // jurnal rekening pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010801" => "harga",// piutang cabang
                            "010101" => "-harga",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "010801" => "harga",// piutang cabang
                            "010101" => "-harga",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "010801" => "harga",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "010101" => "-harga",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "cash_account_source",
                            "extern_nama" => "cash_account_source__label",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "454" => array(
                "master" => array(
                    // locker kas pusat
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
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
                            "cabang_id" => "place2ID",
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

                    // locker kas cabang
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
                ),
                "detail" => array(),
            ),
        ),
    ),
    // uang muka dari konsumen
    "4464" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "ppn" => "(ppnPersen*harga_disc)/100",
//                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4464" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010101" => "nett",// kas
                            "020403" => "nett",// hutang ke konsumen
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
                            "010101" => "nett",// kas
                            "020403" => "nett",// hutang ke konsumen
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
                            "010101" => "nett",// kas
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "020403" => "nett",// hutang ke konsumen
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
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "4464" => array(
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
                            "nilai" => "nett", // nilai_entry
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
    // setoran uang muka dari konsumen
    "7759" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|customerID",
            //            "stepCode|placeID|customerID",
        ),
        "formatNota" => "stepCode|placeID|olehID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
                "place2ID" => "centerDetails",
                "place2Name" => "centerDetails__label",
                "gudang2Name" => "gudang2ID__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "cashMethode" => array(
                    "rekening_koran" => array(
                        "rekening_koran_value" => "nilai_entry",
                        "kas_value" => "0",
                    ),
                    "reguler" => array(
                        "rekening_koran_value" => "0",
                        "kas_value" => "nilai_entry",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
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
            "bottom" => "tagihan",//harga_nett2
        ),
        "additionalItemSource" => array(
            "harga_nett2" => "tagihan",//harga_nett2
            "hpp" => "hpp",
            "ppn" => "ppn",
            "laba_kotor" => "tagihan-hpp",//harga_nett2
        ),
        "additionalItemResult" => array(
            "harga_nett2" => "__harga_nett2",
            "hpp" => "__hpp",
            "ppn" => "__ppn",
            "laba_kotor" => "__laba_kotor",
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
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

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
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
                "new_sisa" => "new_sisa",
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
        "components" => array(),
        "postProcessor" => array(
            "7759r" => array(
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
                            "nilai" => "-nilai_entry",
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
                            //                            "state" => ".payment",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "nilai_entry",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
                            "nilai" => ".1",
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
                            "label" => ".hutang setoran",
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
    "7758" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|customerID",
            //            "stepCode|placeID|customerID",
        ),
        "formatNota" => "stepCode|placeID|olehID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            //            "totalCredit"=>"creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "nilai_bayar" => "nilai_entry",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //            ),
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

        "preProcessor" => array(
//            "7758" => array(
//                "master" => array(
//                    array(
//                        "comName" => "LockerDebtValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "jenis" => ".hutang biaya ke pusat",
//                            "nilai" => "nilai_entry",
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
//                    array(
//                        "comName" => "LockerDebtValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            //                            "gudang_id" => "gudangID",
//                            //                            "state" => ".active",
//                            "jenis" => ".hutang ke pusat",
//                            //                            "produk_id" => "pihakID",
//                            //                            "nama" => "pihakName",
//                            //                            "nilai" => "ppn",
//                            "nilai" => "nilai_sisa_hutang_biaya_ke_pusat",
//                            //                            "transaksi_id" => "masterID",
//                            //                            "oleh_id" => ".0",
//                            //                            "paymentMethod" => "paymentMethod",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "nilai_dipakai" => "nilai_dipakai",
//                                "nilai_sisa" => "nilai_sisa",
//                                //                                "nilai_tambah" => "nilai_tambah",
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // rekening koran
//                    array(
//                        "comName" => "RekeningKoran",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "state" => ".active",
//                            "extern_id" => "cash_account_target",
//                            "extern_nama" => "cash_account_target__label",
//                            "nilai" => "nilai_entry",
//                            "method" => "cashMethode", // cash method yang dipilih saat setor
//                            "jenis" => ".hutang bank",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "nilai_cash" => "nilai_cash",
//                                "nilai_koran" => "nilai_koran",
//                                "nilai_cash_full" => "nilai_cash_full",
//                                "nilai_koran_full" => "nilai_koran_full",
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//            ),

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
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

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
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
                "new_sisa" => "new_sisa",
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
            "7758" => array(
                "master" => array(
                    //<editor-fold desc="bagian cabang">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "010803" => "nilai_entry",// piutang ke pusat
                            "010101" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "010803" => "nilai_entry",// piutang ke pusat
                            "010101" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "010101" => "-nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "cash_account_source",// diisi id bank
                            "extern_nama" => "cash_account_source__label",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "010803" => "nilai_entry",// piutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //</editor-fold>

                    //<editor-fold desc="bagian pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "020412" => "nilai_entry",// hutang ke cabang
                            "010101" => "nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "020412" => "nilai_entry",// hutang ke cabang
                            "010101" => "nilai_entry",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
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
                            "extern_id" => "cash_account_target",// diisi id bank
                            "extern_nama" => "cash_account_target__label",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "020412" => "nilai_entry",// hutang ke cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
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
            "7758" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "gudang_id" => ".0",
                            //                            "state" => ".payment",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "-nilai_entry",
                            //                            "transaksi_id" => ".0",
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
                            "cabang_id" => "cabang2ID",
                            "gudang_id" => ".0",
                            "state" => ".sold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "nilai_entry",
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
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_target",
                            "nama" => "cash_account_target__label",
//                            "nilai" => "nilai_cash_full",
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
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),

    // settlement uang muka dari konsumen
    "7761" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|fulldate",
        ),
        "formatNota" => "stepCode,fulldate,placeID,stepCode|placeID|olehID,olehID,stepCode|olehID",
        "valueGates" => array(
            "master" => array(),
            "detail" => array(),
        ),
        "valueBuilders" => array(
            "nilai_bayar" => "nilai_entry",
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
            "harus_bayar" => "sisa-totalCredit",
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
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

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
                "new_sisa" => "new_sisa",
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
        "components" => array(),
        "postProcessor" => array(),
    ),

    "7760" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID|olehID",
        "valueGates" => array(
            "master" => array(),
            "detail" => array(),
        ),
        "valueBuilders" => array(
            "nilai_bayar" => "nilai_entry",
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
            "harus_bayar" => "sisa-totalCredit",
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
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

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
                "new_sisa" => "new_sisa",
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
        "components" => array(),
        "postProcessor" => array(),
    ),
    // uang muka dari konsumen
    "4467" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "ppn" => "(ppnPersen*harga_disc)/100",
//                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
            "master_dependent" => array(
                "selectedType_konsumen" => array(
                    "include_ppn" => array(
                        "dpp_nilai" => "harga*(100/(100+ppnFactor))",
                        "ppn" => "dpp_nilai*(ppnFactor/100)",
                        "total_ui" => "dpp_nilai",
                        "new_grand_ppn" => "ppn",
                        "grand_ppn" => "ppn",
                        "ppn_out_bulat" => "ppn",
                        "nett1" => "dpp_nilai",
                        "nett1_bulat" => "dpp_nilai",
                        "grand_total_ui" => "dpp_nilai",
                        "tagihan" => "harga",
                        "dpp_ppn" => "dpp_nilai",
                    ),
                    "exclude_ppn" => array(
                        "dpp_nilai" => "harga",
                        "ppn" => "0",
                        "total_ui" => "harga",
                        "nett1" => "harga",
                        "dpp_ppn" => "0",
                        "grand_ppn" => "0",
                        "grand_total_ui" => "harga",
                    ),
                ),

                //switcher rekening pembantu karena edc merelasi ke rekening cvash in dimana disimpan di table ref_id pada table bank
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
                "referensiNota" => array(
                    //titipan masuk payment uang_muka_source
                    "1" => array(
                        "nilai_payment_source" => "0",
                        "nilai_uang_muka_source" => "dpp_nilai",
                        "selectedType_konsumen" => ".exclude_ppn",
                    ),
                    //uangmuka ppn masuk payment_source
                    "2" => array(
                        "nilai_payment_source" => "dpp_nilai",
                        "nilai_uang_muka_source" => "0",
//                        "selectedType_konsumen" => ".include_ppn",

                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "dpp_nilai+ppn",
            "tagihan" => "grand_total",
            "hutang_ke_konsumen" => "dpp_nilai",
            "kas_nilai" => "grand_total",
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
        "populatorsGate" => "items4_sum",// model ini defaultnya ke gerbang items

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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4467" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",
                            "extern_nama" => "cash_account_nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // MENAMBAH HUTANG KE KONSUMEN (UANG MUKA KONSUMEN)....
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050060",// uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050050",//uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //unagmuka konsumen dengan ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050060",// uang muka konsumen dengan ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //unagmuka konsumen tanpa ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",// uang muka konsumen tanpa ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
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
            "4467" => array(
                "master" => array(
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
                            "nilai" => "kas_nilai", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nett",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",
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
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",

        //------------------------------
        "preProcessorAuto" => array(
            "4467" => array(
                "master" => array(
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040020",//hutang biaya ke pusat
//                            "nilai" => "nilai_bayar",
                            "nilai" => "kas_nilai",
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
            "4467" => array(
                "master" => array(
                    //region bagian cabang
                    90 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-kas_nilai",// kas
                            "1010040030" => "-pph23",// pph23 dibayar dimuka
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
                            "1010010010" => "-kas_nilai",// kas
                            "1010040030" => "-pph23",// pph23 dibayar dimuka
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
                            "1010010010" => "-kas_nilai",// kas
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
                    //rekening pembantu pph23
                    95 => array(
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
                    //endregion>

                    //region bagian pusat
                    96 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "kas_nilai",// kas
                            "1010040030" => "pph23",// pph23 dibayar dimuka
//                            "2020020" => "-nilai_koran_full",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    97 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "kas_nilai",// kas
                            "1010040030" => "pph23",// pph23 dibayar dimuka
//                            "2020020" => "-nilai_koran_full",// hutang bank
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
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
                    99 => array(
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
                    100 => array(
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
                    //pembantu uangmuka pph23
                    101 => array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "1010040030" => "pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "-1",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "pph23",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessorAuto" => array(
            "4464" => array(
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
                            "nilai" => "-kas_nilai",
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
                            "nilai" => "kas_nilai",
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
                            "extern_id" => "olehID",
                            "extern_nama" => "olehName",
                            "label" => ".hutang setoran",
                            "target_jenis" => ".7759",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "kas_nilai",
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

    "9467" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",

                "cabang2ID" => ".-1",
                "cabang2Name" => ".pusat",
                "place2ID" => ".-1",
                "place2Name" => ".pusat",
                "gudang2ID" => ".-1",
                "gudang2Name" => ".default center warehouse",

            ),
            "detail" => array(//===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "ppn" => "(ppnPersen*harga_disc)/100",
//                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
            "master_dependent" => array(
                "selectedType_konsumen" => array(
                    "include_ppn" => array(
                        "dpp_nilai" => "harga*(100/(100+ppnFactor))",
                        "ppn" => "dpp_nilai*(ppnFactor/100)",
                        "total_ui" => "dpp_nilai",
                        "new_grand_ppn" => "ppn",
                        "grand_ppn" => "ppn",
                        "ppn_out_bulat" => "ppn",
                        "nett1" => "dpp_nilai",
                        "nett1_bulat" => "dpp_nilai",
                        "grand_total_ui" => "dpp_nilai",
                        "tagihan" => "harga",
                        "dpp_ppn" => "dpp_nilai",
                    ),
                    "exclude_ppn" => array(
                        "dpp_nilai" => "harga",
                        "ppn" => "0",
                        "total_ui" => "harga",
                        "nett1" => "harga",
                        "dpp_ppn" => "0",
                        "grand_ppn" => "0",
                        "grand_total_ui" => "harga",
                    ),
                ),

                //switcher rekening pembantu karena edc merelasi ke rekening cvash in dimana disimpan di table ref_id pada table bank
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
                "referensiNota" => array(
                    //titipan masuk payment uang_muka_source
                    "1" => array(
                        "nilai_payment_source" => "0",
                        "nilai_uang_muka_source" => "dpp_nilai",
                        "selectedType_konsumen" => ".exclude_ppn",
                    ),
                    //uangmuka ppn masuk payment_source
                    "2" => array(
                        "nilai_payment_source" => "dpp_nilai",
                        "nilai_uang_muka_source" => "0",
//                        "selectedType_konsumen" => ".include_ppn",

                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "dpp_nilai+ppn",
            "tagihan" => "grand_total",
            "hutang_ke_konsumen" => "dpp_nilai",
            "kas_nilai" => "grand_total",
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
        "populatorsGate" => "items4_sum",// model ini defaultnya ke gerbang items

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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4467" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",
                            "extern_nama" => "cash_account_nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // MENAMBAH HUTANG KE KONSUMEN (UANG MUKA KONSUMEN)....
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050060",// uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050050",//uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //unagmuka konsumen dengan ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050060",// uang muka konsumen dengan ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //unagmuka konsumen tanpa ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",// uang muka konsumen tanpa ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
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
            "4467" => array(
                "master" => array(
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
                            "nilai" => "kas_nilai", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nett",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",
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
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",

        //------------------------------
//        "preProcessorAuto" => array(
//            "4467" => array(
//                "master" => array(
//                    array(
//                        "comName" => "LockerDebtValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".2040020",//hutang biaya ke pusat
////                            "nilai" => "nilai_bayar",
//                            "nilai" => "kas_nilai",
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
//                    array(
//                        "comName" => "LockerDebtValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".2040010",//hutang ke pusat
//                            "nilai" => "nilai_sisa_2040020",
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
//        "componentsAuto" => array(
//            "4467" => array(
//                "master" => array(
//                    //region bagian cabang
//                    90 => array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
//                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
//                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
//                            "1010010010" => "-kas_nilai",// kas
//                            "1010040030" => "-pph23",// pph23 dibayar dimuka
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    91 => array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
//                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
//                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
//                            "1010010010" => "-kas_nilai",// kas
//                            "1010040030" => "-pph23",// pph23 dibayar dimuka
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    92 => array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "-kas_nilai",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_id",// diisi id bank
//                            "extern_nama" => "cash_account_nama",// diisi nama bank
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    93 => array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => ".-1",
//                            "cabang2_nama" => ".PUSAT (DC)",
//                            "extern_id" => ".-1",
//                            "extern_nama" => ".PUSAT (DC)",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    94 => array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => ".-1",
//                            "cabang2_nama" => ".PUSAT (DC)",
//                            "extern_id" => ".-1",
//                            "extern_nama" => ".PUSAT (DC)",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //rekening pembantu pph23
//                    95 => array(
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
//                    //endregion>
//
//                    //region bagian pusat
//                    96 => array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
//                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
//                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
//                            "1010010010" => "kas_nilai",// kas
//                            "1010040030" => "pph23",// pph23 dibayar dimuka
////                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    97 => array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
//                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
//                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
//                            "1010010010" => "kas_nilai",// kas
//                            "1010040030" => "pph23",// pph23 dibayar dimuka
////                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    98 => array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "kas_nilai",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => "cash_account_id",// diisi id bank
//                            "extern_nama" => "cash_account_nama",// diisi nama bank
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    99 => array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
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
//                    100 => array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
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
//                    //pembantu uangmuka pph23
//                    101 => array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "1010040030" => "pph23",//hutang pph23
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",
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
//                ),
//                "detail" => array(),
//            ),
//        ),
//        "postProcessorAuto" => array(
//            "4464" => array(
//                "master" => array(
//                    // locker kas cabang
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_id",
//                            "nama" => "cash_account_nama",
//                            "nilai" => "-kas_nilai",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // locker kas reguler pusat
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_id",
//                            "nama" => "cash_account_nama",
//                            "nilai" => "kas_nilai",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // locker kas rekening koran pusat
////                    array(
////                        "comName" => "LockerValue",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "gudang_id" => ".0",
////                            "state" => ".active",
////                            "jenis" => ".kas",
////                            "produk_id" => "cash_account_target",
////                            "nama" => "cash_account_target__label",
////                            "nilai" => "nilai_cash",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // menambah available rekening koran
////                    array(
////                        "comName" => "LockerValue",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabangID",
////                            "gudang_id" => ".0",
////                            "state" => ".active",
////                            "jenis" => ".plafon hutang bank",
////                            "produk_id" => "cash_account_target",
////                            "nama" => "cash_account_target__label",
////                            "nilai" => "nilai_koran_full",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "LockerStockPlafonBankMutasiMain",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "cash_account_target",
////                            "extern_nama" => "cash_account_target__label",
////                            "debet" => "nilai_koran_full",
////                            "produk_nilai" => "nilai_koran_full",
////                            "gudang_id" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_jenis" => "jenisTr",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "LockerValue",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabangID",
////                            "gudang_id" => ".0",
////                            "state" => ".active",
////                            "jenis" => ".plafon hutang bank",
////                            "produk_id" => "cash_account_target",
////                            "nama" => "cash_account_target__label",
////                            "nilai" => "-nilai_koran",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "LockerStockPlafonBankMutasiMain",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "cash_account_target",
////                            "extern_nama" => "cash_account_target__label",
////                            "debet" => "-nilai_koran",
////                            "produk_nilai" => "-nilai_koran",
////                            "gudang_id" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_jenis" => "jenisTr",
////                            //                            "transaksi_id"        => "jenisTr",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    array(
//                        "comName" => "PaymentSrcMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "olehID",
//                            "extern_nama" => "olehName",
//                            "label" => ".hutang setoran",
//                            "target_jenis" => ".7759",
//                            "transaksi_id" => "transaksi_id",
//                            "terbayar" => "kas_nilai",
//                            "sisa" => ".0",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                ),
//                "detail" => array(),
//            ),
//        ),
        //------------------------------
    ),
    "19467" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",

                "cabang2ID" => ".-1",
                "cabang2Name" => ".pusat",
                "place2ID" => ".-1",
                "place2Name" => ".pusat",
                "gudang2ID" => ".-1",
                "gudang2Name" => ".default center warehouse",

            ),
            "detail" => array(//===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "ppn" => "(ppnPersen*harga_disc)/100",
//                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
            "master_dependent" => array(
                "selectedType_konsumen" => array(
                    "include_ppn" => array(
                        "dpp_nilai" => "harga*(100/(100+ppnFactor))",
                        "ppn" => "dpp_nilai*(ppnFactor/100)",
                        "total_ui" => "dpp_nilai",
                        "new_grand_ppn" => "ppn",
                        "grand_ppn" => "ppn",
                        "ppn_out_bulat" => "ppn",
                        "nett1" => "dpp_nilai",
                        "nett1_bulat" => "dpp_nilai",
                        "grand_total_ui" => "dpp_nilai",
                        "tagihan" => "harga",
                        "dpp_ppn" => "dpp_nilai",
                    ),
                    "exclude_ppn" => array(
                        "dpp_nilai" => "harga",
                        "ppn" => "0",
                        "total_ui" => "harga",
                        "nett1" => "harga",
                        "dpp_ppn" => "0",
                        "grand_ppn" => "0",
                        "grand_total_ui" => "harga",
                    ),
                ),

                //switcher rekening pembantu karena edc merelasi ke rekening cvash in dimana disimpan di table ref_id pada table bank
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
                "referensiNota" => array(
                    //titipan masuk payment uang_muka_source
                    "1" => array(
                        "nilai_payment_source" => "0",
                        "nilai_uang_muka_source" => "dpp_nilai",
                        "selectedType_konsumen" => ".exclude_ppn",
                    ),
                    //uangmuka ppn masuk payment_source
                    "2" => array(
                        "nilai_payment_source" => "dpp_nilai",
                        "nilai_uang_muka_source" => "0",
//                        "selectedType_konsumen" => ".include_ppn",

                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "dpp_nilai+ppn",
            "tagihan" => "grand_total",
            "hutang_ke_konsumen" => "dpp_nilai",
            "kas_nilai" => "grand_total",
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
        "populatorsGate" => "items4_sum",// model ini defaultnya ke gerbang items

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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4467" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",
                            "extern_nama" => "cash_account_nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // MENAMBAH HUTANG KE KONSUMEN (UANG MUKA KONSUMEN)....
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050060",// uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050050",//uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //unagmuka konsumen dengan ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050060",// uang muka konsumen dengan ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //unagmuka konsumen tanpa ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",// uang muka konsumen tanpa ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
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
            "4467" => array(
                "master" => array(
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
                            "nilai" => "kas_nilai", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nett",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",
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
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",

        //------------------------------
//        "preProcessorAuto" => array(
//            "4467" => array(
//                "master" => array(
//                    array(
//                        "comName" => "LockerDebtValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".2040020",//hutang biaya ke pusat
////                            "nilai" => "nilai_bayar",
//                            "nilai" => "kas_nilai",
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
//                    array(
//                        "comName" => "LockerDebtValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".2040010",//hutang ke pusat
//                            "nilai" => "nilai_sisa_2040020",
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
//        "componentsAuto" => array(
//            "4467" => array(
//                "master" => array(
//                    //region bagian cabang
//                    90 => array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
//                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
//                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
//                            "1010010010" => "-kas_nilai",// kas
//                            "1010040030" => "-pph23",// pph23 dibayar dimuka
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    91 => array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
//                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
//                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
//                            "1010010010" => "-kas_nilai",// kas
//                            "1010040030" => "-pph23",// pph23 dibayar dimuka
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    92 => array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "-kas_nilai",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "cash_account_id",// diisi id bank
//                            "extern_nama" => "cash_account_nama",// diisi nama bank
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    93 => array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => ".-1",
//                            "cabang2_nama" => ".PUSAT (DC)",
//                            "extern_id" => ".-1",
//                            "extern_nama" => ".PUSAT (DC)",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    94 => array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => ".-1",
//                            "cabang2_nama" => ".PUSAT (DC)",
//                            "extern_id" => ".-1",
//                            "extern_nama" => ".PUSAT (DC)",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //rekening pembantu pph23
//                    95 => array(
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
//                    //endregion>
//
//                    //region bagian pusat
//                    96 => array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
//                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
//                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
//                            "1010010010" => "kas_nilai",// kas
//                            "1010040030" => "pph23",// pph23 dibayar dimuka
////                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    97 => array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
//                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
//                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
//                            "1010010010" => "kas_nilai",// kas
//                            "1010040030" => "pph23",// pph23 dibayar dimuka
////                            "2020020" => "-nilai_koran_full",// hutang bank
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    98 => array(
//                        "comName" => "RekeningPembantuKas",
//                        "loop" => array(
//                            "1010010010" => "kas_nilai",// kas
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => "cash_account_id",// diisi id bank
//                            "extern_nama" => "cash_account_nama",// diisi nama bank
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    99 => array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
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
//                    100 => array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
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
//                    //pembantu uangmuka pph23
//                    101 => array(
//                        "comName" => "RekeningPembantuPphMain",
//                        "loop" => array(
//                            "1010040030" => "pph23",//hutang pph23
//                        ),
//                        "static" => array(
//                            "cabang_id" => "-1",
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
//                ),
//                "detail" => array(),
//            ),
//        ),
//        "postProcessorAuto" => array(
//            "4464" => array(
//                "master" => array(
//                    // locker kas cabang
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_id",
//                            "nama" => "cash_account_nama",
//                            "nilai" => "-kas_nilai",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // locker kas reguler pusat
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account_id",
//                            "nama" => "cash_account_nama",
//                            "nilai" => "kas_nilai",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // locker kas rekening koran pusat
////                    array(
////                        "comName" => "LockerValue",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "gudang_id" => ".0",
////                            "state" => ".active",
////                            "jenis" => ".kas",
////                            "produk_id" => "cash_account_target",
////                            "nama" => "cash_account_target__label",
////                            "nilai" => "nilai_cash",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // menambah available rekening koran
////                    array(
////                        "comName" => "LockerValue",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabangID",
////                            "gudang_id" => ".0",
////                            "state" => ".active",
////                            "jenis" => ".plafon hutang bank",
////                            "produk_id" => "cash_account_target",
////                            "nama" => "cash_account_target__label",
////                            "nilai" => "nilai_koran_full",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "LockerStockPlafonBankMutasiMain",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "cash_account_target",
////                            "extern_nama" => "cash_account_target__label",
////                            "debet" => "nilai_koran_full",
////                            "produk_nilai" => "nilai_koran_full",
////                            "gudang_id" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_jenis" => "jenisTr",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "LockerValue",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "cabangID",
////                            "gudang_id" => ".0",
////                            "state" => ".active",
////                            "jenis" => ".plafon hutang bank",
////                            "produk_id" => "cash_account_target",
////                            "nama" => "cash_account_target__label",
////                            "nilai" => "-nilai_koran",
////                            "transaksi_id" => ".0",
////                            "oleh_id" => ".0",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "LockerStockPlafonBankMutasiMain",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "cash_account_target",
////                            "extern_nama" => "cash_account_target__label",
////                            "debet" => "-nilai_koran",
////                            "produk_nilai" => "-nilai_koran",
////                            "gudang_id" => ".0",
////                            "jenis" => "jenisTr",
////                            "transaksi_jenis" => "jenisTr",
////                            //                            "transaksi_id"        => "jenisTr",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    array(
//                        "comName" => "PaymentSrcMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "olehID",
//                            "extern_nama" => "olehName",
//                            "label" => ".hutang setoran",
//                            "target_jenis" => ".7759",
//                            "transaksi_id" => "transaksi_id",
//                            "terbayar" => "kas_nilai",
//                            "sisa" => ".0",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                ),
//                "detail" => array(),
//            ),
//        ),
        //------------------------------
    ),

    "7467" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",

                "cabang2ID" => ".-1",
                "cabang2Name" => ".pusat",
                "place2ID" => ".-1",
                "place2Name" => ".pusat",
                "gudang2ID" => ".-1",
                "gudang2Name" => ".default center warehouse",

            ),
            "detail" => array(//===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "ppn" => "(ppnPersen*harga_disc)/100",
//                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
            "master_dependent" => array(
                "selectedType_konsumen" => array(
                    "include_ppn" => array(
                        "dpp_nilai" => "harga*(100/(100+ppnFactor))",
                        "ppn" => "dpp_nilai*(ppnFactor/100)",
                        "total_ui" => "dpp_nilai",
                        "new_grand_ppn" => "ppn",
                        "grand_ppn" => "ppn",
                        "ppn_out_bulat" => "ppn",
                        "nett1" => "dpp_nilai",
                        "nett1_bulat" => "dpp_nilai",
                        "grand_total_ui" => "dpp_nilai",
                        "tagihan" => "harga",
                        "dpp_ppn" => "dpp_nilai",
                    ),
                    "exclude_ppn" => array(
                        "dpp_nilai" => "harga",
                        "ppn" => "0",
                        "total_ui" => "harga",
                        "nett1" => "harga",
                        "dpp_ppn" => "0",
                        "grand_ppn" => "0",
                        "grand_total_ui" => "harga",
                    ),
                ),

                //switcher rekening pembantu karena edc merelasi ke rekening cvash in dimana disimpan di table ref_id pada table bank
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
                "referensiNota" => array(
                    //titipan masuk payment uang_muka_source
                    "1" => array(
                        "nilai_payment_source" => "0",
                        "nilai_uang_muka_source" => "dpp_nilai",
                        "selectedType_konsumen" => ".exclude_ppn",
                    ),
                    //uangmuka ppn masuk payment_source
                    "2" => array(
                        "nilai_payment_source" => "dpp_nilai",
                        "nilai_uang_muka_source" => "0",
//                        "selectedType_konsumen" => ".include_ppn",

                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "dpp_nilai+ppn",
            "tagihan" => "grand_total",
            "hutang_ke_konsumen" => "dpp_nilai",
            "kas_nilai" => "grand_total",
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
        "populatorsGate" => "items4_sum",// model ini defaultnya ke gerbang items

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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "7467" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",
                            "extern_nama" => "cash_account_nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // MENAMBAH HUTANG KE KONSUMEN (UANG MUKA KONSUMEN)....
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050060",// uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050050",//uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //unagmuka konsumen dengan ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050060",// uang muka konsumen dengan ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //unagmuka konsumen tanpa ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",// uang muka konsumen tanpa ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
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
            "7467" => array(
                "master" => array(
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
                            "nilai" => "kas_nilai", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nett",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",
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
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",

    ),

    "7468" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",

                "cabang2ID" => ".-1",
                "cabang2Name" => ".pusat",
                "place2ID" => ".-1",
                "place2Name" => ".pusat",
                "gudang2ID" => ".-1",
                "gudang2Name" => ".default center warehouse",

            ),
            "detail" => array(//===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "ppn" => "(ppnPersen*harga_disc)/100",
//                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "harga",
            ),
            "master_dependent" => array(
                "selectedType_konsumen" => array(
                    "include_ppn" => array(
                        "dpp_nilai" => "harga*(100/(100+ppnFactor))",
                        "ppn" => "dpp_nilai*(ppnFactor/100)",
                        "total_ui" => "dpp_nilai",
                        "new_grand_ppn" => "ppn",
                        "grand_ppn" => "ppn",
                        "ppn_out_bulat" => "ppn",
                        "nett1" => "dpp_nilai",
                        "nett1_bulat" => "dpp_nilai",
                        "grand_total_ui" => "dpp_nilai",
                        "tagihan" => "harga",
                        "dpp_ppn" => "dpp_nilai",
                    ),
                    "exclude_ppn" => array(
                        "dpp_nilai" => "harga",
                        "ppn" => "0",
                        "total_ui" => "harga",
                        "nett1" => "harga",
                        "dpp_ppn" => "0",
                        "grand_ppn" => "0",
                        "grand_total_ui" => "harga",
                    ),
                ),

                //switcher rekening pembantu karena edc merelasi ke rekening cvash in dimana disimpan di table ref_id pada table bank
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
                "referensiNota" => array(
                    //titipan masuk payment uang_muka_source
                    "1" => array(
                        "nilai_payment_source" => "0",
                        "nilai_uang_muka_source" => "dpp_nilai",
                        "selectedType_konsumen" => ".exclude_ppn",
                    ),
                    //uangmuka ppn masuk payment_source
                    "2" => array(
                        "nilai_payment_source" => "dpp_nilai",
                        "nilai_uang_muka_source" => "0",
//                        "selectedType_konsumen" => ".include_ppn",

                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "dpp_nilai+ppn",
            "tagihan" => "grand_total",
            "hutang_ke_konsumen" => "dpp_nilai",
            "kas_nilai" => "grand_total",
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
        "populatorsGate" => "items4_sum",// model ini defaultnya ke gerbang items

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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "7467" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                            "1010010010" => "kas_nilai",// kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "1010040030" => "pph23",// pph23 dibayar dimuka
                            "2030060" => "ppn",// ppn keluaran belum ada faktur
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_nilai",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_id",
                            "extern_nama" => "cash_account_nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // MENAMBAH HUTANG KE KONSUMEN (UANG MUKA KONSUMEN)....
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050060",// uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050050",//uang muka konsumen
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //unagmuka konsumen dengan ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_payment_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050060",// uang muka konsumen dengan ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //unagmuka konsumen tanpa ppn
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",// uang muka konsumen tanpa ppn
                            "extern2_nama" => ".Uang Muka Konsumen",
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
            "7467" => array(
                "master" => array(
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
                            "nilai" => "kas_nilai", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "nilai_uang_muka_source",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nett",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",
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
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",

    ),

    // terima uang masuk belum teridentifikasi (request dan otorisasi)
    "7444" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(),
            "detail" => array(
                "nett" => "harga",
            ),
            "master_dependent" => array(),
        ),
//        "additionalPostMainBuilder" => array(
//            "cabang2ID" => ".-1",
//            "cabang2Name" => ".pusat",
//            "place2ID" => ".-1",
//            "place2Name" => ".pusat",
//            "gudang2ID" => ".-1",
//            "gudang2Name" => ".default center warehouse",
//        ),
        "valueBuilders" => array(
            "kas_value" => "nett",
            "nilai_entry" => "nett",
//            "nilai_biaya_usaha" => "nett",
//            "nilai_lain_lain" => "nett-nilai_pph_original",
        ),

//        "populators" => array(// model ini defaultnya ke gerbang items
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
//        "populatorsGate" => "items4_sum",// model ini defaultnya ke gerbang items

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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_net" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
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
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "7444" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "kas_value",// kas
                            "2010130" => "nilai_entry",// Hutang Atas Transfer Belum Teridentifikasi
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
                            "1010010010" => "kas_value",// kas
                            "2010130" => "nilai_entry",// Hutang Atas Transfer Belum Teridentifikasi
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_value",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "7444" => array(
                "master" => array(
                    // locker kas
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // locker uang masuk belum teridentifikasi
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".uang tanpa identitas",
                            "produk_id" => ".0",
                            "nama" => ".0",
                            "nilai" => "nilai_entry",
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
        //------------------------------
        "preProcessorAuto" => array(
            "7468" => array(
                "master" => array(
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040020",//hutang biaya ke pusat
                            "nilai" => "nilai_entry",
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
            "7468" => array(
                "master" => array(
                    //region bagian cabang
                    90 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-nilai_entry",// kas
//                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
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
//                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
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
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__nama",// diisi nama bank
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
//                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
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
//                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
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
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__nama",// diisi nama bank
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
                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessorAuto" => array(
            "7468" => array(
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
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
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
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

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
    "7445" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(
            "master" => array(),
            "detail" => array(
                "nett" => "harga",
            ),
            "master_dependent" => array(),
        ),
        "valueBuilders" => array(
            "hutang_no_identitas" => "nett",
            "hutang_ke_konsumen" => "nett",
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
                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "harga",
                "transaksi_net" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
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
                "satuan" => "satuan",
                "keterangan" => "note",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(

        ),
        "postProcessor" => array(

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
        //------------------------------


    ),
);