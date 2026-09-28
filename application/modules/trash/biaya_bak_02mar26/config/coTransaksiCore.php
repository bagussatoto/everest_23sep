<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

/**
 * hutang biaya yang pembantunya bukan supplier di pindah ke rekening beban harus dibayar
 * aebelumnya menggunakan comRekeningpembntuAntarCabang menjadi ComRekeningPembantuBiayaHarusDibayar
 * biaya usaha,biaya umum,biaya produksi yang berasal dari request cabang/ pettycash
 */
$config["coTransaksiCore"] = array(
    //pembiayaan supplies by nota belum fix
    "7762" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",
        ),
        "formatNota" => "stepCode|placeID|cabangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "gudang2ID" => "gudang",
                "gudang2Name" => "gudang__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian

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
                "pihakPembebananLabel" => array(
                    "pusat" => array(
                        "persediaan_supplies_pusat" => "hpp",
                        "persediaan_supplies_cabang" => ".0",
                        "piutang_biaya_cabang" => ".0",
                        "hutang_biaya_ke_pusat" => ".0",
                        "biaya_kategori_terpilih_pusat" => "hpp",
                        "biaya_kategori_terpilih_cabang" => ".0",
                    ),
                    "cabang" => array(
                        "persediaan_supplies_pusat" => ".0",
                        "persediaan_supplies_cabang" => "hpp",
                        "piutang_biaya_cabang" => "hpp",
                        "hutang_biaya_ke_pusat" => "hpp",
                        "biaya_kategori_terpilih_pusat" => ".0",
                        "biaya_kategori_terpilih_cabang" => "hpp",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preValidator" => array(),
        "preProcessor" => array(
            "7762" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "detailValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),
        "components" => array(
            "7762" => array(
                "master" => array(
                    //-------------------------
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-persediaan_supplies_cabang",//persediaan supplies
                            "1010060040" => "piutang_biaya_cabang",//piutang biaya cabang
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
                            "1010030010" => "-persediaan_supplies_cabang",//persediaan supplies
                            "1010060040" => "piutang_biaya_cabang",//piutang biaya cabang
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
                            "1010060040" => "piutang_biaya_cabang",//piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihak2Coa_code}" => "biaya_kategori_terpilih_cabang",
                            //---------------------rencanan ganti relative gerbang pihak2Name ----
                            // "6010"=>"",//biaya usaha
                            // "6020"=>"",//biaya produksi
                            // "0603"=>"",//biaya umum
                            //--------------------
                            "2040020" => "hutang_biaya_ke_pusat",//hutang biaya ke pusat
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
                        "comName" => "Rekening",
                        "loop" => array(
                            "{pihak2Coa_code}" => "biaya_kategori_terpilih_cabang",
                            //---------------------rencanan ganti relative gerbang pihak2Name ----
                            // "6010"=>"",//biaya usaha
                            // "6020"=>"",//biaya produksi
                            // "0603"=>"",//biaya umum
                            //--------------------
                            "2040020" => "hutang_biaya_ke_pusat",//hutang biaya ke pusat
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "hutang_biaya_ke_pusat",//hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //-------------------------

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-persediaan_supplies_pusat",//persediaan supplies
                            "{pihak2Coa_code}" => "biaya_kategori_terpilih_pusat",
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
                            "1010030010" => "-persediaan_supplies_pusat",//persediaan supplies
                            "{pihak2Coa_code}" => "biaya_kategori_terpilih_pusat",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    //<editor-fold desc="Com-rekening pembantu, detail, center">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",//persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    array(
                        "comName" => "{pihak2Com}",
                        "loop" => array(
                            "{pihak2Coa_code}" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihak3ID",
                            "extern_nama" => "pihak3Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "7762r" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Postproc locker stock">

//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".supplies",
//                            "state" => ".active",
//                            "jumlah" => "-qty",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => ".0",
//                            "transaksi_id" => ".0",
//                            "nomer" => ".0",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            //"transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //</editor-fold>

                    array(
                        "comName" => "TransaksiItemUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".invoice",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "transaksi_id" => "referenceID",
                            "sinkron" => "seluruhnya",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "7762" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".sold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
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
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabangID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabangID",
    ),
    //otorisasi biaya produksi done
    "2676" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
        "preProcessor" => array(
            "2676" => array(
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
                            "items" => array(
                                "costNilai" => "nilai",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "2676" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6020" => "harga",//biaya produksi
//                            "2010040" => "harga",//hutang biaya geser ke beban harus bayar karena pembantu bukan supplier
                            "2010090020" => "harga",// beban harusdibayar
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
                            "6020" => "harga",//biaya produksi
                            "2010090020" => "2010090020",// beban harusdibayar
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // hutang biaya milik cabang (vendornya pusat)
                    // dimasukkan ke pembantu antar cabang, men-debet saat otorisasi, men-kredit saat dibayari oleh pusat
                    array(
                        "comName" => "RekeningPembantuBiayaHarusDibayar",//geser sebelumnya ke ComRekeningPembantuAntarCabang
                        "loop" => array(
                            "2010090020" => "harga",//hutang biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // ======= =======
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6020" => "-subtotal_rev",// biaya produksi || dikeluarkan dari biaya produksi
                            "{costIdCoa_1}" => "costNilai_1", // masuk ke kategory cost
                            "{costIdCoa_2}" => "costNilai_2", // masuk ke kategory cost
                            "{costIdCoa_3}" => "costNilai_3", // masuk ke kategory cost
                            /*
                             * relative rekening alfabet ganti ke COA 8 juni 2022
                             */
                            // "{costName_1}" => "costNilai_1", // masuk ke kategory cost
                            // "{costName_2}" => "costNilai_2", // masuk ke kategory cost
                            // "{costName_3}" => "costNilai_3", // masuk ke kategory cost
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
                            "6020" => "-subtotal_rev",// biaya produksi ||
                            "{costIdCoa_1}" => "costNilai_1", // masuk ke kategory cost
                            "{costIdCoa_2}" => "costNilai_2", // masuk ke kategory cost
                            "{costIdCoa_3}" => "costNilai_3", // masuk ke kategory cost
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
                            "3020010" => "-subtotal_rev", // efisiensi biaya masuk ke efisiensi biaya bom
                            "{costIdCoa_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{costIdCoa_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{costIdCoa_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
                            /*
                             * config relative rekening nama {costName_}dimatiin geser ke COA 8 juni 2022
                             */
                            // "{costName_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            // "{costName_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            // "{costName_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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
                            "3020010" => "-subtotal_rev",// efisiensi biaya
                            "{costIdCoa_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{costIdCoa_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{costIdCoa_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
                            /*
                             * config relative rekening nama {costName_}dimatiin geser ke COA 8 juni 2022
                             */
                            // "{costName_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            // "{costName_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            // "{costName_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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


                    //region blok efisiensi biaya, category
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-costNilai_1",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
//                            "extern_id" => "cost2IdCoa_1",//coa code pembenatu efiseinsi misal delivery cost =>  030201000001
                            "extern_id" => "costID_1",//coa code pembenatu efiseinsi misal delivery cost =>  030201000001
                            "extern_nama" => "cost2NameCoa_1",//delivry cost
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-costNilai_2",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
//                            "extern_id" => "cost2IdCoa_2",//coa code pembantu efiseinsi(direct labor)
                            "extern_id" => "costID_2",//coa code pembantu efiseinsi(direct labor)
                            "extern_nama" => "cost2NameCoa_2",//direct labor
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-costNilai_3",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
//                            "extern_id" => "cost2IdCoa_3",
                            "extern_id" => "costID_3",
                            "extern_nama" => "cost2NameCoa_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaProduksi",
                        "loop" => array(
                            "6020" => "harga",//biaya produksi
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // ======= ========
                    array(
                        "comName" => "RekeningPembantuBiayaProduksi",
                        "loop" => array(
                            "6020" => "-subtotal_rev", // biaya produksi selain cabang produksi, maka nilainya 0 saja
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",

                            /*
                             * gerbang rekening relative alfabet geser ke COA 8 juni 2022
                             */
                            // "{costName_1}" => "costNilai_1",
                            // "{costName_2}" => "costNilai_2",
                            // "{costName_3}" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
//                            "{cost2IdCoa_1}" => "-costNilai_1",
//                            "{cost2IdCoa_2}" => "-costNilai_2",
//                            "{cost2IdCoa_3}" => "-costNilai_3",
                            "{costIdCoa_1}" => "-costNilai_1",
                            "{costIdCoa_2}" => "-costNilai_2",
                            "{costIdCoa_3}" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //region blok pembantu sub efisiensi biaya
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_1",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
//                            "extern_id" => "cost2IdCoa_1",//030201000001
//                            "extern_nama" => "cost2NameCoa_1",//delivry cost
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_1",//id biaya
                            "extern2_nama" => "costName_1",//label biaya
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_1",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_2",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
//                            "extern_id" => "cost2IdCoa_2",
//                            "extern_nama" => "cost2NameCoa_2",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_2",
                            "extern2_nama" => "costName_2",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_2",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_3",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
//                            "extern_id" => "cost2IdCoa_3",
//                            "extern_nama" => "cost2NameCoa_3",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_3",
                            "extern2_nama" => "costName_3",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_3",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
        ),
        "postProcessor" => array(),
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
    // config request biaya usaha done
    "677" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
        "components" => array(),
        "postProcessor" => array(),
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
    //otorisasi biya usaha done
    "2677" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
        "preProcessor" => array(
            "2677" => array(
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
                            "items" => array(
                                "costNilai" => "nilai",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "2677" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6010" => "harga",//biaya usaha
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",// beban harusdibayar
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
                            "6010" => "harga",//biaya usaha
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//hutang biaya
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
                        "comName" => "RekeningPembantuBiayaHarusDibayar",
                        "loop" => array(
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//beban harus dibayar
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // ======= =======
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6010" => "-subtotal_rev",// biaya usaha dikeluarkan dari biaya usaha
                            "{cost2IdCoa_1}" => "costNilai_1", // masuk ke kategory cost
                            "{cost2IdCoa_2}" => "costNilai_2", // masuk ke kategory cost
                            "{cost2IdCoa_3}" => "costNilai_3", // masuk ke kategory cost
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
                            "6010" => "-subtotal_rev",// biaya usaha ikeluarkan dari biaya produksi
                            "{cost2IdCoa_1}" => "costNilai_1", // masuk ke kategory cost
                            "{cost2IdCoa_2}" => "costNilai_2", // masuk ke kategory cost
                            "{cost2IdCoa_3}" => "costNilai_3", // masuk ke kategory cost
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
                            "3020010" => "-subtotal_rev", // efisiensi biaya || masuk ke efisiensi biaya bom
                            "{cost2IdCoa_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{cost2IdCoa_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{cost2IdCoa_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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
                            "3020010" => "-subtotal_rev",// efisiensi biaya|| asuk ke efisiensi biaya bom
                            "{cost2IdCoa_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{cost2IdCoa_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{cost2IdCoa_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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

                    //================
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "6020" => "-subtotal_rev",// biaya produksi || dikeluarkan dari biaya produksi
//                            "3020010" => "-subtotal_rev", // efisiensi biaya masuk ke efisiensi biaya bom
//                        ),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "cabang2_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "6020" => "-subtotal_rev",// biaya produksi || dikeluarkan dari biaya produksi
//                            "3020010" => "-subtotal_rev", // efisiensi biaya masuk ke efisiensi biaya bom
//                        ),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "cabang2_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //================

                    //region blok efisiensi biaya, category
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-costNilai_1",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
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
                            "3020010" => "-costNilai_2",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
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
                            "3020010" => "-costNilai_3",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "6010" => "harga",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // ======= ========
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "6010" => "-subtotal_rev", // biaya usaha || selain cabang produksi, maka nilainya 0 saja
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{cost2IdCoa_1}" => "costNilai_1",
                            "{cost2IdCoa_2}" => "costNilai_2",
                            "{cost2IdCoa_3}" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{cost2IdCoa_1}" => "-costNilai_1",
                            "{cost2IdCoa_2}" => "-costNilai_2",
                            "{cost2IdCoa_3}" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //region blok pembantu sub efisiensi biaya
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_1",//efisiensi biaya||
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_1",
                            "extern2_nama" => "costName_1",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_1",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_2",//efisiensi biaya||
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_2",
                            "extern2_nama" => "costName_2",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_2",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_3",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_3",
                            "extern2_nama" => "costName_3",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_3",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),

        ),
        "postProcessor" => array(),
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

    // config request biaya umum pusat done
    "1675" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "1675r" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6030" => "harga",//biaya umum
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//beban harus dibayar
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
                            "6030" => "harga",//biaya umum
//                            "2010040" => "harga",//hutang biaya
                            "2010040" => "harga",//beban harus dibayar
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
                        "comName" => "RekeningPembantuBiayaHarusDibayar",
                        "loop" => array(
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//beban harus dibayar
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",// transaksi terjadi di dc/pusat
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaUmum",
                        "loop" => array(
                            "6030" => "harga",//biaya umum
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(),
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
    //request biaya umum cabang
    "675" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
        "components" => array(),
        "postProcessor" => array(),
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
    ),//done

    "2675" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
        "preProcessor" => array(
            "2675" => array(
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
                            "items" => array(
                                "costNilai" => "nilai",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "2675" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6030" => "harga",//biaya umum
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//beban harus dibayar
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
                            "6030" => "harga",//biaya umum
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//beban harus dibayar
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
                            "6030" => "harga",//biaya umum
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
                        "comName" => "RekeningPembantuBiayaHarusDibayar",
                        "loop" => array(
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//hutang biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // ======= =======
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6030" => "-subtotal_rev",// biaya umum dikeluarkan dari biaya produksi
                            "{cost2IdCoa_1}" => "costNilai_1", // masuk ke kategory cost
                            "{cost2IdCoa_2}" => "costNilai_2", // masuk ke kategory cost
                            "{cost2IdCoa_3}" => "costNilai_3", // masuk ke kategory cost
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
                            "6030" => "-subtotal_rev",// biaya umum dikeluarkan dari biaya produksi
                            "{cost2IdCoa_1}" => "costNilai_1", // masuk ke kategory cost
                            "{cost2IdCoa_2}" => "costNilai_2", // masuk ke kategory cost
                            "{cost2IdCoa_3}" => "costNilai_3", // masuk ke kategory cost
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
                    //costName hanya berlaku jika cabang produksi (solo)
                    // cost vs efisiensi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020010" => "-subtotal_rev", // efisiensi biaya masuk ke efisiensi biaya bom
                            "{cost2IdCoa_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{cost2IdCoa_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{cost2IdCoa_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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
                            "3020010" => "-subtotal_rev",// efisiensi biaya
                            "{cost2IdCoa_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{cost2IdCoa_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{cost2IdCoa_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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

                    //================
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "6020" => "-subtotal_rev",// biaya produksi || dikeluarkan dari biaya produksi
//                            "3020010" => "-subtotal_rev", // efisiensi biaya masuk ke efisiensi biaya bom
//                        ),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "cabang2_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "6020" => "-subtotal_rev",// biaya produksi || dikeluarkan dari biaya produksi
//                            "3020010" => "-subtotal_rev", // efisiensi biaya masuk ke efisiensi biaya bom
//                        ),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "cabang2_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //================

                    //region blok efisiensi biaya, category
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-costNilai_1",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "extern_id" => "costID_1",
                            // "extern_nama" => "costName_1",
//                            "extern_id" => "costIdCoa_1",//coa code pembenatu efiseinsi misal delivery cost =>  030201000001
                            "extern_nama" => "costNameCoa_1",//delivry cost
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-costNilai_2",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "extern_id" => "costID_2",
                            // "extern_nama" => "costName_2",
//                            "extern_id" => "costIdCoa_2",//coa code pembenatu efiseinsi misal delivery cost =>  030201000001
                            "extern_nama" => "costNameCoa_2",//delivry cost
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-costNilai_3",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "cabang2_id" => "placeID",
                            "extern_id" => "costID_3",//coa code pembenatu efiseinsi misal delivery cost =>  030201000001
//                            "extern_id" => "costIdCoa_3",//coa code pembenatu efiseinsi misal delivery cost =>  030201000001
                            "extern_nama" => "costNameCoa_3",//delivry cost
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaUmum",
                        "loop" => array(
                            "6030" => "harga",//biaya umum
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // ======= ========
                    array(
                        "comName" => "RekeningPembantuBiayaUmum",
                        "loop" => array(
                            "6030" => "-subtotal_rev", // biaya umum selain cabang produksi, maka nilainya 0 saja
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{cost2IdCoa_1}" => "costNilai_1",
                            "{cost2IdCoa_2}" => "costNilai_2",
                            "{cost2IdCoa_3}" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{cost2IdCoa_1}" => "costNilai_1",
                            "{cost2IdCoa_2}" => "costNilai_2",
                            "{cost2IdCoa_3}" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //region blok pembantu sub efisiensi biaya
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_1",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
//                            "extern_id" => "costIdCoa_1",
//                            "extern_nama" => "costNameCoa_1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_1",
                            "extern2_nama" => "costName_1",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_1",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_2",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
//                            "extern_id" => "costIdCoa_2",
//                            "extern_nama" => "costNameCoa_2",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_2",
                            "extern2_nama" => "costName_2",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_2",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_3",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
//                            "extern_id" => "costIdCoa_3",
//                            "extern_nama" => "costNameCoa_3",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_3",
                            "extern2_nama" => "costName_3",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_3",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),

        ),
        "postProcessor" => array(),
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
    ),//done

    //biaya besar belum negacu pada pembebanan
    "4675" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "4675" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6100010" => "harga",//biaya
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//beban harus dibayar
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
                            "6100010" => "harga",//biaya
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//beban harus dibayar
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
                        "comName" => "RekeningPembantuBiayaHarusDibayar",
                        "loop" => array(
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "harga",//beban harus dibayar
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiaya",
                        "loop" => array(
                            "6100010" => "harga",//biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "4675" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".biaya",
                            "produk_id" => ".0",
                            "nama" => "",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
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
    ),//done
    // config request biaya usaha CENTER ...
    "1677" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "gudangID" => "gudang",
                "gudangName" => "gudang__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
                "cabangRef_id" => "cabang2_id",
                "cabangRef_nama" => "cabang2_nama",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "biaya_cabang_item" => "allowed_ext*harga",

            ),
            "master_dependent" => array(
                "allowed_ext" => array(
                    1 => array(
                        "kas_out" => "harga",
                        "biaya_cabang" => "harga",
                        "biaya_pusat" => ".0",
                    ),
                    0 => array(
                        "kas_out" => ".0",
                        "biaya_cabang" => ".0",
                        "biaya_pusat" => "harga",
                    ),
                ),
            )
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "1677r" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6010" => "biaya_pusat",//biaya usaha
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "biaya_pusat",//beban harus dibayar
                            "1010010010" => "-kas_out",
                            "1010060040" => "biaya_cabang",
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
                            "6010" => "biaya_pusat",//biaya usaha
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "biaya_pusat",//beban harus dibayar
                            "1010010010" => "-kas_out",
                            "1010060040" => "biaya_cabang",
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
                        "comName" => "RekeningPembantuBiayaHarusDibayar",
                        "loop" => array(
//                            "2010040" => "harga",//hutang biaya
                            "2010090020" => "biaya_pusat",//beban harus dibayar
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",// transaksi terjadi di dc/pusat
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-kas_out",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account_ref",// diisi id bank
                            "extern_nama" => "cash_account_nama_ref",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060040" => "biaya_cabang",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "cabang2_id",
                            "cabang2_nama" => "cabang2_nama",
                            "extern_id" => "cabang2_id",
                            "extern_nama" => "cabang2_nama",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //region jurnal cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040020" => "biaya_cabang",// hutang biaya ke pusat
                            "6010" => "biaya_cabang",// baiya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2_id",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040020" => "biaya_cabang",// hutang biaya ke pusat
                            "6010" => "biaya_cabang",// biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2_id",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "biaya_cabang",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2_id",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "6010" => "harga",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "6010" => "biaya_cabang_item",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "cabangRef_id",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(),
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
    ),//done
    // config supplies yang dibiayakan
    "762" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",
        ),
        "formatNota" => "stepCode|placeID|cabangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "customerID" => "pihakID",
                "customerName" => "pihakName",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock"
            //            ),
            //            3 => array(
            //                "LockerStock"
            //            ),
        ),
        "preProcessor" => array(
            "762" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "qty",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "detailValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),
        "components" => array(
            "762" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-hpp",//persediaan supplies
                            "{pihak2Coa_code}" => "hpp",//biaya usaha,umum,produksi
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
                            "1010030010" => "-hpp",//persediaan supplies
                            "{pihak2Coa_code}" => "hpp",//biaya usaha,umum,produksi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    //jurnal ppv pusat
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "2010090010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "2010090010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // detail laba lain-lain
//                    array(
//                        "comName" => "RekeningPembantuLRLainlain",
//                        "loop" => array(
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => ".3",// laba rugi lain-lain ppv
//                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                ),
                "detail" => array(
                    //<editor-fold desc="Com-rekening pembantu, detail">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",//persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "{pihak2Com}",
                        "loop" => array(
                            "{pihak2Coa_code}" => "sub_hpp",//pembantu biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihak3ID",
                            "extern_nama" => "pihak3Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),
        ),
        "postProcessor" => array(
            "762r" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Postproc locker stock">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            //"transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),
            "762" => array(
                "master" => array(),
                "detail" => array(
                    //region locker stok
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".sold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabangID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabangID",
    ),//belum
    // biaya usaha
    "9982" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|placeID|olehID|customerID",
            //            "stepCode|customerID",
            //            "stepCode|placeID|customerID",
            //            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "transaksi__cabang2_id",
                "place2Name" => "transaksi__cabang2_nama",
                "gudang2ID" => "gudang",
                "gudang2Name" => "gudang__label",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
                //
                //                "qty" => "jml",
                //
                //                "nett1" => "(harga-disc)",
                //                "ppn" => "(nett1*(10/100))",
                //                "nett2" => "(nett1+ppn)",
                //
            ),
            "master_dependent" => array(
                "biayaKategori" => array(
                    "1" => array(
                        "biaya_usaha_nilai" => "harga_disc",
                        "biaya_umum_nilai" => ".0",
                        "biayausaha_coa_code" => ".6010",
                        "biayaumum_coa_code" => ".6030",
                    ),//biaya usaha
                    "2" => array(
                        "biaya_usaha_nilai" => ".0",
                        "biaya_umum_nilai" => "harga_disc",
                        "biayausaha_coa_code" => ".6010",
                        "biayaumum_coa_code" => ".6030",
                    ),//biaya umum
                ),

            ),
        ),
        "valueBuilders" => array(
            //            "bruto" => "sub_harga",
            //            "hpp" => "sub_hpp",
            //            "nett" => "sub_nett",
            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            "tagihan" => "grand_total-discount-dp",
        ),
        "valueBuilders_rsltItems" => array(),
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "referensi_id" => "referenceID",

                "pembayaran" => "paymentMethod",
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
                "produk_jenis" => "invoice",
            ),
        ),
        "components" => array(
            "9982" => array(
                "master" => array(
//                    // pusat
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "6100010" => "-harga_disc",//biaya
//                            "1010060040" => "harga_disc",//piutang biaya cabang
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
//                            "6100010" => "-harga_disc",//biaya
//                            "1010060040" => "harga_disc",//piutang biaya cabang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    //pembantu antar cabang
//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
                    //                        "loop" => array(
//                            "1010060040" => "harga_disc",//piutang biaya cabang
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
//                            "extern_id" => "place2ID",
//                            "extern_nama" => "place2Name",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                    // cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6010" => "biaya_usaha_nilai",//biaya usaha
                            "6030" => "biaya_umum_nilai",//biaya umum
                            "6100010" => "-harga_disc",//hutang biaya ke pusat
                            //                            "ppn in jasa" => "ppn",
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
                        "comName" => "Rekening",
                        "loop" => array(
                            "6010" => "biaya_usaha_nilai",//biaya usaha
                            "6030" => "biaya_umum_nilai",//biaya umum
                            "6100010" => "-harga_disc",//hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekeningpembantu biaya usaha main
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "{biayausaha_coa_code}" => "biaya_usaha_nilai",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_detail",
                            "extern_nama" => "biaya_detail__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekeningpembantu biaya umum main

                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "{biayaumum_coa_code}" => "biaya_umum_nilai",//biaya umum
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_detail",
                            "extern_nama" => "biaya_detail__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    // pusat
                    array(
                        "comName" => "RekeningPembantuBiaya",
                        "loop" => array(
                            "6100010" => "-harga_disc",//biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "9982r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "TransaksiItemUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".invoice",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "transaksi_id" => "referenceID",
                            "sinkron" => "seluruhnya",
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
    ),//done
    // biaya umum
    "9983" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|placeID|olehID|customerID",
            //            "stepCode|customerID",
            //            "stepCode|placeID|customerID",
            //            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "gudang2ID" => "gudang",
                "gudang2Name" => "gudang__label",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
                //
                //                "qty" => "jml",
                //
                //                "nett1" => "(harga-disc)",
                //                "ppn" => "(nett1*(10/100))",
                //                "nett2" => "(nett1+ppn)",
                //
            ),
        ),
        "valueBuilders" => array(
            //            "bruto" => "sub_harga",
            //            "hpp" => "sub_hpp",
            //            "nett" => "sub_nett",
            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            "tagihan" => "grand_total-discount-dp",
        ),
        "valueBuilders_rsltItems" => array(),
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "referensi_id" => "referenceID",

                "pembayaran" => "paymentMethod",
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
                "produk_jenis" => "invoice",
            ),
        ),
        "components" => array(
            "9983" => array(
                "master" => array(
                    // pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6100010" => "-harga_disc",//biaya
                            "1010060040" => "harga_disc",//piutang biaya cabang
                            //                            "ppn in jasa" => "-ppn",
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
                            "6100010" => "-harga_disc",//biaya
                            "1010060040" => "harga_disc",//piutang biaya cabang
                            //                            "ppn in jasa" => "-ppn",
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
                            "1010060040" => "harga_disc",//piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "ppn in jasa" => "-ppn",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "supplierID",
                    //                            "extern_nama" => "supplierName",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                    // cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6030" => "harga_disc",//biaya umum
                            "2040020" => "harga_disc",//hutang biaya ke pusat
                            //                            "ppn in jasa" => "ppn",
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
                        "comName" => "Rekening",
                        "loop" => array(
                            "6030" => "harga_disc",//biaya umum
                            "2040020" => "harga_disc",//hutang biaya ke pusat
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "harga_disc",//hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "ppn in jasa" => "ppn",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "place2ID",
                    //                            "extern_id" => "supplierID",
                    //                            "extern_nama" => "supplierName",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),


                ),
                "detail" => array(
                    // pusat
                    array(
                        "comName" => "RekeningPembantuBiaya",
                        "loop" => array(
                            "6100010" => "-harga_disc",//biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // cabang
                    array(
                        "comName" => "RekeningPembantuBiayaUmum",
                        "loop" => array(
                            "6030" => "harga_disc",//biaya umum
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihak2ID",
                            "extern_nama" => "pihak2Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "9983r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "TransaksiItemUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".invoice",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "transaksi_id" => "referenceID",
                            "sinkron" => "seluruhnya",
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
    // biaya poduksi
    "9984" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|placeID|olehID|customerID",
            //            "stepCode|customerID",
            //            "stepCode|placeID|customerID",
            //            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "gudang2ID" => "gudang",
                "gudang2Name" => "gudang__label",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
                //
                //                "qty" => "jml",
                //
                //                "nett1" => "(harga-disc)",
                //                "ppn" => "(nett1*(10/100))",
                //                "nett2" => "(nett1+ppn)",
                //
            ),
        ),
        "valueBuilders" => array(
            //            "bruto" => "sub_harga",
            //            "hpp" => "sub_hpp",
            //            "nett" => "sub_nett",
            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            "tagihan" => "grand_total-discount-dp",
        ),
        "valueBuilders_rsltItems" => array(),
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "referensi_id" => "referenceID",

                "pembayaran" => "paymentMethod",
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
                "produk_jenis" => "invoice",
            ),
        ),
        "components" => array(
            "9984" => array(
                "master" => array(
                    // pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6100010" => "-harga_disc",//biaya
                            "1010060040" => "harga_disc",//piutang biaya cabang
                            //                            "ppn in jasa" => "-ppn",
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
                            "6100010" => "-harga_disc",//biaya
                            "1010060040" => "harga_disc",//piutang biaya cabang
                            //                            "ppn in jasa" => "-ppn",
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
                            "1010060040" => "harga_disc",//piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "ppn in jasa" => "-ppn",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "extern_id" => "supplierID",
                    //                            "extern_nama" => "supplierName",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                    // cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6020" => "harga_disc",//biaya produksi
                            "2040020" => "harga_disc",//hutang biaya ke pusat
                            //                            "ppn in jasa" => "ppn",
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
                        "comName" => "Rekening",
                        "loop" => array(
                            "6020" => "harga_disc",//biaya produksi
                            "2040020" => "harga_disc",//hutang biaya ke pusat
                            //                            "ppn in jasa" => "ppn",
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "harga_disc",//hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //                    array(
                    //                        "comName" => "RekeningPembantuSupplier",
                    //                        "loop" => array(
                    //                            "ppn in jasa" => "ppn",
                    //                        ),
                    //                        "static" => array(
                    //                            "cabang_id" => "place2ID",
                    //                            "extern_id" => "supplierID",
                    //                            "extern_nama" => "supplierName",
                    //                            "jenis" => "jenisTr",
                    //                            "transaksi_no" => "nomer",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                    // biaya produksi vs efisiensi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6020" => "-harga_disc",//biaya produksi
                            "3020010" => "-harga_disc",//efisiensi biaya

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
                        "comName" => "Rekening",
                        "loop" => array(
                            "6020" => "-harga_disc",//biaya produksi
                            "3020010" => "-harga_disc",//efisiensi biaya
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
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-harga_disc",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
//                            "extern_id" => "costID_coa",
//                            "extern_nama" => "costNameCoa",
                            "extern_id" => "costID",
                            "extern_nama" => "costName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // pusat
                    array(
                        "comName" => "RekeningPembantuBiaya",
                        "loop" => array(
                            "6100010" => "-harga_disc",//biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // cabang
                    array(
                        "comName" => "RekeningPembantuBiayaProduksi",
                        "loop" => array(
                            "6020" => "harga_disc",//biaya produksi
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihak2ID",
                            "extern_nama" => "pihak2Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaProduksi",
                        "loop" => array(
                            "6020" => "-harga_disc",//biaya produksi
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihak2ID",//ini perlu cek ulang masih ke id atau COA
                            "extern_nama" => "pihak2Name",//
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-harga_disc",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
//                            "extern2_id" => "pihak2ID",
//                            "extern2_nama" => "pihak2Name",
//                            "extern_id" => "costID_coa",//coa ID
//                            "extern_nama" => "costNameCoa",//coa label
                            "extern_id" => "pihak2ID",
                            "extern_nama" => "pihak2Name",
//                            "extern2_id" => "costID_coa",//coa ID
//                            "extern2_nama" => "costNameCoa",//coa label
                            "extern2_id" => "costID",
                            "extern2_nama" => "costName",//coa label
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "9984r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "TransaksiItemUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".invoice",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "transaksi_id" => "referenceID",
                            "sinkron" => "seluruhnya",
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
    ),//done
    "9985" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",
        ),
        "formatNota" => "stepCode|placeID|cabangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "gudang2ID" => "gudang",
                "gudang2Name" => "gudang__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock"
            //            ),
            //            3 => array(
            //                "LockerStock"
            //            ),
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
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "detailValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),
        "components" => array(
            "9985" => array(
                "master" => array(
                    //<editor-fold desc="Com-jurnal dan rekening /// center">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihak2Coa_code}" => "harga_disc",
                            "6010" => "-harga_disc",//biaya usaha
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
                            "{pihak2Coa_code}" => "harga_disc",
                            "6010" => "-harga_disc",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //</editor-fold>


                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "6010" => "-sub_harga_disc",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihak3IDSrc",
                            "extern_nama" => "pihak3NameSrc",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "{pihak2Com}",
                        "loop" => array(
                            "{pihak2Coa_code}" => "sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihak3ID",
                            "extern_nama" => "pihak3Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                ),
            ),
        ),
        "postProcessor" => array(
            "9985r" => array(
                "master" => array(),
                "detail" => array(


                    array(
                        "comName" => "TransaksiItemUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".invoice",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "transaksi_id" => "referenceID",
                            "sinkron" => "seluruhnya",
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
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabangID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabangID",
    ),
    //koreksi biaya ke ppv
    "9922" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            //            "stepCode|placeID|olehID|customerID",
            //            "stepCode|customerID",
            //            "stepCode|placeID|customerID",
            //            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "customerID" => "pihakID",
                //                "customerName" => "pihakName",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "transaksi__cabang2_id",
                "place2Name" => "transaksi__cabang2_nama",
                "gudang2ID" => "gudang",
                "gudang2Name" => "gudang__label",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
                //
                "qty" => "jml",
                //
                //                "nett1" => "(harga-disc)",
                //                "ppn" => "(nett1*(10/100))",
                //                "nett2" => "(nett1+ppn)",
                //
            ),
            "master_dependent" => array(
                "biayaKategori" => array(
                    "1" => array(
                        "biaya_usaha_nilai" => "harga_disc",
                        "biaya_umum_nilai" => ".0",
                        "biayausaha_coa_code" => ".6010",
                        "biayaumum_coa_code" => ".6030",
                    ),//biaya usaha
                    "2" => array(
                        "biaya_usaha_nilai" => ".0",
                        "biaya_umum_nilai" => "harga_disc",
                        "biayausaha_coa_code" => ".6010",
                        "biayaumum_coa_code" => ".6030",
                    ),//biaya umum
                ),

            ),
        ),
        "valueBuilders" => array(
            //            "bruto" => "sub_harga",
            //            "hpp" => "sub_hpp",
            //            "nett" => "sub_nett",
            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            "tagihan" => "grand_total-discount-dp",
        ),
        "valueBuilders_rsltItems" => array(),
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
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "referensi_id" => "referenceID",

                "pembayaran" => "paymentMethod",
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
                "produk_jenis" => "invoice",
            ),
        ),
        "components" => array(
            "9922" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6100010" => "-harga",//biaya
                            "2010090010" => "-harga",//hutang lain ppv
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
                            "6100010" => "-harga",//biaya
                            "2010090010" => "-harga",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiaya",
                        "loop" => array(
                            "6100010" => "-harga",//biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "9922r" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".biaya",
                            "produk_id" => ".0",
                            //                            "nama" => "",
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
                            "jenis" => ".biaya",
                            "produk_id" => ".0",
                            //                            "nama" => "",
                            "nilai" => "harga",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "9922" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".biaya",
                            "produk_id" => ".0",
                            //                            "nama" => "",
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
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".transfered",
                            "jenis" => ".biaya",
                            "produk_id" => ".0",
                            //                            "nama" => "",
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
    //config request biaya bunga kepemegang saham
    "4449" => array(
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

            ),
            "detail" => array(//===sumber nilai berupa rincian
                //                "grand_total" => "nilai_bunga+nilai_pph23"
            ),
            "master_dependent" => array(),
        ),
        "valueBuilders" => array(
            //            "totalCredit" => "creditAmount+creditValue",
            //            "nilai_bayar" => "bayar_total+totalCredit+nilai_entry-diskon_factor",
            //            "additionalFactor" => "additional_value*additional",
            //            "nilai_dipakai" => "nilai_entry-additional_expense",
            "nilai_kas_dipakai" => "nilai_bunga-nilai_pph23"
        ),
        "valuePopulator" => array(
            //            "valueSrc" => "nilai_bayar",
            //            "acuanSrc" => ".sisa",
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
        ),
        "additionalBuilders" => array(//==per-item
            //            "new_sisa" => "sisa-nilai_bayar",
        ),
        "additionalMainBuilders" => array(//==per-item
            //            "harus_bayar" => "sisa-totalCredit",
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
                "nomer_top2" => "nomer_top2",
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
            "4449" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6060" => "nilai_bunga",//biaya bunga
                            "2010070" => "nilai_kas_dipakai",//hutang biaya bunga
                            "2030030" => "nilai_pph23",//hutang pph23
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
                            "6060" => "nilai_bunga",//biaya bunga
                            "2010070" => "nilai_kas_dipakai",//hutang biaya bunga
                            "2030030" => "nilai_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuHutangBiayaBunga",
                        "loop" => array(
                            "2010070" => "nilai_kas_dipakai",//hutang biaya bunga
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
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuLoanItem",
                        "loop" => array(
                            "6060" => "nilai_bunga",//biaya bunga
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuPph",
                        "loop" => array(
                            "2030030" => "nilai_pph23",//hutang pph23
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
                ),
            ),
        ),
        "postProcessor" => array(
            "4449" => array(
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
                            "label" => ".hutang ke pemengang saham",
                            "terbayar" => "creditAmount",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

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
                    //                            "nilai" => "-nilai_bunga",
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
                    //                            "nilai" => "nilai_bunga",
                    //                            "transaksi_id" => ".0",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "main",
                    //                        "srcRawGateName" => "main",
                    //                    ),

                ),
                "detail" => array(
                    //                    array(
                    //                        "comName" => "PaymentSourceDetail",//untuk nilis ke payment source karena gerbang dari detail, di trnasksi misc di off kan ya bro
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "cabang_nama" => "cabangName",
                    //                            "extern_id" => "id",
                    //                            "extern_nama" => "name",
                    //                            "label" => ".hutang ke pemegang saham",
                    //                            "jenis" => "jenisTr",
                    //                            "target_jenis" => ".4410",
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
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",
            "stepCode|placeID|pihakID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|pihakID",
            "stepCode|masterID|placeID|pihakID",
        ),
        "formatNotaEdit" => "stepCode|placeID|pihakID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|pihakID",
            "stepCode|placeID|pihakID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|pihakID",
            "stepCode|masterID|placeID|pihakID",
        ),
        "formatNotaReject" => "stepCode|placeID|pihakID",
    ),
    //config biaya jasa pusat
    "119" => array(
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
                "harga_disc" => "harga-disc",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                "hpp_nppn" => "harga_disc-ppn",
                "nett" => "hpp_nppn",
            ),
            "master_dependent" => array(
                "pihakMainRulesID" => array(
                    "pph23" => array(
                        "nilai_pph23" => "ppn",
                        "nilai_pph21" => 0,
                    ),
                    "pph21" => array(
                        "nilai_pph23" => 0,
                        "nilai_pph21" => "ppn",
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
                "produk_jenis" => "service",
            ),
        ),
        "components" => array(
            "119" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainCoa}" => "harga",
                            "{taxesMethodCoa}" => "ppn",
//                            "2010040" => "nett",//hutang biaya
                            "2010090020" => "nett",//beban harus dibayar
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
                            "{pihakMainCoa}" => "harga",
                            "{taxesMethodCoa}" => "ppn",
//                            "2010040" => "nett",//hutang biaya
                            "2010090020" => "nett",//beban harus dibayar
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
                        "comName" => "BiayaHarusDibayar",
                        "loop" => array(
//                            "2010040" => "nett",//hutang biaya
                            "2010090020" => "nett",//hutang biaya
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "{comName_items}",
                        "loop" => array(
                            "{pihakMainCoa}" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(),
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
    ),//tidak dipakai
    // config request biaya produksi...
    "676" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                //                "pihakID" => "olehID",
                //                "pihakName" => "olehName",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
        "components" => array(),
        "postProcessor" => array(),
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
            "stepCode|supplierID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|supplierID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),

    "2762" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",
        ),
        "formatNota" => "stepCode|placeID|cabangID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",

                "pihakMainName" => "pihak2Name",
                "relativeCom" => "pihak2Com",
                "rekName" => "pihak2Name",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
                "pihakMainName" => "pihak2Name",
                "relativeCom" => "pihak2Com",
                "rekName" => "pihak2Name",

            ),
            "rsltItems" => array(
                //===sumber nilai berupa rincian
                "nilai_bayar" => "hpp",
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock"
            //            ),
            //            3 => array(
            //                "LockerStock"
            //            ),
        ),
        "preProcessorInjector" => array(
            "placeID" => "cabang_id",
            "gudangID" => "gudang_id",
            "pihak3ID" => "pihak3ID",
            "pihak3Name" => "pihak3Name",
            "jenisTr" => "jenisTr",
        ),
        "preProcessor" => array(
            "2762" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",

                            "jenisTr" => "jenisTr",
                            "pihak3ID" => "pihak3ID",
                            "pihak3Name" => "pihak3Name",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "nama",
                                "name" => "nama",
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "qty",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "ProduksiPreBiaya",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "cabang2_id" => "placeID",
                            "gudang2_id" => "gudangID",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "hpp*jml",
                            "hpp" => "hpp",
                            //                            "nilai" => "sub_hpp",
                            "jenisTr" => "jenisTr",
                            "pre_biaya_id" => "pihak3ID",
                            "pre_biaya_nama" => "pihak3Name",
                            "rowPreFifo" => "rowPreFifo",
                        ),
                        "resultParams" => array(
//                            "items2_sum" => array(
                            "rsltItems" => array(
                                "costID" => "pre_biaya_id",
                                "costName" => "pre_biaya_nama",
//                                "costNilai" => "nilai",
                                "costNilai" => "hpp",
                            ),
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "detailValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "hpp",
                "hpp" => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan" => "satuan",
            ),
            "rsltItemsValues" => array(
                "hpp" => "hpp",
                "harga" => "harga",
                //                "nett" => "hpp",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),
        "components" => array(
            "2762" => array(
                "master" => array(
                    //<editor-fold desc="Com-jurnal dan rekening">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-hpp",//persediaan supplies
                            "{pihak2Coa_code}" => "hpp",//berisi kode coa
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
                            "1010030010" => "-hpp",//persediaan supplies
                            "{pihak2Coa_code}" => "hpp",//berisi kode coa
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
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainNameCoa_rev}" => "-subtotal_rev",//biaya produksi/umum/usaha
//                            "{cost2IdCoa_1}" => "costNilai_1",// costNilai_1, quality, direct labor, delivery cost
//                            "{cost2IdCoa_2}" => "costNilai_2",// costNilai_2, quality, direct labor, delivery cost
//                            "{cost2IdCoa_3}" => "costNilai_3",// costNilai_3, quality, direct labor, delivery cost
                            "{costIdCoa_1}" => "costNilai_1",// costNilai_1, quality, direct labor, delivery cost
                            "{costIdCoa_2}" => "costNilai_2",// costNilai_2, quality, direct labor, delivery cost
                            "{costIdCoa_3}" => "costNilai_3",// costNilai_3, quality, direct labor, delivery cost
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
                            "{pihakMainNameCoa_rev}" => "-subtotal_rev",//biaya produksi/umum/usaha
//                            "{cost2IdCoa_1}" => "costNilai_1",// costNilai_1, quality, direct labor, delivery cost
//                            "{cost2IdCoa_2}" => "costNilai_2",// costNilai_2, quality, direct labor, delivery cost
//                            "{cost2IdCoa_3}" => "costNilai_3",// costNilai_3, quality, direct labor, delivery cost
                            "{costIdCoa_1}" => "costNilai_1",// costNilai_1, quality, direct labor, delivery cost
                            "{costIdCoa_2}" => "costNilai_2",// costNilai_2, quality, direct labor, delivery cost
                            "{costIdCoa_3}" => "costNilai_3",// costNilai_3, quality, direct labor, delivery cost
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    // masuk ke efisiensi, pabrik
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020010" => "-subtotal_rev",//efisiensi
//                            "{cost2IdCoa_1}" => "-costNilai_1",// costNilai_1, quality, direct labor, delivery cost
//                            "{cost2IdCoa_2}" => "-costNilai_2",// costNilai_2, quality, direct labor, delivery cost
//                            "{cost2IdCoa_3}" => "-costNilai_3",// costNilai_3, quality, direct labor, delivery cost
                            "{costIdCoa_1}" => "-costNilai_1",// costNilai_1, quality, direct labor, delivery cost
                            "{costIdCoa_2}" => "-costNilai_2",// costNilai_2, quality, direct labor, delivery cost
                            "{costIdCoa_3}" => "-costNilai_3",// costNilai_3, quality, direct labor, delivery cost
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
                            "3020010" => "-subtotal_rev",//efisiensi
//                            "{cost2IdCoa_1}" => "-costNilai_1",// costNilai_1, quality, direct labor, delivery cost
//                            "{cost2IdCoa_2}" => "-costNilai_2",// costNilai_2, quality, direct labor, delivery cost
//                            "{cost2IdCoa_3}" => "-costNilai_3",// costNilai_3, quality, direct labor, delivery cost
                            "{costIdCoa_1}" => "-costNilai_1",// costNilai_1, quality, direct labor, delivery cost
                            "{costIdCoa_2}" => "-costNilai_2",// costNilai_2, quality, direct labor, delivery cost
                            "{costIdCoa_3}" => "-costNilai_3",// costNilai_3, quality, direct labor, delivery cost
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //region blok efisiensi biaya, category
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "3020010" => "-costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
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
                            "3020010" => "-costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
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
                            "3020010" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
                            "extern_id" => "costID_3",
                            "extern_nama" => "costName_3",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

//                    //jurnal ppv pusat
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "2010090010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "2010090010" => "-ppv_riil",// hutang lain ppv
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // detail laba lain-lain
//                    array(
//                        "comName" => "RekeningPembantuLRLainlain",
//                        "loop" => array(
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => ".3",// laba rugi lain-lain ppv
//                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                ),
                "detail" => array(
                    //<editor-fold desc="Com-rekening pembantu, detail">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",//persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "{relativeCom}",
                        "loop" => array(
//                            "{pihakMainName}" => "sub_hpp",
                            "{pihakMainNameCoa}" => "sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihak3ID",
                            "extern_nama" => "pihak3Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // kalau cabang produksi ini jalan juga
                    array(
                        "comName" => "{pihak2Com}",
                        "loop" => array(
//                            "{pihak2Name}" => "-subtotal_rev",
                            "{pihak2Coa_code}" => "-subtotal_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihak3ID",
                            "extern_nama" => "pihak3Name",
                            "jenis" => "jenisTr",
                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
//                            "{costName_1}" => "costNilai_1",
//                            "{costName_2}" => "costNilai_2",
//                            "{costName_3}" => "costNilai_3",
                            "{costIdCoa_1}" => "costNilai_1",
                            "{costIdCoa_2}" => "costNilai_2",
                            "{costIdCoa_3}" => "costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihak3ID",
                            "extern_nama" => "pihak3Name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "subtotal_rev", // harga
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //</editor-fold>

                    // mengeluarkan quality, direct labor, delivery cost
                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{costIdCoa_1}" => "-costNilai_1",
                            "{costIdCoa_2}" => "-costNilai_2",
                            "{costIdCoa_3}" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihak3ID",
                            "extern_nama" => "pihak3Name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "subtotal_rev", // harga
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //</editor-fold>

                    //region blok pembantu sub efisiensi biaya
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_1",
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_1",
                            "extern2_nama" => "costName_1",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_2",
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_2",
                            "extern2_nama" => "costName_2",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_2",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-costNilai_3",
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "costID_3",
                            "extern2_nama" => "costName_3",
                            "produk_qty" => "jml",
                            "produk_nilai" => "costNilai_3",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),
                    //endregion


                ),
            ),
        ),
        "postProcessor" => array(
            "2762r" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Postproc locker stock">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            //"transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                ),
            ),
            "2762" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".sold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
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
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabangID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabangID",
            "stepCode|placeID|cabangID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabangID",
            "stepCode|masterID|placeID|cabangID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabangID",
    ),
    //config pendapatan lain-lain
    "742" => array(
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

                "customerID" => "pihakID",
                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "nilai_masuk" => "(nilai_bayar*100)/110",
                "ppn" => "nilai_masuk*(10/100)",
                "nilai_cash" => "nilai_bayar",
            ),
        ),
        "valueBuilders" => array(
            //            "nilai_bayar" => "nilai_masuk+ppn",
            "nilai_bayar" => "nilai_entry",
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
                "transaksi_nilai" => "harga",
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
            "742" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "subtotal",//kas
//                            "7010150" => "subtotal",//pendapatan lain_lain
                            "7010170" => "subtotal",//pendapatan lain_lain
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
                            "1010010010" => "subtotal",//kas
//                            "7010150" => "subtotal",//pendapatan lain_lain
                            "7010170" => "subtotal",//pendapatan lain_lain
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
                            "1010010010" => "subtotal",//kas
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
                        "comName" => "RekeningPembantuPendapatanItem",
                        "loop" => array(
//                            "7010150" => "harga",//tembak dulu//pendapatan lain_lain
                            "7010170" => "harga",//tembak dulu//pendapatan lain_lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_nilai" => "harga",
                            "produk_qty" => ".1",
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
            "742" => array(
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
                            "nilai" => "subtotal",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
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
    ),//done
    //config beban lain lain
    "743" => array(
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

                "customerID" => "pihakID",
                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "nilai_masuk" => "(nilai_bayar*100)/110",
                "ppn" => "nilai_masuk*(10/100)",
                "nilai_cash" => "nilai_bayar",
            ),
        ),
        "valueBuilders" => array(
            //            "nilai_bayar" => "nilai_masuk+ppn",
            "nilai_bayar" => "nilai_entry",
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
                "transaksi_nilai" => "harga",
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
            "743" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "-subtotal",//kas
                            "7020010" => "subtotal",//beban lain lain
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
                            "1010010010" => "-subtotal",//kas
                            "7020010" => "subtotal",//beban lain lain
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
                            "1010010010" => "-subtotal",//kas
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
                        "comName" => "RekeningPembantuBebanLainLainItem",
                        "loop" => array(
                            "7020010" => "subtotal",//beban lain lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",// diisi id bank
                            "extern_nama" => "name",// diisi nama bank
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "743" => array(
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
                            "nilai" => "-subtotal",
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
                            "nilai" => "subtotal",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
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
    ),//done
    //config salary expense pusat
    "2674" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "subtotal" => "jml*harga",
            ),
        ),
        "valueBuilders" => array(
            //biayagaji hutanggaji+bpjskaryawan+bpjs perusahaan
            "biaya_gaji_main" => "hutang_gaji",
            "hutang_bpjs_main" => "hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
            "take_homepay" => "hutang_gaji_main",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "2674" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6050" => "biaya_gaji_main",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
                            "2010080" => "hutang_gaji_main",//hutang gaji
                            "2030010" => "hutang_pph21_main",//hutang pph21
                            "2010060" => "hutang_bpjs_main",//hutang bpjs
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
                            "6050" => "biaya_gaji_main",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
                            "2010080" => "hutang_gaji_main",//hutang gaji
                            "2030010" => "hutang_pph21_main",//hutang pph21
                            "2010060" => "hutang_bpjs_main",//hutang bpjs
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
                            "2010080" => "hutang_gaji_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    /*
                     * pembantu biaya
                     */
                    //pembantu biaya gaji
                    array(
                        "comName" => "RekeningPembantuBiayaGaji",
                        "loop" => array(
                            "6050" => "biaya_gaji_main",//biaya gaji
                            //                            "hutang gaji ke pusat" => ".0",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeName",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu biaya pph21
                    array(
                        "comName" => "RekeningPembantuBiayaPph21",
                        "loop" => array(
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
                            //                            "hutang gaji ke pusat" => ".0",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeName",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu biaya bpjs
                    array(
                        "comName" => "RekeningPembantuBiayaBpjs",
                        "loop" => array(
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
                            //                            "hutang gaji ke pusat" => ".0",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeName",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),//done
    //efisiensi project biayagaji
    "3674" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "place2ID" => ".-1",
//                "place2Name" => ".PUSAT",
//                "gudang2ID" => ".-1",
//                "gudang2Name" => ".PUSAT",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "subtotal" => "jml*harga",
            ),
            //11 internal->tidak terbit hutang
            //22 vendor
            "master_dependent" => array(
                "type_pelaksana" => array(
                    "11" => array(
                        "piutang_tambah" => ".0",

                    ),
                    "22" => array(
                        "piutang_tambah" => "harga_tambahan",
                    ),
                ),
            ),
        ),
        /*
 * CATATAN UNTUK PEMBUILD GERBANG hanya menggunakan 1 underscores contoh biaya_jasa . biaya_jasa_lima(ini tidak diperbolehkan)
 */
        "valueBuilders" => array(),
        "additionalMainBuilders" => array(
            "place2ID" => ".-1",
            "place2Name" => ".PUSAT",
            "gudang2ID" => ".-1",
            "gudang2Name" => ".PUSAT",
        ),

        "preProcessor" => array(
            "3674" => array(
                "master" => array(
                    array(
                        "comName" => "SyncEfisiensi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "nilai" => "piutang_tambah",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "harga_efisiensi" => "nilai",

                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "SyncEfisiensi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "nilai" => "biaya_bpjs_perusahaan",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "efisiensi_bpjs" => "nilai",

                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "SyncEfisiensi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "nilai" => "biaya_pph21_perusahaan",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "efisiensi_pph21" => "nilai",

                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
                "subDetail" => array(),
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "3674" => array(
                "master" => array(
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060040" => "piutang_tambah",//piutang cabang
                            "2010090020" => "piutang_tambah",//beban harus dibayar pakai rekening ini karena tidak punya vendor
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
                            "1010060040" => "piutang_tambah",//piutang cabang
                            "2010090020" => "piutang_tambah",//beban harus dibayar pakai rekening ini karena tidak punya vendor
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
                            "1010060040" => "piutang_tambah",//piutang cabang
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
                        "comName" => "RekeningPembantuBiayaHarusDibayar",
                        "loop" => array(
                            "2010090020" => "piutang_tambah",//beban harus dubayar
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion pusat

                    // khusus di project masuk ke efisiensi biaya
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040020" => "piutang_tambah",//hutang biaya kepusat
                            "3020010" => "-piutang_tambah",//efisiensi biaya

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
                            "2040020" => "piutang_tambah",//hutang biaya kepusat
                            "3020010" => "-piutang_tambah",//efisiensi biaya
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
                            "2040020" => "piutang_tambah",//hutang biaya kepusat
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

                    //kalau reguler

                ),
                "detail" => array(),
                "sub_detail" => array(
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaSub",
                        "loop" => array(
                            "3020010" => "-biaya_tambahan",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_id",
                            "extern_nama" => "biaya_nama",
                            "extern2_id" => "cat_id",
                            "extern2_nama" => "cat_nama",
                            "extern3_id" => "project_id",//projectid
                            "extern3_nama" => "project_nama",
                            "extern4_id" => "wo_id",//wo id
                            "extern4_nama" => "wo_nama",
                            "extern5_id" => "biaya_dasar_id",//biayadetail
                            "extern5_nama" => "biaya_dasar_nama",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                ),

            ),
        ),
        "postProcessor" => array(
            "3674" => array(
                "master" => array(
                    //raw untuk laporan
//                    array(
//                        "comName" => "RekeningPembantuRawMainEfisiensi",
//                        "loop" => array(
//                            "3020010" => "-harga_efisiensi",//efisiensi biaya
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => ".3020010",//biaya
//                            "extern_nama" => ".efisensi",
//                            "extern2_nama" => ".quality",
//                            "extern2_id" => ".4",
//                            "extern3_id" => "pihakWoProjek",//projectid
//                            "extern3_nama" => "pihakWoProjekName",
//                            "extern4_id" => ".1",//biaya
//                            "extern4_nama" => ".gaji",
//                            "produk_id" => "pihakProjekID",//project
//                            "produk_nama" => "pihakProjekName",
//                            "produk_kode" => "pihakWoProjekSpk",
//                            "produk_jenis" => ".project",
////                            "barcode" => "barcode",
//                            "jml" => "1",
//                            "harga" => "harga_efisiensi",// harga dpp
//                            "hpp" => "harga_efisiensi",// hpp produk
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRawMainEfisiensi",
//                        "loop" => array(
//                            "3020010" => "-efisiensi_bpjs",//efisiensi biaya
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => ".3020010",//biaya
//                            "extern_nama" => ".efisensi",
//                            "extern2_nama" => ".quality",
//                            "extern2_id" => ".4",
//                            "extern3_id" => "pihakWoProjek",//projectid
//                            "extern3_nama" => "pihakWoProjekName",
//                            "extern4_id" => ".4",//biaya
//                            "extern4_nama" => ".biaya bpjs",
//                            "produk_id" => "pihakProjekID",//project
//                            "produk_nama" => "pihakProjekName",
//                            "produk_kode" => "pihakWoProjekSpk",
//                            "produk_jenis" => ".project",
////                            "barcode" => "barcode",
//                            "jml" => "1",
//                            "harga" => "efisiensi_bpjs",// harga dpp
//                            "hpp" => "efisiensi_bpjs",// hpp produk
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuRawMainEfisiensi",
//                        "loop" => array(
//                            "3020010" => "-efisiensi_pph21",//efisiensi biaya
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => ".3020010",//biaya
//                            "extern_nama" => ".efisensi",
//                            "extern2_nama" => ".quality",
//                            "extern2_id" => ".4",
//                            "extern3_id" => "pihakWoProjek",//projectid
//                            "extern3_nama" => "pihakWoProjekName",
//                            "extern4_id" => ".5",//biaya
//                            "extern4_nama" => ".biaya bpjs",
//                            "produk_id" => "pihakProjekID",//project
//                            "produk_nama" => "pihakProjekName",
//                            "produk_kode" => "pihakWoProjekSpk",
//                            "produk_jenis" => ".project",
////                            "barcode" => "barcode",
//                            "jml" => "1",
//                            "harga" => "efisiensi_pph21",// harga dpp
//                            "hpp" => "efisiensi_pph21",// hpp produk
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                ),
                "detail" => array(),
                "sub_detail" => array(
                    array(
                        "comName" => "RekeningPembantuRawItemEfisiensi",
                        "loop" => array(
                            "3020010" => "-biaya_tambahan",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".3020010",//biaya
                            "extern_nama" => ".efisensi",
                            "extern2_nama" => "cat_nama",
                            "extern2_id" => "cat_id",
                            "extern3_id" => "wo_id",//projectid
                            "extern3_nama" => "wo_nama",
                            "extern4_id" => "biaya_id",//biaya
                            "extern4_nama" => "biaya_nama",
                            "extern5_id" => "biaya_dasar_id",//biaya detail
                            "extern5_nama" => "biaya_dasar_nama",
                            "produk_id" => "project_id",//project
                            "produk_nama" => "project_nama",
                            "produk_kode" => "no_spk",
                            "produk_jenis" => ".project",
//                            "barcode" => "barcode",
                            "jml" => "jml",
                            "harga" => "harga_tambahan",// harga dpp
                            "hpp" => "harga_tambahan",// hpp produk
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),

    //-----------up sudah modul -----

    // config request biaya gaji done
    "1674_OLD" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "subtotal" => "jml*harga",
            ),
        ),
        /*
 * CATATAN UNTUK PEMBUILD GERBANG hanya menggunakan 1 underscores contoh biaya_jasa . biaya_jasa_lima(ini tidak diperbolehkan)
 */
        "valueBuilders" => array(
            //biayagaji hutanggaji+bpjskaryawan+bpjs perusahaan
            "piutang_cabang_main" => "hutang_gaji+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
            "hutang_bpjs_main" => "hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
//            "biaya_gaji_cabang" => "hutang_gaji",
            "biaya_gaji_main" => "hutang_gaji",
            "efisiensi_biaya_master" => "harga_efisiensi+efisiensi_bpjs+efisiensi_pph21",
            "grand_total" => "piutang_cabang_main",
            "take_homepay" => "hutang_gaji_main",
        ),

        "preProcessor" => array(
            "1674" => array(
                "master" => array(
                    array(
                        "comName" => "SyncEfisiensi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "nilai" => "biaya_gaji_cabang",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "harga_efisiensi" => "nilai",

                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "SyncEfisiensi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "nilai" => "biaya_bpjs_perusahaan",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "efisiensi_bpjs" => "nilai",

                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "SyncEfisiensi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "pihakID",
                            "nilai" => "biaya_pph21_perusahaan",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "efisiensi_pph21" => "nilai",

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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "1674" => array(
                "master" => array(
                    // PEMBEBANAN DI CABANG
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion pusat

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6050" => "biaya_gaji_cabang",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
                            "6050" => "biaya_gaji_cabang",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
                    //pembantu biaya gaji
                    array(
                        "comName" => "RekeningPembantuBiayaGaji",
                        "loop" => array(
                            "6050" => "biaya_gaji_cabang",//biaya gaji
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
                    //pembantu biaya pph21
                    array(
                        "comName" => "RekeningPembantuBiayaPph21",
                        "loop" => array(
                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
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
                    //pembantu biaya bpjs
                    array(
                        "comName" => "RekeningPembantuBiayaBpjs",
                        "loop" => array(
                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
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
                    //endregion


                    // PEMBEBANAN DI DC/PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6050" => "biaya_gaji_pusat",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
                            "6050" => "biaya_gaji_pusat",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu biaya gaji
                    array(
                        "comName" => "RekeningPembantuBiayaGaji",
                        "loop" => array(
                            "6050" => "biaya_gaji_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu biaya pph21
                    array(
                        "comName" => "RekeningPembantuBiayaPph21",
                        "loop" => array(
                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu biaya bpjs
                    array(
                        "comName" => "RekeningPembantuBiayaBpjs",
                        "loop" => array(
                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),

        ),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaReject" => "stepCode|placeID",
        //-----
        "rebuilderCoreKey" => "pihakPembebananCode",
        "rebuilderCore" => array(
            // pusat
            100 => array(
                "biaya_gaji_pusat" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_pusat" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_pusat" => "biaya_pph21_perusahaan",
                "hutang_gaji_pusat_main" => "hutang_gaji",
                "hutang_pph21_pusat_main" => "hutang_pph21_main",
                "hutang_bpjs_pusat_main" => "hutang_bpjs_main",

                "biaya_gaji_cabang" => ".0",
                "biaya_bpjs_perusahaan_cabang" => ".0",
                "biaya_pph21_perusahaan_cabang" => ".0",
                "hutang_gaji_cabang_main" => ".0",
                "hutang_pph21_cabang_main" => ".0",
                "hutang_bpjs_cabang_main" => ".0",

                "piutang_cabang_main_main" => ".0",
            ),
            // cabang
            111 => array(
                "biaya_gaji_pusat" => ".0",
                "biaya_bpjs_perusahaan_pusat" => ".0",
                "biaya_pph21_perusahaan_pusat" => ".0",
                "hutang_gaji_pusat_main" => ".0",
                "hutang_pph21_pusat_main" => ".0",
                "hutang_bpjs_pusat_main" => ".0",

                "biaya_gaji_cabang" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_cabang" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_cabang" => "biaya_pph21_perusahaan",
                "hutang_gaji_cabang_main" => "hutang_gaji",
                "hutang_pph21_cabang_main" => "hutang_pph21_main",
                "hutang_bpjs_cabang_main" => "hutang_bpjs_main",

                "piutang_cabang_main_main" => "piutang_cabang_main",
            ),
        ),
    ),
    "1674" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "subtotal" => "jml*harga",
            ),
            "master_dependent" => array(
                "biaya_option" => array(
                    "biaya_umum" => array(
                        "biaya_umum_total" => "biaya_bpjs_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main",
                        "biaya_usaha_total" => ".0",

                        "biaya_umum_bpjs_perusahaan" => "biaya_bpjs_perusahaan",
                        "biaya_umum_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_umum_gaji_main" => "biaya_gaji_main+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
                        "biaya_umum_gaji" => "biaya_gaji_main",

                        "biaya_usaha_bpjs_perusahaan" => ".0",
                        "biaya_usaha_pph21_perusahaan" => ".0",
                        "biaya_usaha_gaji_main" => ".0",
                        "biaya_usaha_gaji" => ".0",
                    ),
                    "biaya_usaha" => array(
                        "biaya_umum_total" => ".0",
                        "biaya_usaha_total" => "biaya_bpjs_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main",

                        "biaya_umum_bpjs_perusahaan" => ".0",
                        "biaya_umum_pph21_perusahaan" => ".0",
                        "biaya_umum_gaji_main" => ".0",
                        "biaya_umum_gaji" => ".0",

                        "biaya_usaha_bpjs_perusahaan" => "biaya_bpjs_perusahaan",
                        "biaya_usaha_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_usaha_gaji_main" => "biaya_gaji_main+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
                        "biaya_usaha_gaji" => "biaya_gaji_main",
                    ),
                ),
            ),
        ),
        /*
 * CATATAN UNTUK PEMBUILD GERBANG hanya menggunakan 1 underscores contoh biaya_jasa . biaya_jasa_lima(ini tidak diperbolehkan)
 */
        "valueBuilders" => array(
            //biayagaji hutanggaji+bpjskaryawan+bpjs perusahaan
            "piutang_cabang_main" => "hutang_gaji+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
            "hutang_bpjs_main" => "hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
            "biaya_gaji_main" => "hutang_gaji",
            "efisiensi_biaya_master" => "harga_efisiensi+efisiensi_bpjs+efisiensi_pph21",
            "grand_total" => "piutang_cabang_main",
            "take_homepay" => "hutang_gaji_main",
        ),

        "preProcessor" => array(
            "1674" => array(
                "master" => array(
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_gaji_cabang",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "harga_efisiensi" => "nilai",
//
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_bpjs_perusahaan",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "efisiensi_bpjs" => "nilai",
//
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_pph21_perusahaan",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "efisiensi_pph21" => "nilai",
//
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "1674" => array(
                "master" => array(
                    // PEMBEBANAN DI CABANG
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion pusat

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "6010" => "biaya_usaha_cabang",
                            "6030" => "biaya_umum_cabang",
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
//
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "6010" => "biaya_usaha_cabang",
                            "6030" => "biaya_umum_cabang",
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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

                    //pembantu biaya gaji
//                    array(
//                        "comName" => "RekeningPembantuBiayaGaji",
//                        "loop" => array(
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya pph21
//                    array(
//                        "comName" => "RekeningPembantuBiayaPph21",
//                        "loop" => array(
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya bpjs
//                    array(
//                        "comName" => "RekeningPembantuBiayaBpjs",
//                        "loop" => array(
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya usaha
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_main_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_bpjs_perusahaan_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pph21_perusahaan_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion
                    //pembantu biaya umum
                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_main_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_bpjs_perusahaan_cabang",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_pph21_perusahaan_cabang",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_cabang",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion


                    // PEMBEBANAN DI DC/PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "6010" => "biaya_usaha_pusat",
                            "6030" => "biaya_umum_pusat",
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
//
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "6010" => "biaya_usaha_pusat",
                            "6030" => "biaya_umum_pusat",
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //pembantu biaya gaji
//                    array(
//                        "comName" => "RekeningPembantuBiayaGaji",
//                        "loop" => array(
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya pph21
//                    array(
//                        "comName" => "RekeningPembantuBiayaPph21",
//                        "loop" => array(
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya bpjs
//                    array(
//                        "comName" => "RekeningPembantuBiayaBpjs",
//                        "loop" => array(
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya usaha
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_main_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_bpjs_perusahaan_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pph21_perusahaan_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //pembantu biaya umum
                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_main_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_bpjs_perusahaan_pusat",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_pph21_perusahaan_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),

        ),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaReject" => "stepCode|placeID",
        //-----
        "rebuilderCoreKey" => "pihakPembebananCode",
        "rebuilderCore" => array(
            // pusat
            100 => array(
                "biaya_gaji_pusat" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_pusat" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_pusat" => "biaya_pph21_perusahaan",
                "biaya_usaha_pusat" => "biaya_usaha_total",
                "biaya_umum_pusat" => "biaya_umum_total",

//                "hutang_gaji_pusat_main" => "hutang_gaji",
                "hutang_gaji_pusat_main" => "hutang_gaji_main",
                "hutang_pph21_pusat_main" => "hutang_pph21_main",
                "hutang_bpjs_pusat_main" => "hutang_bpjs_main",

                "biaya_gaji_cabang" => ".0",
                "biaya_bpjs_perusahaan_cabang" => ".0",
                "biaya_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_cabang" => ".0",
                "biaya_umum_cabang" => ".0",

                "hutang_gaji_cabang_main" => ".0",
                "hutang_pph21_cabang_main" => ".0",
                "hutang_bpjs_cabang_main" => ".0",

                "piutang_cabang_main_main" => ".0",

                "biaya_umum_bpjs_perusahaan_cabang" => ".0",
                "biaya_umum_pph21_perusahaan_cabang" => ".0",
                "biaya_umum_gaji_main_cabang" => ".0",
                "biaya_umum_gaji_cabang" => ".0",
                "biaya_usaha_bpjs_perusahaan_cabang" => ".0",
                "biaya_usaha_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_gaji_main_cabang" => ".0",
                "biaya_usaha_gaji_cabang" => ".0",

                "biaya_umum_bpjs_perusahaan_pusat" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_pusat" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_pusat" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_pusat" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_pusat" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_pusat" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_pusat" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_pusat" => "biaya_usaha_gaji",

            ),
            // cabang
            111 => array(
                "biaya_gaji_pusat" => ".0",
                "biaya_bpjs_perusahaan_pusat" => ".0",
                "biaya_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_pusat" => ".0",
                "biaya_umum_pusat" => ".0",

                "hutang_gaji_pusat_main" => ".0",
                "hutang_pph21_pusat_main" => ".0",
                "hutang_bpjs_pusat_main" => ".0",

                "biaya_gaji_cabang" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_cabang" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_cabang" => "biaya_pph21_perusahaan",
                "biaya_usaha_cabang" => "biaya_usaha_total",
                "biaya_umum_cabang" => "biaya_umum_total",

//                "hutang_gaji_cabang_main" => "hutang_gaji",
                "hutang_gaji_cabang_main" => "hutang_gaji_main",
                "hutang_pph21_cabang_main" => "hutang_pph21_main",
                "hutang_bpjs_cabang_main" => "hutang_bpjs_main",

                "piutang_cabang_main_main" => "piutang_cabang_main",

                "biaya_umum_bpjs_perusahaan_cabang" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_cabang" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_cabang" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_cabang" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_cabang" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_cabang" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_cabang" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_cabang" => "biaya_usaha_gaji",

                "biaya_umum_bpjs_perusahaan_pusat" => ".0",
                "biaya_umum_pph21_perusahaan_pusat" => ".0",
                "biaya_umum_gaji_main_pusat" => ".0",
                "biaya_umum_gaji_pusat" => ".0",
                "biaya_usaha_bpjs_perusahaan_pusat" => ".0",
                "biaya_usaha_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_gaji_main_pusat" => ".0",
                "biaya_usaha_gaji_pusat" => ".0",

            ),
        ),
    ),
    "11674" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__nama",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "subtotal" => "jml*harga",
            ),
            "master_dependent" => array(
                "biaya_option" => array(
                    "biaya_umum" => array(
                        "biaya_umum_total" => "biaya_bpjs_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main",
                        "biaya_usaha_total" => ".0",

                        "biaya_umum_bpjs_perusahaan" => "biaya_bpjs_perusahaan",
                        "biaya_umum_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_umum_gaji_main" => "biaya_gaji_main+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
                        "biaya_umum_gaji" => "biaya_gaji_main",

                        "biaya_usaha_bpjs_perusahaan" => ".0",
                        "biaya_usaha_pph21_perusahaan" => ".0",
                        "biaya_usaha_gaji_main" => ".0",
                        "biaya_usaha_gaji" => ".0",
                    ),
                    "biaya_usaha" => array(
                        "biaya_umum_total" => ".0",
                        "biaya_usaha_total" => "biaya_bpjs_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main",

                        "biaya_umum_bpjs_perusahaan" => ".0",
                        "biaya_umum_pph21_perusahaan" => ".0",
                        "biaya_umum_gaji_main" => ".0",
                        "biaya_umum_gaji" => ".0",

                        "biaya_usaha_bpjs_perusahaan" => "biaya_bpjs_perusahaan",
                        "biaya_usaha_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_usaha_gaji_main" => "biaya_gaji_main+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
                        "biaya_usaha_gaji" => "biaya_gaji_main",
                    ),
                ),
            ),
        ),
        /*
 * CATATAN UNTUK PEMBUILD GERBANG hanya menggunakan 1 underscores contoh biaya_jasa . biaya_jasa_lima(ini tidak diperbolehkan)
 */
        "valueBuilders" => array(
            //biayagaji hutanggaji+bpjskaryawan+bpjs perusahaan
            "piutang_cabang_main" => "hutang_gaji+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
            "hutang_bpjs_main" => "hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
            "biaya_gaji_main" => "hutang_gaji",
            "efisiensi_biaya_master" => "harga_efisiensi+efisiensi_bpjs+efisiensi_pph21",
            "grand_total" => "piutang_cabang_main",
            "take_homepay" => "hutang_gaji_main",
        ),

        "preProcessor" => array(
            "1674" => array(
                "master" => array(
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_gaji_cabang",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "harga_efisiensi" => "nilai",
//
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_bpjs_perusahaan",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "efisiensi_bpjs" => "nilai",
//
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_pph21_perusahaan",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "efisiensi_pph21" => "nilai",
//
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


                "transaksi_nilai" => "harga",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",

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
        "components" => array(),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaReject" => "stepCode|placeID",
        //-----
        "rebuilderCoreKey" => "pihakPembebananCode",
        "rebuilderCore" => array(
            // pusat
            100 => array(
                "biaya_gaji_pusat" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_pusat" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_pusat" => "biaya_pph21_perusahaan",
                "biaya_usaha_pusat" => "biaya_usaha_total",
                "biaya_umum_pusat" => "biaya_umum_total",

//                "hutang_gaji_pusat_main" => "hutang_gaji",
                "hutang_gaji_pusat_main" => "hutang_gaji_main",
                "hutang_pph21_pusat_main" => "hutang_pph21_main",
                "hutang_bpjs_pusat_main" => "hutang_bpjs_main",

                "biaya_gaji_cabang" => ".0",
                "biaya_bpjs_perusahaan_cabang" => ".0",
                "biaya_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_cabang" => ".0",
                "biaya_umum_cabang" => ".0",

                "hutang_gaji_cabang_main" => ".0",
                "hutang_pph21_cabang_main" => ".0",
                "hutang_bpjs_cabang_main" => ".0",

                "piutang_cabang_main_main" => ".0",

                "biaya_umum_bpjs_perusahaan_cabang" => ".0",
                "biaya_umum_pph21_perusahaan_cabang" => ".0",
                "biaya_umum_gaji_main_cabang" => ".0",
                "biaya_umum_gaji_cabang" => ".0",
                "biaya_usaha_bpjs_perusahaan_cabang" => ".0",
                "biaya_usaha_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_gaji_main_cabang" => ".0",
                "biaya_usaha_gaji_cabang" => ".0",

                "biaya_umum_bpjs_perusahaan_pusat" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_pusat" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_pusat" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_pusat" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_pusat" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_pusat" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_pusat" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_pusat" => "biaya_usaha_gaji",

            ),
            // cabang
            111 => array(
                "biaya_gaji_pusat" => ".0",
                "biaya_bpjs_perusahaan_pusat" => ".0",
                "biaya_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_pusat" => ".0",
                "biaya_umum_pusat" => ".0",

                "hutang_gaji_pusat_main" => ".0",
                "hutang_pph21_pusat_main" => ".0",
                "hutang_bpjs_pusat_main" => ".0",

                "biaya_gaji_cabang" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_cabang" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_cabang" => "biaya_pph21_perusahaan",
                "biaya_usaha_cabang" => "biaya_usaha_total",
                "biaya_umum_cabang" => "biaya_umum_total",

//                "hutang_gaji_cabang_main" => "hutang_gaji",
                "hutang_gaji_cabang_main" => "hutang_gaji_main",
                "hutang_pph21_cabang_main" => "hutang_pph21_main",
                "hutang_bpjs_cabang_main" => "hutang_bpjs_main",

                "piutang_cabang_main_main" => "piutang_cabang_main",

                "biaya_umum_bpjs_perusahaan_cabang" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_cabang" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_cabang" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_cabang" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_cabang" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_cabang" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_cabang" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_cabang" => "biaya_usaha_gaji",

                "biaya_umum_bpjs_perusahaan_pusat" => ".0",
                "biaya_umum_pph21_perusahaan_pusat" => ".0",
                "biaya_umum_gaji_main_pusat" => ".0",
                "biaya_umum_gaji_pusat" => ".0",
                "biaya_usaha_bpjs_perusahaan_pusat" => ".0",
                "biaya_usaha_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_gaji_main_pusat" => ".0",
                "biaya_usaha_gaji_pusat" => ".0",

            ),
        ),
    ),
    "21674" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "subtotal" => "jml*harga",
            ),
            "master_dependent" => array(
                "biaya_option" => array(
                    "biaya_umum" => array(
                        "biaya_umum_total" => "biaya_bpjs_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main",
                        "biaya_usaha_total" => ".0",

                        "biaya_umum_bpjs_perusahaan" => "biaya_bpjs_perusahaan",
                        "biaya_umum_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_umum_gaji_main" => "biaya_gaji_main+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
                        "biaya_umum_gaji" => "biaya_gaji_main",

                        "biaya_usaha_bpjs_perusahaan" => ".0",
                        "biaya_usaha_pph21_perusahaan" => ".0",
                        "biaya_usaha_gaji_main" => ".0",
                        "biaya_usaha_gaji" => ".0",
                    ),
                    "biaya_usaha" => array(
                        "biaya_umum_total" => ".0",
                        "biaya_usaha_total" => "biaya_bpjs_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main",

                        "biaya_umum_bpjs_perusahaan" => ".0",
                        "biaya_umum_pph21_perusahaan" => ".0",
                        "biaya_umum_gaji_main" => ".0",
                        "biaya_umum_gaji" => ".0",

                        "biaya_usaha_bpjs_perusahaan" => "biaya_bpjs_perusahaan",
                        "biaya_usaha_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_usaha_gaji_main" => "biaya_gaji_main+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
                        "biaya_usaha_gaji" => "biaya_gaji_main",
                    ),
                ),
            ),
        ),
        /*
 * CATATAN UNTUK PEMBUILD GERBANG hanya menggunakan 1 underscores contoh biaya_jasa . biaya_jasa_lima(ini tidak diperbolehkan)
 */
        "valueBuilders" => array(
            //biayagaji hutanggaji+bpjskaryawan+bpjs perusahaan
            "piutang_cabang_main" => "hutang_gaji+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
            "hutang_bpjs_main" => "hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
            "biaya_gaji_main" => "hutang_gaji",
            "efisiensi_biaya_master" => "harga_efisiensi+efisiensi_bpjs+efisiensi_pph21",
            "grand_total" => "piutang_cabang_main",
            "take_homepay" => "hutang_gaji_main",
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "21674" => array(
                "master" => array(
                    // PEMBEBANAN DI CABANG
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion pusat

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "6010" => "biaya_usaha_cabang",
                            "6030" => "biaya_umum_cabang",
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
//
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "6010" => "biaya_usaha_cabang",
                            "6030" => "biaya_umum_cabang",
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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

                    //pembantu biaya gaji
//                    array(
//                        "comName" => "RekeningPembantuBiayaGaji",
//                        "loop" => array(
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya pph21
//                    array(
//                        "comName" => "RekeningPembantuBiayaPph21",
//                        "loop" => array(
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya bpjs
//                    array(
//                        "comName" => "RekeningPembantuBiayaBpjs",
//                        "loop" => array(
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya usaha
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_main_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_bpjs_perusahaan_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pph21_perusahaan_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion
                    //pembantu biaya umum
                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_main_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_bpjs_perusahaan_cabang",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_pph21_perusahaan_cabang",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_cabang",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion


                    // PEMBEBANAN DI DC/PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "6010" => "biaya_usaha_pusat",
                            "6030" => "biaya_umum_pusat",
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
//
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "6010" => "biaya_usaha_pusat",
                            "6030" => "biaya_umum_pusat",
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //pembantu biaya gaji
//                    array(
//                        "comName" => "RekeningPembantuBiayaGaji",
//                        "loop" => array(
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya pph21
//                    array(
//                        "comName" => "RekeningPembantuBiayaPph21",
//                        "loop" => array(
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya bpjs
//                    array(
//                        "comName" => "RekeningPembantuBiayaBpjs",
//                        "loop" => array(
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya usaha
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_main_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_bpjs_perusahaan_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pph21_perusahaan_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //pembantu biaya umum
                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_main_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_bpjs_perusahaan_pusat",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_pph21_perusahaan_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),

        ),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaReject" => "stepCode|placeID",
        //-----
        "rebuilderCoreKey" => "pihakPembebananCode",
        "rebuilderCore" => array(
            // pusat
            100 => array(
                "biaya_gaji_pusat" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_pusat" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_pusat" => "biaya_pph21_perusahaan",
                "biaya_usaha_pusat" => "biaya_usaha_total",
                "biaya_umum_pusat" => "biaya_umum_total",

//                "hutang_gaji_pusat_main" => "hutang_gaji",
                "hutang_gaji_pusat_main" => "hutang_gaji_main",
                "hutang_pph21_pusat_main" => "hutang_pph21_main",
                "hutang_bpjs_pusat_main" => "hutang_bpjs_main",

                "biaya_gaji_cabang" => ".0",
                "biaya_bpjs_perusahaan_cabang" => ".0",
                "biaya_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_cabang" => ".0",
                "biaya_umum_cabang" => ".0",

                "hutang_gaji_cabang_main" => ".0",
                "hutang_pph21_cabang_main" => ".0",
                "hutang_bpjs_cabang_main" => ".0",

                "piutang_cabang_main_main" => ".0",

                "biaya_umum_bpjs_perusahaan_cabang" => ".0",
                "biaya_umum_pph21_perusahaan_cabang" => ".0",
                "biaya_umum_gaji_main_cabang" => ".0",
                "biaya_umum_gaji_cabang" => ".0",
                "biaya_usaha_bpjs_perusahaan_cabang" => ".0",
                "biaya_usaha_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_gaji_main_cabang" => ".0",
                "biaya_usaha_gaji_cabang" => ".0",

                "biaya_umum_bpjs_perusahaan_pusat" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_pusat" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_pusat" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_pusat" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_pusat" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_pusat" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_pusat" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_pusat" => "biaya_usaha_gaji",

            ),
            // cabang
            111 => array(
                "biaya_gaji_pusat" => ".0",
                "biaya_bpjs_perusahaan_pusat" => ".0",
                "biaya_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_pusat" => ".0",
                "biaya_umum_pusat" => ".0",

                "hutang_gaji_pusat_main" => ".0",
                "hutang_pph21_pusat_main" => ".0",
                "hutang_bpjs_pusat_main" => ".0",

                "biaya_gaji_cabang" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_cabang" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_cabang" => "biaya_pph21_perusahaan",
                "biaya_usaha_cabang" => "biaya_usaha_total",
                "biaya_umum_cabang" => "biaya_umum_total",

//                "hutang_gaji_cabang_main" => "hutang_gaji",
                "hutang_gaji_cabang_main" => "hutang_gaji_main",
                "hutang_pph21_cabang_main" => "hutang_pph21_main",
                "hutang_bpjs_cabang_main" => "hutang_bpjs_main",

                "piutang_cabang_main_main" => "piutang_cabang_main",

                "biaya_umum_bpjs_perusahaan_cabang" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_cabang" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_cabang" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_cabang" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_cabang" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_cabang" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_cabang" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_cabang" => "biaya_usaha_gaji",

                "biaya_umum_bpjs_perusahaan_pusat" => ".0",
                "biaya_umum_pph21_perusahaan_pusat" => ".0",
                "biaya_umum_gaji_main_pusat" => ".0",
                "biaya_umum_gaji_pusat" => ".0",
                "biaya_usaha_bpjs_perusahaan_pusat" => ".0",
                "biaya_usaha_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_gaji_main_pusat" => ".0",
                "biaya_usaha_gaji_pusat" => ".0",

            ),
        ),
    ),
    // versi lama, yang ditanggung karyawan mengurangi hutang gaji, diaktifkan bila biaya gaji bruto
    "7674_OLD" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "subtotal" => "jml*harga",
            ),
            "master_dependent" => array(
                "biaya_option" => array(
                    "biaya_umum" => array(
                        "biaya_umum_total" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main",
                        "biaya_usaha_total" => ".0",

                        "biaya_umum_bpjs_perusahaan" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan",
                        "biaya_umum_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_umum_gaji_main" => "biaya_gaji_main+biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan",
                        "biaya_umum_gaji" => "biaya_gaji_main",

                        "biaya_usaha_bpjs_perusahaan" => ".0",
                        "biaya_usaha_pph21_perusahaan" => ".0",
                        "biaya_usaha_gaji_main" => ".0",
                        "biaya_usaha_gaji" => ".0",
                    ),
                    "biaya_usaha" => array(
                        "biaya_umum_total" => ".0",
                        "biaya_usaha_total" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main",

                        "biaya_umum_bpjs_perusahaan" => ".0",
                        "biaya_umum_pph21_perusahaan" => ".0",
                        "biaya_umum_gaji_main" => ".0",
                        "biaya_umum_gaji" => ".0",

                        "biaya_usaha_bpjs_perusahaan" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan",
                        "biaya_usaha_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_usaha_gaji_main" => "biaya_gaji_main+biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan",
                        "biaya_usaha_gaji" => "biaya_gaji_main",
                    ),
                ),
            ),
            "master_dependent_items" => array(
                "extern_coa" => array(
                    // bpjs karyawan
                    "2010060010" => array(
                        "nilai_bpjs_karyawan" => "harga",
                        "nilai_bpjs_perusahaan" => ".0",
                    ),
                    // pendapatan bpjsperusahaan
                    "2010060020" => array(
                        "nilai_bpjs_karyawan" => ".0",
                        "nilai_bpjs_perusahaan" => "harga",
                    ),
                ),
            ),
        ),
        /*
 * CATATAN UNTUK PEMBUILD GERBANG hanya menggunakan 1 underscores contoh biaya_jasa . biaya_jasa_lima(ini tidak diperbolehkan)
 */
        "valueBuilders" => array(
            //biayagaji hutanggaji+bpjskaryawan+bpjs perusahaan
            "piutang_cabang_main" => "hutang_gaji+biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan",
            "hutang_bpjs_main" => "hutang_bpjs_kesehatan+hutang_bpjs_tenagakerja+biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan",
            "bpjs_ditanggung_karyawan" => "hutang_bpjs_kesehatan+hutang_bpjs_tenagakerja",
            "bpjs_ditanggung_perusahaan" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_kesehatan+hutang_bpjs_tenagakerja)",
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
            "biaya_gaji_main" => "hutang_gaji",
            "efisiensi_biaya_master" => "harga_efisiensi+efisiensi_bpjs+efisiensi_pph21",
            "grand_total" => "piutang_cabang_main",
            "take_homepay" => "hutang_gaji_main",
        ),

        "preProcessor" => array(
            "7674" => array(
                "master" => array(
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_gaji_cabang",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "harga_efisiensi" => "nilai",
//
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_bpjs_perusahaan",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "efisiensi_bpjs" => "nilai",
//
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_pph21_perusahaan",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "efisiensi_pph21" => "nilai",
//
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "7674" => array(
                "master" => array(
                    // PEMBEBANAN DI CABANG
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion pusat

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "6010" => "biaya_usaha_cabang",
                            "6030" => "biaya_umum_cabang",
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
//
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "6010" => "biaya_usaha_cabang",
                            "6030" => "biaya_umum_cabang",
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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

                    //pembantu biaya gaji
//                    array(
//                        "comName" => "RekeningPembantuBiayaGaji",
//                        "loop" => array(
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya pph21
//                    array(
//                        "comName" => "RekeningPembantuBiayaPph21",
//                        "loop" => array(
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya bpjs
//                    array(
//                        "comName" => "RekeningPembantuBiayaBpjs",
//                        "loop" => array(
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya usaha
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_main_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_bpjs_perusahaan_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pph21_perusahaan_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion
                    //pembantu biaya umum
                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_main_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_bpjs_perusahaan_cabang",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_pph21_perusahaan_cabang",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_cabang",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion


                    // PEMBEBANAN DI DC/PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "6010" => "biaya_usaha_pusat",
                            "6030" => "biaya_umum_pusat",
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
//
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "6010" => "biaya_usaha_pusat",
                            "6030" => "biaya_umum_pusat",
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening pembantu hutang bpjs perusahaan lv1,lv2 ada di detail
                    array(
                        "comName" => "RekeningPembantuHutangBpjs",
                        "loop" => array(
                            "2010060" => "bpjs_ditanggung_karyawan",//hutang bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeName",
                            "cabang2_nama" => "placeName",
                            "extern_id" => ".2010060010",
                            "extern_nama" => ". bpjs ditanggung karyawan",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuHutangBpjs",
                        "loop" => array(
                            "2010060" => "bpjs_ditanggung_perusahaan",//hutang bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeName",
                            "cabang2_nama" => "placeName",
                            "extern_id" => ".2010060020",
                            "extern_nama" => ". bpjs ditanggung perusahaan",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //pembantu biaya usaha
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_main_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_bpjs_perusahaan_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pph21_perusahaan_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //pembantu biaya umum
                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_main_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_bpjs_perusahaan_pusat",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_pph21_perusahaan_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSubHutangBpjs",
                        "loop" => array(
                            "2010060" => "nilai_bpjs_karyawan",//hutang bpjs karyawana
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "extern_coa",
                            "extern2_nama" => "extern_coa_name",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuSubHutangBpjs",
                        "loop" => array(
                            "2010060" => "nilai_bpjs_perusahaan",//hutang bpjs karyawana
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "extern_coa",
                            "extern2_nama" => "extern_coa_name",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),

        ),
        "postProcessor" => array(
            "7674" => array(
                "master" => array(
                    // post procc payment anti source
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
                            "label" => ".hutang gaji",
//                            "sisa" => "hutang_bpjs_karyawan+hutang_pph21_karyawan",
                            "sisa" => "nilai_bpjs_karyawan+hutang_pph21_karyawan",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // payment anti source cache hutang gaji, bertambah
                    array(
                        "comName" => "PaymentAntisourceCustomer",
                        "loop" => array(
                            "2010080" => "nilai_bpjs_karyawan+hutang_pph21_karyawan",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "-creditAmount",
                            "label" => ".hutang gaji",
                            "extern_label2" => ".cabang",
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
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaReject" => "stepCode|placeID",
        //-----
        "rebuilderCoreKey" => "pihakPembebananCode",
        "rebuilderCore" => array(
            // pusat
            100 => array(
                "biaya_gaji_pusat" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_pusat" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_pusat" => "biaya_pph21_perusahaan",
                "biaya_usaha_pusat" => "biaya_usaha_total",
                "biaya_umum_pusat" => "biaya_umum_total",

//                "hutang_gaji_pusat_main" => "hutang_gaji",
                "hutang_gaji_pusat_main" => "hutang_gaji_main",
                "hutang_pph21_pusat_main" => "hutang_pph21_main",
                "hutang_bpjs_pusat_main" => "hutang_bpjs_main",

                "biaya_gaji_cabang" => ".0",
                "biaya_bpjs_perusahaan_cabang" => ".0",
                "biaya_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_cabang" => ".0",
                "biaya_umum_cabang" => ".0",

                "hutang_gaji_cabang_main" => ".0",
                "hutang_pph21_cabang_main" => ".0",
                "hutang_bpjs_cabang_main" => ".0",

                "piutang_cabang_main_main" => ".0",

                "biaya_umum_bpjs_perusahaan_cabang" => ".0",
                "biaya_umum_pph21_perusahaan_cabang" => ".0",
                "biaya_umum_gaji_main_cabang" => ".0",
                "biaya_umum_gaji_cabang" => ".0",
                "biaya_usaha_bpjs_perusahaan_cabang" => ".0",
                "biaya_usaha_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_gaji_main_cabang" => ".0",
                "biaya_usaha_gaji_cabang" => ".0",

                "biaya_umum_bpjs_perusahaan_pusat" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_pusat" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_pusat" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_pusat" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_pusat" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_pusat" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_pusat" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_pusat" => "biaya_usaha_gaji",

            ),
            // cabang
            111 => array(
                "biaya_gaji_pusat" => ".0",
                "biaya_bpjs_perusahaan_pusat" => ".0",
                "biaya_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_pusat" => ".0",
                "biaya_umum_pusat" => ".0",

                "hutang_gaji_pusat_main" => ".0",
                "hutang_pph21_pusat_main" => ".0",
                "hutang_bpjs_pusat_main" => ".0",

                "biaya_gaji_cabang" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_cabang" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_cabang" => "biaya_pph21_perusahaan",
                "biaya_usaha_cabang" => "biaya_usaha_total",
                "biaya_umum_cabang" => "biaya_umum_total",

//                "hutang_gaji_cabang_main" => "hutang_gaji",
                "hutang_gaji_cabang_main" => "hutang_gaji_main",
                "hutang_pph21_cabang_main" => "hutang_pph21_main",
                "hutang_bpjs_cabang_main" => "hutang_bpjs_main",

                "piutang_cabang_main_main" => "piutang_cabang_main",

                "biaya_umum_bpjs_perusahaan_cabang" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_cabang" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_cabang" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_cabang" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_cabang" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_cabang" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_cabang" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_cabang" => "biaya_usaha_gaji",

                "biaya_umum_bpjs_perusahaan_pusat" => ".0",
                "biaya_umum_pph21_perusahaan_pusat" => ".0",
                "biaya_umum_gaji_main_pusat" => ".0",
                "biaya_umum_gaji_pusat" => ".0",
                "biaya_usaha_bpjs_perusahaan_pusat" => ".0",
                "biaya_usaha_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_gaji_main_pusat" => ".0",
                "biaya_usaha_gaji_pusat" => ".0",

            ),
        ),
    ),
    // versi baru, yang ditanggung karyawan menambah biaya gaji, diaktifkan bila biaya gaji take home pay
    "7674" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "subtotal" => "jml*harga",
            ),
            "master_dependent" => array(
                "biaya_option" => array(
                    "biaya_umum" => array(
                        "biaya_umum_total" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main+hutang_pph21_karyawan+bpjs_ditanggung_karyawan",
                        "biaya_usaha_total" => ".0",

                        "biaya_umum_bpjs_perusahaan" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan",
                        "biaya_umum_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_umum_gaji_main" => "biaya_gaji_main+biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan+hutang_pph21_karyawan+bpjs_ditanggung_karyawan",
                        "biaya_umum_gaji" => "biaya_gaji_main+hutang_pph21_karyawan+bpjs_ditanggung_karyawan",

                        "biaya_usaha_bpjs_perusahaan" => ".0",
                        "biaya_usaha_pph21_perusahaan" => ".0",
                        "biaya_usaha_gaji_main" => ".0",
                        "biaya_usaha_gaji" => ".0",
                    ),
                    "biaya_usaha" => array(
                        "biaya_umum_total" => ".0",
                        "biaya_usaha_total" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan+biaya_gaji_main+hutang_pph21_karyawan+bpjs_ditanggung_karyawan",

                        "biaya_umum_bpjs_perusahaan" => ".0",
                        "biaya_umum_pph21_perusahaan" => ".0",
                        "biaya_umum_gaji_main" => ".0",
                        "biaya_umum_gaji" => ".0",

                        "biaya_usaha_bpjs_perusahaan" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan",
                        "biaya_usaha_pph21_perusahaan" => "biaya_pph21_perusahaan",
                        "biaya_usaha_gaji_main" => "biaya_gaji_main+biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan+hutang_pph21_karyawan+bpjs_ditanggung_karyawan",
                        "biaya_usaha_gaji" => "biaya_gaji_main+hutang_pph21_karyawan+bpjs_ditanggung_karyawan",
                    ),
                ),
            ),
            "master_dependent_items" => array(
                "extern_coa" => array(
                    // bpjs karyawan
                    "2010060010" => array(
                        "nilai_bpjs_karyawan" => "harga",
                        "nilai_bpjs_perusahaan" => ".0",
                    ),
                    // pendapatan bpjsperusahaan
                    "2010060020" => array(
                        "nilai_bpjs_karyawan" => ".0",
                        "nilai_bpjs_perusahaan" => "harga",
                    ),
                ),
            ),
        ),
        /*
 * CATATAN UNTUK PEMBUILD GERBANG hanya menggunakan 1 underscores contoh biaya_jasa . biaya_jasa_lima(ini tidak diperbolehkan)
 */
        "valueBuilders" => array(
            //biayagaji hutanggaji+bpjskaryawan+bpjs perusahaan
            "piutang_cabang_main" => "hutang_gaji+biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan+biaya_pph21_perusahaan+hutang_pph21_karyawan+bpjs_ditanggung_karyawan",
            "hutang_bpjs_main" => "hutang_bpjs_kesehatan+hutang_bpjs_tenagakerja+biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan",
            "bpjs_ditanggung_karyawan" => "hutang_bpjs_kesehatan+hutang_bpjs_tenagakerja",
            "bpjs_ditanggung_perusahaan" => "biaya_bpjs_kesehatan_perusahaan+biaya_bpjs_tenagakerja_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
//            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_kesehatan+hutang_bpjs_tenagakerja)",
            "hutang_gaji_main" => ".0",// hutang gaji di nolkan karena bpjs dan pph21 ditanggung karyawan menambah ke biaya gaji
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
            "biaya_gaji_main" => "hutang_gaji",
            "efisiensi_biaya_master" => "harga_efisiensi+efisiensi_bpjs+efisiensi_pph21",
            "grand_total" => "piutang_cabang_main",
            "take_homepay" => "hutang_gaji_main",
        ),

        "preProcessor" => array(
            "7674" => array(
                "master" => array(
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_gaji_cabang",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "harga_efisiensi" => "nilai",
//
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_bpjs_perusahaan",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "efisiensi_bpjs" => "nilai",
//
//                            ),
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "SyncEfisiensi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "pihakID",
//                            "nilai" => "biaya_pph21_perusahaan",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "efisiensi_pph21" => "nilai",
//
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
                "produk_ord_jml" => "jml",
                "produk_ord_hrg" => "harga",
                "hpp" => "harga",
                "satuan" => "satuan",
                "note" => "note",
                "reference" => "reference",
            ),
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
            "7674" => array(
                "master" => array(
                    // PEMBEBANAN DI CABANG
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                            "2010060" => "hutang_bpjs_cabang_main",//hutang bpjs
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
                            "1010060010" => "piutang_cabang_main_main",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2010080" => "hutang_gaji_cabang_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_cabang_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion pusat

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "6010" => "biaya_usaha_cabang",
                            "6030" => "biaya_umum_cabang",
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
//
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
                            "6010" => "biaya_usaha_cabang",
                            "6030" => "biaya_umum_cabang",
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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
                            "2040010" => "piutang_cabang_main_main",//hutang ke pusat
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

                    //pembantu biaya gaji
//                    array(
//                        "comName" => "RekeningPembantuBiayaGaji",
//                        "loop" => array(
//                            "6050" => "biaya_gaji_cabang",//biaya gaji
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya pph21
//                    array(
//                        "comName" => "RekeningPembantuBiayaPph21",
//                        "loop" => array(
//                            "6090" => "biaya_pph21_perusahaan_cabang",//biaya pph21
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya bpjs
//                    array(
//                        "comName" => "RekeningPembantuBiayaBpjs",
//                        "loop" => array(
//                            "6080" => "biaya_bpjs_perusahaan_cabang",//biaya bpjs
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "placeID",
//                            "extern_nama" => "placeName",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //pembantu biaya usaha
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_main_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_bpjs_perusahaan_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pph21_perusahaan_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //endregion
                    //pembantu biaya umum
                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_main_cabang",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_bpjs_perusahaan_cabang",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_pph21_perusahaan_cabang",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_cabang",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion


                    // PEMBEBANAN DI DC/PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "6010" => "biaya_usaha_pusat",
                            "6030" => "biaya_umum_pusat",
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
//
//                            "6050" => "biaya_gaji_pusat",//biaya gaji
//                            "6080" => "biaya_bpjs_perusahaan_pusat",//biaya bpjs
//                            "6090" => "biaya_pph21_perusahaan_pusat",//biaya pph21
                            "6010" => "biaya_usaha_pusat",
                            "6030" => "biaya_umum_pusat",
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                            "2010060" => "hutang_bpjs_pusat_main",//hutang bpjs
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
                            "2010080" => "hutang_gaji_pusat_main",//hutang gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //rekening pembantu hutang bpjs perusahaan lv1,lv2 ada di detail
                    array(
                        "comName" => "RekeningPembantuHutangBpjs",
                        "loop" => array(
                            "2010060" => "bpjs_ditanggung_karyawan",//hutang bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeName",
                            "cabang2_nama" => "placeName",
                            "extern_id" => ".2010060010",
                            "extern_nama" => ". bpjs ditanggung karyawan",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuHutangBpjs",
                        "loop" => array(
                            "2010060" => "bpjs_ditanggung_perusahaan",//hutang bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeName",
                            "cabang2_nama" => "placeName",
                            "extern_id" => ".2010060020",
                            "extern_nama" => ". bpjs ditanggung perusahaan",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //pembantu biaya usaha
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_main_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_bpjs_perusahaan_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pph21_perusahaan_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_gaji_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    //pembantu biaya umum
                    array(
                        "comName" => "RekeningPembantuBiayaUmumMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_main_pusat",//biaya gaji
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_option__biaya_gaji_id",
                            "extern_nama" => ".biaya gaji",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_bpjs_perusahaan_pusat",//biaya bpjs
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".4",
                            "extern_nama" => ".bpjs ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_pph21_perusahaan_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".5",
                            "extern_nama" => ".pph ps21 ditanggung  perusahaan",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUmumSubMain",
                        "loop" => array(
                            "6030" => "biaya_umum_gaji_pusat",//biaya pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "biaya_option__biaya_gaji_id",
                            "extern2_nama" => ".biaya gaji",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "extern3_id" => ".0",
                            "extern3_nama" => ".0",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21_pusat_main",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// diisi id bank
                            "extern_nama" => "pihakName",// diisi nama bank
                            "extern2_id" => ".3",// diisi id bank
                            "extern2_nama" => ".cabang",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSubHutangBpjs",
                        "loop" => array(
                            "2010060" => "nilai_bpjs_karyawan",//hutang bpjs karyawana
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "extern_coa",
                            "extern2_nama" => "extern_coa_name",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuSubHutangBpjs",
                        "loop" => array(
                            "2010060" => "nilai_bpjs_perusahaan",//hutang bpjs karyawana
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "extern_coa",
                            "extern2_nama" => "extern_coa_name",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),

        ),
        "postProcessor" => array(
            "7674" => array(
                // anti source hutang gaji dimatikan karena gaji pakai gaji take home pay
                "master" => array(
                    // post procc payment anti source
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
//                            "label" => ".hutang gaji",
////                            "sisa" => "hutang_bpjs_karyawan+hutang_pph21_karyawan",
//                            "sisa" => "nilai_bpjs_karyawan+hutang_pph21_karyawan",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // payment anti source cache hutang gaji, bertambah
//                    array(
//                        "comName" => "PaymentAntisourceCustomer",
//                        "loop" => array(
//                            "2010080" => "nilai_bpjs_karyawan+hutang_pph21_karyawan",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "gudang_id" => ".0",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "nilai" => "-creditAmount",
//                            "label" => ".hutang gaji",
//                            "extern_label2" => ".cabang",
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
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|olehID|placeID",
            "stepCode|place2ID",
            "stepCode|olehID|place2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|olehID|placeID",
            "stepCode|masterID|place2ID",
            "stepCode|masterID|olehID|place2ID",
        ),
        "formatNotaReject" => "stepCode|placeID",
        //-----
        "rebuilderCoreKey" => "pihakPembebananCode",
        "rebuilderCore" => array(
            // pusat
            100 => array(
                "biaya_gaji_pusat" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_pusat" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_pusat" => "biaya_pph21_perusahaan",
                "biaya_usaha_pusat" => "biaya_usaha_total",
                "biaya_umum_pusat" => "biaya_umum_total",

//                "hutang_gaji_pusat_main" => "hutang_gaji",
                "hutang_gaji_pusat_main" => "hutang_gaji_main",
                "hutang_pph21_pusat_main" => "hutang_pph21_main",
                "hutang_bpjs_pusat_main" => "hutang_bpjs_main",

                "biaya_gaji_cabang" => ".0",
                "biaya_bpjs_perusahaan_cabang" => ".0",
                "biaya_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_cabang" => ".0",
                "biaya_umum_cabang" => ".0",

                "hutang_gaji_cabang_main" => ".0",
                "hutang_pph21_cabang_main" => ".0",
                "hutang_bpjs_cabang_main" => ".0",

                "piutang_cabang_main_main" => ".0",

                "biaya_umum_bpjs_perusahaan_cabang" => ".0",
                "biaya_umum_pph21_perusahaan_cabang" => ".0",
                "biaya_umum_gaji_main_cabang" => ".0",
                "biaya_umum_gaji_cabang" => ".0",
                "biaya_usaha_bpjs_perusahaan_cabang" => ".0",
                "biaya_usaha_pph21_perusahaan_cabang" => ".0",
                "biaya_usaha_gaji_main_cabang" => ".0",
                "biaya_usaha_gaji_cabang" => ".0",

                "biaya_umum_bpjs_perusahaan_pusat" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_pusat" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_pusat" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_pusat" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_pusat" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_pusat" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_pusat" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_pusat" => "biaya_usaha_gaji",

            ),
            // cabang
            111 => array(
                "biaya_gaji_pusat" => ".0",
                "biaya_bpjs_perusahaan_pusat" => ".0",
                "biaya_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_pusat" => ".0",
                "biaya_umum_pusat" => ".0",

                "hutang_gaji_pusat_main" => ".0",
                "hutang_pph21_pusat_main" => ".0",
                "hutang_bpjs_pusat_main" => ".0",

                "biaya_gaji_cabang" => "biaya_gaji_main",
                "biaya_bpjs_perusahaan_cabang" => "biaya_bpjs_perusahaan",
                "biaya_pph21_perusahaan_cabang" => "biaya_pph21_perusahaan",
                "biaya_usaha_cabang" => "biaya_usaha_total",
                "biaya_umum_cabang" => "biaya_umum_total",

//                "hutang_gaji_cabang_main" => "hutang_gaji",
                "hutang_gaji_cabang_main" => "hutang_gaji_main",
                "hutang_pph21_cabang_main" => "hutang_pph21_main",
                "hutang_bpjs_cabang_main" => "hutang_bpjs_main",

                "piutang_cabang_main_main" => "piutang_cabang_main",

                "biaya_umum_bpjs_perusahaan_cabang" => "biaya_umum_bpjs_perusahaan",
                "biaya_umum_pph21_perusahaan_cabang" => "biaya_umum_pph21_perusahaan",
                "biaya_umum_gaji_main_cabang" => "biaya_umum_gaji_main",
                "biaya_umum_gaji_cabang" => "biaya_umum_gaji",
                "biaya_usaha_bpjs_perusahaan_cabang" => "biaya_usaha_bpjs_perusahaan",
                "biaya_usaha_pph21_perusahaan_cabang" => "biaya_usaha_pph21_perusahaan",
                "biaya_usaha_gaji_main_cabang" => "biaya_usaha_gaji_main",
                "biaya_usaha_gaji_cabang" => "biaya_usaha_gaji",

                "biaya_umum_bpjs_perusahaan_pusat" => ".0",
                "biaya_umum_pph21_perusahaan_pusat" => ".0",
                "biaya_umum_gaji_main_pusat" => ".0",
                "biaya_umum_gaji_pusat" => ".0",
                "biaya_usaha_bpjs_perusahaan_pusat" => ".0",
                "biaya_usaha_pph21_perusahaan_pusat" => ".0",
                "biaya_usaha_gaji_main_pusat" => ".0",
                "biaya_usaha_gaji_pusat" => ".0",

            ),
        ),
    ),

    // cashback penjualan (biaya usaha), pilih konsumen dan pilih invoice
    "6677" => array(
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
                "cabang2ID" => "cabang2",
                "cabang2Name" => "cabang2__nama",
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",
                "gudangID" => "gudang",
                "gudangName" => "gudang__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(
                // tidak resiprokal, yang diisi jumlah yang diterima pihak lain/perusahaan/freelancer...
                // maka variabel harga dihitung disini... 06 maret 2025
                "harga" => "(100/(100-pph__tarif))*nilai_kas_cn",

                "nilai_pph23" => "(pph23Methode__tarif/100)*harga",
                "nilai_pph21" => "(pph21Methode__tarif/100)*harga",
                "nilai_pph_original" => "(pph__tarif/100)*harga",

                // bila yang diisi dpp komisi (bruto), maka dibawah ini harus hidup
                // jumlah uang yang diterima freelancer dihitung disini.
//                "nilai_kas_cn" => "harga-nilai_pph23-nilai_pph21",
            ),
            "master_dependent" => array(
                "freelancerOption" => array(
                    1 => array(// MEMBER/TERDAFTAR
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => "nilai_kas_cn",
                        // ini dihilangkan, atau di-nol-kan valuenya
                        "cash_account" => ".0",
                        "cash_account__label" => ".0",
                        "cash_account__nama" => ".0",
                        "kompensasiMethod" => ".0",
                        "kompensasiMethod__label" => ".0",
                        "kompensasiMethod__name" => ".0",
                    ),
                    2 => array(// NON MEMBER/TIDAK TERDAFTAR
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                    ),
                ),
                "kompensasiMethod" => array(
                    // hutang komisi
//                    0 => array(
//                        "kas_cabang" => ".0",
//                        "kas_pusat" => ".0",
//                        "hutang_ke_pusat" => "nilai_kas_cn",
//                        "piutang_cabang" => "nilai_kas_cn",
//
//                        "hutang_ke_konsumen" => ".0",
//                        "biaya_cashback" => "harga",
//                        "hutang_pph23" => "nilai_pph23",
//                        "hutang_pph21" => "nilai_pph21",
//
//                        "hutang_komisi" => "nilai_kas_cn",
//                    ),

                    // kas
                    1 => array(
                        "kas_cabang" => "nilai_kas_cn",
                        "kas_pusat" => "nilai_kas_cn",
                        "hutang_ke_pusat" => "nilai_kas_cn",
                        "piutang_cabang" => "nilai_kas_cn",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                    ),
                    // creditnote
                    2 => array(
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => "nilai_kas_cn",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                    ),
                ),
                "pajakOption" => array(
                    "pph21" => array(
                        "pph21Valid" => ".1",
                        "pph23Methode" => ".0",
                        "pph23Methode__name" => ".0",
                        "pph23Methode__label" => ".0",
                        "pph23Methode__tarif" => ".0",
                        "pph23Valid" => ".0",
                        "pph__tarif" => "pph21Methode__tarif",
                    ),
                    "pph21_5" => array(
                        "pph21Valid" => ".1",
                        "pph23Methode" => ".0",
                        "pph23Methode__name" => ".0",
                        "pph23Methode__label" => ".0",
                        "pph23Methode__tarif" => ".0",
                        "pph23Valid" => ".0",
                        "pph__tarif" => "pph21Methode__tarif",
                    ),
                    "pph23" => array(
                        "pph23Valid" => ".1",
                        "pph21Methode" => ".0",
                        "pph21Methode__name" => ".0",
                        "pph21Methode__label" => ".0",
                        "pph21Methode__tarif" => ".0",
                        "pph21Valid" => ".0",
                        "pph__tarif" => "pph23Methode__tarif",
                    ),
                    "pph23_15" => array(
                        "pph23Valid" => ".1",
                        "pph21Methode" => ".0",
                        "pph21Methode__name" => ".0",
                        "pph21Methode__label" => ".0",
                        "pph21Methode__tarif" => ".0",
                        "pph21Valid" => ".0",
                        "pph__tarif" => "pph23Methode__tarif",
                    ),
                ),
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",

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
        "components" => array(),
        "postProcessor" => array(
            "6677" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerTransaksi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".komisi",
                            "state" => ".hold",
                            "jumlah" => ".1",
                            "produk_id" => "id",
                            "nama" => "name",
//                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
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
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
    "16677" => array(
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
            "master" => array(
                //==sumber nilai utama
//                "cabang2ID" => "branchID",
//                "cabang2Name" => "branchName",
//                "place2ID" => "branchID",
//                "place2Name" => "branchName",
//                "gudangID" => "gudang",
//                "gudangName" => "gudang__label",
//                "gudang2ID" => "gudang2",
//                "gudang2Name" => "gudang2__label",
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "kompensasiMethod" => array(
                    // kas
                    1 => array(
                        "kas_cabang" => "nilai_kas_cn",
                        "kas_pusat" => "nilai_kas_cn",
                        "hutang_ke_pusat" => "nilai_kas_cn",
                        "piutang_cabang" => "nilai_kas_cn",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",
                    ),
                    // creditnote
                    2 => array(
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => "nilai_kas_cn",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "hutang_pph_total" => "hutang_pph21+hutang_pph23",
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",
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
            "16677" => array(
                "master" => array(
                    // PUSAT, kas pusat dibawa ke cabang, saat cashback kas diberikan ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // CABANG, terima kas dari pusat, saat cashback kas diberikan ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // CABANG, pemberian cashback ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6010" => "biaya_cashback",//biaya usaha
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "2010120" => "hutang_komisi",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "6010" => "biaya_cashback",//biaya usaha
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "2010120" => "hutang_komisi",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-kas_cabang",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka tanpa relasi so (creditnote), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu biaya usaha, cashback
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_cashback",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihakMainID",
                            "extern_nama" => "pihakMainName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // CABANG, pindah hutang pph23 di cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "hutang_pph_total",//hutang ke pusat
                            "2030010" => "-hutang_pph21",//hutang pph21
                            "2030030" => "-hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040010" => "hutang_pph_total",//hutang ke pusat
                            "2030010" => "-hutang_pph21",//hutang pph21
                            "2030030" => "-hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hutang_pph23",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
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
                            "2040010" => "hutang_pph21",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // PUSAT, terima pindahan hutang pph23 dari cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "hutang_pph_total",//piutang cabang
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "hutang_pph_total",//piutang cabang
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hutang_pph23",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hutang_pph21",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "hutang_pph21",
//                            "extern2_id" => ".9",
//                            "extern2_nama" => ".customer",
//                            "extern_id" => "customerID",// diisi customer
//                            "extern_nama" => "customerName",// diisi customer
                            "extern2_id" => ".11",
                            "extern2_nama" => ".freelancer",
                            "extern_id" => "freelancerDetails",// diisi customer
                            "extern_nama" => "freelancerDetails__nama",// diisi customer
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030030" => "hutang_pph23",// hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "customerID",// diisi customer
                            "extern_nama" => "customerName",// diisi customer
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "hutang_pph23",
                            "extern2_id" => ".9",
                            "extern2_nama" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    // pembantu biaya usaha, cashback penjualan, invoice
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaSubItem",
                        "loop" => array(
                            "6010" => "sub_harga",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "pihakMainID",
                            "extern2_nama" => "pihakMainName",
                            "extern3_id" => "customerID",
                            "extern3_nama" => "customerName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // pembantu hutang komisi
                    array(
                        "comName" => "RekeningPembantuKomisiItem",
                        "loop" => array(
                            "2010120" => "sub_nilai_kas_cn_detail",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "16677" => array(
                "master" => array(

                    // kas keluar dari pusat
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
                            "nilai" => "-kas_pusat",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // kas masuk ke cabang
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account",
//                            "nama" => "cash_account__label",
//                            "nilai" => "kas_pusat",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // kas keluar dari cabang
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "gudang_id" => ".0",
//                            "state" => ".active",
//                            "jenis" => ".kas",
//                            "produk_id" => "cash_account",
//                            "nama" => "cash_account__label",
//                            "nilai" => "-kas_cabang",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // payment source creditnote konsumen
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka konsumen",
                            "tambah" => "hutang_ke_konsumen",
                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // pembantu hutang komisi
                    array(
                        "comName" => "PaymentSourceBuilder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => ".11",
                            "extern2_nama" => ".freelancer",
                            "extern4_id" => "customerID",
                            "extern4_nama" => "customerName",
                            "extern5_id" => "place2ID",
                            "extern5_nama" => "place2Name",
                            "label" => ".hutang komisi",
                            "target_jenis" => ".1488",
                            "jenis" => "jenisTr",
                            "reference_jenis" => "jenisTr",
                            "tagihan" => "sub_nilai_kas_cn_detail",
                            "sisa" => "sub_nilai_kas_cn_detail",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
    //----------------------
    "6678_OLD" => array(
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
                "cabang2ID" => "cabang2",
                "cabang2Name" => "cabang2__nama",
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",
                "gudangID" => "gudang",
                "gudangName" => "gudang__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(
                // tidak resiprokal, yang diisi jumlah yang diterima pihak lain/perusahaan/freelancer...
                // maka variabel harga dihitung disini... 06 maret 2025
                "harga" => "(100/(100-pph__tarif))*nilai_kas_cn",
                "inv_new_net1" => "inv_new_net3-inv_grand_ppn",
                "inv_dpp_pengganti" => "inv_new_net1*(11/12)",
                "nilai_pph23" => "(pph23Methode__tarif/100)*harga",
                "nilai_pph21" => "(pph21Methode__tarif/100)*harga",
                "nilai_pph_original" => "(pph__tarif/100)*harga",

                // bila yang diisi dpp komisi (bruto), maka dibawah ini harus hidup
                // jumlah uang yang diterima freelancer dihitung disini.
//                "nilai_kas_cn" => "harga-nilai_pph23-nilai_pph21",
            ),
            "master_dependent" => array(
                "freelancerOption" => array(
                    1 => array(// MEMBER/TERDAFTAR
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => "nilai_kas_cn",
                        // ini dihilangkan, atau di-nol-kan valuenya
                        "cash_account" => ".0",
                        "cash_account__label" => ".0",
                        "cash_account__nama" => ".0",
                        "kompensasiMethod" => ".0",
                        "kompensasiMethod__label" => ".0",
                        "kompensasiMethod__name" => ".0",
                    ),
                    2 => array(// NON MEMBER/TIDAK TERDAFTAR
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                    ),
                ),
                "kompensasiMethod" => array(
                    // hutang komisi
//                    0 => array(
//                        "kas_cabang" => ".0",
//                        "kas_pusat" => ".0",
//                        "hutang_ke_pusat" => "nilai_kas_cn",
//                        "piutang_cabang" => "nilai_kas_cn",
//
//                        "hutang_ke_konsumen" => ".0",
//                        "biaya_cashback" => "harga",
//                        "hutang_pph23" => "nilai_pph23",
//                        "hutang_pph21" => "nilai_pph21",
//
//                        "hutang_komisi" => "nilai_kas_cn",
//                    ),

                    // kas
                    1 => array(
                        "kas_cabang" => "nilai_kas_cn",
                        "kas_pusat" => "nilai_kas_cn",
                        "hutang_ke_pusat" => "nilai_kas_cn",
                        "piutang_cabang" => "nilai_kas_cn",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                    ),
                    // creditnote
                    2 => array(
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => "nilai_kas_cn",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                    ),
                ),
                "pajakOption" => array(
                    "pph21" => array(
                        "pph21Valid" => ".1",
                        "pph23Methode" => ".0",
                        "pph23Methode__name" => ".0",
                        "pph23Methode__label" => ".0",
                        "pph23Methode__tarif" => ".0",
                        "pph23Valid" => ".0",
                        "pph__tarif" => "pph21Methode__tarif",
                    ),
                    "pph23" => array(
                        "pph23Valid" => ".1",
                        "pph21Methode" => ".0",
                        "pph21Methode__name" => ".0",
                        "pph21Methode__label" => ".0",
                        "pph21Methode__tarif" => ".0",
                        "pph21Valid" => ".0",
                        "pph__tarif" => "pph23Methode__tarif",
                    ),
                    "pph23_15" => array(
                        "pph23Valid" => ".1",
                        "pph21Methode" => ".0",
                        "pph21Methode__name" => ".0",
                        "pph21Methode__label" => ".0",
                        "pph21Methode__tarif" => ".0",
                        "pph21Valid" => ".0",
                        "pph__tarif" => "pph23Methode__tarif",
                    ),
                ),
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",

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
        "components" => array(),
        "postProcessor" => array(
            "6678" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerProject",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".komisi",
                            "state" => ".hold",
                            "jumlah" => ".1",
                            "produk_id" => "id",
                            "nama" => "name",
//                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
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
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
    "16678_OLD" => array(
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
            "master" => array(
                //==sumber nilai utama
//                "cabang2ID" => "branchID",
//                "cabang2Name" => "branchName",
//                "place2ID" => "branchID",
//                "place2Name" => "branchName",
//                "gudangID" => "gudang",
//                "gudangName" => "gudang__label",
//                "gudang2ID" => "gudang2",
//                "gudang2Name" => "gudang2__label",
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "kompensasiMethod" => array(
                    // kas
                    1 => array(
                        "kas_cabang" => "nilai_kas_cn",
                        "kas_pusat" => "nilai_kas_cn",
                        "hutang_ke_pusat" => "nilai_kas_cn",
                        "piutang_cabang" => "nilai_kas_cn",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",
                    ),
                    // creditnote
                    2 => array(
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => "nilai_kas_cn",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "hutang_pph_total" => "hutang_pph21+hutang_pph23",
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",
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
            "16678" => array(
                "master" => array(
                    // PUSAT, kas pusat dibawa ke cabang, saat cashback kas diberikan ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // CABANG, terima kas dari pusat, saat cashback kas diberikan ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // CABANG, pemberian cashback ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6140" => "biaya_cashback",//biaya project
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "2010120" => "hutang_komisi",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "6140" => "biaya_cashback",//biaya project
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "2010120" => "hutang_komisi",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-kas_cabang",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu hutang ke konsumen, uang muka tanpa relasi so (creditnote), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //biaya project
                    array(
                        "comName" => "RekeningPembantuBiayaProjectMain",
                        "loop" => array(
                            "6140" => "-biaya_cashback",//biaya project
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihakMainID",
                            "extern_nama" => "pihakMainName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //cabang jurnal EFISIENSI
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6140" => "-biaya_cashback",//biaya project
                            "3020010" => "-biaya_cashback",//efisiensi biaya
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
                            "6140" => "-biaya_cashback",//biaya gaji
                            "3020010" => "-biaya_cashback",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu biaya project, cashback masuk langsung dikeluarkan ke efisiensi/ numpang nyatat
                    array(
                        "comName" => "RekeningPembantuBiayaProjectMain",
                        "loop" => array(
                            "6140" => "biaya_cashback",//biaya project
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihakMainID",
                            "extern_nama" => "pihakMainName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // CABANG, pindah hutang pph23 di cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "hutang_pph_total",//hutang ke pusat
                            "2030010" => "-hutang_pph21",//hutang pph21
                            "2030030" => "-hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040010" => "hutang_pph_total",//hutang ke pusat
                            "2030010" => "-hutang_pph21",//hutang pph21
                            "2030030" => "-hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hutang_pph23",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
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
                            "2040010" => "hutang_pph21",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // PUSAT, terima pindahan hutang pph23 dari cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "hutang_pph_total",//piutang cabang
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "hutang_pph_total",//piutang cabang
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hutang_pph23",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hutang_pph21",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "hutang_pph21",
//                            "extern2_id" => ".9",
//                            "extern2_nama" => ".customer",
//                            "extern_id" => "customerID",// diisi customer
//                            "extern_nama" => "customerName",// diisi customer
                            "extern2_id" => ".11",
                            "extern2_nama" => ".freelancer",
                            "extern_id" => "freelancerDetails",// diisi customer
                            "extern_nama" => "freelancerDetails__nama",// diisi customer
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030030" => "hutang_pph23",// hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "customerID",// diisi customer
                            "extern_nama" => "customerName",// diisi customer
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "hutang_pph23",
                            "extern2_id" => ".9",
                            "extern2_nama" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(

                    // pembantu biaya usaha, cashback project masuk
                    array(
                        "comName" => "RekeningPembantuBiayaProjectSubItem",
                        "loop" => array(
                            "6140" => "sub_harga",//biaya project
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "pihakMainID",
                            "extern2_nama" => "pihakMainName",
                            "extern3_id" => "customerID",
                            "extern3_nama" => "customerName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // pembantu biaya usaha, cashback project keluar dipindah ke efisiensi
                    array(
                        "comName" => "RekeningPembantuBiayaProjectSubItem",
                        "loop" => array(
                            "6140" => "-sub_harga",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "pihakMainID",
                            "extern2_nama" => "pihakMainName",
                            "extern3_id" => "customerID",
                            "extern3_nama" => "customerName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // pembantu hutang komisi
                    array(
                        "comName" => "RekeningPembantuKomisiItem",
                        "loop" => array(
                            "2010120" => "sub_nilai_kas_cn_detail",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    //blok pembantu sub efisiensi biaya
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => ".4",
                            "extern2_nama" => ".quality",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "16678" => array(
                "master" => array(

                    // kas keluar dari pusat
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
                            "nilai" => "-kas_pusat",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // payment source creditnote konsumen
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka konsumen",
                            "tambah" => "hutang_ke_konsumen",
                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // pembantu hutang komisi
                    array(
                        "comName" => "PaymentSourceBuilder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => ".11",
                            "extern2_nama" => ".freelancer",
                            "extern3_id" => ".11",
                            "extern3_nama" => ".freelancer",
                            "extern4_id" => "customerID",
                            "extern4_nama" => "customerName",
                            "extern5_id" => "place2ID",
                            "extern5_nama" => "place2Name",
                            "label" => ".hutang komisi",
                            "target_jenis" => ".1488",
                            "jenis" => "jenisTr",
                            "reference_jenis" => "jenisTr",
                            "tagihan" => "sub_nilai_kas_cn_detail",
                            "sisa" => "sub_nilai_kas_cn_detail",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
    //-------------------
    //----------------------
    "6678" => array(
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
                "cabang2ID" => "cabang2",
                "cabang2Name" => "cabang2__nama",
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",
                "gudangID" => "gudang",
                "gudangName" => "gudang__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(
                // tidak resiprokal, yang diisi jumlah yang diterima pihak lain/perusahaan/freelancer...
                // maka variabel harga dihitung disini... 06 maret 2025
                "harga" => "(100/(100-pph__tarif))*nilai_kas_cn",
                "inv_new_net1" => "inv_new_net3-inv_grand_ppn",
                "inv_dpp_pengganti" => "inv_new_net1*(11/12)",
                "nilai_pph23" => "(pph23Methode__tarif/100)*harga",
                "nilai_pph21" => "(pph21Methode__tarif/100)*harga",
                "nilai_pph_original" => "(pph__tarif/100)*harga",

                // bila yang diisi dpp komisi (bruto), maka dibawah ini harus hidup
                // jumlah uang yang diterima freelancer dihitung disini.
//                "nilai_kas_cn" => "harga-nilai_pph23-nilai_pph21",
            ),
            "master_dependent" => array(
                "freelancerOption" => array(
                    1 => array(// MEMBER/TERDAFTAR
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => "nilai_kas_cn",
                        "hutang_komisi_pym_src" => ".0",
                        // ini dihilangkan, atau di-nol-kan valuenya
                        "cash_account" => ".0",
                        "cash_account__label" => ".0",
                        "cash_account__nama" => ".0",
                        "kompensasiMethod" => ".0",
                        "kompensasiMethod__label" => ".0",
                        "kompensasiMethod__name" => ".0",
                    ),
                    2 => array(// NON MEMBER/TIDAK TERDAFTAR
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                        "hutang_komisi_pym_src" => "nilai_kas_cn",
                    ),
                ),
                "kompensasiMethod" => array(
                    // kas
                    1 => array(
//                        "kas_cabang" => "nilai_kas_cn",
//                        "kas_pusat" => "nilai_kas_cn",
//                        "hutang_ke_pusat" => "nilai_kas_cn",
//                        "piutang_cabang" => "nilai_kas_cn",
//
//                        "hutang_ke_konsumen" => ".0",
//                        "biaya_cashback" => "harga",
//                        "hutang_pph23" => "nilai_pph23",
//                        "hutang_pph21" => "nilai_pph21",
//
//                        "hutang_komisi" => ".0",

                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => "nilai_kas_cn",
                        "hutang_komisi_pym_src" => "nilai_kas_cn",
                    ),
                    // creditnote
                    2 => array(
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => "nilai_kas_cn",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                        "hutang_komisi_pym_src" => ".0",
                    ),
                ),
                "pajakOption" => array(
                    "pph21" => array(
                        "pph21Valid" => ".1",
                        "pph23Methode" => ".0",
                        "pph23Methode__name" => ".0",
                        "pph23Methode__label" => ".0",
                        "pph23Methode__tarif" => ".0",
                        "pph23Valid" => ".0",
                        "pph__tarif" => "pph21Methode__tarif",
                    ),
                    "pph23" => array(
                        "pph23Valid" => ".1",
                        "pph21Methode" => ".0",
                        "pph21Methode__name" => ".0",
                        "pph21Methode__label" => ".0",
                        "pph21Methode__tarif" => ".0",
                        "pph21Valid" => ".0",
                        "pph__tarif" => "pph23Methode__tarif",
                    ),
                    "pph23_15" => array(
                        "pph23Valid" => ".1",
                        "pph21Methode" => ".0",
                        "pph21Methode__name" => ".0",
                        "pph21Methode__label" => ".0",
                        "pph21Methode__tarif" => ".0",
                        "pph21Valid" => ".0",
                        "pph__tarif" => "pph23Methode__tarif",
                    ),
                ),
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",

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
        "components" => array(),
        "postProcessor" => array(
            "6678" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerProject",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".komisi",
                            "state" => ".hold",
                            "jumlah" => ".1",
                            "produk_id" => "id",
                            "nama" => "name",
//                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
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
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
    "16678" => array(
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
            "master" => array(
                //==sumber nilai utama
//                "cabang2ID" => "branchID",
//                "cabang2Name" => "branchName",
//                "place2ID" => "branchID",
//                "place2Name" => "branchName",
//                "gudangID" => "gudang",
//                "gudangName" => "gudang__label",
//                "gudang2ID" => "gudang2",
//                "gudang2Name" => "gudang2__label",
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "master_dependent" => array(
                "kompensasiMethod" => array(
                    // kas
                    1 => array(
//                        "kas_cabang" => "nilai_kas_cn",
//                        "kas_pusat" => "nilai_kas_cn",
//                        "hutang_ke_pusat" => "nilai_kas_cn",
//                        "piutang_cabang" => "nilai_kas_cn",
//
//                        "hutang_ke_konsumen" => ".0",
//                        "biaya_cashback" => "harga",
//                        "hutang_pph23" => "nilai_pph23",
//                        "hutang_pph21" => "nilai_pph21",

                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => ".0",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => "nilai_kas_cn",
                        "hutang_komisi_pym_src" => "nilai_kas_cn",
                    ),
                    // creditnote
                    2 => array(
                        "kas_cabang" => ".0",
                        "kas_pusat" => ".0",
                        "hutang_ke_pusat" => ".0",
                        "piutang_cabang" => ".0",

                        "hutang_ke_konsumen" => "nilai_kas_cn",
                        "biaya_cashback" => "harga",
                        "hutang_pph23" => "nilai_pph23",
                        "hutang_pph21" => "nilai_pph21",

                        "hutang_komisi" => ".0",
                        "hutang_komisi_pym_src" => ".0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "hutang_pph_total" => "hutang_pph21+hutang_pph23",
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",
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
            "16678" => array(
                "master" => array(
                    // PUSAT, kas pusat dibawa ke cabang, saat cashback kas diberikan ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "piutang_cabang",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // CABANG, terima kas dari pusat, saat cashback kas diberikan ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "kas_pusat",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hutang_ke_pusat",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // CABANG, pemberian cashback ke konsumen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6140" => "biaya_cashback",//biaya project
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "2010120" => "hutang_komisi",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "6140" => "biaya_cashback",//biaya project
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            "2010120" => "hutang_komisi",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-kas_cabang",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__label",// diisi nama bank
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".2010050050",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu hutang komisi
                    array(
                        "comName" => "RekeningPembantuKomisi",
                        "loop" => array(
                            "2010120" => "hutang_komisi_pym_src",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "freelancerDetails",
                            "extern_nama" => "freelancerDetails__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // rekening pembantu hutang ke konsumen, uang muka tanpa relasi so (creditnote), konsumenID
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050050",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //biaya project
                    array(
                        "comName" => "RekeningPembantuBiayaProjectMain",
                        "loop" => array(
                            "6140" => "-biaya_cashback",//biaya project
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihakMainID",
                            "extern_nama" => "pihakMainName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //cabang jurnal EFISIENSI
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6140" => "-biaya_cashback",//biaya project
                            "3020010" => "-biaya_cashback",//efisiensi biaya
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
                            "6140" => "-biaya_cashback",//biaya gaji
                            "3020010" => "-biaya_cashback",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu biaya project, cashback masuk langsung dikeluarkan ke efisiensi/ numpang nyatat
                    array(
                        "comName" => "RekeningPembantuBiayaProjectMain",
                        "loop" => array(
                            "6140" => "biaya_cashback",//biaya project
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihakMainID",
                            "extern_nama" => "pihakMainName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // CABANG, pindah hutang pph23 di cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "hutang_pph_total",//hutang ke pusat
                            "2030010" => "-hutang_pph21",//hutang pph21
                            "2030030" => "-hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040010" => "hutang_pph_total",//hutang ke pusat
                            "2030010" => "-hutang_pph21",//hutang pph21
                            "2030030" => "-hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hutang_pph23",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
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
                            "2040010" => "hutang_pph21",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // PUSAT, terima pindahan hutang pph23 dari cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "hutang_pph_total",//piutang cabang
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060010" => "hutang_pph_total",//piutang cabang
                            "2030010" => "hutang_pph21",//hutang pph21
                            "2030030" => "hutang_pph23",//hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hutang_pph23",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hutang_pph21",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "hutang_pph21",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "hutang_pph21",
//                            "extern2_id" => ".9",
//                            "extern2_nama" => ".customer",
//                            "extern_id" => "customerID",// diisi customer
//                            "extern_nama" => "customerName",// diisi customer
                            "extern2_id" => ".11",
                            "extern2_nama" => ".freelancer",
                            "extern_id" => "freelancerDetails",// diisi customer
                            "extern_nama" => "freelancerDetails__nama",// diisi customer
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030030" => "hutang_pph23",// hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "customerID",// diisi customer
                            "extern_nama" => "customerName",// diisi customer
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "hutang_pph23",
                            "extern2_id" => ".9",
                            "extern2_nama" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(

                    // pembantu biaya usaha, cashback project masuk
                    array(
                        "comName" => "RekeningPembantuBiayaProjectSubItem",
                        "loop" => array(
                            "6140" => "sub_harga",//biaya project
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "pihakMainID",
                            "extern2_nama" => "pihakMainName",
                            "extern3_id" => "customerID",
                            "extern3_nama" => "customerName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // pembantu biaya usaha, cashback project keluar dipindah ke efisiensi
                    array(
                        "comName" => "RekeningPembantuBiayaProjectSubItem",
                        "loop" => array(
                            "6140" => "-sub_harga",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => "pihakMainID",
                            "extern2_nama" => "pihakMainName",
                            "extern3_id" => "customerID",
                            "extern3_nama" => "customerName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // pembantu hutang komisi
                    array(
                        "comName" => "RekeningPembantuKomisiItem",
                        "loop" => array(
                            "2010120" => "sub_nilai_kas_cn_detail",// hutang komisi freelancer
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    //blok pembantu sub efisiensi biaya
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "3020010" => "-sub_harga",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => ".4",
                            "extern2_nama" => ".quality",
                            "produk_qty" => "jml",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "16678" => array(
                "master" => array(

                    // kas keluar dari pusat
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
                            "nilai" => "-kas_pusat",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // payment source creditnote konsumen
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang_nama" => "placeName",
//                            "transaksi_id" => "uangMuka__transaksi_id",
                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".uang muka konsumen",
                            "tambah" => "hutang_ke_konsumen",
                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // pembantu hutang komisi
                    array(
                        "comName" => "PaymentSourceBuilder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "extern2_id" => ".11",
                            "extern2_nama" => ".freelancer",
                            "extern3_id" => ".11",
                            "extern3_nama" => ".freelancer",
                            "extern4_id" => "customerID",
                            "extern4_nama" => "customerName",
                            "extern5_id" => "place2ID",
                            "extern5_nama" => "place2Name",
                            "label" => ".hutang komisi",
                            "target_jenis" => ".1488",
                            "jenis" => "jenisTr",
                            "reference_jenis" => "jenisTr",
                            "tagihan" => "sub_nilai_kas_cn_detail",
                            "sisa" => "sub_nilai_kas_cn_detail",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
    //-------------------
    "1676" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
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
                "pajakOption" => array(
                    "pph21" => array(
                        "pph_pembagi" => ".100",
                        "nilai_pph21" => "((pph21Methode__tarif)/pph_pembagi)*harga",
                        "nilai_pph23" => ".0",
//                        "nilai_pph23_15" => ".0",

                    ),
                    "pph23" => array(
                        "pph_pembagi" => ".100",
                        "nilai_pph21" => ".0",
                        "nilai_pph23" => "((pph23Methode__tarif)/pph_pembagi)*harga",
//                        "nilai_pph23_15" => ".0",
                    ),
                    "pph23_15" => array(
                        "pph_pembagi" => ".100",
                        "nilai_pph21" => ".0",
//                        "nilai_pph23" => ".0",
//                        "nilai_pph23_15" => "((pph23Methode__tarif)/pph_pembagi)*harga",
                        "nilai_pph23" => "((pph23Methode__tarif)/pph_pembagi)*harga",
                    ),
                ),
                "pph21Methode" => array(
                    "npwp" => array(
                        "pph21Methode__nilai_pph_edit" => ".0",
                        "default__pph21Methode__nilai_pph_edit" => ".0",
                    ),
                    "non_npwp" => array(
                        "pph21Methode__nilai_pph_edit" => ".0",
                        "default__pph21Methode__nilai_pph_edit" => ".0",
                    ),
                    "jumlah_lain" => array(
                        "nilai_pph21" => "default__pph21Methode__nilai_pph_edit",
                    ),
                ),
                "pphMethodeStatus" => array(
                    "1" => array(// pajak ditanggung perusahaan
                        "pph_ditanggung" => "nilai_pph21+nilai_pph23_15+nilai_pph23",
                        "nilai_cash" => ".0",
                    ),
                    "2" => array(// pajak ditanggung pembeli
                        "pph_ditanggung" => ".0",
                        "nilai_cash" => "nilai_pph21+nilai_pph23_15+nilai_pph23",
                    ),
                ),
            ),
        ),

        "valueBuilders" => array(
            "grand_total" => "nett2",
            "tagihan" => "grand_total",
            "rl_tmp" => "grand_total-hpp",
            //----------------
            "biaya_usaha" => "harga",// total penjualan logam mulia
            "rugi_laba_logam_mulia" => "harga-hpp-pph_ditanggung",// rugi/laba penjualan logam mulia
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
        "preProcessor" => array(
            "1676" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoLogamMulia",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => ".0",
                        ),
                        "resultParams" => array(
                            "rsltItems" => array(
                                "id" => "produk_id",
                                "nama" => "produk_nama",
                                "name" => "produk_nama",
//                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "jml" => "qty",
                                "qty" => "qty",
                                "subtotal" => "subtotal",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
        "components" => array(
            "1676" => array(
                "master" => array(
                    //<editor-fold desc="jurnal dan rekening DC PUSAT">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "nilai_cash",//kas
                            "1010025010" => "-hpp",//logam mulia
                            "2030010" => "nilai_pph21",//hutang pph21
                            "2030030" => "nilai_pph23",//hutang pph23
                            "7010210" => "rugi_laba_logam_mulia",//rugi_laba logam mulia
                            "6010" => "biaya_usaha_pusat",//biaya usaha
                            "1010060040" => "piutang_biaya_cabang",//piutang biaya cabang
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
                            "1010010010" => "nilai_cash",//kas
                            "1010025010" => "-hpp",//logam mulia
                            "2030010" => "nilai_pph21",//hutang pph21
                            "2030030" => "nilai_pph23",//hutang pph23
                            "7010210" => "rugi_laba_logam_mulia",//rugi_laba logam mulia
                            "6010" => "biaya_usaha_pusat",//biaya usaha
                            "1010060040" => "piutang_biaya_cabang",//piutang biaya cabang
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
                            "1010010010" => "nilai_cash",
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
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030030" => "nilai_pph23",// hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "customerDetails",// diisi customer
                            "extern_nama" => "customerDetails__label",// diisi customer
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_pph23",
                            "extern2_id" => ".9",
                            "extern2_nama" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030010" => "nilai_pph21",//hutang pph21
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "customerDetails",// diisi customer
                            "extern_nama" => "customerDetails__label",// diisi customer
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_pph23",
                            "extern2_id" => ".9",
                            "extern2_nama" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_pusat",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "biaya_detail",
                            "extern_nama" => "biaya_detail__label",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060040" => "piutang_biaya_cabang",//piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakPembebanan",
                            "extern_nama" => "pihakPembebanan__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>


                    //<editor-fold desc="jurnal dan rekening CABANG">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6010" => "biaya_usaha_cabang",//biaya usaha
                            "2040020" => "hutang_biaya_kepusat",//hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "pihakPembebanan",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "6010" => "biaya_usaha_cabang",//biaya usaha
                            "2040020" => "hutang_biaya_kepusat",//hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "pihakPembebanan",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaUsahaMain",
                        "loop" => array(
                            "6010" => "biaya_usaha_cabang",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "pihakPembebanan",
                            "extern_id" => "biaya_detail",
                            "extern_nama" => "biaya_detail__label",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "hutang_biaya_kepusat",//hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "pihakPembebanan",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                ),
                "detail" => array(
                    // pembantu logam mulia
                    array(
                        "comName" => "RekeningPembantuLogamMuliaItem",
                        "loop" => array(
                            "1010025010" => "-sub_hpp",//logam mulia
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "rsltItems",
                        "srcRawGateName" => "rsltItems",
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "1676r" => array(
                "master" => array(),
                "detail" => array(
                    // locker logam mulia
                    array(
                        "comName" => "LockerStockLogamMulia",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".logam_mulia",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockLogamMulia",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".logam_mulia",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
//                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "1676" => array(
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
                            "nama" => "cash_account__label",
                            "nilai" => "nilai_cash",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // locker logam mulia
                    array(
                        "comName" => "LockerStockLogamMulia",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".logam_mulia",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockLogamMulia",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".logam_mulia",
                            "state" => ".sold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),

        ),
        "extendedSteps" => array(
            //            "discount" => array(
            //                "srcKey" => "discount",
            //                "groupID" => "admin",
            //                "components" => array(),
            //            ),
        ),

        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
//            "stepCode|masterID|placeID|olehID|customerID",
//            "stepCode|masterID|customerID",
//            "stepCode|masterID|placeID|customerID",
//            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
//            "stepCode|placeID|olehID|customerID",
//            "stepCode|customerID",
//            "stepCode|placeID|customerID",
//            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
//            "stepCode|masterID|placeID|olehID|customerID",
//            "stepCode|masterID|customerID",
//            "stepCode|masterID|placeID|customerID",
//            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",
        //-----
        "rebuilderCoreKey" => "pihakPembebanan__kode",
        "rebuilderCore" => array(
            // pusat
            100 => array(
                "biaya_usaha_pusat" => "biaya_usaha",
                "biaya_usaha_cabang" => ".0",
                "piutang_biaya_cabang" => ".0",
                "hutang_biaya_kepusat" => ".0",
            ),
            // cabang
            111 => array(
                "biaya_usaha_pusat" => ".0",
                "biaya_usaha_cabang" => "biaya_usaha",
                "piutang_biaya_cabang" => "biaya_usaha",
                "hutang_biaya_kepusat" => "biaya_usaha",
            ),
        ),
    ),

);


