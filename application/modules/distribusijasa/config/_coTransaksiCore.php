<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(

    // config po jasa projek
    "3461" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "supplierID" => "pihakID",
//                "supplierName" => "pihakName",
//                "place2ID" => "branch",
//                "place2Name" => "branch__label",
                "customerID" => "customerProjek",
                "customerName" => "customerProjek__label",
//                "transaksi_id_target" => "transaksiData",
//                "transaksi_nomer_target" => "transaksiData__label",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "dppPPn" => "harga_disc*(dpp_persen/100)",
//                "dppPPh" => "harga_disc*pph",
                "ppn_persen" => ".10",
//                "ppn" => "(ppn_persen/100)*dppPPn",
//                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "subtotal" => "hpp_nppn",
                "max_dpp_persen" => ".100",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                //--------
                "ppn_persen" => ".10",
                "dppPPn" => "harga_disc*(dpp_ppn_persen/100)",
                "ppn" => "(ppn_persen/100)*dppPPn",
                //--------
                "dppPPh" => "harga_disc*(dpp_pph_persen/100)",
//                "pph" => "(pph_persen/100)*dppPPn",
                //--------
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "max_dpp_persen" => ".100",
                "subtotal" => "jml*hpp_nppn",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
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
        "valueSubDetail" => true,// build value dari detail2 dan direcap ke items
        "valueSubDetailRecap" => array(
            "harga",
            "harga_disc",
            "dppPPn",
            "dppPPh",
            "hpp_nppn",
            "nett",
            "pph_nilai",
            "ppn",
//            "subtotal",
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
            "ppn_value" => "nilai_dpp_ppn*10/100",
            "payment_out" => "nett",
            "dppPph_dipakai" => "valid_pph_key*dppPPh",

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
                "customers_id" => "customerID",
                "customers_nama" => "customerName",

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
//            "3461" => array(
//                "master" => array(
//                    //region PUSAT, PINDAH PROJEK COST
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "projek cost" => "-harga_disc",
//                            "piutang cabang" => "harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "projek cost" => "-harga_disc",
//                            "piutang cabang" => "harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "piutang cabang" => "harga_disc",
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
//                    array(
//                        "comName" => "RekeningPembantuProjek",
//                        "loop" => array(
//                            "projek cost" => "-harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "extern_id" => "customerID",// konsumen
//                            "extern_nama" => "customerName",// konsumen
////                            "extern2_id" => "transaksi_id_target",// so
////                            "extern2_nama" => "transaksi_nomer_target",// so
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //endregion PUSAT, PINDAH PROJEK COST
//
//                    //region CABANG, TERIMA PROJEK COST
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "projek cost" => "harga_disc",
//                            "hutang ke pusat" => "harga_disc",
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
//                            "projek cost" => "harga_disc",
//                            "hutang ke pusat" => "harga_disc",
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
//                        "comName" => "RekeningPembantuAntarcabang",
//                        "loop" => array(
//                            "hutang ke pusat" => "harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "extern_id" => "place2ID",
//                            "extern_nama" => "place2Name",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuProjek",
//                        "loop" => array(
//                            "projek cost" => "harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "customerID",// konsumen
//                            "extern_nama" => "customerName",// konsumen
////                            "extern2_id" => "transaksi_id_target",// so
////                            "extern2_nama" => "transaksi_nomer_target",// so
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //endregion
//
//                    //region CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "projek cost" => "-harga_disc",
//                            "hpp projek" => "harga_disc",
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
//                            "projek cost" => "-harga_disc",
//                            "hpp projek" => "harga_disc",
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
//                        "comName" => "RekeningPembantuProjek",
//                        "loop" => array(
//                            "projek cost" => "-harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "customerID",// konsumen
//                            "extern_nama" => "customerName",// konsumen
////                            "extern2_id" => "transaksi_id_target",// so
////                            "extern2_nama" => "transaksi_nomer_target",// so
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuHpp",
//                        "loop" => array(
//                            "hpp projek" => "harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "customerID",// customer projek
//                            "extern_nama" => "customerName",// customer projek
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //endregion CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
//                ),
//                "detail" => array(),
//            ),
        ),
        "postProcessor" => array(
//            "3461" => array(
//                "master" => array(
//                    // masuk transaksi_data_items, nomer ponya
//
//                    array(
//                        "comName" => "TransaksiItems3_sum",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "insertID",// transaksi id SRN
//                            "produk_nama" => "nomer",// nomer transaksi SRN
//                            "produk_jenis" => ".po_projek",// nomer transaksi SRN
//                            "produk_ord_jml" => ".1",
//                            "produk_ord_hrg" => "harga",//
//                            "produk_ord_diskon" => "disc",
//                            "valid_qty" => ".1",
//                            "satuan" => ".0",
//                            "transaksi_id" => "produkProjek__transaksi_id_app",
//                            "jenisTr" => "jenisTr",
//                            "referenceID" => "referenceID",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "TransaksiDataRegistriUpdate",
//                        "loop" => array(),
//                        "static" => array(
//                            "transaksi_id" => "produkProjek__transaksi_id_app",// transaksi id dari so yang sudah diapprove
//                            "jenisTrMaster" => "jenisTrMaster",
//                            "insertID" => "insertID",
//                            "jenisTr" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // locker projek
//
//                    array(
//                        "comName" => "LockerStockProjekMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".po_projek",
//                            "state" => ".active",
//                            "jumlah" => ".1",
//                            "produk_id" => "insertID",// transaksi id SRN
//                            "nama" => "nomer",// nomer transaksi SRN
//                            "satuan" => "satuan",
//                            "transaksi_id" => "produkProjek__transaksi_id_app",// id SO
//                            "nomer" => "produkProjek__transaksi_no_app",// nomer SO
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                ),
//                "detail" => array(
//
//                ),
//            ),
        ),
    ),
    "3465" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
