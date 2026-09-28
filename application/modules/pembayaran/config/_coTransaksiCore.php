<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(
    //payment objek pajak
    "682" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|supplierID",
            //            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),

        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "nilai_entry+totalCredit",

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
            //            "new_sisa" => "sisa-additionalFactor",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
        ),

        "preProcessor" => array(
            "682" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nilai_entry",
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
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
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
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "682" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "pib" => "nilai_entry",
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
                            //                            "piutang pembelian" => "-creditAmount",
                            //                            "hutang lain ppv" => "-nilai_bayar",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "pib" => "nilai_entry",
                            //                            "credit note" => "-diskon",
                            //                            "{add_jenis}" => "add_diskon",
                            //                            "diskon" => "",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",

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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiaya",
                        "loop" => array(
                            "pib" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "682" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".objek pajak",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "id",
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
    "1483" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|place2ID",
            "stepCode|placeID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            // "nilai_bayar" => "nilai_entry+totalCredit",
            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",
        ),
        "valuePopulator" => array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
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
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "1483" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "transaksi_nilai" => "nett",
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
                "produk_kode" => "produk_kode",
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
            "1483" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang pph21" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "selisih pembulatan" => "selisih_round",
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
                            "hutang pph21" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "selisih pembulatan" => "selisih_round",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //                    array(
                    //                        "comName" => "RekeningPembantuAntarcabang",
                    //                        "loop" => array(
                    //                            "hutang gaji" => "-nilai_entry",
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

                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "hutang pph21" => "-nilai_entry",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => "pairPihakID",// diisi id bank
                            "extern2_nama" => "pairPihakName",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1483" => array(
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
                            "nilai" => "-kas_value",
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
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
//                            "label" => ".hutang pph21",
                            "label" => ".hutang pph 21",
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
    "4447" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",
            "stepCode|placeID|pihakID",
        ),
        "formatNota" => "stepCode|placeID|pihakID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "supplierID" => "pihakID",
                //                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                //                "additional" => array(
                //                    "-1" => array(
                //                        "add_jenis" => ".keutungan kurs",
                //                        "add_diskon" => "additional_value",
                ////                        "bayar_total" => 'additional_value+creditAmount+diskon+nilai_entry',
                //                        "bayar_total" => 'additional_value+creditAmount+diskon',
                //                        "diskon_factor" => "0",
                //
                //                    ),
                //                    "1" => array(
                //                        "add_jenis" => ".kerugian kurs",
                //                        "add_diskon" => "additional_value",
                ////                        "bayar_total" => "creditAmount+diskon+nilai_entry",
                //                        "bayar_total" => "creditAmount+diskon",
                //                        "diskon_factor" => "additional_value",
                //
                //                    ),
                //                    "0" => array(
                //                        "additional_value" => ".0",
                //                        "add_jenis" => ".kerugian kurs",
                //                        "add_diskon" => ".0",
                ////                        "bayar_total" => "creditAmount+diskon+nilai_entry",
                //                        "bayar_total" => "creditAmount+diskon",
                //                        "diskon_factor" => ".0",
                //
                //                    ),
                //                ),
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry-diskon_factor",
            "additionalFactor" => "additional_value*additional",
            "nilai_dipakai" => "nilai_entry-additional_expense",
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

        "preProcessor" => array(
            "4447" => array(
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
                            "nilai" => "nilai_entry",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "4447" => array(
                "master" => array(
                    // jurnal 1
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "kas" => "rekening_koran_value", // kasnya bertambah dulu
                            "hutang bank" => "rekening_koran_value", // rekening koran bertambah
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // jurnal 2
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang bank" => "-nilai_entry", // non rekening koran berkurang
                            "kas" => "-nilai_entry", // kas berkurang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // rekening 1
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "kas" => "rekening_koran_value", // kasnya bertambah dulu
                            "hutang bank" => "rekening_koran_value", // rekening koran bertambah
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening 2
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "hutang bank" => "-nilai_entry", // non rekening koran berkurang
                            "kas" => "-nilai_entry", // kas berkurang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    array(
                        "comName" => "RekeningPembantuBank",
                        "loop" => array(
                            "hutang bank" => "-nilai_entry",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakID",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//rekening pembantu level 1
                    array(
                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
                        "loop" => array(
                            //                            "h" => "harga",//
                            "hutang bank" => "-nilai_entry",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2",//id relasi rekening koran
                            "extern2_id" => "pairPihakID",//id folder rekening koran BRI
                            "extern2_nama" => "pairPihakName",//label folder rekening koran
                            "extern_nama" => ".non rekening koran",//lbel relasi rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//rekening pembantu level 2


                    // rekening pembantu kas 1
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "rekening_koran_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu kas 2
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
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuRekeningKoran",//rekening pembantu level 3
                        "loop" => array(
                            //                            "rekening koran" => "harga",//
                            "non rekening koran" => "-nilai_bayar",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",//id rekening koran BRI xxx
                            "extern_nama" => "pihakName",//lbel rekening koran
                            "extern2_id" => "pair_pihak_id",//lbel rekening koran BRI
                            "extern2_nama" => "pair_pihak_name",//lbel rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "produk_nilai" => "nilai_bayar",
                            "produk_qty" => ".-1",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "4447" => array(
                "master" => array(
                    // locker value kas 1, bertambah
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
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
                    ////                            "nilai" => "nilai_entry",
                    //                            "nilai" => "rekening_koran_value",
                    //                            "transaksi_id" => ".0",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                    // locker value kas 2, berkurang
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
                            "nilai" => "-nilai_entry",
                            //                            "nilai" => "-kas_value",
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang bank",
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
    // config pembayaran biaya umum dari purchasing biaya
    "462" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "source_ppn_persen" => "(ppn/extern_nilai2)*100",
            ),
            "master_dependent" => array(
                "pphGateId" => array(
                    "1" => array(
                        "akun_pph_id" => ".37",
                        "akun_pph_label" => ".pph ps 23",
                    ),//dipotong
                    "2" => array(
                        "akun_pph_id" => ".38",
                        "akun_pph_label" => ".biaya pph ps. 23",
                    ),//tidak dipotong
                ),
                "pajakOption" => array(
                    "pph21" => array(
                        "pph23Method" => ".0",
                        "pph23Method__name" => ".0",
                        "pph23Method__label" => ".0",
                        "pph23Method__tarif" => ".0",
                    ),
                    "pph23" => array(
                        "pph21Method" => ".0",
                        "pph21Method__name" => ".0",
                        "pph21Method__label" => ".0",
                        "pph21Method__tarif" => ".0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            "harus_bayar_orig" => "extern_nilai2-non_pph",

            //            "pph23_nilai" => "(pph23Method__tarif/100)*harus_bayar_orig",// mati dulu
            //            "nilai_bayar" => "nilai_entry+totalCredit+pph23_nilai",
            //            "ppn_key" => "source_ppn_persen+100",
            //            "source_dpp" => "(nilai_entry*100)/ppn_key",
            "source_dpp" => "extern_nilai2",

            "valid_dpp" => "source_dpp-non_pph",
            "pph23_nilai" => "(pph23Method__tarif/100)*valid_dpp",
            "pph21_nilai" => "(pph21Method__tarif/100)*valid_dpp",

            "valid_ppn" => "source_dpp*source_ppn_persen/100",
            "biaya_jasa_23" => "biayaJasa*pph23_nilai",
            "biaya_jasa_21" => "biayaJasa*pph21_nilai",
            "biaya_jasa" => "biaya_jasa_23+biaya_jasa_21",
//            "biaya_jasa" => "(biayaJasa*pph23_nilai)+(biayaJasa*pph21_nilai)",

            "pay_out" => "sisa-(pph21_nilai+pph23_nilai+uang_muka_dipakai)+biaya_jasa",


            "sisa_uang_muka" => "uangMuka-uang_muka_dipakai",
            "payment_out" => "pay_out",
            "valid_sisa" => "(new_sisa-payment_out)",
            "sisa_tagihan" => "sisa-pph23_nilai-pph21_nilai",

            "nilai_entry" => "sisa",
            "final_sisa" => "new_sisa-nilai_bayar",
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
            "new_sisa" => "sisa-nilai_bayar-uangMuka",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "payment_out" => "nilai_entry-pph23_nilai",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
        ),
        "preProcessor" => array(
            "462" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            //                            "nilai" => "creditAmount+nilai_entry", // nilai pembayaran total
                            "nilai" => "nilai_entry", // nilai pembayaran total
                            "jenis" => ".hutang biaya",
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
                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            //                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "nilai" => "payment_out",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
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
            "462" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya usaha" => "biaya_jasa",
                            "piutang pembelian" => "-creditAmount",
                            //                            "kas" => "-payment_out",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            //                            "hutang biaya" => "-(creditAmount+nilai_entry)",
                            //                            "hutang dagang" => "-nilai_dipakai_hutang_biaya",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "hutang biaya" => "-nilai_dipakai_hutang_biaya",
                            "hutang pph23" => "pph23_nilai",
                            "hutang pph21" => "pph21_nilai",
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
                            "biaya usaha" => "biaya_jasa",
                            "piutang pembelian" => "-creditAmount",
                            //                            "kas" => "-payment_out",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            //                            "hutang biaya" => "-(creditAmount+nilai_entry)",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "hutang biaya" => "-nilai_dipakai_hutang_biaya",
                            "hutang pph23" => "pph23_nilai",
                            "hutang pph21" => "pph21_nilai",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            //                            "hutang biaya" => "-(creditAmount+nilai_entry)",
                            //                            "hutang dagang" => "-nilai_dipakai_hutang_biaya",
                            "hutang biaya" => "-nilai_dipakai_hutang_biaya",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "piutang pembelian" => "-creditAmount",
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
                            //                            "kas" => "-payment_out",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "uang muka dibayar" => "-uang_muka_dipakai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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


                    //region jurnal kedua pemindahan biaya pusat
                    //                    array(
                    //                        "comName" => "Jurnal",
                    //                        "loop" => array(
                    //                            "biaya" => "-harga_disc",
                    //                            "piutang biaya cabang" => "harga_disc",
                    ////                            "ppn in jasa" => "-valid_ppn",
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
                    //                            "biaya" => "-harga_disc",
                    //                            "piutang biaya cabang" => "harga_disc",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "jenis" => "jenisTr",
                    //                            // "transaksi_no" => "nomer",
                    //
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuAntarcabang",
                    //                        "loop" => array(
                    //                            "piutang biaya cabang" => "harga_disc",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "branchTarget",
                    //                            "extern_nama" => "branchTarget__nama",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //endregion

                    //region  jurnal cabang
                    //                    array(
                    //                        "comName" => "Jurnal",
                    //                        "loop" => array(
                    //                            "{externMain__label}" => "harga_disc",
                    //                            "hutang biaya ke pusat" => "harga_disc",
                    ////                            "ppn in jasa" => "valid_ppn",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "jenis" => "jenisTr",
                    //                            // "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //                    array(
                    //                        "comName" => "Rekening",
                    //                        "loop" => array(
                    //                            "{externMain__label}" => "harga_disc",
                    //                            "hutang biaya ke pusat" => "harga_disc",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "jenis" => "jenisTr",
                    //                            // "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuAntarcabang",
                    //                        "loop" => array(
                    //                            "hutang biaya ke pusat" => "harga_disc",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "extern_id" => "placeID",
                    //                            "extern_nama" => "placeName",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                    //                    array(
                    //                        "comName" => "Jurnal",
                    //                        "loop" => array(
                    //                            "{externMain__label}" => "-(valid_dpp+non_pph)",
                    //                            "efisiensi biaya" => "-(valid_dpp+non_pph)",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "jenis" => "jenisTr",
                    //                            // "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //                    array(
                    //                        "comName" => "Rekening",
                    //                        "loop" => array(
                    //                            "{externMain__label}" => "-(valid_dpp+non_pph)",
                    //                            "efisiensi biaya" => "-(valid_dpp+non_pph)",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "jenis" => "jenisTr",
                    //                            // "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                    //
                    //                    array(
                    //                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                    //                        "loop" => array(
                    //                            "efisiensi biaya" => "-(valid_dpp+non_pph)",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "extern_id" => "externMain",
                    //                            "extern_nama" => "externMain__label",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "biaya usaha" => "biaya_jasa",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "akun_pph_id",//id dta biaya usaha
                            "extern_nama" => "akun_pph_id",///nama data biaya usaha
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuPph",
                        "loop" => array(
                            "hutang pph23" => "pph23_nilai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_pph23",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuPph",
                        "loop" => array(
                            "hutang pph21" => "pph21_nilai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "harga" => "nilai_pph21",
                            "harga" => "pph21_nilai",
                            "extern2_id" => ".2",// diisi id bank
                            "extern2_nama" => ".supplier",// diisi nama bank
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    // pusat
                    //                    array(
                    //                        "comName" => "RekeningPembantuBiaya",
                    //                        "loop" => array(
                    //                            "biaya" => "-harga_disc",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "id",
                    //                            "extern_nama" => "name",
                    //                            "jenis" => "jenisTr",
                    //                        ),
                    //                        "srcGateName" => "items2_sum",
                    //                        "srcRawGateName" => "items2_sum",
                    //                    ),

                    // cabang
                    //                    array(
                    //                        "comName" => "{reComs}",
                    //                        "loop" => array(
                    //                            "{externMain__label}" => "harga_disc",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "cabang_nama" => "branchTarget__nama",
                    //                            "extern_id" => "dtaDetail",
                    //                            "extern_nama" => "dtaDetail__label",
                    //                            "jenis" => "jenisTr",
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),

                    //                    array(
                    //                        "comName" => "{reComs}",
                    //                        "loop" => array(
                    //                            "{externMain__label}" => "-(valid_dpp+non_pph)",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "cabang_nama" => "branchTarget__nama",
                    //                            "extern_id" => "dtaDetail",
                    //                            "extern_nama" => "dtaDetail__label",
                    //                            "jenis" => "jenisTr",
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuEfisiensiBiaya",
                    //                        "loop" => array(
                    //                            "efisiensi biaya" => "-(valid_dpp+non_pph)",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "branchTarget",
                    //                            "cabang_nama" => "branchTarget__nama",
                    //                            "extern_id" => "dtaDetail",
                    //                            "extern_nama" => "dtaDetail__label",
                    //                            "extern2_id" => "externMain",
                    //                            "extern2_nama" => "externMain__label",
                    //                            "jenis" => "jenisTr",
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "462" => array(
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
                            //                            "nilai" => "-payment_out",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "payment_out",
                            "nilai" => "kas_value",
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

                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang biaya",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => ".0",
                            "ppn" => "valid_ppn",
                            "extern_nilai2" => "valid_dpp",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    // config pembayaran hutang ke supplier (supplies)
    "487" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "additional" => array(
                    "-1" => array(
                        "add_jenis" => ".keutungan kurs",
                        "add_diskon" => "additional_value",
                        "bayar_total" => 'additional_value+credit_note_dipakai+creditValue+diskon',
                        "diskon_factor" => "0",
                        "add_diskon_selisih_kurs" => "additional_value",
                    ),
                    "1" => array(
                        "add_jenis" => ".kerugian kurs",
                        "add_diskon" => "additional_value",
                        "bayar_total" => "credit_note_dipakai+creditValue+diskon",
                        "diskon_factor" => "additional_value",
                        "add_diskon_selisih_kurs" => "-additional_value",
                    ),
                    "0" => array(
                        "additional_value" => ".0",
                        "add_jenis" => ".kerugian kurs",
                        "add_diskon" => ".0",
                        "bayar_total" => "credit_note_dipakai+creditValue+diskon",
                        "diskon_factor" => ".0",
                        "add_diskon_selisih_kurs" => ".0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            //            "totalCredit" => "creditAmount+creditValue",
            "totalCredit" => "credit_note_dipakai+creditValue",
            //            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry-diskon_factor",
            "nilai_bayar" => "bayar_total+nilai_entry+uang_muka_dipakai-diskon_factor+selisih_round",
            "additionalFactor" => "additional_value*additional",
            "nilai_dipakai" => "nilai_entry-additional_expense",
        ),
        "valuePopulator" => array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
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
            //            "new_sisa" => "sisa-additionalFactor",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "((selisih_round*-1)+additionalFactor+sisa)-(totalCredit+additional_expense+uang_muka_dipakai)",
            //            "harus_bayar" => "additionalFactor+sisa-(totalCredit+additional_expense+uang_muka_dipakai)",
            "nilai_sisa" => "additionalFactor+sisa-totalCredit+additional_expense",
            //            "new_sisa" => "sisa-nilai_bayar",
            "new_sisa" => "((selisih_round*-1)+diskon_factor+sisa)-(nilai_entry+bayar_total+uang_muka_dipakai)",
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "487" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "credit_note_dipakai", // nilai piutang pembelian total dari antisource yang dipilih...
                            "jenis" => ".piutang pembelian",
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
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
//                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai-diskon_factor+selisih_round",
                            "nilai" => "creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor+selisih_round",
                            "jenis" => ".hutang dagang",
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

                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "487" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(

                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",//ini ber isi credit note
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
                            "biaya lain lain" => "additional_expense",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            // "credit note" => "-diskon",//dgeser 29 desember 2021 nyasar
//                            "{add_jenis}" => "add_diskon",
                            "laba(rugi) selisih kurs" => "add_diskon_selisih_kurs",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "laba(rugi) selisih adjustment" => "nilai_sisa_hutang_dagang",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "piutang pembelian" => "-creditAmount",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
                            "biaya lain lain" => "additional_expense",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            // "credit note" => "-credit_note_dipakai",
//                            "{add_jenis}" => "add_diskon",
                            "laba(rugi) selisih kurs" => "add_diskon_selisih_kurs",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "laba(rugi) selisih adjustment" => "nilai_sisa_hutang_dagang",
                            "selisih pembulatan" => "selisih_round*-1",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(

                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "credit note" => "-diskon",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "uang muka dibayar" => "-uang_muka_dipakai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "hutang lain ppv" => "-additional_expense",
                            "laba lain lain" => "-additional_expense",
                            "biaya lain lain" => "-additional_expense",
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
//                            "hutang lain ppv" => "-additional_expense",
                            "laba lain lain" => "-additional_expense",
                            "biaya lain lain" => "-additional_expense",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "-additional_expense",
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

                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "487" => array(
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
                            "label" => ".hutang dagang",
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
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

                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang dagang",
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
    // config pembayaran hutang ke supplier (finish goods)
    "489" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "additional" => array(
                    "-1" => array(
                        "add_jenis" => ".keutungan kurs",
                        "add_diskon" => "additional_value",
                        "bayar_total" => 'additional_value+credit_note_dipakai+creditValue+diskon+selisih_round',
                        "diskon_factor" => ".0",
                        "add_diskon_selisih_kurs" => "additional_value",
                    ),
                    "1" => array(
                        "add_jenis" => ".kerugian kurs",
                        "add_diskon" => "additional_value",
                        "bayar_total" => "credit_note_dipakai+creditValue+diskon+selisih_round",
                        "diskon_factor" => "additional_value",
                        "add_diskon_selisih_kurs" => "-additional_value",
                    ),
                    "0" => array(
                        "additional_value" => ".0",
                        "add_jenis" => ".kerugian kurs",
                        "add_diskon" => ".0",
                        "bayar_total" => "credit_note_dipakai+creditValue+diskon+selisih_round",
                        "diskon_factor" => ".0",
                        "add_diskon_selisih_kurs" => ".0",
                    ),
                ),


                //                "cashMethode" => array(
                //                    "rekening_koran" => array(
                //                        "rekening_koran_value" => "nilai_entry",
                //                        "kas_value" => "0",
                //                    ),
                //                    "reguler" => array(
                //                        "rekening_koran_value" => "0",
                //                        "kas_value" => "nilai_entry",
                //                    ),
                //                ),
            ),
        ),
        "valueBuilders" => array(
            //            "totalCredit" => "creditAmount+creditValue",
            "totalCredit" => "credit_note_dipakai+creditValue",
            //            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry-diskon_factor",
            "nilai_bayar" => "bayar_total+nilai_entry+uang_muka_dipakai-diskon_factor",
            //            "nilai_bayar" => "bayar_total+nilai_entry+uang_muka_dipakai",
            "additionalFactor" => "additional_value*additional",
            "nilai_dipakai" => "nilai_entry-additional_expense",
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
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalBuilders" => array(//==per-item
            //            "new_sisa" => "sisa-(nilai_entry+bayar_total+uang_muka_dipakai)-diskon_factor",
            //            "new_sisa" => "sisa-bayar_total",
            //            "new_sisa" => "sisa-additionalFactor",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "((selisih_round*-1)+additionalFactor+sisa+additional_expense)-(totalCredit+uang_muka_dipakai)",
            "nilai_sisa" => "additionalFactor+sisa+additional_expense-totalCredit",
            //            "new_sisa" => "sisa-(nilai_entry+bayar_total+uang_muka_dipakai)-diskon_factor",
            //            "new_sisa" => "(diskon_factor+sisa)-(nilai_entry+bayar_total+uang_muka_dipakai+diskon_factor)",
            "new_sisa" => "((selisih_round*-1)+diskon_factor+sisa)-(nilai_entry+bayar_total+uang_muka_dipakai)",
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "489" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "credit_note_dipakai", // nilai piutang pembelian total dari antisource yang dipilih...
                            "jenis" => ".piutang pembelian",
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
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            // "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "nilai" => "creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor", // piutang pembelian sudah masuk ke bayar total
                            "jenis" => ".hutang dagang",
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

                    //                    array(
                    //                        "comName" => "RekeningValue",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "cash_account",
                    //                            "extern_nama" => "cash_account__nama",
                    //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                    //                            "jenis" => ".kas",
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


                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_entry+diskon_factor",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "489" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "piutang pembelian" => "-creditAmount",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
                            "biaya lain lain" => "additional_expense",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "credit note" => "-diskon",
//                            "{add_jenis}" => "add_diskon",
                            "laba(rugi) selisih kurs" => "add_diskon_selisih_kurs",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "laba(rugi) selisih adjustment" => "nilai_sisa_hutang_dagang",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "piutang pembelian" => "-creditAmount",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
                            "biaya lain lain" => "additional_expense",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "credit note" => "-diskon",
//                            "{add_jenis}" => "add_diskon",
                            "laba(rugi) selisih kurs" => "add_diskon_selisih_kurs",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "laba(rugi) selisih adjustment" => "nilai_sisa_hutang_dagang",
                            "selisih pembulatan" => "selisih_round*-1",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            //                            "hutang dagang" => "-(creditAmount+creditValue+nilai_dipakai)",
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            //                            "piutang pembelian" => "-creditAmount",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "hutang lain ppv" => "-additional_expense",
                            "laba lain lain" => "-additional_expense",
                            "biaya lain lain" => "-additional_expense",
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
//                            "hutang lain ppv" => "-additional_expense",
                            "laba lain lain" => "-additional_expense",
                            "biaya lain lain" => "-additional_expense",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "-additional_expense",
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

                    //                    array(
                    //                        "comName" => "RekeningPembantuBiayaMain",
                    //                        "loop" => array(
                    ////                            "hutang lain ppv" => "-valid_expense",
                    //                            "biaya" => "additional_expense",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "jenis" => "jenisTr",
                    //                            "extern_id" => ".37",
                    //                            "extern_nama" => ".biaya lain lain",
                    //                            "transaksi_no" => "nomer",
                    //                            "transaksi_id" => "transaksi_id",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "uang muka dibayar" => "-uang_muka_dipakai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
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
            "489" => array(
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
                            "label" => ".piutang pembelian",
                            "terbayar" => "nilai_dipakai_piutang_pembelian",
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
                            "nilai" => "-kas_value",
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
                            "nilai" => "kas_value",
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
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang dagang",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //                    array(
                    //                        "comName"        => "CreditNote",
                    //                        "loop"           => array(),
                    //                        "static"         => array(
                    //                            "cabang_id"    => "placeID",
                    //                            "extern_id"    => "pihakID",
                    //                            "extern_nama"  => "pihakName",
                    //                            "label"        => ".credit note",
                    ////                            "jenis" => "jenis",
                    ////                            "reference_jenis" => "jenis",
                    //                            "target_jenis" => ".489",
                    //                            "transaksi_id" => "id",
                    ////                            "amount"     => "creditValue",
                    //                            "used"     => "creditValue",
                    //                            "remain"         => ".0",
                    //                            "oleh_id"         => "olehID",
                    //                            "oleh_nama"         => "olehName",
                    //                            "mode" => ".update",
                    //                        ),
                    //                        "reversable"     => true,
                    //                        "srcGateName"    => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                ),
            ),
        ),
    ),
    //  config pembayaran expense/biaya usaha
    "477" => array(
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
            //            "hpp_sumber" => "hpp",
            //            "harga"      => "harga",
            "nilai_bayar" => "nilai_entry+selisih_round",
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
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "477" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_bayar",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "jenis_label" => "jenisTrName", "div_id" => "divID", "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
            //            "detail2"         => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "harga",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
            //            "rsltItems"       => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "hpp",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "expense",
            ),
        ),
        "components" => array(
            "477" => array(
                "master" => array(
                    //region DC/center
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "piutang biaya cabang" => "nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "piutang biaya cabang" => "nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
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
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    //endregion

                    //region branch
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang biaya" => "-nilai_bayar",
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
                            "hutang biaya" => "-nilai_bayar",
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
                            "hutang biaya" => "-nilai_bayar",
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
                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "477" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            //                            "extern_id" => "id",
                            //                            "extern_nama" => "name",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang biaya usaha",
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
    "1477" => array(
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
            //            "hpp_sumber" => "hpp",
            //            "harga"      => "harga",
            "nilai_bayar" => "nilai_entry+selisih_round",
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
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "1477" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "jenis_label" => "jenisTrName", "div_id" => "divID", "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
            //            "detail2"         => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "harga",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
            //            "rsltItems"       => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "hpp",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "expense",
            ),
        ),
        "components" => array(
            "1477" => array(
                "master" => array(
                    //region DC/center
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
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

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    //endregion


                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1477" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            //                            "extern_id" => "id",
                            //                            "extern_nama" => "name",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang biaya usaha",
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
    "6475" => array(
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
            //            "hpp_sumber" => "hpp",
            //            "harga"      => "harga",
            "nilai_bayar" => "nilai_entry+selisih_round",
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
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "7475" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_bayar",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "jenis_label" => "jenisTrName", "div_id" => "divID", "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
            //            "detail2"         => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "harga",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
            //            "rsltItems"       => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "hpp",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "expense",
            ),
        ),
        "components" => array(
            "7475" => array(
                "master" => array(
                    //region DC/center
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
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

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    //endregion

                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "7475" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            //                            "extern_id" => "id",
                            //                            "extern_nama" => "name",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang biaya",
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
    //config pembayaran hutang kepemegang saham
    "4448" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "subtotal" => "nilai_bayar",
            ),
            "master_dependent" => array(),
        ),
        "valueBuilders" => array( //main
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "bayar_total+nilai_entry-diskon_factor",
            "additionalFactor" => "additional_value*additional",
            "nilai_dipakai" => "nilai_entry-additional_expense",
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
            "harus_bayar" => "additionalFactor+sisa-totalCredit+additional_expense",
        ),
        "preProcessor" => array(
            "4448" => array(
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
                            "nilai" => "nilai_dipakai",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "4448" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang ke pemegang saham" => "-nilai_dipakai",
                            //                            "kas" => "-nilai_dipakai",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
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
                            "hutang ke pemegang saham" => "-nilai_dipakai",
                            //                            "kas" => "-nilai_dipakai",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuHutangSaham",
                        "loop" => array(
                            "hutang ke pemegang saham" => "-nilai_bayar",
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
                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                ),
                "detail" => array(
                    //                    array(
                    //                        "comName" => "RekeningPembantuHutangSahamItem",
                    //                        "loop" => array(
                    //                            "hutang ke pemegang saham" => "-nilai_bayar",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "pihakID",
                    //                            "extern_nama" => "pihakName",
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
            "4448" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "-kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem2", // pake ini karna bisa multi vendor
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang ke pemegang saham",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //                    array(
                    //                        "comName" => "PaymentSourceDetail",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "cabangName",
                    //                            "extern_id" => "id",
                    //                            "extern_nama" => "name",
                    //                            "label" => ".hutang ke pemegang saham",
                    //                            "jenis" => "jenisTr",
                    //                            "target_jenis" => ".4448",
                    //                            "transaksi_id" => "transaksi_id",
                    //                            "terbayar" => "0",
                    //                            "tagihan" => "harga",
                    //                            "sisa" => "harga",
                    //                            "nomer" =>"nomer",
                    //                            "reference_jenis" =>"jenisTr",
                    //                            "extern_nilai_2" =>"harga",
                    //                            "oleh_id"=>"olehID",
                    //                            "oleh_nama" =>"olehName",
                    //                        ),
                    //                        "reversable" => true,
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                ),
            ),
        ),
    ),
    // config pembayaran hutang gaji ke cabang
    "1485" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|place2ID",
            "stepCode|placeID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            "nilai_bayar" => "nilai_entry+totalCredit",
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
            //            "nilai_bayar" => "nilai_entry+totalCredit",
        ),

        "preProcessor" => array(
            "1485" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "transaksi_nilai" => "nett",
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
                "produk_kode" => "produk_kode",
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
            "1485" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang gaji" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
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
                            "hutang gaji" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
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
                            "hutang gaji" => "-nilai_entry",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1485" => array(
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
                            "nilai" => "-kas_value",
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
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang gaji",
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
    //config pembayaran expense/biaya umum
    "475" => array(
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
            //            "hpp_sumber" => "hpp",
            //            "harga"      => "harga",
            "nilai_bayar" => "nilai_entry+selisih_round",
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
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "475" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_bayar",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "jenis_label" => "jenisTrName", "div_id" => "divID", "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
            //            "detail2"         => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "harga",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
            //            "rsltItems"       => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "hpp",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "expense",
            ),
        ),
        "components" => array(
            "475" => array(
                "master" => array(
                    //region DC/center
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "piutang biaya cabang" => "nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "piutang biaya cabang" => "nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
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
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    //endregion

                    //region branch
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang biaya" => "-nilai_bayar",
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
                            "hutang biaya" => "-nilai_bayar",
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
                            "hutang biaya" => "-nilai_bayar",
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
                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "475" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            //                            "extern_id" => "id",
                            //                            "extern_nama" => "name",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang biaya umum",
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
    "1475" => array(
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
            //            "hpp_sumber" => "hpp",
            //            "harga"      => "harga",
            "nilai_bayar" => "nilai_entry+selisih_round",
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
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "1475" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_bayar",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "jenis_label" => "jenisTrName", "div_id" => "divID", "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
            //            "detail2"         => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "harga",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
            //            "rsltItems"       => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "hpp",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "expense",
            ),
        ),
        "components" => array(
            "1475" => array(
                "master" => array(
                    //region DC/center
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
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

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    //endregion

                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1475" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            //                            "nilai" => "-kas_value",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            //                            "extern_id" => "id",
                            //                            "extern_nama" => "name",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang biaya umum",
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
    //rekening koran payable
    "4440" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",
            "stepCode|placeID|pihakID",
        ),
        "formatNota" => "stepCode|placeID|pihakID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "supplierID" => "pihakID",
                //                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                //                "additional" => array(
                //                    "-1" => array(
                //                        "add_jenis" => ".keutungan kurs",
                //                        "add_diskon" => "additional_value",
                ////                        "bayar_total" => 'additional_value+creditAmount+diskon+nilai_entry',
                //                        "bayar_total" => 'additional_value+creditAmount+diskon',
                //                        "diskon_factor" => "0",
                //
                //                    ),
                //                    "1" => array(
                //                        "add_jenis" => ".kerugian kurs",
                //                        "add_diskon" => "additional_value",
                ////                        "bayar_total" => "creditAmount+diskon+nilai_entry",
                //                        "bayar_total" => "creditAmount+diskon",
                //                        "diskon_factor" => "additional_value",
                //
                //                    ),
                //                    "0" => array(
                //                        "additional_value" => ".0",
                //                        "add_jenis" => ".kerugian kurs",
                //                        "add_diskon" => ".0",
                ////                        "bayar_total" => "creditAmount+diskon+nilai_entry",
                //                        "bayar_total" => "creditAmount+diskon",
                //                        "diskon_factor" => ".0",
                //
                //                    ),
                //                ),
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry-diskon_factor+selisih_round",
            "additionalFactor" => "additional_value*additional",
            "nilai_dipakai" => "nilai_entry-additional_expense",
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
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
            "selisih_round" => "sisa-nilai_round",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "4440" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang bank" => "-nilai_entry",
                            "kas" => "-nilai_entry",
                            "selisih pembulatan" => "selisih_round",
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
                            "hutang bank" => "-nilai_entry",
                            "kas" => "-nilai_entry",
                            "selisih pembulatan" => "selisih_round",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBank",
                        "loop" => array(
                            "hutang bank" => "-nilai_entry",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pairPihakID",
                            "extern_nama" => "pairPihakName",
                            "jenis" => "jenisTr",
                            "extern2_id" => "pairPihakID",//id folder rekening koran
                            "extern2_nama" => "pairPihakID",//label folder rekening koran
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//rekening pembantu level 1
                    array(
                        "comName" => "RekeningPembantuRelasiRekeningKoran",//rekening pembantu level 2
                        "loop" => array(
                            //                            "h" => "harga",//
                            "hutang bank" => "-nilai_entry",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",//id relasi rekening koran
                            "extern2_id" => "pairPihakID",//id folder rekening koran BRI
                            "extern2_nama" => "pairPihakName",//label folder rekening koran
                            "extern_nama" => ".rekening koran",//lbel relasi rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),//rekening pembantu level 2

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
                            // "transaksi_no" => "nomer",
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
                            "rekening koran" => "-nilai_bayar",//
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",//id rekening koran BRI xxx
                            "extern_nama" => "pihakName",//lbel rekening koran
                            "extern2_id" => "pair_pihak_id",//lbel rekening koran BRI
                            "extern2_nama" => "pair_pihak_name",//lbel rekening koran
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "produk_nilai" => "nilai_bayar",
                            "produk_qty" => ".-1",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "4440" => array(
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
                            "state" => ".payment",
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
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".hutang bank",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
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
                            "label" => ".hutang bank",
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
    "1487" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|place2ID",
            "stepCode|placeID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            // "nilai_bayar" => "nilai_entry+totalCredit",
            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya+selisih_round",
        ),
        "valuePopulator" => array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
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
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "1487" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
                "transaksi_nilai" => "nett",
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
                "produk_kode" => "produk_kode",
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
            "1487" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang bpjs" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "selisih pembulatan" => "selisih_round",
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
                            "hutang bpjs" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "selisih pembulatan" => "selisih_round",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //                    array(
                    //                        "comName" => "RekeningPembantuAntarcabang",
                    //                        "loop" => array(
                    //                            "hutang gaji" => "-nilai_entry",
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening koran
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
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1487" => array(
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
                            "nilai" => "-kas_value",
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
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang bpjs",
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
    //config pembayaran hutang ke pihak lain
    "4411" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "subtotal" => "nilai_bayar",
            ),
            "master_dependent" => array(),
        ),
        "valueBuilders" => array( //main
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "bayar_total+nilai_entry-diskon_factor+selisih_round",
            "additionalFactor" => "additional_value*additional",
            "nilai_dipakai" => "nilai_entry-additional_expense",
        ),
        "valuePopulator" => array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
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
            "harus_bayar" => "additionalFactor+sisa-totalCredit+additional_expense",
            "selisih_round" => "sisa-nilai_round",
        ),
        "preProcessor" => array(
            "4411" => array(
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
                            "nilai" => "nilai_dipakai",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "4411" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang ke pihak lain" => "-nilai_dipakai",
                            //                            "kas" => "-nilai_dipakai",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            //                            "selisih pembulatan" => "selisih_round*-1",
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
                            "hutang ke pihak lain" => "-nilai_dipakai",
                            //                            "kas" => "-nilai_dipakai",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            //                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuHutangPihakLain",
                        "loop" => array(
                            "hutang ke pihak lain" => "-nilai_bayar",
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

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                ),
                "detail" => array(
                    //                    array(
                    //                        "comName" => "RekeningPembantuHutangSahamItem",
                    //                        "loop" => array(
                    //                            "hutang ke pemegang saham" => "-nilai_bayar",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "pihakID",
                    //                            "extern_nama" => "pihakName",
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
            "4411" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem2", // pake ini karna bisa multi vendor
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang ke pihak lain",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //                    array(
                    //                        "comName" => "PaymentSourceDetail",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "cabangName",
                    //                            "extern_id" => "id",
                    //                            "extern_nama" => "name",
                    //                            "label" => ".hutang ke pemegang saham",
                    //                            "jenis" => "jenisTr",
                    //                            "target_jenis" => ".4448",
                    //                            "transaksi_id" => "transaksi_id",
                    //                            "terbayar" => "0",
                    //                            "tagihan" => "harga",
                    //                            "sisa" => "harga",
                    //                            "nomer" =>"nomer",
                    //                            "reference_jenis" =>"jenisTr",
                    //                            "extern_nilai_2" =>"harga",
                    //                            "oleh_id"=>"olehID",
                    //                            "oleh_nama" =>"olehName",
                    //                        ),
                    //                        "reversable" => true,
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                ),
            ),
        ),
    ),
    //config niaya jasa /imbalan jasa
    "2119" => array(
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
                //                "unitPrice_ui" =>"extern_nilai2",
            ),
            "master_dependent" => array(
                "pihakMainName" => array(
                    "hutang pph23" => array(
                        "nilai_pph23" => "pph_23",
                        "nilai_pph21" => 0,
                    ),
                    "hutang pph21" => array(
                        "nilai_pph23" => 0,
                        "nilai_pph21" => "pph_23",
                    ),

                ),

            ),
        ),
        "valueBuilders" => array(
            //            "hpp_sumber" => "hpp",
            //            "harga"      => "harga",
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
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "selisih_round" => "sisa-nilai_round",
        ),
        "preProcessor" => array(
            "2119" => array(
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
                            "nilai" => "nilai_bayar",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "jenis_label" => "jenisTrName", "div_id" => "divID", "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
            //            "detail2"         => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "harga",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
            //            "rsltItems"       => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "hpp",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "expense",
            ),
        ),
        "components" => array(
            "2119" => array(
                "master" => array(
                    //region DC/center
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_bayar",
                            //                            "selisih pembulatan" =>"selisih_round",
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
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_bayar",
                            //                            "selisih pembulatan" =>"selisih_round",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
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
                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    //endregion

                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "2119" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            //                            "extern_id" => "id",
                            //                            "extern_nama" => "name",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang imbalan jasa",
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
    //payment pph 29
    "5684" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry-diskon_factor",
            "nilai_dipakai" => "nilai_entry",
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
            "harus_bayar" => "sisa-totalCredit",
        ),

        "preProcessor" => array(
            "5684" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "5684" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang pph29" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",

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
                            "hutang pph29" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "5684" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang pph 29",
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
    //payment pph 23
    "115" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry-diskon_factor",
            "nilai_dipakai" => "nilai_entry",
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
            "harus_bayar" => "sisa-totalCredit",
        ),

        "preProcessor" => array(
            "115" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "115" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang pph23" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",

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
                            "hutang pph23" => "-nilai_entry",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "hutang pph23" => "-nilai_entry",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_entry",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "115" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang pph23",
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

    //set0r ppn bulanan
    "114" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(),
        ),
        "valueBuilders" => array(
            "totalCredit" => "credit_note_dipakai+creditValue",

            //            "additionalFactor" => "additional_value*additional",
            "nilai_entry" => "(sisa+denda_nilai)-(src_harga+nilai_deposit_src_dipakai)",
            "nilai_bayar" => "bayar_total+src_harga+denda_nilai+nilai_entry+nilai_deposit_src_dipakai",
            "nilai_dipakai" => "src_harga+denda_nilai",
            "ppn_masukan" => "src_harga",
            "saldo_deposit" => "ppn_masukan-(sisa+denda_nilai+nilai_deposit_src_dipakai)",
        ),
        "valuePopulator" => array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
        ),
        "valueReplaceCalculate" => array(
            "nilai_entry", "saldo_deposit", "harus_bayar"
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
            //            "new_sisa" => "sisa-(nilai_entry+bayar_total+uang_muka_dipakai)-diskon_factor",
            //            "new_sisa" => "sisa-bayar_total",
            //            "new_sisa" => "sisa-additionalFactor",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "(sisa+denda_nilai)-(src_harga+nilai_deposit_src_dipakai)",
            "nilai_ppn" => "harus_bayar",
            "nilai_sisa" => "sisa",
            //            "new_sisa" => "sisa-(nilai_entry+bayar_total+uang_muka_dipakai)-diskon_factor",
            "nilai_sisa_src" => "(sisa+denda_nilai)-src_harga",
            "new_sisa" => "(sisa+denda_nilai)-nilai_bayar",

        ),
        "preProcessor" => array(
            "114" => array(
                "master" => array(

                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "jenis" => ".hutang dagang",
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

                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "114" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "piutang pembelian" => "-creditAmount",
                            "ppn in realisasi" => "-src_harga",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "beban lain lain" => "denda_nilai",
                            "ppn out" => "-subtotal",
                            "deposit pajak" => "saldo_deposit-nilai_deposit_src_dipakai",

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
                            //                            "piutang pembelian" => "-creditAmount",
                            "ppn in realisasi" => "-src_harga",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "beban lain lain" => "denda_nilai",
                            "ppn out" => "-subtotal",
                            //                            "deposit pajak" =>"saldo_deposit",
                            "deposit pajak" => "saldo_deposit-nilai_deposit_src_dipakai",

                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                    //endrekening koran
                    //kas
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening pembantu beban lain lain
                    array(
                        "comName" => "RekeningPembantuBebanLainLain",
                        "loop" => array(
                            "beban lain lain" => "denda_nilai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".11",// diisi id jenis biaya
                            "extern_nama" => ".Beban Sanksi Pajak",// diisi nama bank
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
            "114" => array(
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
                            "nilai" => "-kas_value",
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
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "PaymentDeposit",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "label" => ".desposit pajak",
                            "jenis" => ".00001",
                            "id" => "deposit_dipakai__id",
                            "terbayar" => "nilai_deposit_src_dipakai",

                            //                            "sisa" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(
                    //ppn keluaran
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".ppn out",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //ppn masukan
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "label" => ".ppn realisasi",
                            "target_jenis" => ".0000",
                            "transaksi_id" => "refID",
                            "terbayar" => "subtotal",
                            "sisa" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "itemSrc",
                        "srcRawGateName" => "itemSrc",
                    ),
                ),

            ),
        ),
    ),
    //setor hutang pph ps4(2)
    "1120" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry-diskon_factor",
            "nilai_dipakai" => "nilai_entry",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "1120" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang pph4 ayat 2" => "-nilai_entry",
                            "kas" => "-nilai_entry",

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
                            "hutang pph4 ayat 2" => "-nilai_entry",
                            "kas" => "-nilai_entry",
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
                            "kas" => "-nilai_entry",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
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
            "1120" => array(
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
                            "state" => ".payment",
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
                            "label" => ".hutang pph4 ayat 2",
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
    //payment objek pajak
    "5682" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|supplierID",
            //            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),

        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            "nilai_bayar" => "nilai_entry+totalCredit",

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
            //            "new_sisa" => "sisa-additionalFactor",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
        ),

        "preProcessor" => array(
            "5682" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nilai_entry",
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
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
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
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk_source",
            ),
        ),
        "components" => array(
            "5682" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "piutang pembelian" => "-creditAmount",
                            //                            "hutang lain ppv" => "-nilai_bayar",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "pph22" => "nilai_entry",
                            //                            "credit note" => "-diskon",
                            //                            "{add_jenis}" => "add_diskon",
                            //                            "diskon" => "",
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
                            //                            "piutang pembelian" => "-creditAmount",
                            //                            "hutang lain ppv" => "-nilai_bayar",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "pph22" => "nilai_entry",
                            //                            "credit note" => "-diskon",
                            //                            "{add_jenis}" => "add_diskon",
                            //                            "diskon" => "",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "hutang dagang" => "-(creditAmount+creditValue+nilai_bayar)",
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
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "piutang pembelian" => "-creditAmount",
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
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "credit note" => "-diskon",
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiaya",
                        "loop" => array(
                            "pph22" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "5682" => array(
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
                            "nilai" => "-kas_value",
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
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".objek pajak",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "id",
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
    //service A/P payment pusat
    "1462" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "source_ppn_persen" => "(ppn/extern_nilai2)*100",
                "pph_value" => "pph23_nilai",

            ),
            "master_dependent" => array(
                "pphGateId" => array(
                    "1" => array(
                        "akun_pph_id" => ".37",
                        "akun_pph_label" => ".pph ps 23",
                    ),//dipotong
                    "2" => array(
                        "akun_pph_id" => ".38",
                        "akun_pph_label" => ".biaya pph ps. 23",
                    ),//tidak dipotong

                ),
            ),

        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            "harus_bayar_orig" => "extern_nilai2-non_pph",

            //            "pph23_nilai" => "(pph23Method__tarif/100)*harus_bayar_orig",// mati dulu
            //            "nilai_bayar" => "nilai_entry+totalCredit+pph23_nilai",

            //            "ppn_key" => "source_ppn_persen+100",
            //            "source_dpp" => "(nilai_entry*100)/ppn_key",


            "valid_dpp" => "extern_nilai2-non_pph",
            "pph23_nilai" => "(pph23Method__tarif/100)*valid_dpp",

            "valid_ppn" => "source_dpp*source_ppn_persen/100",
            "biaya_jasa" => "biayaJasa*pph23_nilai",
            // "pay_out" => "sisa-(pph23_nilai+uang_muka_dipakai)+biaya_jasa",
            "pay_out" => "(sisa+biaya_jasa)-(pph23_nilai+uang_muka_dipakai)",
            "sisa_uang_muka" => "uangMuka-uang_muka_dipakai",
            "payment_out" => "pay_out",
            "nilai_entry" => "sisa",
            "nilai_bayar" => "nilai_entry+totalCredit",

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
            "new_sisa" => "sisa-nilai_bayar-uangMuka",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "payment_out" => "nilai_entry-pph23_nilai",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
        ),
        "preProcessor" => array(
            "1462" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            //                            "nilai" => "creditAmount+nilai_entry", // nilai pembayaran total
                            "nilai" => "nilai_bayar", // nilai pembayaran total
                            "jenis" => ".hutang biaya",
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

                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "payment_out",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
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
            "1462" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "piutang pembelian" => "-creditAmount",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_dipakai_hutang_biaya",
                            "hutang pph23" => "pph23_nilai",
//                            "biaya import" => "-harga",
//                            "hutang lain ppv" => "-harga",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "biaya usaha" => "biaya_jasa"
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
                            "piutang pembelian" => "-creditAmount",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "hutang biaya" => "-nilai_dipakai_hutang_biaya",
                            "hutang pph23" => "pph23_nilai",
//                            "biaya import" => "-harga",
//                            "hutang lain ppv" => "-harga",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "biaya usaha" => "biaya_jasa"
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            //                            "hutang biaya" => "-(creditAmount+nilai_entry)",
                            //                            "hutang dagang" => "-nilai_dipakai_hutang_biaya",
                            "hutang biaya" => "-nilai_dipakai_hutang_biaya",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "piutang pembelian" => "-creditAmount",
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
                            //                            "kas" => "-payment_out",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "uang muka dibayar" => "-uang_muka_dipakai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
//                    array(
//                        "comName" => "RekeningPembantuBiayaImport",
//                        "loop" => array(
//                            //                            "biaya import" => "-(non_pph+valid_dpp)",//ini di ofkan nyasar over biaya jadinya non pph dari gerbang main tanpa kalkulasi di items
//                            "biaya import" => "-harga",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
                    array(
                        "comName" => "RekeningPembantuPph",
                        "loop" => array(
                            "hutang pph23" => "pph23_nilai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "pph23_nilai",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "biaya usaha" => "biaya_jasa",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "akun_pph_id",//id dta biaya usaha
                            "extern_nama" => "akun_pph_label",///nama data biaya usaha
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "1462" => array(
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
                            //                            "nilai" => "-payment_out",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "payment_out",
                            "nilai" => "kas_value",
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

                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang biaya",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                            "ppn" => "valid_ppn",
                            "extern_nilai2" => "valid_dpp",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    //  config pembayaran expense/biaya produksi
    "476" => array(
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
            //            "hpp_sumber" => "hpp",
            //            "harga"      => "harga",
            "nilai_bayar" => "nilai_entry+selisih_round",
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
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalBuilders" => array(//==per-item
            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
            "selisih_round" => "sisa-nilai_round",
        ),

        "preProcessor" => array(
            "476" => array(
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
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_bayar",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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
                "jenis_label" => "jenisTrName", "div_id" => "divID", "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
            //            "detail2"         => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "harga",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
            //            "rsltItems"       => array(
            //                "dtime"          => "dtime",
            //                "produk_id"      => "id",
            //                "produk_kode"    => "code",
            //                "produk_label"   => "label",
            //                "produk_nama"    => "name",
            //                "produk_ord_jml" => "jml",
            //                "produk_ord_hrg" => "hpp",
            //                "hpp"            => "harga",
            //                "satuan"         => "satuan",
            //            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "expense",
            ),
        ),
        "components" => array(
            "476" => array(
                "master" => array(
                    //region DC/center
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "piutang biaya cabang" => "nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_bayar",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "piutang biaya cabang" => "nilai_bayar",
                            "selisih pembulatan" => "selisih_round*-1",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
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
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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

                    //endregion

                    //region branch
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "hutang biaya" => "-nilai_bayar",
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
                            "hutang biaya" => "-nilai_bayar",
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
                            "hutang biaya" => "-nilai_bayar",
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
                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "476" => array(
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            //                            "extern_id" => "id",
                            //                            "extern_nama" => "name",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang biaya produksi",
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
    //config A/P payment import
    "4891" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(
                //===sumber nilai berupa rincian
                "nilai_bayar_nota" => "valas_nilai_bayar*extern_nilai2",
            ),
            "master_dependent" => array(
                //                "cashMethodeOption" => array(
                //                    "cash" => array(
                //                        "kurs__exchange" => ".1",
                //                        "sisa_ui" => "sisa",
                //                        "idr_nilai_entry" => "nilai_entry",
                //                        "valas_nilai_entry" => "pembayaran_total/kurs_actual",
                //                    ),
                //                    "valas" => array(
                //                        "kurs__exchange" => ".1",
                //                        "sisa_ui" => "sisa",
                //                        "valas_sisa_ui" => "sisa_valas",
                //                        "idr_nilai_entry" => "exchange_entry",
                //                        "valas_nilai_locker" => "valas_nilai_entry",
                //                    ),
                //                    "none" => array(
                //                        "kurs__exchange" => ".1",
                //                        "sisa_ui" => "sisa",
                //                        "idr_nilai_entry" => ".0",
                ////                        "valas_nilai_entry" => ".0",
                //                    ),
                //                ),
                "additional" => array(
                    "-1" => array(
                        "add_jenis" => ".keutungan kurs",
                        //                        "additional_value_total" => "additional_value_valas+additional_value",
                        "add_diskon" => "additional_value",
//                        "add_diskon_selisih_kurs" => "additional_value",

                        "bayar_total" => "additional_value+credit_note_dipakai_nilai+creditValue+diskon",
                        "diskon_factor" => ".0",
                        "pembayaran_total_kas" => "nilai_entry",
                        "pembayaran_total" => "nilai_entry",
                        "nilai_bayar_orig" => "(uang_muka_valas_hpp+valas_harga+pembayaran_total+credit_note_dipakai_nilai)+additional_value_total",
                        "nilai_entry_additional" => "nilai_entry",
                    ),
                    "1" => array(
                        "add_jenis" => ".kerugian kurs",
                        //                        "additional_value_total" => "additional_value_valas+additional_value",
                        "add_diskon" => "additional_value",
//                        "add_diskon_selisih_kurs" => "-additional_value",

                        "bayar_total" => "credit_note_dipakai_nilai+creditValue+diskon",
                        "diskon_factor" => "additional_value",
                        "pembayaran_total_kas" => "(nilai_entry+additional_value)",
                        "pembayaran_total" => "nilai_entry+additional_value",
                        "nilai_bayar_orig" => "(uang_muka_valas_hpp+valas_harga+pembayaran_total+credit_note_dipakai_nilai)-additional_value_total",
                        "nilai_entry_additional" => "nilai_entry+additional_value",
                    ),
                    "0" => array(
                        "additional_value" => ".0",
                        "add_jenis" => ".kerugian kurs",
                        //                        "additional_value_total" => "additional_value_valas+additional_value",
                        "add_diskon" => ".0",
//                        "add_diskon_selisih_kurs" => ".0",

                        "bayar_total" => "credit_note_dipakai_nilai+creditValue+diskon",
                        "diskon_factor" => ".0",
                        "pembayaran_total_kas" => "nilai_entry",
                        "pembayaran_total" => "nilai_entry",
                        "nilai_bayar_orig" => "(uang_muka_valas_hpp+valas_harga+pembayaran_total+credit_note_dipakai_nilai)",
                        "nilai_entry_additional" => "nilai_entry",
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
            "uang_muka_stok_valas" => "uang_muka_valas_dipakai+valas_nilai_stock",
            "uang_muka_stok_valas_exchange" => "valas_harga+uang_muka_valas_hpp",
            "valas_nilai_entry" => "nilai_entry_additional/kurs_actual",
            //            "nilai_bayar" => "bayar_total+idr_nilai_entry",
            "nilai_bayar" => "nilai_bayar_orig",
            "valas_nilai_bayar" => "uang_muka_valas_dipakai+valas_nilai_stock+valas_nilai_entry+credit_note_dipakai",
            //-------------------
            "ppv" => "biaya_transfer+biaya_lain_lain_novalas",
            "biaya_lain_total" => "biaya_lain_lain_novalas",
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
            "valas_nilai_bayar" => array(
                "mainSrc" => array(
                    "key" => "valas_nilai_bayar",
                ),
                "itemTarget" => array(
                    "key" => "valas_nilai_bayar",
                    "maxAmountSrc" => "sisa_valas",
                ),
            ),
            "uang_muka_stok_valas" => array(
                "mainSrc" => array(
                    "key" => "uang_muka_stok_valas",
                ),
                "itemTarget" => array(
                    "key" => "uang_muka_stok_valas",
                    "maxAmountSrc" => "sisa_valas",
                ),
            ),
        ),
        "additionalBuilders" => array(
            //==per-item
            //            "new_sisa" => "sisa-(nilai_entry+bayar_total+uang_muka_dipakai)-diskon_factor",
            //            "new_sisa" => "sisa-bayar_total",

            "new_sisa_ui" => "sisa-nilai_bayar_nota",
            "new_sisa" => "sisa-nilai_bayar",
            "valas_new_sisa" => "sisa_valas-valas_nilai_bayar",
            //
        ),
        "additionalMainBuilders" => array(
            //==per-item
            //---
            "harus_bayar" => "additionalFactor+sisa+additional_expense-(totalCredit+uang_muka_dipakai)",
            "nilai_sisa" => "additionalFactor+sisa+additional_expense-totalCredit",
            "new_sisa" => "sisa-(nilai_entry+bayar_total+uang_muka_dipakai)",

            //---
            //            "valas_new_sisa" => "sisa_valas-valas_nilai_stock",
            "valas_new_sisa" => "sisa_valas-valas_nilai_bayar",
            "valas_kurang" => "sisa_valas-(uang_muka_valas_dipakai+valas_nilai_stock+credit_note_dipakai)",
        ),
        "preProcessor" => array(
            "4891" => array(
                "master" => array(


                    // preprocc fifo credit note valas
                    array(
                        "comName" => "FifoValasExternAverageCreditNoteMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valasDetails",
                            "extern_nama" => "valas_nama",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "produk_qty" => "credit_note_dipakai", // jumlah uang muka valas yang dipakai
                            "gudang_id" => ".0",
                            "cash_methode" => ".valas",// ditembak valas supaya bisa dijalankan

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "FifoValasExternCreditNoteMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valasDetails",// harus ada isinya atau tidak boleh jalan fifonya
                            "extern_nama" => "valas_nama",
                            "extern2_id" => "pihakID",// harus ada isinya atau tidak boleh jalan fifonya
                            "extern2_nama" => "pihakName",
                            "produk_qty" => "credit_note_dipakai", // jumlah uang muka valas yang dipakai
                            "gudang_id" => ".0",
                            "cash_methode" => ".valas",// ditembak valas supaya bisa dijalankan
                            //                            "cash_methode" => "cashMethodeOption",
                        ),
                        "resultParams" => array(
                            "rsltItems3" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "jml" => "qty",
                                "qty" => "qty",
                                "credit_note_dipakai_nilai" => "hpp",
                                //                                "uang_muka_valas_hpp" => "hpp",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "switchResultParams" => true,
                    ),

                    // preprocc fifo stok valas
                    array(
                        "comName" => "FifoValasAverageMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valas_account",
                            "extern_nama" => "valas_account__label",
                            "produk_qty" => "valas_nilai_stock",// jumlah stok valas yang dipakai || valas_nilai_bayar
                            "gudang_id" => ".0",
                            "cash_methode" => ".valas",// ditembak valas supaya bisa dijalankan
                            //                            "cash_methode" => "cashMethodeOption",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "FifoValasMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valas_account",// harus ada isinya atau tidak boleh jalan fifonya
                            "extern_nama" => "valas_account__label",
                            "produk_qty" => "valas_nilai_stock", // jumlah stok valas yang dipakai || valas_nilai_bayar
                            "gudang_id" => ".0",
                            "cash_methode" => ".valas",// ditembak valas supaya bisa dijalankan
                            //                            "cash_methode" => "cashMethodeOption",
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
                                //                                "valas_subtotal" => "subtotal",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "switchResultParams" => true,
                    ),

                    // preprocc fifo uang muka valas
                    array(
                        "comName" => "FifoValasExternAverageMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valasDetails",
                            "extern_nama" => "valas_nama",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "produk_qty" => "uang_muka_valas_dipakai", // jumlah uang muka valas yang dipakai
                            "gudang_id" => ".0",
                            "cash_methode" => ".valas",// ditembak valas supaya bisa dijalankan
                            //                            "cash_methode" => "cashMethodeOption",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "FifoValasExternMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "valasDetails",// harus ada isinya atau tidak boleh jalan fifonya
                            "extern_nama" => "valas_nama",
                            "extern2_id" => "pihakID",// harus ada isinya atau tidak boleh jalan fifonya
                            "extern2_nama" => "pihakName",
                            "produk_qty" => "uang_muka_valas_dipakai", // jumlah uang muka valas yang dipakai
                            "gudang_id" => ".0",
                            "cash_methode" => ".valas",// ditembak valas supaya bisa dijalankan
                            //                            "cash_methode" => "cashMethodeOption",
                        ),
                        "resultParams" => array(
                            "rsltItems2" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "jml" => "qty",
                                "qty" => "qty",
                                "uang_muka_valas_harga" => "hpp",
                                "uang_muka_valas_hpp" => "hpp",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "switchResultParams" => true,
                    ),
                    //------------------------------------

                    // inject selisih kurs
                    array(
                        "comName" => "SelisihKurs",
                        "loop" => array(),
                        "static" => array(
                            "uang_muka_stock_valas" => "uang_muka_stok_valas_exchange", // fifo valas
                            //                            "total_new_exchange" => "valas_harga+uang_muka_valas_hpp", // fifo valas
                            "jenisTr" => "jenisTr",
                            "cashMethodeOption" => ".valas",
                            "additional" => "additional",
                            "additional_value" => "additional_value",
                            "nilai_entry" => "nilai_entry",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //  piutang pembelian
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "credit_note_dipakai_nilai", // nilai piutang pembelian total dari antisource yang dipilih...
                            "jenis" => ".piutang pembelian",
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
                    // hutang dagang
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nilai_bayar",
                            "jenis" => ".hutang dagang",
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
                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "pembayaran_total_kas",
                            //                            "nilai" => "pembayaran_total",
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
                "detail" => array(
                    //                    // preprocc fifo stok valas
                    //                    array(
                    //                        "comName" => "FifoValasAverage",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "valas_id",
                    //                            "extern_nama" => "valas_nama",
                    //                            "produk_qty" => "valas_nilai_bayar", // jumlah stok valas yang dipakai
                    //                            "gudang_id" => ".0",
                    //                            "cash_methode" => "cashMethodeOption",
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                    //                    array(
                    //                        "comName" => "FifoValas",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "valas_id",
                    //                            "extern_nama" => "valas_nama",
                    //                            "produk_qty" => "valas_nilai_bayar", // jumlah stok valas yang dipakai
                    //                            "gudang_id" => ".0",
                    //                            "cash_methode" => "cashMethodeOption",
                    //                        ),
                    //                        "resultParams" => array(
                    //                            "rsltItems" => array(
                    //                                "id" => "produk_id",
                    //                                "nama" => "nama",
                    //                                "name" => "nama",
                    //                                "jml" => "qty",
                    //                                "qty" => "qty",
                    //                                "valas_harga" => "hpp",
                    //                                "valas_hpp" => "hpp",
                    ////                                "valas_subtotal" => "subtotal",
                    //                            ),
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                    //
                    //                    // preprocc fifo uang muka valas
                    //                    array(
                    //                        "comName" => "FifoValasExternAverage",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "valas_id",
                    //                            "extern_nama" => "valas_nama",
                    //                            "extern2_id" => "pihakID",
                    //                            "extern2_nama" => "pihakName",
                    //                            "produk_qty" => "valas_nilai_bayar", // jumlah uang muka valas yang dipakai
                    //                            "gudang_id" => ".0",
                    //                            "cash_methode" => "cashMethodeOption",
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                    //                    array(
                    //                        "comName" => "FifoValasExtern",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "valas_id",
                    //                            "extern_nama" => "valas_nama",
                    //                            "extern2_id" => "pihakID",
                    //                            "extern2_nama" => "pihakName",
                    //                            "produk_qty" => "valas_nilai_bayar", // jumlah uang muka valas yang dipakai
                    //                            "gudang_id" => ".0",
                    //                            "cash_methode" => "cashMethodeOption",
                    //                        ),
                    //                        "resultParams" => array(
                    //                            "rsltItems" => array(
                    //                                "id" => "produk_id",
                    //                                "nama" => "nama",
                    //                                "name" => "nama",
                    //                                "jml" => "qty",
                    //                                "qty" => "qty",
                    //                                "valas_harga" => "hpp",
                    //                                "valas_hpp" => "hpp",
                    ////                                "valas_subtotal" => "subtotal",
                    //                            ),
                    //                        ),
                    //                        "srcGateName" => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "4891" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang", //
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",

//                            "{add_jenis}" => "additional_value_total",
                            "laba(rugi) selisih kurs" => "add_diskon_selisih_kurs",
                            "uang muka valas" => "-uang_muka_valas_hpp",
                            "laba(rugi) selisih adjustment" => "nilai_sisa_hutang_dagang",
                            "valas" => "-valas_harga",
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
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang", //
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",

//                            "{add_jenis}" => "additional_value_total",
                            "laba(rugi) selisih kurs" => "add_diskon_selisih_kurs",
                            "uang muka valas" => "-uang_muka_valas_hpp",
                            "laba(rugi) selisih adjustment" => "nilai_sisa_hutang_dagang",
                            "valas" => "-valas_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //----------------------------
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //----------------------------

//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "hutang lain ppv" => "-additional_expense",
//                            "biaya lain lain" => "-additional_expense",
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
//                            "hutang lain ppv" => "-additional_expense",
//                            "biaya lain lain" => "-additional_expense",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuBiayaMain",
//                        "loop" => array(
//                            "biaya" => "additional_expense",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "extern_id" => ".37",
//                            "extern_nama" => ".biaya lain lain",
//                            "transaksi_no" => "nomer",
//                            "transaksi_id" => "transaksi_id",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // uang muka valas by vendor,
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "uang muka valas" => "-uang_muka_valas_hpp",
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
                    // uang muka valas by vendor, by valas
                    array(
                        "comName" => "RekeningPembantuUangMukaExternMain",
                        "loop" => array(
                            "uang muka valas" => "-uang_muka_valas_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "uangMukaValas__extern2_id",
                            "extern2_nama" => "uangMukaValas__extern2_nama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "qty" => "-uang_muka_valas_dipakai",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //
                    //rekening koran
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
                    //----------------------------


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
                    // valas pusat
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
            "4891" => array(
                "master" => array(
                    //                    array(
                    //                        "comName" => "PaymentAntiSource",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "placeName",
                    //                            "transaksi_id" => "creditAmount__transaksi_id",
                    //                            "jenis" => "creditAmount__jenis",
                    //                            //                            "nomer"        => "referenceNomer",
                    //                            "extern_id" => "pihakID",
                    //                            "extern_nama" => "pihakName",
                    //                            "label" => ".piutang pembelian",
                    //                            "terbayar" => "nilai_dipakai_piutang_pembelian",
                    //                        ),
                    //                        "reversable" => true,
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-(kas_value+kas_add)",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "(kas_value+kas_add)",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
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
                    //"extern_label2"=>"uangMuka__extern_label2",//ini update untuk pembeda vemdor/ customer
                    //                    ),
                    //---locker value valas----------------
                    //---locker stock valas----------------
                    //                        ),
                    //                        "reversable" => true,
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",

                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".valas",
                            "produk_id" => "valasDetails",
                            "nama" => "valas_nama",
                            "nilai" => "-valas_nilai_stock",
                            //                            "nilai" => "-valas_nilai_locker",
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
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".payment",
                            "jenis" => ".valas",
                            "produk_id" => "valasDetails",
                            "nama" => "valas_nama",
                            "nilai" => "valas_nilai_stock",
                            //                            "nilai" => "valas_nilai_locker",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //-------------------
                    //---locker uang muka valas, vendor----------------
                    array(
                        "comName" => "LockerValueExtern",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".valas",
                            "produk_id" => "uangMukaValas__extern2_id",
                            "nama" => "uangMukaValas__extern2_nama",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "-uang_muka_valas_dipakai",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerValueExtern",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".payment",
                            "jenis" => ".valas",
                            "produk_id" => "uangMukaValas__extern2_id",
                            "nama" => "uangMukaValas__extern2_nama",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "uang_muka_valas_dipakai",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //-------------------

                    //loker rekening koran
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
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".sold",
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

                    //----- payment source uang muka valas
                    array(
                        "comName" => "UangMukaValasSourceMain",//untuk nulis ke payment source karena gerbang dari detail, di trnasksi misc di off kan ya bro
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka valas",
                            "jenis" => "jenisTr",
                            //                            "target_jenis" => ".14464",
                            //                            "transaksi_id" => "transaksi_id",
                            //---------
                            //                            "terbayar" => "0",
                            //                            "tagihan" => "harga",
                            //                            "sisa" => "harga",
                            "nilai" => "-uang_muka_valas_hpp",
                            //---------
                            "terbayar" => "uang_muka_valas_hpp",//
                            "terbayar_valas" => "uang_muka_valas_dipakai",//
                            "nilai_valas" => "-uang_muka_valas_dipakai",
                            //---------
                            "reference_jenis" => "jenisTr",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "extern2_id" => "valasDetails",
                            "extern2_nama" => "valas_nama",
                            "extern_label2" => ".vendor",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // payment antisource credit note valas
                    array(
                        "comName" => "PaymentAntiSourceValas",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "target_jenis" => "jenisTr",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang pembelian",
                            "terbayar_valas" => "credit_note_dipakai",
                            "terbayar" => "credit_note_dipakai_nilai",
                            "valas_id" => "valasDetails",
                            "valas_nama" => "valas_nama",
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
                            "label" => ".hutang dagang",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                            "bayar_valas" => "valas_nilai_bayar",
                            "sisa_valas" => "valas_new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
        ),
    ),
    // config pembayaran hutang sewa
    "1424" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__nama",
                "cabang2ID" => "cabang2",
                "cabang2Name" => "cabang2__nama",
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",
                "ppn_val" => "",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppn_persen_dipakai*harga_disc)/100",
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "srcAccount" => "nama",
                "harga_dipakai" => "hpp_nppn-ppn",

                "source_ppn_persen" => "(ppn/extern_nilai2)*100",
                "pph_value" => "pph23_nilai",

                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",

                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            "harus_bayar_orig" => "extern_nilai2-non_pph",

            "valid_dpp" => "source_dpp-non_pph",
            "pph23_nilai" => "(pph23Method__tarif/100)*valid_dpp",
            "valid_ppn" => "source_dpp*source_ppn_persen/100",

            "final_sisa" => "new_sisa-nilai_bayar",

            "nilai_sisa" => "sisa-pph23_nilai-credit_note_dipakai-uang_muka_dipakai",
            "nilai_entry" => "nilai_sisa",
            "nilai_bayar" => "nilai_entry+credit_note_dipakai+uang_muka_dipakai",

            "payment_out" => "0",
            //            "nilai_entry" =>"sisa-nilai_bayar",
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
        "preProcessor" => array(
            "1424" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "credit_note_dipakai", // nilai piutang pembelian total dari antisource yang dipilih...
                            "jenis" => ".piutang pembelian",
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
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            //                            "nilai" => "creditAmount+nilai_entry", // nilai pembayaran total
                            //                            "nilai" => "creditAmount+creditValue+nilai_dipakai", // nilai pembayaran total
                            //                            "nilai" => "nilai_bayar", // nilai pembayaran total
                            "nilai" => "nilai_bayar",
                            "jenis" => ".hutang sewa",
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
                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "nilai_entry",
                            //"nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
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
            "1424" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang sewa" => "-nilai_dipakai_hutang_sewa",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            //                            "hutang pph23" => "pph23_nilai",
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
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang sewa" => "-nilai_dipakai_hutang_sewa",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "uang muka dibayar" => "-uang_muka_dipakai",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "hutang sewa" => "-nilai_dipakai_hutang_sewa",
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
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            //                            "piutang pembelian" => "-creditAmount",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                    //                    array(
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "piutang pembelian" => "-creditAmount",
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
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "1424" => array(
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
                            "label" => ".piutang pembelian",
                            "terbayar" => "nilai_dipakai_piutang_pembelian",
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
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
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang sewa",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                            "ppn" => "valid_ppn",
                            "extern_nilai2" => "valid_dpp",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    // config pembayaran service projek
    "483" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "source_ppn_persen" => "(ppn/extern_nilai2)*100",
            ),
            "master_dependent" => array(
                "pphGateId" => array(
                    "1" => array(
                        "akun_pph_id" => ".37",
                        "akun_pph_label" => ".pph ps 23",
                    ),//dipotong
                    "2" => array(
                        "akun_pph_id" => ".38",
                        "akun_pph_label" => ".biaya pph ps. 23",
                    ),//tidak dipotong

                ),
            ),
        ),
        "valueBuilders" => array(
            "totalCredit" => "creditAmount+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            "harus_bayar_orig" => "extern_nilai2-non_pph",

            //            "pph23_nilai" => "(pph23Method__tarif/100)*harus_bayar_orig",// mati dulu
            //            "nilai_bayar" => "nilai_entry+totalCredit+pph23_nilai",
            //            "ppn_key" => "source_ppn_persen+100",
            //            "source_dpp" => "(nilai_entry*100)/ppn_key",
            "source_dpp" => "extern_nilai2",

            "valid_dpp" => "source_dpp-non_pph",
            "pph23_nilai" => "(pph23Method__tarif/100)*valid_dpp",

            "valid_ppn" => "source_dpp*source_ppn_persen/100",


            "pay_out" => "sisa-(pph23_nilai+uang_muka_dipakai)+biaya_jasa",
            //            "pay_out" => "sisa-(pph23_nilai+uang_muka_dipakai)",
            //            "pay_out" => "nilai_entry-(pph23_nilai+uangMuka)",
            "sisa_uang_muka" => "uangMuka-uang_muka_dipakai",
            "payment_out" => "pay_out",
            "valid_sisa" => "(new_sisa-payment_out)",
            "sisa_tagihan" => "sisa-pph23_nilai",

            "nilai_entry" => "sisa",//aslinya tidak melihat uang muka dan pph ini
            // "nilai_entry" => "pay_out",//
            "final_sisa" => "new_sisa-nilai_bayar",
            "nilai_bayar" => "nilai_entry",
            "biaya_jasa" => "biayaJasa*pph23_nilai",

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
            "new_sisa" => "sisa-nilai_bayar-uangMuka",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa-totalCredit",
            //            "payment_out" => "nilai_entry-pph23_nilai",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
        ),
        "preProcessor" => array(
            "483" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            //                            "nilai" => "creditAmount+nilai_entry", // nilai pembayaran total
                            "nilai" => "nilai_entry", // nilai pembayaran total
                            "jenis" => ".hutang dagang",
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
                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            //                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "nilai" => "payment_out",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
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
            "483" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya usaha" => "biaya_jasa",
                            "piutang pembelian" => "-creditAmount",
                            //                            "kas" => "-payment_out",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            //                            "hutang biaya" => "-(creditAmount+nilai_entry)",
                            //                            "hutang dagang" => "-nilai_dipakai_hutang_biaya",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
                            "hutang pph23" => "pph23_nilai",
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
                            "biaya usaha" => "biaya_jasa",
                            "piutang pembelian" => "-creditAmount",
                            //                            "kas" => "-payment_out",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            //                            "hutang biaya" => "-(creditAmount+nilai_entry)",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
                            "hutang pph23" => "pph23_nilai",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "hutang dagang" => "-nilai_dipakai_hutang_dagang",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "piutang pembelian" => "-creditAmount",
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
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "uang muka dibayar" => "-uang_muka_dipakai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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


                    //region JURNAL PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya usaha" => "-biaya_jasa",
                            "piutang cabang" => "biaya_jasa",
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
                            "biaya usaha" => "-biaya_jasa",
                            "piutang cabang" => "biaya_jasa",

                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "piutang cabang" => "biaya_jasa",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //region JURNAL CABANG
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya usaha" => "biaya_jasa",
                            "hutang ke pusat" => "biaya_jasa",
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
                            "biaya usaha" => "biaya_jasa",
                            "hutang ke pusat" => "biaya_jasa",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "hutang ke pusat" => "biaya_jasa",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya usaha" => "-biaya_jasa",
                            "hpp projek" => "biaya_jasa",
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
                            "biaya usaha" => "-biaya_jasa",
                            "hpp projek" => "biaya_jasa",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion JURNAL CABANG

                ),
                "detail" => array(
                    //region PUSAT
                    array(
                        "comName" => "RekeningPembantuPph",
                        "loop" => array(
                            "hutang pph23" => "pph23_nilai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_pph23",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "biaya usaha" => "biaya_jasa",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "akun_pph_id",//id dta biaya usaha
                            "extern_nama" => "akun_pph_id",///nama data biaya usaha
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "biaya usaha" => "-biaya_jasa",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "akun_pph_id",//id dta biaya usaha
                            "extern_nama" => "akun_pph_id",///nama data biaya usaha

                            "jenis" => "jenisTr",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
        ),
        "postProcessor" => array(
            "483" => array(
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
                            //                            "nilai" => "-payment_out",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "payment_out",
                            "nilai" => "kas_value",
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
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang dagang",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => ".0",
                            "ppn" => "valid_ppn",
                            "extern_nilai2" => "valid_dpp",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
        ),
    ),

    // config pembayaran aset
    "4821" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
        ),
        "formatNota" => "stepCode|placeID|supplierID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "supplierID" => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "additional" => array(
                    "-1" => array(
                        "add_jenis" => ".keutungan kurs",
                        "add_diskon" => "additional_value",
                        //                        "bayar_total" => 'additional_value+creditAmount+diskon+nilai_entry',
                        "bayar_total" => 'additional_value+credit_note_dipakai+diskon',
                        "diskon_factor" => "0",

                    ),
                    "1" => array(
                        "add_jenis" => ".kerugian kurs",
                        "add_diskon" => "additional_value",
                        //                        "bayar_total" => "creditAmount+diskon+nilai_entry",
                        "bayar_total" => "credit_note_dipakai+diskon",
                        "diskon_factor" => "additional_value",

                    ),
                    "0" => array(
                        "add_jenis" => ".kerugian kurs",
                        "add_diskon" => ".0",
                        //                        "bayar_total" => "creditAmount+diskon+nilai_entry",
                        "bayar_total" => "credit_note_dipakai+diskon",
                        "diskon_factor" => ".0",

                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            //            "totalCredit" => "creditAmount+creditValue",
            //            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry+uangMuka-diskon_factor",
            //            "additionalFactor" => "additional_value*additional",
            //            "nilai_dipakai" => "nilai_entry-additional_expense",

            "totalCredit" => "credit_note_dipakai+creditValue",
            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry+uang_muka_dipakai-diskon_factor+selisih_round",
            "additionalFactor" => "additional_value*additional",
            "nilai_dipakai" => "nilai_entry-additional_expense",
            //            "new_sisa" => "sisa-nilai_bayar",

            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
            "nett1_bulat" => "harga",
            "ppn_out_bulat" => "ppn",

            //            "totalCredit" => "credit_note_dipakai+creditValue",
            //            "harus_bayar" => "sisa-totalCredit",
            "harus_bayar_orig" => "extern_nilai2-non_pph",

            //            "pph23_nilai" => "(pph23Method__tarif/100)*harus_bayar_orig",// mati dulu
            //            "nilai_bayar" => "nilai_entry+totalCredit+pph23_nilai",

            "ppn_key" => "source_ppn_persen+100",
            "source_dpp" => "(nilai_entry/sisa)*100",

            //            "valid_dpp" => "source_dpp-extern_nilai4-non_pph-ppn",

            "valid_dpp" => "((source_dpp*extern_nilai2)/100)-non_pph",
            "pph23_nilai" => "(pph23Method__tarif/100)*valid_dpp",
            "valid_ppn" => "source_dpp*source_ppn_persen/100",
            "new_sisa" => "sisa-(pph23_nilai+credit_note_dipakai+uang_muka_dipakai+nilai_entry+selisih_round)",
            "valid_sisa" => "(new_sisa-payment_out)",
            "pay_out" => "sisa-(pph23_nilai+uang_muka_dipakai+credit_note_dipakai)",
            "payment_out" => "pay_out+selisih_round",
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

        ),
        "additionalRound" => array(
            "sisa" => "nilai_round",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "additionalFactor+sisa-totalCredit+additional_expense",
            "selisih_round" => "sisa-nilai_round",

        ),
        "preProcessor" => array(
            "4821" => array(
                "master" => array(
                    array(
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "credit_note_dipakai", // nilai piutang pembelian total dari antisource yang dipilih...
                            "jenis" => ".piutang pembelian",
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
                        "comName" => "RekeningValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            //                            "nilai" => "creditAmount+nilai_entry", // nilai pembayaran total
                            //                            "nilai" => "creditAmount+creditValue+nilai_dipakai", // nilai pembayaran total
                            "nilai" => "credit_note_dipakai+creditValue+uang_muka_dipakai+nilai_entry+selisih_round", // nilai pembayaran total
                            "jenis" => ".hutang aktiva tetap",
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
                    // rekening koran
                    array(
                        "comName" => "RekeningKoranMinus",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".active",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "nilai" => "nilai_entry",
                            //                            "nilai" => "nilai_dipakai_piutang_pembelian+creditValue+nilai_entry+uang_muka_dipakai+bayar_total-diskon_factor",
                            "method" => "cashMethode", // cash method yang dipilih saat setor
                            "jenis" => ".kas",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "kas_value" => "nilai_cash",
                                "rekening_koran_value" => "nilai_koran",
                                //                                "nilai_cash_full" => "nilai_cash_full",
                                //                                "nilai_koran_full" => "nilai_koran_full",
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

                "suppliers_id" => "pihakID",
                "suppliers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
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
            "4821" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang aktiva tetap" => "-nilai_dipakai_hutang_aktiva_tetap",
                            "biaya lain lain" => "additional_expense",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "credit note" => "-diskon",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "hutang pph23" => "pph23_nilai",
                            "selisih pembulatan" => "selisih_round*-1",

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
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
                            "hutang aktiva tetap" => "-nilai_dipakai_hutang_aktiva_tetap",
                            "biaya lain lain" => "additional_expense",
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                            "hutang bank" => "rekening_koran_value",
                            "credit note" => "-diskon",
                            "uang muka dibayar" => "-uang_muka_dipakai",
                            "hutang pph23" => "pph23_nilai",
                            "selisih pembulatan" => "selisih_round*-1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            //                            "hutang dagang" => "-(creditAmount+creditValue+nilai_dipakai)",
                            "hutang aktiva tetap" => "-nilai_dipakai_hutang_aktiva_tetap",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "credit note" => "-diskon",
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
                            //                            "kas" => "-nilai_entry",
                            "kas" => "-kas_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "uang muka dibayar" => "-uang_muka_dipakai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "uangMuka__extern_id",
                            "extern_nama" => "uangMuka__extern_nama",
                            //dimatiin rekening salah extern id bro , 4/20/2022
                            // "extern_id" => "uangMuka__extern2_id",
                            // "extern_nama" => "uangMuka__extern2_nama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening koran
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
                            //                            "h" => "harga",//
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
                            //                            "rekening koran" => "harga",//
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
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "4821" => array(
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
                            "label" => ".piutang pembelian",
                            "terbayar" => "nilai_dipakai_piutang_pembelian",
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
                            //                            "nilai" => "-nilai_entry",
                            "nilai" => "-kas_value",
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
                            //                            "nilai" => "nilai_entry",
                            "nilai" => "kas_value",
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
                    //loker rekening koran
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
                            "nilai" => "-rekening_koran_value",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                    array(
                        "comName" => "LockerStockPlafonBankMutasiMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "debet" => "-rekening_koran_value",
                            "produk_nilai" => "-rekening_koran_value",
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
                            "state" => ".sold",
                            "jenis" => ".plafon hutang bank",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "rekening_koran_value",
                            "transaksi_id" => ".0",
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
                            "label" => ".hutang aktiva tetap",
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