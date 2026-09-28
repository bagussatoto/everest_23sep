<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

$config["coTransaksiCore"] = array(

    "6699" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|referenceID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "hpp" => "harga",
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
//                "hpp_nppv" => "harga*ppv_index__nilai",
//                "ppv" => "hpp_nppv-harga",
                "hpp_nppv" => ".0",
                "ppv" => ".0",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
                //----------------------------
                "diskon_npph_nilai_total" => "diskon_nilai_total-diskon_pph23",
                "laba_lain_lain" => "diskon_pph23",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
//                    "cash" => array(
//                        "nilai_cash" => "tagihan",
//                        "nilai_credit" => "0",
//                    ),
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
//            "selisih_ppn_realisasi" => "nilai_tambah_ppn_in-ppn_realisasi",
//            "new_sisa" =>"nilai_tambah_piutang_pembelian-selisih_ppn_realisasi",
        ),
        "preProcessor" => array(
            "6699r" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "pihakID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
//            "467" => array(
//                "master" => array(
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".active",
//                            "jenis" => ".ppn in",
//                            "produk_id" => "pihakID",
//                            "nama" => "pihakName",
//                            "nilai" => "ppn",
////                            "transaksi_id" => "masterID",
//                            "transaksi_id" => "currentID",
//                            "oleh_id" => ".0",
//                            "paymentMethod" => "paymentMethod",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "nilai_dipakai" => "nilai_dipakai",
//                                "nilai_tambah" => "nilai_tambah",
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".active",
//                            "jenis" => ".piutang pembelian",
//                            "produk_id" => "pihakID",
//                            "nama" => "pihakName",
//                            "nilai" => "tagihan-nilai_dipakai_ppn_in",
////                            "transaksi_id" => "masterID",
//                            "transaksi_id" => "currentID",
//                            "oleh_id" => ".0",
//                            "paymentMethod" => "paymentMethod",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "nilai_dipakai" => "nilai_dipakai",
//                                "nilai_tambah" => "nilai_tambah",
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // extract diskon items
//                    array(
//                        "comName" => "SyncDiskonPembelian",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "source" => ".items",
//                            "target" => ".items4_sum",
//                            "jenisTr" => "jenisTr",
//                            "jenisTrMaster" => "jenisTrMaster",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                ),
//                "detail" => array(),
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

                "suppliers_id" => "supplierID",
                "suppliers_nama" => "supplierName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nett",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",

            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
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
                "produk_jenis" => "produk",
            ),
        ),

        "components" => array(
            "6699" => array(
                "master" => array(

//                    //region jurnal pertama
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030040" => "harga",//persediaan produk riil
//                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
//
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagan
//                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
//                            "1010030040" => "harga",//persediaan produk riil
//                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
//                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
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
//                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // pembantu hutang dagang (supplier)
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    /*
// * dimatikan karena detail hutang dagang lokal/import belum digenerate
// * tujuan untuk memisah kategori hutang dagang
// * 22 desember 2022*/
//                    // pembantu hutang dagang (lokal / import)
//
////                    array(
////                        "comName" => "RekeningPembantuSupplierJenis",
////                        "loop" => array(
////                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => ".2010010010",
////                            "extern_nama" => ".lokal",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // pembantu hutang dagang (lokal/import dengan supplier)
//
////                    array(
////                        "comName" => "RekeningPembantuSupplierSubJenis",
////                        "loop" => array(
////                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => ".2010010010",
////                            "extern_nama" => ".lokal",
////                            "extern2_id" => "pihakID",
////                            "extern2_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    //endregion

                    //region jurnal kedua pindah persediaan riil ke persediaan(std)
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "harga",//persediaan produk
//                            "1010030040" => "-harga",//persediaan produk riil
                            "3010020" => "harga",//modal
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "harga",//persediaan produk
//                            "1010030040" => "-harga",//persediaan produk riil
                            "3010020" => "harga",//modal
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

//                    // region mencatat piutang, diskon dari supplier
//                    99 => array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total
//                            "1010020030" => "diskon_nilai_total",// piutang supplier
//                            "7010150" => "laba_lain_lain",// laba lain-lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    98 => array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total
//                            "1010020030" => "diskon_nilai_total",// piutang supplier
//                            "7010150" => "laba_lain_lain",// laba lain-lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // endregion mencatat piutang, diskon dari supplier


                ),
                "detail" => array(
//                    array(
//                        "comName" => "RekeningPembantuProdukRiil",
//                        "loop" => array(
//                            "1010030040" => "sub_harga",//persediaan produk riil
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "produk_nilai" => "harga",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuProdukRiil",
//                        "loop" => array(
//                            "1010030040" => "-sub_harga",//persediaan produk riil
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "-qty",
//                            "produk_nilai" => "harga",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
//                            "1010030030" => "sub_hpp_nppv",//persediaan produk
                            "1010030030" => "sub_harga",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier
//                    array(
//                        "comName" => "RekeningPembantuPiutangSupplierItem",
//                        "loop" => array(
//                            "1010020030" => "sub_diskon_nilai",// piutang supplier
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
////                            "extern_id" => "diskon_id",
////                            "extern_nama" => "diskon_nama",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier
//                    array(
//                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
//                        "loop" => array(
//                            "1010020030" => "sub_diskon_nilai",// piutang supplier
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "extern2_id" => "diskon_id",
////                            "extern2_nama" => "diskon_nama",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id
//                    array(
//                        "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
//                        "loop" => array(
//                            "1010020030" => "sub_diskon_nilai",// piutang supplier
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
////                            "extern2_id" => "pihakID",
////                            "extern2_nama" => "pihakName",
////                            "extern_id" => "diskon_id",
////                            "extern_nama" => "diskon_nama",
//                            "extern3_id" => "pihakID",// supplier
//                            "extern3_nama" => "pihakName",// supplier
//                            "extern2_id" => "diskon_id",// jenis diskon
//                            "extern2_nama" => "diskon_nama",// jenis diskon
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),

                    // rekening pembantu produk, mengurangi sebesar diskon supplier
//                    array(
//                        "comName" => "RekeningPembantuProduk",
//                        "loop" => array(
//                            "1010030030" => "-sub_diskon_npph_nilai_total",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => ".0",
//                            "produk_nilai" => "-diskon_nilai_total",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
        ),
        "postProcessor" => array(
//            "466r" => array(
//                "master" => array(
////                    array(
////                        "comName" => "Jurnal_activity",
////                        "loop" => array(
////                            "activity" => ".1",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "cabang2_id" => "placeID",
////                            "cabang2_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////                            "step_number" => ".1",
//////                            "step_number" => "step_number",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Jurnal_activityMain",
////                        "loop" => array(
////                            "activity" => ".1",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "cabang2_id" => "placeID",
////                            "cabang2_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////                            "step_number" => ".1",
//////                            "step_number" => "step_number",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // locker transkasi
//                    // array(
//                    //     "comName" => "LockerTransaksi",
//                    //     "loop" => array(),
//                    //     "static" => array(
//                    //         "cabang_id" => "placeID",
//                    //         "jenis" => ".transaksi",
//                    //         "state" => ".active",
//                    //         "jumlah" => ".1",
//                    //         "produk_id" => ".0",
//                    //         "nama" => "",
//                    //         "satuan" => "",
//                    //         "oleh_id" => ".0",
//                    //         "gudang_id" => ".0",
//                    //     ),
//                    //     "srcGateName" => "main",
//                    //     "srcRawGateName" => "main",
//                    // ),
//                ),
//                "detail" => array(),
//            ),
//            "466" => array(
//                "master" => array(
////                    array(
////                        "comName" => "Jurnal_activity",
////                        "loop" => array(
////                            "activity" => ".1",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "cabang2_id" => "placeID",
////                            "cabang2_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////                            "step_number" => ".2",
//////                            "step_number" => "step_number",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    array(
////                        "comName" => "Jurnal_activityMain",
////                        "loop" => array(
////                            "activity" => ".1",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "cabang2_id" => "placeID",
////                            "cabang2_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////                            "step_number" => ".2",
//////                            "step_number" => "step_number",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // locker transkasi
//                    // array(
//                    //     "comName" => "LockerTransaksi",
//                    //     "loop" => array(),
//                    //     "static" => array(
//                    //         "cabang_id" => "placeID",
//                    //         "jenis" => ".transaksi",
//                    //         "state" => ".active",
//                    //         "jumlah" => ".1",
//                    //         "produk_id" => ".0",
//                    //         "nama" => "",
//                    //         "satuan" => "",
//                    //         "oleh_id" => ".0",
//                    //         "gudang_id" => ".0",
//                    //     ),
//                    //     "srcGateName" => "main",
//                    //     "srcRawGateName" => "main",
//                    // ),
//                ),
//                "detail" => array(
////                    array(
////                        "comName" => "PriceProdukPerSupplier",
////                        "loop" => array(),
////                        "static" => array(
////                            "produk_id" => "id",
////                            "suppliers_id" => "pihakID",
////                            "produk_nama" => "name",
////                            "nilai" => "harga",
////                            "cabang_id" => "placeID",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "jenis" => ".produk",
////                            "jenis_value" => ".hpp",
////                        ),
////                        "srcGateName" => "items",
////                        "srcRawGateName" => "items",
////                    ),
//                ),
//            ),
            "6699r" => array(
                "master" => array(),
                "detail" => array(
                    // serial number produk
                    array(
                        "comName" => "ProdukSerialNumber",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_serial_number" => "serial_number",
                            "produk_sku" => "produk_sku",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",

                            "transaksi_reference_count" => "referenceCount",
                            "transaksi_jenis_count" => "referenceCount",
                            "transaksi_count" => "referenceCount",
                            "transaksi_reference_fulldate" => "referenceFulldate",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
            "6699" => array(
                "master" => array(
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".hold",
//                            "jenis" => ".ppn in",
//                            "produk_id" => "pihakID",
//                            "nama" => "pihakName",
//                            "nilai" => "-nilai_dipakai_ppn_in",
//                            "transaksi_id" => "currentID",
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
//                            "gudang_id" => "gudangID",
//                            "state" => ".hold",
//                            "jenis" => ".piutang pembelian",
//                            "produk_id" => "pihakID",
//                            "nama" => "pihakName",
//                            "nilai" => "-nilai_dipakai_piutang_pembelian",
//                            "transaksi_id" => "currentID",
//                            "oleh_id" => ".0",
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
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".3",
////                            "step_number" => "step_number",
//                            "partial_otorisasi" => "partial_otorisasi",
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
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".3",
////                            "step_number" => "step_number",
//                            "partial_otorisasi" => "partial_otorisasi",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // locker transkasi
                    // array(
                    //     "comName" => "LockerTransaksi",
                    //     "loop" => array(),
                    //     "static" => array(
                    //         "cabang_id" => "placeID",
                    //         "jenis" => ".transaksi",
                    //         "state" => ".active",
                    //         "jumlah" => ".1",
                    //         "produk_id" => ".0",
                    //         "nama" => "",
                    //         "satuan" => "",
                    //         "oleh_id" => ".0",
                    //         "gudang_id" => ".0",
                    //     ),
                    //     "srcGateName" => "main",
                    //     "srcRawGateName" => "main",
                    // ),
                ),
                "detail" => array(
                    // menambah persediaan full
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "harga",
                            "jml_nilai" => "sub_harga",
                            "hpp_riil" => "harga",
                            "jml_nilai_riil" => "sub_harga",
                            "ppv_riil" => "ppv",
                            "ppv_nilai_riil" => "sub_ppv",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "pihakID",
                            "ppn_in" => "ppn",
                            "ppn_in_nilai" => "sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".lokal",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // mengurangi persediaan, sebesar diskon konsumen
//                    array(
//                        "comName" => "FifoAverage",
//                        "loop" => array(),
//                        "static" => array(
//                            "jenis" => ".produk",
//                            "jml" => ".0",
//                            "produk_id" => "id",
//                            "hpp" => "diskon_nilai_total",
//                            "jml_nilai" => "-sub_diskon_nilai_total",//sub_piutang_supplier
//                            "hpp_riil" => "diskon_nilai_total",
//                            "jml_nilai_riil" => "-sub_diskon_nilai_total",//sub_piutang_supplier
//                            "ppv_riil" => "ppv",
//                            "ppv_nilai_riil" => "sub_ppv",
//                            "hpp_nppv" => "hpp_nppv",
//                            "jml_nilai_nppv" => "sub_hpp_nppv",
//                            "nama" => "name",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn",
//                            "ppn_in_nilai" => "sub_ppn",
//                            "suppliers_id" => "pihakID",
//                            "suppliers_nama" => "pihakName",
//                            "produk_jenis" => ".lokal",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "qty",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
////                            "hpp" => "hpp_nppv",
////                            "jml_nilai" => "sub_hpp_nppv",
//                            "hpp" => "harga",
//                            "jml_nilai" => "sub_harga",
//                            "hpp_riil" => "harga",
//                            "jml_nilai_riil" => "sub_harga",
//                            "ppv_riil" => "ppv",
//                            "ppv_nilai_riil" => "sub_ppv",
//                            "hpp_nppv" => "hpp_nppv",
//                            "jml_nilai_nppv" => "sub_hpp_nppv",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn",
//                            "ppn_in_nilai" => "sub_ppn",
//                            "suppliers_id" => "pihakID",
//                            "suppliers_nama" => "pihakName",
//                            "produk_jenis" => ".lokal",
//                            "produk_jenis_id" => ".1",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // locker stok reguler
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "pihakID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

//                    array(
//                        "comName" => "PriceProduk",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "hpp",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".produk",
//                            "jenis_value" => ".hpp_nppv",
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

//                    array(
//                        "comName" => "PriceProduk",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "harga",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".produk",
//                            "jenis_value" => ".hpp_grn",
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // last purchase, tabel produk price last purchase
//                    array(
//                        "comName" => "PriceProdukLastPurchase",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "harga",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".produk",
//                            "jenis_value" => ".hpp",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // last purchase, tabel price
//                    array(
//                        "comName" => "PriceProduk",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "harga",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".produk",
//                            "jenis_value" => ".hpp",
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // last purchase ppn, tabel price
//                    array(
//                        "comName" => "PriceProduk",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "hpp_nppn",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".produk",
//                            "jenis_value" => ".hpp_nppn",
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // harga tandas
//                    array(
//                        "comName" => "PriceProduk",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "hrg_tandas",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".produk",
//                            "jenis_value" => ".hpp_nppv",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // harga tandas
//                    array(
//                        "comName" => "PriceProduk",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "hrg_tandas_npph23",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".produk",
//                            "jenis_value" => ".hpp_nppv_pph23",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // harga jual baru, tabel price
//                    array(
//                        "comName" => "PriceProduk",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "jual_baru",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".produk",
//                            "jenis_value" => ".jual",
//
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),
                ),
            ),
//            "111" => array(
//                "master" => array(
//                    array(
//                        "comName" => "PaymentSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".hutang dagang",
////                            "target_jenis" => "jenisTr",
//                            "jenis" => ".467",
//                            "transaksi_id" => "currentID",
//                            "ppn_approved" => "ppn_realisasi",
////                            "sisa" => "new_sisa",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "PaymentSource",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".hutang dagang",
////                            "target_jenis" => ".489",
//                            "jenis" => ".467",
//                            "transaksi_id" => "currentID",
//                            "terbayar" => "selisih_ppn_realisasi",
//                            "sisa" => "new_sisa",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
////                    array(
////                        "comName" => "Jurnal_activity",
////                        "loop" => array(
////                            "activity" => ".1",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "cabang2_id" => "placeID",
////                            "cabang2_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////                            "step_number" => ".4",
//////                            "step_number" => "step_number",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    array(
////                        "comName" => "Jurnal_activityMain",
////                        "loop" => array(
////                            "activity" => ".1",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "cabang2_id" => "placeID",
////                            "cabang2_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////                            "step_number" => ".4",
//////                            "step_number" => "step_number",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                ),
//                "detail" => array(),
//            ),
        ),

        "closedRequest" => array(
//            "466" => array(
//                "enabled" => true,
//            ),
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


);