//                "supplierID" => "pihakID",
//                "supplierName" => "pihakName",
//                "place2ID" => "branch",
//                "place2Name" => "branch__label",
                "customerID" => "customerProjek",
                "customerName" => "customerProjek__label",
//                "transaksi_id_target" => "transaksiData",
//                "transaksi_nomer_target" => "transaksiData__label",
                "projectID" => "produkProjek",
                "projectName" => "produkProjek__label",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "dppPPn" => "harga_disc*(dpp_persen/100)",
//                "dppPPh" => "harga_disc*pph",
                "ppn_persen" => ".10",
//                "ppn" => "(ppn_persen/100)*dppPPn",
//                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "subtotal" => "hpp_nppn",
                "max_dpp_persen" => ".100",
            ),
            "detail2" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                //--------
                "ppn_persen" => ".10",
                "dppPPn" => "harga_disc*(dpp_ppn_persen/100)",
                "ppn" => "(ppn_persen/100)*dppPPn",
                //--------
                "dppPPh" => "harga_disc*(dpp_pph_persen/100)",
//                "pph" => "(pph_persen/100)*dppPPn",
                //--------
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "max_dpp_persen" => ".100",
                "subtotal" => "jml*hpp_nppn",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
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
        "valueSubDetail" => true,// build value dari detail2 dan direcap ke items
        "valueSubDetailRecap" => array(
            "harga",
            "harga_disc",
            "dppPPn",
            "dppPPh",
            "hpp_nppn",
            "nett",
            "pph_nilai",
            "ppn",
//            "subtotal",
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
            "ppn_value" => "nilai_dpp_ppn*10/100",
            "payment_out" => "nett",
            "dppPph_dipakai" => "valid_pph_key*dppPPh",

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
                "customers_id" => "customerID",
                "customers_nama" => "customerName",

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
            "3465" => array(
                "master" => array(
                    //region PUSAT, PINDAH PROJEK COST

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "projek cost" => "-harga_disc",
                            "piutang cabang" => "harga_disc",
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
                            "projek cost" => "-harga_disc",
                            "piutang cabang" => "harga_disc",
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
                            "piutang cabang" => "harga_disc",
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
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "projek cost" => "-harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "customerID",// konsumen
                            "extern_nama" => "customerName",// konsumen
//                            "extern2_id" => "transaksi_id_target",// so
//                            "extern2_nama" => "transaksi_nomer_target",// so
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //endregion PUSAT, PINDAH PROJEK COST

                    //region CABANG, TERIMA PROJEK COST

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "projek cost" => "harga_disc",
                            "hutang ke pusat" => "harga_disc",
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
                            "projek cost" => "harga_disc",
                            "hutang ke pusat" => "harga_disc",
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
                            "hutang ke pusat" => "harga_disc",
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
                    array(
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "projek cost" => "harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "customerID",// konsumen
                            "extern_nama" => "customerName",// konsumen
//                            "extern2_id" => "transaksi_id_target",// so
//                            "extern2_nama" => "transaksi_nomer_target",// so
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //endregion

                    // project cost digeser saat packinglist di cabang (hpp project pada porject cost) 2022-06-29
                    //region CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK

//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "projek cost" => "-harga_disc",
//                            "hpp projek" => "harga_disc",
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
//                            "projek cost" => "-harga_disc",
//                            "hpp projek" => "harga_disc",
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
//                        "comName" => "RekeningPembantuProjek",
//                        "loop" => array(
//                            "projek cost" => "-harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "customerID",// konsumen
//                            "extern_nama" => "customerName",// konsumen
////                            "extern2_id" => "transaksi_id_target",// so
////                            "extern2_nama" => "transaksi_nomer_target",// so
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuHppProject",
//                        "loop" => array(
//                            "hpp projek" => "harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "customerID",// customer projek
//                            "extern_nama" => "customerName",// customer projek
//                            "extern2_id" => "projectID",
//                            "extern2_nama" => "projectName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //endregion CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "3465" => array(
                "master" => array(
                    // masuk transaksi_data_items, nomer ponya

                    array(
                        "comName" => "TransaksiItems3_sum",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "referenceInsertID",// transaksi id SRN
                            "produk_nama" => "referenceNomer",// nomer transaksi SRN
                            "produk_jenis" => ".po_projek",// nomer transaksi SRN
                            "produk_ord_jml" => ".1",
                            "produk_ord_hrg" => "harga",//
                            "produk_ord_diskon" => "disc",
                            "valid_qty" => ".1",
                            "satuan" => ".0",
                            "transaksi_id" => "produkProjek__transaksi_id_app",
                            "jenisTr" => "jenisTr",
                            "referenceID" => "referenceID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "TransaksiDataRegistriUpdate",
                        "loop" => array(),
                        "static" => array(
                            "transaksi_id" => "produkProjek__transaksi_id_app",// transaksi id dari so yang sudah diapprove
                            "jenisTrMaster" => "jenisTrMaster",
                            "insertID" => "insertID",
                            "jenisTr" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // locker projek

                    array(
                        "comName" => "LockerStockProjekMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".po_projek",
                            "state" => ".active",
                            "jumlah" => ".1",
                            "produk_id" => "referenceInsertID",// transaksi id SRN
                            "nama" => "referenceNomer",// nomer transaksi SRN
                            "satuan" => "satuan",
                            "transaksi_id" => "produkProjek__transaksi_id_app",// id SO
                            "nomer" => "produkProjek__transaksi_no_app",// nomer SO
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
        ),
    ),


);