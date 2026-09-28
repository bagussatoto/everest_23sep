<?php

$config["coTransaksiCore"] = array(
    // settlement uang muka dari konsumen
    "7761" => array(
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
        "formatNotaEdit" => "stepCode|placeID|olehID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
        ),
        "formatNotaReject" => "stepCode|placeID|olehID",
    ),
    "7760" => array(
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
        "postProcessor" => array(),
    ),
);