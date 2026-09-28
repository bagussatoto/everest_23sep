<?php

/**
 * Created by PhpStorm.
 * User: chepy
 * Date: 10/23/2021
 * Time: 13:16 PM
 */

$config["coTransaksiValues"] = array(

    //config pembelian aset
    "421" => array(
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
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__nama",
                "cabang2ID" => "cabang2",
                "cabang2Name" => "cabang2__nama",
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",
                "ppn_val" => "",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                "harga_other" => "harga_disc+other",
                "ppn" => "(ppn_persen_dipakai*harga_disc)/100",
                //                "ppn" => "(ppnPersen*harga_disc)/100",
                "hpp_nppn" => "harga_disc+ppn+other",
                "nett" => "hpp_nppn",
                "srcAccount" => "nama",
                "harga_dipakai" => "hpp_nppn-ppn",
            ),
            "master_dependent" => array(
                "pihakMainRulesID" => array(
                    "pm" => array(
                        "nilai_ppn" => "ppn",
                        "nilai_persediaan" => "harga_other",
                        "dpp_vat" => "harga_disc",
                        //                        "ppnFactor" =>"10",
                    ),
                    "non_pm" => array(
                        "nilai_ppn" => ".0",
                        "nilai_persediaan" => "nett",
                        "dpp_vat" => ".0",
                        //                        "ppnFactor" =>"0",
                    ),


                ),
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
                "grand_total" => "hpp_nppn",
                "other" => "other",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

                "cabang2_id" => "cabang2ID",
                "cabang2_nama" => "cabang2Name",
                "place2_id" => "place2ID",
                "place2_nama" => "place2Name",
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
                //                "srcAccount" =>"name",
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
        "postProcessor" => array(
            "421" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "PriceAktivaTetap",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".aktiva",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "423" => array(
                "master" => array(
                    array(
                        "comName" => "Signature",
                        "loop" => array(
                            "transaksi_id" => "references",
                        ),
                        "static" => array(

                            "nomer" => "nomer",
                            "step_number" => ".3",
                            "step_code" => ".761ro",
                            "step_name" => ".request process",
                            "group_code" => ".sys",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "keterangan" => ".autostep by other transaction",
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

                ),
                "detail" => array(
                    //                    array(
                    //                        "comName" => "LockerValueItem",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "cabang_id" => "placeID",
                    //                            "gudang_id" => "gudangID",
                    //                            "state" => ".active",
                    //                            "jenis" => ".aktiva",
                    //                            "produk_id" => "id",
                    //                            "nama" => "nama",
                    //                            "nilai" => "harga_dipakai",
                    //                            "transaksi_id" => "currentID",
                    //                            "oleh_id" => ".0",
                    //                        ),
                    //                        "srcGateName" => "items2_sum",
                    //                        "srcRawGateName" => "items2_sum",
                    //                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga_dipakai",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "PriceAktivaTetap",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "note" => "note",
                            "nilai" => "harga_dipakai",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".aktiva",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".aktiva",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "note" => "note",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiAktiva",
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
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                ),
            ),
            "111" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang aktiva tetap",
                            //                            "target_jenis" => "jenisTr",
                            "jenis" => ".423",
                            "transaksi_id" => "currentID",
                            "ppn_approved" => "ppn_realisasi",
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
                            "label" => ".hutang aktiva tetap",
                            //                            "target_jenis" => ".489",
                            "jenis" => ".423",
                            "transaksi_id" => "currentID",
                            "terbayar" => "selisih_ppn_realisasi",
                            "dihapus" => "selisih_ppn_realisasi",
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
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
        ),
    ),

    "422" => array(
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
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__nama",
                "cabang2ID" => "cabang2",
                "cabang2Name" => "cabang2__nama",
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",
                "ppn_val" => "",

            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                "ppn" => "(ppn_persen_dipakai*harga_disc)/100",
//                "ppn" => "(ppnPersen*harga_disc)/100",
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "srcAccount" => "nama",
                "harga_dipakai" => "hpp_nppn-ppn",
            ),
            "master_dependent" => array(
                "pihakMainRulesID" => array(
                    "pm" => array(
                        "nilai_ppn" => "ppn",
                        "nilai_persediaan" => "harga_disc",
//                        "ppnFactor" =>"10",
                    ),
                    "non_pm" => array(
                        "nilai_ppn" => "0",
                        "nilai_persediaan" => "nett",
//                        "ppnFactor" =>"0",
                    ),


                ),
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
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",

                "cabang2_id" => "cabang2ID",
                "cabang2_nama" => "cabang2Name",
                "place2_id" => "place2ID",
                "place2_nama" => "place2Name",
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
//                "srcAccount" =>"name",
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

        "postProcessor" => array(
            "422" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga_dipakai",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "PriceAktivaTetap",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "note" => "note",
                            "nilai" => "harga_dipakai",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".aktiva",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                ),
            ),
        ),
    ),



    //config aset distribution
    "2483" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID|cabang2ID",
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
//                "dtime" => "dtime",
//                "id" => "id",
//                "code" => "code",
//                "label" => "label",
//                "name" => "nama",
//                "qty" => "jml",
//                "satuan" => "satuan",
//                "note" => "note",


            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian
            ),
        ),
        "valueBuilders" => array(),
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

        "postProcessor" => array(
            "2483r" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
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
                ),
                "detail" => array(
                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".aktiva",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "transaksi_id" => ".0",
                            "nomer" => "",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".aktiva",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //region locker value
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga",
                            "nomer" => "nomer",
//                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //endregion
                ),
            ),
            "2483" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(
                            "activity" => ".1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
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


                    //<editor-fold desc="Post-locker stock">

//                    array(
//                        "comName" => "LockerStockAktiva",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".aktiva",
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
//                    array(
//                        "comName" => "LockerStockAktiva",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".aktiva",
//                            "state" => ".hold",
//                            "jumlah" => "qty",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "oleh_id" => ".0",
//                            "oleh_nama" => "",
//                            "transaksi_id" => "masterID",
//                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    //</editor-fold>
                ),
            ),
        ),
    ),

    "2485" => array(
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
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__nama",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
//                "ppn" => "(ppnFactor*harga)/100",
                "ppn" => "(ppnPersen*harga_disc)/100",
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "srcAccount" => "nama",
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
//                "srcAccount" =>"name",
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

        "postProcessor" => array(
            "2485r" => array(
                "master" => array(
                    array(
                        "comName" => "Signature",
                        "loop" => array(
                            "transaksi_id" => "references",
                        ),
                        "static" => array(

                            "nomer" => "nomer",
                            "step_number" => ".3",
                            "step_code" => ".761ro",
                            "step_name" => ".request process",
                            "group_code" => ".sys",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "keterangan" => ".autostep by other transaction",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //kurangi locker value untuk auto distribusi ke cabang

                ),
                "detail" => array(
//                    array(
//                        "comName" => "LockerValueItem",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".active",
//                            "jenis" => ".aktiva",
//                            "produk_id" => "id",
//                            "nama" => "nama",
//                            "nilai" => "-harga",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    array(
//                        "comName" => "LockerValueItem",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".hold",
//                            "jenis" => ".aktiva",
//                            "produk_id" => "id",
//                            "nama" => "nama",
//                            "nilai" => "harga",
////                            "transaksi_id" => "transaksi_id",
//                            "transaksi_id" => "currentID",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
            "2485" => array(
                "master" => array(
                    array(
                        "comName" => "Signature",
                        "loop" => array(
                            "transaksi_id" => "references",
                        ),
                        "static" => array(

                            "nomer" => "nomer",
                            "step_number" => ".3",
                            "step_code" => ".761ro",
                            "step_name" => ".request process",
                            "group_code" => ".sys",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "keterangan" => ".autostep by other transaction",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
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
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
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
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "cabang2ID",
                            "state" => ".hold",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga",
//                            "transaksi_id" => "transaksi_id",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga",
//                            "transaksi_id" => "transaksi_id",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PriceAktivaTetap",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "note" => "note",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".aktiva",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "jenis" => ".aktiva",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "jenis" => ".aktiva",
                            "state" => ".sold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "jenis" => ".aktiva",
                            "state" => ".active",
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
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
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

                ),
            ),
        ),
    ),

    //config depresiasi (PUSAT)
    "8786" => array(
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
                "produk_jenis" => "aktiva",
            ),
        ),

        "postProcessor" => array(
            "8786r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".depresiasi",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "8786" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
//                            "state" => ".active",
//                            "jenis" => ".aktiva",
                            "state" => ".hold",
                            "jenis" => ".depresiasi",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga",
//                            "transaksi_id" => ".0",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".sold",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
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

    //config depresiasi
    "8787" => array(
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
                "produk_jenis" => "aktiva",
            ),
        ),

        "postProcessor" => array(
            "8787r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".depresiasi",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "8787" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
//                            "state" => ".active",
//                            "jenis" => ".aktiva",
                            "state" => ".hold",
                            "jenis" => ".depresiasi",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga",
//                            "transaksi_id" => ".0",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".sold",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //------------

                ),
            ),
        ),
    ),

    //config depresiasi sewa
    "8788" => array(
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
                "produk_jenis" => "sewa",
            ),
        ),

        "postProcessor" => array(
            "8788" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".sewa",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".sold",
                            "jenis" => ".sewa",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga",
                            // "transaksi_id" => "transaksi_id",
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

    //config jual asset
    "8789" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID|olehID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "nett1" => "(harga-disc)",
                "nett2" => "(nett1+ongkir)",
                "ppn" => "nett1*(10/100)",
                "subtotal" => "jml*nett1",
                "harga_depre" => "harga_perolehan-harga_sisa",
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
            ),
            "master_dependent" => array(
                "pihakMainRulesID" => array(
                    "pk" => array(
                        "nilai_ppn" => "new_net2*(10/100)",
                        "nilai_persediaan" => "harga_disc",
                        "ppn_faktor" => ".10",
                    ),
                    "non_pk" => array(
                        "nilai_ppn" => ".0",
                        "nilai_persediaan" => "nett",
                        "ppn_faktor" => ".0",
                    ),
                ),
                "txt_rugilaba" => array(
                    "keuntungan" => array(
                        "rugiLabaLainRekName" => ".laba lain lain",
                        "rugiLabaLain" => "nilai_final_rugilaba",
                    ),
                    "kerugian" => array(
                        "rugiLabaLainRekName" => ".rugi lain lain",
                        "rugiLabaLain" => "nilai_final_rugilaba",
                    ),
                ),
                "pihakMainName" => array(
                    "kendaraan" => array(
                        "pihakMainRekName" => ".akum penyu kendaraan",
                    ),
                    "peralatan kantor" => array(
                        "pihakMainRekName" => ".akum penyu peralatan kantor",
                    ),
                    "mesin produksi" => array(
                        "pihakMainRekName" => ".akum penyu mesin produksi",
                    ),
                    "peralatan produksi" => array(
                        "pihakMainRekName" => ".akum penyu peralatan produksi",
                    ),
                    "tanah dan bangunan" => array(
                        "pihakMainRekName" => ".akum penyu tanah dan bangunan",
                    ),
                ),
                "paymentMethod" => array(
                    "cia_tunai" => array(
                        "nilai_cash" => ".0",
                        "nilai_credit" => ".0",
                    ),
                ),
                "shippingService" => array(
                    "ongkir_ppn_by_cust" => array(
                        "ongkir_ui" => "shipping_service",
                        "ongkir" => "shipping_service",
                        "ongkir_ppn" => "shipsvc_ppn_value",
                        "ongkir_net" => "shipping_service",
                        "srcOngkir" => ".0",
                    ),
                    "ongkir_tanpa_ppn_by_cust" => array(
                        "ongkir_ui" => "shipping_service",
                        "ongkir" => ".0",
                        "ongkir_ppn" => ".0",
                        "ongkir_net" => ".0",
                        "srcOngkir" => "shipping_service",
                    ),
                    "ongkir_tanpa_ppn_by_company" => array(
                        "ongkir_ui" => ".0",
                        "ongkir" => ".0",
                        "ongkir_ppn" => ".0",
                        "ongkir_net" => ".0",
                        "srcOngkir" => ".0",
                    ),
                    "tanpa_ongkir" => array(
                        "ongkir_ui" => ".0",
                        "ongkir" => ".0",
                        "ongkir_ppn" => ".0",
                        "ongkir_net" => ".0",
                    ),
                ),
            ),
        ),
        "staticAccountComRekening" => array(
            "kendaraan" => "RekeningPembantuAkumPenyusutanKendaraan",
            "peralatan kantor" => "RekeningPembantuAkumPenyusutanPeralatanKantor",
            "mesin produksi" => "RekeningPembantuAkumPenyusutanMesinProduksi",
            "peralatan produksi" => "RekeningPembantuAkumPenyusutanPeralatanProduksi",
            "tanah dan bangunan" => "RekeningPembantuAkumPenyusutanBangunan",
        ),
        "valueBuilders" => array(
            "shipsvc_ppn_value" => "(shipping_service*ppn_faktor/100)",
            "dp_value" => "(dp*100)/(100+ppn_faktor)",
            "dp_ppn_value" => "dp_value*(ppn_faktor/100)",
            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
            "grand_total" => "nett1+install_tax+install+ongkir",
            "grand_ppn" => "ongkir_ppn+ppn",
            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
            "new_net1" => "nett1+ongkir",
            "new_net2" => "nett2+ongkir",
            "new_net3" => "new_net2+ongkir_ppn",
            "tagihan" => "new_net1+grand_ppn-dp-nilai_cia",
            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+nilai_ppn-dp-nilai_cia",
            "grand_net" => "new_net3-nilai_dipakai_ppn_out",
//            "harga_depre"             => "harga_perolehan-harga_sisa",
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
                "transaksi_nilai" => "new_net2",
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
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
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
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),

        "postProcessor" => array(
            "8789r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga_sisa",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nomer" => "nomer",
                            "nilai" => "harga_sisa",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".aktiva",
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
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".aktiva",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "8789" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".downpayment",
                            "produk_id" => "transaksi_id",
                            "nama" => "nomer",
                            "nilai" => "dp_value",
//                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
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
                            "nilai" => "tagihan_ui",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga_sisa",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".sold",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "harga_sisa",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "jenis" => ".aktiva",
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
                        "comName" => "LockerStockAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "jenis" => ".aktiva",
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
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiAktiva",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
    ),
    //config sewa
    "424" => array(
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
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__nama",
                "cabang2ID" => "cabang2",
                "cabang2Name" => "cabang2__nama",
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",

                "ppn_val" => "",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "pph_value" => "(harga*tarif_pph)/100",
                "harga_disc" => "harga-pph_value",
                "ppn" => "(ppn_persen_dipakai*harga)/100",
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "srcAccount" => "nama",
                "harga_dipakai" => "hpp_nppn-ppn",

                "source_ppn_persen" => "(ppn/extern_nilai2)*100",
                //                "pph_value" => "pph23_nilai",

                "dtime" => "dtime",
                "id" => "id",
                "code" => "code",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",

                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "volume_gross",

                "hpp" => "hpp",
                "harga" => "harga",

                "pihakID" => "pihakID",
                "pihakName" => "pihakName",
                "cabangID" => "placeID",
                "cabangName" => "placeName",
                "olehID" => "olehID",
                "olehName" => "olehName",
                "pihakMainId" => "id",
                "pihakMainName" => "srcAccount",
            ),
            "master_dependent" => array(
                "pihakMainRulesID" => array(
                    "pm" => array(
                        "nilai_ppn" => "ppn",
                        "nilai_persediaan" => "harga_disc",
                    ),
                    "non_pm" => array(
                        "nilai_ppn" => "0",
                        "nilai_persediaan" => "nett",
                    ),
                ),
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
                "pihakMainID" => array(
                    "pph4_2" => array(
                        "extern_target_jenis" => ".1120",
                    ),
                    "pph23" => array(
                        "extern_target_jenis" => ".115",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
            "nett1_bulat" => "harga",
            "ppn_out_bulat" => "ppn",
            "pph_key_val" => "100-tarif_pph",

            "totalCredit" => "creditAmount+creditValue",

            "harus_bayar_orig" => "extern_nilai2-non_pph",

            "ppn_key" => "source_ppn_persen+100",
            "source_dpp" => "(nilai_entry*100)/ppn_key",

            "valid_dpp" => "source_dpp-non_pph",
            "pph23_nilai" => "(pph23Method__tarif/100)*valid_dpp",
            "nilai_bayar" => "nilai_entry+totalCredit",
            "valid_ppn" => "source_dpp*source_ppn_persen/100",


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
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
                "cabang2_id" => "cabang2ID",
                "cabang2_nama" => "cabang2Name",
                "place2_id" => "place2ID",
                "place2_nama" => "place2Name",
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
                "produk_jenis" => "account",
            ),
        ),

        "postProcessor" => array(
            "425" => array(
                "master" => array(
                    array(
                        "comName" => "Signature",
                        "loop" => array(
                            "transaksi_id" => "references",
                        ),
                        "static" => array(
                            "nomer" => "nomer",
                            "step_number" => ".3",
                            "step_code" => ".424",
                            "step_name" => ".goods receive note",
                            "group_code" => ".sys",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "keterangan" => ".autostep by other transaction",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => "pphGate",
                            "jenis" => "jenisTr",
                            "target_jenis" => "extern_target_jenis",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "0",
                            "tagihan" => "pph_value",
                            "sisa" => "pph_value",
                            "nomer" => "nomer",
                            "reference_jenis" => "jenisTr",
                            "extern_nilai2" => "harga",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "ppn_pph_faktor" => "tarif_pph",
                            //                            "extern2_nama" =>"pihakMainName",
                            //                            "extern_jenis" =>"pphGate",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(

                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "branchTarget__placeID",
                            "gudang_id" => "branchTarget__gudangID",
                            "state" => ".active",
                            "jenis" => ".sewa",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "note" => "note",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "SetupDepresiasi",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "cabang_id" => "placeID",
                            "rekening_main" => "externMain__label",
                            "rekening_details" => "dtaDetail",
                            //                            "dtime_perolehan" => date("Y-m-d"),
                            "dtime_start" => "sewaDtime_start",
                            "economic_life_time" => "sewaPeriode",
                            "residual_value" => ".0",
                            "repeat" => ".10",
                            "cabang_id" => "branchTarget",
                            "gudang_id" => "branchTarget__gudangID",
                            "jenis" => ".sewa",
                            "harga_perolehan" => "harga",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //                    array(
                    //                        "comName" => "PriceAktivaTetap",
                    //                        "loop" => array(),
                    //                        "static" => array(
                    //                            "produk_id" => "id",
                    //                            "produk_nama" => "name",
                    //                            "nilai" => "harga_dipakai",
                    //                            "cabang_id" => "placeID",
                    //                            "oleh_id" => "olehID",
                    //                            "oleh_nama" => "olehName",
                    //                            "note" => "note",
                    //                            "jenis" => ".sewa",
                    //                            "jenis_value" => ".hpp",
                    //                        ),
                    //                        "srcGateName" => "items2_sum",
                    //                        "srcRawGateName" => "items2_sum",
                    //                    ),

                ),
            ),
        ),
    ),

);