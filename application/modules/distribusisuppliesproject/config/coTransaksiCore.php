<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(

    // DISTRIBUSI PROJECT
    //  config distribusi dari dc ke cabang project
    "5834" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID|cabang2ID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(
                "gudangID" => "pihakProjekID",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
//                "gudang2ID" => "pihakProjekGudangID",
//                "gudang2Name" => "pihakProjekGudangNama",
//                "gudang2ID" => "pihakProjekWorkorderGudangID",
//                "gudang2Name" => "pihakProjekWorkorderGudangName",
                "gudang2ID" => "pihakProjekWorkorderSubGudangID",
                "gudang2Name" => "pihakProjekWorkorderSubGudangName",
            ),
            "detail" => array(),
            "detail_rsltItems" => array(),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "5834sc" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
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
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

//                "gudang_id" => "pihakProjekID",
//                "gudang_nama" => "gudangProjectName",
                "gudang_id" => "gudangProjectID",
                "gudang_nama" => "gudangProjectName",
//                "gudang2_id" => "gudang2ID",
//                "gudang2_nama" => "gudang2Name",
                "gudang2_id" => "gudangProject2ID",
                "gudang2_nama" => "gudangProject2Name",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
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
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(
            "5834r" => array(
                "master" => array(),
                "detail" => array(),
                "sub_detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "biaya_id" => "biaya_id",
                            "oleh2_nama" => "pihakProjekNoSpk",
//                            "oleh2_nama" => "no_spk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh2_id" => ".0",
                            "oleh2_nama" => "pihakProjekNoSpk",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "biaya_id" => "biaya_id",
//                            "oleh2_nama" => "no_spk",
                            "oleh2_nama" => "pihakProjekNoSpk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                    //----
//                    array(
//                        "comName" => "TransaksiProduk",
//                        "loop" => array(
//                            "5834r" => "jml",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "rekening_nama" => "targetJenisLabel",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => ".1",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "pihakProjekWorkOrderSubID",
//                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_name" => "olehName",
//                            "master_id" => "transaksi_id",
//                            "master_jenis" => "jenisTrMaster",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2",
//                        "srcRawGateName" => "items2",
//                    ),
//                    array(
//                        "comName" => "TransaksiProduk",
//                        "loop" => array(
//                            "5834" => "-qty",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "rekening_nama" => "targetJenisLabel",
//                            "produk_qty" => "-qty",
//                            "produk_nilai" => ".1",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "pihakProjekWorkOrderSubID",
//                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_name" => "olehName",
//                            "master_id" => "transaksi_id",
//                            "master_jenis" => "jenisTrMaster",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2",
//                        "srcRawGateName" => "items2",
//                    ),
                ),
            ),
            "5834" => array(
                "master" => array(),
                "detail" => array(),
                "sub_detail" => array(
                    //----
//                    array(
//                        "comName" => "TransaksiProduk",
//                        "loop" => array(
//                            "5834" => "-qty",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "rekening_nama" => "targetJenisLabel",
//                            "produk_qty" => "-qty",
//                            "produk_nilai" => ".1",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "pihakProjekWorkOrderSubID",
//                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_name" => "olehName",
//                            "master_id" => "transaksi_id",
//                            "master_jenis" => "jenisTrMaster",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2",
//                        "srcRawGateName" => "items2",
//                    ),
//                    array(
//                        "comName" => "TransaksiProduk",
//                        "loop" => array(
//                            "5856" => "jml",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "gudang_nama" => "gudangName",
//                            "cabang_nama" => "placeName",
//                            "rekening_nama" => "targetJenisLabel",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => ".1",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "extern2_id" => "pihakProjekWorkOrderSubID",
//                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
//                            "produk_kode" => "code",
//                            "produk_part" => "no_part",
//                            "produk_label" => "label",
//                            "produk_jenis" => "jenis",
//                            "produk_satuan" => "satuan",
//                            "satuan" => "satuan",
//                            "oleh_id" => "olehID",
//                            "oleh_name" => "olehName",
//                            "master_id" => "transaksi_id",
//                            "master_jenis" => "jenisTrMaster",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items2",
//                        "srcRawGateName" => "items2",
//                    ),
                ),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabang2ID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabang2ID",
    ),

    //  config penerimaan distribusi di cabang project
    "5856" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
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
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "5856" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "jml",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "resultParams" => array(
                            "items2_sum" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
                "sub_detail" => array(),
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

//                "gudang_id" => "gudangID",
//                "gudang_nama" => "gudangName",
//                "gudang_id" => "gudangProjectID",
//                "gudang_nama" => "gudangProjectName",
                "gudang_id" => "gudangProject2ID",
                "gudang_nama" => "gudangProject2Name",
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
                //                "nett" => "hpp",
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
            "5856" => array(
                "master" => array(
                    //<editor-fold desc="komponen milik pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-hpp",// persediaan supplies
                            "1010060010" => "hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030010" => "-hpp",// persediaan supplies
                            "1010060010" => "hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "pihak2ID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="komponen milik cabang">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "hpp",// persediaan supplies
                            "2040010" => "hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030010" => "hpp",// persediaan supplies
                            "2040010" => "hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                ),
                "detail" => array(

                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "biaya_dasar_nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // rekening pembantu produk serial
//                    array(
//                        "comName" => "RekeningPembantuProdukPerSerial",
//                        "loop" => array(
//                            "1010030010" => ".-1",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "gudang_id" => "gudangProjectID",
//                            "extern_id" => ".0",
//                            "extern_nama" => "produk_serial",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "produk_sku_part_nama",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => ".1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "supplierID" => "pihakID",
//                        ),
//                        "srcGateName" => "items3_sum",
//                        "srcRawGateName" => "items3_sum",
//                    ),


                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "biaya_dasar_nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangWorkOrderTarget",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    // rekening pembantu produk serial
//                    array(
//                        "comName" => "RekeningPembantuProdukPerSerial",
//                        "loop" => array(
//                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "extern_id" => ".0",
//                            "extern_nama" => "produk_serial",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "produk_sku_part_nama",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => ".1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "supplierID" => "pihakID",
//                        ),
//                        "srcGateName" => "items3_sum",
//                        "srcRawGateName" => "items3_sum",
//                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "5856" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "nama",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
                "sub_detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "oleh_id" => ".0",
//                            "oleh_nama" => "pihakProjekNoSpk",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                            "biaya_id" => "biaya_id",
                        ),
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".distribute",
                            "jumlah" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
//                            "transaksi_id" => "id_master",
//                            "nomer" => "nomer",
                            "oleh2_id" => ".0",
                            "oleh2_nama" => "pihakProjekNoSpk",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                            "biaya_id" => "biaya_id",
                        ),
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),

                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-jml",
                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "biaya_id" => "biaya_id",
                        ),
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),

                    //----memcatat produk yang diterima...
                    array(
                        "comName" => "LockerStockWorkOrder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "jenis" => ".supplies",
                            "state" => ".diterima",
                            "jumlah" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "project_id" => "pihakProjekID",
                            "work_order_id" => "pihakProjekWorkOrderID",
                            "biaya_id" => "biaya_id",
                        ),
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                ),
            ),
        ),
    ),

    // RETURN DISTRIBUSI PROJECT
    //  config distribusi dari dc ke cabang project
    "9834" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID|cabang2ID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(
                "cabang2ID" => "cabangTargetID",
                "cabang2Name" => "pihakName",
                "place2ID" => "cabangTargetID",
                "place2Name" => "pihakName",

                "gudangID" => "gudangProject2ID",
                "gudangName" => "pihakProjekWorkorderSubGudangName",

                "gudangProjectID" => "gudang",
                "gudangProjectName" => "gudang__label",
                "gudang2ID" => "gudangProjectID",// gudang project pusat/dc
                "gudang2Name" => "gudangProjectName",// gudang project pusat/dc
            ),
            "detail" => array(
//                "stok_awal" => "stok+jml"
                "jml_dikembalikan" => "jml",
                "qty_dikembalikan" => "jml",
                "jml_dipakai" => "stok_awal-jml",
                "qty_dipakai" => "jml_dipakai",
            ),
            "detail_rsltItems" => array(),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "9834sc" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "ProdukProjectReturn",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
