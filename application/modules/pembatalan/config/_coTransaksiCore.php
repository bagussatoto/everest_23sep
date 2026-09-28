<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(
    //config return (juranl manual non stok)
    "9911" => array(
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                //                "transaksi_nilai" => "srcDefValue",
                "transaksi_nilai" => "nilai_cancel",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
                //----
                "reference_jenis" => "jenisTr_reference",
                "reference_id" => "referenceID",
                "reference_nomer" => "referenceNomer",
                "reference_id_top" => "referenceID_top",
                "reference_nomer_top" => "referenceNomer_top",
                "reference_jenis_top" => "pihakExternMasterID",
                //----
            ),
            "mainValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
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
                "debet" => "debet",
                "kredit" => "kredit",
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
        "components" => array(//kosong baca dari builder helper lihat he_accounting
        ),
        "relativeComponets" => true,//untuk baca dari session builder helper relative jurnal
        "postProcessor" => array(//kosong baca dari builder helper lihat he_accounting

        ),
    ),
    //config return (juranl manual non stok cabang)
    "9912" => array(
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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                //                "transaksi_nilai" => "srcDefValue",
                "transaksi_nilai" => "nilai_cancel",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
                //----
                "reference_jenis" => "jenisTr_reference",
                "reference_id" => "referenceID",
                "reference_nomer" => "referenceNomer",
                "reference_id_top" => "referenceID_top",
                "reference_nomer_top" => "referenceNomer_top",
                "reference_jenis_top" => "pihakExternMasterID",
                //----
            ),
            "mainValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
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
                "debet" => "debet",
                "kredit" => "kredit",
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
        "components" => array(//kosong baca dari builder helper lihat he_accounting
        ),
        "relativeComponets" => true,//untuk baca dari session builder helper relative jurnal
        "postProcessor" => array(//kosong baca dari builder helper lihat he_accounting

        ),
    ),
    // config penerimaan piutang customer (uang masuk dari konsumen)
    "9749" => array(
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
            //            "totalCredit" => "credit_note_dipakai+creditValue",
            //            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya",
            //            "lebih_bayar" => "nilai_entry-harus_bayar",

            "nilai_entry" => "sisa",
            "nilai_bayar" => "nilai_entry",
            // "nilai_penghapusan"=>"nilai_entry",
            "lebih_bayar" => "nilai_entry-harus_bayar",
            "new_sisa" => "sisa-nilai_entry",
            "harus_bayar" => "sisa",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //        ),
        ),

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
        //            "new_sisa" => "sisa-nilai_bayar",
        //        ),
        //        "additionalMainBuilders" => array(//==per-item
        //            "harus_bayar" => "sisa-totalCredit-nilai_biaya",
        //            //            "nilai_bayar" => "nilai_entry+totalCredit",
        //        ),

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
            "9749" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "piutang dagang" => "-nilai_bayar",
                            "rugilaba lain lain" => "nilai_bayar",
                            // "rugi piutang dihapus" => "sisa",
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
                            "piutang dagang" => "-nilai_bayar",
                            "rugilaba lain lain" => "nilai_bayar",
                            // "rugi piutang dihapus" => "sisa",
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "piutang dagang" => "-nilai_bayar",
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
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "rugilaba lain lain" => "-nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",
                            "extern_nama" => ".penghapusan piutang",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
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
            "9749r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".piutang dagang",
                            //                            "target_jenis" => "jenisTr",
                            //                            "target_jenis" => "jenis_source",
                            "target_jenis" => ".749",
                            "transaksi_id" => "refID",
                            //                            "terbayar" => "nilai_bayar",
                            //                            "dihapus" => "nilai_bayar",
                            "dihapus" => "nilai_bayar",
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