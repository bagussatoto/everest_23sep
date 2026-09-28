<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

$config["coTransaksiCore"] = array(
    //  config distribusi dari dc ke cabang project
    "5833" => array(
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
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
                "gudang2ID" => "pihakProjekWorkorderSubGudangID",
                "gudang2Name" => "pihakProjekWorkorderSubGudangName",
            ),
            "detail" => array(),
            "detail_rsltItems" => array(),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "5833sc" => array(
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
                "produk_ord_jml" => "qty",
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
                "produk_ord_jml" => "qty",
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
            "5833r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "oleh2_nama" => "pihakProjekNoSpk",
//                            "oleh2_nama" => "no_spk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "pihakProjekNoSpk",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
//                            "oleh2_nama" => "no_spk",
                            "oleh2_nama" => "pihakProjekNoSpk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //----intransit+
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "jenis" => ".produk",
                            "state" => ".intransit",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "pihakProjekNoSpk",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
//                            "oleh2_nama" => "no_spk",
                            "oleh2_nama" => "pihakProjekNoSpk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //----
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "5833r" => "qty",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "5833" => "-qty",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "5833sc" => array(
                "master" => array(),
                "detail" => array(
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangProjectID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    // serial intransit dibawah sini
                    array(
                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangProjectID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
                            "transaksi_id" => "masterID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
            "5833" => array(
                "master" => array(),
                "detail" => array(
                    //----intransit-
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "jenis" => ".produk",
                            "state" => ".intransit",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "pihakProjekNoSpk",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
//                            "oleh2_nama" => "no_spk",
                            "oleh2_nama" => "pihakProjekNoSpk",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //----
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "5833" => "-qty",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "5855" => "qty",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
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
    "5855" => array(
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
            "5855" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "resultParams" => array(
                            "items" => array(
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

//                "gudang_id" => "gudangID",
//                "gudang_nama" => "gudangName",
                "gudang_id" => "gudangProjectID",
                "gudang_nama" => "gudangProjectName",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
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
                "produk_ord_jml" => "qty",
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
            "5855" => array(
                "master" => array(
                    //<editor-fold desc="komponen milik pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
                            "1010060010" => "hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "validate" => ".1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
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
                            "cabang2_id" => "pihakID",
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
                            "1010030030" => "hpp",// persediaan produk
                            "2040010" => "hpp",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "validate" => ".1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
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
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial intransit
                    array(
                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudangProjectID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "transaksi_id" => "masterID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),


                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                ),
            ),
        ),
        "postProcessor" => array(
            "5855" => array(
                "master" => array(),
                "detail" => array(

                    //<editor-fold desc="Postproc-locker milik pusat">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".distribute",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
//                            "gudang_id" => "gudang2ID",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Postproc-locker milik cabang">
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
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
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
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //</editor-fold>

                    //----
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "5855" => "-qty",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudangProjectID",
                            "gudang_nama" => "gudangProjectName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "5833r" => "qty",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudangProjectID",
                            "gudang_nama" => "gudangProjectName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //----memcatat produk yang diterima...
                    array(
                        "comName" => "LockerStockWorkOrder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenis" => ".produk",
                            "state" => ".diterima",
                            "jumlah" => "qty",
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

    // RETURN DISTRIBUSI PROJECT
    //  config distribusi dari dc ke cabang project
    "9833" => array(
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
//                "pihakID" => ".-1",
//                "pihakName" => ".DC/PUSAT",
                "cabang2ID" => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID" => "pihakID",
                "place2Name" => "pihakName",
//                "gudang2ID" => "pihakProjekGudangID",
//                "gudang2Name" => "pihakProjekGudangNama",
//                "gudangID" => "pihakProjekWorkorderGudangID",
//                "gudangName" => "pihakProjekWorkorderGudangName",
                "gudangID" => "pihakProjekWorkorderSubGudangID",
                "gudangName" => "pihakProjekWorkorderSubGudangName",
                "gudang2ID" => "gudangProjectID",// gudang project pusat/dc
                "gudang2Name" => "gudangProjectName",// gudang project pusat/dc
            ),
            "detail" => array(
//                "stok_awal" => "stok+jml"
                "jml_dikembalikan" => "jml",
                "qty_dikembalikan" => "qty",
                "jml_dipakai" => "stok_awal-jml",
                "qty_dipakai" => "jml_dipakai",
            ),
            "detail_rsltItems" => array(),
        ),
        "valueBuilders" => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor" => array(
            "9833sc" => array(
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
                "produk_ord_jml" => "qty",
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
                "produk_ord_jml" => "qty",
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
            "9833r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
//                            "gudang_id" => "gudangProjectID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
//                            "gudang_id" => "gudangProjectID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //----
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "9833r" => "qty",
                        ),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "9833" => "-qty",
                        ),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "9833sc" => array(
                "master" => array(),
                "detail" => array(
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    // serial intransit dibawah sini
                    array(
                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
                            "transaksi_id" => "masterID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
            "9833" => array(
                "master" => array(),
                "detail" => array(
                    //----
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "9833" => "-qty",
                        ),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "9855" => "qty",
                        ),
                        "static" => array(
                            "cabang_id" => "projectCabangID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
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
    "9855" => array(
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
            "9855" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudang2ID",
//                            "gudang_id" => "gudangProjectID",
                            "jenisTr" => "jenisTr",
                            "exception" => ".1",
                        ),
                        "resultParams" => array(
                            "items" => array(
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
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudang2ID",
////                            "gudang_id" => "gudangProjectID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                                "hpp_nppv" => "hpp_nppv",
//                                "produk_jenis" => "produk_jenis",
//                                "produk_jenis_id" => "produk_jenis_id",
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
                "produk_ord_jml" => "qty",
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
                "produk_ord_jml" => "qty",
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
            "9855" => array(
                "master" => array(
                    //<editor-fold desc="komponen milik pusat">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "1010060010" => "-hpp",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "validate" => ".1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "hpp",// persediaan produk
                            "1010060010" => "-hpp",// piutang cabang
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
                            "1010030030" => "-hpp",// persediaan produk
                            "2040010" => "-hpp",// hutang ke pusat

                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "validate" => ".1",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-hpp",// persediaan produk
                            "2040010" => "-hpp",// hutang ke pusat
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
                    //<editor-fold desc="subkomponen milik cabang">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
//                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // serial intransit dibawah sini
                    array(
                        "comName" => "RekeningPembantuProdukPerSerialIntransit",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "transaksi_id" => "masterID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="subkomponen milik pusat">
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangProjectID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                    //</editor-fold>
                ),
            ),
        ),
        "postProcessor" => array(
            "9855" => array(
                "master" => array(),
                "detail" => array(

                    //<editor-fold desc="Postproc-locker milik cabang">
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "transaksi_id" => "masterID",
                            "nomer" => "nomer",
                            "gudang_id" => "gudang2ID",
//                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".produk",
                            "state" => ".distribute",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
                            "gudang_id" => "gudang2ID",
//                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
//                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Postproc-locker milik pusat">
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
                            "oleh_id" => ".0",
                            "transaksi_id" => ".0",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "jml",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "name",
                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "ppn_in" => "ppn_in",
                            "ppn_in_nilai" => "sub_ppn_in",
                            "suppliers_id" => "suppliers_id",
                            "suppliers_nama" => "suppliers_nama",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "produk_jenis" => "produk_jenis",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "jml",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "hpp" => "hpp",
//                            "jml_nilai" => "sub_hpp",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
////                            "gudang_id" => "gudangID",
//                            "gudang_id" => "gudangProjectID",
//                            "ppn_in" => "ppn_in",
//                            "ppn_in_nilai" => "sub_ppn_in",
//                            "suppliers_id" => "suppliers_id",
//                            "suppliers_nama" => "suppliers_nama",
//                            "hpp_nppv" => "hpp_nppv",
//                            "jml_nilai_nppv" => "sub_hpp_nppv",
//                            "produk_jenis" => "produk_jenis",
//                            "produk_jenis_id" => "produk_jenis_id",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),

                    //</editor-fold>

                    //----
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "9855" => "-qty",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                            "gudang_nama" => "gudang2Name",
                            "cabang_nama" => "place2Name",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "9833r" => "qty",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "gudang2ID",
                            "gudang_nama" => "gudang2Name",
                            "cabang_nama" => "place2Name",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "extern2_id" => "pihakProjekWorkOrderSubID",
                            "extern2_nama" => "pihakProjekWorkOrderSubNama",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
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
                            "jumlah" => "qty",
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

