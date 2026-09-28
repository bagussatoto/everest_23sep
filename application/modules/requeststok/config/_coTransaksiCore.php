<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(
    //  config pr (request)
    "761"  => array(
        "counters"       => array("stepCode|placeID", "stepCode|olehID", "stepCode|placeID|olehID"),
        "formatNota"     => "stepCode|placeID",
        "valueGates"     => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "cabang2ID"   => "pihakID",
                "cabang2Name" => "pihakName",
                "place2ID"    => "pihakID",
                "place2Name"  => "pihakName",
                "gudang2ID"   => "gudang2",
                "gudang2Name" => "gudang2__label",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "name" => "nama",
                "qty"  => "jml",
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //                //"berat"         => "berat",
                //                //"lebar"         => "lebar",
                //                //"panjang"       => "panjang",
                //                //"tinggi"        => "tinggi",
                //                //"volume"        => "volume",
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "harga" => "harga",
                //                "hpp" => "harga",
                //                "ppn" => "ppn",
                //                "nett" => "harga+ppn",
                //
                //
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "supplierID" => "pihakID",
                //                "supplierName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
                //                "gudangID" => "gudangID",
                //                "gudangName" => "gudangName",
            ),
        ),
        "valueBuilders"  => array(),
        "preProcessor"   => array(),
        "tableIn"        => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top"    => "jenisTrTop",
                "jenis"        => "jenisTr",
                "jenis_label"  => "jenisTrName",
                "div_id"       => "divID",
                "div_nama"     => "divName",
                "dtime"        => "dtime",
                "fulldate"     => "fulldate",
                "oleh_id"      => "olehID",
                "oleh_nama"    => "olehName",

                "cabang2_id"   => "place2ID",
                "cabang2_nama" => "place2Name",

                "cabang_id"       => "placeID",
                "cabang_nama"     => "placeName",
                "transaksi_nilai" => "hpp",
                //                "transaksi_net" => "hpp",
                "transaksi_jenis" => "jenisTr",
                "keterangan"      => "description",

                "gudang_id"    => "gudangID",
                "gudang_nama"  => "gudangName",
                "gudang2_id"   => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),

            "detail"       => array(
                "dtime"          => "dtime",
                "produk_id"      => "id",
                "produk_kode"    => "code",
                "produk_label"   => "label",
                "produk_nama"    => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan"         => "satuan",
                "keterangan"     => "note",
            ),
            "detailValues" => array(
                "harga" => "harga",
                "ppn"   => "ppn",
                "nett"  => "nett",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash"        => 0,
                "produk_jenis" => "supplies",
            ),
        ),

        "components"    => array(),
        "postProcessor" => array(),

    ),
    //config request  supplies center
    "763"  => array(
        "counters"                => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota"              => "stepCode|placeID",
        "valueGates"              => array(//==sumber nilai yang dikirim kemana2
            "master"    => array(//==sumber nilai utama
                "pihakID"       => "cabang2ID",
                "pihakName"     => "cabang2Name",
                "gudang"        => "gudang2ID",
                "gudang__label" => "gudang2Name",
                "gudang__name"  => "gudang2Name",
                "gudang2Name"   => "gudang2Name",

            ),
            "detail"    => array(//===sumber nilai berupa rincian

            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders"           => array(),
        "valueBuilders_rsltItems" => array(),
        "preProcessor"            => array(
            "763" => array(
                "master" => array(),
                "detail" => array(),
            ),
        ),
        "tableIn"                 => array(
            "master"    => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top"    => "jenisTrTop",
                "jenis"        => "jenisTr",
                "jenis_label"  => "jenisTrName",
                "div_id"       => "divID",
                "div_nama"     => "divName",
                "dtime"        => "dtime",
                "fulldate"     => "fulldate",
                "oleh_id"      => "olehID",
                "oleh_nama"    => "olehName",

                "cabang_id"       => "placeID",
                "cabang_nama"     => "placeName",
                "transaksi_nilai" => "hpp",

                "transaksi_jenis" => "jenisTr",
                "keterangan"      => "description",

                "pihakID"     => "place2ID",
                "pihakName"   => "place2Name",
                "pihakName2"  => "place2Name",
                "cabang2ID"   => "place2ID",
                "cabang2Name" => "place2ID",
                "place2ID"    => "place2ID",
                "place2Name"  => "place2ID",

                "gudang"        => "gudangID",
                "gudang__label" => "gudang2Name",
                "gudang__name"  => "gudang2Name",
            ),
            "detail"    => array(
                "dtime"          => "dtime",
                "produk_id"      => "id",
                "produk_kode"    => "produk_kode",
                "produk_label"   => "label",
                "produk_nama"    => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp"            => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan"         => "satuan",
            ),
            "rsltItems" => array(
                "dtime"          => "dtime",
                "produk_id"      => "id",
                "produk_kode"    => "code",
                "produk_label"   => "label",
                "produk_nama"    => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "hpp",
                "hpp"            => "harga",
                //                "ppn" => "ppn",
                // "produk_ord_diskon",
                // "produk_hrg_ori",
                // "produk_hrg_gap",
                "satuan"         => "satuan",
            ),

        ),
        "tableIn_static"          => array(
            "master"    => array(
                "trash" => 0,
            ),
            "detail"    => array(
                "trash"        => 0,
                "produk_jenis" => "produk",
            ),
            "rsltItems" => array(
                "trash"        => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components"              => array(
            "763" => array(
                "master" => array(),
                "detail" => array(),
            ),
        ),
        "postProcessor"           => array(
            "763" => array(
                "master" => array(),
                "detail" => array(),
            ),
        ),
    ),
    "9763" => array(
        "counters"                => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|supplierID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
            "stepCode|olehID|supplierID",
        ),
        "formatNota"              => "stepCode|placeID",
        "valueGates"              => array(//==sumber nilai yang dikirim kemana2
            "master"    => array(//==sumber nilai utama
                "supplierID"   => "pihakID",
                "supplierName" => "pihakName",

            ),
            "detail"    => array(//===sumber nilai berupa rincian
                "ppn"           => "(ppnFactor*harga)/100",
                "hpp_nppn"      => "harga+ppn",
                "hpp_nppv"      => "harga*ppv_index__nilai",
                "ppv"           => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett"          => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders"           => array(
            "grand_total" => "harga+ppn",
            "tagihan"     => "grand_total-discount",
        ),
        "valueBuilders_rsltItems" => array(),
        "preProcessor"            => array(),
        "tableIn"                 => array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top"    => "jenisTrTop",
                "jenis"        => "jenisTr",
                "jenis_label"  => "jenisTrName",
                "div_id"       => "divID",
                "div_nama"     => "divName",
                "dtime"        => "dtime",
                "fulldate"     => "fulldate",
                "oleh_id"      => "olehID",
                "oleh_nama"    => "olehName",

                "suppliers_id"   => "supplierID",
                "suppliers_nama" => "supplierName",

                "cabang_id"       => "placeID",
                "cabang_nama"     => "placeName",
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan"      => "description",
                "referensi_id"    => "referenceID",

                "pembayaran"  => "paymentMethod",
                "gudang_id"   => "gudangID",
                "gudang_nama" => "gudangName",
            ),
            "detail" => array(
                "dtime"          => "dtime",
                "produk_id"      => "id",
                "produk_kode"    => "produk_kode",
                "produk_label"   => "label",
                "produk_nama"    => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan"         => "satuan",
            ),
        ),
        "tableIn_static"          => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash"        => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components"              => array(),
        "postProcessor"           => array(
            "9763" => array(
                "master" => array(
                    array(
                        "comName"        => "Jurnal_activity",
                        "loop"           => array(//                            "activity" => ".1",
                        ),
                        "static"         => array(
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
                            //                            "step_number" => ".1",
                        ),
                        "srcGateName"    => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),

        ),


    ),
);