<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(
    //  valas exchange
    "383" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID|customerID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "placeID" => "placeID",
                "placeName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
                "customerID" => "pihakID",
                "customerName" => "pihakName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "jenisTr" => "jenisTr",
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",
                "ftot_discount" => "ftot_discount",

                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "(lebar_gross*panjang_gross*tinggi_gross)",

                "ppv" => "ppv",
                "hpp" => "hpp",
                "harga" => "harga",
                "disc" => "disc",
                "nett1" => "(harga-disc)",

                "nett2" => "(nett1+ppn)",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "placeID" => "placeID",
                "placeName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "(lebar_gross*panjang_gross*tinggi_gross)",

                "hpp" => "hpp",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "placeID" => "placeID",
                "placeName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
                    "cash" => array(
                        "nilai_cash" => "tagihan",
                        "nilai_credit" => "0",

                    ),
                    "cia" => array(
                        //                        "nilai_cash"   => "tagihan",
                        "nilai_cash" => "0",
                        "nilai_credit" => "0",
                    ),
                    "credit" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "credit_card" => array(
                        "nilai_cash" => "tagihan",
                        "nilai_credit" => "0",
                    ),
                    "debit_card" => array(
                        "nilai_cash" => "tagihan",
                        "nilai_credit" => "0",
                    ),
                ),
            ),
        ),

        "valueBuilders" => array(
            "grand_total" => "nett2",
            "tagihan" => "grand_total",
            "rl_tmp" => "grand_total-hpp",
        ),
        "valueBuilders_rsltItems" => array(
            "hpp" => "sub_hpp",
            "berat_gross" => "sub_berat_gross",
            "volume_gross" => "sub_volume_gross",


        ),
        "externalValues" => array(),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock",
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
                "transaksi_nilai" => "grand_total",
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
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
            "detailValues" => array(
                "harga" => "harga",
                "hpp" => "hpp",
                "disc" => "disc",
                "ppn" => "ppn",
                "nett1" => "nett1",
                "nett2" => "nett2",

                "ppv" => "ppv",

                "berat_gross" => "berat_gross",
                "volume_gross" => "volume_gross",
                //                "lebar_gross"   => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross"  => "tinggi_gross",
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
            "rsltItemsValues" => array(
                //                "harga"  => "harga",
                "hpp" => "hpp",
                //                "diskon" => "diskon",
                //                "ppn"    => "ppn",
                //                "nett"   => "nett",

                //                "ppv" => "ppv",

                "berat_gross" => "berat_gross",
                "volume_gross" => "volume_gross",
                //                "lebar_gross"   => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross"  => "tinggi_gross",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),

        "postProcessor" => array(
            "383" => array(
                "master" => array(),
                "detail" => array(),
            ),

        ),
        "extendedSteps" => array(
            //            "discount" => array(
            //                "srcKey" => "discount",
            //                "groupID" => "admin",
            //                "components" => array(),
            //            ),
        ),

    ),
    //  purchasing valas
    "384" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(
                //==sumber nilai utama
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "placeID" => "placeID",
                //                "placeName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
                //                "jenisTr" => "jenisTr",
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //                "ftot_discount" => "ftot_discount",

                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "disc" => "disc",
                //                "nett1" => "(harga-disc)",
                //                "nett2" => "(nett1+ppn)",

                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "placeID" => "placeID",
                //                "placeName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
            //            "rsltItems" => array(
            //                //===sumber nilai berupa rincian
            ////                "dtime" => "dtime",
            ////                "id" => "id",
            ////                "code" => "code",
            ////                "label" => "label",
            ////                "name" => "nama",
            ////                "qty" => "jml",
            ////                "satuan" => "satuan",
            ////                "note" => "note",
            //
            //                "hpp" => "hpp",
            ////
            ////                "pihakID" => "pihakID",
            ////                "pihakName" => "pihakName",
            ////                "cabangID" => "placeID",
            ////                "cabangName" => "placeName",
            ////                "placeID" => "placeID",
            ////                "placeName" => "placeName",
            ////                "olehID" => "olehID",
            ////                "olehName" => "olehName",
            //            ),
            //            "master_dependent" => array(
            //                "paymentMethod" => array(
            //                    "cash" => array(
            //                        "nilai_cash" => "tagihan",
            //                        "nilai_credit" => "0",
            //
            //                    ),
            //                    "cia" => array(
            //                        //                        "nilai_cash"   => "tagihan",
            //                        "nilai_cash" => "0",
            //                        "nilai_credit" => "0",
            //                    ),
            //                    "credit" => array(
            //                        "nilai_credit" => "tagihan",
            //                        "nilai_cash" => "0",
            //                    ),
            //                    "credit_card" => array(
            //                        "nilai_cash" => "tagihan",
            //                        "nilai_credit" => "0",
            //                    ),
            //                    "debit_card" => array(
            //                        "nilai_cash" => "tagihan",
            //                        "nilai_credit" => "0",
            //                    ),
            //                ),
            //            ),
        ),
        "valueBuilders" => array(
            //            "grand_total" => "nett2",
            //            "tagihan" => "grand_total",
            //            "netto" => "harga-biaya_bank",
            //            "rugilaba_konversi" => "netto-hpp",
            //            "biaya_bank_konversi" => ".0",
        ),
        "externalValues" => array(),
        "preValidator" => array(),
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
                "transaksi_nilai" => "grand_total",
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

            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "valas",
            ),
        ),
        "postProcessor" => array(
            "384r" => array(
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
                            "nilai" => "-harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
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
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "harga",
                            //                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "384" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "-harga",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // fifo valas masuk
                    array(
                        "comName" => "FifoValasAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "id",
                            "jml" => "qty",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "nama" => "nama",
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
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "unit" => "qty",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // locker value valas
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".valas",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "qty",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    //  config penyetoran maya uang asing
    "1759" => array(
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
            "new_sisa_valas" => "sisa_valas-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            "harus_bayar" => "sisa_valas-totalCredit",
            //            "nilai_bayar" => "nilai_entry+totalCredit",
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

        "postProcessor" => array(
            "1759r" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".valas",
                            "produk_id" => "valasDetails",
                            "nama" => "valasDetails__label",
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
                            "state" => ".hold",
                            "jenis" => ".valas",
                            "produk_id" => "valasDetails",
                            "nama" => "valasDetails__label",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //                    array(
                    //                        "comName" => "Jurnal_activity",
                    //                        "loop" => array(
                    //                            "activity" => ".1",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "placeName",
                    //                            "cabang2_id" => "place2ID",
                    //                            "cabang2_nama" => "place2Name",
                    //                            "oleh_id" => "olehID",
                    //                            "oleh_nama" => "olehName",
                    //                            "jenis" => "jenisTr",
                    //                            "jenis_master" => "jenisTrMaster",
                    //                            "jenis_top" => "jenisTrTop",
                    //                            "master_id" => "transaksi_id",
                    //                            "step_number" => ".1",
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
                    //                            "cabang2_id" => "place2ID",
                    //                            "cabang2_nama" => "place2Name",
                    //                            "oleh_id" => "olehID",
                    //                            "oleh_nama" => "olehName",
                    //                            "jenis" => "jenisTr",
                    //                            "jenis_master" => "jenisTrMaster",
                    //                            "jenis_top" => "jenisTrTop",
                    //                            "master_id" => "transaksi_id",
                    //                            "step_number" => ".1",
                    //                            "nilai" => ".1",
                    //                        ),
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

        //-----------------------------
        "recapItemBuilder" => array(
            "gateNameSource" => "items",
            "gateNameTarget" => "items2_sum",
            "key" => "valasDetails",
            "val" => array(
                "tagihan",
                "terbayar",
                "sisa",
                "tagihan_valas",
                "terbayar_valas",
                "sisa_valas",
                "nilai_bayar",
            ),
        ),
    ),
    "1758" => array(
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

        "postProcessor" => array(
            2 => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".valas",
                            "produk_id" => "valasDetails",
                            "nama" => "valasDetails__label",
                            "nilai" => "-nilai_entry",
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
                            "jenis" => ".valas",
                            "produk_id" => "valasDetails",
                            "nama" => "valasDetails__label",
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
                            "jenis" => ".valas",
                            "produk_id" => "valasDetails",
                            "nama" => "valasDetails__label",
                            "nilai" => "nilai_entry",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


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
                    ////                            "transaksi_id"        => "jenisTr",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),


                    //                    array(
                    //                        "comName" => "Jurnal_activity",
                    //                        "loop" => array(
                    //                            "activity" => ".1",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "placeName",
                    //                            "cabang2_id" => "place2ID",
                    //                            "cabang2_nama" => "place2Name",
                    //                            "oleh_id" => "olehID",
                    //                            "oleh_nama" => "olehName",
                    //                            "jenis" => "jenisTr",
                    //                            "jenis_master" => "jenisTrMaster",
                    //                            "jenis_top" => "jenisTrTop",
                    //                            "master_id" => "transaksi_id",
                    //                            "step_number" => ".2",
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
                    //                            "cabang2_id" => "place2ID",
                    //                            "cabang2_nama" => "place2Name",
                    //                            "oleh_id" => "olehID",
                    //                            "oleh_nama" => "olehName",
                    //                            "jenis" => "jenisTr",
                    //                            "jenis_master" => "jenisTrMaster",
                    //                            "jenis_top" => "jenisTrTop",
                    //                            "master_id" => "transaksi_id",
                    //                            "step_number" => ".2",
                    //                            "nilai" => ".1",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),
                ),
                "detail" => array(
                    // fifo valas masuk di pusat
                    array(
                        "comName" => "FifoValasAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "jml" => "jml",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "produk_id" => "valasDetails",
                            "nama" => "valasDetails__label",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern2_id" => "cash_account_target",
                            "extern2_nama" => "cash_account_target__label",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "FifoValas",
                        "loop" => array(),
                        "static" => array(
                            "unit" => "jml",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "produk_id" => "valasDetails",
                            "produk_nama" => "valasDetails__label",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern2_id" => "cash_account_target",
                            "extern2_nama" => "cash_account_target__label",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                ),
            ),
        ),
    ),


    //  konversi kas ke valas
    "385" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(),
            "detail" => array(),
            "master_dependent" => array(
                "biayaKategori" => array(
                    "1" => array(
                        "biaya_usaha_nilai" => "biaya",
                        "biaya_umum_nilai" => ".0",
                        "biayausaha_coa_code" => ".6010",
                        "biayaumum_coa_code" => ".6030",
                    ),//biaya usaha
                    "2" => array(
                        "biaya_usaha_nilai" => ".0",
                        "biaya_umum_nilai" => "biaya",
                        "biayausaha_coa_code" => ".6010",
                        "biayaumum_coa_code" => ".6030",
                    ),//biaya umum
                ),

            ),
        ),

        "valueBuilders" => array(
            "netto" => "harga+biaya",
            "grand_total" => "netto",

        ),
        "externalValues" => array(),
        "preValidator" => array(),
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
                "transaksi_nilai" => "grand_total",
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

            ),

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "valas",
            ),
        ),
        "components" => array(
            "385" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "-netto",//kas
                            "1010010020" => "harga",//valas
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
                            "1010010010" => "-netto",//kas
                            "1010010020" => "harga",//valas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // pembantu kas
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-netto",//kas
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

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuValas",
                        "loop" => array(
                            "1010010020" => "sub_harga",//valas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
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
            "385r" => array(
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
                            "nilai" => "-harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
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
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "harga",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "385" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "-harga",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // fifo valas masuk
                    array(
                        "comName" => "FifoValasAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".valas",
                            "produk_id" => "id",
                            "jml" => "qty",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "nama" => "nama",
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
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "unit" => "qty",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // locker value valas
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".valas",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "qty",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
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


);