<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(

    // config po jasa projek
    "3463_ORI" => array(
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
                "place2ID" => "branch",
                "place2Name" => "branch__label",
                "customerID" => "customerProjek",
                "customerName" => "customerProjek__label",
//                "transaksi_id_target" => "transaksiData",
//                "transaksi_nomer_target" => "transaksiData__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
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

        "postProcessor" => array(
            "3463ro" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // penamnda sudah pre-po pada SO Projek, trID, nomer, olehID, olehnama
                    array(
                        "comName" => "TransaksiProjekUpdate",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => "produkProjek__transaksi_id_app", // transaksi id dari so projek

                            "nomer" => "nomer",// nomer po projek
                            "tr_id" => "transaksi_id",// nomer po projek
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "3463o" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PriceSupplies",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".jasa",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "3463" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".ppn in",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-nilai_dipakai_ppn_in",
                            "transaksi_id" => "currentID",
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
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".piutang pembelian",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-nilai_dipakai_piutang_pembelian",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".3",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".3",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // masuk transaksi_data_items, nomer ponya
                    array(
                        "comName" => "TransaksiItems3_sum",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "insertID",// transaksi id SRN
                            "produk_nama" => "nomer",// nomer transaksi SRN
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
                            "cabang_id" => "place2ID",
                            "jenis" => ".po_projek",
                            "state" => ".active",
                            "jumlah" => ".1",
                            "produk_id" => "insertID",// transaksi id SRN
                            "nama" => "nomer",// nomer transaksi SRN
                            "satuan" => "satuan",
                            "transaksi_id" => "produkProjek__transaksi_id_app",// id SO
                            "nomer" => "produkProjek__transaksi_no_app",// nomer SO
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
            "3113" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang dagang",
//                            "target_jenis" => "jenisTr",
                            "jenis" => ".3463",
                            "transaksi_id" => "currentID",
                            "ppn_approved" => "nilai_tambah_ppn_in",
//                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".4",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".4",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),

    "3463" => array(
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
                "place2ID" => "branch",
                "place2Name" => "branch__label",
                "customerID" => "customerProjek",
                "customerName" => "customerProjek__label",
//                "transaksi_id_target" => "transaksiData",
//                "transaksi_nomer_target" => "transaksiData__label",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
                "projectID" => "produkProjek",
                "projectName" => "produkProjek__label",
            ),
            "detail" => array(
                //===sumber nilai berupa rincian
//                "disc" => "(discPersen*harga)/100",
//                "harga_disc" => "harga-disc",
//                "dppPPn" => "harga_disc*(dpp_persen/100)",
//                "dppPPh" => "harga_disc*pph",
                "ppn_persen" => "ppnFactor",
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
                "ppn_persen" => "ppnFactor",
                "dppPPn" => "harga_disc*(dpp_ppn_persen/100)",
                "ppn" => "(ppn_persen/100)*dppPPn",
                //--------
                "dppPPh" => "harga_disc*(dpp_pph_persen/100)",
                "pph_nilai" => "(pph_persen/100)*dppPPh",
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
            "ppn_value" => "nilai_dpp_ppn*ppnFactor/100",
            "payment_out" => "nett",
            "dppPph_dipakai" => "valid_pph_key*dppPPh",

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
                "customers_id" => "customerID",
                "customers_nama" => "customerName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
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
        
        "postProcessor" => array(
            "3463ro" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".1",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // penamnda sudah pre-po pada SO Projek, trID, nomer, olehID, olehnama
                    array(
                        "comName" => "TransaksiProjekUpdate",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => "produkProjek__transaksi_id_app", // transaksi id dari so projek

                            "nomer" => "nomer",// nomer po projek
                            "tr_id" => "transaksi_id",// nomer po projek
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "3463o" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".2",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PriceSupplies",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".jasa",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "3463" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".ppn in",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-nilai_dipakai_ppn_in",
                            "transaksi_id" => "currentID",
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
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".piutang pembelian",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-nilai_dipakai_piutang_pembelian",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".3",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".3",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // masuk transaksi_data_items, nomer ponya

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

                    // locker projek

//                    array(
//                        "comName" => "LockerStockProjekMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "place2ID",
//                            "jenis" => ".po_projek",
//                            "state" => ".active",
//                            "jumlah" => ".1",
//                            "produk_id" => "insertID",// transaksi id SRN
//                            "nama" => "nomer",// nomer transaksi SRN
//                            "satuan" => "satuan",
//                            "transaksi_id" => "produkProjek__transaksi_id_app",// id SO
//                            "nomer" => "produkProjek__transaksi_no_app",// nomer SO
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudang2ID",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                ),
                "detail" => array(),
            ),
            "3113" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang dagang",
//                            "target_jenis" => "jenisTr",
                            "jenis" => ".3463",
                            "transaksi_id" => "currentID",
                            "ppn_approved" => "nilai_tambah_ppn_in",
//                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang dagang",
                            "jenis" => ".3463",
                            "transaksi_id" => "currentID",
                            "terbayar" => "selisih_ppn_realisasi",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".4",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal_activityMain",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => "jenisTr",
                            "jenis_master" => "jenisTrMaster",
                            "jenis_top" => "jenisTrTop",
                            "master_id" => "transaksi_id",
                            "step_number" => ".4",
//                            "step_number" => "step_number",
                            "nilai" => ".1",
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