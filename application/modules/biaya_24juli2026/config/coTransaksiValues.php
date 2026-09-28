<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(
    //produk
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
        "components" => array(),
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
                            "0601" => "harga",//biaya produksi
                            "020404" => "harga",//hutang biaya
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
                            "0601" => "harga",//biaya produksi
                            "020404" => "harga",//hutang biaya
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
                            "020404" => "harga",//hutang biaya
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
    //otorisasi biya usaha
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
        "postProcessor" => array(),
    ),
    // config request biaya gaji done
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
            "piutang_cabang_main" => "hutang_gaji+biaya_bpjs_perusahaan+biaya_pph21_perusahaan",
            "hutang_bpjs_main" => "hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
            "biaya_gaji_cabang" => "hutang_gaji",
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
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang_main",//piutang cabang
                            //                            "piutang gaji cabang" => ".0",
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
                            "1010060010" => "piutang_cabang_main",//piutang cabang
                            //                            "piutang gaji cabang" => ".0",
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
                            "1010060010" => "piutang_cabang_main",//piutang cabang
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

                    //endregion pusat

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6050" => "biaya_gaji_cabang",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
                            "2040010" => "piutang_cabang_main",//hutang ke pusat
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
                            "6050" => "biaya_gaji_cabang",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
                            "2040010" => "piutang_cabang_main",//hutang ke pusat
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "piutang_cabang_main",//hutang ke pusat
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
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
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
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
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
                            "6050" => "-harga_efisiensi",//biaya gaji
                            "6080" => "-efisiensi_bpjs",//biaya bpjs
                            "6090" => "-efisiensi_pph21",//biaya pph21
                            "3020010" => "-efisiensi_biaya_master",//efisiensi biaya
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
                            "6050" => "-harga_efisiensi",//biaya gaji
                            "6080" => "-efisiensi_bpjs",//biaya bpjs
                            "6090" => "-efisiensi_pph21",//biaya pph21
                            "3020010" => "-efisiensi_biaya_master",//efisiensi biaya
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
                            "3020010" => "-efisiensi_biaya_master",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",//ganti coa bro
//                            "extern_id" => ".03020100003",//ganti coa bro
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
                            "3020010" => "-harga_efisiensi",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",//ori