//                            "gudang_id" => "gudangProjectID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                            "produk_id" => "id",
                            "produk_nama" => "nama",
                            "gate_target" => ".items7_sum",
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
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),

            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
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

        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
            "rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(
            "9834r" => array(
                "master" => array(),
                "detail" => array(),
                "sub_detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "biaya_id" => "biaya_id",
                            "oleh2_nama" => "pihakProjekNoSpk",
//                            "oleh2_nama" => "no_spk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "pihakProjekNoSpk",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "biaya_id" => "biaya_id",
//                            "oleh2_nama" => "no_spk",
                            "oleh2_nama" => "pihakProjekNoSpk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                ),
            ),
            "9834" => array(
                "master" => array(),
                "detail" => array(),
                "sub_detail" => array(

                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "biaya_id" => "biaya_id",
                            "oleh2_nama" => "pihakProjekNoSpk",
//                            "oleh2_nama" => "no_spk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
                            "state" => ".return",
                            "jumlah" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "pihakProjekNoSpk",
                            "transaksi_id" => ".0",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "biaya_id" => "biaya_id",
//                            "oleh2_nama" => "no_spk",
                            "oleh2_nama" => "pihakProjekNoSpk",
                        ),
                        "reversable" => true,
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
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaEdit" => "stepCode|placeID|cabang2ID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|cabang2ID",
            "stepCode|masterID|placeID|cabang2ID",
        ),
        "formatNotaReject" => "stepCode|placeID|cabang2ID",
    ),
    //  config penerimaan distribusi di cabang project
    "9856" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
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
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "9856" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "biaya_dasar_id",
                            "extern_nama" => "biaya_dasar_nama",
                            "produk_qty" => "jml",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "jenisTr" => "jenisTr",
                            "exception" => ".1",
                        ),
                        "resultParams" => array(
                            "items2_sum" => array(
                                "harga" => "hpp",
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                            ),
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
                "sub_detail" => array(),
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
                //                "nett" => "hpp",
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
            "9856" => array(
                "master" => array(
                    //<editor-fold desc="komponen milik pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "hpp",// persediaan supplies
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030010" => "hpp",// persediaan produk
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",// ini pakai placeID bukan place2ID (dterima di DC/PUSAT, maka place2ID adalah cabang)
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                    //<editor-fold desc="komponen milik cabang">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-hpp",// persediaan produk
                            "2040010" => "-hpp",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030010" => "-hpp",// persediaan produk
                            "2040010" => "-hpp",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",// ini pakai place2ID bukan placeID (dterima di DC/PUSAT, maka place2ID adalah cabang)
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-hpp",// hutang ke pusat
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
                ),
                "detail" => array(
                    // stok supplies bertambah di dc/pusat
                    100 => array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "biaya_dasar_nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // stok supplies berkurang di cabang, guang project pelaksana
                    101 => array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",// persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "biaya_dasar_nama",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangWorkOrderTarget",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
                "sub_detail" => array(
//                    // stok supplies bertambah di dc/pusat
//                    100 => array(
//                        "comName" => "RekeningPembantuSupplies",
//                        "loop" => array(
//                            "1010030010" => "sub_hpp",// persediaan supplies
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "biaya_dasar_nama",
//                            "produk_qty" => "jml",
//                            "produk_nilai" => "hpp",
////                            "gudang_id" => "gudang2ID",
//                            "gudang_id" => "gudangProjectID",
//                        ),
//                        "srcGateName" => "items2",
//                        "srcRawGateName" => "items2",
//                    ),
//                    // stok supplies berkurang di cabang, guang project pelaksana
//                    101 => array(
//                        "comName" => "RekeningPembantuSupplies",
//                        "loop" => array(
//                            "1010030010" => "-sub_hpp",// persediaan supplies
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => "id",
//                            "extern_nama" => "biaya_dasar_nama",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangWorkOrderTarget",
//                        ),
//                        "srcGateName" => "items2",
//                        "srcRawGateName" => "items2",
//                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "9856" => array(
                "master" => array(),
                "detail" => array(
                    // average
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(
                            "hpp" => "hpp",//sengaja dikasih nilai biar kalau yang dikembalikan 0 supaya tidak dibuatkan outparamnya
                        ),
                        "static" => array(
                            // ini cabang_id dan gudang_id milik DC/PUSAT
                            // cabang_id = -1, gudang_id = 9 (gudang project DC)
                            // cabang_id = -1, karena tidak ada gerbang nilai cabang_id -1.
//
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangProjectID",
                            "cabang_id" => ".-1",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => ".supplies",
                            "jml" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "biaya_dasar_nama",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
                "sub_detail" => array(
                    // BAGIAN CABANG
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".return",
                            "jumlah" => "-jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "biaya_id" => "biaya_id",
                            "oleh2_nama" => "pihakProjekNoSpk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "state" => ".returned",
                            "jumlah" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "pihakProjekNoSpk",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangWorkOrderTarget",
                            "biaya_id" => "biaya_id",
                            "oleh2_nama" => "pihakProjekNoSpk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),

                    // BAGIAN DC/PUSAT
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            // ini cabang_id dan gudang_id milik DC/PUSAT
                            "cabang_id" => ".-1",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "nama" => "biaya_dasar_nama",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
//                            "biaya_id" => "biaya_id",
                        ),
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),

                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            // ini cabang_id dan gudang_id milik DC/PUSAT
                            "cabang_id" => ".-1",
                            "gudang_id" => "gudangProjectID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),

                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(
                            "hpp" => "hpp",//sengaja dikasih nilai biar kalau yang dikembalikan 0 supaya tidak dibuatkan outparamnya
                        ),
                        "static" => array(
                            // ini cabang_id dan gudang_id milik DC/PUSAT
                            // cabang_id = -1, gudang_id = 9 (gudang project DC)
                            // cabang_id = -1, karena tidak ada gerbang nilai cabang_id -1.

//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangProjectID",
                            "cabang_id" => ".-1",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => ".supplies",
                            "jml" => "jml",
                            "produk_id" => "biaya_dasar_id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "biaya_dasar_nama",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2",
                        "srcRawGateName" => "items2",
                    ),

                    //----memcatat produk yang dikembalikan...
                    array(
                        "comName" => "LockerStockWorkOrder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                            "jenis" => ".produk",
                            "state" => ".dikembalikan",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "project_id" => "pihakProjekID",
                            "work_order_id" => "pihakProjekWorkOrderID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),

);