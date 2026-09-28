<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
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
                    //<editor-fold desc="Com-jurnal dan rekening /// center">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                            //                            "{pihak2Name}" => "hpp",
                            "piutang biaya cabang" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            //                            "{pihak2Name}" => "hpp",
                            "piutang biaya cabang" => "hpp",
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
                            "piutang biaya cabang" => "hpp",
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
                    //</editor-fold>

                    //<editor-fold desc="Com-jurnal dan rekening /// branch">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            //                            "persediaan supplies" => "-hpp",
                            "{pihak2Name}" => "hpp",
                            "hutang biaya ke pusat" => "hpp",
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
                            //                            "persediaan supplies" => "-hpp",
                            "{pihak2Name}" => "hpp",
                            "hutang biaya ke pusat" => "hpp",
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
                            "hutang biaya ke pusat" => "hpp",
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
                    //</editor-fold>


                    //<editor-fold desc="Com-rugilaba dan neraca">
                    array(
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                ),
                "detail" => array(
                    //<editor-fold desc="Com-rekening pembantu, detail, center">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
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
                    //</editor-fold>
                    array(
                        "comName" => "{pihak2Com}",
                        "loop" => array(
                            "{pihak2Name}" => "sub_hpp",
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
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
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
            "7762"=>array(
                "master"=>array(),
                "detail"=>array(
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
    ),
    //otorisasi biaya produksi
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
                            "biaya produksi" => "harga",
                            "hutang biaya" => "harga",
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
                            "biaya produksi" => "harga",
                            "hutang biaya" => "harga",
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
                            "hutang biaya" => "harga",
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
                            "biaya produksi" => "-subtotal_rev",// dikeluarkan dari biaya produksi
                            "{costName_1}" => "costNilai_1", // masuk ke kategory cost
                            "{costName_2}" => "costNilai_2", // masuk ke kategory cost
                            "{costName_3}" => "costNilai_3", // masuk ke kategory cost
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
                            "biaya produksi" => "-subtotal_rev",// nilai_bayar
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
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
                            "efisiensi biaya" => "-subtotal_rev", // masuk ke efisiensi biaya bom
                            "{costName_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{costName_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{costName_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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
                            "efisiensi biaya" => "-subtotal_rev",// nilai_bayar
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
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
                            "efisiensi biaya" => "-costNilai_1",
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
                            "efisiensi biaya" => "-costNilai_2",
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
                            "efisiensi biaya" => "-costNilai_3",
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
                        "comName" => "RekeningPembantuBiayaProduksi",
                        "loop" => array(
                            "biaya produksi" => "harga",
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
                            //                            "{rekName}" => "-subtotal", // selain cabang produksi, maka nilainya 0 saja
                            "biaya produksi" => "-subtotal_rev", // selain cabang produksi, maka nilainya 0 saja
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
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
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
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
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
                            "efisiensi biaya" => "-costNilai_1",
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
                            "efisiensi biaya" => "-costNilai_2",
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
                            "efisiensi biaya" => "-costNilai_3",
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
    ),
    // config request biaya usaha...
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
    ),
    //otorisasi baiya usaha
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
                            "biaya usaha" => "harga",
                            "hutang biaya" => "harga",
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
                            "biaya usaha" => "harga",
                            "hutang biaya" => "harga",
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
                            "hutang biaya" => "harga",
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
                            "biaya usaha" => "-subtotal_rev",// dikeluarkan dari biaya produksi
                            "{costName_1}" => "costNilai_1", // masuk ke kategory cost
                            "{costName_2}" => "costNilai_2", // masuk ke kategory cost
                            "{costName_3}" => "costNilai_3", // masuk ke kategory cost
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
                            "biaya usaha" => "-subtotal_rev",// nilai_bayar
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
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
                            "efisiensi biaya" => "-subtotal_rev", // masuk ke efisiensi biaya bom
                            "{costName_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{costName_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{costName_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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
                            "efisiensi biaya" => "-subtotal_rev",// nilai_bayar
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
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
                            "efisiensi biaya" => "-costNilai_1",
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
                            "efisiensi biaya" => "-costNilai_2",
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
                            "efisiensi biaya" => "-costNilai_3",
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
                            "biaya usaha" => "harga",
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
                            "biaya usaha" => "-subtotal_rev", // selain cabang produksi, maka nilainya 0 saja
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
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
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
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
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
                            "efisiensi biaya" => "-costNilai_1",
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
                            "efisiensi biaya" => "-costNilai_2",
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
                            "efisiensi biaya" => "-costNilai_3",
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
    ),
    // config request biaya gaji...
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
        ),
        /*
 * CATATAN UNTUK PEMBUILD GERBANG hanya menggunakan 1 underscores contoh biaya_jasa . biaya_jasa_lima(ini tidak diperbolehkan)
 */
        "valueBuilders" => array(
            //biayagaji hutanggaji+bpjskaryawan+bpjs perusahaan
            "piutang_cabang_main"=>"hutang_gaji+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
            "hutang_bpjs_main"=>"hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main"=>"hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main"=>"hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main"=>"biaya_pph21_perusahaan",
            "biaya_gaji_cabang"=>"hutang_gaji",
            "efisiensi_biaya_master"=>"harga_efisiensi+efisiensi_bpjs+efisiensi_pph21",
            "grand_total"=>"piutang_cabang_main",
            "take_homepay"=>"hutang_gaji_main",
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

                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "piutang cabang" => "piutang_cabang_main",
                            //                            "piutang gaji cabang" => ".0",
                            "hutang gaji" => "hutang_gaji_main",
                            "hutang pph21" => "hutang_pph21_main",
                            "hutang bpjs" => "hutang_bpjs_main",
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
                            "piutang cabang" => "piutang_cabang_main",
                            //                            "piutang gaji cabang" => ".0",
                            "hutang gaji" => "hutang_gaji_main",
                            "hutang pph21" => "hutang_pph21_main",
                            "hutang bpjs" => "hutang_bpjs_main",
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
                            "piutang cabang" => "piutang_cabang_main",
                            //                            "piutang gaji cabang" => ".0",
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
                            "hutang gaji" => "hutang_gaji_main",
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

                    //endregion pusat


                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya gaji" => "biaya_gaji_cabang",
                            "biaya bpjs"=>"biaya_bpjs_perusahaan",
                            "biaya pph21"=>"biaya_pph21_perusahaan",
                            "hutang ke pusat" => "piutang_cabang_main",
                            //                            "hutang gaji ke pusat" => ".0",
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
                            "biaya gaji" => "biaya_gaji_cabang",
                            "biaya bpjs"=>"biaya_bpjs_perusahaan",
                            "biaya pph21"=>"biaya_pph21_perusahaan",
                            "hutang ke pusat" => "piutang_cabang_main",
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
                            "hutang ke pusat" => "piutang_cabang_main",
                            //                            "hutang gaji ke pusat" => ".0",
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
                            "biaya gaji" => "biaya_gaji_cabang",
                            //                            "hutang gaji ke pusat" => ".0",
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
                            "biaya pph21" => "biaya_pph21_perusahaan",
                            //                            "hutang gaji ke pusat" => ".0",
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
                            "biaya bpjs" => "biaya_bpjs_perusahaan",
                            //                            "hutang gaji ke pusat" => ".0",
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

                    // khusus di solo masuk ke efisiensi biaya
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya gaji" => "-harga_efisiensi",
                            "biaya bpjs" => "-efisiensi_bpjs",
                            "biaya pph21" => "-efisiensi_pph21",
                            "efisiensi biaya" => "-efisiensi_biaya_master",
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
                            "biaya gaji" => "-harga_efisiensi",
                            "biaya bpjs" => "-efisiensi_bpjs",
                            "biaya pph21" => "-efisiensi_pph21",
                            "efisiensi biaya" => "-efisiensi_biaya_master",
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
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "efisiensi biaya" => "-efisiensi_biaya_master",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",
                            "extern_nama" => ".quality",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //exrternid lihat di MdlGaji karena MdlStatic
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaSubMain",
                        "loop" => array(
                            "efisiensi biaya" => "-harga_efisiensi",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => ".4",
                            "extern2_nama" => ".quality",
                            "extern_id" => ".1",
                            "extern_nama" => ".gaji",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaSubMain",
                        "loop" => array(
                            "efisiensi biaya" => "-efisiensi_bpjs",
                        ),
                        //biaya bpjs
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => ".4",
                            "extern2_nama" => ".quality",
                            "extern_id" => ".4",
                            "extern_nama" => ".biaya bpjs",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuEfisiensiBiayaSubMain",
                        "loop" => array(
                            "efisiensi biaya" => "-efisiensi_pph21",
                        ),
                        //biaya bpjs
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern2_id" => ".4",
                            "extern2_nama" => ".quality",
                            "extern_id" => ".5",
                            "extern_nama" => ".biaya pph21",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    //                    array(
                    //                        "comName"        => "RekeningPembantuBiaya",
                    //                        "loop"           => array(
                    //                            "biaya gaji" => ".0",
                    //                        ),
                    //                        "static"         => array(
                    //                            "cabang_id"   => "place2ID",
                    //                            "extern_id"   => "id",
                    //                            "extern_nama" => "name",
                    //                            "jenis"       => "jenisTr",
                    //                        ),
                    //                        "srcGateName"    => "items",
                    //                        "srcRawGateName" => "items",
                    //                    ),
                ),
            ),

        ),
        "postProcessor" => array(),
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
    ),
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
                            "biaya umum" => "harga",
                            "hutang biaya" => "harga",
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
                            "biaya umum" => "harga",
                            "hutang biaya" => "harga",
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
                            "hutang biaya" => "harga",
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
                            "biaya umum" => "-subtotal_rev",// dikeluarkan dari biaya produksi
                            "{costName_1}" => "costNilai_1", // masuk ke kategory cost
                            "{costName_2}" => "costNilai_2", // masuk ke kategory cost
                            "{costName_3}" => "costNilai_3", // masuk ke kategory cost
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
                            "biaya umum" => "-subtotal_rev",// nilai_bayar
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
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
                            "efisiensi biaya" => "-subtotal_rev", // masuk ke efisiensi biaya bom
                            "{costName_1}" => "-costNilai_1", // dikeluarkan dari kategory cost
                            "{costName_2}" => "-costNilai_2", // dikeluarkan dari kategory cost
                            "{costName_3}" => "-costNilai_3", // dikeluarkan dari kategory cost
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
                            "efisiensi biaya" => "-subtotal_rev",// nilai_bayar
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
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
                            "efisiensi biaya" => "-costNilai_1",
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
                            "efisiensi biaya" => "-costNilai_2",
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
                            "efisiensi biaya" => "-costNilai_3",
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
                        "comName" => "RekeningPembantuBiayaUmum",
                        "loop" => array(
                            "biaya umum" => "harga",
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
                            "biaya umum" => "-subtotal_rev", // selain cabang produksi, maka nilainya 0 saja
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
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
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
                            "{costName_1}" => "-costNilai_1",
                            "{costName_2}" => "-costNilai_2",
                            "{costName_3}" => "-costNilai_3",
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
                            "efisiensi biaya" => "-costNilai_1",
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
                            "efisiensi biaya" => "-costNilai_2",
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
                            "efisiensi biaya" => "-costNilai_3",
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
    ),
    // config request biaya umum pusat
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
            "1675" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya umum" => "harga",
                            "hutang biaya" => "harga",
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
                            "biaya umum" => "harga",
                            "hutang biaya" => "harga",
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
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
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
                            "biaya umum" => "harga",
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
    ),
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
                            "biaya" => "harga",
                            "hutang biaya" => "harga",
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
                            "biaya" => "harga",
                            "hutang biaya" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
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
                            "biaya" => "harga",
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
    ),
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
            "1677" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya usaha" => "harga",
                            "hutang biaya" => "harga",
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
                            "biaya usaha" => "harga",
                            "hutang biaya" => "harga",
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
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "biaya usaha" => "harga",
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
    ),
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
                    //<editor-fold desc="Com-jurnal dan rekening">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "persediaan supplies" => "-hpp",
                            "{pihak2Name}" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            "{pihak2Name}" => "hpp",
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

                    //<editor-fold desc="Com-rugilaba dan neraca">
                    array(
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                ),
                "detail" => array(
                    //<editor-fold desc="Com-rekening pembantu, detail">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
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
                            "{pihak2Name}" => "sub_hpp",
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
            "762" =>array(
                "master"=>array(),
                "detail"=>array(
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
    ),
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
            "9982" => array(
                "master" => array(
                    // pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya" => "-harga_disc",
                            "piutang biaya cabang" => "harga_disc",
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
                            "biaya" => "-harga_disc",
                            "piutang biaya cabang" => "harga_disc",
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
                            "piutang biaya cabang" => "harga_disc",
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
                            "biaya usaha" => "harga_disc",
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "biaya usaha" => "harga_disc",
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "biaya" => "-harga_disc",
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
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "biaya usaha" => "harga_disc",
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
    ),
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
                            "biaya" => "-harga_disc",
                            "piutang biaya cabang" => "harga_disc",
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
                            "biaya" => "-harga_disc",
                            "piutang biaya cabang" => "harga_disc",
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
                            "piutang biaya cabang" => "harga_disc",
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
                            "biaya umum" => "harga_disc",
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "biaya umum" => "harga_disc",
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "biaya" => "-harga_disc",
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
                            "biaya umum" => "harga_disc",
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
                            "biaya" => "-harga_disc",
                            "piutang biaya cabang" => "harga_disc",
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
                            "biaya" => "-harga_disc",
                            "piutang biaya cabang" => "harga_disc",
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
                            "piutang biaya cabang" => "harga_disc",
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
                            "biaya produksi" => "harga_disc",
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "biaya produksi" => "harga_disc",
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "hutang biaya ke pusat" => "harga_disc",
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
                            "biaya produksi" => "-harga_disc",
                            "efisiensi biaya" => "-harga_disc",

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
                            "biaya produksi" => "-harga_disc",
                            "efisiensi biaya" => "-harga_disc",
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
                            "efisiensi biaya" => "-harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "pihakID",
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
                            "biaya" => "-harga_disc",
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
                            "biaya produksi" => "harga_disc",
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
                            "biaya produksi" => "-harga_disc",
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
                        "comName" => "RekeningPembantuEfisiensiBiaya",
                        "loop" => array(
                            "efisiensi biaya" => "-harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "pihak2ID",
                            "extern_nama" => "pihak2Name",
                            "extern2_id" => "costID",
                            "extern2_nama" => "costName",
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
    ),
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
        "preProcessor" => array(
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
            "9985" => array(
                "master" => array(
                    //<editor-fold desc="Com-jurnal dan rekening /// center">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihak2Name}" => "harga_disc",
                            "biaya usaha" => "-harga_disc",
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
                            "{pihak2Name}" => "harga_disc",
                            "biaya usaha" => "-harga_disc",
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


                    //<editor-fold desc="Com-rugilaba dan neraca">
                    array(
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
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
                            "biaya usaha" => "-sub_harga_disc",
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
                            "{pihak2Name}" => "sub_harga_disc",
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
    ),
    //koteksi biaya ke ppv
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
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
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
                            "biaya" => "-harga",
                            "hutang lain ppv" => "-harga",
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
                            "biaya" => "-harga",
                            "hutang lain ppv" => "-harga",
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
                            "biaya" => "-harga",
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
                            "biaya bunga" => "nilai_bunga",
                            "hutang biaya bunga" => "nilai_kas_dipakai",
                            "hutang pph23" => "nilai_pph23",
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
                            "biaya bunga" => "nilai_bunga",
                            "hutang biaya bunga" => "nilai_kas_dipakai",
                            "hutang pph23" => "nilai_pph23",
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
                            "hutang biaya bunga" => "nilai_kas_dipakai",
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
                            "biaya bunga" => "nilai_bunga",
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
                            "hutang pph23" => "nilai_pph23",
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
                            "{pihakMainName}" => "harga",
                            "{taxesMethod}" => "ppn",
                            "hutang biaya" => "nett",
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
                            "{pihakMainName}" => "harga",
                            "{taxesMethod}" => "ppn",
                            "hutang biaya" => "nett",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
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
                            "{pihakMainName}" => "harga",
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
    ),
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
                            //                            "nilai" => "sub_hpp",
                            "jenisTr" => "jenisTr",
                            "pre_biaya_id" => "pihak3ID",
                            "pre_biaya_nama" => "pihak3Name",
                        ),
                        "resultParams" => array(
                            "items2_sum" => array(
                                "costID" => "pre_biaya_id",
                                "costName" => "pre_biaya_nama",
                                "costNilai" => "nilai",
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
                            "persediaan supplies" => "-hpp",
                            "{pihak2Name}" => "hpp",
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
                            "persediaan supplies" => "-hpp",
                            "{pihak2Name}" => "hpp",
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
                            "{pihakMainName_rev}" => "-nilai_bayar_rev",
                            "{costName_1}" => "nilai_bayar_rev",// costNilai_1
                            "{costName_2}" => "nilai_bayar_rev",// costNilai_2
                            "{costName_3}" => "nilai_bayar_rev",// costNilai_3
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
                            "{pihakMainName_rev}" => "-nilai_bayar_rev",
                            "{costName_1}" => "nilai_bayar_rev",// costNilai_1
                            "{costName_2}" => "nilai_bayar_rev",// costNilai_2
                            "{costName_3}" => "nilai_bayar_rev",// costNilai_3
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

                    //<editor-fold desc="Com-rugilaba dan neraca">
                    array(
                        "comName" => "RugiLaba",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Neraca",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                ),
                "detail" => array(
                    //<editor-fold desc="Com-rekening pembantu, detail">
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "persediaan supplies" => "-sub_hpp",
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
                            "{pihakMainName}" => "sub_hpp",
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
                        //                        "comName" => "{relativeCom}",
                        "comName" => "{pihak2Com}",
                        "loop" => array(
                            //                            "{pihakMainName_rev}" => "-nilai_bayar_rev",
                            "{pihak2Name}" => "-subtotal_rev",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihak3ID",
                            "extern_nama" => "pihak3Name",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuBiayaKomposisiProduksi",
                        "loop" => array(
                            "{costName_1}" => "costNilai_1",
                            "{costName_2}" => "costNilai_2",
                            "{costName_3}" => "costNilai_3",
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
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Com-locker stock">
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
                    //</editor-fold>
                ),
            ),
        ),
        "postProcessor" => array(
            "2762" => array(
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
        ),
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
                            //                            "hutang ke konsumen" => "nilai_masuk",
                            "kas" => "subtotal",
                            "pendapatan lain_lain" => "subtotal",
                            //                            "{rekName}" => "subtotal",
                            //                            "ppn out" => "ppn",
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
                            //                            "hutang ke konsumen" => "nilai_masuk",
                            "kas" => "subtotal",
                            "{rekName}" => "subtotal",
                            //                            "ppn out" => "ppn",
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
                            "kas" => "subtotal",
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
                            //                            "{pihakMainChild}" => "harga",//tembak dulu
                            "pendapatan lain_lain" => "harga",//tembak dulu
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
    ),
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
                            //                            "hutang ke konsumen" => "nilai_masuk",
                            "kas" => "-subtotal",
                            "beban lain lain" => "subtotal",
                            //                            "ppn out" => "ppn",
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
                            //                            "hutang ke konsumen" => "nilai_masuk",
                            "kas" => "-subtotal",
                            "beban lain lain" => "subtotal",
                            //                            "ppn out" => "ppn",
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
                            "kas" => "-subtotal",
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
                            "beban lain lain" => "subtotal",
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
    ),
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
            "biaya_gaji_main"=>"hutang_gaji",
            "hutang_bpjs_main"=>"hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main"=>"hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main"=>"hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main"=>"biaya_pph21_perusahaan",
            "take_homepay"=>"hutang_gaji_main",

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
                            //                            "piutang cabang" => "harga",
                            "biaya gaji" => "biaya_gaji_main",
                            "biaya bpjs" => "biaya_bpjs_perusahaan",
                            "biaya pph21" => "biaya_pph21_perusahaan",
                            "hutang gaji" => "hutang_gaji_main",
                            "hutang pph21" => "hutang_pph21_main",
                            "hutang bpjs" => "hutang_bpjs_main",
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
                            "biaya gaji" => "biaya_gaji_main",
                            "biaya bpjs" => "biaya_bpjs_perusahaan",
                            "biaya pph21" => "biaya_pph21_perusahaan",
                            "hutang gaji" => "hutang_gaji_main",
                            "hutang pph21" => "hutang_pph21_main",
                            "hutang bpjs" => "hutang_bpjs_main",
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
                            "hutang gaji" => "hutang_gaji_main",
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
                            "biaya gaji" => "biaya_gaji_main",
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
                            "biaya pph21" => "biaya_pph21_perusahaan",
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
                            "biaya bpjs" => "biaya_bpjs_perusahaan",
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
    ),
    //-----------up sudah modul -----

);