//                            "extern_id" => ".3020010030",//ganti coa bro
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
                            "3020010" => "-efisiensi_bpjs",//efisiensi biaya
                        ),
                        //biaya bpjs
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",//ori
//                            "extern_id" => ".3020010030",//ganti coa bro
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
                            "3020010" => "-efisiensi_pph21",//efisiensi biaya
                        ),
                        //biaya bpjs
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",//ori
//                            "extern_id" => ".3020010030",//ganti coa bro
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
            "biaya_gaji_cabang" => "hutang_gaji",
            "efisiensi_biaya_master" => "harga_efisiensi+efisiensi_bpjs+efisiensi_pph21",
            "grand_total" => "piutang_cabang_main",
            "take_homepay" => "hutang_gaji_main",
        ),

        "preProcessor" => array(
            "7674" => array(
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
            "7674" => array(
                "master" => array(
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "piutang_cabang_main",//piutang cabang
                            //                            "piutang gaji cabang" => ".0",
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
                            "1010060010" => "piutang_cabang_main",//piutang cabang
                            //                            "piutang gaji cabang" => ".0",
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
                            "1010060010" => "piutang_cabang_main",//piutang cabang
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

                    //endregion pusat

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6050" => "biaya_gaji_cabang",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
                            "2040010" => "piutang_cabang_main",//hutang ke pusat
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
                            "6050" => "biaya_gaji_cabang",//biaya gaji
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
                            "2040010" => "piutang_cabang_main",//hutang ke pusat
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
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "piutang_cabang_main",//hutang ke pusat
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
                            "6090" => "biaya_pph21_perusahaan",//biaya pph21
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
                            "6080" => "biaya_bpjs_perusahaan",//biaya bpjs
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
                            "6050" => "-harga_efisiensi",//biaya gaji
                            "6080" => "-efisiensi_bpjs",//biaya bpjs
                            "6090" => "-efisiensi_pph21",//biaya pph21
                            "3020010" => "-efisiensi_biaya_master",//efisiensi biaya
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
                            "6050" => "-harga_efisiensi",//biaya gaji
                            "6080" => "-efisiensi_bpjs",//biaya bpjs
                            "6090" => "-efisiensi_pph21",//biaya pph21
                            "3020010" => "-efisiensi_biaya_master",//efisiensi biaya
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
                            "3020010" => "-efisiensi_biaya_master",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",//ganti coa bro
//                            "extern_id" => ".03020100003",//ganti coa bro
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
                            "3020010" => "-harga_efisiensi",//efisiensi biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",//ori
//                            "extern_id" => ".3020010030",//ganti coa bro
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
                            "3020010" => "-efisiensi_bpjs",//efisiensi biaya
                        ),
                        //biaya bpjs
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",//ori
//                            "extern_id" => ".3020010030",//ganti coa bro
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
                            "3020010" => "-efisiensi_pph21",//efisiensi biaya
                        ),
                        //biaya bpjs
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => ".4",//ori
//                            "extern_id" => ".3020010030",//ganti coa bro
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
                            "0606" => "nilai_bunga",//biaya bunga
                            "020406" => "nilai_kas_dipakai",//hutang biaya bunga
                            "020302" => "nilai_pph23",//hutang pph23
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
                            "0606" => "nilai_bunga",//biaya bunga
                            "020406" => "nilai_kas_dipakai",//hutang biaya bunga
                            "020302" => "nilai_pph23",//hutang pph23
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
                            "020406" => "nilai_kas_dipakai",//hutang biaya bunga
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
                            "0606" => "nilai_bunga",//biaya bunga
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
                            "020302" => "nilai_pph23",//hutang pph23
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
            "biaya_gaji_main" => "hutang_gaji",
            "hutang_bpjs_main" => "hutang_bpjs_karyawan+biaya_bpjs_perusahaan",
            "hutang_pph21_main" => "hutang_pph21_karyawan+biaya_pph21_perusahaan",
            "hutang_gaji_main" => "hutang_gaji-(hutang_pph21_karyawan+hutang_bpjs_karyawan)",
            "biaya_pph21_main" => "biaya_pph21_perusahaan",
            "take_homepay" => "hutang_gaji_main",

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
        "postProcessor" => array(),
    ),
    //biaya project
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
                "place2ID" => "pihakProjekID",
                "place2Name" => "pihakProjekName",
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
            "3674" => array(
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
    ),
    "3675" => array(
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
                "place2ID" => "pihakProjekID",
                "place2Name" => "pihakProjekName",
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
            "3675" => array(
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
    ),
    //-----------up sudah modul -----


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
                "cabang2ID" => "branchID",
                "cabang2Name" => "branchName",
                "place2ID" => "branchID",
                "place2Name" => "branchName",
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
                "cabang2ID" => "branchID",
                "cabang2Name" => "branchName",
                "place2ID" => "branchID",
                "place2Name" => "branchName",
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
            "master" => array(//==sumber nilai utama
                "cabang2ID" => "branchID",
                "cabang2Name" => "branchName",
                "place2ID" => "branchID",
                "place2Name" => "branchName",
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
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
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
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
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
                            "1010010010" => "-kas",// kas
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
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
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


                    // CABANG, pindah hutang pph23 di cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "hutang_pph23",//hutang ke pusat
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
                            "2040010" => "hutang_pph23",//hutang ke pusat
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


                    // PUSAT, terima pindahan hutang pph23 dari cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "hutang_pph23",//piutang cabang
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
                            "1010060010" => "hutang_pph23",//piutang cabang
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

                ),
                "detail" => array(),
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
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "kas_pusat",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // kas keluar dari cabang
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "-kas_cabang",
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
                "detail" => array(),
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
            "master" => array(//==sumber nilai utama
                "cabang2ID" => "branchID",
                "cabang2Name" => "branchName",
                "place2ID" => "branchID",
                "place2Name" => "branchName",
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
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
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
                            "2030030" => "hutang_pph23",//hutang pph23
                            "1010010010" => "-kas_cabang",//kas
                            "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
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
                            "1010010010" => "-kas",// kas
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
                    // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
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


                    // CABANG, pindah hutang pph23 di cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "hutang_pph23",//hutang ke pusat
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
                            "2040010" => "hutang_pph23",//hutang ke pusat
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


                    // PUSAT, terima pindahan hutang pph23 dari cabang ke pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "hutang_pph23",//piutang cabang
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
                            "1010060010" => "hutang_pph23",//piutang cabang
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

                ),
                "detail" => array(),
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
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "kas_pusat",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // kas keluar dari cabang
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__label",
                            "nilai" => "-kas_cabang",
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
                "detail" => array(),
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
    "1676" => array(
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
        "preProcessor" => array(
            "1676" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Preproc DETAIL">
                    array(
                        "comName" => "FifoValasAverage",
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
                                "hpp" => "hpp",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "FifoValas",
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
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
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
                    //<editor-fold desc="jurnal">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "grand_total",//kas
                            "4010040" => "grand_total",//penjualan valas
                            "1010010020" => "-hpp",//valas aka persediaan valas
                            "5040" => "hpp",//hpp valas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="com-rekening">
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010010010" => "grand_total",//kas
                            "4010040" => "grand_total",//penjualan valas
                            "1010010020" => "-hpp",//valas aka persediaan valas
                            "5040" => "hpp",//hpp valas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //</editor-fold>

                    //<editor-fold desc="com-rekening-pembantu">
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "grand_total",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "paymentMethod_cash",// diisi id bank
                            "extern_nama" => ".0",// diisi nama bank
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    //<editor-fold desc="Com-pembantu produk">
                    array(
                        "comName" => "RekeningPembantuValas",
                        "loop" => array(
                            "1010010020" => "-sub_hpp",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        //                        "srcGateName" => "out_detail_rsltItems",
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                ),
            ),
        ),
        "postProcessor" => array(
            "1676" => array(
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

);